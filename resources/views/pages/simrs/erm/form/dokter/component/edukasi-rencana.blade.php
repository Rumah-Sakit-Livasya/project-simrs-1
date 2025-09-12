<h4 class="text-primary mt-4 font-weight-bold">
    {{ isset($registration->department) && \Illuminate\Support\Str::of($registration->department->name)->lower()->contains('bedah') ? 'VI' : 'V' }}.
    EDUKASI & RENCANA
</h4>
<table class="table table-bordered">
    <tr>
        <td style="width: 25%"><b>Edukasi</b></td>
        <td>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="edukasi[proses_penyakit]" value="1" id="edu1"
                    {{ ($data['edukasi']['proses_penyakit'] ?? 0) == 1 ? 'checked' : '' }}>
                <label class="form-check-label" for="edu1">Proses Penyakit</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="edukasi[terapi]" value="1" id="edu2"
                    {{ ($data['edukasi']['terapi'] ?? 0) == 1 ? 'checked' : '' }}>
                <label class="form-check-label" for="edu2">Terapi</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="edukasi[tindakan_medis]" value="1"
                    id="edu3" {{ ($data['edukasi']['tindakan_medis'] ?? 0) == 1 ? 'checked' : '' }}>
                <label class="form-check-label" for="edu3">Tindakan Medis</label>
            </div>
        </td>
    </tr>
    <tr>
        <td><b>Evaluasi Penyakit</b></td>
        <td>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="evaluasi_penyakit[akut]" value="1"
                    id="eva1" {{ ($data['evaluasi_penyakit']['akut'] ?? 0) == 1 ? 'checked' : '' }}>
                <label class="form-check-label" for="eva1">Akut</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="evaluasi_penyakit[kronis]" value="1"
                    id="eva2" {{ ($data['evaluasi_penyakit']['kronis'] ?? 0) == 1 ? 'checked' : '' }}>
                <label class="form-check-label" for="eva2">Kronis</label>
            </div>
        </td>
    </tr>
    <tr>
        <td><b>Rencana Tindak Lanjut</b></td>
        <td>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="rencana_tindak_lanjut_pasien[rawat_jalan]"
                    value="1" id="rtl1"
                    {{ ($data['rencana_tindak_lanjut_pasien']['rawat_jalan'] ?? 0) == 1 ? 'checked' : '' }}>
                <label class="form-check-label" for="rtl1">Rawat Jalan</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="rencana_tindak_lanjut_pasien[rawat_inap]"
                    value="1" id="rtl2"
                    {{ ($data['rencana_tindak_lanjut_pasien']['rawat_inap'] ?? 0) == 1 ? 'checked' : '' }}>
                <label class="form-check-label" for="rtl2">Rawat Inap</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="rencana_tindak_lanjut_pasien[rujuk]"
                    value="1" id="rtl3"
                    {{ ($data['rencana_tindak_lanjut_pasien']['rujuk'] ?? 0) == 1 ? 'checked' : '' }}>
                <label class="form-check-label" for="rtl3">Rujuk</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="rencana_tindak_lanjut_pasien[konsul]"
                    value="1" id="rtl4"
                    {{ ($data['rencana_tindak_lanjut_pasien']['konsul'] ?? 0) == 1 ? 'checked' : '' }}>
                <label class="form-check-label" for="rtl4">Konsul</label>
            </div>
        </td>
    </tr>
</table>
