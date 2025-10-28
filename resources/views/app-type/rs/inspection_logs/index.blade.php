@extends('inc.layout')
@section('title', 'Log Inspeksi (QA/QC)')

@section('extended-css')
    <link rel="stylesheet" media="screen, print" href="/css/datagrid/datatables/datatables.bundle.css">
    <link rel="stylesheet" media="screen, print" href="/css/formplugins/bootstrap-datepicker/bootstrap-datepicker.css">
@endsection

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">QA/QC</a></li>
            <li class="breadcrumb-item active">Log Inspeksi</li>
        </ol>
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Catatan Inspeksi Proyek</h2>
                        <div class="panel-toolbar">
                            <button class="btn btn-primary btn-sm" id="addInspectionBtn">
                                <i class="fal fa-plus"></i> Tambah Log Inspeksi
                            </button>
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table id="inspection-table" class="table table-bordered table-hover table-striped w-100">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tgl Inspeksi</th>
                                        <th>Tipe</th>
                                        <th>Deskripsi</th>
                                        <th>Material Terkait</th>
                                        <th>Hasil</th>
                                        <th>Inspektor</th>
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

    <!-- Modal Form Inspection Log -->
    <div class="modal fade" id="inspectionModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <form id="inspectionForm">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="log_id" id="log_id">
                        <div class="form-group">
                            <label class="form-label">Tipe Inspeksi</label>
                            <div class="frame-wrap">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input" id="typeMaterial"
                                        name="inspection_type" value="Incoming Material" checked>
                                    <label class="custom-control-label" for="typeMaterial">Penerimaan Material</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input" id="typeWork" name="inspection_type"
                                        value="Work In Progress">
                                    <label class="custom-control-label" for="typeWork">Pekerjaan Terpasang</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="inspection_date">Tanggal Inspeksi</label>
                                    <input type="text" id="inspection_date" name="inspection_date"
                                        class="form-control datepicker" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="result">Hasil Inspeksi</label>
                                    <select class="form-control" id="result" name="result" required>
                                        <option value="Pass">Lulus (Pass)</option>
                                        <option value="Fail">Gagal (Fail)</option>
                                        <option value="Correction Required">Perlu Perbaikan</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Form group ini akan muncul/hilang tergantung tipe inspeksi --}}
                        <div id="material-section">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="material_approval_id">Material (Sesuai
                                            Persetujuan)</label>
                                        <select class="form-control" id="material_approval_id" name="material_approval_id">
                                            <option value="">-- Pilih Material --</option>
                                            @foreach ($approvedMaterials as $material)
                                                <option value="{{ $material->id }}"
                                                    data-doc-number="{{ $material->document->document_number ?? '' }}">
                                                    {{-- <-- Tambahkan data attribute --}}
                                                    {{ $material->material_name }} ({{ $material->brand }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="reference_document">No. Surat Jalan /
                                            Referensi</label>
                                        <input type="text" id="reference_document" name="reference_document"
                                            class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="description">Deskripsi Pekerjaan/Material yang
                                Diinspeksi</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required
                                placeholder="Contoh: Pengecekan keramik lantai 1, kuantitas 100 box"></textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="notes">Catatan / Tindak Lanjut</label>
                            <textarea class="form-control" id="notes" name="notes" rows="2"
                                placeholder="Contoh: 10 box keramik pecah, perlu diganti"></textarea>
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
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script>
        $(document).ready(function() {
            // Setup CSRF Token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Init Select2
            $('#result, #material_approval_id').select2({
                dropdownParent: $('#inspectionModal'),
                width: '100%'
            });

            // Init Datepicker
            $('.datepicker').datepicker({
                todayHighlight: true,
                autoclose: true,
                format: 'yyyy-mm-dd'
            });

            // Logic to show/hide material section based on inspection type
            $('input[name="inspection_type"]').change(function() {
                if ($(this).val() === 'Incoming Material') {
                    $('#material-section').slideDown();
                } else {
                    $('#material-section').slideUp();
                }
            });

            // Autofill Reference Document Number when a material is selected
            $('#material_approval_id').on('change', function() {
                // Dapatkan elemen <option> yang sedang dipilih
                var selectedOption = $(this).find('option:selected');

                // Ambil nilai dari data attribute 'data-doc-number'
                var docNumber = selectedOption.data('doc-number');

                // Jika ada nomor dokumen, isikan ke field reference_document
                if (docNumber) {
                    $('#reference_document').val(docNumber);
                }
            });
            // ==

            // DataTable Init
            var table = $('#inspection-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('inspection-logs.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'inspection_date',
                        name: 'inspection_date'
                    },
                    {
                        data: 'inspection_type',
                        name: 'inspection_type'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'material_name',
                        name: 'materialApproval.material_name'
                    },
                    {
                        data: 'result',
                        name: 'result'
                    },
                    {
                        data: 'inspector.name',
                        name: 'inspector.name'
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

            // Add Inspection button
            $('#addInspectionBtn').click(function() {
                $('#log_id').val('');
                $('#inspectionForm').trigger("reset");
                $('#modalTitle').html("Tambah Log Inspeksi Baru");
                $('#result, #material_approval_id').val(null).trigger('change');
                $('#reference_document').val(''); // <-- Pastikan field ini juga direset
                $('#typeMaterial').prop('checked', true).trigger(
                    'change'); // Set default radio and trigger change
                $('#inspectionModal').modal('show');
            });

            // Edit button
            $('body').on('click', '.edit-btn', function() {
                var id = $(this).data('id');
                $.get("{{ route('inspection-logs.index') }}" + '/' + id + '/edit', function(data) {
                    $('#modalTitle').html("Edit Log Inspeksi");
                    $('#log_id').val(data.id);
                    $('input[name="inspection_type"][value="' + data.inspection_type + '"]').prop(
                        'checked', true).trigger('change');
                    $('#inspection_date').val(data.inspection_date.split('T')[0]); // Format Y-m-d
                    $('#result').val(data.result).trigger('change');
                    $('#material_approval_id').val(data.material_approval_id).trigger('change');
                    $('#reference_document').val(data.reference_document);
                    $('#description').val(data.description);
                    $('#notes').val(data.notes);
                    $('#inspectionModal').modal('show');
                })
            });

            // Form submission
            $('#inspectionForm').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $('#saveBtn').html('Menyimpan..').prop('disabled', true);
                var log_id = $('#log_id').val();
                var url = log_id ? "{{ url('inspection-logs') }}/" + log_id :
                    "{{ route('inspection-logs.store') }}";
                var method = log_id ? "POST" : "POST";

                if (log_id) {
                    formData.append('_method', 'PUT');
                }

                $.ajax({
                    type: method,
                    url: url,
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: (data) => {
                        $('#inspectionModal').modal('hide');
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
                        url: "{{ url('inspection-logs') }}" + '/' + id,
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
