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

        .hidden {
            display: none !important;
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
                                                        {{-- <div class="col-md"> --}}
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
                                                                        <p class="text-muted mt-1" style="font-size: 9pt">
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
                                <div class="card-actionbar">
                                    {{-- Tombol Tombol Aksi --}}
                                    @include('pages.simrs.pendaftaran.partials.button-registrasi')
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="menu-layanan">
                        {{-- Menu Daftar Layanan1 --}}
                        @include('pages.simrs.pendaftaran.partials.menu-daftar-layanan')
                    </div>
                    <div id="pengkajian-nurse-rajal" style="display: none;">
                        {{-- Header Pasien --}}
                        @include('pages.simrs.pendaftaran.partials.menu')
                        @include('pages.simrs.pendaftaran.partials.header-pasien')
                        {{-- Perawat --}}
                        @include('pages.simrs.pendaftaran.partials.perawat.pengkajian-nurse-rajal')
                        @include('pages.simrs.pendaftaran.partials.perawat.transfer-pasien-antar-ruangan')
                        {{-- Dokter --}}
                        @include('pages.simrs.pendaftaran.partials.dokter.pengkajian-dokter-rajal')
                        @include('pages.simrs.pendaftaran.partials.dokter.cppt')
                        @include('pages.simrs.pendaftaran.partials.dokter.resume-medis-rajal')
                    </div>
                    <div id="tindakan-medis" style="display: none;">
                        {{-- Tindakan Medis --}}
                        @include('pages.simrs.pendaftaran.partials.tindakan-medis')
                    </div>
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
            // Set CSRF token untuk semua permintaan AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Hide and show services menu with fade effect
            $('#pengkajian-nurse-rajal').hide();
            $('.menu-layanan').on('click', function() {
                $('#menu-layanan').fadeOut(500); // 500ms for transition

                // Get data-layanan to determine which element to show
                var namaLayanan = $(this).data('layanan');
                if (namaLayanan == 'pengkajian-nurse-rajal') {
                    $('#pengkajian-nurse-rajal').show();
                }
                var pengkajianId = $('#pengkajian-rajal-id').val();

                // Show the selected service element with fade in effect
                $('#' + namaLayanan).delay(500).fadeIn(500); // 500ms for transition
            });

            // Select2 initialization for various dropdowns
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

            // DataTable initialization
            $('#cppt-table').DataTable({
                responsive: true,
                lengthChange: false,
                pageLength: 4,
                language: {
                    search: "", // Empty to not display "Search:" label
                    searchPlaceholder: "Cari...", // Placeholder for search input
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

            // Event listener for history of assessments
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

            // Event listener for signature button
            $('#btn-ttd').on('click', function() {
                popupwindow(base_url() + 'pengkajian/signature/ttd', 'popup_ttd', 730, 420, 'no');
            });

            // Event listener for pain score selection
            $('.img-baker .pointer').on('click', function() {
                $('#skor_nyeri').val($(this).data('skor'));
            });

            // Bartel index calculation
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

            // BMI calculation
            function get_bmi() {
                var A = $('#body_height').val();
                var B = $('#body_weight').val();
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

            // Fall risk assessment
            function resiko_jatuh() {
                var resiko_jatuh1 = document.getElementById('resiko_jatuh1').checked;
                var resiko_jatuh2 = document.getElementById('resiko_jatuh2').checked;
                var resiko_jatuh3 = document.getElementById('resiko_jatuh3').checked;

                if (!resiko_jatuh1 && !resiko_jatuh2 && !resiko_jatuh3) {
                    $('#resiko_jatuh_hasil').val("Tidak Beresiko");
                } else if (resiko_jatuh1 || resiko_jatuh2) {
                    if (resiko_jatuh3) {
                        $('#resiko_jatuh_hasil').val("Resiko Tinggi");
                    } else {
                        $('#resiko_jatuh_hasil').val("Resiko Sedang");
                    }
                } else if (!resiko_jatuh1 || !resiko_jatuh2) {
                    if (resiko_jatuh3) {
                        $('#resiko_jatuh_hasil').val("Resiko Sedang");
                    } else {
                        $('#resiko_jatuh_hasil').val("Resiko Tinggi");
                    }
                }
            }
            resiko_jatuh();

            // Function to open signature pad
            function openSignaturePad() {
                idSignature = $(this).attr('data-id');
                $('#signatureModal').modal('show'); // Example using Bootstrap modal
            }
        });
    </script>

    @yield('script-tindakan-medis')

    <script>
        let idSignature = null;
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');
        let painting = false;
        let history = [];
        const offsetX = 0;
        const offsetY = 5;

        function startPosition(e) {
            painting = true;
            draw(e);
        }

        function endPosition() {
            painting = false;
            ctx.beginPath();
            history.push(ctx.getImageData(0, 0, canvas.width, canvas.height));
        }

        function draw(e) {
            if (!painting) return;

            const rect = canvas.getBoundingClientRect();
            const x = e.clientX - rect.left - offsetX;
            const y = e.clientY - rect.top - offsetY;

            ctx.lineWidth = 5;
            ctx.lineCap = 'round';
            ctx.strokeStyle = 'black';

            ctx.lineTo(x, y);
            ctx.stroke();
            ctx.beginPath();
            ctx.moveTo(x, y);
        }

        function clearCanvas() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            history = [];
        }

        function undo() {
            if (history.length > 0) {
                ctx.putImageData(history.pop(), 0, 0);
            }
        }

        function saveSignature() {
            const dataURL = canvas.toDataURL('image/png');
            $.ajax({
                url: '/api/dashboard/kpi/save-signature/' + idSignature,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    signature_image: dataURL
                },
                success: function(response) {
                    // Update the signature display
                    $('#tombol-' + idSignature).hide();
                    $('#signature-display-' + idSignature).attr('src', response.path).show();
                    $('#signatureModal').modal('hide'); // Hide the modal
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }

        function openSignaturePad(id) {
            if (id == null) {
                $('#tombol-pegawai').attr('id', 'tombol-' + idSignature);
                $('#signature-display').attr('id', 'signature-display-' + idSignature);
            } else {
                idSignature = id;
            }


            $('#signatureModal').modal('show');
        }

        canvas.addEventListener('mousedown', startPosition);
        canvas.addEventListener('mouseup', endPosition);
        canvas.addEventListener('mousemove', draw);
    </script>
@endsection
