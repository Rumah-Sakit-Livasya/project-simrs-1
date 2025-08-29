@extends('inc.layout') {{-- Ganti dengan layout utama Anda --}}
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <ol class="breadcrumb bg-primary-300">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item">Master Data</li>
            <li class="breadcrumb-item active">Kategori Diagnosa Keperawatan</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
        </ol>
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Daftar Kategori Diagnosa Keperawatan</h2>
                        <div class="panel-toolbar">
                            <button class="btn btn-primary btn-sm" id="add-category">Tambah Kategori</button>
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table id="categories-table" class="table table-bordered table-hover table-striped w-100">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Kategori</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- ================================= MODAL SECTION ================================= --}}
    <div class="modal fade" id="category-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="category-form">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-title">Modal Title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="fal fa-times"></i></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="category-id">
                        <div class="form-group">
                            <label for="name">Nama Kategori</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                            <div class="invalid-feedback" id="name-error"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary" id="save-btn">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('plugin')
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    <script>
        $(function() {
            // Setup CSRF token untuk semua request AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Inisialisasi Datatables
            var table = $('#categories-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('api/simrs/master-data/diagnosa-keperawatan/diagnosis-categories') }}",
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

            // Tombol Tambah Kategori
            $('#add-category').click(function() {
                $('#category-id').val('');
                $('#category-form').trigger("reset");
                $('#modal-title').html("Tambah Kategori Baru");
                $('#category-modal').modal('show');
            });

            // Tombol Edit
            $('body').on('click', '.edit-btn', function() {
                var id = $(this).data('id');
                $.get("{{ url('api/simrs/master-data/diagnosa-keperawatan/diagnosis-categories') }}/" + id +
                    '/edit',
                    function(data) {
                        $('#modal-title').html("Edit Kategori");
                        $('#category-id').val(data.id);
                        $('#name').val(data.name);
                        $('#category-modal').modal('show');
                    });
            });

            // Tombol Simpan (Create & Update)
            $('#save-btn').click(function(e) {
                e.preventDefault();
                var id = $('#category-id').val();
                var url = id ?
                    "{{ url('api/simrs/master-data/diagnosa-keperawatan/diagnosis-categories') }}/" + id :
                    "{{ url('api/simrs/master-data/diagnosa-keperawatan/diagnosis-categories') }}";
                var method = id ? 'PUT' : 'POST';

                $.ajax({
                    data: $('#category-form').serialize(),
                    url: url,
                    type: method,
                    dataType: 'json',
                    success: function(response) {
                        $('#category-form').trigger("reset");
                        $('#category-modal').modal('hide');
                        table.draw();
                        // Tampilkan notifikasi sukses (contoh pakai SweetAlert2)
                        Swal.fire('Sukses!', response.success, 'success');
                    },
                    error: function(data) {
                        console.log('Error:', data);
                        // Handle error validasi
                        alert('Terjadi kesalahan. Cek konsol untuk detail.');
                    }
                });
            });

            // Tombol Hapus
            $('body').on('click', '.delete-btn', function() {
                var id = $(this).data("id");

                // Konfirmasi penghapusan (contoh pakai SweetAlert2)
                Swal.fire({
                    title: 'Anda yakin?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "DELETE",
                            url: "{{ url('api/simrs/master-data/diagnosa-keperawatan/diagnosis-categories') }}/" +
                                id,
                            success: function(response) {
                                table.draw();
                                Swal.fire('Dihapus!', response.success, 'success');
                            },
                            error: function(data) {
                                console.log('Error:', data);
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
