@extends('inc.layout')
@section('title', 'User')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row mb-5">
            <div class="col-xl-12">
                <button type="button" class="btn btn-primary waves-effect waves-themed" data-backdrop="static"
                    data-keyboard="false" data-toggle="modal" data-target="#tambah-user" title="Tambah User">
                    <span class="fal fa-plus-circle mr-1"></span>
                    Tambah User
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Table <span class="fw-300"><i>User</i></span>
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <!-- datatable start -->
                            <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                                <thead>
                                    <tr>
                                        <th style="white-space: nowrap">No</th>
                                        <th style="white-space: nowrap">Nama User</th>
                                        <th style="white-space: nowrap">Email</th>
                                        <th style="white-space: nowrap">Akses Role</th>
                                        <th style="white-space: nowrap">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td style="white-space: nowrap">{{ $loop->iteration }}</td>
                                            <td style="white-space: nowrap">{{ $user->name }}</td>
                                            <td style="white-space: nowrap">{{ $user->email }}</td>
                                            <td style="white-space: nowrap">
                                                {{-- Loop untuk menampilkan semua role --}}
                                                @forelse ($user->getRoleNames() as $role)
                                                    <span class="badge badge-primary">{{ ucfirst($role) }}</span>
                                                @empty
                                                    <span class="badge badge-secondary">No Roles</span>
                                                @endforelse
                                            </td>

                                            <td style="white-space: nowrap">
                                                <button type="button" data-backdrop="static" data-keyboard="false"
                                                    class="badge mx-1 btn-edit badge-primary p-2 border-0 text-white"
                                                    data-id="{{ $user->id }}" title="Ubah">
                                                    <span class="fal fa-pencil ikon-edit"></span>
                                                    <div class="span spinner-text d-none">
                                                        <span class="spinner-border spinner-border-sm" role="status"
                                                            aria-hidden="true"></span>
                                                        Loading...
                                                    </div>
                                                </button>
                                                <button type="button" data-backdrop="static" data-keyboard="false"
                                                    class="badge mx-1 badge-danger p-2 border-0 text-white btn-hapus"
                                                    data-id="{{ $user->id }}" title="Hapus">
                                                    <span class="fal fa-trash ikon-hapus"></span>
                                                    <div class="span spinner-text d-none">
                                                        <span class="spinner-border spinner-border-sm" role="status"
                                                            aria-hidden="true"></span>
                                                        Loading...
                                                    </div>
                                                </button>
                                                <button type="button" data-backdrop="static" data-keyboard="false"
                                                    class="badge mx-1 badge-secondary p-2 border-0 text-white btn-akses"
                                                    {{-- Kirim ID role sebagai JSON string --}}
                                                    data-role-ids="{{ json_encode($user->roles->pluck('id')) }}"
                                                    data-user-id="{{ $user->id }}" title="Akses">
                                                    <span class="fal fa-user-secret ikon-akses"></span>
                                                    <div class="span spinner-text d-none">
                                                        <span class="spinner-border spinner-border-sm" role="status"
                                                            aria-hidden="true"></span>
                                                        Loading...
                                                    </div>
                                                </button>
                                                <a href="{{ route('users.assignPermissions', $user->id) }}" type="button"
                                                    data-backdrop="static" data-keyboard="false"
                                                    class="badge mx-1 badge-warning p-2 border-0 text-white btn-add-permissions"
                                                    data-user-id="{{ $user->id }}" title="Assign Permissions">
                                                    <span class="fal fa-universal-access ikon-add-permissions"></span>
                                                    <div class="span spinner-text d-none">
                                                        <span class="spinner-border spinner-border-sm" role="status"
                                                            aria-hidden="true"></span>
                                                        Loading...
                                                    </div>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <!-- datatable end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('pages.master-data.user.partials.update-user')
        @include('pages.master-data.user.partials.create-user')
        @include('pages.master-data.user.partials.update-akses')
    </main>
@endsection
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(document).ready(function() {
            let idUser = null;

            // Inisialisasi Select2 untuk modal tambah
            $('#tambah-user .select2').select2({
                placeholder: 'Pilih Pegawai',
                dropdownParent: $('#tambah-user')
            });

            // Inisialisasi Select2 untuk modal ubah akses
            $('#roles').select2({
                placeholder: 'Pilih Role',
                dropdownParent: $('#ubah-akses')
            });


            $('.btn-edit').click(function(e) {
                e.preventDefault();
                let button = $(this);
                let id = button.data('id');
                idUser = id; // Simpan id user untuk form update

                button.find('.ikon-edit').hide();
                button.find('.spinner-text').removeClass('d-none');

                $.ajax({
                    type: "GET",
                    url: `/api/dashboard/user/get/${id}`,
                    dataType: "json",
                    success: function(data) {
                        $('#ubah-user').modal('show');
                        $('#ubah-user #name').val(data.name);
                        $('#ubah-user #email').val(data.email);
                    },
                    error: function(xhr) {
                        showErrorAlertNoRefresh('Gagal memuat data user.');
                        console.log(xhr.responseText);
                    }
                }).always(function() {
                    button.find('.ikon-edit').show();
                    button.find('.spinner-text').addClass('d-none');
                });
            });

            $('#update-form').on('submit', function(e) {
                e.preventDefault();
                let formData = $(this).serialize();
                let button = $(this).find('button[type="submit"]');

                $.ajax({
                    type: "PUT",
                    url: '/api/dashboard/user/update/' + idUser,
                    data: formData,
                    beforeSend: function() {
                        button.prop('disabled', true).find('.fal').hide();
                        button.append(
                            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
                            );
                    },
                    success: function(response) {
                        $('#ubah-user').modal('hide');
                        showSuccessAlert(response.message);
                        setTimeout(() => location.reload(), 1500);
                    },
                    error: function(xhr) {
                        showErrorAlertNoRefresh('Gagal memperbarui data.');
                        console.log(xhr.responseText);
                    }
                }).always(function() {
                    button.prop('disabled', false).find('.fal').show();
                    button.find('.spinner-border').remove();
                });
            });

            $('.btn-akses').click(function(e) {
                e.preventDefault();
                let button = $(this);
                let roleIds = button.data('role-ids'); // Ambil array role IDs
                idUser = button.data('user-id'); // Set user id untuk form

                // Set value untuk select2
                $('#roles').val(roleIds).trigger('change');

                // Tampilkan modal
                $('#ubah-akses').modal('show');
            });

            $('#akses-form').on('submit', function(e) {
                e.preventDefault();
                let formData = $(this).serialize();
                let button = $(this).find('button[type="submit"]');

                $.ajax({
                    type: "PUT",
                    url: '/api/dashboard/user/update-akses/' + idUser,
                    data: formData,
                    beforeSend: function() {
                        button.prop('disabled', true).find('.fal').hide();
                        button.append(
                            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
                            );
                    },
                    success: function(response) {
                        $('#ubah-akses').modal('hide');
                        showSuccessAlert(response.message);
                        setTimeout(() => location.reload(), 1500);
                    },
                    error: function(xhr) {
                        showErrorAlertNoRefresh('Gagal memperbarui role.');
                        console.log(xhr.responseText);
                    }
                }).always(function() {
                    button.prop('disabled', false).find('.fal').show();
                    button.find('.spinner-border').remove();
                });
            });

            $('#store-form').on('submit', function(e) {
                e.preventDefault();
                let formData = $(this).serialize();
                let button = $(this).find('button[type="submit"]');

                $.ajax({
                    type: "POST",
                    url: '/api/dashboard/user/store',
                    data: formData,
                    beforeSend: function() {
                        button.prop('disabled', true).find('.fal').hide();
                        button.append(
                            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
                            );
                    },
                    success: function(response) {
                        $('#tambah-user').modal('hide');
                        showSuccessAlert(response.message);
                        setTimeout(() => location.reload(), 1500);
                    },
                    error: function(xhr) {
                        showErrorAlertNoRefresh('Gagal menambah user.');
                        console.log(xhr.responseText);
                    }
                }).always(function() {
                    button.prop('disabled', false).find('.fal').show();
                    button.find('.spinner-border').remove();
                });
            });

            $('.btn-hapus').click(function(e) {
                e.preventDefault();
                let button = $(this);
                let id = button.data('id');

                showDeleteConfirmation(function() {
                    $.ajax({
                        type: "DELETE", // Method DELETE
                        url: '/api/dashboard/user/delete/' + id,
                        data: {
                            _token: "{{ csrf_token() }}" // Kirim token csrf
                        },
                        beforeSend: function() {
                            button.find('.ikon-hapus').hide();
                            button.find('.spinner-text').removeClass('d-none');
                        },
                        success: function(response) {
                            showSuccessAlert(response.message);
                            setTimeout(() => location.reload(), 1500);
                        },
                        error: function(xhr) {
                            showErrorAlertNoRefresh('Gagal menghapus user.');
                            console.log(xhr.responseText);
                        }
                    }).always(function() {
                        button.find('.ikon-hapus').show();
                        button.find('.spinner-text').addClass('d-none');
                    });
                });
            });

            $('#dt-basic-example').dataTable({
                responsive: true
            });

        });
    </script>
@endsection
