@extends('inc.layout')
@section('title', 'Tambah Pembayaran Asuransi')
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

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            display: none;
            justify-content: center;
            align-items: center;
        }

        .loading-spinner {
            color: white;
            font-size: 2rem;
        }

        .loading-spinner i {
            animation: spin 1s linear infinite;
        }

        .payment-input {
            text-align: right;
        }

        .pelunasan-cell {
            padding: 0 !important;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>

    <!-- Loading overlay -->
    <div class="loading-overlay">
        <div class="loading-spinner">
            <i class="fa fa-spinner fa-spin"></i> Memproses...
        </div>
    </div>

    <main id="js-page-content" role="main" class="page-content">
        <!-- Pembayaran Form -->
        <form action="{{ route('keuangan.pembayaran-asuransi.store') }}" method="POST" id="form-pembayaran">
            @csrf

            <!-- Filter Panel: Informasi Pembayaran A/R -->
            <div class="row">
                <div class="col-xl-12">
                    <div id="panel-1" class="panel">
                        <div class="panel-hdr">
                            <h2>Filter <span class="fw-300"><i>Tagihan</i></span></h2>
                        </div>
                        <div class="panel-container show">
                            <div class="panel-content">
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="form-group row">
                                            <label class="col-xl-4 text-center col-form-label">Periode Awal</label>
                                            <div class="col-xl-8">
                                                <div class="input-group">
                                                    <input type="text" class="form-control datepicker"
                                                        name="tanggal_awal" id="tanggal_awal"
                                                        placeholder="Pilih tanggal awal" value="{{ date('d-m-Y') }}"
                                                        autocomplete="off">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text fs-xl">
                                                            <i class="fal fa-calendar"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-xl-4 text-center col-form-label">Penjamin</label>
                                            <div class="col-xl-8">
                                                <select class="form-control select2 w-100" id="penjamin_id"
                                                    style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                    name="penjamin_id">
                                                    <option value="">Pilih Penjamin</option>
                                                    @foreach ($penjamins as $penjamin)
                                                        <option value="{{ $penjamin->id }}">
                                                            {{ $penjamin->nama_perusahaan }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="form-group row">
                                            <label class="col-xl-4 text-center col-form-label">Periode Akhir</label>
                                            <div class="col-xl-8">
                                                <div class="input-group">
                                                    <input type="text" class="form-control datepicker"
                                                        name="tanggal_akhir" id="tanggal_akhir"
                                                        placeholder="Pilih tanggal akhir" value="{{ date('d-m-Y') }}"
                                                        autocomplete="off">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text fs-xl">
                                                            <i class="fal fa-calendar"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-xl-4 text-center col-form-label">No. Invoice</label>
                                            <div class="col-xl-8">
                                                <input type="text" class="form-control" id="invoice"
                                                    style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                    name="invoice">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-end mt-3">
                                    <div class="col-auto">
                                        <button type="button" class="btn bg-primary-600 mb-3" id="search-btn">
                                            <span class="fal fa-search mr-1"></span> Cari
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Penerimaan Pembayaran Panel -->
            <div class="row">
                <div class="col-xl-12">
                    <div id="panel-2" class="panel">
                        <div class="panel-hdr">
                            <h2>Penerimaan <span class="fw-300"><i>Pembayaran</i></span></h2>
                        </div>
                        <div class="panel-container show">
                            <div class="panel-content">
                                <div class="row">
                                    {{-- Kolom Kiri --}}
                                    <div class="col-xl-6">
                                        <div class="form-group row">
                                            <label class="col-xl-4 text-center col-form-label">Cash / Bank Account</label>
                                            <div class="col-xl-8">
                                                <select class="form-control select2 w-100" id="bank_account_id"
                                                    style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                    name="bank_account_id" required>
                                                    <option value="">Pilih Bank Account</option>
                                                    @foreach ($banks as $bank)
                                                        <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-xl-4 text-center col-form-label">Total Penerimaan</label>
                                            <div class="col-xl-8">
                                                <input type="text" class="form-control money" id="total_penerimaan"
                                                    value="Rp 0" readonly>
                                                <input type="hidden" name="total_penerimaan_hidden"
                                                    id="total_penerimaan_hidden" value="0">
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Kolom Kanan --}}
                                    <div class="col-xl-6">
                                        <div class="form-group row">
                                            <label class="col-xl-4 text-center col-form-label">Tgl. Jurnal</label>
                                            <div class="col-xl-8">
                                                <div class="input-group">
                                                    <input type="text" class="form-control datepicker"
                                                        name="tanggal_jurnal" id="tanggal_jurnal"
                                                        placeholder="Pilih tanggal jurnal" value="{{ date('d-m-Y') }}"
                                                        autocomplete="off" required>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text fs-xl">
                                                            <i class="fal fa-calendar"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- ✅ Tambahan: Keterangan Pembayaran --}}
                                        <div class="form-group row mt-3">
                                            <label class="col-xl-4 text-center col-form-label">Keterangan</label>
                                            <div class="col-xl-8">
                                                <textarea name="keterangan" id="keterangan" class="form-control" rows="3"
                                                    placeholder="Tulis keterangan pembayaran...">{{ old('keterangan') }}</textarea>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Data Tagihan Panel -->
            <div class="row">
                <div class="col-12">
                    <div id="panel-3" class="panel">
                        <div class="panel-hdr">
                            <h2>Data <span class="fw-300"><i>Tagihan</i></span></h2>
                        </div>
                        <div class="panel-container show">
                            <div class="panel-content">
                                <div class="table-responsive">
                                    <table id="dt-invoice-table"
                                        class="table table-bordered table-striped table-hover table-sm w-100 text-center">
                                        <thead class="bg-primary-600 align-middle">
                                            <tr>
                                                <th rowspan="2" style="width: 40px;">No</th>
                                                <th rowspan="2" style="min-width: 110px;">No. RM / Reg.</th>
                                                <th rowspan="2" style="min-width: 130px;">Nama Pasien</th>
                                                <th rowspan="2" style="min-width: 130px;">No. Inv.</th>
                                                <th rowspan="2" style="width: 100px;">Tgl Tagihan</th>
                                                <th rowspan="2" style="width: 100px;">Jatuh Tempo</th>
                                                <th rowspan="2" style="min-width: 100px;">Tagihan</th>
                                                <th rowspan="2" style="min-width: 100px;">Pelunasan</th>
                                                <th colspan="5">Due Date Period (IN DAYS)</th>
                                                <th rowspan="2" style="width: 40px;">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input"
                                                            id="check-all">
                                                        <label class="custom-control-label" for="check-all"></label>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th style="width: 50px;">&le;0</th>
                                                <th style="width: 50px;">0–15</th>
                                                <th style="width: 50px;">16–30</th>
                                                <th style="width: 50px;">31–60</th>
                                                <th style="width: 50px;">&gt;60</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($query as $item)
                                                <tr data-invoice-id="{{ $item->id }}">
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->registration->patient->medical_record_number ?? '-' }} /
                                                        {{ $item->registration->registration_number ?? '-' }}</td>
                                                    <td>{{ $item->registration->patient->name ?? '-' }}</td>
                                                    <td>{{ $item->invoice ?? '-' }}</td>
                                                    <td>{{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') : '-' }}
                                                    </td>
                                                    <td>{{ $item->jatuh_tempo ? \Carbon\Carbon::parse($item->jatuh_tempo)->format('d-m-Y') : '-' }}
                                                    </td>
                                                    @php
                                                        $sisa =
                                                            ($item->sisa_tagihan === null ||
                                                                $item->sisa_tagihan == 0) &&
                                                            ($item->total_dibayar == null || $item->total_dibayar == 0)
                                                                ? $item->jumlah
                                                                : $item->sisa_tagihan;
                                                    @endphp

                                                    <td class="tagihan-cell" data-amount="{{ $sisa }}">
                                                        {{ number_format($sisa, 0, ',', '.') }}
                                                    </td>

                                                    <td class="pelunasan-cell">
                                                        <div class="payment-container">
                                                            <input type="text"
                                                                class="form-control payment-input money-input"
                                                                data-max="{{ $sisa }}"
                                                                data-invoice-id="{{ $item->id }}"
                                                                name="payment_amount[{{ $item->id }}]" value="0"
                                                                disabled>
                                                        </div>
                                                    </td>


                                                    <td>{{ number_format($item->umur_0 ?? 0, 0, ',', '.') }}</td>
                                                    <td>{{ number_format($item->umur_15 ?? 0, 0, ',', '.') }}</td>
                                                    <td>{{ number_format($item->umur_30 ?? 0, 0, ',', '.') }}</td>
                                                    <td>{{ number_format($item->umur_60 ?? 0, 0, ',', '.') }}</td>
                                                    <td>{{ number_format($item->umur_60_plus ?? 0, 0, ',', '.') }}</td>
                                                    <td class="text-center">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input row-check"
                                                                name="selected_invoices[]" value="{{ $item->id }}"
                                                                id="check-{{ $loop->index }}">
                                                            <label class="custom-control-label"
                                                                for="check-{{ $loop->index }}"></label>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="15" class="text-center">Tidak ada data tagihan
                                                        tersedia.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Tombol aksi -->
                                <div class="row justify-content-between mt-3">
                                    <div class="col-auto">
                                        <button type="submit" class="btn bg-primary-600 mb-3" id="proses-btn">
                                            <span class="fal fa-cogs mr-1"></span> Proses dan Pelunasan AR
                                        </button>
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
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/formplugins/inputmask/inputmask.bundle.js"></script>
    <script src="/js/notifications/toastr/toastr.js"></script>
    <link rel="stylesheet" href="/css/notifications/toastr/toastr.css">

    <script>
        $(document).ready(function() {
            // Helper functions
            function formatDate(dateString) {
                if (!dateString) return '-';
                let date = new Date(dateString);
                return date.toLocaleDateString('id-ID');
            }

            function formatCurrency(amount) {
                if (typeof amount !== 'number') {
                    amount = parseFloat(amount);
                }
                if (isNaN(amount)) return 'Rp 0';
                return amount.toLocaleString('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                });
            }

            function parseFormattedCurrency(currency) {
                if (!currency) return 0;
                return parseInt(currency.replace(/[^\d]/g, '')) || 0;
            }

            // Initialize datepicker
            $('.datepicker').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true
            });

            // Initialize select2
            $('.select2').select2({
                placeholder: "Pilih",
                allowClear: true
            });

            // Inputmask for currency formatting
            $(document).on('focus', '.money-input', function() {
                $(this).inputmask({
                    alias: 'currency',
                    prefix: 'Rp ',
                    groupSeparator: '.',
                    radixPoint: ',',
                    digits: 0,
                    autoGroup: true,
                    rightAlign: false
                });
            });

            // Store original aging data when the page loads or new data is fetched
            const originalAgingData = {};

            // Function to store original aging data
            function storeOriginalAgingData() {
                $('#dt-invoice-table tbody tr').each(function() {
                    const invoiceId = $(this).data('invoice-id');
                    if (invoiceId) {
                        originalAgingData[invoiceId] = {
                            umur_0: $(this).find('td:eq(8)').text(),
                            umur_15: $(this).find('td:eq(9)').text(),
                            umur_30: $(this).find('td:eq(10)').text(),
                            umur_60: $(this).find('td:eq(11)').text(),
                            umur_60_plus: $(this).find('td:eq(12)').text()
                        };

                        // Set all aging columns to 0 initially
                        $(this).find('td:eq(8), td:eq(9), td:eq(10), td:eq(11), td:eq(12)').text('0');
                    }
                });
            }

            // Call this function when page loads
            storeOriginalAgingData();

            // Checkbox change handler - Modified to handle aging data
            $(document).on('change', '.row-check', function() {
                const row = $(this).closest('tr');
                const isChecked = $(this).prop('checked');
                const paymentInput = row.find('.payment-input');
                const invoiceId = row.data('invoice-id');

                if (isChecked) {
                    // Enable payment input and set default value to full amount
                    paymentInput.prop('disabled', false).focus();
                    const tagihan = parseFormattedCurrency(row.find('.tagihan-cell').text());
                    paymentInput.val(formatCurrency(tagihan)).trigger('input');

                    // Restore original aging data
                    if (originalAgingData[invoiceId]) {
                        row.find('td:eq(8)').text(originalAgingData[invoiceId].umur_0);
                        row.find('td:eq(9)').text(originalAgingData[invoiceId].umur_15);
                        row.find('td:eq(10)').text(originalAgingData[invoiceId].umur_30);
                        row.find('td:eq(11)').text(originalAgingData[invoiceId].umur_60);
                        row.find('td:eq(12)').text(originalAgingData[invoiceId].umur_60_plus);
                    }
                } else {
                    // Disable payment input and reset value
                    paymentInput.prop('disabled', true);
                    paymentInput.val('0').trigger('input');

                    // Reset aging data to 0
                    row.find('td:eq(8), td:eq(9), td:eq(10), td:eq(11), td:eq(12)').text('0');
                }

                calculateTotal();
            });

            // Check all checkbox handler
            $('#check-all').on('click', function() {
                const isChecked = $(this).prop('checked');
                $('.row-check').prop('checked', isChecked).trigger('change');
            });

            // Payment input change handler
            $(document).on('input', '.payment-input', function() {
                const input = $(this);
                const row = input.closest('tr');
                const maxAmount = parseFloat(input.data('max'));
                let value = parseFormattedCurrency(input.val());

                if (value > maxAmount) {
                    value = maxAmount;
                    input.val(formatCurrency(value));
                    toastr.warning('Jumlah pembayaran tidak boleh melebihi nilai tagihan');
                }

                input.attr('data-value', value);
                calculateTotal();
            });

            // Calculate total
            function calculateTotal() {
                let total = 0;
                $('.row-check:checked').each(function() {
                    const row = $(this).closest('tr');
                    const value = parseFormattedCurrency(row.find('.payment-input').val());
                    total += value;
                });

                $('#total_penerimaan').val(formatCurrency(total));
                $('#total_penerimaan_hidden').val(total);
            }

            // Search tagihan - Modified to reset and store aging data
            $('#search-btn').on('click', function() {
                const penjaminId = $('#penjamin_id').val();
                const tanggalAwal = $('#tanggal_awal').val();
                const tanggalAkhir = $('#tanggal_akhir').val();
                const invoice = $('#invoice').val();

                if (!penjaminId) {
                    toastr.warning('Silakan pilih penjamin terlebih dahulu');
                    return;
                }

                $('#total_penerimaan').val('Rp 0');
                $('#total_penerimaan_hidden').val(0);
                $('#check-all').prop('checked', false);
                $('.loading-overlay').show();

                $.ajax({
                    url: '{{ route('keuangan.pembayaran-asuransi.get-tagihan') }}',
                    type: 'GET',
                    data: {
                        penjamin_id: penjaminId,
                        tanggal_awal: tanggalAwal,
                        tanggal_akhir: tanggalAkhir,
                        invoice: invoice,
                        ajax: true
                    },
                    success: function(response) {
                        let html = '';

                        if (response.length > 0) {
                            $.each(response, function(index, item) {
                                const daysOverdue = item.days_overdue || 0;
                                const jumlah = item.jumlah || 0;
                                const sisa = (item.sisa_tagihan === null || item
                                        .sisa_tagihan == 0) &&
                                    (item.total_dibayar == null || item.total_dibayar ==
                                        0) ?
                                    jumlah : item.sisa_tagihan;

                                // Calculate aging values but don't display them yet
                                const umur_0 = daysOverdue <= 0 ? Math.abs(
                                    daysOverdue) : 0;
                                const umur_15 = (daysOverdue > -15 && daysOverdue <=
                                    0) ? Math.abs(daysOverdue) : 0;
                                const umur_30 = (daysOverdue > -30 && daysOverdue <= -
                                    15) ? Math.abs(daysOverdue) : 0;
                                const umur_60 = (daysOverdue > -60 && daysOverdue <= -
                                    30) ? Math.abs(daysOverdue) : 0;
                                const umur_60_plus = daysOverdue <= -60 ? Math.abs(
                                    daysOverdue) : 0;

                                html += '<tr data-invoice-id="' + item.id + '">' +
                                    '<td>' + (index + 1) + '</td>' +
                                    '<td>' + (item.registration.patient
                                        .medical_record_number || '-') + ' / ' +
                                    (item.registration.registration_number || '-') +
                                    '</td>' +
                                    '<td>' + (item.registration.patient.name || '-') +
                                    '</td>' +
                                    '<td>' + (item.invoice || '-') + '</td>' +
                                    '<td>' + formatDate(item.tanggal) + '</td>' +
                                    '<td>' + formatDate(item.jatuh_tempo) + '</td>' +
                                    '<td class="tagihan-cell" data-amount="' + sisa +
                                    '">' +
                                    formatCurrency(sisa) + '</td>' +
                                    '<td class="pelunasan-cell">' +
                                    '<input type="text" class="form-control payment-input money-input" ' +
                                    'data-max="' + sisa + '" data-invoice-id="' + item
                                    .id +
                                    '" name="payment_amount[' + item.id +
                                    ']" value="0" disabled>' +
                                    '</td>' +
                                    '<td>0</td>' +
                                    // Display 0 for all aging columns initially
                                    '<td>0</td>' +
                                    '<td>0</td>' +
                                    '<td>0</td>' +
                                    '<td>0</td>' +
                                    '<td><div class="custom-control custom-checkbox">' +
                                    '<input type="checkbox" class="custom-control-input row-check" ' +
                                    'name="selected_invoices[]" value="' + item.id +
                                    '" id="check-' + index + '">' +
                                    '<label class="custom-control-label" for="check-' +
                                    index + '"></label>' +
                                    '</div></td>' +
                                    '</tr>';

                                // Store actual aging values for this invoice
                                originalAgingData[item.id] = {
                                    umur_0: umur_0,
                                    umur_15: umur_15,
                                    umur_30: umur_30,
                                    umur_60: umur_60,
                                    umur_60_plus: umur_60_plus
                                };
                            });
                        } else {
                            html =
                                '<tr><td colspan="15" class="text-center">Tidak ada data tagihan tersedia.</td></tr>';
                        }

                        $('#dt-invoice-table tbody').html(html);
                        $('.loading-overlay').hide();
                    },
                    error: function() {
                        toastr.error('Terjadi kesalahan saat mengambil data');
                        $('.loading-overlay').hide();
                    }
                });
            });

            // Submit form
            $('#form-pembayaran').on('submit', function(e) {
                e.preventDefault();

                if ($('.row-check:checked').length === 0) {
                    toastr.warning('Pilih minimal satu tagihan untuk diproses');
                    return false;
                }

                const bankAccountId = $('#bank_account_id').val();
                if (!bankAccountId) {
                    toastr.warning('Silakan pilih bank account');
                    return false;
                }

                let hasZeroPayment = false;
                $('.row-check:checked').each(function() {
                    const row = $(this).closest('tr');
                    const payment = parseFormattedCurrency(row.find('.payment-input').val());
                    if (payment <= 0) {
                        hasZeroPayment = true;
                        return false;
                    }
                });

                if (hasZeroPayment) {
                    toastr.warning('Nilai pembayaran tidak boleh nol. Silakan masukkan jumlah yang valid.');
                    return false;
                }

                const paymentDetails = [];
                $('.row-check:checked').each(function() {
                    const invoiceId = $(this).val();
                    const paymentAmount = parseFormattedCurrency($(this).closest('tr').find(
                        '.payment-input').val());
                    paymentDetails.push({
                        invoice_id: invoiceId,
                        amount: paymentAmount
                    });
                });

                $('#form-pembayaran').append(
                    $('<input>').attr('type', 'hidden').attr('name', 'payment_details').val(JSON
                        .stringify(paymentDetails))
                );

                $('.loading-overlay').show();
                this.submit();
            });
        });
    </script>
@endsection
