@extends('inc.layout')
@section('title', 'Master Jenis Linen')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="container-fluid py-4">
            <div class="panel">
                <div class="panel-hdr">
                    <h2>Master Data - Jenis Linen</h2>
                </div>
                <div class="panel-container show">
                    <div class="panel-content">
                        <button class="btn btn-success mb-3" id="createNewLinenType">
                            <i class="fas fa-plus mr-2"></i>Tambah Jenis Linen Baru
                        </button>
                        <table class="table table-bordered table-hover w-100" id="linenTypeTable">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Nama Jenis Linen</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="ajaxModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="linenTypeForm" name="linenTypeForm">
                            <input type="hidden" name="id" id="linen_type_id">
                            <div class="form-group">
                                <label for="name" class="control-label">Nama Jenis Linen</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Contoh: Sprei" required>
                                <div class="invalid-feedback">Nama tidak boleh kosong.</div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary" id="saveBtn">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script>
        $(function() {
            // Setup CSRF token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Inisialisasi DataTable
            const table = $('#linenTypeTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('linen-types.index') }}",
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
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            // Tombol Tambah Data
            $('#createNewLinenType').click(function() {
                $('#saveBtn').val("create-item");
                $('#linen_type_id').val('');
                $('#linenTypeForm').trigger("reset");
                $('#modalTitle').html("Tambah Jenis Linen Baru");
                $('#ajaxModal').modal('show');
                $('#name').removeClass('is-invalid');
            });

            // Tombol Edit Data
            $('body').on('click', '.editLinenType', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');

                $('#modalTitle').html("Edit Jenis Linen");
                $('#linen_type_id').val(id);
                $('#name').val(name);
                $('#ajaxModal').modal('show');
                $('#name').removeClass('is-invalid');
            });

            // Tombol Simpan (Create & Update)
            $('#saveBtn').click(function(e) {
                e.preventDefault();
                $(this).html('Menyimpan..').prop('disabled', true);
                $('#name').removeClass('is-invalid');

                $.ajax({
                    data: $('#linenTypeForm').serialize(),
                    url: "{{ route('linen-types.store') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {
                        $('#linenTypeForm').trigger("reset");
                        $('#ajaxModal').modal('hide');
                        table.draw();
                        // Tampilkan notifikasi sukses (misal: menggunakan Toastr atau SweetAlert)
                        alert(data.success);
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            // Tangani error validasi
                            const errors = xhr.responseJSON.errors;
                            if (errors.name) {
                                $('#name').addClass('is-invalid').next('.invalid-feedback')
                                    .text(errors.name[0]);
                            }
                        } else {
                            alert("Terjadi kesalahan. Silakan coba lagi.");
                        }
                        console.log('Error:', xhr);
                    },
                    complete: function() {
                        $('#saveBtn').html('Simpan').prop('disabled', false);
                    }
                });
            });

            // Tombol Hapus Data
            $('body').on('click', '.deleteLinenType', function() {
                const id = $(this).data("id");

                if (confirm("Apakah Anda yakin ingin menghapus data ini?")) {
                    $.ajax({
                        type: "DELETE",
                        url: `{{ url('master/linen-types') }}/${id}`,
                        success: function(data) {
                            table.draw();
                            alert(data.success);
                        },
                        error: function(xhr) {
                            alert(xhr.responseJSON.error || 'Gagal menghapus data.');
                            console.log('Error:', xhr);
                        }
                    });
                }
            });
        });
    </script>
@endsection
