<form id="form-bayi">
    @csrf
    <input type="hidden" name="bayi_id" id="bayi_id">

    <div class="row">
        <!-- ========================================================== -->
        <!--                   PANEL BIODATA BAYI                       -->
        <!-- ========================================================== -->
        <div class="col-12 mb-4">
            <div class="panel">
                <div class="panel-hdr">
                    <h2><i class="fal fa-baby-carriage mr-2"></i> Biodata Bayi</h2>
                </div>
                <div class="panel-container show">
                    <div class="panel-content">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Nama Bayi <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" name="nama_bayi" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Nama Keluarga</label>
                                <input type="text" class="form-control form-control-sm" name="nama_keluarga">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Tempat Lahir <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" name="tempat_lahir" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Tgl & Jam Lahir <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control form-control-sm" name="tgl_lahir"
                                    required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Jenis Kelamin <span class="text-danger">*</span></label>
                                <div class="mt-2">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="jk_laki" name="jenis_kelamin" value="Laki-laki"
                                            class="custom-control-input" required>
                                        <label class="custom-control-label" for="jk_laki">Laki-laki</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="jk_perempuan" name="jenis_kelamin" value="Perempuan"
                                            class="custom-control-input">
                                        <label class="custom-control-label" for="jk_perempuan">Perempuan</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Kelahiran Ke</label>
                                <input type="number" class="form-control form-control-sm" name="kelahiran_ke"
                                    min="1">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <h6 class="text-muted border-bottom pb-2 mb-3"><i
                                        class="fal fa-ruler mr-2"></i>Pengukuran Fisik</h6>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Berat <span class="text-danger">*</span></label>
                                <div class="input-group input-group-sm">
                                    <input type="number" step="0.1" class="form-control" name="berat" required
                                        min="0">
                                    <div class="input-group-append"><span class="input-group-text">gr</span></div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Panjang <span class="text-danger">*</span></label>
                                <div class="input-group input-group-sm">
                                    <input type="number" step="0.1" class="form-control" name="panjang" required
                                        min="0">
                                    <div class="input-group-append"><span class="input-group-text">cm</span></div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Lingkar Kepala</label>
                                <div class="input-group input-group-sm">
                                    <input type="number" step="0.1" class="form-control" name="lingkar_kepala"
                                        min="0">
                                    <div class="input-group-append"><span class="input-group-text">cm</span></div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Lingkar Dada</label>
                                <div class="input-group input-group-sm">
                                    <input type="number" step="0.1" class="form-control" name="lingkar_dada"
                                        min="0">
                                    <div class="input-group-append"><span class="input-group-text">cm</span></div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <h6 class="text-muted border-bottom pb-2 mb-3"><i
                                        class="fal fa-clipboard-check mr-2"></i>Kondisi Tambahan</h6>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Kelainan Fisik</label>
                                <input type="text" class="form-control form-control-sm" name="kelainan_fisik"
                                    placeholder="Jika ada kelainan fisik">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Gestasi (minggu)</label>
                                <input type="number" class="form-control form-control-sm" name="gestasi"
                                    min="20" max="45" placeholder="Usia kehamilan">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================================== -->
        <!--                    PANEL DATA REGISTRASI                   -->
        <!-- ========================================================== -->
        <div class="col-md-6 mb-4">
            <div class="panel h-100">
                <div class="panel-hdr">
                    <h2><i class="fal fa-clipboard-list mr-2"></i> Data Registrasi</h2>
                </div>
                <div class="panel-container show">
                    <div class="panel-content">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label>Dokter Penanggung Jawab <span class="text-danger">*</span></label>
                                <select name="doctor_id" id="select-dokter-bayi"
                                    class="form-control form-control-sm select-doctor" required
                                    style="width: 100%;"></select>
                                <small class="form-text text-muted">Ketik nama dokter untuk mencari</small>
                            </div>
                            <div class="col-12 mb-3">
                                <label>Kelas / Kamar Rawat <span class="text-danger">*</span></label>
                                <div class="input-group input-group-sm bg-white shadow-inset-2">
                                    <input id="bayi_kelas_kamar_input" readonly type="text"
                                        class="form-control border-right-0 bg-transparent pr-0"
                                        placeholder="Klik untuk pilih kamar bayi..." required>
                                    <input type="hidden" id="bayi_bed_id_input" name="bed_id">
                                    <input type="hidden" id="bayi_kelas_rawat_id_input" name="kelas_rawat_id">
                                    <div class="input-group-append">
                                        <span class="input-group-text btn-outline-primary" style="cursor: pointer"
                                            data-toggle="modal" data-target="#modal-pilih-kamar-bayi">
                                            <i class="fal fa-search"></i>
                                        </span>
                                    </div>
                                </div>
                                <small class="form-text text-muted">Pilih kamar rawat untuk bayi</small>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <h6 class="text-muted border-bottom pb-2 mb-3"><i class="fal fa-female mr-2"></i>Data
                                    Ibu (G/P/A)</h6>
                            </div>
                            <div class="col-12 mb-3">
                                <label>Kehamilan, Persalinan, Abortus</label>
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend"><span class="input-group-text">G</span></div>
                                    <input type="number" class="form-control" name="pregnant_g"
                                        placeholder="Gravida" min="0">
                                    <div class="input-group-prepend"><span class="input-group-text">P</span></div>
                                    <input type="number" class="form-control" name="pregnant_p" placeholder="Para"
                                        min="0">
                                    <div class="input-group-prepend"><span class="input-group-text">A</span></div>
                                    <input type="number" class="form-control" name="pregnant_a"
                                        placeholder="Abortus" min="0">
                                </div>
                                <small class="form-text text-muted">G: Jumlah kehamilan, P: Jumlah persalinan, A:
                                    Jumlah abortus</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================================== -->
        <!--                    PANEL DATA KELAHIRAN                    -->
        <!-- ========================================================== -->
        <div class="col-md-6 mb-4">
            <div class="panel h-100">
                <div class="panel-hdr">
                    <h2><i class="fal fa-notes-medical mr-2"></i> Data Kelahiran</h2>
                </div>
                <div class="panel-container show">
                    <div class="panel-content">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label>Status Lahir <span class="text-danger">*</span></label>
                                <div class="mt-2">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="lahir_hidup" name="status_lahir" value="Hidup"
                                            class="custom-control-input" required>
                                        <label class="custom-control-label" for="lahir_hidup"><i
                                                class="fal fa-heart text-success mr-1"></i>Hidup</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="lahir_meninggal" name="status_lahir"
                                            value="Meninggal" class="custom-control-input">
                                        <label class="custom-control-label" for="lahir_meninggal"><i
                                                class="fal fa-heart-broken text-danger mr-1"></i>Meninggal</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <label>Jenis Kelahiran <span class="text-danger">*</span></label>
                                <div class="mt-2">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="jenis_tunggal" name="jenis_kelahiran"
                                            value="Tunggal" class="custom-control-input" required>
                                        <label class="custom-control-label" for="jenis_tunggal">Tunggal</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="jenis_kembar" name="jenis_kelahiran"
                                            value="Kembar" class="custom-control-input">
                                        <label class="custom-control-label" for="jenis_kembar">Kembar</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <label>Apgar Score</label>
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend"><span class="input-group-text">1'</span></div>
                                    <input type="number" class="form-control" name="apgar_score_1_minute"
                                        min="0" max="10" placeholder="0-10">
                                    <div class="input-group-prepend"><span class="input-group-text">5'</span></div>
                                    <input type="number" class="form-control" name="apgar_score_5_minutes"
                                        min="0" max="10" placeholder="0-10">
                                </div>
                                <small class="form-text text-muted">Penilaian kondisi bayi (0-10)</small>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <h6 class="text-muted border-bottom pb-2 mb-3"><i
                                        class="fal fa-procedures mr-2"></i>Jenis Persalinan</h6>
                            </div>
                            <div class="col-12 mb-3">
                                <label>Kelahiran Normal</label>
                                <input type="text" class="form-control form-control-sm" name="kelahiran_normal"
                                    placeholder="Detail persalinan normal">
                            </div>
                            <div class="col-12 mb-3">
                                <label>Kelahiran dengan Tindakan</label>
                                <input type="text" class="form-control form-control-sm"
                                    name="kelahiran_dgn_tindakan" placeholder="SC, Forceps, Vacuum, dll">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================================== -->
        <!--                   PANEL DATA KOMPLIKASI                    -->
        <!-- ========================================================== -->
        <div class="col-12 mb-4">
            <div class="panel">
                <div class="panel-hdr">
                    <h2><i class="fal fa-exclamation-triangle mr-2"></i> Data Komplikasi & Kondisi Khusus</h2>
                </div>
                <div class="panel-container show">
                    <div class="panel-content">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Komplikasi Kehamilan</label>
                                <input type="text" class="form-control form-control-sm"
                                    name="pregnant_complication" placeholder="Pre-eklampsia, DM Gestasional, dll">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Kondisi Partus</label>
                                <input type="text" class="form-control form-control-sm" name="partus"
                                    placeholder="Spontan, Induksi, dll">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Komplikasi Partus</label>
                                <input type="text" class="form-control form-control-sm" name="partus_complication"
                                    placeholder="Ruptur perineum, perdarahan, dll">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Berat Plasenta (gram)</label>
                                <input type="number" class="form-control form-control-sm" name="placenta_weight"
                                    placeholder="Berat plasenta" min="0">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Ukuran Plasenta (cm)</label>
                                <input type="text" class="form-control form-control-sm" name="placenta_measure"
                                    placeholder="Panjang x Lebar">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Kelainan Plasenta</label>
                                <input type="text" class="form-control form-control-sm" name="placenta_anomaly"
                                    placeholder="Plasenta previa, abruption, dll">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row">
        <div class="col-12">
            <div class="panel">
                <div class="panel-container show">
                    <div class="panel-content">
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-secondary btn-sm mr-2" id="btn-batal-bayi">
                                <i class="fal fa-times mr-1"></i>Batal
                            </button>
                            <button type="submit" class="btn btn-primary btn-sm" id="btn-simpan-bayi">
                                <i class="fal fa-save mr-1"></i>Simpan Data Bayi
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
