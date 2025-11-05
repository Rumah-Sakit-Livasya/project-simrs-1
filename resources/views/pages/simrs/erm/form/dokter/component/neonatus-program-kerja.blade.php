{{-- =================================================================================================== --}}
{{-- FILE: resources/views/pages/simrs/erm/form/dokter/component/neonatus-program-kerja.blade.php      --}}
{{-- =================================================================================================== --}}

{{-- PROGRAM KERJA (RENCANA TINDAK LANJUT) --}}
<h4 class="text-primary mt-4 font-weight-bold">V. Program Kerja (Rencana Tindak Lanjut)</h4>
<div class="row">
    {{-- Edukasi --}}
    <div class="col-md-12 mb-3">
        <div class="form-group">
            <label class="form-label" for="edukasi">Edukasi</label>
            <input class="form-control" id="edukasi" name="data[edukasi]" type="text"
                value="{{ $data['edukasi'] ?? '' }}" placeholder="Edukasi yang diberikan kepada keluarga...">
        </div>
    </div>

    {{-- Anjuran Pemeriksaan Penunjang --}}
    <div class="col-md-12 mb-3">
        <div class="form-group">
            <label class="form-label" for="anjuran_pemeriksaan_penunjang">Anjuran Pemeriksaan Penunjang</label>
            <input class="form-control" id="anjuran_pemeriksaan_penunjang" name="data[anjuran_pemeriksaan_penunjang]"
                type="text" value="{{ $data['anjuran_pemeriksaan_penunjang'] ?? '' }}"
                placeholder="Pemeriksaan lanjutan yang dianjurkan...">
        </div>
    </div>

    {{-- Terapi Atau Tindakan --}}
    <div class="col-md-12 mb-3">
        <div class="form-group">
            <label class="form-label" for="terapi_tindakan">Terapi Atau Tindakan</label>
            <textarea class="form-control" id="terapi_tindakan" name="data[terapi_tindakan]" rows="3"
                placeholder="Rencana terapi, medikamentosa, atau tindakan medis lainnya...">{{ $data['terapi_tindakan'] ?? '' }}</textarea>
        </div>
    </div>

    {{-- Perkiraan Lama Rawat Inap --}}
    <div class="col-md-12 mb-3">
        <div class="form-group">
            <label class="form-label" for="perkiraan_ranap">Perkiraan Lama Rawat Inap</label>
            <input class="form-control" id="perkiraan_ranap" name="data[perkiraan_ranap]" type="text"
                value="{{ $data['perkiraan_ranap'] ?? '' }}" placeholder="Contoh: 3 hari, 1 minggu...">
        </div>
    </div>
</div>
