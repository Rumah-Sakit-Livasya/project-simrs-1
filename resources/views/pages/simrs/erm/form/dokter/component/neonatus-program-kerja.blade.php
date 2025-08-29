{{-- =================================================================================================== --}}
{{-- FILE: resources/views/pages/simrs/erm/form/dokter/component/neonatus-program-kerja.blade.php      --}}
{{-- =================================================================================================== --}}

{{-- PROGRAM KERJA (RENCANA TINDAK LANJUT) --}}
<h4 class="text-primary mt-4 font-weight-bold">V. Program Kerja (Rencana Tindak Lanjut)</h4>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <input class="form-control" id="edukasi" name="data[edukasi]" type="text"
                value="{{ $data['edukasi'] ?? '' }}">
            <label for="edukasi">Edukasi</label>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <input class="form-control" id="anjuran_pemeriksaan_penunjang" name="data[anjuran_pemeriksaan_penunjang]"
                type="text" value="{{ $data['anjuran_pemeriksaan_penunjang'] ?? '' }}">
            <label for="anjuran_pemeriksaan_penunjang">Anjuran Pemeriksaan Penunjang</label>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <textarea class="form-control" id="terapi_tindakan" name="data[terapi_tindakan]" rows="3">{{ $data['terapi_tindakan'] ?? '' }}</textarea>
            <label for="terapi_tindakan" class="control-label">Terapi Atau Tindakan</label>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <input class="form-control" id="perkiraan_ranap" name="data[perkiraan_ranap]" type="text"
                value="{{ $data['perkiraan_ranap'] ?? '' }}">
            <label for="perkiraan_ranap">Perkiraan Lama Rawat Inap</label>
        </div>
    </div>
</div>
