@extends('inc.layout')
@section('title', 'Tambah Form Template')
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
    <style src="{{ asset('summernote-0.9.0/summernote-bs4.min.css') }}"></style>
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
                            Buat Formulir
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content" id="filter-wrapper">

                            <form action="#" method="POST" id="store-form">
                                @csrf
                                <div class="row">
                                    {{-- Kolom Nama Formulir & Status tidak berubah --}}
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group">
                                            <label for="nama_form" class="form-label">Nama Formulir</label>
                                            <input type="text" name="nama_form" id="nama_form" class="form-control"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group">
                                            <label for="tipe_form" class="form-label">Tipe Formulir</label>
                                            <select class="form-control" name="tipe_form" id="tipe_form" required>
                                                <option value="rawat-jalan">Rawat Jalan</option>
                                                <option value="rawat-inap">Rawat Inap</option>
                                                <option value="all">Semua</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group">
                                            <label for="form_kategori_id" class="form-label">Kategori Formulir</label>
                                            <select class="form-control" name="form_kategori_id" id="form_kategori_id"
                                                required>
                                                {{-- [UBAH] Tambahkan option kosong untuk placeholder --}}
                                                <option></option>
                                                @foreach ($kategori as $item)
                                                    <option value="{{ $item->id }}">{{ $item->nama_kategori }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <div class="form-group">
                                            <label for="is_active" class="form-label">Status</label>
                                            <select class="form-control" name="is_active" id="is_active" required>
                                                <option value="1">Aktif</option>
                                                <option value="0">Tidak Aktif</option>
                                            </select>
                                        </div>
                                    </div>
                                    {{-- Sisa form tidak berubah --}}
                                    <div class="col-md-12 mb-3">
                                        <label for="summernote" class="form-label mb-2">Isi Formulir</label>
                                        <textarea name="form_source" id="summernote" class="form-control" rows="20"></textarea>
                                    </div>
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-block mt-2 btn-primary">
                                            <i class="fas fa-save mr-1"></i> Simpan
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
    <script src="{{ asset('summernote-0.9.0/summernote-bs4.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Inisialisasi untuk Kategori Formulir
            $('#tipe_form').select2({
                placeholder: "Pilih Tipe Formulir",
                // Ini adalah kunci untuk mengaktifkan fitur select-or-create.
                // Pengguna bisa memilih dari daftar atau mengetikkan nilai baru.
                tags: true
            });

            $('#form_kategori_id').select2({
                placeholder: "Pilih atau ketik kategori baru",
                // Ini adalah kunci untuk mengaktifkan fitur select-or-create.
                // Pengguna bisa memilih dari daftar atau mengetikkan nilai baru.
                tags: true
            });

            // Inisialisasi untuk Status (tanpa tagging)
            $('#is_active').select2({
                placeholder: "Pilih status"
            });

            // FUNGSI UNTUK MEMBUAT TOMBOL KUSTOM DI SUMMERNOTE

            // [UBAH TOTAL] Inisialisasi Summernote dengan Konfigurasi Khusus Form
            $('#summernote').summernote({
                height: 450,
                tabsize: 2,
                // [PENTING] Nonaktifkan filter XSS bawaan yang bisa merusak tag form
                disableDragAndDrop: false, // Biarkan drag-drop gambar tetap aktif
                codeviewFilter: false,
                codeviewIframeFilter: false,

                // [PENTING] Definisikan tag apa saja yang kita izinkan
                // Ini mencegah Summernote menghapus atribut penting seperti 'name', 'id', 'value', dll.
                allowedTags: [
                    'p', 'br', 'ul', 'ol', 'li', 'hr', 'table', 'thead', 'tbody', 'tr', 'th', 'td',
                    'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'b', 'i', 'u', 'strong', 'em', 'span',
                    'div', 'a', 'img', 'input', 'textarea', 'select', 'option', 'label', 'button'
                ],

                // [BARU] Tambahkan tombol kustom ke toolbar untuk snippet form
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

            // Handler untuk submit form
            $('#store-form').on('submit', function(e) {
                e.preventDefault();

                // Pastikan summernote mengupdate textarea sebelum serialisasi
                $('#summernote').summernote('code');

                var formData = $(this).serialize();

                $.ajax({
                    url: '{{ route('api.form-builder.store') }}',
                    type: 'POST',
                    data: formData,
                    beforeSend: function() {
                        $('button[type="submit"]').prop('disabled', true).html(
                            '<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
                    },
                    success: function(response) {
                        alert(response.message ||
                            'Formulir berhasil disimpan!'); // Fallback message

                        // Arahkan ke halaman daftar formulir
                        window.location.href =
                            '{{ route('master-data.setup.form-builder') }}';
                    },
                    error: function(xhr) {
                        var errorMessages = 'Terjadi kesalahan:\n\n';
                        if (xhr.status === 422) { // Error validasi
                            var errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                errorMessages += '- ' + value[0] + '\n';
                            });
                        } else { // Error server lainnya
                            errorMessages += xhr.responseJSON.message ||
                                'Tidak dapat terhubung ke server.';
                        }
                        alert(errorMessages);
                    },
                    complete: function() {
                        $('button[type="submit"]').prop('disabled', false).html(
                            '<i class="fas fa-save mr-1"></i> Simpan');
                    }
                });
            });
        });
    </script>
@endsection
