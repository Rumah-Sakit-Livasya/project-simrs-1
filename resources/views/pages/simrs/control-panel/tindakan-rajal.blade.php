@extends('inc.layout')
@section('title', 'Migrasi Tarif Tindakan')

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">
                <div class="card shadow-sm">
                    <div class="card-header d-flex align-items-center">
                        <i class="fa fa-download text-primary me-2"></i>
                        <h5 class="mb-0">Migrasi Tarif Tindakan</h5>
                    </div>
                    <div class="card-body">

                        {{-- Notifikasi --}}
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fa fa-exclamation-triangle me-2"></i> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger" role="alert">
                                <strong>Terdapat Kesalahan:</strong>
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row g-4">
                            {{-- Download Template --}}
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-header bg-light">
                                        <i class="fa fa-download text-primary me-2"></i>
                                        <span>Download Template Tarif</span>
                                    </div>
                                    <div class="card-body">
                                        <form method="post" action="{{ route('tindakan.export') }}">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="did" class="form-label">Departemen</label>
                                                <select name="did" id="did" class="form-select select2"
                                                    data-placeholder="Pilih Departemen" required>
                                                    <option value="" disabled selected>Pilih Departemen</option>
                                                    @foreach ($departments as $dept)
                                                        <option value="{{ $dept->id }}">{{ strtoupper($dept->name) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-4">
                                                <label for="igid" class="form-label">Group Tarif Asuransi</label>
                                                <select name="igid" id="igid" class="form-select select2"
                                                    data-placeholder="Pilih Group Tarif" required>
                                                    <option value="" disabled selected>Pilih Group Tarif</option>
                                                    @foreach ($grupPenjamins as $gp)
                                                        <option value="{{ $gp->id }}">{{ $gp->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-download me-2"></i> Download Template
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            {{-- Upload Migrasi --}}
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-header bg-light">
                                        <i class="fa fa-upload text-success me-2"></i>
                                        <span>Upload File Migrasi</span>
                                    </div>
                                    <div class="card-body">
                                        <form method="post" id="fupload" action="{{ route('tindakan.import') }}"
                                            enctype="multipart/form-data" autocomplete="off" novalidate>
                                            @csrf
                                            <div class="mb-4">
                                                <label for="file" class="form-label fw-semibold">
                                                    <i class="fa fa-file-excel text-success me-1"></i>
                                                    Pilih File <span class="text-muted fw-normal">(Excel .xlsx /
                                                        .csv)</span>
                                                </label>
                                                <div class="input-group">
                                                    <input type="file" id="file" name="file"
                                                        class="form-control @error('file') is-invalid @enderror"
                                                        accept=".xlsx,.csv" required>
                                                    <button type="button" class="btn btn-outline-secondary"
                                                        onclick="document.getElementById('file').value = '';">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </div>
                                                @error('file')
                                                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                                                @enderror
                                                <div class="form-text text-muted mt-1">
                                                    Maksimal ukuran file 2MB. Pastikan format sesuai template.
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center gap-2">
                                                <button type="button" class="btn btn-success px-4 py-2"
                                                    onclick="cek_upload(this)">
                                                    <span class="spinner-border spinner-border-sm me-2 d-none"
                                                        role="status" aria-hidden="true"></span>
                                                    <i class="fa fa-upload me-2"></i> Upload Data
                                                </button>
                                                <span id="upload-status" class="text-muted small"></span>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div> {{-- end row --}}
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('plugin')
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        function cek_upload(obj) {
            var fileInput = document.getElementById('file');
            var file = fileInput.value;
            var allowedExtensions = /(\.xlsx|\.csv)$/i;

            if (file === '') {
                fileInput.focus();
                fileInput.classList.add('is-invalid');
                alert('File belum dipilih!');
                return false;
            } else if (!allowedExtensions.exec(file)) {
                alert('Format file tidak valid! Harap unggah file .xlsx atau .csv');
                fileInput.value = '';
                return false;
            } else {
                obj.innerHTML = '<i class="fa fa-spinner fa-spin me-2"></i> Mohon tunggu...';
                obj.disabled = true;
                document.getElementById('fupload').submit();
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            $('.select2').select2({
                // placeholder: "Pilih Status", // Teks placeholder
                allowClear: true, // Memungkinkan pengguna untuk menghapus pilihan
                dropdownCssClass: "select2-dropdown", // Menyesuaikan class CSS dropdown
            });
        });
    </script>
@endsection
