@php
    // Ambil data dari array utama, atau array kosong jika belum ada
    $riwayatKesehatan = $data['riwayat_kesehatan'] ?? [];
    $riwayatKesehatanLalu = $data['riwayat_kesehatan_lalu'] ?? [];
    $riwayatAlergi = $data['riwayat_alergi'] ?? [];
    $riwayatKeluarga = $data['riwayat_kesehatan_keluarga'] ?? [];
@endphp

<h4 class="text-primary mt-4 font-weight-bold">RIWAYAT KESEHATAN</h4>

{{-- Keluhan Utama & Alasan Masuk --}}
<div class="row">
    <div class="col-md-6 form-group">
        <label>Keluhan Utama</label>
        <input name="riwayat_kesehatan[keluhan_utama]" type="text" class="form-control"
            value="{{ $riwayatKesehatan['keluhan_utama'] ?? '' }}">
    </div>
    <div class="col-md-6 form-group">
        <label>Alasan Masuk RS</label>
        <input name="riwayat_kesehatan[alasan_masuk]" type="text" class="form-control"
            value="{{ $riwayatKesehatan['alasan_masuk'] ?? '' }}">
    </div>
</div>

{{-- Riwayat Penyakit Dahulu --}}
<div class="form-group">
    <label class="font-weight-bold">Riwayat Penyakit Dahulu</label>
    <div class="d-flex flex-wrap">
        @foreach (['Tidak ada', 'Asma', 'Hipertensi', 'Jantung', 'DM'] as $item)
            <div class="custom-control custom-checkbox custom-control-inline">
                <input type="checkbox" id="penyakit_dahulu_{{ Str::slug($item) }}"
                    name="riwayat_kesehatan[penyakit_dahulu][{{ Str::slug($item) }}]" value="1"
                    class="custom-control-input" @checked(isset($riwayatKesehatan['penyakit_dahulu'][Str::slug($item)]))>
                <label class="custom-control-label"
                    for="penyakit_dahulu_{{ Str::slug($item) }}">{{ $item }}</label>
            </div>
        @endforeach
        <div class="custom-control custom-checkbox custom-control-inline d-flex align-items-center">
            <input type="checkbox" id="penyakit_dahulu_lainnya" name="riwayat_kesehatan[penyakit_dahulu][lainnya]"
                value="1" class="custom-control-input" @checked(isset($riwayatKesehatan['penyakit_dahulu']['lainnya']))>
            <label class="custom-control-label" for="penyakit_dahulu_lainnya">Lainnya:</label>
            <input type="text" name="riwayat_kesehatan[penyakit_dahulu][lainnya_ket]"
                class="form-control form-control-sm ml-2" style="width: 200px;"
                value="{{ $riwayatKesehatan['penyakit_dahulu']['lainnya_ket'] ?? '' }}">
        </div>
    </div>
</div>

{{-- Riwayat Kesehatan Lalu --}}
<h4 class="text-primary mt-4 font-weight-bold">RIWAYAT KESEHATAN LALU</h4>
<div class="row">
    @foreach (['dirawat' => 'Di Rawat di RS', 'operasi' => 'Operasi', 'transfusi' => 'Riwayat Transfusi'] as $key => $label)
        <div class="col-md-4">
            <div class="form-group">
                <label class="font-weight-bold">{{ $label }}</label>
                <div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="{{ $key }}_ya"
                            name="riwayat_kesehatan_lalu[{{ $key }}][status]" value="ya"
                            class="custom-control-input" @checked(isset($riwayatKesehatanLalu[$key]['status']) && $riwayatKesehatanLalu[$key]['status'] == 'ya')>
                        <label class="custom-control-label" for="{{ $key }}_ya">Ya</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="{{ $key }}_tidak"
                            name="riwayat_kesehatan_lalu[{{ $key }}][status]" value="tidak"
                            class="custom-control-input" @checked(isset($riwayatKesehatanLalu[$key]['status']) && $riwayatKesehatanLalu[$key]['status'] == 'tidak')>
                        <label class="custom-control-label" for="{{ $key }}_tidak">Tidak</label>
                    </div>
                </div>
                <label class="mt-2">Bila Ya, Keterangan (Kapan/Dimana/Reaksi):</label>
                <input type="text" name="riwayat_kesehatan_lalu[{{ $key }}][keterangan]"
                    class="form-control" value="{{ $riwayatKesehatanLalu[$key]['keterangan'] ?? '' }}">
            </div>
        </div>
    @endforeach
</div>

{{-- ========================================================== --}}
{{-- BAGIAN YANG DILENGKAPI --}}
{{-- ========================================================== --}}

{{-- Alergi dan Reaksi --}}
<h4 class="text-primary mt-4 font-weight-bold">ALERGI DAN REAKSI</h4>
<div class="form-group">
    <div class="d-flex flex-wrap">
        @foreach (['Obat-obatan', 'Makanan', 'Debu/udara'] as $item)
            <div class="custom-control custom-checkbox custom-control-inline">
                <input type="checkbox" id="alergi_{{ Str::slug($item) }}"
                    name="riwayat_alergi[{{ Str::slug($item) }}]" value="1" class="custom-control-input"
                    @checked(isset($riwayatAlergi[Str::slug($item)]))>
                <label class="custom-control-label" for="alergi_{{ Str::slug($item) }}">{{ $item }}</label>
            </div>
        @endforeach
        <div class="custom-control custom-checkbox custom-control-inline d-flex align-items-center">
            <input type="checkbox" id="alergi_lainnya" name="riwayat_alergi[lainnya]" value="1"
                class="custom-control-input" @checked(isset($riwayatAlergi['lainnya']))>
            <label class="custom-control-label" for="alergi_lainnya">Alergi lainnya:</label>
            <input type="text" name="riwayat_alergi[lainnya_ket]" class="form-control form-control-sm ml-2"
                style="width: 200px;" value="{{ $riwayatAlergi['lainnya_ket'] ?? '' }}">
        </div>
    </div>
</div>

{{-- Riwayat Kesehatan Keluarga --}}
<h4 class="text-primary mt-4 font-weight-bold">RIWAYAT KESEHATAN KELUARGA</h4>
<div class="form-group">
    <div class="d-flex flex-wrap">
        @foreach (['Tidak ada', 'Asma', 'Hipertensi', 'Jantung', 'DM'] as $item)
            <div class="custom-control custom-checkbox custom-control-inline">
                <input type="checkbox" id="keskel_{{ Str::slug($item) }}"
                    name="riwayat_kesehatan_keluarga[{{ Str::slug($item) }}]" value="1"
                    class="custom-control-input" @checked(isset($riwayatKeluarga[Str::slug($item)]))>
                <label class="custom-control-label" for="keskel_{{ Str::slug($item) }}">{{ $item }}</label>
            </div>
        @endforeach
        <div class="custom-control custom-checkbox custom-control-inline d-flex align-items-center">
            <input type="checkbox" id="keskel_lainnya" name="riwayat_kesehatan_keluarga[lainnya]" value="1"
                class="custom-control-input" @checked(isset($riwayatKeluarga['lainnya']))>
            <label class="custom-control-label" for="keskel_lainnya">Riwayat kesehatan keluarga lainnya:</label>
            <input type="text" name="riwayat_kesehatan_keluarga[lainnya_ket]"
                class="form-control form-control-sm ml-2" style="width: 200px;"
                value="{{ $riwayatKeluarga['lainnya_ket'] ?? '' }}">
        </div>
    </div>
</div>
