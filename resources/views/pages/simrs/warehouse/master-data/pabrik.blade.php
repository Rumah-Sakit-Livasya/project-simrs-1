@extends('inc.layout')
@section('title', 'Manajemen Pabrik')

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <!-- Page Title -->
        <div class="subheader">
            <h1 class="subheader-title">
                <i class='subheader-icon fal fa-industry-alt'></i> Manajemen Pabrik
                <small>
                    Pengelolaan data master pabrik.
                </small>
            </h1>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Daftar Pabrik
                        </h2>
                        <div class="panel-toolbar">
                            <a href="{{ route('warehouse-pabrik.index') }}" class="btn btn-info btn-sm mr-2"
                                title="Import/Export Data">
                                <i class="fal fa-upload"></i> Migrasi
                            </a>
                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#pabrik-modal"
                                id="add-new-pabrik">
                                <i class="fal fa-plus"></i> Tambah Pabrik
                            </button>
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <!-- datatable start -->
                            <table id="pabrik-datatable" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama Pabrik</th>
                                        <th>Alamat</th>
                                        <th>Telepon</th>
                                        <th>Contact Person</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data will be loaded via AJAX -->
                                </tbody>
                            </table>
                            <!-- datatable end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Pabrik Modal -->
    <div class="modal fade" id="pabrik-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pabrik-modal-title">Tambah Pabrik Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="pabrik-form">
                        @csrf
                        <input type="hidden" id="pabrik_id" name="id">
                        <div class="form-group">
                            <label class="form-label" for="nama">Nama Pabrik</label>
                            <input type="text" id="nama" name="nama" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="alamat">Alamat</label>
                            <textarea id="alamat" name="alamat" class="form-control"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="telp">Telepon</label>
                                    <input type="text" id="telp" name="telp" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="contact_person_phone">No. HP Contact
                                        Person</label>
                                    <input type="text" id="contact_person_phone" name="contact_person_phone"
                                        class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="contact_person">Nama Contact Person</label>
                            <input type="text" id="contact_person" name="contact_person" class="form-control">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <div class="frame-wrap">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input" id="status-aktif" name="aktif"
                                        value="1" checked>
                                    <label class="custom-control-label" for="status-aktif">Aktif</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input" id="status-nonaktif" name="aktif"
                                        value="0">
                                    <label class="custom-control-label" for="status-nonaktif">Tidak Aktif</label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="save-pabrik-btn">Simpan</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script>
        $(document).ready(function() {
            // Setup CSRF token for all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Initialize datatable
            var table = $('#pabrik-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('warehouse.master-data.pabrik.data') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'alamat',
                        name: 'alamat'
                    },
                    {
                        data: 'telp',
                        name: 'telp'
                    },
                    {
                        data: 'contact_person',
                        name: 'contact_person'
                    },
                    {
                        data: 'status_label',
                        name: 'aktif',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                responsive: true,
                order: [
                    [0, 'desc']
                ], // Default order by ID descending
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                        extend: 'pdfHtml5',
                        text: 'PDF',
                        titleAttr: 'Generate PDF',
                        className: 'btn-outline-danger btn-sm mr-1'
                    },
                    {
                        extend: 'excelHtml5',
                        text: 'Excel',
                        titleAttr: 'Generate Excel',
                        className: 'btn-outline-success btn-sm mr-1'
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        titleAttr: 'Print Table',
                        className: 'btn-outline-primary btn-sm'
                    }
                ]
            });

            // Show Add Modal
            $('#add-new-pabrik').on('click', function() {
                $('#pabrik-form')[0].reset();
                $('#pabrik_id').val('');
                $('#pabrik-modal-title').text('Tambah Pabrik Baru');
                $('input[name="aktif"][value="1"]').prop('checked', true); // Default to 'Aktif'
            });

            // Show Edit Modal
            $('body').on('click', '.edit-btn', function() {
                var id = $(this).data('id');
                $.get("{{ url('simrs/warehouse/master-data/pabrik') }}/" + id, function(response) {
                    if (response.success && response.data) {
                        $('#pabrik-modal-title').text('Edit Pabrik');
                        $('#pabrik_id').val(response.data.id ?? '');
                        $('#nama').val(response.data.nama ?? '');
                        $('#alamat').val(response.data.alamat ?? '');
                        $('#telp').val(response.data.telp ?? '');
                        $('#contact_person').val(response.data.contact_person ?? '');
                        $('#contact_person_phone').val(response.data.contact_person_phone ?? '');
                        $('input[name="aktif"][value="' + (response.data.aktif ? 1 : 0) + '"]')
                            .prop('checked', true);
                        $('#pabrik-modal').modal('show');
                    } else {
                        showErrorAlert('Data tidak ditemukan atau gagal mengambil data.');
                    }
                });
            });

            // Save or Update Pabrik
            $('#save-pabrik-btn').on('click', function(e) {
                e.preventDefault();
                var id = $('#pabrik_id').val();
                var url = id ? "{{ url('simrs/warehouse/master-data/pabrik') }}/" + id :
                    "{{ route('warehouse.master-data.pabrik.store') }}";
                var method = id ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    type: method,
                    data: $('#pabrik-form').serialize(),
                    success: function(response) {
                        $('#pabrik-modal').modal('hide');
                        showSuccessAlert(response.success);
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        // Handle validation errors
                        var errors = xhr.responseJSON.errors;
                        var errorMessage = '';
                        $.each(errors, function(key, value) {
                            errorMessage += value[0] + '\n';
                        });
                        showErrorAlertNoRefresh(errorMessage);
                    }
                });
            });

            // Delete Pabrik
            $('body').on('click', '.delete-btn', function() {
                var id = $(this).data('id');
                showDeleteConfirmation(function() {
                    $.ajax({
                        url: "{{ url('simrs/warehouse/master-data/pabrik') }}/" + id,
                        type: 'DELETE',
                        success: function(response) {
                            showSuccessAlert(response.success);
                            table.ajax.reload();
                        },
                        error: function(xhr) {
                            showErrorAlert(
                                'Gagal menghapus data. Silakan coba lagi.');
                        }
                    });
                });
            });
        });
    </script>
@endsection
