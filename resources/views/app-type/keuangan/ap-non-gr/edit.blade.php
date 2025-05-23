@extends('inc.layout')
@section('title', 'Edit AP NON GR')
@section('content')
    <style>
        /* .form-control {
                border: 0;
                border-bottom: 1.9px solid #eaeaea;
                border-radius: 0;
                padding-left: 0;
                padding-right: 0;
            } */

        .form-control:focus {
            box-shadow: none;
            border-color: #eaeaea;
        }

        .select2-selection {
            border: 0 !important;
            border-bottom: 1.9px solid #eaeaea !important;
            border-radius: 0 !important;
        }

        table {
            font-size: 8pt !important;
        }
    </style>
    <main id="js-page-content" role="main" class="page-content">
        <div class="row justify-content-center">
            <div class="col-xl-12">
                <div class="panel">
                    <div class="panel-hdr">
                        <h2>Form <span class="fw-300"><i>AP Non GR</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form action="#" method="post">
                                @csrf
                                <div class="form-row">
                                    <div class="col-md-4 mb-3">
                                        <label>Periode Awal</label>
                                        <input type="text" class="form-control datepicker" name="periode_awal"
                                            value="23-01-2025">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label>Supplier</label>
                                        <select class="form-control select2" name="supplier_id">
                                            <option value="1" selected>KIMIA FARMA</option>
                                            <option value="2">CV. METROPOLITAN MITRA UTAMA</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label>Due Date</label>
                                        <input type="text" class="form-control datepicker" name="due_date"
                                            value="22-02-2025">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-md-4 mb-3">
                                        <label>No Invoice</label>
                                        <input type="text" class="form-control" name="no_invoice" value="2808081925">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label>Faktur Pajak</label>
                                        <input type="text" class="form-control" name="faktur_pajak">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label>Tgl Faktur Pajak</label>
                                        <input type="text" class="form-control datepicker" name="tgl_faktur_pajak">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-md-12 mb-3">
                                        <label>Keterangan</label>
                                        <input type="text" class="form-control" name="keterangan"
                                            value="Beban Langsung Penunjang Medis - Obat Rawat Inap">
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="bg-primary-600 text-white">
                                            <tr>
                                                <th>Nama Transaksi</th>
                                                <th>Keterangan</th>
                                                <th>Cost Center</th>
                                                <th>Nominal</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>5212020000 Beban Langsung Penunjang Medis - Obat Rawat Inap</td>
                                                <td>
                                                    <input type="text" class="form-control" name="keterangan_detail"
                                                        value="Beban Langsung Penunjang Medis - Obat Rawat Inap">
                                                </td>
                                                <td>
                                                    <select class="form-control select2" name="cost_center">
                                                        <option value="APOTIK" selected>APOTIK</option>
                                                        <option value="IGD">IGD</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="nominal_detail"
                                                        value="165.887">
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-sm btn-danger"><i
                                                            class="fal fa-times"></i></button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="form-row mt-3">
                                    <div class="col-md-2 offset-md-8 text-right">
                                        <label>PPN (%)</label>
                                        <input type="number" class="form-control" name="ppn" value="0">
                                    </div>
                                    <div class="col-md-2 text-right">
                                        <label>Total</label>
                                        <input type="text" class="form-control" name="total" value="165.887">
                                    </div>
                                </div>

                                <div class="form-row mt-4 justify-content-end">
                                    <a href="#" class="btn btn-secondary mr-2">Back</a>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
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
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script>
        $(document).ready(function() {
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true,
                clearBtn: true,
                language: 'id',
                orientation: 'bottom auto'
            });

            $('.select2').select2({
                placeholder: "Pilih...",
                allowClear: true
            });
        });
    </script>
@endsection
