@extends('pages.simrs.erm.index')
@section('erm')
    {{-- content start --}}
    @if (isset($registration) || $registration != null)
        @php
            // Helper: ambil data pengkajian jika ada, jika tidak pakai triage untuk field yang sama
            function field_igd($field, $pengkajian, $triage, $default = '')
            {
                // Cek pengkajian dulu, jika null/empty, baru cek triage
                if (!empty($pengkajian?->$field) || $pengkajian?->$field === 0 || $pengkajian?->$field === '0') {
                    return $pengkajian->$field;
                }
                if (!empty($triage?->$field) || $triage?->$field === 0 || $triage?->$field === '0') {
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

                    {{-- =================================================================================================================================== --}}
                    {{-- ASESMEN AWAL MEDIS --}}
                    {{-- =================================================================================================================================== --}}
                    <header class="text-primary mb-4">
                        <h4 class="font-weight-bold">ASESMEN AWAL MEDIS</h4>
                    </header>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label for="keluhan_utama">Keluhan Utama</label>
                                <textarea name="keluhan_utama" id="keluhan_utama" class="form-control">{{ field_igd('keluhan_utama', $pengkajian, $triage) }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label for="riwayat_penyakit_sekarang">Riwayat Penyakit Sekarang</label>
                                <textarea name="riwayat_penyakit_sekarang" id="riwayat_penyakit_sekarang" class="form-control">{{ field_igd('riwayat_penyakit_sekarang', $pengkajian, $triage) }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label for="riwayat_penyakit_dahulu">Riwayat Penyakit Dahulu</label>
                                <textarea name="riwayat_penyakit_dahulu" id="riwayat_penyakit_dahulu" class="form-control">{{ field_igd('riwayat_penyakit_dahulu', $pengkajian, $triage) }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="control-label font-weight-bold">Riwayat Alergi</label>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input" name="riwayat_alergi"
                                            id="riwayat_alergi_tidak" value="Tidak"
                                            {{ field_igd('riwayat_alergi', $pengkajian, $triage, 'Tidak') == 'Tidak' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="riwayat_alergi_tidak">Tidak</label>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input" name="riwayat_alergi"
                                            id="riwayat_alergi_ya" value="Ya"
                                            {{ field_igd('riwayat_alergi', $pengkajian, $triage) == 'Ya' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="riwayat_alergi_ya">Ya, Sebutkan</label>
                                        <input type="text" name="riwayat_alergi_text" id="riwayat_alergi_text"
                                            class="form-control form-control-sm d-inline" style="width: 70%;"
                                            value="{{ field_igd('riwayat_alergi_text', $pengkajian, $triage) }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="mt-4">

                    {{-- =================================================================================================================================== --}}
                    {{-- PEMERIKSAAN FISIK --}}
                    {{-- =================================================================================================================================== --}}
                    <header class="text-primary my-4">
                        <h4 class="font-weight-bold">PEMERIKSAAN FISIK</h4>
                    </header>
                    <div class="row align-items-center mb-3">
                        <div class="col-md-3"><label class="font-weight-bold">Keadaan Umum</label></div>
                        <div class="col-md-9">
                            <div class="row">
                                @foreach (['Baik', 'Sakit Ringan', 'Sakit Sedang', 'Sakit Berat'] as $keadaan)
                                    <div class="col-auto">
                                        <div class="form-check">
                                            <input type="radio" class="form-check-input" name="keadaan_umum"
                                                id="keadaan_umum_{{ Str::slug($keadaan) }}" value="{{ $keadaan }}"
                                                {{ field_igd('keadaan_umum', $pengkajian, $triage) == $keadaan ? 'checked' : '' }}>
                                            <label class="form-check-label"
                                                for="keadaan_umum_{{ Str::slug($keadaan) }}">{{ $keadaan }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="row align-items-center mb-3">
                        <div class="col-md-3"><label class="font-weight-bold">Kesadaran (GCS)</label></div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="gcse">E:</label>
                                        <input type="number" name="gcse" id="gcse" class="form-control gcs-sum"
                                            value="{{ field_igd('gcse', $pengkajian, $triage) }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="gcsm">M:</label>
                                        <input type="number" name="gcsm" id="gcsm" class="form-control gcs-sum"
                                            value="{{ field_igd('gcsm', $pengkajian, $triage) }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="gcsv">V:</label>
                                        <input type="number" name="gcsv" id="gcsv" class="form-control gcs-sum"
                                            value="{{ field_igd('gcsv', $pengkajian, $triage) }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="gcstotal">Total:</label>
                                        <input type="text" name="gcstotal" id="gcstotal" class="form-control"
                                            readonly value="{{ field_igd('gcstotal', $pengkajian, $triage) }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
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
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <input type="radio" class="form-check-input" name="tingkat_kesadaran"
                                                id="kesadaran_{{ Str::slug($kesadaran) }}" value="{{ $kesadaran }}"
                                                {{ field_igd('tingkat_kesadaran', $pengkajian, $triage) == $kesadaran ? 'checked' : '' }}>
                                            <label class="form-check-label"
                                                for="kesadaran_{{ Str::slug($kesadaran) }}">{{ $kesadaran }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="row align-items-center mb-3">
                        <div class="col-md-3"><label class="font-weight-bold">Tanda Vital</label></div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="bb_triage">BB</label>
                                        <div class="input-group">
                                            <input type="text" name="bb_triage" id="bb_triage" class="form-control"
                                                value="{{ field_igd('bb_triage', $pengkajian, $triage) }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text">Kg</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tb_triage">TB</label>
                                        <div class="input-group">
                                            <input type="text" name="tb_triage" id="tb_triage" class="form-control"
                                                value="{{ field_igd('tb_triage', $pengkajian, $triage) }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text">Cm</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="td">TD</label>
                                        <div class="input-group">
                                            <input type="text" name="td" id="td" class="form-control"
                                                value="{{ field_igd('td', $pengkajian, $triage, field_igd('bp', $pengkajian, $triage)) }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text">mmHg</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="pr_triage">Nadi</label>
                                        <div class="input-group">
                                            <input type="text" name="pr_triage" id="pr_triage" class="form-control"
                                                value="{{ field_igd('pr_triage', $pengkajian, $triage, field_igd('pr', $pengkajian, $triage)) }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text">x/mnt</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="rr_triage">RR</label>
                                        <div class="input-group">
                                            <input type="text" name="rr_triage" id="rr_triage" class="form-control"
                                                value="{{ field_igd('rr_triage', $pengkajian, $triage, field_igd('rr', $pengkajian, $triage)) }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text">x/mnt</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="sb">SB</label>
                                        <div class="input-group">
                                            <input type="text" name="sb" id="sb" class="form-control"
                                                value="{{ field_igd('sb', $pengkajian, $triage, field_igd('temperatur', $pengkajian, $triage)) }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text">°C</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="dokterSPO2">SPO2</label>
                                        <div class="input-group">
                                            <input type="text" name="dokterSPO2" id="dokterSPO2" class="form-control"
                                                value="{{ field_igd('dokterSPO2', $pengkajian, $triage, field_igd('sp02', $pengkajian, $triage)) }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="mt-4">

                    {{-- =================================================================================================================================== --}}
                    {{-- STATUS GENERALIS & LOKALIS --}}
                    {{-- =================================================================================================================================== --}}
                    <header class="text-primary my-4">
                        <h4 class="font-weight-bold">STATUS GENERALIS</h4>
                    </header>
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
                            // In controller, save this as a JSON: json_encode(['Kepala' => 'text', 'Mata' => 'text'])
                            $saved_generalis = field_igd('status_generalis', $pengkajian, $triage, []);
                        @endphp
                        @foreach ($generalis_options as $item)
                            <div class="col-md-3 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox"
                                        name="generalis_check[{{ $item }}]" value="1"
                                        id="generalis_{{ Str::slug($item) }}"
                                        {{ isset($saved_generalis[$item]) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="generalis_{{ Str::slug($item) }}">
                                        {{ $item }}
                                    </label>
                                </div>
                                <input class="form-control" name="generalis_text[{{ $item }}]"
                                    id="isi_generalis_{{ Str::slug($item) }}" type="text"
                                    value="{{ $saved_generalis[$item] ?? '' }}">
                            </div>
                        @endforeach
                    </div>

                    <header class="text-primary my-4">
                        <h4 class="font-weight-bold">STATUS LOKALIS</h4>
                    </header>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <textarea class="form-control" id="status_lokalis" name="status_lokalis" rows="4">{{ field_igd('status_lokalis', $pengkajian, $triage) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <hr class="mt-4">

                    {{-- =================================================================================================================================== --}}
                    {{-- PEMERIKSAAN PENUNJANG & DIAGNOSA --}}
                    {{-- =================================================================================================================================== --}}
                    <header class="text-primary my-4">
                        <h4 class="font-weight-bold">PEMERIKSAAN PENUNJANG</h4>
                    </header>
                    @php
                        // In controller, save this as JSON: json_encode(['laboratorium' => ['checked' => true, 'text' => '...']])
                        $penunjang = field_igd('pemeriksaan_penunjang', $pengkajian, $triage, '[]');
                    @endphp
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="penunjang_check[laboratorium]"
                                    id="laboratorium" value="1"
                                    {{ $penunjang['laboratorium']['checked'] ?? false ? 'checked' : '' }}>
                                <label class="form-check-label" for="laboratorium">Laboratorium</label>
                            </div>
                            <input type="text" name="penunjang_text[laboratorium]" id="laboratorium_text"
                                class="form-control" value="{{ $penunjang['laboratorium']['text'] ?? '' }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="penunjang_check[ekg]"
                                    id="ekg" value="1"
                                    {{ $penunjang['ekg']['checked'] ?? false ? 'checked' : '' }}>
                                <label class="form-check-label" for="ekg">EKG, Kesan</label>
                            </div>
                            <input type="text" name="penunjang_text[ekg]" id="ekg_text" class="form-control"
                                value="{{ $penunjang['ekg']['text'] ?? '' }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="penunjang_check[radiologi]"
                                    id="radiologi" value="1"
                                    {{ $penunjang['radiologi']['checked'] ?? false ? 'checked' : '' }}>
                                <label class="form-check-label" for="radiologi">Radiologi</label>
                            </div>
                            <input type="text" name="penunjang_text[radiologi]" id="radiologi_text"
                                class="form-control" value="{{ $penunjang['radiologi']['text'] ?? '' }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold">Pemeriksaan Lainnya</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox"
                                        name="penunjang_check[pemeriksaan_lainnya]" id="pemeriksaan_lainnya"
                                        value="1"
                                        {{ $penunjang['pemeriksaan_lainnya']['checked'] ?? false ? 'checked' : '' }}>
                                    <label class="form-check-label" for="pemeriksaan_lainnya">Lainnya</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="penunjang_check[rapid_antigen]"
                                        id="rapid_antigen" value="1"
                                        {{ $penunjang['rapid_antigen']['checked'] ?? false ? 'checked' : '' }}>
                                    <label class="form-check-label" for="rapid_antigen">Rapid Antigen</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox"
                                        name="penunjang_check[rapid_antibody]" id="rapid_antibody" value="1"
                                        {{ $penunjang['rapid_antibody']['checked'] ?? false ? 'checked' : '' }}>
                                    <label class="form-check-label" for="rapid_antibody">Rapid Antibody</label>
                                </div>
                            </div>
                            <input type="text" name="penunjang_text[pemeriksaan_lainnya]"
                                id="pemeriksaan_lainnya_text" class="form-control"
                                value="{{ $penunjang['pemeriksaan_lainnya']['text'] ?? '' }}">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label class="font-weight-bold" for="diagnosa_kerja">Diagnosa Kerja</label>
                                <textarea name="diagnosa_kerja" id="diagnosa_kerja" class="form-control">{{ field_igd('diagnosa_kerja', $pengkajian, $triage) }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="diagnosa_banding">Diagnosa Banding</label>
                                <textarea name="diagnosa_banding" id="diagnosa_banding" class="form-control">{{ field_igd('diagnosa_banding', $pengkajian, $triage) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <hr class="mt-4">

                    {{-- =================================================================================================================================== --}}
                    {{-- TERAPI ATAU TINDAKAN --}}
                    {{-- =================================================================================================================================== --}}
                    <header class="text-primary my-4">
                        <h4 class="font-weight-bold">TERAPI ATAU TINDAKAN</h4>
                    </header>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="jam_tindakan">Jam</label>
                                <input type="time" name="jam_tindakan" id="jam_tindakan" class="form-control"
                                    value="{{ field_igd('jam_tindakan', $pengkajian, $triage) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="terapi_tindakan">Terapi/Tindakan</label>
                                <textarea class="form-control" id="terapi_tindakan" name="terapi_tindakan" rows="4">{{ field_igd('terapi_tindakan', $pengkajian, $triage) }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="diberikan_oleh">Diberikan Oleh</label>
                                <textarea class="form-control" id="diberikan_oleh" name="diberikan_oleh" rows="4">{{ field_igd('diberikan_oleh', $pengkajian, $triage) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <hr class="mt-4">

                    {{-- =================================================================================================================================== --}}
                    {{-- KESIMPULAN AKHIR & TINDAK LANJUT --}}
                    {{-- =================================================================================================================================== --}}
                    <header class="text-primary my-4">
                        <h4 class="font-weight-bold">KESIMPULAN AKHIR & TINDAK LANJUT</h4>
                    </header>
                    <div class="form-group">
                        <label class="font-weight-bold">Kondisi Saat Pulang</label>
                        <div class="row">
                            @php
                                $kondisi_pulang_options = ['Membaik', 'Memburuk', 'Tetap', 'DAA', 'Meninggal'];
                            @endphp
                            @foreach ($kondisi_pulang_options as $kondisi)
                                <div class="col-auto">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="kondisi_pulang"
                                            id="kondisi_pulang_{{ Str::slug($kondisi) }}" value="{{ $kondisi }}"
                                            {{ field_igd('kondisi_pulang', $pengkajian, $triage) == $kondisi ? 'checked' : '' }}>
                                        <label class="form-check-label"
                                            for="kondisi_pulang_{{ Str::slug($kondisi) }}">{{ $kondisi }}</label>
                                    </div>
                                </div>
                            @endforeach
                            <div class="col-md-4">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Jam Meninggal</span>
                                    </div>
                                    <input type="time" name="jam_meninggal" id="jam_meninggal" class="form-control"
                                        value="{{ field_igd('jam_meninggal', $pengkajian, $triage) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Tanda Vital Saat Pulang</label>
                        <div class="row">
                            <div class="col-md-3">
                                <label>TD</label>
                                <div class="input-group">
                                    <input type="text" name="td_pulang" id="td_pulang" class="form-control"
                                        value="{{ field_igd('td_pulang', $pengkajian, $triage) }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">mmHg</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label>Nadi</label>
                                <div class="input-group">
                                    <input type="text" name="nadi_pulang" id="nadi_pulang" class="form-control"
                                        value="{{ field_igd('nadi_pulang', $pengkajian, $triage) }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">x/mnt</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label>RR</label>
                                <div class="input-group">
                                    <input type="text" name="rr_pulang" id="rr_pulang" class="form-control"
                                        value="{{ field_igd('rr_pulang', $pengkajian, $triage) }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">x/mnt</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label>SB</label>
                                <div class="input-group">
                                    <input type="text" name="sb_pulang" id="sb_pulang" class="form-control"
                                        value="{{ field_igd('sb_pulang', $pengkajian, $triage) }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">°C</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="terapi_pulang">Terapi Pulang</label>
                        <textarea class="form-control" id="terapi_pulang" name="terapi_pulang" rows="4">{{ field_igd('terapi_pulang', $pengkajian, $triage) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Tindak Lanjut</label>
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
                            $saved_tindak_lanjut = field_igd('tindak_lanjut', $pengkajian, $triage, '[]');
                        @endphp
                        <div class="row">
                            @foreach ($tindak_lanjut_options as $tindak)
                                <div class="col-md-4 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="tindak_lanjut[]"
                                            id="tindak_lanjut_{{ Str::slug($tindak) }}" value="{{ $tindak }}"
                                            {{ in_array($tindak, $saved_tindak_lanjut) ? 'checked' : '' }}>
                                        <label class="form-check-label"
                                            for="tindak_lanjut_{{ Str::slug($tindak) }}">{{ $tindak }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label for="tindak_lanjut_rujuk_ke_text">Tujuan Rujuk</label>
                                <input type="text" name="tindak_lanjut_rujuk_ke_text" id="tindak_lanjut_rujuk_ke_text"
                                    class="form-control"
                                    value="{{ field_igd('tindak_lanjut_rujuk_ke_text', $pengkajian, $triage) }}">
                            </div>
                            <div class="col-md-6">
                                <label for="alasan_rujuk">Alasan Rujuk</label>
                                <input type="text" name="alasan_rujuk" id="alasan_rujuk" class="form-control"
                                    value="{{ field_igd('alasan_rujuk', $pengkajian, $triage) }}">
                            </div>
                        </div>
                    </div>

                    <hr class="mt-4">

                    {{-- =================================================================================================================================== --}}
                    {{-- EDUKASI --}}
                    {{-- =================================================================================================================================== --}}
                    <header class="text-primary my-4">
                        <h4 class="font-weight-bold">EDUKASI</h4>
                    </header>
                    <div class="form-group">
                        <label class="font-weight-bold">Edukasi awal disampaikan tentang diagnosa, rencana dan tujuan
                            terapi kepada</label>
                        @php
                            $edukasi_penerima = field_igd('edukasi_penerima', $pengkajian, $triage, '[]');
                        @endphp
                        <div class="row">
                            <div class="col-auto">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="edukasi_penerima[]"
                                        id="edukasi_pasien" value="Pasien"
                                        {{ in_array('Pasien', $edukasi_penerima) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="edukasi_pasien">Pasien</label>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="edukasi_penerima[]"
                                        id="edukasi_keluarga" value="Keluarga"
                                        {{ in_array('Keluarga', $edukasi_penerima) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="edukasi_keluarga">Keluarga</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="edukasi_tidak_dapat_diberikan"
                                id="edukasi_tidak_dapat_diberikan" value="1"
                                {{ field_igd('edukasi_tidak_dapat_diberikan', $pengkajian, $triage) ? 'checked' : '' }}>
                            <label class="form-check-label" for="edukasi_tidak_dapat_diberikan">
                                Tidak dapat memberikan edukasi kepada pasien atau keluarga karena:
                            </label>
                        </div>
                        <input type="text" name="edukasi_alasan" id="edukasi_alasan" class="form-control"
                            value="{{ field_igd('edukasi_alasan', $pengkajian, $triage) }}">
                    </div>

                    {{-- =================================================================================================================================== --}}
                    {{-- TANDA TANGAN & TOMBOL SIMPAN --}}
                    {{-- =================================================================================================================================== --}}
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
                                            <span class="mdi mdi-content-save mr-2"></span> Simpan (draft)
                                        </button>
                                        <button type="button"
                                            class="btn btn-primary waves-effect waves-light save-form d-flex align-items-center"
                                            data-form-id="pengkajian-dokter-igd-form" data-status="1"
                                            id="sf-pengkajian-dokter-igd">
                                            <span class="mdi mdi-content-save mr-2"></span> Simpan (final)
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
            // Auto-calculate GCS Total
            $('.gcs-sum').on('keyup change', function() {
                let e = parseInt($('#gcse').val()) || 0;
                let m = parseInt($('#gcsm').val()) || 0;
                let v = parseInt($('#gcsv').val()) || 0;
                $('#gcstotal').val(e + m + v);
            });
        });
    </script>
    {{-- Anda perlu membuat file action-js untuk handle form submission AJAX --}}
    @include('pages.simrs.erm.partials.action-js.pengkajian-dokter-igd')
@endsection
