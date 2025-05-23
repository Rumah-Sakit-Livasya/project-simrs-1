@extends('inc.layout')
@section('title', 'Tambah AP Supplier')
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
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div class="panel">
                    <div class="panel-hdr">
                        <h2>Proses <span class="fw-300"><i>AP Supplier</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form action="#" method="post">
                                <div class="form-row">
                                    <div class="col-md-4 mb-3">
                                        <label>Tanggal AP</label>
                                        <input type="text" class="form-control datepicker" name="tanggal_ap"
                                            value="22-05-2025">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label>Due Date</label>
                                        <input type="text" class="form-control datepicker" name="duedate">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label>Tgl Faktur Pajak</label>
                                        <input type="text" class="form-control datepicker" name="tgl_faktur_pajak"
                                            value="22-05-2025">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-md-6 mb-3">
                                        <label>Supplier</label>
                                        <select class="form-control select2" name="supplier_id">
                                            <option value="">Pilih Supplier</option>
                                            <option value="1">PT. PARIT PADANG GLOBAL</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>No Invoice</label>
                                        <input type="text" class="form-control" name="no_invoice">
                                    </div>
                                </div>

                                <!-- Tabel Item -->
                                <table class="table table-bordered mt-3">
                                    <thead class="bg-primary-600 text-white">
                                        <tr>
                                            <th width="30">#</th>
                                            <th>Tgl GRN</th>
                                            <th>No. GRN</th>
                                            <th>No. PO</th>
                                            <th>Keterangan</th>
                                            <th>Diskon</th>
                                            <th>Biaya Lainnya</th>
                                            <th>Nominal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="8" class="text-center">No data to display</td>
                                        </tr>
                                    </tbody>
                                </table>

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <textarea class="form-control" rows="4" placeholder="Notes here"></textarea>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" checked> Kwitansi<br>
                                            <input class="form-check-input" type="checkbox" checked> Faktur Pajak<br>
                                            <input class="form-check-input" type="checkbox"> Surat Jalan<br>
                                            <input class="form-check-input" type="checkbox" checked> Salinan PO<br>
                                            <input class="form-check-input" type="checkbox" checked> Tanda Terima Barang<br>
                                            <input class="form-check-input" type="checkbox"> Berita Acara
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-md-6 col-form-label">Retur</label>
                                            <div class="col-md-6">
                                                <input type="number" class="form-control" name="retur" value="0">
                                            </div>
                                            <label class="col-md-6 col-form-label">Diskon Final</label>
                                            <div class="col-md-6">
                                                <input type="number" class="form-control" name="diskon_final"
                                                    value="0">
                                            </div>
                                            <label class="col-md-6 col-form-label">PPN (%)</label>
                                            <div class="col-md-6">
                                                <input type="number" class="form-control" name="ppn" value="0">
                                            </div>
                                            <label class="col-md-6 col-form-label">Material</label>
                                            <div class="col-md-6">
                                                <input type="number" class="form-control" name="material" value="0">
                                            </div>
                                            <label class="col-md-6 col-form-label">Grand Total</label>
                                            <div class="col-md-6">
                                                <input type="number" class="form-control" name="grand_total"
                                                    value="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row mt-3">
                                    <div class="col-md-12 text-right">
                                        <button type="button" class="btn btn-secondary">Back</button>
                                        <button type="button" class="btn btn-info">Pilih GRN</button>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
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
