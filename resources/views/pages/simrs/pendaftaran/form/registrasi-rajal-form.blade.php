{{-- resources/views/pages/simrs/pendaftaran/form/registrasi-rajal-form.blade.php (Final Refactored Version) --}}

@php
    // Definisikan semua data statis di satu tempat agar mudah dikelola
    $penjaminOptions = $penjamins->pluck('nama_perusahaan', 'id')->all();

    $paketOptions = [
        'skin-care' => 'Paket Skin Care',
    ];

    $tipeJadwalOptions = [
        'OR' => 'Sesuai Jadwal Praktek',
        'WA' => 'On Call',
    ];

    $rujukanOptions = [
        'inisiatif pribadi' => 'Inisiatif Pribadi',
        'dalam rs' => 'Dalam RS',
        'luar rs' => 'Luar RS',
        'rujukan bpjs' => 'Rujukan BPJS',
    ];

    $tipeRujukanOptions = [
        'rsu/rsk/rb' => 'RSU/RSK/RB',
        'puskesmas' => 'PUSKESMAS',
        'bidan/perawat' => 'BIDAN/PERAWAT',
        'dokter' => 'DOKTER',
        'dukun terlatih' => 'DUKUN TERLATIH',
        'kasus polisi' => 'KASUS POLISI',
        'klinik' => 'KLINIK',
        'lain-lain' => 'LAIN-LAIN',
    ];
@endphp

<form id="form-registrasi" data-action-url="{{ route('simpan.registrasi') }}">
    @csrf
    {{-- Hidden Inputs --}}
    <input type="hidden" name="patient_id" value="{{ $patient->id }}">
    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
    <input type="hidden" name="employee_id" value="{{ auth()->user()->employee->id }}">
    <input type="hidden" name="registration_type" value="rawat-jalan">

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
                <x-doctor-select id="doctor_id" name="doctor_id" :doctors="$groupedDoctors" :selected="old('doctor_id')" />
            </x-form-row>

            <x-form-row label="Poliklinik" for="poliklinik">
                <input type="text" class="form-control form-control-dashed" id="poliklinik" name="poliklinik"
                    readonly value="{{ old('poliklinik') }}">
            </x-form-row>

            <x-form-row label="Penjamin" for="penjamin_id">
                <x-form-select id="penjamin_id" name="penjamin_id" :options="$penjaminOptions" />
            </x-form-row>
        </div>

        {{-- Kolom Kanan --}}
        <div class="col-xl-6">
            <x-form-row label="Paket" for="paket">
                <x-form-select id="paket" name="paket" :options="$paketOptions" placeholder="Tidak ada paket" />
            </x-form-row>

            <x-form-row label="Tipe Jadwal" for="type">
                <x-form-select id="type" name="type" :options="$tipeJadwalOptions" :selected="'OR'" />
            </x-form-row>

            <x-form-row label="Kartu Pasien" for="patient_card">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="patient_card" name="patient_card"
                        value="1" {{ old('patient_card') ? 'checked' : '' }}>
                    <label class="custom-control-label" for="patient_card">Ya</label>
                </div>
            </x-form-row>

            <x-form-row label="Rujukan" for="rujukan">
                <div class="frame-wrap pt-2">
                    @foreach ($rujukanOptions as $value => $label)
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="{{ Str::slug($value) }}"
                                name="rujukan" value="{{ $value }}"
                                {{ old('rujukan', 'inisiatif pribadi') == $value ? 'checked' : '' }}>
                            <label class="custom-control-label"
                                for="{{ Str::slug($value) }}">{{ $label }}</label>
                        </div>
                    @endforeach
                </div>
            </x-form-row>

            {{-- Container dinamis --}}
            <div class="mt-3 d-none" id="dokter_perujuk_container">
                <x-form-row label="Dokter Perujuk" for="dokter_perujuk">
                    <x-doctor-select id="dokter_perujuk" name="dokter_perujuk" :doctors="$groupedDoctors" :selected="old('dokter_perujuk')" />
                </x-form-row>
            </div>

            <div class="row d-none" id="luar_dan_rujuk_bpjs_container">
                <div class="col-12">
                    <x-form-row label="Tipe Rujuk" for="tipe_rujukan">
                        <x-form-select id="tipe_rujukan" name="tipe_rujukan" :options="$tipeRujukanOptions" />
                    </x-form-row>
                    <x-form-row label="Nama Perujuk" for="nama_perujuk">
                        <input type="text" class="form-control" id="nama_perujuk" name="nama_perujuk"
                            value="{{ old('nama_perujuk') }}">
                    </x-form-row>
                    <x-form-row label="Telp/HP Perujuk" for="telp_perujuk">
                        <input type="text" class="form-control" id="telp_perujuk" name="telp_perujuk"
                            value="{{ old('telp_perujuk') }}">
                    </x-form-row>
                    <x-form-row label="Alamat Perujuk" for="alamat_perujuk">
                        <input type="text" class="form-control" id="alamat_perujuk" name="alamat_perujuk"
                            value="{{ old('alamat_perujuk') }}">
                    </x-form-row>
                </div>
            </div>
        </div>

        {{-- Skrining Awal (kini konsisten) --}}
        <div class="col-xl-12 mt-4">
            <x-form-row label="Skrining Awal" for="diagnosa_awal">
                <textarea class="form-control" id="diagnosa_awal" name="diagnosa_awal" rows="5">{{ old('diagnosa_awal') }}</textarea>
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
