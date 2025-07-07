@extends('inc.layout')
@section('title', 'Gudang Opname')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row justify-content-center">
            <div class="col-xl-8">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Stock Opname: <span class="fw-300"><i>Gudang Opname</i></span>
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">

                            <form action="{{ route('warehouse.revaluasi-stock.stock-opname.gudang-opname.update') }}"
                                method="post">
                                @method('PUT')
                                @csrf
                                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                <input type="hidden" name="employee_id" value="{{ auth()->user()->employee->id }}">

                                <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                                    <i id="loading-spinner" class="fas fa-spinner fa-spin"></i>
                                    <thead class="bg-primary-600">
                                        <tr>
                                            <th width="5%">#</th>
                                            <th>Nama Gudang</th>
                                            <th width="10%">Opname?</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($gudangs as $gudang)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $gudang->nama }}</td>
                                                <td><input type="checkbox" class="form-control pointer"
                                                        name="opname[{{ $gudang->id }}]" value="1"
                                                        {{ $gudang->ongoing_stock_opname ? 'checked' : '' }}></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>#</th>
                                            <th>Nama Gudang</th>
                                            <th>Opname?</th>
                                        </tr>
                                    </tfoot>
                                </table>

                                <div class="row justify-content-end mt-3">
                                    <div class="col-2">
                                        <button type="submit" class="btn btn-primary waves-effect waves-themed"
                                            id="tambah-btn">
                                            <span class="fal fa-save mr-1"></span>
                                            Simpan
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
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>

    <script>
        var controls = {
            leftArrow: '<i class="fal fa-angle-left" style="font-size: 1.25rem"></i>',
            rightArrow: '<i class="fal fa-angle-right" style="font-size: 1.25rem"></i>'
        }

        $(document).ready(function() {

            $('#loading-spinner').show();
            // initialize datatable
            $('#dt-basic-example').dataTable({
                "drawCallback": function(settings) {
                    // Menyembunyikan preloader setelah data berhasil dimuat
                    $('#loading-spinner').hide();
                },
                paging: false,
                responsive: true,
                lengthChange: false,
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
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
    </script>


@endsection
