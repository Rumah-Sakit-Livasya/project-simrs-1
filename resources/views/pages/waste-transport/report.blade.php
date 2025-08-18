@extends('inc.layout')
@section('title', 'Laporan Pengelolaan Limbah')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="container-fluid py-4">
            <!-- Panel Filter -->
            <div class="panel">
                <div class="panel-hdr">
                    <h2>Filter Laporan</h2>
                </div>
                <div class="panel-container show">
                    <div class="panel-content">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="start_date">Tanggal Mulai</label>
                                <input type="text" id="start_date" class="form-control datepicker">
                            </div>
                            <div class="col-md-4">
                                <label for="end_date">Tanggal Selesai</label>
                                <input type="text" id="end_date" class="form-control datepicker">
                            </div>
                            <div class="col-md-4">
                                <label for="category_id">Kategori Limbah</label>
                                <select id="category_id" class="form-control select2">
                                    <option value="">Semua Kategori</option>
                                    @foreach ($wasteCategories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <button class="btn btn-primary" id="filterBtn">
                                    <i class="fal fa-filter mr-1"></i> Terapkan Filter
                                </button>
                                <button class="btn btn-secondary" id="resetBtn">
                                    <i class="fal fa-sync mr-1"></i> Reset
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel Visualisasi Chart -->
            <div class="panel mt-4">
                <div class="panel-hdr">
                    <h2>Visualisasi Volume per Kategori</h2>
                </div>
                <div class="panel-container show">
                    <div class="panel-content" style="min-height: 400px;">
                        <canvas id="wasteChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Panel Tabel Laporan -->
            <div class="panel mt-4">
                <div class="panel-hdr">
                    <h2>Data Laporan Agregat</h2>
                </div>
                <div class="panel-container show">
                    <div class="panel-content">
                        <table id="reportTable" class="table table-bordered table-hover w-100">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kategori Limbah</th>
                                    <th class="text-right">Total Volume (Kg)</th>
                                    <th class="text-center">Jumlah Pengangkutan</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2" class="text-right font-weight-bold">GRAND TOTAL:</th>
                                    <th class="text-right font-weight-bold"></th>
                                    <th class="text-center font-weight-bold"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('plugin')
    <!-- Plugin Dependencies -->
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    <script src="/js/statistics/chartjs/chartjs.bundle.js"></script>

    <script>
        $(document).ready(function() {
            // Inisialisasi plugin filter
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true
            });
            $('.select2').select2();

            let wasteChart = null; // Variabel global untuk instance chart

            // Inisialisasi DataTable dengan tombol ekspor
            const table = $('#reportTable').DataTable({
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'category_name',
                        name: 'category_name'
                    },
                    {
                        data: 'total_volume',
                        name: 'total_volume',
                        className: 'text-right',
                        render: $.fn.dataTable.render.number('.', ',', 2)
                    },
                    {
                        data: 'transport_count',
                        name: 'transport_count',
                        className: 'text-center'
                    }
                ],
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                        extend: 'print',
                        className: 'btn-outline-primary'
                    },
                    {
                        extend: 'excelHtml5',
                        className: 'btn-outline-success'
                    },
                    {
                        extend: 'pdfHtml5',
                        className: 'btn-outline-danger'
                    }
                ],
                footerCallback: function(row, data, start, end, display) {
                    var api = this.api();

                    // Hitung Grand Total Volume
                    var totalVolume = api.column(2, {
                        page: 'current'
                    }).data().reduce((a, b) => parseFloat(a) + parseFloat(b), 0);

                    // Hitung Grand Total Pengangkutan
                    var totalCount = api.column(3, {
                        page: 'current'
                    }).data().reduce((a, b) => parseInt(a) + parseInt(b), 0);

                    // Update footer
                    $(api.column(2).footer()).html(totalVolume.toFixed(2) + ' Kg');
                    $(api.column(3).footer()).html(totalCount);
                }
            });

            // Fungsi untuk memuat data laporan via AJAX
            function loadReportData() {
                const startDate = $('#start_date').val();
                const endDate = $('#end_date').val();
                const categoryId = $('#category_id').val();

                $.ajax({
                    url: "{{ route('reports.waste.data') }}",
                    type: "GET",
                    data: {
                        start_date: startDate,
                        end_date: endDate,
                        category_id: categoryId
                    },
                    beforeSend: function() {
                        // Tampilkan loading spinner jika perlu
                        $('#filterBtn').prop('disabled', true).html('Memuat...');
                    },
                    success: function(response) {
                        updateReport(response.data);
                    },
                    error: function(xhr) {
                        console.error("Gagal memuat data laporan:", xhr);
                        alert("Gagal memuat data laporan.");
                    },
                    complete: function() {
                        $('#filterBtn').prop('disabled', false).html(
                            '<i class="fal fa-filter mr-1"></i> Terapkan Filter');
                    }
                });
            }

            // Fungsi untuk memperbarui tabel dan chart
            function updateReport(data) {
                // Update DataTable
                table.clear().rows.add(data).draw();

                // Siapkan data untuk Chart.js
                const labels = data.map(item => item.category_name);
                const volumes = data.map(item => item.total_volume);

                // Hancurkan chart lama jika ada
                if (wasteChart) {
                    wasteChart.destroy();
                }

                // Buat chart baru
                const ctx = document.getElementById('wasteChart').getContext('2d');
                wasteChart = new Chart(ctx, {
                    type: 'pie', // Tipe chart: pie, bar, line, etc.
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Volume Limbah (Kg)',
                            data: volumes,
                            backgroundColor: [ // Sediakan warna yang cukup
                                'rgba(255, 99, 132, 0.7)', 'rgba(54, 162, 235, 0.7)',
                                'rgba(255, 206, 86, 0.7)', 'rgba(75, 192, 192, 0.7)',
                                'rgba(153, 102, 255, 0.7)', 'rgba(255, 159, 64, 0.7)',
                                'rgba(199, 199, 199, 0.7)', 'rgba(83, 102, 255, 0.7)'
                            ],
                            borderColor: '#fff',
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top'
                            },
                            title: {
                                display: true,
                                text: 'Distribusi Volume Limbah Berdasarkan Kategori'
                            }
                        }
                    }
                });
            }

            // Event listener untuk tombol filter
            $('#filterBtn').on('click', loadReportData);

            $('#resetBtn').on('click', function() {
                $('#start_date').val('').trigger('change');
                $('#end_date').val('').trigger('change');
                $('#category_id').val('').trigger('change');
                loadReportData();
            });

            // Muat data pertama kali saat halaman dibuka
            loadReportData();
        });
    </script>
@endsection
