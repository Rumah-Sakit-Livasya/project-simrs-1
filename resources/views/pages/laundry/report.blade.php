@extends('inc.layout')
@section('title', 'Laporan Laundry')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="container-fluid py-4">
            <!-- Panel Filter -->
            <div class="panel">
                <div class="panel-hdr">
                    <h2>Filter Laporan Laundry</h2>
                </div>
                <div class="panel-container show">
                    <div class="panel-content">
                        <div class="row">
                            <div class="col-md-3"><label>Tanggal Mulai</label><input type="text" id="start_date"
                                    class="form-control datepicker"></div>
                            <div class="col-md-3"><label>Tanggal Selesai</label><input type="text" id="end_date"
                                    class="form-control datepicker"></div>
                            <div class="col-md-2"><label>Jenis Linen</label><select id="linen_type_id"
                                    class="form-control select2">
                                    <option value="">Semua</option>
                                    @foreach ($linenTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2"><label>Kategori</label><select id="linen_category_id"
                                    class="form-control select2">
                                    <option value="">Semua</option>
                                    @foreach ($linenCategories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2"><label>PIC</label><select id="pic_id" class="form-control select2">
                                    <option value="">Semua</option>
                                    @foreach ($pics as $pic)
                                        <option value="{{ $pic->id }}">{{ $pic->fullname }}</option>
                                    @endforeach
                                </select></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12"><button class="btn btn-primary" id="filterBtn">Terapkan</button> <button
                                    class="btn btn-secondary" id="resetBtn">Reset</button></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel Visualisasi Chart -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="panel">
                        <div class="panel-hdr">
                            <h2>Volume per Jenis Linen</h2>
                        </div>
                        <div class="panel-container show">
                            <div class="panel-content" style="min-height: 400px;"><canvas id="typeChart"></canvas></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel">
                        <div class="panel-hdr">
                            <h2>Volume per Kategori (Infeksius vs Non)</h2>
                        </div>
                        <div class="panel-container show">
                            <div class="panel-content" style="min-height: 400px;"><canvas id="categoryChart"></canvas></div>
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
                                    <th>Jenis Linen</th>
                                    <th>Kategori</th>
                                    <th>PIC (Kesling)</th>
                                    <th class="text-right">Total Volume (Kg)</th>
                                    <th class="text-center">Jumlah Input</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-right font-weight-bold">GRAND TOTAL:</th>
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
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true
            });
            $('.select2').select2();

            let typeChart = null,
                categoryChart = null;

            const table = $('#reportTable').DataTable({
                columns: [{
                        data: null,
                        searchable: false,
                        orderable: false,
                        defaultContent: ''
                    },
                    {
                        data: 'linen_type_name'
                    }, {
                        data: 'linen_category_name'
                    }, {
                        data: 'pic_name'
                    },
                    {
                        data: 'total_volume',
                        className: 'text-right',
                        render: $.fn.dataTable.render.number('.', ',', 2)
                    },
                    {
                        data: 'input_count',
                        className: 'text-center'
                    }
                ],
                "fnCreatedRow": (nRow, aData, iDataIndex) => $('td:eq(0)', nRow).html(iDataIndex + 1),
                dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                footerCallback: function(row, data, start, end, display) {
                    var api = this.api();
                    var totalVolume = api.column(4, {
                        page: 'current'
                    }).data().reduce((a, b) => parseFloat(a) + parseFloat(b), 0);
                    var totalCount = api.column(5, {
                        page: 'current'
                    }).data().reduce((a, b) => parseInt(a) + parseInt(b), 0);
                    $(api.column(4).footer()).html(totalVolume.toFixed(2) + ' Kg');
                    $(api.column(5).footer()).html(totalCount);
                }
            });

            function loadReportData() {
                $.ajax({
                    url: "{{ route('laundry.data') }}",
                    data: {
                        start_date: $('#start_date').val(),
                        end_date: $('#end_date').val(),
                        linen_type_id: $('#linen_type_id').val(),
                        linen_category_id: $('#linen_category_id').val(),
                        pic_id: $('#pic_id').val()
                    },
                    beforeSend: () => $('#filterBtn').prop('disabled', true).text('Memuat...'),
                    success: (response) => updateUI(response.data),
                    error: (xhr) => alert("Gagal memuat data laporan."),
                    complete: () => $('#filterBtn').prop('disabled', false).text('Terapkan')
                });
            }

            function updateUI(data) {
                table.clear().rows.add(data).draw();

                const typeData = aggregateData(data, 'linen_type_name', 'total_volume');
                updateChart(typeChart, 'typeChart', 'pie', Object.keys(typeData), Object.values(typeData),
                    'Volume per Jenis Linen (Kg)', (id, chart) => typeChart = chart);

                const categoryData = aggregateData(data, 'linen_category_name', 'total_volume');
                updateChart(categoryChart, 'categoryChart', 'doughnut', Object.keys(categoryData), Object.values(
                    categoryData), 'Volume per Kategori (Kg)', (id, chart) => categoryChart = chart);
            }

            const aggregateData = (data, key, val) => data.reduce((acc, i) => {
                acc[i[key]] = (acc[i[key]] || 0) + parseFloat(i[val]);
                return acc;
            }, {});

            function updateChart(instance, id, type, labels, data, title, callback) {
                if (instance) instance.destroy();
                const colors = ['rgba(255, 99, 132, 0.7)', 'rgba(54, 162, 235, 0.7)', 'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)', 'rgba(153, 102, 255, 0.7)', 'rgba(255, 159, 64, 0.7)'
                ];
                const chart = new Chart(document.getElementById(id).getContext('2d'), {
                    type: type,
                    data: {
                        labels: labels,
                        datasets: [{
                            label: title,
                            data: data,
                            backgroundColor: colors
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top'
                            }
                        }
                    }
                });
                callback(id, chart);
            }

            $('#filterBtn').on('click', loadReportData);
            $('#resetBtn').on('click', () => {
                $('#start_date, #end_date').val('');
                $('#linen_type_id, #linen_category_id, #pic_id').val('').trigger('change');
                loadReportData();
            });

            loadReportData();
        });
    </script>
@endsection
