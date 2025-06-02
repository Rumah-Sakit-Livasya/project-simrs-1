<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\keuangan\JasaDokter; // <-- Pastikan ini model JasaDokter Anda
use App\Models\SIMRS\Doctor;
use App\Models\SIMRS\Registration;
use App\Models\SIMRS\Bilingan;
use App\Models\SIMRS\TagihanPasien;
use App\Models\SIMRS\TindakanMedis;
use App\Models\TarifTindakanMedis;
// use App\Models\SIMRS\PembayaranTagihan; // Import jika diperlukan

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\JasaDokterExport; // Pastikan export class ini ada dan diperbarui
class JasaDokterController extends Controller
{
    public function index(Request $request)
    {
        $dokters = Doctor::with('employee')->get();

        $query = JasaDokter::query()
            // Filter: hanya yang punya tagihan pasien yang terkait dengan bilingan status final
            ->whereHas('tagihanPasien.bilinganSatu', function ($q) {
                $q->where('status', 'final');
            })
            // Filter: hanya tagihan tindakan medis
            ->whereHas('tagihanPasien', function ($q) {
                $q->where('tagihan', 'LIKE', '[Tindakan Medis]%');
            })
            // Relasi yang dibutuhkan untuk view
            ->with([
                'tagihanPasien.registration.patient',
                'tagihanPasien.registration.penjamin',
                'tagihanPasien.registration.kelas_rawat',
                'tagihanPasien.registration.doctor.employee',
                'tagihanPasien.tindakan_medis.tarifTindakanMedis',
                'tagihanPasien.bilinganSatu.pembayaranTagihan',
                'dokter.employee'
            ]);

        // Filter tanggal dari bilingan (created_at)
        if ($request->filled('tanggal_awal')) {
            $query->whereHas('tagihanPasien.bilinganSatu', function ($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->tanggal_awal);
            });
        }
        if ($request->filled('tanggal_akhir')) {
            $query->whereHas('tagihanPasien.bilinganSatu', function ($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->tanggal_akhir);
            });
        }

        // Filter tipe registrasi
        if ($request->filled('tipe_registrasi')) {
            $query->whereHas('tagihanPasien.registration', function ($q) use ($request) {
                $q->where('registration_type', $request->tipe_registrasi);
            });
        }

        // Filter status pembayaran lunas/belum lunas
        if ($request->filled('tagihan_pasien')) {
            $query->whereHas('tagihanPasien.bilinganSatu', function ($q) use ($request) {
                if ($request->tagihan_pasien === 'lunas') {
                    $q->whereHas('pembayaranTagihan', function ($pq) {
                        $pq->where(DB::raw('lower(bill_notes)'), 'like', '%lunas%');
                    });
                } elseif ($request->tagihan_pasien === 'belum-lunas') {
                    $q->whereDoesntHave('pembayaranTagihan', function ($pq) {
                        $pq->where(DB::raw('lower(bill_notes)'), 'like', '%lunas%');
                    });
                }
            });
        }

        // Filter status AP
        if ($request->filled('status_ap')) {
            if ($request->status_ap === 'draft') {
                $query->where('status', 'draft');
            } elseif ($request->status_ap === 'final') {
                $query->where('status', 'final');
            }
        }

        // Filter dokter berdasarkan registrasi
        if ($request->filled('dokter_id')) {
            $query->whereHas('tagihanPasien.registration', function ($q) use ($request) {
                $q->where('doctor_id', $request->dokter_id);
            });
        }

        // Urutkan berdasarkan tanggal billing (created_at dari bilingan)
        $jasaDokterItems = $query->get();
        $jasaDokterItems = $jasaDokterItems->sortByDesc(function ($item) {
            return optional($item->tagihanPasien?->bilinganSatu)->created_at;
        });

        // Ambil data

        return view('app-type.keuangan.jasa-dokter.index', compact('jasaDokterItems', 'dokters'));
    }


    // Method untuk menyimpan AP Dokter dari item yang dipilih (batch create)
    public function storeSelected(Request $request)
    {
        $request->validate([
            'item_ids' => 'required|array',
            'item_ids.*' => 'exists:jasa_dokter,id',
            'tanggal_ap_save' => 'required|date_format:Y-m-d',
        ]);

        $selectedJasaDokterIds = $request->input('item_ids');
        $apDateFromFilter = $request->input('tanggal_ap_save');

        // Ambil JasaDokter yang statusnya 'draft' dan ID-nya ada di $selectedJasaDokterIds
        $jasaDokterItemsToProcess = JasaDokter::whereIn('id', $selectedJasaDokterIds)
            ->where('status', 'draft')
            // ->with([ ... relasi jika dibutuhkan ... ]) // Opsional jika hanya update
            ->get();

        if ($jasaDokterItemsToProcess->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Tidak ada item Jasa Dokter berstatus "Draft" yang valid ditemukan untuk diproses.'], 400);
        }

        $processedCount = 0;
        $apDate = Carbon::parse($apDateFromFilter)->startOfDay();

        DB::beginTransaction();
        try {
            $currentSequence = 1; // Reset sequence untuk batch ini berdasarkan tanggal AP yang sama
            $lastApOnDate = JasaDokter::whereDate('ap_date', $apDate->toDateString())
                ->whereNotNull('ap_number')
                ->orderBy('ap_number', 'desc')
                ->first();
            if ($lastApOnDate && $lastApOnDate->ap_number) {
                if (preg_match('/-(\d{4})$/', $lastApOnDate->ap_number, $matches)) {
                    $currentSequence = (int)$matches[1] + 1;
                } elseif (preg_match('/-(\d+)$/', $lastApOnDate->ap_number, $matches)) {
                    $currentSequence = (int)$matches[1] + 1;
                }
            }

            foreach ($jasaDokterItemsToProcess as $jasaDokter) {
                $apNumber = 'JD-' . $apDate->format('Ymd') . '-' . str_pad($currentSequence, 4, '0', STR_PAD_LEFT);
                $currentSequence++;

                $jasaDokter->update([
                    'ap_number' => $apNumber,
                    'ap_date'   => $apDate,
                    'status'    => 'final',
                ]);
                $processedCount++;
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => "Berhasil membuat AP Dokter untuk {$processedCount} item."]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in JasaDokterController@storeSelected: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['success' => false, 'message' => 'Gagal memproses AP Dokter: Terjadi kesalahan internal.'], 500);
        }
    }
    // Method placeholder untuk generate nomor AP (prefix saja)

    // Method placeholder untuk generate nomor AP (prefix saja)
    protected function generateApNumber(\Carbon\Carbon $date)
    {
        // Method ini hanya mengembalikan prefix JD-YYYYMMDD
        $prefix = 'JD-' . $date->format('Ymd');
        return $prefix;
    }

    // Method untuk menampilkan data untuk modal edit/create single
    public function edit($id)
    {
        try {
            // Ambil data JasaDokter dengan relasi yang diperlukan
            $jasaDokter = JasaDokter::with([
                'tagihanPasien.registration.patient',
                'tagihanPasien.registration.doctor.employee',
                'tagihanPasien.registration.penjamin',
                'tagihanPasien.registration.kelas_rawat',
                'tagihanPasien.tindakan_medis.tarifTindakanMedis',
                'tagihanPasien.bilinganSatu'
            ])->findOrFail($id);

            // Pastikan relasi tagihanPasien ada
            if (!$jasaDokter->tagihanPasien) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tagihan pasien tidak ditemukan untuk AP Dokter ini.'
                ], 404);
            }

            $item = $jasaDokter->tagihanPasien;

            // Ambil nilai share_dr dan total dari TarifTindakanMedis
            $shareDrDefault = 0;
            $nominalTotalTarifDefault = 0;

            if ($item->tindakan_medis && $item->tindakan_medis->tarifTindakanMedis) {
                $tarif = $item->tindakan_medis->getTarif(
                    $item->registration->penjamin_id ?? null,
                    $item->registration->kelas_rawat_id ?? null
                );

                $shareDrDefault = $tarif->share_dr ?? 0;
                $nominalTotalTarifDefault = $tarif->total ?? 0;
            }

            return response()->json([
                'success' => true,
                'data' => [
                    // Data utama (readonly)
                    'id' => $item->id,
                    'registration_number' => $item->registration->registration_number ?? '-',
                    'medical_record_number' => $item->registration->patient->medical_record_number ?? '-',
                    'patient_name' => $item->registration->patient->name ?? '-',
                    'tindakan_medis_name' => $item->tindakan_medis->nama_tindakan ?? '-',
                    'current_dokter_name' => $item->registration->doctor->employee->fullname ?? 'N/A',
                    'nominal' => $nominalTotalTarifDefault,
                    'diskon' => $item->diskon ?? 0,

                    // Data AP yang bisa diedit
                    'ap' => [
                        'id' => $jasaDokter->id,
                        'dokter_id' => $jasaDokter->dokter_id,
                        'share_dokter' => $jasaDokter->share_dokter,
                        'jkp' => $jasaDokter->jkp,
                    ],

                    // Data referensi untuk form
                    'default_share_dokter' => $shareDrDefault,
                ]
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data AP Dokter tidak ditemukan.'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error in JasaDokterController@edit: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data.'
            ], 500);
        }
    }



    // Method untuk update AP Dokter dari modal edit
    public function update(Request $request, $id)
    {
        $jasaDokter = JasaDokter::findOrFail($id);

        // Validasi hanya field yang bisa diedit
        $request->validate([
            'dokter_id' => 'required|exists:doctors,id',
            'share_dokter' => 'required|numeric|min:0',
            'jkp' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Hanya update field yang diizinkan
            $jasaDokter->update([
                'dokter_id' => $request->dokter_id,
                'share_dokter' => $request->share_dokter,
                'jkp' => $request->jkp,
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Data AP Dokter berhasil diupdate.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating Jasa Dokter AP: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal mengupdate AP Dokter.'], 500);
        }
    }

    // Method untuk membuat AP Dokter dari modal (single create)
    public function createSingle(Request $request, $tagihanPasienId)
    {
        // Cari TagihanPasien item
        $item = TagihanPasien::with(['registration.kelas_rawat', 'registration.penjamin', 'registration.doctor', 'tindakan_medis.tarifTindakanMedis', 'bilinganSatu'])
            ->findOrFail($tagihanPasienId);

        // Cek lagi apakah AP Dokter sudah ada untuk item ini
        if ($item->jasaDokter) {
            return response()->json(['success' => false, 'message' => 'AP Dokter sudah ada untuk item ini. Silakan muat ulang halaman.'], 400);
        }

        // Validasi input dari modal
        $request->validate([
            'ap_date' => 'required|date_format:Y-m-d',
            'nominal' => 'required|numeric|min:0', // Nominal AP Dokter dari form
            'diskon' => 'nullable|numeric|min:0',
            'ppn_persen' => 'nullable|numeric|min:0',
            'jkp' => 'nullable|numeric|min:0',
            'share_dokter' => 'required|numeric|min:0', // Jasa Dokter AP dari form
            // Status 'final' adalah default saat create single dari modal icon
            // 'status' => 'required|in:final', // Jika status selalu final saat buat dari modal
        ]);

        DB::beginTransaction();
        try {
            // Ambil nilai default dari tarif
            $tarif = $item->tindakan_medis?->getTarif(
                $item->registration->penjamin_id ?? null,
                $item->registration->kelas_rawat_id ?? null
            );

            $shareDrDefault = $tarif->share_dr ?? 0;
            $nominalTotalTarifDefault = $tarif->total ?? 0;

            $apDate = Carbon::parse($request->input('ap_date')); // Ambil dan parse tanggal AP dari request modal
            // Generate Nomor AP Dokter berdasarkan tanggal AP dari request modal
            $apNumberPrefix = $this->generateApNumber($apDate);

            // Dapatkan nomor urut terakhir untuk tanggal AP dari modal
            $lastApToday = JasaDokter::whereDate('ap_date', $apDate->toDateString())
                ->orderBy('ap_number', 'desc')
                ->first();
            $currentSequence = 1;
            if ($lastApToday && preg_match('/^JD-\d{8}-(\d+)$/', $lastApToday->ap_number, $matches)) {
                $currentSequence = (int)$matches[1] + 1;
            }
            // Generate nomor AP lengkap
            $apNumber = $apNumberPrefix . '-' . str_pad($currentSequence, 4, '0', STR_PAD_LEFT);


            $jasaDokter = JasaDokter::create([
                'tagihan_pasien_id' => $item->id,
                'registration_id' => $item->registration->id,
                'bilingan_id' => $item->bilinganSatu->id,
                'dokter_id' => $item->registration->doctor->id ?? null,
                'ap_number' => $apNumber, // Nomor AP yang digenerate
                'ap_date' => $apDate, // Tanggal AP dari modal
                'bill_date' => $item->bilinganSatu->created_at, // Tanggal Bill dari created_at bilingan
                'nama_tindakan' => $item->tindakan_medis->nama_tindakan ?? 'N/A',
                'nominal' => $request->input('nominal', $nominalTotalTarifDefault), // Nominal AP dari form, fallback ke total tarif
                'diskon' => $request->input('diskon', 0),
                'ppn_persen' => $request->input('ppn_persen', 0),
                'jkp' => $request->input('jkp', 0),
                'jasa_dokter' => $request->input('jasa_dokter', $shareDrDefault), // Jasa Dokter AP dari form, fallback ke share dr
                // HAPUS pengisian kolom share_dokter karena sudah dihapus di database
                // 'share_dokter' => $shareDrDefault,
                'status' => 'final', // Default status AP saat dibuat dari modal
                // 'order_tindakan_medis_id' => ?
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'AP Dokter berhasil dibuat.', 'ap_id' => $jasaDokter->id]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating single Jasa Dokter AP: ' . $e->getMessage(), ['exception' => $e, 'tagihan_pasien_id' => $tagihanPasienId, 'request_data' => $request->all()]);
            // Return validation errors specifically if they exist
            if ($e instanceof \Illuminate\Validation\ValidationException) {
                return response()->json(['success' => false, 'message' => 'Validasi Error', 'errors' => $e->errors()], 422);
            }
            return response()->json(['success' => false, 'message' => 'Gagal membuat AP Dokter: ' . $e->getMessage()], 500);
        }
    }

    // Method untuk menghapus AP Dokter yang dipilih (cancel)
    // Di JasaDokterController.php
    public function cancelSelected(Request $request)
    {
        $request->validate([
            'item_ids' => 'required|array',
            'item_ids.*' => 'exists:jasa_dokter,id',
        ]);

        $selectedJasaDokterIds = $request->input('item_ids');

        $jasaDokterItemsToCancel = JasaDokter::whereIn('id', $selectedJasaDokterIds)
            ->where('status', 'final') // Hanya yang final yang bisa dibatalkan
            ->get();

        if ($jasaDokterItemsToCancel->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Tidak ada item Jasa Dokter berstatus "Sudah Dibuat" yang valid ditemukan untuk dibatalkan.'], 400);
        }

        $cancelledCount = 0;
        DB::beginTransaction();
        try {
            foreach ($jasaDokterItemsToCancel as $jasaDokter) {
                $jasaDokter->update([
                    'ap_number' => null,
                    'ap_date'   => null,
                    'status'    => 'draft',
                ]);
                $cancelledCount++;
            }
            DB::commit();
            return response()->json(['success' => true, 'message' => "Berhasil membatalkan AP Dokter untuk {$cancelledCount} item."]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in JasaDokterController@cancelSelected: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal membatalkan AP Dokter: Terjadi kesalahan internal.'], 500);
        }
    }


    // Method untuk export Excel (jika menggunakan Maatwebsite\Excel)
    public function exportExcel(Request $request)
    {
        // Replikasi logika query filtering dari method index() di sini
        $query = TagihanPasien::query()
            ->whereHas('bilinganSatu', function ($q) {
                $q->where('status', 'final');
            })
            ->with([
                'registration.patient',
                'registration.penjamin',
                'registration.kelas_rawat',
                'registration.doctor.employee',
                'tindakan_medis.tarifTindakanMedis',
                'bilinganSatu.pembayaranTagihan',
                'jasaDokter',
                'bilinganSatu'
            ]);

        // Apply Filters (filter tanggal_awal dan tanggal_akhir menggunakan created_at bilingan)
        if ($request->filled('tanggal_awal')) {
            $query->whereHas('bilinganSatu', function ($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->tanggal_awal);
            });
        }
        if ($request->filled('tanggal_akhir')) {
            $query->whereHas('bilinganSatu', function ($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->tanggal_akhir);
            });
        }
        // HAPUS FILTER TANGGAL AP DI EXPORT JUGA KARENA INPUT FILTER UTAMA BUKAN UNTUK FILTER AP DATE LAGI
        // if ($request->filled('tanggal_ap')) { ... }

        // Filter tipe registrasi
        if ($request->filled('tipe_registrasi')) {
            $query->whereHas('registration', function ($q) use ($request) {
                $q->where('registration_type', $request->tipe_registrasi);
            });
        }
        // Filter status pembayaran tagihan
        if ($request->filled('tagihan_pasien')) {
            $query->whereHas('bilinganSatu', function ($q) use ($request) {
                if ($request->tagihan_pasien == 'lunas') {
                    $q->whereHas('pembayaranTagihan', function ($pq) {
                        $pq->where(DB::raw('lower(bill_notes)'), 'like', '%lunas%');
                    });
                } elseif ($request->tagihan_pasien == 'belum-lunas') {
                    $q->whereDoesntHave('pembayaranTagihan', function ($pq) {
                        $pq->where(DB::raw('lower(bill_notes)'), 'like', '%lunas%');
                    });
                }
            });
        }
        // Filter status AP
        if ($request->filled('status_ap')) {
            if ($request->status_ap == 'draft') {
                $query->whereDoesntHave('jasaDokter');
            } elseif ($request->status_ap == 'final') {
                $query->whereHas('jasaDokter');
            }
        }
        // Filter dokter registrasi
        if ($request->filled('dokter_id')) {
            $query->whereHas('registration', function ($q) use ($request) {
                $q->where('doctor_id', $request->dokter_id);
            });
        }

        // Urutkan berdasarkan created_at di tabel bilingan menggunakan subquery
        $query->orderByDesc(
            Bilingan::select('created_at')
                ->whereColumn('bilingan.id', 'tagihan_pasien.bilingan_id')
                ->limit(1)
        );


        $tagihanPasienItems = $query->get(); // Ambil data yang sudah difilter

        // Buat Export Class menggunakan Maatwebsite\Excel
        // Pastikan JasaDokterExport Class sudah diperbarui untuk tidak menyertakan kolom share_dokter
        return Excel::download(new JasaDokterExport($tagihanPasienItems), 'jasa_dokter_ap_' . now()->format('Ymd') . '.xlsx');
    }

    // Tambahkan method baru untuk get data modal
    public function getModalData(Request $request, $id)
    {
        try {
            $mode = $request->query('mode'); // 'create' atau 'edit'

            if ($mode === 'create') {
                // Data untuk create (dari TagihanPasien)
                $item = TagihanPasien::with(['registration.patient', 'registration.doctor.employee', 'tindakan_medis', 'bilinganSatu'])
                    ->findOrFail($id);

                $tarif = $item->tindakan_medis?->getTarif(
                    $item->registration->penjamin_id,
                    $item->registration->kelas_rawat_id
                );

                return response()->json([
                    'success' => true,
                    'mode' => 'create',
                    'data' => [
                        'rm_reg' => ($item->registration->patient->medical_record_number ?? '-') . '/' . ($item->registration->registration_number ?? '-'),
                        'patient_name' => $item->registration->patient->name ?? '-',
                        'tindakan_medis_name' => $item->tindakan_medis->nama_tindakan ?? '-',
                        'dokter_name' => $item->registration->doctor->employee->fullname ?? 'N/A',
                        'bill_date' => $item->bilinganSatu->created_at->format('d-m-Y'),
                        'nominal' => $tarif->total ?? 0,
                        'share_dr' => $tarif->share_dr ?? 0,
                    ]
                ]);
            } else {
                // Data untuk edit (dari JasaDokter)
                $jasa = JasaDokter::with(['tagihanPasien.registration.patient', 'tagihanPasien.registration.doctor.employee', 'tagihanPasien.tindakan_medis'])
                    ->findOrFail($id);

                return response()->json([
                    'success' => true,
                    'mode' => 'edit',
                    'data' => [
                        'ap_number' => $jasa->ap_number,
                        'ap_date' => $jasa->ap_date->format('Y-m-d'),
                        'rm_reg' => ($jasa->tagihanPasien->registration->patient->medical_record_number ?? '-') . '/' . ($jasa->tagihanPasien->registration->registration_number ?? '-'),
                        // ... data lainnya sama dengan create ...
                        'jasa_dokter' => $jasa->jasa_dokter,
                        'jkp' => $jasa->jkp,
                    ]
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error in getModalData: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }
    }
}
