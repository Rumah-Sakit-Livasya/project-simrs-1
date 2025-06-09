@extends('inc.layout')
@section('title', 'Pilih PO')

@push('styles')
    <style>
        /* Styles from AP Dokter & Pilih PO Image */
        .status-icon {
            cursor: pointer;
        }

        .status-icon.grey {
            color: #999;
            /* Warna abu-abu */
        }

        .status-icon.green {
            color: #00a65a;
            /* Warna hijau */
        }

        /* Style for validation errors in modal */
        #modalValidationErrorMessagesInsideModal {
            margin-top: 15px;
        }

        #modalValidationErrorMessagesInsideModal ul {
            padding-left: 20px;
            margin-bottom: 0;
        }

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
    </style>
@endpush

@section('content')
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
                            <form action="#" method="get" id="filterPoForm">
                                {{-- Struktur Form disamakan dengan AP Dokter untuk konsistensi --}}
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="mb-1">Supplier</label> {{-- Mengganti "supplier" menjadi "Supplier" --}}
                                        <select class="form-control form-control-sm select2" name="supplier">
                                            <option value="">All</option>
                                            <option value="test-supplier-1">Test Supplier 1</option>
                                            <option value="test-supplier-2">Test Supplier 2</option>
                                            <option value="test-supplier-3">Test Supplier 3</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="mb-1">Nomor PO</label>
                                        <input type="text" class="form-control" name="po_number"
                                            placeholder="Masukkan Nomor PO">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="mb-1">No Invoice</label>
                                        <input type="text" class="form-control" name="invoice_number"
                                            placeholder="Masukkan No. Invoice">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="mb-1">GRN</label>
                                        <input type="text" class="form-control" name="grn_number"
                                            placeholder="Masukkan GRN">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end mt-2">
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
                                        <th>Tgl Penerimaan</th>
                                        <th>Kode GRN</th>
                                        <th>Supplier</th>
                                        <th>Kode PO</th>
                                        <th>No Invoice</th>
                                        <th class="text-right">Materai</th>
                                        <th class="text-right">Disc Final</th>
                                        <th class="text-right">PPN</th>
                                        <th class="text-right">Total</th>
                                        <th class="text-center no-export">Aksi</th> {{-- No-export class for buttons column --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Data dummy, sesuaikan dengan data dari backend Anda --}}
                                    <tr>
                                        <td>24 Jan 2025</td>
                                        <td>APS25-000028</td>
                                        <td>PT. PARIT PADANG GLOBAL</td>
                                        <td>00190/FNP02501</td>
                                        <td>8290317806</td>
                                        <td class="text-right">10,000.00</td>
                                        <td class="text-right">0.00</td>
                                        <td class="text-right">323,350.10</td>
                                        <td class="text-right">2,939,546.40</td>
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

@section('plugins')
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
                    // Initialize tooltips
                    $('[data-toggle="tooltip"]').tooltip();

                    // Inisialisasi Select2 (Sama seperti halaman AP Dokter)
                    $('.select2').select2({
                        dropdownCssClass: "move-up",
                        placeholder: "Pilih opsi",
                        allowClear: true
                    });

                    $(document).ready(function() {

                        // Inisialisasi Select2
                        $('.select2').select2({
                            dropdownCssClass: "move-up",
                            placeholder: "Pilih opsi",
                            allowClear: true
                        });
                        // Initialize DataTables
                        var tablePO = $('#dt-basic-example').DataTable({
                            responsive: true,
                            dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'lB>>" +
                                "<'row'<'col-sm-12'tr>>" +
                                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                            buttons: [{
                                    extend: 'pdfHtml5',
                                    text: '<i class="fal fa-file-pdf mr-1"></i> PDF',
                                    titleAttr: 'Generate PDF',
                                    className: 'btn-outline-danger btn-sm mr-1',
                                    exportOptions: {
                                        columns: ':not(.no-export)' // Exclude columns with 'no-export' class
                                    },
                                    orientation: 'landscape'
                                },
                                {
                                    extend: 'excelHtml5',
                                    text: '<i class="fal fa-file-excel mr-1"></i> Excel',
                                    titleAttr: 'Generate Excel',
                                    className: 'btn-outline-success btn-sm mr-1',
                                    exportOptions: {
                                        columns: ':not(.no-export)'
                                    }
                                },
                                {
                                    extend: 'print',
                                    text: '<i class="fal fa-print mr-1"></i> Print',
                                    titleAttr: 'Print Table',
                                    className: 'btn-outline-primary btn-sm',
                                    exportOptions: {
                                        columns: ':not(.no-export)'
                                    }
                                }
                            ],
                            lengthChange: true,
                            pageLength: 10,
                            language: {
                                search: "_INPUT_",
                                searchPlaceholder: "Cari data...",
                                lengthMenu: "Tampil _MENU_ data",
                                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                                infoEmpty: "Tidak ada data.",
                                infoFiltered: "(difilter dari _MAX_ total data)",
                                paginate: {
                                    first: "Awal",
                                    last: "Akhir",
                                    next: "Berikutnya",
                                    previous: "Sebelumnya"
                                },
                                zeroRecords: "Tidak ada data yang cocok ditemukan."
                            }
                        });

                        // Handle form submission for filtering DataTable
                        $('#filterPoForm').on('submit', function(e) {
                            e.preventDefault();
                            // tablePO.ajax.reload(); // Uncomment if using AJAX data source
                            toastr.info(
                                'Filter diterapkan. Jika menggunakan data statis, gunakan pencarian DataTables.'
                            );
                        });

                    });
    </script>
@endsection
