@php
    $pendidikan = $data['riwayat_pendidikan'] ?? '';
    $psikososial = $data['riwayat_psikososial'] ?? [];
    $komunikasi = $data['riwayat_komunikasi'] ?? [];
    $kebudayaan = $data['riwayat_kebudayaan'] ?? [];
@endphp

<h4 class="text-primary mt-4 font-weight-bold">RIWAYAT PENDIDIKAN, PSIKOSOSIAL, SPIRITUAL, KOMUNIKASI & KEBUDAYAAN</h4>

{{-- Riwayat Pendidikan --}}
<div class="form-group">
    <label class="font-weight-bold">Pendidikan Terakhir</label>
    <div>
        @foreach (['SD', 'SMP', 'SLTA', 'PT'] as $item)
            <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" id="pendidikan_{{ $item }}" name="riwayat_pendidikan"
                    value="{{ $item }}" class="custom-control-input" @checked($pendidikan == $item)>
                <label class="custom-control-label" for="pendidikan_{{ $item }}">{{ $item }}</label>
            </div>
        @endforeach
    </div>
</div>

{{-- Riwayat Psikososial dan Spiritual --}}
<div class="row">
    <div class="col-md-3 form-group">
        <label>Agama</label>
        <select class="form-control" name="riwayat_psikososial[agama]">
            <option value=""></option>
            @foreach (['Islam', 'Katolik', 'Protestan', 'Hindu', 'Budha', 'Konghucu'] as $agama)
                <option value="{{ $agama }}" @selected(isset($psikososial['agama']) && $psikososial['agama'] == $agama)>{{ $agama }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="row">
    @foreach ([
        'yakin_sembuh' => 'Keyakinan terhadap penyembuhan',
        'sosialisasi_lingkungan' => 'Sosialisasi dengan lingkungan sekitar',
        'dampingan_agama' => 'Butuh dampingan pemuka agama',
        'ibadah' => 'Menjalankan ibadah',
    ] as $key => $label)
        <div class="col-md-6 form-group">
            <label>{{ $label }}</label>
            <div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="{{ $key }}_ya" name="riwayat_psikososial[{{ $key }}]"
                        value="ya" class="custom-control-input" @checked(isset($psikososial[$key]) && $psikososial[$key] == 'ya')>
                    <label class="custom-control-label" for="{{ $key }}_ya">Ya</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="{{ $key }}_tidak"
                        name="riwayat_psikososial[{{ $key }}]" value="tidak" class="custom-control-input"
                        @checked(isset($psikososial[$key]) && $psikososial[$key] == 'tidak')>
                    <label class="custom-control-label" for="{{ $key }}_tidak">Tidak</label>
                </div>
            </div>
        </div>
    @endforeach
</div>

{{-- Riwayat Komunikasi --}}
<div class="row">
    <div class="col-md-6 form-group">
        <label class="font-weight-bold">Komunikasi Verbal</label>
        <div>
            @foreach (['Normal', 'Aphasia', 'Gagap'] as $item)
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="verbal_{{ Str::slug($item) }}" name="riwayat_komunikasi[verbal]"
                        value="{{ $item }}" class="custom-control-input" @checked(isset($komunikasi['verbal']) && $komunikasi['verbal'] == $item)>
                    <label class="custom-control-label"
                        for="verbal_{{ Str::slug($item) }}">{{ $item }}</label>
                </div>
            @endforeach
        </div>
    </div>
    <div class="col-md-6 form-group">
        <label class="font-weight-bold">Komunikasi Non Verbal</label>
        <div>
            @foreach (['Gambar', 'Isyarat', 'Tulisan'] as $item)
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="non_verbal_{{ Str::slug($item) }}" name="riwayat_komunikasi[non_verbal]"
                        value="{{ $item }}" class="custom-control-input" @checked(isset($komunikasi['non_verbal']) && $komunikasi['non_verbal'] == $item)>
                    <label class="custom-control-label"
                        for="non_verbal_{{ Str::slug($item) }}">{{ $item }}</label>
                </div>
            @endforeach
        </div>
    </div>
    <div class="col-md-12 form-group">
        <label class="font-weight-bold">Bahasa Sehari-hari</label>
        <div class="d-flex flex-wrap">
            @foreach (['Indonesia', 'Daerah'] as $item)
                <div class="custom-control custom-checkbox custom-control-inline">
                    <input type="checkbox" id="bahasa_{{ Str::slug($item) }}"
                        name="riwayat_komunikasi[bahasa][{{ Str::slug($item) }}]" value="1"
                        class="custom-control-input" @checked(isset($komunikasi['bahasa'][Str::slug($item)]))>
                    <label class="custom-control-label"
                        for="bahasa_{{ Str::slug($item) }}">{{ $item }}</label>
                </div>
            @endforeach
            <div class="custom-control custom-checkbox custom-control-inline d-flex align-items-center">
                <input type="checkbox" id="bahasa_lainnya" name="riwayat_komunikasi[bahasa][lainnya]" value="1"
                    class="custom-control-input" @checked(isset($komunikasi['bahasa']['lainnya']))>
                <label class="custom-control-label" for="bahasa_lainnya">Lainnya:</label>
                <input type="text" name="riwayat_komunikasi[bahasa][lainnya_ket]"
                    class="form-control form-control-sm ml-2" style="width: 200px;"
                    value="{{ $komunikasi['bahasa']['lainnya_ket'] ?? '' }}">
            </div>
        </div>
    </div>
</div>

{{-- Riwayat Kebudayaan --}}
<div class="form-group">
    <label class="font-weight-bold">Klien Berasal dari Suku</label>
    <div class="d-flex flex-wrap">
        @foreach (['Sunda', 'Jawa'] as $item)
            <div class="custom-control custom-checkbox custom-control-inline">
                <input type="checkbox" id="suku_{{ Str::slug($item) }}"
                    name="riwayat_kebudayaan[suku][{{ Str::slug($item) }}]" value="1" class="custom-control-input"
                    @checked(isset($kebudayaan['suku'][Str::slug($item)]))>
                <label class="custom-control-label" for="suku_{{ Str::slug($item) }}">{{ $item }}</label>
            </div>
        @endforeach
        <div class="custom-control custom-checkbox custom-control-inline d-flex align-items-center">
            <input type="checkbox" id="suku_lainnya" name="riwayat_kebudayaan[suku][lainnya]" value="1"
                class="custom-control-input" @checked(isset($kebudayaan['suku']['lainnya']))>
            <label class="custom-control-label" for="suku_lainnya">Lainnya:</label>
            <input type="text" name="riwayat_kebudayaan[suku][lainnya_ket]"
                class="form-control form-control-sm ml-2" style="width: 200px;"
                value="{{ $kebudayaan['suku']['lainnya_ket'] ?? '' }}">
        </div>
    </div>
</div>
<div class="form-group">
    <label>Nilai-nilai kepercayaan hubungan dengan adat dan kebudayaan</label>
    <input type="text" name="riwayat_kebudayaan[nilai_kepercayaan]" class="form-control"
        value="{{ $kebudayaan['nilai_kepercayaan'] ?? '' }}">
</div>

{{-- Respon Emosi & Kognitif --}}
<div class="form-group">
    <label class="font-weight-bold">Respon Emosi dan Kognitif</label>
    <div class="d-flex flex-wrap">
        @foreach (['Takut terhadap lingkungan dan tindakan di RS', 'Tidak mampu menahan diri', 'Marah/Tegang', 'Rendah diri', 'Sedih', 'Gelisah', 'Menangis'] as $item)
            <div class="custom-control custom-checkbox custom-control-inline">
                <input type="checkbox" id="respon_{{ Str::slug($item) }}"
                    name="respon_emosi_kognitif[{{ Str::slug($item) }}]" value="1" class="custom-control-input"
                    @checked(isset($data['respon_emosi_kognitif'][Str::slug($item)]))>
                <label class="custom-control-label" for="respon_{{ Str::slug($item) }}">{{ $item }}</label>
            </div>
        @endforeach
    </div>
</div>

{{-- Informasi Diinginkan --}}
<div class="form-group">
    <label class="font-weight-bold">Pasien dan keluarga menginginkan informasi tentang</label>
    <div class="d-flex flex-wrap">
        @foreach (['Penyakit yang diderita', 'Tindakan Pengobatan', 'Perencanaan Diet', 'Perawat di Rumah'] as $item)
            <div class="custom-control custom-checkbox custom-control-inline">
                <input type="checkbox" id="info_{{ Str::slug($item) }}"
                    name="informasi_diinginkan[{{ Str::slug($item) }}]" value="1" class="custom-control-input"
                    @checked(isset($data['informasi_diinginkan'][Str::slug($item)]))>
                <label class="custom-control-label" for="info_{{ Str::slug($item) }}">{{ $item }}</label>
            </div>
        @endforeach
    </div>
</div>
