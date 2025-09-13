@extends('inc.layout')
@section('title', 'Satu Sehat - Dashboard')

@section('extended-css')
    <link rel="stylesheet" media="screen, print" href="/css/formplugins/bootstrap-datepicker/bootstrap-datepicker.css">
    <link rel="stylesheet" media="screen, print" href="/css/datagrid/datatables/datatables.bundle.css">
    <link rel="stylesheet" media="screen, print" href="/css/formplugins/select2/select2.bundle.css">
    <style>
        .summary-box {
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .summary-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .summary-box .display-4 {
            font-size: 2.2rem;
        }

        .card-title {
            font-size: 0.8rem;
            font-weight: 500;
            color: #888;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }

        .card-value {
            font-size: 1.75rem;
            font-weight: 700;
        }

        .card-modul {
            border: 1px solid #e2e2e2;
            border-radius: .5rem;
            padding: 1rem;
            background-color: #fff;
            margin-bottom: 1rem;
        }
    </style>
@endsection

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">Satu Sehat</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
        </ol>

        {{-- Filter Tanggal --}}
        <div class="panel mb-g">
            <div class="panel-hdr">
                <h2 class="fw-bolder"><i class="fal fa-filter mr-2"></i>Filter Periode Registrasi</h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label" for="start_date">Periode Awal</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i
                                                class="fal fa-calendar-alt"></i></span></div>
                                    <input type="text" class="form-control datepicker" id="start_date" name="start_date"
                                        value="{{ \Carbon\Carbon::now()->startOfMonth()->format('d-m-Y') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label" for="end_date">Periode Akhir</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i
                                                class="fal fa-calendar-alt"></i></span></div>
                                    <input type="text" class="form-control datepicker" id="end_date" name="end_date"
                                        value="{{ \Carbon\Carbon::now()->format('d-m-Y') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <div class="form-group w-100">
                                <button type="button" class="btn btn-primary btn-block" id="btn-filter"><i
                                        class="fal fa-search mr-2"></i> Cari Data</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Summary Cards --}}
        <div class="row" id="summary-cards-container">
            <div class="col-sm-6 col-xl-3">
                <div class="p-3 bg-primary-300 rounded overflow-hidden position-relative text-white mb-g summary-box">
                    <div class="">
                        <h3 class="display-4 d-block l-h-n m-0 fw-500" id="total-terkirim">... <small
                                class="d-block m-0 l-h-n">Total Terkirim</small></h3>
                        <small id="subtext-total-terkirim">Total Registrasi: ...</small>
                    </div>
                    <i class="fal fa-paper-plane position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n1"
                        style="font-size:6rem"></i>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="p-3 bg-success-300 rounded overflow-hidden position-relative text-white mb-g summary-box">
                    <div class="">
                        <h3 class="display-4 d-block l-h-n m-0 fw-500" id="total-rajal">... <small
                                class="d-block m-0 l-h-n">Rawat Jalan</small></h3>
                        <small id="subtext-total-rajal">Total Registrasi: ...</small>
                    </div>
                    <i class="fal fa-walking position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n1"
                        style="font-size:6rem"></i>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="p-3 bg-info-300 rounded overflow-hidden position-relative text-white mb-g summary-box">
                    <div class="">
                        <h3 class="display-4 d-block l-h-n m-0 fw-500" id="total-igd">... <small
                                class="d-block m-0 l-h-n">IGD</small></h3>
                        <small id="subtext-total-igd">Total Registrasi: ...</small>
                    </div>
                    <i class="fal fa-ambulance position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n1"
                        style="font-size:6rem"></i>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="p-3 bg-warning-400 rounded overflow-hidden position-relative text-white mb-g summary-box">
                    <div class="">
                        <h3 class="display-4 d-block l-h-n m-0 fw-500" id="total-ranap">... <small
                                class="d-block m-0 l-h-n">Rawat Inap</small></h3>
                        <small id="subtext-total-ranap">Total Registrasi: ...</small>
                    </div>
                    <i class="fal fa-procedures position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n1"
                        style="font-size:6rem"></i>
                </div>
            </div>
        </div>

        {{-- Charts --}}
        <div class="row">
            <div class="col-lg-8">
                <div id="panel-encounter-chart" class="panel">
                    <div class="panel-hdr">
                        <h2 id="encounter-chart-title">Pencapaian Kiriman Encounter</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content"><canvas id="encounter-chart" style="height: 350px;"></canvas></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div id="panel-summary-fhir" class="panel">
                    <div class="panel-hdr">
                        <h2>Ringkasan Transaksi FHIR</h2>
                    </div>
                    <div class="panel-container show" style="max-height: 455px; overflow-y: auto;">
                        <div class="panel-content">
                            <div class="row" id="fhir-summary-container">
                                {{-- Akan diisi oleh JavaScript --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div id="panel-master-data-chart" class="panel">
                    <div class="panel-hdr">
                        <h2>Capaian Mapping Master Data</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content"><canvas id="master-data-chart" style="min-height: 400px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel Log Mapping --}}
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-log-mapping" class="panel">
                    <div class="panel-hdr">
                        <h2>Detail Log Mapping Master Data</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="mapping_type" class="form-label">Tipe Data</label>
                                    <select class="form-control select2" id="mapping_type">
                                        <option value="department">Department</option>
                                        <option value="loc_department">Lokasi Department</option>
                                        <option value="nakes">Tenaga Kesehatan</option>
                                        {{-- Tambahkan opsi lain jika perlu --}}
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="mapping_status" class="form-label">Status</label>
                                    <select class="form-control select2" id="mapping_status">
                                        <option value="">Semua Status</option>
                                        <option value="sukses">Sukses</option>
                                        <option value="gagal">Gagal</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="mapping_keyword" class="form-label">Pencarian</label>
                                    <input type="text" id="mapping_keyword" class="form-control"
                                        placeholder="Cari berdasarkan nama/kode...">
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button class="btn btn-primary w-100" id="btn-filter-log"><i
                                            class="fal fa-search mr-2"></i>Filter Log</button>
                                </div>
                            </div>
                            <table id="dt-log-mapping" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th>Detail Master Data</th>
                                        <th class="text-center">Status</th>
                                        <th>Hasil Mapping</th>
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
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/statistics/chartjs/chartjs.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(document).ready(function() {
            // Inisialisasi
            $('.datepicker').datepicker({
                todayBtn: "linked",
                clearBtn: true,
                todayHighlight: true,
                autoclose: true,
                format: "dd-mm-yyyy"
            });
            $('.select2').select2({
                width: '100%'
            });

            let encounterChart, masterDataChart;
            const logTable = $('#dt-log-mapping').DataTable({
                responsive: true,
                pageLength: 10,
                processing: true,
                serverSide: false,
                data: [], // Mulai dengan data kosong
                columns: [{
                        data: 'detail'
                    },
                    {
                        data: 'status',
                        className: 'text-center',
                        render: function(data) {
                            return `<span class="badge badge-${data === 'Berhasil' ? 'success' : 'danger'} badge-pill">${data}</span>`;
                        }
                    },
                    {
                        data: 'hasil'
                    }
                ]
            });
            const fhirResources = ['Encounter', 'Condition', 'Observation', 'Procedure', 'Composition',
                'Medication', 'MedicationRequest', 'MedicationDispense', 'AllergyIntolerance', 'ImagingStudy',
                'ServiceRequest', 'ClinicalImpression', 'Immunization', 'QuestionnaireResponse',
                'MedicationStatement', 'CarePlan', 'Specimen', 'DiagnosticReport', 'EpisodeOfCare'
            ];

            function showLoading(element) {
                $(element).parent().append(
                    '<div class="panel-lock-shield"><div class="sk-fading-circle"><div class="sk-circle1 sk-circle"></div><div class="sk-circle2 sk-circle"></div><div class="sk-circle3 sk-circle"></div><div class="sk-circle4 sk-circle"></div><div class="sk-circle5 sk-circle"></div><div class="sk-circle6 sk-circle"></div><div class="sk-circle7 sk-circle"></div><div class="sk-circle8 sk-circle"></div><div class="sk-circle9 sk-circle"></div><div class="sk-circle10 sk-circle"></div><div class="sk-circle11 sk-circle"></div><div class="sk-circle12 sk-circle"></div></div></div>'
                    );
            }

            function hideLoading(element) {
                $(element).parent().find('.panel-lock-shield').remove();
            }

            function loadDashboardData() {
                const startDate = $('#start_date').val();
                const endDate = $('#end_date').val();

                showLoading('#summary-cards-container');
                showLoading('#panel-encounter-chart');
                showLoading('#panel-summary-fhir');
                showLoading('#panel-master-data-chart');

                $.ajax({
                    type: "POST",
                    url: "{{ route('satu-sehat.dashboard.summary-cards') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        start_date: startDate,
                        end_date: endDate
                    },
                    success: function(data) {
                        $('#total-terkirim').html(
                            `${data.total_terkirim} <small class="d-block m-0 l-h-n">Total Terkirim</small>`
                            );
                        $('#subtext-total-terkirim').text(`Total Registrasi: ${data.total_registrasi}`);
                        $('#total-rajal').html(
                            `${data.total_rajal_terkirim} <small class="d-block m-0 l-h-n">Rawat Jalan</small>`
                            );
                        $('#subtext-total-rajal').text(`Total Registrasi: ${data.total_rajal}`);
                        $('#total-igd').html(
                            `${data.total_igd_terkirim} <small class="d-block m-0 l-h-n">IGD</small>`
                            );
                        $('#subtext-total-igd').text(`Total Registrasi: ${data.total_igd}`);
                        $('#total-ranap').html(
                            `${data.total_ranap_terkirim} <small class="d-block m-0 l-h-n">Rawat Inap</small>`
                            );
                        $('#subtext-total-ranap').text(`Total Registrasi: ${data.total_ranap}`);
                    },
                    complete: () => hideLoading('#summary-cards-container')
                });

                $.ajax({
                    type: "POST",
                    url: "{{ route('satu-sehat.dashboard.encounter-chart') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        start_date: startDate,
                        end_date: endDate
                    },
                    success: function(data) {
                        $('#encounter-chart-title').text(
                            `Pencapaian Kiriman Encounter (${startDate} - ${endDate})`);
                        if (encounterChart) encounterChart.destroy();
                        encounterChart = new Chart($('#encounter-chart').get(0).getContext('2d'), {
                            type: 'line',
                            data: {
                                labels: data.labels,
                                datasets: data.datasets.map(ds => ({
                                    ...ds,
                                    borderWidth: 2,
                                    tension: 0.4,
                                    pointBackgroundColor: ds.borderColor
                                }))
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false
                            }
                        });
                    },
                    complete: () => hideLoading('#panel-encounter-chart')
                });

                // KODE YANG DIPERBARUI DAN DITAMBAHKAN
                $.ajax({
                    type: 'POST',
                    url: "{{ route('satu-sehat.dashboard.fhir-summary') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        start_date: startDate,
                        end_date: endDate
                    },
                    success: function(data) {
                        let html = '';
                        fhirResources.forEach(res => {
                            const count = data[res] || 0;
                            html +=
                                `<div class="col-6 col-md-4"><div class="card-modul text-center"><div class="card-title">${res}</div><div class="card-value text-primary">${count}</div></div></div>`;
                        });
                        $('#fhir-summary-container').html(html);
                    },
                    complete: () => hideLoading('#panel-summary-fhir')
                });

                $.ajax({
                    type: "POST",
                    url: "{{ route('satu-sehat.dashboard.master-data-chart') }}",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        if (masterDataChart) masterDataChart.destroy();
                        masterDataChart = new Chart($('#master-data-chart').get(0).getContext('2d'), {
                            type: 'bar',
                            data: {
                                labels: data.categories,
                                datasets: data.series.map(s => ({
                                    label: s.name,
                                    data: s.data,
                                    backgroundColor: s.color
                                }))
                            },
                            options: {
                                indexAxis: 'y',
                                responsive: true,
                                maintainAspectRatio: true,
                                scales: {
                                    x: {
                                        stacked: true
                                    },
                                    y: {
                                        stacked: true,
                                        ticks: {
                                            mirror: true,
                                            labelOffset: -10,
                                            z: 1
                                        }
                                    }
                                }
                            }
                        });
                    },
                    complete: () => hideLoading('#panel-master-data-chart')
                });
            }

            function loadLogTable() {
                showLoading('#panel-log-mapping');
                $.ajax({
                    type: "POST",
                    url: "{{ route('satu-sehat.dashboard.mapping-log-table') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        type: $('#mapping_type').val(),
                        status: $('#mapping_status').val(),
                        keyword: $('#mapping_keyword').val()
                    },
                    success: function(response) {
                        logTable.clear().rows.add(response.data).draw();
                    },
                    complete: () => hideLoading('#panel-log-mapping')
                });
            }

            $('#btn-filter').on('click', loadDashboardData);
            $('#btn-filter-log').on('click', loadLogTable);

            loadDashboardData();
            loadLogTable();
        });
    </script>
@endsection
