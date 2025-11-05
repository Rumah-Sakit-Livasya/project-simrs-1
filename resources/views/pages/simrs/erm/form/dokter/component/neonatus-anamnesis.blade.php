{{-- =================================================================================================== --}}
{{-- FILE: resources/views/pages/simrs/erm/form/dokter/component/neonatus-anamnesis.blade.php           --}}
{{-- =================================================================================================== --}}

{{-- ANAMNESIS & RIWAYAT KESEHATAN --}}
<h4 class="text-primary mt-4 font-weight-bold">I. Anamnesis & Riwayat</h4>
<div class="row mb-3">
    {{-- Anamnesis --}}
    <div class="col-md-12 mb-3">
        <div class="form-group">
            <label class="form-label">Anamnesis</label>
            <div class="frame-wrap">
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="auto_anamnesis" name="data[anamnesis]" class="custom-control-input"
                        value="Auto Anamnesis" {{ ($data['anamnesis'] ?? null) == 'Auto Anamnesis' ? 'checked' : '' }}>
                    <label class="custom-control-label" for="auto_anamnesis">Auto Anamnesis</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="allo_anamnesis" name="data[anamnesis]" class="custom-control-input"
                        value="Allo Anamnesis" {{ ($data['anamnesis'] ?? null) == 'Allo Anamnesis' ? 'checked' : '' }}>
                    <label class="custom-control-label" for="allo_anamnesis">Allo Anamnesis</label>
                </div>
            </div>
        </div>
    </div>

    {{-- Keluhan Utama --}}
    <div class="col-md-12 mb-3">
        <div class="form-group">
            <label class="form-label" for="keluhan_utama">Keluhan Utama</label>
            <textarea class="form-control" name="data[keluhan_utama]" id="keluhan_utama" rows="2">{{ $data['keluhan_utama'] ?? '' }}</textarea>
        </div>
    </div>

    {{-- Riwayat Penyakit Keluarga --}}
    <div class="col-md-6">
        <div class="form-group">
            <label class="form-label" for="riwayat_penyakit_keluarga">Riwayat Penyakit Keluarga</label>
            <textarea class="form-control" name="data[riwayat_penyakit_keluarga]" id="riwayat_penyakit_keluarga" rows="2">{{ $data['riwayat_penyakit_keluarga'] ?? '' }}</textarea>
        </div>
    </div>

    {{-- Riwayat Penyakit Dahulu --}}
    <div class="col-md-6">
        <div class="form-group">
            <label class="form-label" for="riwayat_penyakit_dahulu">Riwayat Kesehatan Dahulu</label>
            <textarea class="form-control" name="data[riwayat_penyakit_dahulu]" id="riwayat_penyakit_dahulu" rows="2">{{ $data['riwayat_penyakit_dahulu'] ?? '' }}</textarea>
        </div>
    </div>
</div>
<hr class="mt-1 mb-3">

{{-- RIWAYAT KELAHIRAN --}}
<div class="row mb-3">
    {{-- Riwayat Kehamilan (GPA) --}}
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label">Riwayat Kehamilan (GPA)</label>
            <div class="d-flex align-items-center">
                <span class="mr-2 font-weight-bold">G</span>
                <input name="data[riwayat_kehamilan][g]" value="{{ $data['riwayat_kehamilan']['g'] ?? '' }}"
                    type="text" class="form-control" style="width: 70px;">
                <span class="mx-2 font-weight-bold">P</span>
                <input name="data[riwayat_kehamilan][p]" value="{{ $data['riwayat_kehamilan']['p'] ?? '' }}"
                    type="text" class="form-control" style="width: 70px;">
                <span class="mx-2 font-weight-bold">A</span>
                <input name="data[riwayat_kehamilan][a]" value="{{ $data['riwayat_kehamilan']['a'] ?? '' }}"
                    type="text" class="form-control" style="width: 70px;">
            </div>
        </div>
    </div>

    {{-- Usia Kehamilan --}}
    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label" for="usia_kehamilan">Usia Kehamilan</label>
            <div class="input-group">
                <input class="form-control" id="usia_kehamilan" name="data[usia_kehamilan]"
                    value="{{ $data['usia_kehamilan'] ?? '' }}" type="text">
                <div class="input-group-append">
                    <span class="input-group-text">Minggu</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Tanggal & Jam Lahir --}}
    <div class="col-md-5">
        <div class="form-group">
            <label class="form-label" for="tanggal_lahir">Tanggal & Jam Lahir</label>
            <div class="input-group">
                <input class="form-control datepicker" id="tanggal_lahir" name="data[tanggal_lahir]"
                    value="{{ $data['tanggal_lahir'] ?? '' }}" type="text" readonly placeholder="Pilih tanggal...">
                <div class="input-group-append">
                    <input class="form-control timepicker" id="jam_lahir" name="data[jam_lahir]"
                        value="{{ $data['jam_lahir'] ?? '' }}" type="text" readonly placeholder="Pilih jam..."
                        style="width: 120px;">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-3">
    {{-- Lahir Secara --}}
    <div class="col-md-6">
        <div class="form-group">
            <label class="form-label" for="lahir_secara">Lahir Secara</label>
            <input class="form-control" id="lahir_secara" name="data[lahir_secara]" type="text"
                value="{{ $data['lahir_secara'] ?? '' }}">
        </div>
    </div>

    {{-- Indikasi --}}
    <div class="col-md-6">
        <div class="form-group">
            <label class="form-label" for="indikasi">Indikasi</label>
            <input class="form-control" id="indikasi" name="data[indikasi]" type="text"
                value="{{ $data['indikasi'] ?? '' }}">
        </div>
    </div>
</div>

<div class="row mb-3">
    {{-- Ketuban --}}
    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label" for="ketuban">Ketuban</label>
            <input class="form-control" id="ketuban" name="data[ketuban]" value="{{ $data['ketuban'] ?? '' }}"
                type="text">
        </div>
    </div>

    {{-- Lilitan Tali Pusat --}}
    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label" for="lilitan_tali_pusat">Lilitan Tali Pusat</label>
            <input class="form-control" id="lilitan_tali_pusat" name="data[lilitan_tali_pusat]" type="text"
                value="{{ $data['lilitan_tali_pusat'] ?? '' }}">
        </div>
    </div>

    {{-- Meco --}}
    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label" for="meco">Meco</label>
            <input class="form-control" id="meco" name="data[meco]" type="text"
                value="{{ $data['meco'] ?? '' }}">
        </div>
    </div>

    {{-- Miksi --}}
    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label" for="miksi">Miksi</label>
            <input class="form-control" id="miksi" name="data[miksi]" type="text"
                value="{{ $data['miksi'] ?? '' }}">
        </div>
    </div>
</div>

<div class="row mb-3">
    {{-- Keadaan Umum --}}
    <div class="col-md-12 mb-3">
        <div class="form-group">
            <label class="form-label" for="keadaan_umum">Keadaan Umum</label>
            <textarea class="form-control" id="keadaan_umum" name="data[keadaan_umum]" rows="2">{{ $data['keadaan_umum'] ?? '' }}</textarea>
        </div>
    </div>

    {{-- Kesadaran --}}
    <div class="col-md-12 mb-3">
        <div class="form-group">
            <label class="form-label" for="kesadaran">Kesadaran</label>
            <textarea class="form-control" id="kesadaran" name="data[kesadaran]" rows="2">{{ $data['kesadaran'] ?? '' }}</textarea>
        </div>
    </div>
</div>
