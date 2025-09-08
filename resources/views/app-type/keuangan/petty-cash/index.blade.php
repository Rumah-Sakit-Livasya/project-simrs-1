@extends('inc.layout')
@section('title', 'Daftar Transaksi Petty Cash')
@section('content')
    <style>
        /* CSS BARU UNTUK DETAILS CONTROL */
        table {
            font-size: 8pt !important;
        }

        .modal-lg {
            max-width: 800px;
        }

        .details-control {
            cursor: pointer;
            text-align: center;
            width: 30px;
            padding: 8px !important;
        }

        .details-control i {
            transition: transform 0.3s ease, color 0.3s ease;
            color: #3498db;
            font-size: 16px;
            transform: rotate(0deg);
        }

        .details-control:hover i {
            color: #2980b9;
        }

        tr.dt-hasChild td.details-control i {
            transform: rotate(180deg);
            color: #e74c3c;
        }

        td.details-control::before {
            display: none !important;
        }

        .child-row-content {
            padding: 15px;
            background-color: #f9f9f9;
        }

        table.dataTable thead .sorting:after,
        table.dataTable thead .sorting_asc:after,
        table.dataTable thead .sorting_desc:after,
        table.dataTable thead .sorting_asc_disabled:after,
        table.dataTable thead .sorting_desc_disabled:after {
            display: none !important;
        }

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

        #dt-basic-example tbody tr:hover {
            background-color: #f8f9fa;
        }
    </style>
    <main id="js-page-content" role="main" class="page-content">
        <!-- Search Panel -->
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div class="panel">
                    <div class="panel-hdr">
                        <h2>Filter Data</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form action="{{ route('keuangan.petty-cash.index') }}" method="get">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label>Periode Awal</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" name="tanggal_awal"
                                                value="{{ request('tanggal_awal') }}">
                                            <div class="input-group-append"><span class="input-group-text fs-sm"><i
                                                        class="fal fa-calendar"></i></span></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Periode Akhir</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" name="tanggal_akhir"
                                                value="{{ request('tanggal_akhir') }}">
                                            <div class="input-group-append"><span class="input-group-text fs-sm"><i
                                                        class="fal fa-calendar"></i></span></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Keterangan</label>
                                        <input type="text" class="form-control" name="keterangan"
                                            placeholder="Cari keterangan..." value="{{ request('keterangan') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label class="form-label">Biaya</label>
                                            <select class="form-control select2" id="kas_id" name="kas_id">
                                                <option value="">Biaya</option>
                                                @foreach ($kass as $kas)
                                                    <option value="{{ $kas->id }}"
                                                        {{ request('kas_id') == $kas->id ? 'selected' : '' }}>
                                                        {{ $kas->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary"><span
                                                class="fal fa-search mr-1"></span>
                                            Cari</button>
                                        <a href="{{ route('keuangan.petty-cash.index') }}"
                                            class="btn btn-secondary ml-2">Reset
                                            Filter</a>
                                        <a href="{{ route('keuangan.petty-cash.create') }}" class="btn btn-primary ml-2"><i
                                                class="fal fa-plus"></i>
                                            Tambah Transaksi </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Data Table Panel -->
        <div class="panel mt-4">
            <div class="panel-hdr">
                <h2>Daftar Transaksi Petty Cash</h2>
                <div class="panel-toolbar">
                    @can('create petty-cash')
                    @endcan
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                        <thead class="bg-primary-600">
                            <tr>
                                <th style="width: 10px;"></th> {{-- Kolom ikon expand/collapse --}}
                                <th>Tanggal</th>
                                <th>Kode Transaksi</th>
                                <th>Kas/Bank</th>
                                <th>Keterangan</th>
                                <th class="text-right">Nominal</th>
                                <th>User Input</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pettycash as $pc)
                                <tr data-id="{{ $pc->id }}">
                                    <td class="details-control"><i class="fal fa-chevron-up"></i></td>
                                    <td>{{ \Carbon\Carbon::parse($pc->tanggal)->format('d-m-Y') }}</td>
                                    <td>{{ $pc->kode_transaksi }}</td>
                                    <td>{{ $pc->kas_nama ?? 'N/A' }}</td>
                                    <td>{{ $pc->keterangan }}</td>
                                    <td class="text-right">{{ number_format($pc->total_nominal, 0, ',', '.') }}</td>
                                    <td>{{ $pc->user_name ?? 'N/A' }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('keuangan.petty-cash.print-jurnal', ['id' => $pc->id]) }}"
                                            onclick="openPrintPopup(this.href); return false;"
                                            class="btn btn-xs btn-primary" title="Cetak Jurnal">
                                            <i class="fal fa-print"></i>
                                        </a>
                                        <a href="{{ route('keuangan.petty-cash.print-voucher', ['id' => $pc->id]) }}"
                                            onclick="openPrintPopup(this.href); return false;" class="btn btn-xs btn-info"
                                            title="Cetak Voucher Pengeluaran">
                                            <i class="fal fa-file-alt"></i>
                                        </a>
                                        <a href="{{ route('keuangan.petty-cash.edit', ['id' => $pc->id]) }}"
                                            class="btn btn-xs btn-warning" title="Edit">
                                            <i class="fal fa-pencil"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- Template untuk Child Row --}}
                    <div id="child-row-template" style="display: none;">
                        <div class="child-row-content">
                            <h6 class="mb-3"><strong>Rincian untuk Transaksi <span
                                        class="invoice-placeholder"></span>:</strong></h6>
                            <table class="child-table table table-sm table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Nama Akun Biaya</th>
                                        <th>Keterangan</th>
                                        <th class="text-right">Nominal</th>
                                    </tr>
                                </thead>
                                <tbody class="detail-tbody"></tbody>
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
        // ==========================================================
        // TAMBAHAN: Fungsi untuk membuka link di Pop-up Window
        // ==========================================================
        function openPrintPopup(url) {
            const width = 1200;
            const height = 800;
            const left = (screen.width / 2) - (width / 2);
            const top = (screen.height / 2) - (height / 2);
            window.open(url, 'printWindow',
                `width=${width},height=${height},top=${top},left=${left},resizable=yes,scrollbars=yes`);
        }

        $(document).ready(function() {
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true
            });
            $('.select2').select2();

            const allDetails = {!! json_encode($detailsForJs ?? []) !!};

            var table = $('#dt-basic-example').DataTable({
                responsive: true,
                lengthChange: false,
                pageLength: 20,
                order: [
                    [1, 'desc']
                ],
                columnDefs: [{
                    orderable: false,
                    targets: [0, 7] // Updated to reflect new column indices after removing Status
                }]
            });

            function formatChildRow(invoice, details) {
                var template = $('#child-row-template').clone();
                template.find('.invoice-placeholder').text(invoice);
                var tbody = template.find('.detail-tbody');
                tbody.empty();

                if (details && details.length > 0) {
                    details.forEach(function(detail) {
                        var rowHtml = `
                        <tr>
                            <td>${detail.tipe_transaksi || '-'}</td>
                            <td>${detail.keterangan || '-'}</td>
                            <td class="text-right">${detail.nominal_formatted || 'Rp 0'}</td>
                        </tr>`;
                        tbody.append(rowHtml);
                    });
                } else {
                    tbody.append(
                        '<tr><td colspan="3" class="text-center text-muted">Tidak ada rincian data.</td></tr>');
                }
                return template.html();
            }

            $('#dt-basic-example tbody').on('click', 'td.details-control', function() {
                var tr = $(this).closest('tr');
                var row = table.row(tr);
                if (row.child.isShown()) {
                    row.child.hide();
                    tr.removeClass('dt-hasChild');
                } else {
                    var pettyCashId = tr.data('id');
                    var invoice = tr.find('td:eq(2)').text().trim();
                    var details = allDetails[pettyCashId] || [];
                    row.child(formatChildRow(invoice, details)).show();
                    tr.addClass('dt-hasChild');
                }
            });
        });
    </script>
@endsection
