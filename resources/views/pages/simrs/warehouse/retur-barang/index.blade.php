@extends('inc.layout')
@section('title', 'Retur Barang Gudang')

@section('extended-css')
    {{-- CSS Kustom dan Plugin --}}
    <link rel="stylesheet" media="screen, print" href="/css/datagrid/datatables/datatables.bundle.css">
    <link rel="stylesheet" media="screen, print" href="/css/formplugins/select2/select2.bundle.css">
    <link rel="stylesheet" media="screen, print"
        href="/css/formplugins/bootstrap-daterangepicker/bootstrap-daterangepicker.css">
    <style>
        .details-control {
            background: url('/img/datatable/details_open.png') no-repeat center center;
            cursor: pointer;
            width: 18px;
        }

        tr.shown .details-control {
            background: url('/img/datatable/details_close.png') no-repeat center center;
        }

        .child-table {
            width: 100%;
            border-spacing: 0;
            border-collapse: collapse;
        }

        .child-table th,
        .child-table td {
            padding: 8px;
            border: 1px solid #ddd;
        }

        .child-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }

        .child-table td {
            vertical-align: middle;
        }
    </style>
@endsection

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="subheader">
            <h1 class="subheader-title">
                <i class='subheader-icon fal fa-exchange-alt'></i> Retur Barang Gudang
                <small>Daftar retur barang ke supplier.</small>
            </h1>
        </div>
        <div class="row">
            <div class="col-xl-12">
                {{-- Panel Filter --}}
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Filter Pencarian</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form action="{{ route('warehouse.penerimaan-barang.retur-barang') }}" method="get">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label" for="tanggal_retur_filter">Tanggal Retur</label>
                                        <input type="text" class="form-control" id="tanggal_retur_filter"
                                            name="tanggal_retur" placeholder="Pilih rentang tanggal..."
                                            value="{{ request('tanggal_retur') }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label" for="kode_retur">Kode Retur</label>
                                        <input type="text" value="{{ request('kode_retur') }}" class="form-control"
                                            id="kode_retur" name="kode_retur" placeholder="Masukkan kode retur...">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label" for="supplier_id_filter">Supplier</label>
                                        <select name="supplier_id" id="supplier_id_filter" class="form-control select2">
                                            <option value="">Semua Supplier</option>
                                            @foreach ($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}"
                                                    {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                                    {{ $supplier->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label" for="nama_barang">Nama Barang</label>
                                        <input type="text" value="{{ request('nama_barang') }}" class="form-control"
                                            id="nama_barang" name="nama_barang" placeholder="Masukkan nama barang...">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary"><i class="fal fa-search"></i>
                                            Cari</button>
                                        <a href="{{ route('warehouse.penerimaan-barang.retur-barang') }}"
                                            class="btn btn-secondary">Reset</a>
                                        <button type="button" class="btn btn-primary waves-effect waves-themed float-right"
                                            id="tambah-btn">
                                            <span class="fal fa-plus mr-1"></span>
                                            Retur Barang
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Panel DataTable --}}
                <div id="panel-2" class="panel">
                    <div class="panel-hdr">
                        <h2>Daftar Retur Barang</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table id="retur-table" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th></th>
                                        <th>No</th>
                                        <th>Kode Retur</th>
                                        <th>Tanggal Retur</th>
                                        <th>Supplier</th>
                                        <th>Total Nominal</th>
                                        <th>Keterangan</th>
                                        <th>User</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rbs as $rb)
                                        <tr data-id="{{ $rb->id }}">
                                            <td class="details-control"></td>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $rb->kode_retur }}</td>
                                            <td>{{ \Carbon\Carbon::parse($rb->tanggal_retur)->format('d-m-Y') }}</td>
                                            <td>{{ $rb->supplier->nama }}</td>
                                            <td class="text-right">{{ rp($rb->nominal) }}</td>
                                            <td>{{ $rb->keterangan }}</td>
                                            <td>{{ $rb->user->name }}</td>
                                            <td class="text-center">
                                                <a href="javascript:void(0);" class="btn btn-xs btn-primary print-btn"
                                                    data-id="{{ $rb->id }}" title="Cetak"><i
                                                        class="fal fa-print"></i></a>
                                                <a href="javascript:void(0);" class="btn btn-xs btn-danger delete-btn"
                                                    data-id="{{ $rb->id }}" title="Batal Retur"><i
                                                        class="fal fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
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
    <script src="/js/dependency/moment/moment.js"></script>
    <script src="/js/formplugins/bootstrap-daterangepicker/bootstrap-daterangepicker.js"></script>
    <script src="{{ asset('js/simrs/warehouse/penerimaan-barang/retur-barang.js') }}?v={{ time() }}"></script>
    <script>
        /* Fungsi untuk format child row */
        function format(data) {
            if (!data || data.length === 0) {
                return '<div class="p-3 text-center">Tidak ada item detail untuk retur ini.</div>';
            }
            let rows = '';
            data.forEach((d, index) => {
                rows += `<tr>
                    <td>${index + 1}</td>
                    <td>${d.kode_penerimaan}</td>
                    <td>${d.kode_po}</td>
                    <td>${d.kode_barang}</td>
                    <td>${d.nama_barang}</td>
                    <td>${d.satuan}</td>
                    <td>${d.tanggal_exp}</td>
                    <td>${d.batch_no}</td>
                    <td class="text-center">${d.qty}</td>
                    <td class="text-right">${new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(d.harga)}</td>
                    <td class="text-right">${new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(d.subtotal)}</td>
                </tr>`;
            });

            return `<div class="p-2 bg-white">
                <table class="child-table">
                    <thead class="bg-primary-100">
                        <tr>
                            <th>#</th>
                            <th>Kode Terima</th>
                            <th>Kode PO</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Satuan</th>
                            <th>Exp Date</th>
                            <th>No Batch</th>
                            <th>Qty</th>
                            <th>Harga</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>${rows}</tbody>
                </table>
            </div>`;
        }

        $(document).ready(function() {
            // Inisialisasi Select2
            $('.select2').select2({
                width: '100%'
            });

            // Inisialisasi Date Range Picker
            $('#tanggal_retur_filter').daterangepicker({
                opens: 'left',
                autoUpdateInput: false,
                locale: {
                    format: 'YYYY-MM-DD',
                    cancelLabel: 'Clear',
                    separator: ' to '
                }
            });
            $('#tanggal_retur_filter').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' to ' + picker.endDate.format(
                    'YYYY-MM-DD'));
            });
            $('#tanggal_retur_filter').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });

            // Inisialisasi DataTable
            var table = $('#retur-table').DataTable({
                responsive: true,
                columnDefs: [{
                    orderable: false,
                    className: 'details-control',
                    targets: 0
                }],
                order: [
                    [2, 'desc']
                ] // Default sort by Kode Retur
            });

            // Event listener untuk membuka dan menutup child row
            $('#retur-table tbody').on('click', 'td.details-control', function() {
                var tr = $(this).closest('tr');
                var row = table.row(tr);
                var returId = tr.data('id');

                if (row.child.isShown()) {
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    row.child(
                        '<tr><td colspan="9" class="text-center"><i class="fas fa-spinner fa-spin"></i> Memuat data...</td></tr>'
                    ).show();
                    tr.addClass('shown');

                    $.ajax({
                        url: `/warehouse/retur-barang/${returId}/details`,
                        method: 'GET',
                        success: function(response) {
                            if (response.data) {
                                row.child(format(response.data)).show();
                            } else {
                                row.child(
                                    '<tr><td colspan="9">Gagal memuat data detail.</td></tr>'
                                ).show();
                            }
                        },
                        error: function() {
                            row.child(
                                '<tr><td colspan="9">Terjadi kesalahan saat mengambil data.</td></tr>'
                            ).show();
                        }
                    });
                }
            });
        });
    </script>


    <script src="{{ asset('js/simrs/warehouse/penerimaan-barang/retur-barang.js') }}?v={{ time() }}"></script>

@endsection
