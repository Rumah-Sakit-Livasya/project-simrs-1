@extends('inc.layout')
@section('title', 'Role')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row mb-5">
            <div class="col-xl-12">
                <button type="button" class="btn btn-primary waves-effect waves-themed" data-backdrop="static"
                    data-keyboard="false" data-toggle="modal" data-target="#tambah-permission" title="Tambah Role">
                    <span class="fal fa-plus-circle mr-1"></span>
                    Tambah Permission
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Tabel Permissions
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <!-- datatable start -->
                            <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                                <thead>
                                    <tr>
                                        <th style="white-space: nowrap">No</th>
                                        <th style="white-space: nowrap">Permission</th>
                                        <th style="white-space: nowrap">Guard Name</th>
                                        <th style="white-space: nowrap">Group Name</th>
                                        <th style="white-space: nowrap">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($permissions as $item)
                                        <tr>
                                            <td style="white-space: nowrap">{{ $loop->iteration }}</td>
                                            <td style="white-space: nowrap">
                                                {{ strlen($item->name) <= 3 ? strtoupper($item->name) : ucfirst($item->name) }}
                                            </td>
                                            <td style="white-space: nowrap">{{ ucfirst($item->guard_name) }}</td>
                                            <td style="white-space: nowrap">{{ $item->group }}</td>

                                            <td style="white-space: nowrap">
                                                <button type="button" data-backdrop="static" data-keyboard="false"
                                                    class="badge mx-1 btn-edit badge-primary p-2 border-0 text-white"
                                                    data-id="{{ $item->id }}" title="Ubah">
                                                    <span class="fal fa-pencil ikon-edit"></span>
                                                    <div class="span spinner-text d-none">
                                                        <span class="spinner-border spinner-border-sm" role="status"
                                                            aria-hidden="true"></span>
                                                        Loading...
                                                    </div>
                                                </button>
                                                <button type="button" data-backdrop="static" data-keyboard="false"
                                                    class="badge mx-1 badge-success p-2 border-0 text-white btn-hapus"
                                                    data-id="{{ $item->id }}" title="Hapus">
                                                    <span class="fal fa-trash ikon-hapus"></span>
                                                    <div class="span spinner-text d-none">
                                                        <span class="spinner-border spinner-border-sm" role="status"
                                                            aria-hidden="true"></span>
                                                        Loading...
                                                    </div>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th style="white-space: nowrap">No</th>
                                        <th style="white-space: nowrap">Permission</th>
                                        <th style="white-space: nowrap">Guard Name</th>
                                        <th style="white-space: nowrap">Group Name</th>
                                        <th style="white-space: nowrap">Aksi</th>
                                    </tr>
                                </tfoot>
                            </table>

                            <!-- datatable end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    @include('pages.master-data.permissions.partials.update-permission')
    @include('pages.master-data.permissions.partials.create-permission')
@endsection
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        /* demo scripts for change table color */
        /* change background */
        $(document).ready(function() {

            let idPermission = null;

            async function fetchPermissionData(id, button) {
                try {
                    // Menampilkan spinner sebelum mengirim request
                    button.find('.ikon-edit').hide();
                    button.find('.spinner-text').removeClass('d-none');

                    // Mengirim request GET menggunakan fetch
                    const response = await fetch(`/api/dashboard/permissions/get/${id}`, {
                        method: "GET",
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    });

                    // Memeriksa apakah response statusnya OK (200)
                    if (!response.ok) {
                        throw new Error(`Error: ${response.statusText}`);
                    }

                    // Mengonversi response menjadi JSON
                    const data = await response.json();
                    // Memperbarui tampilan setelah mendapatkan response
                    button.find('.ikon-edit').show();
                    button.find('.spinner-text').addClass('d-none');
                    $('#ubah-permission').modal('show');
                    $('#ubah-permission #name').val(data.permission.name);
                    $('#ubah-permission #group').val(data.permission.group);
                } catch (error) {
                    // Menangani error dan menampilkan pesan error
                    showErrorAlert(error.message);
                }
            }

            async function deletePermission(id, button) {
                try {
                    // Menampilkan spinner sebelum mengirim request
                    button.find('.ikon-hapus').hide();
                    button.find('.spinner-text').removeClass('d-none');

                    // Mengirim request GET menggunakan fetch
                    const response = await fetch(`/api/dashboard/permissions/delete/${id}`, {
                        method: "GET",
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    });

                    // Memeriksa apakah response statusnya OK (200)
                    if (!response.ok) {
                        throw new Error(`Error: ${response.statusText}`);
                    }

                    // Mengonversi response menjadi JSON
                    let result = await response.json();
                    // Memperbarui tampilan setelah mendapatkan response
                    button.find('.ikon-hapus').show();
                    button.find('.spinner-text').addClass('d-none');
                    showSuccessAlert(result.message);
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } catch (error) {
                    // Menangani error dan menampilkan pesan error
                    showErrorAlert(error.message);
                }
            }

            $('.btn-edit').click(function(e) {
                e.preventDefault();
                let button = $(this);
                let id = button.attr('data-id');
                idPermission = id;
                button.find('.ikon-edit').hide();
                button.find('.spinner-text').removeClass('d-none');
                fetchPermissionData(id, button);
            });

            $('#update-form').on('submit', async function(e) {
                e.preventDefault();
                let formData = new URLSearchParams(new FormData(this)).toString();
                let id = idPermission; // Ganti dengan ID yang sesuai

                $('#update-form').find('.ikon-update').hide();
                $('#update-form').find('.spinner-text').removeClass('d-none');

                try {
                    let response = await fetch('/api/dashboard/permissions/get/' + id, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: formData,
                    });

                    if (!response.ok) {
                        throw new Error(await response.text());
                    }

                    let result = await response.json();

                    $('#update-form').find('.ikon-update').show();
                    $('#update-form').find('.spinner-text').addClass('d-none');
                    $('#ubah-permission').modal('hide');
                    showSuccessAlert(result.message);
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } catch (error) {
                    $('#ubah-permission').modal('hide');
                    showErrorAlert(error.message);
                    $('#update-form').find('.ikon-edit').show();
                    $('#update-form').find('.spinner-text').addClass('d-none');
                }
            });


            $('#store-form').on('submit', async function(e) {
                e.preventDefault();
                let formData = new URLSearchParams(new FormData(this)).toString();

                $('#store-form').find('.ikon-tambah').hide();
                $('#store-form').find('.spinner-text').removeClass('d-none');
                try {

                    let response = await fetch('/api/dashboard/permissions/store', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: formData,
                    });

                    if (!response.ok) {
                        throw new Error(await response.text());
                    }

                    let result = await response.json();

                    $('#store-form').find('.ikon-tambah').show();
                    $('#store-form').find('.spinner-text').addClass('d-none');
                    $('#tambah-permission').modal('hide');
                    showSuccessAlert(result.message);
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } catch (error) {
                    $('#tambah-permission').modal('hide');
                    showErrorAlert(error.message);
                }
            });

            $('.btn-hapus').click(function(e) {
                e.preventDefault();
                let button = $(this);
                let id = button.attr('data-id');

                if (!confirm('Yakin ingin menghapus ini?')) {
                    // Jika pengguna memilih "Tidak", proses penghapusan dibatalkan
                    return;
                }

                deletePermission(id, button);
            });

            $('#dt-basic-example').dataTable({
                responsive: true
            });

            $('.js-thead-colors a').on('click', function() {
                var theadColor = $(this).attr("data-bg");
                $('#dt-basic-example thead').removeClassPrefix('bg-').addClass(theadColor);
            });

            $('.js-tbody-colors a').on('click', function() {
                var theadColor = $(this).attr("data-bg");
                $('#dt-basic-example').removeClassPrefix('bg-').addClass(theadColor);
            });

        });
    </script>
@endsection
