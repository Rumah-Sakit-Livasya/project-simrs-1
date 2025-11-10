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
            // Setup CSRF token untuk semua request AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            // Inisialisasi DataTable
            const table = $('#dt-basic-example').DataTable({
                responsive: true,
                // Opsi lainnya...
            });

            // Inisialisasi Select2
            $('.select2-create').select2({
                placeholder: 'Pilih Pegawai',
                dropdownParent: $('#tambah-user')
            });
            $('.select2-akses').select2({
                placeholder: 'Pilih Role',
                dropdownParent: $('#ubah-akses')
            });

            // --- FUNGSI-FUNGSI BANTUAN ---

            // Fungsi AJAX generik untuk menghindari pengulangan kode
            function performAjaxRequest(url, method, data, successMessage, button) {
                const originalButtonHtml = button.html();
                button.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...'
                );

                $.ajax({
                    url: url,
                    type: method,
                    data: data,
                    success: function(response) {
                        showSuccessAlert(successMessage);
                        $('.modal').modal('hide');
                        table.ajax
                            .reload(); // Ganti dengan reload data tabel jika Anda pakai server-side
                        location.reload(); // Atau reload halaman penuh jika lebih mudah
                    },
                    error: function(xhr) {
                        const errors = xhr.responseJSON.errors;
                        let errorMessage = 'Terjadi kesalahan.';
                        if (errors) {
                            errorMessage = Object.values(errors).flat().join('<br>');
                        }
                        showErrorAlertNoRefresh(errorMessage);
                    }
                }).always(function() {
                    button.prop('disabled', false).html(originalButtonHtml);
                });
            }


            // --- EVENT LISTENERS ---

            // Otomatis isi form tambah user saat pegawai dipilih
            $('#employee_id').on('change', function() {
                const selectedOption = $(this).find('option:selected');
                const email = selectedOption.data('email') || '';
                const name = selectedOption.data('name') || '';
                $('#create-name').val(name);
                $('#create-email').val(email);
            });

            // Submit form TAMBAH user
            $('#store-form').on('submit', function(e) {
                e.preventDefault();
                performAjaxRequest(
                    '/api/dashboard/user/store',
                    'POST',
                    $(this).serialize(),
                    'User berhasil ditambahkan!',
                    $(this).find('button[type="submit"]')
                );
            });

            // Submit form UBAH user
            $('#update-form').on('submit', function(e) {
                e.preventDefault();
                const userId = $('#update-user-id').val();
                performAjaxRequest(
                    `/api/dashboard/user/update/${userId}`,
                    'PUT',
                    $(this).serialize(),
                    'User berhasil diperbarui!',
                    $(this).find('button[type="submit"]')
                );
            });

            // Submit form UBAH AKSES
            $('#akses-form').on('submit', function(e) {
                e.preventDefault();
                const userId = $(this).data('user-id');
                performAjaxRequest(
                    `/api/dashboard/user/update-akses/${userId}`,
                    'PUT',
                    $(this).serialize(),
                    'Akses user berhasil diperbarui!',
                    $(this).find('button[type="submit"]')
                );
            });


            // Event Delegation untuk tombol di dalam tabel
            $('#dt-basic-example tbody').on('click', '.btn-edit', function() {
                const userId = $(this).data('id');
                $.get(`/api/dashboard/user/get/${userId}`, function(data) {
                    $('#update-user-id').val(data.id);
                    $('#update-name').val(data.name);
                    $('#update-email').val(data.email);
                    $('#update-password').val(''); // Kosongkan field password
                    $('#ubah-user').modal('show');
                });
            });

            $('#dt-basic-example tbody').on('click', '.btn-akses', function() {
                const userId = $(this).data('user-id');
                const roleIds = $(this).data('role-ids');
                $('#roles').val(roleIds).trigger('change');
                $('#akses-form').data('user-id', userId); // Simpan user ID di form
                $('#ubah-akses').modal('show');
            });

            $('#dt-basic-example tbody').on('click', '.btn-hapus', function() {
                const userId = $(this).data('id');
                const button = $(this);
                showDeleteConfirmation(function() {
                    performAjaxRequest(
                        `/api/dashboard/user/delete/${userId}`,
                        'DELETE', {},
                        'User berhasil dihapus!',
                        button
                    );
                });
            });

        });
    </script>
@endsection
