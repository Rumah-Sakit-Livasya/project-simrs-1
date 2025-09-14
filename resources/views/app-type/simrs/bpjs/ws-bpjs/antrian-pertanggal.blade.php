@extends('inc.layout')
@section('title', 'Antrean Per Tanggal')
@section('content')

    <style>
        table {
            font-size: 6pt !important;
        }
    </style>
    <main id="js-page-content" role="main" class="page-content">
        <!-- Search Panel -->
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Form <span class="fw-300"><i>Pencarian</i></span>
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form action="" method="get" id="form-pencarian">
                                @csrf
                                <div class="row align-items-end">
                                    <!-- Tanggal -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label" for="tanggal">Tanggal</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" name="tanggal"
                                                id="tanggal" value="{{ date('Y-m-d') }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text fs-sm"><i
                                                        class="fal fa-calendar-alt"></i></span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tombol Cari -->

                                </div>
                                <div class="row">
                                    <div class="col-12 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fal fa-search mr-1"></i> Cari
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table Panel -->
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-2" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Antrean Per Tanggal <span class="fw-300 fs-sm"><i>(DATA DARI SERVER BPJS KESEHATAN)</i></span>
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table id="dt-antrian-pertanggal" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>No. RM</th>
                                        <th>Peserta NIK</th>
                                        <th>No. Kartu</th>
                                        <th>No. HP</th>
                                        <th>Jenis Kunjungan</th>
                                        <th>No. Reff</th>
                                        <th>Kode Booking</th>
                                        <th>No Urut</th>
                                        <th>Poli</th>
                                        <th>Kode Dokter</th>
                                        <th>Jam Praktek</th>
                                        <th>Sumber Data</th>
                                        <th>Status</th>
                                        <th>Update All</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Data will be populated by DataTables via AJAX --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('plugin')
    {{-- Include necessary plugins from your template --}}
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/notifications/toastr/toastr.js"></script>
    <link rel="stylesheet" href="/css/notifications/toastr/toastr.css">

    <script>
        $(document).ready(function() {
            // 1. Initialize Plugins
            $('.datepicker').datepicker({
                todayHighlight: true,
                orientation: "bottom left",
                format: 'yyyy-mm-dd', // Set format as shown in image
                autoclose: true
            });

            // 2. Initialize DataTable
            var table = $('#dt-antrian-pertanggal').DataTable({
                responsive: true,
                scrollX: true, // IMPORTANT: This enables horizontal scrolling
                processing: true,
                pageLength: 25,
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [ // Optional buttons
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fal fa-file-excel mr-1"></i> Excel',
                        className: 'btn-outline-success btn-sm mr-1'
                    }
                ],
                // AJAX placeholder for loading data
                // ajax: { ... },
                columns: [{
                        data: 'tanggal'
                    },
                    {
                        data: 'norm'
                    },
                    {
                        data: 'nik'
                    },
                    {
                        data: 'nokartu'
                    },
                    {
                        data: 'nohp'
                    },
                    {
                        data: 'jeniskunjungan'
                    },
                    {
                        data: 'nomorreferensi'
                    },
                    {
                        data: 'kodebooking'
                    },
                    {
                        data: 'angkaantrean'
                    },
                    {
                        data: 'namapoli'
                    },
                    {
                        data: 'kodedokter'
                    },
                    {
                        data: 'jampraktek'
                    },
                    {
                        data: 'sumberdata'
                    },
                    {
                        data: 'status'
                    },
                    {
                        data: 'kodebooking', // Use a unique ID for the button
                        searchable: false,
                        orderable: false,
                        className: 'text-center',
                        render: function(data, type, row) {
                            return `<button class="btn btn-xs btn-warning">Update</button>`;
                        }
                    }
                ]
            });

            // 3. Handle Form Submission
            $('#form-pencarian').on('submit', function(e) {
                e.preventDefault();
                // For a real app, use: table.ajax.reload();

                // DEMO: For demonstration purposes, we will load mock data.
                toastr.info('Mencari data antrean...', 'Info');
                table.clear().draw();

                setTimeout(function() {

                    table.rows.add(mockData).draw();
                    toastr.success('Data berhasil dimuat.', 'Sukses');
                }, 1000); // Simulate network delay
            });

            // Trigger search on page load to show initial data
            $('#form-pencarian').submit();
        });
    </script>
@endsection
