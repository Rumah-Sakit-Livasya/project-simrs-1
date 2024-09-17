<style>
    .row {
        margin-left: -12px;
        margin-right: -12px;
    }

    .form .form-group .input-group {
        margin-top: -16px;
    }

    .input-group[class*="col-"] {
        float: none;
        padding-left: 0;
        padding-right: 0;
    }

    .input-group {
        position: relative;
        display: table;
        border-collapse: separate;
    }

    .input-group-content {
        position: relative;
        display: table-cell;
        vertical-align: bottom;
    }

    .input-group .form-control:last-child,
    .input-group-addon:last-child,
    .input-group-btn:last-child>.btn,
    .input-group-btn:last-child>.btn-group>.btn,
    .input-group-btn:last-child>.dropdown-toggle,
    .input-group-btn:first-child>.btn:not(:first-child),
    .input-group-btn:first-child>.btn-group:not(:first-child)>.btn {
        border-bottom-left-radius: 0;
        border-top-left-radius: 0;
    }

    .input-group .form-control:first-child,
    .input-group-addon:first-child,
    .input-group-btn:first-child>.btn,
    .input-group-btn:first-child>.btn-group>.btn,
    .input-group-btn:first-child>.dropdown-toggle,
    .input-group-btn:last-child>.btn:not(:last-child):not(.dropdown-toggle),
    .input-group-btn:last-child>.btn-group:not(:last-child)>.btn {
        border-bottom-right-radius: 0;
        border-top-right-radius: 0;
    }

    .input-group-addon,
    .input-group-btn,
    .input-group .form-control {
        display: table-cell;
    }

    .input-group .form-control {
        position: relative;
        z-index: 2;
        float: left;
        width: 100%;
        margin-bottom: 0;
    }

    .form-control {
        font-size: 1.4rem;
        font-weight: 300;
        color: #313534;
    }


    .form-control {
        padding: 0;
        height: 37px;
        border-left: none;
        border-right: none;
        border-top: none;
        border-bottom-color: rgba(12, 12, 12, 0.12);
        background: transparent;
        color: #0c0c0c;
        font-size: 16px;
        -webkit-box-shadow: none;
        box-shadow: none;
    }

    .form .form-group .input-group-content,
    .form .form-group .input-group-addon,
    .form .form-group .input-group-btn {
        padding-top: 16px;
    }

    .card-head .header-pasien {
        display: grid;
        padding: 15px 24px;
        line-height: 1.864;
        grid-template-columns: 100px 1fr 100px 1fr;
        grid-column-gap: 10px;
        font-weight: 300;
        color: #9E9E9E;
    }

    .wongbaker {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        justify-items: center;
    }

    *,
    *::before,
    *::after {
        box-sizing: border-box;
    }
</style>
<div class="card-head">
    <div class="header-pasien">
        <img src="http://192.168.1.253/real/include/avatar/woman-icon.png" width="80" class="avatar">
        <div>
            <div class="name" onclick="reg_patient()">REVITA JULIANI</div>
            <div class="birth">14 Jul 2023 (1thn 1bln 30hr) <i class="mdi mdi-gender-female"></i></div>
            <div class="rm">RM 03-87-41</div>
            <div class="birth">BPJS KESEHATAN</div>
            <div>
                Info Billing: <span title="Billing: 164.574, Proses Order: 0"
                    style="color: green;
        font-weight: 400;text-decoration: underline; margin-right: 5px;"
                    id="info_billing">164.574</span><i class="fa fa-refresh pointer" id="get_info_bill"></i>
            </div>
            <!-- tambahan by rizal -->
            <div class="detail-alergi" onclick="openForm()">Tidak ada alergi</div>
        </div>
        <img src="http://192.168.1.253/real/include/avatar/woman-doctor.png" width="80" class="avatar">
        <div>
            <div class="name">dr. Ratih Eka Pujasari Sp.A</div>
            <div class="birth">KLINIK ANAK</div>
            <div class="rm">Reg 2409130117 (13 Sep 2024)</div>
            <div class="rm">Rawat Jalan</div>
        </div>
    </div>
</div>
<div class="card-actionbar p-3">
    <div class="card-actionbar-row-left">
        <button type="button" class="btn btn-primary waves-effect waves-light margin-left-xl" id="panggil"
            onclick="panggil()"><span class="glyphicon glyphicon-music "></span>&nbsp;&nbsp;Panggil Antrian</button>
        <button class="btn btn-warning"
            onclick="popupFull('http://192.168.1.253/real/antrol_bpjs/update_waktu_antrean_vclaim/2409047399','p_card', 900,600,'no'); return false;">
            <i class="mdi mdi-update"></i> Antrol BPJS
        </button>
        <button class="btn btn-danger waves-effect waves-light" onclick="showIcare();"><i
                class="mdi mdi-account-convert"></i> Bridging Icare</button>
        <button class="btn btn-info margin-left-md" id="popup_klpcm">
            <i class="mdi mdi-file" id="mdi-chk"></i> KLPCM
        </button>
    </div>
</div>

<form method="post" class="form" id="form-builder" autocomplete="off" enctype='multipart/form-data'>
    <input type="hidden" name="pregid" id="pregid" value="183016">
    <input type="hidden" name="ftid" id="ftid" value="-24">
    <input type="hidden" name="pkid" id="pkid" value="">
    <div class="card-actionbar">
        <div class="card-actionbar-row">
            <button class="btn btn-primary m-3" id="histori_pengkajian" type="button"><i class="mdi mdi-history"></i>
                Histori</button>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <header class="green-text">
                <h4>MASUK RUMAH SAKIT</h4>
            </header>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="tgl_masuk" class="control-label">Tanggal &amp; jam masuk</label>
                        <div class="input-daterange input-group col-sm-8" id="demo-date-range">
                            <div class="input-group-content">
                                <input name="tgl_masuk" id="tgl_masuk" class="form-control datepicker"
                                    data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy"
                                    type="text" im-insert="false">
                            </div>
                            <span class="input-group-addon">, </span>
                            <div class="input-group-content">
                                <input class="form-control time" name="jam_masuk" id="jam_masuk" style="width: 50px;"
                                    type="text" im-insert="false">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="tgl_dilayani" class="control-label">Tanggal &amp; jam dilayani</label>
                        <div class="input-daterange input-group col-sm-8" id="demo-date-range">
                            <div class="input-group-content">
                                <input name="tgl_dilayani" id="tgl_dilayani" class="form-control datepicker"
                                    data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy"
                                    type="text" im-insert="false">
                            </div>
                            <span class="input-group-addon">, </span>
                            <div class="input-group-content">
                                <input class="form-control time" name="jam_dilayani" id="jam_dilayani"
                                    style="width: 50px;" type="text" im-insert="false">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <textarea class="form-control" id="keluhan_utama" name="keluhan_utama" rows="3" required=""
                            data-label="Keluhan utama"></textarea>
                        <label for="keluhan_utama" class="control-label">Keluhan utama *</label>
                    </div>
                </div>

            </div>
            <header class="orange-text margin-top-lg">
                <h4>TANDA TANDA VITAL</h4>
            </header>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-content">
                                <input class="form-control numeric" id="pr" name="pr" value=""
                                    type="text">
                                <label for="pr">Nadi (PR)</label>
                            </div>
                            <span class="input-group-addon grey-text">x/menit</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-content">
                                <input class="form-control numeric" id="rr" name="rr" value=""
                                    type="text">
                                <label for="rr">Respirasi (RR)</label>
                            </div>
                            <span class="input-group-addon grey-text">x/menit</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-content">
                                <input class="form-control numeric" id="bp" name="bp" value=""
                                    type="text">
                                <label for="bp">Tensi (BP)</label>
                            </div>
                            <span class="input-group-addon grey-text">mmHg</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-content">
                                <input class="form-control numeric" id="temperatur" name="temperatur" value=""
                                    type="text">
                                <label for="temperatur">Suhu (T)</label>
                            </div>
                            <span class="input-group-addon grey-text">C°</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-content">
                                <input class="form-control numeric calc-bmi" id="body_height" name="body_height"
                                    type="text">
                                <label for="height">Tinggi Badan</label>
                            </div>
                            <span class="input-group-addon grey-text">Cm</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-content">
                                <input class="form-control numeric calc-bmi" id="body_weight" name="body_weight"
                                    type="text">
                                <label for="weight">Berat Badan</label>
                            </div>
                            <span class="input-group-addon grey-text">Kg</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-content">
                                <input class="form-control numeric" id="bmi" name="bmi"
                                    readonly="readonly" type="text">
                                <label for="bmi">Index Massa Tubuh</label>
                            </div>
                            <span class="input-group-addon grey-text">Kg/m</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-content">
                                <input class="form-control" id="kat_bmi" name="kat_bmi" readonly="readonly"
                                    type="text">
                                <label for="kat_bmi">Katerogi IMT</label>
                            </div>
                            <span class="input-group-addon grey-text"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <input class="form-control" id="sp02" name="sp02" value="" type="text">
                        <label for="sp02" class="">SP 02</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <input class="form-control" id="lingkar_kepala" name="lingkar_kepala" value=""
                            type="text">
                        <label for="lingkar_kepala" class="">Lingkar Kepala</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <select name="diagnosa_keperawatan" id="diagnosa_keperawatan" class="sel2">
                            <option value="-">-</option>
                            <option value="Gangguan rasa nyaman">Gangguan rasa nyaman</option>
                            <option value="Nyeri">Nyeri</option>
                            <option value="Pola Nafas tidak efektif">Pola Nafas tidak efektif</option>
                            <option value="Bersihan jalan nafas tidak efektif">Bersihan jalan nafas tidak efektif
                            </option>
                            <option value="Nyeri Akut">Nyeri Akut</option>
                            <option value="Nyeri Kronis">Nyeri Kronis</option>
                            <option value="Resiko Infeksi">Resiko Infeksi</option>
                            <option value="Harga diri Rendah">Harga diri Rendah</option>
                            <option value="Resiko Perilaku Kekerasan">Resiko Perilaku Kekerasan</option>
                            <option value="Halusinasi">Halusinasi</option>
                            <option value="Isolasi Sosial">Isolasi Sosial</option>
                            <option value="Resiko Bunuh Diri">Resiko Bunuh Diri</option>
                            <option value="Waham">Waham</option>
                        </select>
                        <label for="nyeri" class="control-label">Diagnosa Keperawatan</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <select name="rencana_tindak_lanjut" id="rencana_tindak_lanjut" class="sel2">
                            <option value="-">-</option>
                            <option value="Kolaborasi Dokter">Kolaborasi Dokter</option>
                            <option value="Perawatan Luka">Perawatan Luka</option>
                            <option value="Memberikan Edukasi">Memberikan Edukasi</option>
                            <option value="Mengukur tanda - tanda vital">Mengukur tanda - tanda vital</option>
                        </select>
                        <label for="nyeri" class="control-label">Rencana Tindak Lanjut</label>
                    </div>
                </div>
            </div>

            <header class="red-text">
                <h4>ALERGI DAN REAKSI</h4>
            </header>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="alergi_obat" class="control-label margin-tb-10">Alergi Obat</label>
                        <div class="form-radio"> &nbsp;&nbsp;&nbsp;&nbsp;
                            <label class="radio-inline radio-styled radio-info">
                                <input value="Ya" name="alergi_obat" id="alergi_obat1"
                                    type="radio"><span>Ya</span>
                            </label>
                            <input name="ket_alergi_obat" id="ket_alergi_obat"
                                style="margin-right: 10px; width: 200px;border-left: none;border-right: none;border-top: none;border-bottom-color: rgba(12, 12, 12, 0.12);"
                                type="text">
                            <label class="radio-inline radio-styled radio-info">
                                <input value="Tidak" name="alergi_obat" id="alergi_obat2"
                                    type="radio"><span>Tidak</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <input name="reaksi_alergi_obat" id="reaksi_alergi_obat" class="form-control alergi"
                            type="text">
                        <label for="reaksi_alergi_obat" class="control-label">Reaksi terhadap alergi obat</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="alergi_makanan" class="control-label margin-tb-10">Alergi makanan</label>
                        <div class="form-radio"> &nbsp;&nbsp;&nbsp;&nbsp;
                            <label class="radio-inline radio-styled radio-info">
                                <input value="Ya" name="alergi_makanan" id="alergi_makanan1"
                                    type="radio"><span>Ya</span>
                            </label>
                            <input name="ket_alergi_makanan" id="ket_alergi_makanan"
                                style="margin-right: 10px; width: 200px;border-left: none;border-right: none;border-top: none;border-bottom-color: rgba(12, 12, 12, 0.12);"
                                type="text">
                            <label class="radio-inline radio-styled radio-info">
                                <input value="Tidak" name="alergi_makanan" id="alergi_makanan2"
                                    type="radio"><span>Tidak</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <input name="reaksi_alergi_makanan" id="reaksi_alergi_makanan" class="form-control alergi"
                            type="text">
                        <label for="reaksi_alergi_makanan" class="control-label">Reaksi terhadap alergi
                            makanan</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="alergi_lainnya" class="control-label margin-tb-10">Alergi lainya</label>
                        <div class="form-radio"> &nbsp;&nbsp;&nbsp;&nbsp;
                            <label class="radio-inline radio-styled radio-info">
                                <input value="Ya" name="alergi_lainnya" id="alergi_lainnya1"
                                    type="radio"><span>Ya</span>
                            </label>
                            <input name="ket_alergi_lainnya" id="ket_alergi_lainnya"
                                style="margin-right: 10px; width: 200px;border-left: none;border-right: none;border-top: none;border-bottom-color: rgba(12, 12, 12, 0.12);"
                                type="text">
                            <label class="radio-inline radio-styled radio-info">
                                <input value="Tidak" name="alergi_lainnya" id="alergi_lainnya2"
                                    type="radio"><span>Tidak</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <input name="reaksi_alergi_lainnya" id="reaksi_alergi_lainnya" class="form-control alergi"
                            type="text">
                        <label for="reaksi_alergi_lainnya" class="control-label">Reaksi terhadap alergi
                            lainnya</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="kondisi_khusus1" class="control-label margin-tb-10">Gelang tanda
                            alergi</label>
                        <div class="form-radio">
                            <label class="checkbox-styled checkbox-success no-margin">
                                <input value="warna merah" name="gelang" type="checkbox">
                                <span> dipasang (warna merah)</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <header class="red-text">
                <h4>SKRINING NYERI</h4>
            </header>
            <div class="row">
                <div class="col-md-11">
                    <div class="wongbaker">
                        <div class="img-baker">
                            <img src="http://192.168.1.253/testing/include/images/wongbaker/1.jpg">
                            <div>
                                <span class="badge pink accent-2 pointer" data-skor="0">0</span>
                            </div>
                        </div>
                        <div class="img-baker">
                            <img src="http://192.168.1.253/testing/include/images/wongbaker/2.jpg">
                            <div>
                                <span class="badge green pointer" data-skor="1">1</span>
                                <span class="badge green pointer" data-skor="2">2</span>
                            </div>
                        </div>
                        <div class="img-baker">
                            <img src="http://192.168.1.253/testing/include/images/wongbaker/3.jpg">
                            <div>
                                <span class="badge blue pointer" data-skor="3">3</span>
                                <span class="badge blue pointer" data-skor="4">4</span>
                            </div>
                        </div>
                        <div class="img-baker">
                            <img src="http://192.168.1.253/testing/include/images/wongbaker/4.jpg">
                            <div>
                                <span class="badge purple pointer" data-skor="5">5</span>
                                <span class="badge purple pointer" data-skor="6">6</span>
                            </div>
                        </div>
                        <div class="img-baker">
                            <img src="http://192.168.1.253/testing/include/images/wongbaker/5.jpg">
                            <div>
                                <span class="badge orange pointer" data-skor="7">7</span>
                                <span class="badge orange pointer" data-skor="8">8</span>
                            </div>
                        </div>
                        <div class="img-baker">
                            <img src="http://192.168.1.253/testing/include/images/wongbaker/6.jpg">
                            <div>
                                <span class="badge pointer red" data-skor="9">9</span>
                                <span class="badge pointer red" data-skor="10">10</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <input name="skor_nyeri" id="skor_nyeri" class="form-control"
                            style="font-size: 3rem; height: 60px;" type="text">
                        <label for="skor_nyeri" class="control-label">Skor</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <input name="provokatif" id="provokatif" class="form-control" type="text">
                        <label for="provokatif" class="control-label">Provokatif</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <input name="quality" id="quality" class="form-control" type="text">
                        <label for="quality" class="control-label">Quality</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <input name="region" id="region" class="form-control" type="text">
                        <label for="region" class="control-label">Region</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <input name="time" id="time" class="form-control" type="text">
                        <label for="time" class="control-label">Time</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <select name="nyeri" id="nyeri" class="sel2">
                            <option value="-">-</option>
                            <option value="Nyeri kronis">Nyeri kronis</option>
                            <option value="Nyeri akut">Nyeri akut</option>
                            <option value="TIdak ada nyeri">TIdak ada nyeri</option>
                        </select>
                        <label for="nyeri" class="control-label">Nyeri</label>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="form-group">
                        <input name="nyeri_hilang" id="nyeri_hilang" class="form-control" type="text">
                        <label for="nyeri_hilang" class="control-label">Nyeri hilang apabila</label>
                    </div>
                </div>
            </div>

            <header class="green-text">
                <h4>SKRINING GIZI</h4>
            </header>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <select name="penurunan_bb" id="penurunan_bb" class="sel2">
                            <option></option>
                            <option value="Tidak">Tidak</option>
                            <option value="Tidak yakin / Ragu-ragu">Tidak yakin / Ragu-ragu</option>
                            <option value="Ya, 1-5 Kg">Ya, 1-5 Kg</option>
                            <option value="Ya, 6-10 Kg">Ya, 6-10 Kg</option>
                            <option value="Ya, 11-15 Kg">Ya, 11-15 Kg</option>
                            <option value="Ya, > 15 Kg">Ya, &gt; 15 Kg</option>
                            <option value="Ya, tidak tahu berapa Kg">Ya, tidak tahu berapa Kg</option>
                        </select>
                        <label for="penurunan_bb" class="control-label">Penurunan berat badan 6 bln
                            terakhir</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <select name="asupan_makan" id="asupan_makan" class="sel2">
                            <option></option>
                            <option value="Normal">Normal</option>
                            <option value="Berkurang, penurunan nafsu makan/kesulitan menerima makan" data-skor="1">
                                Berkurang, penurunan nafsu makan/kesulitan menerima makan</option>
                        </select>
                        <label for="asupan_makan" class="control-label">Asupan makanan pasien</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="kondisi_khusus1" class="control-label margin-tb-10">Pasien dalam kondisi
                            khusus</label>
                        <div class="form-radio">
                            <label class="checkbox-styled checkbox-success no-margin">
                                <input name="kondisi_khusus1" id="kondisi_khusus1" value="Anak usia 1-5 tahun"
                                    type="checkbox"><span>Anak usia 1-5 tahun</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="form-radio">
                            <label class="checkbox-styled checkbox-success no-margin">
                                <input name="kondisi_khusus2" id="kondisi_khusus2" value="Lansia > 60 tahun"
                                    type="checkbox"><span>Lansia &gt; 60 tahun</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="form-radio">
                            <label class="checkbox-styled checkbox-success no-margin">
                                <input name="kondisi_khusus3" id="kondisi_khusus3"
                                    value="Penyakit kronis dengan komplikasi" type="checkbox"><span>Penyakit
                                    kronis dengan komplikasi</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="form-radio">
                            <label class="checkbox-styled checkbox-success no-margin">
                                <input name="kondisi_khusus4" id="kondisi_khusus4" value="Kanker stadium III/IV"
                                    type="checkbox"><span>Kanker stadium III/IV</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="form-radio">
                            <label class="checkbox-styled checkbox-success no-margin">
                                <input name="kondisi_khusus5" id="kondisi_khusus5" value="HIV/AIDS"
                                    type="checkbox"><span>HIV/AIDS</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="form-radio">
                            <label class="checkbox-styled checkbox-success no-margin">
                                <input name="kondisi_khusus6" id="kondisi_khusus6" value="TB"
                                    type="checkbox"><span>TB</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="form-radio">
                            <label class="checkbox-styled checkbox-success no-margin">
                                <input name="kondisi_khusus7" id="kondisi_khusus7" value="Bedah mayor degestif"
                                    type="checkbox"><span>Bedah mayor degestif</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="form-radio">
                            <label class="checkbox-styled checkbox-success no-margin">
                                <input name="kondisi_khusus8" id="kondisi_khusus8" value="Luka bakar > 20%"
                                    type="checkbox"><span>Luka bakar &gt; 20%</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <header class="purple-text">
                <h4>RIWAYAT IMUNISASI DASAR</h4>
            </header>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="form-radio">
                            <label class="checkbox-styled checkbox-success no-margin">
                                <input name="imunisasi_dasar1" id="imunisasi_dasar1" value="BCG"
                                    type="checkbox"><span>BCG</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="form-radio">
                            <label class="checkbox-styled checkbox-success no-margin">
                                <input name="imunisasi_dasar2" id="imunisasi_dasar2" value="DPT"
                                    type="checkbox"><span>DPT</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="form-radio">
                            <label class="checkbox-styled checkbox-success no-margin">
                                <input name="imunisasi_dasar3" id="imunisasi_dasar3" value="Hepatitis B"
                                    type="checkbox"><span>Hepatitis B</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="form-radio">
                            <label class="checkbox-styled checkbox-success no-margin">
                                <input name="imunisasi_dasar4" id="imunisasi_dasar4" value="Polio"
                                    type="checkbox"><span>Polio</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="form-radio">
                            <label class="checkbox-styled checkbox-success no-margin">
                                <input name="imunisasi_dasar5" id="imunisasi_dasar5" value="Campak"
                                    type="checkbox"><span>Campak</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <header class="purple-text">
                <h4>SKRINING RESIKO JATUH - GET UP &amp; GO</h4>
            </header>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="resiko_jatuh1" class="control-label margin-tb-10">A. Cara Berjalan</label>
                        <div class="form-radio">
                            <label class="checkbox-styled checkbox-success no-margin">
                                <input onclick="resiko_jatuh()" name="resiko_jatuh1" id="resiko_jatuh1"
                                    value="Tidak seimbang/sempoyongan/limbung" type="checkbox"><span>Tidak
                                    seimbang/sempoyongan/limbung</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="form-radio">
                            <label class="checkbox-styled checkbox-success no-margin">
                                <input onclick="resiko_jatuh()" name="resiko_jatuh2" id="resiko_jatuh2"
                                    value="Alat bantu: kruk,kursi roda/dibantu" type="checkbox"><span>Jalan dengan
                                    alat bantu(kruk,kursi roda/dibantu)</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="resiko_jatuh3" class="control-label margin-tb-10">B. Menopang saat
                            duduk</label>
                        <div class="form-radio">
                            <label class="checkbox-styled checkbox-success no-margin">
                                <input onclick="resiko_jatuh()" name="resiko_jatuh3" id="resiko_jatuh3"
                                    value="Pegang pinggiran meja/kursi/alat bantu untuk duduk"
                                    type="checkbox"><span>Pegang pinggiran meja/kursi/alat bantu untuk duduk</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <span class="input-group-addon grey-text">Hasil : </span>
                        <div class="input-group-content">
                            <input class="form-control" name="resiko_jatuh_hasil" id="resiko_jatuh_hasil"
                                type="text" readonly="">
                        </div>
                    </div>
                </div>
            </div>
            <header class="orange-text">
                <h4>RIWAYAT PSIKOSOSIAL, SPIRITUAL &amp; KEPERCAYAAN</h4>
            </header>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <select name="status_psikologis" id="status_psikologis" class="sel2">
                            <option></option>
                            <option value="Tenang">Tenang</option>
                            <option value="Cemas">Cemas</option>
                            <option value="Takut">Takut</option>
                            <option value="Marah">Marah</option>
                            <option value="Sedih">Sedih</option>
                            <option value="Kecenderungan bunuh diri">Kecenderungan bunuh diri</option>
                        </select>
                        <label for="status_psikologis" class="control-label">Status psikologis</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <select name="status_spiritual" id="status_spiritual" class="sel2">
                            <option></option>
                            <option value="Percaya Nilai-nilai dan kepercayaan">Percaya Nilai-nilai dan kepercayaan
                            </option>
                            <option value="Tidak Percaya Nilai-nilai dan kepercayaan">Tidak Percaya Nilai-nilai dan
                                kepercayaan</option>
                        </select>
                        <label for="status_spiritual" class="control-label">Status spiritual</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <input name="masalah_prilaku" id="masalah_prilaku" class="form-control" type="text">
                        <label for="masalah_prilaku" class="control-label">Masalah prilaku(bila ada)</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <input name="kekerasan_dialami" id="kekerasan_dialami" class="form-control" type="text">
                        <label for="kekerasan_dialami" class="control-label">Kekerasan yg pernah dialami</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <input name="hub_dengan_keluarga" id="hub_dengan_keluarga" class="form-control"
                            type="text">
                        <label for="hub_dengan_keluarga" class="control-label">Hubungan dengan anggota
                            keluarga</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <input name="tempat_tinggal" id="tempat_tinggal" class="form-control" type="text">
                        <label for="tempat_tinggal" class="control-label">Tempat tinggal
                            (rumah/panti/kos/dll)</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <input name="kerabat_dihub" id="kerabat_dihub" class="form-control" type="text">
                        <label for="kerabat_dihub" class="control-label">Kerabat yang dapat dihubungi</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <input name="no_kontak_kerabat" id="no_kontak_kerabat" class="form-control" type="text">
                        <label for="no_kontak_kerabat" class="control-label">Kontak kerabat yang dapat
                            dihubungi</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="status_perkawinan" class="control-label">Status perkawinan</label>
                        <input name="status_perkawinan" id="status_perkawinan" class="form-control"
                            value="Belum Nikah" disabled="" type="text">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="pekerjaan" class="control-label">Pekerjaan</label>
                        <input name="pekerjaan" id="pekerjaan" class="form-control" value="" disabled=""
                            type="text">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <select name="penghasilan" id="penghasilan" class="sel2">
                            <option></option>
                            <option value="< 1 Juta">&lt; 1 Juta</option>
                            <option value="1 - 2,9 Juta">1 - 2,9 Juta</option>
                            <option value="3 - 4,9 Juta">3 - 4,9 Juta</option>
                            <option value="5 - 9,9 Juta">5 - 9,9 Juta</option>
                            <option value="10 - 14,9 Juta">10 - 14,9 Juta</option>
                            <option value="15 - 19.5 Juta">15 - 19.5 Juta</option>
                            <option value="> 20 Juta">&gt; 20 Juta</option>
                        </select>
                        <label for="penghasilan" class="control-label">Penghasilan</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <input name="pendidikan" id="pendidikan" class="form-control" type="text"
                            value="Belum / Tidak tamat SD">
                        <label for="pendidikan" class="control-label">Pendidikan</label>
                    </div>
                </div>
            </div>
            <header class="brown-text">
                <h4>KEBUTUHAN EDUKASI</h4>
            </header>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="hambatan_belajar1" class="control-label margin-tb-10">Hambatan dalam
                            pembelajaran</label>
                        <div class="form-radio">
                            <label class="checkbox-styled checkbox-success no-margin">
                                <input name="hambatan_belajar1" id="hambatan_belajar1" value="Pendengaran"
                                    type="checkbox"><span>Pendengaran</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="form-radio">
                            <label class="checkbox-styled checkbox-success no-margin">
                                <input name="hambatan_belajar2" id="hambatan_belajar2" value="Penglihatan"
                                    type="checkbox"><span>Penglihatan</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="form-radio">
                            <label class="checkbox-styled checkbox-success no-margin">
                                <input name="hambatan_belajar3" id="hambatan_belajar3" value="Kognitif"
                                    type="checkbox"><span>Kognitif</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="form-radio">
                            <label class="checkbox-styled checkbox-success no-margin">
                                <input name="hambatan_belajar4" id="hambatan_belajar4" value="Fisik"
                                    type="checkbox"><span>Fisik</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="form-radio">
                            <label class="checkbox-styled checkbox-success no-margin">
                                <input name="hambatan_belajar5" id="hambatan_belajar5" value="Budaya"
                                    type="checkbox"><span>Budaya</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="form-radio">
                            <label class="checkbox-styled checkbox-success no-margin">
                                <input name="hambatan_belajar6" id="hambatan_belajar6" value="Agama"
                                    type="checkbox"><span>Agama</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="form-radio">
                            <label class="checkbox-styled checkbox-success no-margin">
                                <input name="hambatan_belajar7" id="hambatan_belajar7" value="Emosi"
                                    type="checkbox"><span>Emosi</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="form-radio">
                            <label class="checkbox-styled checkbox-success no-margin">
                                <input name="hambatan_belajar8" id="hambatan_belajar8" value="Bahasa"
                                    type="checkbox"><span>Bahasa</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <div class="form-radio">
                            <label class="checkbox-styled checkbox-success no-margin">
                                <input name="ambatan_belajar9" id="ambatan_belajar9" value="Tidak ada Hamabatan"
                                    type="checkbox"><span>Tidak ada Hamabatan</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <input name="hambatan_lainnya" id="hambatan_lainnya" class="form-control" type="text">
                        <label for="hambatan_lainnya" class="control-label">Hambatan lainnya</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <input name="kebutuhan_penerjemah" id="kebutuhan_penerjemah" class="form-control"
                            type="text">
                        <label for="kebutuhan_penerjemah" class="control-label">Kebutuhan penerjemah</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="kebuthan_pembelajaran1" class="control-label margin-tb-10">Kebutuhan
                            pembelajaran</label>
                        <div class="form-radio">
                            <label class="checkbox-styled checkbox-success no-margin">
                                <input name="kebuthan_pembelajaran1" id="kebuthan_pembelajaran1"
                                    value="Diagnosa managemen" type="checkbox"><span>Diagnosa managemen</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="form-radio">
                            <label class="checkbox-styled checkbox-success no-margin">
                                <input name="kebuthan_pembelajaran2" id="kebuthan_pembelajaran2" value="Obat-obatan"
                                    type="checkbox"><span>Obat-obatan</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="form-radio">
                            <label class="checkbox-styled checkbox-success no-margin">
                                <input name="kebuthan_pembelajaran3" id="kebuthan_pembelajaran3"
                                    value="Perawatan luka" type="checkbox"><span>Perawatan luka</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="form-radio">
                            <label class="checkbox-styled checkbox-success no-margin">
                                <input name="kebuthan_pembelajaran4" id="kebuthan_pembelajaran4" value="Rehabilitasi"
                                    type="checkbox"><span>Rehabilitasi</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="form-radio">
                            <label class="checkbox-styled checkbox-success no-margin">
                                <input name="kebuthan_pembelajaran5" id="kebuthan_pembelajaran5"
                                    value="Manajemen nyeri" type="checkbox"><span>Manajemen nyeri</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="form-radio">
                            <label class="checkbox-styled checkbox-success no-margin">
                                <input name="kebuthan_pembelajaran6" id="kebuthan_pembelajaran6"
                                    value="Diet &amp; nutrisi" type="checkbox"><span>Diet &amp; nutrisi</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="form-radio">
                            <label class="checkbox-styled checkbox-success no-margin">
                                <input name="kebuthan_pembelajaran7" id="kebuthan_pembelajaran7"
                                    value="Tidak ada Hamabatan" type="checkbox"><span>Tidak ada Hamabatan</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <input name="pembelajaran_lainnya" id="pembelajaran_lainnya" class="form-control"
                            type="text">
                        <label for="pembelajaran_lainnya" class="control-label">Kebutuhan pembelajaran
                            lainnya</label>
                    </div>
                </div>
            </div>

            <header class="orange-text">
                <h4>Assesment Fungsional (Pengkajian Fungsi)</h4>
            </header>
            <header class="orange-text">
                <h4>Sensorik</h4>
            </header>
            <div class="row">
                <table class="table">
                    <tbody>
                        <tr>
                            <td>Penglihatan</td>
                            <td>
                                <div class="radio radio-styled">
                                    <label>
                                        <input name="sensorik_penglihatan" value="Normal" data-skor="0"
                                            class="apgar" type="radio">
                                        <span>Normal</span>
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="radio radio-styled">
                                    <label>
                                        <input name="sensorik_penglihatan" value="Kabur" data-skor="1"
                                            class="apgar" type="radio">
                                        <span>Kabur</span>
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="radio radio-styled">
                                    <label>
                                        <input name="sensorik_penglihatan" value="Kaca Mata" data-skor="2"
                                            class="apgar" type="radio">
                                        <span>Kaca Mata</span>
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="radio radio-styled">
                                    <label>
                                        <input name="sensorik_penglihatan" value="Lensa Kontak" data-skor="3"
                                            class="apgar" type="radio">
                                        <span>Lensa Kontak</span>
                                    </label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Penciuman</td>
                            <td>
                                <div class="radio radio-styled">
                                    <label>
                                        <input name="sensorik_penciuman" value="Normal" data-skor="0"
                                            class="apgar" type="radio">
                                        <span>Normal</span>
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="radio radio-styled">
                                    <label>
                                        <input name="sensorik_penciuman" value="Tidak" data-skor="1"
                                            class="apgar" type="radio">
                                        <span>Tidak</span>
                                    </label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Pendengaran</td>
                            <td>
                                <div class="radio radio-styled">
                                    <label>
                                        <input name="sensorik_pendengaran" value="Normal" data-skor="0"
                                            class="apgar" type="radio">
                                        <span>Normal</span>
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="radio radio-styled">
                                    <label>
                                        <input name="sensorik_pendengaran" value="Tuli Ka / Ki" data-skor="1"
                                            class="apgar" type="radio">
                                        <span>Tuli Ka / Ki</span>
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="radio radio-styled">
                                    <label>
                                        <input name="sensorik_pendengaran" value="Ada alat bantu dengar ka/ki"
                                            data-skor="2" class="apgar" type="radio">
                                        <span>Ada alat bantu dengar ka/ki</span>
                                    </label>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <header class="orange-text">
                <h4>Kognitif</h4>
            </header>
            <div class="row">
                <table class="table">
                    <tbody>
                        <tr>
                            <td>&nbsp;</td>
                            <td>
                                <div class="radio radio-styled">
                                    <label>
                                        <input name="kognitif" value="Normal" data-skor="0" class="apgar"
                                            type="radio">
                                        <span>Normal</span>
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="radio radio-styled">
                                    <label>
                                        <input name="kognitif" value="Bingung" data-skor="1" class="apgar"
                                            type="radio">
                                        <span>Bingung</span>
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="radio radio-styled">
                                    <label>
                                        <input name="kognitif" value="Pelupa" data-skor="2" class="apgar"
                                            type="radio">
                                        <span>Pelupa</span>
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="radio radio-styled">
                                    <label>
                                        <input name="kognitif" value="Tidak Dapat dimengerti" data-skor="3"
                                            class="apgar" type="radio">
                                        <span>Tidak Dapat dimengerti</span>
                                    </label>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <header class="orange-text">
                <h4>Motorik</h4>
            </header>
            <div class="row">
                <table class="table">
                    <tbody>
                        <tr>
                            <td>Aktifitas Sehari - hari</td>
                            <td>
                                <div class="radio radio-styled">
                                    <label>
                                        <input name="motorik_aktifitas" value="Mandiri" data-skor="0"
                                            class="apgar" type="radio">
                                        <span>Mandiri</span>
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="radio radio-styled">
                                    <label>
                                        <input name="motorik_aktifitas" value="Bantuan Minimal" data-skor="1"
                                            class="apgar" type="radio">
                                        <span>Bantuan Minimal</span>
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="radio radio-styled">
                                    <label>
                                        <input name="motorik_aktifitas" value="Bantuan Ketergantungan Total"
                                            data-skor="2" class="apgar" type="radio">
                                        <span>Bantuan Ketergantungan Total</span>
                                    </label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Berjalan</td>
                            <td>
                                <div class="radio radio-styled">
                                    <label>
                                        <input name="motorik_berjalan" value="Tidak Ada kesulitan" data-skor="0"
                                            class="apgar" type="radio">
                                        <span>Tidak Ada kesulitan</span>
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="radio radio-styled">
                                    <label>
                                        <input name="motorik_berjalan" value="Perlu Bantuan" data-skor="1"
                                            class="apgar" type="radio">
                                        <span>Perlu Bantuan</span>
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="radio radio-styled">
                                    <label>
                                        <input name="motorik_berjalan" value="Sering Jatuh" data-skor="0"
                                            class="apgar" type="radio">
                                        <span>Sering Jatuh</span>
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="radio radio-styled">
                                    <label>
                                        <input name="motorik_berjalan" value="Kelumpuhan" data-skor="1"
                                            class="apgar" type="radio">
                                        <span>Kelumpuhan</span>
                                    </label>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="input-group">
                        <div
                            style="min-height: 150px; display: flex; justify-content: flex-end; flex-direction: column; align-items: center;">
                            <div>Perawat,</div>
                            <div>
                                <input type="hidden" name="data_ttd" id="data_ttd" value="{ttd_perawat}"
                                    data-imgview="img_ttd">
                                <img src="http://192.168.1.253/testing/include/images/ttd_blank.png" id="img_ttd"
                                    style="width: 200px; height:100px;"
                                    onerror="this.onerror=null; this.src='http://192.168.1.253/testing/include/images/ttd_blank.png'">
                            </div>
                            <div style="width: 70%;">
                                <input type="text" name="nama_dokter" class="form-control text-center">
                            </div>
                            <div>
                                <span class="badge blue pointer" id="btn-ttd">TTD Pen Tablet</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-actionbar">
        <div class="card-actionbar-row">
            <a href="#!" class="btn btn-primary waves-effect waves-light pull-left"><span
                    class="mdi mdi-printer print-pengkajian" data-pkid="" data-pregid="183016" data-ftid="-24"
                    data-printtype="{print_type}" data-link="{link}"> Print</span></a>
            <button type="button" class="btn btn-warning waves-effect waves-light save-form" data-dismiss='modal'
                data-status="0"><span class="mdi mdi-content-save"></span> Simpan
                (draft)</button>
            <button type="button" class="btn btn-save-final waves-effect waves-light save-form"
                data-dismiss='modal' data-status="1"><span class="mdi mdi-content-save"></span> Simpan
                (final)</button>
        </div>
    </div>
</form>
@section('plugin')
    <script type="text/javascript" src="/js/painterro-1.2.3.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#histori_pengkajian').on('click', function() {
                atmedic.App.popup({
                    url: base_url() + 'pengkajian/histori_pengkajian/183016',
                    mode: 'md',
                    data: {
                        pregid: '183016',
                        ftid: '-24'
                    },
                    title: 'Histori pengkajian'
                });
            });

            $('#btn-ttd').on('click', function() {
                popupwindow(base_url() + 'pengkajian/signature/ttd', 'popup_ttd', 730, 420, 'no');
            });

            $('.img-baker .pointer').on('click', function() {
                $('#skor_nyeri').val($(this).data('skor'));
            });

            $('.bartel').on('change', function() {
                let skor = bartelIndex();
                $('#skor_bartel').val(skor);
                if (skor < 9)
                    $('#analisis_bartel').val('Total Care');
                else if (skor >= 9 && skor < 12)
                    $('#analisis_bartel').val('Partial Care');
                else
                    $('#analisis_bartel').val('Self Care');
            });

            let bartelIndex = function() {
                let data = 0;
                $('.bartel').each(function(index) {
                    data += isNaN($("option:selected", this).data('skor')) ? 0 : $("option:selected",
                        this).data('skor');
                });

                return data;
            }

            if (document.getElementById('pkid').value == '')
                atmedic.App.getDataLink(base_url() + 'pengkajian/get_link_pengkajian_rajal', '183016');

            function get_bmi() {
                A = document.getElementById('body_height').value;
                B = document.getElementById('body_weight').value;

                if (A != '' && B != '') {
                    A = A / 100;
                    C = B / (A * A);
                    C = Math.round(C * 10) / 10;

                    if (C < 18.5)
                        document.getElementById('kat_bmi').value = 'Kurus';
                    else if (C > 24.9)
                        document.getElementById('kat_bmi').value = 'Gemuk';
                    else if ((C >= 18.5) && (C <= 24.9))
                        document.getElementById('kat_bmi').value = 'Normal';
                    else
                        document.getElementById('kat_bmi').value = '';
                    document.getElementById('bmi').value = C;

                    $('#bmi, #kat_bmi').addClass('dirty');
                } else {
                    document.getElementById('bmi').value = '';
                    document.getElementById('kat_bmi').value = '';
                    $('#bmi, #kat_bmi').removeClass('dirty');
                }
            }

            get_bmi();

            $('.calc-bmi').on('change', get_bmi);
        });

        function resiko_jatuh() {
            var resiko_jatuh1 = document.getElementById('resiko_jatuh1').checked;
            var resiko_jatuh2 = document.getElementById('resiko_jatuh2').checked;
            var resiko_jatuh3 = document.getElementById('resiko_jatuh3').checked;

            if (resiko_jatuh1 == false && resiko_jatuh2 == false && resiko_jatuh3 == false) {
                $('#resiko_jatuh_hasil').val("Tidak Beresiko");
            } else if (resiko_jatuh1 == true || resiko_jatuh2 == true) {
                if (resiko_jatuh3 == true) {
                    $('#resiko_jatuh_hasil').val("Resiko Tinggi");
                } else if (resiko_jatuh3 == false) {
                    $('#resiko_jatuh_hasil').val("Resiko Sedang");
                }
            } else if (resiko_jatuh1 == false || resiko_jatuh2 == false) {
                if (resiko_jatuh3 == true) {
                    $('#resiko_jatuh_hasil').val("Resiko Sedang");
                } else if (resiko_jatuh3 == false) {
                    $('#resiko_jatuh_hasil').val("Resiko Tinggi");
                }
            }
        };
        resiko_jatuh();
    </script>
@endsection
