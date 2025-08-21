@php
    $saatIni = $data['riwayat_kehamilan_saat_ini'] ?? [];
    $lalu = $data['riwayat_kehamilan_lalu'] ?? [];
@endphp

<hr>
<h4 class="text-primary mt-4 font-weight-bold">RIWAYAT KEHAMILAN</h4>

{{-- ================= RIWAYAT KEHAMILAN SAAT INI ================= --}}
<div class="card">
    <div class="card-header bg-light"><b>I. Riwayat Kehamilan Saat Ini</b></div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 form-group">
                <label>Alasan Masuk RS</label>
                <input type="text" name="riwayat_kehamilan_saat_ini[alasan_masuk]" class="form-control"
                    value="{{ $saatIni['alasan_masuk'] ?? '' }}">
            </div>
            <div class="col-md-6 form-group">
                <label>Keluhan Utama</label>
                <input type="text" name="riwayat_kehamilan_saat_ini[keluhan_utama]" class="form-control"
                    value="{{ $saatIni['keluhan_utama'] ?? '' }}">
            </div>
        </div>
        <div class="row align-items-end">
            <div class="col-md-4 form-group">
                <label>Riwayat Kehamilan Saat Ini</label>
                <div class="input-group">
                    @foreach (['g', 'p', 'a'] as $item)
                        <div class="input-group-prepend"><span class="input-group-text">{{ strtoupper($item) }}</span>
                        </div>
                        <input type="number" name="riwayat_kehamilan_saat_ini[gpa][{{ $item }}]"
                            class="form-control" value="{{ $saatIni['gpa'][$item] ?? '' }}">
                    @endforeach
                </div>
            </div>
            <div class="col-md-4 form-group">
                <label>HPHT</label>
                <input type="text" name="riwayat_kehamilan_saat_ini[hpht]" class="form-control"
                    value="{{ $saatIni['hpht'] ?? '' }}">
            </div>
            <div class="col-md-4 form-group">
                <label>HPL</label>
                <input type="text" name="riwayat_kehamilan_saat_ini[hpl]" class="form-control"
                    value="{{ $saatIni['hpl'] ?? '' }}">
            </div>
        </div>

        <div class="table-responsive mt-3">
            <table class="table table-bordered">
                <thead class="text-center bg-light">
                    <tr>
                        <th>Kategori</th>
                        <th>Tanggal</th>
                        <th>Pukul</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (['Permulaan HIS', 'Keluhan air ketuban', 'Keluar lendir darah', 'Keluar darah'] as $item)
                        <tr>
                            <td>{{ $item }}</td>
                            <td><input type="date"
                                    name="riwayat_kehamilan_saat_ini[kejadian][{{ Str::slug($item) }}][tanggal]"
                                    class="form-control"
                                    value="{{ $saatIni['kejadian'][Str::slug($item)]['tanggal'] ?? '' }}"></td>
                            <td><input type="time"
                                    name="riwayat_kehamilan_saat_ini[kejadian][{{ Str::slug($item) }}][pukul]"
                                    class="form-control"
                                    value="{{ $saatIni['kejadian'][Str::slug($item)]['pukul'] ?? '' }}"></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="row">
            <div class="col-md-4 form-group"><label>Lingkar perut/Fundus uteri</label><input type="text"
                    name="riwayat_kehamilan_saat_ini[fundus_uteri]" class="form-control"
                    value="{{ $saatIni['fundus_uteri'] ?? '' }}"></div>
            <div class="col-md-4 form-group"><label>Letak janin/Punggung</label><input type="text"
                    name="riwayat_kehamilan_saat_ini[letak_janin]" class="form-control"
                    value="{{ $saatIni['letak_janin'] ?? '' }}"></div>
            <div class="col-md-4 form-group"><label>Presentasi</label><input type="text"
                    name="riwayat_kehamilan_saat_ini[presentasi]" class="form-control"
                    value="{{ $saatIni['presentasi'] ?? '' }}"></div>
            <div class="col-md-4 form-group"><label>DJJ</label><input type="text"
                    name="riwayat_kehamilan_saat_ini[djj]" class="form-control" value="{{ $saatIni['djj'] ?? '' }}">
            </div>
            <div class="col-md-4 form-group"><label>HIS</label><input type="text"
                    name="riwayat_kehamilan_saat_ini[his]" class="form-control" value="{{ $saatIni['his'] ?? '' }}">
            </div>
            <div class="col-md-4 form-group"><label>Periksa dalam</label><input type="text"
                    name="riwayat_kehamilan_saat_ini[periksa_dalam]" class="form-control"
                    value="{{ $saatIni['periksa_dalam'] ?? '' }}"></div>
        </div>

        <div class="form-group">
            <label class="font-weight-bold">Sudah ditangani oleh</label>
            <div class="d-flex flex-wrap">
                @foreach (['Dokter', 'Bidan', 'Dukun'] as $item)
                    <div class="custom-control custom-checkbox custom-control-inline d-flex align-items-center">
                        <input type="checkbox" id="ditangani_{{ Str::slug($item) }}"
                            name="riwayat_kehamilan_saat_ini[ditangani_oleh][{{ Str::slug($item) }}]" value="1"
                            class="custom-control-input" @checked(isset($saatIni['ditangani_oleh'][Str::slug($item)]))>
                        <label class="custom-control-label"
                            for="ditangani_{{ Str::slug($item) }}">{{ $item }}:</label>
                        <input type="text"
                            name="riwayat_kehamilan_saat_ini[ditangani_oleh][{{ Str::slug($item) }}_ket]"
                            class="form-control form-control-sm ml-2"
                            value="{{ $saatIni['ditangani_oleh'][Str::slug($item) . '_ket'] ?? '' }}">
                    </div>
                @endforeach
            </div>
        </div>
        <div class="form-group">
            <label class="font-weight-bold">Kontrol ANC teratur</label>
            <div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="anc_tidak" name="riwayat_kehamilan_saat_ini[anc_teratur]" value="tidak"
                        class="custom-control-input" @checked(isset($saatIni['anc_teratur']) && $saatIni['anc_teratur'] == 'tidak')>
                    <label class="custom-control-label" for="anc_tidak">Tidak</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline d-flex align-items-center">
                    <input type="radio" id="anc_ya" name="riwayat_kehamilan_saat_ini[anc_teratur]"
                        value="ya" class="custom-control-input" @checked(isset($saatIni['anc_teratur']) && $saatIni['anc_teratur'] == 'ya')>
                    <label class="custom-control-label" for="anc_ya">Ya, dimana:</label>
                    <input type="text" name="riwayat_kehamilan_saat_ini[anc_teratur_ket]"
                        class="form-control form-control-sm ml-2" value="{{ $saatIni['anc_teratur_ket'] ?? '' }}">
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ================= RIWAYAT KEHAMILAN LALU ================= --}}
<div class="card mt-4">
    <div class="card-header bg-light"><b>II. Riwayat Kehamilan, Persalinan, Nifas yang Lalu</b></div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered text-center">
                <thead class="bg-light">
                    <tr>
                        <th rowspan="2" class="align-middle">No</th>
                        <th rowspan="2" class="align-middle">Umur Kehamilan</th>
                        <th colspan="5">Anak</th>
                        <th rowspan="2" class="align-middle">Jenis Persalinan</th>
                        <th rowspan="2" class="align-middle">Keadaan Nifas</th>
                        <th rowspan="2" class="align-middle">Penolong</th>
                    </tr>
                    <tr>
                        <th>L/P</th>
                        <th>H/M</th>
                        <th>Lahir Thn</th>
                        <th>BB Lahir</th>
                        <th>Keadaan</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($i = 0; $i < 5; $i++)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td><input type="text" class="form-control"
                                    name="riwayat_kehamilan_lalu[{{ $i }}][umur_kehamilan]"
                                    value="{{ $lalu[$i]['umur_kehamilan'] ?? '' }}"></td>
                            <td><input type="text" class="form-control"
                                    name="riwayat_kehamilan_lalu[{{ $i }}][lp]"
                                    value="{{ $lalu[$i]['lp'] ?? '' }}"></td>
                            <td><input type="text" class="form-control"
                                    name="riwayat_kehamilan_lalu[{{ $i }}][hm]"
                                    value="{{ $lalu[$i]['hm'] ?? '' }}"></td>
                            <td><input type="text" class="form-control"
                                    name="riwayat_kehamilan_lalu[{{ $i }}][lahir_thn]"
                                    value="{{ $lalu[$i]['lahir_thn'] ?? '' }}"></td>
                            <td><input type="text" class="form-control"
                                    name="riwayat_kehamilan_lalu[{{ $i }}][bb_lahir]"
                                    value="{{ $lalu[$i]['bb_lahir'] ?? '' }}"></td>
                            <td><input type="text" class="form-control"
                                    name="riwayat_kehamilan_lalu[{{ $i }}][keadaan]"
                                    value="{{ $lalu[$i]['keadaan'] ?? '' }}"></td>
                            <td><input type="text" class="form-control"
                                    name="riwayat_kehamilan_lalu[{{ $i }}][jenis_persalinan]"
                                    value="{{ $lalu[$i]['jenis_persalinan'] ?? '' }}"></td>
                            <td><input type="text" class="form-control"
                                    name="riwayat_kehamilan_lalu[{{ $i }}][keadaan_nifas]"
                                    value="{{ $lalu[$i]['keadaan_nifas'] ?? '' }}"></td>
                            <td><input type="text" class="form-control"
                                    name="riwayat_kehamilan_lalu[{{ $i }}][penolong]"
                                    value="{{ $lalu[$i]['penolong'] ?? '' }}"></td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
</div>
