@extends('inc.layout')
@section('title', 'Aging AP Supplier')

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
            <div class="col-xl-8">
                <div class="panel">
                    <div class="panel-hdr">
                        <h2>Aging <span class="fw-300"><i>AP Supplier</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form action="#" method="get">
                                <div class="form-row">
                                    <div class="col-md-6 mb-3">
                                        <label>Sampai dengan tgl</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" name="tanggal_akhir"
                                                value="{{ request('tanggal_akhir') ?? '' }}">
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
                                        <th>Faktur Pajak</th>
                                        <th>Kode AP</th>
                                        <th>Tgl AP</th>
                                        <th>Duedate</th>
                                        <th>DPP</th>
                                        <th>PPN</th>
                                        <th>Total Hutang</th>
                                        <th>Sisa Hutang</th>
                                        <th>&lt;= 7Hari</th>
                                        <th>8-14Hari</th>
                                        <th>&gt;= 15Hari</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Contoh data dummy, ubah sesuai controller -->
                                    <tr>
                                        <td>1</td>
                                        <td>Total PT.MTA ALKES INDO</td>
                                        <td>202010002878</td>
                                        <td>01000720683645</td>
                                        <td>APS20-000589</td>
                                        <td>15-10-2020</td>
                                        <td>15-10-2020</td>
                                        <td>1.018.000,00</td>
                                        <td>101.800,00</td>
                                        <td>1.119.800,00</td>
                                        <td>1.119.800,00</td>
                                        <td>1.119.800,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="7" class="text-right font-weight-bold">Total</td>
                                        <td class="text-right font-weight-bold">T:5.118.695.493,01</td>
                                        <td class="text-right font-weight-bold">T:486.864.721,14</td>
                                        <td class="text-right font-weight-bold">T:5.605.560.214,15</td>
                                        <td class="text-right font-weight-bold">T:5.582.474.906,50</td>
                                        <td class="text-right font-weight-bold">T:5.566.339.017,50</td>
                                        <td class="text-right font-weight-bold">T:0,00</td>
                                        <td class="text-right font-weight-bold">T:16.135.889,00</td>
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
