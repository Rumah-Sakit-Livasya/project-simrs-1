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
                                        <label class="mb-1">Tanggal AP</label>
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control form-control-sm datepicker"
                                                name="tanggal_AP" placeholder="Pilih Tanggal AP" value=""
                                                autocomplete="off">
                                            <div class="input-group-append">
                                                <span class="input-group-text fs-sm">
                                                    <i class="fal fa-calendar"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="mb-1">Due Date</label>
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control form-control-sm datepicker"
                                                name="due_date" placeholder="Atur Jatuh Tempo" value=""
                                                autocomplete="off">
                                            <div class="input-group-append">
                                                <span class="input-group-text fs-sm">
                                                    <i class="fal fa-calendar"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="mb-1">Tanggal Faktur Pajak</label>
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control form-control-sm datepicker"
                                                name="Tanggal_faktur_pajak" placeholder="Tanggal Faktur Pajak"
                                                value="" autocomplete="off">
                                            <div class="input-group-append">
                                                <span class="input-group-text fs-sm">
                                                    <i class="fal fa-calendar"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-md-6 mb-3">
                                        <label class="mb-1">Supplier</label> {{-- Mengganti "supplier" menjadi "Supplier" --}}
                                        <select class="form-control form-control-sm select2" name="supplier">
                                            <option value="">All</option>
                                            <option value="test-supplier-1">Test Supplier 1</option>
                                            <option value="test-supplier-2">Test Supplier 2</option>
                                            <option value="test-supplier-3">Test Supplier 3</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="mb-1">No Invoice</label>
                                        <input type="text" class="form-control form-control-sm" name="no_invoice"
                                            placeholder="Masukkan No Invoice" value="">
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
                                        <div class="form-check mt-2"> {{-- Menambahkan margin top untuk spasi --}}
                                            <input class="form-check-input" type="checkbox" checked> Kwitansi<br>
                                            <input class="form-check-input" type="checkbox" checked> Faktur Pajak<br>
                                            <input class="form-check-input" type="checkbox"> Surat Jalan<br>
                                            <input class="form-check-input" type="checkbox" checked> Salinan PO<br>
                                            <input class="form-check-input" type="checkbox" checked> Tanda Terima
                                            Barang<br>
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
                                                <input type="number" class="form-control" name="ppn"
                                                    value="0">
                                            </div>
                                            <label class="col-md-6 col-form-label">Material</label>
                                            <div class="col-md-6">
                                                <input type="number" class="form-control" name="material"
                                                    value="0">
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
                                        <a href="{{ route('keuangan.ap-supplier.partials.pilih-po') }}"
                                            class="btn btn-info">
                                            Pilih GRN
                                        </a>
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

@section('plugin')
    {{-- ... your existing plugin scripts ... --}}
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
        // Gunakan $(document).ready() untuk memastikan semua elemen DOM sudah dimuat
        $(document).ready(function() {

            // Inisialisasi Select2
            $('.select2').select2({
                dropdownCssClass: "move-up",
                placeholder: "Pilih opsi",
                allowClear: true
            });

            // =========================================================================
            // PERBAIKAN: Inisialisasi Datepicker untuk semua elemen dengan class .datepicker
            // =========================================================================
            $('.datepicker').datepicker({
                format: 'dd-mm-yyyy', // Mengatur format tanggal
                autoclose: true, // Kalender akan otomatis tertutup setelah tanggal dipilih
                todayHighlight: true, // Menyorot tanggal hari ini
                orientation: "bottom left" // Mengatur posisi kalender agar tidak terpotong
            });

        });
    </script>
@endsection
