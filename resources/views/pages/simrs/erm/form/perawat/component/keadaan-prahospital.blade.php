<header class="text-warning mt-4">
    <h4 class="font-weight-bold">KEADAAN PRA HOSPITAL</h4>
</header>
<div class="row mt-3">
    <div class="col-md-4 mb-3">
        <div class="form-group"><label class="text-primary">Tinggi Badan</label><input type="text"
                name="pra_tinggi_badan" class="form-control" value="{{ $pengkajian?->pra_tinggi_badan }}"></div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="form-group"><label class="text-primary">Berat Badan</label><input type="text"
                name="pra_berat_badan" class="form-control" value="{{ $pengkajian?->pra_berat_badan }}"></div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="form-group"><label class="text-primary">GCS</label><input type="text" name="pra_gcs"
                class="form-control" value="{{ $pengkajian?->pra_gcs }}"></div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="form-group"><label class="text-primary">Tekanan Darah</label><input type="text"
                name="pra_tekanan_darah" class="form-control" value="{{ $pengkajian?->pra_tekanan_darah }}"></div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="form-group"><label class="text-primary">Nadi</label><input type="text" name="pra_nadi"
                class="form-control" value="{{ $pengkajian?->pra_nadi }}"></div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="form-group"><label class="text-primary">Suhu</label><input type="text" name="pra_suhu"
                class="form-control" value="{{ $pengkajian?->pra_suhu }}"></div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="form-group"><label class="text-primary">RR</label><input type="text" name="pra_rr"
                class="form-control" value="{{ $pengkajian?->pra_rr }}"></div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="form-group"><label class="text-primary">SP02</label><input type="text" name="pra_sp02"
                class="form-control" value="{{ $pengkajian?->pra_sp02 }}"></div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="form-group"><label class="text-primary">O2</label><input type="text" name="pra_o2"
                class="form-control" value="{{ $pengkajian?->pra_o2 }}"></div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="form-group"><label class="text-primary">Data Penunjang</label><input type="text"
                name="pra_data_penunjang" class="form-control" value="{{ $pengkajian?->pra_data_penunjang }}"></div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="form-group"><label class="text-primary">Obat / Infus</label><input type="text"
                name="pra_obat_infus" class="form-control" value="{{ $pengkajian?->pra_obat_infus }}"></div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="form-group"><label class="text-primary">Alasan / Indikasi Dirujuk</label><input type="text"
                name="pra_alasan_dirujuk" class="form-control" value="{{ $pengkajian?->pra_alasan_dirujuk }}"></div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="form-group"><label class="text-primary">Lain-lain</label><input type="text" name="pra_lain_lain"
                class="form-control" value="{{ $pengkajian?->pra_lain_lain }}">
        </div>
    </div>
</div>
