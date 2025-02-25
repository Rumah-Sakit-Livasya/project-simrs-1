@extends('inc.layout')
@section('tmp_body', 'layout-composed')
@section('extended-css')
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
                            <form action="javascript:void(0)" class="w-100" data-tipe-cppt="perawat"
                                data-tipe-rawat="rawat-jalan" id="cppt-perawat-rajal-form">
                                @csrf
                                @method('POST')
                                <div class="col-md-12">
                                    <div class="p-3">
                                        <div class="card-head collapsed d-flex justify-content-between">
                                            <div class="title">
                                                <header class="text-primary text-center font-weight-bold mb-4">
                                                    <h2 class="font-weight-bold">CPPT PERAWAT</h4>
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
                                                <input type="hidden" name="tipe_rawat"
                                                    value="rawat-jalan" />
                                                <input type="hidden" name="tipe_cppt"
                                                    value="perawat" />
                                                <input type="hidden" name="medical_record_number" id="noRM_cppt"
                                                    value="{{ $registration->patient->medical_record_number }}" />

                                                <!-- Perawat -->
                                                <div class="row">
                                                    <div class="col-md-6 mt-3">
                                                        <label for="pid_dokter" class="form-label">Perawat</label>
                                                        <select
                                                            class="select2 form-control @error('perawat_id') is-invalid @enderror"
                                                            name="perawat_id" id="perawat_id">
                                                            <option value=""></option>
                                                            @foreach ($perawat as $item)
                                                                <option value="{{$item->user->id}}">{{$item->fullname}}</option>
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
    @include('pages.simrs.poliklinik.partials.js-filter')
    
    <script>
        $(document).ready(function() {

            $('#cppt_doctor_id').val("{{ $registration->doctor_id }}")
            $('.btnAdd').click(function() {
                $('#add_soap').collapse('show');
            });

            $('#tutup').on('click', function() {
                $('#add_soap').collapse('hide');

                $('.btnAdd').attr('aria-expanded', 'false');
                $('.btnAdd').addClass('collapsed');
            });

            // Saat tombol Save Final diklik
            $('#bsSOAP').on('click', function() {
                submitFormCPPT(); // Panggil fungsi submitForm dengan parameter final
            });

            // function loadCPPTData() {
            //     $.ajax({
            //         // url: '{{-- route('cppt.get') --}}', // Mengambil route Laravel
            //         type: 'GET',
            //         dataType: 'json',
            //         success: function(response) {
            //             // Bersihkan tabel
            //             $('#list_soap').empty();

            //             // Iterasi setiap data dan tambahkan ke dalam tabel
            //             $.each(response, function(index, data) {
            //                 var row = `
            //                 <tr>
            //                     <td class="text-center">
            //                         <div class="deep-purple-text">${data.created_at}<br>
            //                             <span class="green-text" style="font-weight:400;">${data.tipe_rawat}</span><br>
            //                             <b style="font-weight: 400;">Dokter ID: ${data.doctor_id}</b><br>
            //                             <div class="input-oleh deep-orange-text">Input oleh: ${data.user_id}</div>
            //                             <a href="javascript:void(0)" class="d-block text-uppercase badge badge-primary"><i class="mdi mdi-plus-circle"></i> Verifikasi</a>
            //                             <div>
            //                                 <img src="http://192.168.1.253/real/include/images/ttd_blank.png" width="200px;" height="100px;">
            //                             </div>
            //                         </div>
            //                     </td>
            //                     <td>
            //                         <table width="100%" class="table-soap nurse">
            //                             <tbody>
            //                                 <tr><td colspan="3" class="soap-text title">CPPT</td></tr>
            //                                 <tr><td class="soap-text deep-purple-text text-center" width="8%">S</td><td>${data.subjective.replace(/\n/g, "<br>")}</td></tr>
            //                                 <tr><td class="soap-text deep-purple-text text-center">O</td><td>${data.objective.replace(/\n/g, "<br>")}</td></tr>
            //                                 <tr><td class="soap-text deep-purple-text text-center">A</td><td>${data.assesment}</td></tr>
            //                                 <tr><td class="soap-text deep-purple-text text-center">P</td><td>${data.planning}</td></tr>
            //                                 <tr><td class="soap-text deep-purple-text text-center">I</td><td>${data.instruksi}</td></tr>
            //                             </tbody>
            //                         </table>
            //                     </td>
            //                     <td>
            //                         <i class="mdi mdi-content-copy blue-text pointer mdi-18px copy-soap" data-id="${data.id}" title="Copy"></i>
            //                         <i class="mdi mdi-delete-forever red-text pointer mdi-18px hapus-soap" data-id="${data.id}" title="Hapus"></i>
            //                         <i class="mdi mdi-pencil red-text pointer mdi-18px edit-soap" data-id="${data.id}" title="Edit SOAP & Resep Elektronik"></i>
            //                         <i class="mdi mdi-printer blue-text pointer mdi-18px print-antrian" data-id="${data.id}" title="Print Antrian Resep"></i>
            //                     </td>
            //                 </tr>
            //             `;
            //                 // Tambahkan ke dalam tabel
            //                 $('#list_soap').append(row);
            //             });
            //         },
            //         error: function(xhr, status, error) {
            //             console.error(xhr.responseText);
            //         }
            //     });
            // }

            function submitFormCPPT(actionType) {
                const form = $('#cppt-perawat-rajal-form');
                const registrationNumber = "{{ $registration->registration_number }}";

                const url =
                    "{{ route('cppt.dokter-rajal.store', ['type' => 'rawat-jalan', 'registration_number' => '__registration_number__']) }}"
                    .replace('__registration_number__', registrationNumber);

                // Now you can use `url` in your form submission or AJAX request

                let formData = form.serialize(); // Ambil data dari form

                // Tambahkan tipe aksi (draft atau final) ke data form
                formData += '&action_type=' + actionType;

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: formData,
                    success: function(response) {
                        if (actionType === 'draft') {
                            showSuccessAlert('Data berhasil disimpan sebagai draft!');
                        } else {
                            showSuccessAlert('Data berhasil disimpan sebagai final!');
                        }
                        setTimeout(() => {
                            console.log('Reloading the page now.');
                            window.location.reload();
                        }, 1000);
                    },
                    error: function(response) {
                        // Tangani error
                        var errors = response.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            showErrorAlert(value[0]);
                        });
                    }
                });
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            $('body').addClass('layout-composed');
            $('.select2').select2({
                placeholder: 'Pilih Item',
            });
            $('#departement_id').select2({
                placeholder: 'Pilih Klinik',
            });
            $('#doctor_id').select2({
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
@endsection
