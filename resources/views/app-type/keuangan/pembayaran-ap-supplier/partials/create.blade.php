@extends('inc.layout')
@section('title', 'Tambah Pembayaran AP Supplier')
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
    </style>
    <main id="js-page-content" role="main" class="page-content">
        <form action="{{ route('keuangan.pembayaran-ap-supplier.store') }}" method="POST" id="payment-form">
            @csrf
            {{-- Panel Form Header --}}
            <div class="row justify-content-center">
                <div class="col-xl-10">
                    <div class="panel">
                        <div class="panel-hdr">
                            <h2>Form Pembayaran AP Supplier</h2>
                        </div>
                        <div class="panel-container show">
                            <div class="panel-content">
                                {{-- ... (Form header Anda: Tanggal, Supplier, Kas/Bank, dll. sudah benar) ... --}}
                                <div class="form-row">
                                    <div class="col-md-4 mb-3">
                                        <label for="tanggal_pembayaran">Tanggal Pembayaran <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" name="tanggal_pembayaran"
                                                value="" required autocomplete="off">
                                            <div class="input-group-append"><span class="input-group-text"><i
                                                        class="fal fa-calendar"></i></span></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label" for="supplier_id">Supplier <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control select2" id="supplier_id" name="supplier_id" required>
                                            <option value="">Pilih Supplier</option>
                                            @foreach ($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}">{{ $supplier->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label" for="kas_bank_id">Kas/Bank <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control select2" id="kas_bank_id" name="kas_bank_id" required>
                                            <option value="">Pilih Kas/Bank</option>
                                            @foreach ($bank as $kas_bank)
                                                <option value="{{ $kas_bank->id }}">{{ $kas_bank->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label" for="metode_pembayaran">Metode Pembayaran <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control select2" id="metode_pembayaran" name="metode_pembayaran"
                                            required>
                                            <option value="Transfer">Transfer</option>
                                            <option value="Giro">Giro</option>
                                            <option value="Tunai">Tunai</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label" for="no_referensi">No. Referensi</label>
                                        <input type="text" class="form-control" id="no_referensi" name="no_referensi">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label" for="keterangan">Keterangan</label>
                                        <textarea class="form-control" id="keterangan" name="keterangan" rows="1"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Panel Tabel Invoice --}}
                <div class="col-xl-12 mt-4">
                    <div class="panel">
                        <div class="panel-hdr">
                            <h2>Detail Invoice yang Dibayar</h2>
                            <div class="panel-toolbar">

                            </div>
                        </div>
                        <div class="panel-container show">
                            <div class="panel-content">
                                <table class="table table-bordered table-hover" id="invoice-table">
                                    <thead class="bg-primary-600">
                                        <tr class="text-center">
                                            <th width="3%">#</th>
                                            <th>Kode AP</th>
                                            <th>No Invoice</th>
                                            <th>Nominal Hutang</th>
                                            <th>Sisa Hutang</th>
                                            <th width="15%">Pembayaran</th>
                                            <th width="12%">Potongan</th>
                                            <th width="12%">Biaya Lain</th>
                                            <th>Sisa Akhir</th>
                                        </tr>
                                    </thead>
                                    <tbody id="invoice-list">
                                        <tr id="placeholder-row">
                                            <td colspan="9" class="text-center text-muted">Pilih supplier untuk memulai.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                {{-- BAGIAN TOTAL BARU --}}
                                <div class="row mt-4 justify-content-end">
                                    <div class="col-lg-5">
                                        <div class="total-section">
                                            <table class="table table-sm table-borderless">
                                                <tbody>
                                                    <tr>
                                                        <td class="font-weight-bold">Total Pembayaran Invoice</td>
                                                        <td class="text-right font-weight-bold"
                                                            id="total-pembayaran-display">Rp 0</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="font-weight-bold">Total Potongan</td>
                                                        <td class="text-right font-weight-bold" id="total-potongan-display">
                                                            Rp 0</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="font-weight-bold">Total Biaya Lainnya</td>
                                                        <td class="text-right font-weight-bold"
                                                            id="total-biaya-lain-display">Rp 0</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="font-weight-bold">Pembulatan</td>
                                                        <td>
                                                            <input type="text" name="pembulatan" id="pembulatan-input"
                                                                class="form-control form-control-sm text-right"
                                                                value="0">
                                                        </td>
                                                    </tr>
                                                    <tr class="border-top">
                                                        <td class="font-weight-bold h5">Grand Total</td>
                                                        <td class="text-right font-weight-bold h5"
                                                            id="grand-total-display">Rp 0</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-12 text-right">
                                        <a href="{{ route('keuangan.pembayaran-ap-supplier.index') }}"
                                            class="btn btn-secondary">Batal</a>
                                        <button type="button" class="btn bg-warning text-white" id="btn-pilih-invoice"
                                            disabled>
                                            <i class="fal fa-file-search me-1"></i> Pilih Invoice
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fal fa-save mr-1"></i> Simpan Pembayaran
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
    {{-- Plugin CSS & JS --}}
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/formplugins/sweetalert2/sweetalert2.bundle.js"></script>
    <script src="/js/notifications/toastr/toastr.js"></script>
    <link rel="stylesheet" media="screen, print" href="/css/notifications/toastr/toastr.css">

    <script>
        $(document).ready(function() {
            // 1. INISIALISASI PLUGIN & VARIABEL GLOBAL
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true,
                orientation: 'bottom auto'
            });
            $('.select2').select2({
                placeholder: "Pilih opsi"
            });

            let selectedInvoices = [];
            let paymentPopupWindow = null;

            // 2. FUNGSI HELPER
            function parseCurrency(value) {
                if (typeof value === 'number') return value;
                if (typeof value !== 'string') return 0;
                // Fungsi ini sekarang hanya untuk USER INPUT yang menggunakan format "1.000.000"
                return parseFloat(String(value).replace(/\./g, '').replace(/,/g, '.').replace(/[^-0-9.]/g, '')) ||
                    0;
            }

            function formatCurrency(number) {
                // Fungsi ini menerima ANGKA dan memformatnya ke string "1.000.000"
                return new Intl.NumberFormat('id-ID').format(Math.round(parseCurrency(number)));
            }

            function displayCurrency(number) {
                // Fungsi ini menerima ANGKA dan mengembalikannya sebagai "Rp 1.000.000"
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(number));
            }

            // 3. FUNGSI UTAMA KALKULASI & RENDER

            /**
             * Menghitung Sisa Akhir untuk satu baris invoice.
             */
            function updateRowCalculation($row) {
                const sisaHutangAwal = parseCurrency($row.find('.sisa-hutang-awal-display').text());
                const pembayaran = parseCurrency($row.find('.payment-input').val());

                let sisaHutangAkhir = sisaHutangAwal - pembayaran;
                sisaHutangAkhir = Math.max(0, sisaHutangAkhir);

                $row.find('.sisa-akhir-display').text(displayCurrency(sisaHutangAkhir));
            }

            /**
             * Menghitung dan menampilkan semua total di bagian bawah.
             */
            function updateAllTotals() {
                let totalPembayaranInvoice = 0;
                let totalPotongan = 0;
                let totalBiayaLain = 0;

                $('#invoice-list tr[data-id]').each(function() {
                    totalPembayaranInvoice += parseCurrency($(this).find('.payment-input').val());
                    totalPotongan += parseCurrency($(this).find('.potongan-input').val());
                    totalBiayaLain += parseCurrency($(this).find('.biaya-lain-input').val());
                });

                const pembulatan = parseCurrency($('#pembulatan-input').val());
                const grandTotal = (totalPembayaranInvoice + totalBiayaLain - totalPotongan) + pembulatan;

                $('#total-pembayaran-display').text(displayCurrency(totalPembayaranInvoice));
                $('#total-potongan-display').text(displayCurrency(totalPotongan));
                $('#total-biaya-lain-display').text(displayCurrency(totalBiayaLain));
                $('#grand-total-display').text(displayCurrency(grandTotal));
            }

            /**
             * Merender ulang seluruh tabel invoice.
             */
            function renderInvoiceTable() {
                const list = $('#invoice-list');
                list.empty();
                $('#payment-form input[name^="invoices"]').remove();

                if (selectedInvoices.length === 0) {
                    list.html(
                        '<tr id="placeholder-row"><td colspan="9" class="text-center text-muted">Belum ada invoice dipilih.</td></tr>'
                    );
                } else {
                    selectedInvoices.forEach((invoice, index) => {
                        // ================== BAGIAN PERBAIKAN ADA DI SINI ==================
                        // Gunakan parseFloat() untuk mengubah data dari server menjadi angka.
                        // Ini akan menangani format "279000000.00" atau 279000000 dengan benar.
                        const grandTotal = parseFloat(invoice.grand_total) || 0;
                        const sisaHutang = parseFloat(invoice.sisa_hutang) || 0;
                        // ===================================================================

                        const row = `
                        <tr data-id="${invoice.id}">
                            <input type="hidden" name="invoices[${index}][id]" value="${invoice.id}">
                            <td class="text-center"><button type="button" class="btn btn-xs btn-danger btn-remove-invoice" title="Hapus"><i class="fal fa-times"></i></button></td>
                            <td>${invoice.kode_ap}</td>
                            <td>${invoice.no_invoice_supplier}</td>
                            <td class="text-right">${displayCurrency(grandTotal)}</td>
                            <td class="text-right sisa-hutang-awal-display">${displayCurrency(sisaHutang)}</td>
                            <td><input type="text" name="invoices[${index}][pembayaran]" class="form-control form-control-sm text-right payment-input" value="${formatCurrency(sisaHutang)}"></td>
                            <td><input type="text" name="invoices[${index}][potongan]" class="form-control form-control-sm text-right potongan-input" value="0"></td>
                            <td><input type="text" name="invoices[${index}][biaya_lain]" class="form-control form-control-sm text-right biaya-lain-input" value="0"></td>
                            <td class="text-right font-weight-bold sisa-akhir-display">${displayCurrency(0)}</td>
                        </tr>`;
                        list.append(row);
                    });
                }
                $('#invoice-list tr[data-id]').each(function() {
                    updateRowCalculation($(this));
                });
                updateAllTotals();
            }

            // 4. EVENT LISTENERS
            $('#supplier_id').on('change', function() {
                const supplierId = $(this).val();
                $('#btn-pilih-invoice').prop('disabled', !supplierId);
                if (selectedInvoices.length > 0) {
                    Swal.fire({
                        title: 'Ganti Supplier?',
                        text: "Ini akan menghapus semua invoice yang dipilih. Lanjutkan?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Lanjutkan!',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            selectedInvoices = [];
                            renderInvoiceTable();
                        } else {
                            const oldSupplierId = selectedInvoices.length > 0 ? selectedInvoices[0]
                                .supplier_id : '';
                            $(this).val(oldSupplierId).trigger('change.select2');
                            $('#btn-pilih-invoice').prop('disabled', !oldSupplierId);
                        }
                    });
                }
            });

            $('#btn-pilih-invoice').on('click', function() {
                const supplierId = $('#supplier_id').val();
                if (!supplierId) return;
                const url =
                    `{{ route('keuangan.pembayaran-ap-supplier.pilihInvoice') }}?supplier_id=${supplierId}`;
                if (paymentPopupWindow && !paymentPopupWindow.closed) {
                    paymentPopupWindow.focus();
                } else {
                    paymentPopupWindow = window.open(url, 'PilihInvoicePopup',
                        'width=1400,height=800,scrollbars=yes');
                }
            });

            window.addEventListener('message', function(event) {
                if (event.origin !== window.location.origin || !event.data || event.data.type !==
                    'INVOICE_SELECTED') return;
                const newInvoices = event.data.data;
                let addedCount = 0;
                newInvoices.forEach(newInv => {
                    if (!selectedInvoices.some(ex => ex.id === newInv.id)) {
                        newInv.supplier_id = $('#supplier_id').val();
                        selectedInvoices.push(newInv);
                        addedCount++;
                    }
                });
                if (addedCount > 0) {
                    toastr.success(`${addedCount} invoice berhasil ditambahkan.`);
                    renderInvoiceTable();
                }
                if (paymentPopupWindow) paymentPopupWindow.close();
            });

            $('#invoice-list').on('click', '.btn-remove-invoice', function() {
                selectedInvoices = selectedInvoices.filter(inv => inv.id !== $(this).closest('tr').data(
                    'id'));
                toastr.warning('Satu invoice telah dihapus.');
                renderInvoiceTable();
            });

            $('#payment-form').on('input', '.payment-input, .potongan-input, .biaya-lain-input, #pembulatan-input',
                function() {
                    const $row = $(this).closest('tr[data-id]');
                    if ($row.length) {
                        updateRowCalculation($row);
                    }
                    updateAllTotals();
                });

            $('#payment-form').on('blur', '.payment-input, .potongan-input, .biaya-lain-input, #pembulatan-input',
                function() {
                    $(this).val(formatCurrency($(this).val()));
                });

            $('#payment-form').on('submit', function(e) {
                if (selectedInvoices.length === 0) {
                    e.preventDefault();
                    Swal.fire('Validasi Gagal', 'Pilih setidaknya satu invoice.', 'error');
                    return;
                }

                let isValid = true;
                $('#invoice-list tr[data-id]').each(function() {
                    const sisaHutangAwal = parseCurrency($(this).find('.sisa-hutang-awal-display')
                        .text());
                    const pembayaran = parseCurrency($(this).find('.payment-input').val());

                    if (pembayaran > sisaHutangAwal + 0.01) {
                        const kodeAp = $(this).find('td:nth-child(2)').text();
                        Swal.fire('Validasi Gagal',
                            `Pembayaran untuk invoice ${kodeAp} tidak boleh melebihi sisa hutang.`,
                            'error');
                        isValid = false;
                        return false;
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    return;
                }

                $('.payment-input, .potongan-input, .biaya-lain-input, #pembulatan-input').each(function() {
                    $(this).val(parseCurrency($(this).val()));
                });
            });

            $(window).on('beforeunload', function() {
                if (paymentPopupWindow && !paymentPopupWindow.closed) paymentPopupWindow.close();
            });


            // 5. INISIALISASI HALAMAN
            renderInvoiceTable();
        });
    </script>
@endsection
