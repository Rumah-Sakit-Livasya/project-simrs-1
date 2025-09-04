<h5 class="font-weight-bold mt-4 mb-3">Faktor Penyakit & Hasil Laboratorium</h5>
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><b>Faktor Penyakit</b></div>
            <div class="card-body">
                @php
                    $penyakitOptions = [
                        'hbs_ag' => 'HBS Ag',
                        'anti_hcv' => 'Anti HCV',
                        'anti_hiv' => 'Anti HIV',
                    ];
                @endphp
                @foreach ($penyakitOptions as $key => $label)
                    <div class="form-group">
                        <label>{{ $label }}</label>
                        <select class="form-control" name="faktor_penyakit[{{ $key }}]">
                            <option value=""></option>
                            <option value="Reaktif" @selected(isset($data[$key]) && $data[$key] == 'Reaktif')>Reaktif</option>
                            <option value="Non Reaktif" @selected(isset($data[$key]) && $data[$key] == 'Non Reaktif')>Non Reaktif</option>
                            <option value="Tidak diperiksa" @selected(isset($data[$key]) && $data[$key] == 'Tidak diperiksa')>Tidak diperiksa</option>
                        </select>
                    </div>
                @endforeach
                <div class="form-group">
                    <label>Lain-lain</label>
                    <input class="form-control" type="text" name="faktor_penyakit[lain_lain]"
                        value="{{ $data['lain_lain'] ?? '' }}">
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><b>Hasil Laboratorium</b></div>
            <div class="card-body">
                <div class="form-group">
                    <label>Leukosit</label>
                    <input class="form-control" type="text" name="faktor_penyakit[lab_leukosit]"
                        value="{{ $data['lab_leukosit'] ?? '' }}">
                </div>
                <div class="form-group">
                    <label>LED</label>
                    <input class="form-control" type="text" name="faktor_penyakit[lab_led]"
                        value="{{ $data['lab_led'] ?? '' }}">
                </div>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-header"><b>Hasil Radiologi</b></div>
            <div class="card-body">
                <input class="form-control" type="text" name="faktor_penyakit[hasil_radiologi]"
                    value="{{ $data['hasil_radiologi'] ?? '' }}">
            </div>
        </div>
    </div>
</div>
