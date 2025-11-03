@extends('inc.layout')
@section('title', 'Dashboard Notifikasi')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">HRIS</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
        </ol>
        <div class="row">
            <div class="col-sm-12">
                <div class="subheader">
                    <h1 class="subheader-title">
                        <i class='subheader-icon fal fa-bell'></i> Notifikasi Masa Berlaku Dokumen
                    </h1>
                </div>
            </div>
        </div>

        <!-- Filter & Statistik -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel">
                    <div class="panel-container show">
                        <div class="panel-content">
                            <div class="form-group row align-items-center">
                                <label class="col-sm-2 col-form-label text-right" for="days-filter">Tampilkan yang
                                    kadaluarsa dalam:</label>
                                <div class="col-sm-3">
                                    <select class="form-control" id="days-filter">
                                        <option value="30">30 Hari ke Depan</option>
                                        <option value="60">60 Hari ke Depan</option>
                                        <option value="90">90 Hari ke Depan</option>
                                        <option value="180">6 Bulan ke Depan</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Statistik STR -->
            <div class="col-sm-6 col-xl-3">
                <div class="p-3 bg-danger-300 rounded overflow-hidden position-relative text-white mb-g">
                    <div class="">
                        <h3 class="display-4 d-block l-h-n m-0 fw-500" id="expired-str-count">
                            <i class="fas fa-spinner fa-spin"></i>
                        </h3>
                        <small>STR Sudah Kadaluarsa</small>
                    </div>
                    <i class="fal fa-id-card-alt position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n1"
                        style="font-size:6rem"></i>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="p-3 bg-warning-400 rounded overflow-hidden position-relative text-white mb-g">
                    <div class="">
                        <h3 class="display-4 d-block l-h-n m-0 fw-500" id="expiring-str-count">
                            <i class="fas fa-spinner fa-spin"></i>
                        </h3>
                        <small>STR Akan Kadaluarsa</small>
                    </div>
                    <i class="fal fa-exclamation-triangle position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n1"
                        style="font-size:6rem"></i>
                </div>
            </div>
            <!-- Statistik SIP -->
            <div class="col-sm-6 col-xl-3">
                <div class="p-3 bg-danger-300 rounded overflow-hidden position-relative text-white mb-g">
                    <div class="">
                        <h3 class="display-4 d-block l-h-n m-0 fw-500" id="expired-sip-count">
                            <i class="fas fa-spinner fa-spin"></i>
                        </h3>
                        <small>SIP Sudah Kadaluarsa</small>
                    </div>
                    <i class="fal fa-file-certificate position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n1"
                        style="font-size:6rem"></i>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="p-3 bg-warning-400 rounded overflow-hidden position-relative text-white mb-g">
                    <div class="">
                        <h3 class="display-4 d-block l-h-n m-0 fw-500" id="expiring-sip-count">
                            <i class="fas fa-spinner fa-spin"></i>
                        </h3>
                        <small>SIP Akan Kadaluarsa</small>
                    </div>
                    <i class="fal fa-file-medical-alt position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n1"
                        style="font-size:6rem"></i>
                </div>
            </div>
        </div>

        <!-- Tabel Notifikasi -->
        <div class="row">
            <!-- Tabel STR -->
            <div class="col-xl-12">
                <div id="panel-str" class="panel">
                    <div class="panel-hdr">
                        <h2>Notifikasi <span class="fw-300"><i>Surat Tanda Registrasi (STR)</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table id="dt-str" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th>Nama Pegawai</th>
                                        <th>Nomor STR</th>
                                        <th>Masa Berlaku</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel SIP -->
            <div class="col-xl-12">
                <div id="panel-sip" class="panel">
                    <div class="panel-hdr">
                        <h2>Notifikasi <span class="fw-300"><i>Surat Izin Praktik (SIP)</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table id="dt-sip" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th>Nama Pegawai</th>
                                        <th>Nomor SIP</th>
                                        <th>Masa Berlaku</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script>
        $(document).ready(function() {

            // Fungsi untuk memuat ulang statistik
            function loadStats(days) {
                // Tampilkan spinner saat loading
                $('#expired-str-count, #expiring-str-count, #expired-sip-count, #expiring-sip-count').html(
                    '<i class="fas fa-spinner fa-spin"></i>');

                $.ajax({
                    url: "{{ route('dashboard.stats') }}",
                    type: 'GET',
                    data: {
                        days: days
                    },
                    success: function(data) {
                        $('#expired-str-count').text(data.expired_str);
                        $('#expiring-str-count').text(data.expiring_str);
                        $('#expired-sip-count').text(data.expired_sip);
                        $('#expiring-sip-count').text(data.expiring_sip);
                    },
                    error: function() {
                        // Tampilkan 0 jika error
                        $('#expired-str-count, #expiring-str-count, #expired-sip-count, #expiring-sip-count')
                            .text('0');
                    }
                });
            }

            // Inisialisasi DataTable STR
            var tableStr = $('#dt-str').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('dashboard.str-notifications') }}",
                    data: function(d) {
                        d.days = $('#days-filter').val(); // Kirim nilai filter hari
                    }
                },
                columns: [{
                        data: 'employee.fullname',
                        name: 'employee.fullname'
                    },
                    {
                        data: 'str_number',
                        name: 'str_number'
                    },
                    {
                        data: 'str_expiry_date',
                        name: 'str_expiry_date'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                ],
                responsive: true,
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                        extend: 'excelHtml5',
                        text: 'Excel',
                        titleAttr: 'Generate Excel',
                        className: 'btn-outline-success btn-sm mr-1'
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        titleAttr: 'Print Table',
                        className: 'btn-outline-primary btn-sm'
                    }
                ]
            });

            // Inisialisasi DataTable SIP
            var tableSip = $('#dt-sip').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('dashboard.sip-notifications') }}",
                    data: function(d) {
                        d.days = $('#days-filter').val(); // Kirim nilai filter hari
                    }
                },
                columns: [{
                        data: 'employee.fullname',
                        name: 'employee.fullname'
                    },
                    {
                        data: 'sip_number',
                        name: 'sip_number'
                    },
                    {
                        data: 'sip_expiry_date',
                        name: 'sip_expiry_date'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                ],
                responsive: true,
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                        extend: 'excelHtml5',
                        text: 'Excel',
                        titleAttr: 'Generate Excel',
                        className: 'btn-outline-success btn-sm mr-1'
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        titleAttr: 'Print Table',
                        className: 'btn-outline-primary btn-sm'
                    }
                ]
            });

            // Event listener untuk filter
            $('#days-filter').on('change', function() {
                var selectedDays = $(this).val();
                // Muat ulang statistik
                loadStats(selectedDays);
                // Muat ulang data tabel
                tableStr.ajax.reload();
                tableSip.ajax.reload();
            });

            // Muat statistik saat halaman pertama kali dibuka
            loadStats($('#days-filter').val());
        });
    </script>
@endsection
