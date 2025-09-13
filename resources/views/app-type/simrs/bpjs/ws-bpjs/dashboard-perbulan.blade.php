@extends('inc.layout')
@section('title', 'Dashboard Antrian Perbulan')
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
                                    <!-- Bulan -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label" for="bulan">Bulan</label>
                                        <select class="form-control select2" id="bulan" name="bulan">
                                            <option value="01" @if (date('m') == '01') selected @endif>Januari
                                            </option>
                                            <option value="02" @if (date('m') == '02') selected @endif>
                                                Februari</option>
                                            <option value="03" @if (date('m') == '03') selected @endif>Maret
                                            </option>
                                            <option value="04" @if (date('m') == '04') selected @endif>April
                                            </option>
                                            <option value="05" @if (date('m') == '05') selected @endif>Mei
                                            </option>
                                            <option value="06" @if (date('m') == '06') selected @endif>Juni
                                            </option>
                                            <option value="07" @if (date('m') == '07') selected @endif>Juli
                                            </option>
                                            <option value="08" @if (date('m') == '08') selected @endif>Agustus
                                            </option>
                                            <option value="09" @if (date('m') == '09') selected @endif>
                                                September</option>
                                            <option value="10" @if (date('m') == '10') selected @endif>Oktober
                                            </option>
                                            <option value="11" @if (date('m') == '11') selected @endif>
                                                November</option>
                                            <option value="12" @if (date('m') == '12') selected @endif>
                                                Desember</option>
                                        </select>
                                    </div>

                                    <!-- Tahun -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label" for="tahun">Tahun</label>
                                        <input type="number" class="form-control" id="tahun" name="tahun"
                                            value="{{ date('Y') }}">
                                    </div>

                                    <!-- Waktu -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label" for="waktu">Waktu</label>
                                        <select class="form-control select2" id="waktu" name="waktu">
                                            <option value="SERVER" selected>SERVER</option>
                                            <option value="LOCAL">LOCAL</option>
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
                            Dashboard Antrian <span class="fw-300"><i>Perbulan</i></span>
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table id="dt-dashboard-bulanan" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th>#</th>
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
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/notifications/toastr/toastr.js"></script>
    <link rel="stylesheet" href="/css/notifications/toastr/toastr.css">

    <script>
        $(document).ready(function() {
            // 1. Initialize Plugins
            $('.select2').select2({
                width: '100%'
            });

            // 2. Initialize DataTable
            var table = $('#dt-dashboard-bulanan').DataTable({
                responsive: true,
                scrollX: true, // Enable horizontal scrolling for the wide table
                processing: true,
                pageLength: 50, // Set default length to 50 as in the image
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [], // Hide export buttons if not needed
                columns: [{
                        data: null,
                        searchable: false,
                        orderable: false,
                        className: 'text-center'
                    },
                    {
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
                    }
                ],
                // Add row counter
                "fnDrawCallback": function(oSettings) {
                    var api = this.api();
                    api.column(0, {
                        search: 'applied',
                        order: 'applied'
                    }).nodes().each(function(cell, i) {
                        cell.innerHTML = i + 1;
                    });
                },
                // Example of how to highlight a row (like the red one in the image)
                "createdRow": function(row, data, dataIndex) {
                    if (data.jml_antrian == 6) { // Example condition
                        $(row).find('td').css('color', 'red');
                    }
                }
            });

            // 3. Handle Form Submission
            $('#form-pencarian').on('submit', function(e) {
                e.preventDefault();
                // In a real application, you would trigger `table.ajax.reload();` here

                toastr.info('Memuat data...', 'Info');
                table.clear().draw();

                // DEMO: Populate with mock data similar to the screenshot

            });

            // Trigger search on page load
            $('#form-pencarian').submit();
        });
    </script>
@endsection
