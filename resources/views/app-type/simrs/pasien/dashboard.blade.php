@extends('inc.layout')
@section('title', 'Dashboard Pasien - ' . $pasien->name)

@section('extended-css')
    <style>
        .patient-header-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            padding: 2rem;
            color: white;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            transition: all 0.3s ease;
            border-left: 4px solid;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .stat-card.primary {
            border-color: #667eea;
        }

        .stat-card.success {
            border-color: #28a745;
        }

        .stat-card.warning {
            border-color: #ffc107;
        }

        .stat-card.danger {
            border-color: #dc3545;
        }

        .stat-card.info {
            border-color: #17a2b8;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            line-height: 1;
        }

        .timeline-item {
            position: relative;
            padding-left: 2.5rem;
            padding-bottom: 2rem;
            border-left: 2px solid #e9ecef;
        }

        .timeline-item:last-child {
            border-left: 0;
            padding-bottom: 0;
        }

        .timeline-badge {
            position: absolute;
            left: -10px;
            top: 0;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #667eea;
            border: 3px solid white;
            box-shadow: 0 0 0 3px #e9ecef;
        }

        .quick-action-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            border: 2px solid transparent;
        }

        .quick-action-card:hover {
            border-color: #667eea;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.2);
        }

        .quick-action-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .info-badge {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            margin: 0.25rem;
        }

        .accordion-custom .card {
            border: none;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            border-radius: 10px;
            overflow: hidden;
        }

        .accordion-custom .card-header {
            background: linear-gradient(to right, #f8f9fa, #e9ecef);
            border: none;
            padding: 1.25rem 1.5rem;
        }

        .chart-container {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .filter-tabs {
            background: white;
            border-radius: 10px;
            padding: 0.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .filter-tabs .nav-link {
            border: none;
            border-radius: 8px;
            color: #6c757d;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .filter-tabs .nav-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .vitals-card {
            background: white;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .vitals-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: #667eea;
        }
    </style>
@endsection

@section('content')
    <main id="js-page-content" role="main" class="page-content" x-data="patientDashboard()">
        {{-- Patient Header Card --}}
        <div class="patient-header-card mb-4">
            <div class="row align-items-center">
                <div class="col-md-2 text-center">
                    <img src="{{ $pasien->photo ?? ($pasien->gender == 'L' ? '/img/demo/avatars/avatar-c.png' : '/img/demo/avatars/avatar-p.png') }}"
                        class="rounded-circle border border-white"
                        style="width: 120px; height: 120px; object-fit: cover; border-width: 4px !important;"
                        alt="Foto Pasien">
                </div>
                <div class="col-md-7">
                    <h2 class="mb-2 text-white font-weight-bold">{{ $pasien->name }}</h2>
                    <div class="d-flex flex-wrap">
                        <span class="info-badge bg-white text-primary">
                            <i class="fas fa-id-card mr-1"></i> RM: {{ $pasien->medical_record_number }}
                        </span>
                        <span class="info-badge bg-white text-primary">
                            <i class="fas fa-id-badge mr-1"></i> NIK: {{ $pasien->id_card ?? 'N/A' }}
                        </span>
                        <span class="info-badge bg-white text-primary">
                            <i class="fas fa-birthday-cake mr-1"></i>
                            {{ $pasien->date_of_birth ? \Carbon\Carbon::parse($pasien->date_of_birth)->age : 'N/A' }} Tahun
                        </span>
                        <span class="info-badge bg-white text-primary">
                            <i class="fas {{ $pasien->gender == 'Laki-laki' ? 'fa-mars' : 'fa-venus' }} mr-1"></i>
                            {{ $pasien->gender == 'Laki-laki' ? 'Laki-laki' : 'Perempuan' }}
                        </span>
                    </div>
                    <div class="mt-3">
                        <span class="text-white"><i
                                class="fas fa-map-marker-alt mr-2"></i>{{ $pasien->address ?? 'Alamat tidak tersedia' }}</span>
                    </div>
                    @if ($pasien->alergi)
                        <div class="mt-2">
                            <span class="info-badge bg-danger text-white">
                                <i class="fas fa-exclamation-triangle mr-1"></i> Alergi: {{ $pasien->alergi }}
                            </span>
                        </div>
                    @endif
                </div>
                <div class="col-md-3 text-right">
                    <button class="btn btn-light btn-lg mb-2 w-100" onclick="window.print()">
                        <i class="fas fa-print mr-2"></i> Cetak Riwayat
                    </button>
                    <a href="{{ route('edit.pendaftaran.pasien', $pasien->id) }}"
                        class="btn btn-outline-light btn-lg w-100">
                        <i class="fas fa-edit mr-2"></i> Edit Data
                    </a>
                </div>
            </div>
        </div>

        {{-- Statistics Cards --}}
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stat-card primary">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Kunjungan</h6>
                            <div class="stat-number text-primary">{{ $statistics['total_visits'] }}</div>
                        </div>
                        <div class="text-primary" style="font-size: 3rem; opacity: 0.2;">
                            <i class="fas fa-hospital-user"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card success">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Kunjungan Selesai</h6>
                            <div class="stat-number text-success">{{ $statistics['completed_visits'] }}</div>
                        </div>
                        <div class="text-success" style="font-size: 3rem; opacity: 0.2;">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card warning">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Tindakan</h6>
                            <div class="stat-number text-warning">{{ $statistics['total_procedures'] }}</div>
                        </div>
                        <div class="text-warning" style="font-size: 3rem; opacity: 0.2;">
                            <i class="fas fa-procedures"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card info">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Asesmen</h6>
                            <div class="stat-number text-info">{{ $statistics['total_assessments'] }}</div>
                        </div>
                        <div class="text-info" style="font-size: 3rem; opacity: 0.2;">
                            <i class="fas fa-stethoscope"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="row mb-4">
            <div class="col-12">
                <h4 class="mb-3"><i class="fas fa-bolt text-warning mr-2"></i>Aksi Cepat</h4>
            </div>
            <div class="col-md-2">
                <div class="quick-action-card"
                    onclick="location.href='{{ route('pendaftaran.daftar_registrasi_pasien', ['patient_id' => $pasien->id]) }}'">
                    <div class="quick-action-icon">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                    <h6 class="mb-0">Registrasi Baru</h6>
                </div>
            </div>
            <div class="col-md-2">
                <div class="quick-action-card" @click="showSection('history')">
                    <div class="quick-action-icon">
                        <i class="fas fa-history"></i>
                    </div>
                    <h6 class="mb-0">Riwayat Lengkap</h6>
                </div>
            </div>
            <div class="col-md-2">
                <div class="quick-action-card" @click="showSection('lab')">
                    <div class="quick-action-icon">
                        <i class="fas fa-flask"></i>
                    </div>
                    <h6 class="mb-0">Hasil Lab</h6>
                </div>
            </div>
            <div class="col-md-2">
                <div class="quick-action-card" @click="showSection('radiology')">
                    <div class="quick-action-icon">
                        <i class="fas fa-x-ray"></i>
                    </div>
                    <h6 class="mb-0">Hasil Radiologi</h6>
                </div>
            </div>
            <div class="col-md-2">
                <div class="quick-action-card" @click="showSection('medication')">
                    <div class="quick-action-icon">
                        <i class="fas fa-pills"></i>
                    </div>
                    <h6 class="mb-0">Riwayat Obat</h6>
                </div>
            </div>
            <div class="col-md-2">
                <div class="quick-action-card" @click="showSection('billing')">
                    <div class="quick-action-icon">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <h6 class="mb-0">Tagihan</h6>
                </div>
            </div>
        </div>

        {{-- Filter Tabs --}}
        <div class="filter-tabs">
            <ul class="nav nav-pills" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="pill" href="#overview" @click="currentTab = 'overview'">
                        <i class="fas fa-th-large mr-2"></i>Ringkasan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="pill" href="#history" @click="currentTab = 'history'">
                        <i class="fas fa-history mr-2"></i>Riwayat Kunjungan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="pill" href="#vitals" @click="currentTab = 'vitals'">
                        <i class="fas fa-heartbeat mr-2"></i>Vital Signs
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="pill" href="#lab" @click="currentTab = 'lab'">
                        <i class="fas fa-flask mr-2"></i>Laboratorium
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="pill" href="#radiology" @click="currentTab = 'radiology'">
                        <i class="fas fa-x-ray mr-2"></i>Radiologi
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="pill" href="#documents" @click="currentTab = 'documents'">
                        <i class="fas fa-file-medical mr-2"></i>Dokumen
                    </a>
                </li>
            </ul>
        </div>

        {{-- Tab Content --}}
        <div class="tab-content">
            {{-- Overview Tab --}}
            <div id="overview" class="tab-pane fade show active">
                <div class="row">
                    {{-- Recent Activity Timeline --}}
                    <div class="col-md-8">
                        <div class="panel">
                            <div class="panel-hdr">
                                <h2><i class="fas fa-clock text-primary mr-2"></i>Aktivitas Terbaru</h2>
                            </div>
                            <div class="panel-container show">
                                <div class="panel-content">
                                    <div class="timeline">
                                        @forelse($recentActivities as $activity)
                                            <div class="timeline-item">
                                                <div class="timeline-badge" style="background: {{ $activity['color'] }}">
                                                </div>
                                                <div class="timeline-content">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <div>
                                                            <h6 class="mb-1">{{ $activity['title'] }}</h6>
                                                            <p class="text-muted mb-1">{{ $activity['description'] }}</p>
                                                            <small class="text-muted">
                                                                <i class="far fa-clock mr-1"></i>{{ $activity['date'] }}
                                                            </small>
                                                        </div>
                                                        <span
                                                            class="badge badge-{{ $activity['badge'] }}">{{ $activity['type'] }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="text-center py-5">
                                                <i class="fas fa-inbox text-muted" style="font-size: 3rem;"></i>
                                                <p class="text-muted mt-3">Belum ada aktivitas</p>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Latest Vitals --}}
                    <div class="col-md-4">
                        <div class="panel">
                            <div class="panel-hdr">
                                <h2><i class="fas fa-heartbeat text-danger mr-2"></i>Vital Signs Terakhir</h2>
                            </div>
                            <div class="panel-container show">
                                <div class="panel-content">
                                    @if ($latestVitals)
                                        <div class="vitals-card">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="text-muted">Tekanan Darah</span>
                                                <span
                                                    class="vitals-value">{{ $latestVitals->blood_pressure ?? '-' }}</span>
                                            </div>
                                            <small class="text-muted">mmHg</small>
                                        </div>
                                        <div class="vitals-card">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="text-muted">Nadi</span>
                                                <span class="vitals-value">{{ $latestVitals->pulse ?? '-' }}</span>
                                            </div>
                                            <small class="text-muted">x/menit</small>
                                        </div>
                                        <div class="vitals-card">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="text-muted">Suhu</span>
                                                <span class="vitals-value">{{ $latestVitals->temperature ?? '-' }}</span>
                                            </div>
                                            <small class="text-muted">°C</small>
                                        </div>
                                        <div class="vitals-card">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="text-muted">Respirasi</span>
                                                <span class="vitals-value">{{ $latestVitals->respiration ?? '-' }}</span>
                                            </div>
                                            <small class="text-muted">x/menit</small>
                                        </div>
                                        <div class="text-center mt-3">
                                            <small class="text-muted">
                                                <i
                                                    class="far fa-clock mr-1"></i>{{ $latestVitals->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                    @else
                                        <div class="text-center py-5">
                                            <i class="fas fa-heartbeat text-muted" style="font-size: 3rem;"></i>
                                            <p class="text-muted mt-3">Belum ada data vital signs</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Charts Row --}}
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="chart-container">
                            <h5 class="mb-3"><i class="fas fa-chart-line text-info mr-2"></i>Grafik Kunjungan</h5>
                            <canvas id="visitsChart"></canvas>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="chart-container">
                            <h5 class="mb-3"><i class="fas fa-chart-pie text-success mr-2"></i>Distribusi Tindakan</h5>
                            <canvas id="proceduresChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- History Tab --}}
            <div id="history" class="tab-pane fade">
                <div class="panel">
                    <div class="panel-hdr">
                        <h2>Riwayat Kunjungan Lengkap</h2>
                        <div class="panel-toolbar">
                            <input type="text" class="form-control form-control-sm" placeholder="Cari..."
                                x-model="searchQuery" @input="filterHistory()">
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <div class="accordion accordion-custom" id="accordionHistory">
                                <template x-for="(reg, index) in filteredRegistrations" :key="reg.id">
                                    <div class="card">
                                        <div class="card-header" :id="'heading' + index">
                                            <h5 class="mb-0">
                                                <button
                                                    class="btn btn-link w-100 text-left d-flex justify-content-between align-items-center"
                                                    type="button" data-toggle="collapse"
                                                    :data-target="'#collapse' + index">
                                                    <div>
                                                        <strong x-text="reg.jenis_rawat"></strong>
                                                        <span class="text-muted ml-2" x-text="reg.date"></span>
                                                        <span class="badge badge-primary ml-2"
                                                            x-text="reg.departement"></span>
                                                    </div>
                                                    <i class="fas fa-chevron-down"></i>
                                                </button>
                                            </h5>
                                        </div>
                                        <div :id="'collapse' + index" class="collapse" :aria-labelledby="'heading' + index"
                                            data-parent="#accordionHistory">
                                            <div class="card-body">
                                                {{-- Registration Details --}}
                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <strong>No. Registrasi:</strong> <span
                                                            x-text="reg.registration_number"></span><br>
                                                        <strong>Dokter DPJP:</strong> <span x-text="reg.doctor"></span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>Penjamin:</strong> <span x-text="reg.penjamin"></span><br>
                                                        <strong>Status:</strong> <span class="badge badge-success"
                                                            x-text="reg.status"></span>
                                                    </div>
                                                </div>

                                                {{-- Additional details loaded via AJAX --}}
                                                <div :id="'details-' + reg.id" class="details-container">
                                                    <div class="text-center py-3">
                                                        <div class="spinner-border text-primary" role="status">
                                                            <span class="sr-only">Loading...</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Vitals Tab --}}
            <div id="vitals" class="tab-pane fade">
                <div class="panel">
                    <div class="panel-hdr">
                        <h2>Riwayat Vital Signs</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table id="vitalsTable" class="table table-bordered table-hover w-100">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Tekanan Darah</th>
                                        <th>Nadi</th>
                                        <th>Suhu</th>
                                        <th>Respirasi</th>
                                        <th>Berat Badan</th>
                                        <th>Tinggi Badan</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Lab Tab --}}
            <div id="lab" class="tab-pane fade">
                <div class="panel">
                    <div class="panel-hdr">
                        <h2>Riwayat Pemeriksaan Laboratorium</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table id="labTable" class="table table-bordered table-hover w-100">
                                <thead>
                                    <tr>
                                        <th>Tanggal Order</th>
                                        <th>No. Order</th>
                                        <th>Pemeriksaan</th>
                                        <th>Hasil</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Radiology Tab --}}
            <div id="radiology" class="tab-pane fade">
                <div class="panel">
                    <div class="panel-hdr">
                        <h2>Riwayat Pemeriksaan Radiologi</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table id="radiologyTable" class="table table-bordered table-hover w-100">
                                <thead>
                                    <tr>
                                        <th>Tanggal Order</th>
                                        <th>No. Order</th>
                                        <th>Pemeriksaan</th>
                                        <th>Hasil</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Documents Tab --}}
            <div id="documents" class="tab-pane fade">
                <div class="panel">
                    <div class="panel-hdr">
                        <h2>Dokumen Medis</h2>
                        <div class="panel-toolbar">
                            <button class="btn btn-primary btn-sm" data-toggle="modal"
                                data-target="#uploadDocumentModal">
                                <i class="fas fa-upload mr-2"></i>Upload Dokumen
                            </button>
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <div class="row">
                                @forelse($documents as $doc)
                                    <div class="col-md-3 mb-3">
                                        <div class="card">
                                            <div class="card-body text-center">
                                                <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
                                                <h6 class="card-title">{{ $doc->name }}</h6>
                                                <small class="text-muted">{{ $doc->created_at->format('d M Y') }}</small>
                                                <div class="mt-3">
                                                    <a href="{{ $doc->url }}" class="btn btn-sm btn-primary"
                                                        target="_blank">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ $doc->url }}" class="btn btn-sm btn-success"
                                                        download>
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-center py-5">
                                        <i class="fas fa-folder-open text-muted" style="font-size: 4rem;"></i>
                                        <p class="text-muted mt-3">Belum ada dokumen</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('plugin')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="//unpkg.com/alpinejs" defer></script>

    <script>
        function patientDashboard() {
            return {
                currentTab: 'overview',
                searchQuery: '',
                registrations: @json($registrations),
                filteredRegistrations: @json($registrations),

                init() {
                    this.initCharts();
                    this.initDataTables();
                },

                showSection(section) {
                    this.currentTab = section;
                    $(`a[href="#${section}"]`).tab('show');
                },

                filterHistory() {
                    const query = this.searchQuery.toLowerCase();
                    this.filteredRegistrations = this.registrations.filter(reg => {
                        return reg.registration_number.toLowerCase().includes(query) ||
                            reg.departement.toLowerCase().includes(query) ||
                            reg.doctor.toLowerCase().includes(query);
                    });
                },

                initCharts() {
                    // Visits Chart
                    const visitsCtx = document.getElementById('visitsChart');
                    if (visitsCtx) {
                        new Chart(visitsCtx, {
                            type: 'line',
                            data: {
                                labels: @json($chartData['visits']['labels']),
                                datasets: [{
                                    label: 'Kunjungan',
                                    data: @json($chartData['visits']['data']),
                                    borderColor: '#667eea',
                                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                                    tension: 0.4,
                                    fill: true
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: true,
                                plugins: {
                                    legend: {
                                        display: false
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            stepSize: 1
                                        }
                                    }
                                }
                            }
                        });
                    }

                    // Procedures Chart
                    const proceduresCtx = document.getElementById('proceduresChart');
                    if (proceduresCtx) {
                        new Chart(proceduresCtx, {
                            type: 'doughnut',
                            data: {
                                labels: @json($chartData['procedures']['labels']),
                                datasets: [{
                                    data: @json($chartData['procedures']['data']),
                                    backgroundColor: [
                                        '#667eea',
                                        '#28a745',
                                        '#ffc107',
                                        '#dc3545',
                                        '#17a2b8'
                                    ]
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: true,
                                plugins: {
                                    legend: {
                                        position: 'bottom'
                                    }
                                }
                            }
                        });
                    }
                },

                initDataTables() {
                    // Vitals Table
                    $('#vitalsTable').DataTable({
                        responsive: true,
                        processing: true,
                        serverSide: true,
                        ajax: '{{ route('patients.vitals.data', $pasien->id) }}',
                        columns: [{
                                data: 'date',
                                name: 'date'
                            },
                            {
                                data: 'blood_pressure',
                                name: 'blood_pressure'
                            },
                            {
                                data: 'pulse',
                                name: 'pulse'
                            },
                            {
                                data: 'temperature',
                                name: 'temperature'
                            },
                            {
                                data: 'respiration',
                                name: 'respiration'
                            },
                            {
                                data: 'weight',
                                name: 'weight'
                            },
                            {
                                data: 'height',
                                name: 'height'
                            }
                        ],
                        order: [
                            [0, 'desc']
                        ],
                        language: {
                            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
                        }
                    });

                    // Lab Table
                    $('#labTable').DataTable({
                        responsive: true,
                        processing: true,
                        serverSide: true,
                        ajax: '{{ route('patients.lab.data', $pasien->id) }}',
                        columns: [{
                                data: 'order_date',
                                name: 'order_date'
                            },
                            {
                                data: 'order_number',
                                name: 'order_number'
                            },
                            {
                                data: 'examination',
                                name: 'examination'
                            },
                            {
                                data: 'result',
                                name: 'result'
                            },
                            {
                                data: 'status',
                                name: 'status'
                            },
                            {
                                data: 'action',
                                name: 'action',
                                orderable: false,
                                searchable: false
                            }
                        ],
                        order: [
                            [0, 'desc']
                        ],
                        language: {
                            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
                        }
                    });

                    // Radiology Table
                    $('#radiologyTable').DataTable({
                        responsive: true,
                        processing: true,
                        serverSide: true,
                        ajax: '{{ route('patients.radiology.data', $pasien->id) }}',
                        columns: [{
                                data: 'order_date',
                                name: 'order_date'
                            },
                            {
                                data: 'order_number',
                                name: 'order_number'
                            },
                            {
                                data: 'examination',
                                name: 'examination'
                            },
                            {
                                data: 'result',
                                name: 'result'
                            },
                            {
                                data: 'status',
                                name: 'status'
                            },
                            {
                                data: 'action',
                                name: 'action',
                                orderable: false,
                                searchable: false
                            }
                        ],
                        order: [
                            [0, 'desc']
                        ],
                        language: {
                            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
                        }
                    });
                }
            }
        }

        // Load registration details when accordion is opened
        $(document).on('shown.bs.collapse', '.collapse', function() {
            const regId = $(this).attr('id').replace('collapse', '');
            const detailsContainer = $(`#details-${regId}`);

            if (!detailsContainer.data('loaded')) {
                $.ajax({
                    url: `/api/patients/registration-details/${regId}`,
                    method: 'GET',
                    success: function(response) {
                        detailsContainer.html(response.html);
                        detailsContainer.data('loaded', true);
                    },
                    error: function() {
                        detailsContainer.html(
                            '<div class="alert alert-danger">Gagal memuat data</div>');
                    }
                });
            }
        });
    </script>
@endsection
