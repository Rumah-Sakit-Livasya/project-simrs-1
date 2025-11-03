@extends('inc.layout')
@section('title', 'Dashboard HRIS')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">HRIS</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
        </ol>

        <!-- Header -->
        <div class="row">
            <div class="col-sm-12">
                <div class="subheader">
                    <h1 class="subheader-title">
                        <i class='subheader-icon fal fa-tachometer-alt-fast'></i> Ringkasan Informasi HR
                    </h1>
                </div>
            </div>
        </div>

        <!-- Filter & Statistik -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel mb-g">
                    <div class="panel-container show">
                        <div class="panel-content">
                            <div class="form-group row align-items-center">
                                <label class="col-sm-3 col-form-label text-right font-weight-bold"
                                    for="days-filter">Tampilkan Notifikasi Kadaluarsa dalam:</label>
                                <div class="col-sm-4">
                                    <select class="form-control" id="days-filter">
                                        <option value="30" selected>30 Hari ke Depan</option>
                                        <option value="60">60 Hari ke Depan</option>
                                        <option value="90">90 Hari ke Depan</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- WIDGET STATISTIK BARU -->
        <div class="row">
            <div class="col-sm-6 col-xl-3">
                <div class="p-3 bg-primary-300 rounded overflow-hidden position-relative text-white mb-g">
                    <div>
                        <h3 class="display-4 d-block l-h-n m-0 fw-500" id="new-employees-count"><i
                                class="fas fa-spinner fa-spin"></i></h3> <small>Pegawai Baru Bulan Ini</small>
                    </div>
                    <i class="fal fa-user-plus position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n1"
                        style="font-size:6rem"></i>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="p-3 bg-success-300 rounded overflow-hidden position-relative text-white mb-g">
                    <div>
                        <h3 class="display-4 d-block l-h-n m-0 fw-500" id="birthdays-today-count"><i
                                class="fas fa-spinner fa-spin"></i></h3> <small>Ulang Tahun Hari Ini</small>
                    </div>
                    <i class="fal fa-birthday-cake position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n1"
                        style="font-size:6rem"></i>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="p-3 bg-warning-400 rounded overflow-hidden position-relative text-white mb-g">
                    <div>
                        <h3 class="display-4 d-block l-h-n m-0 fw-500" id="expiring-contracts-count"><i
                                class="fas fa-spinner fa-spin"></i></h3> <small>Kontrak Akan Berakhir</small>
                    </div>
                    <i class="fal fa-file-signature position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n1"
                        style="font-size:6rem"></i>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="p-3 bg-warning-400 rounded overflow-hidden position-relative text-white mb-g">
                    <div>
                        <h3 class="display-4 d-block l-h-n m-0 fw-500" id="expiring-sip-count"><i
                                class="fas fa-spinner fa-spin"></i></h3> <small>SIP Akan Kadaluarsa</small>
                    </div>
                    <i class="fal fa-id-card-alt position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n1"
                        style="font-size:6rem"></i>
                </div>
            </div>
        </div>

        <!-- TABEL-TABEL NOTIFIKASI -->
        <div class="row">
            <!-- Notifikasi Kontrak -->
            <div class="col-xl-6">
                <div id="panel-contracts" class="panel">
                    <div class="panel-hdr">
                        <h2 class="text-warning"><i class="fal fa-file-signature mr-2"></i> Notifikasi <span
                                class="fw-300"><i>Kontrak Kerja</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table id="dt-contracts" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-warning-200">
                                    <tr>
                                        <th>Nama Pegawai</th>
                                        <th>Tgl. Berakhir</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Notifikasi Ulang Tahun -->
            <div class="col-xl-6">
                <div id="panel-birthdays" class="panel">
                    <div class="panel-hdr">
                        <h2 class="text-success"><i class="fal fa-birthday-cake mr-2"></i> Notifikasi <span
                                class="fw-300"><i>Ulang Tahun</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table id="dt-birthdays" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-success-200">
                                    <tr>
                                        <th>Nama Pegawai</th>
                                        <th>Tanggal</th>
                                        <th>Usia</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Tabel SIP -->
            <div class="col-xl-12">
                <div id="panel-sip" class="panel">
                    <div class="panel-hdr">
                        <h2><i class="fal fa-file-certificate mr-2"></i> Notifikasi <span class="fw-300"><i>Surat Izin
                                    Praktik (SIP)</i></span></h2>
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

            <!-- Tabel STR -->
            <div class="col-xl-12">
                <div id="panel-str" class="panel">
                    <div class="panel-hdr">
                        <h2><i class="fal fa-id-card-alt mr-2"></i> Notifikasi <span class="fw-300"><i>Surat Tanda
                                    Registrasi (STR)</i></span></h2>
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
        </div>
    </main>
@endsection
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script>
        $(document).ready(function() {
            // Definisikan semua elemen spinner di awal
            const statElements = {
                new_employees: $('#new-employees-count'),
                birthdays_today: $('#birthdays-today-count'),
                expiring_contracts: $('#expiring-contracts-count'),
                expiring_str: $('#expiring-str-count'),
                expiring_sip: $('#expiring-str-count'),
                expiring_sip: $('#expiring-sip-count'), // Pastikan ID ini ada di HTML jika diperlukan
                expired_sip: $('#expired-sip-count'),
                expired_str: $('#expired-str-count')
            };
            const spinner = '<i class="fas fa-spinner fa-spin"></i>';

            // Fungsi untuk memuat ulang statistik
            function loadStats(days) {
                $.each(statElements, function(key, el) {
                    el.html(spinner);
                });

                $.ajax({
                    url: "{{ route('dashboard.stats') }}",
                    type: 'GET',
                    data: {
                        days: days
                    },
                    success: function(data) {
                        statElements.new_employees.text(data.new_employees);
                        statElements.birthdays_today.text(data.birthdays_today);
                        statElements.expiring_contracts.text(data.expiring_contracts);
                        statElements.expiring_str.text(data.expiring_str);
                        statElements.expiring_sip.text(data.expiring_sip);
                        // Tambahkan statistik lain jika ada widgetnya
                    },
                    error: function() {
                        $.each(statElements, function(key, el) {
                            el.text('0');
                        });
                    }
                });
            }

            // Inisialisasi DataTable Kontrak
            var tableContracts = $('#dt-contracts').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('dashboard.contract-notifications') }}",
                    data: (d) => {
                        d.days = $('#days-filter').val();
                    }
                },
                columns: [{
                        data: 'fullname',
                        name: 'fullname'
                    },
                    {
                        data: 'contract_end_date',
                        name: 'contract_end_date'
                    },
                    {
                        data: 'status_kontrak',
                        name: 'status_kontrak',
                        orderable: false,
                        searchable: false
                    },
                ],
                responsive: true,
                searching: false,
                lengthChange: false,
                pageLength: 5 // Tampilkan 5 entri saja
            });

            // Inisialisasi DataTable Ulang Tahun
            var tableBirthdays = $('#dt-birthdays').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('dashboard.birthday-notifications') }}",
                    data: (d) => {
                        d.days = $('#days-filter').val();
                    }
                },
                columns: [{
                        data: 'fullname',
                        name: 'fullname'
                    },
                    {
                        data: 'birthday_date',
                        name: 'birthday_date'
                    },
                    {
                        data: 'age',
                        name: 'age',
                        orderable: false,
                        searchable: false
                    },
                ],
                responsive: true,
                searching: false,
                lengthChange: false,
                pageLength: 5
            });

            // Inisialisasi DataTable STR (sudah ada)
            var tableStr = $('#dt-str').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('dashboard.str-notifications') }}",
                    data: (d) => {
                        d.days = $('#days-filter').val();
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
                responsive: true
            });

            // Inisialisasi DataTable SIP (sudah ada)
            var tableSip = $('#dt-sip').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('dashboard.sip-notifications') }}",
                    data: (d) => {
                        d.days = $('#days-filter').val();
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
                responsive: true
            });

            // Event listener untuk filter
            $('#days-filter').on('change', function() {
                var selectedDays = $(this).val();
                loadStats(selectedDays);
                tableContracts.ajax.reload();
                tableBirthdays.ajax.reload();
                tableStr.ajax.reload();
                tableSip.ajax.reload();
            });

            // Muat statistik awal
            loadStats($('#days-filter').val());
        });
    </script>
@endsection
