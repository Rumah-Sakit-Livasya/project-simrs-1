@extends('pages.simrs.erm.index')

@section('erm')
    {{-- CSS untuk Dropzone --}}
    {{-- <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" /> --}}

    {{-- Header Pasien --}}
    <div class="p-3">@include('pages.simrs.erm.partials.detail-pasien')</div>
    <hr>

    <!-- Card untuk Daftar Dokumen -->
    <div class="card">
        <div class="card-header bg-light">
            <h4 class="card-title mb-0"><i class="fas fa-list-alt"></i> Daftar Dokumen Terunggah</h4>
        </div>
        <div class="card-body">
            <table id="documents-table" class="table table-bordered table-striped table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tgl Upload</th>
                        <th>Kategori</th>
                        <th>Nama File</th>
                        <th>Keterangan</th>
                        <th>User</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <!-- Card untuk Form Upload -->
    <div class="card mt-4">
        <div class="card-header bg-success text-white">
            <h4 class="card-title mb-0"><i class="fas fa-upload"></i> Unggah Dokumen Baru</h4>
        </div>
        <div class="card-body">
            <form id="form-upload-document" action="javascript:void(0);" autocomplete="off">
                @csrf
                <input type="hidden" name="registration_id" value="{{ $registration->id }}">
                <div class="form-group row">
                    <label for="document_category_id" class="col-sm-2 col-form-label">Tipe Dokumen</label>
                    <div class="col-sm-10">
                        <select name="document_category_id" id="document_category_id" class="form-control select2" required>
                            <option value="">-- Pilih Tipe Dokumen --</option>
                            @foreach ($documentCategories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="description" class="col-sm-2 col-form-label">Keterangan</label>
                    <div class="col-sm-10">
                        <textarea name="description" id="description" class="form-control" rows="3" placeholder="Keterangan singkat mengenai file..."></textarea>
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label for="file_upload" class="col-sm-2 col-form-label">Pilih File</label>
                    <div class="col-sm-10">
                        <div class="custom-file">
                            <input type="file" name="file_upload" id="file_upload" class="custom-file-input" required accept=".pdf,.jpg,.jpeg,.png">
                            <label class="custom-file-label" for="file_upload" id="file_upload_label">Pilih file...</label>
                        </div>
                        <small class="form-text text-muted mt-2">
                            <i class="fas fa-info-circle"></i> Maks. 5MB &mdash; Tipe: <span class="badge badge-primary">PDF</span> <span class="badge badge-success">JPG</span> <span class="badge badge-info">PNG</span>
                        </small>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-footer text-right">
            <button type="button" class="btn btn-primary" id="btn-upload-document"><i class="fas fa-paper-plane"></i> Unggah Sekarang</button>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    <script>
        // Update label saat file dipilih
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('file_upload');
            const fileLabel = document.getElementById('file_upload_label');
            if (fileInput) {
                fileInput.addEventListener('change', function(e) {
                    const fileName = this.files && this.files.length > 0 ? this.files[0].name : 'Pilih file...';
                    fileLabel.textContent = fileName;
                });
            }
        });
    </script>

    {{-- <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script> --}}
    @include('pages.simrs.erm.form.penunjang.partials.upload-dokumen-js')
@endpush
