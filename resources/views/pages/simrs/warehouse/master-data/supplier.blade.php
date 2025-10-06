@extends('inc.layout')
@section('title', 'Master Supplier')

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="subheader">
            <h1 class="subheader-title">
                <i class='subheader-icon fal fa-truck'></i> Master <span class='fw-300'>Supplier</span>
                <small>
                    Manajemen data master untuk supplier.
                </small>
            </h1>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Daftar <span class="fw-300"><i>Supplier</i></span>
                        </h2>
                        <div class="panel-toolbar">
                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#add-modal">
                                <i class="fal fa-plus"></i> Tambah Supplier
                            </button>
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <!-- datatable start -->
                            <table id="dt-supplier" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Supplier</th>
                                        <th>Kategori</th>
                                        <th>Kontak</th>
                                        <th>TOP</th>
                                        <th>PPN (%)</th>
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

    @php
        $topOptions = ['COD', '7HARI', '14HARI', '21HARI', '24HARI', '30HARI', '37HARI', '40HARI', '45HARI'];
        $tipeTopOptions = ['SETELAH_TUKAR_FAKTUR', 'SETELAH_TERIMA_BARANG'];
    @endphp

    <!-- Add Modal -->
    <div class="modal fade" id="add-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form id="add-form" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Supplier</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="fal fa-times"></i></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{-- Form fields will be included here --}}
                        @include('pages.simrs.warehouse.master-data.partials.supplier-form-fields', [
                            'prefix' => 'add',
                            'topOptions' => $topOptions,
                            'tipeTopOptions' => $tipeTopOptions,
                        ])
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
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form id="edit-form" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit-id" name="id">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Supplier</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="fal fa-times"></i></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{-- Form fields will be included here --}}
                        @include('pages.simrs.warehouse.master-data.partials.supplier-form-fields', [
                            'prefix' => 'edit',
                            'topOptions' => $topOptions,
                            'tipeTopOptions' => $tipeTopOptions,
                        ])
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
            // Setup CSRF token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Initialize DataTables
            var table = $('#dt-supplier').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('warehouse.master-data.supplier.data') }}",
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
                        data: 'kategori',
                        name: 'kategori'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'top',
                        name: 'top'
                    },
                    {
                        data: 'ppn',
                        name: 'ppn'
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
                        extend: 'copy',
                        text: '<i class="fal fa-copy"></i> Salin',
                        className: 'btn btn-secondary btn-sm'
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fal fa-file-excel"></i> Excel',
                        className: 'btn btn-success btn-sm'
                    },
                    {
                        extend: 'csv',
                        text: '<i class="fal fa-file-csv"></i> CSV',
                        className: 'btn btn-info btn-sm'
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fal fa-file-pdf"></i> PDF',
                        className: 'btn btn-danger btn-sm'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fal fa-print"></i> Cetak',
                        className: 'btn btn-primary btn-sm'
                    }
                ]
            });

            // Store new data
            $('#add-form').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('warehouse.master-data.supplier.store') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            $('#add-modal').modal('hide');
                            showSuccessAlert(response.message);
                            table.ajax.reload();
                        }
                    },
                    error: function(xhr) {
                        showErrorAlertNoRefresh('Gagal menyimpan data.');
                    }
                });
            });

            // Edit button click
            $('#dt-supplier').on('click', '.edit-btn', function() {
                var url = $(this).data('url');
                $.get(url, function(response) {
                    if (response.success) {
                        var data = response.data;
                        $('#edit-modal').modal('show');
                        // Populate form
                        $('#edit-id').val(data.id);
                        for (const key in data) {
                            if (Object.hasOwnProperty.call(data, key)) {
                                const value = data[key];
                                if ($(`#edit-${key}`).is(':radio')) {
                                    $(`input[name=${key}][value="${value}"]`).prop('checked', true);
                                } else {
                                    $(`#edit-${key}`).val(value);
                                }
                            }
                        }
                        // Set form action URL
                        var updateUrl = "{{ url('warehouse/master-data/supplier') }}/" + data.id;
                        $('#edit-form').attr('action', updateUrl);
                    }
                });
            });

            // Update data
            $('#edit-form').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            $('#edit-modal').modal('hide');
                            showSuccessAlert(response.message);
                            table.ajax.reload();
                        }
                    },
                    error: function(xhr) {
                        showErrorAlertNoRefresh('Gagal memperbarui data.');
                    }
                });
            });

            // Delete button click
            $('#dt-supplier').on('click', '.delete-btn', function() {
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
