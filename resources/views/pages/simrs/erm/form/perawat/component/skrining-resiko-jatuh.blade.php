<header class="text-warning mt-4">
    <h4 class="font-weight-bold">SKRINING RESIKO JATUH - GET UP & GO</h4>
</header>
<div class="row mt-3">
    <div class="col-md-12 mb-3">
        <label class="control-label text-primary">Cara Berjalan</label>
    </div>
    <div class="col-md-4 mb-3">
        <div class="form-group">
            <div class="custom-control custom-checkbox custom-control-inline">
                <input type="checkbox" class="custom-control-input" name="berjalan_stabil" id="berjalan_stabil"
                    value="1" {{ $pengkajian?->berjalan_stabil == 1 ? 'checked' : '' }}>
                <label class="custom-control-label" for="berjalan_stabil">Tidak
                    seimbang/sempoyongan/limbung</label>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="form-group">
            <div class="custom-control custom-checkbox custom-control-inline">
                <input type="checkbox" class="custom-control-input" name="alat_bantu" id="alat_bantu" value="1"
                    {{ $pengkajian?->alat_bantu == 1 ? 'checked' : '' }}>
                <label class="custom-control-label" for="alat_bantu">Alat bantu: kruk/kursi
                    roda/dibantu</label>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="form-group">
            <div class="custom-control custom-checkbox custom-control-inline">
                <input type="checkbox" class="custom-control-input" name="pegang_meja" id="pegang_meja" value="1"
                    {{ $pengkajian?->pegang_meja == 1 ? 'checked' : '' }}>
                <label class="custom-control-label" for="pegang_meja">Pegang pinggiran
                    meja/kursi/saat
                    bantu untuk duduk</label>
            </div>
        </div>
    </div>
</div>
