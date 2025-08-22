<h4 class="text-primary mt-4 font-weight-bold">III. ANAMNESIS</h4>
<div class="form-group">
    <label>Keluhan Utama</label>
    <textarea name="anamnesis[keluhan_utama]" class="form-control" rows="3">{{ $data['anamnesis']['keluhan_utama'] ?? '' }}</textarea>
</div>
 <div class="form-group">
    <label>Riwayat Penyakit Sekarang</label>
    <textarea name="anamnesis[riwayat_penyakit_sekarang]" class="form-control" rows="3">{{ $data['anamnesis']['riwayat_penyakit_sekarang'] ?? '' }}</textarea>
</div>
 <div class="form-group">
    <label>Riwayat Penyakit Dahulu</label>
    <textarea name="anamnesis[riwayat_penyakit_dahulu]" class="form-control" rows="3">{{ $data['anamnesis']['riwayat_penyakit_dahulu'] ?? '' }}</textarea>
</div>
<div class="form-group">
    <label>Alergi Obat</label>
    <div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="anamnesis[alergi][status]" id="alergi_ya" value="Ya" {{ ($data['anamnesis']['alergi']['status'] ?? '') == 'Ya' ? 'checked' : '' }}>
            <label class="form-check-label" for="alergi_ya">Ya, sebutkan:</label>
        </div>
         <input type="text" name="anamnesis[alergi][keterangan]" class="form-control-sm" style="border: 0; border-bottom: 1px solid #ced4da;" placeholder="Keterangan alergi..." value="{{ $data['anamnesis']['alergi']['keterangan'] ?? '' }}">
        <div class="form-check form-check-inline ml-3">
            <input class="form-check-input" type="radio" name="anamnesis[alergi][status]" id="alergi_tidak" value="Tidak" {{ ($data['anamnesis']['alergi']['status'] ?? 'Tidak') == 'Tidak' ? 'checked' : '' }}>
            <label class="form-check-label" for="alergi_tidak">Tidak</label>
        </div>
    </div>
</div>
