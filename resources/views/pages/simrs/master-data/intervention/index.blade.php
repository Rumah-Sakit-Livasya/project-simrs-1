@extends('inc.layout')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <ol class="breadcrumb bg-primary-300">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item">Master Data</li>
            <li class="breadcrumb-item active">Intervensi Keperawatan</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
        </ol>
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Daftar Intervensi Keperawatan</h2>
                        <div class="panel-toolbar">
                            <button class="btn btn-primary btn-sm" id="add-intervention">Tambah Intervensi</button>
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table id="interventions-table" class="table table-bordered table-hover table-striped w-100">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Intervensi</th>
                                        <th>Tipe Rawat</th>
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

    <!-- Modal -->
    <div class="modal fade" id="intervention-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="intervention-form">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-title">Modal Title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="fal fa-times"></i></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="intervention-id">
                        <div class="form-group">
                            <label for="name">Nama Intervensi</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="tipe_rawat">Tipe Rawat</label>
                            <select class="form-control" name="tipe_rawat" id="tipe_rawat" required>
                                <option value="" disabled selected>Pilih Tipe Rawat</option>
                                <option value="all">Semua Tipe Rawat</option>
                                <option value="rawat-inap">Rawat Inap</option>
                                <option value="rawat-inap">Rawat Inap</option>
                            </select>
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

            $('#tipe_rawat').select2({
                placeholder: 'Pilih Tipe Rawat',
                dropdownParent: $('#intervention-modal') // Penting agar Select2 berfungsi di dalam modal
            });

            // Inisialisasi Datatables
            var table = $('#interventions-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('api/simrs/master-data/interventions') }}",
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
                        data: 'tipe_rawat',
                        name: 'tipe_rawat'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            // Tombol Tambah Intervensi
            $('#add-intervention').click(function() {
                $('#intervention-id').val('');
                $('#intervention-form').trigger("reset");
                $('#tipe_rawat').val('').trigger('change');
                $('#modal-title').html("Tambah Intervensi Baru");
                $('#intervention-modal').modal('show');
            });

            // Tombol Edit
            $('body').on('click', '.edit-btn', function() {
                var id = $(this).data('id');
                $.get("{{ url('api/simrs/master-data/interventions') }}/" + id + '/edit', function(data) {
                    $('#modal-title').html("Edit Intervensi");
                    $('#intervention-id').val(data.id);
                    $('#name').val(data.name);
                    $('#tipe_rawat').val(data.tipe_rawat).trigger('change');
                    $('#intervention-modal').modal('show');
                });
            });

            // Tombol Simpan (Create & Update)
            $('#save-btn').click(function(e) {
                e.preventDefault();
                var id = $('#intervention-id').val();
                var url = id ?
                    "{{ url('api/simrs/master-data/interventions') }}/" + id :
                    "{{ url('api/simrs/master-data/interventions') }}";
                var method = id ? 'PUT' : 'POST';

                $.ajax({
                    data: $('#intervention-form').serialize(),
                    url: url,
                    type: method,
                    dataType: 'json',
                    success: function(response) {
                        $('#intervention-form').trigger("reset");
                        $('#intervention-modal').modal('hide');
                        table.draw();
                        Swal.fire('Sukses!', response.success, 'success');
                    },
                    error: function(data) {
                        console.log('Error:', data);
                        alert('Terjadi kesalahan. Cek konsol untuk detail.');
                    }
                });
            });

            // Tombol Hapus
            $('body').on('click', '.delete-btn', function() {
                var id = $(this).data("id");

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
                            url: "{{ url('api/simrs/master-data/interventions') }}/" + id,
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
