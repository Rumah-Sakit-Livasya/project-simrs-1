@extends('inc.layout')
@section('title', 'Master Kategori Barang')

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="subheader">
            <h1 class="subheader-title">
                <i class='subheader-icon fal fa-tags'></i> Master <span class='fw-300'>Kategori Barang</span>
                <small>
                    Manajemen data master untuk kategori barang.
                </small>
            </h1>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Daftar <span class="fw-300"><i>Kategori Barang</i></span>
                        </h2>
                        <div class="panel-toolbar">
                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#add-modal">
                                <i class="fal fa-plus"></i> Tambah Kategori
                            </button>
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <!-- datatable start -->
                            <table id="dt-kategori-barang" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Nama Kategori</th>
                                        <th>COA Persediaan</th>
                                        <th>Konsinyasi</th>
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
        $coaFields = [
            'coa_inventory' => 'COA Persediaan',
            'coa_sales_outpatient' => 'COA Penjualan Rajal',
            'coa_cogs_outpatient' => 'COA HPP Rajal',
            'coa_sales_inpatient' => 'COA Penjualan Ranap',
            'coa_cogs_inpatient' => 'COA HPP Ranap',
            'coa_adjustment_daily' => 'COA Adjustment Harian',
            'coa_adjustment_so' => 'COA Adjustment SO',
        ];
    @endphp

    <!-- Add Modal -->
    <div class="modal fade" id="add-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form id="add-form" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Kategori Barang</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="fal fa-times"></i></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="add-kode">Kode Kategori</label>
                                    <input type="text" id="add-kode" name="kode" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="add-nama">Nama Kategori</label>
                                    <input type="text" id="add-nama" name="nama" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            @foreach ($coaFields as $field => $label)
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="add-{{ $field }}">{{ $label }}</label>
                                        <select class="form-control select2-add" id="add-{{ $field }}"
                                            name="{{ $field }}" style="width: 100%;">
                                            <option value="">Pilih COA...</option>
                                            @foreach ($coas as $coa)
                                                <option value="{{ $coa->id }}">{{ $coa->name }}
                                                    ({{ $coa->account_code }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Status</label>
                                    <div class="frame-wrap">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="add-aktif-true"
                                                name="aktif" value="1" checked>
                                            <label class="custom-control-label" for="add-aktif-true">Aktif</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="add-aktif-false"
                                                name="aktif" value="0">
                                            <label class="custom-control-label" for="add-aktif-false">Non Aktif</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Konsinyasi</label>
                                    <div class="frame-wrap">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="add-konsinyasi-true"
                                                name="konsinyasi" value="1">
                                            <label class="custom-control-label" for="add-konsinyasi-true">Ya</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="add-konsinyasi-false"
                                                name="konsinyasi" value="0" checked>
                                            <label class="custom-control-label" for="add-konsinyasi-false">Tidak</label>
                                        </div>
                                    </div>
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
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form id="edit-form" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit-id" name="id">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Kategori Barang</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="fal fa-times"></i></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="edit-kode">Kode Kategori</label>
                                    <input type="text" id="edit-kode" name="kode" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="edit-nama">Nama Kategori</label>
                                    <input type="text" id="edit-nama" name="nama" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            @foreach ($coaFields as $field => $label)
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label"
                                            for="edit-{{ $field }}">{{ $label }}</label>
                                        <select class="form-control select2-edit" id="edit-{{ $field }}"
                                            name="{{ $field }}" style="width: 100%;">
                                            <option value="">Pilih COA...</option>
                                            @foreach ($coas as $coa)
                                                <option value="{{ $coa->id }}">{{ $coa->name }}
                                                    ({{ $coa->account_code }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="row">
                            <div class="col-md-6">
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Konsinyasi</label>
                                    <div class="frame-wrap">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="edit-konsinyasi-true"
                                                name="konsinyasi" value="1">
                                            <label class="custom-control-label" for="edit-konsinyasi-true">Ya</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" id="edit-konsinyasi-false"
                                                name="konsinyasi" value="0">
                                            <label class="custom-control-label" for="edit-konsinyasi-false">Tidak</label>
                                        </div>
                                    </div>
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
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(document).ready(function() {
            // Setup CSRF token for all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Initialize Select2
            $('.select2-add').select2({
                dropdownParent: $('#add-modal')
            });
            $('.select2-edit').select2({
                dropdownParent: $('#edit-modal')
            });

            // Initialize DataTables
            var table = $('#dt-kategori-barang').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('warehouse.master-data.kategori-barang.data') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'kode',
                        name: 'kode'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'coa_inventory_name',
                        name: '_coa_inventory.name'
                    },
                    {
                        data: 'konsinyasi_status',
                        name: 'konsinyasi'
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
                buttons: [ /* ... buttons ... */ ]
            });

            // Store new data
            $('#add-form').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('warehouse.master-data.kategori-barang.store') }}",
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
                        showErrorAlertNoRefresh('Gagal menyimpan. Pastikan kode & nama unik.');
                    }
                });
            });

            // Edit button click
            $('#dt-kategori-barang').on('click', '.edit-btn', function() {
                var url = $(this).data('url');
                $.get(url, function(response) {
                    if (response.success) {
                        var data = response.data;
                        $('#edit-modal').modal('show');
                        $('#edit-id').val(data.id);
                        $('#edit-nama').val(data.nama);
                        $('#edit-kode').val(data.kode);

                        // Set radio buttons
                        $(`input[name=aktif][value=${data.aktif}]`).prop('checked', true);
                        $(`input[name=konsinyasi][value=${data.konsinyasi}]`).prop('checked', true);

                        // Set Select2 values
                        $('#edit-coa_inventory').val(data.coa_inventory).trigger('change');
                        $('#edit-coa_sales_outpatient').val(data.coa_sales_outpatient).trigger(
                            'change');
                        $('#edit-coa_cogs_outpatient').val(data.coa_cogs_outpatient).trigger(
                            'change');
                        $('#edit-coa_sales_inpatient').val(data.coa_sales_inpatient).trigger(
                            'change');
                        $('#edit-coa_cogs_inpatient').val(data.coa_cogs_inpatient).trigger(
                        'change');
                        $('#edit-coa_adjustment_daily').val(data.coa_adjustment_daily).trigger(
                            'change');
                        $('#edit-coa_adjustment_so').val(data.coa_adjustment_so).trigger('change');

                        // Set form action URL
                        var updateUrl = "{{ url('warehouse/master-data/kategori-barang') }}/" +
                            data.id;
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
                        showErrorAlertNoRefresh(
                            'Gagal memperbarui data. Pastikan kode & nama unik.');
                    }
                });
            });

            // Delete button click
            $('#dt-kategori-barang').on('click', '.delete-btn', function() {
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
            $('#add-modal').on('hidden.bs.modal', function() {
                $(this).find('form')[0].reset();
                $('.select2-add').val(null).trigger('change');
            });
            $('#edit-modal').on('hidden.bs.modal', function() {
                $(this).find('form')[0].reset();
                $('.select2-edit').val(null).trigger('change');
            });
        });
    </script>
@endsection
