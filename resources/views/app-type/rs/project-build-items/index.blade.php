@extends('inc.layout')
@section('title', 'Master Katalog Item Proyek')

@section('extended-css')
    <link rel="stylesheet" media="screen, print" href="/css/datagrid/datatables/datatables.bundle.css">
    <style>
        /* Tambahkan jarak antar input modal */
        #itemModal .modal-body .form-group,
        #itemModal .modal-body .row {
            margin-bottom: 1.25rem;
            /* 20px */
        }

        #itemModal .select2-container {
            width: 100% !important;
        }
    </style>
@endsection

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">Master Data</a></li>
            <li class="breadcrumb-item active">Katalog Item Proyek</li>
        </ol>
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Daftar Master Item Proyek</h2>
                        <div class="panel-toolbar">
                            <button class="btn btn-primary btn-sm" id="addItemBtn">
                                <i class="fal fa-plus"></i> Tambah Item
                            </button>
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table id="item-table" class="table table-bordered table-hover table-striped w-100">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Item</th>
                                        <th>Nama Item</th>
                                        <th>Kategori</th>
                                        <th>Satuan</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal Form -->
    <div class="modal fade" id="itemModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                {{-- Modal header, close button, and title could go here --}}
                <form id="itemForm">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="item_id" id="item_id">

                        <div class="form-group">
                            <label class="form-label" for="item_code">Kode Item</label>
                            <input type="text" id="item_code" name="item_code" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="item_name">Nama Item</label>
                            <input type="text" id="item_name" name="item_name" class="form-control" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="kategori_id">Kategori</label>
                                    <select class="form-control select2" id="kategori_id" name="kategori_id" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach ($kategoris as $item)
                                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="satuan_id">Satuan Dasar</label>
                                    <select class="form-control select2" id="satuan_id" name="satuan_id" required>
                                        <option value="">-- Pilih Satuan --</option>
                                        @foreach ($satuans as $item)
                                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="description">Deskripsi</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label d-block mb-2">Status</label>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="is_active_yes" name="is_active"
                                    value="1" checked>
                                <label class="custom-control-label" for="is_active_yes">Aktif</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="is_active_no" name="is_active"
                                    value="0">
                                <label class="custom-control-label" for="is_active_no">Non-Aktif</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-dismiss="modal" type="button">Batal</button>
                        <button class="btn btn-primary" type="submit" id="saveBtn">Simpan</button>
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
            // Init select2 pada select modal
            $('#kategori_id, #satuan_id').select2({
                dropdownParent: $('#itemModal'),
                width: '100%',
                placeholder: '-- Pilih --',
                allowClear: true,
            });

            const table = $('#item-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('project-build-items.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'item_code',
                        name: 'item_code'
                    },
                    {
                        data: 'item_name',
                        name: 'item_name'
                    },
                    {
                        data: 'kategori.nama',
                        name: 'kategori.nama',
                        defaultContent: '-'
                    },
                    {
                        data: 'satuan.nama',
                        name: 'satuan.nama',
                        defaultContent: '-'
                    },
                    {
                        data: 'is_active',
                        name: 'is_active',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ],
                responsive: true
            });

            // Modal open for add item
            $('#addItemBtn').on('click', function() {
                $('#itemForm')[0].reset();
                $('#item_id').val('');
                $('#kategori_id, #satuan_id').val('').trigger('change');
                $('#itemModal').modal('show');
                $('#itemModal .modal-title').text('Tambah Item Proyek');
                $('#is_active_yes').prop('checked', true);
            });

            // Edit handler
            $('#item-table').on('click', '.edit-btn', function() {
                const id = $(this).data('id');
                $.get("{{ url('project-build-items') }}/" + id + "/edit", function(res) {
                    $('#item_id').val(res.id);
                    $('#item_code').val(res.item_code);
                    $('#item_name').val(res.item_name);
                    $('#kategori_id').val(res.kategori_id).trigger('change');
                    $('#satuan_id').val(res.satuan_id).trigger('change');
                    $('#description').val(res.description);
                    if (res.is_active) {
                        $('#is_active_yes').prop('checked', true);
                    } else {
                        $('#is_active_no').prop('checked', true);
                    }
                    $('#itemModal').modal('show');
                    $('#itemModal .modal-title').text('Edit Item Proyek');
                });
            });

            // Save handler
            $('#itemForm').submit(function(e) {
                e.preventDefault();
                let id = $('#item_id').val();
                let url = id ? ("{{ url('project-build-items') }}/" + id) :
                    "{{ route('project-build-items.store') }}";
                let method = id ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    method: method,
                    data: $(this).serialize(),
                    success: function(res) {
                        $('#itemModal').modal('hide');
                        table.ajax.reload(null, false);
                        toastr.success(res.success);
                    },
                    error: function(err) {
                        if (err.status === 422) {
                            let errors = err.responseJSON.errors;
                            let msg = '';
                            Object.keys(errors).forEach(function(k) {
                                msg += errors[k][0] + "<br>";
                            });
                            toastr.error(msg);
                        } else {
                            toastr.error('Terjadi kesalahan!');
                        }
                    }
                });
            });

            // Delete handler
            $('#item-table').on('click', '.delete-btn', function() {
                const id = $(this).data('id');
                if (confirm('Apakah anda yakin ingin menghapus item ini?')) {
                    $.ajax({
                        url: "{{ url('project-build-items') }}/" + id,
                        method: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(res) {
                            table.ajax.reload(null, false);
                            toastr.success(res.success);
                        },
                        error: function(err) {
                            if (err.status === 422 && err.responseJSON.error) {
                                toastr.error(err.responseJSON.error);
                            } else {
                                toastr.error('Gagal menghapus data!');
                            }
                        }
                    });
                }
            });

            // Reset modal when closed
            $('#itemModal').on('hidden.bs.modal', function() {
                $('#itemForm')[0].reset();
                $('#itemForm').find('.is-invalid').removeClass('is-invalid');
                $('#kategori_id, #satuan_id').val('').trigger('change');
            });
        });
    </script>
@endsection
