@extends('inc.layout')
@section('title', 'AP Supplier')
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
        <!-- Search Panel -->
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Form <span class="fw-300"><i>Pencarian</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form action="#" method="get">
                                <div class="form-row">
                                    <div class="col-md-4 mb-3">
                                        <label>Periode Awal</label>
                                        <div class="input-group input-grup-sm">
                                            <input type="text" class="form-control datepicker" name="tanggal_awal"
                                                value="" placeholder="Pilih Tanggal Awal">
                                            <div class="input-group-append">
                                                <span class="input-group-text fs-sm">
                                                    <i class="fal fa-calendar"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label>Periode Akhir</label>
                                        <div class="input-group input-grup-sm">
                                            <input type="text" class="form-control datepicker" name="periode_akhir"
                                                value="" placeholder="Pilih Tanggal Akhir">
                                            <div class="input-group-append">
                                                <span class="input-group-text fs-sm">
                                                    <i class="fal fa-calendar"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label>Supplier</label>
                                        <select class="form-control select2" id="supplier" name="supplier_id">
                                            <option value="">Pilih Supplier</option>
                                            <option value="1">PT. PARIT PADANG GLOBAL</option>
                                            <option value="2">KIMIA FARMA</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-4 mb-3">
                                        <label>Nomor PO</label>
                                        <input type="text" class="form-control" name="po_number"
                                            placeholder="Masukkan Nomor PO">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label>No Invoice</label>
                                        <input type="text" class="form-control" name="invoice_number"
                                            placeholder="Masukkan Nomor Invoice">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label>GRN</label>
                                        <input type="text" class="form-control" name="grn_number"
                                            placeholder="Masukkan Nomor GRN">
                                    </div>
                                </div>
                                <div class="form-row justify-content-end">
                                    <button type="submit" class="btn btn-sm btn-primary mr-2">
                                        <i class="fal fa-search mr-1"></i> Cari
                                    </button>
                                    <a href="{{ route('keuangan.ap-supplier.partials.create') }}"
                                        class="btn btn-sm btn-success">
                                        <i class="fal fa-plus mr-1"></i> Tambah Baru
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="row mt-4">
            <div class="col-xl-12">
                <div class="panel">
                    <div class="panel-hdr">
                        <h2>List <span class="fw-300"><i>AP Supplier</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Kode AP</th>
                                        <th>Supplier</th>
                                        <th>PO</th>
                                        <th>No Invoice</th>
                                        <th>Duedate</th>
                                        <th>Nominal</th>
                                        <th>Keterangan</th>
                                        <th>User Entry</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>24 Jan 2025</td>
                                        <td>APS25-000028</td>
                                        <td>PT. PARIT PADANG GLOBAL</td>
                                        <td>00190/FNP02501</td>
                                        <td>8290317806</td>
                                        <td>14 Feb 2025</td>
                                        <td class="text-right">2.939.546,40</td>
                                        <td>-</td>
                                        <td>Dede Rizki Nurfauzi, S.Ak.</td>
                                        <td class="text-center">
                                            <button class="btn btn-xs btn-success" title="Print"><i
                                                    class="fal fa-print"></i></button>
                                            <button class="btn btn-xs btn-primary" title="Edit"><i
                                                    class="fal fa-edit"></i></button>
                                            <button class="btn btn-xs btn-danger" title="Delete"><i
                                                    class="fal fa-trash"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
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
