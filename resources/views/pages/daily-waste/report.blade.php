@extends('inc.layout')
@section('title', 'Laporan Limbah Harian')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="container-fluid py-4">
            <!-- Panel Filter -->
            <div class="panel">
                <div class="panel-hdr">
                    <h2>Filter Laporan Limbah Harian</h2>
                </div>
                <div class="panel-container show">
                    <div class="panel-content">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="start_date">Tanggal Mulai</label>
                                <input type="text" id="start_date" class="form-control datepicker">
                            </div>
                            <div class="col-md-3">
                                <label for="end_date">Tanggal Selesai</label>
                                <input type="text" id="end_date" class="form-control datepicker">
                            </div>
                            <div class="col-md-3">
                                <label for="category_id">Kategori Limbah</label>
                                <select id="category_id" class="form-control select2">
                                    <option value="">Semua Kategori</option>
                                    @foreach ($wasteCategories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="pic_id">PIC (CS)</label>
                                <select id="pic_id" class="form-control select2">
                                    <option value="">Semua PIC</option>
                                    @foreach ($pics as $pic)
                                        <option value="{{ $pic->id }}">{{ $pic->fullname }}</option>
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
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="panel">
                        <div class="panel-hdr">
                            <h2>Volume per Kategori</h2>
                        </div>
                        <div class="panel-container show">
                            <div class="panel-content" style="min-height: 400px;">
                                <canvas id="categoryChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel">
                        <div class="panel-hdr">
                            <h2>Volume per PIC</h2>
                        </div>
                        <div class="panel-container show">
                            <div class="panel-content" style="min-height: 400px;">
                                <canvas id="picChart"></canvas>
                            </div>
                        </div>
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
                                    <th>PIC (CS)</th>
                                    <th class="text-right">Total Volume (Kg)</th>
                                    <th class="text-center">Jumlah Input</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-right font-weight-bold">GRAND TOTAL:</th>
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
            // Inisialisasi plugin
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true
            });
            $('.select2').select2();

            let categoryChart = null;
            let picChart = null;

            const table = $('#reportTable').DataTable({
                columns: [{
                        data: null,
                        searchable: false,
                        orderable: false,
                        defaultContent: ''
                    },
                    {
                        data: 'category_name',
                        name: 'category_name'
                    },
                    {
                        data: 'pic_name',
                        name: 'pic_name'
                    },
                    {
                        data: 'total_volume',
                        name: 'total_volume',
                        className: 'text-right',
                        render: $.fn.dataTable.render.number('.', ',', 2)
                    },
                    {
                        data: 'input_count',
                        name: 'input_count',
                        className: 'text-center'
                    }
                ],
                // Tambahkan penomoran di sisi client
                "fnCreatedRow": function(nRow, aData, iDataIndex) {
                    $('td:eq(0)', nRow).html(iDataIndex + 1);
                },
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: ['print', 'excelHtml5', 'pdfHtml5'],
                footerCallback: function(row, data, start, end, display) {
                    var api = this.api();
                    var totalVolume = api.column(3, {
                        page: 'current'
                    }).data().reduce((a, b) => parseFloat(a) + parseFloat(b), 0);
                    var totalCount = api.column(4, {
                        page: 'current'
                    }).data().reduce((a, b) => parseInt(a) + parseInt(b), 0);

                    $(api.column(3).footer()).html(totalVolume.toFixed(2) + ' Kg');
                    $(api.column(4).footer()).html(totalCount);
                }
            });

            // Fungsi untuk memuat data via AJAX
            function loadReportData() {
                $.ajax({
                    url: "{{ route('reports.daily.data') }}",
                    type: "GET",
                    data: {
                        start_date: $('#start_date').val(),
                        end_date: $('#end_date').val(),
                        category_id: $('#category_id').val(),
                        pic_id: $('#pic_id').val()
                    },
                    beforeSend: () => $('#filterBtn').prop('disabled', true).html('Memuat...'),
                    success: (response) => updateUI(response.data),
                    error: (xhr) => {
                        console.error("Gagal memuat data:", xhr);
                        alert("Gagal memuat data laporan.");
                    },
                    complete: () => $('#filterBtn').prop('disabled', false).html(
                        '<i class="fal fa-filter mr-1"></i> Terapkan Filter')
                });
            }

            // Fungsi untuk memperbarui UI (Tabel & Chart)
            function updateUI(data) {
                table.clear().rows.add(data).draw();

                // --- Update Chart Kategori ---
                const categoryData = aggregateData(data, 'category_name', 'total_volume');
                updateChart(categoryChart, 'categoryChart', 'pie', Object.keys(categoryData), Object.values(
                    categoryData), 'Volume per Kategori (Kg)');

                // --- Update Chart PIC ---
                const picData = aggregateData(data, 'pic_name', 'total_volume');
                updateChart(picChart, 'picChart', 'bar', Object.keys(picData), Object.values(picData),
                    'Volume per PIC (Kg)');
            }

            // Helper function untuk agregasi data untuk chart
            function aggregateData(data, keyField, valueField) {
                return data.reduce((acc, item) => {
                    acc[item[keyField]] = (acc[item[keyField]] || 0) + parseFloat(item[valueField]);
                    return acc;
                }, {});
            }

            // Helper function untuk membuat/memperbarui chart
            function updateChart(chartInstance, canvasId, type, labels, data, title) {
                if (chartInstance) chartInstance.destroy();

                const backgroundColors = [
                    'rgba(255, 99, 132, 0.7)', 'rgba(54, 162, 235, 0.7)', 'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)', 'rgba(153, 102, 255, 0.7)', 'rgba(255, 159, 64, 0.7)'
                ];

                const ctx = document.getElementById(canvasId).getContext('2d');
                const newChart = new Chart(ctx, {
                    type: type,
                    data: {
                        labels: labels,
                        datasets: [{
                            label: title,
                            data: data,
                            backgroundColor: backgroundColors,
                            borderColor: '#fff',
                            borderWidth: (type === 'pie' ? 2 : 1)
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: type === 'pie'
                            }
                        }
                    }
                });

                if (canvasId === 'categoryChart') categoryChart = newChart;
                if (canvasId === 'picChart') picChart = newChart;
            }

            // Event listeners
            $('#filterBtn').on('click', loadReportData);
            $('#resetBtn').on('click', function() {
                $('#start_date, #end_date').val('');
                $('#category_id, #pic_id').val('').trigger('change');
                loadReportData();
            });

            // Muat data saat halaman pertama kali dibuka
            loadReportData();
        });
    </script>
@endsection
