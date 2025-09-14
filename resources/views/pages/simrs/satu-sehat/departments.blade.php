@extends('inc.layout')
@section('title', 'Satu Sehat Department')

@section('extended-css')
    <link rel="stylesheet" media="screen, print" href="/css/datagrid/datatables/datatables.bundle.css">
    <style>
        .btn-map {
            cursor: pointer;
        }
    </style>
@endsection

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">Satu Sehat</a></li>
            <li class="breadcrumb-item active">Department</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
        </ol>

        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2><i class="fal fa-hospital-user mr-2"></i> Mapping Department</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <!-- Navigasi Tab -->
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item"><a
                                        class="nav-link {{ $activeCategory == 'poliklinik' ? 'active' : '' }}"
                                        href="{{ route('satu-sehat.departments', 'poliklinik') }}">POLIKLINIK</a></li>
                                <li class="nav-item"><a
                                        class="nav-link {{ $activeCategory == 'penunjang_medis' ? 'active' : '' }}"
                                        href="{{ route('satu-sehat.departments', 'penunjang_medis') }}">PENUNJANG MEDIS</a>
                                </li>
                                <li class="nav-item"><a class="nav-link {{ $activeCategory == 'lainnya' ? 'active' : '' }}"
                                        href="{{ route('satu-sehat.departments', 'lainnya') }}">LAINNYA</a></li>
                            </ul>
                            <!-- Konten Tabel -->
                            <div class="tab-content border border-top-0 p-3">
                                <div class="tab-pane fade show active" role="tabpanel">
                                    <table id="dt-departments" class="table table-bordered table-hover table-striped w-100">
                                        <thead class="bg-primary-600">
                                            <tr>
                                                <th>Kode</th>
                                                <th>Nama Department</th>
                                                <th>Keterangan</th>
                                                <th>Organization ID</th>
                                                <th class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{-- Loop melalui variabel $departments dari controller --}}
                                            @foreach ($departments as $dept)
                                                <tr id="row-{{ $dept->id }}">
                                                    <td>{{ $dept->kode }}</td>
                                                    <td>{{ $dept->name }}</td>
                                                    <td>{{ $dept->keterangan }}</td>
                                                    {{-- Gunakan kolom baru kita --}}
                                                    <td class="org-id-cell">
                                                        {{ $dept->satu_sehat_organization_id ?? 'Belum di-mapping' }}</td>
                                                    <td class="text-center">
                                                        <button class="btn btn-sm btn-icon btn-success btn-map"
                                                            data-id="{{ $dept->id }}" data-name="{{ $dept->name }}"
                                                            title="Mapping Department" {{-- Cek kolom baru untuk menonaktifkan tombol --}}
                                                            {{ $dept->satu_sehat_organization_id ? 'disabled' : '' }}>
                                                            <i
                                                                class="fas fa-{{ $dept->satu_sehat_organization_id ? 'check' : 'share-square' }}"></i>
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
            $('#dt-departments').DataTable({
                responsive: true,
                pageLength: 25,
            });

            $('#dt-departments').on('click', '.btn-map', function() {
                let button = $(this);
                let deptId = button.data('id');
                let deptName = button.data('name');
                // URL disesuaikan dengan route baru
                let url = `{{ url('satu-sehat/departments') }}/${deptId}/map`;

                Swal.fire({
                    title: 'Apakah Anda Yakin?',
                    text: `Anda akan me-mapping department "${deptName}".`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Lanjutkan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "POST",
                            url: url,
                            dataType: "json",
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            beforeSend: function() {
                                button.prop('disabled', true).html(
                                    '<i class="fas fa-spinner fa-spin"></i>');
                            },
                            success: function(data) {
                                showSuccessAlert(data.text);
                                button.html('<i class="fas fa-check"></i>');
                                let row = button.closest('tr');
                                row.find('.org-id-cell').text(data.organization_id);
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
