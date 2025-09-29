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

    @stack('css-detail-regis')
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
                                        <img src="/img/patient/man-icon.png" style="width: 120px; height: 120px;">
                                    @else
                                        <img src="/img/patient/woman-icon.png" style="width: 120px; height: 120px;">
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
                                                    <span class="d-block">{{ hitungUmur($patient->date_of_birth) }}</span>
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

                                        <div class="mt-4 d-flex align-items-center">
                                            <button type="button" id="edit-pasien-btn"
                                                class="btn btn-sm btn-outline-primary d-flex align-items-center"
                                                title="Edit Data Pasien">
                                                <i class="fas fa-edit mr-2"></i>
                                                Edit Data Pasien
                                            </button>
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
                            @if ($registration->status == 'aktif')
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
                    @php
                        $isTindakanMedis = request()->is('daftar-registrasi-pasien/*/*');
                    @endphp

                    @if (!$isTindakanMedis)
                        <div id="menu-layanan">
                            {{-- Menu Daftar Layanan1 --}}
                            @include('pages.simrs.pendaftaran.partials.menu-daftar-layanan')
                        </div>
                    @endif

                    @if ($isTindakanMedis)
                        @yield('page-layanan')
                    @endif


                </div>
            </div>
        </div>

        </div>
    </main>

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
            $('#edit-pasien-btn').on('click', function() {
                // Membuka pop-up window saat tombol diklik
                var url = '{{ route('edit.pendaftaran.pasien', $patient->id) }}';
                var fullscreen = 'toolbar=yes,scrollbars=yes,resizable=yes,top=0,left=0,width=' + screen
                    .width + ',height=' + screen.height;
                window.open(url, '_blank', fullscreen);
            });

            $('.btn-back-to-layanan').on('click', function(e) {
                e.preventDefault(); // Prevent default link behavior
                var currentPanelId = $(this).data(
                    'target-menu'); // Get the ID of the current panel (e.g., 'laboratorium')

                $('#' + currentPanelId).fadeOut(500, function() {
                    $('#menu-layanan').fadeIn(500);
                });
            });

            $('#btn-back-to-menu').on('click', function() {
                // Tutup semua panel layanan yang bisa muncul
                $('#pengkajian-nurse-rajal, #tindakan-medis, #operasi, #persalinan, #radiologi, #laboratorium')
                    .fadeOut(500, function() {
                        // Setelah semua tertutup, tampilkan kembali menu layanan
                        $('#menu-layanan').fadeIn(500);
                    });
            });

            // Set CSRF token untuk semua permintaan AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Hide and show services menu with fade effect
            // $('.menu-layanan').on('click', function() {
            //     $('#menu-layanan').fadeOut(500); // 500ms for transition

            //     // Get data-layanan to determine which element to show
            //     var namaLayanan = $(this).data('layanan');
            //     switch (namaLayanan) {
            //         case 'pengkajian-nurse-rajal':
            //             $('#pengkajian-nurse-rajal').show();
            //             break;
            //         case 'radiologi':
            //             $('#radiologi').show();
            //             break;
            //         case 'laboratorium':
            //             $('#laboratorium').show();
            //             break;
            //         case 'operasi':
            //             $('#operasi').show();
            //             break;
            //     }

            //     var pengkajianId = $('#pengkajian-rajal-id').val();

            //     // Show the selected service element with fade in effect
            //     $('#' + namaLayanan).delay(500).fadeIn(500); // 500ms for transition
            // });

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
        });
    </script>

    @yield('script-radiologi')
    @yield('script-pemakaian-alat')
    @yield('script-tindakan-medis')
    @yield('script-laboratorium')
    @yield('script-operasi')
    @yield('script-persalinan')
    @yield('script-vk')
    @yield('script-visite-dokter')

    @stack('script-detail-regis')
@endsection
