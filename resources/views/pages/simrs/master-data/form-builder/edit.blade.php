@extends('inc.layout')
@section('title', 'Edit Form Template')
@section('extended-css')
    <style>
        hr {
            border: 1px dashed #fd3995 !important;
        }

        div.table-responsive>div.dataTables_wrapper>div.row>div[class^="col-"]:last-child {
            padding: 0px;
        }

        .dataTables_scrollHeadInner,
        .dataTables_scrollFootInner {
            width: 100% !important;
        }
    </style>
    {{-- Path untuk Summernote CSS --}}
    <link href="{{ asset('summernote-0.9.0/summernote-bs4.min.css') }}" rel="stylesheet">
@endsection
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row justify-content-center">
            <div class="col-xl-12">

                <div class="row mb-3">
                    <div class="col">
                        <a href="{{ route('master-data.setup.form-builder') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali
                        </a>
                    </div>
                </div>

                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Edit Formulir: <strong>{{ $formTemplate->nama_form }}</strong>
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content" id="filter-wrapper">

                            <form action="#" method="POST" id="update-form">
                                @csrf
                                {{-- Kita tidak perlu @method('PUT') karena menggunakan AJAX dengan POST --}}
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group">
                                            <label for="nama_form" class="form-label">Nama Formulir</label>
                                            <input type="text" name="nama_form" id="nama_form" class="form-control"
                                                value="{{ old('nama_form', $formTemplate->nama_form) }}">
                                        </div>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label for="tipe_form" class="form-label">Tipe Formulir</label>
                                        <select class="form-control select2" name="tipe_form" id="tipe_form">
                                            <option value="rawat-jalan"
                                                {{ old('tipe_form', $formTemplate->tipe_form) == 'rawat-jalan' ? 'selected' : '' }}>
                                                Rawat Jalan</option>
                                            <option value="rawat-inap"
                                                {{ old('tipe_form', $formTemplate->tipe_form) == 'rawat-inap' ? 'selected' : '' }}>
                                                Rawat Inap</option>
                                            <option value="all"
                                                {{ old('tipe_form', $formTemplate->tipe_form) == 'all' ? 'selected' : '' }}>
                                                Semua</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <div class="form-group">
                                            <label for="form_kategori_id" class="form-label">Kategori Formulir</label>
                                            <select class="form-control select2" name="form_kategori_id"
                                                id="form_kategori_id">
                                                @foreach ($kategori as $item)
                                                    <option value="{{ $item->id }}"
                                                        {{ old('form_kategori_id', $formTemplate->form_kategori_id) == $item->id ? 'selected' : '' }}>
                                                        {{ $item->nama_kategori }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group">
                                            <label for="is_active" class="form-label">Status</label>
                                            <select class="form-control select2" name="is_active" id="is_active">
                                                <option value="1"
                                                    {{ old('is_active', $formTemplate->is_active) == 1 ? 'selected' : '' }}>
                                                    Aktif</option>
                                                <option value="0"
                                                    {{ old('is_active', $formTemplate->is_active) == 0 ? 'selected' : '' }}>
                                                    Tidak Aktif</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="summernote" class="form-label mb-2">Isi Formulir</label>
                                        <textarea name="form_source" id="summernote" class="form-control" rows="20">{{ old('form_source', $formTemplate->form_source) }}</textarea>
                                    </div>
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-block mt-2 btn-primary">
                                            <i class="fas fa-save mr-1"></i> Update
                                        </button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    {{-- Path untuk Summernote JS --}}
    <script src="{{ asset('summernote-0.9.0/summernote-bs4.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();

            // Inisialisasi Summernote dengan Konfigurasi Khusus Form
            $('#summernote').summernote({
                height: 450,
                tabsize: 2,
                // Nonaktifkan filter XSS bawaan yang bisa merusak tag form
                disableDragAndDrop: false, // Biarkan drag-drop gambar tetap aktif
                codeviewFilter: false,
                codeviewIframeFilter: false,

                // Definisikan tag apa saja yang kita izinkan
                // Ini mencegah Summernote menghapus atribut penting seperti 'name', 'id', 'value', dll.
                allowedTags: [
                    'p', 'br', 'ul', 'ol', 'li', 'hr', 'table', 'thead', 'tbody', 'tr', 'th', 'td',
                    'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'b', 'i', 'u', 'strong', 'em', 'span',
                    'div', 'a', 'img', 'input', 'textarea', 'select', 'option', 'label', 'button'
                ],

                // Tambahkan tombol kustom ke toolbar untuk snippet form
                toolbar: [
                    ['style', ['style', 'bold', 'italic', 'underline', 'clear']],
                    ['font', ['fontname', 'fontsize']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['view', ['fullscreen', 'codeview']],
                    // Grup baru untuk Form Snippets
                    ['form', ['insertInput', 'insertTextarea', 'insertSelect', 'insertDataPlaceholder', 'insertSignaturePad']]
                ]
            });

            $('#update-form').on('submit', function(e) {
                e.preventDefault();

                var formData = $(this).serialize();
                // Dapatkan ID dari data form template yang di-pass dari controller
                var formId = "{{ $formTemplate->id }}";

                $.ajax({
                    // ==========================================================
                    // INI BAGIAN YANG DIPERBARUI
                    // Sesuaikan URL dengan rute API Anda yang baru
                    url: '/api/simrs/master-data/setup/form-builder/' + formId + '/update',
                    // ==========================================================
                    type: 'POST', // Menggunakan POST
                    data: formData,
                    beforeSend: function() {
                        // Tambahkan loading state jika perlu
                        // contoh: $('button[type="submit"]').prop('disabled', true);
                    },
                    success: function(response) {
                        showSuccessAlert(response.message);
                        // Redirect ke halaman index setelah berhasil
                        setTimeout(() => {
                            window.location.href =
                                "{{ route('master-data.setup.form-builder') }}";
                        }, 1000);
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            var errorMessages = '';
                            $.each(errors, function(key, value) {
                                errorMessages += value + '\n';
                            });
                            showErrorAlert('Terjadi kesalahan validasi:\n' + errorMessages);
                        } else {
                            showErrorAlert('Terjadi kesalahan server: ' + error);
                        }
                    },
                    complete: function() {
                        // Hapus loading state
                        // contoh: $('button[type="submit"]').prop('disabled', false);
                    }
                });
            });
        });
    </script>
@endsection
