@extends('pages.simrs.erm.index')
@php
    // Menggabungkan data dari pengkajian atau transfer jika ada
    $data = $pengkajian ?? ($transfer ?? null);
@endphp

@section('erm')
    <main id="js-page-content" role="main" class="page-content">
        {{-- Breadcrumb & Subheader --}}
        @include('inc.breadcrumb', ['bcrumb' => 'bc_level_dua', 'bc_1' => 'Formulir ERM'])
        <div class="subheader">
            @component('inc.subheader', ['subheader_title' => 'st_type_2'])
                @slot('sh_icon')
                    far fa-file-alt
                @endslot
                @slot('sh_descipt')
                    Formulir Transfer Pasien Antar Ruangan
                @endslot
            @endcomponent
        </div>

        @php
            // Ambil data transfer pasien antar ruangan ($pengkajian) jika ada, jika tidak, gunakan $cppt untuk field yang sama
            // $cppt diharapkan sudah dikirim dari controller jika $pengkajian tidak ada
            $cppt = $cppt ?? null;

            // Helper untuk mengambil nilai field dari $data (pengkajian/transfer), jika tidak ada ambil dari $cppt, jika tidak ada juga, fallback default
            // Diubah menjadi Closure (anonymous function) agar bisa menggunakan `use`
            $transfer_pasien_value = function ($field, $default = null) use ($data, $cppt) {
                // Cek di $data (pengkajian/transfer)
                if (isset($data) && isset($data->$field)) {
                    return $data->$field;
                }
                // Cek di $cppt (hanya untuk field yang cocok)
                if (isset($cppt)) {
                    // Mapping field transfer ke field cppt
                    $map = [
                        // II. Kondisi Pasien Saat Pindah
                        'keluhan_utama' => function ($cppt) {
                            // Cari "Keluhan Utama :" di $cppt->subjective
                            if (preg_match('/Keluhan Utama\s*:\s*(.*)/i', $cppt->subjective, $m)) {
                                return trim($m[1]);
                            }
                            return '';
                        },
                        'td' => function ($cppt) {
                            // Tensi (BP) di objective
                            if (preg_match('/Tensi\s*\(BP\)\s*:\s*([^\n]*)/i', $cppt->objective, $m)) {
                                return trim($m[1]);
                            }
                            return '';
                        },
                        'nd' => function ($cppt) {
                            // Nadi (PR) di objective
                            if (preg_match('/Nadi\s*\(PR\)\s*:\s*([^\n]*)/i', $cppt->objective, $m)) {
                                return trim($m[1]);
                            }
                            return '';
                        },
                        'rr' => function ($cppt) {
                            // Respirasi (RR) di objective
                            if (preg_match('/Respirasi\s*\(RR\)\s*:\s*([^\n]*)/i', $cppt->objective, $m)) {
                                return trim($m[1]);
                            }
                            return '';
                        },
                        'sb' => function ($cppt) {
                            // Suhu (T) di objective
                            if (preg_match('/Suhu\s*\(T\)\s*:\s*([^\n]*)/i', $cppt->objective, $m)) {
                                return trim($m[1]);
                            }
                            return '';
                        },
                        'spo2' => function ($cppt) {
                            // SpO2 di objective
                            if (preg_match('/SpO2\s*:\s*([^\n]*)/i', $cppt->objective, $m)) {
                                return trim($m[1]);
                            }
                            return '';
                        },
                        'tb' => function ($cppt) {
                            // Tinggi Badan di objective
                            if (preg_match('/Tinggi Badan\s*:\s*([^\n]*)/i', $cppt->objective, $m)) {
                                return trim($m[1]);
                            }
                            return '';
                        },
                        'bb' => function ($cppt) {
                            // Berat Badan di objective
                            if (preg_match('/Berat Badan\s*:\s*([^\n]*)/i', $cppt->objective, $m)) {
                                return trim($m[1]);
                            }
                            return '';
                        },
                        'pemeriksaan_penunjang' => function ($cppt) {
                            // Pemeriksaan Penunjang di objective
                            if (preg_match('/Pemeriksaan Penunjang\s*:\s*([^\n]*)/i', $cppt->objective, $m)) {
                                return trim($m[1]);
                            }
                            return '';
                        },
                        'asesmen' => function ($cppt) {
                            // Diagnosa Kerja di assesment
                            if (preg_match('/Diagnosa Kerja\s*:\s*([^\n]*)/i', $cppt->assesment, $m)) {
                                return trim($m[1]);
                            }
                            return '';
                        },
                        'intervensi_tindakan' => function ($cppt) {
                            // Terapi / Tindakan di planning
                            if (isset($cppt->planning)) {
                                return trim($cppt->planning);
                            }
                            return '';
                        },
                        // Lain-lain bisa ditambah sesuai kebutuhan
                    ];
                    if (isset($map[$field])) {
                        return $map[$field]($cppt);
                    }
                }
                // Fallback default
                return $default;
            }; // <-- Jangan lupa titik koma di sini
        @endphp

        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Formulir <span class="fw-300"><i>Transfer Pasien Antar Ruangan</i></span>
                        </h2>
                        <div class="panel-toolbar">
                            <button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip"
                                data-offset="0,10" data-original-title="Collapse"></button>
                            <button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip"
                                data-offset="0,10" data-original-title="Fullscreen"></button>
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form id="transferPasienForm">
                                @csrf
                                {{-- Detail Pasien (diasumsikan sudah ada) --}}
                                @include('pages.simrs.erm.partials.detail-pasien')
                                <hr class="my-4">

                                {{-- I. Informasi Umum & Tujuan Transfer --}}
                                <h5 class="frame-heading">I. Informasi Umum & Tujuan Transfer</h5>
                                <div class="frame-wrap">
                                    <div class="form-row">
                                        <div class="col-md-4 form-group">
                                            <label class="form-label" for="tgl_masuk_pasien">Tanggal Masuk RS</label>
                                            <input type="date" name="tgl_masuk_pasien" id="tgl_masuk_pasien"
                                                class="form-control"
                                                value="{{ old('tgl_masuk_pasien', optional($data)->tgl_masuk_pasien ?? date('Y-m-d')) }}">
                                        </div>
                                        <div class="col-md-2 form-group">
                                            <label class="form-label" for="jam_masuk_pasien">Jam</label>
                                            <input type="time" name="jam_masuk_pasien" id="jam_masuk_pasien"
                                                class="form-control"
                                                value="{{ old('jam_masuk_pasien', isset($data->jam_masuk_pasien) ? \Carbon\Carbon::parse($data->jam_masuk_pasien)->format('H:i') : date('H:i')) }}">
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label class="form-label" for="tgl">Tanggal Transfer Pasien</label>
                                            <input type="date" name="tgl" id="tgl" class="form-control"
                                                value="{{ old('tgl', optional($data)->tgl ?? date('Y-m-d')) }}">
                                        </div>
                                        <div class="col-md-2 form-group">
                                            <label class="form-label" for="jam">Jam</label>
                                            <input type="time" name="jam" id="jam" class="form-control"
                                                value="{{ old('jam', isset($data->jam) ? \Carbon\Carbon::parse($data->jam)->format('H:i') : date('H:i')) }}">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-6 form-group">
                                            <label class="form-label">Asal Ruangan & Kelas</label>
                                            <div class="input-group">
                                                <input name="ruangan_asal" placeholder="Ruangan Asal" class="form-control"
                                                    type="text"
                                                    value="{{ old('ruangan_asal', $data->ruangan_asal ?? '') }}">
                                                <input name="kelas_asal" placeholder="Kelas Asal" class="form-control"
                                                    type="text" value="{{ old('kelas_asal', $data->kelas_asal ?? '') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="form-label">Pindah ke Ruangan & Kelas</label>
                                            <div class="input-group">
                                                <input name="ruangan_pindah" placeholder="Ruangan Pindah"
                                                    class="form-control" type="text"
                                                    value="{{ old('ruangan_pindah', $data->ruangan_pindah ?? '') }}">
                                                <input name="kelas_pindah" placeholder="Kelas Pindah" class="form-control"
                                                    type="text"
                                                    value="{{ old('kelas_pindah', $data->kelas_pindah ?? '') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-6 form-group">
                                            <label class="form-label" for="asesmen">DX Medis</label>
                                            <input class="form-control" name="asesmen" id="asesmen" type="text"
                                                value="{{ old('asesmen', $transfer_pasien_value('asesmen', '')) }}">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="form-label" for="masalah_keperawatan">Masalah Keperawatan</label>
                                            <input class="form-control" name="masalah_keperawatan"
                                                id="masalah_keperawatan" type="text"
                                                value="{{ old('masalah_keperawatan', $data->masalah_keperawatan ?? '') }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Dokter yang Merawat</label>
                                        <input name="dokter" placeholder="1. Nama Dokter" class="form-control mb-2"
                                            type="text"
                                            value="{{ old('dokter', $data->dokter ?? ($registration->doctor->employee->fullname ?? '')) }}">
                                        <input name="dokter2" placeholder="2. Nama Dokter (opsional)"
                                            class="form-control mb-2" type="text"
                                            value="{{ old('dokter2', $data->dokter2 ?? '') }}">
                                        <input name="dokter3" placeholder="3. Nama Dokter (opsional)"
                                            class="form-control" type="text"
                                            value="{{ old('dokter3', $data->dokter3 ?? '') }}">
                                    </div>
                                </div>

                                {{-- II. Kondisi Pasien Saat Pindah --}}
                                <h5 class="frame-heading mt-4">II. Kondisi Pasien Saat Pindah</h5>
                                <div class="frame-wrap">
                                    <div class="form-row">
                                        <div class="col-md-9 form-group">
                                            <label for="keluhan_utama" class="form-label">Keluhan Utama</label>
                                            <input name="keluhan_utama" id="keluhan_utama" class="form-control"
                                                type="text"
                                                value="{{ old('keluhan_utama', $transfer_pasien_value('keluhan_utama', '')) }}">
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <label for="tiba_diruangan" class="form-label">Tiba di Ruangan (Jam)</label>
                                            <input name="tiba_diruangan" id="tiba_diruangan" type="time"
                                                class="form-control"
                                                value="{{ old('tiba_diruangan', isset($data->tiba_diruangan) ? \Carbon\Carbon::parse($data->tiba_diruangan)->format('H:i') : '') }}">
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-center">
                                        <div class="col-md-6">
                                            <label class="form-label d-block">Keadaan Umum</label>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" name="keadaan_umum"
                                                    id="ku_baik" value="Baik"
                                                    {{ old('keadaan_umum', $data->keadaan_umum ?? '') == 'Baik' ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="ku_baik">Baik</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" name="keadaan_umum"
                                                    id="ku_sedang" value="Sedang"
                                                    {{ old('keadaan_umum', $data->keadaan_umum ?? '') == 'Sedang' ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="ku_sedang">Sedang</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" name="keadaan_umum"
                                                    id="ku_berat" value="Berat"
                                                    {{ old('keadaan_umum', $data->keadaan_umum ?? '') == 'Berat' ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="ku_berat">Berat</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-0">
                                                <label class="form-label" for="kesadaran">Kesadaran</label>
                                                <input name="kesadaran" id="kesadaran" class="form-control"
                                                    type="text"
                                                    value="{{ old('kesadaran', $data->kesadaran ?? '') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-4 form-group">
                                            <label class="form-label">GCS</label>
                                            <input type="text" name="ket_gcs" class="form-control"
                                                placeholder="E_V_M_" value="{{ old('ket_gcs', $data->ket_gcs ?? '') }}">
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label class="form-label">TD (mmHg)</label>
                                            <input type="text" name="td" class="form-control"
                                                value="{{ old('td', $transfer_pasien_value('td', '')) }}">
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label class="form-label">ND (x/menit)</label>
                                            <input type="text" name="nd" class="form-control"
                                                value="{{ old('nd', $transfer_pasien_value('nd', '')) }}">
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label class="form-label">RR (x/menit)</label>
                                            <input type="text" name="rr" class="form-control"
                                                value="{{ old('rr', $transfer_pasien_value('rr', '')) }}">
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label class="form-label">SB (°C)</label>
                                            <input type="text" name="sb" class="form-control"
                                                value="{{ old('sb', $transfer_pasien_value('sb', '')) }}">
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label class="form-label">SPO2 (%)</label>
                                            <input name="spo2" class="form-control" type="text"
                                                value="{{ old('spo2', $transfer_pasien_value('spo2', '')) }}">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-4 form-group">
                                            <label class="form-label">BB (Kg)</label>
                                            <input name="bb" class="form-control" type="text"
                                                value="{{ old('bb', $transfer_pasien_value('bb', '')) }}">
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label class="form-label">TB (Cm)</label>
                                            <input name="tb" class="form-control" type="text"
                                                value="{{ old('tb', $transfer_pasien_value('tb', '')) }}">
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label class="form-label">Status Nyeri</label>
                                            <input name="status_nyeri" class="form-control" type="text"
                                                value="{{ old('status_nyeri', $data->status_nyeri ?? '') }}">
                                        </div>
                                    </div>
                                </div>

                                {{-- III. Alasan Pemindahan Pasien --}}
                                <h5 class="frame-heading mt-4">III. Alasan Pemindahan Pasien</h5>
                                <div class="frame-wrap">
                                    <div class="form-group">
                                        <label class="form-label d-block">Tindakan</label>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" name="tindakan"
                                                id="tindakan_ok" value="OK"
                                                {{ old('tindakan', $data->tindakan ?? '') == 'OK' ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="tindakan_ok">OK</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" name="tindakan"
                                                id="tindakan_vk" value="VK"
                                                {{ old('tindakan', $data->tindakan ?? '') == 'VK' ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="tindakan_vk">VK</label>
                                        </div>
                                        <div class="input-group mt-2">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" class="custom-control-input"
                                                            id="tindakan_lainnya_radio" name="tindakan" value="Lainnya"
                                                            {{ old('tindakan', $data->tindakan ?? '') == 'Lainnya' ? 'checked' : '' }}>
                                                        <label class="custom-control-label"
                                                            for="tindakan_lainnya_radio">Lainnya</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control" name="ket_lainnya"
                                                value="{{ old('ket_lainnya', $data->ket_lainnya ?? '') }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input"
                                                            id="app_lainnya" name="app_lainnya" value="1"
                                                            {{ old('app_lainnya', $data->app_lainnya ?? false) ? 'checked' : '' }}>
                                                        <label class="custom-control-label"
                                                            for="app_lainnya">Lainnya</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control" name="app_lainnya_text"
                                                value="{{ old('app_lainnya_text', $data->app_lainnya_text ?? '') }}">
                                        </div>
                                    </div>
                                </div>

                                {{-- IV. Status & Kebutuhan Pasien --}}
                                <h5 class="frame-heading mt-4">IV. Status & Kebutuhan Pasien</h5>
                                <div class="frame-wrap">
                                    <div class="form-row">
                                        <div class="col-md-6 form-group">
                                            <label class="form-label d-block">Status Fungsional</label>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" name="sfp"
                                                    id="sfp_mandiri" value="Mandiri"
                                                    {{ old('sfp', $data->sfp ?? '') == 'Mandiri' ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="sfp_mandiri">Mandiri</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" name="sfp"
                                                    id="sfp_partial" value="Partial Care"
                                                    {{ old('sfp', $data->sfp ?? '') == 'Partial Care' ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="sfp_partial">Partial Care</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" name="sfp"
                                                    id="sfp_total" value="Total Care"
                                                    {{ old('sfp', $data->sfp ?? '') == 'Total Care' ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="sfp_total">Total Care</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="form-label d-block">Risiko Jatuh</label>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" name="rj"
                                                    id="rj_tidak" value="Tidak Berisiko"
                                                    {{ old('rj', $data->rj ?? '') == 'Tidak Berisiko' ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="rj_tidak">Tidak Berisiko</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" name="rj"
                                                    id="rj_rendah" value="Rendah"
                                                    {{ old('rj', $data->rj ?? '') == 'Rendah' ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="rj_rendah">Rendah</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" name="rj"
                                                    id="rj_tinggi" value="Tinggi"
                                                    {{ old('rj', $data->rj ?? '') == 'Tinggi' ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="rj_tinggi">Tinggi</label>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-row">
                                        <div class="col-md-6 form-group">
                                            <label class="form-label d-block">Kewaspadaan Transmisi/Infeksi</label>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" name="kti"
                                                    id="kti_kontak" value="Kontak"
                                                    {{ old('kti', $data->kti ?? '') == 'Kontak' ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="kti_kontak">Kontak</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" name="kti"
                                                    id="kti_percikan" value="Percikan"
                                                    {{ old('kti', $data->kti ?? '') == 'Percikan' ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="kti_percikan">Percikan</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" name="kti"
                                                    id="kti_udara" value="Udara"
                                                    {{ old('kti', $data->kti ?? '') == 'Udara' ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="kti_udara">Udara</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="form-label d-block">Memerlukan Perawatan Isolasi</label>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" name="mpi"
                                                    id="mpi_ya" value="1"
                                                    {{ old('mpi', $data->mpi ?? false) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="mpi_ya">Ya</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" name="mpi"
                                                    id="mpi_tidak" value="0"
                                                    {{ !old('mpi', $data->mpi ?? true) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="mpi_tidak">Tidak</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- V. Prosedur & Informasi Keluarga --}}
                                <h5 class="frame-heading mt-4">V. Prosedur & Informasi Keluarga</h5>
                                <div class="frame-wrap">
                                    <div class="form-group">
                                        <label class="form-label d-block">Metode Pemindahan Pasien</label>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" name="mpp"
                                                id="mpp_kuro" value="Kursi Roda"
                                                {{ old('mpp', $data->mpp ?? '') == 'Kursi Roda' ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="mpp_kuro">Kursi Roda</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" name="mpp"
                                                id="mpp_temti" value="Tempat Tidur"
                                                {{ old('mpp', $data->mpp ?? '') == 'Tempat Tidur' ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="mpp_temti">Tempat Tidur</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" name="mpp"
                                                id="mpp_brangkar" value="Brangkar"
                                                {{ old('mpp', $data->mpp ?? '') == 'Brangkar' ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="mpp_brangkar">Brangkar</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" name="mpp"
                                                id="mpp_bok_bayi" value="Bok Bayi"
                                                {{ old('mpp', $data->mpp ?? '') == 'Bok Bayi' ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="mpp_bok_bayi">Bok Bayi</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" name="mpp"
                                                id="mpp_jalan" value="Jalan/Gendong"
                                                {{ old('mpp', $data->mpp ?? '') == 'Jalan/Gendong' ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="mpp_jalan">Jalan/Gendong</label>
                                        </div>
                                    </div>
                                    <hr />
                                    <div class="form-group">
                                        <label class="form-label d-block">Peralatan yang Menyertai Saat Pemindahan</label>
                                        <div class="row">
                                            <div class="col-md-5">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input"
                                                                    id="pmp_kuro_cb" name="pmp_kuro" value="Oksigen"
                                                                    {{ old('pmp_kuro', $data->pmp_kuro ?? false) ? 'checked' : '' }}>
                                                                <label class="custom-control-label"
                                                                    for="pmp_kuro_cb">Oksigen</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <input type="text" name="pmp_text" class="form-control"
                                                        placeholder="... ltr/mnt"
                                                        value="{{ old('pmp_text', $data->pmp_text ?? '') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-7 d-flex align-items-center">
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" class="custom-control-input"
                                                        name="pmp_cateter_urine" id="pmp_cateter_urine" value="1"
                                                        {{ old('pmp_cateter_urine', $data->pmp_cateter_urine ?? false) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="pmp_cateter_urine">Cateter
                                                        Urine</label>
                                                </div>
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" class="custom-control-input" name="pmp_ngt"
                                                        id="pmp_ngt" value="1"
                                                        {{ old('pmp_ngt', $data->pmp_ngt ?? false) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="pmp_ngt">NGT</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-row">
                                        <div class="col-md-4 form-group">
                                            <label class="form-label d-block">Pasien/Keluarga Mengetahui Alasan
                                                Pindah</label>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" name="ap"
                                                    id="ap_ya" value="1"
                                                    {{ old('ap', $data->ap ?? false) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="ap_ya">Ya</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" name="ap"
                                                    id="ap_tidak" value="0"
                                                    {{ !old('ap', $data->ap ?? true) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="ap_tidak">Tidak</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label for="ap_nama" class="form-label">Nama</label>
                                            <input type="text" name="ap_nama" id="ap_nama" class="form-control"
                                                value="{{ old('ap_nama', $data->ap_nama ?? '') }}">
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label for="ap_hubungan" class="form-label">Hubungan Keluarga</label>
                                            <input type="text" name="ap_hubungan" id="ap_hubungan"
                                                class="form-control"
                                                value="{{ old('ap_hubungan', $data->ap_hubungan ?? '') }}">
                                        </div>
                                    </div>
                                </div>

                                {{-- VI. Catatan Medis, Tindakan & Terapi --}}
                                <h5 class="frame-heading mt-4">VI. Catatan Medis, Tindakan & Terapi</h5>
                                <div class="frame-wrap">
                                    <div class="form-row">
                                        <div class="col-md-6 form-group">
                                            <label for="alasan_pdh_temuan_anamesis" class="form-label">Instruksi Dokter
                                                Umum</label>
                                            <textarea name="alasan_pdh_temuan_anamesis" id="alasan_pdh_temuan_anamesis" class="form-control" rows="3">{{ old('alasan_pdh_temuan_anamesis', $data->alasan_pdh_temuan_anamesis ?? '') }}</textarea>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label for="pemeriksaan_penunjang" class="form-label">Hasil Pemeriksaan
                                                Penunjang (Lab, EKG, dll)</label>
                                            <textarea name="pemeriksaan_penunjang" id="pemeriksaan_penunjang" class="form-control" rows="3">{{ old('pemeriksaan_penunjang', $transfer_pasien_value('pemeriksaan_penunjang', '')) }}</textarea>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label for="intervensi_tindakan" class="form-label">Advice DPJP</label>
                                            <textarea name="intervensi_tindakan" id="intervensi_tindakan" class="form-control" rows="3">{{ old('intervensi_tindakan', $transfer_pasien_value('intervensi_tindakan', '')) }}</textarea>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label for="diet" class="form-label">Diet (bila pindah ruangan)</label>
                                            <textarea name="diet" id="diet" class="form-control" rows="3">{{ old('diet', $data->diet ?? "Jenis Diet :\nPuasa :\nTerakhir minum :\nTerakhir makan :") }}</textarea>
                                        </div>
                                    </div>
                                    <hr>
                                    <h5 class="text-center mb-3">Pemberian Terapi Sebelum Pindah</h5>
                                    <div class="form-row">
                                        <div class="col-md-6 form-group">
                                            <label class="form-label d-block">Infus</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="ptsp_infus" name="ptsp_infus" value="1"
                                                                {{ old('ptsp_infus', $data->ptsp_infus ?? false) ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="ptsp_infus"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <input class="form-control" type="text" name="ptsp_infus_text"
                                                    placeholder="Jenis Cairan"
                                                    value="{{ old('ptsp_infus_text', $data->ptsp_infus_text ?? '') }}">
                                                <input class="form-control" type="text" name="ptsp_infus_tetesan"
                                                    placeholder="Tetesan/Jam"
                                                    value="{{ old('ptsp_infus_tetesan', $data->ptsp_infus_tetesan ?? '') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row mt-4">
                                        @for ($i = 1; $i <= 10; $i++)
                                            <div class="col-md-6 form-group">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">{{ $i }}.</span>
                                                    </div>
                                                    <input name="resep{{ $i }}" type="text"
                                                        class="form-control" placeholder="Nama Obat/Tindakan"
                                                        value="{{ old('resep' . $i, $data->{'resep' . $i} ?? '') }}">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">Jam</span>
                                                    </div>
                                                    <input name="jam_pemberian{{ $i }}" type="time"
                                                        class="form-control"
                                                        value="{{ old('jam_pemberian' . $i, isset($data->{'jam_pemberian' . $i}) ? \Carbon\Carbon::parse($data->{'jam_pemberian' . $i})->format('H:i') : '') }}">
                                                </div>
                                            </div>
                                        @endfor
                                    </div>
                                </div>

                                {{-- VII. Serah Terima Pasien --}}
                                <h5 class="frame-heading mt-4">VII. Serah Terima Pasien</h5>
                                <div class="frame-wrap">
                                    <div class="row mt-3 justify-content-center">
                                        <div class="col-md-5 text-center form-group">
                                            @include('pages.simrs.erm.partials.signature-many', [
                                                'judul' => 'Perawat yang Menyerahkan',
                                                'name_prefix' => 'data_ttd1',
                                                'pic' => auth()->user()->name,
                                                'index' => 1,
                                                'signature_model' => $data?->signature_pengirim,
                                            ])
                                            <input type="text" name="nama_perawat_pengirim"
                                                class="form-control mt-2 text-center" placeholder="Nama Jelas Perawat"
                                                value="{{ old('nama_perawat_pengirim', $data->nama_perawat_pengirim ?? auth()->user()->name) }}">
                                        </div>
                                        <div class="col-md-5 text-center form-group">
                                            @include('pages.simrs.erm.partials.signature-many', [
                                                'judul' => 'Perawat yang Menerima',
                                                'name_prefix' => 'data_ttd2',
                                                'pic' => '',
                                                'index' => 2,
                                                'signature_model' => $data?->signature_penerima,
                                            ])
                                            <input type="text" name="nama_perawat_penerima"
                                                class="form-control mt-2 text-center" placeholder="Nama Jelas Perawat"
                                                value="{{ old('nama_perawat_penerima', $data->nama_perawat_penerima ?? '') }}">
                                        </div>
                                    </div>
                                </div>

                                {{-- VIII. Pasien Kembali --}}
                                <h5 class="frame-heading mt-4">VIII. Diisi untuk Pasien yang Kembali ke Ruang Semula</h5>
                                <div class="frame-wrap">
                                    <div class="form-row">
                                        <div class="col-md-4 form-group">
                                            <label for="pasien_kelmbali" class="form-label">Pasien Kembali Pukul
                                                (WIB)</label>
                                            <input type="time" name="pasien_kelmbali" class="form-control"
                                                value="{{ old('pasien_kelmbali', isset($data->pasien_kelmbali) ? \Carbon\Carbon::parse($data->pasien_kelmbali)->format('H:i') : '') }}">
                                        </div>
                                        <div class="col-md-8 form-group">
                                            <label for="keadaan_umum_after" class="form-label">Keadaan Umum</label>
                                            <input type="text" name="keadaan_umum_after" class="form-control"
                                                value="{{ old('keadaan_umum_after', $data->keadaan_umum_after ?? '') }}">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-3 form-group">
                                            <label class="form-label">TD (mmHg)</label>
                                            <input type="text" name="td_after" class="form-control"
                                                value="{{ old('td_after', $data->td_after ?? '') }}">
                                        </div>
                                        <div class="col-md-2 form-group">
                                            <label class="form-label">ND (x/m)</label>
                                            <input type="text" name="nd_after" class="form-control"
                                                value="{{ old('nd_after', $data->nd_after ?? '') }}">
                                        </div>
                                        <div class="col-md-2 form-group">
                                            <label class="form-label">RR (x/m)</label>
                                            <input type="text" name="rr_after" class="form-control"
                                                value="{{ old('rr_after', $data->rr_after ?? '') }}">
                                        </div>
                                        <div class="col-md-2 form-group">
                                            <label class="form-label">Suhu (°C)</label>
                                            <input type="text" name="sb_after" class="form-control"
                                                value="{{ old('sb_after', $data->sb_after ?? '') }}">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-6 form-group">
                                            <label class="form-label d-block">Resiko Jatuh</label>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" name="rj_after"
                                                    id="rj_tidak_after" value="Tidak Berisiko"
                                                    {{ old('rj_after', $data->rj_after ?? '') == 'Tidak Berisiko' ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="rj_tidak_after">Tidak
                                                    Berisiko</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" name="rj_after"
                                                    id="rj_rendah_after" value="Rendah"
                                                    {{ old('rj_after', $data->rj_after ?? '') == 'Rendah' ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="rj_rendah_after">Rendah</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" name="rj_after"
                                                    id="rj_tinggi_after" value="Tinggi"
                                                    {{ old('rj_after', $data->rj_after ?? '') == 'Tinggi' ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="rj_tinggi_after">Tinggi</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="form-label">Diet</label>
                                            <input type="text" name="diet_after" class="form-control"
                                                value="{{ old('diet_after', $data->diet_after ?? '') }}">
                                        </div>
                                    </div>
                                    <div class="row mt-3 justify-content-center">
                                        <div class="col-md-5 text-center form-group">
                                            @include('pages.simrs.erm.partials.signature-many', [
                                                'judul' => 'Perawat yang Menyerahkan',
                                                'name_prefix' => 'data_ttd3',
                                                'pic' => auth()->user()->name,
                                                'index' => 3,
                                                'signature_model' => $data?->signature_pengirim_balik,
                                            ])
                                            <input type="text" name="nama_perawat_pengirim_after"
                                                class="form-control mt-2 text-center" placeholder="Nama Jelas Perawat"
                                                value="{{ old('nama_perawat_pengirim_after', $data->nama_perawat_pengirim_after ?? auth()->user()->name) }}">
                                        </div>
                                        <div class="col-md-5 text-center form-group">
                                            @include('pages.simrs.erm.partials.signature-many', [
                                                'judul' => 'Perawat yang Menerima',
                                                'name_prefix' => 'data_ttd4',
                                                'pic' => '',
                                                'index' => 4,
                                                'signature_model' => $data?->signature_penerima_balik,
                                            ])
                                            <input type="text" name="nama_perawat_penerima_after"
                                                class="form-control mt-2 text-center" placeholder="Nama Jelas Perawat"
                                                value="{{ old('nama_perawat_penerima_after', $data->nama_perawat_penerima_after ?? '') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="panel-content-end d-flex justify-content-end mt-4">
                                    <button class="btn btn-primary" type="submit" id="save-transfer-button">
                                        Simpan Data
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@section('plugin-erm')
    {{-- Script AJAX dan Tanda Tangan Anda yang sudah ada bisa diletakkan di sini --}}
    <script>
        // =====================================================================
        // INISIALISASI TANDA TANGAN (DENGAN PENYIMPANAN STATE EKSTERNAL)
        // =====================================================================
        (function() {
            const canvasManyElement = document.getElementById('canvas-many');
            console.log(canvasManyElement);

            if (!canvasManyElement || window.signaturePadManyInitialized) return;

            const ctxMany = canvasManyElement.getContext('2d', {
                willReadFrequently: true
            });
            let signatureState = {};
            let currentSession = {
                painting: false,
                history: [],
                hasDrawn: false,
                index: null
            };

            function startNewSession(index) {
                currentSession = {
                    painting: false,
                    history: [],
                    hasDrawn: false,
                    index: index
                };
                ctxMany.clearRect(0, 0, canvasManyElement.width, canvasManyElement.height);
            }

            function startPositionMany(e) {
                e.preventDefault();
                currentSession.painting = true;
                drawMany(e);
            }

            function endPositionMany(e) {
                e.preventDefault();
                if (!currentSession.painting) return;
                currentSession.painting = false;
                ctxMany.beginPath();
                currentSession.history.push(ctxMany.getImageData(0, 0, canvasManyElement.width, canvasManyElement
                    .height));
            }

            function drawMany(e) {
                if (!currentSession.painting) return;
                const rect = canvasManyElement.getBoundingClientRect();
                const x = (e.clientX || e.touches?.[0]?.clientX) - rect.left;
                const y = (e.clientY || e.touches?.[0]?.clientY) - rect.top;
                ctxMany.lineWidth = 3;
                ctxMany.lineCap = 'round';
                ctxMany.strokeStyle = '#000';
                ctxMany.lineTo(x, y);
                ctxMany.stroke();
                ctxMany.beginPath();
                ctxMany.moveTo(x, y);
                currentSession.hasDrawn = true;
            }

            function undoMany() {
                if (currentSession.history.length > 0) {
                    currentSession.history.pop();
                    if (currentSession.history.length > 0) {
                        ctxMany.putImageData(currentSession.history[currentSession.history.length - 1], 0, 0);
                    } else {
                        ctxMany.clearRect(0, 0, canvasManyElement.width, canvasManyElement.height);
                        currentSession.hasDrawn = false;
                    }
                }
            }

            window.openSignaturePadMany = function(index) {
                startNewSession(index);
                $('#signatureModalMany').modal('show');
            }

            window.saveSignatureMany = function() {
                if (!currentSession.hasDrawn) {
                    alert("Silakan buat tanda tangan terlebih dahulu.");
                    return;
                }
                const dataURL = canvasManyElement.toDataURL('image/png');
                const currentIndex = currentSession.index;
                const preview = document.getElementById(`signature_preview_${currentIndex}`);
                const input = document.getElementById(`signature_image_${currentIndex}`);

                signatureState[currentIndex] = dataURL;
                if (preview) {
                    preview.src = dataURL;
                    preview.style.display = 'block';
                }
                if (input) {
                    input.value = dataURL;
                }

                console.log(`Signature for index ${currentIndex} saved to state.`);
                $('#signatureModalMany').modal('hide');
                const triggerButton = document.getElementById(`ttd_pegawai_${currentIndex}`);
                if (triggerButton) {
                    triggerButton.focus();
                }
            }

            window.syncSignatureStateToForm = function() {
                console.log('Syncing signature state to form inputs...');
                for (const index in signatureState) {
                    if (signatureState.hasOwnProperty(index)) {
                        const input = document.getElementById(`signature_image_${index}`);
                        if (input && signatureState[index]) {
                            input.value = signatureState[index];
                            console.log(`Input ${index} synced.`);
                        }
                    }
                }
            }

            $('#signatureModalMany .btn-outline-danger').on('click', function() {
                ctxMany.clearRect(0, 0, canvasManyElement.width, canvasManyElement.height);
                currentSession.history = [];
                currentSession.hasDrawn = false;
            });
            $('#signatureModalMany .btn-outline-secondary').on('click', undoMany);
            $('#signatureModalMany .btn-success').on('click', window.saveSignatureMany);

            canvasManyElement.addEventListener('mousedown', startPositionMany);
            canvasManyElement.addEventListener('mouseup', endPositionMany);
            canvasManyElement.addEventListener('mousemove', drawMany);
            canvasManyElement.addEventListener('touchstart', startPositionMany, {
                passive: false
            });
            canvasManyElement.addEventListener('touchend', endPositionMany, {
                passive: false
            });
            canvasManyElement.addEventListener('touchmove', drawMany, {
                passive: false
            });

            window.signaturePadManyInitialized = true;
            console.log('Signature Pad Initialized with EXTERNAL state management.');
        })();

        // =====================================================================
        // AJAX FORM SUBMISSION (DENGAN SINKRONISASI)
        // =====================================================================
        $(document).ready(function() {
            $('#transferPasienForm').on('submit', function(e) {
                e.preventDefault();

                window.syncSignatureStateToForm();

                console.log('Form submission intercepted. Starting AJAX...');
                const form = $(this);
                const url = "{{ route('transfer-pasien-antar-ruangan.store') }}";
                const formData = new FormData(this);
                formData.append('registration_id', '{{ $registration->id }}');

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        $('#save-transfer-button').prop('disabled', true).html(
                            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...'
                        );
                    },
                    success: function(response) {
                        alert(response.message);
                        window.location.reload();
                    },
                    error: function(xhr) {
                        var response = xhr.responseJSON;
                        var errorMessage = 'Terjadi kesalahan. Silakan coba lagi.';
                        if (response) {
                            errorMessage = response.error || Object.values(response.errors)
                                .join('\n');
                        }
                        alert('Error: \n' + errorMessage);
                    },
                    complete: function() {
                        $('#save-transfer-button').prop('disabled', false).html(
                            '<span class="mdi mdi-content-save mr-2"></span> Simpan Data');
                    }
                });
            });

            // ==========================================================
            // LOGIKA BARU UNTUK POPUP TANDA TANGAN
            // ==========================================================

            // Fungsi ini dipanggil dari window popup untuk mengupdate halaman utama
            window.updateSignature = function(targetInputId, targetPreviewId, dataURL) {
                // Cari elemen di halaman utama dan isi nilainya
                const inputField = document.getElementById(targetInputId);
                const previewImage = document.getElementById(targetPreviewId);

                if (inputField) {
                    inputField.value = dataURL;
                }
                if (previewImage) {
                    previewImage.src = dataURL;
                    previewImage.style.display = 'block';
                }
            };

            // Fungsi ini dipanggil oleh tombol "Tanda Tangan" untuk membuka popup
            window.openSignaturePopup = function(targetInputId, targetPreviewId) {
                const windowWidth = screen.availWidth;
                const windowHeight = screen.availHeight;
                const left = 0;
                const top = 0;

                // Bangun URL dengan query string untuk memberitahu popup elemen mana yang harus diupdate
                const url =
                    `{{ route('signature.pad') }}?targetInput=${targetInputId}&targetPreview=${targetPreviewId}`;

                // Buka popup window
                window.open(
                    url,
                    'SignatureWindow',
                    `width=${windowWidth},height=${windowHeight},top=${top},left=${left},resizable=yes,scrollbars=yes`
                );
            };

        });
    </script>
@endsection
