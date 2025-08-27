<?php

// File: app/Http/Controllers/SIMRS/Poliklinik/PoliklinikController.php

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
                $query = Registration::whereDate('registration_date', Carbon::today())->where('registration_type', '!=',  'igd');
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
                'html' => $html
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function showForm(Request $request, $registrationId, $encryptedID)
    {
        try {
            $id = base64_decode($encryptedID);
            $formTemplateModel = FormTemplate::find($id);
            $registration = Registration::find($registrationId);
            $formSource = $formTemplateModel->form_source;

            $data = [
                'no_rm' => $registration->patient->medical_record_number ?? '',
                'nama_pasien' => $registration->patient->name ?? '',
                'tgl_lahir_pasien' => Carbon::parse($registration->patient->date_of_birth)->format('d-m-Y') ?? '',
                'umur_pasien' => Carbon::parse($registration->patient->date_of_birth)->diffInYears(Carbon::now()) ?? '',
                'kelamin_pasien' => $registration->patient->gender ?? '',
                'alamat_pasien' => $registration->patient->address ?? '',
                'dpjp' => $registration->doctor->employee->fullname ?? '',
                'no_hp_pasien' => $registration->patient->mobile_phone_number ?? '',
                'tgl_sekarang' => Carbon::now()->format('d-m-Y') ?? '',
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
                    'initialData' => '' // Kosong karena ini form baru
                ])->render();
            }, $formSource);

            return view('pages.simrs.poliklinik.pengkajian_lanjutan.show_form', [
                'formTemplate'   => $formSource,
                'formTemplateId' => $id,
                'registrationId' => $registrationId
            ]);
        } catch (Exception $e) {
            Log::error('Gagal menampilkan form: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(404, 'Data template form atau registrasi tidak ditemukan.');
        }
    }


    // ==========================================================
    // METHOD BARU UNTUK MELIHAT DAN MENGEDIT FORM YANG SUDAH DIISI
    // ==========================================================

    /**
     * Menampilkan halaman form yang sudah diisi dalam mode LIHAT/CETAK (read-only).
     * Method ini dipanggil oleh route `poliklinik.pengkajian-lanjutan.show`.
     *
     * @param PengkajianLanjutan $pengkajianLanjutan Instance model dari Route Model Binding.
     * @return View
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
     * @param PengkajianLanjutan $pengkajianLanjutan Instance model dari Route Model Binding.
     * @return \Illuminate\Http\RedirectResponse|View
     */
    public function editFilledForm(PengkajianLanjutan $pengkajianLanjutan)
    {
        // Aturan Bisnis: Jangan izinkan edit jika form sudah difinalisasi.
        if ($pengkajianLanjutan->is_final) {
            // Redirect kembali ke halaman sebelumnya dengan pesan error.
            return back()->with('error', 'Form yang sudah difinalisasi tidak dapat diubah lagi.');
        }

        // Panggil helper method dengan flag isEditMode = true
        return $this->prepareAndShowForm($pengkajianLanjutan, true);
    }

    /**
     * Private helper method untuk menghindari duplikasi kode.
     * Method ini menyiapkan semua data yang dibutuhkan dan merender view.
     *
     * @param PengkajianLanjutan $pengkajian
     * @param boolean $isEditMode
     * @return View
     */
    /**
     * Private helper method untuk menghindari duplikasi kode.
     *
     * @param PengkajianLanjutan $pengkajian
     * @param boolean $isEditMode
     * @return \Illuminate\View\View
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
            // 3. [BARU & PENTING] Ganti Placeholder Data Registrasi & Pasien
            // =========================================================================
            $data = [
                'no_rm' => $registration->patient->medical_record_number ?? '',
                'nama_pasien' => $registration->patient->name ?? '',
                'tgl_lahir_pasien' => Carbon::parse($registration->patient->date_of_birth)->format('d-m-Y') ?? '',
                'umur_pasien' => Carbon::parse($registration->patient->date_of_birth)->diffInYears(Carbon::now()) ?? '',
                'kelamin_pasien' => $registration->patient->gender ?? '',
                'alamat_pasien' => $registration->patient->address ?? '',
                'dpjp' => $registration->doctor->employee->fullname ?? '',
                'no_hp_pasien' => $registration->patient->mobile_phone_number ?? '',
                'tgl_sekarang' => $pengkajian->created_at->format('d-m-Y') ?? '',
            ];

            // [DIPERBAIKI] Ganti placeholder dengan regex agar lebih fleksibel
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
            // 5. Proses Tanda Tangan (Signature Pad)
            // =========================================================================
            $signaturePadInitializers = [];
            $formSource = preg_replace_callback('/\[SIGNATURE_PAD:(.*?)\]/', function ($matches) use ($formValues, $isEditMode) {
                $inputName = $matches[1];
                $initialData = $formValues[$inputName] ?? '';

                // --- LOGIKA BARU: Tentukan output berdasarkan isEditMode ---

                // JIKA DALAM MODE LIHAT (READ-ONLY)
                if (!$isEditMode) {
                    if (!empty($initialData)) {
                        // Kembalikan tag <img> dengan styling yang benar untuk menjaga rasio aspek.
                        // Dibungkus dalam div agar alignment-nya konsisten.
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
                        // Kembalikan placeholder jika tidak ada tanda tangan
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
                    $inputId = 'signature-input-' . $inputName;
                    $previewId = 'signature-preview-' . $inputName;

                    $imgDisplay = !empty($initialData) ? 'block' : 'none';
                    $placeholderDisplay = empty($initialData) ? 'block' : 'none';

                    // Kembalikan HTML untuk trigger popup
                    return '
                        <div class="signature-wrapper text-center">
                            <div class="signature-preview-container border rounded mb-2" style="min-height: 120px; display: flex; align-items: center; justify-content: center; position: relative;">
                                <img id="' . $previewId . '"
                                     src="' . htmlspecialchars($initialData) . '"
                                     alt="Pratinjau Tanda Tangan"
                                     style="max-width: 100%; height: auto; display: ' . $imgDisplay . ';">

                                <span class="text-muted placeholder-text" style="display: ' . $placeholderDisplay . ';">Belum ada tanda tangan</span>
                            </div>

                            <button type="button" class="btn btn-outline-primary btn-sm open-signature-popup"
                                    data-input-target="' . $inputId . '"
                                    data-preview-target="' . $previewId . '">
                                <i class="fas fa-pen-alt"></i> Bubuhkan Tanda Tangan
                            </button>

                            <input type="hidden" name="' . $inputName . '" id="' . $inputId . '" value="' . htmlspecialchars($initialData) . '">
                        </div>
                    ';
                }
            }, $formSource);


            // =========================================================================
            // 6. Isi Ulang Nilai Form (Rehidrasi)
            // =========================================================================
            $formSource = preg_replace_callback('/<(input|textarea|select)([^>]*)name=["\']([^"\']+)["\']([^>]*)>/i', function ($matches) use ($formValues, $isEditMode) {
                $tag          = strtolower($matches[1]);
                $beforeName   = $matches[2];
                $name         = $matches[3];
                $afterName    = $matches[4];
                $originalTag  = "<$tag" . $beforeName . "name='$name'" . $afterName . ">";

                $cleanName = str_replace('[]', '', $name);
                $value = $formValues[$cleanName] ?? null;

                // --- Jika dalam mode LIHAT, ubah semua elemen menjadi teks biasa ---
                if (!$isEditMode) {
                    // Jika tidak ada data, tampilkan strip
                    if ($value === null || $value === '') return '<span class="text-muted">-</span>';

                    $typeAttr = [];
                    preg_match('/type=["\']([^"\']+)["\']/i', $originalTag, $typeAttr);
                    $type = strtolower($typeAttr[1] ?? 'text');

                    // Untuk checkbox, tampilkan hanya jika dicentang
                    if ($type === 'checkbox') {
                        $valAttr = [];
                        preg_match('/value=["\']([^"\']+)["\']/i', $originalTag, $valAttr);
                        $tagValue = $valAttr[1] ?? 'on';

                        $isChecked = is_array($value) ? in_array($tagValue, $value) : ($value == $tagValue);

                        // Kita cari labelnya
                        $labelRegex = '/<label[^>]*for=["\']' . preg_quote($matches[0], '/') . '[^>]*>(.*?)<\/label>/is';
                        $labelForId = [];
                        preg_match('/id=["\']([^"\']+)["\']/i', $originalTag, $labelForId);
                        if (!empty($labelForId)) {
                            $labelRegex = '/<label[^>]*for=["\']' . $labelForId[1] . '["\'][^>]*>(.*?)<\/label>/is';
                            preg_match($labelRegex, $GLOBALS['formSource'] ?? '', $labelMatch);
                        }

                        $labelText = $labelMatch[1] ?? 'Setuju';

                        return $isChecked ? '<p class="form-control-plaintext mb-0">☑ ' . strip_tags($labelText) . '</p>' : '';
                    }

                    // Untuk radio, tampilkan labelnya jika terpilih
                    if ($type === 'radio') {
                        // Implementasi jika diperlukan, mirip checkbox
                    }

                    // Untuk elemen lain, tampilkan sebagai teks biasa
                    $displayValue = is_array($value) ? implode(', ', $value) : $value;
                    return nl2br(htmlspecialchars($displayValue));
                }

                // --- Jika dalam mode EDIT, isi atributnya (Kode ini sudah benar) ---
                if ($tag === 'textarea') {
                    return "<textarea" . $beforeName . "name='$name'" . $afterName . ">" . htmlspecialchars($value ?? '') . "</textarea>";
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

            // Simpan hasil proses ke variabel global sementara untuk diakses di callback berikutnya
            $GLOBALS['formSource'] = $formSource;

            // [DISEMPURNAKAN] Isi ulang untuk <select>
            if ($isEditMode) {
                $formSource = preg_replace_callback('/<option([^>]*)value=(["\'])(.*?)\2([^>]*)>/i', function ($matches) use ($formValues) {
                    $optionValue = $matches[3];
                    foreach ($formValues as $key => $savedValue) {
                        if ($savedValue == $optionValue) {
                            return '<option' . $matches[1] . 'value="' . $optionValue . '"' . $matches[4] . ' selected>';
                        }
                    }
                    return $matches[0];
                }, $formSource);
            } else {
                // Jika mode lihat, hapus tag select dan option, karena valuenya sudah ditampilkan
                $formSource = preg_replace('/<\/?select[^>]*>/i', '', $formSource);
                $formSource = preg_replace('/<\/?option[^>]*>/i', '', $formSource);
            }

            // Hapus variabel global
            unset($GLOBALS['formSource']);

            // =========================================================================
            // 7. Render View
            // =========================================================================
            return view('pages.simrs.poliklinik.pengkajian_lanjutan.show', [
                'pengkajian'               => $pengkajian,
                'processedFormHtml'        => $formSource,
                'isEditMode'               => $isEditMode,
                'signaturePadInitializers' => $signaturePadInitializers,
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal memuat form pengkajian lanjutan: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            abort(500, 'Terjadi kesalahan saat memuat data form. Silakan coba lagi nanti.');
        }
    }
}
