@extends('inc.layout')
@section('title', 'Dashboard Antrian Pertanggal')
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

                                    <!-- Waktu -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label" for="waktu">Waktu</label>
                                        <select class="form-control select2" id="waktu" name="waktu">
                                            <option value="SERVER" selected>SERVER</option>
                                            <option value="LOCAL">RS</option>
                                        </select>
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
                            Dashboard Antrian <span class="fw-300"><i>Pertanggal</i></span>
                        </h2>
                        <div class="panel-toolbar">
                            {{-- Optional: Toolbar buttons can go here --}}
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table id="dt-dashboard-antrian" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th>Kode PPK</th>
                                        <th>Poli</th>
                                        <th>Jml Antrian</th>
                                        <th>Tanggal</th>
                                        <th>Tanggal Insert</th>
                                        <th>Task 1</th>
                                        <th>AVG Task 1</th>
                                        <th>Task 2</th>
                                        <th>AVG Task 2</th>
                                        <th>Task 3</th>
                                        <th>AVG Task 3</th>
                                        <th>Task 4</th>
                                        <th>AVG Task 4</th>
                                        <th>Task 5</th>
                                        <th>AVG Task 5</th>
                                        <th>Task 6</th>
                                        <th>AVG Task 6</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Data will be populated by DataTables --}}
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
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/notifications/toastr/toastr.js"></script>
    <link rel="stylesheet" href="/css/notifications/toastr/toastr.css">

    <script>
        $(document).ready(function() {
            // 1. Initialize Plugins
            $('.datepicker').datepicker({
                todayHighlight: true,
                orientation: "bottom left",
                format: 'yyyy-mm-dd', // Format YYYY-MM-DD as shown in image
                autoclose: true
            });

            $('.select2').select2({
                width: '100%'
            });

            // 2. Initialize DataTable
            var table = $('#dt-dashboard-antrian').DataTable({
                responsive: true,
                scrollX: true, // IMPORTANT: Enable horizontal scrolling for wide tables
                lengthChange: true, // Show the 'Show X entries' dropdown
                pageLength: 50, // Set default page length to 50
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [ // Optional: Add export buttons
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fal fa-file-excel mr-1"></i> Excel',
                        className: 'btn-outline-success btn-sm mr-1'
                    }
                ],
                // Set custom text for empty table to match screenshot
                language: {
                    emptyTable: "Tidak ada data"
                },
                // AJAX placeholder for loading data
                // ajax: { ... },
                columns: [{
                        data: 'kode_ppk'
                    },
                    {
                        data: 'poli'
                    },
                    {
                        data: 'jml_antrian'
                    },
                    {
                        data: 'tanggal'
                    },
                    {
                        data: 'tanggal_insert'
                    },
                    {
                        data: 'task1'
                    },
                    {
                        data: 'avg_task1'
                    },
                    {
                        data: 'task2'
                    },
                    {
                        data: 'avg_task2'
                    },
                    {
                        data: 'task3'
                    },
                    {
                        data: 'avg_task3'
                    },
                    {
                        data: 'task4'
                    },
                    {
                        data: 'avg_task4'
                    },
                    {
                        data: 'task5'
                    },
                    {
                        data: 'avg_task5'
                    },
                    {
                        data: 'task6'
                    },
                    {
                        data: 'avg_task6'
                    },
                ]
            });

            // 3. Handle Form Submission
            $('#form-pencarian').on('submit', function(e) {
                e.preventDefault();
                // For a real app, you would use: table.ajax.reload();

                // DEMO: For demonstration, we'll load mock data
                toastr.info('Memuat data dashboard...', 'Info');
                table.clear().draw(); // Clear existing data

                setTimeout(function() {
                    var mockData = [{
                            kode_ppk: '1234F001',
                            poli: 'PENYAKIT DALAM',
                            jml_antrian: 15,
                            tanggal: '2025-09-13',
                            tanggal_insert: '2025-09-13 08:00:00',
                            task1: 15,
                            avg_task1: 60,
                            task2: 15,
                            avg_task2: 120,
                            task3: 14,
                            avg_task3: 300,
                            task4: 10,
                            avg_task4: 180,
                            task5: 10,
                            avg_task5: 240,
                            task6: 10,
                            avg_task6: 120
                        },
                        {
                            kode_ppk: '1234F001',
                            poli: 'JANTUNG',
                            jml_antrian: 10,
                            tanggal: '2025-09-13',
                            tanggal_insert: '2025-09-13 08:05:00',
                            task1: 10,
                            avg_task1: 75,
                            task2: 10,
                            avg_task2: 150,
                            task3: 9,
                            avg_task3: 320,
                            task4: 8,
                            avg_task4: 200,
                            task5: 8,
                            avg_task5: 260,
                            task6: 8,
                            avg_task6: 130
                        },
                        {
                            kode_ppk: '1234F001',
                            poli: 'ANAK',
                            jml_antrian: 22,
                            tanggal: '2025-09-13',
                            tanggal_insert: '2025-09-13 08:10:00',
                            task1: 22,
                            avg_task1: 50,
                            task2: 21,
                            avg_task2: 110,
                            task3: 20,
                            avg_task3: 280,
                            task4: 15,
                            avg_task4: 160,
                            task5: 15,
                            avg_task5: 220,
                            task6: 15,
                            avg_task6: 110
                        },
                    ];
                    table.rows.add(mockData).draw();
                    toastr.success('Data berhasil dimuat.', 'Sukses');
                }, 1500); // Simulate network delay
            });

        });
    </script>
@endsection
