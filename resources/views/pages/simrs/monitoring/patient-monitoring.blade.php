@extends('inc.layout')

@section('title', 'Monitoring Pasien')

@section('extended-css')
    <style>
        /* --------------------
                                                               General Styles
                                                            -------------------- */
        body {
            font-size: 0.9rem;
        }

        /* --------------------
                                                               Filter Section
                                                            -------------------- */
        .filter-section {
            background: #ffffff;
            border: 1px solid #e3e6f0;
            border-radius: 0.5rem;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        .filter-section label {
            font-weight: 600;
            font-size: 0.85rem;
        }

        /* --------------------
                                                               Statistic Cards
                                                            -------------------- */
        .stat-card {
            border-radius: 0.5rem;
            transition: all 0.3s;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            padding: 1rem;
            text-align: center;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .stat-card .h5 {
            font-size: 1.75rem;
            margin: 0;
        }

        .stat-card .text-muted {
            font-size: 0.75rem;
        }

        /* --------------------
                                                               Monitoring Table
                                                            -------------------- */
        #monitoring-table {
            border-collapse: separate;
            border-spacing: 0 0.5rem;
        }

        #monitoring-table thead th {
            position: sticky;
            top: 0;
            background: #f8f9fa;
            font-weight: 600;
            font-size: 0.8rem;
            text-align: center;
        }

        #monitoring-table tbody tr {
            background: #fff;
            transition: all 0.2s;
            border-radius: 0.5rem;
        }

        #monitoring-table tbody tr:hover {
            background: #f1f3f5;
        }

        #monitoring-table td,
        #monitoring-table th {
            vertical-align: middle;
            padding: 0.5rem 0.75rem;
        }

        .patient-info b,
        .doctor-info b {
            display: block;
        }

        .progress-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: bold;
            color: #fff;
        }

        .progress-circle.bg-success {
            background: #28a745;
        }

        .progress-circle.bg-warning {
            background: #ffc107;
            color: #343a40;
        }

        .progress-circle.bg-danger {
            background: #dc3545;
        }

        /* --------------------
                                                               Modal
                                                            -------------------- */
        #detailModal .modal-content {
            border-radius: 0.75rem;
            overflow: hidden;
        }

        #detailModal .progress {
            height: 1.5rem;
            border-radius: 0.5rem;
        }

        #detailModal .card {
            border-radius: 0.5rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        #detailModal .badge {
            font-size: 0.75rem;
        }
    </style>
@endsection

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-12">

                {{-- Filter Section --}}
                <div class="filter-section d-flex flex-wrap align-items-end justify-content-between">
                    <div class="form-group mr-3">
                        <label for="date_filter">Tanggal</label>
                        <input type="date" id="date_filter" class="form-control" value="{{ $date }}">
                    </div>
                    <div class="form-group mr-3">
                        <label for="departement_filter">Departemen</label>
                        <select id="departement_filter" class="form-control select2bs4" style="width:200px;">
                            <option value="">Semua</option>
                            @foreach ($departements as $dep)
                                <option value="{{ $dep->id }}" {{ $departementId == $dep->id ? 'selected' : '' }}>
                                    {{ $dep->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mr-3">
                        <label for="doctor_filter">Dokter</label>
                        <select id="doctor_filter" class="form-control select2bs4" style="width:200px;">
                            <option value="">Semua</option>
                            @foreach ($doctors as $doc)
                                <option value="{{ $doc->id }}" {{ $doctorId == $doc->id ? 'selected' : '' }}>
                                    {{ $doc->name ?? 'N/A' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="ml-auto">
                        <button class="btn btn-primary mr-2" id="filter_btn"><i
                                class="fas fa-filter mr-1"></i>Filter</button>
                        <button class="btn btn-outline-secondary" id="refresh_btn"><i
                                class="fas fa-sync-alt mr-1"></i>Refresh</button>
                    </div>
                </div>

                {{-- Statistik Section --}}
                <div class="row my-4" id="stats-container"></div>

                {{-- Monitoring Table --}}
                <div class="table-responsive">
                    <table id="monitoring-table" class="table table-hover">
                        <thead>
                            <tr>
                                <th rowspan="2">No</th>
                                <th rowspan="2">Pasien</th>
                                <th rowspan="2">Dokter / Unit</th>
                                <th rowspan="2">Penjamin</th>
                                <th colspan="14" class="text-center">Tahapan Monitoring</th>
                                <th rowspan="2">Progress</th>
                                <th rowspan="2">Aksi</th>
                            </tr>
                            <tr>
                                <th>P. Awal</th>
                                <th>P. Dokter</th>
                                <th>Resume</th>
                                <th>Diagnosa</th>
                                <th>Tindakan</th>
                                <th>Resep</th>
                                <th>Obat/Alkes</th>
                                <th>BHP</th>
                                <th>Lab</th>
                                <th>Rad</th>
                                <th>Fisio</th>
                                <th>Hemo</th>
                                <th>Keluar</th>
                                <th>Tagihan</th>
                            </tr>
                        </thead>
                        <tbody id="monitoring-tbody">
                            <tr>
                                <td colspan="20" class="text-center text-muted">Memuat data...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        {{-- Modal --}}
        <div class="modal fade" id="detailModal" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title"><i class="fas fa-info-circle mr-2"></i>Detail Monitoring Pasien</h5>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body" id="detailModalBody">
                        <div class="text-center text-muted">Memuat detail...</div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('plugin')
    {{-- Pastikan Anda sudah memuat jQuery dan Bootstrap JS, serta Select2 dan Popper.js jika digunakan --}}
    {{-- Contoh:
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2-bootstrap4-theme@1.0.0/dist/select2-bootstrap4.min.js"></script>
    --}}
    <script>
        $(function() {
            // Initialize Select2 for filter dropdowns if you have the plugin
            if ($.fn.select2) {
                $('.select2bs4').select2({
                    theme: 'bootstrap4',
                    placeholder: $(this).data('placeholder') || 'Pilih...',
                    allowClear: true
                });
            }

            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();

            // Initial load
            loadMonitoringData();
            loadStatistics();

            // Event listeners for filters and buttons
            $('#filter_btn').on('click', function() {
                loadMonitoringData();
                loadStatistics();
            });

            $('#refresh_btn').on('click', function() {
                // Clear filters and reload
                $('#date_filter').val("{{ date('Y-m-d') }}"); // Reset to current date or initial value
                $('#departement_filter').val('').trigger('change'); // Reset Select2
                $('#doctor_filter').val('').trigger('change'); // Reset Select2
                loadMonitoringData();
                loadStatistics();
            });

            $('#date_filter, #departement_filter, #doctor_filter').on('change', function() {
                // Auto-apply filter on change for dropdowns/date
                loadMonitoringData();
                loadStatistics();
            });
        });

        // ===============================
        // Load Data Monitoring
        // ===============================
        function loadMonitoringData() {
            $('#monitoring-tbody').html(
                '<tr><td colspan="20" class="text-center text-muted"><i class="fas fa-spinner fa-spin mr-2"></i>Memuat data...</td></tr>'
            );
            $.get("{{ url('simrs/poliklinik/patient-monitoring/data') }}", {
                date: $('#date_filter').val(),
                departement_id: $('#departement_filter').val(),
                doctor_id: $('#doctor_filter').val()
            }, function(res) {
                if (res.success) renderMonitoringTable(res.data);
                else $('#monitoring-tbody').html(
                    `<tr><td colspan="20" class="text-center text-danger"><i class="fas fa-exclamation-circle mr-2"></i>${res.message}</td></tr>`
                );
            }).fail(() => $('#monitoring-tbody').html(
                '<tr><td colspan="20" class="text-center text-danger"><i class="fas fa-exclamation-triangle mr-2"></i>Gagal memuat data</td></tr>'
            ));
        }

        // ===============================
        // Render Table Monitoring
        // ===============================
        function renderMonitoringTable(data) {
            if (!data.length) {
                $('#monitoring-tbody').html(
                    '<tr><td colspan="20" class="text-center text-info"><i class="fas fa-info-circle mr-2"></i>Tidak ada data untuk filter ini.</td></tr>'
                );
                return;
            }
            let html = '';
            data.forEach((reg, i) => {
                html += `<tr>
                    <td class="text-center">${i+1}</td>
                    <td class="patient-info" style="white-space: nowrap;"><b>${reg.patient_name}</b><small class="text-muted">${reg.medical_record_number}</small></td>
                    <td class="doctor-info" style="white-space: nowrap;"><b>${reg.doctor_name}</b><small class="text-muted">${reg.departement_name}</small></td>
                    <td class="doctor-info" style="white-space: nowrap;">${reg.penjamin_name}</td>`;
                Object.keys(reg.stages).forEach(k => {
                    let s = reg.stage_status[k];
                    html +=
                        `<td class="text-center"><span class="badge badge-status ${getStatusClass(s)}" data-toggle="tooltip" title="${s}">${getStatusIcon(s)} ${s.replace('_', ' ')}</span></td>`;
                });
                html += `<td class="text-center">
                    <div class="progress-circle ${getProgressClass(reg.completion_percentage)}">
                        ${reg.completion_percentage}%
                    </div>
                </td>
                <td class="text-center">
                    <button class="btn btn-sm btn-info" onclick="showDetail(${reg.registration_id})" data-toggle="tooltip" title="Lihat Detail">
                        <i class="fas fa-eye"></i>
                    </button>
                </td>
                </tr>`;
            });
            $('#monitoring-tbody').html(html);
            $('[data-toggle="tooltip"]').tooltip(); // Re-initialize tooltips for new elements
        }

        // ===============================
        // Load Statistik
        // ===============================
        function loadStatistics() {
            $('#stats-container').html(
                `<div class="col-12 text-center text-muted"><i class="fas fa-spinner fa-spin mr-2"></i>Memuat statistik...</div>`
            );
            $.get("{{ url('simrs/poliklinik/patient-monitoring/stats') }}", {
                date: $('#date_filter').val(),
                departement_id: $('#departement_filter').val()
            }, function(res) {
                if (res.success) {
                    let html = '';
                    // Define colors for each stat based on label or index if needed
                    const statColors = {
                        'Total Pasien': 'primary',
                        'Selesai': 'success',
                        'Dalam Proses': 'warning',
                        'Belum Dimulai': 'danger'
                    };
                    const statIcons = {
                        'Total Pasien': 'users',
                        'Selesai': 'check-circle',
                        'Dalam Proses': 'clock',
                        'Belum Dimulai': 'times-circle'
                    };

                    res.data.forEach(stat => {
                        const colorClass = statColors[stat.label] || 'secondary'; // Fallback color
                        const iconClass = statIcons[stat.label] || 'chart-bar'; // Fallback icon
                        html += `<div class="col-md-3 mt-3">
                            <div class="card stat-card text-center p-3 shadow-sm border-left-${colorClass}">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-${colorClass} mb-1">
                                                ${stat.label}
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">${stat.value}</div>
                                            <small class="text-muted">${stat.percentage}% dari total</small>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-${iconClass} fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>`;
                    });
                    $('#stats-container').html(html);
                } else {
                    $('#stats-container').html(
                        `<div class="col-12 text-center text-danger"><i class="fas fa-exclamation-circle mr-2"></i>${res.message}</div>`
                    );
                }
            }).fail(() => {
                $('#stats-container').html(
                    '<div class="col-12 text-center text-danger"><i class="fas fa-exclamation-triangle mr-2"></i>Gagal memuat statistik</div>'
                );
            });
        }

        // ===============================
        // Modal Detail
        // ===============================
        function showDetail(id) {
            $('#detailModalBody').html(
                '<div class="text-center text-muted"><i class="fas fa-spinner fa-spin mr-2"></i>Memuat detail...</div>');
            $('#detailModal').modal('show');

            $.get("{{ url('simrs/poliklinik/patient-monitoring/detail') }}", {
                registration_id: id
            }, function(res) {
                if (res.success) {
                    let d = res.data,
                        html = '';
                    html += `<div class="row">
                        <div class="col-md-6">
                            <div class="card card-body p-3 shadow-sm mb-3">
                                <h6 class="border-bottom pb-2 mb-3"><i class="fas fa-user-injured mr-2"></i> Informasi Pasien</h6>
                                <p><b>Nama:</b> ${d.patient_name}</p>
                                <p><b>No. RM:</b> ${d.medical_record_number}</p>
                                <p><b>Dokter:</b> ${d.doctor_name}</p>
                                <p><b>Departemen:</b> ${d.departement_name}</p>
                                <p><b>Tanggal Registrasi:</b> ${d.registration_date}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-body p-3 shadow-sm mb-3">
                                <h6 class="border-bottom pb-2 mb-3"><i class="fas fa-chart-line mr-2"></i> Status Progress</h6>
                                <p class="mb-1"><b>Completion:</b> <span class="float-right font-weight-bold text-info">${d.completion_percentage}%</span></p>
                                <div class="progress mb-2" style="height: 20px;">
                                    <div class="progress-bar ${getProgressClass(d.completion_percentage)}" role="progressbar" style="width:${d.completion_percentage}%" aria-valuenow="${d.completion_percentage}" aria-valuemin="0" aria-valuemax="100">
                                        ${d.completion_percentage}%
                                    </div>
                                </div>
                                <small class="text-muted">${d.completed_stages} dari ${d.total_stages} tahap selesai</small>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="card card-body p-3 shadow-sm">
                            <h6 class="border-bottom pb-2 mb-3"><i class="fas fa-list-ul mr-2"></i> Detail Tahapan</h6>
                            <table class="table table-sm table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Tahap</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>`;
                    Object.keys(d.stages).forEach(k => {
                        let s = d.stage_status[k];
                        html +=
                            `<tr>
                                <td>${d.stages[k]}</td>
                                <td class="text-center"><span class="badge ${getStatusClass(s)}">${getStatusIcon(s)} ${s.replace('_', ' ')}</span></td>
                            </tr>`;
                    });
                    html += '</tbody></table></div></div>';
                    $('#detailModalBody').html(html);
                    $('[data-toggle="tooltip"]').tooltip(); // Re-initialize tooltips for new elements
                } else {
                    $('#detailModalBody').html(
                        `<div class="text-center text-danger"><i class="fas fa-exclamation-circle mr-2"></i>${res.message}</div>`
                    );
                }
            }).fail(() => {
                $('#detailModalBody').html(
                    '<div class="text-center text-danger"><i class="fas fa-exclamation-triangle mr-2"></i>Gagal memuat detail pasien.</div>'
                );
            });
        }

        // ===============================
        // Helper Functions
        // ===============================
        function getStatusClass(s) {
            return s === 'completed' ? 'badge-success' : s === 'in_progress' ? 'badge-warning' : 'badge-secondary';
        }

        function getStatusIcon(s) {
            return s === 'completed' ? '<i class="fas fa-check-circle"></i>' : s === 'in_progress' ?
                '<i class="fas fa-hourglass-half"></i>' : '<i class="fas fa-times-circle"></i>';
        }

        function getProgressClass(p) {
            return p >= 80 ? 'bg-success' : p >= 50 ? 'bg-warning' : 'bg-danger';
        }
    </script>
@endsection
