<header class="text-warning mt-4">
    <h4 class="font-weight-bold">PERENCANAAN PULANG (DISCHARGE PLANNING)</h4>
</header>
<div class="row mt-3">
    <div class="col-md-12">
        <label class="control-label text-primary d-block">Kondisi Discharge Planning</label>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <div class="custom-control custom-checkbox mb-2">
                <input type="checkbox" class="custom-control-input" id="discharge_kondisi_umur65"
                    name="discharge_kondisi_umur65" value="1"
                    {{ $pengkajian?->discharge_kondisi_umur65 == 1 ? 'checked' : '' }}>
                <label class="custom-control-label" for="discharge_kondisi_umur65">Umur &gt; 65
                    Tahun</label>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <div class="custom-control custom-checkbox mb-2">
                <input type="checkbox" class="custom-control-input" id="discharge_kondisi_mobilitas"
                    name="discharge_kondisi_mobilitas" value="1"
                    {{ $pengkajian?->discharge_kondisi_mobilitas == 1 ? 'checked' : '' }}>
                <label class="custom-control-label" for="discharge_kondisi_mobilitas">Keterbatasan
                    Mobilitas</label>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <div class="custom-control custom-checkbox mb-2">
                <input type="checkbox" class="custom-control-input" id="discharge_perawatan_lanjutan"
                    name="discharge_perawatan_lanjutan" value="1"
                    {{ $pengkajian?->discharge_perawatan_lanjutan == 1 ? 'checked' : '' }}>
                <label class="custom-control-label" for="discharge_perawatan_lanjutan">Perawatan
                    atau
                    pengobatan
                    lanjutan</label>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <div class="custom-control custom-checkbox mb-2">
                <input type="checkbox" class="custom-control-input" id="discharge_bantuan_aktivitas"
                    name="discharge_bantuan_aktivitas" value="1"
                    {{ $pengkajian?->discharge_bantuan_aktivitas == 1 ? 'checked' : '' }}>
                <label class="custom-control-label" for="discharge_bantuan_aktivitas">Bantuan
                    beraktivitas sehari hari</label>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <label class="control-label text-primary d-block">Perencanaan Pulang</label>
    </div>
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group mt-2">
                    <div class="custom-control custom-checkbox mb-2">
                        <input type="checkbox" class="custom-control-input" id="discharge_perawatan_diri"
                            name="discharge_perawatan_diri" value="1"
                            {{ $pengkajian?->discharge_perawatan_diri == 1 ? 'checked' : '' }}>
                        <label class="custom-control-label" for="discharge_perawatan_diri">Perawatan
                            diri (mandi, BAK, BAB)</label>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group mt-2">
                    <div class="custom-control custom-checkbox mb-2">
                        <input type="checkbox" class="custom-control-input" id="discharge_pemberian_obat"
                            name="discharge_pemberian_obat" value="1"
                            {{ $pengkajian?->discharge_pemberian_obat == 1 ? 'checked' : '' }}>
                        <label class="custom-control-label" for="discharge_pemberian_obat">Pemantauan
                            pemberian obat</label>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group mt-2">
                    <div class="custom-control custom-checkbox mb-2">
                        <input type="checkbox" class="custom-control-input" id="discharge_pemantauan_diet"
                            name="discharge_pemantauan_diet" value="1"
                            {{ $pengkajian?->discharge_pemantauan_diet == 1 ? 'checked' : '' }}>
                        <label class="custom-control-label" for="discharge_pemantauan_diet">Pemantauan
                            diet</label>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group mt-2">
                    <div class="custom-control custom-checkbox mb-2">
                        <input type="checkbox" class="custom-control-input" id="discharge_perawatan_luka"
                            name="discharge_perawatan_luka" value="1"
                            {{ $pengkajian?->discharge_perawatan_luka == 1 ? 'checked' : '' }}>
                        <label class="custom-control-label" for="discharge_perawatan_luka">Perawatan
                            Luka</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="form-group mt-2">
                    <div class="custom-control custom-checkbox mb-2">
                        <input type="checkbox" class="custom-control-input" id="discharge_latihan_fisik"
                            name="discharge_latihan_fisik" value="1"
                            {{ $pengkajian?->discharge_latihan_fisik == 1 ? 'checked' : '' }}>
                        <label class="custom-control-label" for="discharge_latihan_fisik">Latihan
                            Fisik Lanjutan</label>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group mt-2">
                    <div class="custom-control custom-checkbox mb-2">
                        <input type="checkbox" class="custom-control-input" id="discharge_pendamping_tenaga"
                            name="discharge_pendamping_tenaga" value="1"
                            {{ $pengkajian?->discharge_pendamping_tenaga == 1 ? 'checked' : '' }}>
                        <label class="custom-control-label" for="discharge_pendamping_tenaga">Pendampingan
                            tenaga khusus
                            dirumah</label>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group mt-2">
                    <div class="custom-control custom-checkbox mb-2">
                        <input type="checkbox" class="custom-control-input" id="discharge_bantuan_medis"
                            name="discharge_bantuan_medis" value="1"
                            {{ $pengkajian?->discharge_bantuan_medis == 1 ? 'checked' : '' }}>
                        <label class="custom-control-label" for="discharge_bantuan_medis">Bantuan
                            Medis/perawatan dirumah</label>
                    </div>
                </div>
            </div>
            {{-- BUatkan input baru --}}

            <div class="col-md-3">
                <div class="form-group mt-2">
                    <div class="custom-control custom-checkbox mb-2">
                        <input type="checkbox" class="custom-control-input" id="discharge_bantuan_aktivitas_fisik"
                            name="discharge_bantuan_aktivitas_fisik" value="1"
                            {{ $pengkajian?->discharge_bantuan_aktivitas_fisik == 1 ? 'checked' : '' }}>
                        <label class="custom-control-label" for="discharge_bantuan_aktivitas_fisik">Bantuan
                            aktivitas fisik</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
