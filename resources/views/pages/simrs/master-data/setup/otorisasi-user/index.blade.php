@extends('inc.layout')
@section('title', 'Otorisasi User')

@section('extended-css')
    {{-- Tambahkan CSS untuk Select2 jika diperlukan --}}
    <link rel="stylesheet" media="screen, print" href="/css/formplugins/select2/select2.bundle.css">
@endsection

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Manajemen <span class="fw-300"><i>Otorisasi User</i></span>
                        </h2>
                        <div class="panel-toolbar">
                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#otorisasiModal"
                                id="add-btn">
                                <i class="fal fa-plus"></i> Tambah Otorisasi
                            </button>
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <!-- datatable start -->
                            <table id="otorisasiTable" class="table table-bordered table-hover table-striped w-100">
                                <thead>
                                    <tr>
                                        <th style="width: 30px;">No</th>
                                        <th>Nama User</th>
                                        <th>Tipe Otorisasi</th>
                                        <th style="width: 80px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data akan di-load oleh DataTables -->
                                </tbody>
                            </table>
                            <!-- datatable end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal -->
    <div class="modal fade" id="otorisasiModal" tabindex="-1" role="dialog" aria-labelledby="otorisasiModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">Tambah Otorisasi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="otorisasiForm">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="otorisasi_id">
                        <div class="form-group">
                            <label class="form-label" for="user_id">User</label>
                            <select class="form-control select2" id="user_id" name="user_id" required
                                style="width: 100%;">
                                <option value="">Pilih User...</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="otorisasi_type">Tipe Otorisasi</label>
                            <input type="text" id="otorisasi_type" name="otorisasi_type" class="form-control"
                                placeholder="Masukkan tipe otorisasi" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="save-btn">Simpan</button>
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
            // Setup CSRF Token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Inisialisasi Select2
            $('.select2').select2({
                dropdownParent: $('#otorisasiModal')
            });

            // Inisialisasi DataTables
            var table = $('#otorisasiTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('otorisasi-user.data') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'user_name',
                        name: 'user.name'
                    },
                    {
                        data: 'otorisasi_type',
                        name: 'otorisasi_type'
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
            $('#add-btn').click(function() {
                $('#otorisasiForm').trigger("reset");
                $('#otorisasi_id').val('');
                $('#modal-title').html("Tambah Otorisasi");
                $('#user_id').val('').trigger('change'); // Reset Select2
            });

            // Tombol Edit
            $('body').on('click', '.edit-btn', function() {
                var url = $(this).data('url');
                $('#modal-title').html("Edit Otorisasi");
                $.get(url, function(data) {
                    $('#otorisasiModal').modal('show');
                    $('#otorisasi_id').val(data.id);
                    $('#user_id').val(data.user_id).trigger('change');
                    $('#otorisasi_type').val(data.otorisasi_type);
                })
            });

            // Submit Form
            $('#otorisasiForm').submit(function(e) {
                e.preventDefault();
                $('#save-btn').html('Menyimpan...').attr('disabled', true);

                var id = $('#otorisasi_id').val();
                var url = id ? "{{ url('master-data/otorisasi-user') }}/" + id :
                    "{{ route('otorisasi-user.store') }}";
                var method = id ? 'PUT' : 'POST';

                $.ajax({
                    data: $(this).serialize(),
                    url: url,
                    type: method,
                    dataType: 'json',
                    success: function(response) {
                        $('#otorisasiModal').modal('hide');
                        showSuccessAlert(response.success);
                        table.draw();
                        $('#save-btn').html('Simpan').attr('disabled', false);
                    },
                    error: function(data) {
                        console.error('Error:', data);
                        showErrorAlertNoRefresh('Terjadi kesalahan saat menyimpan data.');
                        $('#save-btn').html('Simpan').attr('disabled', false);
                    }
                });
            });

            // Tombol Hapus
            $('body').on('click', '.delete-btn', function() {
                var url = $(this).data('url');
                showDeleteConfirmation(function() {
                    $.ajax({
                        type: "DELETE",
                        url: url,
                        success: function(response) {
                            showSuccessAlert(response.success);
                            table.draw();
                        },
                        error: function(data) {
                            console.error('Error:', data);
                            showErrorAlertNoRefresh(
                                'Terjadi kesalahan saat menghapus data.');
                        }
                    });
                });
            });
        });
    </script>
@endsection
