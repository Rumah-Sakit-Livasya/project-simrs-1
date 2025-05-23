@extends('inc.layout')
@section('title', 'Pembayaran AP Supplier')

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
                <div class="panel">
                    <div class="panel-hdr">
                        <h2>Form <span class="fw-300"><i>Pencarian</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form action="#" method="get">
                                <div class="form-row">
                                    <div class="col-md-4 mb-3">
                                        <label>Periode Awal</label>
                                        <input type="text" class="form-control datepicker" name="tanggal_awal">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label>Periode Akhir</label>
                                        <input type="text" class="form-control datepicker" name="tanggal_akhir">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label>Supplier</label>
                                        <select class="form-control select2" name="supplier_id">
                                            <option value="">Pilih Supplier</option>
                                            <option value="1">PT. RAJAWALI NUSINDO</option>
                                            <option value="2">PT. MERAPI UTAMA PHARMA</option>
                                            <option value="3">MENSA BINASUKSES PT</option>
                                            <option value="4">KIMIA FARMA</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-4 mb-3">
                                        <label>No Invoice</label>
                                        <input type="text" class="form-control" name="no_invoice">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label>Kode AP</label>
                                        <input type="text" class="form-control" name="kode_ap">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label>Kode Payment</label>
                                        <input type="text" class="form-control" name="kode_payment">
                                    </div>
                                </div>
                                <div class="form-row justify-content-end">
                                    <button type="submit" class="btn btn-sm btn-primary mr-2"> <i
                                            class="fal fa-search mr-1"></i> Cari</button>
                                    <a href="{{ route('keuangan.pembayaran-ap-supplier.create') }}"
                                        class="btn btn-sm btn-success"> <i class="fal fa-plus mr-1"></i>
                                        Tambah Baru</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Data -->
        <div class="row">
            <div class="col-xl-12">
                <div class="panel">
                    <div class="panel-hdr">
                        <h2>List <span class="fw-300"><i>Payment AP Supplier</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table class="table table-bordered table-hover table-striped">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Kode Payment</th>
                                        <th>Supplier</th>
                                        <th>Kas/Bank</th>
                                        <th>Keterangan</th>
                                        <th>Nominal</th>
                                        <th>User Entry</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>06 Feb 2025</td>
                                        <td>PAP25-000107</td>
                                        <td>PT. RAJAWALI NUSINDO</td>
                                        <td>BANK BNI</td>
                                        <td>-</td>
                                        <td>Rp 2.102.137,76</td>
                                        <td>Dede Rizki Nurfauzi, S.Ak.</td>
                                        <td class="text-center">
                                            <a href="{{ route('keuangan.pembayaran-ap-supplier.details') }}"
                                                class="btn btn-sm btn-success" data-toggle="tooltip" title="Detail">
                                                <i class="fal fa-check-circle"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <!-- Tambahkan baris lainnya sesuai data -->
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
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script>
        $(document).ready(function() {
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true,
                clearBtn: true,
                language: 'id'
            });

            $('.select2').select2({
                placeholder: 'Pilih...',
                allowClear: true
            });
        });
    </script>
@endsection
