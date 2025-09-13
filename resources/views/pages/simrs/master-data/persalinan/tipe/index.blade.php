@extends('inc.layout')
@section('title', 'Kategori Persalinan')
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
@endsection
@section('content')
    <main id="js-page-content" role="main" class="page-content">

        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Tipe Persalinan
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
                                            <th>Persentase</th>
                                            <th>Operator</th>
                                            <th>Anestesi</th>
                                            <th>Prediatric</th>
                                            <th>Room</th>
                                            <th>Observasi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tipe_persalinan as $row)
                                            <tr>
                                                <td>{{ $row->tipe }}</td>
                                                <td><input type="number" name="persentase[{{ $row->id }}]"
                                                        data-id="{{ $row->id }}" value="{{ $row->persentase }}">
                                                </td>
                                                <td>
                                                    <input type="checkbox" name="operator[{{ $row->id }}]"
                                                        data-id="{{ $row->id }}"
                                                        {{ $row->operator == 1 ? 'checked' : '' }}>
                                                </td>
                                                <td>
                                                    <input type="checkbox" name="anestesi[{{ $row->id }}]"
                                                        data-id="{{ $row->id }}"
                                                        {{ $row->anestesi == 1 ? 'checked' : '' }}>
                                                </td>
                                                <td>
                                                    <input type="checkbox" name="prediatric[{{ $row->id }}]"
                                                        data-id="{{ $row->id }}"
                                                        {{ $row->prediatric == 1 ? 'checked' : '' }}>
                                                </td>
                                                <td>
                                                    <input type="checkbox" name="room[{{ $row->id }}]"
                                                        data-id="{{ $row->id }}"
                                                        {{ $row->room == 1 ? 'checked' : '' }}>
                                                </td>
                                                <td>
                                                    <input type="checkbox" name="observasi[{{ $row->id }}]"
                                                        data-id="{{ $row->id }}"
                                                        {{ $row->observasi == 1 ? 'checked' : '' }}>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" class="text-center">
                                                <button type="button"
                                                    class="btn btn-outline-primary waves-effect waves-themed"
                                                    id="btn-tambah-tipe">
                                                    <span class="fal fa-plus-circle"></span>
                                                    Tambah Tipe Persalinan
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
    @include('pages.simrs.master-data.persalinan.tipe.partials.tambah')
@endsection
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(document).ready(function() {
            let kategoriId = null;
            $('#loading-spinner').show();

            $('#btn-tambah-tipe').click(function() {
                $('#modal-tambah-tipe').modal('show');
            });

            // Event listener untuk checkbox
            $('input[type="checkbox"]').change(function() {
                let checkbox = $(this);
                let fieldName = checkbox.attr('name');
                let isChecked = checkbox.is(':checked');
                let tipeId = checkbox.attr('data-id'); // Mengambil id dari atribut data-id

                // Membuat URL dengan id yang dinamis
                let url = '{{ route('master-data.persalinan.tipe.update', ':tipeId') }}';
                url = url.replace(':tipeId',
                    tipeId); // Mengganti placeholder :id dengan nilai sebenarnya dari id

                $.ajax({
                    url: url, // Menggunakan URL yang sudah diganti
                    type: 'PATCH',
                    data: {
                        _token: '{{ csrf_token() }}', // CSRF token
                        id: tipeId,
                        field: fieldName, // Nama field (misalnya 'operator[1]')
                        value: isChecked ? 1 : 0 // Status checkbox (checked atau tidak)
                    },
                    success: function(response) {
                        // Berhasil update
                        console.log(response.message);
                        showSuccessAlert(response.message);
                    },
                    error: function(xhr, status, error) {
                        // Jika terjadi error
                        console.error(xhr.responseText);
                        showErrorAlert('Terjadi kesalahan. Mohon coba lagi.');
                    }
                });
            });



            $('#store-form').on('submit', function(e) {
                e.preventDefault(); // Mencegah form submit secara default

                var formData = $(this).serialize(); // Mengambil semua data dari form

                $.ajax({
                    url: '{{ route('master-data.persalinan.tipe.store') }}',
                    type: 'POST',
                    data: formData,
                    beforeSend: function() {
                        $('#store-form').find('.ikon-tambah').hide();
                        $('#store-form').find('.spinner-text').removeClass(
                            'd-none');
                    },
                    success: function(response) {
                        $('#modal-tambah-tipe').modal('hide');
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

                            $('#modal-tambah-tipe').modal('hide');
                            showErrorAlert('Terjadi kesalahan:\n' +
                                errorMessages);
                        } else {
                            $('#modal-tambah-tipe').modal('hide');
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
