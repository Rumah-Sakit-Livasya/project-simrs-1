{{-- =================================================================================================== --}}
{{-- FILE: resources/views/pages/simrs/erm/form/dokter/component/neonatus-vital-apgar.blade.php         --}}
{{-- =================================================================================================== --}}

{{-- TANDA-TANDA VITAL & APGAR SCORE --}}
<h4 class="text-primary mt-4 font-weight-bold">II. Tanda Tanda Vital & Pengukuran</h4>
<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-content">
                    <input class="form-control" name="data[vital_signs][nadi]"
                        value="{{ $data['vital_signs']['nadi'] ?? '' }}" type="text">
                    <label>Nadi</label>
                </div>
                <span class="input-group-addon grey-text">x/mnt</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-content">
                    <input class="form-control" name="data[vital_signs][rr]"
                        value="{{ $data['vital_signs']['rr'] ?? '' }}" type="text">
                    <label>RR</label>
                </div>
                <span class="input-group-addon grey-text">x/mnt</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-content">
                    <input class="form-control" name="data[vital_signs][sb]"
                        value="{{ $data['vital_signs']['sb'] ?? '' }}" type="text">
                    <label>SB</label>
                </div>
                <span class="input-group-addon grey-text">°C</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-content">
                    <input class="form-control" name="data[vital_signs][spo2]"
                        value="{{ $data['vital_signs']['spo2'] ?? '' }}" type="text">
                    <label>SpO2</label>
                </div>
                <span class="input-group-addon grey-text">%</span>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">Menangis</label>
            <div class="form-radio mt-2">
                <label class="radio-styled radio-info">
                    <input value="Kuat" name="data[vital_signs][menangis]" type="radio"
                        {{ ($data['vital_signs']['menangis'] ?? null) == 'Kuat' ? 'checked' : '' }}><span>Kuat</span>
                </label>&nbsp;&nbsp;
                <label class="radio-styled radio-info">
                    <input value="Merintih" name="data[vital_signs][menangis]" type="radio"
                        {{ ($data['vital_signs']['menangis'] ?? null) == 'Merintih' ? 'checked' : '' }}><span>Merintih</span>
                </label>&nbsp;&nbsp;
                <label class="radio-styled radio-info">
                    <input value="Lemah" name="data[vital_signs][menangis]" type="radio"
                        {{ ($data['vital_signs']['menangis'] ?? null) == 'Lemah' ? 'checked' : '' }}><span>Lemah</span>
                </label>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">Jenis Kelamin</label>
            <div class="form-radio mt-2">
                <label class="radio-styled radio-info">
                    <input value="Laki-Laki" name="data[vital_signs][jenis_kelamin]" type="radio"
                        {{ ($data['vital_signs']['jenis_kelamin'] ?? null) == 'Laki-Laki' ? 'checked' : '' }}><span>Laki-Laki</span>
                </label>&nbsp;&nbsp;
                <label class="radio-styled radio-info">
                    <input value="Perempuan" name="data[vital_signs][jenis_kelamin]" type="radio"
                        {{ ($data['vital_signs']['jenis_kelamin'] ?? null) == 'Perempuan' ? 'checked' : '' }}><span>Perempuan</span>
                </label>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <input class="form-control" name="data[measurements][as]" type="text"
                value="{{ $data['measurements']['as'] ?? '' }}">
            <label>A/S</label>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-content">
                    <input class="form-control" name="data[measurements][bb]"
                        value="{{ $data['measurements']['bb'] ?? '' }}" type="text">
                    <label>BB</label>
                </div>
                <span class="input-group-addon grey-text">Gram</span>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-content">
                    <input class="form-control" name="data[measurements][tb]"
                        value="{{ $data['measurements']['tb'] ?? '' }}" type="text">
                    <label>TB</label>
                </div>
                <span class="input-group-addon grey-text">Cm</span>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-content">
                    <input class="form-control" name="data[measurements][lk]"
                        value="{{ $data['measurements']['lk'] ?? '' }}" type="text">
                    <label>LK</label>
                </div>
                <span class="input-group-addon grey-text">Cm</span>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-content">
                    <input class="form-control" name="data[measurements][ld]"
                        value="{{ $data['measurements']['ld'] ?? '' }}" type="text">
                    <label>LD</label>
                </div>
                <span class="input-group-addon grey-text">Cm</span>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-content">
                    <input class="form-control" name="data[measurements][lp]"
                        value="{{ $data['measurements']['lp'] ?? '' }}" type="text">
                    <label>LP</label>
                </div>
                <span class="input-group-addon grey-text">Cm</span>
            </div>
        </div>
    </div>
</div>
<hr>
<div class="row">
    @include('pages.simrs.erm.form.perawat.component.skor-apgar', [
        'data' => $pengkajianKhusus['intranatal']['apgar'] ?? [],
    ])
</div>
