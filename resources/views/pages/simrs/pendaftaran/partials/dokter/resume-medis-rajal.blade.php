<div class="tab-pane fade mt-3 border-top" id="resume-medis-rajal" role="tabpanel">
    <header class="text-primary text-center font-weight-bold mt-4 mb-4">
        <h2>RINGKASAN PASIEN RAWAT JALAN</h4>
    </header>
    <div class="row">
        <div class="col-md-12 p-4">
            <table class="table table-borderless">
                <tbody>
                    <tr>
                        <td style="width: 20%;">
                            <label>Nama Pasien</label>
                        </td>
                        <td style="width: 3%;">
                            <label>:</label>
                        </td>
                        <td style="width: 50%;">
                            <input type="text" class="form-control" id="nama_pasien" name="nama_pasien">
                        </td>
                        <td style="width: 20%;">
                            <div class="form-check">
                                <input class="form-check-input custom-checkbox" type="checkbox" id="kunjungan_awal"
                                    name="kunjungan_awal" value="kunjungan_awal">
                                <label class="form-check-label ml-2" for="kunjungan_awal">Kunjungan Awal</label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>No. Rekam Medis</label>
                        </td>
                        <td>
                            <label>:</label>
                        </td>
                        <td>
                            <input type="text" class="form-control" id="norm" name="norm">
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input custom-checkbox" type="checkbox" id="kontrol_lanjutan"
                                    name="kontrol_lanjutan" value="kontrol_lanjutan">
                                <label class="form-check-label ml-2" for="kontrol_lanjutan">Kontrol Lanjutan</label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Tanggal Lahir</label>
                        </td>
                        <td>
                            <label>:</label>
                        </td>
                        <td>
                            <input type="text" class="form-control" id="tgl_lahir" name="tgl_lahir">
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input custom-checkbox" type="checkbox" id="observasi"
                                    name="observasi" value="observasi">
                                <label class="form-check-label ml-2" for="observasi">Observasi</label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Jenis Kelamin</label>
                        </td>
                        <td>
                            <label>:</label>
                        </td>
                        <td>
                            <input type="text" class="form-control" id="kelamin" name="kelamin">
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input custom-checkbox" type="checkbox" id="post_operasi"
                                    name="post_operasi" value="post_operasi">
                                <label class="form-check-label ml-2" for="post_operasi">Post Operasi</label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Tanggal Masuk RS</label>
                        </td>
                        <td>
                            <label>:</label>
                        </td>
                        <td>
                            <input type="text" class="form-control" id="tgl_masuk" name="tgl_masuk">
                        </td>
                        <td>
                            <div class="input-group">
                                <div class="form-group">
                                    <label class="form-label">Berat Lahir</label>
                                    <div class="input-group">
                                        <input type="text"
                                            class="form-control numeric text-left border-top-0 border-right-0 border-left-0 border-bottom"
                                            id="berat_lahir" name="berat_lahir">
                                        <span class="input-group-addon grey-text text-small">gram</span>
                                    </div>
                                </div>

                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Cara Keluar RS</label>
                        </td>
                        <td>
                            <label>:</label>
                        </td>
                        <td colspan="2">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input custom-checkbox" type="checkbox" id="sembuh"
                                    name="sembuh" value="sembuh">
                                <label class="form-check-label" for="sembuh">Sembuh</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input custom-checkbox" type="checkbox" id="meninggal"
                                    name="meninggal" value="meninggal">
                                <label class="form-check-label" for="meninggal">Meninggal</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input custom-checkbox" type="checkbox" id="rawat"
                                    name="rawat" value="rawat">
                                <label class="form-check-label" for="rawat">Rawat</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input custom-checkbox" type="checkbox" id="rujuk"
                                    name="rujuk" value="rujuk">
                                <label class="form-check-label" for="rujuk">Rujuk</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input custom-checkbox" type="checkbox" id="aps"
                                    name="aps" value="aps">
                                <label class="form-check-label" for="aps">APS</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input custom-checkbox" type="checkbox" id="kontrol"
                                    name="kontrol" value="kontrol">
                                <label class="form-check-label" for="kontrol">Kontrol</label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <div class="card mt-3">
                                <div class="card-header bg-info text-white">
                                    Anamnesa
                                </div>
                                <div class="card-body p-0">
                                    <textarea class="form-control border-0 rounded-0" id="anamnesa" name="anamnesa" rows="4">
                                        {Anamnesa}
                                    </textarea>
                                </div>
                            </div>

                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 p-4">
            <h5>KODE ICD-X</h5>
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td>
                            <div class="form-group">
                                <label for="diagnosa_utama" class="form-label">DIAGNOSA UTAMA *</label>
                                <textarea class="form-control" id="diagnosa_utama" name="diagnosa_utama" rows="4" required>Diagnosa Kerja    : P3A1POST SC</textarea>
                            </div>
                        </td>
                        <td style="width: 25%">
                            <div class="form-group">
                                <label for="cari_icd" class="form-label">Cari ICD 10</label>
                                <input type="text" name="cari_icd" id="cari_icd"
                                    class="form-control ui-autocomplete-input" placeholder="Cari ICD 10"
                                    autocomplete="off">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="form-group">
                                <label for="diagnosa_tambahan" class="form-label">DIAGNOSA TAMBAHAN</label>
                                <textarea class="form-control" id="diagnosa_tambahan" name="diagnosa_tambahan" rows="4"></textarea>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <label for="cari_icd_tambahan" class="form-label">Cari ICD 10</label>
                                <input type="text" name="cari_icd_tambahan" id="cari_icd_tambahan"
                                    class="form-control ui-autocomplete-input" placeholder="Cari ICD 10"
                                    autocomplete="off">
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <h5>KODE ICD 9 CM</h5>
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td>
                            <div class="form-group">
                                <label for="tindakan_utama" class="form-label">TINDAKAN UTAMA</label>
                                <textarea class="form-control" id="tindakan_utama" name="tindakan_utama" rows="4">Terapi / Tindakan : K AFF HC GV P. LUKA</textarea>
                            </div>
                        </td>
                        <td style="width: 25%">
                            <div class="form-group">
                                <label for="cari_icd2" class="form-label">Cari ICD 9</label>
                                <input type="text" name="cari_icd2" id="cari_icd2"
                                    class="form-control ui-autocomplete-input" placeholder="Cari ICD 9"
                                    autocomplete="off">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="form-group">
                                <label for="tindakan_tambahan" class="form-label">TINDAKAN TAMBAHAN</label>
                                <textarea class="form-control" id="tindakan_tambahan" name="tindakan_tambahan" rows="4"></textarea>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <label for="cari_icd2_tambahan" class="form-label">Cari ICD 9</label>
                                <input type="text" name="cari_icd2_tambahan" id="cari_icd2_tambahan"
                                    class="form-control ui-autocomplete-input" placeholder="Cari ICD 9"
                                    autocomplete="off">
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>


</div>
