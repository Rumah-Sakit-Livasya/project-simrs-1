@extends('inc.layout')
@se
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

        .btn-action:hover {
            text-decoration: underline;
            color: #336bc5 !important;
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

        @media (max-width: 768px) {
            .col-3 {
                display: none !important;
            }

            .col-6 {
                width: 100% !important;
                flex: 0 0 100%;
                max-width: 100%;
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

    {{-- Card CSS --}}
    <style>
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .card-hover::after {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            bottom: -4px;
            height: 6px;
            background: rgba(0, 0, 0, 0.1);
            filter: blur(4px);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }

        .card-hover:hover::after {
            opacity: 1;
        }
    </style>
@endsection
@section('content')
    <main id="js-page-content" role="main" class="page-content">

        @if (auth()->user()->hasRole('super admin') || auth()->user()->can('master kepustakaan'))
            @if ($view == 'parent')
                <div class="row mb-5">
                    <div class="col-xl-12 pl-0">
                        <button type="button" class="btn btn-primary waves-effect waves-themed btn-ajukan"
                            id="btn-tambah-departement">
                            <span class="fal fa-plus-circle mr-1"></span>
                            Tambah Departement
                        </button>
                    </div>
                </div>
            @else
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
        @else
            @if (
                (count($breadcrumbs) > 1 && auth()->user()->organization_id != $folder->organization_id) ||
                    auth()->user()->can('tambah kepustakaan'))
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
        @endif

        @if ($view == 'parent')
            <!-- Parent Structure (Initial View) -->
            <div class="row">
                <div class="col-xl-12">
                    <div id="panel-1" class="panel">
                        <div class="panel-hdr">
                            <h2>
                                <a href="{{ route('kepustakaan.index') }}" class="text-info">
                                    Kepustakaan
                                </a>
                            </h2>
                        </div>
                        <div class="panel-container show">
                            <div class="panel-content">
                                <div class="row">
                                    @foreach ($kepustakaan as $item)
                                        @if (
                                            $item->organization_id == auth()->user()->employee->organization_id ||
                                                $item->organization_id == null ||
                                                auth()->user()->hasRole('super admin') ||
                                                auth()->user()->can('master kepustakaan') ||
                                                (in_array($item->organization_id, [26, 27, 25]) &&
                                                    in_array(auth()->user()->employee->organization_id, [26, 27, 25])))
                                            <div class="col-xl-3 col-m d-6 mb-4">
                                                <a
                                                    href="{{ $item->type == 'folder' ? route('kepustakaan.folder', Crypt::encrypt($item->id)) : route('kepustakaan.download', Crypt::encrypt($item->id)) }}">
                                                    <div class="card m-auto border p-5 card-hover d-flex flex-column justify-content-between"
                                                        style="border-radius: 11px; height: 300px;">
                                                        <div class="card-body text-center">
                                                            <img src="{{ asset('/img/logo.png') }}" class="d-block m-auto"
                                                                alt="Livasya" aria-roledescription="logo"
                                                                style="width: 100px">
                                                            <p class="mt-3"
                                                                style="font-size: 11px; overflow-wrap: break-word;">RUMAH
                                                                SAKIT LIVASYA</p>
                                                        </div>
                                                        <div class="card-footer">
                                                            <h5 class="card-text font-weight-bold text-center"
                                                                style="font-size: 12px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                                {{ strtoupper($item->name) }}
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Child Structure (Inside a Parent) -->
            <div class="row">
                <div class="col-xl-12">
                    <div id="panel-1" class="panel">
                        <div class="panel-hdr">
                            <h2>
                                Kepustakaan
                            </h2>
                        </div>
                        <div class="panel-container show">
                            <div class="panel-content">
                                <div class="accordion" id="js_demo_accordion-4">
                                    <div class="row bg-light font-weight-bold p-2">
                                        <div class="col-6">Nama</div>
                                        <div class="col-3 text-center">File Size</div>
                                        <div class="col-3 text-center">Last Modified</div>
                                    </div>

                                    @foreach ($kepustakaan as $item)
                                        @php
                                            $allowedOrganizations = $organizationFolder ?? [];
                                        @endphp

                                        @if (
                                            $item->organization_id == auth()->user()->employee->organization_id ||
                                                $item->organization_id == null ||
                                                auth()->user()->hasRole('super admin') ||
                                                auth()->user()->can('master kepustakaan') ||
                                                (in_array($item->organization_id, [25, 26, 27]) &&
                                                    in_array(auth()->user()->employee->organization_id, [25, 26, 27])) ||
                                                in_array($item->organization_id, $allowedOrganizations))
                                            <div class="card">
                                                <div class="card-header p-0 bg-white">
                                                    <div class="row align-items-center py-2">
                                                        <div class="col-6 d-flex justify-content-between"
                                                            style="height: 15px">
                                                            <div class="folder-wrapper d-flex align-items-center">
                                                                @php
                                                                    $createdAt = \Carbon\Carbon::parse(
                                                                        $item->created_at,
                                                                    );
                                                                    $dateLimit =
                                                                        $createdAt->day > 5 && $createdAt->day < 15;
                                                                @endphp
                                                                @if ($item->type == 'folder')
                                                                    <i class="fas fa-folder text-success fs-xl mr-2"></i>
                                                                    <a href="{{ route('kepustakaan.folder', Crypt::encrypt($item->id)) }}"
                                                                        class="card-title">
                                                                        {{ $item->name }}
                                                                    </a>
                                                                @else
                                                                    <i class="fas fa-file text-primary fs-xl mr-2"></i>
                                                                    <a href="{{ route('kepustakaan.download', Crypt::encrypt($item->id)) }}"
                                                                        class="card-title {{ $dateLimit ? 'text-danger' : '' }}">
                                                                        {{ $item->name . '.' . pathinfo($item->file, PATHINFO_EXTENSION) }}
                                                                    </a>
                                                                @endif
                                                            </div>
                                                            <div class="action-kepustakaan float-right">
                                                                @if (in_array($item->type, ['folder', 'file']) &&
                                                                        auth()->user()->can('edit kepustakaan') &&
                                                                        ($item->organization_id == auth()->user()->employee->organization_id ||
                                                                            (in_array($item->organization_id, [25, 26, 27]) &&
                                                                                in_array(auth()->user()->employee->organization_id, [25, 26, 27])) ||
                                                                            in_array($item->organization_id, $allowedOrganizations)))
                                                                    <i class="btn-action btn-edit fas fa-pencil text-warning fs-xl mr-2"
                                                                        data-url="{{ route('kepustakaan.get', Crypt::encrypt($item->id)) }}"
                                                                        data-id="{{ Crypt::encrypt($item->id) }}"></i>
                                                                    @if ($item->type == 'file')
                                                                        <i class="btn-action btn-cut fas fa-cut text-success fs-xl mr-2"
                                                                            data-url="{{ route('kepustakaan.get', Crypt::encrypt($item->id)) }}"
                                                                            data-id="{{ Crypt::encrypt($item->id) }}"></i>
                                                                    @endif
                                                                @endif

                                                                @if (auth()->user()->can('delete kepustakaan') &&
                                                                        ($item->organization_id == auth()->user()->employee->organization_id ||
                                                                            (in_array($item->organization_id, [25, 26, 27]) &&
                                                                                in_array(auth()->user()->employee->organization_id, [25, 26, 27])) ||
                                                                            in_array($item->organization_id, $allowedOrganizations)))
                                                                    <i class="btn-action btn-delete fas fa-trash text-danger fs-xl mr-2"
                                                                        data-url="{{ route('kepustakaan.delete', Crypt::encrypt($item->id)) }}"
                                                                        data-type="{{ $item->type }}"></i>
                                                                @endif
                                                            </div>
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
                                        @endif
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif


    </main>
    {{-- @if (auth()->user()->can('master kepustakaan'))
        @include('pages.simrs.kepustakaan.partials.create')
    @else --}}
    @include('pages.simrs.kepustakaan.partials.create-departement')
    @include('pages.simrs.kepustakaan.partials.create-for-employee')
    @include('pages.simrs.kepustakaan.partials.edit')
    @include('pages.simrs.kepustakaan.partials.pindah-file')
    {{-- @endif --}}
    {{-- @include('pages.simrs.master-data.kepustakaan.partials.edit') --}}
@endsection
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(document).ready(function() {
            let kepustakaanId = null;

            // Hide the file upload section by default if Folder is selected
            toggleFileUpload();

            // Detect changes on the radio buttons
            $('input[name="type"]').change(function() {
                toggleFileUpload();
            });

            $('#customFile').on('change', function() {
                // Ambil nama file
                var fileName = $(this).val().split('\\').pop();
                // Tampilkan nama file di label
                $(this).next('.custom-file-label').addClass("selected").html(fileName);
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

            $('#modal-tambah-departement .select2').select2({
                dropdownParent: $('#modal-tambah-departement'),
                placeholder: 'Jangan dipilih jika tidak ada'
            });

            $('#btn-tambah-kepustakaan').click(function() {
                $('#modal-tambah-kepustakaan').modal('show');
            });

            $('#btn-tambah-departement').click(function() {
                $('#modal-tambah-departement').modal('show');
            });

            $('.btn-edit').click(function() {

                let url = $(this).data('url');
                kepustakaanId = $(this).data('id');
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        $('#modal-edit-kepustakaan').modal('show');
                        $('#modal-edit-kepustakaan #name').val(response.name);
                    },
                    error: function(xhr, status, error) {
                        $('#modal-edit-kepustakaan').modal('hide');
                        showErrorAlert('Terjadi kesalahan: ' + error);
                    }
                });

            });

            $('.btn-cut').click(function() {

                let url = $(this).data('url');
                kepustakaanId = $(this).data('id');
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        $('#modal-pindah-file-kepustakaan').modal('show');
                        $('#modal-pindah-file-kepustakaan .select2').select2({
                            dropdownParent: $('#modal-pindah-file-kepustakaan')
                        });
                        $('#modal-pindah-file-kepustakaan #name').val(response.name);
                    },
                    error: function(xhr, status, error) {
                        $('#modal-pindah-file-kepustakaan').modal('hide');
                        showErrorAlert('Terjadi kesalahan: ' + error);
                    }
                });

            });

            $('.btn-delete').click(function() {
                let url = $(this).data('url');
                let type = $(this).data('type');
                const email = "{{ auth()->user()->email }}";
                let confirmationMessage = type == 'file' ?
                    'Yakin ingin menghapus file ini?' :
                    'Yakin ingin menghapus folder ini? semua file yang ada pada folder ini akan ikut terhapus juga!';

                if (confirm(confirmationMessage)) {
                    let password = prompt('Masukkan password untuk konfirmasi penghapusan:');

                    if (password !== null) {
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                email: email,
                                password: password
                            }, // Kirim password ke server
                            success: function(response) {
                                showSuccessAlert(response.message);

                                setTimeout(() => {
                                    console.log('Reloading the page now.');
                                    window.location.reload();
                                }, 1000);
                            },
                            error: function(xhr, status, error) {
                                if (xhr.status === 403) {
                                    showErrorAlert('Password salah. Penghapusan dibatalkan.');
                                } else {
                                    showErrorAlert('Terjadi kesalahan: ' + error);
                                }
                            }
                        });
                    } else {
                        console.log('Penghapusan dibatalkan oleh pengguna.');
                    }
                } else {
                    console.log('Penghapusan dibatalkan oleh pengguna.');
                }
            });


            $('#update-form').on('submit', function(e) {
                e.preventDefault();

                let updateUrl =
                    "{{ route('kepustakaan.update', ':id') }}";
                updateUrl = updateUrl.replace(':id', kepustakaanId);
                let formData = $(this).serialize();
                $.ajax({
                    url: updateUrl,
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

            $('#pindah-form').on('submit', function(e) {
                e.preventDefault();

                let updateUrl =
                    "{{ route('kepustakaan.update', ':id') }}";
                updateUrl = updateUrl.replace(':id', kepustakaanId);
                let formData = $(this).serialize();
                $.ajax({
                    url: updateUrl,
                    type: 'PATCH',
                    data: formData,
                    beforeSend: function() {
                        $('#update-form').find('.ikon-edit').hide();
                        $('#update-form').find('.spinner-text').removeClass(
                            'd-none');
                    },
                    success: function(response) {
                        $('#modal-pindah-file-kepustakaan').modal('hide');
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

            function handleFormSubmit(formId, modalId) {
                $(`#${formId}`).on('submit', function(e) {
                    e.preventDefault();

                    var formElement = document.getElementById(formId);
                    var formData = new FormData(formElement);

                    var fileInput = $(formElement).find('input[type="file"]')[0];
                    if (fileInput && fileInput.files.length > 0) {
                        var file = fileInput.files[0];
                        formData.append('size', file.size);
                    }

                    $.ajax({
                        url: "{{ route('kepustakaan.store') }}",
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        beforeSend: function() {
                            $(`#${formId}`).find('.ikon-tambah').hide();
                            $(`#${formId}`).find('.spinner-text').removeClass('d-none');
                        },
                        success: function(response) {
                            $(`#${modalId}`).modal('hide');
                            showSuccessAlert(response.message);

                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        },
                        error: function(xhr, status, error) {
                            $(`#${modalId}`).modal('hide');

                            if (xhr.status === 422) {
                                var errors = xhr.responseJSON.errors;
                                var errorMessages = '';

                                $.each(errors, function(key, value) {
                                    errorMessages += value + '\n';
                                });

                                showErrorAlert('Terjadi kesalahan:\n' + errorMessages);
                            } else {
                                showErrorAlert('Terjadi kesalahan: ' + error);
                                console.log(error);
                            }
                        }
                    });
                });
            }

            // Inisialisasi kedua form
            handleFormSubmit('store-form-kepustakaan', 'modal-tambah-kepustakaan');
            handleFormSubmit('store-form-departement', 'modal-tambah-departement');

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
