@php
    $nutrisi = $data['nutrisi'] ?? [];
    $eliminasi = $data['eliminasi'] ?? [];
    $hygiene = $data['personal_hygiene'] ?? [];
    $istirahat = $data['istirahat_tidur'] ?? [];
    $aktivitas = $data['aktivitas_latihan'] ?? [];
    $neuro = $data['neuro_cerebral'] ?? [];
    $kesadaran = $data['tingkat_kesadaran'] ?? [];
    $fisik = $data['pemeriksaan_fisik'] ?? [];
@endphp

<hr>
<h4 class="text-primary mt-4 font-weight-bold">NUTRISI</h4>
<div class="row">
    <div class="col-md-3 form-group">
        <label>BB (Kg)</label>
        <input type="number" step="0.1" name="nutrisi[bb]" class="form-control" value="{{ $nutrisi['bb'] ?? '' }}">
    </div>
    <div class="col-md-3 form-group">
        <label>TB (Cm)</label>
        <input type="number" step="0.1" name="nutrisi[tb]" class="form-control" value="{{ $nutrisi['tb'] ?? '' }}">
    </div>
</div>
@include('pages.simrs.erm.form.perawat.component.skrining-gizi-mst', [
    'data' => $nutrisi['skrining_mst'] ?? [],
])

<hr>
<h4 class="text-primary mt-4 font-weight-bold">ELIMINASI</h4>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="font-weight-bold">Masalah Perkemihan</label>
            <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text">Frekuensi</span></div>
                <input type="number" name="eliminasi[perkemihan][frekuensi]" class="form-control"
                    value="{{ $eliminasi['perkemihan']['frekuensi'] ?? '' }}">
                <div class="input-group-append"><span class="input-group-text">x/hr</span></div>
            </div>
            <div class="d-flex flex-wrap mt-2">
                @foreach (['Tidak Ada', 'Poliuria', 'Anuria', 'Disuria', 'Hematuria', 'Nocturna', 'Retensi', 'Inkontinen'] as $item)
                    <div class="custom-control custom-checkbox custom-control-inline">
                        <input type="checkbox" id="kemih_{{ Str::slug($item) }}"
                            name="eliminasi[perkemihan][masalah][{{ Str::slug($item) }}]" value="1"
                            class="custom-control-input" @checked(isset($eliminasi['perkemihan']['masalah'][Str::slug($item)]))>
                        <label class="custom-control-label"
                            for="kemih_{{ Str::slug($item) }}">{{ $item }}</label>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="font-weight-bold">Masalah Pencernaan</label>
            <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text">Frekuensi</span></div>
                <input type="number" name="eliminasi[pencernaan][frekuensi]" class="form-control"
                    value="{{ $eliminasi['pencernaan']['frekuensi'] ?? '' }}">
                <div class="input-group-append"><span class="input-group-text">x/hr</span></div>
            </div>
            <div class="d-flex flex-wrap mt-2">
                @foreach (['Tidak Ada', 'Konstipasi', 'Diare', 'Haemoroid', 'Colostomy', 'Melena', 'Nyeri', 'Inkontinen'] as $item)
                    <div class="custom-control custom-checkbox custom-control-inline">
                        <input type="checkbox" id="cerna_{{ Str::slug($item) }}"
                            name="eliminasi[pencernaan][masalah][{{ Str::slug($item) }}]" value="1"
                            class="custom-control-input" @checked(isset($eliminasi['pencernaan']['masalah'][Str::slug($item)]))>
                        <label class="custom-control-label"
                            for="cerna_{{ Str::slug($item) }}">{{ $item }}</label>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<hr>
<h4 class="text-primary mt-4 font-weight-bold">POLA PERSONAL HYGIENE</h4>
<div class="row">
    <div class="col-md-4 form-group">
        <label>Mandi</label>
        <div>
            @foreach (['2x/hr', '1x/hr', 'Tidak tiap hari'] as $item)
                <div class="custom-control custom-radio custom-control-inline"> <input type="radio"
                        id="mandi_{{ Str::slug($item) }}" name="personal_hygiene[mandi]" value="{{ $item }}"
                        class="custom-control-input" @checked(isset($hygiene['mandi']) && $hygiene['mandi'] == $item)> <label class="custom-control-label"
                        for="mandi_{{ Str::slug($item) }}">{{ $item }}</label> </div>
            @endforeach
        </div>
    </div>
    <div class="col-md-4 form-group">
        <label>Keramas</label>
        <div>
            @foreach (['2x/hr', '1x/hr'] as $item)
                <div class="custom-control custom-radio custom-control-inline"> <input type="radio"
                        id="keramas_{{ Str::slug($item) }}" name="personal_hygiene[keramas]"
                        value="{{ $item }}" class="custom-control-input" @checked(isset($hygiene['keramas']) && $hygiene['keramas'] == $item)> <label
                        class="custom-control-label" for="keramas_{{ Str::slug($item) }}">{{ $item }}</label>
                </div>
            @endforeach
            <div class="custom-control custom-radio custom-control-inline d-flex align-items-center"> <input
                    type="radio" id="keramas_lainnya" name="personal_hygiene[keramas]" value="Lainnya"
                    class="custom-control-input" @checked(isset($hygiene['keramas']) && $hygiene['keramas'] == 'Lainnya')> <label class="custom-control-label"
                    for="keramas_lainnya">Lainnya:</label> <input type="text" name="personal_hygiene[keramas_ket]"
                    class="form-control form-control-sm ml-2" style="width: 150px;"
                    value="{{ $hygiene['keramas_ket'] ?? '' }}"> </div>
        </div>
    </div>
    <div class="col-md-4 form-group">
        <label>Gosok Gigi</label>
        <div>
            @foreach (['2x/hr', '1x/hr', 'Tidak tiap hari'] as $item)
                <div class="custom-control custom-radio custom-control-inline"> <input type="radio"
                        id="gosok_gigi_{{ Str::slug($item) }}" name="personal_hygiene[gosok_gigi]"
                        value="{{ $item }}" class="custom-control-input" @checked(isset($hygiene['gosok_gigi']) && $hygiene['gosok_gigi'] == $item)> <label
                        class="custom-control-label"
                        for="gosok_gigi_{{ Str::slug($item) }}">{{ $item }}</label> </div>
            @endforeach
        </div>
    </div>
</div>

<hr>
<h4 class="text-primary mt-4 font-weight-bold">POLA ISTIRAHAT TIDUR</h4>
<div class="row">
    <div class="col-md-4 form-group">
        <label>Pola Tidur</label>
        <div>
            @foreach (['Normal', 'Suka Tidur', 'Gelisah'] as $item)
                <div class="custom-control custom-radio custom-control-inline"> <input type="radio"
                        id="pola_tidur_{{ Str::slug($item) }}" name="istirahat_tidur[pola_tidur]"
                        value="{{ $item }}" class="custom-control-input" @checked(isset($istirahat['pola_tidur']) && $istirahat['pola_tidur'] == $item)> <label
                        class="custom-control-label"
                        for="pola_tidur_{{ Str::slug($item) }}">{{ $item }}</label> </div>
            @endforeach
        </div>
    </div>
    <div class="col-md-4 form-group">
        <label>Kebiasaan Tidur Malam</label>
        <div class="input-group">
            <input type="number" name="istirahat_tidur[kebiasaan_jam]" class="form-control"
                value="{{ $istirahat['kebiasaan_jam'] ?? '' }}">
            <div class="input-group-append"><span class="input-group-text">Jam</span></div>
        </div>
    </div>
    <div class="col-md-4 form-group">
        <label>Istirahat Siang</label>
        <div>
            <div class="custom-control custom-radio custom-control-inline"> <input type="radio"
                    id="istirahat_siang_tidak" name="istirahat_tidur[istirahat_siang]" value="tidak"
                    class="custom-control-input" @checked(isset($istirahat['istirahat_siang']) && $istirahat['istirahat_siang'] == 'tidak')> <label class="custom-control-label"
                    for="istirahat_siang_tidak">Tidak</label> </div>
            <div class="custom-control custom-radio custom-control-inline d-flex align-items-center"> <input
                    type="radio" id="istirahat_siang_ya" name="istirahat_tidur[istirahat_siang]" value="ya"
                    class="custom-control-input" @checked(isset($istirahat['istirahat_siang']) && $istirahat['istirahat_siang'] == 'ya')> <label class="custom-control-label"
                    for="istirahat_siang_ya">Ya, </label> <input type="text"
                    name="istirahat_tidur[istirahat_siang_jam]" class="form-control form-control-sm ml-2"
                    style="width: 100px;" value="{{ $istirahat['istirahat_siang_jam'] ?? '' }}"> <span
                    class="ml-2">jam</span> </div>
        </div>
    </div>
    <div class="col-md-12 form-group">
        <label>Yang Membantu Cepat Tidur</label>
        <div class="d-flex flex-wrap">
            @foreach (['Merokok', 'Musik', 'Minum susu', 'Lampu mati'] as $item)
                <div class="custom-control custom-checkbox custom-control-inline"> <input type="checkbox"
                        id="bantu_tidur_{{ Str::slug($item) }}"
                        name="istirahat_tidur[bantuan_tidur][{{ Str::slug($item) }}]" value="1"
                        class="custom-control-input" @checked(isset($istirahat['bantuan_tidur'][Str::slug($item)]))> <label
                        class="custom-control-label"
                        for="bantu_tidur_{{ Str::slug($item) }}">{{ $item }}</label> </div>
            @endforeach
        </div>
    </div>
</div>

<hr>
<h4 class="text-primary mt-4 font-weight-bold">AKTIVITAS DAN LATIHAN</h4>
<div class="row">
    <div class="col-md-4 form-group"><label>Aktivitas</label>
        <div>
            @foreach (['Mandiri', 'Dibantu total', 'Dibantu sebagian'] as $item)
                <div class="custom-control custom-radio custom-control-inline"> <input type="radio"
                        id="aktivitas_{{ Str::slug($item) }}" name="aktivitas_latihan[aktivitas]"
                        value="{{ $item }}" class="custom-control-input" @checked(isset($aktivitas['aktivitas']) && $aktivitas['aktivitas'] == $item)>
                    <label class="custom-control-label"
                        for="aktivitas_{{ Str::slug($item) }}">{{ $item }}</label>
                </div>
            @endforeach
        </div>
    </div>
    <div class="col-md-4 form-group"><label>Gaya Berjalan</label>
        <div>
            @foreach (['Normal', 'Abnormal', 'Ggn koordinasi'] as $item)
                <div class="custom-control custom-radio custom-control-inline"> <input type="radio"
                        id="gaya_jalan_{{ Str::slug($item) }}" name="aktivitas_latihan[gaya_berjalan]"
                        value="{{ $item }}" class="custom-control-input" @checked(isset($aktivitas['gaya_berjalan']) && $aktivitas['gaya_berjalan'] == $item)>
                    <label class="custom-control-label"
                        for="gaya_jalan_{{ Str::slug($item) }}">{{ $item }}</label>
                </div>
            @endforeach
        </div>
    </div>
    <div class="col-md-4 form-group"><label>Alat Bantu</label>
        <div>
            @foreach (['Tidak ada', 'Kursi roda', 'Tongkat'] as $item)
                <div class="custom-control custom-radio custom-control-inline"> <input type="radio"
                        id="alat_bantu_{{ Str::slug($item) }}" name="aktivitas_latihan[alat_bantu]"
                        value="{{ $item }}" class="custom-control-input" @checked(isset($aktivitas['alat_bantu']) && $aktivitas['alat_bantu'] == $item)>
                    <label class="custom-control-label"
                        for="alat_bantu_{{ Str::slug($item) }}">{{ $item }}</label>
                </div>
            @endforeach
        </div>
    </div>
    <div class="col-md-12 form-group"><label>Persendian</label>
        <div class="d-flex flex-wrap">
            @foreach (['Tidak ada kelainan', 'Susah digerakan', 'Bengkak', 'Nyeri', 'Kontraktur'] as $item)
                <div class="custom-control custom-checkbox custom-control-inline"> <input type="checkbox"
                        id="sendi_{{ Str::slug($item) }}"
                        name="aktivitas_latihan[persendian][{{ Str::slug($item) }}]" value="1"
                        class="custom-control-input" @checked(isset($aktivitas['persendian'][Str::slug($item)]))> <label
                        class="custom-control-label" for="sendi_{{ Str::slug($item) }}">{{ $item }}</label>
                </div>
            @endforeach
        </div>
    </div>
</div>

<hr>
<h4 class="text-primary mt-4 font-weight-bold">FUNGSI NEURO CEREBRAL & TINGKAT KESADARAN</h4>
<div class="form-group">
    <label class="font-weight-bold">Fungsi Neuro Cerebral</label>
    <div class="d-flex flex-wrap">
        @foreach (['Orientasi penuh', 'Gangguan perhatian', 'Gangguan daya ingat', 'Sakit kepala', 'Kesukaan berbicara', 'Gangguan persepsi'] as $item)
            <div class="custom-control custom-checkbox custom-control-inline"> <input type="checkbox"
                    id="neuro_{{ Str::slug($item) }}" name="neuro_cerebral[fungsi][{{ Str::slug($item) }}]"
                    value="1" class="custom-control-input" @checked(isset($neuro['fungsi'][Str::slug($item)]))> <label
                    class="custom-control-label" for="neuro_{{ Str::slug($item) }}">{{ $item }}</label>
            </div>
        @endforeach
    </div>
</div>
<div class="form-group">
    <label class="font-weight-bold">Hemiparise/paralise/tetraparase</label>
    <div>
        <div class="custom-control custom-radio custom-control-inline"> <input type="radio" id="hemi_tidak"
                name="neuro_cerebral[hemiparise]" value="tidak" class="custom-control-input"
                @checked(isset($neuro['hemiparise']) && $neuro['hemiparise'] == 'tidak')> <label class="custom-control-label" for="hemi_tidak">Tidak</label>
        </div>
        <div class="custom-control custom-radio custom-control-inline d-flex align-items-center"> <input
                type="radio" id="hemi_ya" name="neuro_cerebral[hemiparise]" value="ya"
                class="custom-control-input" @checked(isset($neuro['hemiparise']) && $neuro['hemiparise'] == 'ya')> <label class="custom-control-label"
                for="hemi_ya">Ya, Lokasi:</label> <input type="text" name="neuro_cerebral[hemiparise_lokasi]"
                class="form-control form-control-sm ml-2" style="width: 250px;"
                value="{{ $neuro['hemiparise_lokasi'] ?? '' }}"> </div>
    </div>
</div>
<div class="form-group">
    <label class="font-weight-bold">Tingkat Kesadaran Umum</label>
    <div>
        @foreach (['Baik', 'Sedang', 'Lemah'] as $item)
            <div class="custom-control custom-radio custom-control-inline"> <input type="radio"
                    id="kesadaran_umum_{{ Str::slug($item) }}" name="tingkat_kesadaran[umum]"
                    value="{{ $item }}" class="custom-control-input" @checked(isset($kesadaran['umum']) && $kesadaran['umum'] == $item)> <label
                    class="custom-control-label"
                    for="kesadaran_umum_{{ Str::slug($item) }}">{{ $item }}</label> </div>
        @endforeach
    </div>
</div>
<div class="row">
    <div class="col-md-3 form-group">
        <div class="input-group">
            <div class="input-group-prepend"><span class="input-group-text">GCS: E</span></div><input type="number"
                class="form-control" name="tingkat_kesadaran[gcs][e]" value="{{ $kesadaran['gcs']['e'] ?? '' }}">
        </div>
    </div>
    <div class="col-md-3 form-group">
        <div class="input-group">
            <div class="input-group-prepend"><span class="input-group-text">GCS: M</span></div><input type="number"
                class="form-control" name="tingkat_kesadaran[gcs][m]" value="{{ $kesadaran['gcs']['m'] ?? '' }}">
        </div>
    </div>
    <div class="col-md-3 form-group">
        <div class="input-group">
            <div class="input-group-prepend"><span class="input-group-text">GCS: V</span></div><input type="number"
                class="form-control" name="tingkat_kesadaran[gcs][v]" value="{{ $kesadaran['gcs']['v'] ?? '' }}">
        </div>
    </div>
    <div class="col-md-3 form-group">
        <div class="input-group">
            <div class="input-group-prepend"><span class="input-group-text">Total</span></div><input type="number"
                class="form-control" name="tingkat_kesadaran[gcs][total]"
                value="{{ $kesadaran['gcs']['total'] ?? '' }}">
        </div>
    </div>
    <div class="col-md-12 form-group">
        <label>Skor EWS</label>
        <textarea class="form-control" name="tingkat_kesadaran[ews]" rows="2">{{ $kesadaran['ews'] ?? '' }}</textarea>
    </div>
    <div class="col-md-12 form-group">
        <div class="d-flex flex-wrap">
            @foreach (['Composmentis: 15-14', 'Apastis: 13-12', 'Delirium: 11-10', 'Somnolen: 9-7', 'Stupor: 6-4', 'Coma: < 3'] as $item)
                <div class="custom-control custom-checkbox custom-control-inline"> <input type="checkbox"
                        id="kesadaran_{{ Str::slug($item) }}"
                        name="tingkat_kesadaran[kategori][{{ Str::slug($item) }}]" value="1"
                        class="custom-control-input" @checked(isset($kesadaran['kategori'][Str::slug($item)]))> <label
                        class="custom-control-label"
                        for="kesadaran_{{ Str::slug($item) }}">{{ $item }}</label> </div>
            @endforeach
        </div>
    </div>
</div>

<hr>
<h4 class="text-primary mt-4 font-weight-bold">PEMERIKSAAN FISIK (HEAD TO TOE)</h4>
{{-- ... Lanjutkan pola ini untuk semua bagian Pemeriksaan Fisik: Rambut, Mata, Hidung, Telinga, Mulut, Leher, Dada, Abdomen, Kuku, Genitalia, dan Ekstremitas ... --}}
{{-- Contoh untuk Ekstremitas Kekuatan Otot --}}
<div class="form-group">
    <label class="font-weight-bold">Ekstremitas: Kekuatan Otot</label>
    <div class="row">
        <div class="col-md-6">
            <table class="table table-bordered text-center">
                <tr>
                    <td>
                        @for ($i = 1; $i <= 5; $i++)
                            <div class="custom-control custom-checkbox d-inline-block mx-2"><input type="checkbox"
                                    id="otot_a_{{ $i }}"
                                    name="pemeriksaan_fisik[ekstremitas][a][{{ $i }}]" value="1"
                                    class="custom-control-input" @checked(isset($fisik['ekstremitas']['a'][$i]))><label
                                    class="custom-control-label"
                                    for="otot_a_{{ $i }}">{{ $i }}</label></div>
                        @endfor
                    </td>
                </tr>
                <tr>
                    <td style="border-top: 3px solid black;">
                        @for ($i = 1; $i <= 5; $i++)
                            <div class="custom-control custom-checkbox d-inline-block mx-2"><input type="checkbox"
                                    id="otot_c_{{ $i }}"
                                    name="pemeriksaan_fisik[ekstremitas][c][{{ $i }}]" value="1"
                                    class="custom-control-input" @checked(isset($fisik['ekstremitas']['c'][$i]))><label
                                    class="custom-control-label"
                                    for="otot_c_{{ $i }}">{{ $i }}</label></div>
                        @endfor
                    </td>
                </tr>
            </table>
        </div>
        <div class="col-md-6">
            <table class="table table-bordered text-center">
                <tr>
                    <td>
                        @for ($i = 1; $i <= 5; $i++)
                            <div class="custom-control custom-checkbox d-inline-block mx-2"><input type="checkbox"
                                    id="otot_b_{{ $i }}"
                                    name="pemeriksaan_fisik[ekstremitas][b][{{ $i }}]" value="1"
                                    class="custom-control-input" @checked(isset($fisik['ekstremitas']['b'][$i]))><label
                                    class="custom-control-label"
                                    for="otot_b_{{ $i }}">{{ $i }}</label></div>
                        @endfor
                    </td>
                </tr>
                <tr>
                    <td style="border-top: 3px solid black;">
                        @for ($i = 1; $i <= 5; $i++)
                            <div class="custom-control custom-checkbox d-inline-block mx-2"><input type="checkbox"
                                    id="otot_d_{{ $i }}"
                                    name="pemeriksaan_fisik[ekstremitas][d][{{ $i }}]" value="1"
                                    class="custom-control-input" @checked(isset($fisik['ekstremitas']['d'][$i]))><label
                                    class="custom-control-label"
                                    for="otot_d_{{ $i }}">{{ $i }}</label></div>
                        @endfor
                    </td>
                </tr>
            </table>
        </div>
        <div class="col-md-12 form-group">
            <label>Keterangan Ekstremitas</label>
            <input type="text" name="pemeriksaan_fisik[ekstremitas][keterangan]" class="form-control"
                value="{{ $fisik['ekstremitas']['keterangan'] ?? '' }}">
        </div>
    </div>
</div>
