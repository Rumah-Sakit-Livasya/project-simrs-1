@extends('inc.layout')
@section('title', 'Penerimaan Barang Farmasi')

@section('extended-css')
    {{-- CSS untuk child row DataTables --}}
    <style>
        td.details-control {
            cursor: pointer;
            position: relative;
        }

        td.details-control::before {
            font-family: "Font Awesome 5 Free";
            content: "\f067";
            /* fa-plus */
            font-weight: 900;
            display: inline-block;
            color: #007bff;
            font-size: 1rem;
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
        }

        tr.shown td.details-control::before {
            content: "\f068";
            /* fa-minus */
            color: #dc3545;
        }

        .child-row-table {
            width: 100%;
            border-spacing: 0;
            border-collapse: collapse;
        }

        .child-row-table th,
        .child-row-table td {
            padding: 8px 12px;
            border-bottom: 1px solid #e0e0e0;
        }

        .child-row-table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }
    </style>
    {{-- daterangepicker --}}
    <link rel="stylesheet" href="/css/formplugins/bootstrap-daterangepicker/bootstrap-daterangepicker.css">
@endsection

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="subheader">
            <h1 class="subheader-title">
                <i class='subheader-icon fal fa-truck-loading'></i> Penerimaan Barang <span class='fw-300'>Farmasi</span>
                <small>
                    Manajemen data penerimaan barang dari supplier farmasi.
                </small>
            </h1>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Filter & Daftar Penerimaan
                        </h2>
                        <div class="panel-toolbar">
                            <a href="{{ route('warehouse.procurement.penerimaan-barang.pharmacy.create') }}"
                                class="btn btn-primary btn-sm"
                                onclick="window.open(this.href, 'TambahPenerimaanBarangFarmasi', 'fullscreen=yes,scrollbars=yes,resizable=yes'); return false;">
                                <i class="fal fa-plus"></i> Tambah Penerimaan
                            </a>
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">

                            {{-- FORM PENCARIAN --}}
                            <form class="mb-4" action="{{ route('warehouse.penerimaan-barang.pharmacy.index') }}"
                                method="get">
                                @csrf
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="tanggal_terima">Rentang Tanggal Terima</label>
                                            <input type="text" class="form-control" id="tanggal_terima"
                                                name="tanggal_terima" value="{{ request('tanggal_terima') }}"
                                                autocomplete="off" placeholder="Pilih rentang tanggal">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="nama_barang">Nama Barang</label>
                                            <input type="text" class="form-control" id="nama_barang" name="nama_barang"
                                                value="{{ request('nama_barang') }}" placeholder="Cari nama barang...">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="batch_no">No. Batch</label>
                                            <input type="text" class="form-control" id="batch_no" name="batch_no"
                                                value="{{ request('batch_no') }}" placeholder="Cari no. batch...">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="kode_penerimaan">Kode Penerimaan</label>
                                            <input type="text" class="form-control" id="kode_penerimaan"
                                                name="kode_penerimaan" value="{{ request('kode_penerimaan') }}"
                                                placeholder="Contoh: 000001/FNGR/2301">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="kode_po">Kode PO</label>
                                            <input type="text" class="form-control" id="kode_po" name="kode_po"
                                                value="{{ request('kode_po') }}" placeholder="Cari kode PO...">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="no_faktur">No. Faktur / Surat Jalan</label>
                                            <input type="text" class="form-control" id="no_faktur" name="no_faktur"
                                                value="{{ request('no_faktur') }}" placeholder="Cari no. faktur...">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary"><i class="fal fa-search"></i>
                                            Cari</button>
                                        <a href="{{ route('warehouse.penerimaan-barang.pharmacy.index') }}"
                                            class="btn btn-secondary">Reset Filter</a>
                                    </div>
                                </div>
                            </form>
                            <hr>

                            {{-- TABEL DATA --}}
                            <table id="dt-penerimaan-farmasi" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th style="width: 15px;"></th> {{-- Kolom untuk tombol child row --}}
                                        <th style="width: 15px;">#</th>
                                        <th>Kode Penerimaan</th>
                                        <th>Tgl Terima</th>
                                        <th>Supplier</th>
                                        <th>Kode PO</th>
                                        <th>No Faktur</th>
                                        <th>Nominal</th>
                                        <th>Status</th>
                                        <th style="width: 100px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pbs as $pb)
                                        <tr data-pb-id="{{ $pb->id }}">
                                            <td class="details-control"></td>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <span class="font-weight-bold">{{ $pb->kode_penerimaan }}</span>
                                            </td>
                                            <td>{{ tgl($pb->tanggal_terima) }}</td>
                                            <td>{{ $pb->supplier->nama }}</td>
                                            <td>{{ $pb->po?->kode_po ?? '-' }}</td>
                                            <td>{{ $pb->no_faktur }}</td>
                                            <td class="text-right">{{ rp($pb->total_final) }}</td>
                                            <td>
                                                @if ($pb->status == 'final')
                                                    <span class="badge badge-success">Final</span>
                                                @else
                                                    <span class="badge badge-warning">Draft</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a href="javascript:void(0);"
                                                    class="btn btn-xs btn-primary btn-icon print-btn" title="Cetak"
                                                    data-id="{{ $pb->id }}">
                                                    <i class="fal fa-print"></i>
                                                </a>
                                                @php
                                                    $canEdit =
                                                        \Carbon\Carbon::parse($pb->created_at)->diffInDays(now()) < 7;
                                                @endphp
                                                @if (($pb->status == 'draft' || $pb->status == 'final') && $canEdit)
                                                    <a href="{{ route('warehouse.penerimaan-barang.pharmacy.edit', $pb->id) }}"
                                                        class="btn btn-xs btn-warning btn-icon" title="Edit"
                                                        onclick="window.open(this.href, 'EditPenerimaanBarangFarmasi', 'fullscreen=yes,scrollbars=yes,resizable=yes'); return false;">
                                                        <i class="fal fa-pencil"></i>
                                                    </a>
                                                @endif
                                                @if ($pb->status == 'draft')
                                                    <a href="javascript:void(0);"
                                                        class="btn btn-xs btn-danger btn-icon delete-btn" title="Hapus"
                                                        data-id="{{ $pb->id }}">
                                                        <i class="fal fa-trash"></i>
                                                    </a>
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
    </main>
@endsection

@section('plugin')
    {{-- Plugin DataTables --}}
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>

    {{-- Plugin DateRangePicker --}}
    <script src="/js/dependency/moment/moment.js"></script>
    <script src="/js/formplugins/bootstrap-daterangepicker/bootstrap-daterangepicker.js"></script>
    {{-- Add moment.js if not already included --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>

    <script>
        // Fungsi untuk format Child Row
        function formatChildRow(items) {
            let itemsHtml = '';
            if (Array.isArray(items) && items.length > 0) {
                items.forEach(item => {
                    // Gunakan relasi jika ada, fallback ke field utama jika tidak
                    const namaBarang = item.item && item.item.nama_barang ? item.item.nama_barang : (item
                        .nama_barang || '-');
                    const kodeBarang = item.item && item.item.kode_barang ? item.item.kode_barang : (item
                        .kode_barang || '-');
                    const satuan = item.satuan && item.satuan.unit_barang ? item.satuan.unit_barang : (item
                        .unit_barang || '-');
                    itemsHtml += `
                        <tr>
                            <td>
                                ${namaBarang}
                                <br>
                                <small class="text-muted">${kodeBarang}</small>
                            </td>
                            <td>${item.batch_no ?? '-'}</td>
                            <td>${item.tanggal_exp ?? '-'}</td>
                            <td class="text-center">${item.qty ?? 0} <span class="text-muted">${satuan}</span></td>
                            <td class="text-right">${new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(item.harga ?? 0)}</td>
                            <td class="text-right">${new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(item.subtotal ?? 0)}</td>
                        </tr>
                    `;
                });
            } else {
                return '<div class="p-3 text-center text-muted">Tidak ada item detail untuk penerimaan ini.</div>';
            }

            return `
                <div class="p-3">
                    <table class="child-row-table table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>Nama Barang</th>
                                <th>No. Batch</th>
                                <th>Exp. Date</th>
                                <th class="text-center">Qty</th>
                                <th class="text-right">Harga</th>
                                <th class="text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${itemsHtml}
                        </tbody>
                    </table>
                </div>
            `;
        }

        $(document).ready(function() {
            // Pastikan moment tersedia
            if (typeof moment === 'undefined') {
                console.error('moment.js is not loaded!');
            }

            // Inisialisasi daterangepicker pada input tanggal_terima
            var today = new Date();

            $('#tanggal_terima').daterangepicker({
                opens: 'left',
                startDate: moment(today).format('YYYY-MM-DD'),
                endDate: moment(today).format('YYYY-MM-DD'),
                autoUpdateInput: false,
                locale: {
                    format: 'YYYY-MM-DD',
                    cancelLabel: 'Batal',
                    applyLabel: 'Terapkan',
                    fromLabel: 'Dari',
                    toLabel: 'Sampai',
                    customRangeLabel: "Custom",
                    daysOfWeek: [
                        "Min",
                        "Sen",
                        "Sel",
                        "Rab",
                        "Kam",
                        "Jum",
                        "Sab"
                    ],
                    monthNames: [
                        "Januari",
                        "Februari",
                        "Maret",
                        "April",
                        "Mei",
                        "Juni",
                        "Juli",
                        "Agustus",
                        "September",
                        "Oktober",
                        "November",
                        "Desember"
                    ],
                    firstDay: 1
                }
            });

            $('#tanggal_terima').on('apply.daterangepicker', function(ev, picker) {
                var start = picker.startDate.format('YYYY-MM-DD');
                var end = picker.endDate.format('YYYY-MM-DD');
                $(this).val(start + ' s/d ' + end);
            });

            $('#tanggal_terima').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });

            // Inisialisasi DataTables
            var table = $('#dt-penerimaan-farmasi').DataTable({
                responsive: true,
                order: [
                    [2, 'desc']
                ],
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                        extend: 'pdfHtml5',
                        text: 'PDF',
                        titleAttr: 'Generate PDF',
                        className: 'btn-outline-danger btn-sm mr-1'
                    },
                    {
                        extend: 'excelHtml5',
                        text: 'Excel',
                        titleAttr: 'Generate Excel',
                        className: 'btn-outline-success btn-sm mr-1'
                    },
                    {
                        extend: 'csvHtml5',
                        text: 'CSV',
                        titleAttr: 'Generate CSV',
                        className: 'btn-outline-primary btn-sm mr-1'
                    },
                    {
                        extend: 'copyHtml5',
                        text: 'Copy',
                        titleAttr: 'Copy to clipboard',
                        className: 'btn-outline-primary btn-sm mr-1'
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        titleAttr: 'Print Table',
                        className: 'btn-outline-primary btn-sm'
                    }
                ],
                columnDefs: [{
                        orderable: false,
                        targets: [0, 9]
                    },
                    {
                        searchable: false,
                        targets: [0, 1, 9]
                    }
                ]
            });

            // Event listener untuk membuka dan menutup child row
            $('#dt-penerimaan-farmasi tbody').on('click', 'td.details-control', function() {
                var tr = $(this).closest('tr');
                var row = table.row(tr);
                var pbId = tr.data('pb-id');

                if (row.child.isShown()) {
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    row.child('<div><i class="fas fa-spinner fa-spin"></i> Memuat data...</div>').show();
                    tr.addClass('shown');

                    $.ajax({
                        url: `/api/simrs/warehouse/penerimaan-barang/pharmacy/${pbId}/details`,
                        method: 'GET',
                        success: function(response) {
                            // Perbaikan: response.data adalah array of items
                            if (response.success && Array.isArray(response.data)) {
                                row.child(formatChildRow(response.data)).show();
                            } else {
                                row.child(
                                    '<div class="p-3 text-center text-danger">Gagal memuat data detail.</div>'
                                ).show();
                            }
                        },
                        error: function() {
                            row.child(
                                '<div class="p-3 text-center text-danger">Terjadi kesalahan saat mengambil data.</div>'
                            ).show();
                        }
                    });
                }
            });

            // Event listener untuk tombol hapus
            $('#dt-penerimaan-farmasi').on('click', '.delete-btn', function() {
                var pbId = $(this).data('id');
                var deleteUrl =
                    "{{ route('warehouse.penerimaan-barang.pharmacy.delete', ['id' => ':id']) }}".replace(
                        ':id', pbId);

                showDeleteConfirmation(function() {
                    $.ajax({
                        url: deleteUrl,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                showSuccessAlert(response.message);
                                location.reload();
                            } else {
                                showErrorAlertNoRefresh(response.message);
                            }
                        },
                        error: function(xhr) {
                            showErrorAlert('Gagal menghapus data.');
                        }
                    });
                });
            });

            // Event listener untuk tombol print
            $('#dt-penerimaan-farmasi').on('click', '.print-btn', function() {
                var pbId = $(this).data('id');
                if (!pbId) return;
                var url = '/simrs/warehouse/penerimaan-barang/pharmacy/print/' + pbId;
                var width = screen.width;
                var height = screen.height;
                var left = width - width / 2;
                var top = height - height / 2;
                window.open(
                    url,
                    'popupWindow_printPBFarmasi' + pbId,
                    'width=' +
                    width +
                    ',height=' +
                    height +
                    ',scrollbars=yes,resizable=yes,left=' +
                    left +
                    ',top=' +
                    top
                );
            });
        });
    </script>

    <script src="{{ asset('js/simrs/warehouse/penerimaan-barang/pharmacy.js') }}?v={{ time() }}"></script>
@endsection
