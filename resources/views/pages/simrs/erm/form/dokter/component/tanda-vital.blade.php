<h4 class="text-primary mt-2 font-weight-bold">I. TANDA-TANDA VITAL</h4>
<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label>Nadi (PR)</label>
            <div class="input-group">
                <input type="text" name="tanda_vital[pr]" class="form-control" value="{{ $data['tanda_vital']['pr'] ?? '' }}">
                <div class="input-group-append"><span class="input-group-text">x/menit</span></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label>Respirasi (RR)</label>
            <div class="input-group">
                <input type="text" name="tanda_vital[rr]" class="form-control" value="{{ $data['tanda_vital']['rr'] ?? '' }}">
                <div class="input-group-append"><span class="input-group-text">x/menit</span></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label>Tensi (BP)</label>
            <div class="input-group">
                <input type="text" name="tanda_vital[bp]" class="form-control" value="{{ $data['tanda_vital']['bp'] ?? '' }}">
                <div class="input-group-append"><span class="input-group-text">mmHg</span></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label>Suhu (T)</label>
            <div class="input-group">
                <input type="text" name="tanda_vital[temperatur]" class="form-control" value="{{ $data['tanda_vital']['temperatur'] ?? '' }}">
                <div class="input-group-append"><span class="input-group-text">°C</span></div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label>Tinggi Badan</label>
            <div class="input-group">
                <input type="text" id="height_badan" name="tanda_vital[height_badan]" class="form-control calc-bmi" value="{{ $data['tanda_vital']['height_badan'] ?? '' }}">
                <div class="input-group-append"><span class="input-group-text">cm</span></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label>Berat Badan</label>
            <div class="input-group">
                <input type="text" id="weight_badan" name="tanda_vital[weight_badan]" class="form-control calc-bmi" value="{{ $data['tanda_vital']['weight_badan'] ?? '' }}">
                <div class="input-group-append"><span class="input-group-text">kg</span></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
         <div class="form-group">
            <label>Index Massa Tubuh (IMT)</label>
            <div class="input-group">
                <input type="text" id="bmi" name="tanda_vital[bmi]" class="form-control" readonly value="{{ $data['tanda_vital']['bmi'] ?? '' }}">
                 <div class="input-group-append"><span class="input-group-text">kg/m²</span></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label>Kategori IMT</label>
            <input type="text" id="kat_bmi" name="tanda_vital[kat_bmi]" class="form-control" readonly value="{{ $data['tanda_vital']['kat_bmi'] ?? '' }}">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label>Saturasi Oksigen (SpO2)</label>
             <div class="input-group">
                <input type="text" name="tanda_vital[spo2]" class="form-control" value="{{ $data['tanda_vital']['spo2'] ?? '' }}">
                <div class="input-group-append"><span class="input-group-text">%</span></div>
            </div>
        </div>
    </div>
</div>
