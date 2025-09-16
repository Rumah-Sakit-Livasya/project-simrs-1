@extends('inc.layout')
@section('title', 'Satu Sehat - Mapping Lokasi Department')

{{-- Bagian @section('extended-css') tidak berubah --}}
@section('extended-css')
    <link rel="stylesheet" media="screen, print" href="/css/datagrid/datatables/datatables.bundle.css">
    <style>
        .btn-map {
            cursor: pointer;
        }

        .badge-status {
            color: white;
        }
    </style>
@endsection

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        {{-- Breadcrumb tidak berubah --}}
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">Satu Sehat</a></li>
            <li class="breadcrumb-item active">Lokasi Department</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
        </ol>

        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2><i class="fal fa-map-marker-alt mr-2"></i> Mapping Department ke Lokasi Fisik</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <!-- Navigasi Tab - DIPERBARUI -->
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link {{ $activeCategory == 'rawat_jalan' ? 'active' : '' }}"
                                        href="{{ route('satu-sehat.department-locations', 'rawat_jalan') }}">RAWAT JALAN</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ $activeCategory == 'rawat_inap' ? 'active' : '' }}"
                                        href="{{ route('satu-sehat.department-locations', 'rawat_inap') }}">RAWAT INAP</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ $activeCategory == 'penunjang_medis' ? 'active' : '' }}"
                                        href="{{ route('satu-sehat.department-locations', 'penunjang_medis') }}">PENUNJANG
                                        MEDIS</a>
                                </li>
                            </ul>

                            {{-- Konten tabel dan isinya tidak perlu diubah --}}
                            <div class="tab-content border border-top-0 p-3">
                                <div class="tab-pane fade show active" role="tabpanel">
                                    <table id="dt-dept-locations"
                                        class="table table-bordered table-hover table-striped w-100">
                                        {{-- ... thead dan tbody tetap sama seperti sebelumnya ... --}}
                                        <thead class="bg-primary-600">
                                            <tr>
                                                <th>Nama Department</th>
                                                <th class="text-center">Mode</th>
                                                <th class="text-center">Tipe</th>
                                                <th>Location ID</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($departments as $dept)
                                                <tr id="row-{{ $dept->id }}">
                                                    <td>{{ $dept->name }}</td>
                                                    <td class="text-center">
                                                        {{ strtoupper($dept->location_mode ?? 'INSTANCE') }}</td>
                                                    <td class="text-center">
                                                        {{ strtoupper($dept->location_physical_type ?? 'RO') }} - ROOM</td>
                                                    <td class="loc-id-cell">
                                                        {{ $dept->satu_sehat_location_id ?? 'Belum di-mapping' }}</td>
                                                    <td class="text-center">
                                                        @if ($dept->location_status == 'active')
                                                            <span class="badge badge-success badge-status">ACTIVE</span>
                                                        @else
                                                            <span
                                                                class="badge badge-warning badge-status">{{ strtoupper($dept->location_status) }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <button class="btn btn-sm btn-icon btn-success btn-map"
                                                            data-id="{{ $dept->id }}" data-name="{{ $dept->name }}"
                                                            title="Mapping Lokasi"
                                                            {{ $dept->satu_sehat_location_id ? 'disabled' : '' }}>
                                                            <i
                                                                class="fas fa-{{ $dept->satu_sehat_location_id ? 'check' : 'share-square' }}"></i>
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

{{-- Bagian @section('plugin') tidak berubah --}}
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script>
        $(document).ready(function() {
            $('#dt-dept-locations').DataTable({
                responsive: true,
                pageLength: 25,
            });

            $('#dt-dept-locations').on('click', '.btn-map', function() {
                let button = $(this);
                let deptId = button.data('id');
                let deptName = button.data('name');
                let url = `{{ url('satu-sehat/department-locations') }}/${deptId}/map`;

                Swal.fire({
                    title: 'Apakah Anda Yakin?',
                    text: `Anda akan me-mapping "${deptName}" sebagai Lokasi Fisik.`,
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
                                row.find('.loc-id-cell').text(data.location_id);
                                row.find('.badge-status').removeClass('badge-warning')
                                    .addClass('badge-success').text('ACTIVE');
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
