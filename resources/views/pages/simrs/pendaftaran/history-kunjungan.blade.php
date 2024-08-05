@extends('inc.layout')
@section('title', 'Daftar Rekam Medis')
@section('content')
<main id="js-page-content" role="main" class="page-content">
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div id="panel-1" class="panel">
                <div class="panel-hdr">
                    <h2>
                        History Kunjungan <span class="fw-300"><i>Pasien</i></span>
                    </h2>
                </div>
                <div class="panel-container show">
                    <div class="panel-content">

                        <form action="/history-kunjungan" method="get">
                            @csrf
                            <div class="row justify-content-center">
                                <div class="col-xl-4">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-xl-4" style="text-align: right">
                                                <label for="registration_number">No. Registrasi</label>
                                            </div>
                                            <div class="col-xl">
                                                <input type="text" value="{{ request('registration_number') }}"
                                                    style="border: 0; border-bottom: 1.9px solid #FD61AA; margin-top: -.5rem; border-radius: 0"
                                                    class="form-control" id="registration_number"
                                                    name="registration_number">
                                                @error('registration_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-end mt-3">
                                <div class="col-xl-3">
                                    <button type="submit" class="btn btn-outline-primary waves-effect waves-themed">
                                        <span class="fal fa-search mr-1"></span>
                                        Cari
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
                        Daftar <span class="fw-300"><i>Kunjungan Pasien</i></span>
                    </h2>
                </div>
                <div class="panel-container show">
                    <div class="panel-content">
                        <!-- datatable start -->
                        <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                            <i id="loading-spinner" class="fas fa-spinner fa-spin"></i>
                            <thead class="bg-primary-600">
                                <tr>
                                    <th>#</th>
                                    <th>No. Reg</th>
                                    <th>Tgl Masuk</th>
                                    <th>Tgl Keluar</th>
                                    <th>Jml Type Reg</th>
                                    <th>Unit</th>
                                    <th>Dokter</th>
                                    <th>Diagnosa Awal</th>
                                    <th>Diagnosa Akhir</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>No. Reg</th>
                                    <th>Tgl Masuk</th>
                                    <th>Tgl Keluar</th>
                                    <th>Jml Type Reg</th>
                                    <th>Unit</th>
                                    <th>Dokter</th>
                                    <th>Diagnosa Awal</th>
                                    <th>Diagnosa Akhir</th>
                                    <th>Status</th>
                                </tr>
                            </tfoot>
                        </table>
                        <!-- datatable end -->
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
<script>
    $(document).ready(function() {
            $('#loading-spinner').show();
            // initialize datatable
            $('#dt-basic-example').dataTable({
                "drawCallback": function(settings) {
                    // Menyembunyikan preloader setelah data berhasil dimuat
                    $('#loading-spinner').hide();
                },
                responsive: true,
                lengthChange: false,
                dom:
                    "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [
                    {
                        extend: 'pdfHtml5',
                        text: 'PDF',
                        titleAttr: 'Generate PDF',
                        className: 'btn-outline-danger btn-sm mr-1'
                    },
                    {
                        extend: 'excelHtml5',
                        text: 'Excel',
                        titleAttr: 'Generate Excel',
                        className: 'btn-outline-success btn-sm mr-1'
                    },
                    {
                        extend: 'csvHtml5',
                        text: 'CSV',
                        titleAttr: 'Generate CSV',
                        className: 'btn-outline-primary btn-sm mr-1'
                    },
                    {
                        extend: 'copyHtml5',
                        text: 'Copy',
                        titleAttr: 'Copy to clipboard',
                        className: 'btn-outline-primary btn-sm mr-1'
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        titleAttr: 'Print Table',
                        className: 'btn-outline-primary btn-sm'
                    }
                ]
            });

        });


        // Input RM
        function formatAngka(input) {
            var value = input.value.replace(/\D/g, '');
            var formattedValue = '';

            if (value.length > 6) {
                value = value.substr(0, 6);
            }

            if (value.length > 0) {
                formattedValue = value.match(/.{1,2}/g).join('-');
            }

            input.value = formattedValue;
        }
</script>
@endsection