@extends('inc.layout')
@section('title', 'Role')
@section('extended-css')
    {{-- Tambahkan sedikit style untuk badge permission --}}
    <style>
        .permission-badge {
            margin: 2px;
            font-size: 0.8rem;
            font-weight: 500;
        }
    </style>
@endsection
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        {{-- ... (bagian atas tetap sama) ... --}}

        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Tabel <span class="fw-300"><i>Roles</i></span>
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                                <thead>
                                    <tr>
                                        <th style="white-space: nowrap">No</th>
                                        <th style="white-space: nowrap">Nama Role</th>
                                        <th style="white-space: nowrap">Guard Name</th>
                                        {{-- 1. TAMBAHKAN KOLOM BARU DI HEADER --}}
                                        <th class="w-50">Permissions</th>
                                        <th style="white-space: nowrap">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($roles as $role)
                                        <tr>
                                            <td style="white-space: nowrap">{{ $loop->iteration }}</td>
                                            <td style="white-space: nowrap">
                                                {{ ucfirst($role->name) }}
                                            </td>
                                            <td style="white-space: nowrap">{{ $role->guard_name }}</td>

                                            {{-- 2. TAMBAHKAN KOLOM BARU DI BODY UNTUK MENAMPILKAN PERMISSION --}}
                                            <td>
                                                {{-- Loop melalui permissions yang sudah kita eager load --}}
                                                @forelse ($role->permissions as $permission)
                                                    <span
                                                        class="badge badge-info permission-badge">{{ $permission->name }}</span>
                                                @empty
                                                    <span class="text-muted">No permissions assigned</span>
                                                @endforelse
                                            </td>

                                            <td style="white-space: nowrap">
                                                {{-- ... (Tombol aksi tetap sama) ... --}}
                                                <button type="button" data-backdrop="static" data-keyboard="false"
                                                    class="badge mx-1 btn-edit badge-primary p-2 border-0 text-white"
                                                    data-id="{{ $role->id }}" title="Ubah">
                                                    <span class="fal fa-pencil ikon-edit"></span>
                                                </button>
                                                <button type="button" data-backdrop="static" data-keyboard="false"
                                                    class="badge mx-1 badge-danger p-2 border-0 text-white btn-hapus"
                                                    data-id="{{ $role->id }}" title="Hapus">
                                                    <span class="fal fa-trash ikon-hapus"></span>
                                                </button>
                                                <a href="{{ route('roles.assignPermissions', $role->id) }}"
                                                    class="badge mx-1 badge-warning p-2 border-0 text-white"
                                                    title="Assign Permissions">
                                                    <span class="fal fa-universal-access"></span>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th style="white-space: nowrap">No</th>
                                        <th style="white-space: nowrap">Nama Role</th>
                                        <th style="white-space: nowrap">Guard Name</th>
                                        {{-- 3. TAMBAHKAN KOLOM BARU DI FOOTER --}}
                                        <th>Permissions</th>
                                        <th style="white-space: nowrap">Aksi</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('pages.master-data.role.partials.create-role')
        @include('pages.master-data.role.partials.update-role')
        {{-- Anda tidak perlu meng-include modal assign-permissions lagi di sini --}}
        {{-- @include('pages.master-data.role.partials.assign-permissions') --}}
    </main>
@endsection
@section('plugin')
    {{-- Script JavaScript Anda tidak perlu diubah sama sekali --}}
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(document).ready(function() {
            // ... (SEMUA KODE JS ANDA TETAP SAMA)
            let roleId;

            const dt = $('#dt-basic-example').DataTable({
                responsive: true
            });

            // Handle CREATE
            $('#store-form').on('submit', function(e) {
                e.preventDefault();
                const button = $(this).find('button[type="submit"]');
                const formData = $(this).serialize();

                $.ajax({
                    type: "POST",
                    url: '/api/dashboard/role/store',
                    data: formData,
                    beforeSend: function() {
                        button.prop('disabled', true).find('.fal').hide().parent().append(
                            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
                        );
                    },
                    success: function(response) {
                        $('#tambah-role').modal('hide');
                        showSuccessAlert(response.message);
                        setTimeout(() => location.reload(), 1500);
                    },
                    error: function(xhr) {
                        showErrorAlertNoRefresh('Gagal menyimpan: ' + xhr.responseJSON.message);
                    }
                }).always(function() {
                    button.prop('disabled', false).find('.fal').show().parent().find(
                        '.spinner-border').remove();
                });
            });

            // Handle EDIT button click
            $('#dt-basic-example').on('click', '.btn-edit', function() {
                roleId = $(this).data('id');
                $.ajax({
                    type: "GET",
                    url: `/api/dashboard/role/get/${roleId}`,
                    success: function({
                        role
                    }) {
                        $('#update_name').val(role.name);
                        $('#update_guard_name').val(role.guard_name);
                        $('#ubah-role').modal('show');
                    },
                    error: function() {
                        showErrorAlertNoRefresh('Gagal memuat data role.');
                    }
                });
            });

            // Handle UPDATE form submission
            $('#update-form').on('submit', function(e) {
                e.preventDefault();
                const button = $(this).find('button[type="submit"]');
                const formData = $(this).serialize();

                $.ajax({
                    type: "PUT",
                    url: '/api/dashboard/role/update/' + roleId,
                    data: formData,
                    beforeSend: function() {
                        button.prop('disabled', true).find('.fal').hide().parent().append(
                            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
                        );
                    },
                    success: function(response) {
                        $('#ubah-role').modal('hide');
                        showSuccessAlert(response.message);
                        setTimeout(() => location.reload(), 1500);
                    },
                    error: function(xhr) {
                        showErrorAlertNoRefresh('Gagal memperbarui: ' + xhr.responseJSON
                            .message);
                    }
                }).always(function() {
                    button.prop('disabled', false).find('.fal').show().parent().find(
                        '.spinner-border').remove();
                });
            });

            // Handle DELETE button click
            $('#dt-basic-example').on('click', '.btn-hapus', function() {
                roleId = $(this).data('id');
                showDeleteConfirmation(function() {
                    $.ajax({
                        type: "DELETE",
                        url: `/api/dashboard/role/delete/${roleId}`,
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            showSuccessAlert(response.message);
                            setTimeout(() => location.reload(), 1500);
                        },
                        error: function(xhr) {
                            showErrorAlertNoRefresh('Gagal menghapus: ' + xhr
                                .responseJSON.message);
                        }
                    });
                });
            });
        });
    </script>
@endsection
