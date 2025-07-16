<header class="text-warning mt-4">
    <h4 class="font-weight-bold">SKRINING GIZI</h4>
</header>
<div class="row mt-3">
    <div class="col-md-3 mb-3">
        <div class="form-group">
            <label class="control-label text-primary">Penurunan Berat Badan 6 Bulan Terakhir</label>
            <select name="gizi_penurunan_bb" class="select2 form-select">
                <option></option>
                <option value="Tidak" {{ ($pengkajian?->gizi_penurunan_bb ?? '') == 'Tidak' ? 'selected' : '' }}>Tidak
                </option>
                <option value="Tidak Yakin"
                    {{ ($pengkajian?->gizi_penurunan_bb ?? '') == 'Tidak Yakin' ? 'selected' : '' }}>
                    Tidak Yakin</option>
                <option value="Ya" {{ ($pengkajian?->gizi_penurunan_bb ?? '') == 'Ya' ? 'selected' : '' }}>Ya
                </option>
            </select>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="form-group">
            <label class="control-label text-primary">Asupan Makanan Pasien</label>
            <select name="gizi_asupan_makanan" class="select2 form-select">
                <option></option>
                <option value="Tidak Ada Penurunan"
                    {{ ($pengkajian?->gizi_asupan_makanan ?? '') == 'Tidak Ada Penurunan' ? 'selected' : '' }}>
                    Tidak Ada Penurunan</option>
                <option value="Ada Penurunan"
                    {{ ($pengkajian?->gizi_asupan_makanan ?? '') == 'Ada Penurunan' ? 'selected' : '' }}>
                    Ada Penurunan</option>
            </select>
        </div>
    </div>
</div>
<div class="row mt-3">
    <div class="col-md-12 mb-3">
        <label class="control-label text-primary">Pasien dalam kondisi khusus</label>
    </div>
    <div class="col-md-12 mb-3">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="gizi_kondisi_anak"
                            id="gizi_kondisi_anak" value="1"
                            {{ $pengkajian?->gizi_kondisi_anak == 1 ? 'checked' : '' }}>
                        <label class="custom-control-label" for="gizi_kondisi_anak">Anak usia 1-5
                            tahun</label>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="gizi_kondisi_lansia"
                            id="gizi_kondisi_lansia" value="1"
                            {{ $pengkajian?->gizi_kondisi_lansia == 1 ? 'checked' : '' }}>
                        <label class="custom-control-label" for="gizi_kondisi_lansia">Lansia &gt; 60
                            tahun</label>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="gizi_kondisi_komplikasi"
                            id="gizi_kondisi_komplikasi" value="1"
                            {{ $pengkajian?->gizi_kondisi_komplikasi == 1 ? 'checked' : '' }}>
                        <label class="custom-control-label" for="gizi_kondisi_komplikasi">Penyakit
                            kronis
                            dengan komplikasi</label>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="gizi_kondisi_kanker"
                            id="gizi_kondisi_kanker" value="1"
                            {{ $pengkajian?->gizi_kondisi_kanker == 1 ? 'checked' : '' }}>
                        <label class="custom-control-label" for="gizi_kondisi_kanker">Kanker stadium
                            III/IV</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-3">
                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="gizi_kondisi_hiv"
                            id="gizi_kondisi_hiv" value="1"
                            {{ $pengkajian?->gizi_kondisi_hiv == 1 ? 'checked' : '' }}>
                        <label class="custom-control-label" for="gizi_kondisi_hiv">HIV/AIDS</label>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="gizi_kondisi_tb" id="gizi_kondisi_tb"
                            value="1" {{ $pengkajian?->gizi_kondisi_tb == 1 ? 'checked' : '' }}>
                        <label class="custom-control-label" for="gizi_kondisi_tb">TB</label>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="gizi_kondisi_bedah"
                            id="gizi_kondisi_bedah" value="1"
                            {{ $pengkajian?->gizi_kondisi_bedah == 1 ? 'checked' : '' }}>
                        <label class="custom-control-label" for="gizi_kondisi_bedah">Bedah mayor
                            digestif</label>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="gizi_kondisi_luka"
                            id="gizi_kondisi_luka" value="1"
                            {{ $pengkajian?->gizi_kondisi_luka == 1 ? 'checked' : '' }}>
                        <label class="custom-control-label" for="gizi_kondisi_luka">Luka bakar &gt;
                            20%</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
