@extends('inc.layout-no-side')
@section('title', 'Pilih GRN')

@push('styles')
    <style>
        /* Styles from AP Dokter & Pilih PO Image */
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

        .selected-row {
            background-color: #d1ecf1 !important;
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
                                <input type="hidden" name="supplier_id" value="{{ $activeSupplierId }}">

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="mb-1">Supplier</label>
                                        <select class="form-control form-control-sm select2" disabled>
                                            {{-- Dropdown ini dinonaktifkan dan hanya untuk tampilan --}}
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
                                    <a href="{{ route('keuangan.ap-supplier.partials.create') }}"
                                        class="btn btn-sm btn-success">
                                        <i class="fal fa-plus mr-1"></i> Buat AP Supplier
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
                        <h2>List <span class="fw-300"><i>GRN Tersedia</i></span></h2>
                        @if ($hasFilters && $availableGrns->count() > 0)
                            <div class="panel-toolbar">
                                <button type="button" class="btn btn-sm btn-success" id="selectSelectedGrns">
                                    <i class="fal fa-check mr-1"></i> Pilih GRN Terpilih
                                </button>
                            </div>
                        @endif
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            @if ($hasFilters)
                                @if ($availableGrns->count() > 0)
                                    <table id="dt-basic-example"
                                        class="table table-bordered table-hover table-striped w-100">
                                        <thead class="bg-primary-600">
                                            <tr>
                                                <th class="text-center">
                                                    <input type="checkbox" id="selectAll">
                                                </th>
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
                                            @foreach ($availableGrns as $grn)
                                                <tr data-grn-id="{{ $grn->id }}">
                                                    <td class="text-center">
                                                        <input type="checkbox" class="grn-checkbox"
                                                            value="{{ $grn->id }}">
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($grn->tanggal_penerimaan)->format('d M Y') }}
                                                    </td>
                                                    <td>{{ $grn->no_grn }}</td>
                                                    <td>{{ $grn->supplier->nama ?? '-' }}</td>
                                                    <td>{{ $grn->purchasable->no_po ?? '-' }}</td>
                                                    <td>{{ $grn->no_invoice ?? '-' }}</td>
                                                    <td class="text-right">{{ number_format($grn->total_nilai_barang, 2) }}
                                                    </td>
                                                    <td class="text-center">
                                                        <button class="btn btn-xs btn-primary select-grn-btn"
                                                            data-grn-id="{{ $grn->id }}"
                                                            data-grn-no="{{ $grn->no_grn }}"
                                                            data-supplier="{{ $grn->supplier->nama ?? '' }}"
                                                            data-po="{{ $grn->purchasable->no_po ?? '' }}"
                                                            data-total="{{ $grn->total_nilai_barang }}" title="Pilih GRN">
                                                            <i class="fal fa-edit"></i>
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

            // Inisialisasi Select2
            $('.select2').select2({
                dropdownCssClass: "move-up",
                placeholder: "Pilih opsi",
                allowClear: true
            });

            // Initialize DataTables
            @if ($hasFilters && $availableGrns->count() > 0)
                var tableGrn = $('#dt-basic-example').DataTable({
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
                                columns: ':not(.no-export)'
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
            @endif

            // Handle select all checkbox
            $('#selectAll').on('change', function() {
                $('.grn-checkbox').prop('checked', this.checked);
                updateRowSelection();
            });

            // Handle individual checkbox
            $(document).on('change', '.grn-checkbox', function() {
                updateRowSelection();

                // Update select all checkbox
                var totalCheckboxes = $('.grn-checkbox').length;
                var checkedCheckboxes = $('.grn-checkbox:checked').length;

                $('#selectAll').prop('indeterminate', checkedCheckboxes > 0 && checkedCheckboxes <
                    totalCheckboxes);
                $('#selectAll').prop('checked', checkedCheckboxes === totalCheckboxes);
            });

            // Function to update row selection visual
            function updateRowSelection() {
                $('.grn-checkbox').each(function() {
                    var row = $(this).closest('tr');
                    if ($(this).is(':checked')) {
                        row.addClass('selected-row');
                    } else {
                        row.removeClass('selected-row');
                    }
                });
            }

            // Handle single GRN selection (icon pena)
            $(document).on('click', '.select-grn-btn', function() {
                var grnId = $(this).data('grn-id');
                var grnNo = $(this).data('grn-no');
                var supplier = $(this).data('supplier');
                var po = $(this).data('po');
                var total = $(this).data('total');

                // Redirect ke halaman create dengan data GRN terpilih
                var createUrl = "{{ route('keuangan.ap-supplier.partials.create') }}";
                var params = new URLSearchParams({
                    selected_grn: grnId,
                    grn_no: grnNo,
                    supplier: supplier,
                    po_no: po,
                    total: total
                });

                window.location.href = createUrl + '?' + params.toString();
            });

            // Handle multiple GRN selection
            $('#selectSelectedGrns').on('click', function() {
                var selectedGrns = [];
                $('.grn-checkbox:checked').each(function() {
                    var row = $(this).closest('tr');
                    var grnId = $(this).val();
                    selectedGrns.push(grnId);
                });

                if (selectedGrns.length === 0) {
                    toastr.warning('Pilih minimal satu GRN untuk melanjutkan.');
                    return;
                }

                // Redirect ke halaman create dengan multiple GRN
                var createUrl = "{{ route('keuangan.ap-supplier.partials.create') }}";
                var params = new URLSearchParams({
                    selected_grns: selectedGrns.join(',')
                });

                window.location.href = createUrl + '?' + params.toString();
            });

            // Handle form submission for filtering
            $('#filterGrnForm').on('submit', function(e) {
                // Allow normal form submission to filter data
            });
        });
    </script>
@endsection
