{{-- =================================================================================================== --}}
{{-- FILE: resources/views/pages/simrs/erm/form/dokter/component/neonatus-kriteria-pulang.blade.php    --}}
{{-- =================================================================================================== --}}

{{-- REKONSILIASI OBAT & KRITERIA PULANG --}}
<h4 class="text-primary mt-4 font-weight-bold">VI. Rencana Pemulangan Pasien</h4>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label class="control-label d-block">Sudah dilakukan rekonsiliasi terhadap obat yang sedang digunakan saat
                ini:</label>
            <div class="form-radio mt-2">
                <label class="radio-styled radio-info">
                    <input value="Ya" name="data[rekonsiliasi_obat]" type="radio"
                        {{ ($data['rekonsiliasi_obat'] ?? null) == 'Ya' ? 'checked' : '' }}>
                    <span>Ya</span>
                </label>
                <label class="radio-styled radio-info ml-3">
                    <input value="Tidak" name="data[rekonsiliasi_obat]" type="radio"
                        {{ ($data['rekonsiliasi_obat'] ?? null) == 'Tidak' ? 'checked' : '' }}>
                    <span>Tidak</span>
                </label>
            </div>
        </div>
    </div>
    <div class="col-md-12 mt-3">
        <div class="form-group">
            <textarea class="form-control" id="kriteria_pulang" name="data[kriteria_pulang]" rows="3">{{ $data['kriteria_pulang'] ?? '' }}</textarea>
            <label for="kriteria_pulang" class="control-label">Kriteria Pulang</label>
        </div>
    </div>
</div>
