@extends('inc.layout')
@section('title', 'Kepustakaan')
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

        #filter-wrapper .form-group {
            display: flex;
            align-items: center;
        }

        #filter-wrapper .form-label {
            margin-bottom: 0;
            width: 100px;
            /* Atur lebar label agar semua label sejajar */
        }

        #filter-wrapper .form-control {
            flex: 1;
        }

        .accordion a:hover {
            text-decoration: underline !important;
        }

        @media (max-width: 767.98px) {

            /* Sembunyikan kolom file size dan last modified */
            .file-info {
                display: none;
                /* Menyembunyikan elemen ini di mobile */
            }

            .accordion .row.bg-light {
                margin-bottom: 0;
                /* Menghapus margin bawah untuk header */
            }

            /* Ubah header menjadi full col-12 di mobile */
            .accordion .row.bg-light .col-6 {
                width: 100%;
                /* Header menjadi full width */
            }
        }
    </style>

    <!-- Custom CSS -->
    <style>
        /* Mengatur tampilan kolom */
        .card-title i {
            margin-right: 8px;
        }

        /* Mengatur jarak antar elemen */
        .page-content .row {
            margin-left: 0;
            margin-right: 0;
        }

        /* Menyesuaikan ukuran ikon */
        .fa-folder,
        .fa-file {
            font-size: 1.2rem;
        }

        /* Mengatur tampilan kolom header */
        .bg-light {
            background-color: #4679cc !important;
            color: #ffffff;
        }

        .font-weight-bold {
            font-weight: 700 !important;
        }

        .page-content .row.align-items-center {
            height: 35px;
        }
    </style>
@endsection
@section('content')
    <main id="js-page-content" role="main" class="page-content">

        @if (count($breadcrumbs) > 0 || auth()->user()->hasRole('super admin'))
            <div class="row mb-5">
                <div class="col-xl-12 pl-0">
                    <button type="button" class="btn btn-primary waves-effect waves-themed btn-ajukan"
                        id="btn-tambah-kepustakaan">
                        <span class="fal fa-plus-circle mr-1"></span>
                        Tambah Folder / File
                    </button>
                </div>
            </div>
        @endif

        <div class="row">
            <div class="col-xl-12 pl-0">
                <div class="demo-v-spacing mb-4">
                    <ol class="breadcrumb breadcrumb-seperator-1">
                        <li class="breadcrumb-item">
                            <a href="{{ route('kepustakaan.index') }}" class="text-info">/</a>
                        </li>

                        @if (empty($breadcrumbs))
                            <!-- Jika tidak ada breadcrumbs (Root folder) -->
                            <li class="breadcrumb-item active">Root Folder</li>
                        @else
                            <!-- Jika ada breadcrumbs -->
                            @foreach ($breadcrumbs as $crumb)
                                <li class="breadcrumb-item">
                                    <a href="{{ route('kepustakaan.folder', Crypt::encrypt($crumb->id)) }}"
                                        class="text-info">{{ $crumb->name }}</a>
                                </li>
                            @endforeach
                        @endif
                    </ol>
                </div>
            </div>
        </div>


        <div class="frame-wrap w-100">
            <div class="accordion" id="js_demo_accordion-4">

                <div class="row bg-light font-weight-bold p-2">
                    <div class="col-6">Nama</div>
                    <div class="col-3 text-center">File Size</div>
                    <div class="col-3 text-center">Last Modified</div>
                </div>

                @if (auth()->user()->can('master kepustakaan'))
                    @foreach ($kepustakaan as $item)
                        <div class="card">
                            <div class="card-header p-0 bg-white">
                                <div class="row align-items-center py-2">
                                    <div class="col-6 d-flex align-items-center" style="height: 15px">
                                        @if ($item->type == 'folder')
                                            <i class="fas fa-folder text-success fs-xl mr-2"></i>
                                            <a href="{{ route('kepustakaan.folder', Crypt::encrypt($item->id)) }}"
                                                class="card-title">
                                                {{ $item->name }}
                                            </a>
                                        @else
                                            <i class="fas fa-file text-primary fs-xl mr-2"></i>
                                            @php
                                                $filename = $item->file;
                                                $extension = pathinfo($filename, PATHINFO_EXTENSION);

                                                if ($extension === 'pdf') {
                                                    $extension = 'pdf';
                                                } elseif ($extension === 'doc' || $extension === 'docx') {
                                                    $extension = 'word';
                                                } elseif ($extension === 'xls' || $extension === 'xlsx') {
                                                    $extension = 'excel';
                                                } elseif ($extension === 'ppt' || $extension === 'pptx') {
                                                    $extension = 'ppt';
                                                } else {
                                                    $extension = 'others';
                                                }
                                            @endphp
                                            @if ($extension == 'pdf')
                                                <a href="{{ asset('storage/kepustakaan/' . Str::slug($item->kategori) . '/' . $item->file) }}"
                                                    class="card-title" target="_blank">
                                                    {{ $item->file ?? $item->name }}
                                                </a>
                                            @elseif ($extension == 'word' || $extension == 'excel' || $extension == 'ppt')
                                                <a href="https://docs.google.com/viewer?url={{ urlencode(asset('storage/kepustakaan/' . Str::slug($item->kategori) . '/' . $item->file)) }}&embedded=true"
                                                    class="card-title" target="_blank">
                                                    {{ $item->file ?? $item->name }}
                                                </a>
                                            @elseif ($extension == 'others')
                                                <a href="https://docs.google.com/viewer?url={{ urlencode(asset('storage/kepustakaan/' . Str::slug($item->kategori) . '/' . $item->file)) }}&embedded=true"
                                                    class="card-title" target="_blank">
                                                    Lainya
                                                </a>
                                            @endif
                                        @endif
                                    </div>
                                    <div class="col-3 text-center file-info">
                                        {{ $item->size > 0 ? number_format($item->size / 1024, 2) . ' KB' : '-' }}
                                    </div>
                                    <div class="col-3 text-center file-info">
                                        {{ $item->updated_at ? $item->updated_at->format('d M Y') : '--' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    @foreach ($kepustakaan->where('organization_id', auth()->user()->employee->organization_id)->get() as $item)
                        <div class="card">
                            <div class="card-header p-0 bg-white">
                                <div class="row align-items-center py-2">
                                    <div class="col-6 d-flex align-items-center" style="height: 15px">
                                        @if ($item->type == 'folder')
                                            <i class="fas fa-folder text-success fs-xl mr-2"></i>
                                            <a href="{{ route('kepustakaan.folder', Crypt::encrypt($item->id)) }}"
                                                class="card-title">
                                                {{ $item->name }}
                                            </a>
                                        @else
                                            <i class="fas fa-file text-primary fs-xl mr-2"></i>
                                            @php
                                                $filename = $item->file;
                                                $extension = pathinfo($filename, PATHINFO_EXTENSION);

                                                if ($extension === 'pdf') {
                                                    $extension = 'pdf';
                                                } elseif ($extension === 'doc' || $extension === 'docx') {
                                                    $extension = 'word';
                                                } elseif ($extension === 'xls' || $extension === 'xlsx') {
                                                    $extension = 'excel';
                                                } elseif ($extension === 'ppt' || $extension === 'pptx') {
                                                    $extension = 'ppt';
                                                } else {
                                                    $extension = 'others';
                                                }
                                            @endphp
                                            @if ($extension == 'pdf')
                                                <a href="{{ asset('storage/kepustakaan/' . Str::slug($item->kategori) . '/' . $item->file) }}"
                                                    class="card-title" target="_blank">
                                                    {{ $item->file ?? $item->name }}
                                                </a>
                                            @elseif ($extension == 'word' || $extension == 'excel' || $extension == 'ppt')
                                                <a href="https://docs.google.com/viewer?url={{ urlencode(asset('storage/kepustakaan/' . Str::slug($item->kategori) . '/' . $item->file)) }}&embedded=true"
                                                    class="card-title" target="_blank">
                                                    {{ $item->file ?? $item->name }}
                                                </a>
                                            @elseif ($extension == 'others')
                                                <a href="https://docs.google.com/viewer?url={{ urlencode(asset('storage/kepustakaan/' . Str::slug($item->kategori) . '/' . $item->file)) }}&embedded=true"
                                                    class="card-title" target="_blank">
                                                    Lainya
                                                </a>
                                            @endif
                                        @endif
                                    </div>
                                    <div class="col-3 text-center file-info">
                                        {{ $item->size > 0 ? number_format($item->size / 1024, 2) . ' KB' : '-' }}
                                    </div>
                                    <div class="col-3 text-center file-info">
                                        {{ $item->updated_at ? $item->updated_at->format('d M Y') : '--' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif

            </div>
        </div>


    </main>
    {{-- @if (auth()->user()->can('master kepustakaan'))
        @include('pages.simrs.kepustakaan.partials.create')
    @else --}}
    @include('pages.simrs.kepustakaan.partials.create-for-employee')
    {{-- @endif --}}
    {{-- @include('pages.simrs.master-data.kepustakaan.partials.edit') --}}
@endsection
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(document).ready(function() {
            let grupSuplierId = null;

            // Hide the file upload section by default if Folder is selected
            toggleFileUpload();

            // Detect changes on the radio buttons
            $('input[name="type"]').change(function() {
                toggleFileUpload();
            });


            function toggleFileUpload() {
                // If 'File' is selected, show the file upload section, otherwise hide it
                if ($('#type_file').is(':checked')) {
                    $('#file_upload_section').show();
                } else {
                    $('#file_upload_section').hide();
                }
            }

            $('#loading-spinner').show();

            $('#modal-tambah-kepustakaan .select2').select2({
                dropdownParent: $('#modal-tambah-kepustakaan'),
                placeholder: 'Jangan dipilih jika tidak ada'
            });

            $('#btn-tambah-kepustakaan').click(function() {
                $('#modal-tambah-kepustakaan').modal('show');
            });

            $('.btn-edit').click(function() {
                console.log('clicked');
                $('#modal-edit-kepustakaan').modal('show');
                grupSuplierId = $(this).attr('data-id');
                $('#modal-edit-kepustakaan form').attr('data-id', grupSuplierId);

                $.ajax({
                    url: '/api/simrs/master-data/kepustakaan/' +
                        grupSuplierId,
                    type: 'GET',
                    success: function(response) {
                        $('#modal-edit-kepustakaan input[name="kategori"]').val(response
                            .kategori);
                        $('#modal-edit-kepustakaan input[name="status"][value="' +
                                response
                                .status + '"]')
                            .prop(
                                'checked', true);
                        $('#modal-edit-kepustakaan input[name="coa_utang"]').val(response
                            .coa_utang);
                    },
                    error: function(xhr, status, error) {
                        $('#modal-edit-kepustakaan').modal('hide');
                        showErrorAlert('Terjadi kesalahan: ' + error);
                    }
                });

            });

            $('.btn-delete').click(function() {
                var grupSuplierId = $(this).attr('data-id');

                // Menggunakan confirm() untuk mendapatkan konfirmasi dari pengguna
                var userConfirmed = confirm('Anda Yakin ingin menghapus ini?');

                if (userConfirmed) {
                    // Jika pengguna mengklik "Ya" (OK), maka lakukan AJAX request
                    $.ajax({
                        url: '/api/simrs/master-data/kepustakaan/' +
                            grupSuplierId +
                            '/delete',
                        type: 'DELETE',
                        success: function(response) {
                            showSuccessAlert(response.message);

                            setTimeout(() => {
                                console.log('Reloading the page now.');
                                window.location.reload();
                            }, 1000);
                        },
                        error: function(xhr, status, error) {
                            showErrorAlert('Terjadi kesalahan: ' + error);
                        }
                    });
                } else {
                    console.log('Penghapusan dibatalkan oleh pengguna.');
                }
            });

            $('#update-form').on('submit', function(e) {
                e.preventDefault(); // Mencegah form submit secara default

                var formData = $(this).serialize();
                grupSuplierId = $(this).attr('data-id');
                $.ajax({
                    url: '/api/simrs/master-data/kepustakaan/' +
                        grupSuplierId +
                        '/update',
                    type: 'PATCH',
                    data: formData,
                    beforeSend: function() {
                        $('#update-form').find('.ikon-edit').hide();
                        $('#update-form').find('.spinner-text').removeClass(
                            'd-none');
                    },
                    success: function(response) {
                        $('#modal-edit-kepustakaan').modal('hide');
                        showSuccessAlert(response.message);

                        setTimeout(() => {
                            console.log('Reloading the page now.');
                            window.location.reload();
                        }, 1000);
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            var errorMessages = '';

                            $.each(errors, function(key, value) {
                                errorMessages += value +
                                    '\n';
                            });

                            $('#modal-edit-kepustakaan').modal('hide');
                            showErrorAlert('Terjadi kesalahan:\n' +
                                errorMessages);
                        } else {
                            $('#modal-edit-kepustakaan').modal('hide');
                            showErrorAlert('Terjadi kesalahan: ' + error);
                            console.log(error);
                        }
                    }
                });
            });

            $('#store-form').on('submit', function(e) {
                e.preventDefault(); // Mencegah form submit secara default

                // Pastikan elemen ini adalah form HTML
                var formElement = document.getElementById('store-form');

                // Menggunakan FormData dengan benar
                var formData = new FormData(formElement);

                var fileInput = $('#customFile')[0]; // Mengambil input file
                if (fileInput.files.length > 0) {
                    var file = fileInput.files[0]; // Mengambil file yang dipilih
                    // Mendapatkan ukuran file dalam byte
                    var fileSize = file.size;
                    // Menambahkan ukuran file ke FormData
                    formData.append('size', fileSize);
                }

                $.ajax({
                    url: "{{ route('kepustakaan.store') }}",
                    type: 'POST',
                    data: formData,
                    processData: false, // Tidak memproses data menjadi string
                    contentType: false, // Tidak menetapkan tipe konten secara otomatis
                    beforeSend: function() {
                        $('#store-form').find('.ikon-tambah').hide();
                        $('#store-form').find('.spinner-text').removeClass('d-none');
                    },
                    success: function(response) {
                        $('#modal-tambah-kepustakaan').modal('hide');
                        showSuccessAlert(response.message);

                        setTimeout(() => {
                            console.log('Reloading the page now.');
                            window.location.reload();
                        }, 1000);
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            var errorMessages = '';

                            $.each(errors, function(key, value) {
                                errorMessages += value + '\n';
                            });

                            $('#modal-tambah-kepustakaan').modal('hide');
                            showErrorAlert('Terjadi kesalahan:\n' + errorMessages);
                        } else {
                            $('#modal-tambah-kepustakaan').modal('hide');
                            showErrorAlert('Terjadi kesalahan: ' + error);
                            console.log(error);
                        }
                    }
                });
            });

            // initialize datatable
            $('#dt-basic-example').DataTable({
                "drawCallback": function(settings) {
                    // Menyembunyikan preloader setelah data berhasil dimuat
                    $('#loading-spinner').hide();
                },
                responsive: false, // Responsif diaktifkan
                scrollX: true, // Tambahkan scroll horizontal
                lengthChange: false,
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end buttons-container'B>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                        extend: 'pdfHtml5',
                        text: 'PDF',
                        titleAttr: 'Generate PDF',
                        className: 'btn-outline-danger btn-sm mr-1 custom-margin'
                    },
                    {
                        extend: 'excelHtml5',
                        text: 'Excel',
                        titleAttr: 'Generate Excel',
                        className: 'btn-outline-success btn-sm mr-1 custom-margin'
                    },
                    {
                        extend: 'csvHtml5',
                        text: 'CSV',
                        titleAttr: 'Generate CSV',
                        className: 'btn-outline-primary btn-sm mr-1 custom-margin'
                    },
                    {
                        extend: 'copyHtml5',
                        text: 'Copy',
                        titleAttr: 'Copy to clipboard',
                        className: 'btn-outline-primary btn-sm mr-1 custom-margin'
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        titleAttr: 'Print Table',
                        className: 'btn-outline-primary btn-sm custom-margin'
                    }
                ]
            });

        });
    </script>
@endsection
