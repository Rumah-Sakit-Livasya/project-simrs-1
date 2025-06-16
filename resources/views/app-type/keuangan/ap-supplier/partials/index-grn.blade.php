@extends('inc.layout-no-side')
@section('title', 'Pilih GRN')
@push('styles')
    <style>
         table {
            font-size: 7px !important; /* Reduced from 8pt */
        }

        .badge-waiting {
            background-color: #f39c12;
            color: white;
            font-size: 7pt; /* Added */
        }

        .badge-approved {
            background-color: #00a65a;
            color: white;
            font-size: 7pt; /* Added */
        }

        .badge-rejected {
            background-color: #dd4b39;
            color: white;
            font-size: 7pt; /* Added */
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
            font-size: 12px; /* Reduced from 14px */
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
            padding: 10px; /* Reduced from 15px */
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
            width: 25px; /* Reduced from 30px */
        }

        .control-details .dropdown-icon {
            font-size: 14px; /* Reduced from 18px */
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
            padding: 10px; /* Reduced from 15px */
            width: 100%;
        }

        /* Styling untuk tabel di dalam child row */
        .child-table {
            width: 98% !important;
            margin: 8px auto !important; /* Reduced from 10px */
            border-radius: 4px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .child-table thead th {
            background-color: #021d39;
            color: white;
            font-size: 10px; /* Reduced from 12px */
            padding: 6px !important; /* Reduced from 8px */
        }

        .child-table tbody td {
            padding: 6px !important; /* Reduced from 8px */
            font-size: 10px; /* Reduced from 12px */
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

        /* Additional size reductions */
        .panel-content {
            font-size: 9pt; /* Added */
        }
        
        .form-control, .select2-container {
            font-size: 9pt !important; /* Added */
        }
        
        .btn {
            font-size: 9pt !important; /* Added */
            padding: 0.25rem 0.5rem !important; /* Added */
        }
        
        .alert {
            font-size: 9pt; /* Added */
            padding: 0.5rem 1rem; /* Added */
        }
        
        .panel-toolbar span.badge {
            font-size: 9pt; /* Added */
            padding: 0.25em 0.4em; /* Added */
        }
        
        h2 {
            font-size: 1.2rem; /* Reduced */
        }
    </style>
@endpush

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <!-- Search Panel -->
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Form <span class="fw-300"><i>Pencarian GRN</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form action="{{ route('keuangan.ap-supplier.indexGrn') }}" method="get" id="filterGrnForm">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="mb-1">Supplier</label>
                                        <select class="form-control form-control-sm select2" disabled>
                                            @foreach ($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}"
                                                    {{ $activeSupplierId == $supplier->id ? 'selected' : '' }}>
                                                    {{ $supplier->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="mb-1">Nomor PO</label>
                                        <input type="text" class="form-control" name="po_number"
                                            value="{{ request('po_number') }}" placeholder="Masukkan Nomor PO">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="mb-1">No Invoice</label>
                                        <input type="text" class="form-control" name="invoice_number"
                                            value="{{ request('invoice_number') }}" placeholder="Masukkan No. Invoice">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="mb-1">GRN</label>
                                        <input type="text" class="form-control" name="grn_number"
                                            value="{{ request('grn_number') }}" placeholder="Masukkan GRN">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end mt-2">
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

        <!-- Data Table -->
        <div class="row mt-4">
            <div class="col-xl-12">
                <div class="panel">
                    <div class="panel-hdr">
                        <h2>List <span class="fw-300"><i>GRN Tersedia</i></span></h2>
                        @if ($isSearching && $availableGrns->count() > 0)
                            <div class="panel-toolbar">
                                <span class="badge badge-info">
                                    <i class="fal fa-info-circle mr-1"></i>
                                    Klik icon pena untuk memilih GRN
                                </span>
                            </div>
                        @endif
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            @if ($isSearching)
                                @if ($availableGrns->count() > 0)
                                    <table id="dt-basic-example"
                                        class="table table-bordered table-hover table-striped w-100">
                                        <thead class="bg-primary-600">
                                            <tr>
                                                <th>No</th>
                                                <th>Tgl Penerimaan</th>
                                                <th>Kode GRN</th>
                                                <th>Supplier</th>
                                                <th>Kode PO</th>
                                                <th>No Invoice</th>
                                                <th class="text-right">Total Nilai</th>
                                                <th class="text-center no-export">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($availableGrns as $index => $grn)
                                                <tr data-grn-id="{{ $grn->id }}" class="grn-row">
                                                    <td class="text-center">{{ $index + 1 }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($grn->tanggal_penerimaan)->format('d M Y') }}
                                                    </td>
                                                    <td>{{ $grn->no_grn }}</td>
                                                    <td>{{ $grn->supplier->nama ?? '-' }}</td>
                                                    <td>{{ $grn->purchasable->no_po ?? '-' }}</td>
                                                    <td>{{ $grn->no_invoice ?? '-' }}</td>
                                                    <td class="text-right">{{ number_format($grn->total_nilai_barang, 2) }}
                                                    </td>
                                                    <td class="text-center">
                                                        <button class="btn btn-xs btn-primary select-grn-btn btn-action"
                                                            data-grn-id="{{ $grn->id }}"
                                                            data-grn-no="{{ $grn->no_grn }}"
                                                            data-supplier="{{ $grn->supplier->nama ?? '' }}"
                                                            data-supplier-id="{{ $grn->supplier_id ?? '' }}"
                                                            data-po="{{ $grn->purchasable->no_po ?? '' }}"
                                                            data-invoice="{{ $grn->no_invoice ?? '' }}"
                                                            data-tanggal="{{ \Carbon\Carbon::parse($grn->tanggal_penerimaan)->format('d M Y') }}"
                                                            data-total="{{ $grn->total_nilai_barang }}"
                                                            title="Pilih GRN ini" data-toggle="tooltip">
                                                            <i class='bx bx-check-circle'></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div class="alert alert-info">
                                        <i class="fal fa-info-circle mr-2"></i>
                                        Tidak ada GRN yang tersedia dengan kriteria pencarian yang diberikan.
                                    </div>
                                @endif
                            @else
                                <div class="alert alert-warning">
                                    <i class="fal fa-exclamation-triangle mr-2"></i>
                                    Silakan gunakan form pencarian di atas untuk menampilkan GRN yang tersedia.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/dependency/moment/moment.js"></script>
    <script src="/js/formplugins/bootstrap-daterangepicker/bootstrap-daterangepicker.js"></script>
    <script src="/js/formplugins/inputmask/inputmask.bundle.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="/js/notifications/toastr/toastr.js"></script>
    <link rel="stylesheet" href="/css/notifications/toastr/toastr.css">
    <!-- Tambahkan Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <script>
        $(document).ready(function () {
            // Konfigurasi toastr agar pasti tampil
            toastr.options = {
                closeButton: true,
                debug: false,
                newestOnTop: false,
                progressBar: true,
                positionClass: "toast-top-right",
                preventDuplicates: true,
                onclick: null,
                showDuration: "300",
                hideDuration: "1000",
                timeOut: "3000",
                extendedTimeOut: "1000",
                showEasing: "swing",
                hideEasing: "linear",
                showMethod: "fadeIn",
                hideMethod: "fadeOut"
            };

            function sendDataToParent(event) {
                event.preventDefault();
                event.stopPropagation();

                const $button = $(this).hasClass('select-grn-btn') ? $(this) : $(this).find('.select-grn-btn');

                if ($button.length === 0) {
                    return;
                }

                const grnData = {
                    id: parseInt($button.data('grn-id')),
                    no_grn: $button.data('grn-no'),
                    supplier: $button.data('supplier'),
                    supplier_id: parseInt($button.data('supplier-id')),
                    po_no: $button.data('po'),
                    no_invoice: $button.data('invoice'),
                    tanggal_penerimaan: $button.data('tanggal'),
                    total_nilai_barang: parseFloat($button.data('total')),
                    purchasable: {
                        no_po: $button.data('po')
                    }
                };

                try {
                    const parentWindow = window.opener || window.parent;

                    if (parentWindow && !parentWindow.closed) {
                        parentWindow.postMessage({
                            type: 'GRN_SELECTED',
                            data: grnData
                        }, window.location.origin);

                        toastr.success('GRN berhasil dipilih!', 'Sukses');
                    } 
                } catch (error) {
                    toastr.error('Gagal mengirim data ke parent window', 'Error');
                }
            }

            // Klik tombol select
            $(document).on('click', '.select-grn-btn', function (e) {
                sendDataToParent.call(this, e);
            });

            // Klik baris GRN
            $(document).on('click', '.grn-row', function (e) {
                if (!$(e.target).closest('.select-grn-btn').length) {
                    sendDataToParent.call(this, e);
                }
            });
        });
    </script>
@endsection