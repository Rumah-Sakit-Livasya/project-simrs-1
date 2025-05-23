@extends('inc.layout')
@section('title', 'Laporan Jatuh Tempo')

@section('content')
    <style>
        table {
            font-size: 8pt !important;
        }

        .form-control {
            border: 0;
            border-bottom: 1.9px solid #eaeaea;
            border-radius: 0;
            padding-left: 0;
            padding-right: 0;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #eaeaea;
        }

        .select2-selection {
            border: 0 !important;
            border-bottom: 1.9px solid #eaeaea !important;
            border-radius: 0 !important;
        }

        .panel-loading {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 999;
        }
    </style>

    <main id="js-page-content" role="main" class="page-content">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div class="panel">
                    <div class="panel-hdr">
                        <h2>Laporan <span class="fw-300"><i>Jatuh Tempo</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form action="#" method="get">
                                <div class="form-row">
                                    <div class="col-md-6 mb-3">
                                        <label>Awal Duedate</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" name="awal_due"
                                                value="{{ request('awal_due') ?? '' }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text fs-xl">
                                                    <i class="fal fa-calendar"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Akhir Duedate</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" name="akhir_due"
                                                value="{{ request('akhir_due') ?? '' }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text fs-xl">
                                                    <i class="fal fa-calendar"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Supplier</label>
                                        <select class="form-control select2" name="supplier_id">
                                            <option value="">Pilih Supplier</option>
                                            <!-- Tambahkan opsi supplier -->
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Invoice</label>
                                        <input type="text" class="form-control" name="invoice"
                                            value="{{ request('invoice') ?? '' }}">
                                    </div>
                                </div>
                                <div class="form-row justify-content-end">
                                    <button type="submit" class="btn btn-sm btn-primary mr-2">
                                        <i class="fal fa-search mr-1"></i> Cari
                                    </button>
                                    <button type="button" class="btn btn-sm btn-success">
                                        <i class="fal fa-file-excel mr-1"></i> Export
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="panel">
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600 text-white">
                                    <tr>
                                        <th>No</th>
                                        <th>Supplier</th>
                                        <th>Inv Number</th>
                                        <th>Kode AP</th>
                                        <th>Tgl AP</th>
                                        <th>Duedate</th>
                                        <th>DPP</th>
                                        <th>PPN</th>
                                        <th>Total Hutang</th>
                                        <th>Sisa Hutang</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>PT. ENSEVAL PUTERA MEGATRADING</td>
                                        <td>123456789</td>
                                        <td>APS25-000123</td>
                                        <td>2025-05-01</td>
                                        <td>2025-05-23</td>
                                        <td>100.000.000,00</td>
                                        <td>10.000.000,00</td>
                                        <td>110.000.000,00</td>
                                        <td>50.000.000,00</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="6" class="text-right font-weight-bold">Total</td>
                                        <td class="text-right font-weight-bold">T:100.000.000,00</td>
                                        <td class="text-right font-weight-bold">T:10.000.000,00</td>
                                        <td class="text-right font-weight-bold">T:110.000.000,00</td>
                                        <td class="text-right font-weight-bold">T:50.000.000,00</td>
                                    </tr>
                                </tfoot>
                            </table>
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
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/dependency/moment/moment.js"></script>
    <script src="/js/formplugins/bootstrap-daterangepicker/bootstrap-daterangepicker.js"></script>
    <script src="/js/formplugins/inputmask/inputmask.bundle.js"></script>
    <script src="/js/formplugins/sweetalert2/sweetalert2.bundle.js"></script>
    <script src="/js/notifications/toastr/toastr.js"></script>
    <link rel="stylesheet" href="/css/notifications/toastr/toastr.css">

    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Pilih Supplier",
                allowClear: true,
                width: 'resolve'
            });

            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                todayHighlight: true,
                autoclose: true,
                orientation: "bottom auto"
            });

            $('#dt-basic-example').DataTable({
                responsive: true,
                paging: true,
                ordering: true,
                searching: true,
                autoWidth: false,
                order: [
                    [0, 'asc']
                ]
            });
        });
    </script>
@endsection
