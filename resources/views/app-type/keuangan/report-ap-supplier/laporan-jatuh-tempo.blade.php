@extends('inc.layout')
@section('title', 'Laporan Jatuh Tempo')

@section('content')
    <style>
        table {
            font-size: 8pt !important;
        }

        .badge-waiting {
            background-color: #f39c12;
            color: white;
        }

        .badge-approved {
            background-color: #00a65a;
            color: white;
        }

        .badge-rejected {
            background-color: #dd4b39;
            color: white;
        }

        .modal-lg {
            max-width: 800px;
        }

        .panel-loading {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 999;
        }

        /* PENTING: Tambahkan CSS ini jika belum ada untuk memastikan toggle berfungsi */
        .child-row {
            display: none;
            /* Sembunyikan secara default */
        }

        .dropdown-icon {
            font-size: 14px;
            transition: transform 0.3s ease;
            display: inline-block;
        }

        .dropdown-icon.bxs-down-arrow {
            transform: rotate(180deg);
        }

        /* Styling tambahan untuk memperjelas batas row */
        .child-row td {
            background-color: #f9f9f9;
            border-bottom: 2px solid #ddd;
        }

        /* Pastikan table di dalam child row memiliki margin dan padding yang tepat */
        .child-row td>div {
            padding: 15px;
            margin: 0;
        }

        /* Pastikan parent dan child row terhubung secara visual */
        tr.parent-row.active {
            border-bottom: none !important;
        }

        /* Tambahkan di bagian style */
        .control-details {
            cursor: pointer;
            text-align: center;
            width: 30px;
        }

        .control-details .dropdown-icon {
            font-size: 18px;
            transition: transform 0.3s ease, color 0.3s ease;
            display: inline-block;
            color: #3498db;
            /* Warna biru */
        }

        .control-details .dropdown-icon.bxs-up-arrow {
            transform: rotate(180deg);
            color: #e74c3c;
            /* Warna merah saat terbuka */
        }

        .control-details:hover .dropdown-icon {
            color: #2980b9;
            /* Warna biru lebih gelap saat hover */
        }

        /* Sembunyikan ikon sort bawaan DataTables */
        table.dataTable thead .sorting:after,
        table.dataTable thead .sorting_asc:after,
        table.dataTable thead .sorting_desc:after,
        table.dataTable thead .sorting_asc_disabled:after,
        table.dataTable thead .sorting_desc_disabled:after {
            display: none !important;
        }

        /* Styling untuk child row */
        /* Pastikan content di child row tidak overflow */
        .child-row td>div {
            padding: 15px;
            width: 100%;
        }

        /* Styling untuk tabel di dalam child row */
        .child-table {
            width: 98% !important;
            margin: 10px auto !important;
            border-radius: 4px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .child-table thead th {
            background-color: #021d39;
            color: white;
            font-size: 12px;
            padding: 8px !important;
        }

        .child-table tbody td {
            padding: 8px !important;
            font-size: 12px;
            background-color: white;
        }

        /* Animasi untuk transisi smooth */
        .child-row {
            transition: all 0.3s ease;
        }

        .child-row.show {
            opacity: 1;
        }

        td.control-details::before {
            display: none !important;
        }

        /* Efek hover untuk row */
        #dt-basic-example tbody tr.parent-row:hover {
            background-color: #f8f9fa;
            cursor: pointer;
        }

        /* Warna berbeda untuk child row */
        #dt-basic-example tbody tr.child-row:hover {
            background-color: #f1f1f1;
        }

        /* NEW CSS: Styling for the filler rows to make them clean */
    </style>

    <main id="js-page-content" role="main" class="page-content">
        {{-- PANEL FILTER (No change here) --}}
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div class="panel">
                    <div class="panel-hdr">
                        <h2>Filter <span class="fw-300"><i>Laporan Jatuh Tempo</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form action="{{ route('keuangan.report-ap-supplier.laporan-jatuh-tempo') }}" method="get">
                                <div class="form-row">
                                    <div class="col-md-6 mb-3">
                                        <label>Awal Tanggal Jatuh Tempo</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" name="awal_due"
                                                value="{{ $awal_due }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text fs-xl"><i class="fal fa-calendar"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Akhir Tanggal Jatuh Tempo</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" name="akhir_due"
                                                value="{{ $akhir_due }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text fs-xl"><i class="fal fa-calendar"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Supplier</label>
                                        <select class="form-control select2" name="supplier_id">
                                            <option value="">Semua Supplier</option>
                                            @foreach ($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}"
                                                    {{ $selected_supplier_id == $supplier->id ? 'selected' : '' }}>
                                                    {{ $supplier->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Invoice</label>
                                        <input type="text" class="form-control" name="invoice"
                                            value="{{ request('invoice') ?? '' }}"
                                            placeholder="Filter berdasarkan No. Invoice">
                                    </div>
                                </div>
                                <div class="form-row justify-content-end">
                                    <button type="submit" class="btn btn-sm btn-primary mr-2">
                                        <i class="fal fa-search mr-1"></i> Cari
                                    </button>
                                    <button type="button" class="btn btn-sm btn-success" id="export-excel">
                                        <i class="fal fa-file-excel mr-1"></i> Export
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- PANEL DATA --}}
        <div class="row">
            <div class="col-xl-12">
                <div class="panel">
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600 text-white">
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th>Supplier</th>
                                        <th>Inv Number</th>
                                        <th>Kode AP</th>
                                        <th>Tgl AP</th>
                                        <th>Duedate</th>
                                        <th class="text-center">DPP</th>
                                        <th class="text-center">PPN</th>
                                        <th class="text-center">Total Hutang</th>
                                        <th class="text-center">Sisa Hutang</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                        $rowCount = 0;
                                        // Count the rows that will be rendered
                                        foreach ($groupedAps as $aps) {
                                            $rowCount += $aps->count(); // Add number of data rows
                                            $rowCount++; // Add 1 for the subtotal row
                                        }
                                        $minRows = 10; // Set a minimum number of rows for a full-page look
                                        $fillerRowsNeeded = max(0, $minRows - $rowCount);
                                    @endphp

                                    @forelse ($groupedAps as $supplierName => $aps)
                                        @foreach ($aps as $ap)
                                            <tr>
                                                <td class="text-center">{{ $no++ }}</td>
                                                <td>{{ $ap->supplier->nama }}</td>
                                                <td>{{ $ap->no_invoice_supplier }}</td>
                                                <td>{{ $ap->kode_ap }}</td>
                                                <td>{{ \Carbon\Carbon::parse($ap->tanggal_ap)->format('d-m-Y') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($ap->due_date)->format('d-m-Y') }}</td>
                                                <td class="text-right">{{ number_format($ap->subtotal, 2, ',', '.') }}</td>
                                                <td class="text-right">{{ number_format($ap->ppn_nominal, 2, ',', '.') }}
                                                </td>
                                                <td class="text-right">{{ number_format($ap->grand_total, 2, ',', '.') }}
                                                </td>
                                                <td class="text-right">{{ number_format($ap->sisa_hutang, 2, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach

                                        <tr class="subtotal-row">
                                            <td></td>
                                            <td class="font-weight-bold" colspan="5">Total {{ $supplierName }}</td>
                                            <td class="text-right font-weight-bold">
                                                {{ number_format($aps->sum('subtotal'), 2, ',', '.') }}</td>
                                            <td class="text-right font-weight-bold">
                                                {{ number_format($aps->sum('ppn_nominal'), 2, ',', '.') }}</td>
                                            <td class="text-right font-weight-bold">
                                                {{ number_format($aps->sum('grand_total'), 2, ',', '.') }}</td>
                                            <td class="text-right font-weight-bold">
                                                {{ number_format($aps->sum('sisa_hutang'), 2, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center">Tidak ada data hutang yang jatuh tempo
                                                pada periode ini.</td>
                                        </tr>
                                    @endforelse

                                    {{-- ======================================================= --}}
                                    {{-- NEW: LOGIC TO ADD EMPTY FILLER ROWS TO PUSH FOOTER DOWN --}}
                                    {{-- ======================================================= --}}
                                    @for ($i = 0; $i < $fillerRowsNeeded; $i++)
                                        <tr class="filler-row">
                                            <td> </td>
                                            <td> </td>
                                            <td> </td>
                                            <td> </td>
                                            <td> </td>
                                            <td> </td>
                                            <td> </td>
                                            <td> </td>
                                            <td> </td>
                                            <td> </td>
                                        </tr>
                                    @endfor

                                </tbody>
                                <tfoot>
                                    @if ($groupedAps->isNotEmpty())
                                        <tr class="grand-total-row">
                                            <th colspan="6" class="text-right">GRAND TOTAL</th>
                                            <th class="text-right">
                                                {{ number_format($groupedAps->flatten()->sum('subtotal'), 2, ',', '.') }}
                                            </th>
                                            <th class="text-right">
                                                {{ number_format($groupedAps->flatten()->sum('ppn_nominal'), 2, ',', '.') }}
                                            </th>
                                            <th class="text-right">
                                                {{ number_format($groupedAps->flatten()->sum('grand_total'), 2, ',', '.') }}
                                            </th>
                                            <th class="text-right">
                                                {{ number_format($groupedAps->flatten()->sum('sisa_hutang'), 2, ',', '.') }}
                                            </th>
                                        </tr>
                                    @endif
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('plugin')
    {{-- (No change here) --}}
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Pilih Supplier",
                allowClear: true,
                width: '100%'
            });

            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                todayHighlight: true,
                autoclose: true,
                orientation: "bottom auto"
            });

            $('#export-excel').click(function() {
                window.location.href =
                    "{{ route('keuangan.report-ap-supplier.laporan-jatuh-tempo') }}?export=excel&" + $(
                        'form').serialize();
            });

        });
    </script>
@endsection
