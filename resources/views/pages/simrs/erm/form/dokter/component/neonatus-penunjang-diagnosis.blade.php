{{-- =================================================================================================== --}}
{{-- FILE: resources/views/pages/simrs/erm/form/dokter/component/neonatus-penunjang-diagnosis.blade.php --}}
{{-- =================================================================================================== --}}

{{-- PEMERIKSAAN PENUNJANG & DIAGNOSIS --}}
<h4 class="text-primary mt-4 font-weight-bold">IV. Pemeriksaan Penunjang & Diagnosis</h4>
<div class="row">
    {{-- Pemeriksaan Penunjang --}}
    <div class="col-md-12 mb-3">
        <div class="form-group">
            <label class="form-label" for="pemeriksaan_penunjang">Pemeriksaan Penunjang</label>
            <textarea class="form-control" id="pemeriksaan_penunjang" name="data[pemeriksaan_penunjang]" rows="3"
                placeholder="Hasil Laboratorium, Radiologi, dll...">{{ $data['pemeriksaan_penunjang'] ?? '' }}</textarea>
        </div>
    </div>

    {{-- Diagnosis --}}
    <div class="col-md-12 mb-3">
        <div class="form-group">
            <label class="form-label" for="diagnosis">Diagnosis</label>
            <textarea class="form-control" id="diagnosis" name="data[diagnosis]" rows="3"
                placeholder="Diagnosis kerja berdasarkan temuan...">{{ $data['diagnosis'] ?? '' }}</textarea>
        </div>
    </div>

    {{-- Diagnosis Banding --}}
    <div class="col-md-12 mb-3">
        <div class="form-group">
            <label class="form-label" for="diagnosa_banding">Diagnosis Banding</label>
            <textarea class="form-control" id="diagnosa_banding" name="data[diagnosa_banding]" rows="3"
                placeholder="Diagnosis lain yang mungkin...">{{ $data['diagnosa_banding'] ?? '' }}</textarea>
        </div>
    </div>
</div>
