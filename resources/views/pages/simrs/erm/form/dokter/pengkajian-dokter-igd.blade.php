@extends('pages.simrs.erm.index')
@section('erm')
    @if (isset($registration) || $registration != null)
        @php
            function field_igd($field, $pengkajian, $triage, $default = '')
            {
                if (
                    isset($pengkajian) &&
                    (isset($pengkajian->$field) &&
                        ($pengkajian->$field !== '' || $pengkajian->$field === 0 || $pengkajian->$field === '0'))
                ) {
                    return $pengkajian->$field;
                }
                if (
                    isset($triage) &&
                    (isset($triage->$field) &&
                        ($triage->$field !== '' || $triage->$field === 0 || $triage->$field === '0'))
                ) {
                    return $triage->$field;
                }
                return $default;
            }
        @endphp
        <div class="tab-content p-3">
            <div class="tab-pane fade show active" id="tab_default-1" role="tabpanel">
                @include('pages.simrs.erm.partials.detail-pasien')
                <hr style="border-color: #868686; margin-bottom: 50px;">
                <header class="text-primary text-center font-weight-bold mb-4">
                    <div id="alert-pengkajian-igd"></div>
                    <h2 class="font-weight-bold">PENGKAJIAN DOKTER IGD</h2>
                </header>
                <form action="javascript:void(0)" id="pengkajian-dokter-igd-form">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="registration_id" value="{{ $registration->id }}">

                    <h4 class="frame-heading text-primary">ASESMEN AWAL MEDIS</h4>
                    <div class="frame-wrap">
                        <div class="form-group">
                            <label class="form-label" for="keluhan_utama">Keluhan Utama</label>
                            <textarea name="keluhan_utama" id="keluhan_utama" class="form-control" rows="3">{{ field_igd('keluhan_utama', $pengkajian, $triage) }}</textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="riwayat_penyakit_sekarang">Riwayat Penyakit Sekarang</label>
                            <textarea name="riwayat_penyakit_sekarang" id="riwayat_penyakit_sekarang" class="form-control" rows="3">{{ field_igd('riwayat_penyakit_sekarang', $pengkajian, $triage) }}</textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="riwayat_penyakit_dahulu">Riwayat Penyakit Dahulu</label>
                            <textarea name="riwayat_penyakit_dahulu" id="riwayat_penyakit_dahulu" class="form-control" rows="3">{{ field_igd('riwayat_penyakit_dahulu', $pengkajian, $triage) }}</textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Riwayat Alergi</label>
                            <div class="d-flex align-items-center">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input" name="riwayat_alergi"
                                        id="riwayat_alergi_tidak" value="Tidak"
                                        {{ field_igd('riwayat_alergi', $pengkajian, $triage, 'Tidak') == 'Tidak' ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="riwayat_alergi_tidak">Tidak</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input" name="riwayat_alergi"
                                        id="riwayat_alergi_ya" value="Ya"
                                        {{ field_igd('riwayat_alergi', $pengkajian, $triage) == 'Ya' ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="riwayat_alergi_ya">Ya, Sebutkan</label>
                                </div>
                                <input type="text" name="riwayat_alergi_text" id="riwayat_alergi_text"
                                    class="form-control form-control-sm ml-2" style="width: 50%;"
                                    value="{{ field_igd('riwayat_alergi_text', $pengkajian, $triage) }}">
                            </div>
                        </div>
                    </div>

                    <h4 class="frame-heading text-primary mt-4">PEMERIKSAAN FISIK</h4>
                    <div class="frame-wrap">
                        <div class="form-group row align-items-center">
                            <label class="form-label col-md-3">Keadaan Umum</label>
                            <div class="col-md-9">
                                @foreach (['Baik', 'Sakit Ringan', 'Sakit Sedang', 'Sakit Berat'] as $keadaan)
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" class="custom-control-input" name="keadaan_umum"
                                            id="keadaan_umum_{{ Str::slug($keadaan) }}" value="{{ $keadaan }}"
                                            {{ field_igd('keadaan_umum', $pengkajian, $triage) == $keadaan ? 'checked' : '' }}>
                                        <label class="custom-control-label"
                                            for="keadaan_umum_{{ Str::slug($keadaan) }}">{{ $keadaan }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="form-group row align-items-center">
                            <label class="form-label col-md-3">Kesadaran (GCS)</label>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text">E</span></div>
                                            <input type="number" name="gcse" id="gcse" class="form-control gcs-sum"
                                                value="{{ field_igd('gcse', $pengkajian, $triage) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text">M</span></div>
                                            <input type="number" name="gcsm" id="gcsm" class="form-control gcs-sum"
                                                value="{{ field_igd('gcsm', $pengkajian, $triage) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text">V</span></div>
                                            <input type="number" name="gcsv" id="gcsv"
                                                class="form-control gcs-sum"
                                                value="{{ field_igd('gcsv', $pengkajian, $triage) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text">Total</span>
                                            </div>
                                            <input type="text" name="gcstotal" id="gcstotal" class="form-control"
                                                readonly value="{{ field_igd('gcstotal', $pengkajian, $triage) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3"></div>
                            <div class="col-md-9">
                                <div class="row">
                                    @php
                                        $kesadaran_options = [
                                            'Composmentis : 15-14',
                                            'Apatis : 13-12',
                                            'Delirium : 11-10',
                                            'Somnolen : 9-7',
                                            'Stupor : 6-4',
                                            'Coma : < 3',
                                        ];
                                    @endphp
                                    @foreach ($kesadaran_options as $kesadaran)
                                        <div class="col-md-4">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input"
                                                    name="tingkat_kesadaran" id="kesadaran_{{ Str::slug($kesadaran) }}"
                                                    value="{{ $kesadaran }}"
                                                    {{ field_igd('tingkat_kesadaran', $pengkajian, $triage) == $kesadaran ? 'checked' : '' }}>
                                                <label class="custom-control-label"
                                                    for="kesadaran_{{ Str::slug($kesadaran) }}">{{ $kesadaran }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="form-group row align-items-center">
                            <label class="form-label col-md-3">Tanda Vital</label>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-4 mb-2">
                                        <label class="form-label" for="bb_triage">BB</label>
                                        <div class="input-group">
                                            <input type="text" name="bb_triage" id="bb_triage" class="form-control"
                                                value="{{ field_igd('bb_triage', $pengkajian, $triage) }}">
                                            <div class="input-group-append"><span class="input-group-text">Kg</span></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label class="form-label" for="tb_triage">TB</label>
                                        <div class="input-group">
                                            <input type="text" name="tb_triage" id="tb_triage" class="form-control"
                                                value="{{ field_igd('tb_triage', $pengkajian, $triage) }}">
                                            <div class="input-group-append"><span class="input-group-text">Cm</span></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-2">
                                        <label class="form-label" for="td">TD</label>
                                        <div class="input-group">
                                            <input type="text" name="td" id="td" class="form-control"
                                                value="{{ field_igd('td', $pengkajian, $triage, field_igd('bp', $pengkajian, $triage)) }}">
                                            <div class="input-group-append"><span class="input-group-text">mmHg</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label class="form-label" for="pr_triage">Nadi</label>
                                        <div class="input-group">
                                            <input type="text" name="pr_triage" id="pr_triage" class="form-control"
                                                value="{{ field_igd('pr_triage', $pengkajian, $triage, field_igd('pr', $pengkajian, $triage)) }}">
                                            <div class="input-group-append"><span class="input-group-text">x/mnt</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label class="form-label" for="rr_triage">RR</label>
                                        <div class="input-group">
                                            <input type="text" name="rr_triage" id="rr_triage" class="form-control"
                                                value="{{ field_igd('rr_triage', $pengkajian, $triage, field_igd('rr', $pengkajian, $triage)) }}">
                                            <div class="input-group-append"><span class="input-group-text">x/mnt</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label class="form-label" for="sb">SB</label>
                                        <div class="input-group">
                                            <input type="text" name="sb" id="sb" class="form-control"
                                                value="{{ field_igd('sb', $pengkajian, $triage, field_igd('temperatur', $pengkajian, $triage)) }}">
                                            <div class="input-group-append"><span class="input-group-text">°C</span></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label class="form-label" for="dokterSPO2">SPO2</label>
                                        <div class="input-group">
                                            <input type="text" name="dokterSPO2" id="dokterSPO2" class="form-control"
                                                value="{{ field_igd('dokterSPO2', $pengkajian, $triage, field_igd('sp02', $pengkajian, $triage)) }}">
                                            <div class="input-group-append"><span class="input-group-text">%</span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h4 class="frame-heading text-primary mt-4">STATUS GENERALIS</h4>
                    <div class="frame-wrap">
                        <div class="row">
                            @php
                                $generalis_options = [
                                    'Kepala',
                                    'Mata',
                                    'Mulut',
                                    'Leher',
                                    'Dada',
                                    'Perut',
                                    'Alat Gerak',
                                    'Genitalia/Anus',
                                ];
                                $saved_generalis_raw = field_igd('status_generalis', $pengkajian, $triage, []);
                                $saved_generalis = is_string($saved_generalis_raw)
                                    ? json_decode($saved_generalis_raw, true)
                                    : $saved_generalis_raw;
                                if (!is_array($saved_generalis)) {
                                    $saved_generalis = [];
                                }
                            @endphp
                            @foreach ($generalis_options as $item)
                                <div class="col-md-3 mb-3">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox"
                                                name="generalis_check[{{ $item }}]" value="1"
                                                id="generalis_{{ Str::slug($item) }}"
                                                {{ isset($saved_generalis[$item]) ? 'checked' : '' }}>
                                            <label class="custom-control-label"
                                                for="generalis_{{ Str::slug($item) }}">{{ $item }}</label>
                                        </div>
                                        <input class="form-control mt-1" name="generalis_text[{{ $item }}]"
                                            id="isi_generalis_{{ Str::slug($item) }}" type="text"
                                            value="{{ $saved_generalis[$item] ?? '' }}">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <h4 class="frame-heading text-primary mt-4">STATUS LOKALIS</h4>
                    <div class="frame-wrap">
                        <div class="form-group">
                            <textarea class="form-control" id="status_lokalis" name="status_lokalis" rows="4">{{ field_igd('status_lokalis', $pengkajian, $triage) }}</textarea>
                        </div>
                    </div>

                    <h4 class="frame-heading text-primary mt-4">PEMERIKSAAN PENUNJANG</h4>
                    <div class="frame-wrap">
                        @php
                            $penunjang_raw = field_igd('pemeriksaan_penunjang', $pengkajian, $triage, []);
                            $penunjang = is_string($penunjang_raw) ? json_decode($penunjang_raw, true) : $penunjang_raw;
                            if (!is_array($penunjang)) {
                                $penunjang = [];
                            }
                        @endphp
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox"
                                        name="penunjang_check[laboratorium]" id="laboratorium" value="1"
                                        {{ $penunjang['laboratorium']['checked'] ?? false ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="laboratorium">Laboratorium</label>
                                </div>
                                <input type="text" name="penunjang_text[laboratorium]" id="laboratorium_text"
                                    class="form-control mt-1" value="{{ $penunjang['laboratorium']['text'] ?? '' }}">
                            </div>
                            <div class="col-md-6 form-group">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" name="penunjang_check[ekg]"
                                        id="ekg" value="1"
                                        {{ $penunjang['ekg']['checked'] ?? false ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="ekg">EKG, Kesan</label>
                                </div>
                                <input type="text" name="penunjang_text[ekg]" id="ekg_text"
                                    class="form-control mt-1" value="{{ $penunjang['ekg']['text'] ?? '' }}">
                            </div>
                            <div class="col-md-6 form-group">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" name="penunjang_check[radiologi]"
                                        id="radiologi" value="1"
                                        {{ $penunjang['radiologi']['checked'] ?? false ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="radiologi">Radiologi</label>
                                </div>
                                <input type="text" name="penunjang_text[radiologi]" id="radiologi_text"
                                    class="form-control mt-1" value="{{ $penunjang['radiologi']['text'] ?? '' }}">
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="form-label">Pemeriksaan Lainnya</label>
                                <div>
                                    <div class="custom-control custom-checkbox custom-control-inline">
                                        <input class="custom-control-input" type="checkbox"
                                            name="penunjang_check[pemeriksaan_lainnya]" id="pemeriksaan_lainnya"
                                            value="1"
                                            {{ $penunjang['pemeriksaan_lainnya']['checked'] ?? false ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="pemeriksaan_lainnya">Lainnya</label>
                                    </div>
                                    <div class="custom-control custom-checkbox custom-control-inline">
                                        <input class="custom-control-input" type="checkbox"
                                            name="penunjang_check[rapid_antigen]" id="rapid_antigen" value="1"
                                            {{ $penunjang['rapid_antigen']['checked'] ?? false ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="rapid_antigen">Rapid Antigen</label>
                                    </div>
                                    <div class="custom-control custom-checkbox custom-control-inline">
                                        <input class="custom-control-input" type="checkbox"
                                            name="penunjang_check[rapid_antibody]" id="rapid_antibody" value="1"
                                            {{ $penunjang['rapid_antibody']['checked'] ?? false ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="rapid_antibody">Rapid Antibody</label>
                                    </div>
                                </div>
                                <input type="text" name="penunjang_text[pemeriksaan_lainnya]"
                                    id="pemeriksaan_lainnya_text" class="form-control mt-1"
                                    value="{{ $penunjang['pemeriksaan_lainnya']['text'] ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <label class="form-label" for="diagnosa_kerja">Diagnosa Kerja</label>
                            <textarea name="diagnosa_kerja" id="diagnosa_kerja" class="form-control" rows="3">{{ field_igd('diagnosa_kerja', $pengkajian, $triage) }}</textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="diagnosa_banding">Diagnosa Banding</label>
                            <textarea name="diagnosa_banding" id="diagnosa_banding" class="form-control" rows="3">{{ field_igd('diagnosa_banding', $pengkajian, $triage) }}</textarea>
                        </div>
                    </div>

                    <h4 class="frame-heading text-primary mt-4">TERAPI ATAU TINDAKAN</h4>
                    <div class="frame-wrap">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label" for="jam_tindakan">Jam</label>
                                    <input type="time" name="jam_tindakan" id="jam_tindakan" class="form-control"
                                        value="{{ field_igd('jam_tindakan', $pengkajian, $triage) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="terapi_tindakan">Terapi/Tindakan</label>
                                    <textarea class="form-control" id="terapi_tindakan" name="terapi_tindakan" rows="4">{{ field_igd('terapi_tindakan', $pengkajian, $triage) }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label" for="diberikan_oleh">Diberikan Oleh</label>
                                    <textarea class="form-control" id="diberikan_oleh" name="diberikan_oleh" rows="4">{{ field_igd('diberikan_oleh', $pengkajian, $triage) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h4 class="frame-heading text-primary mt-4">KESIMPULAN AKHIR & TINDAK LANJUT</h4>
                    <div class="frame-wrap">
                        <div class="form-group">
                            <label class="form-label">Kondisi Saat Pulang</label>
                            <div class="d-flex align-items-center">
                                @php
                                    $kondisi_pulang_options = ['Membaik', 'Memburuk', 'Tetap', 'DAA', 'Meninggal'];
                                @endphp
                                @foreach ($kondisi_pulang_options as $kondisi)
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input class="custom-control-input" type="radio" name="kondisi_pulang"
                                            id="kondisi_pulang_{{ Str::slug($kondisi) }}" value="{{ $kondisi }}"
                                            {{ field_igd('kondisi_pulang', $pengkajian, $triage) == $kondisi ? 'checked' : '' }}>
                                        <label class="custom-control-label"
                                            for="kondisi_pulang_{{ Str::slug($kondisi) }}">{{ $kondisi }}</label>
                                    </div>
                                @endforeach
                                <div class="input-group ml-3" style="width: 250px;">
                                    <div class="input-group-prepend"><span class="input-group-text">Jam Meninggal</span>
                                    </div>
                                    <input type="time" name="jam_meninggal" id="jam_meninggal" class="form-control"
                                        value="{{ field_igd('jam_meninggal', $pengkajian, $triage) }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tanda Vital Saat Pulang</label>
                            <div class="row">
                                <div class="col-md-3">
                                    <label>TD</label>
                                    <div class="input-group">
                                        <input type="text" name="td_pulang" id="td_pulang" class="form-control"
                                            value="{{ field_igd('td_pulang', $pengkajian, $triage) }}">
                                        <div class="input-group-append"><span class="input-group-text">mmHg</span></div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label>Nadi</label>
                                    <div class="input-group">
                                        <input type="text" name="nadi_pulang" id="nadi_pulang" class="form-control"
                                            value="{{ field_igd('nadi_pulang', $pengkajian, $triage) }}">
                                        <div class="input-group-append"><span class="input-group-text">x/mnt</span></div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label>RR</label>
                                    <div class="input-group">
                                        <input type="text" name="rr_pulang" id="rr_pulang" class="form-control"
                                            value="{{ field_igd('rr_pulang', $pengkajian, $triage) }}">
                                        <div class="input-group-append"><span class="input-group-text">x/mnt</span></div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label>SB</label>
                                    <div class="input-group">
                                        <input type="text" name="sb_pulang" id="sb_pulang" class="form-control"
                                            value="{{ field_igd('sb_pulang', $pengkajian, $triage) }}">
                                        <div class="input-group-append"><span class="input-group-text">°C</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="terapi_pulang">Terapi Pulang</label>
                            <textarea class="form-control" id="terapi_pulang" name="terapi_pulang" rows="4">{{ field_igd('terapi_pulang', $pengkajian, $triage) }}</textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tindak Lanjut</label>
                            @php
                                $tindak_lanjut_options = [
                                    'Rawat Inap',
                                    'Rawat Jalan',
                                    'Kontrol ke poliklinik/puskesmas',
                                    'Rujuk dengan ambulan',
                                    'Rujuk tanpa ambulan',
                                    'Menolak Rawat Inap',
                                    'Rujuk Ke',
                                ];
                                $saved_tindak_lanjut_raw = field_igd('tindak_lanjut', $pengkajian, $triage, []);
                                $saved_tindak_lanjut = is_string($saved_tindak_lanjut_raw)
                                    ? json_decode($saved_tindak_lanjut_raw, true)
                                    : $saved_tindak_lanjut_raw;
                                if (!is_array($saved_tindak_lanjut)) {
                                    $saved_tindak_lanjut = [];
                                }
                            @endphp
                            <div class="row">
                                @foreach ($tindak_lanjut_options as $tindak)
                                    <div class="col-md-4 mb-2">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" name="tindak_lanjut[]"
                                                id="tindak_lanjut_{{ Str::slug($tindak) }}" value="{{ $tindak }}"
                                                {{ in_array($tindak, $saved_tindak_lanjut) ? 'checked' : '' }}>
                                            <label class="custom-control-label"
                                                for="tindak_lanjut_{{ Str::slug($tindak) }}">{{ $tindak }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <label class="form-label" for="tindak_lanjut_rujuk_ke_text">Tujuan Rujuk</label>
                                    <input type="text" name="tindak_lanjut_rujuk_ke_text"
                                        id="tindak_lanjut_rujuk_ke_text" class="form-control"
                                        value="{{ field_igd('tindak_lanjut_rujuk_ke_text', $pengkajian, $triage) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="alasan_rujuk">Alasan Rujuk</label>
                                    <input type="text" name="alasan_rujuk" id="alasan_rujuk" class="form-control"
                                        value="{{ field_igd('alasan_rujuk', $pengkajian, $triage) }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <h4 class="frame-heading text-primary mt-4">EDUKASI</h4>
                    <div class="frame-wrap">
                        <div class="form-group">
                            <label class="form-label">Edukasi awal disampaikan tentang diagnosa, rencana dan tujuan terapi
                                kepada</label>
                            @php
                                $edukasi_penerima_raw = field_igd('edukasi_penerima', $pengkajian, $triage, []);
                                $edukasi_penerima = is_string($edukasi_penerima_raw)
                                    ? json_decode($edukasi_penerima_raw, true)
                                    : $edukasi_penerima_raw;
                                if (!is_array($edukasi_penerima)) {
                                    $edukasi_penerima = [];
                                }
                            @endphp
                            <div>
                                <div class="custom-control custom-checkbox custom-control-inline">
                                    <input class="custom-control-input" type="checkbox" name="edukasi_penerima[]"
                                        id="edukasi_pasien" value="Pasien"
                                        {{ in_array('Pasien', $edukasi_penerima) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="edukasi_pasien">Pasien</label>
                                </div>
                                <div class="custom-control custom-checkbox custom-control-inline">
                                    <input class="custom-control-input" type="checkbox" name="edukasi_penerima[]"
                                        id="edukasi_keluarga" value="Keluarga"
                                        {{ in_array('Keluarga', $edukasi_penerima) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="edukasi_keluarga">Keluarga</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" name="edukasi_tidak_dapat_diberikan"
                                    id="edukasi_tidak_dapat_diberikan" value="1"
                                    {{ field_igd('edukasi_tidak_dapat_diberikan', $pengkajian, $triage) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="edukasi_tidak_dapat_diberikan">Tidak dapat
                                    memberikan edukasi kepada pasien atau keluarga karena:</label>
                            </div>
                            <input type="text" name="edukasi_alasan" id="edukasi_alasan" class="form-control mt-1"
                                value="{{ field_igd('edukasi_alasan', $pengkajian, $triage) }}">
                        </div>
                    </div>

                    @include('pages.simrs.erm.partials.signature-field', [
                        'judul' => 'Dokter IGD,',
                        'pic' => $pengkajian?->dokter_name ?? auth()->user()->employee->fullname,
                        'role' => 'dokter',
                        'prefix' => 'pengkajian',
                        'signature_model' => $pengkajian?->signature,
                    ])

                    <div class="row">
                        <div class="col-md-12 px-3">
                            <div class="card-actionbar">
                                <div class="card-actionbar-row d-flex justify-content-end align-items-center">
                                    <div style="width: 40%" class="d-flex justify-content-between">
                                        <button type="button"
                                            class="btn btn-warning waves-effect text-white waves-light save-form d-flex align-items-center"
                                            data-form-id="pengkajian-dokter-igd-form" data-status="0"
                                            id="sd-pengkajian-dokter-igd">
                                            <i class='bx bx-save mr-2'></i> Simpan (draft)
                                        </button>
                                        <button type="button"
                                            class="btn btn-primary waves-effect waves-light save-form d-flex align-items-center"
                                            data-form-id="pengkajian-dokter-igd-form" data-status="1"
                                            id="sf-pengkajian-dokter-igd">
                                            <i class='bx bxs-save mr-2'></i> Simpan (final)
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endsection
@section('plugin-erm')
    <script>
        $(document).ready(function() {
            $('.gcs-sum').on('keyup change', function() {
                let e = parseInt($('#gcse').val()) || 0;
                let m = parseInt($('#gcsm').val()) || 0;
                let v = parseInt($('#gcsv').val()) || 0;
                $('#gcstotal').val(e + m + v);
            });

            function toggleAlergiText() {
                if ($('input[name="riwayat_alergi"]:checked').val() === 'Ya') {
                    $('#riwayat_alergi_text').prop('disabled', false);
                } else {
                    $('#riwayat_alergi_text').prop('disabled', true).val('');
                }
            }
            $('input[name="riwayat_alergi"]').on('change', toggleAlergiText);
            toggleAlergiText();

            function toggleEdukasiAlasan() {
                if ($('#edukasi_tidak_dapat_diberikan').is(':checked')) {
                    $('#edukasi_alasan').prop('disabled', false);
                } else {
                    $('#edukasi_alasan').prop('disabled', true).val('');
                }
            }
            $('#edukasi_tidak_dapat_diberikan').on('change', toggleEdukasiAlasan);
            toggleEdukasiAlasan();
        });
    </script>
    @include('pages.simrs.erm.partials.action-js.pengkajian-dokter-igd')
@endsection
