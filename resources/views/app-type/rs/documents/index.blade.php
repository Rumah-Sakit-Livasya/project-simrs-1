@extends('inc.layout')
@section('title', 'Manajemen Dokumen')

@section('extended-css')
    <link rel="stylesheet" media="screen, print" href="/css/datagrid/datatables/datatables.bundle.css">
@endsection

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">Proyek</a></li>
            <li class="breadcrumb-item active">Manajemen Dokumen</li>
        </ol>
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Daftar Dokumen Proyek</h2>
                        <div class="panel-toolbar">
                            <button class="btn btn-primary btn-sm" id="addDocumentBtn">
                                <i class="fal fa-plus"></i> Tambah Dokumen
                            </button>
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table id="document-table" class="table table-bordered table-hover table-striped w-100">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No. Dokumen</th>
                                        <th>Judul</th>
                                        <th>Tipe</th>
                                        <th>Status</th>
                                        <th>Uploader</th>
                                        <th>Tgl Upload</th>
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

    <!-- Modal Form Dokumen -->
    <div class="modal fade" id="documentModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <form id="documentForm" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="document_id" id="document_id">

                        {{-- Kontainer untuk notifikasi revisi --}}
                        <div id="revision-alert" class="alert alert-warning d-none" role="alert">
                            <strong>Perhatian!</strong> Anda sedang mengedit data. Jika Anda meng-upload file baru, sistem
                            akan membuat <strong>revisi baru</strong> dari dokumen ini. Jika tidak, hanya informasi teks
                            yang akan diperbarui.
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="document_number">Nomor Dokumen/Agenda</label>
                            <input type="text" id="document_number" name="document_number" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="title">Judul Dokumen</label>
                            <input type="text" id="title" name="title" class="form-control" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="document_type_id">Tipe Dokumen</label>
                                    <select class="form-control" id="document_type_id" name="document_type_id" required>
                                        <option value="" disabled selected>-- Pilih Tipe Dokumen --</option>
                                        @foreach ($documentTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="status">Status</label>
                                    <select class="form-control" id="status" name="status" required>
                                        <option value="Diajukan">Diajukan</option>
                                        <option value="Diterima">Diterima</option>
                                        <option value="Direview">Direview</option>
                                        <option value="Revisi">Revisi</option>
                                        <option value="Disetujui">Disetujui</option>
                                        <option value="Dibalas">Dibalas</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="person_in_charge_id">Penanggung Jawab (PIC)</label>
                            <select class="form-control" id="person_in_charge_id" name="person_in_charge_id">
                                <option value="" selected>-- Pilih PIC (Opsional) --</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Upload File <span id="file-required-star"
                                    class="text-danger">*</span></label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="file" name="file">
                                <label class="custom-file-label" for="file">Pilih file...</label>
                            </div>
                            <span class="help-block" id="file-help-block">File: PDF, DOC, XLS, DWG, JPG, PNG. Max:
                                10MB</span>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="description">Deskripsi</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
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
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script>
        $(document).ready(function() {
            // Setup CSRF token for all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Init Select2
            $('#document_type_id, #status, #person_in_charge_id').select2({
                dropdownParent: $('#documentModal'),
                width: '100%'
            });

            // Update file input label on change
            $('.custom-file-input').on('change', function() {
                let fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').html(fileName || 'Pilih file...');
            });

            // DataTable Init
            var table = $('#document-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('documents.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'document_number',
                        name: 'document_number'
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'type_name',
                        name: 'documentType.name'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'uploader.name',
                        name: 'uploader.name'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        render: data => new Date(data).toLocaleDateString('id-ID')
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                responsive: true,
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                        extend: 'pdfHtml5',
                        text: 'PDF',
                        titleAttr: 'Generate PDF'
                    },
                    {
                        extend: 'excelHtml5',
                        text: 'Excel',
                        titleAttr: 'Generate Excel'
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        titleAttr: 'Print Table'
                    }
                ]
            });

            // Reset and prepare modal for "Tambah"
            $('#addDocumentBtn').on('click', function() {
                $('#documentForm').trigger("reset");
                $('#document_id').val('');
                $('#modalTitle').text('Tambah Dokumen Baru');
                $('.select2').val(null).trigger('change');
                $('#status').val('Diajukan').trigger('change');
                $('#revision-alert').addClass('d-none');
                $('#file').prop('required', true); // File wajib saat membuat baru
                $('#file-required-star').removeClass('d-none');
                $('#file-help-block').text('File: PDF, DOC, XLS, DWG, JPG, PNG. Max: 10MB');
                $('#documentModal').modal('show');
            });

            // Prepare modal for "Edit"
            $('body').on('click', '.edit-btn', function() {
                let id = $(this).data('id');
                $.get("{{ url('documents') }}/" + id + "/edit", function(data) {
                    $('#documentForm').trigger("reset");
                    $('#modalTitle').text('Edit Dokumen: ' + data.title);
                    $('#document_id').val(data.id);
                    $('#document_number').val(data.document_number);
                    $('#title').val(data.title);
                    $('#document_type_id').val(data.document_type_id).trigger('change');
                    $('#status').val(data.status).trigger('change');
                    $('#person_in_charge_id').val(data.person_in_charge_id).trigger('change');
                    $('#description').val(data.description);

                    $('#revision-alert').removeClass('d-none');
                    $('#file').prop('required', false); // File tidak wajib saat edit
                    $('#file-required-star').addClass('d-none');
                    $('#file-help-block').html(
                        'Kosongkan jika tidak ingin meng-upload revisi baru. File saat ini: <strong>' +
                        data.file_name + '</strong>');

                    $('#documentModal').modal('show');
                });
            });

            // Handle Form Submission (Create and Update)
            $('#documentForm').on('submit', function(e) {
                e.preventDefault();
                $('#saveBtn').html('Menyimpan...').prop('disabled', true);

                var formData = new FormData(this);
                var id = $('#document_id').val();
                var url = id ? "{{ url('documents') }}/" + id : "{{ route('documents.store') }}";

                // Untuk update, kita gunakan method POST tapi di-override dengan _method
                // Ini lebih sesuai dengan standar form-data multipart
                if (id) {
                    formData.append('_method', 'PUT');
                }

                $.ajax({
                    url: url,
                    type: "POST", // Selalu POST untuk FormData
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $('#documentModal').modal('hide');
                        table.ajax.reload();
                        showSuccessAlert(response.success);
                    },
                    error: function(xhr) {
                        let errorMsg = "Terjadi kesalahan. Silakan coba lagi.";
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errorMsg = Object.values(xhr.responseJSON.errors).join('\n');
                        }
                        showErrorAlertNoRefresh(errorMsg);
                    },
                    complete: function() {
                        $('#saveBtn').html('Simpan').prop('disabled', false);
                    }
                });
            });

            // Handle Delete
            $('body').on('click', '.delete-btn', function() {
                var id = $(this).data('id');
                showDeleteConfirmation(function() {
                    $.ajax({
                        url: "{{ url('documents') }}/" + id,
                        type: "DELETE", // Langsung gunakan method DELETE
                        success: function(response) {
                            table.ajax.reload();
                            showSuccessAlert(response.success);
                        },
                        error: function(xhr) {
                            showErrorAlertNoRefresh("Gagal menghapus dokumen.");
                        }
                    });
                });
            });

            $('body').on('click', '.view-btn', function() {
                var id = $(this).data('id');
                var url = `{{ url('documents') }}/${id}/preview`;

                // Tentukan ukuran dan properti popup window
                var width = 900;
                var height = 700;
                var left = (screen.width / 2) - (width / 2);
                var top = (screen.height / 2) - (height / 2);

                var features =
                    `width=${width},height=${height},left=${left},top=${top},resizable=yes,scrollbars=yes`;

                // Buka popup window baru
                window.open(url, 'DocumentPreview', features);
            });
        });
    </script>
@endsection
