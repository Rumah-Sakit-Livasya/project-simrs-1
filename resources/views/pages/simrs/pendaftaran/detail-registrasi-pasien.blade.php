@extends('inc.layout')
@section('extended-css')
    <link rel="stylesheet" href="/css/framework_custom.min.css">
    <style>
        input[type="time"] {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }

        .wongbaker {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            justify-items: center;
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

        .menu-detail-regist .nav-item .dropdown-toggle::after {
            margin-left: 10px;
        }

        .menu-detail-regist .nav-item .dropdown-toggle {
            font-size: 0.9rem !important;
            color: #a07adb !important;
            font-family: "Poppins", sans-serif !important;
            /* Menggunakan Poppins dengan sans-serif sebagai fallback */
        }

        .detail-regist-name {
            color: #ff00ff;
            font-weight: 500;
            text-decoration: underline;
            text-transform: uppercase;
        }

        .fade:not(.show) {
            display: none;
        }

        .font-weight-bold h2 {
            font-weight: 600 !important;
        }

        #cppt-table th:nth-child(1),
        #cppt-table td:nth-child(1) {
            width: 25%;
        }

        #cppt-table th:nth-child(3),
        #cppt-table td:nth-child(3) {
            width: 5%;
        }

        #resume-medis-rajal .custom-checkbox {
            width: 20px;
            height: 20px;
            margin-right: 15px;
        }

        #resume-medis-rajal .form-check-inline {
            margin-right: 20px;
        }
    </style>
@endsection
@section('content')
    @php
        use Carbon\Carbon;
        $today = Carbon::today()->format('d-m-Y');
        if ($registration->registration_type == 'rawat-jalan') {
            $unitLayanan = $registration->poliklinik;
        } elseif ($registration->registration_type == 'igd') {
            $unitLayanan = 'UGD';
        } elseif ($registration->registration_type == 'odc') {
            $unitLayanan = 'ONE DAY CARE';
        } elseif ($registration->registration_type == 'rawat-inap') {
            $unitLayanan = 'RAWAT INAP';
        }

    @endphp
    <main id="js-page-content" role="main" class="page-content overflow-hidden">
        <div class="row">
            <div class="col-xl-3">
                <div id="panel-1" class="panel h-100">
                    <div class="panel-container show">
                        <div class="panel-content">
                            <div class="row">
                                <div class="col biodata-pasien">
                                    @if ($patient->gender == 'Laki-laki')
                                        <img src="http://103.191.196.126:8888/real/include/avatar/man-icon.png"
                                            style="width: 120px; height: 120px;">
                                    @else
                                        <img src="http://103.191.196.126:8888/real/include/avatar/woman-icon.png"
                                            style="width: 120px; height: 120px;">
                                    @endif

                                    <h3 class="text-center mt-3 text-black">
                                        {{ strtoupper($patient->name) }}
                                        <small class="text-danger text-accent-2 mt-1">
                                            No RM : {{ $patient->medical_record_number }}
                                        </small>
                                    </h3>
                                </div>

                                <div class="col-md-10 col-bg-10">

                                    <ul class="nav nav-tabs nav-tabs-clean" role="tablist">
                                        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#biodata"
                                                role="tab">Biodata</a></li>
                                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#kunjungan"
                                                role="tab">Kunjungan</a></li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane fade show active ml-5" id="biodata" role="tabpanel"
                                            aria-labelledby="biodata">
                                            <div class="mt-3 row align-items-center">
                                                <div class="col-xl-2">
                                                    <i class="fas fa-calendar text-warning mr-2"
                                                        style="transform: scale(1.4)"></i>
                                                </div>
                                                <div class="col">
                                                    <span class="d-block">{{ $patient->place }},
                                                        {{ $patient->date_of_birth }}</span>
                                                    <span class="text-primary">Tempat, Tanggal Lahir</span>
                                                </div>
                                            </div>
                                            <div class="mt-3 row align-items-center">
                                                <div class="col-xl-2">
                                                    <i class="fas fa-calendar-alt text-warning mr-2"
                                                        style="transform: scale(1.4)"></i>
                                                </div>
                                                <div class="col">
                                                    <span class="d-block">{{ $age }}</span>
                                                    <span class="text-primary">Umur</span>
                                                </div>
                                            </div>
                                            <div class="mt-3 row align-items-center">
                                                <div class="col-xl-2">
                                                    <i class="fas fa-venus-mars text-warning mr-2"
                                                        style="transform: scale(1.4)"></i>
                                                </div>
                                                <div class="col">
                                                    <span class="d-block">{{ $patient->gender }}</span>
                                                    <span class="text-primary">Jenis Kelamin</span>
                                                </div>
                                            </div>
                                            <div class="mt-3 row align-items-center">
                                                <div class="col-xl-2">
                                                    <i class="fas fa-map-marker-alt text-warning mr-2"
                                                        style="transform: scale(1.4)"></i>
                                                </div>
                                                <div class="col">
                                                    <span class="d-block">{{ $patient->address }}</span>
                                                    <span class="text-primary">Alamat</span>
                                                </div>
                                            </div>
                                            <div class="mt-3 row align-items-center">
                                                <div class="col-xl-2">
                                                    <i class="fas fa-mobile-android-alt text-warning mr-2"
                                                        style="transform: scale(1.4)"></i>
                                                </div>
                                                <div class="col">
                                                    <span class="d-block">{{ $patient->mobile_phone_number }}</span>
                                                    <span class="text-primary">Telp/HP</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="kunjungan" role="tabpanel"
                                            aria-labelledby="kunjungan">
                                            <div class="card-body tab-content">
                                                <div class="tab-pane active" id="second4" style="width: 125%;">
                                                    <div class="row">
                                                        <div class="col-md">
                                                            @foreach ($patient->registration as $item)
                                                                @if ($item->status !== 'batal')
                                                                    <div class="d-flex">
                                                                        <span
                                                                            class="d-block text-white display-4 mr-3 flex-shrink-0">
                                                                            <i class="mdi mdi-stethoscope mdi-18px bg-primary p-3 white-text"
                                                                                style="border-radius: 11px"></i>
                                                                        </span>
                                                                        <div class="d-inline-flex flex-column">
                                                                            <a href="{{ route('detail.registrasi.pasien', $item->id) }}"
                                                                                class="fs-lg fw-500 d-block">
                                                                                {{ $item->departement->name }}
                                                                            </a>
                                                                            <div class="d-block text-muted fs-md mt-1">
                                                                                {{ $item->doctor->employee->fullname }}
                                                                            </div>
                                                                            <p class="text-muted mt-1"
                                                                                style="font-size: 9pt">
                                                                                {{ tgl_waktu($item->registration_date) }}
                                                                                -
                                                                                {{ $item->registration_close_date ? tgl_waktu($item->registration_close_date) : 'sekarang' }}
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-9">
                <div id="panel-1" class="panel h-100">
                    <div class="panel-hdr">
                        <h2 class="text-light">
                            <i class="fas fa-address-card mr-3 ml-2 text-primary" style="transform: scale(2.1)"></i>
                            <span class="text-primary">Data Registrasi</span>
                        </h2>
                        <a href="#" class="btn btn-icon-toggle toolbar-menu-pasien waves-effect text-primary"
                            data-toggle="dropdown" aria-expanded="true"><i class="mdi mdi-menu mdi-24px"></i></a>

                        <ul id="menu_layanan_pasien" class="dropdown-menu pull-right menu-card-styling w-25"
                            role="menu" style="text-align: left;">
                            <li class="p-2">
                                <a href="javascript:void(0);" data-toggle="modal" data-target="#rujuk-ranap-poli"
                                    class="nextRegis">
                                    <i class="fa fa-circle-o fa-fw pink-text text-accent-2"></i>
                                    Rujuk Rawat Inap / Poli Lain
                                </a>
                            </li>
                            @if ($registration->status == 'online')
                                <li class="p-2">
                                    <a href="javascript:void(0);" data-toggle="modal" data-target="#tutup-kunjungan">
                                        <i class="fa fa-circle-o fa-fw pink-text text-accent-2"></i>
                                        Tutup Kunjungan
                                    </a>
                                </li>
                                <li class="p-2">
                                    <a href="javascript:void(0);" data-toggle="modal" data-target="#batal-registrasi">
                                        <i class="fa fa-circle-o fa-fw pink-text text-accent-2"></i>
                                        Batal Registrasi
                                    </a>
                                </li>
                            @else
                                <li class="p-2">
                                    <a href="javascript:void(0);" data-toggle="modal" data-target="#batal-keluar""
                                        data-style="style-default">
                                        <i class="fa fa-circle-o fa-fw pink-text text-accent-2"></i>
                                        Batal Keluar
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <div class="card-body style-default-bright">
                                <!-- Info registrasi -->
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="row">
                                                <label for="s_tgl_1" class="col-md-4 control-label">Tanggal
                                                    Registrasi</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" type="text"
                                                        value="{{ $registration->registration_date }}"
                                                        readonly="readonly">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="row">
                                                <label for="s_tgl_1" class="col-md-4 control-label">
                                                    <a href="javascript:void(0);" data-toggle="modal"
                                                        data-target="#ganti-dpjp">
                                                        <u>Dokter (DPJP)</u>
                                                    </a>
                                                </label>
                                                <div class="col-md-8">
                                                    <input class="form-control" type="text"
                                                        value="{{ $registration->doctor->employee->fullname }}"
                                                        readonly="readonly">
                                                    <!--					<span class="mdi mdi-account-edit mdi-24px red-text form-control-feedback pointer btnEditDokter" title="Edit Dokter"></span> -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="row">
                                                <label for="s_tgl_1" class="col-md-4 control-label">No Registrasi</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" type="text"
                                                        value="{{ $registration->registration_number }}"
                                                        readonly="readonly">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="row">
                                                <label for="s_tgl_1" class="col-md-4 control-label">Unit Layanan</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" type="text"
                                                        value="{{ $registration->registration_type === 'rawat-inap' ? 'RAWAT INAP' : $registration->departement->name }}"
                                                        readonly="readonly">
                                                </div>
                                                <div class="ml-5 mt-2">
                                                    <span style="font-size: 1.4em;color:red">No.Urut :
                                                        {{ $registration->no_urut }}</span>
                                                    <br>
                                                    <button class="btn btn-warning waves-effect mr-3 mt-2"
                                                        style="display: "
                                                        onclick="popupFull('http://192.168.1.253/real/antrol_bpjs/regenerate_antrol/180789','p_card', 900,600,'no'); return false;">
                                                        <i class="mdi mdi-update"></i> Re Generate BPJS
                                                    </button>
                                                    <button class="btn btn-success waves-effect mt-2" style="display: ;"
                                                        onclick="popupFull('http://192.168.1.253/real/antrol_bpjs/get_task_antrian_vclaim/2408046471','p_card', 900,600,'no'); return false;">
                                                        <i class="mdi mdi-update"></i> Status TASK ID
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="row">
                                                <label for="s_tgl_1" class="col-md-4 control-label">Kelas Rawat</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" type="text"
                                                        value="{{ $kelasRawat }} " readonly="readonly">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="row">
                                                <label for="s_tgl_1"
                                                    class="col-md-4 control-label editPenjamin pointer"><u>Penjamin</u></label>
                                                <div class="col-md-8">
                                                    <input class="form-control" type="text"
                                                        value="{{ $penjamin }}" readonly="readonly">
                                                </div>

                                                <div style="display:none">
                                                    <a id="sepada" class="blinkme red-text"
                                                        style="clear:both:margin-top:1em; "
                                                        onclick="popupwindow('http://192.168.1.253/real/vclaim/inject_noka_sep/180789','p_insurance','800','500','yes');return false;"
                                                        href="">
                                                        Pasien ini Belum Memiliki SEP,Klik disini Untuk Inject SEP
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="row">
                                                <label for="s_tgl_1" class="col-md-4 control-label">Ruangan - Bed</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" type="text" value="  - "
                                                        readonly="readonly">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="row">
                                                <label for="s_tgl_1" class="col-md-4 control-label">Keterangan</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" type="text" value="-"
                                                        readonly="readonly">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="row">
                                                <label for="s_tgl_1" class="col-md-4 control-label">
                                                    <a href="javascript:void(0);" data-toggle="modal"
                                                        data-target="#ganti-diagnosa">
                                                        <u>Diagnosa Awal</u>
                                                    </a>
                                                </label>
                                                <div class="col-md-8">
                                                    <input class="form-control" type="text"
                                                        value="{{ $registration->diagnosa_awal }}" readonly="readonly">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- end info registrasi -->
                                </div><!--end .card-body -->
                                <div class="row mt-3">
                                    <div class="col-md-12" style="display: none">
                                        <div class="form-group">
                                            <div class="row">
                                                <label for="s_tgl_1" class="col-md-4 control-label pointer"><u>Informasi
                                                        Billing</u></label>
                                                <div class="col-md-12">
                                                    <input class="form-control {infocolor} blinkme"
                                                        style="font-weight: bold;" type="text" value="0 - 0 -  - "
                                                        readonly="readonly">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-actionbar">
                                    <div class="card-actionbar-row " id="group-print-pasien">
                                        <button class="btn btn-primary pull-left waves-effect" style="margin: 2px"
                                            onclick="popupwindow('http://192.168.1.253/real/regprint/print_kartu_pdf/53432/180789','p_card', 400,400,'no'); return false"><i
                                                class="mdi mdi-printer"></i> Kartu pasien</button>
                                        <button class="btn btn-primary pull-left waves-effect" style="margin: 2px"
                                            onclick="popupwindow('http://192.168.1.253/real/regprint/print_identitas/53432/180789','p_card', 400,400,'yes'); return false"><i
                                                class="mdi mdi-printer"></i> Identitas Pasien</button>
                                        <button class="btn btn-primary pull-left waves-effect" style="margin: 2px"
                                            onclick="popupwindow('http://192.168.1.253/real/regprint/label_rm_pdf/53432/180789','p_card', 400,400,'no'); return false;"><i
                                                class="mdi mdi-printer"></i> Label RM (PDF)</button>
                                        <button class="btn btn-primary pull-left waves-effect" style="margin: 2px"
                                            onclick="popupwindow('http://192.168.1.253/real/regprint/label_rm_new/53432/180789','p_card', 400,400,'no'); return false;"><i
                                                class="mdi mdi-printer"></i> Label RM</button>
                                        <button class="btn btn-primary pull-left waves-effect" style="margin: 2px"
                                            onclick="popupwindow('http://192.168.1.253/real/regprint/label_gelang_anak_pdf/53432/180789','p_card', 400,400,'no'); return false;"><i
                                                class="mdi mdi-printer"></i> Label Gelang Anak</button>
                                        <button class="btn btn-primary pull-left waves-effect" style="margin: 2px"
                                            onclick="popupwindow('http://192.168.1.253/real/regprint/label_gelang_dewasa_pdf/53432/180789','p_card', 400,400,'no'); return false;"><i
                                                class="mdi mdi-printer"></i> Label Gelang Dewasa</button>
                                        <button class="btn btn-primary pull-left waves-effect" style="margin: 2px"
                                            onclick="popupwindow('http://192.168.1.253/real/regprint/tracer_new/53432/180789','p_tracer_new', 800,500,'no'); return false;"><i
                                                class="mdi mdi-printer"></i> Tracer</button>
                                        <button class="btn btn-primary pull-left waves-effect" style="margin: 2px"
                                            onclick="popupwindow('http://192.168.1.253/real/regprint/slip_dokter/53432/180789','p_card', 400,400,'no'); return false;"><i
                                                class="mdi mdi-printer"></i> Charges Slip</button>
                                        <button class="btn btn-primary pull-left waves-effect" style="margin: 2px"
                                            onclick="popupwindow('http://192.168.1.253/real/persalinan/skl/180789/modul_pasien','p_card', 400,400,'no'); return false;"><i
                                                class="mdi mdi-printer"></i> Surat Keterangan Lahir</button>
                                        <button class="btn btn-primary pull-left waves-effect" style="margin: 2px"
                                            style="display: none"
                                            onclick="popupwindow('http://192.168.1.253/real/regprint/print_surat_ket_mati/53432/180789','p_card', 2000,2000,'no'); return false;"><i
                                                class="mdi mdi-printer"></i> Surat Keterangan Kematian</button>
                                        <button class="btn btn-primary pull-left waves-effect" style="margin: 2px"
                                            onclick="popupwindow('http://192.168.1.253/real/pengkajian/general_consent?pregid=180789','p_card', 1000,900,'yes'); return false;"><i
                                                class="mdi mdi-printer"></i> General Consent</button>
                                        <div class="col-sm-12"></div>
                                        <button class="btn btn-danger pull-left waves-effect" style="margin: 2px"
                                            onclick="popupFull('http://192.168.1.253/real/vclaim/print_sep_pdf/180789'); return false;"><i
                                                class="mdi mdi-printer"></i> Print SEP</button>
                                        <button class="btn btn-danger pull-left waves-effect" style="margin: 2px"
                                            onclick="popupFull('http://192.168.1.253/real/vclaim/sep_internal/180789'); return false;"><i
                                                class="mdi mdi-printer"></i> Cek SEP Internal</button>
                                        <button class="btn btn-danger pull-left waves-effect" style="margin: 2px"
                                            onclick="popupFull('http://192.168.1.253/real/vclaim/form_rencana_kontrol/2/180789'); return false;"><i
                                                class="mdi mdi-printer"></i> Rencana Kontrol</button>
                                        <button class="btn btn-danger pull-left waves-effect" style="margin: 2px"
                                            onclick="popupFull('http://192.168.1.253/real/vclaim/form_rencana_kontrol/1/180789'); return false;"><i
                                                class="mdi mdi-printer"></i> Surat SPRI</button>
                                        <button class="btn btn-danger pull-left waves-effect" style="margin: 2px"
                                            onclick="popupFull('http://192.168.1.253/real/vclaim/rujukan/180789'); return false;"><i
                                                class="mdi mdi-printer"></i> Rujukan BPJS</button>
                                        <button class="btn btn-danger pull-left waves-effect"
                                            onclick="popupwindow('http://192.168.1.253/real/vclaim/pengajuan_add/180789','p_card', 900,600,'no'); return false;"><i
                                                class="mdi mdi-printer" style="margin: 2px"></i> Pengajuan</button>
                                        <button style="display: ;" class="btn btn-success pull-left waves-effect"
                                            onclick="popupwindow('http://192.168.1.253/real/satu_sehat/get_encounter/180789','p_card', 900,600,'no'); return false;"><i
                                                class="mdi mdi-history" style="margin: 2px"></i> Status Kunjungan
                                            (SatuSehat)</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="menu-layanan">
                        @include('pages.simrs.pendaftaran.partials.menu-daftar-layanan')
                    </div>
                    <div id="pengkajian-nurse-rajal">
                        @include('pages.simrs.pendaftaran.partials.pengkajian-nurse-rajal')
                        @include('pages.simrs.pendaftaran.partials.dokter.pengkajian-dokter-rajal')
                        @include('pages.simrs.pendaftaran.partials.dokter.cppt')
                        @include('pages.simrs.pendaftaran.partials.dokter.resume-medis-rajal')
                    </div>
                </div>
            </div>
        </div>
    </main>
    {{-- {{ $registration->pengkajian_nurse_rajal->id }} --}}
    @if ($registration->pengkajian_nurse_rajal)
        <input type="hidden" id="pengkajian-rajal-id" value="{{ $registration->pengkajian_nurse_rajal->id }}">
    @endif

    @include('pages.simrs.pendaftaran.form.batal-register-form')
    @include('pages.simrs.pendaftaran.form.batal-keluar-form')
    @include('pages.simrs.pendaftaran.form.tutup-kunjungan-form')
    @include('pages.simrs.pendaftaran.form.ganti-dpjp-form')
    @include('pages.simrs.pendaftaran.form.ganti-diagnosa-form')
@endsection
@section('plugin')
    {{-- Select 2 --}}
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script type="text/javascript" src="/js/painterro-1.2.3.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#pengkajian-nurse-rajal').hide();
            // Ketika elemen dengan kelas .menu-layanan diklik
            $('.menu-layanan').on('click', function() {
                // Sembunyikan menu layanan dengan efek fade out
                $('#menu-layanan').fadeOut(500); // 500ms untuk transisi

                // Ambil data-layanan untuk menentukan ID elemen yang akan ditampilkan
                var namaLayanan = $(this).data('layanan');
                var pengkajianId = $('#pengkajian-rajal-id').val();

                // Tampilkan elemen layanan yang dipilih dengan efek fade in
                $('#' + namaLayanan).delay(500).fadeIn(500); // 500ms untuk transisi
                if (namaLayanan == 'pengkajian-nurse-rajal') {
                    if (pengkajianId !== undefined) {
                        $('#pengkajian-nurse-rajal').show();
                        $.ajax({
                            type: "GET", // Method pengiriman data bisa dengan GET atau POST
                            url: `/api/simrs/pengkajian/nurse-rajal/${pengkajianId}/get`, // Isi dengan url/path file php yang dituju
                            dataType: "json",
                            success: function(data) {
                                $('#nurse-rajal #tgl_masuk').val(data.tgl_masuk);
                                $('#nurse-rajal #jam_masuk').val(data.jam_masuk);
                                $('#nurse-rajal #tgl_dilayani').val(data.tgl_dilayani);
                                $('#nurse-rajal #jam_dilayani').val(data.jam_dilayani);
                                $('#nurse-rajal #keluhan_utama').val(data.keluhan_utama);
                                $('#nurse-rajal #pr').val(data.pr);
                                $('#nurse-rajal #rr').val(data.rr);
                                $('#nurse-rajal #bp').val(data.bp);
                                $('#nurse-rajal #temperatur').val(data.temperatur);
                                $('#nurse-rajal #body_height').val(data.body_height);
                                $('#nurse-rajal #body_weight').val(data.body_weight);
                                $('#nurse-rajal #bmi').val(data.bmi);
                                $('#nurse-rajal #kat_bmi').val(data.kat_bmi);
                                $('#nurse-rajal #sp02').val(data.sp02);
                                $('#nurse-rajal #lingkar_kepala').val(data.lingkar_kepala);
                                $('#nurse-rajal #lingkar_kepala').val(data.lingkar_kepala);
                                // Set the value for Select2 elements
                                $('#nurse-rajal #diagnosa_keperawatan').val(data
                                    .diagnosa_keperawatan).trigger('change');
                                $('#nurse-rajal #rencana_tindak_lanjut').val(data
                                    .rencana_tindak_lanjut).trigger('change');
                                // Assuming 'data' is the object retrieved from the database
                                if (data.alergi_obat === "Ya") {
                                    $('#nurse-rajal #ket_alergi_obat').val(data
                                        .ket_alergi_obat);
                                    $('#nurse-rajal #alergi_obat1').prop('checked', true);
                                } else if (data.alergi_obat === "Tidak") {
                                    $('#nurse-rajal #alergi_obat2').prop('checked', true);
                                }
                                if (data.alergi_makanan === "Ya") {
                                    $('#nurse-rajal #ket_alergi_makanan').val(data
                                        .ket_alergi_makanan);
                                    $('#nurse-rajal #alergi_makanan1').prop('checked', true);
                                } else if (data.alergi_makanan === "Tidak") {
                                    $('#nurse-rajal #alergi_makanan2').prop('checked', true);
                                }
                                if (data.alergi_lainnya === "Ya") {
                                    $('#nurse-rajal #ket_alergi_lainnya').val(data
                                        .ket_alergi_lainnya);
                                    $('#nurse-rajal #alergi_lainnya1').prop('checked', true);
                                } else if (data.alergi_lainnya === "Tidak") {
                                    $('#nurse-rajal #alergi_lainnya2').prop('checked', true);
                                }
                            },
                            error: function(xhr) {
                                console.log(xhr.responseText);
                            }
                        });
                    }
                }
            });

            // Fungsi untuk tombol kembali
            $('.btn-kembali').on('click', function() {
                // Sembunyikan div layanan yang aktif dan tampilkan kembali menu layanan
                $('#pengkajian-nurse-rajal').fadeOut(500, function() {
                    $('#menu-layanan').fadeIn(500);
                });
            });

            // Select 2
            $(function() {
                $('.select2').select2();

                $('#tutup-kunjungan #alasan_keluar').select2({
                    dropdownCssClass: "move-up",
                    dropdownParent: $('#tutup-kunjungan'),
                    placeholder: "Pilih Alasan Keluar"
                });
                $('#tutup-kunjungan #proses_keluar').select2({
                    dropdownCssClass: "move-up",
                    dropdownParent: $('#tutup-kunjungan'),
                    placeholder: "Pilih Proses Keluar"
                });
            });

            $(function() {
                $('#ganti-dpjp #doctor_id').select2({
                    placeholder: 'Pilih Data Berikut',
                    dropdownParent: $('#ganti-dpjp')
                });
            });

            $('#nurse-rajal').on('submit', function(e) {
                e.preventDefault();
                let formData = $(this).serialize();
                const submitButton = $('#nurse-rajal').find('button[type="submit"]');
                submitButton.prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: '/api/simrs/pengkajian/nurse-rajal/store',
                    data: formData,
                    beforeSend: function() {
                        $('#nurse-rajal').find('.ikon-tambah').hide();
                        $('#nurse-rajal').find('.spinner-text').removeClass(
                            'd-none');
                    },
                    success: function(response) {
                        $('#nurse-rajal').find('.ikon-edit').show();
                        $('#nurse-rajal').find('.spinner-text').addClass('d-none');
                        $('#tambah-data').modal('hide');
                        showSuccessAlert(response.message)
                        setTimeout(function() {
                            location.reload();
                        }, 500);
                    },
                    error: function(xhr, status, error) {
                        $('#tambah-data').modal('hide');
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            var errorMessages = '';

                            $.each(errors, function(key, value) {
                                errorMessages += value +
                                    '\n';
                            });

                            // $('#modal-tambah-grup-tindakan').modal('hide');
                            showErrorAlert('Terjadi kesalahan:\n' +
                                errorMessages);
                        } else {
                            // $('#modal-tambah-grup-tindakan').modal('hide');
                            showErrorAlert('Terjadi kesalahan: ' + error);
                            console.log(error);
                        }
                    }
                });
            });

            $('#cppt-table').DataTable({
                responsive: true,
                lengthChange: false,
                pageLength: 4,
                language: {
                    search: "", // Kosongkan untuk tidak menampilkan label "Cari:"
                    searchPlaceholder: "Cari...", // Placeholder untuk input pencarian
                    zeroRecords: "Tidak ada data yang ditemukan",
                    info: "Menampilkan halaman _PAGE_ dari _PAGES_",
                    infoEmpty: "Tidak ada data yang tersedia",
                    infoFiltered: "(difilter dari _MAX_ total entri)",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    }
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {

            function get_bmi_pd() {
                var pdA = $('#pengkajian-dokter-rajal #body_height').val();
                var pdB = $('#pengkajian-dokter-rajal #body_weight').val();
                console.log(pdA);

                if (pdA !== '' && pdB !== '') {
                    pdA = pdA / 100; // Mengonversi tinggi dari cm ke m
                    var pdC = pdB / (pdA * pdA); // Menghitung BMI
                    pdC = Math.round(pdC * 10) / 10; // Membulatkan BMI

                    // Menentukan kategori BMI
                    if (pdC < 18.5) {
                        $('#pengkajian-dokter-rajal #kat_bmi').val('Kurus');
                    } else if (pdC > 24.9) {
                        $('#pengkajian-dokter-rajal #kat_bmi').val('Gemuk');
                    } else {
                        $('#pengkajian-dokter-rajal #kat_bmi').val('Normal');
                    }

                    // Mengatur nilai BMI
                    $('#pengkajian-dokter-rajal #bmi').val(pdC);

                    // Menandai input sebagai 'dirty'
                    $('#pengkajian-dokter-rajal #bmi, #pengkajian-dokter-rajal #kat_bmi').addClass('dirty');
                } else {
                    // Reset nilai jika input tidak valid
                    $('#pengkajian-dokter-rajal #bmi').val('');
                    $('#pengkajian-dokter-rajal #kat_bmi').val('');
                    $('#pengkajian-dokter-rajal #bmi, #pengkajian-dokter-rajal #kat_bmi').removeClass('dirty');
                }
            }

            // Memanggil fungsi get_bmi_pd pada saat halaman dimuat
            get_bmi_pd();

            // Mengikat fungsi get_bmi_pd ke event change pada elemen dengan kelas calc-bmi
            $('.calc-bmi').on('change', get_bmi_pd);

            $('#histori_pengkajian').on('click', function() {
                atmedic.App.popup({
                    url: base_url() + 'pengkajian/histori_pengkajian/189221',
                    mode: 'md',
                    data: {
                        pregid: '189221',
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

            /*if($('#alergi_obat1').is(':checked') || $('#ket_alergi_obat').val()!='')
              $('#ket_alergi_obat').show();
            else
              $('#ket_alergi_obat').hide();

            $('#alergi_obat1').on('click',function(){
              $('#ket_alergi_obat').show();
            });

            $('#alergi_obat2').on('click',function(){
              $('#ket_alergi_obat').hide().val('');
            });*/

            function get_bmi() {
                var A = $('#body_height').val();
                var B = $('#body_weight').val();
                console.log(A);


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
