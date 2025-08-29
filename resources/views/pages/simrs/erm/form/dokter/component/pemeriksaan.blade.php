<h4 class="text-primary mt-4 font-weight-bold">IV. PEMERIKSAAN & DIAGNOSA</h4>
<div class="form-group">
    <label>Pemeriksaan Fisik</label>
    <textarea name="pemeriksaan_fisik" class="form-control" rows="4">{{ $pengkajian->pemeriksaan_fisik ?? '' }}</textarea>
</div>
<div class="form-group">
    <label>Pemeriksaan Penunjang</label>
    <textarea name="pemeriksaan_penunjang" class="form-control" rows="4">{{ $pengkajian->pemeriksaan_penunjang ?? '' }}</textarea>
</div>
<div class="form-group">
    <label>Diagnosa Kerja</label>
    <textarea name="diagnosa_kerja" class="form-control" rows="3">{{ $pengkajian->diagnosa_kerja ?? '' }}</textarea>
</div>
<div class="form-group">
    <label>Diagnosa Banding</label>
    <textarea name="diagnosa_banding" class="form-control" rows="3">{{ $pengkajian->diagnosa_banding ?? '' }}</textarea>
</div>
 <div class="form-group">
    <label>Terapi / Tindakan</label>
    <textarea name="terapi_tindakan" class="form-control" rows="4">{{ $pengkajian->terapi_tindakan ?? '' }}</textarea>
</div>
