@extends('inc.layout')
@section('title', 'Proyek Internal')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Manajemen Proyek Internal
                        </h2>
                        <div class="panel-toolbar">
                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#formModal"
                                id="btn-add">
                                <i class="fas fa-plus"></i> Tambah Proyek
                            </button>
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <!-- datatable start -->
                            <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th>#</th>
                                        <th>Nama Proyek</th>
                                        <th>Deskripsi</th>
                                        <th>PIC</th>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                            <!-- datatable end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal Form -->
    <div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-labelledby="formModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModalLabel">Tambah Proyek</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="projectForm">
                        <input type="hidden" name="id" id="projectId">
                        <div class="form-group">
                            <label for="name">Nama Proyek</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Masukkan nama proyek">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label for="user_id">Penanggung Jawab (PIC)</label>
                            <select class="form-control select2" id="user_id" name="user_id" style="width: 100%;">
                                <option value="">Pilih PIC</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label for="datetime">Tanggal & Waktu</label>
                            <input type="datetime-local" class="form-control" id="datetime" name="datetime">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea class="form-control" id="description" name="description" rows="3"
                                placeholder="Deskripsi singkat proyek"></textarea>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="pending">Pending</option>
                                <option value="on-progress">On Progress</option>
                                <option value="done">Done</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-group" id="done_at_group" style="display: none;">
                            <label for="done_at">Tanggal Selesai</label>
                            <input type="datetime-local" class="form-control" id="done_at" name="done_at">
                            <div class="invalid-feedback"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="btn-save">Simpan</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(document).ready(function() {
            // Setup CSRF Token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Inisialisasi Select2
            $('.select2').select2({
                dropdownParent: $('#formModal') // Penting untuk modal
            });

            // Inisialisasi Datatables
            var table = $('#dt-basic-example').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: "{{ route('project-internal.data') }}",
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
                        data: 'user.name',
                        name: 'user.name'
                    },
                    {
                        data: 'datetime',
                        name: 'datetime'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            // Tampilkan/Sembunyikan field 'done_at' berdasarkan status
            $('#status').on('change', function() {
                if ($(this).val() === 'done') {
                    $('#done_at_group').show();
                } else {
                    $('#done_at_group').hide();
                }
            });

            // Fungsi untuk membersihkan form
            function resetForm() {
                $('#projectForm')[0].reset();
                $('#projectId').val('');
                $('.form-control').removeClass('is-invalid');
                $('.invalid-feedback').text('');
                $('#user_id').val(null).trigger('change');
                $('#done_at_group').hide();
            }

            // Tombol Tambah
            $('#btn-add').on('click', function() {
                resetForm();
                $('#formModalLabel').text('Tambah Proyek Baru');
            });

            // Tombol Simpan (Create & Update)
            $('#btn-save').on('click', function() {
                var id = $('#projectId').val();
                var url = id ? "{{ url('project-internal') }}/" + id :
                    "{{ route('project-internal.store') }}";
                var method = id ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    type: method,
                    data: $('#projectForm').serialize(),
                    success: function(response) {
                        if (response.success) {
                            $('#formModal').modal('hide');
                            table.ajax.reload();
                            showSuccessAlert(response.message);
                        }
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON.errors;
                        $('.form-control').removeClass('is-invalid');
                        $('.invalid-feedback').text('');
                        $.each(errors, function(key, value) {
                            $('#' + key).addClass('is-invalid');
                            $('#' + key).next('.invalid-feedback').text(value[0]);
                        });
                    }
                });
            });

            // Tombol Edit
            $('body').on('click', '.btn-edit', function() {
                var id = $(this).data('id');
                $.get("{{ url('project-internal') }}/" + id, function(response) {
                    if (response.success) {
                        var data = response.data;
                        resetForm();
                        $('#formModalLabel').text('Edit Proyek');
                        $('#projectId').val(data.id);
                        $('#name').val(data.name);
                        $('#user_id').val(data.user_id).trigger('change');
                        $('#datetime').val(data.datetime.substring(0, 16));
                        $('#description').val(data.description);
                        $('#status').val(data.status);

                        if (data.status === 'done') {
                            $('#done_at_group').show();
                            $('#done_at').val(data.done_at ? data.done_at.substring(0, 16) : '');
                        }

                        $('#formModal').modal('show');
                    }
                });
            });

            // Tombol Hapus
            $('body').on('click', '.btn-delete', function() {
                var id = $(this).data('id');
                showDeleteConfirmation(function() {
                    $.ajax({
                        url: "{{ url('project-internal') }}/" + id,
                        type: 'DELETE',
                        success: function(response) {
                            if (response.success) {
                                table.ajax.reload();
                                showSuccessAlert(response.message);
                            }
                        },
                        error: function(xhr) {
                            showErrorAlert(xhr.responseJSON.message);
                        }
                    });
                });
            });

        });
    </script>
@endsection
