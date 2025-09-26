@extends('inc.layout')
@section('title', 'Daftar Order Gizi')

@section('extended-css')
    <link rel="stylesheet" media="screen, print" href="/css/datagrid/datatables/datatables.bundle.css">
    <link rel="stylesheet" media="screen, print" href="/css/formplugins/select2/select2.bundle.css">
    <style>
        td.details-control::before {
            font-family: "Boxicons" !important;
            font-weight: normal;
            content: "\ec08";
            color: #28a745;
            font-size: 1.5rem;
            line-height: 1;
            vertical-align: middle;
        }

        tr.shown td.details-control::before {
            content: "\ebc8";
            color: #dc3545;
        }

        .dataTables_processing {
            z-index: 1080 !important;
        }

        #dt-order thead th {
            vertical-align: middle;
        }
    </style>
@endsection

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        @include('pages.simrs.gizi.partials.list-order-form')

        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Daftar Order Gizi</h2>
                        <div class="panel-toolbar">
                            <button type="button" class="btn btn-sm btn-primary mr-2" id="bulk-send-btn"
                                title="Kirim semua pesanan yang dipilih">
                                <i class="fal fa-truck mr-1"></i> Kirim Terpilih
                            </button>
                            <button type="button" class="btn btn-sm btn-info" id="bulk-print-btn"
                                title="Print label semua pesanan yang dipilih">
                                <i class="fal fa-tags mr-1"></i> Label Terpilih
                            </button>
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table id="dt-order" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th></th> {{-- Kolom untuk Expander Child Row --}}
                                        {{-- KOLOM UNTUK CHECKBOX --}}
                                        <th class="text-center" style="width: 25px;"><input type="checkbox"
                                                id="select-all-checkbox"></th>
                                        <th>Pemesan</th>
                                        <th>Untuk</th>
                                        <th>Pasien</th>
                                        <th>No RM / Reg</th>
                                        <th>Waktu Makan</th>
                                        <th>Harga</th>
                                        <th>Ditagihkan</th>
                                        <th>Pembayaran</th>
                                        <th>Pesanan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
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
    <script src="/js/simrs/order-gizi.js"></script>
@endsection
