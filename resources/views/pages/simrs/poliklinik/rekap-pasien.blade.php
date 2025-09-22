@extends('inc.layout')

@section('title', 'Laporan Rekap Pasien Per Poliklinik')

@section('extended-css')
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-800: #1e293b;
            --gray-900: #0f172a;
            --border-radius: 8px;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        }

        .filter-section {
            background: white;
            border: 1px solid var(--gray-200);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
        }

        .filter-form {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .filter-label {
            font-weight: 600;
            color: var(--gray-700);
            margin: 0;
        }

        .filter-input {
            border: 1px solid var(--gray-300);
            border-radius: var(--border-radius);
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .filter-input:focus {
            outline: 0;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgb(37 99 235 / 0.1);
        }

        .filter-submit {
            background: var(--primary-color);
            border: 1px solid var(--primary-color);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: var(--border-radius);
            font-weight: 500;
            transition: background-color 0.15s ease-in-out, border-color 0.15s ease-in-out;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .filter-submit:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .stats-section {
            background: white;
            border: 1px solid var(--gray-200);
            border-radius: var(--border-radius);
            padding: 1rem 1.5rem;
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
        }

        .stats-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--gray-600);
            font-size: 0.875rem;
        }

        .stat-value {
            font-weight: 600;
            color: var(--gray-900);
        }

        .data-table-container {
            background: white;
            border: 1px solid var(--gray-200);
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        .table-header {
            background: var(--gray-50);
            border-bottom: 1px solid var(--gray-200);
        }

        .table-header th {
            padding: 1rem;
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--gray-700);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border: none;
        }

        .table-body td {
            padding: 1rem;
            border-bottom: 1px solid var(--gray-100);
            vertical-align: middle;
        }

        .table-body tr:hover {
            background: var(--gray-50);
        }

        .department-name {
            font-weight: 600;
            color: var(--gray-900);
        }

        .patient-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 2rem;
            height: 2rem;
            padding: 0 0.75rem;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .patient-count.zero {
            background: var(--gray-100);
            color: var(--gray-600);
        }

        .patient-count.low {
            background: #fef3c7;
            color: #d97706;
        }

        .patient-count.high {
            background: #dbeafe;
            color: #2563eb;
        }

        .action-button {
            background: var(--primary-color);
            border: 1px solid var(--primary-color);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: var(--border-radius);
            font-weight: 500;
            font-size: 0.875rem;
            transition: background-color 0.15s ease-in-out, border-color 0.15s ease-in-out;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .action-button:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .action-button:disabled {
            background: var(--gray-300);
            border-color: var(--gray-300);
            color: var(--gray-500);
            cursor: not-allowed;
        }

        .patient-details-section {
            background: white;
            border: 1px solid var(--gray-200);
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            margin-top: 2rem;
        }

        .patient-details-header {
            background: var(--gray-50);
            border-bottom: 1px solid var(--gray-200);
            padding: 1rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .patient-details-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--gray-900);
            margin: 0;
        }

        .close-button {
            background: var(--gray-200);
            border: 1px solid var(--gray-300);
            color: var(--gray-600);
            width: 2rem;
            height: 2rem;
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color 0.15s ease-in-out, border-color 0.15s ease-in-out;
        }

        .close-button:hover {
            background: var(--gray-300);
            color: var(--gray-700);
        }

        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }

        .loading-content {
            text-align: center;
        }

        .loading-spinner {
            display: inline-block;
            width: 2rem;
            height: 2rem;
            border: 2px solid var(--gray-200);
            border-top: 2px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        .loading-text {
            margin-top: 0.75rem;
            color: var(--primary-color);
            font-weight: 600;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: var(--gray-500);
        }

        .empty-state-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.7;
        }

        .empty-state-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 0.5rem;
        }

        .empty-state-description {
            font-size: 0.875rem;
            margin: 0;
        }

        .patient-details-table {
            width: 100%;
            border-collapse: collapse;
        }

        .patient-details-table th {
            background: var(--gray-50);
            padding: 0.75rem 1rem;
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--gray-700);
            text-align: left;
            border-bottom: 1px solid var(--gray-200);
        }

        .patient-details-table td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--gray-100);
            vertical-align: middle;
        }

        .patient-details-table tr:hover {
            background: var(--gray-50);
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        @media (max-width: 768px) {
            .page-title {
                font-size: 1.5rem;
            }

            .filter-form {
                flex-direction: column;
                align-items: stretch;
            }

            .stats-content {
                flex-direction: column;
                align-items: stretch;
                gap: 0.75rem;
            }

            .patient-details-header {
                flex-direction: column;
                gap: 0.75rem;
                text-align: center;
            }
        }
    </style>
@endsection

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="container-fluid">
            <!-- Stats Section -->
            <div class="stats-section">
                <div class="stats-content">
                    <div class="stat-item">
                        <i class="fas fa-building"></i>
                        <span>Total Departemen: <span class="stat-value">{{ count($departements) }}</span></span>
                    </div>
                    <div class="stat-item">
                        <i class="fas fa-users"></i>
                        <span>Total Pasien: <span class="stat-value">{{ $departements->sum('jumlah_pasien') }}</span></span>
                    </div>
                </div>
            </div>

            <!-- Main Data Table -->
            <div class="row">
                <div class="col-12">
                    <div class="data-table-container">
                        <div class="table-responsive">
                            <table class="table mb-0" id="rekapTable">
                                <thead class="table-header">
                                    <tr>
                                        <th width="80" class="text-center">No</th>
                                        <th>Poliklinik</th>
                                        <th width="180" class="text-center">Jumlah Pasien</th>
                                        <th width="150" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="table-body">
                                    @forelse($departements as $index => $departement)
                                        <tr>
                                            <td class="text-center font-weight-bold">{{ $index + 1 }}</td>
                                            <td>
                                                <span class="department-name">{{ $departement['name'] }}</span>
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $jumlah = $departement['jumlah_pasien'];
                                                    $badgeClass =
                                                        $jumlah == 0 ? 'zero' : ($jumlah <= 10 ? 'low' : 'high');
                                                @endphp
                                                <span class="patient-count {{ $badgeClass }}">
                                                    {{ $jumlah }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @if ($departement['jumlah_pasien'] > 0)
                                                    <button class="action-button"
                                                        onclick="detailPasien({{ $departement['id'] }}, '{{ request('date', date('Y-m-d')) }}', '{{ $departement['name'] }}')">
                                                        <i class="fas fa-eye"></i>
                                                        <span>Detail</span>
                                                    </button>
                                                @else
                                                    <button class="action-button" disabled>
                                                        <i class="fas fa-eye"></i>
                                                        <span>Detail</span>
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4">
                                                <div class="empty-state">
                                                    <i class="fas fa-hospital-alt empty-state-icon"></i>
                                                    <h5 class="empty-state-title">Tidak ada data departemen</h5>
                                                    <p class="empty-state-description">Data departemen tidak tersedia untuk
                                                        tanggal yang dipilih.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Patient Details Section -->
            <div class="row" id="patientDetailsContainer" style="display: none;">
                <div class="col-12">
                    <div class="patient-details-section">
                        <div class="patient-details-header">
                            <h5 class="patient-details-title">
                                <i class="fas fa-users"></i>
                                <span>Detail Pasien - </span>
                                <span id="departmentName"></span>
                            </h5>
                            <button type="button" class="close-button" onclick="hidePatientDetails()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="p-4 position-relative">
                            <!-- Loading Overlay -->
                            <div class="loading-overlay" id="loadingOverlay" style="display: none;">
                                <div class="loading-content">
                                    <div class="loading-spinner"></div>
                                    <p class="loading-text">Memuat data pasien...</p>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="patient-details-table" id="patientDetailsTable">
                                    <thead>
                                        <tr>
                                            <th width="80" class="text-center">No</th>
                                            <th>No. Registrasi</th>
                                            <th>Nama Pasien</th>
                                            <th>No. RM</th>
                                            <th width="100" class="text-center">Umur</th>
                                            <th width="120" class="text-center">Jenis Kelamin</th>
                                            <th>Dokter</th>
                                            <th width="150" class="text-center">Tanggal</th>
                                            <th width="120" class="text-center">Waktu</th>
                                        </tr>
                                    </thead>
                                    <tbody id="patientDetailsBody">
                                        <!-- Patient data will be populated here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal Detail Pasien (removed) -->
    </div>
@endsection

@section('plugin')
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="/css/datagrid/datatables/datatables.bundle.css">

    <!-- DataTables JS -->
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable with simplified configuration
            $('#rekapTable').dataTable({
                paging: false,
                searching: false,
                ordering: false,
                info: false,
                lengthChange: true,
                pageLength: 10,
                responsive: true,
                columnDefs: [{
                    orderable: false,
                    targets: [3] // Disable sorting for Action column
                }],
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    }
                }
            });

            // Form submission with loading state
            $('form').on('submit', function() {
                const submitBtn = $(this).find('button[type="submit"]');
                const originalText = submitBtn.html();
                submitBtn.prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...');

                // Reset after 2 seconds (in case of slow response)
                setTimeout(() => {
                    submitBtn.prop('disabled', false).html(originalText);
                }, 2000);
            });
        });

        function detailPasien(departementId, date, departmentName) {
            // Show patient details container
            $('#departmentName').text(departmentName);
            $('#patientDetailsContainer').show();

            // Show loading overlay
            $('#loadingOverlay').show();

            // Scroll to the patient details section
            $('html, body').animate({
                scrollTop: $('#patientDetailsContainer').offset().top - 100
            }, 300);

            // Clear previous data
            $('#patientDetailsBody').html('');

            // Fetch patient data
            $.ajax({
                url: '/simrs/poliklinik/rekap-pasien/patient-details',
                type: 'POST',
                data: {
                    departement_id: departementId,
                    date: date,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    // Hide loading overlay
                    $('#loadingOverlay').hide();

                    if (response.success) {
                        populatePatientTable(response.data.patients);
                    } else {
                        showErrorMessage(response.message || 'Terjadi kesalahan saat memuat data');
                    }
                },
                error: function(xhr, status, error) {
                    // Hide loading overlay
                    $('#loadingOverlay').hide();

                    let errorMessage = 'Terjadi kesalahan saat memuat data';
                    if (xhr.status === 0) {
                        errorMessage = 'Tidak dapat terhubung ke server';
                    } else if (xhr.status === 404) {
                        errorMessage = 'Endpoint tidak ditemukan';
                    } else if (xhr.status === 500) {
                        errorMessage = 'Terjadi kesalahan pada server';
                    }

                    showErrorMessage(errorMessage);
                    console.log('Error:', error);
                }
            });
        }

        function populatePatientTable(patients) {
            let html = '';

            if (patients.length === 0) {
                html = `
                    <tr>
                        <td colspan="9">
                            <div class="empty-state">
                                <i class="fas fa-users empty-state-icon"></i>
                                <h5 class="empty-state-title">Tidak ada data pasien</h5>
                                <p class="empty-state-description">Tidak ada pasien yang terdaftar untuk poliklinik ini pada tanggal yang dipilih.</p>
                            </div>
                        </td>
                    </tr>
                `;
            } else {
                patients.forEach(function(patient, index) {
                    const genderText = patient.gender === 'L' ? 'Laki-laki' :
                        patient.gender === 'P' ? 'Perempuan' : '-';

                    html += `
                        <tr>
                            <td class="text-center font-weight-bold">${index + 1}</td>
                            <td>
                                <span class="font-weight-medium">${patient.registration_number || '-'}</span>
                            </td>
                            <td>
                                <span>${patient.patient_name || '-'}</span>
                            </td>
                            <td>
                                <span class="font-weight-medium">
                                    ${patient.medical_record_number || '-'}
                                </span>
                            </td>
                            <td class="text-center">
                                <span>
                                    ${patient.age !== null ? patient.age : '-'}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="font-weight-medium">${genderText}</span>
                            </td>
                            <td>
                                <span>${patient.doctor_name || '-'}</span>
                            </td>
                            <td class="text-center">
                                <small class="font-weight-bold">
                                    ${patient.registration_date}
                                </small>
                            </td>
                            <td class="text-center">
                                <small class="font-weight-bold">
                                    ${patient.registration_time || '-'}
                                </small>
                            </td>
                        </tr>
                    `;
                });
            }

            $('#patientDetailsBody').html(html);
        }

        function showErrorMessage(message) {
            $('#patientDetailsBody').html(`
                <tr>
                    <td colspan="9">
                        <div class="empty-state">
                            <i class="fas fa-exclamation-triangle empty-state-icon"></i>
                            <h5 class="empty-state-title">Terjadi Kesalahan</h5>
                            <p class="empty-state-description">${message}</p>
                        </div>
                    </td>
                </tr>
            `);
        }

        function hidePatientDetails() {
            $('#patientDetailsContainer').hide();
            $('#patientDetailsBody').html('');
            $('#loadingOverlay').hide();
        }
    </script>
@endsection
