{{-- =================================================================================================== --}}
{{-- FILE: resources/views/pages/simrs/erm/form/dokter/component/neonatus-anamnesis.blade.php           --}}
{{-- =================================================================================================== --}}

{{-- ANAMNESIS & RIWAYAT KESEHATAN --}}
<h4 class="text-primary font-weight-bold">I. Anamnesis & Riwayat</h4>
<div class="row">
    <div class="col-md-12">
        <div class="form-group" style="margin: 0px;">
            <label for="anamnesis" class="control-label margin-tb-10">Anamnesis</label>
            <div class="form-radio" style="margin: 0px;">
                <label class="radio-styled radio-info">
                    <input value="Auto Anamnesis" name="data[anamnesis]" type="radio"
                        {{ ($data['anamnesis'] ?? null) == 'Auto Anamnesis' ? 'checked' : '' }}><span>Auto
                        Anamnesis</span>
                </label>&nbsp;&nbsp;
                <label class="radio-styled radio-info">
                    <input value="Allo Anamnesis" name="data[anamnesis]" type="radio"
                        {{ ($data['anamnesis'] ?? null) == 'Allo Anamnesis' ? 'checked' : '' }}><span>Allo
                        Anamnesis</span>
                </label>
            </div>
        </div>
    </div>
    <div class="col-md-12 mt-3">
        <div class="form-group">
            <textarea class="form-control" name="data[keluhan_utama]" rows="2">{{ $data['keluhan_utama'] ?? '' }}</textarea>
            <label class="control-label">Keluhan Utama</label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <textarea class="form-control" name="data[riwayat_penyakit_keluarga]" rows="2">{{ $data['riwayat_penyakit_keluarga'] ?? '' }}</textarea>
            <label class="control-label">Riwayat Penyakit Keluarga</label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <textarea class="form-control" name="data[riwayat_penyakit_dahulu]" rows="2">{{ $data['riwayat_penyakit_dahulu'] ?? '' }}</textarea>
            <label class="control-label">Riwayat Kesehatan Dahulu</label>
        </div>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label">Riwayat Kehamilan</label>
            <div class="form-inline" style="margin-top: 10px;">
                <label class="mr-2">G</label>
                <input name="data[riwayat_kehamilan][g]" value="{{ $data['riwayat_kehamilan']['g'] ?? '' }}"
                    style="width: 80px;" type="text" class="form-control mx-2">
                <label class="mr-2">P</label>
                <input name="data[riwayat_kehamilan][p]" value="{{ $data['riwayat_kehamilan']['p'] ?? '' }}"
                    style="width: 80px;" type="text" class="form-control mx-2">
                <label class="mr-2">A</label>
                <input name="data[riwayat_kehamilan][a]" value="{{ $data['riwayat_kehamilan']['a'] ?? '' }}"
                    style="width: 80px;" type="text" class="form-control mx-2">
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-content">
                    <input class="form-control" id="usia_kehamilan" name="data[usia_kehamilan]"
                        value="{{ $data['usia_kehamilan'] ?? '' }}" type="text">
                    <label for="usia_kehamilan">Usia Kehamilan</label>
                </div>
                <span class="input-group-addon grey-text">Minggu</span>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-content">
                    <input class="form-control datepicker" id="tanggal_lahir" name="data[tanggal_lahir]"
                        value="{{ $data['tanggal_lahir'] ?? '' }}" type="text" readonly>
                    <label for="tanggal_lahir">Tanggal Lahir</label>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-content">
                    <input class="form-control timepicker" id="jam_lahir" name="data[jam_lahir]"
                        value="{{ $data['jam_lahir'] ?? '' }}" type="text" readonly>
                    <label for="jam_lahir">Jam</label>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <input class="form-control" id="lahir_secara" name="data[lahir_secara]" type="text"
                value="{{ $data['lahir_secara'] ?? '' }}">
            <label for="lahir_secara">Lahir Secara</label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <input class="form-control" id="indikasi" name="data[indikasi]" type="text"
                value="{{ $data['indikasi'] ?? '' }}">
            <label for="indikasi">Indikasi</label>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <input class="form-control" id="ketuban" name="data[ketuban]" value="{{ $data['ketuban'] ?? '' }}"
                type="text">
            <label for="ketuban">Ketuban</label>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <input class="form-control" id="lilitan_tali_pusat" name="data[lilitan_tali_pusat]" type="text"
                value="{{ $data['lilitan_tali_pusat'] ?? '' }}">
            <label for="lilitan_tali_pusat">Lilitan Tali Pusat</label>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <input class="form-control" id="meco" name="data[meco]" type="text"
                value="{{ $data['meco'] ?? '' }}">
            <label for="meco">Meco</label>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <input class="form-control" id="miksi" name="data[miksi]" type="text"
                value="{{ $data['miksi'] ?? '' }}">
            <label for="miksi">Miksi</label>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <textarea class="form-control" id="keadaan_umum" name="data[keadaan_umum]" rows="2">{{ $data['keadaan_umum'] ?? '' }}</textarea>
            <label for="keadaan_umum" class="control-label">Keadaan Umum</label>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <textarea class="form-control" id="kesadaran" name="data[kesadaran]" rows="2">{{ $data['kesadaran'] ?? '' }}</textarea>
            <label for="kesadaran" class="control-label">Kesadaran</label>
        </div>
    </div>
</div>
