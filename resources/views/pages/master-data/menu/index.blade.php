@extends('inc.layout')
@section('title', 'Menu')
@section('extended-css')
    <style>
        #dt-basic-example thead th,
        #dt-basic-example tbody td,
        #dt-basic-example tfoot th {
            vertical-align: middle;
        }

        a.link_nama:hover {
            text-decoration: underline !important;
        }
    </style>
@endsection
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row mb-5">
            <div class="col-xl-12">
                <button type="button" class="btn btn-primary waves-effect waves-themed mr-2" data-backdrop="static"
                    data-keyboard="false" data-toggle="modal" data-target="#tambah-data" title="Tambah Menu">
                    <span class="fal fa-plus-circle mr-1"></span>
                    Tambah Menu
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Tabel Menu
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <!-- datatable start -->
                            <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                                <thead>
                                    <tr>
                                        <th style="white-space: nowrap">No</th>
                                        <th style="white-space: nowrap">Menu</th>
                                        <th style="white-space: nowrap">URL</th>
                                        <th style="white-space: nowrap">Icon</th>
                                        <th style="white-space: nowrap">Parent ID</th>
                                        <th style="white-space: nowrap">Sort Order</th>
                                        <th style="white-space: nowrap">Permission</th>
                                        <th style="white-space: nowrap">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($menus as $item)
                                        <tr>
                                            <td style="white-space: nowrap">{{ $loop->iteration }}</td>
                                            <td style="white-space: nowrap">
                                                {{ $item->title ?? '-' }}
                                            </td>
                                            <td style="white-space: nowrap">{{ $item->url ?? '-' }}</td>
                                            <td style="white-space: nowrap">{{ $item->icon ?? '-' }}</td>
                                            <td style="white-space: nowrap">{{ $item->parent_id ?? '-' }}</td>
                                            <td style="white-space: nowrap">{{ $item->sort_order ?? '-' }}</td>
                                            <td style="white-space: nowrap">{{ $item->permission ?? '-' }}</td>
                                            <td style="white-space: nowrap">
                                                <button type="button" data-backdrop="static" data-keyboard="false"
                                                    class="badge mx-1 btn-edit badge-primary p-2 border-0 text-white"
                                                    data-id="{{ $item->id }}" title="Ubah"
                                                    onclick="handleEditButtonClick(event)">
                                                    <span class="fal fa-pencil ikon-edit"></span>
                                                    <div class="span spinner-text d-none">
                                                        <span class="spinner-border spinner-border-sm" role="status"
                                                            aria-hidden="true"></span>
                                                        Loading...
                                                    </div>
                                                </button>
                                                <button type="button" data-backdrop="static" data-keyboard="false"
                                                    class="badge mx-1 badge-success p-2 border-0 text-white btn-hapus"
                                                    data-id="{{ $item->id }}" title="Hapus"
                                                    onclick="handleHapusButtonClick(event)">
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
                                        <th style="white-space: nowrap">Menu</th>
                                        <th style="white-space: nowrap">URL</th>
                                        <th style="white-space: nowrap">Icon</th>
                                        <th style="white-space: nowrap">Parent ID</th>
                                        <th style="white-space: nowrap">Sort Order</th>
                                        <th style="white-space: nowrap">Permission</th>
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
    @include('pages.master-data.menu.partials.create')
    @include('pages.master-data.menu.partials.edit')
@endsection
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script><!-- FixedColumns JS -->
    <script>
        // Fungsi untuk menangani klik tombol edit
        async function handleEditButtonClick(event) {
            event.preventDefault();
            let button = $(event.currentTarget);
            console.log('clicked');
            let id = button.attr('data-id');
            button.find('.ikon-edit').hide();
            button.find('.spinner-text').removeClass('d-none');
            $('#update-form').attr('data-id', id);
            try {
                let response = await fetch(`/api/dashboard/menu/get/${id}`);
                if (!response.ok) {
                    throw new Error(`Error: ${response.statusText}`);
                }
                let data = await response.json();
                button.find('.ikon-edit').show();
                button.find('.spinner-text').addClass('d-none');
                $('#ubah-data').modal('show');
                $('#ubah-data #title').val(data.menu.title);
                $('#ubah-data #url').val(data.menu.url);
                $('#ubah-data #icon').val(data.menu.icon);
                $('#ubah-data #parent_id').val(data.menu.parent_id).select2({
                    dropdownParent: $('#ubah-data')
                });
                $('#ubah-data #sort_order').val(data.menu.sort_order);
                $('#ubah-data #permission').val(data.menu.permission);
            } catch (error) {
                $('#ubah-data').modal('hide');
                showErrorAlert(error.message);
            }
        }

        async function handleHapusButtonClick(event) {
            event.preventDefault();
            let button = $(event.currentTarget);

            if (!confirm('Apakah Anda yakin ingin menghapus data ini?')) {

                return;
            }

            let id = button.attr('data-id');
            button.find('.ikon-hapus').hide();
            button.find('.spinner-text').removeClass('d-none');

            try {
                let response = await fetch(`/api/dashboard/menu/delete/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Username': 'fizar*#',
                        'Password': '#*ganteng'
                    }
                });
                if (!response.ok) {
                    throw new Error(`Error: ${response.statusText}`);
                }
                let result = await response.json();
                button.find('.ikon-hapus').show();
                button.find('.spinner-text').addClass('d-none');
                showSuccessAlert(result.message);
                setTimeout(function() {
                    location.reload();
                }, 1000);
            } catch (error) {
                showErrorAlert(error.message);
            }
        }

        // Fungsi untuk menangani pengiriman formulir pembaruan
        async function handleUpdateFormSubmit(event) {
            event.preventDefault();
            let formData = new URLSearchParams(new FormData(event.currentTarget)).toString();
            let id = $(event.currentTarget).attr('data-id');

            try {
                $('#update-form').find('.ikon-edit').hide();
                $('#update-form').find('.spinner-text').removeClass('d-none');

                let response = await fetch(`/api/dashboard/menu/update/${id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: formData
                });
                if (!response.ok) {
                    throw new Error(`Error: ${response.statusText}`);
                }
                let result = await response.json();
                $('#ubah-data').modal('hide');
                showSuccessAlert(result.message);
                setTimeout(function() {
                    location.reload();
                }, 1000);
            } catch (error) {
                $('#ubah-data').modal('hide');
                showErrorAlert(error.message);
            }
        }

        $(document).ready(function() {

            $('#update-form').on('submit', handleUpdateFormSubmit);

            $(function() {
                $('.select2').select2({
                    placeholder: 'Pilih Data Berikut',
                    dropdownParent: $('#tambah-data')
                });
            });

            $('#store-form').on('submit', async function(e) {
                e.preventDefault();
                let formData = new FormData(this); // Menggunakan FormData untuk mengirim data form

                try {
                    // Tampilkan spinner sebelum permintaan dimulai
                    $('#store-form').find('.ikon-tambah').hide();
                    $('#store-form').find('.spinner-text').removeClass('d-none');

                    // Lakukan permintaan menggunakan fetch
                    let response = await fetch('/api/dashboard/menu/store', {
                        method: 'POST',
                        body: formData
                    });

                    // Pastikan respons statusnya sukses (200-299)
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    // Parsing response JSON
                    let result = await response.json();

                    // Sembunyikan spinner dan tampilkan ikon edit
                    $('#store-form').find('.ikon-tambah').show();
                    $('#store-form').find('.spinner-text').addClass('d-none');
                    $('#tambah-data').modal('hide');

                    // Tampilkan pesan sukses dan reload halaman
                    showSuccessAlert(result.message);
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } catch (error) {
                    // Tampilkan pesan error jika terjadi kesalahan
                    $('#tambah-data').modal('hide');
                    showErrorAlert(error.message || 'Terjadi kesalahan, silakan coba lagi.');
                }
            });

            $('#store-assign-permissions-form').on('submit', function(e) {
                e.preventDefault();
                let formData = $(this).serialize();
                $.ajax({
                    type: "POST",
                    url: '/api/dashboard/role/store',
                    data: formData,
                    beforeSend: function() {
                        $('#store-form').find('.ikon-tambah').hide();
                        $('#store-form').find('.spinner-text').removeClass('d-none');
                    },
                    success: function(response) {
                        $('#store-form').find('.ikon-edit').show();
                        $('#store-form').find('.spinner-text').addClass('d-none');
                        $('#tambah-role').modal('hide');
                        showSuccessAlert(response.message);
                        setTimeout(function() {
                            location.reload();
                        }, 500);
                    },
                    error: function(xhr) {
                        showErrorAlert(xhr.responseText);
                    }
                });
            });

            $('.btn-akses').click(function(e) {
                e.preventDefault();
                let button = $(this);
                let roleId = button.attr('data-id');
                getRoleData(roleId, button);
            });

            async function getRoleData(id, button) {
                try {
                    // Menampilkan spinner sebelum mengirim request
                    button.find('.ikon-akses').hide();
                    button.find('.spinner-text').removeClass('d-none');

                    // Mengirim request GET menggunakan fetch
                    const response = await fetch(`/api/dashboard/role/get/${id}`, {
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
                    button.find('.ikon-akses').show();
                    button.find('.spinner-text').addClass('d-none');
                    $('#assign-permissions').modal('show');
                    $('#assign-permissions #name').val(data.role.name);
                    console.log(data.permissions);
                    $('#assign-permissions #permissions').val(data.permissions).select2({
                        dropdownParent: $('#assign-permissions'),
                        placeholder: 'Pilih permissions'
                    });
                } catch (error) {
                    // Menangani error dan menampilkan pesan error
                    showErrorAlert(error.message);
                }
            }

            $('.btn-edit').on('click', handleEditButtonClick);

            function initializeDataTable(isResponsive) {
                $('#dt-basic-example').DataTable({
                    destroy: true, // Destroy any existing table instances
                    responsive: isResponsive,
                    scrollX: !isResponsive,
                    fixedColumns: {
                        leftColumns: 2,
                        rightColumns: 1
                    }
                });
            }

            function checkScreenSize() {
                if (window.matchMedia("(max-width: 768px)").matches) {
                    // Mobile screen
                    initializeDataTable(true);
                } else {
                    // Desktop screen
                    initializeDataTable(false);
                }
            }

            // Initialize DataTable on load
            checkScreenSize();

            // Reinitialize DataTable on window resize
            $(window).resize(function() {
                checkScreenSize();
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
