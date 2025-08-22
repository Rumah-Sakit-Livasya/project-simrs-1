<h4 class="text-primary mt-2 font-weight-bold">I. SINGLE-ELEMENT DATA</h4>
<hr>
<!-- AORTA -->
<div class="form-group row">
    <label class="col-md-3 col-form-label font-weight-bold">AORTA</label>
    <div class="col-md-9">
        <div class="row align-items-center">
            <div class="col-md-6">
                <label>Root Diameter</label>
                <input type="text" name="aorta[root_diameter]" class="form-control" value="{{ $data['aorta']['root_diameter'] ?? '' }}">
                <small class="form-text text-muted">(Normal: 20 - 37mm)</small>
            </div>
            <div class="col-md-6">
                <label>Value</label>
                <select name="aorta[value]" id="aorta_value_select" class="form-control select2">
                    <option value=""></option>
                    <option value="Normal" {{ ($data['aorta']['value'] ?? '') == 'Normal' ? 'selected' : '' }}>Normal</option>
                    <option value="Other" {{ ($data['aorta']['value'] ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                </select>
                <input type="text" name="aorta[value_other]" id="aorta_value_other" class="form-control mt-2" placeholder="Sebutkan..." value="{{ $data['aorta']['value_other'] ?? '' }}" style="display: none;">
            </div>
        </div>
    </div>
</div>
<hr>

<!-- LEFT ATRIUM -->
<div class="form-group row">
    <label class="col-md-3 col-form-label font-weight-bold">LEFT ATRIUM</label>
    <div class="col-md-9">
        <div class="row">
            <div class="col-md-6">
                <label>Diameter</label>
                <input type="text" name="left_atrium[diameter]" class="form-control" value="{{ $data['left_atrium']['diameter'] ?? '' }}">
                <small class="form-text text-muted">(Normal: 15 - 40mm)</small>
            </div>
            <div class="col-md-6">
                <label>LA / AO Ratio</label>
                <input type="text" name="left_atrium[la_ao_ratio]" class="form-control" value="{{ $data['left_atrium']['la_ao_ratio'] ?? '' }}">
                <small class="form-text text-muted">(Normal: < 1,3)</small>
            </div>
        </div>
    </div>
</div>
<hr>

<!-- RIGHT VENTRICLE -->
<div class="form-group row">
    <label class="col-md-3 col-form-label font-weight-bold">RIGHT VENTRICLE</label>
    <div class="col-md-9">
        <div class="row">
            <div class="col-md-6 form-group">
                <label>Diameter (Dd)</label>
                <input type="text" name="right_ventricle[diameter_dd]" class="form-control" value="{{ $data['right_ventricle']['diameter_dd'] ?? '' }}">
                 <small class="form-text text-muted">(Normal: < 30mm)</small>
            </div>
            <div class="col-md-6 form-group">
                <label>Diameter (Ds)</label>
                <input type="text" name="right_ventricle[diameter_ds]" class="form-control" value="{{ $data['right_ventricle']['diameter_ds'] ?? '' }}">
            </div>
            <div class="col-md-6 form-group">
                <label>TAPSE</label>
                <input type="text" name="right_ventricle[tapse]" class="form-control" value="{{ $data['right_ventricle']['tapse'] ?? '' }}">
            </div>
        </div>
    </div>
</div>
<hr>

<!-- LEFT VENTRICLE -->
<div class="form-group row">
    <label class="col-md-3 col-form-label font-weight-bold">LEFT VENTRICLE</label>
    <div class="col-md-9">
        {{-- Grupkan field-field LV yang banyak di sini --}}
        <div class="row">
            <div class="col-md-4 form-group"><label>Diameter (ED)</label><input type="text" name="left_ventricle[diameter_ed]" class="form-control" value="{{ $data['left_ventricle']['diameter_ed'] ?? '' }}"><small>(56mm)</small></div>
            <div class="col-md-4 form-group"><label>Diameter (ES)</label><input type="text" name="left_ventricle[diameter_es]" class="form-control" value="{{ $data['left_ventricle']['diameter_es'] ?? '' }}"><small>(Variable)</small></div>
            <div class="col-md-4 form-group"><label>Fract Shortening</label><input type="text" name="left_ventricle[fract_shortening]" class="form-control" value="{{ $data['left_ventricle']['fract_shortening'] ?? '' }}"><small>(25%)</small></div>
            <div class="col-md-4 form-group"><label>EF</label><input type="text" name="left_ventricle[ef]" class="form-control" value="{{ $data['left_ventricle']['ef'] ?? '' }}"><small>(>50%)</small></div>
            <div class="col-md-4 form-group"><label>LVPW thickness (ED)</label><input type="text" name="left_ventricle[lvpw_thickness_ed]" class="form-control" value="{{ $data['left_ventricle']['lvpw_thickness_ed'] ?? '' }}"><small>(6-12mm)</small></div>
            <div class="col-md-4 form-group"><label>% Thickening</label><input type="text" name="left_ventricle[percent_thickening_lvpw]" class="form-control" value="{{ $data['left_ventricle']['percent_thickening_lvpw'] ?? '' }}"><small>(30%)</small></div>
            <div class="col-md-4 form-group"><label>Motion Pattern LVPW</label><select name="left_ventricle[motion_pattern_lvpw]" class="form-control select2"><option value=""></option><option value="Normal" {{($data['left_ventricle']['motion_pattern_lvpw'] ?? '') == 'Normal' ? 'selected' : ''}}>Normal</option><option value="Hyper" {{($data['left_ventricle']['motion_pattern_lvpw'] ?? '') == 'Hyper' ? 'selected' : ''}}>Hyper</option><option value="Hypo" {{($data['left_ventricle']['motion_pattern_lvpw'] ?? '') == 'Hypo' ? 'selected' : ''}}>Hypo</option><option value="Akinetik" {{($data['left_ventricle']['motion_pattern_lvpw'] ?? '') == 'Akinetik' ? 'selected' : ''}}>Akinetik</option><option value="Paradoxial" {{($data['left_ventricle']['motion_pattern_lvpw'] ?? '') == 'Paradoxial' ? 'selected' : ''}}>Paradoxial</option></select></div>
            <div class="col-md-4 form-group"><label>IVS Thickness/ED</label><input type="text" name="left_ventricle[ivs_thickness_ed]" class="form-control" value="{{ $data['left_ventricle']['ivs_thickness_ed'] ?? '' }}"><small>(6-12mm)</small></div>
            <div class="col-md-4 form-group"><label>% Thickening IVS</label><input type="text" name="left_ventricle[percent_thickening_ivs]" class="form-control" value="{{ $data['left_ventricle']['percent_thickening_ivs'] ?? '' }}"><small>(30%)</small></div>
            <div class="col-md-4 form-group"><label>Motion Pattern IVS</label><select name="left_ventricle[motion_pattern_ivs]" class="form-control select2"><option value=""></option><option value="Normal" {{($data['left_ventricle']['motion_pattern_ivs'] ?? '') == 'Normal' ? 'selected' : ''}}>Normal</option><option value="Hyper" {{($data['left_ventricle']['motion_pattern_ivs'] ?? '') == 'Hyper' ? 'selected' : ''}}>Hyper</option><option value="Hypo" {{($data['left_ventricle']['motion_pattern_ivs'] ?? '') == 'Hypo' ? 'selected' : ''}}>Hypo</option><option value="Akinetik" {{($data['left_ventricle']['motion_pattern_ivs'] ?? '') == 'Akinetik' ? 'selected' : ''}}>Akinetik</option><option value="Paradoxial" {{($data['left_ventricle']['motion_pattern_ivs'] ?? '') == 'Paradoxial' ? 'selected' : ''}}>Paradoxial</option></select></div>
            <div class="col-md-4 form-group"><label>IVS / LVPW Ratio</label><input type="text" name="left_ventricle[ivs_lvpw_ratio]" class="form-control" value="{{ $data['left_ventricle']['ivs_lvpw_ratio'] ?? '' }}"><small>(< 1,3)</small></div>
            <div class="col-md-4 form-group"><label>EPSS</label><input type="text" name="left_ventricle[epss]" class="form-control" value="{{ $data['left_ventricle']['epss'] ?? '' }}"></div>
            <div class="col-md-4 form-group"><label>S</label><input type="text" name="left_ventricle[s]" class="form-control" value="{{ $data['left_ventricle']['s'] ?? '' }}"></div>
            <div class="col-md-4 form-group"><label>E/E'</label><input type="text" name="left_ventricle[e_e_prime]" class="form-control" value="{{ $data['left_ventricle']['e_e_prime'] ?? '' }}"></div>
            <div class="col-md-4 form-group"><label>E</label><input type="text" name="left_ventricle[e]" class="form-control" value="{{ $data['left_ventricle']['e'] ?? '' }}"></div>
            <div class="col-md-4 form-group"><label>D</label><input type="text" name="left_ventricle[d]" class="form-control" value="{{ $data['left_ventricle']['d'] ?? '' }}"></div>
            <div class="col-md-4 form-group"><label>DT</label><input type="text" name="left_ventricle[dt]" class="form-control" value="{{ $data['left_ventricle']['dt'] ?? '' }}"></div>
            <div class="col-md-4 form-group"><label>A</label><input type="text" name="left_ventricle[a]" class="form-control" value="{{ $data['left_ventricle']['a'] ?? '' }}"></div>
            <div class="col-md-4 form-group"><label>S/D</label><input type="text" name="left_ventricle[s_d]" class="form-control" value="{{ $data['left_ventricle']['s_d'] ?? '' }}"></div>
            <div class="col-md-4 form-group"><label>IVRT</label><input type="text" name="left_ventricle[ivrt]" class="form-control" value="{{ $data['left_ventricle']['ivrt'] ?? '' }}"></div>
            <div class="col-md-4 form-group"><label>E/A</label><input type="text" name="left_ventricle[e_a]" class="form-control" value="{{ $data['left_ventricle']['e_a'] ?? '' }}"></div>
        </div>
    </div>
</div>
<hr>
