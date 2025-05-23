@extends('inc.layout')
@section('title', 'Details Pembayaran AP Supplier')

@section('content')
    <style>
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

        table {
            font-size: 8pt !important;
        }
    </style>

    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">
                <div class="panel">
                    <div class="panel-hdr">
                        <h2>Proses <span class="fw-300"><i>Pembayaran</i></span> <span class="ml-2">Doc ID
                                25-20-000371</span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <label>Tanggal Pembayaran</label>
                                    <input type="text" class="form-control" value="30-04-2025" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label>Supplier</label>
                                    <input type="text" class="form-control" value="TRISNA AUFA" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label>Metode Pembayaran</label>
                                    <input type="text" class="form-control" value="TRANSFER" readonly>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <label>Kas/Bank</label>
                                    <input type="text" class="form-control" value="BANK BNI" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label>Nomor (Giro, Transfer)</label>
                                    <input type="text" class="form-control" value="" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label>Keterangan</label>
                                    <input type="text" class="form-control" value="" readonly>
                                </div>
                            </div>

                            <table class="table table-bordered table-striped">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th>Tgl AP</th>
                                        <th>Due Date</th>
                                        <th>Kode AP</th>
                                        <th>No. Invoice</th>
                                        <th>Nominal Hutang</th>
                                        <th>Belum Dibayar</th>
                                        <th>Potongan</th>
                                        <th>Biaya Lainnya</th>
                                        <th>Pembayaran</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>28 Mar 2025</td>
                                        <td>15 Apr 2025</td>
                                        <td>APN25-000104</td>
                                        <td>23667</td>
                                        <td class="text-right">Rp 5.736.500,00</td>
                                        <td class="text-right">Rp 0,00</td>
                                        <td class="text-right">Rp 0,00</td>
                                        <td class="text-right">Rp 0,00</td>
                                        <td class="text-right">Rp 5.736.500,00</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4" class="text-right font-weight-bold">Total</td>
                                        <td class="text-right font-weight-bold">Rp 5.736.500,00</td>
                                        <td class="text-right font-weight-bold">Rp 0,00</td>
                                        <td class="text-right font-weight-bold">Rp 0,00</td>
                                        <td class="text-right font-weight-bold">Rp 0,00</td>
                                        <td class="text-right font-weight-bold">Rp 5.736.500,00</td>
                                    </tr>
                                </tfoot>
                            </table>

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <label>Pembulatan</label>
                                    <input type="text" class="form-control" value="0" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label>Grand Total</label>
                                    <input type="text" class="form-control" value="5.736.500" readonly>
                                </div>
                            </div>

                            <div class="mt-4">
                                <a href="#" class="btn btn-secondary"> <i class="fal fa-arrow-left mr-1"></i> Back</a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
