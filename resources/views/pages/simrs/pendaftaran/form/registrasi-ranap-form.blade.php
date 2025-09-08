{{-- resources/views/pages/simrs/pendaftaran/form/registrasi-ranap-form.blade.php (Refactored Version) --}}

@php
    // Definisikan semua data statis dan dinamis di satu tempat.
    $penjaminOptions = $penjamins->pluck('nama_perusahaan', 'id')->all();
    $paketOptions = ['Paket Skin Care' => 'Paket Skin Care'];
    $kelasTitipanOptions = $kelasTitipan->pluck('kelas', 'id')->all();

    $prosedurMasukOptions = [
        'rawat-jalan' => 'Rawat Jalan',
        'igd' => 'IGD',
        'vk' => 'VK',
        'ok' => 'OK',
    ];

    // Siapkan opsi rujukan dengan logika kondisional
    $rujukanOptions = [
        'inisiatif pribadi' => 'Inisiatif Pribadi',
        'dalam rs' => 'Dalam RS',
        'luar rs' => 'Luar RS',
    ];
    if (!$ranapBPJSdalam1bulan) {
        $rujukanOptions['rujukan bpjs'] = 'Rujukan BPJS';
    }
@endphp

{{-- Logika Peringatan BPJS --}}
@if ($ranapBPJSdalam1bulan)
    <script>
        // Pastikan SweetAlert2 sudah dimuat sebelum skrip ini dijalankan
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan Riwayat Rawat Inap',
                html: 'Pasien ini telah melakukan <strong>rawat inap dengan BPJS</strong> dalam 1 bulan terakhir.<br>Opsi rujukan BPJS dinonaktifkan.',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 7000,
                timerProgressBar: true,
            });
        });
    </script>
@endif

<form id="form-registrasi" data-action-url="{{ route('simpan.registrasi') }}">
    @csrf
    {{-- Hidden Inputs --}}
    <input type="hidden" name="patient_id" value="{{ old('patient_id', $patient->id) }}">
    <input type="hidden" name="user_id" value="{{ old('user_id', auth()->user()->id) }}">
    <input type="hidden" name="employee_id" value="{{ old('employee_id', auth()->user()->employee->id) }}">
    <input type="hidden" name="registration_type" value="{{ old('registration_type', 'rawat-inap') }}">
    {{-- Hidden inputs untuk data dari modal --}}
    <input type="hidden" id="bed_id_input" name="bed_id" value="{{ old('bed_id') }}">
    <input type="hidden" id="kelas_rawat_id_input" name="kelas_rawat_id" value="{{ old('kelas_rawat_id') }}">

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

            <x-form-row label="Kelas / Kamar Rawat" for="kamar_tujuan">
                {{-- Ini adalah input custom, jadi kita letakkan HTML-nya langsung di dalam slot komponen --}}
                <div class="input-group bg-white shadow-inset-2">
                    <input id="kamar_tujuan" readonly name="kamar_tujuan" type="text"
                        class="form-control border-right-0 bg-transparent pr-0" placeholder="Pilih kamar dari daftar..."
                        value="{{ old('kamar_tujuan') }}">
                    <div class="input-group-append">
                        <span class="input-group-text bg-transparent">
                            <i class="fal fa-search" style="cursor: pointer" data-toggle="modal"
                                data-target="#kelas-rawat-form"></i>
                        </span>
                    </div>
                </div>
            </x-form-row>

            <x-form-row label="Kartu Pasien" for="patient_card">
                <div class="custom-control custom-checkbox pt-2">
                    <input type="checkbox" class="custom-control-input" id="patient_card" name="patient_card"
                        value="1" {{ old('patient_card') ? 'checked' : '' }}>
                    <label class="custom-control-label" for="patient_card">Ya</label>
                </div>
            </x-form-row>

            <x-form-row label="Prosedur Masuk" for="prosedur_masuk">
                <div class="frame-wrap pt-2">
                    @foreach ($prosedurMasukOptions as $value => $label)
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="prosedur-{{ Str::slug($value) }}"
                                name="prosedur_masuk" value="{{ $value }}"
                                {{ old('prosedur_masuk') == $value ? 'checked' : '' }}>
                            <label class="custom-control-label"
                                for="prosedur-{{ Str::slug($value) }}">{{ $label }}</label>
                        </div>
                    @endforeach
                </div>
            </x-form-row>
        </div>

        {{-- Kolom Kanan --}}
        <div class="col-xl-6">
            <x-form-row label="Paket" for="paket">
                <x-form-select id="paket" name="paket" :options="$paketOptions" placeholder="Tidak ada paket" />
            </x-form-row>

            <x-form-row label="Penjamin" for="penjamin_id">
                <x-form-select id="penjamin_id" name="penjamin_id" :options="$penjaminOptions" />
            </x-form-row>

            <x-form-row label="Kelas Titipan" for="titip_kelas_rawat">
                <x-form-select id="titip_kelas_rawat" name="titip_kelas_rawat" :options="$kelasTitipanOptions"
                    placeholder="Tanpa kelas titipan" />
                <i class="text-danger" style="font-size: 8pt;">Tarif kamar tetap mengikuti tarif kelas yang
                    diinginkan.</i>
            </x-form-row>

            <x-form-row label="Rujukan" for="rujukan">
                <div class="frame-wrap pt-2">
                    {{-- Loop melalui opsi rujukan yang sudah disiapkan secara kondisional --}}
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
        </div>

        {{-- Skrining Awal --}}
        <div class="col-xl-12 mt-4">
            <x-form-row label="Diagnosa Awal" for="diagnosa_awal">
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
                    {{-- Tombol Simpan --}}
                    <button type="submit" class="btn btn-lg btn-primary waves-effect waves-themed" id="simpan-btn">
                        <span class="fal fa-save mr-1"></span>
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

{{-- Modal untuk memilih kelas rawat tetap di luar form --}}
<div class="modal fade" id="kelas-rawat-form" tabindex="-1" role="dialog" aria-hidden="true">
    {{-- Konten modal Anda tidak berubah dan tetap di sini --}}
    <div class="modal-dialog modal-lg" style="max-width: 80vw" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white"><strong>Pilih Tempat Tidur</strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fal fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <div class="card m-auto border">
                            <div class="card-header py-2 bg-primary">
                                <div class="card-title text-white">Form Pencarian</div>
                            </div>
                            <div class="card-body">
                                <form id="form-cari-kelas">
                                    <div class="form-group">
                                        <label class="form-label" for="kelas_rawat_id">Kelas Rawat</label>
                                        <select class="form-control w-100" id="kelas_rawat_id" name="kelas_rawat_id">
                                            <option value=""></option>
                                            @foreach ($kelas_rawats as $kelas_rawat)
                                                <option value="{{ $kelas_rawat->id }}">{{ $kelas_rawat->kelas }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        <table id="bed-table" style="width: 100%;" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Ruangan</th>
                                    <th>T. Tidur</th>
                                    <th>Pasien</th>
                                    <th>Fungsi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
