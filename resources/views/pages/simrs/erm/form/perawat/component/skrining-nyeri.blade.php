<header class="text-warning mt-4">
    <h4 class="font-weight-bold">SKRINING NYERI</h4>
</header>
<div class="row align-items-center">
    <div class="col-md-11">
        <div class="d-flex justify-content-around text-center flex-wrap wong-baker-scale">
            @for ($i = 0; $i < 6; $i++)
                <div class="p-2">
                    <img src="/img/nyeri/{{ $i + 1 }}.jpg" class="img-fluid"
                        style="max-width: 100px; border: 1px solid #ddd; border-radius: 5px; cursor: pointer;"
                        alt="Skala Nyeri Wong Baker">
                    <div class="mt-2">
                        @if ($i == 0)
                            <span class="badge badge-pill badge-success pointer" data-skor="0">0</span>
                        @elseif($i == 1)
                            <span class="badge badge-pill badge-success pointer" data-skor="1">1</span>
                            <span class="badge badge-pill badge-success pointer" data-skor="2">2</span>
                        @elseif($i == 2)
                            <span class="badge badge-pill badge-info pointer" data-skor="3">3</span>
                            <span class="badge badge-pill badge-info pointer" data-skor="4">4</span>
                        @elseif($i == 3)
                            <span class="badge badge-pill badge-primary pointer" data-skor="5">5</span>
                            <span class="badge badge-pill badge-primary pointer" data-skor="6">6</span>
                        @elseif($i == 4)
                            <span class="badge badge-pill badge-warning pointer" data-skor="7">7</span>
                            <span class="badge badge-pill badge-warning pointer" data-skor="8">8</span>
                        @else
                            <span class="badge badge-pill badge-danger pointer" data-skor="9">9</span>
                            <span class="badge badge-pill badge-danger pointer" data-skor="10">10</span>
                        @endif
                    </div>
                </div>
            @endfor
        </div>
    </div>
    <div class="col-md-1 form-group">
        <label for="skor_nyeri"><b>Skor</b></label>
        <input type="text" name="asesmen_nyeri[skor_nyeri]" id="skor_nyeri"
            class="form-control text-center font-weight-bold" style="font-size: 2rem; height: 60px;"
            value="{{ $nyeri['skor_nyeri'] ?? '' }}" readonly>
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
