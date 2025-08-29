{{-- =================================================================================================== --}}
{{-- FILE: resources/views/pages/simrs/erm/form/dokter/component/neonatus-penunjang-diagnosis.blade.php --}}
{{-- =================================================================================================== --}}

{{-- PEMERIKSAAN PENUNJANG & DIAGNOSIS --}}
<h4 class="text-primary mt-4 font-weight-bold">IV. Pemeriksaan Penunjang & Diagnosis</h4>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="pemeriksaan_penunjang" class="control-label">Pemeriksaan Penunjang</label>
            <textarea class="form-control" id="pemeriksaan_penunjang" name="data[pemeriksaan_penunjang]" rows="3">{{ $data['pemeriksaan_penunjang'] ?? '' }}</textarea>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <textarea class="form-control" id="diagnosis" name="data[diagnosis]" rows="3">{{ $data['diagnosis'] ?? '' }}</textarea>
            <label for="diagnosis" class="control-label">Diagnosis</label>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <textarea class="form-control" id="diagnosa_banding" name="data[diagnosa_banding]" rows="3">{{ $data['diagnosa_banding'] ?? '' }}</textarea>
            <label for="diagnosa_banding" class="control-label">Diagnosis Banding</label>
        </div>
    </div>
</div>
