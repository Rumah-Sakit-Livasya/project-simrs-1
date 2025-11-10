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
    {{-- Path yang benar untuk Summernote CSS --}}
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
                            Buat Formulir Baru
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form action="#" method="POST" id="store-form">
                                @csrf
                                <div class="row">
                                    {{-- Data Utama Form --}}
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
                                                <option value="all" selected>Semua</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group">
                                            <label for="form_kategori_id" class="form-label">Kategori Formulir</label>
                                            <select class="form-control" name="form_kategori_id" id="form_kategori_id"
                                                required>
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
                                                <option value="1" selected>Aktif</option>
                                                <option value="0">Tidak Aktif</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <hr class="my-3">
                                    </div>

                                    {{-- EDITOR UNTUK TAMPILAN LAYAR (FORM SOURCE) --}}
                                    <div class="col-md-12 mb-3">
                                        <label for="summernote_form" class="form-label mb-2 fw-bold">Isi Formulir (Tampilan
                                            Layar)</label>
                                        <textarea name="form_source" id="summernote_form" class="form-control" rows="20"></textarea>
                                    </div>

                                    {{-- EDITOR UNTUK TEMPLATE CETAK (PRINT SOURCE) --}}
                                    <div class="col-md-12 mb-3">
                                        <label for="summernote_print" class="form-label mb-2 fw-bold">Template Cetak
                                            (Opsional)</label>
                                        <textarea name="print_source" id="summernote_print" class="form-control" rows="15"></textarea>
                                        <small class="form-text text-muted">
                                            Buat layout khusus untuk dicetak di sini. Anda bisa memasukkan style
                                            <code>&#64;page</code> untuk ukuran kertas. Gunakan placeholder
                                            <code>@{{ ... }}</code> yang sama. Jika dikosongkan, tampilan cetak
                                            akan mengikuti "Isi Formulir" di atas.
                                        </small>
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
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="{{ asset('summernote-0.9.0/summernote-bs4.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Inisialisasi Select2
            $('#tipe_form, #is_active').select2({
                placeholder: "Pilih Opsi",
                minimumResultsForSearch: Infinity
            });
            $('#form_kategori_id').select2({
                placeholder: "Pilih atau ketik kategori baru",
                tags: true
            });

            // Konfigurasi umum untuk kedua editor Summernote
            const summernoteConfig = {
                height: 450,
                tabsize: 2,
                disableDragAndDrop: false,
                codeviewFilter: false,
                codeviewIframeFilter: false,
                toolbar: [
                    ['style', ['style', 'bold', 'italic', 'underline', 'clear']],
                    ['font', ['fontname', 'fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']],
                    ['form', ['insertInput', 'insertTextarea', 'insertSelect', 'insertDataPlaceholder',
                        'insertSignaturePad', 'insertImageEditor'
                    ]]
                ]
            };

            // Inisialisasi editor untuk form_source
            $('#summernote_form').summernote(summernoteConfig);

            // Inisialisasi editor untuk print_source dengan tinggi yang lebih pendek
            $('#summernote_print').summernote($.extend({}, summernoteConfig, {
                height: 300
            }));

            // Handler untuk submit form
            $('#store-form').on('submit', function(e) {
                e.preventDefault();

                // Pastikan kedua summernote mengupdate textarea mereka sebelum serialisasi
                $('#summernote_form').summernote('code');
                $('#summernote_print').summernote('code');

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
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message || 'Formulir berhasil disimpan!',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href =
                                '{{ route('master-data.setup.form-builder') }}';
                        });
                    },
                    error: function(xhr) {
                        let errorMessages = 'Terjadi kesalahan. Silakan coba lagi.';
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let messages = Object.values(errors).map(msg =>
                                `<li>${msg[0]}</li>`).join('');
                            errorMessages = `<ul class="text-left mb-0">${messages}</ul>`;
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessages = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Menyimpan',
                            html: errorMessages
                        });
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
