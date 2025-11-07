<?php

namespace App\Http\Controllers\SIMRS\Poliklinik;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SIMRS\ERMController;
use App\Models\SIMRS\Departement;
use App\Models\SIMRS\JadwalDokter;
use App\Models\SIMRS\Pengkajian\FormTemplate;
use App\Models\SIMRS\Pengkajian\PengkajianLanjutan;
use App\Models\SIMRS\Registration;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class PoliklinikController extends Controller
{
    public function index()
    {
        $uriSegments = explode('/', request()->path());
        $path = $uriSegments[1];

        $menu = request()->menu;
        $noRegist = request()->registration;

        $departements = Departement::latest()->get();
        $hariIni = Carbon::now()->translatedFormat('l');
        $jadwal_dokter = JadwalDokter::where('hari', $hariIni)->get();
        $registration = Registration::where('registration_number', $noRegist)->first();

        $query = Registration::whereDate('registration_date', Carbon::today());

        if ($registration) {
            $query->when($registration->departement_id, function ($q) use ($registration) {
                return $q->where('departement_id', $registration->departement_id);
            });

            $query->when($registration->doctor_id, function ($q) use ($registration) {
                return $q->where('doctor_id', $registration->doctor_id);
            });
        }

        $registrations = $query->get();

        if ($menu && $noRegist) {
            $html = view('pages.simrs.poliklinik.partials.list-pasien', compact('registrations'))->render();

            $menuResponse = ERMController::poliklinikMenu($noRegist, $menu, $departements, $jadwal_dokter, $registration, $registrations, $path);
            if ($menuResponse) {
                return $menuResponse;
            }
        } else {
            return view('pages.simrs.poliklinik.index', compact('departements', 'jadwal_dokter', 'registration', 'registrations', 'path'));
        }

        return view('pages.simrs.poliklinik.index', compact('departements', 'jadwal_dokter', 'registration', 'registrations'));
    }

    public function filterPasien(Request $request)
    {
        try {
            $routePath = parse_url($request['route'], PHP_URL_PATH);

            if ($routePath === '/simrs/igd/catatan-medis') {
                $query = Registration::whereDate('registration_date', Carbon::today())->where('registration_type', 'igd');
            } else {
                $query = Registration::whereDate('registration_date', Carbon::today())->where('registration_type', '!=', 'igd');
            }

            $query->when($request->departement_id, function ($q) use ($request) {
                return $q->where('departement_id', $request->departement_id);
            });

            $query->when($request->doctor_id, function ($q) use ($request) {
                return $q->where('doctor_id', $request->doctor_id);
            });

            $registrations = $query->get();

            // Render partial view sebagai HTML
            $html = view('pages.simrs.poliklinik.partials.list-pasien', compact('registrations'))->render();

            return response()->json([
                'success' => true,
                'message' => 'Data retrieved successfully',
                'html' => $html,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function showForm(Request $request, $registrationId, $encryptedID)
    {
        // try {
        $id = base64_decode($encryptedID);
        $formTemplateModel = FormTemplate::find($id);
        $registration = Registration::find($registrationId);
        $formSource = $formTemplateModel->form_source;
        $ruangan =  $registration->patient->bed?->room->ruangan . ' - ' . $registration->patient->bed?->nama_tt;

        $data = [
            'no_rm' => $registration->patient->medical_record_number ?? '',
            'nama_pasien' => $registration->patient->name ?? '',
            'tgl_lahir_pasien' => Carbon::parse($registration->patient->date_of_birth)->format('d-m-Y') ?? '',
            'umur_pasien' => hitungUmur($registration->patient->date_of_birth) ?? '',
            'kelamin_pasien' => $registration->patient->gender == 'm' ? 'Laki-laki' : 'Perempuan',
            'alamat_pasien' => $registration->patient->address ?? '',
            'dpjp' => $registration->doctor->employee->fullname ?? '',
            'no_hp_pasien' => $registration->patient->mobile_phone_number ?? '',
            'nik_pasien' => $registration->patient->id_card ?? '',
            'tgl_sekarang' => Carbon::now()->format('d-m-Y') ?? '',
            'pegawai' => auth()->user()?->employee?->fullname ?? '',
            'ruangan' => $ruangan ?? 'Belum Masuk Ruangan',
        ];

        // [DIPERBAIKI] Ganti placeholder dengan regex agar lebih fleksibel
        foreach ($data as $key => $value) {
            // Regex ini akan mencari {{key}} atau {{{key}}}
            $formSource = preg_replace('/\{\{\{?' . preg_quote($key) . '\}?\}\}/', htmlspecialchars($value), $formSource);
        }
        // Ubah logika penggantian placeholder tanda tangan
        $formSource = preg_replace_callback('/\[SIGNATURE_PAD:(.*?)\]/', function ($matches) {
            $inputName = $matches[1];
            $inputId = 'signature-input-' . $inputName;
            $previewId = 'signature-preview-' . $inputName;

            return view('components.signature-popup-trigger', [
                'inputName' => $inputName,
                'inputId' => $inputId,
                'previewId' => $previewId,
                'initialData' => '', // Kosong karena ini form baru
            ])->render();
        }, $formSource);

        $formSource = preg_replace_callback('/\[IMAGE_EDITOR:(.*?)\]/', function ($matches) {
            $inputName = $matches[1];

            // Tentukan gambar latar belakang default. Pastikan file ini ada di public/images
            $defaultImage = asset('images/audiogram-background.jpg');

            // Render komponen Blade 'image-editor'
            return view('s', [
                'inputName' => $inputName,
                'initialData' => '', // Selalu kosong untuk form baru
                'defaultImage' => $defaultImage,
            ])->render();
        }, $formSource);

        return view('pages.simrs.poliklinik.pengkajian_lanjutan.show_form', [
            'formTemplate' => $formSource,
            'formTemplateId' => $id,
            'registrationId' => $registrationId,
        ]);
        // } catch (Exception $e) {
        //     Log::error('Gagal menampilkan form: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
        //     abort(404, 'Data template form atau registrasi tidak ditemukan.');
        // }
    }

    // ==========================================================
    // METHOD BARU UNTUK MELIHAT DAN MENGEDIT FORM YANG SUDAH DIISI
    // ==========================================================

    /**
     * Menampilkan halaman form yang sudah diisi dalam mode LIHAT/CETAK (read-only).
     * Method ini dipanggil oleh route `poliklinik.pengkajian-lanjutan.show`.
     *
     * @param  PengkajianLanjutan  $pengkajianLanjutan  Instance model dari Route Model Binding.
     */
    public function showFilledForm(PengkajianLanjutan $pengkajianLanjutan): View
    {
        // Panggil helper method dengan flag isEditMode = false
        return $this->prepareAndShowForm($pengkajianLanjutan, false);
    }

    /**
     * Menampilkan halaman form yang sudah diisi dalam mode EDIT.
     * Method ini dipanggil oleh route `poliklinik.pengkajian-lanjutan.edit`.
     *
     * @param  PengkajianLanjutan  $pengkajianLanjutan  Instance model dari Route Model Binding.
     * @return \Illuminate\Http\RedirectResponse|View
     */
    public function editFilledForm(PengkajianLanjutan $pengkajianLanjutan)
    {

        // Aturan Bisnis: Jangan izinkan edit jika form sudah difinalisasi.
        if ($pengkajianLanjutan->is_final) {
            // Redirect kembali ke halaman sebelumnya dengan pesan error.
            return back()->with('error', 'Form yang sudah difinalisasi tidak dapat diubah lagi.');
        }
        dd($pengkajianLanjutan);

        // Panggil helper method dengan flag isEditMode = true
        return $this->prepareAndShowForm($pengkajianLanjutan, true);
    }

    /**
     * Private helper method untuk menghindari duplikasi kode.
     * Method ini menyiapkan semua data yang dibutuhkan dan merender view.
     */
    /**
     * Private helper method untuk menghindari duplikasi kode.
     */
    // private function prepareAndShowForm(PengkajianLanjutan $pengkajian, bool $isEditMode): \Illuminate\View\View
    // {
    //     try {
    //         // 1. Eager Load relasi untuk mencegah N+1 Query Problem.
    //         $pengkajian->load(['form_template', 'registration.patient', 'creator', 'editor']);

    //         // 2. [DIPERBAIKI] Logika aman untuk menangani 'form_values'.
    //         // Variabel ini akan menampung hasil akhir.
    //         $formValues = [];

    //         // Ambil data mentah dari model.
    //         $sourceData = $pengkajian->form_values;

    //         // Cek tipe datanya.
    //         if (is_string($sourceData)) {
    //             // Jika masih berupa string JSON, maka kita decode.
    //             $formValues = json_decode($sourceData, true) ?? [];
    //         } elseif (is_array($sourceData)) {
    //             // Jika sudah berupa array (karena casting model berhasil), langsung gunakan.
    //             $formValues = $sourceData;
    //         }
    //         // Jika null atau tipe lain, $formValues akan tetap menjadi array kosong, sehingga aman.

    //         // 3. Render view yang BENAR untuk menampilkan/mengedit form yang sudah diisi.
    //         return view('pages.simrs.poliklinik.pengkajian_lanjutan.show', [ // <-- DIUBAH DI SINI
    //             'pengkajian'    => $pengkajian,
    //             'formTemplate'  => $pengkajian->form_template,
    //             'formValues'    => $formValues,
    //             'isEditMode'    => $isEditMode,
    //         ]);
    //     } catch (\Exception $e) {
    //         // Tangani error jika terjadi masalah
    //         \Illuminate\Support\Facades\Log::error('Gagal memuat form pengkajian lanjutan: ' . $e->getMessage());
    //         abort(500, 'Terjadi kesalahan saat memuat data form. Silakan coba lagi nanti.');
    //     }
    // }

    private function prepareAndShowForm(PengkajianLanjutan $pengkajian, bool $isEditMode): \Illuminate\View\View
    {
        try {
            // 1. Eager Load relasi untuk performa.
            $pengkajian->load(['form_template', 'registration.patient', 'registration.doctor.employee', 'creator', 'editor']);

            // 2. Ambil `form_source` HTML dari template.
            $formSource = $pengkajian->form_template->form_source;
            $registration = $pengkajian->registration;

            // =========================================================================
            // 3. Ganti Placeholder Data Registrasi & Pasien
            // =========================================================================
            $data = [
                'no_rm' => $registration->patient->medical_record_number ?? '',
                'nama_pasien' => $registration->patient->name ?? '',
                'tgl_lahir_pasien' => Carbon::parse($registration->patient->date_of_birth)->format('d-m-Y') ?? '',
                'umur_pasien' => hitungUmur($registration->patient->date_of_birth) ?? '',
                'kelamin_pasien' => $registration->patient->gender ?? '',
                'alamat_pasien' => $registration->patient->address ?? '',
                'dpjp' => $registration->doctor->employee->fullname ?? '',
                'no_hp_pasien' => $registration->patient->mobile_phone_number ?? '',
                'nik_pasien' => $registration->patient->id_card ?? '',
                'tgl_sekarang' => $pengkajian->created_at->format('d-m-Y') ?? '',
                'pegawai' => auth()->user()?->employee?->fullname ?? '',
            ];

            // Ganti placeholder dengan regex agar lebih fleksibel
            foreach ($data as $key => $value) {
                $formSource = preg_replace('/\{\{\{?' . preg_quote($key) . '\}?\}\}/', htmlspecialchars($value), $formSource);
            }

            // 4. Ambil nilai yang tersimpan dan pastikan formatnya adalah array.
            $formValues = $pengkajian->form_values ?? [];
            if (is_string($formValues)) {
                $formValues = json_decode($formValues, true) ?? [];
            }
            $formValues = (array) $formValues;

            // =========================================================================
            // 5. Proses Tanda Tangan (Signature Pad) - LOGIKA ASLI TETAP SAMA
            // =========================================================================
            $signaturePadInitializers = []; // Variabel ini mungkin tidak digunakan jika Anda menggunakan popup, tapi biarkan saja.
            $formSource = preg_replace_callback('/\[SIGNATURE_PAD:(.*?)\]/', function ($matches) use ($formValues, $isEditMode) {
                $inputName = $matches[1];
                $initialData = $formValues[$inputName] ?? '';

                // JIKA DALAM MODE LIHAT (READ-ONLY)
                if (! $isEditMode) {
                    if (! empty($initialData)) {
                        return '
                        <div class="signature-view-wrapper text-center">
                            <div style="position: relative; width: 250px; height: 125px; margin: 0 auto; border-bottom: 1px solid #333;">
                                <img src="' . htmlspecialchars($initialData) . '"
                                     alt="Tanda Tangan"
                                     style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: contain;">
                            </div>
                        </div>
                    ';
                    } else {
                        return '
                        <div class="signature-view-wrapper text-center">
                            <div style="width: 250px; height: 80px; border-bottom: 1px solid #333; text-align: center; padding-top: 30px; color: #999; margin: 0 auto;">
                                <em>(Tidak ada tanda tangan)</em>
                            </div>
                        </div>
                    ';
                    }
                }
                // JIKA DALAM MODE EDIT
                else {
                    // Menggunakan komponen Blade untuk trigger popup
                    return view('components.signature-popup-trigger', [
                        'inputName' => $inputName,
                        'initialData' => $initialData,
                    ])->render();
                }
            }, $formSource);

            // =========================================================================
            // 5B. [LOGIKA BARU DITAMBAHKAN DI SINI] Proses Image Editor
            // =========================================================================
            $formSource = preg_replace_callback('/\[IMAGE_EDITOR:(.*?)\]/', function ($matches) use ($formValues, $isEditMode) {
                $inputName = $matches[1];
                $initialData = $formValues[$inputName] ?? ''; // base64 data gambar yang tersimpan
                $defaultImage = asset('images/audiogram-background.jpg'); // Pastikan path ini benar

                // JIKA DALAM MODE LIHAT (READ-ONLY)
                if (! $isEditMode) {
                    if (! empty($initialData)) {
                        // Tampilkan sebagai gambar statis
                        return '<img src="' . htmlspecialchars($initialData) . '" alt="Gambar ' . e($inputName) . '" style="width: 100%; height: auto; border: 1px solid #ddd; border-radius: .25rem;">';
                    } else {
                        // Tampilkan placeholder jika tidak ada gambar
                        return '<div style="width: 100%; min-height: 450px; border: 1px dashed #ccc; display: flex; align-items: center; justify-content: center; background-color: #f8f9fa; border-radius: .25rem;"><em class="text-muted">(Tidak ada gambar yang tersimpan)</em></div>';
                    }
                }
                // JIKA DALAM MODE EDIT
                else {
                    // Render komponen Blade 'image-editor' dengan data yang sudah ada
                    return view('components.image-editor', [
                        'inputName' => $inputName,
                        'initialData' => $initialData,
                        'defaultImage' => $defaultImage,
                    ])->render();
                }
            }, $formSource);

            // =========================================================================
            // 6. Isi Ulang Nilai Form (Rehidrasi) - LOGIKA ASLI TETAP SAMA
            // =========================================================================
            // Simpan hasil proses ke variabel global sementara untuk diakses di callback berikutnya
            $GLOBALS['formSource'] = $formSource;

            $formSource = preg_replace_callback('/<(input|textarea|select)([^>]*)name=["\']([^"\']+)["\']([^>]*)>/i', function ($matches) use ($formValues, $isEditMode) {
                $tag = strtolower($matches[1]);
                $beforeName = $matches[2];
                $name = $matches[3];
                $afterName = $matches[4];
                $originalTag = "<$tag" . $beforeName . "name='$name'" . $afterName . '>';

                $cleanName = str_replace('[]', '', $name);
                $value = $formValues[$cleanName] ?? null;

                if (! $isEditMode) {
                    if ($value === null || $value === '') {
                        // Untuk tag 'select', kita tidak ingin menampilkan '-', biarkan kosong
                        if ($tag === 'select') return '';
                        return '<span class="text-muted">-</span>';
                    }

                    $typeAttr = [];
                    preg_match('/type=["\']([^"\']+)["\']/i', $originalTag, $typeAttr);
                    $type = strtolower($typeAttr[1] ?? 'text');

                    if ($type === 'checkbox') {
                        $valAttr = [];
                        preg_match('/value=["\']([^"\']+)["\']/i', $originalTag, $valAttr);
                        $tagValue = $valAttr[1] ?? 'on';
                        $isChecked = is_array($value) ? in_array($tagValue, $value) : ($value == $tagValue);

                        if ($isChecked) {
                            // Coba cari label yang berasosiasi dengan checkbox ini
                            $labelForId = [];
                            preg_match('/id=["\']([^"\']+)["\']/i', $originalTag, $labelForId);
                            $labelText = $tagValue; // Fallback ke value
                            if (!empty($labelForId[1])) {
                                $labelRegex = '/<label[^>]*for=["\']' . preg_quote($labelForId[1], '/') . '["\'][^>]*>(.*?)<\/label>/is';
                                if (preg_match($labelRegex, $GLOBALS['formSource'], $labelMatch)) {
                                    $labelText = strip_tags($labelMatch[1]);
                                }
                            }
                            return '<p class="form-control-plaintext mb-0">☑ ' . htmlspecialchars($labelText) . '</p>';
                        }
                        return ''; // Jangan tampilkan apa-apa jika tidak di-check
                    }

                    if ($type === 'radio') {
                        $valAttr = [];
                        preg_match('/value=["\']([^"\']+)["\']/i', $originalTag, $valAttr);
                        $tagValue = $valAttr[1] ?? 'on';
                        if ($value == $tagValue) {
                            // Coba cari labelnya
                            $labelForId = [];
                            preg_match('/id=["\']([^"\']+)["\']/i', $originalTag, $labelForId);
                            $labelText = $tagValue;
                            if (!empty($labelForId[1])) {
                                $labelRegex = '/<label[^>]*for=["\']' . preg_quote($labelForId[1], '/') . '["\'][^>]*>(.*?)<\/label>/is';
                                if (preg_match($labelRegex, $GLOBALS['formSource'], $labelMatch)) {
                                    $labelText = strip_tags($labelMatch[1]);
                                }
                            }
                            return '<p class="form-control-plaintext mb-0">◉ ' . htmlspecialchars($labelText) . '</p>';
                        }
                        return '';
                    }

                    if ($tag === 'select') {
                        // Cari teks dari option yang terpilih
                        $optionRegex = '/<option[^>]*value=["\']' . preg_quote($value, '/') . '["\'][^>]*>(.*?)<\/option>/is';
                        if (preg_match($optionRegex, $originalTag, $optionMatch)) {
                            return htmlspecialchars(strip_tags($optionMatch[1]));
                        }
                        return htmlspecialchars($value);
                    }

                    $displayValue = is_array($value) ? implode(', ', $value) : $value;
                    return '<p class="form-control-plaintext">' . nl2br(htmlspecialchars($displayValue)) . '</p>';
                }

                // --- Jika dalam mode EDIT, isi atributnya ---
                if ($tag === 'textarea') {
                    return '<textarea' . $beforeName . "name='$name'" . $afterName . '>' . htmlspecialchars($value ?? '') . '</textarea>';
                }

                if ($tag === 'input') {
                    if (preg_match('/type=["\'](checkbox|radio)["\']/i', $originalTag)) {
                        $valAttr = [];
                        preg_match('/value=["\']([^"\']+)["\']/i', $originalTag, $valAttr);
                        $tagValue = $valAttr[1] ?? 'on';
                        $isChecked = is_array($value) ? in_array($tagValue, $value) : ($value == $tagValue);
                        if ($isChecked) {
                            return '<input' . $beforeName . "name='$name'" . $afterName . ' checked>';
                        }
                    } else {
                        return '<input' . $beforeName . "name='$name'" . $afterName . ' value="' . htmlspecialchars($value ?? '') . '">';
                    }
                }

                return $originalTag;
            }, $formSource);


            // [DISEMPURNAKAN] Isi ulang untuk <select>
            if ($isEditMode) {
                $formSource = preg_replace_callback('/<select([^>]*)name=["\']([^"\']+)["\'](.*?)<\/select>/is', function ($matches) use ($formValues) {
                    $selectTag = $matches[0];
                    $selectName = $matches[2];
                    $selectedValue = $formValues[$selectName] ?? null;

                    if ($selectedValue !== null) {
                        $selectTag = preg_replace_callback('/<option([^>]*)value=(["\'])(.*?)\2([^>]*)>/i', function ($optionMatches) use ($selectedValue) {
                            $optionValue = $optionMatches[3];
                            if ($optionValue == $selectedValue) {
                                return '<option' . $optionMatches[1] . 'value="' . $optionValue . '"' . $optionMatches[4] . ' selected>';
                            }
                            return $optionMatches[0];
                        }, $selectTag);
                    }
                    return $selectTag;
                }, $formSource);
            } else {
                $formSource = preg_replace('/<\/?select[^>]*>/i', '', $formSource);
                $formSource = preg_replace('/<\/?option[^>]*>/i', '', $formSource);
            }


            // Hapus variabel global
            unset($GLOBALS['formSource']);
            // =========================================================================
            // 7. Render View - LOGIKA ASLI TETAP SAMA
            // =========================================================================
            return view('pages.simrs.poliklinik.pengkajian_lanjutan.show', [
                'pengkajian' => $pengkajian,
                'processedFormHtml' => $formSource,
                'isEditMode' => $isEditMode,
                'signaturePadInitializers' => $signaturePadInitializers,
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal memuat form pengkajian lanjutan: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Terjadi kesalahan saat memuat data form. Silakan coba lagi nanti.');
        }
    }

    /**
     * Menampilkan rekap pasien per poliklinik dengan fitur filtering dan caching
     *
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function rekapPasienPerPoliklinik(Request $request)
    {
        try {
            // Handle AJAX requests for real-time updates
            if ($request->ajax()) {
                return $this->getDepartmentDataAjax($request);
            }

            $date = $request->get('date', Carbon::today()->format('Y-m-d'));
            $selectedDate = Carbon::parse($date);

            // Cache key untuk performa
            $cacheKey = "rekap_pasien_poliklinik_{$selectedDate->format('Y-m-d')}";

            // Ambil data dengan caching (cache selama 5 menit)
            $departements = Cache::remember($cacheKey, 300, function () use ($selectedDate) {
                return $this->getDepartmentData($selectedDate);
            });

            // Hitung statistik
            $statistics = $this->calculateStatistics($departements, $selectedDate);

            // Data untuk chart (opsional)
            $chartData = $this->prepareChartData($departements);

            return view('pages.simrs.poliklinik.rekap-pasien', compact(
                'departements',
                'statistics',
                'chartData',
                'selectedDate'
            ));
        } catch (\Exception $e) {
            Log::error('Error in rekapPasienPerPoliklinik: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat memuat data',
                ], 500);
            }

            return back()->with('error', 'Terjadi kesalahan saat memuat data rekap pasien');
        }
    }

    /**
     * Handle AJAX requests untuk data real-time
     *
     * @return \Illuminate\Http\JsonResponse
     */
    private function getDepartmentDataAjax(Request $request)
    {
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));
        $selectedDate = Carbon::parse($date);

        $departements = $this->getDepartmentData($selectedDate);
        $statistics = $this->calculateStatistics($departements, $selectedDate);

        return response()->json([
            'success' => true,
            'data' => $departements,
            'statistics' => $statistics,
        ]);
    }

    /**
     * Ambil data departemen dengan jumlah pasien
     *
     * @return \Illuminate\Support\Collection
     */
    private function getDepartmentData(Carbon $date)
    {
        // Daftar klinik yang akan ditampilkan
        $allowedClinics = [
            'KLINIK ANAK',
            'KLINIK BEDAH UMUM',
            'KLINIK GIGI',
            'KLINIK JANTUNG',
            'KLINIK JIWA',
            'KLINIK OBGYN',
            'KLINIK PARU',
            'KLINIK PENYAKIT DALAM',
            'KLINIK REHAB MEDIK',
            'KLINIK THT',
        ];

        return Departement::select([
            'departements.id',
            'departements.name',
            DB::raw('COUNT(registrations.id) as jumlah_pasien'),
            DB::raw('COUNT(CASE WHEN registrations.registration_type = "igd" THEN 1 END) as jumlah_igd'),
            DB::raw('COUNT(CASE WHEN registrations.registration_type = "ralan" THEN 1 END) as jumlah_ralan'),
        ])
            ->leftJoin('registrations', function ($join) use ($date) {
                $join->on('departements.id', '=', 'registrations.departement_id')
                    ->whereDate('registrations.registration_date', $date)
                    ->whereNull('registrations.deleted_at'); // Pastikan tidak include soft deleted
            })
            ->whereIn('departements.name', $allowedClinics) // Filter hanya klinik yang diizinkan
            ->groupBy('departements.id', 'departements.name')
            ->orderByRaw("FIELD(departements.name, '" . implode("','", $allowedClinics) . "')") // Urutkan sesuai urutan di array
            ->get()
            ->map(function ($departement) {
                // Hitung persentase aktif
                $totalPasien = $departement->jumlah_pasien;
                $isActive = $totalPasien > 0;

                return [
                    'id' => $departement->id,
                    'name' => $departement->name,
                    'jumlah_pasien' => $totalPasien,
                    'jumlah_igd' => $departement->jumlah_igd ?? 0,
                    'jumlah_ralan' => $departement->jumlah_ralan ?? 0,
                    'is_active' => $isActive,
                    'status' => $isActive ? 'Aktif' : 'Tidak Aktif',
                    'badge_color' => $isActive ? 'primary' : 'secondary',
                    'card_border' => $isActive ? 'border-primary' : 'border-secondary',
                ];
            });
    }

    /**
     * Hitung statistik keseluruhan
     *
     * @param  \Illuminate\Support\Collection  $departements
     * @return array
     */
    private function calculateStatistics($departements, Carbon $date)
    {
        $totalPoliklinik = $departements->count();
        $totalPasien = $departements->sum('jumlah_pasien');
        $poliklinikAktif = $departements->where('is_active', true)->count();
        $poliklinikTidakAktif = $totalPoliklinik - $poliklinikAktif;

        // Hitung rata-rata pasien per poliklinik aktif
        $rataRataPasien = $poliklinikAktif > 0 ? round($totalPasien / $poliklinikAktif, 1) : 0;

        // Cari poliklinik dengan pasien terbanyak
        $poliklinikTerbanyak = $departements->sortByDesc('jumlah_pasien')->first();

        return [
            'total_poliklinik' => $totalPoliklinik,
            'total_pasien' => $totalPasien,
            'poliklinik_aktif' => $poliklinikAktif,
            'poliklinik_tidak_aktif' => $poliklinikTidakAktif,
            'rata_rata_pasien' => $rataRataPasien,
            'tanggal' => $date->format('d/m/Y'),
            'hari' => $date->locale('id')->dayName,
            'poliklinik_terbanyak' => $poliklinikTerbanyak ? $poliklinikTerbanyak['name'] : '-',
            'max_pasien' => $poliklinikTerbanyak ? $poliklinikTerbanyak['jumlah_pasien'] : 0,
        ];
    }

    /**
     * Siapkan data untuk chart (opsional)
     *
     * @param  \Illuminate\Support\Collection  $departements
     * @return array
     */
    private function prepareChartData($departements)
    {
        $activeDepartments = $departements->where('is_active', true)->take(10); // Top 10

        return [
            'labels' => $activeDepartments->pluck('name')->toArray(),
            'data' => $activeDepartments->pluck('jumlah_pasien')->toArray(),
        ];
    }

    /**
     * Get patient details for a specific department
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPatientDetails(Request $request)
    {
        try {
            $departementId = $request->get('departement_id');
            $date = $request->get('date', Carbon::today()->format('Y-m-d'));
            $selectedDate = Carbon::parse($date);

            $patients = Registration::select([
                'registrations.id',
                'registrations.registration_number',
                'registrations.registration_date',
                'registrations.created_at',
                'patients.medical_record_number',
                'patients.name as patient_name',
                'patients.date_of_birth',
                'patients.gender',
                'patients.address',
                'doctors.employee_id',
                'employees.fullname as doctor_name',
                'departements.name as department_name',
            ])
                ->join('patients', 'registrations.patient_id', '=', 'patients.id')
                ->leftJoin('doctors', 'registrations.doctor_id', '=', 'doctors.id')
                ->leftJoin('employees', 'doctors.employee_id', '=', 'employees.id')
                ->join('departements', 'registrations.departement_id', '=', 'departements.id')
                ->where('registrations.departement_id', $departementId)
                ->whereDate('registrations.registration_date', $selectedDate)
                ->whereNull('registrations.deleted_at')
                ->orderBy('registrations.created_at', 'asc')
                ->get()
                ->map(function ($registration) {
                    return [
                        'id' => $registration->id,
                        'registration_number' => $registration->registration_number,
                        'registration_date' => tglDefault($registration->registration_date),
                        'registration_time' => $registration->created_at?->format('H:i:s'),
                        'medical_record_number' => $registration->medical_record_number,
                        'patient_name' => $registration->patient_name,
                        'date_of_birth' => $registration->date_of_birth,
                        'age' => hitungUmur($registration->date_of_birth),
                        'gender' => $registration->gender,
                        'address' => $registration->address,
                        'doctor_name' => $registration->doctor_name,
                        'department_name' => $registration->department_name,
                    ];
                });

            $department = Departement::find($departementId);

            return response()->json([
                'success' => true,
                'data' => [
                    'department' => $department ? $department->name : 'Unknown',
                    'patients' => $patients,
                    'total_patients' => $patients->count(),
                    'date' => $selectedDate->format('d/m/Y'),
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting patient details: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data pasien',
            ], 500);
        }
    }

    /**
     * Refresh data untuk AJAX calls
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refreshData(Request $request)
    {
        try {
            $date = $request->get('date', Carbon::today()->format('Y-m-d'));
            $selectedDate = Carbon::parse($date);

            // Clear cache untuk memastikan data terbaru
            $cacheKey = "rekap_pasien_poliklinik_{$selectedDate->format('Y-m-d')}";
            Cache::forget($cacheKey);

            $departements = $this->getDepartmentData($selectedDate);
            $statistics = $this->calculateStatistics($departements, $selectedDate);

            return response()->json([
                'success' => true,
                'data' => $departements,
                'statistics' => $statistics,
                'message' => 'Data berhasil diperbarui',
            ]);
        } catch (\Exception $e) {
            Log::error('Error refreshing data: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui data',
            ], 500);
        }
    }

    /**
     * Export data rekap pasien ke Excel/PDF
     *
     * @return \Illuminate\Http\Response
     */
    public function exportRekap(Request $request)
    {
        try {
            $date = $request->get('date', Carbon::today()->format('Y-m-d'));
            $format = $request->get('format', 'pdf'); // pdf or excel
            $selectedDate = Carbon::parse($date);

            $departements = $this->getDepartmentData($selectedDate);
            $statistics = $this->calculateStatistics($departements, $selectedDate);

            // Implementation for export would go here
            // For now, return JSON response
            return response()->json([
                'success' => true,
                'message' => 'Export functionality - to be implemented',
                'data' => [
                    'departements' => $departements,
                    'statistics' => $statistics,
                    'date' => $selectedDate->format('d/m/Y'),
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Error exporting data: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengekspor data',
            ], 500);
        }
    }

    /**
     * Handle patient monitoring search and display results
     *
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function searchMonitoring(Request $request)
    {
        try {
            // Get search parameters
            $tawal = $request->get('tawal', Carbon::today()->format('d-m-Y'));
            $takhir = $request->get('takhir', Carbon::today()->format('d-m-Y'));
            $pid = $request->get('pid');
            $name_real = $request->get('name_real');
            $tipe_rawat = $request->get('tipe_rawat');
            $is_discharged = $request->get('is_discharged');
            $did = $request->get('did');
            $nama_ibu = $request->get('nama_ibu');
            $date_of_birth = $request->get('date_of_birth');
            $address = $request->get('address');

            $s_view = $request->get('s_view');
            $xls = $request->get('xls');

            // Handle Excel export
            if ($xls == 'yes') {
                return $this->exportMonitoringToExcel($request);
            }

            // Parse dates
            $startDate = Carbon::createFromFormat('d-m-Y', $tawal)->startOfDay();
            $endDate = Carbon::createFromFormat('d-m-Y', $takhir)->endOfDay();

            // Build query for patient search
            $query = Registration::select([
                'registrations.id',
                'registrations.registration_number',
                'registrations.registration_date',
                'registrations.created_at',
                'registrations.registration_type',
                'registrations.registration_close_date',
                'patients.medical_record_number',
                'patients.name as patient_name',
                'patients.date_of_birth',
                'patients.gender',
                'patients.address',
                'doctors.employee_id',
                'employees.fullname as doctor_name',
                'departements.name as department_name',
            ])
                ->join('patients', 'registrations.patient_id', '=', 'patients.id')
                ->leftJoin('doctors', 'registrations.doctor_id', '=', 'doctors.id')
                ->leftJoin('employees', 'doctors.employee_id', '=', 'employees.id')
                ->join('departements', 'registrations.departement_id', '=', 'departements.id')
                ->whereBetween('registrations.registration_date', [$startDate, $endDate])
                ->whereNull('registrations.deleted_at');

            // Apply filters
            if (! empty($pid)) {
                $query->where('patients.medical_record_number', 'like', '%' . $pid . '%');
            }

            if (! empty($name_real)) {
                $query->where('patients.name', 'like', '%' . $name_real . '%');
            }

            if (! empty($tipe_rawat)) {
                $query->where('registrations.registration_type', $tipe_rawat);
            }

            if ($is_discharged !== '' && $is_discharged !== null) {
                if ($is_discharged === 't') {
                    // Closed registrations have a registration_close_date
                    $query->whereNotNull('registrations.registration_close_date');
                } else {
                    // Active registrations don't have a registration_close_date
                    $query->whereNull('registrations.registration_close_date');
                }
            }

            if (! empty($did)) {
                $query->where('registrations.departement_id', $did);
            }

            if (! empty($date_of_birth)) {
                $birthDate = Carbon::createFromFormat('d-m-Y', $date_of_birth)->format('Y-m-d');
                $query->where('patients.date_of_birth', $birthDate);
            }

            if (! empty($address)) {
                $query->where('patients.address', 'like', '%' . $address . '%');
            }

            // Order by registration date and time
            $query->orderBy('registrations.registration_date', 'desc')
                ->orderBy('registrations.created_at', 'desc');

            // Get results
            $patients = $query->get();

            // Transform data for display
            $patients = $patients->map(function ($registration) {
                // Determine status based on registration_close_date
                $isClosed = !is_null($registration->registration_close_date);
                $statusText = $isClosed ? 'Tutup Kunjungan' : 'Registrasi Aktif';
                $statusColor = $isClosed ? 'danger' : 'success';

                return [
                    'id' => $registration->id,
                    'registration_number' => $registration->registration_number,
                    'registration_date' => tglDefault($registration->registration_date),
                    'registration_time' => $registration->created_at ? $registration->created_at->format('H:i:s') : '-',
                    'registration_type' => $this->getRegistrationTypeText($registration->registration_type),
                    'medical_record_number' => $registration->medical_record_number,
                    'patient_name' => $registration->patient_name,
                    'date_of_birth' => $registration->date_of_birth ? tglDefault($registration->date_of_birth) : '-',
                    'age' => $registration->date_of_birth ? hitungUmur($registration->date_of_birth) : '-',
                    'gender' => $registration->gender === 'L' ? 'Laki-laki' : ($registration->gender === 'P' ? 'Perempuan' : '-'),
                    'address' => $registration->address ?: '-',
                    'doctor_name' => $registration->doctor_name ?: '-',
                    'department_name' => $registration->department_name,
                    'status' => $statusText,
                    'status_color' => $statusColor,
                ];
            });

            // Prepare search parameters for display
            $searchParams = [
                'tawal' => $tawal,
                'takhir' => $takhir,
                'pid' => $pid,
                'name_real' => $name_real,
                'tipe_rawat' => $tipe_rawat,
                'is_discharged' => $is_discharged,
                'did' => $did,
                'nama_ibu' => $nama_ibu,
                'date_of_birth' => $date_of_birth,
                'address' => $address,
            ];

            // Return view with results
            return view('pages.simrs.poliklinik.patient-monitoring-results', compact(
                'patients',
                'searchParams'
            ));
        } catch (\Exception $e) {
            Log::error('Error in searchMonitoring: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);

            return back()->with('error', 'Terjadi kesalahan saat mencari data pasien: ' . $e->getMessage());
        }
    }

    /**
     * Get human-readable registration type text
     *
     * @param  string  $type
     * @return string
     */
    private function getRegistrationTypeText($type)
    {
        $types = [
            'ralan' => 'Rawat Jalan',
            'ranap' => 'Rawat Inap',
            'igd' => 'IGD',
        ];

        return $types[$type] ?? ucfirst($type);
    }

    /**
     * Export monitoring results to Excel
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    private function exportMonitoringToExcel(Request $request)
    {
        try {
            // Reuse the search logic
            $tawal = $request->get('tawal', Carbon::today()->format('d-m-Y'));
            $takhir = $request->get('takhir', Carbon::today()->format('d-m-Y'));
            $pid = $request->get('pid');
            $name_real = $request->get('name_real');
            $tipe_rawat = $request->get('tipe_rawat');
            $is_discharged = $request->get('is_discharged');
            $did = $request->get('did');
            $nama_ibu = $request->get('nama_ibu');
            $date_of_birth = $request->get('date_of_birth');
            $address = $request->get('address');

            // Parse dates
            $startDate = Carbon::createFromFormat('d-m-Y', $tawal)->startOfDay();
            $endDate = Carbon::createFromFormat('d-m-Y', $takhir)->endOfDay();

            // Build query for patient search
            $query = Registration::select([
                'registrations.id',
                'registrations.registration_number',
                'registrations.registration_date',
                'registrations.created_at',
                'registrations.registration_type',
                'registrations.registration_close_date',
                'patients.medical_record_number',
                'patients.name as patient_name',
                'patients.date_of_birth',
                'patients.gender',
                'patients.address',
                'doctors.employee_id',
                'employees.fullname as doctor_name',
                'departements.name as department_name',
            ])
                ->join('patients', 'registrations.patient_id', '=', 'patients.id')
                ->leftJoin('doctors', 'registrations.doctor_id', '=', 'doctors.id')
                ->leftJoin('employees', 'doctors.employee_id', '=', 'employees.id')
                ->join('departements', 'registrations.departement_id', '=', 'departements.id')
                ->whereBetween('registrations.registration_date', [$startDate, $endDate])
                ->whereNull('registrations.deleted_at');

            // Apply filters (same logic as search)
            if (! empty($pid)) {
                $query->where('patients.medical_record_number', 'like', '%' . $pid . '%');
            }
            if (! empty($name_real)) {
                $query->where('patients.name', 'like', '%' . $name_real . '%');
            }
            if (! empty($tipe_rawat)) {
                $query->where('registrations.registration_type', $tipe_rawat);
            }
            if ($is_discharged !== '' && $is_discharged !== null) {
                if ($is_discharged === 't') {
                    // Closed registrations have a registration_close_date
                    $query->whereNotNull('registrations.registration_close_date');
                } else {
                    // Active registrations don't have a registration_close_date
                    $query->whereNull('registrations.registration_close_date');
                }
            }
            if (! empty($did)) {
                $query->where('registrations.departement_id', $did);
            }
            if (! empty($date_of_birth)) {
                $birthDate = Carbon::createFromFormat('d-m-Y', $date_of_birth)->format('Y-m-d');
                $query->where('patients.date_of_birth', $birthDate);
            }
            if (! empty($address)) {
                $query->where('patients.address', 'like', '%' . $address . '%');
            }

            $query->orderBy('registrations.registration_date', 'desc')
                ->orderBy('registrations.created_at', 'desc');

            $patients = $query->get();

            // Transform data for Excel export
            $exportData = $patients->map(function ($registration) {
                // Determine status based on registration_close_date
                $isClosed = !is_null($registration->registration_close_date);
                $statusText = $isClosed ? 'Tutup Kunjungan' : 'Registrasi Aktif';

                return [
                    'No. Registrasi' => $registration->registration_number,
                    'Tgl. Registrasi' => tglDefault($registration->registration_date),
                    'Jam Registrasi' => $registration->created_at ? $registration->created_at->format('H:i:s') : '-',
                    'No. RM' => $registration->medical_record_number,
                    'Nama Pasien' => $registration->patient_name,
                    'Tgl. Lahir' => $registration->date_of_birth ? tglDefault($registration->date_of_birth) : '-',
                    'Umur' => $registration->date_of_birth ? hitungUmur($registration->date_of_birth) : '-',
                    'Jenis Kelamin' => $registration->gender === 'L' ? 'Laki-laki' : ($registration->gender === 'P' ? 'Perempuan' : '-'),
                    'Alamat' => $registration->address ?: '-',
                    'Tipe Rawat' => $this->getRegistrationTypeText($registration->registration_type),
                    'Poliklinik' => $registration->department_name,
                    'Dokter' => $registration->doctor_name ?: '-',
                    'Status' => $statusText,
                ];
            });

            // Create filename with date range
            $filename = 'monitoring-pasien-' . $tawal . '-sd-' . $takhir . '.xlsx';

            // Export to Excel (simplified - you might want to use Laravel Excel package)
            return response()->json([
                'success' => true,
                'message' => 'Export berhasil',
                'data' => $exportData->toArray(),
                'filename' => $filename,
            ]);
        } catch (\Exception $e) {
            Log::error('Error exporting monitoring data: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengekspor data',
            ], 500);
        }
    }
}
