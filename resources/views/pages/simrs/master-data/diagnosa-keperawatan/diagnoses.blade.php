@extends('inc.layout')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <ol class="breadcrumb bg-primary-300">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item">Master Data</li>
            <li class="breadcrumb-item active">Diagnosa Keperawatan</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
        </ol>
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Daftar Diagnosa Keperawatan</h2>
                        <div class="panel-toolbar">
                            <button class="btn btn-primary btn-sm" id="add-diagnosis">Tambah Diagnosa</button>
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table id="diagnoses-table" class="table table-bordered table-hover table-striped w-100">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Diagnosa</th>
                                        <th>Kategori</th>
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
    <div class="modal fade" id="diagnosis-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="diagnosis-form">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-title">Modal Title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="fal fa-times"></i></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="diagnosis-id">
                        <div class="form-group">
                            <label for="category_id">Kategori</label>
                            <select class="form-control" name="category_id" id="category_id" style="width: 100%;"
                                required></select>
                        </div>
                        <div class="form-group">
                            <label for="code">Kode Diagnosa</label>
                            <input type="text" class="form-control" id="code" name="code" required>
                        </div>
                        <div class="form-group">
                            <label for="diagnosa">Nama Diagnosa</label>
                            <textarea class="form-control" id="diagnosa" name="diagnosa" rows="3" required></textarea>
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

            // Inisialisasi Select2
            $('#category_id').select2({
                placeholder: 'Pilih Kategori',
                dropdownParent: $('#diagnosis-modal'),
                ajax: {
                    url: "{{ url('api/simrs/master-data/diagnosa-keperawatan/diagnosis-categories/select-all') }}",
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                }
            });

            // Inisialisasi Datatables
            var table = $('#diagnoses-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('api/simrs/master-data/diagnosa-keperawatan/nursing-diagnoses') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'code',
                        name: 'code'
                    },
                    {
                        data: 'diagnosa',
                        name: 'diagnosa'
                    },
                    {
                        data: 'category_name',
                        name: 'category.name'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            // Tombol Tambah Diagnosa
            $('#add-diagnosis').click(function() {
                $('#diagnosis-id').val('');
                $('#diagnosis-form').trigger("reset");
                $('#category_id').val(null).trigger('change');
                $('#modal-title').html("Tambah Diagnosa Baru");
                $('#diagnosis-modal').modal('show');
            });

            // Tombol Edit
            $('body').on('click', '.edit-btn', function() {
                var id = $(this).data('id');
                $.get("{{ url('api/simrs/master-data/diagnosa-keperawatan/nursing-diagnoses') }}/" + id +
                    '/edit',
                    function(data) {
                        $('#modal-title').html("Edit Diagnosa");
                        $('#diagnosis-id').val(data.id);
                        $('#code').val(data.code);
                        $('#diagnosa').val(data.diagnosa);

                        // Set value untuk Select2
                        if (data.category) {
                            var option = new Option(data.category.name, data.category.id, true, true);
                            $('#category_id').append(option).trigger('change');
                        }

                        $('#diagnosis-modal').modal('show');
                    });
            });

            // Tombol Simpan (Create & Update)
            $('#save-btn').click(function(e) {
                e.preventDefault();
                var id = $('#diagnosis-id').val();
                var url = id ?
                    "{{ url('api/simrs/master-data/diagnosa-keperawatan/nursing-diagnoses') }}/" + id :
                    "{{ url('api/simrs/master-data/diagnosa-keperawatan/nursing-diagnoses') }}";
                var method = id ? 'PUT' : 'POST';

                $.ajax({
                    data: $('#diagnosis-form').serialize(),
                    url: url,
                    type: method,
                    dataType: 'json',
                    success: function(response) {
                        $('#diagnosis-form').trigger("reset");
                        $('#diagnosis-modal').modal('hide');
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
                            url: "{{ url('api/simrs/master-data/diagnosa-keperawatan/nursing-diagnoses') }}/" +
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
