@php
    $riwayatKesehatan = $data['riwayat_kesehatan'] ?? [];
    $riwayatKelahiran = $data['riwayat_kelahiran'] ?? [];
    $pengkajianKhusus = $data['pengkajian_khusus_neonatus'] ?? [];
@endphp

<hr>
<h4 class="text-primary mt-4 font-weight-bold">I. RIWAYAT KESEHATAN</h4>
<div class="form-group">
    <label>Keluhan Utama</label>
    <input type="text" name="riwayat_kesehatan[keluhan_utama]" class="form-control"
        value="{{ $riwayatKesehatan['keluhan_utama'] ?? '' }}">
</div>

<h5 class="font-weight-bold mt-3">A. RIWAYAT KESEHATAN YANG LALU</h5>
<div class="form-group">
    <label>Dirawat di RS</label>
    <div class="d-flex align-items-center flex-wrap">
        @foreach (['Operasi', 'Kecelakaan'] as $item)
            <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" id="dirawat_{{ Str::slug($item) }}"
                    name="riwayat_kesehatan[dirawat_di_rs][pilihan]" value="{{ $item }}"
                    class="custom-control-input" @checked(isset($riwayatKesehatan['dirawat_di_rs']['pilihan']) && $riwayatKesehatan['dirawat_di_rs']['pilihan'] == $item)>
                <label class="custom-control-label" for="dirawat_{{ Str::slug($item) }}">{{ $item }}</label>
            </div>
        @endforeach
        <div class="custom-control custom-radio custom-control-inline d-flex align-items-center">
            <input type="radio" id="dirawat_lain" name="riwayat_kesehatan[dirawat_di_rs][pilihan]"
                value="Penyakit Lain" class="custom-control-input" @checked(isset($riwayatKesehatan['dirawat_di_rs']['pilihan']) &&
                        $riwayatKesehatan['dirawat_di_rs']['pilihan'] == 'Penyakit Lain')>
            <label class="custom-control-label" for="dirawat_lain">Penyakit Lain:</label>
            <input type="text" name="riwayat_kesehatan[dirawat_di_rs][keterangan]"
                class="form-control form-control-sm mx-2"
                value="{{ $riwayatKesehatan['dirawat_di_rs']['keterangan'] ?? '' }}">
            <label>Tahun:</label>
            <input type="text" name="riwayat_kesehatan[dirawat_di_rs][tahun]"
                class="form-control form-control-sm ml-2" style="width: 80px;"
                value="{{ $riwayatKesehatan['dirawat_di_rs']['tahun'] ?? '' }}">
        </div>
    </div>
</div>
<div class="form-group">
    <label>Riwayat Alergi</label>
    <div>
        <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" id="alergi_tidak" name="riwayat_kesehatan[riwayat_alergi]" value="Tidak Ada"
                class="custom-control-input" @checked(isset($riwayatKesehatan['riwayat_alergi']) && $riwayatKesehatan['riwayat_alergi'] == 'Tidak Ada')>
            <label class="custom-control-label" for="alergi_tidak">Tidak Ada</label>
        </div>
        <div class="custom-control custom-radio custom-control-inline d-flex align-items-center">
            <input type="radio" id="alergi_ada" name="riwayat_kesehatan[riwayat_alergi]" value="Ada"
                class="custom-control-input" @checked(isset($riwayatKesehatan['riwayat_alergi']) && $riwayatKesehatan['riwayat_alergi'] == 'Ada')>
            <label class="custom-control-label" for="alergi_ada">Ada, Sebutkan:</label>
            <input type="text" name="riwayat_kesehatan[riwayat_alergi_ket]" class="form-control form-control-sm ml-2"
                value="{{ $riwayatKesehatan['riwayat_alergi_ket'] ?? '' }}">
        </div>
    </div>
</div>
<div class="form-group">
    <label>Obat Yang Digunakan / Dibawa Dari Rumah</label>
    @include('pages.simrs.erm.form.perawat.component.tabel-obat', [
        'prefix' => 'riwayat_kesehatan[obat_dibawa]',
        'data' => $riwayatKesehatan['obat_dibawa'] ?? [],
    ])
</div>

<h5 class="font-weight-bold mt-3">B. RIWAYAT PENYAKIT KELUARGA</h5>
<div class="d-flex flex-wrap">
    @foreach (['Tidak Ada', 'Asma', 'Hipertensi', 'Jantung', 'DM'] as $item)
        <div class="custom-control custom-checkbox custom-control-inline">
            <input type="checkbox" id="keluarga_{{ Str::slug($item) }}"
                name="riwayat_kesehatan[penyakit_keluarga][{{ Str::slug($item) }}]" value="1"
                class="custom-control-input" @checked(isset($riwayatKesehatan['penyakit_keluarga'][Str::slug($item)]))>
            <label class="custom-control-label" for="keluarga_{{ Str::slug($item) }}">{{ $item }}</label>
        </div>
    @endforeach
    <div class="custom-control custom-checkbox custom-control-inline d-flex align-items-center">
        <input type="checkbox" id="keluarga_lainnya" name="riwayat_kesehatan[penyakit_keluarga][lainnya]" value="1"
            class="custom-control-input" @checked(isset($riwayatKesehatan['penyakit_keluarga']['lainnya']))>
        <label class="custom-control-label" for="keluarga_lainnya">Lainnya:</label>
        <input type="text" name="riwayat_kesehatan[penyakit_keluarga][lainnya_ket]"
            class="form-control form-control-sm ml-2"
            value="{{ $riwayatKesehatan['penyakit_keluarga']['lainnya_ket'] ?? '' }}">
    </div>
</div>

<h5 class="font-weight-bold mt-3">C. RIWAYAT KELAHIRAN</h5>
<div class="row">
    <div class="col-md-3 form-group"><label>Cara Lahir</label>
        <div>
            @foreach (['Normal', 'SC'] as $item)
                <div class="custom-control custom-radio custom-control-inline"><input type="radio"
                        id="cara_lahir_{{ $item }}" name="riwayat_kelahiran[cara_lahir]"
                        value="{{ $item }}" class="custom-control-input" @checked(isset($riwayatKelahiran['cara_lahir']) && $riwayatKelahiran['cara_lahir'] == $item)><label
                        class="custom-control-label" for="cara_lahir_{{ $item }}">{{ $item }}</label>
                </div>
            @endforeach
        </div>
    </div>
    <div class="col-md-3 form-group"><label>Umur kehamilan ibu</label>
        <div class="input-group"><input type="number" name="riwayat_kelahiran[umur_kehamilan]" class="form-control"
                value="{{ $riwayatKelahiran['umur_kehamilan'] ?? '' }}">
            <div class="input-group-append"><span class="input-group-text">mg</span></div>
        </div>
    </div>
    <div class="col-md-3 form-group"><label>BB anak saat lahir</label>
        <div class="input-group"><input type="number" name="riwayat_kelahiran[bb_lahir]" class="form-control"
                value="{{ $riwayatKelahiran['bb_lahir'] ?? '' }}">
            <div class="input-group-append"><span class="input-group-text">gr</span></div>
        </div>
    </div>
    <div class="col-md-3 form-group"><label>TB anak saat lahir</label>
        <div class="input-group"><input type="number" name="riwayat_kelahiran[tb_lahir]" class="form-control"
                value="{{ $riwayatKelahiran['tb_lahir'] ?? '' }}">
            <div class="input-group-append"><span class="input-group-text">cm</span></div>
        </div>
    </div>
    <div class="col-md-3 form-group"><label>Lingkar kepala</label>
        <div class="input-group"><input type="number" name="riwayat_kelahiran[lingkar_kepala]" class="form-control"
                value="{{ $riwayatKelahiran['lingkar_kepala'] ?? '' }}">
            <div class="input-group-append"><span class="input-group-text">cm</span></div>
        </div>
    </div>
    <div class="col-md-3 form-group"><label>Lingkar dada</label>
        <div class="input-group"><input type="number" name="riwayat_kelahiran[lingkar_dada]" class="form-control"
                value="{{ $riwayatKelahiran['lingkar_dada'] ?? '' }}">
            <div class="input-group-append"><span class="input-group-text">cm</span></div>
        </div>
    </div>
    <div class="col-md-3 form-group"><label>Lingkar perut</label>
        <div class="input-group"><input type="number" name="riwayat_kelahiran[lingkar_perut]" class="form-control"
                value="{{ $riwayatKelahiran['lingkar_perut'] ?? '' }}">
            <div class="input-group-append"><span class="input-group-text">cm</span></div>
        </div>
    </div>
    <div class="col-md-3 form-group"><label>Bayi mendapat susu formula umur</label>
        <div class="input-group"><input type="number" name="riwayat_kelahiran[susu_formula_umur]"
                class="form-control" value="{{ $riwayatKelahiran['susu_formula_umur'] ?? '' }}">
            <div class="input-group-append"><span class="input-group-text">hari</span></div>
        </div>
    </div>
    <div class="col-md-6 form-group"><label>Warna ketuban</label><input type="text"
            name="riwayat_kelahiran[warna_ketuban]" class="form-control"
            value="{{ $riwayatKelahiran['warna_ketuban'] ?? '' }}"></div>
    <div class="col-md-3 form-group"><label>Pemberian Vit K</label><input type="text"
            name="riwayat_kelahiran[vit_k]" class="form-control" value="{{ $riwayatKelahiran['vit_k'] ?? '' }}">
    </div>
    <div class="col-md-3 form-group"><label>Gentamicin tetes mata</label><input type="text"
            name="riwayat_kelahiran[gentamicin]" class="form-control"
            value="{{ $riwayatKelahiran['gentamicin'] ?? '' }}"></div>
</div>

<h5 class="font-weight-bold mt-3">D. PENGKAJIAN KHUSUS NEONATUS</h5>
<h6><b>1. KHUSUS BAYI SAKIT RUJUKAN UGD/POLIKLINIK</b></h6>
<div class="form-group">
    <label>Nama panggilan anak:</label>
    <input type="text" name="pengkajian_khusus_neonatus[nama_panggilan]" class="form-control"
        value="{{ $pengkajianKhusus['nama_panggilan'] ?? '' }}">
</div>
<div class="form-group">
    <label>Riwayat Imunisasi</label>
    <div>
        @foreach (['Hepatitis B 0', 'Polio 0', 'BCG'] as $item)
            <div class="custom-control custom-checkbox custom-control-inline"><input type="checkbox"
                    id="imunisasi_neo_{{ Str::slug($item) }}"
                    name="pengkajian_khusus_neonatus[imunisasi][{{ Str::slug($item) }}]" value="1"
                    class="custom-control-input" @checked(isset($pengkajianKhusus['imunisasi'][Str::slug($item)]))><label class="custom-control-label"
                    for="imunisasi_neo_{{ Str::slug($item) }}">{{ $item }}</label></div>
        @endforeach
    </div>
</div>
<h6 class="mt-3"><b>2. BAYI SEHAT DAN SAKIT (Intranatal)</b></h6>
<div class="row">
    <div class="col-md-6 form-group"><label>By Ny:</label><input type="text"
            name="pengkajian_khusus_neonatus[intranatal][by_ny]" class="form-control"
            value="{{ $pengkajianKhusus['intranatal']['by_ny'] ?? '' }}"></div>
    <div class="col-md-6 form-group"><label>Lahir Tanggal & Jam</label>
        <div class="input-group"><input type="date" name="pengkajian_khusus_neonatus[intranatal][tgl_lahir]"
                class="form-control" value="{{ $pengkajianKhusus['intranatal']['tgl_lahir'] ?? '' }}"><input
                type="time" name="pengkajian_khusus_neonatus[intranatal][jam_lahir]" class="form-control"
                value="{{ $pengkajianKhusus['intranatal']['jam_lahir'] ?? '' }}"></div>
    </div>
    <div class="col-md-6 form-group">
        <label>Status Gestasi</label>
        <div class="input-group">
            @foreach (['g', 'p', 'a', 'h'] as $item)
                <div class="input-group-prepend"><span class="input-group-text">{{ strtoupper($item) }}</span></div>
                <input type="number" name="pengkajian_khusus_neonatus[intranatal][gestasi][{{ $item }}]"
                    class="form-control" value="{{ $pengkajianKhusus['intranatal']['gestasi'][$item] ?? '' }}">
            @endforeach
        </div>
    </div>
    <div class="col-md-6 form-group"><label>Masa Gestasi</label>
        <div class="input-group"><input type="number" name="pengkajian_khusus_neonatus[intranatal][masa_gestasi]"
                class="form-control" value="{{ $pengkajianKhusus['intranatal']['masa_gestasi'] ?? '' }}">
            <div class="input-group-append"><span class="input-group-text">mg</span></div>
        </div>
    </div>
    <div class="col-md-12 form-group"><label>Bayi dilahirkan scr. Spontan/SC dibantu oleh:</label><input
            type="text" name="pengkajian_khusus_neonatus[intranatal][dibantu_oleh]" class="form-control"
            value="{{ $pengkajianKhusus['intranatal']['dibantu_oleh'] ?? '' }}"></div>
</div>
@include('pages.simrs.erm.form.perawat.component.skor-apgar', [
    'data' => $pengkajianKhusus['intranatal']['apgar'] ?? [],
])
