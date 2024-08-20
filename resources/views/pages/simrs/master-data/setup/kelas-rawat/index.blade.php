@extends('inc.layout')
@section('title', 'Kelas Rawat')
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
                            Kelas Rawat
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
                                            <th>No</th>
                                            <th>Kelas Rawat</th>
                                            <th>Jumlah Ruangan</th>
                                            <th>Jumlah T. Tidur</th>
                                            <th>Bed Tambahan</th>
                                            <th>Jml Bed (BOR)</th>
                                            <th>Keterangan</th>
                                            <th>Urutan</th>
                                            <th>Fungsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($kelas_rawat as $row)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <a
                                                        href="{{ route('master-data.setup.rooms', $row->id) }}">{{ $row->kelas }}</a>
                                                </td>
                                                <td>{{ $row->rooms->count() }}</td>
                                                <td>{{ $row->beds->count() }}</td>
                                                <td>{{ $row->bedTambahan->count() }}</td>
                                                <td>{{ $row->bedBor->count() }}</td>
                                                <td>{{ $row->keterangan }}</td>
                                                <td>{{ $row->urutan }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-success px-2 py-1 btn-edit"
                                                        data-id="{{ $row->id }}">
                                                        <i class="fas fa-pencil"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-primary px-2 py-1 btn-tarif"
                                                        data-id="{{ $row->id }}">
                                                        <i class="fas fa-money-bill"></i>
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
                                                    id="btn-tambah-kelas" data-toggle="modal"
                                                    data-target="#modal-tambah-kelas" data-action="tambah">
                                                    <span class="fal fa-plus-circle"></span>
                                                    Tambah Kelas Rawat
                                                </button>
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <!-- datatable end -->
                    </div>
                </div>
            </div>
        </div>
        </div>
    </main>
    @include('pages.simrs.master-data.setup.kelas-rawat.partials.edit-kelas')
    @include('pages.simrs.master-data.setup.kelas-rawat.partials.edit-tarif')
    @include('pages.simrs.master-data.setup.kelas-rawat.partials.tambah-kelas')
@endsection
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(document).ready(function() {
            let kelasId = null;
            $('#loading-spinner').show();

            $('#btn-tambah-kelas').click(function() {
                $('#modal-tambah-kelas').modal('show');
            });

            $('#modal-tambah-kelas .select2').select2({
                dropdownParent: $('#modal-tambah-kelas')
            });

            $('.btn-edit').click(function() {
                $('#modal-edit-kelas').modal('show');
                kelasId = $(this).attr('data-id');
                $.ajax({
                    url: '/api/simrs/master-data/setup/kelas-rawat/' + kelasId,
                    type: 'GET',
                    success: function(response) {
                        // Isi form dengan data yang diterima
                        $('#modal-edit-kelas #kelas').val(response.kelas);
                        $('#modal-edit-kelas #urutan').val(response.urutan);
                        $('#modal-edit-kelas #keterangan').val(response.keterangan);
                        // Set radio button berdasarkan nilai isICU dari database
                        if (response.isICU == 1) {
                            $('#modal-edit-kelas #isICU_aktif').prop('checked', true);
                        } else {
                            $('#modal-edit-kelas #isICU_tidak').prop('checked', true);
                        }
                    },
                    error: function(xhr, status, error) {
                        showErrorAlert('Terjadi kesalahan: ' + error);
                    }
                });
            });

            $('.btn-tarif').click(function() {
                $('#modal-edit-tarif').modal('show');
                let kelasId = $(this).attr('data-id');

                $.ajax({
                    url: '/api/simrs/master-data/setup/tarif/' + kelasId,
                    type: 'GET',
                    success: function(response) {
                        $('#modal-edit-tarif #kelas-rawat-id').val(kelasId);
                        // Menggunakan map untuk membuat elemen HTML
                        let inputFields = response.map(function(tarif) {
                            return `
                                <input type="hidden" name="tarif_id[${tarif.id}]" value="${tarif.id}">
                                <div class="form-group mt-3">
                                    <label for="tarif-${tarif.group_penjamin_id}" class="form-label">
                                        ${tarif.group_penjamin.name}
                                    </label>
                                    <input type="number" class="form-control" id="tarif-${tarif.group_penjamin_id}" 
                                        name="tarif[${tarif.group_penjamin_id}]" value="${tarif.tarif}" placeholder="Masukkan tarif untuk ${tarif.group_penjamin.name}">
                                </div>`;
                        }).join('');

                        // Menampilkan input fields langsung menggunakan html
                        $('#tarif-inputs').html(inputFields);
                    },
                    error: function(xhr, status, error) {
                        alert('Terjadi kesalahan: ' + error);
                    }
                });
            });

            $('#update-tarif-form').submit(function(e) {
                e.preventDefault();
                let kelasId = $(this).attr('data-kelas-id');
                let tarifData = $(this).serializeArray(); // Mengambil data form dalam format array

                $.ajax({
                    url: '/api/simrs/master-data/setup/tarif/', // Sesuaikan endpoint jika perlu
                    type: 'PATCH',
                    data: tarifData,
                    beforeSend: function() {
                        $('#btn-update').prop('disabled', true);
                        $('.spinner-text').removeClass('d-none');
                    },
                    success: function(response) {
                        $('#modal-edit-tarif').modal('hide');
                        showSuccessAlert(response.message);

                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    },
                    error: function(xhr, status, error) {
                        alert('Terjadi kesalahan: ' + error);
                    },
                    complete: function() {
                        $('#btn-update').prop('disabled', false);
                        $('.spinner-text').addClass('d-none');
                    }
                });
            });


            $('.btn-delete').click(function() {
                var kelasId = $(this).attr('data-id');

                // Menggunakan confirm() untuk mendapatkan konfirmasi dari pengguna
                var userConfirmed = confirm('Anda Yakin ingin menghapus ini?');

                if (userConfirmed) {
                    // Jika pengguna mengklik "Ya" (OK), maka lakukan AJAX request
                    $.ajax({
                        url: '/api/simrs/master-data/setup/kelas-rawat/' + kelasId + '/delete',
                        type: 'DELETE',
                        success: function(response) {
                            showSuccessAlert(response.message);

                            setTimeout(() => {
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

                var formData = $(this).serialize(); // Mengambil semua data dari form

                $.ajax({
                    url: '/api/simrs/master-data/setup/kelas-rawat/' + kelasId + '/update',
                    type: 'PATCH',
                    data: formData,
                    beforeSend: function() {
                        $('#update-form').find('.ikon-edit').hide();
                        $('#update-form').find('.spinner-text').removeClass(
                            'd-none');
                    },
                    success: function(response) {
                        $('#modal-edit-kelas').modal('hide');
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

                            $('#modal-edit-kelas').modal('hide');
                            showErrorAlert('Terjadi kesalahan:\n' +
                                errorMessages);
                        } else {
                            $('#modal-edit-kelas').modal('hide');
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
                    url: '/api/simrs/master-data/setup/kelas-rawat',
                    type: 'POST',
                    data: formData,
                    beforeSend: function() {
                        $('#store-form').find('.ikon-tambah').hide();
                        $('#store-form').find('.spinner-text').removeClass(
                            'd-none');
                    },
                    success: function(response) {
                        $('#modal-tambah-kelas').modal('hide');
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

                            $('#modal-tambah-kelas').modal('hide');
                            showErrorAlert('Terjadi kesalahan:\n' +
                                errorMessages);
                        } else {
                            $('#modal-tambah-kelas').modal('hide');
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
