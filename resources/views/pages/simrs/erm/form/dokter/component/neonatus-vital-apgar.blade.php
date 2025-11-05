{{-- =================================================================================================== --}}
{{-- FILE: resources/views/pages/simrs/erm/form/dokter/component/neonatus-vital-apgar.blade.php         --}}
{{-- =================================================================================================== --}}

{{-- TANDA-TANDA VITAL & APGAR SCORE --}}
<h4 class="text-primary mt-4 font-weight-bold">II. Tanda Tanda Vital & Pengukuran</h4>
<div class="row mb-3">
    {{-- Nadi --}}
    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label" for="vital_nadi">Nadi</label>
            <div class="input-group">
                <input class="form-control" id="vital_nadi" name="data[vital_signs][nadi]"
                    value="{{ $data['vital_signs']['nadi'] ?? '' }}" type="text">
                <div class="input-group-append">
                    <span class="input-group-text">x/mnt</span>
                </div>
            </div>
        </div>
    </div>

    {{-- RR --}}
    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label" for="vital_rr">RR</label>
            <div class="input-group">
                <input class="form-control" id="vital_rr" name="data[vital_signs][rr]"
                    value="{{ $data['vital_signs']['rr'] ?? '' }}" type="text">
                <div class="input-group-append">
                    <span class="input-group-text">x/mnt</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Suhu Badan (SB) --}}
    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label" for="vital_sb">Suhu Badan (SB)</label>
            <div class="input-group">
                <input class="form-control" id="vital_sb" name="data[vital_signs][sb]"
                    value="{{ $data['vital_signs']['sb'] ?? '' }}" type="text">
                <div class="input-group-append">
                    <span class="input-group-text">°C</span>
                </div>
            </div>
        </div>
    </div>

    {{-- SpO2 --}}
    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label" for="vital_spo2">SpO2</label>
            <div class="input-group">
                <input class="form-control" id="vital_spo2" name="data[vital_signs][spo2]"
                    value="{{ $data['vital_signs']['spo2'] ?? '' }}" type="text">
                <div class="input-group-append">
                    <span class="input-group-text">%</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-3">
    {{-- Menangis --}}
    <div class="col-md-6">
        <div class="form-group">
            <label class="form-label">Menangis</label>
            <div class="frame-wrap">
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="menangis_kuat" name="data[vital_signs][menangis]"
                        class="custom-control-input" value="Kuat"
                        {{ ($data['vital_signs']['menangis'] ?? null) == 'Kuat' ? 'checked' : '' }}>
                    <label class="custom-control-label" for="menangis_kuat">Kuat</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="menangis_merintih" name="data[vital_signs][menangis]"
                        class="custom-control-input" value="Merintih"
                        {{ ($data['vital_signs']['menangis'] ?? null) == 'Merintih' ? 'checked' : '' }}>
                    <label class="custom-control-label" for="menangis_merintih">Merintih</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="menangis_lemah" name="data[vital_signs][menangis]"
                        class="custom-control-input" value="Lemah"
                        {{ ($data['vital_signs']['menangis'] ?? null) == 'Lemah' ? 'checked' : '' }}>
                    <label class="custom-control-label" for="menangis_lemah">Lemah</label>
                </div>
            </div>
        </div>
    </div>

    {{-- Jenis Kelamin --}}
    <div class="col-md-6">
        <div class="form-group">
            <label class="form-label">Jenis Kelamin</label>
            <div class="frame-wrap">
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="jk_laki" name="data[vital_signs][jenis_kelamin]"
                        class="custom-control-input" value="Laki-Laki"
                        {{ ($data['vital_signs']['jenis_kelamin'] ?? null) == 'Laki-Laki' ? 'checked' : '' }}>
                    <label class="custom-control-label" for="jk_laki">Laki-Laki</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="jk_perempuan" name="data[vital_signs][jenis_kelamin]"
                        class="custom-control-input" value="Perempuan"
                        {{ ($data['vital_signs']['jenis_kelamin'] ?? null) == 'Perempuan' ? 'checked' : '' }}>
                    <label class="custom-control-label" for="jk_perempuan">Perempuan</label>
                </div>
            </div>
        </div>
    </div>
</div>

<hr class="mt-1 mb-3">
<h5 class="font-weight-bold">Pengukuran Antropometri</h5>
<div class="row mb-3">
    {{-- A/S --}}
    <div class="col-md-2">
        <div class="form-group">
            <label class="form-label" for="measure_as">Apgar Score (A/S)</label>
            <input class="form-control" id="measure_as" name="data[measurements][as]" type="text"
                value="{{ $data['measurements']['as'] ?? '' }}">
        </div>
    </div>

    {{-- BB --}}
    <div class="col-md-2">
        <div class="form-group">
            <label class="form-label" for="measure_bb">Berat Badan (BB)</label>
            <div class="input-group">
                <input class="form-control" id="measure_bb" name="data[measurements][bb]"
                    value="{{ $data['measurements']['bb'] ?? '' }}" type="text">
                <div class="input-group-append">
                    <span class="input-group-text">Gram</span>
                </div>
            </div>
        </div>
    </div>

    {{-- TB --}}
    <div class="col-md-2">
        <div class="form-group">
            <label class="form-label" for="measure_tb">Tinggi Badan (TB)</label>
            <div class="input-group">
                <input class="form-control" id="measure_tb" name="data[measurements][tb]"
                    value="{{ $data['measurements']['tb'] ?? '' }}" type="text">
                <div class="input-group-append">
                    <span class="input-group-text">Cm</span>
                </div>
            </div>
        </div>
    </div>

    {{-- LK --}}
    <div class="col-md-2">
        <div class="form-group">
            <label class="form-label" for="measure_lk">Lingkar Kepala (LK)</label>
            <div class="input-group">
                <input class="form-control" id="measure_lk" name="data[measurements][lk]"
                    value="{{ $data['measurements']['lk'] ?? '' }}" type="text">
                <div class="input-group-append">
                    <span class="input-group-text">Cm</span>
                </div>
            </div>
        </div>
    </div>

    {{-- LD --}}
    <div class="col-md-2">
        <div class="form-group">
            <label class="form-label" for="measure_ld">Lingkar Dada (LD)</label>
            <div class="input-group">
                <input class="form-control" id="measure_ld" name="data[measurements][ld]"
                    value="{{ $data['measurements']['ld'] ?? '' }}" type="text">
                <div class="input-group-append">
                    <span class="input-group-text">Cm</span>
                </div>
            </div>
        </div>
    </div>

    {{-- LP --}}
    <div class="col-md-2">
        <div class="form-group">
            <label class="form-label" for="measure_lp">Lingkar Perut (LP)</label>
            <div class="input-group">
                <input class="form-control" id="measure_lp" name="data[measurements][lp]"
                    value="{{ $data['measurements']['lp'] ?? '' }}" type="text">
                <div class="input-group-append">
                    <span class="input-group-text">Cm</span>
                </div>
            </div>
        </div>
    </div>
</div>
<hr>
<div class="row mb-3">
    @include('pages.simrs.erm.form.perawat.component.skor-apgar', [
        'data' => $pengkajianKhusus['intranatal']['apgar'] ?? [],
    ])
</div>
