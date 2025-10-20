{{-- resources/views/pages/simrs/pendaftaran/form/registrasi-igd-form.blade.php (Refactored Version) --}}

@php
    // Definisikan semua data statis dan dinamis di satu tempat agar mudah dikelola.
    // ->all() mengubah koleksi menjadi array biasa, yang dibutuhkan oleh komponen.
    $doctorOptions = $doctors->pluck('employee.fullname', 'id')->all();
    $penjaminOptions = $penjamins->pluck('nama_perusahaan', 'id')->all();

    $igdTypeOptions = [
        'darurat' => 'Darurat',
        'darurat-tidak-gawat' => 'Darurat Tidak Gawat',
        'gawat-darurat' => 'Gawat Darurat',
        'gawat-tidak-darurat' => 'Gawat Tidak Darurat',
        'tidak-gawat-tidak-darurat' => 'Tidak Gawat Tidak Darurat',
    ];

    $pelayananOptions = [
        'kebidanan' => 'Kebidanan',
        'bedah' => 'Bedah',
        'non-bedah' => 'Non Bedah',
        'psikiatrik' => 'Psikiatrik',
        'anak' => 'Anak',
    ];

    $rujukanOptions = [
        'inisiatif pribadi' => 'Inisiatif Pribadi',
        'dalam rs' => 'Dalam RS',
        'luar rs' => 'Luar RS',
        'rujukan bpjs' => 'Rujukan BPJS',
    ];
@endphp

{{-- ID 'form-registrasi' ditambahkan untuk ditargetkan oleh JavaScript AJAX --}}
<form id="form-registrasi" data-action-url="{{ route('simpan.registrasi') }}">
    @csrf
    {{-- Hidden Inputs --}}
    <input type="hidden" name="patient_id" value="{{ $patient->id }}">
    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
    <input type="hidden" name="employee_id" value="{{ auth()->user()->employee->id }}">
    <input type="hidden" name="registration_type" value="igd">
    <input type="hidden" name="poliklinik" value="UGD"> {{-- Nilai tetap untuk IGD --}}

    {{-- Notifikasi untuk pesan sukses/gagal umum dari AJAX --}}
    <div id="form-notification" class="alert d-none" role="alert"></div>

    <div class="row">
        {{-- Kolom Kiri --}}
        <div class="col-xl-6">
            <x-form-row label="Tanggal Registrasi" for="registration_date">
                <input type="text" class="form-control form-control-dashed" id="registration_date"
                    name="registration_date" readonly value="{{ old('registration_date', $today) }}">
            </x-form-row>

            <x-form-row label="Dokter" for="doctor_id">
                <x-form-select id="doctor_id" name="doctor_id" :options="$doctorOptions" />
            </x-form-row>

            <x-form-row label="Tipe *" for="igd_type">
                <x-form-select id="igd_type" name="igd_type" :options="$igdTypeOptions" />
            </x-form-row>

            <x-form-row label="Penjamin" for="penjamin_id">
                <x-form-select id="penjamin_id" name="penjamin_id" :options="$penjaminOptions" />
            </x-form-row>
        </div>

        {{-- Kolom Kanan --}}
        <div class="col-xl-6">
            <x-form-row label="Kartu Pasien" for="patient_card">
                <div class="custom-control custom-checkbox pt-2">
                    <input type="checkbox" class="custom-control-input" id="patient_card" name="patient_card"
                        value="1" {{ old('patient_card') ? 'checked' : '' }}>
                    <label class="custom-control-label" for="patient_card">Ya</label>
                </div>
            </x-form-row>

            <x-form-row label="Rujukan" for="rujukan">
                <div class="frame-wrap pt-2">
                    @foreach ($rujukanOptions as $value => $label)
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="rujukan-{{ Str::slug($value) }}"
                                name="rujukan" value="{{ $value }}"
                                {{ old('rujukan', 'inisiatif pribadi') == $value ? 'checked' : '' }}>
                            <label class="custom-control-label"
                                for="rujukan-{{ Str::slug($value) }}">{{ $label }}</label>
                        </div>
                    @endforeach
                </div>
            </x-form-row>

            <x-form-row label="Pelayanan *" for="pelayanan">
                <x-form-select id="pelayanan" name="pelayanan" :options="$pelayananOptions" />
            </x-form-row>

            <x-form-row label="Diagnosa Awal" for="diagnosa_awal">
                <textarea class="form-control" id="diagnosa_awal" name="diagnosa_awal" rows="4">{{ old('diagnosa_awal') }}</textarea>
            </x-form-row>
        </div>

        {{-- Tombol Aksi --}}
        <div class="col-xl-12 mt-5">
            <div class="row">
                <div class="col-xl-6">
                    <a href="{{ route('detail.pendaftaran.pasien', $patient->id) }}"
                        class="btn btn-lg btn-default waves-effect waves-themed">
                        <span class="fal fa-arrow-left mr-1 text-primary"></span>
                        <span class="text-primary">Kembali</span>
                    </a>
                </div>
                <div class="col-xl-6 text-right">
                    {{-- onclick dihapus, akan ditangani oleh event listener di JavaScript --}}
                    <button type="submit" class="btn btn-lg btn-primary waves-effect waves-themed" id="simpan-btn">
                        <span class="fal fa-save mr-1"></span>
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
