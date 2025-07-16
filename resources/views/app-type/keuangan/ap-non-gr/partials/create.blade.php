    @extends('inc.layout')
    @section('title', 'AP NON GR')
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
                /* background-color: #021d39; */
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
                /* backgroun    d-color: #021d39; */
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
                cursor: pointer;
                transition: all 0.3s ease;
                box-shadow: 0 2px 4px rgba(0, 123, 255, 0.3);
            }

            .btn-add-icon:hover {
                background-color: #0056b3;
                transform: scale(1.05);
                box-shadow: 0 4px 8px rgba(0, 123, 255, 0.4);
            }

            .btn-add-icon:focus {
                outline: none;
                box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
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
                /* Menempel di sisi kanan */
            }
        </style>

    @section('content')
        <main id="js-page-content" role="main" class="page-content">
            {{-- Ganti 'ap-non-gr.store' menjadi 'ap-non-po.store' jika Anda sudah me-rename route --}}
            <form action="{{ route('keuangan.ap-non-gr.store') }}" method="POST" id="create-ap-non-po-form">
                @csrf
                <div class="row justify-content-center">
                    <!-- Panel Form Header -->
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
                                            {{-- Ganti nama 'tanggal_awal' menjadi 'tanggal_ap' agar konsisten --}}
                                            <div class="form-group">
                                                <label for="tanggal_ap">Tanggal AP <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control datepicker" name="tanggal_ap"
                                                        value="{{ old('tanggal_ap', date('d-m-Y')) }}" required
                                                        autocomplete="off">
                                                    <div class="input-group-append"><span class="input-group-text"><i
                                                                class="fal fa-calendar"></i></span></div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="supplier_id">Supplier <span class="text-danger">*</span></label>
                                                <select class="form-control select2" id="supplier_id" name="supplier_id"
                                                    required>
                                                    <option value="" disabled selected>Pilih Supplier...</option>
                                                    @foreach ($suppliers as $supplier)
                                                        <option value="{{ $supplier->id }}"
                                                            {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                                            {{ $supplier->nama }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="due_date">Due Date <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control datepicker" name="due_date"
                                                        value="{{ old('due_date', date('d-m-Y', strtotime('+1 days'))) }}"
                                                        required autocomplete="off">
                                                    <div class="input-group-append"><span class="input-group-text"><i
                                                                class="fal fa-calendar"></i></span></div>
                                                </div>
                                            </div>

                                            {{-- Ganti bagian ini di file create.blade.php --}}

                                            <div class="form-group">
                                                <label for="transaksi_coa_select">Pilih Transaksi <span
                                                        class="text-danger">*</span></label>
                                                <div class="select-with-icon">
                                                    <select class="form-control select2" id="transaksi_coa_select">
                                                        <option value="">Pilih Akun...</option>

                                                        {{-- Loop through the new hierarchical data from the controller --}}
                                                        @foreach ($hierarchicalCoas as $groupName => $coas)
                                                            <optgroup label="{{ $groupName }}">
                                                                @foreach ($coas as $coa)
                                                                    {{-- 
                            - The value is the COA's ID.
                            - data-coa-name holds the full original name for the detail table.
                            - The displayed text is cleaner, showing only the specific detail part.
                        --}}
                                                                    <option value="{{ $coa->id }}"
                                                                        data-coa-name="{{ $coa->name }}">
                                                                        {{ $coa->code }} - {{ $coa->detail_name }}
                                                                    </option>
                                                                @endforeach
                                                            </optgroup>
                                                        @endforeach

                                                    </select>
                                                    <button type="button" class="btn-add-icon bg-primary-600"
                                                        id="btn-add-transaction" title="Tambah Transaksi">
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
                                                    name="no_invoice_supplier" value="{{ old('no_invoice_supplier') }}"
                                                    required>
                                            </div>
                                            <div class="form-group">
                                                <label for="no_faktur_pajak">No. Faktur Pajak</label>
                                                <input type="text" class="form-control" id="no_faktur_pajak"
                                                    name="no_faktur_pajak" value="{{ old('no_faktur_pajak') }}">
                                            </div>
                                            <div class="form-group">
                                                <label for="tanggal_faktur_pajak">Tgl Faktur Pajak</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control datepicker"
                                                        id="tanggal_faktur_pajak" name="tanggal_faktur_pajak"
                                                        value="{{ old('tanggal_faktur_pajak') }}" autocomplete="off">
                                                    <div class="input-group-append"><span class="input-group-text"><i
                                                                class="fal fa-calendar"></i></span></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="notes">Keterangan </label>
                                                <textarea class="form-control" id="notes" name="notes" rows="1">{{ old('notes') }}</textarea>
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
                                                    <th style="width: 5%;">Aksi</th>
                                                    <th style="width: 20%;">Nama Akun</th>
                                                    <th style="width: 25%;">Keterangan</th>
                                                    <th style="width: 20%;">Cost Center</th>
                                                    <th style="width: 30%;">Nominal</th>
                                                </tr>
                                            </thead>
                                            <tbody id="transaction-details">
                                                <tr id="placeholder-row">
                                                    <td colspan="5" class="text-center text-muted">Belum ada transaksi
                                                        ditambahkan.</td>
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="3" class="text-right font-weight-bold">Subtotal</td>
                                                    <td colspan="2" class="text-right font-weight-bold"
                                                        id="subtotal-display">Rp 0</td>
                                                </tr>
                                                <tr class="ppn-row">
                                                    <td colspan="3" class="text-right font-weight-bold">PPN</td>
                                                    <td style="width: 10%;">
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" class="form-control text-right"
                                                                id="ppn_persen" name="ppn_persen" value="11"
                                                                min="0" max="100" step="0.01">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">%</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td style="width: 10%;"
                                                        class="text-right font-weight-bold ppn-display">
                                                        <span id="ppn-display">Rp 0</span>
                                                        <input type="hidden" id="ppn_nominal" name="ppn_nominal"
                                                            value="0">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3" class="text-right font-weight-bold"
                                                        style="">Grand Total</td>
                                                    <td colspan="2" class="text-right font-weight-bold"
                                                        id="grand-total-display" style="    ">Rp 0</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <div
                                        class="panel-content border-faded border-left-0 border-right-0 border-bottom-0 d-flex flex-row align-items-center mt-3">
                                        <a href="{{ route('keuangan.ap-non-gr.index') }}"
                                            class="btn btn-secondary">Kembali</a>
                                        <div class="ml-auto">
                                            <button type="submit" class="btn btn-primary"><i class="fal fa-save"></i>
                                                Simpan
                                                Transaksi</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </main>
    @endsection

    @section('plugin')
        <script src="/js/formplugins/select2/select2.bundle.js"></script>
        <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
        <script src="/js/notifications/toastr/toastr.js"></script>

        <script>
            $(document).ready(function() {
                // 1. Inisialisasi Plugin
                $('.select2').select2({
                    placeholder: "Pilih..."
                });
                $('.datepicker').datepicker({
                    format: 'dd-mm-yyyy',
                    autoclose: true,
                    todayHighlight: true
                });
                toastr.options.positionClass = 'toast-top-right';

                let transactionIndex = 0;

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

                // 3. Fungsi Utama
                function calculateTotal() {
                    let subtotal = 0;
                    $('.nominal-input').each(function() {
                        subtotal += parseCurrency($(this).val());
                    });

                    // Hitung PPN berdasarkan persentase
                    const ppnPercent = parseFloat($('#ppn_persen').val()) || 0;
                    const ppnPersen = subtotal * (ppnPercent / 100);

                    const grandTotal = subtotal + ppnPersen;

                    // Update tampilan
                    $('#subtotal-display').text(formatCurrency(subtotal));
                    $('#ppn-display').text(formatCurrency(ppnPersen));
                    $('#ppn_nominal').val(ppnPersen);
                    $('#grand-total-display').text(formatCurrency(grandTotal));
                }

                // 4. Event Listeners
                $('#btn-add-transaction').on('click', function() {
                    const selectedOption = $('#transaksi_coa_select').find('option:selected');
                    const coaId = selectedOption.val();
                    if (!coaId) {
                        toastr.warning('Silakan pilih akun transaksi terlebih dahulu.');
                        return;
                    }

                    if ($(`input[name$="[coa_id]"][value="${coaId}"]`).length > 0) {
                        toastr.error('Akun ini sudah ditambahkan.');
                        return;
                    }

                    $('#placeholder-row').remove();

                    const costCenterOptions = `{!! $costCenters->map(function ($rnc) {
                            return "<option value=\"{$rnc->id}\">{$rnc->nama_rnc} </option>";
                        })->implode('') !!}`;
                    const newRow = `
                    <tr data-row-id="${transactionIndex}">
                        <td class="text-center"><button type="button" class="btn btn-xs btn-danger btn-remove-row"><i class="fal fa-times"></i></button></td>
                        <td><input type="hidden" name="details[${transactionIndex}][coa_id]" value="${coaId}"><input type="text" class="form-control form-control-sm bg-light" value="${selectedOption.data('coa-name')}" readonly></td>
                        <td><input type="text" name="details[${transactionIndex}][keterangan]" class="form-control form-control-sm"></td>
                        <td>
                    <select class="form-control form-control-sm select2-detail" name="details[${transactionIndex}][cost_center_id]" required>
                        <option value="">Pilih Cost Center</option>
                        ${costCenterOptions}
                    </select>
                </td>
                        <td><input type="text" name="details[${transactionIndex}][nominal]" class="form-control form-control-sm text-right nominal-input" value="0" required></td>
                    </tr>`;

                    $('#transaction-details').append(newRow);
                    $(`tr[data-row-id="${transactionIndex}"] .select2-detail`).select2();
                    transactionIndex++;
                    $('#transaksi_coa_select').val(null).trigger('change');

                    // Tambahkan efek visual feedback
                    $(this).addClass('btn-success').removeClass('btn-primary');
                    setTimeout(() => {
                        $(this).removeClass('btn-success').addClass('btn-primary');
                    }, 200);
                });

                $('#transaction-details').on('click', '.btn-remove-row', function() {
                    $(this).closest('tr').remove();
                    if ($('#transaction-details tr').length === 0) {
                        $('#transaction-details').append(
                            '<tr id="placeholder-row"><td colspan="5" class="text-center text-muted">Belum ada transaksi ditambahkan.</td></tr>'
                        );
                    }
                    calculateTotal();
                });

                // Gunakan event delegation untuk input yang dinamis
                $('#transaction-details').on('input blur', '.nominal-input', function(event) {
                    let value = parseCurrency($(this).val());
                    if (event.type === 'blur') {
                        $(this).val(formatCurrency(value).replace('Rp', '').trim());
                    }
                    calculateTotal();
                });

                // Event listener untuk perubahan persentase PPN
                $('#ppn_persen').on('input change', function() {
                    calculateTotal();
                });

                // 5. Submit Handler
                $('#create-ap-non-po-form').on('submit', function(e) {
                    if ($('.nominal-input').length === 0) {
                        e.preventDefault();
                        toastr.error('Harap tambahkan minimal satu rincian transaksi.');
                        return;
                    }
                    // Unformat semua nilai sebelum submit
                    $('.nominal-input').each(function() {
                        $(this).val(parseCurrency($(this).val()));
                    });

                });
            });
        </script>
    @endsection
