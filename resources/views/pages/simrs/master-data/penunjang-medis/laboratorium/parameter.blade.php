@extends('inc.layout')
@section('title', 'Parameter Laboratorium')
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
                            Parameter Laboratorium
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
                                            <th>Tipe</th>
                                            <th>Grup</th>
                                            <th>No. Urut</th>
                                            <th>Kode</th>
                                            <th>Parameter</th>
                                            <th>Kode Parameter</th>
                                            <th>Kode Spesimen</th>
                                            <th>Fungsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($parameter as $row)
                                            <tr>
                                                <td>{{ $row->tipe_laboratorium_id }}</td>
                                                <td>{{ $row->grup_parameter_laboratorium_id }}</td>
                                                <td>{{ $row->no_urut }}</td>
                                                <td>{{ $row->kode }}</td>
                                                <td>{{ $row->parameter }}</td>
                                                <td></td>
                                                <td></td>
                                                <td>
                                                    <button class="btn btn-sm btn-primary px-2 py-1 btn-tarif"
                                                        data-id="{{ $row->id }}"> <i class="fas fa-credit-card"></i>
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
                                            <th colspan="8" class="text-center">
                                                <button type="button"
                                                    class="btn btn-outline-primary waves-effect waves-themed"
                                                    id="btn-tambah-parameter-laboratorium">
                                                    <span class="fal fa-plus-circle"></span>
                                                    Tambah Parameter Laboratorium
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
    @include('pages.simrs.master-data.penunjang-medis.laboratorium.partials.tambah-parameter-lab')
    @include('pages.simrs.master-data.penunjang-medis.laboratorium.partials.edit-parameter-lab')
@endsection
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(document).ready(function() {
            let kategoriId = null;
            $('#loading-spinner').show();

            $('#btn-tambah-parameter-laboratorium').click(function() {
                $('#modal-tambah-parameter-laboratorium').modal('show');
            });

            $('#modal-tambah-parameter-laboratorium .select2').select2({
                dropdownParent: $('#modal-tambah-parameter-laboratorium')
            });

            $('#modal-edit-parameter-laboratorium .select2').select2({
                dropdownParent: $('#modal-edit-parameter-laboratorium')
            });

            $('.btn-tarif').click(function() {
                const id_param = $(this).attr('data-id');
                const url = `{{ route('master-data.penunjang-medis.laboratorium.parameter.tarif', ':id') }}`
                    .replace(':id', id_param);
                const popupWidth = 900;
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
                console.log('clicked');
                $('#modal-edit-parameter-laboratorium').modal('show');
                kategoriId = $(this).attr('data-id');
                $('#modal-edit-parameter-laboratorium form').attr('data-id', kategoriId);

                $.ajax({
                    url: '/api/simrs/master-data/penunjang-medis/laboratorium/parameter/' +
                        kategoriId,
                    type: 'GET',
                    success: function(response) {
                        console.log(response);

                        $('#modal-edit-parameter-laboratorium #grup_parameter_laboratorium_id')
                            .val(response.grup_parameter_laboratorium_id)
                            .trigger('change');

                        $('#modal-edit-parameter-laboratorium select[name="kategori_laboratorium_id"]')
                            .val(response.kategori_laboratorium_id)
                            .trigger('change');

                        $('#modal-edit-parameter-laboratorium select[name="tipe_laboratorium_id"]')
                            .val(response.tipe_laboratorium_id)
                            .trigger('change');

                        $('#modal-edit-parameter-laboratorium input[name="parameter"]')
                            .val(response.parameter);

                        $('#modal-edit-parameter-laboratorium input[name="satuan"]')
                            .val(response.satuan);

                        // this is a checkbox
                        $('#modal-edit-parameter-laboratorium input[name="is_hasil"]')
                            .prop('checked', response.is_hasil);

                        // this is a checkbox
                        $('#modal-edit-parameter-laboratorium input[name="is_order"]')
                            .prop('checked', response.is_order);

                        $('#modal-edit-parameter-laboratorium input[name="metode"]')
                            .val(response.metode);

                        $('#modal-edit-parameter-laboratorium input[name="no_urut"]')
                            .val(response.no_urut);

                        $('#modal-edit-parameter-laboratorium input[name="kode"]')
                            .val(response.kode);

                        const subParameters = [];
                        for (let i = 0; i < response.sub_parameters.length; i++) {
                            subParameters.push(
                            `${response.sub_parameters[i].sub_parameter_id}`);
                        }

                        // this is a multiple select
                        $('#modal-edit-parameter-laboratorium select[name="sub_parameter[]"]')
                            .val(subParameters)
                            .trigger('change');

                    },
                    error: function(xhr, status, error) {
                        $('#modal-edit-parameter-laboratorium').modal('hide');
                        showErrorAlert('Terjadi kesalahan: ' + error);
                    }
                });

            });

            $('.btn-delete').click(function() {
                var kategoriId = $(this).attr('data-id');

                // Menggunakan confirm() untuk mendapatkan konfirmasi dari pengguna
                var userConfirmed = confirm('Anda Yakin ingin menghapus ini?');

                if (userConfirmed) {
                    // Jika pengguna mengklik "Ya" (OK), maka lakukan AJAX request
                    $.ajax({
                        url: '/api/simrs/master-data/penunjang-medis/laboratorium/parameter/' +
                            kategoriId +
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
                parameterId = $(this).attr('data-id');
                $.ajax({
                    url: '/api/simrs/master-data/penunjang-medis/laboratorium/parameter/' +
                        parameterId +
                        '/update',
                    type: 'PATCH',
                    data: formData,
                    beforeSend: function() {
                        $('#update-form').find('.ikon-edit').hide();
                        $('#update-form').find('.spinner-text').removeClass(
                            'd-none');
                    },
                    success: function(response) {
                        $('#modal-edit-parameter-laboratorium').modal('hide');
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

                            $('#modal-edit-parameter-laboratorium').modal('hide');
                            showErrorAlert('Terjadi kesalahan:\n' +
                                errorMessages);
                        } else {
                            $('#modal-edit-parameter-laboratorium').modal('hide');
                            showErrorAlert('Terjadi kesalahan: ' + error);
                            console.log(error);
                        }
                    }
                });
            });

            $('#store-form').on('submit', function(e) {
                e.preventDefault(); // Mencegah form submit secara default

                var formData = $(this).serialize();
                const _formData = new FormData(this);
                for (var pair of _formData.entries()) {
                    console.log(pair[0] + ': ' + pair[1]);
                }
                // return;


                $.ajax({
                    url: '/api/simrs/master-data/penunjang-medis/laboratorium/parameter',
                    type: 'POST',
                    data: formData,
                    beforeSend: function() {
                        $('#store-form').find('.ikon-tambah').hide();
                        $('#store-form').find('.spinner-text').removeClass(
                            'd-none');
                    },
                    success: function(response) {
                        $('#modal-tambah-parameter-laboratorium').modal('hide');
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

                            $('#modal-tambah-parameter-laboratorium').modal('hide');
                            showErrorAlert('Terjadi kesalahan:\n' +
                                errorMessages);
                        } else {
                            $('#modal-tambah-parameter-laboratorium').modal('hide');
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
