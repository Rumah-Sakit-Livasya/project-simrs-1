@extends('inc.layout')
@section('title', 'Satu Sehat - Tenaga Medis')

@section('extended-css')
    <link rel="stylesheet" media="screen, print" href="/css/datagrid/datatables/datatables.bundle.css">
    <style>
        .btn-map {
            cursor: pointer;
        }

        .blinkme {
            animation: blinker 1.5s linear infinite;
        }

        @keyframes blinker {
            50% {
                opacity: 0.3;
            }
        }
    </style>
@endsection

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">Satu Sehat</a></li>
            <li class="breadcrumb-item active">Tenaga Medis</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
        </ol>

        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2><i class="fal fa-user-md mr-2"></i> Mapping Tenaga Medis (Practitioner)</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <!-- Navigasi Tab -->
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item"><a class="nav-link {{ $activeCategory == 'pegawai' ? 'active' : '' }}"
                                        href="{{ route('satu-sehat.practitioners', 'pegawai') }}">Pegawai</a></li>
                                <li class="nav-item"><a class="nav-link {{ $activeCategory == 'dokter' ? 'active' : '' }}"
                                        href="{{ route('satu-sehat.practitioners', 'dokter') }}">Dokter</a></li>
                                <li class="nav-item"><a class="nav-link {{ $activeCategory == 'bidan' ? 'active' : '' }}"
                                        href="{{ route('satu-sehat.practitioners', 'bidan') }}">Bidan</a></li>
                                <li class="nav-item"><a class="nav-link {{ $activeCategory == 'perawat' ? 'active' : '' }}"
                                        href="{{ route('satu-sehat.practitioners', 'perawat') }}">Perawat</a></li>
                                <li class="nav-item"><a
                                        class="nav-link {{ $activeCategory == 'therapist' ? 'active' : '' }}"
                                        href="{{ route('satu-sehat.practitioners', 'therapist') }}">Therapist/Analyst</a>
                                </li>
                                <li class="nav-item"><a
                                        class="nav-link {{ $activeCategory == 'radiografer' ? 'active' : '' }}"
                                        href="{{ route('satu-sehat.practitioners', 'radiografer') }}">Radiografer</a></li>
                                <li class="nav-item"><a
                                        class="nav-link {{ $activeCategory == 'apoteker' ? 'active' : '' }}"
                                        href="{{ route('satu-sehat.practitioners', 'apoteker') }}">Apoteker</a></li>
                            </ul>
                            <!-- Konten Tabel -->
                            <div class="tab-content border border-top-0 p-3">
                                <div class="tab-pane fade show active" role="tabpanel">
                                    <table id="dt-practitioners"
                                        class="table table-bordered table-hover table-striped w-100">
                                        <thead class="bg-primary-600">
                                            <tr>
                                                <th>Nama Lengkap</th>
                                                <th>TTL</th>
                                                <th class="text-center">Jenis Kelamin</th>
                                                <th>ID Nakes</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($employees as $employee)
                                                <tr id="row-{{ $employee->id }}">
                                                    <td>
                                                        <div class="fw-500">{{ $employee->fullname }}</div>
                                                        <small
                                                            class="text-muted d-block">{{ $employee->jobPosition->name ?? 'Jabatan tidak diatur' }}</small>
                                                    </td>
                                                    <td>
                                                        {{ $employee->place_of_birth }} <br>
                                                        {{ \Carbon\Carbon::parse($employee->birthdate)->format('d-m-Y') }}
                                                    </td>
                                                    <td class="text-center">{{ $employee->gender }}</td>
                                                    <td class="practitioner-id-cell">
                                                        @if ($employee->satu_sehat_practitioner_id)
                                                            {{ $employee->satu_sehat_practitioner_id }}
                                                        @else
                                                            <span class="text-danger blinkme">Nakes Belum Mapping</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <span
                                                            class="badge badge-{{ $employee->is_active ? 'success' : 'danger' }}">
                                                            {{ $employee->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        <button class="btn btn-sm btn-icon btn-success btn-map"
                                                            data-id="{{ $employee->id }}"
                                                            data-name="{{ $employee->fullname }}" title="Mapping Nakes"
                                                            {{ $employee->satu_sehat_practitioner_id ? 'disabled' : '' }}>
                                                            <i
                                                                class="fas fa-{{ $employee->satu_sehat_practitioner_id ? 'check' : 'share-square' }}"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script>
        $(document).ready(function() {
            $('#dt-practitioners').DataTable({
                responsive: true,
                pageLength: 50,
            });

            $('#dt-practitioners').on('click', '.btn-map', function() {
                let button = $(this);
                let empId = button.data('id');
                let empName = button.data('name');
                let url = `{{ url('satu-sehat/practitioners') }}/${empId}/map`;

                Swal.fire({
                    title: 'Apakah Anda Yakin?',
                    text: `Anda akan me-mapping Nakes "${empName}".`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Lanjutkan!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "POST",
                            url: url,
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            dataType: "json",
                            beforeSend: function() {
                                button.prop('disabled', true).html(
                                    '<i class="fas fa-spinner fa-spin"></i>');
                            },
                            success: function(data) {
                                showSuccessAlert(data.text);
                                button.html('<i class="fas fa-check"></i>');
                                let row = button.closest('tr');
                                row.find('.practitioner-id-cell').html(data
                                    .practitioner_id).removeClass(
                                    'text-danger blinkme');
                                row.addClass('table-success');
                            },
                            error: function(jqXHR) {
                                button.prop('disabled', false).html(
                                    '<i class="fas fa-share-square"></i>');
                                let errorMsg = jqXHR.responseJSON ? jqXHR.responseJSON
                                    .text : 'Terjadi kesalahan koneksi.';
                                showErrorAlert(errorMsg);
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
