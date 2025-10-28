@extends('inc.layout')
@section('title', 'Persetujuan Material (QA/QC)')

@section('extended-css')
    <link rel="stylesheet" media="screen, print" href="/css/datagrid/datatables/datatables.bundle.css">
@endsection

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">QA/QC</a></li>
            <li class="breadcrumb-item active">Persetujuan Material</li>
        </ol>
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Kamus Material Proyek</h2>
                        <div class="panel-toolbar">
                            <button class="btn btn-primary btn-sm" id="addMaterialBtn">
                                <i class="fal fa-plus"></i> Tambah Material
                            </button>
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table id="material-table" class="table table-bordered table-hover table-striped w-100">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No. Dokumen Terkait</th> {{-- <-- KOLOM BARU --}}
                                        <th>Nama Material</th>
                                        <th>Merek</th>
                                        <th>Qty Disetujui</th> {{-- <-- KOLOM BARU --}}
                                        <th>Status</th>
                                        <th>Diajukan Oleh</th>
                                        <th>Direview Oleh</th>
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

    <!-- Modal Form Material Approval -->
    <div class="modal fade" id="materialModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <form id="materialForm" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="material_id" id="material_id">
                        <div class="form-group">
                            <label class="form-label" for="document_id">Dokumen Referensi (Surat Pengajuan)</label>
                            <select class="form-control" id="document_id" name="document_id">
                                <option value="">-- Pilih Dokumen (Opsional) --</option>
                                @foreach ($documents as $doc)
                                    <option value="{{ $doc->id }}">{{ $doc->document_number }} - {{ $doc->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="material_name">Nama Material</label>
                                    <input type="text" id="material_name" name="material_name" class="form-control"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label" for="quantity">Kuantitas</label>
                                    <input type="number" id="quantity" name="quantity" class="form-control"
                                        step="0.01">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label" for="satuan_id">Satuan</label>
                                    <select class="form-control" id="satuan_id" name="satuan_id">
                                        <option value="">-- Pilih Satuan --</option>
                                        @foreach ($satuans as $satuan)
                                            <option value="{{ $satuan->id }}">{{ $satuan->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="brand">Merek</label>
                                    <input type="text" id="brand" name="brand" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="type_or_model">Tipe/Model</label>
                                    <input type="text" id="type_or_model" name="type_or_model" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="technical_specifications">Spesifikasi Teknis</label>
                            <textarea class="form-control" id="technical_specifications" name="technical_specifications" rows="3"
                                required></textarea>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="status">Status</label>
                                    <select class="form-control" id="status" name="status" required>
                                        <option value="Submitted">Diajukan (Submitted)</option>
                                        <option value="Approved">Disetujui (Approved)</option>
                                        <option value="Rejected">Ditolak (Rejected)</option>
                                        <option value="Revision Required">Perlu Revisi (Revision Required)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="reviewed_by">Direview Oleh</label>
                                    <select class="form-control" id="reviewed_by" name="reviewed_by">
                                        <option value="">-- Pilih Reviewer --</option>
                                        {{-- This will be populated by users --}}
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Foto Material</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="image" name="image">
                                <label class="custom-file-label" for="image">Pilih gambar...</label>
                            </div>
                            <span class="help-block" id="image-help-block">Kosongkan jika tidak ada foto. (JPG,
                                PNG)</span>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="remarks">Catatan/Alasan</label>
                            <textarea class="form-control" id="remarks" name="remarks" rows="2"></textarea>
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
            // Setup CSRF token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Init Select2
            $('#status, #reviewed_by, #document_id, #satuan_id').select2({
                dropdownParent: $('#materialModal'),
                width: '100%'
            });

            // File input label handler
            $('.custom-file-input').on('change', function() {
                let fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').html(fileName || 'Pilih gambar...');
            });

            // DataTable Init
            var table = $('#material-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('material-approvals.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'document_number',
                        name: 'document.document_number'
                    },
                    {
                        data: 'material_name',
                        name: 'material_name'
                    },
                    {
                        data: 'brand',
                        name: 'brand'
                    },
                    {
                        data: null,
                        name: 'quantity',
                        className: 'text-right',
                        render: function(data, type, row) {
                            // Tampilkan qty dan satuan
                            return (row.quantity ? parseFloat(row.quantity) : '-') + ' ' + (row
                                .satuan ? row.satuan.nama : '');
                        }
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'submitter.name',
                        name: 'submitter.name'
                    },
                    {
                        data: 'reviewer.name',
                        name: 'reviewer.name',
                        defaultContent: "-"
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                responsive: true
            });

            // Add Material button
            $('#addMaterialBtn').click(function() {
                $('#material_id').val('');
                $('#materialForm').trigger("reset");
                $('#modalTitle').html("Tambah Pengajuan Material");
                $('#document_id, #status, #reviewed_by, #satuan_id').val(null).trigger('change');
                $('#materialModal').modal('show');
            });

            // Edit button
            $('body').on('click', '.edit-btn', function() {
                var id = $(this).data('id');
                $.get("{{ route('material-approvals.index') }}" + '/' + id + '/edit', function(data) {
                    $('#modalTitle').html("Edit Pengajuan Material");
                    $('#material_id').val(data.id);
                    $('#material_name').val(data.material_name);
                    $('#document_id').val(data.document_id).trigger('change');
                    $('#material_name').val(data.material_name);
                    $('#quantity').val(data.quantity); // <-- SET VALUE
                    $('#satuan_id').val(data.satuan_id).trigger('change'); // <-- SET VALUE
                    $('#brand').val(data.brand);
                    $('#type_or_model').val(data.type_or_model);
                    $('#technical_specifications').val(data.technical_specifications);
                    $('#status').val(data.status).trigger('change');
                    $('#reviewed_by').val(data.reviewed_by).trigger('change');
                    $('#remarks').val(data.remarks);
                    $('#materialModal').modal('show');
                })
            });

            // Form submission
            $('#materialForm').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $('#saveBtn').html('Menyimpan..').prop('disabled', true);
                var url = $('#material_id').val() ? "{{ route('material-approvals.index') }}" + '/' + $(
                    '#material_id').val() : "{{ route('material-approvals.store') }}";

                // Add method override for update
                if ($('#material_id').val()) {
                    formData.append('_method', 'PUT');
                }

                $.ajax({
                    type: "POST",
                    url: url,
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: (data) => {
                        $('#materialModal').modal('hide');
                        table.draw();
                        showSuccessAlert(data.success);
                    },
                    error: function(data) {
                        let errorMsg = "Terjadi kesalahan. Silakan coba lagi.";
                        if (data.responseJSON && data.responseJSON.errors) {
                            errorMsg = Object.values(data.responseJSON.errors).join('\n');
                        }
                        showErrorAlertNoRefresh(errorMsg);
                    },
                    complete: function() {
                        $('#saveBtn').html('Simpan').prop('disabled', false);
                    }
                });
            });

            // Delete button
            $('body').on('click', '.delete-btn', function() {
                var id = $(this).data("id");
                showDeleteConfirmation(function() {
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('material-approvals.store') }}" + '/' + id,
                        success: function(data) {
                            table.draw();
                            showSuccessAlert(data.success);
                        },
                        error: function(data) {
                            showErrorAlertNoRefresh('Gagal menghapus data.');
                        }
                    });
                });
            });
        });
    </script>
@endsection
