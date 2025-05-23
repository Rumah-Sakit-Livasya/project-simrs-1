@extends('inc.layout')
@section('title', 'AP NON GR')
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
                                            <option value="1">KIMIA FARMA</option>
                                            <option value="2">CV. METROPOLITAN MITRA UTAMA</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-4 mb-3">
                                        <label>No Invoice</label>
                                        <input type="text" class="form-control" name="invoice_number">
                                    </div>
                                </div>
                                <div class="form-row justify-content-end">
                                    <button type="submit" class="btn btn-sm btn-primary mr-2">
                                        <i class="fal fa-search mr-1"></i> Cari
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table Panel -->
        <div class="row mt-4">
            <div class="col-xl-12">
                <div class="panel">
                    <div class="panel-hdr">
                        <h2>List <span class="fw-300"><i>AP Non GR</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table class="table table-bordered table-hover table-striped">
                                <thead class="bg-primary-600 text-white">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Kode AP</th>
                                        <th>Supplier</th>
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
                                        <td>23 Jan 2025</td>
                                        <td>APN25-000003</td>
                                        <td>KIMIA FARMA</td>
                                        <td>2808081925</td>
                                        <td>22 Feb 2025</td>
                                        <td class="text-right">165.887,00</td>
                                        <td>-</td>
                                        <td>Dede Rizki Nurfauzi, S.Ak.</td>
                                        <td class="text-center">
                                            <button class="btn btn-xs btn-success" title="Print"><i
                                                    class="fal fa-print"></i></button>
                                            <a href="{{ route('keuangan.ap-non-gr.edit', ['id' => 1]) }}">
                                                <button class="btn btn-xs btn-primary" title="Edit"><i
                                                        class="fal fa-edit"></i></button>
                                            </a>
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
