@extends('inc.layout')
@section('title', 'Satu Sehat - Dashboard')

@section('extended-css')
    {{-- CSS untuk Date Picker --}}
    <link rel="stylesheet" media="screen, print" href="/css/formplugins/bootstrap-datepicker/bootstrap-datepicker.css">
    <style>
        .summary-box {
            cursor: pointer;
        }

        .summary-box .fs-xxl {
            font-size: 2.5rem;
        }

        .card-title {
            font-size: 0.9rem;
            font-weight: 500;
            color: #888;
            text-transform: uppercase;
        }

        .card-value {
            font-size: 1.5rem;
            font-weight: 700;
        }

        .card-modul {
            border: 1px solid #e2e2e2;
            border-radius: .5rem;
            padding: 1rem;
            background-color: #fff;
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
        <div class="panel">
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label" for="start_date">Periode Awal</label>
                                <input type="text" class="form-control datepicker" id="start_date" name="start_date"
                                    value="{{ \Carbon\Carbon::now()->startOfMonth()->format('d-m-Y') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label" for="end_date">Periode Akhir</label>
                                <input type="text" class="form-control datepicker" id="end_date" name="end_date"
                                    value="{{ \Carbon\Carbon::now()->format('d-m-Y') }}">
                            </div>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <div class="form-group">
                                <button type="button" class="btn btn-primary btn-block" id="btn-filter">
                                    <i class="fal fa-search mr-2"></i> Cari
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Summary Cards --}}
        <div class="row">
            <div class="col-sm-6 col-xl-3">
                <div class="p-3 bg-primary-300 rounded overflow-hidden position-relative text-white mb-g summary-box">
                    <div class="">
                        <h3 class="display-4 d-block l-h-n m-0 fw-500" id="total-terkirim">
                            <small class="d-block m-0 l-h-n">Total Terkirim</small>
                        </h3>
                    </div>
                    <i class="fal fa-paper-plane position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n1"
                        style="font-size:6rem"></i>
                </div>
            </div>
            {{-- Tambahkan 3 box lainnya (Rajal, IGD, Ranap) dengan style yang berbeda --}}
        </div>

        {{-- Charts --}}
        <div class="row">
            <div class="col-lg-8">
                <div id="panel-encounter-chart" class="panel">
                    <div class="panel-hdr">
                        <h2>Pencapaian Kiriman Encounter</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content"><canvas id="encounter-chart"></canvas></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div id="panel-summary-fhir" class="panel">
                    <div class="panel-hdr">
                        <h2>Ringkasan Transaksi FHIR</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <div class="row">
                                {{-- Buat loop atau list statis untuk menampilkan 19 box kecil --}}
                                <div class="col-6 mb-3">
                                    <div class="card-modul text-center">
                                        <div class="card-title">Encounter</div>
                                        <div class="card-value text-primary">211</div>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="card-modul text-center">
                                        <div class="card-title">Condition</div>
                                        <div class="card-value text-primary">2</div>
                                    </div>
                                </div>
                                {{-- ...lanjutkan untuk resource FHIR lainnya --}}
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
                        <div class="panel-content"><canvas id="master-data-chart" style="height: 400px;"></canvas></div>
                    </div>
                </div>
            </div>
        </div>

    </main>
@endsection

@section('plugin')
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/statistics/chartjs/chartjs.bundle.js"></script>
    <script>
        $(document).ready(function() {
            // Inisialisasi Datepicker
            $('.datepicker').datepicker({
                todayBtn: "linked",
                clearBtn: true,
                todayHighlight: true,
                autoclose: true,
                format: "dd-mm-yyyy"
            });

            // Inisialisasi Chart
            let encounterChart, masterDataChart;

            function loadDashboardData() {
                const startDate = $('#start_date').val();
                const endDate = $('#end_date').val();

                // 1. Load Summary Cards
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
                        // Update card lainnya
                    }
                });

                // 2. Load Encounter Chart
                $.ajax({
                    type: "POST",
                    url: "{{ route('satu-sehat.dashboard.encounter-chart') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        start_date: startDate,
                        end_date: endDate
                    },
                    success: function(data) {
                        if (encounterChart) encounterChart.destroy();
                        const ctx = document.getElementById('encounter-chart').getContext('2d');
                        encounterChart = new Chart(ctx, {
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
                    }
                });

                // 3. Load Master Data Chart
                $.ajax({
                    type: "POST",
                    url: "{{ route('satu-sehat.dashboard.master-data-chart') }}",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        if (masterDataChart) masterDataChart.destroy();
                        const ctx = document.getElementById('master-data-chart').getContext('2d');
                        masterDataChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: data.map(d => d.tipe_data),
                                datasets: [{
                                        label: 'Total Data',
                                        data: data.map(d => d.total_data),
                                        backgroundColor: '#7cb5ec'
                                    },
                                    {
                                        label: 'Sudah Mapping',
                                        data: data.map(d => d.total_mapping),
                                        backgroundColor: '#90ed7d'
                                    },
                                ]
                            },
                            options: {
                                indexAxis: 'y',
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    x: {
                                        stacked: true
                                    },
                                    y: {
                                        stacked: true
                                    }
                                }
                            }
                        });
                    }
                });
            }

            // Event listener untuk tombol filter
            $('#btn-filter').on('click', function() {
                loadDashboardData();
            });

            // Muat data saat halaman pertama kali dibuka
            loadDashboardData();
        });
    </script>
@endsection
