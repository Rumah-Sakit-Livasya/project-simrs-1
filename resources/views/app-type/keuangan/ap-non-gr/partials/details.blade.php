@extends('inc.layout')
@section('title', 'Detail AP Non-PO')
@section('content')
    <style>
        /* CSS yang sudah dirapihkan */
        .status-icon {
            cursor: pointer;
        }

        .status-icon.grey {
            color: #999;
        }

        .status-icon.green {
            color: #00a65a;
        }

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

        .child-row {
            display: none;
        }

        .dropdown-icon {
            font-size: 14px;
            transition: transform 0.3s ease;
            display: inline-block;
        }

        .dropdown-icon.bxs-down-arrow {
            transform: rotate(180deg);
        }

        .child-row td {
            background-color: #f9f9f9;
            border-bottom: 2px solid #ddd;
        }

        .child-row td>div {
            padding: 15px;
            margin: 0;
        }

        tr.parent-row.active {
            border-bottom: none !important;
        }

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
        }

        .control-details .dropdown-icon.bxs-up-arrow {
            transform: rotate(180deg);
            color: #e74c3c;
        }

        .control-details:hover .dropdown-icon {
            color: #2980b9;
        }

        table.dataTable thead .sorting:after,
        table.dataTable thead .sorting_asc:after,
        table.dataTable thead .sorting_desc:after,
        table.dataTable thead .sorting_asc_disabled:after,
        table.dataTable thead .sorting_desc_disabled:after {
            display: none !important;
        }

        .child-row td>div {
            padding: 15px;
            width: 100%;
        }

        .child-table {
            width: 98% !important;
            margin: 10px auto !important;
            border-radius: 4px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .child-table thead th {
            color: white;
            font-size: 12px;
            padding: 8px !important;
        }

        .child-table tbody td {
            padding: 8px !important;
            font-size: 12px;
            background-color: white;
        }

        .child-row {
            transition: all 0.3s ease;
        }

        .child-row.show {
            opacity: 1;
        }

        td.control-details::before {
            display: none !important;
        }

        #dt-basic-example tbody tr.parent-row:hover {
            background-color: #f8f9fa;
            cursor: pointer;
        }

        #dt-basic-example tbody tr.child-row:hover {
            background-color: #f1f1f1;
        }

        /* Tambahan styling untuk form */
        .form-section {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 4px;
        }

        .table-detail th {
            color: white;
        }

        .total-section {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 4px;
        }

        /* Custom styling untuk icon tambah */
        .btn-add-icon {
            width: 38px;
            height: 38px;
            border-top-right-radius: 3px;
            border-bottom-right-radius: 3px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            color: white;
            cursor: not-allowed;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 123, 255, 0.3);
            opacity: 0.5;
        }

        .btn-add-icon i {
            font-size: 16px;
            font-weight: bold;
        }

        /* Styling untuk container select dengan icon */
        .select-with-icon {
            position: relative;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .select-with-icon .form-control {
            flex: 1;
        }

        .btn-add-icon {
            position: absolute;
            right: 0;
            z-index: 10;
        }

        /* Status badge styling */
        .badge-status {
            font-size: 100%;
            padding: 0.5em 0.75em;
        }

        /* Additional styling for better visual hierarchy */
        .detail-header {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .detail-section-title {
            font-size: 1.2rem;
            color: #2c3e50;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .info-label {
            font-weight: 600;
            color: #6c757d;
        }

        .info-value {
            font-weight: 500;
            color: #343a40;
        }

        .btn-remove-row {
            display: none !important;
        }
    </style>



    <main id="js-page-content" role="main" class="page-content">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div class="panel">
                    <div class="panel-hdr">
                        <h2>Form AP Non-PO</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <div class="row">
                                <!-- Kolom Kiri -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tanggal_ap">Tanggal AP <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" name="tanggal_ap"
                                                value="{{ $apNonGrn->tanggal_ap->format('d-m-Y') }}" autocomplete="off">
                                            <div class="input-group-append"><span class="input-group-text"><i
                                                        class="fal fa-calendar"></i></span></div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="supplier_id">Supplier <span class="text-danger">*</span></label>
                                        <select class="form-control select2" id="supplier_id" name="supplier_id">
                                            <option value="{{ $apNonGrn->supplier_id }}" selected>
                                                {{ $apNonGrn->supplier->nama }}
                                            </option>
                                            @foreach ($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}"
                                                    {{ $apNonGrn->supplier_id == $supplier->id ? 'selected' : '' }}>
                                                    {{ $supplier->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="due_date">Due Date <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" name="due_date"
                                                value="{{ $apNonGrn->due_date->format('d-m-Y') }}" autocomplete="off">
                                            <div class="input-group-append"><span class="input-group-text"><i
                                                        class="fal fa-calendar"></i></span></div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="transaksi_coa_select">Pilih Transaksi <span
                                                class="text-danger">*</span></label>
                                        <div class="select-with-icon">
                                            <select class="form-control select2" id="transaksi_coa_select">
                                                <option value="">Pilih Akun...</option>
                                                @foreach ($grupCoa as $grup)
                                                    @if (isset($groupedCoaDetails[$grup->id]) && $groupedCoaDetails[$grup->id]->count() > 0)
                                                        <optgroup label="{{ $grup->name }}">
                                                            @foreach ($groupedCoaDetails[$grup->id] as $coa)
                                                                <option value="{{ $coa->id }}"
                                                                    data-coa-name="{{ $coa->name }}"
                                                                    @if ($apNonGrn->NonGrnDetails->isNotEmpty() && $apNonGrn->NonGrnDetails->first()->coa_id == $coa->id) selected @endif>
                                                                    {{ $coa->code }} - {{ $coa->name }}
                                                                </option>
                                                            @endforeach
                                                        </optgroup>
                                                    @endif
                                                @endforeach
                                            </select>
                                            <button type="button" class="btn-add-icon bg-primary-600"
                                                title="Mode Detail - Tidak Dapat Menambah">
                                                <i class="fal fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Kolom Kanan -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="no_invoice_supplier">No. Invoice Supplier <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="no_invoice_supplier"
                                            name="no_invoice_supplier" value="{{ $apNonGrn->no_invoice_supplier }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="no_faktur_pajak">No. Faktur Pajak</label>
                                        <input type="text" class="form-control" id="no_faktur_pajak"
                                            name="no_faktur_pajak" value="{{ $apNonGrn->no_faktur_pajak }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="tanggal_faktur_pajak">Tgl Faktur Pajak</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" id="tanggal_faktur_pajak"
                                                name="tanggal_faktur_pajak"
                                                value="{{ $apNonGrn->tanggal_faktur_pajak ? $apNonGrn->tanggal_faktur_pajak->format('d-m-Y') : '' }}"
                                                autocomplete="off">
                                            <div class="input-group-append"><span class="input-group-text"><i
                                                        class="fal fa-calendar"></i></span></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="notes">Keterangan</label>
                                        <textarea class="form-control" id="notes" name="notes" rows="1">{{ $apNonGrn->notes }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel Tabel Detail -->
            <div class="col-xl-12 mt-4">
                <div class="panel">
                    <div class="panel-hdr">
                        <h2>Detail Transaksi</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-detail">
                                    <thead class="bg-primary-600">
                                        <tr class="text-center">
                                            <th style="width: 5%;">No</th>
                                            <th style="width: 20%;">Nama Akun</th>
                                            <th style="width: 25%;">Keterangan</th>
                                            <th style="width: 20%;">Cost Center</th>
                                            <th style="width: 30%;">Nominal</th>
                                        </tr>
                                    </thead>
                                    <tbody id="transaction-details">
                                        @forelse ($apNonGrn->NonGrnDetails as $index => $detail)
                                            <tr data-row-id="{{ $index }}">
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td>
                                                    <input type="hidden" name="details[{{ $index }}][coa_id]"
                                                        value="{{ $detail->coa_id }}">
                                                    <input type="text" class="form-control form-control-sm bg-light"
                                                        value="{{ $detail->coa->code ?? '' }} - {{ $detail->coa->name ?? '' }}">
                                                </td>
                                                <td>
                                                    <input type="text" name="details[{{ $index }}][keterangan]"
                                                        class="form-control form-control-sm"
                                                        value="{{ $detail->keterangan }}">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control form-control-sm"
                                                        value="{{ $detail->costCenter ? $detail->costCenter->code . ' - ' . $detail->costCenter->name : 'N/A' }}">
                                                </td>
                                                <td>
                                                    <input type="text" name="details[{{ $index }}][nominal]"
                                                        class="form-control form-control-sm text-right nominal-input"
                                                        value="{{ number_format($detail->nominal, 0, ',', '.') }}">
                                                </td>
                                            </tr>
                                        @empty
                                            <tr id="placeholder-row">
                                                <td colspan="5" class="text-center text-muted">Tidak ada rincian
                                                    transaksi.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-right font-weight-bold">Subtotal</td>
                                            <td colspan="2" class="text-right font-weight-bold" id="subtotal-display">
                                                Rp {{ number_format($apNonGrn->subtotal, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                        <tr class="ppn-row">
                                            <td colspan="3" class="text-right font-weight-bold">PPN</td>
                                            <td style="width: 10%;">
                                                <div class="input-group input-group-sm">
                                                    @php
                                                        $ppn_percent =
                                                            $apNonGrn->subtotal > 0
                                                                ? ($apNonGrn->ppn_nominal / $apNonGrn->subtotal) * 100
                                                                : 0;
                                                    @endphp
                                                    <input type="number" class="form-control text-right"
                                                        id="ppn_percent" name="ppn_percent"
                                                        value="{{ number_format($ppn_percent, 2) }}" min="0"
                                                        max="100" step="0.01">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td style="width: 10%;" class="text-right font-weight-bold ppn-display">
                                                <span id="ppn-display">Rp
                                                    {{ number_format($apNonGrn->ppn_nominal, 0, ',', '.') }}</span>
                                                <input type="hidden" id="ppn_nominal" name="ppn_nominal"
                                                    value="{{ $apNonGrn->ppn_nominal }}">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-right font-weight-bold">Grand Total</td>
                                            <td colspan="2" class="text-right font-weight-bold"
                                                id="grand-total-display">
                                                Rp {{ number_format($apNonGrn->grand_total, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div
                                class="panel-content border-faded border-left-0 border-right-0 border-bottom-0 d-flex flex-row align-items-center mt-3">
                                <a href="{{ route('keuangan.ap-non-gr.index') }}" class="btn btn-secondary">
                                    <i class="fal fa-arrow-left"></i> Kembali
                                </a>
                                <div class="ml-auto">
                                    @if ($apNonGrn->status_pembayaran == 'Belum Lunas')
                                        <form action="{{ route('keuangan.ap-non-gr.destroy', $apNonGrn->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin membatalkan AP ini? Tindakan ini tidak dapat diurungkan.');"
                                            style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fal fa-trash"></i> Batalkan AP
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('plugin')
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/notifications/toastr/toastr.js"></script>

    <script>
        $(document).ready(function() {
            // 1. Inisialisasi Plugin untuk detail view (semua disabled)
            $('.select2').select2({
                placeholder: "Pilih...",
                disabled: true
            });

            $('.select2-detail').select2({
                disabled: true
            });

            $('.datepicker').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true
            });

            // 2. Fungsi Helper
            function formatCurrency(number) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(number);
            }

            function parseCurrency(value) {
                return parseFloat(String(value).replace(/[^0-9]/g, '')) || 0;
            }

            // 3. Format display nominal yang sudah ada
            $('.nominal-input').each(function() {
                let currentValue = $(this).val();
                // Jika sudah dalam format currency, biarkan saja
                if (!currentValue.includes('Rp')) {
                    let numericValue = parseCurrency(currentValue);
                    $(this).val(formatCurrency(numericValue).replace('Rp', '').trim());
                }
            });

            // // 4. Disable semua interaksi form
            // $('input, select, textarea').not('.form-control-plaintext').prop('', true);
            // $('button').not('.btn-secondary, .btn-danger').prop('disabled', true);

            // // 5. Tambahkan efek visual untuk menunjukkan bahwa form dalam mode view
            // $('.form-control, .select2-selection').css({
            //     'background-color': '#f8f9fa',
            //     'opacity': '1',
            //     'cursor': 'default'
            // });

            // 6. Pesan informasi mode detail
            toastr.options.positionClass = 'toast-top-right';
            toastr.info('Mode Detail - Data tidak dapat diubah', 'Informasi');
        });

        $(document).ready(function() {
            // Inisialisasi select2
            $('.select2').select2({
                placeholder: "Pilih...",
                disabled: true
            });

            // Jika ada data detail, set nilai select transaksi
            @if ($apNonGrn->NonGrnDetails->isNotEmpty())
                var firstCoaId = '{{ $apNonGrn->NonGrnDetails->first()->coa_id }}';
                $('#transaksi_coa_select').val(firstCoaId).trigger('change');
            @endif
        });
    </script>
@endsection
