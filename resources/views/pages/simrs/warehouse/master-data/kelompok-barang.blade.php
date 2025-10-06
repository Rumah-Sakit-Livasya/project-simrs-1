@extends('inc.layout')
@section('title', 'Master Kelompok Barang')

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="subheader">
            <h1 class="subheader-title">
                <i class='subheader-icon fal fa-layer-group'></i> Master <span class='fw-300'>Kelompok Barang</span>
                <small>
                    Manajemen data master untuk kelompok barang.
                </small>
            </h1>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Daftar <span class="fw-300"><i>Kelompok Barang</i></span>
                        </h2>
                        <div class="panel-toolbar">
                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#add-modal">
                                <i class="fal fa-plus"></i> Tambah Kelompok
                            </button>
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <!-- datatable start -->
                            <table id="dt-kelompok-barang" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Kelompok</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data akan diisi oleh DataTables -->
                                </tbody>
                            </table>
                            <!-- datatable end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Add Modal -->
    <div class="modal fade" id="add-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="add-form" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Kelompok Barang</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="fal fa-times"></i></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label" for="add-nama">Nama Kelompok</label>
                            <input type="text" id="add-nama" name="nama" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <div class="frame-wrap">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input" id="add-aktif-true" name="aktif"
                                        value="1" checked>
                                    <label class="custom-control-label" for="add-aktif-true">Aktif</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input" id="add-aktif-false" name="aktif"
                                        value="0">
                                    <label class="custom-control-label" for="add-aktif-false">Non Aktif</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="edit-form" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit-id" name="id">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Kelompok Barang</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="fal fa-times"></i></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label" for="edit-nama">Nama Kelompok</label>
                            <input type="text" id="edit-nama" name="nama" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <div class="frame-wrap">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input" id="edit-aktif-true"
                                        name="aktif" value="1">
                                    <label class="custom-control-label" for="edit-aktif-true">Aktif</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input" id="edit-aktif-false"
                                        name="aktif" value="0">
                                    <label class="custom-control-label" for="edit-aktif-false">Non Aktif</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script>
        $(document).ready(function() {
            // Setup CSRF token for all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Initialize DataTables
            var table = $('#dt-kelompok-barang').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('warehouse.master-data.kelompok-barang.data') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                ],
                responsive: true,
                lengthChange: false,
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                        extend: 'excelHtml5',
                        text: '<i class="fal fa-file-excel"></i>',
                        titleAttr: 'Export to Excel',
                        className: 'btn-outline-success btn-sm mr-1'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fal fa-print"></i>',
                        titleAttr: 'Print Table',
                        className: 'btn-outline-primary btn-sm'
                    }
                ]
            });

            // Store new data
            $('#add-form').on('submit', function(e) {
                e.preventDefault();
                var formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('warehouse.master-data.kelompok-barang.store') }}",
                    type: "POST",
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            $('#add-modal').modal('hide');
                            showSuccessAlert(response.message);
                            table.ajax.reload();
                        }
                    },
                    error: function(xhr) {
                        showErrorAlertNoRefresh(
                            'Gagal menyimpan data. Pastikan nama unik dan semua field terisi.'
                        );
                    }
                });
            });

            // Edit button click
            $('#dt-kelompok-barang').on('click', '.edit-btn', function() {
                var url = $(this).data('url');
                $.get(url, function(response) {
                    if (response.success) {
                        var data = response.data;
                        $('#edit-modal').modal('show');
                        $('#edit-id').val(data.id);
                        $('#edit-nama').val(data.nama);
                        if (data.aktif == 1) {
                            $('#edit-aktif-true').prop('checked', true);
                        } else {
                            $('#edit-aktif-false').prop('checked', true);
                        }
                        // Set the form action URL dynamically
                        var updateUrl = "{{ url('warehouse/master-data/kelompok-barang') }}/" + data
                            .id;
                        $('#edit-form').attr('action', updateUrl);
                    }
                });
            });

            // Update data
            $('#edit-form').on('submit', function(e) {
                e.preventDefault();
                var url = $(this).attr('action');
                var formData = $(this).serialize();

                $.ajax({
                    url: url,
                    type: "POST", // Method override handled by _method:PUT in form
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            $('#edit-modal').modal('hide');
                            showSuccessAlert(response.message);
                            table.ajax.reload();
                        }
                    },
                    error: function(xhr) {
                        showErrorAlertNoRefresh('Gagal memperbarui data. Pastikan nama unik.');
                    }
                });
            });

            // Delete button click
            $('#dt-kelompok-barang').on('click', '.delete-btn', function() {
                var url = $(this).data('url');
                showDeleteConfirmation(function() {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        success: function(response) {
                            if (response.success) {
                                showSuccessAlert(response.message);
                                table.ajax.reload();
                            }
                        },
                        error: function(xhr) {
                            showErrorAlert('Gagal menghapus data.');
                        }
                    });
                });
            });

            // Clear modal on hidden
            $('#add-modal, #edit-modal').on('hidden.bs.modal', function() {
                $(this).find('form')[0].reset();
            });
        });
    </script>
@endsection
