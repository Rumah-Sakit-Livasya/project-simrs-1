<h4 class="text-primary mt-4 font-weight-bold">III. EFFUSION & COMMENTS</h4>
<hr>
<!-- PERICARDIAL EFFUSION -->
<div class="form-group row">
    <label class="col-md-3 col-form-label font-weight-bold">PERICARDIAL EFFUSION</label>
    <div class="col-md-9">
        <div class="row">
            <div class="col-md-3 form-group"><label>None</label><input type="text" name="pericardial_effusion[none]" class="form-control" value="{{ $data['pericardial_effusion']['none'] ?? '' }}"></div>
            <div class="col-md-3 form-group"><label>Small</label><input type="text" name="pericardial_effusion[small]" class="form-control" value="{{ $data['pericardial_effusion']['small'] ?? '' }}"></div>
            <div class="col-md-3 form-group"><label>Moderate</label><input type="text" name="pericardial_effusion[moderate]" class="form-control" value="{{ $data['pericardial_effusion']['moderate'] ?? '' }}"></div>
            <div class="col-md-3 form-group"><label>Large</label><input type="text" name="pericardial_effusion[large]" class="form-control" value="{{ $data['pericardial_effusion']['large'] ?? '' }}"></div>
        </div>
    </div>
</div>
<hr>
<!-- COMMENTS -->
<div class="form-group row">
    <label class="col-md-3 col-form-label font-weight-bold">COMMENTS</label>
    <div class="col-md-9">
        <div class="row">
            <div class="col-md-12 form-group"><label>Dimensi ruang jantung</label><input type="text" name="comments[dimensi_ruang_jantung]" class="form-control" value="{{ $data['comments']['dimensi_ruang_jantung'] ?? '' }}"></div>
            <div class="col-md-12 form-group"><label>Dinding LV</label><input type="text" name="comments[dinding_lv]" class="form-control" value="{{ $data['comments']['dinding_lv'] ?? '' }}"></div>
            <div class="col-md-12 form-group"><label>LV wall motion</label><input type="text" name="comments[lv_wall_motion]" class="form-control" value="{{ $data['comments']['lv_wall_motion'] ?? '' }}"></div>
            <div class="col-md-12 form-group"><label>Katup-katup Jantung</label><input type="text" name="comments[katup_jantung]" class="form-control" value="{{ $data['comments']['katup_jantung'] ?? '' }}"></div>
            <div class="col-md-6 form-group"><label>LV fungsi sistolik</label><input type="text" name="comments[lv_fungsi_sistolik]" class="form-control" value="{{ $data['comments']['lv_fungsi_sistolik'] ?? '' }}"></div>
            <div class="col-md-6 form-group"><label>EF (%)</label><input type="text" name="comments[ef_percent]" class="form-control" value="{{ $data['comments']['ef_percent'] ?? '' }}"></div>
            <div class="col-md-12 form-group"><label>LV fungsi diastolic</label><input type="text" name="comments[lv_fungsi_diastolic]" class="form-control" value="{{ $data['comments']['lv_fungsi_diastolic'] ?? '' }}"></div>
            <div class="col-md-6 form-group"><label>RV fungsi sistolik</label><input type="text" name="comments[rv_fungsi_sistolik]" class="form-control" value="{{ $data['comments']['rv_fungsi_sistolik'] ?? '' }}"></div>
            <div class="col-md-6 form-group"><label>TAPSE (mm)</label><input type="text" name="comments[tapse_mm]" class="form-control" value="{{ $data['comments']['tapse_mm'] ?? '' }}"></div>
            <div class="col-md-6 form-group"><label>Trombus</label><input type="text" name="comments[trombus]" class="form-control" value="{{ $data['comments']['trombus'] ?? '' }}"></div>
            <div class="col-md-6 form-group"><label>PE</label><input type="text" name="comments[pe]" class="form-control" value="{{ $data['comments']['pe'] ?? '' }}"></div>
        </div>
    </div>
</div>
<hr>
