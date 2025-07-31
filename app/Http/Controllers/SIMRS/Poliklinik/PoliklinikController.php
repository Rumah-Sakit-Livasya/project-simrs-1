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

        $query->when(isset($registration->departement_id), function ($q) use ($registration) {
            return $q->where('departement_id', $registration->departement_id);
        });

        $query->when(isset($registration->doctor_id), function ($q) use ($registration) {
            return $q->where('doctor_id', $registration->doctor_id);
        });

        $registrations = $query->get();

        if ($menu && $noRegist) {
            $menuResponse = ERMController::poliklinikMenu($noRegist, $menu, $departements, $jadwal_dokter, $registration, $registrations, $path);
            if ($menuResponse) {
                return $menuResponse;
            }
        } else {
            return view('pages.simrs.poliklinik.index', compact('departements', 'jadwal_dokter', 'registration', 'registrations', 'path'));
        }
    }

    public function showForm(Request $request, $registrationId, $encryptedID)
    {
        try {
            // Dekripsi ID
            $id = base64_decode($encryptedID);

            // Ambil data berdasarkan ID
            $formTemplate = FormTemplate::findOrFail($id)->form_source;
            $registration = Registration::findOrFail($registrationId);

            // Data pasien
            $data = [
                'no_rm' => $registration->patient->medical_record_number ?? '',
                'nama_pasien' => $registration->patient->name ?? '',
                'tgl_lahir_pasien' => Carbon::parse($registration->patient->date_of_birth)->format('Y-m-d') ?? '',
                'umur_pasien' => Carbon::parse($registration->patient->date_of_birth)->diffInYears(Carbon::now()) ?? '',
                'kelamin_pasien' => $registration->patient->gender ?? '',
                'alamat_pasien' => $registration->patient->address ?? '',
                'dpjp' => $registration->doctor->employee->fullname ?? '',
                'no_hp_pasien' => $registration->patient->mobile_phone_number ?? '',
                'tgl_sekarang' => Carbon::now()->format('Y-m-d') ?? '',
            ];

            // Replace placeholder di formTemplate dengan data pasien
            foreach ($data as $key => $value) {
                $formTemplate = str_replace("{{$key}}", $value, $formTemplate);
            }
            $formTemplateId = $id;

            // Pastikan view yang dituju benar
            // return view('pages.simrs.poliklinik.pengkajian_lanjutan.show_form', compact('formTemplate', 'formTemplateId', 'registrationId'));
            return view('pages.simrs.poliklinik.pengkajian_lanjutan.show_form', [
                'formTemplate'     => $formTemplate->form_source, // Kirim source HTML
                'formTemplateId'   => $id, // Kirim ID template
                'registrationId'   => $registrationId // Kirim ID registrasi
            ]);
        } catch (Exception $e) {
            Log::error('Gagal menampilkan form baru: ' . $e->getMessage());
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
    private function prepareAndShowForm(PengkajianLanjutan $pengkajian, bool $isEditMode): \Illuminate\View\View
    {
        try {
            // 1. Eager Load relasi untuk mencegah N+1 Query Problem.
            $pengkajian->load(['form_template', 'registration.patient', 'creator', 'editor']);

            // 2. [DIPERBAIKI] Logika aman untuk menangani 'form_values'.
            // Variabel ini akan menampung hasil akhir.
            $formValues = [];

            // Ambil data mentah dari model.
            $sourceData = $pengkajian->form_values;

            // Cek tipe datanya.
            if (is_string($sourceData)) {
                // Jika masih berupa string JSON, maka kita decode.
                $formValues = json_decode($sourceData, true) ?? [];
            } elseif (is_array($sourceData)) {
                // Jika sudah berupa array (karena casting model berhasil), langsung gunakan.
                $formValues = $sourceData;
            }
            // Jika null atau tipe lain, $formValues akan tetap menjadi array kosong, sehingga aman.

            // 3. Render view yang BENAR untuk menampilkan/mengedit form yang sudah diisi.
            return view('pages.simrs.poliklinik.pengkajian_lanjutan.show', [ // <-- DIUBAH DI SINI
                'pengkajian'    => $pengkajian,
                'formTemplate'  => $pengkajian->form_template,
                'formValues'    => $formValues,
                'isEditMode'    => $isEditMode,
            ]);
        } catch (\Exception $e) {
            // Tangani error jika terjadi masalah
            \Illuminate\Support\Facades\Log::error('Gagal memuat form pengkajian lanjutan: ' . $e->getMessage());
            abort(500, 'Terjadi kesalahan saat memuat data form. Silakan coba lagi nanti.');
        }
    }
}
