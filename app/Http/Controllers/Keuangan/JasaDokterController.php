<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Keuangan\JasaDokter; // <-- Pastikan ini model JasaDokter Anda
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
use Illuminate\Support\Facades\Validator;

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
            // UBAH BAGIAN INI: Filter untuk mengecualikan 'Biaya Administrasi'
            ->whereHas('tagihanPasien', function ($q) {
                $q->where('tagihan', 'NOT LIKE', 'Biaya Administrasi%');
            })
            // --- AKHIR PERUBAHAN ---
            // Relasi yang dibutuhkan untuk view
            ->with(['tagihanPasien.registration.patient', 'tagihanPasien.registration.penjamin', 'tagihanPasien.registration.kelas_rawat', 'tagihanPasien.registration.doctor.employee', 'tagihanPasien.tindakan_medis.tarifTindakanMedis', 'tagihanPasien.bilinganSatu.pembayaranTagihan', 'dokter.employee']);

        // ... (sisa method index tidak perlu diubah) ...
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

    // ... (sisa method dari storeSelected hingga sebelum exportExcel tidak perlu diubah) ...

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
            $lastApOnDate = JasaDokter::whereDate('ap_date', $apDate->toDateString())->whereNotNull('ap_number')->orderBy('ap_number', 'desc')->first();
            if ($lastApOnDate && $lastApOnDate->ap_number) {
                if (preg_match('/-(\d{4})$/', $lastApOnDate->ap_number, $matches)) {
                    $currentSequence = (int) $matches[1] + 1;
                } elseif (preg_match('/-(\d+)$/', $lastApOnDate->ap_number, $matches)) {
                    $currentSequence = (int) $matches[1] + 1;
                }
            }

            foreach ($jasaDokterItemsToProcess as $jasaDokter) {
                $apNumber = 'JD-' . $apDate->format('Ymd') . '-' . str_pad($currentSequence, 4, '0', STR_PAD_LEFT);
                $currentSequence++;

                $jasaDokter->update([
                    'ap_number' => $apNumber,
                    'ap_date' => $apDate,
                    'status' => 'final',
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
            $jasaDokter = JasaDokter::with(['tagihanPasien.registration.patient', 'tagihanPasien.registration.doctor.employee', 'tagihanPasien.registration.penjamin', 'tagihanPasien.registration.kelas_rawat', 'tagihanPasien.tindakan_medis.tarifTindakanMedis', 'tagihanPasien.bilinganSatu'])->findOrFail($id);

            // Pastikan relasi tagihanPasien ada
            if (!$jasaDokter->tagihanPasien) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Data tagihan pasien tidak ditemukan untuk AP Dokter ini.',
                    ],
                    404,
                );
            }

            $item = $jasaDokter->tagihanPasien;

            // Ambil nilai share_dr dan total dari TarifTindakanMedis
            $shareDrDefault = 0;
            $nominalTotalTarifDefault = 0;

            if ($item->tindakan_medis && $item->tindakan_medis->tarifTindakanMedis) {
                $tarif = $item->tindakan_medis->getTarif($item->registration->penjamin_id ?? null, $item->registration->kelas_rawat_id ?? null);

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
                ],
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Data AP Dokter tidak ditemukan.',
                ],
                404,
            );
        } catch (\Exception $e) {
            Log::error('Error in JasaDokterController@edit: ' . $e->getMessage());
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat mengambil data.',
                ],
                500,
            );
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
        $item = TagihanPasien::with(['registration.kelas_rawat', 'registration.penjamin', 'registration.doctor', 'tindakan_medis.tarifTindakanMedis', 'bilinganSatu'])->findOrFail($tagihanPasienId);

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
            $tarif = $item->tindakan_medis?->getTarif($item->registration->penjamin_id ?? null, $item->registration->kelas_rawat_id ?? null);

            $shareDrDefault = $tarif->share_dr ?? 0;
            $nominalTotalTarifDefault = $tarif->total ?? 0;

            $apDate = Carbon::parse($request->input('ap_date')); // Ambil dan parse tanggal AP dari request modal
            // Generate Nomor AP Dokter berdasarkan tanggal AP dari request modal
            $apNumberPrefix = $this->generateApNumber($apDate);

            // Dapatkan nomor urut terakhir untuk tanggal AP dari modal
            $lastApToday = JasaDokter::whereDate('ap_date', $apDate->toDateString())->orderBy('ap_number', 'desc')->first();
            $currentSequence = 1;
            if ($lastApToday && preg_match('/^JD-\d{8}-(\d+)$/', $lastApToday->ap_number, $matches)) {
                $currentSequence = (int) $matches[1] + 1;
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
                    'ap_date' => null,
                    'status' => 'draft',
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
        // ... (validasi request tidak perlu diubah) ...
        $request->validate([
            'tanggal_awal' => 'nullable|date',
            'tanggal_akhir' => 'nullable|date|after_or_equal:tanggal_awal',
            'dokter_ids' => 'nullable|array', // Array dokter yang dipilih
            'dokter_ids.*' => 'integer|exists:doctors,id',
            'tipe_registrasi' => 'nullable|string',
            'tagihan_pasien' => 'nullable|in:lunas,belum-lunas',
            'status_ap' => 'nullable|in:draft,final',
        ]);

        // Query utama dimulai dari JasaDokter, sama seperti di method index()
        $query = JasaDokter::query()
            ->whereHas('tagihanPasien.bilinganSatu', function ($q) {
                $q->where('status', 'final'); // Bilingan dari tagihan pasien harus final
            })
            // UBAH BAGIAN INI: Filter untuk mengecualikan 'Biaya Administrasi'
            ->whereHas('tagihanPasien', function ($q) {
                $q->where('tagihan', 'NOT LIKE', 'Biaya Administrasi%');
            })
            // --- AKHIR PERUBAHAN ---
            ->with([
                // Eager load relasi yang dibutuhkan untuk data export
                'tagihanPasien.registration.patient',
                'tagihanPasien.registration.penjamin',
                'tagihanPasien.registration.kelas_rawat',
                'tagihanPasien.registration.doctor.employee', // Dokter Registrasi
                'tagihanPasien.bilinganSatu.pembayaranTagihan', // Untuk info pembayaran
                'tagihanPasien.tindakan_medis', // Untuk nama tindakan asli jika perlu
                'dokter.employee', // Dokter AP (yang ada di tabel jasa_dokter)
            ]);

        // ... (sisa method exportExcel tidak perlu diubah) ...
        // --- FILTER PERIODE TANGGAL (WAJIB) ---
        // Filter tanggal dari bilingan (created_at pada tabel bilingan melalui tagihanPasien)
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

        // --- FILTER DOKTER SPESIFIK (BARU) ---
        // Filter berdasarkan dokter yang dipilih (bisa multiple)
        if ($request->filled('dokter_ids') && is_array($request->dokter_ids)) {
            $query->whereIn('dokter_id', $request->dokter_ids);
        }

        // Atau jika ingin filter berdasarkan dokter registrasi
        if ($request->filled('dokter_registrasi_ids') && is_array($request->dokter_registrasi_ids)) {
            $query->whereHas('tagihanPasien.registration', function ($q) use ($request) {
                $q->whereIn('doctor_id', $request->dokter_registrasi_ids);
            });
        }

        // --- Filter Lainnya (Opsional) ---

        // Filter tipe registrasi (melalui tagihanPasien)
        if ($request->filled('tipe_registrasi')) {
            $query->whereHas('tagihanPasien.registration', function ($q) use ($request) {
                $q->where('registration_type', $request->tipe_registrasi);
            });
        }

        // Filter status pembayaran lunas/belum lunas (melalui tagihanPasien)
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

        // Filter status AP (langsung pada tabel jasa_dokter)
        if ($request->filled('status_ap')) {
            if ($request->status_ap === 'draft') {
                $query->where('status', 'draft');
            } elseif ($request->status_ap === 'final') {
                $query->where('status', 'final');
            }
        }

        // Filter dokter tunggal (untuk backward compatibility)
        if ($request->filled('dokter_id')) {
            $query->whereHas('tagihanPasien.registration', function ($q) use ($request) {
                $q->where('doctor_id', $request->dokter_id);
            });
        }

        // Mengambil data JasaDokter yang sudah difilter
        $jasaDokterItems = $query->get();

        // Validasi jika tidak ada data
        if ($jasaDokterItems->isEmpty()) {
            return back()->with('warning', 'Tidak ada data untuk periode dan filter yang dipilih.');
        }

        // Lakukan pengurutan di collection
        $jasaDokterItems = $jasaDokterItems->sortByDesc(function ($item) {
            return optional($item->tagihanPasien?->bilinganSatu)->created_at;
        });

        // Generate nama file dengan informasi filter
        $filename = 'laporan_ap_dokter';

        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $filename .= '_' . date('Ymd', strtotime($request->tanggal_awal)) .
                '_' . date('Ymd', strtotime($request->tanggal_akhir));
        }

        if ($request->filled('dokter_ids') && count($request->dokter_ids) == 1) {
            // Jika hanya satu dokter dipilih, tambahkan nama dokter ke filename
            $dokter = doctor::find($request->dokter_ids[0]);
            if ($dokter && $dokter->employee) {
                $dokterName = str_replace(' ', '_', $dokter->employee->fullname);
                $filename .= '_' . $dokterName;
            }
        }

        $filename .= '_' . now()->format('Ymd_His') . '.xlsx';

        // Kirim koleksi JasaDokter ke class export dengan informasi filter
        return Excel::download(
            new JasaDokterExport($jasaDokterItems, $request->all()),
            $filename
        );
    }

    // ... (sisa method lainnya tidak perlu diubah) ...
    public function getModalData($jasaDokterId)
    {
        try {
            $jasaDokter = JasaDokter::with(['tagihanPasien.registration.patient', 'tagihanPasien.registration', 'tagihanPasien.tindakan_medis', 'dokter.employee'])->findOrFail($jasaDokterId);

            $data = [
                'dokter_id_ap' => $jasaDokter->dokter_id,
                'jasa_dokter_ap' => $jasaDokter->jasa_dokter, // Ganti ini jika share_dokter sudah dihapus
                'jkp_ap' => $jasaDokter->jkp,
                'ap_number_display' => $jasaDokter->ap_number,
                'ap_date_display' => optional($jasaDokter->ap_date)->format('d-m-Y'),
                'rm_reg_display' => ($jasaDokter->tagihanPasien->registration->patient->medical_record_number ?? '-') . '/' . ($jasaDokter->tagihanPasien->registration->registration_number ?? '-'),
                'pasien_name_display' => $jasaDokter->tagihanPasien->registration->patient->name ?? '-',
                'detail_tagihan_referensi' => $jasaDokter->nama_tindakan ?? ($jasaDokter->tagihanPasien->tindakan_medis->nama_tindakan ?? ($jasaDokter->tagihanPasien->tagihan ?? 'N/A')),
                'nominal_tagihan_referensi' => $jasaDokter->tagihanPasien->nominal ?? 0,
                'diskon_tagihan_referensi' => $jasaDokter->tagihanPasien->diskon ?? 0,
            ];

            return response()->json(['success' => true, 'data' => $data]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Data AP Dokter tidak ditemukan.'], 404);
        } catch (\Exception $e) {
            Log::error('Error in getModalDataForEdit: ' . $e->getMessage(), ['id' => $jasaDokterId, 'trace' => $e->getTraceAsString()]);
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan server saat mengambil data.'], 500);
        }

        return view('appt-type.keuangan.jasa-dokter.index')->with([
            'item' => $jasaDokter, // atau null jika buat baru
            'dokters' => $dokters,
            'diskon' => $diskon,
        ]);
    }

    public function editPopup(JasaDokter $jasaDokter)
    {
        // Eager load relasi yang dibutuhkan untuk form
        $jasaDokter->load([
            'tagihanPasien.registration.patient',
            'tagihanPasien.registration.penjamin',
            'tagihanPasien.registration.kelas_rawat',
            'tagihanPasien.bilinganSatu', // Untuk tanggal bill
            'dokter.employee', // Dokter yang ditugaskan pada AP Jasa ini (jika relasi 'dokter' ada di JasaDokter)
            'tagihanPasien.tindakan_medis', // Untuk nama tindakan asli
        ]);

        // Ambil semua dokter untuk dropdown
        // Sesuaikan query ini jika model Dokter atau struktur data dokter Anda berbeda
        $allDoctors = Doctor::with('employee') // Asumsi relasi 'employee' di model Doctor memiliki 'fullname'
            // ->where('is_active', true) // Contoh filter dokter aktif
            ->get()
            ->map(function ($doc) {
                return [
                    'id' => $doc->id,
                    // Sesuaikan cara mendapatkan nama lengkap dokter
                    'fullname' => optional($doc->employee)->fullname ?? ($doc->nama_dokter ?? 'Dokter Tanpa Nama (ID: ' . $doc->id . ')'),
                ];
            });

        return view('app-type.keuangan.jasa-dokter.edit-popup', compact('jasaDokter', 'allDoctors'));
    }

    public function updatePopup(Request $request, JasaDokter $jasaDokter)
    {
        // Pastikan parameter $jasaDokter
        $validator = Validator::make(
            $request->all(),
            [
                'dokter_id_ap' => 'required|exists:doctors,id',
            ],
            [
                'dokter_id_ap.required' => 'Kolom dokter wajib diisi.', // Pesan custom
                'dokter_id_ap.exists' => 'Dokter yang dipilih tidak valid.',
            ],
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('error_popup', 'Silakan periksa kembali input Anda.'); // Pesan umum untuk Toastr
        }

        DB::beginTransaction();
        try {
            $jasaDokter->dokter_id = $request->input('dokter_id_ap');
            $jasaDokter->save();
            DB::commit();

            return back()->with('success_popup', 'Dokter AP berhasil diperbarui.')->with('close_popup_and_refresh_opener', true);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat update JasaDokter via popup: ' . $e->getMessage(), [
                'jasa_dokter_id' => $jasaDokter->id,
                'request_data' => $request->all(),
            ]);
            return redirect()->back()->with('error_popup', 'Gagal memperbarui data: Terjadi kesalahan server.')->withInput();
        }
    }
}
