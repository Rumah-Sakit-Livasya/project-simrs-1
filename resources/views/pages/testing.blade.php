@extends('inc.layout-no-side')
@section('content')
    <style type="text/css">
        .form-control2 {
            border-left: none;
            border-right: none;
            border-top: none;
            width: 100%;
        }
    </style>
    <div class="row">
        <div class="card">
            <div class="card-body">
                <div class="text-center">
                    <header class="text-warning mt-4">
                        <h4 class="font-weight-bold">TRANSFER RUJUKAN KE RUMAH SAKIT LAIN</h4>
                    </header>
                </div>
                <br>

                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-sm-6 mt-3">
                                <label class="control-label text-primary" for="kpd_yth">Kepada Yth : </label>
                                <input class="form-control" name="kpd_yth" id="kpd_yth" type="text">
                            </div>
                            <div class="col-sm-6 mt-3">
                                <label class="control-label text-primary" for="staff_tlp">Staff Penerima Tlp : </label>
                                <input class="form-control" name="staff_tlp" id="staff_tlp" type="text">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 mt-3">
                                <label class="control-label text-primary" for="bagian">Bagian : </label>
                                <input class="form-control" name="bagian" id="bagian" type="text">
                            </div>
                            <div class="col-sm-6 mt-3">
                                <label class="control-label text-primary" for="kontak_person">Kontak Person : </label>
                                <input class="form-control" name="kontak_person" id="kontak_person" type="text">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 mt-3">
                                <label class="control-label text-primary" for="rs_tujuan">Rumah Sakit Tujuan :</label>
                                <input class="form-control" name="rs_tujuan" id="rs_tujuan" type="text">
                            </div>
                            <div class="col-sm-6 mt-3">
                                <label class="control-label text-primary" for="waktu_tiba_rs">Waktu Tiba di RS Tujuan
                                    :</label>
                                <input class="form-control" name="waktu_tiba_rs" id="waktu_tiba_rs" type="text">
                            </div>
                        </div>
                    </div>
                </div>
                <br>

                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-sm-12">
                                <h4>Mohon untuk tindak lanjut pemeriksaan, pengobatan dan tindakan atas pasien :</h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 mt-3">
                                <label class="control-label text-primary" for="nama_pasien">Nama : </label>
                                <input class="form-control" name="nama_pasien" id="nama_pasien" type="text">
                            </div>
                            <div class="col-sm-6 mt-3">
                                <label class="control-label text-primary" for="tgl_lahir">Tanggal Lahir : </label>
                                <input class="form-control" name="tgl_lahir" id="tgl_lahir" type="text">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 mt-3">
                                <label class="control-label text-primary" for="diagnosa">Diagnosa : </label>
                                <textarea class="form-control" name="diagnosa" id="diagnosa" rows="2"></textarea>
                            </div>
                            <div class="col-sm-6 mt-3">
                                <label class="control-label text-primary" for="riwayat_penyakit">Riwayat Penyakit : </label>
                                <textarea class="form-control" name="riwayat_penyakit" id="riwayat_penyakit" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 mt-3">
                                <label class="control-label text-primary" for="alasan_rujukan">Alasan Rujukan :</label>
                                <textarea class="form-control" name="alasan_rujukan" id="alasan_rujukan" rows="1"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <br>

                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4>Bahwa yang bersangkutan telah mendapat perawatan di Rumah Sakit Ibu dan Anak Livasya
                                    selama :</h4>
                            </div>
                            <div class="col-sm-4">
                                <input class="form-control2" name="tgl_rawat" id="tgl_rawat" type="text">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <h4>Kepada yang bersangkutan telah diberikan</h4>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-sm-12">
                                <label class="control-label text-primary" for="nama_pasien">Tindakan yang dilakukan
                                    :</label>
                            </div>
                            <div class="col-sm-6 mt-2">
                                <div class="col-sm-1">
                                    <label>1.</label>
                                </div>
                                <div class="col-sm-11">
                                    <input class="form-control2" name="tindakan1" id="tindakan1" type="text">
                                </div>
                            </div>
                            <div class="col-sm-6 mt-2">
                                <div class="col-sm-1">
                                    <label>3.</label>
                                </div>
                                <div class="col-sm-11">
                                    <input class="form-control2" name="tindakan3" id="tindakan3" type="text">
                                </div>
                            </div>
                            <div class="col-sm-6 mt-5">
                                <div class="col-sm-1">
                                    <label>2.</label>
                                </div>
                                <div class="col-sm-11">
                                    <input class="form-control2" name="tindakan2" id="tindakan2" type="text">
                                </div>
                            </div>
                            <div class="col-sm-6 mt-5">
                                <div class="col-sm-1">
                                    <label>4.</label>
                                </div>
                                <div class="col-sm-11">
                                    <input class="form-control2" name="tindakan4" id="tindakan4" type="text">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>

                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-sm-12">
                                <label class="control-label text-primary" for="nama_pasien">Terapi yang dilakukan
                                    :</label>
                            </div>
                            <div class="col-sm-6">
                                <div class="col-sm-1">
                                    <label>1.</label>
                                </div>
                                <div class="col-sm-11">
                                    <input class="form-control2" name="terapi1" id="terapi1" type="text">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="col-sm-1">
                                    <label>5.</label>
                                </div>
                                <div class="col-sm-11">
                                    <input class="form-control2" name="terapi5" id="terapi5" type="text">
                                </div>
                            </div>
                            <div class="col-sm-6 mt-5">
                                <div class="col-sm-1">
                                    <label>2.</label>
                                </div>
                                <div class="col-sm-11">
                                    <input class="form-control2" name="terapi2" id="terapi2" type="text">
                                </div>
                            </div>
                            <div class="col-sm-6 mt-5">
                                <div class="col-sm-1">
                                    <label>6.</label>
                                </div>
                                <div class="col-sm-11">
                                    <input class="form-control2" name="terapi6" id="terapi6" type="text">
                                </div>
                            </div>
                            <div class="col-sm-6 mt-5">
                                <div class="col-sm-1">
                                    <label>3.</label>
                                </div>
                                <div class="col-sm-11">
                                    <input class="form-control2" name="terapi3" id="terapi3" type="text">
                                </div>
                            </div>
                            <div class="col-sm-6 mt-5">
                                <div class="col-sm-1">
                                    <label>7.</label>
                                </div>
                                <div class="col-sm-11">
                                    <input class="form-control2" name="terapi7" id="terapi7" type="text">
                                </div>
                            </div>
                            <div class="col-sm-6 mt-5">
                                <div class="col-sm-1">
                                    <label>4.</label>
                                </div>
                                <div class="col-sm-11">
                                    <input class="form-control2" name="terapi4" id="terapi4" type="text">
                                </div>
                            </div>
                            <div class="col-sm-6 mt-5">
                                <div class="col-sm-1">
                                    <label>8.</label>
                                </div>
                                <div class="col-sm-11">
                                    <input class="form-control2" name="terapi8" id="terapi8" type="text">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>

                <div class="row">
                    <div class="col-md-6">
                        <div class="row mt-3">
                            <div class="col-sm-12">
                                <label class="control-label text-primary" for="nama_pasien">Terpasang peralatan medis
                                    :</label>
                            </div>
                            <div class="col-sm-6 mt-2">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="alat_infus"
                                        name="alat_infus" value="infus">
                                    <label class="custom-control-label" for="alat_infus">Infus</label>
                                </div>
                            </div>

                            <div class="col-sm-6 mt-2">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="alat_ett" name="alat_ett"
                                        value="ett">
                                    <label class="custom-control-label" for="alat_ett">ETT</label>
                                </div>
                            </div>

                            <div class="col-sm-6 mt-2">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="alat_catheter"
                                        name="alat_catheter" value="catheter">
                                    <label class="custom-control-label" for="alat_catheter">Catheter</label>
                                </div>
                            </div>

                            <div class="col-sm-6 mt-2">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="alat_oksigen"
                                        name="alat_oksigen" value="oksigen">
                                    <label class="custom-control-label" for="alat_oksigen">Oksigen</label>
                                </div>
                            </div>

                            <div class="col-sm-6 mt-2">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="alat_bidai"
                                        name="alat_bidai" value="bidai">
                                    <label class="custom-control-label" for="alat_bidai">Bidai</label>
                                </div>
                            </div>

                            <div class="col-sm-6 mt-2">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="alat_other"
                                        name="alat_other" value="lain-lain">
                                    <label class="custom-control-label" for="alat_other">Lain - Lain</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="row mt-3">
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label class="control-label text-primary" for="nama_pasien">Kondisi pasien saat
                                            ini :</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input class="form-control2" name="kondisi_now" id="kondisi_now" type="text">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-sm-2">
                                        <label class="control-label text-primary" for="nama_pasien">Kesadaran :</label>
                                    </div>
                                    <div class="col-sm-10">
                                        <div class="col-sm-3">
                                            <span>E :<input class="form-control2" type="text" name="kesadaran_e"
                                                    id="kesadaran_e" style="width:75px;"></span>
                                        </div>
                                        <div class="col-sm-4">
                                            <span>M :<input class="form-control2" type="text" name="kesadaran_m"
                                                    id="kesadaran_m" style="width:85px;"></span>
                                        </div>
                                        <div class="col-sm-3">
                                            <span>V :<input class="form-control2" type="text" name="kesadaran_v"
                                                    id="kesadaran_v" style="width:80px;"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label for="td">TD :<input class="form-control2" name="td" id="td"
                                        type="text" style="width:200px;"></label>
                            </div>
                            <div class="col-sm-6">
                                <label for="n">N :<input class="form-control2" name="n" id="n"
                                        type="text" style="width:200px;"></label>
                            </div>
                            <div class="col-sm-6">
                                <label for="rr">RR :<input class="form-control2" name="rr" id="rr"
                                        type="text" style="width:200px;"></label>
                            </div>
                            <div class="col-sm-6">
                                <label for="s">S : <input class="form-control2" name="s" id="s"
                                        type="text" style="width:200px;"></label>
                            </div>
                        </div>
                    </div>
                </div>
                <br>

                <div class="row mt-3">
                    <div class="col-sm-12">
                        <h4>Demikian surat rujukan ini kami sampaikan, atas perhatian bapak/ibu kami ucapkan terimakasih.
                        </h4>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col"></div>
                            <div class="col"></div>
                            <div class="col">
                                <div class="mb-4 text-center">
                                    Majalengka <input type="text" name="tgl_dilayani" id="tgl_dilayani"
                                        style="border-top: none; border-left: none; border-right: none;">
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-4">
                                <div>
                                    &nbsp;
                                </div>
                                <div style="text-align: center;">
                                    <h4>Dokter Pengirim</h4>
                                </div>
                                <div class="text-center">
                                    <div>
                                        <input type="hidden" name="data_ttd1" id="data_ttd1" value="{ttd1}"
                                            data-imgview="img_ttd1">
                                        <img src="" id="img_ttd1" style="width: 200px; height:100px;"
                                            onerror="this.onerror=null; this.src=''">
                                    </div>
                                    <div>
                                        <input type="text" name="nama_dokter" id="nama_dokter"
                                            class="form-control text-center">
                                    </div>
                                    <div>
                                        <span class="badge blue pointer" id="btn-ttd1">TTD Pen Tablet</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div>
                                    &nbsp;
                                </div>
                                <div style="text-align: center;">
                                    <h4>Staff Yang Melakukan Rujukan</h4>
                                </div>
                                <div class="text-center">
                                    <div>
                                        <input type="hidden" name="data_ttd2" id="data_ttd2" value="{ttd2}"
                                            data-imgview="img_ttd2">
                                        <img src="" id="img_ttd2" style="width: 200px; height:100px;"
                                            onerror="this.onerror=null; this.src=''">
                                    </div>
                                    <div>
                                        <input type="text" name="staff_kirim" id="staff_kirim"
                                            class="form-control text-center">
                                    </div>
                                    <div>
                                        <span class="badge blue pointer" id="btn-ttd2">TTD Pen Tablet</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div style="text-align: center;">
                                    <h4>Staff Penerima Rujukan</h4>
                                </div>
                                <div class="text-center">
                                    <div>
                                        <input type="hidden" name="data_ttd3" id="data_ttd3" value="{ttd3}"
                                            data-imgview="img_ttd3">
                                        <img src="" id="img_ttd3" style="width: 200px; height:100px;"
                                            onerror="this.onerror=null; this.src=''">
                                    </div>
                                    <div>
                                        <input type="text" name="staff_terima" id="staff_terima"
                                            class="form-control text-center">
                                    </div>
                                    <div>
                                        <span class="badge blue pointer" id="btn-ttd3">TTD Pen Tablet</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
            </div>
        </div>
    </div>
@endsection
