@extends('inc.layout')
@section('title', 'Purchase Request (Farmasi)')

@section('extended-css')
    <link rel="stylesheet" media="screen, print" href="/css/datagrid/datatables/datatables.bundle.css">
    <link rel="stylesheet" media="screen, print" href="/css/formplugins/select2/select2.bundle.css">
    <link rel="stylesheet" media="screen, print"
        href="/css/formplugins/bootstrap-daterangepicker/bootstrap-daterangepicker.css">

    {{-- ========================================================== --}}
    {{-- CSS KUSTOM UNTUK MENIRU GAYA JQGRID --}}
    {{-- ========================================================== --}}
    <style>
        /* Wrapper utama yang menyerupai gbox */
        .jqgrid-emulation-wrapper {
            border: 1px solid #ddd;
        }

        /* Title bar */
        .jqgrid-titlebar {
            background-color: #f6f6f6;
            padding: .5rem .75rem;
            border-bottom: 1px solid #ddd;
            font-size: 1rem;
            font-weight: 500;
            color: #333;
        }

        /* Menyamakan gaya header DataTables dengan jqGrid */
        #pr-table thead tr {
            background-color: #f9f9f9;
            border-bottom: 1px solid #ddd;
        }

        #pr-table thead th {
            color: #333;
            font-weight: 500;
            border-bottom-width: 1px !important;
            border-right: 1px solid #ddd;
            vertical-align: middle;
        }

        #pr-table thead th:last-child {
            border-right: none;
        }

        /* Menyamakan gaya baris data dengan jqGrid */
        #pr-table tbody tr:nth-of-type(odd) {
            background-color: #fff;
        }

        #pr-table tbody tr:nth-of-type(even) {
            background-color: #f9f9f9;
        }

        #pr-table tbody tr.selected {
            background-color: #cce5ff !important;
            /* Warna saat baris dipilih */
        }

        /* Child row styling */
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
            border: 1px solid #e1e1e1;
        }

        .child-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }

        /* Menghilangkan border default dari panel */
        #panel-2.panel {
            border: none;
            box-shadow: none;
        }

        #panel-2 .panel-hdr {
            display: none;
            /* Sembunyikan header panel default */
        }

        #panel-2 .panel-container {
            padding: 0;
        }


        /* Hapus background image default */
        td.details-control {
            background-image: none !important;
            cursor: pointer;
            width: 30px;
            /* Beri sedikit ruang agar tidak terlalu sempit */
            text-align: center;
            vertical-align: middle;
            font-family: "Font Awesome 5 Free";
            /* Pastikan font awesome dimuat */
            font-weight: 900;
            font-size: 1rem;
        }

        /* Atur ikon default (plus) */
        td.details-control::before {
            content: "\f067";
            /* Kode unicode untuk ikon plus di FontAwesome */
            color: #28a745;
            /* Warna hijau untuk 'tambah' */
            display: inline-block;
        }

        /* Atur ikon saat baris terbuka (minus) */
        tr.shown td.details-control::before {
            content: "\f068";
            /* Kode unicode untuk ikon minus di FontAwesome */
            color: #dc3545;
            /* Warna merah untuk 'tutup' */
        }

        /* Child row styling (tetap sama) */
        .child-table {
            width: 100%;
            border-spacing: 0;
            border-collapse: collapse;
        }

        .child-table th,
        .child-table td {
            padding: 8px;
            border: 1px solid #e1e1e1;
        }

        .child-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
    </style>
@endsection

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="subheader">
            <h1 class="subheader-title">
                <i class='subheader-icon fal fa-shopping-cart'></i> Purchase Request (Farmasi)
                <small>Daftar permintaan pembelian barang farmasi.</small>
            </h1>
        </div>
        <div class="row">
            <div class="col-xl-12">
                {{-- Panel Filter tetap sama --}}
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Filter Pencarian</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form action="{{ route('warehouse.purchase-request.pharmacy') }}" method="get">
                                {{-- ... isi form filter ... --}}
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label" for="tanggal_pr_filter">Tanggal PR</label>
                                        <input type="text" class="form-control" id="tanggal_pr_filter" name="tanggal_pr"
                                            value="{{ request('tanggal_pr') }}">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label" for="kode_pr">Kode PR</label>
                                        <input type="text" class="form-control" id="kode_pr" name="kode_pr"
                                            value="{{ request('kode_pr') }}">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label" for="nama_barang">Nama Barang</label>
                                        <input type="text" class="form-control" id="nama_barang" name="nama_barang"
                                            value="{{ request('nama_barang') }}">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label" for="status_filter">Status</label>
                                        <select name="status" id="status_filter" class="form-control select2">
                                            <option value="">Semua Status</option>
                                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>
                                                Draft</option>
                                            <option value="final" {{ request('status') == 'final' ? 'selected' : '' }}>
                                                Final</option>
                                            <option value="reviewed"
                                                {{ request('status') == 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary"><i class="fal fa-search"></i>
                                            Cari</button>
                                        <a href="{{ route('warehouse.purchase-request.pharmacy') }}"
                                            class="btn btn-secondary">Reset</a>
                                        <button type="button" class="btn btn-success float-right" id="add-pr-btn"
                                            data-url="{{ route('warehouse.purchase-request.pharmacy.create') }}"><i
                                                class="fal fa-plus"></i> Tambah PR</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- UBAH STRUKTUR PANEL DATATABLES --}}
                <div id="panel-2" class="panel">
                    <div class="panel-container show">
                        {{-- Wrapper jqGrid --}}
                        <div class="jqgrid-emulation-wrapper">
                            {{-- Title Bar --}}
                            <div class="jqgrid-titlebar">
                                List Of Purchase Requests (Pharmacy)
                            </div>
                            {{-- Konten Tabel --}}
                            <div class="panel-content">
                                <table id="pr-table" class="table table-bordered table-hover table-striped w-100">
                                    <thead>
                                        <tr>
                                            <th></th> {{-- child row --}}
                                            <th>Kode PR</th>
                                            <th>Tanggal PR</th>
                                            <th>Gudang</th>
                                            <th>User</th>
                                            <th>Tipe</th>
                                            <th class="text-right">Nominal</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($prs as $pr)
                                            <tr data-id="{{ $pr->id }}">
                                                <td class="details-control"></td>
                                                <td>{{ $pr->kode_pr }}</td>
                                                <td>{{ \Carbon\Carbon::parse($pr->tanggal_pr)->format('d-m-Y') }}</td>
                                                <td>{{ $pr->gudang->nama }}</td>
                                                <td>{{ $pr->user->employee->fullname ?? $pr->user->name }}</td>
                                                <td class="text-center"><span
                                                        class="badge badge-{{ $pr->tipe == 'urgent' ? 'danger' : 'info' }}">{{ ucfirst($pr->tipe) }}</span>
                                                </td>
                                                <td class="text-right">{{ rp($pr->nominal) }}</td>
                                                <td class="text-center">
                                                    @php
                                                        $statusClass = 'secondary';
                                                        $gearColor = null;
                                                        if ($pr->status == 'final') {
                                                            $statusClass = 'primary';
                                                            $gearColor = 'text-danger';
                                                        }
                                                        if ($pr->status == 'reviewed') {
                                                            $statusClass = 'success';
                                                            $gearColor = 'text-success';
                                                        }
                                                    @endphp
                                                    {{-- <span class="badge badge-{{ $statusClass }}"> --}}
                                                    {{-- {{ ucfirst($pr->status) }} --}}
                                                    @if ($pr->status == 'final' || $pr->status == 'reviewed')
                                                        <i class="fal fa-cog {{ $gearColor }} ml-1"></i>
                                                    @endif
                                                    {{-- </span> --}}
                                                </td>
                                                <td class="text-center">
                                                    <a href="javascript:void(0);" class="btn btn-xs btn-primary print-btn"
                                                        title="Cetak"
                                                        data-url="{{ route('warehouse.purchase-request.pharmacy.print', $pr->id) }}"><i
                                                            class="fal fa-print"></i></a>
                                                    @if ($pr->status == 'draft')
                                                        <a href="javascript:void(0);"
                                                            class="btn btn-xs btn-warning edit-btn" title="Edit"
                                                            data-url="{{ route('warehouse.purchase-request.pharmacy.edit', $pr->id) }}"><i
                                                                class="fal fa-pencil"></i></a>
                                                        <a href="javascript:void(0);"
                                                            class="btn btn-xs btn-danger delete-btn" title="Hapus"
                                                            data-url="{{ route('warehouse.purchase-request.pharmacy.destroy', $pr->id) }}"><i
                                                                class="fal fa-trash"></i></a>
                                                    @endif
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
        </div>
    </main>
@endsection

@section('plugin')
    {{-- ... Script plugin Anda tetap sama ... --}}
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/dependency/moment/moment.js"></script>
    <script src="/js/formplugins/bootstrap-daterangepicker/bootstrap-daterangepicker.js"></script>
    <script src="{{ asset('js/simrs/warehouse/purchase-request/pharmacy.js') }}?v={{ time() }}"></script>
@endsection
