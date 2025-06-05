@extends('inc.layout')
@section('title', 'Biaya Registrasi Layanan')
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

        @media (max-width: 767.98px) {
            .custom-margin {
                margin-top: 15px;
            }

            #filter-wrapper .form-group {
                flex-direction: column;
                align-items: flex-start !important;
            }

            #filter-wrapper .form-label {
                width: auto;
                /* Biarkan lebar label mengikuti konten */
                margin-bottom: 0.5rem;
            }

            #filter-wrapper .form-control {
                width: 100%;
            }
        }
    </style>
@endsection
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Form Pencarian</span>
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content" id="filter-wrapper">

                            <form action="/daftar-rekam-medis" method="get">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <div class="form-group d-flex align-items-center">
                                            <label for="nama_tindakan_1" class="form-label">Nama</label>
                                            <input type="text" name="nama_tindakan" id="nama_tindakan_1"
                                                class="form-control rounded-0 border-top-0 border-left-0 border-right-0 p-0">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-sm float-right mt-2 btn-primary">
                                            <i class="fas fa-search mr-1"></i> Cari
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Daftar Tarif Registrasi
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <!-- datatable start -->
                            <div class="table-responsive">
                                <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                                    <i id="loading-spinner" class="fas fa-spinner fa-spin"></i>
                                    <thead class="bg-primary-600">
                                        <tr>
                                            <th>Nama</th>
                                            <th>Tipe</th>
                                            <th>Status</th>
                                            <th>Ruangan</th>
                                            <th>Fungsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tarif_registrasi as $row)
                                            <tr>
                                                <td>{{ $row->nama_tarif }}</td>
                                                <td>{{ $row->tipe }}</td>
                                                <td>Rawat Jalan</td>
                                                <td>
                                                    @foreach ($row->departements as $departement)
                                                        {{ $departement->name }}
                                                    @endforeach
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-primary px-2 py-1 btn-departement"
                                                        data-id="{{ $row->id }}">
                                                        <i class="fas fa-home"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-primary px-2 py-1 btn-tarif"
                                                        data-id="{{ $row->id }}">
                                                        <i class="fas fa-credit-card"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-success px-2 py-1 btn-edit"
                                                        data-id="{{ $row->id }}">
                                                        <i class="fas fa-pencil"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger px-2 py-1 btn-delete"
                                                        data-id="{{ $row->id }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="5" class="text-center">
                                                <button type="button"
                                                    class="btn btn-outline-primary waves-effect waves-themed"
                                                    id="btn-tambah-tarif-registrasi">
                                                    <span class="fal fa-plus-circle"></span>
                                                    Tambah Tarif Registrasi
                                                </button>
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <!-- datatable end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    @include('pages.simrs.master-data.setup.tarif-registrasi-layanan.partials.create')
    @include('pages.simrs.master-data.setup.tarif-registrasi-layanan.partials.edit')
@endsection
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(document).ready(function() {
            let biayaRegistId = null;
            $('#loading-spinner').show();

            $('#modal-tambah-tarif-registrasi .select2').select2({
                dropdownParent: $('#modal-tambah-tarif-registrasi')
            });

            $('#btn-tambah-tarif-registrasi').click(function() {
                $('#modal-tambah-tarif-registrasi').modal('show');
            });

            $('.btn-tarif').click(function() {
                const id_param = $(this).attr('data-id');
                const url = `{{ route('master-data.setup.tarif-registrasi.set-tarif', ':id') }}`
                    .replace(':id', id_param);
                const popupWidth = 850;
                const popupHeight = 600;
                const left = (screen.width - popupWidth) / 2;
                const top = (screen.height - popupHeight) / 2;

                window.open(
                    url,
                    "popupWindow",
                    "width=" + popupWidth + ",height=" + popupHeight + ",top=" + top + ",left=" + left +
                    ",scrollbars=yes,resizable=yes"
                );
            });

            $('.btn-departement').click(function() {
                const id_param = $(this).attr('data-id');
                const url = `{{ route('master-data.setup.tarif-registrasi.set-departement', ':id') }}`
                    .replace(':id', id_param);
                const popupWidth = 850;
                const popupHeight = 600;
                const left = (screen.width - popupWidth) / 2;
                const top = (screen.height - popupHeight) / 2;

                window.open(
                    url,
                    "popupWindow",
                    "width=" + popupWidth + ",height=" + popupHeight + ",top=" + top + ",left=" + left +
                    ",scrollbars=yes,resizable=yes"
                );
            });

            $('.btn-edit').click(function() {
                $('#modal-edit-tarif-registrasi').modal('show');
                biayaRegistId = $(this).attr('data-id');
                $('#modal-edit-tarif-registrasi form').attr('data-id', biayaRegistId);

                $.ajax({
                    url: '/api/simrs/master-data/setup/tarif-registrasi-layanan/' +
                        biayaRegistId,
                    type: 'GET',
                    success: function(response) {
                        $('#modal-edit-tarif-registrasi #tipe_edit').val(response.tipe)
                            .select2({
                                dropdownParent: $('#modal-edit-tarif-registrasi')
                            });
                        $('#modal-edit-tarif-registrasi input[name="nama_tarif"]').val(
                            response
                            .nama_tarif);
                    },
                    error: function(xhr, status, error) {
                        $('#modal-edit-tarif-registrasi').modal('hide');
                        showErrorAlert('Terjadi kesalahan: ' + error);
                    }
                });

            });

            $('.btn-delete').click(function() {
                var biayaRegistId = $(this).attr('data-id');

                // Menggunakan confirm() untuk mendapatkan konfirmasi dari pengguna
                var userConfirmed = confirm('Anda Yakin ingin menghapus ini?');

                if (userConfirmed) {
                    // Jika pengguna mengklik "Ya" (OK), maka lakukan AJAX request
                    $.ajax({
                        url: '/api/simrs/master-data/setup/tarif-registrasi-layanan/' +
                            biayaRegistId +
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
                biayaRegistId = $(this).attr('data-id');
                $.ajax({
                    url: '/api/simrs/master-data/setup/tarif-registrasi-layanan/' +
                        biayaRegistId +
                        '/update',
                    type: 'PATCH',
                    data: formData,
                    beforeSend: function() {
                        $('#update-form').find('.ikon-edit').hide();
                        $('#update-form').find('.spinner-text').removeClass(
                            'd-none');
                    },
                    success: function(response) {
                        $('#modal-edit-tarif-registrasi').modal('hide');
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

                            $('#modal-edit-tarif-registrasi').modal('hide');
                            showErrorAlert('Terjadi kesalahan:\n' +
                                errorMessages);
                        } else {
                            $('#modal-edit-tarif-registrasi').modal('hide');
                            showErrorAlert('Terjadi kesalahan: ' + error);
                            console.log(error);
                        }
                    }
                });
            });

            $('#store-form').on('submit', function(e) {
                e.preventDefault(); // Mencegah form submit secara default

                var formData = $(this).serialize(); // Mengambil semua data dari form

                $.ajax({
                    url: '/api/simrs/master-data/setup/tarif-registrasi-layanan',
                    type: 'POST',
                    data: formData,
                    beforeSend: function() {
                        $('#store-form').find('.ikon-tambah').hide();
                        $('#store-form').find('.spinner-text').removeClass(
                            'd-none');
                    },
                    success: function(response) {
                        $('#modal-tambah-tarif-registrasi').modal('hide');
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

                            $('#modal-tambah-tarif-registrasi').modal('hide');
                            showErrorAlert('Terjadi kesalahan:\n' +
                                errorMessages);
                        } else {
                            $('#modal-tambah-tarif-registrasi').modal('hide');
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
