@extends('inc.layout')
@section('title', 'Suku')
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
                            Suku
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
                                        <thead class="bg-primary-600">
                                            <tr>
                                                <th>Nama Suku</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($ethnics as $ethnic)
                                                <tr>
                                                    <td>{{ $ethnic->name }}</td>
                                                    <td>
                                                        <button title="Edit suku"
                                                            class="btn btn-sm btn-success px-2 py-1 btn-edit"
                                                            data-id="{{ $ethnic->id }}">
                                                            <i class="fas fa-pencil"></i>
                                                        </button>
                                                        <button title="Hapus suku" data-name="{{ $ethnic->name }}"
                                                            class="btn btn-sm btn-danger px-2 py-1 btn-delete"
                                                            data-id="{{ $ethnic->id }}">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="4" class="text-center">
                                                    <button type="button"
                                                        class="btn btn-outline-primary waves-effect waves-themed"
                                                        id="btn-tambah-suku">
                                                        <span class="fal fa-plus-circle"></span>
                                                        Tambah Suku
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
    @include('pages.simrs.master-data.setup.ethnics.partials.modal-add')
@endsection
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(document).ready(function() {
            // on btn-tambah-suku click
            // show modal
            $('#btn-tambah-suku').on('click', function() {
                $('#modal-tambah-ethnic').modal('show');
            });

            // listen to btn-delete click event
            $('.btn-delete').on('click', function(event) {
                // prevent default
                event.preventDefault();

                var id = $(this).data('id');
                var name = $(this).data('name');
                var url = '/api/simrs/master-data/setup/ethnics/delete/' + id;
                var message = 'Hapus suku ' + name + '?';

                // fire alert with sweetalert2
                Swal.fire({
                    title: 'Hapus Suku',
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Hapus'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // send delete request to server
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                // show success message with sweetalert2
                                Swal.fire({
                                    title: 'Berhasil',
                                    text: 'Suku berhasil dihapus',
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(function() {
                                    // reload page
                                    location.reload();
                                });
                            },
                            error: function(xhr, status, error) {
                                Swal.fire({
                                    title: 'Gagal',
                                    text: 'Suku gagal dihapus',
                                    icon: 'error',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            }
                        });
                    }
                });
            });

            $('#store-form').on('submit', function(e) {
                console.log('submitted');

                e.preventDefault(); // Mencegah form submit secara default

                var form = $(this);
                var formData = form.serialize();

                // log to console input with name 'name' within the form
                console.log(form.find('input[name="name"]').val());

                $.ajax({
                    url: '/api/simrs/master-data/setup/ethnics/create',
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        $('#modal-tambah-ethnic').find('#btn-tambah').prop(
                            'disabled', true);
                        $('#modal-tambah-ethnic').find('.ikon-tambah').hide();
                        $('#modal-tambah-ethnic').find('.spinner-text')
                            .removeClass(
                                'd-none');
                    },
                    success: function(response) {
                        $('#modal-tambah-ethnic').modal('hide');
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

                            $('#modal-tambah-ethnic').modal('hide');
                            showErrorAlert('Terjadi kesalahan:\n' +
                                errorMessages);
                        } else {
                            $('#modal-tambah-ethnic').modal('hide');
                            showErrorAlert('Terjadi kesalahan: ' + error);
                            console.log(error);
                        }
                    }
                });
            });
        });
    </script>
@endsection
