{{-- =================================================================================================== --}}
{{-- FILE: resources/views/pages/simrs/erm/form/dokter/component/neonatus-kriteria-pulang.blade.php    --}}
{{-- =================================================================================================== --}}

{{-- REKONSILIASI OBAT & KRITERIA PULANG --}}
<h4 class="text-primary mt-4 font-weight-bold">VI. Rencana Pemulangan Pasien</h4>
<div class="row">
    {{-- Rekonsiliasi Obat --}}
    <div class="col-md-12 mb-3">
        <div class="form-group">
            <label class="form-label">Sudah dilakukan rekonsiliasi terhadap obat yang sedang digunakan saat ini:</label>
            <div class="frame-wrap">
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="rekonsiliasi_ya" name="data[rekonsiliasi_obat]" class="custom-control-input"
                        value="Ya" {{ ($data['rekonsiliasi_obat'] ?? null) == 'Ya' ? 'checked' : '' }}>
                    <label class="custom-control-label" for="rekonsiliasi_ya">Ya</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="rekonsiliasi_tidak" name="data[rekonsiliasi_obat]"
                        class="custom-control-input" value="Tidak"
                        {{ ($data['rekonsiliasi_obat'] ?? null) == 'Tidak' ? 'checked' : '' }}>
                    <label class="custom-control-label" for="rekonsiliasi_tidak">Tidak</label>
                </div>
            </div>
        </div>
    </div>

    {{-- Kriteria Pulang --}}
    <div class="col-md-12 mb-3">
        <div class="form-group">
            <label class="form-label" for="kriteria_pulang">Kriteria Pulang</label>
            <textarea class="form-control" id="kriteria_pulang" name="data[kriteria_pulang]" rows="3"
                placeholder="Jelaskan kriteria klinis pasien diperbolehkan pulang...">{{ $data['kriteria_pulang'] ?? '' }}</textarea>
        </div>
    </div>
</div>
