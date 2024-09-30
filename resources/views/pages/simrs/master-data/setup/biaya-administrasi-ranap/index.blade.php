@extends('inc.layout')
@section('title', 'Biaya Administrasi Ranap')
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
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Biaya Administrasi Rawat Inap
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <!-- datatable start -->
                            <div class="table-responsive">
                                <form id="update-biaya">
                                    @method('POST')
                                    @csrf
                                    <table id="dt-basic-example"
                                        class="table table-bordered table-hover table-striped w-100">
                                        <i id="loading-spinner" class="fas fa-spinner fa-spin"></i>
                                        <thead class="bg-primary-600">
                                            <tr>
                                                <th>Group Name</th>
                                                <th>Persentase</th>
                                                <th>Min Tarif</th>
                                                <th>Max Tarif</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($tarif as $row)
                                                <tr>
                                                    <td>{{ $row->group_penjamin->name }}</td>
                                                    <td>
                                                        <input type="text" id="example-input-material"
                                                            value="{{ $row->persentase }}"
                                                            name="persentase[{{ $row->group_penjamin_id }}]"
                                                            class="form-control form-control-lg rounded-0 border-top-0 border-left-0 border-right-0 px-0"
                                                            placeholder="Material">
                                                    </td>
                                                    <td>
                                                        <input type="text" id="example-input-material"
                                                            value="{{ $row->min_tarif }}"
                                                            name="min_tarif[{{ $row->group_penjamin_id }}]"
                                                            class="form-control form-control-lg rounded-0 border-top-0 border-left-0 border-right-0 px-0"
                                                            placeholder="Material">
                                                    </td>
                                                    <td>
                                                        <input type="text" id="example-input-material"
                                                            value="{{ $row->max_tarif }}"
                                                            name="max_tarif[{{ $row->group_penjamin_id }}]"
                                                            class="form-control form-control-lg rounded-0 border-top-0 border-left-0 border-right-0 px-0"
                                                            placeholder="Material">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="4" class="text-center">
                                                    <button type="submit"
                                                        class="btn btn-outline-primary waves-effect waves-themed"
                                                        id="btn-tambah-tarif-registrasi">
                                                        <span class="fal fa-plus-circle"></span>
                                                        Update Biaya
                                                    </button>
                                                </th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </form>
                            </div>
                            <!-- datatable end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    {{-- @include('pages.simrs.master-data.setup.tarif-registrasi-layanan.partials.create')
    @include('pages.simrs.master-data.setup.tarif-registrasi-layanan.partials.edit') --}}
@endsection
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(document).ready(function() {
            $('#loading-spinner').show();
            $('#update-biaya').on('submit', function(e) {
                e.preventDefault(); // Mencegah form submit secara default

                var formData = $(this).serialize();
                $.ajax({
                    url: '/api/simrs/master-data/setup/biaya-administrasi-ranap/update',
                    type: 'PATCH',
                    data: formData,
                    beforeSend: function() {
                        $('#update-biaya').find('.ikon-edit').hide();
                        $('#update-biaya').find('.spinner-text').removeClass(
                            'd-none');
                    },
                    success: function(response) {
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
