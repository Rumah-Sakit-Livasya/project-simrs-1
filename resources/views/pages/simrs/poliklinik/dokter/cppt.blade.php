@extends('inc.layout')
@section('tmp_body', 'layout-composed')
@section('extended-css')
    @include('pages.simrs.poliklinik.partials.css-sidebar-custom')
    <style>
        main {
            overflow-x: hidden;
        }

        input[type="time"] {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }

        .badge {
            cursor: pointer;
        }

        .badge.badge-orange {
            background-color: #ff5722;
            color: #ffffff;
        }

        .badge.badge-red {
            background-color: #f44336;
            color: #ffffff;
        }

        @media (max-width: 768px) {
            .img-baker {
                width: 45%;
                margin-bottom: 1rem;
            }
        }


        @media (min-width: 992px) {
            .nav-function-hidden:not(.nav-function-top) .page-sidebar:hover {
                left: -16.25rem;
                -webkit-transition: 450ms cubic-bezier(0.9, 0.01, 0.09, 1);
                transition: 450ms cubic-bezier(0.9, 0.01, 0.09, 1);
            }

            .nav.nav-tabs.action-erm {
                position: fixed;
                background: #ffffff;
                width: 100%;
                padding-top: 10px;
                padding-bottom: 10px;
                padding-left: 15px;
                z-index: 1;
            }

            .tab-content {
                margin-top: 55px;
            }
        }

        .slide-on-mobile {
            width: 20rem;
        }

        .text-decoration-underline {
            text-decoration: underline;
        }

        .text-secondary {
            font-size: 12px;
        }

        @media only screen and (max-width: 992px) {
            .slide-on-mobile-left {
                border-right: 1px solid rgba(0, 0, 0, 0.09);
                left: 0;
            }

            .slide-on-mobile {
                width: 17rem;
            }
        }

        #toggle-pasien i {
            color: #3366b9;
        }

        #js-slide-left {
            border-right: 1px solid rgba(0, 0, 0, 0.3);
            background: white;
        }

        #js-slide-left.hide {
            display: none;
        }

        .gradient-text {
            font-size: 1.5rem;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
            background: linear-gradient(135deg, rgba(0, 123, 255, 1), rgb(255 121 0 / 100%));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            display: block;
        }

        .spaced-text {
            letter-spacing: 0.4em;
            font-weight: bold;
            background: linear-gradient(135deg, rgba(0, 123, 255, 1), rgb(255 121 0 / 100%));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            display: block;
        }

        .logo-dashboard-simrs {
            width: 100%;
        }
    </style>
@endsection
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <!-- notice the utilities added to the wrapper below -->
        <div class="d-flex flex-grow-1 p-0 shadow-1 layout-composed">
            <!-- left slider panel : must have unique ID-->
            @include('pages.simrs.poliklinik.partials.filter-poli')

            <!-- middle content area -->
            <div class="d-flex flex-column flex-grow-1 bg-white">
                @include('pages.simrs.poliklinik.partials.menu-erm')

                {{-- content start --}}
                <div class="tab-content p-3">
                    <div class="tab-pane fade show active" id="tab_default-1" role="tabpanel">
                        @include('pages.simrs.poliklinik.partials.detail-pasien')
                        <hr style="border-color: #868686; margin-top: 50px; margin-bottom: 30px;">
                        <div class="row">
                            <form action="javascript:void(0)" class="w-100" data-tipe-cppt="dokter"
                                data-tipe-cppt="rawat-jalan" id="cppt-dokter-rajal-form">
                                @csrf
                                @method('POST')
                                <div class="col-md-12">
                                    <div class="p-3">
                                        <div class="card-head collapsed d-flex justify-content-between">
                                            <div class="title">
                                                <header class="text-primary text-center font-weight-bold mb-4">
                                                    <h2 class="font-weight-bold">CPPT DOKTER</h4>
                                                </header>
                                            </div> <!-- Tambahkan judul jika perlu -->
                                            <div class="tools ml-auto">
                                                <!-- Tambahkan ml-auto untuk memindahkan tombol ke kanan -->
                                                <button class="btn btn-primary btnAdd mr-2" id="btnAdd"
                                                    data-toggle="collapse" data-parent="#accordion_soap"
                                                    data-target="#add_soap" aria-expanded="true">
                                                    <i class="mdi mdi-plus-circle"></i> Tambah CPPT
                                                </button>
                                                <button class="btn btn-secondary collapsed" data-toggle="collapse"
                                                    data-parent="#accordion_soap" data-target="#view-fitler-soap"
                                                    aria-expanded="false">
                                                    <i class="mdi mdi-filter"></i> Filter
                                                </button>
                                            </div>
                                        </div>
                                        <div id="add_soap" class="panel-content collapse in" aria-expanded="true">
                                            <form method="post" class="form-horizontal" id="fsSOAP" autocomplete="off">
                                                <input type="hidden" name="registration_id"
                                                    value="{{ $registration->id }}" />
                                                <input type="hidden" name="medical_record_number" id="noRM_cppt"
                                                    value="{{ $registration->patient->medical_record_number }}" />

                                                <!-- Perawat -->
                                                <div class="row">
                                                    <div class="col-md-6 mt-3">
                                                        <label for="pid_dokter" class="form-label">Dokter</label>
                                                        <select
                                                            class="select2 form-control @error('doctor_id') is-invalid @enderror"
                                                            name="doctor_id" id="cppt_doctor_id">
                                                            <option value=""></option>
                                                            @foreach ($jadwal_dokter as $jadwal)
                                                                <option value="{{ $jadwal->doctor_id }}">
                                                                    {{ $jadwal->doctor->employee->fullname }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 mt-3">
                                                        <label for="konsulkan_ke" class="form-label">Konsulkan Ke</label>
                                                        <select
                                                            class="select2 form-control @error('doctor_id') is-invalid @enderror"
                                                            name="konsulkan_ke" id="konsulkan_ke">
                                                            <option value=""></option>
                                                            @foreach ($jadwal_dokter as $jadwal)
                                                                <option value="{{ $jadwal->doctor_id }}">
                                                                    {{ $jadwal->doctor->employee->fullname }}</option>
                                                            @endforeach
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
                                                                <textarea class="form-control border-0 rounded-0" id="subjective" name="subjective" rows="4"
                                                                    placeholder="Keluhan Utama">Alergi obat : 
Reaksi alergi obat : 
Keluhan Utama : KONSULTASI
PASIEN TELAH PENGOBATAN 6 BULAN TB PARU
DI PUSKESMAS JATITUJUH 
Riwayat Penyakit Sekarang : KONSULTASI
PASIEN TELAH PENGOBATAN 6 BULAN TB PARU
DI PUSKESMAS JATITUJUH 
Riwayat Penyakit Dahulu : TIDAK ADA
Riwayat Penyakit Keluarga : TIDAK ADA
Alergi makan : 
Reaksi alergi makan : 
Alergi lainya : 
Reaksi alergi lainya : </textarea>
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
                                                                <textarea class="form-control border-0 rounded-0" id="objective" name="objective" rows="4">Nadi (PR): 
Respirasi (RR): 
Tensi (BP): 
Suhu (T): 
Tinggi Badan: 
Berat Badan: 
Skrining Nyeri:
                                                            </textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Two Column Layout for Assessment and Planning -->
                                                <div class="row">
                                                    <!-- Assessment -->
                                                    <div class="col-md-6">
                                                        <div class="card mt-3">
                                                            <div
                                                                class="card-header bg-danger text-white d-flex justify-content-between">
                                                                <span>Assessment</span>
                                                                <span id="diag_perawat"
                                                                    class="badge badge-warning pointer">Diagnosa
                                                                    Keperawatan</span>
                                                            </div>
                                                            <div class="card-body p-0">
                                                                <textarea class="form-control border-0 rounded-0" id="assesment" name="assesment" rows="4"
                                                                    placeholder="Diagnosa Keperawatan">Diagnosa Kerja:</textarea>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Planning -->
                                                    <div class="col-md-6">
                                                        <div class="card mt-3">
                                                            <div
                                                                class="card-header bg-warning text-white d-flex justify-content-between">
                                                                <span>Planning</span>
                                                                <span id="intervensi_perawat"
                                                                    class="badge badge-dark pointer">Intervensi</span>
                                                            </div>
                                                            <div class="card-body p-0">
                                                                <textarea class="form-control border-0 rounded-0" id="planning" name="planning" rows="4"
                                                                    placeholder="Rencana Tindak Lanjut">Terapi / Tindakan :</textarea>
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

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="card mt-3">
                                                            <div class="card-header bg-primary text-white">
                                                                Resep Elektronik
                                                            </div>
                                                            <div class="card-body p-0">
                                                                <div class="row p-2">
                                                                    <div class="col-6">
                                                                        <select
                                                                            class="select2 form-control @error('doctor_id') is-invalid @enderror"
                                                                            name="doctor_id" id="cppt_doctor_id">
                                                                            <option value="152">BK IBU</option>
                                                                            <option selected="selected" value="3">
                                                                                FARMASI RAJAL</option>
                                                                            <option value="110">FARMASI RANAP</option>
                                                                            <option value="150">OBAT KHUSUS KARYAWAN
                                                                            </option>
                                                                            <option value="140">PSRS</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <input type="text" name="nama_obat"
                                                                            id="nama_obat"
                                                                            class="form-control ui-autocomplete-input"
                                                                            placeholder="Cari Obat" autocomplete="off">
                                                                        <div class="form-control-line"></div>
                                                                        <input type="hidden" name="mbid"
                                                                            id="mbid">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{-- <div class="col-lg-12">
                                                        <div class="card-head deep-purple-text-bg"><header class="no-padding-left">Resep Elektronik</header></div>
                                                        <div class="col-sm-3">
                                                            <select name="mgid" id="mgid" class="sel2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                                                <option value="152">BK IBU</option>
                                                                <option selected="selected" value="3">FARMASI RAJAL</option>
                                                                <option value="110">FARMASI RANAP</option>
                                                                <option value="150">OBAT KHUSUS KARYAWAN</option>
                                                                <option value="140">PSRS</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-7">
                                                            <input type="text" name="nama_obat" id="nama_obat" class="form-control ui-autocomplete-input" placeholder="Cari Obat" autocomplete="off"><div class="form-control-line"></div>
                                                            <input type="hidden" name="mbid" id="mbid">
                                                            <span class="mdi mdi-magnify mdi-24px pink-text form-control-feedback pointer" id="pilih_item"></span>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <div class="form-group">
                                                                <div class="form-radio" style="margin: 5px 12px 0 12px;">
                                                                    <label class="checkbox-styled checkbox-success no-margin">
                                                                        <input name="zat_aktif" id="zat_aktif" value="true" type="checkbox"><span>Zat Aktif</span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div> --}}
                                                </div>

                                                <div class="row">
                                                    <div class="col-12">
                                                        <table class="table table-striped">
                                                            <thead class="smooth">
                                                                <tr>
                                                                    <th style="width: 25%;">Nama Obat</th>
                                                                    <th style="width: 10%;">UOM</th>
                                                                    <th style="width: 5%;">Stok</th>
                                                                    <th style="width: 5%;">Harga</th>
                                                                    <th style="width: 10%;">Qty</th>
                                                                    <th style="width: 10%;">Subtotal Harga</th>
                                                                    <th style="width: 15%">Signa</th>
                                                                    <th style="width: 15%">Instruksi</th>
                                                                    <th style="width: 1%;">&nbsp;</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="table_re"></tbody>
                                                            <tbody>
                                                                <tr>
                                                                    <td colspan="8" align="right">Grand Total</td>
                                                                    <td align="right"><span id="grand_total"
                                                                            style="text-align: right;"
                                                                            class="numeric">0</span>
                                                                        <input type="hidden" name="total_bpjs"
                                                                            id="total_bpjs" value="0"
                                                                            readonly="">
                                                                        <input type="hidden" name="is_bpjs"
                                                                            id="is_bpjs" value="f" readonly="">
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>

                                                <!-- Action Buttons -->
                                                <div class="d-flex justify-content-between mt-4">
                                                    <button type="button" class="btn btn-outline-secondary"
                                                        id="tutup">
                                                        <span class="mdi mdi-arrow-up-bold-circle-outline"></span> Tutup
                                                    </button>
                                                    <button type="button" class="btn btn-primary btn-saves-soap"
                                                        id="bsSOAP" name="save">
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
                                                            <label for="s_tgl_1" class="col-sm-4 control-label">Tgl.
                                                                CPPT</label>
                                                            <div class="input-daterange input-group col-sm-8"
                                                                id="demo-date-range">
                                                                <input name="sdate" type="text"
                                                                    class="datepicker form-control" id="sdate"
                                                                    readonly />
                                                                <span class="input-group-addon">s/d</span>
                                                                <input name="edate" type="text"
                                                                    class="datepicker form-control" id="edate"
                                                                    readonly />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label for="dept" class="col-sm-4 control-label">Status
                                                                Rawat</label>
                                                            <div class="col-sm-8">
                                                                <select class="form-control sel2" id="dept"
                                                                    name="dept">
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
                                                            <label for="role" class="col-sm-4 control-label">Tipe
                                                                CPPT</label>
                                                            <div class="col-sm-8">
                                                                <select class="form-control sel2" id="role"
                                                                    name="role">
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
                                        <!-- End Filter Section -->
                                    </div>
                                </div>
                            </form>

                            <div class="col-md-12">
                                <hr style="border-color: #868686; margin-bottom: 50px;">
                                <div class="card-body p-3">
                                    <div class="table-responsive no-margin">
                                        <table id="cppt-table" class="table table-striped table-bordered"
                                            style="width:100%">
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
                                                        <div class="deep-purple-text">01 Oct 2024 22:34<br><span
                                                                class="green-text" style="font-weight:400;">RAWAT
                                                                INAP</span><br><b style="font-weight: 400;">Lia Yulianti,
                                                                A.Md.Kep</b><br>
                                                            <div class="input-oleh deep-orange-text">Input oleh : <br>Lia
                                                                Yulianti,
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
                                                                    <td colspan="3" class="soap-text title">Perawat
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="soap-text deep-purple-text text-center"
                                                                        width="8%">S
                                                                    </td>
                                                                    <td>Keluhan utama : px mengatakan nyeri luka post sc
                                                                        berkurang</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="soap-text deep-purple-text text-center">O
                                                                    </td>
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
                                                                    <td class="soap-text deep-purple-text text-center">A
                                                                    </td>
                                                                    <td>Diagnosa Keperawatan : gangguan rasa nyaman
                                                                        nyeri<br>
                                                                        Diagnosa Keperawatan : <br>
                                                                        Diagnosa Keperawatan : </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="soap-text deep-purple-text text-center">P
                                                                    </td>
                                                                    <td>Rencana Tindak Lanjut : obs ku dan ttv<br>
                                                                        Rencana Tindak Lanjut : berikan therapy sesuai advis
                                                                        dpjp<br>
                                                                        Rencana Tindak Lanjut : </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="soap-text deep-purple-text text-center">
                                                                    </td>
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
                                                        <i class="mdi mdi-pencil red-text pointer mdi-18px edit-soap"
                                                            data-id="90988" title="Edit SOAP & Resep Elektronik"
                                                            style="display: {show_admin}"></i>
                                                        <i class="mdi mdi-printer blue-text pointer mdi-18px print-antrian"
                                                            data-id="90988" title="Print Antrian Resep"
                                                            style="display:"></i>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center">
                                                        <div class="deep-purple-text">01 Oct 2024 22:34<br><span
                                                                class="green-text" style="font-weight:400;">RAWAT
                                                                INAP</span><br><b style="font-weight: 400;">Lia Yulianti,
                                                                A.Md.Kep</b><br>
                                                            <div class="input-oleh deep-orange-text">Input oleh : <br>Lia
                                                                Yulianti,
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
                                                                    <td colspan="3" class="soap-text title">Perawat
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="soap-text deep-purple-text text-center"
                                                                        width="8%">S
                                                                    </td>
                                                                    <td>Keluhan utama : px mengatakan nyeri luka post sc
                                                                        berkurang</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="soap-text deep-purple-text text-center">O
                                                                    </td>
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
                                                                    <td class="soap-text deep-purple-text text-center">A
                                                                    </td>
                                                                    <td>Diagnosa Keperawatan : gangguan rasa nyaman
                                                                        nyeri<br>
                                                                        Diagnosa Keperawatan : <br>
                                                                        Diagnosa Keperawatan : </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="soap-text deep-purple-text text-center">P
                                                                    </td>
                                                                    <td>Rencana Tindak Lanjut : obs ku dan ttv<br>
                                                                        Rencana Tindak Lanjut : berikan therapy sesuai advis
                                                                        dpjp<br>
                                                                        Rencana Tindak Lanjut : </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="soap-text deep-purple-text text-center">
                                                                    </td>
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
                                                        <i class="mdi mdi-pencil red-text pointer mdi-18px edit-soap"
                                                            data-id="90988" title="Edit SOAP & Resep Elektronik"
                                                            style="display: {show_admin}"></i>
                                                        <i class="mdi mdi-printer blue-text pointer mdi-18px print-antrian"
                                                            data-id="90988" title="Print Antrian Resep"
                                                            style="display:"></i>
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
                </div>
            </div>
        </div>
    </main>
@endsection
@section('plugin')
    <script script src="/js/formplugins/select2/select2.bundle.js"></script>
    @include('pages.simrs.poliklinik.partials.action-js.cppt-dokter-rajal')
    <script>
        $(document).ready(function() {
            $('body').addClass('layout-composed');
            $('.select2').select2({
                placeholder: 'Pilih Item',
            });
            $('#departement_id').select2({
                placeholder: 'Pilih Klinik',
            });

            // $('#doctor_id').select2({
            //     placeholder: 'Pilih Dokter',
            // });

            $('#cppt_doctor_id').select2({
                placeholder: 'Pilih Dokter',
            });

            $('#toggle-pasien').on('click', function() {
                var target = $('#js-slide-left'); // Mengambil elemen target berdasarkan data-target
                var backdrop = $('.slide-backdrop'); // Mengambil backdrop

                // Toggle kelas untuk menampilkan atau menyembunyikan panel dan backdrop
                target.toggleClass('hide');
                backdrop.toggleClass('show');
            });

            // Close the panel if the backdrop is clicked
            $('.slide-backdrop').on('click', function() {
                $('#js-slide-left').removeClass('slide-on-mobile-left-show');
                $(this).removeClass('show');
            });
        });
    </script>

    @include('pages.simrs.poliklinik.partials.js-filter')
@endsection
