<?php

namespace App\Http\Controllers\SIMRS;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\OrderRadiologi;
use App\Models\SIMRS\Bilingan;
use App\Models\SIMRS\BilinganTagihanPasien;
use App\Models\SIMRS\Departement;
use App\Models\SIMRS\KelasRawat;
use App\Models\SIMRS\Laboratorium\OrderLaboratorium;
use App\Models\SIMRS\OrderTindakanMedis;
use App\Models\SIMRS\Peralatan\OrderAlatMedis;
use App\Models\SIMRS\Registration;
use App\Models\SIMRS\TagihanPasien;
use App\Models\SIMRS\TindakanMedis;
use App\Models\SIMRS\TipeTransaksi;
use App\Models\TarifTindakanMedis;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class TagihanPasienController extends Controller
{
    public function index(Request $request)
    {
        $query = Bilingan::query();

        if ($request->filled('registration_date')) {
            $dates = explode(' - ', $request->registration_date);
            $start_date = date('Y-m-d 00:00:00', strtotime(trim($dates[0])));
            $end_date = date('Y-m-d 23:59:59', strtotime(trim($dates[1])));
            $query->whereBetween('created_at', [$start_date, $end_date]);
        } else {
            $query->whereDate('created_at', today());
        }

        if ($request->filled('medical_record_number')) {
            $query->whereHas('registration.patient', function ($q) use ($request) {
                $q->where('medical_record_number', $request->medical_record_number);
            });
        }

        if ($request->filled('name')) {
            $query->whereHas('registration.patient', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->name . '%');
            });
        }

        if ($request->filled('departement_id')) {
            $query->whereHas('registration', function ($q) use ($request) {
                $q->where('departement_id', $request->departement_id);
            });
        }

        // Format Date
        $registrationDate = old('registration_date') ?? request('registration_date');
        $startDate = $endDate = now()->format('Y-m-d');

        if ($registrationDate && strpos($registrationDate, ' - ') !== false) {
            [$startDate, $endDate] = explode(' - ', $registrationDate);
        }

        $tagihan_pasien = $query->get();
        $departements = Departement::all();

        return view('pages.simrs.keuangan.kasir.index', compact('tagihan_pasien', 'startDate', 'endDate', 'registrationDate', 'departements'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'tipe_tagihan' => 'required|string',
            'kelas_rawat_id' => 'required|integer',
            'dokter_id' => 'required|integer',
            'departement_id' => 'required|integer',
            'tindakan_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
            'nominal_awal' => 'required|numeric',
            'bilingan_id' => 'required|integer',
            'registration_id' => 'required|integer',
            'user_id' => 'required|integer',
        ]);
        $validatedData['nominal'] = $validatedData['nominal_awal'] * $validatedData['quantity'];

        // Tambahkan logika: Cek status Bilingan, jika sudah 'final' tolak penyimpanan
        $bilingan = Bilingan::find($validatedData['bilingan_id']);
        if (!$bilingan) {
            return response()->json([
                'success' => false,
                'message' => 'Bilingan tidak ditemukan.',
            ], 404);
        }
        if ($bilingan->status === 'final') {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menambah tagihan. Status bilingan sudah final.',
            ], 403);
        }

        $tindakan = TindakanMedis::where('id', $request->tindakan_id)->first();
        if ($validatedData['tipe_tagihan'] == "Biaya Tindakan Medis") {
            $validatedData['tagihan'] =  "[Tindakan Medis] " . $tindakan->nama_tindakan;
            $validatedData['type'] = "Tindakan Medis";
            $validatedData['tindakan_medis_id'] = $tindakan->id;
        }

        try {
            // Simpan data ke database
            $tagihanPasien = TagihanPasien::create($validatedData);

            // Simpan relasi bilingan-tagihan pasien
            BilinganTagihanPasien::create([
                'tagihan_pasien_id' => $tagihanPasien->id,
                'bilingan_id' => $bilingan->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan.',
                'data' => $tagihanPasien,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function detailTagihan($id)
    {
        $bilingan = Bilingan::with('registration')->findOrFail($id);
        $registrationId = $bilingan->registration->id;

        // =================================================================
        // PERBAIKAN UTAMA: Filter orders by the CURRENT registration ID
        // =================================================================
        $order_lab = OrderLaboratorium::where('registration_id', $registrationId)
            ->where('status_billed', 0)
            ->get();

        $order_rad = OrderRadiologi::where('registration_id', $registrationId)
            ->where('status_billed', 0)
            ->get();

        // Combine the counts for the badge
        $belum_ditagihkan = $order_lab->count() + $order_rad->count();

        // Data for dropdowns in modal (no change needed here)
        $kelasRawats = KelasRawat::all();
        $doctors = Employee::where('is_doctor', 1)->get();
        $departements = Departement::all();
        $tipe_transaksi = TipeTransaksi::all();

        $authorizedUsers = User::whereHas('otorisasi', function ($query) {
            $query->where('otorisasi_type', 'Cancel Payment');
        })
            ->where('is_active', 1)
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return view('pages.simrs.keuangan.kasir.detail', compact(
            'tipe_transaksi',
            'belum_ditagihkan',
            'bilingan',
            'kelasRawats',
            'doctors',
            'departements',
            'authorizedUsers'
        ));
    }

    public function destroyTagihan($id)
    {
        try {
            $tagihan = TagihanPasien::findOrFail($id);
            if ($tagihan->tindakan_medis_id) {
                // return dd($tagihan->tindakan_medis);
            }
            // return dd($tagihan->registration);
            $tagihan->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error deleting tagihan: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getData($id)
    {
        try {
            $bilingan = Bilingan::find($id);

            if (!$bilingan) {
                return response()->json(['data' => []]);
            }

            if (strtolower($bilingan->status) === 'final') {
                return response()->json(['data' => []]);
            }

            $query = TagihanPasien::where('bilingan_id', $id);

            return DataTables::of($query)
                ->addColumn('tanggal', fn($row) => $row->date)
                ->addColumn('detail_tagihan', fn($row) => $row->tagihan)
                ->addColumn('diskon_rp', function ($row) {
                    // Tampilkan nilai diskon langsung, tanpa dikalikan/dibagi,
                    // format sebagai angka Indonesia (contoh: 375000.0 → 375.000)
                    if ($row->diskon === null) {
                        return null;
                    }
                    return number_format($row->diskon, 0, ',', '.');
                })
                ->addColumn('jaminan_rp', function ($row) {
                    if ($row->jaminan === null) {
                        return null;
                    }
                    return number_format($row->jaminan, 0, ',', '.');
                })
                ->addColumn('del', function ($row) {
                    return '<button type="button" class="btn btn-danger btn-sm delete-btn" data-id="' . $row->id . '"><i class="fa fa-trash"></i></button>';
                })
                ->rawColumns(['del'])
                ->make(true);
        } catch (\Exception $e) {
            Log::error('Error fetching Tagihan Pasien data for DataTables: ' . $e->getMessage());
            return response()->json(['error' => 'Data tidak dapat diambil.'], 500);
        }
    }


    public function getNominalAwal($id)
    {
        try {
            $tagihan = TagihanPasien::findOrFail($id);
            return response()->json(['nominal_awal' => $tagihan->nominal_awal]);
        } catch (\Exception $e) {
            \Log::error('Error fetching nominal awal: ' . $e->getMessage());
            return response()->json(['error' => 'Data could not be retrieved.'], 500);
        }
    }

    public function updateTagihan(Request $request, $id)
    {
        $validatedData = $request->validate([
            'tanggal' => 'required',
            'detail_tagihan' => 'required',
            'quantity' => 'required',
            'nominal' => 'required',
            'tipe_diskon' => 'required',
            'disc' => 'required',
            'diskon_rp' => 'required',
            'jamin' => 'required',
            'jaminan_rp' => 'required',
            'wajib_bayar' => 'required',
        ]);

        try {
            $tagihan = TagihanPasien::findOrFail($id);

            if (is_null($tagihan->nominal_awal)) {
                $tagihan->nominal_awal = $tagihan->nominal;
            }

            $tagihan->update($validatedData);

            // Ambil semua tagihan pasien dengan registration_id dan bilingan_id yang sama
            $totalWajibBayar = TagihanPasien::where('registration_id', $tagihan->registration_id)
                ->where('bilingan_id', $tagihan->bilingan_id)
                ->sum('wajib_bayar');

            // Update kolom wajib_bayar di tabel Bilingan
            $bilingan = Bilingan::find($tagihan->bilingan_id);
            if ($bilingan) {
                $bilingan->wajib_bayar = $totalWajibBayar;
                $bilingan->save();
            }

            return response()->json(['success' => 'Data updated successfully.']);
        } catch (\Exception $e) {
            \Log::error('Error updating data: ' . $e->getMessage());
            return response()->json(['error' => 'Data could not be updated. Reason: ' . $e->getMessage()], 500);
        }
    }

    // public function updateDisc($id)
    // {
    //     try {
    //         $tipeDiskon = request()->input('tipe_diskon');
    //         $tagihan = TagihanPasien::findOrFail($id);

    //         // Simpan nominal awal jika belum ada
    //         if (is_null($tagihan->nominal_awal)) {
    //             $tagihan->nominal_awal = $tagihan->nominal;
    //             $tagihan->save();
    //         }

    //         $tindakan = $tagihan->tindakan_medis;
    //         $group_penjamin_id = $tagihan->registration->penjamin->group_penjamin_id;
    //         $kelas_id = $tagihan->registration->kelas_rawat_id;

    //         $tarif = $tindakan->tarifTindakanMedis($group_penjamin_id, $kelas_id);

    //         $disc = [
    //             'share_rs' => (int) str_replace('.', '', $tarif['share_rs']),
    //             'share_dr' => (int) str_replace('.', '', $tarif['share_dr']),
    //             'total'    => (int) str_replace('.', '', $tarif['total']),
    //         ];

    //         $diskon = 0;
    //         if ($tipeDiskon === 'Dokter') {
    //             $diskon = $disc['share_dr'] * $tagihan->quantity;
    //         } elseif ($tipeDiskon === 'Rumah Sakit') {
    //             $diskon = $disc['share_rs'] * $tagihan->quantity;
    //         } elseif ($tipeDiskon === 'All') {
    //             $diskon = $disc['total'] * $tagihan->quantity;
    //         }

    //         $totalNominal = $tagihan->nominal_awal * $tagihan->quantity;

    //         // Tambahan diskon dari input user (%)
    //         $diskonPersen = ($tagihan->disc ?? 0) / 100 * $totalNominal;

    //         // Tambahan jaminan dari input user (%)
    //         $jaminanPersen = ($tagihan->jamin ?? 0) / 100 * $totalNominal;

    //         // Total diskon dan jaminan
    //         $totalDiskon = $diskon + $diskonPersen;
    //         $totalJaminan = ($tagihan->jaminan_rp ?? 0) + $jaminanPersen;

    //         // Hitung wajib bayar akhir
    //         $wajibBayar = $totalNominal - $totalDiskon - $totalJaminan;
    //         $wajibBayar = max(0, $wajibBayar);

    //         // Update data tagihan
    //         $tagihan->update([
    //             'tipe_diskon' => $tipeDiskon,
    //             'diskon' => $diskon,
    //             'diskon_rp' => $diskon,
    //             'wajib_bayar' => $wajibBayar,
    //         ]);

    //         // Hitung ulang total wajib_bayar untuk Bilingan
    //         $totalWajibBayar = TagihanPasien::where('registration_id', $tagihan->registration_id)
    //             ->where('bilingan_id', $tagihan->bilingan_id)
    //             ->sum('wajib_bayar');

    //         // Update ke model Bilingan
    //         $bilingan = Bilingan::find($tagihan->bilingan_id);
    //         if ($bilingan) {
    //             $bilingan->wajib_bayar = $totalWajibBayar;
    //             $bilingan->save();
    //         }

    //         return response()->json([
    //             'success' => true,
    //             'diskon' => $diskon,
    //             'wajib_bayar' => $wajibBayar,
    //             'total_nominal' => $totalNominal
    //         ]);
    //     } catch (\Exception $e) {
    //         \Log::error('Error updating discount: ' . $e->getMessage());
    //         return response()->json([
    //             'success' => false,
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }
    public function updateDisc($id)
    {
        try {
            $tipeDiskon = request()->input('tipe_diskon');
            $tagihan = TagihanPasien::findOrFail($id);

            if (is_null($tagihan->nominal_awal)) {
                $tagihan->nominal_awal = $tagihan->nominal;
                $tagihan->save();
            }

            $tindakan = $tagihan->tindakan_medis;

            if (!$tindakan) {
                $tagihan->update(['tipe_diskon' => 'None', 'diskon' => 0, 'diskon_rp' => 0]);
                $this->recalculateAndUpdateBilling($tagihan);
                return response()->json([
                    'success' => false,
                    'message' => 'Tipe diskon hanya berlaku untuk tindakan medis.',
                    'wajib_bayar' => $tagihan->fresh()->wajib_bayar,
                ]);
            }

            $group_penjamin_id = $tagihan->registration->penjamin->group_penjamin_id;
            $kelas_id = $tagihan->registration->kelas_rawat_id;

            // Memanggil method getTarif() yang benar
            $tarif = $tindakan->getTarif($group_penjamin_id, $kelas_id);

            $disc = ['share_rs' => 0.00, 'share_dr' => 0.00, 'total' => 0.00];
            if ($tarif) {
                $disc['share_rs'] = (float) $tarif->share_rs;
                $disc['share_dr'] = (float) $tarif->share_dr;
                $disc['total']    = (float) $tarif->total;
            } else {
                \Log::warning("Tarif tidak ditemukan untuk Tindakan ID: {$tindakan->id}, Group Penjamin: {$group_penjamin_id}, Kelas: {$kelas_id}");
                $tipeDiskon = 'None';
            }

            $diskon = 0;
            if ($tipeDiskon === 'Dokter') {
                $diskon = $disc['share_dr'] * $tagihan->quantity;
            } elseif ($tipeDiskon === 'Rumah Sakit') {
                $diskon = $disc['share_rs'] * $tagihan->quantity;
            } elseif ($tipeDiskon === 'All') {
                $diskon = $disc['total'] * $tagihan->quantity;
            }

            // Cek jika diskon yang dihasilkan adalah 0, mungkin karena data tarifnya 0
            if ($diskon == 0 && $tipeDiskon !== 'None') {
                \Log::info("Diskon otomatis untuk '{$tipeDiskon}' adalah 0. Kemungkinan data tarif share-nya 0.");
            }

            $tagihan->update([
                'tipe_diskon' => $tipeDiskon,
                'diskon'      => $diskon,
                'diskon_rp'   => $diskon,
            ]);

            $this->recalculateAndUpdateBilling($tagihan);

            return response()->json([
                'success'     => true,
                'diskon'      => $diskon,
                'wajib_bayar'   => $tagihan->fresh()->wajib_bayar,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error updating discount: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'error' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }

    private function recalculateAndUpdateBilling(TagihanPasien $tagihan)
    {
        // Reload tagihan untuk memastikan kita bekerja dengan data terbaru
        $tagihan->refresh();

        $totalNominal = $tagihan->nominal_awal * $tagihan->quantity;

        // Diskon dari input persentase (%)
        $diskonPersen = ($tagihan->disc ?? 0) / 100 * $totalNominal;

        // Jaminan dari input persentase (%)
        $jaminanPersen = ($tagihan->jamin ?? 0) / 100 * $totalNominal;

        // Total semua diskon (otomatis + manual)
        $totalDiskon = ($tagihan->diskon_rp ?? 0) + $diskonPersen;

        // Total semua jaminan (manual)
        $totalJaminan = ($tagihan->jaminan_rp ?? 0) + $jaminanPersen;

        // Hitung wajib bayar akhir untuk item ini
        $wajibBayar = $totalNominal - $totalDiskon - $totalJaminan;
        $wajibBayar = max(0, $wajibBayar); // Pastikan tidak negatif

        $tagihan->update(['wajib_bayar' => $wajibBayar]);

        // Hitung ulang total wajib_bayar untuk seluruh Bilingan
        $totalWajibBayarBilingan = TagihanPasien::where('bilingan_id', $tagihan->bilingan_id)->sum('wajib_bayar');

        // Update ke model Bilingan
        $bilingan = Bilingan::find($tagihan->bilingan_id);
        if ($bilingan) {
            $bilingan->wajib_bayar = $totalWajibBayarBilingan;
            $bilingan->save();
        }
    }

    public function getTarifShare($id)
    {
        try {
            $tagihan = TagihanPasien::find($id);
            $tindakan = $tagihan->tindakan_medis;
            $group_penjamin_id = $tagihan->registration->penjamin->group_penjamin_id;
            $kelas_id = $tagihan->registration->kelas_rawat_id;

            $tarif = $tindakan->tarifTindakanMedis($group_penjamin_id, $kelas_id);

            $result = [
                'share_rs' => (int) str_replace('.', '', $tarif['share_rs']),
                'share_dr' => (int) str_replace('.', '', $tarif['share_dr']),
                'total'    => (int) str_replace('.', '', $tarif['total']),
            ];

            return response()->json(['success' => true, 'tarif' => $result]);
        } catch (\Exception $e) {
            \Log::error('Error getting tarif: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Memindahkan satu atau lebih TagihanPasien ke Bilingan tujuan,
     * termasuk memperbarui relasi di tabel pivot.
     */
    public function merge(Request $request)
    {
        $request->validate([
            'destination_id' => 'required|exists:tagihan_pasien,id',
            'source_ids'     => 'required|array',
            'source_ids.*'   => 'exists:tagihan_pasien,id',
        ]);

        DB::beginTransaction();

        try {
            // Tentukan Bilingan Tujuan dari ID Bilingan tujuan
            $destinationBilingan = Bilingan::findOrFail($request->destination_id);
            $targetBilinganId = $destinationBilingan->id;

            // Ambil semua Bilingan yang akan diproses (termasuk tujuan itu sendiri)
            $bilingansToProcess = Bilingan::whereIn('id', $request->source_ids)->get();

            $oldBilinganIds = [];
            $logDebug = [
                'target_bilingan_id' => $targetBilinganId,
                'operations' => [],
            ];

            // Proses pemindahan untuk setiap Bilingan
            foreach ($bilingansToProcess as $bilingan) {
                $originalBilinganId = $bilingan->id;

                // Hanya jalankan jika Bilingan-nya berbeda dengan target
                if ($originalBilinganId && $originalBilinganId != $targetBilinganId) {

                    if (!in_array($originalBilinganId, $oldBilinganIds)) {
                        $oldBilinganIds[] = $originalBilinganId;
                    }

                    // Ambil semua TagihanPasien yang terkait dengan Bilingan ini
                    $tagihanPasienList = $bilingan->tagihanPasien()->get();

                    foreach ($tagihanPasienList as $tagihanPasien) {
                        // Update bilingan_id pada TagihanPasien ke target
                        $tagihanPasien->bilingan_id = $targetBilinganId;
                        $tagihanPasien->save();

                        // Update relasi pivot jika ada
                        if (method_exists($tagihanPasien, 'bilingan')) {
                            $tagihanPasien->bilingan()->sync([$targetBilinganId]);
                        }

                        $logDebug['operations'][] = [
                            'tagihan_id' => $tagihanPasien->id,
                            'status' => 'MOVED',
                            'from_bilingan' => $originalBilinganId,
                            'to_bilingan' => $targetBilinganId,
                        ];
                    }
                } else {
                    // Jika Bilingan sudah sama dengan target, lewati saja.
                    $logDebug['operations'][] = [
                        'bilingan_id' => $originalBilinganId,
                        'status' => 'SKIPPED',
                        'reason' => 'Already in target bilingan.',
                    ];
                }
            }

            // Cleanup: Jangan hapus Bilingan lama yang sudah kosong (tagihan kosongnya jangan di delete)
            // Kode penghapusan bilingan kosong dihapus sesuai permintaan

            DB::commit();

            Log::info("Merge Bilingan berhasil: " . count($request->source_ids) . " bilingan diproses ke Bilingan {$targetBilinganId}");
            Log::debug('Debug detail merge bilingan', $logDebug);

            return response()->json(['message' => 'Bilingan berhasil diproses dan dipindahkan ke bilingan yang dituju!']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal melakukan merge bilingan: ' . $e->getMessage(), ['trace' => $e->getTraceAsString(), 'request_data' => $request->all()]);
            return response()->json(['message' => 'Terjadi kesalahan fatal saat memindahkan bilingan.'], 500);
        }
    }
}
