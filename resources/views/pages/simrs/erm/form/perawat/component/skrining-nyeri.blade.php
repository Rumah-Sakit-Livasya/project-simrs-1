<header class="text-warning mt-4">
    <h4 class="font-weight-bold">SKRINING NYERI</h4>
</header>
<div class="row mt-3 justify-content-center">
    <div class="col text-center">
        <img src="/img/nyeri/1.jpg" alt="nyeri 1" class="img-fluid">
        <br>
        <p class="badge badge-danger p-1">0</p>
    </div>
    <div class="col text-center">
        <img src="/img/nyeri/2.jpg" alt="nyeri 2" class="img-fluid">
        <br>
        <p class="badge badge-success p-1">1</p>
        <p class="badge badge-success p-1">2</p>
    </div>
    <div class="col text-center">
        <img src="/img/nyeri/3.jpg" alt="nyeri 3" class="img-fluid">
        <br>
        <p class="badge badge-info p-1">3</p>
        <p class="badge badge-info p-1">4</p>
    </div>
    <div class="col text-center">
        <img src="/img/nyeri/4.jpg" alt="nyeri 4" class="img-fluid">
        <br>
        <p class="badge badge-secondary p-1">5</p>
        <p class="badge badge-secondary p-1">6</p>
    </div>
    <div class="col text-center">
        <img src="/img/nyeri/5.jpg" alt="nyeri 5" class="img-fluid">
        <br>
        <p class="badge badge-warning p-1">7</p>
        <p class="badge badge-warning p-1">8</p>
    </div>
    <div class="col text-center">
        <img src="/img/nyeri/6.jpg" alt="nyeri 6" class="img-fluid">
        <br>
        <p class="badge badge-danger p-1">9</p>
        <p class="badge badge-danger p-1">10</p>
    </div>
    <div class="col text-center">
        <label for="skor-nyeri" class="control-label">Skor</label>
        <input name="skala_nyeri" id="skor-nyeri" class="form-control m-auto text-center"
            value="{{ $pengkajian?->skala_nyeri ?? '' }}" style="font-size: 3rem; height: 60px; width: 80px;"
            type="text">
    </div>
</div>
<div class="row mt-3">
    <div class="col-md-3 mb-3">
        <div class="form-group mt-2">
            <label for="provokatif" class="control-label text-primary">Provokatif</label>
            <input type="text" name="nyeri_provokatif" id="provokatif" class="form-control"
                value="{{ $pengkajian?->nyeri_provokatif }}">
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="form-group mt-2">
            <label for="quality" class="control-label text-primary">Quality</label>
            <input type="text" name="nyeri_quality" id="quality" class="form-control"
                value="{{ $pengkajian?->nyeri_quality }}">
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="form-group mt-2">
            <label for="region" class="control-label text-primary">Region</label>
            <input type="text" name="nyeri_quality" id="region" class="form-control"
                value="{{ $pengkajian?->nyeri_quality }}">
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="form-group mt-2">
            <label for="time" class="control-label text-primary">Time</label>
            <input type="text" name="nyeri_time" id="time" class="form-control"
                value="{{ $pengkajian?->nyeri_time }}">
        </div>
    </div>
</div>
<div class="row mt-3">
    <div class="col-md-3">
        <div class="form-group">
            <label class="control-label text-primary">Nyeri</label>
            <select name="nyeri" class="select2 form-select">
                <option></option>
                <option value="Nyeri Kronis" {{ ($pengkajian?->nyeri ?? '') == 'Nyeri Kronis' ? 'selected' : '' }}>
                    Nyeri Kronis</option>
                <option value="Nyeri Akut" {{ ($pengkajian?->nyeri ?? '') == 'Nyeri Akut' ? 'selected' : '' }}>Nyeri
                    Akut
                </option>
                <option value="Tidak ada Nyeri"
                    {{ ($pengkajian?->nyeri ?? '') == 'Tidak ada Nyeri' ? 'selected' : '' }}>
                    Tidak ada Nyeri
                </option>
            </select>
        </div>
    </div>
    <div class="col-md-9">
        <div class="form-group">
            <label for="nyeri-hilang-apabila" class="control-label text-primary">Nyeri hilang
                apabila</label>
            <input type="text" name="nyeri_hilang_apabila" id="nyeri-hilang-apabila" class="form-control"
                value="{{ $pengkajian?->nyeri_hilang_apabila }}">
        </div>
    </div>
</div>
