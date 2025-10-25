@extends('inc.layout')
@section('title', 'Master Tipe Dokumen')
@section('extended-css')
    <link rel="stylesheet" media="screen, print" href="/css/datagrid/datatables/datatables.bundle.css">
@endsection
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">Master Data</a></li>
            <li class="breadcrumb-item active">Tipe Dokumen</li>
        </ol>
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Daftar Tipe Dokumen</h2>
                        <div class="panel-toolbar">
                            <button class="btn btn-primary btn-sm" id="createNewType">
                                <i class="fal fa-plus"></i> Tambah Tipe Baru
                            </button>
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table id="type-table" class="table table-bordered table-hover table-striped w-100">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Tipe</th>
                                        <th>Deskripsi</th>
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

    <!-- Modal -->
    <div class="modal fade" id="ajaxModel" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modelHeading"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <form id="typeForm">
                    <div class="modal-body">
                        <input type="hidden" name="type_id" id="type_id">
                        <div class="form-group">
                            <label for="name" class="form-label">Nama Tipe</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary" id="saveBtn">Simpan</button>
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
            // Setup CSRF Token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Inisialisasi DataTable
            var table = $('#type-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('document-types.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            // Tombol Tambah
            $('#createNewType').click(function() {
                $('#saveBtn').val("create-type");
                $('#type_id').val('');
                $('#typeForm').trigger("reset");
                $('#modelHeading').html("Tambah Tipe Baru");
                $('#ajaxModel').modal('show');
            });

            // Tombol Edit
            $('body').on('click', '.edit-btn', function() {
                var type_id = $(this).data('id');
                $.get("{{ url('document-types') }}" + '/' + type_id + '/edit', function(data) {
                    $('#modelHeading').html("Edit Tipe Dokumen");
                    $('#saveBtn').val("edit-type");
                    $('#ajaxModel').modal('show');
                    $('#type_id').val(data.id);
                    $('#name').val(data.name);
                    $('#description').val(data.description);
                })
            });

            // Submit Form
            $('#typeForm').on('submit', function(e) {
                e.preventDefault();
                $('#saveBtn').html('Menyimpan..');

                $.ajax({
                    data: $(this).serialize(),
                    url: "{{ route('document-types.store') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {
                        $('#typeForm').trigger("reset");
                        $('#ajaxModel').modal('hide');
                        table.draw();
                        showSuccessAlert(data.success);
                    },
                    error: function(data) {
                        console.log('Error:', data);
                        $('#saveBtn').html('Simpan');
                        showErrorAlertNoRefresh(data.responseJSON.errors.name[0]);
                    },
                    complete: function() {
                        $('#saveBtn').html('Simpan');
                    }
                });
            });

            // Hapus Data
            $('body').on('click', '.delete-btn', function() {
                var type_id = $(this).data("id");
                showDeleteConfirmation(function() {
                    $.ajax({
                        type: "DELETE",
                        url: "{{ url('document-types') }}" + '/' + type_id,
                        success: function(data) {
                            table.draw();
                            showSuccessAlert(data.success);
                        },
                        error: function(data) {
                            showErrorAlertNoRefresh(data.responseJSON.error);
                        }
                    });
                });
            });
        });
    </script>
@endsection
