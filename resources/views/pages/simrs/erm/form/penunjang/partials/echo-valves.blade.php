<h4 class="text-primary mt-4 font-weight-bold">II. VALVES</h4>
<hr>
<!-- MITRAL VALVE -->
<div class="form-group row">
    <label class="col-md-3 col-form-label font-weight-bold">MITRAL VALVE</label>
    <div class="col-md-9">
        <div class="row">
            <div class="col-md-6 form-group">
                <label>Anterior leaflet: Amplitude</label>
                <input type="text" name="mitral_valve[anterior_amplitude]" class="form-control" value="{{ $data['mitral_valve']['anterior_amplitude'] ?? '' }}">
            </div>
            <div class="col-md-6 form-group">
                <label>Anterior leaflet: Slope (F-Fo)</label>
                <input type="text" name="mitral_valve[anterior_slope]" class="form-control" value="{{ $data['mitral_valve']['anterior_slope'] ?? '' }}">
            </div>
            @php
                $valveOptions = ['Normal', 'MS', 'AI', 'Prolapse Aquivocal'];
            @endphp
            <div class="col-md-4 form-group">
                <label>Diastolic Motion</label>
                <select name="mitral_valve[diastolic_motion]" class="form-control select2">
                    <option value=""></option>
                    @foreach($valveOptions as $option)
                    <option value="{{ $option }}" {{ ($data['mitral_valve']['diastolic_motion'] ?? '') == $option ? 'selected' : '' }}>{{ $option }}</option>
                    @endforeach
                </select>
            </div>
             <div class="col-md-4 form-group">
                <label>Systolic Motion</label>
                <select name="mitral_valve[systolic_motion]" class="form-control select2">
                     <option value=""></option>
                    @foreach($valveOptions as $option)
                    <option value="{{ $option }}" {{ ($data['mitral_valve']['systolic_motion'] ?? '') == $option ? 'selected' : '' }}>{{ $option }}</option>
                    @endforeach
                </select>
            </div>
             <div class="col-md-4 form-group">
                <label>Posterior Motion</label>
                <select name="mitral_valve[posterior_motion]" class="form-control select2">
                     <option value=""></option>
                    @foreach($valveOptions as $option)
                    <option value="{{ $option }}" {{ ($data['mitral_valve']['posterior_motion'] ?? '') == $option ? 'selected' : '' }}>{{ $option }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>
<hr>
<!-- OTHER VALVES -->
<div class="form-group row">
    <label class="col-md-3 col-form-label font-weight-bold">OTHER VALVES</label>
    <div class="col-md-9">
        <div class="row">
            <div class="col-md-6 form-group">
                <label>TRICUSPID VALVE</label>
                <input type="text" name="other_valves[tricuspid_valve]" class="form-control" value="{{ $data['other_valves']['tricuspid_valve'] ?? '' }}">
            </div>
            <div class="col-md-6 form-group">
                <label>PULMONARY VALVE</label>
                <input type="text" name="other_valves[pulmonary_valve]" class="form-control" value="{{ $data['other_valves']['pulmonary_valve'] ?? '' }}">
            </div>
        </div>
    </div>
</div>
<hr>
