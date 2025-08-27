@extends('inc.layout')
@section('title', 'Dashboard Manajerial Kendaraan')

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <ol class="breadcrumb bg-primary-300">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Dashboard Kendaraan</li>
        </ol>

        {{-- BARIS 1: KARTU KPI UTAMA --}}
        <div class="row">
            <div class="col-sm-6 col-xl-3">
                <div class="p-3 bg-primary-300 rounded overflow-hidden position-relative text-white mb-g">
                    <div class="">
                        <h3 class="display-4 d-block l-h-n m-0 fw-500">{{ $kpi['total_vehicles'] }} <small
                                class="m-0 l-h-n">Total Kendaraan</small></h3>
                    </div>
                    <i class="fal fa-car position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n1"
                        style="font-size:6rem"></i>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="p-3 bg-danger-300 rounded overflow-hidden position-relative text-white mb-g">
                    <div class="">
                        <h3 class="display-4 d-block l-h-n m-0 fw-500">{{ $kpi['open_tickets'] }} <small
                                class="m-0 l-h-n">Tiket Servis Terbuka</small></h3>
                    </div>
                    <i class="fal fa-wrench position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n1"
                        style="font-size:6rem"></i>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="p-3 bg-warning-400 rounded overflow-hidden position-relative text-white mb-g">
                    <div class="">
                        <h3 class="display-4 d-block l-h-n m-0 fw-500">{{ $kpi['total_alerts'] }} <small
                                class="m-0 l-h-n">Peringatan Aktif</small></h3>
                    </div>
                    <i class="fal fa-bell position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n1"
                        style="font-size:6rem"></i>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="p-3 bg-success-200 rounded overflow-hidden position-relative text-white mb-g">
                    <div class="">
                        <h3 class="display-4 d-block l-h-n m-0 fw-500">Rp
                            <small>{{ number_format($kpi['total_cost_this_month'], 0, ',', '.') }}</small> <small
                                class="m-0 l-h-n">Biaya Bulan Ini</small>
                        </h3>
                    </div>
                    <i class="fal fa-money-bill-wave position-absolute pos-right pos-bottom opacity-15 mb-n1 mr-n1"
                        style="font-size:6rem"></i>
                </div>
            </div>
        </div>

        {{-- BARIS 2: PERINGATAN & NOTIFIKASI PROAKTIF --}}
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-alerts" class="panel">
                    <div class="panel-hdr">
                        <h2><i class="fal fa-exclamation-triangle mr-2 text-warning"></i> Peringatan & Tindakan Diperlukan
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            @if ($kpi['total_alerts'] == 0)
                                <div class="alert alert-success">Tidak ada peringatan aktif. Semua sistem berjalan normal.
                                </div>
                            @else
                                <div class="row">
                                    @forelse ($alerts['expiring_stnk'] as $vehicle)
                                        <div class="col-md-6">
                                            <div class="alert alert-danger"><strong>STNK AKAN HABIS (H-60):</strong>
                                                {{ $vehicle->name }} ({{ $vehicle->plate_number }}) akan berakhir pada
                                                {{ \Carbon\Carbon::parse($vehicle->stnk_due_date)->format('d M Y') }}.</div>
                                        </div>
                                    @empty
                                    @endforelse
                                    @forelse ($alerts['expiring_taxes'] as $vehicle)
                                        <div class="col-md-6">
                                            <div class="alert alert-warning"><strong>PAJAK TAHUNAN (H-30):</strong>
                                                {{ $vehicle->name }} ({{ $vehicle->plate_number }}) akan berakhir pada
                                                {{ \Carbon\Carbon::parse($vehicle->tax_due_date)->format('d M Y') }}.</div>
                                        </div>
                                    @empty
                                    @endforelse
                                    @forelse ($alerts['expiring_licenses'] as $driver)
                                        <div class="col-md-6">
                                            <div class="alert alert-warning"><strong>SIM PENGEMUDI (H-30):</strong> SIM a/n
                                                {{ $driver->name }} akan berakhir pada
                                                {{ \Carbon\Carbon::parse($driver->license_expiry_date)->format('d M Y') }}.
                                            </div>
                                        </div>
                                    @empty
                                    @endforelse
                                    @forelse ($alerts['oil_change_due'] as $vehicle)
                                        <div class="col-md-6">
                                            <div class="alert alert-info"><strong>GANTI OLI (KM):</strong>
                                                {{ $vehicle->name }} ({{ $vehicle->plate_number }}) sudah waktunya ganti
                                                oli.</div>
                                        </div>
                                    @empty
                                    @endforelse
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div id="panel-in-use" class="panel">
                    <div class="panel-hdr">
                        <h2><i class="fal fa-shipping-fast mr-2 text-info"></i> Kendaraan yang Sedang Digunakan Saat Ini
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            @forelse ($vehiclesInUse as $log)
                                <div class="card border mb-2 shadow-sm-hover">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <h5 class="mb-1">{{ $log->internal_vehicle->name ?? 'N/A' }} <small
                                                        class="text-muted">({{ $log->internal_vehicle->license_plate ?? 'N/A' }})</small>
                                                </h5>
                                                <p class="mb-1 text-muted">
                                                    <i class="fal fa-user mr-2"></i> Pengemudi:
                                                    <strong>{{ $log->driver->employee->fullname ?? 'N/A' }}</strong>
                                                </p>
                                                <p class="mb-0 text-muted">
                                                    <i class="fal fa-map-marker-alt mr-2"></i> Tujuan:
                                                    <strong>{{ $log->destination }}</strong>
                                                </p>
                                            </div>
                                            <div class="text-right">
                                                <div class="text-muted">Berangkat Sejak</div>
                                                <div class="font-weight-bold h5 text-info">
                                                    {{-- Tampilkan waktu relatif yang mudah dibaca --}}
                                                    {{ \Carbon\Carbon::parse($log->start_datetime)->diffForHumans() }}
                                                </div>
                                                <small
                                                    class="text-muted">{{ \Carbon\Carbon::parse($log->start_datetime)->format('d M Y, H:i') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="alert alert-success text-center">
                                    <i class="fal fa-check-circle mr-2"></i> Saat ini tidak ada kendaraan yang sedang
                                    digunakan. Semua unit berada di lokasi.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- BARIS 3: LAPORAN VISUAL & TABEL --}}
        <div class="row">
            {{-- LAPORAN PERAWATAN --}}
            <div class="col-lg-7">
                <div id="panel-maintenance-chart" class="panel">
                    <div class="panel-hdr">
                        <h2>Biaya Perawatan per Bulan (6 Bulan Terakhir)</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content"><canvas id="maintenanceChart" style="height: 250px;"></canvas></div>
                    </div>
                </div>
                <div id="panel-maintenance-table" class="panel">
                    <div class="panel-hdr">
                        <h2>Ringkasan Biaya Perawatan per Kendaraan</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table class="table table-sm table-bordered table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Kendaraan</th>
                                        <th>Jumlah Servis</th>
                                        <th>Total Biaya</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($maintenanceReport as $report)
                                        <tr>
                                            <td>{{ $report->name }}</td>
                                            <td>{{ $report->total_tickets }} Tiket</td>
                                            {{-- KODE BARU, MEMANGGIL AKSESOR --}}
                                            <td>Rp {{ number_format($report->total_maintenance_cost, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            {{-- LAPORAN INSPEKSI & OPERASIONAL --}}
            <div class="col-lg-5">
                <div id="panel-inspection" class="panel">
                    <div class="panel-hdr">
                        <h2>Ringkasan Inspeksi (Bulan Ini)</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center">Total Sesi
                                    Inspeksi<span
                                        class="badge badge-primary badge-pill">{{ $inspectionSummary['total_sessions'] }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">Total Temuan
                                    Kerusakan<span
                                        class="badge badge-danger badge-pill">{{ $inspectionSummary['total_findings'] }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">Temuan Paling
                                    Umum<span
                                        class="badge badge-secondary badge-pill">{{ $inspectionSummary['most_common_finding']->item->name ?? 'N/A' }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div id="panel-operational" class="panel">
                    <div class="panel-hdr">
                        <h2>Ringkasan Operasional (All-Time)</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table class="table table-sm table-bordered table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Kendaraan</th>
                                        <th>Jarak Tempuh</th>
                                        <th>Rata-rata BBM</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($operationalReport as $report)
                                        <tr>
                                            <td>{{ $report->name }}</td>
                                            <td>{{ number_format($report->total_distance, 0, ',', '.') }} KM</td>
                                            <td>{{ number_format($report->average, 2, ',', '.') }} KM/L</td>
                                        </tr>
                                    @endforeach
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
    <script src="/js/statistics/chartjs/chartjs.bundle.js"></script>
    <script>
        $(document).ready(function() {
            var ctx = document.getElementById('maintenanceChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($chartLabels),
                    datasets: [{
                        label: 'Total Biaya (Rp)',
                        data: @json($chartData),
                        backgroundColor: 'rgba(54, 162, 235, 0.6)'
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: (value) => 'Rp ' + new Intl.NumberFormat('id-ID').format(value)
                            }
                        }
                    },
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: (context) => 'Rp ' + new Intl.NumberFormat('id-ID').format(context
                                    .parsed.y)
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection
