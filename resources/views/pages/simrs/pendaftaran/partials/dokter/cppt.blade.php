<div class="tab-pane fade" id="cppt-dokter-rajal" role="tabpanel">
    <div class="row">
        <div class="col-md-12">
            <div class="p-3 border-top">
                <div class="card-head collapsed d-flex justify-content-between">
                    <div class="title">
                        <header class="text-primary text-center font-weight-bold mb-4">
                            <h2>CPPT DOKTER</h4>
                        </header>
                    </div> <!-- Tambahkan judul jika perlu -->
                    <div class="tools ml-auto"> <!-- Tambahkan ml-auto untuk memindahkan tombol ke kanan -->
                        <button class="btn btn-primary btnAdd mr-2" id="btnAdd" data-toggle="collapse"
                            data-parent="#accordion_soap" data-target="#add_soap" aria-expanded="true">
                            <i class="mdi mdi-plus-circle"></i> Tambah CPPT
                        </button>
                        <button class="btn btn-secondary collapsed" data-toggle="collapse" data-parent="#accordion_soap"
                            data-target="#view-fitler-soap" aria-expanded="false">
                            <i class="mdi mdi-filter"></i> Filter
                        </button>
                    </div>
                </div>
                <div id="add_soap" class="panel-content collapse in" aria-expanded="true">
                    <form method="post" class="form-horizontal" id="fsSOAP" autocomplete="off">
                        <input type="hidden" name="pregid" value="190313" />
                        <input type="hidden" name="prsid" id="prsid" value="" />
                        <input type="hidden" name="tipe" id="tipe" value="2" />
                        <input type="hidden" name="list_kid" id="list_kid" />
                        <input type="hidden" name="is_edit" id="is_edit" value="f" />
                        <input type="hidden" name="re_id" id="re_id" value="" />
                        <input type="hidden" name="taid" id="taid" value="" />
                        <input type="hidden" name="cpid_edit" id="cpid_edit" value="" />

                        <!-- Perawat -->
                        <div class="row">
                            <div class="col-md-6 mt-3">
                                <label for="pid_dokter" class="form-label">Dokter</label>
                                <select name="dokter_id" id="dokter_id" class="form-control select2">
                                    <option></option>
                                    <option value="419">AAN N (BIDAN)</option>
                                    <option value="420">ADE ATIKAH (BIDAN)</option>
                                    <option value="607">ADE ICE</option>
                                    <option value="840">Adi Panji Kusuma Putra, A.Md. Kep</option>
                                    <option value="421">AI ROHMAYATI (BIDAN)</option>
                                </select>
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="konsul_ke" class="form-label">Konsulkan Ke</label>
                                <select name="konsul_ke" id="konsul_ke" class="form-control select2">
                                    <option></option>
                                    <option value="419">AAN N (BIDAN)</option>
                                    <option value="420">ADE ATIKAH (BIDAN)</option>
                                    <option value="607">ADE ICE</option>
                                    <option value="840">Adi Panji Kusuma Putra, A.Md. Kep</option>
                                    <option value="421">AI ROHMAYATI (BIDAN)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Two Column Layout for Subjective and Objective -->
                        <div class="row">
                            <!-- Subjective -->
                            <div class="col-md-6">
                                <div class="card mt-3">
                                    <div class="card-header bg-primary text-white">
                                        <span>Subjective</span>
                                    </div>
                                    <div class="card-body p-0">
                                        <textarea class="form-control border-0 rounded-0" id="subje" name="subje" rows="4"
                                            placeholder="Keluhan Utama"></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Objective -->
                            <div class="col-md-6">
                                <div class="card mt-3">
                                    <div class="card-header bg-success text-white">
                                        <span>Objective</span>
                                    </div>
                                    <div class="card-body p-0">
                                        <textarea class="form-control border-0 rounded-0" id="obje" name="obje" rows="4"
                                            placeholder="Nadi (PR), Respirasi (RR), Tensi (BP), Suhu (T), Tinggi Badan, Berat Badan, Skrining Nyeri"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Two Column Layout for Assessment and Planning -->
                        <div class="row">
                            <!-- Assessment -->
                            <div class="col-md-6">
                                <div class="card mt-3">
                                    <div class="card-header bg-danger text-white d-flex justify-content-between">
                                        <span>Assessment</span>
                                        <span id="diag_perawat" class="badge badge-warning pointer">Diagnosa
                                            Keperawatan</span>
                                    </div>
                                    <div class="card-body p-0">
                                        <textarea class="form-control border-0 rounded-0" id="asse" name="asse" rows="4"
                                            placeholder="Diagnosa Keperawatan"></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Planning -->
                            <div class="col-md-6">
                                <div class="card mt-3">
                                    <div class="card-header bg-warning text-white d-flex justify-content-between">
                                        <span>Planning</span>
                                        <span id="intervensi_perawat"
                                            class="badge badge-dark pointer">Intervensi</span>
                                    </div>
                                    <div class="card-body p-0">
                                        <textarea class="form-control border-0 rounded-0" id="plan" name="plan" rows="4"
                                            placeholder="Rencana Tindak Lanjut"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Evaluation Section -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card mt-3">
                                    <div class="card-header bg-info text-white">
                                        Instruksi
                                    </div>
                                    <div class="card-body p-0">
                                        <textarea class="form-control border-0 rounded-0" id="instruksi" name="instruksi" rows="4"
                                            placeholder="Evaluasi"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card mt-3">
                                    <div class="card-header bg-info text-white">
                                        Resep Manual
                                    </div>
                                    <div class="card-body p-0">
                                        <textarea class="form-control border-0 rounded-0" id="resep_manual" name="resep_manual" rows="4"
                                            placeholder="Resep Manual"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-outline-secondary" id="tutup">
                                <span class="mdi mdi-arrow-up-bold-circle-outline"></span> Tutup
                            </button>
                            <button type="button" class="btn btn-primary" id="bsSOAP" name="save">
                                <span class="mdi mdi-content-save"></span> Simpan
                            </button>
                        </div>
                    </form>
                </div>
                <!-- Filter Section -->
                <div id="view-fitler-soap" class="panel-content collapse" aria-expanded="false">
                    <div class="card-body no-padding">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="s_tgl_1" class="col-sm-4 control-label">Tgl. CPPT</label>
                                    <div class="input-daterange input-group col-sm-8" id="demo-date-range">
                                        <input name="sdate" type="text" class="datepicker form-control"
                                            id="sdate" readonly />
                                        <span class="input-group-addon">s/d</span>
                                        <input name="edate" type="text" class="datepicker form-control"
                                            id="edate" readonly />
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="dept" class="col-sm-4 control-label">Status Rawat</label>
                                    <div class="col-sm-8">
                                        <select class="form-control sel2" id="dept" name="dept">
                                            <option value=""></option>
                                            <option value="ri">Rawat Inap</option>
                                            <option value="rj">Rawat Jalan</option>
                                            <option value="igd">IGD</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="role" class="col-sm-4 control-label">Tipe CPPT</label>
                                    <div class="col-sm-8">
                                        <select class="form-control sel2" id="role" name="role">
                                            <option value=""></option>
                                            <option value="dokter">Dokter</option>
                                            <option value="perawat">Perawat</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 border-top">
            <div class="card-body p-3">
                <div class="table-responsive no-margin">
                    <table id="cppt-table" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th style="width:25%;">Tanggal</th>
                                <th style="width: 70%;">Catatan</th>
                                <th style="width: 6%;">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody id="list_soap">
                            <tr>
                                <td class="text-center">
                                    <div class="deep-purple-text">01 Oct 2024 22:34<br><span class="green-text"
                                            style="font-weight:400;">RAWAT INAP</span><br><b
                                            style="font-weight: 400;">Lia Yulianti, A.Md.Kep</b><br>
                                        <div class="input-oleh deep-orange-text">Input oleh : <br>Lia Yulianti,
                                            A.Md.Kep</div>
                                        <a href="javascript:void(0)"
                                            class="d-block text-uppercase badge badge-primary"><i
                                                class="mdi mdi-plus-circle"></i> Verifikasi</a>
                                        <div>
                                            <img src="http://192.168.1.253/real/include/images/ttd_blank.png"
                                                width="200px;" height="100px;"
                                                onerror="this.src=this.onerror=null; this.src='http://192.168.1.253/real/include/images/ttd_blank.png'">
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <table width="100%" class="table-soap nurse">
                                        <tbody>
                                            <tr>
                                                <td colspan="3" class="soap-text title">Perawat</td>
                                            </tr>
                                            <tr>
                                                <td class="soap-text deep-purple-text text-center" width="8%">S
                                                </td>
                                                <td>Keluhan utama : px mengatakan nyeri luka post sc berkurang</td>
                                            </tr>
                                            <tr>
                                                <td class="soap-text deep-purple-text text-center">O</td>
                                                <td>Keadaan Umum : sedang<br>
                                                    Nadi : 80x/menit <br>
                                                    Respirasi(RR) : 20x/menit<br>
                                                    Tensi (BP) : 130/80mmHg<br>
                                                    Suhu (T) : 36.8C<br>
                                                    Berat badan : Kg<br>
                                                    Skor EWS : 0<br>
                                                    Skor nyeri : 0<br>
                                                    Saturasi : 99<br>
                                                    Skor resiko jatuh : 35</td>
                                            </tr>
                                            <tr>
                                                <td class="soap-text deep-purple-text text-center">A</td>
                                                <td>Diagnosa Keperawatan : gangguan rasa nyaman nyeri<br>
                                                    Diagnosa Keperawatan : <br>
                                                    Diagnosa Keperawatan : </td>
                                            </tr>
                                            <tr>
                                                <td class="soap-text deep-purple-text text-center">P</td>
                                                <td>Rencana Tindak Lanjut : obs ku dan ttv<br>
                                                    Rencana Tindak Lanjut : berikan therapy sesuai advis dpjp<br>
                                                    Rencana Tindak Lanjut : </td>
                                            </tr>
                                            <tr>
                                                <td class="soap-text deep-purple-text text-center"></td>
                                                <td><strong class="deep-orange-text"></strong></td>
                                            </tr>
                                            <tr>
                                                <td class="soap-text"></td>
                                                <td colspan="2"><strong
                                                        class="deep-purple-text"><u>Evaluasi</u>:</strong><br>instoper<br>
                                                    cefo 2x1 jam06;00 4)<br>
                                                    metro tab 3x1<br>
                                                    by (+)<br>
                                                    hasil visit :<br>
                                                    + nifed 3x1<br>
                                                    + dopamet 3x1</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                                <td>
                                    <i class="mdi mdi-content-copy blue-text pointer mdi-18px copy-soap"
                                        data-id="90988" title="Copy"></i>
                                    <i class="mdi mdi-delete-forever red-text pointer mdi-18px hapus-soap"
                                        data-id="90988" title="Hapus"></i>
                                    <i class="mdi mdi-pencil red-text pointer mdi-18px edit-soap" data-id="90988"
                                        title="Edit SOAP & Resep Elektronik" style="display: {show_admin}"></i>
                                    <i class="mdi mdi-printer blue-text pointer mdi-18px print-antrian"
                                        data-id="90988" title="Print Antrian Resep" style="display:"></i>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    <div class="deep-purple-text">01 Oct 2024 22:34<br><span class="green-text"
                                            style="font-weight:400;">RAWAT INAP</span><br><b
                                            style="font-weight: 400;">Lia Yulianti, A.Md.Kep</b><br>
                                        <div class="input-oleh deep-orange-text">Input oleh : <br>Lia Yulianti,
                                            A.Md.Kep</div>
                                        <a href="javascript:void(0)"
                                            class="d-block text-uppercase badge badge-primary"><i
                                                class="mdi mdi-plus-circle"></i> Verifikasi</a>
                                        <div>
                                            <img src="http://192.168.1.253/real/include/images/ttd_blank.png"
                                                width="200px;" height="100px;"
                                                onerror="this.src=this.onerror=null; this.src='http://192.168.1.253/real/include/images/ttd_blank.png'">
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <table width="100%" class="table-soap nurse">
                                        <tbody>
                                            <tr>
                                                <td colspan="3" class="soap-text title">Perawat</td>
                                            </tr>
                                            <tr>
                                                <td class="soap-text deep-purple-text text-center" width="8%">S
                                                </td>
                                                <td>Keluhan utama : px mengatakan nyeri luka post sc berkurang</td>
                                            </tr>
                                            <tr>
                                                <td class="soap-text deep-purple-text text-center">O</td>
                                                <td>Keadaan Umum : sedang<br>
                                                    Nadi : 80x/menit <br>
                                                    Respirasi(RR) : 20x/menit<br>
                                                    Tensi (BP) : 130/80mmHg<br>
                                                    Suhu (T) : 36.8C<br>
                                                    Berat badan : Kg<br>
                                                    Skor EWS : 0<br>
                                                    Skor nyeri : 0<br>
                                                    Saturasi : 99<br>
                                                    Skor resiko jatuh : 35</td>
                                            </tr>
                                            <tr>
                                                <td class="soap-text deep-purple-text text-center">A</td>
                                                <td>Diagnosa Keperawatan : gangguan rasa nyaman nyeri<br>
                                                    Diagnosa Keperawatan : <br>
                                                    Diagnosa Keperawatan : </td>
                                            </tr>
                                            <tr>
                                                <td class="soap-text deep-purple-text text-center">P</td>
                                                <td>Rencana Tindak Lanjut : obs ku dan ttv<br>
                                                    Rencana Tindak Lanjut : berikan therapy sesuai advis dpjp<br>
                                                    Rencana Tindak Lanjut : </td>
                                            </tr>
                                            <tr>
                                                <td class="soap-text deep-purple-text text-center"></td>
                                                <td><strong class="deep-orange-text"></strong></td>
                                            </tr>
                                            <tr>
                                                <td class="soap-text"></td>
                                                <td colspan="2"><strong
                                                        class="deep-purple-text"><u>Evaluasi</u>:</strong><br>instoper<br>
                                                    cefo 2x1 jam06;00 4)<br>
                                                    metro tab 3x1<br>
                                                    by (+)<br>
                                                    hasil visit :<br>
                                                    + nifed 3x1<br>
                                                    + dopamet 3x1</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                                <td>
                                    <i class="mdi mdi-content-copy blue-text pointer mdi-18px copy-soap"
                                        data-id="90988" title="Copy"></i>
                                    <i class="mdi mdi-delete-forever red-text pointer mdi-18px hapus-soap"
                                        data-id="90988" title="Hapus"></i>
                                    <i class="mdi mdi-pencil red-text pointer mdi-18px edit-soap" data-id="90988"
                                        title="Edit SOAP & Resep Elektronik" style="display: {show_admin}"></i>
                                    <i class="mdi mdi-printer blue-text pointer mdi-18px print-antrian"
                                        data-id="90988" title="Print Antrian Resep" style="display:"></i>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    <div class="deep-purple-text">01 Oct 2024 22:34<br><span class="green-text"
                                            style="font-weight:400;">RAWAT INAP</span><br><b
                                            style="font-weight: 400;">Lia Yulianti, A.Md.Kep</b><br>
                                        <div class="input-oleh deep-orange-text">Input oleh : <br>Lia Yulianti,
                                            A.Md.Kep</div>
                                        <a href="javascript:void(0)"
                                            class="d-block text-uppercase badge badge-primary"><i
                                                class="mdi mdi-plus-circle"></i> Verifikasi</a>
                                        <div>
                                            <img src="http://192.168.1.253/real/include/images/ttd_blank.png"
                                                width="200px;" height="100px;"
                                                onerror="this.src=this.onerror=null; this.src='http://192.168.1.253/real/include/images/ttd_blank.png'">
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <table width="100%" class="table-soap nurse">
                                        <tbody>
                                            <tr>
                                                <td colspan="3" class="soap-text title">Perawat</td>
                                            </tr>
                                            <tr>
                                                <td class="soap-text deep-purple-text text-center" width="8%">S
                                                </td>
                                                <td>Keluhan utama : px mengatakan nyeri luka post sc berkurang</td>
                                            </tr>
                                            <tr>
                                                <td class="soap-text deep-purple-text text-center">O</td>
                                                <td>Keadaan Umum : sedang<br>
                                                    Nadi : 80x/menit <br>
                                                    Respirasi(RR) : 20x/menit<br>
                                                    Tensi (BP) : 130/80mmHg<br>
                                                    Suhu (T) : 36.8C<br>
                                                    Berat badan : Kg<br>
                                                    Skor EWS : 0<br>
                                                    Skor nyeri : 0<br>
                                                    Saturasi : 99<br>
                                                    Skor resiko jatuh : 35</td>
                                            </tr>
                                            <tr>
                                                <td class="soap-text deep-purple-text text-center">A</td>
                                                <td>Diagnosa Keperawatan : gangguan rasa nyaman nyeri<br>
                                                    Diagnosa Keperawatan : <br>
                                                    Diagnosa Keperawatan : </td>
                                            </tr>
                                            <tr>
                                                <td class="soap-text deep-purple-text text-center">P</td>
                                                <td>Rencana Tindak Lanjut : obs ku dan ttv<br>
                                                    Rencana Tindak Lanjut : berikan therapy sesuai advis dpjp<br>
                                                    Rencana Tindak Lanjut : </td>
                                            </tr>
                                            <tr>
                                                <td class="soap-text deep-purple-text text-center"></td>
                                                <td><strong class="deep-orange-text"></strong></td>
                                            </tr>
                                            <tr>
                                                <td class="soap-text"></td>
                                                <td colspan="2"><strong
                                                        class="deep-purple-text"><u>Evaluasi</u>:</strong><br>instoper<br>
                                                    cefo 2x1 jam06;00 4)<br>
                                                    metro tab 3x1<br>
                                                    by (+)<br>
                                                    hasil visit :<br>
                                                    + nifed 3x1<br>
                                                    + dopamet 3x1</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                                <td>
                                    <i class="mdi mdi-content-copy blue-text pointer mdi-18px copy-soap"
                                        data-id="90988" title="Copy"></i>
                                    <i class="mdi mdi-delete-forever red-text pointer mdi-18px hapus-soap"
                                        data-id="90988" title="Hapus"></i>
                                    <i class="mdi mdi-pencil red-text pointer mdi-18px edit-soap" data-id="90988"
                                        title="Edit SOAP & Resep Elektronik" style="display: {show_admin}"></i>
                                    <i class="mdi mdi-printer blue-text pointer mdi-18px print-antrian"
                                        data-id="90988" title="Print Antrian Resep" style="display:"></i>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    <div class="deep-purple-text">01 Oct 2024 22:34<br><span class="green-text"
                                            style="font-weight:400;">RAWAT INAP</span><br><b
                                            style="font-weight: 400;">Lia Yulianti, A.Md.Kep</b><br>
                                        <div class="input-oleh deep-orange-text">Input oleh : <br>Lia Yulianti,
                                            A.Md.Kep</div>
                                        <a href="javascript:void(0)"
                                            class="d-block text-uppercase badge badge-primary"><i
                                                class="mdi mdi-plus-circle"></i> Verifikasi</a>
                                        <div>
                                            <img src="http://192.168.1.253/real/include/images/ttd_blank.png"
                                                width="200px;" height="100px;"
                                                onerror="this.src=this.onerror=null; this.src='http://192.168.1.253/real/include/images/ttd_blank.png'">
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <table width="100%" class="table-soap nurse">
                                        <tbody>
                                            <tr>
                                                <td colspan="3" class="soap-text title">Perawat</td>
                                            </tr>
                                            <tr>
                                                <td class="soap-text deep-purple-text text-center" width="8%">S
                                                </td>
                                                <td>Keluhan utama : px mengatakan nyeri luka post sc berkurang</td>
                                            </tr>
                                            <tr>
                                                <td class="soap-text deep-purple-text text-center">O</td>
                                                <td>Keadaan Umum : sedang<br>
                                                    Nadi : 80x/menit <br>
                                                    Respirasi(RR) : 20x/menit<br>
                                                    Tensi (BP) : 130/80mmHg<br>
                                                    Suhu (T) : 36.8C<br>
                                                    Berat badan : Kg<br>
                                                    Skor EWS : 0<br>
                                                    Skor nyeri : 0<br>
                                                    Saturasi : 99<br>
                                                    Skor resiko jatuh : 35</td>
                                            </tr>
                                            <tr>
                                                <td class="soap-text deep-purple-text text-center">A</td>
                                                <td>Diagnosa Keperawatan : gangguan rasa nyaman nyeri<br>
                                                    Diagnosa Keperawatan : <br>
                                                    Diagnosa Keperawatan : </td>
                                            </tr>
                                            <tr>
                                                <td class="soap-text deep-purple-text text-center">P</td>
                                                <td>Rencana Tindak Lanjut : obs ku dan ttv<br>
                                                    Rencana Tindak Lanjut : berikan therapy sesuai advis dpjp<br>
                                                    Rencana Tindak Lanjut : </td>
                                            </tr>
                                            <tr>
                                                <td class="soap-text deep-purple-text text-center"></td>
                                                <td><strong class="deep-orange-text"></strong></td>
                                            </tr>
                                            <tr>
                                                <td class="soap-text"></td>
                                                <td colspan="2"><strong
                                                        class="deep-purple-text"><u>Evaluasi</u>:</strong><br>instoper<br>
                                                    cefo 2x1 jam06;00 4)<br>
                                                    metro tab 3x1<br>
                                                    by (+)<br>
                                                    hasil visit :<br>
                                                    + nifed 3x1<br>
                                                    + dopamet 3x1</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                                <td>
                                    <i class="mdi mdi-content-copy blue-text pointer mdi-18px copy-soap"
                                        data-id="90988" title="Copy"></i>
                                    <i class="mdi mdi-delete-forever red-text pointer mdi-18px hapus-soap"
                                        data-id="90988" title="Hapus"></i>
                                    <i class="mdi mdi-pencil red-text pointer mdi-18px edit-soap" data-id="90988"
                                        title="Edit SOAP & Resep Elektronik" style="display: {show_admin}"></i>
                                    <i class="mdi mdi-printer blue-text pointer mdi-18px print-antrian"
                                        data-id="90988" title="Print Antrian Resep" style="display:"></i>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    <div class="deep-purple-text">01 Oct 2024 22:34<br><span class="green-text"
                                            style="font-weight:400;">RAWAT INAP</span><br><b
                                            style="font-weight: 400;">Lia Yulianti, A.Md.Kep</b><br>
                                        <div class="input-oleh deep-orange-text">Input oleh : <br>Lia Yulianti,
                                            A.Md.Kep</div>
                                        <a href="javascript:void(0)"
                                            class="d-block text-uppercase badge badge-primary"><i
                                                class="mdi mdi-plus-circle"></i> Verifikasi</a>
                                        <div>
                                            <img src="http://192.168.1.253/real/include/images/ttd_blank.png"
                                                width="200px;" height="100px;"
                                                onerror="this.src=this.onerror=null; this.src='http://192.168.1.253/real/include/images/ttd_blank.png'">
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <table width="100%" class="table-soap nurse">
                                        <tbody>
                                            <tr>
                                                <td colspan="3" class="soap-text title">Perawat</td>
                                            </tr>
                                            <tr>
                                                <td class="soap-text deep-purple-text text-center" width="8%">S
                                                </td>
                                                <td>Keluhan utama : px mengatakan nyeri luka post sc berkurang</td>
                                            </tr>
                                            <tr>
                                                <td class="soap-text deep-purple-text text-center">O</td>
                                                <td>Keadaan Umum : sedang<br>
                                                    Nadi : 80x/menit <br>
                                                    Respirasi(RR) : 20x/menit<br>
                                                    Tensi (BP) : 130/80mmHg<br>
                                                    Suhu (T) : 36.8C<br>
                                                    Berat badan : Kg<br>
                                                    Skor EWS : 0<br>
                                                    Skor nyeri : 0<br>
                                                    Saturasi : 99<br>
                                                    Skor resiko jatuh : 35</td>
                                            </tr>
                                            <tr>
                                                <td class="soap-text deep-purple-text text-center">A</td>
                                                <td>Diagnosa Keperawatan : gangguan rasa nyaman nyeri<br>
                                                    Diagnosa Keperawatan : <br>
                                                    Diagnosa Keperawatan : </td>
                                            </tr>
                                            <tr>
                                                <td class="soap-text deep-purple-text text-center">P</td>
                                                <td>Rencana Tindak Lanjut : obs ku dan ttv<br>
                                                    Rencana Tindak Lanjut : berikan therapy sesuai advis dpjp<br>
                                                    Rencana Tindak Lanjut : </td>
                                            </tr>
                                            <tr>
                                                <td class="soap-text deep-purple-text text-center"></td>
                                                <td><strong class="deep-orange-text"></strong></td>
                                            </tr>
                                            <tr>
                                                <td class="soap-text"></td>
                                                <td colspan="2"><strong
                                                        class="deep-purple-text"><u>Evaluasi</u>:</strong><br>instoper<br>
                                                    cefo 2x1 jam06;00 4)<br>
                                                    metro tab 3x1<br>
                                                    by (+)<br>
                                                    hasil visit :<br>
                                                    + nifed 3x1<br>
                                                    + dopamet 3x1</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                                <td>
                                    <i class="mdi mdi-content-copy blue-text pointer mdi-18px copy-soap"
                                        data-id="90988" title="Copy"></i>
                                    <i class="mdi mdi-delete-forever red-text pointer mdi-18px hapus-soap"
                                        data-id="90988" title="Hapus"></i>
                                    <i class="mdi mdi-pencil red-text pointer mdi-18px edit-soap" data-id="90988"
                                        title="Edit SOAP & Resep Elektronik" style="display: {show_admin}"></i>
                                    <i class="mdi mdi-printer blue-text pointer mdi-18px print-antrian"
                                        data-id="90988" title="Print Antrian Resep" style="display:"></i>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    <div class="deep-purple-text">01 Oct 2024 22:34<br><span class="green-text"
                                            style="font-weight:400;">RAWAT INAP</span><br><b
                                            style="font-weight: 400;">Lia Yulianti, A.Md.Kep</b><br>
                                        <div class="input-oleh deep-orange-text">Input oleh : <br>Lia Yulianti,
                                            A.Md.Kep</div>
                                        <a href="javascript:void(0)"
                                            class="d-block text-uppercase badge badge-primary"><i
                                                class="mdi mdi-plus-circle"></i> Verifikasi</a>
                                        <div>
                                            <img src="http://192.168.1.253/real/include/images/ttd_blank.png"
                                                width="200px;" height="100px;"
                                                onerror="this.src=this.onerror=null; this.src='http://192.168.1.253/real/include/images/ttd_blank.png'">
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <table width="100%" class="table-soap nurse">
                                        <tbody>
                                            <tr>
                                                <td colspan="3" class="soap-text title">Perawat</td>
                                            </tr>
                                            <tr>
                                                <td class="soap-text deep-purple-text text-center" width="8%">S
                                                </td>
                                                <td>Keluhan utama : px mengatakan nyeri luka post sc berkurang</td>
                                            </tr>
                                            <tr>
                                                <td class="soap-text deep-purple-text text-center">O</td>
                                                <td>Keadaan Umum : sedang<br>
                                                    Nadi : 80x/menit <br>
                                                    Respirasi(RR) : 20x/menit<br>
                                                    Tensi (BP) : 130/80mmHg<br>
                                                    Suhu (T) : 36.8C<br>
                                                    Berat badan : Kg<br>
                                                    Skor EWS : 0<br>
                                                    Skor nyeri : 0<br>
                                                    Saturasi : 99<br>
                                                    Skor resiko jatuh : 35</td>
                                            </tr>
                                            <tr>
                                                <td class="soap-text deep-purple-text text-center">A</td>
                                                <td>Diagnosa Keperawatan : gangguan rasa nyaman nyeri<br>
                                                    Diagnosa Keperawatan : <br>
                                                    Diagnosa Keperawatan : </td>
                                            </tr>
                                            <tr>
                                                <td class="soap-text deep-purple-text text-center">P</td>
                                                <td>Rencana Tindak Lanjut : obs ku dan ttv<br>
                                                    Rencana Tindak Lanjut : berikan therapy sesuai advis dpjp<br>
                                                    Rencana Tindak Lanjut : </td>
                                            </tr>
                                            <tr>
                                                <td class="soap-text deep-purple-text text-center"></td>
                                                <td><strong class="deep-orange-text"></strong></td>
                                            </tr>
                                            <tr>
                                                <td class="soap-text"></td>
                                                <td colspan="2"><strong
                                                        class="deep-purple-text"><u>Evaluasi</u>:</strong><br>instoper<br>
                                                    cefo 2x1 jam06;00 4)<br>
                                                    metro tab 3x1<br>
                                                    by (+)<br>
                                                    hasil visit :<br>
                                                    + nifed 3x1<br>
                                                    + dopamet 3x1</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                                <td>
                                    <i class="mdi mdi-content-copy blue-text pointer mdi-18px copy-soap"
                                        data-id="90988" title="Copy"></i>
                                    <i class="mdi mdi-delete-forever red-text pointer mdi-18px hapus-soap"
                                        data-id="90988" title="Hapus"></i>
                                    <i class="mdi mdi-pencil red-text pointer mdi-18px edit-soap" data-id="90988"
                                        title="Edit SOAP & Resep Elektronik" style="display: {show_admin}"></i>
                                    <i class="mdi mdi-printer blue-text pointer mdi-18px print-antrian"
                                        data-id="90988" title="Print Antrian Resep" style="display:"></i>
                                </td>
                            </tr>
                            <!-- Additional rows here -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-center">
                                    <!-- Pagination will be handled by DataTables -->
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div><!--end .table-responsive -->
            </div>
        </div>

    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
{{-- <script src="/js/datagrid/datatables/datatables.bundle.js"></script> --}}
<!-- DataTables JS -->

<script>
    $(document).ready(function() {
        $('.btnAdd').click(function() {
            $('#add_soap').collapse('show');
        });

        $('#tutup').on('click', function() {
            $('#add_soap').collapse('hide');

            $('.btnAdd').attr('aria-expanded', 'false');
            $('.btnAdd').addClass('collapsed');
        });
    });
</script>
