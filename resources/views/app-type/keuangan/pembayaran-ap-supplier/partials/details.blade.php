    @extends('inc.layout')
    @section('title', 'Edit Pembayaran AP Supplier')
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

            /* Tambahan styling untuk form */
            .payment-input,
            .potongan-input {
                min-width: 100px;
            }

            .action-buttons {
                margin-top: 20px;
                padding-top: 15px;
                border-top: 1px solid #eee;
            }

            .total-section {
                background-color: #f8f9fa;
                padding: 15px;
                border-radius: 4px;
                margin-bottom: 20px;
            }
        </style>
        <main id="js-page-content" role="main" class="page-content">
            <form action="{{ route('keuangan.pembayaran-ap-supplier.update', $payment->id) }}" method="POST"
                id="payment-form">
                @csrf
                @method('PUT')

                {{-- Panel Form Header --}}
                <div class="row justify-content-center">
                    <div class="col-xl-10">
                        <div class="panel">
                            <div class="panel-hdr">
                                <h2>Edit Pembayaran | {{ $payment->kode_pembayaran }}</h2>
                            </div>
                            <div class="panel-container show">
                                <div class="panel-content">
                                    {{-- ISI SEMUA FIELD DENGAN DATA DARI OBJEK $payment --}}
                                    <div class="form-row">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Tanggal Pembayaran</label>
                                            <input type="text" class="form-control datepicker" name="tanggal_pembayaran"
                                                value="{{ old('tanggal_pembayaran', $payment->tanggal_pembayaran->format('Y-m-d')) }}"
                                                required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Supplier</label>
                                            <select class="form-control select2" name="supplier_id" required disabled>
                                                @foreach ($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}"
                                                        {{ $payment->supplier_id == $supplier->id ? 'selected' : '' }}>
                                                        {{ $supplier->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" name="supplier_id" value="{{ $payment->supplier_id }}">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Kas/Bank</label>
                                            <select class="form-control select2" name="kas_bank_id" required>
                                                @foreach ($bank as $kas_bank)
                                                    <option value="{{ $kas_bank->id }}"
                                                        {{ $payment->kas_bank_id == $kas_bank->id ? 'selected' : '' }}>
                                                        {{ $kas_bank->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Metode Pembayaran</label>
                                            <select class="form-control select2" name="metode_pembayaran" required>
                                                <option value="Transfer"
                                                    {{ $payment->metode_pembayaran == 'Transfer' ? 'selected' : '' }}>
                                                    Transfer</option>
                                                <option value="Giro"
                                                    {{ $payment->metode_pembayaran == 'Giro' ? 'selected' : '' }}>Giro
                                                </option>
                                                <option value="Tunai"
                                                    {{ $payment->metode_pembayaran == 'Tunai' ? 'selected' : '' }}>Tunai
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">No. Referensi</label>
                                            <input type="text" class="form-control" name="no_referensi"
                                                value="{{ old('no_referensi', $payment->no_referensi) }}">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Keterangan</label>
                                            <textarea class="form-control" name="keterangan" rows="1">{{ old('keterangan', $payment->keterangan) }}</textarea>
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
                            </div>
                            <div class="panel-container show">
                                <div class="panel-content">
                                    <table class="table table-bordered table-hover" id="invoice-table">
                                        <thead class="bg-primary-600">
                                            <tr class="text-center">
                                                <th>#</th>
                                                <th>Kode AP</th>
                                                <th>No Invoice</th>
                                                <th>Nominal </th>
                                                <th>Hutang</th>
                                                <th width="15%">Pembayaran</th>
                                                <th width="12%">Potongan</th>
                                                <th width="12%">Biaya Lain</th>
                                                <th>Sisa Hutang</th>
                                            </tr>
                                        </thead>
                                        <tbody id="invoice-list">
                                            <tr id="placeholder-row">
                                                <td colspan="9" class="text-center text-muted">Memuat data...</td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    {{-- Bagian Total --}}
                                    <div class="row mt-4 justify-content-end">
                                        <div class="col-lg-5">
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
                                                                value="{{ old('pembulatan', $payment->pembulatan) }}">
                                                        </td>
                                                    </tr>
                                                    <tr class="border-top">
                                                        <td class="font-weight-bold h5">Grand Total</td>
                                                        <td class="text-right font-weight-bold h5" id="grand-total-display">
                                                            Rp 0</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    {{-- Tombol Aksi --}}
                                    <div class="row mt-4">
                                        <div class="col-md-12 text-right">
                                            <a href="{{ route('keuangan.pembayaran-ap-supplier.index') }}"
                                                class="btn btn-secondary">Kembali</a>
                                            <button type="button" class="btn btn-danger" id="btn-cancel-payment"
                                                data-url="{{ route('keuangan.pembayaran-ap-supplier.destroy', $payment->id) }}">
                                                <i class="fal fa-trash-alt mr-1"></i>
                                                Batalkan Pembayaran
                                            </button>

                                            <button type="button" class="btn bg-warning text-white"
                                                id="btn-pilih-invoice">Pilih Invoice Baru</button>
                                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>

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
                // =========================================================================
                // 1. INISIALISASI PLUGIN & VARIABEL GLOBAL
                // =========================================================================
                $('.datepicker').datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true,
                    todayHighlight: true,
                    orientation: 'bottom auto'
                });
                $('.select2').select2({
                    placeholder: "Pilih opsi"
                });

                // DATA AWAL: Diisi dari JSON yang dikirim oleh Controller 'show'/'edit'
                let selectedInvoices = {!! $selectedInvoicesJson !!};
                let paymentPopupWindow = null;

                // =========================================================================
                // 2. FUNGSI HELPER (SADAR DESIMAL & MATA UANG)
                // =========================================================================

                function parseCurrency(value) {
                    if (typeof value === 'number') return value;
                    if (typeof value !== 'string') return 0;
                    return parseFloat(String(value).replace(/\./g, '').replace(/,/g, '.').replace(/[^-0-9.]/g, '')) ||
                        0;
                }

                function formatCurrency(number) {
                    return new Intl.NumberFormat('id-ID').format(Math.round(parseCurrency(number)));
                }

                function displayCurrency(number) {
                    return 'Rp ' + formatCurrency(number);
                }

                // =========================================================================
                // 3. FUNGSI UTAMA KALKULASI & RENDER TAMPILAN
                // =========================================================================

                /**
                 * Menghitung Sisa Akhir untuk satu baris invoice.
                 * Aturan: Sisa Hutang Awal - HANYA Pembayaran
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
                 * Aturan: Grand Total = (Pembayaran + Biaya Lain - Potongan) + Pembulatan
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
                 * Merender ulang seluruh tabel invoice berdasarkan array `selectedInvoices`.
                 */
                function renderInvoiceTable() {
                    const list = $('#invoice-list');
                    list.empty();
                    $('#payment-form input[name^="invoices"]').remove();

                    if (selectedInvoices.length === 0) {
                        list.html(
                            '<tr id="placeholder-row"><td colspan="9" class="text-center text-muted">Tidak ada invoice. Klik "Pilih Invoice Baru" untuk menambahkan.</td></tr>'
                        );
                    } else {
                        selectedInvoices.forEach((invoice, index) => {
                            const row = `
                        <tr data-id="${invoice.id}">
                            {{-- Input tersembunyi untuk dikirim ke backend --}}
                            <input type="hidden" name="invoices[${index}][id]" value="${invoice.id}">

                            <td class="text-center"><button type="button" class="btn btn-xs btn-danger btn-remove-invoice" title="Hapus"><i class="fal fa-times"></i></button></td>
                            <td>${invoice.kode_ap}</td>
                            <td>${invoice.no_invoice_supplier}</td>
                            <td class="text-right">${displayCurrency(invoice.grand_total)}</td>
                            <td class="text-right sisa-hutang-awal-display">${displayCurrency(invoice.sisa_hutang)}</td>
                            <td><input type="text" name="invoices[${index}][pembayaran]" class="form-control form-control-sm text-right payment-input" value="${formatCurrency(invoice.pembayaran)}"></td>
                            <td><input type="text" name="invoices[${index}][potongan]" class="form-control form-control-sm text-right potongan-input" value="${formatCurrency(invoice.potongan)}"></td>
                            <td><input type="text" name="invoices[${index}][biaya_lain]" class="form-control form-control-sm text-right biaya-lain-input" value="${formatCurrency(invoice.biaya_lain)}"></td>
                            <td class="text-right font-weight-bold sisa-akhir-display">${displayCurrency(0)}</td>
                        </tr>`;
                            list.append(row);
                        });
                    }

                    // Panggil kalkulasi ulang setelah tabel di-render
                    $('#invoice-list tr[data-id]').each(function() {
                        updateRowCalculation($(this));
                    });
                    updateAllTotals();
                }

                // =========================================================================
                // 4. EVENT LISTENERS
                // =========================================================================

                // Tombol "Pilih Invoice Baru" (Supplier sudah di-disable, jadi tidak perlu event change)
                $('#btn-pilih-invoice').on('click', function() {
                    const supplierId = $('input[name="supplier_id"]').val(); // Ambil dari hidden input
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

                // Mendengarkan data dari popup window untuk menambah invoice baru
                window.addEventListener('message', function(event) {
                    if (event.origin !== window.location.origin || !event.data || event.data.type !==
                        'INVOICE_SELECTED') {
                        return;
                    }

                    const newInvoices = event.data.data;
                    let addedCount = 0;
                    newInvoices.forEach(newInv => {
                        if (!selectedInvoices.some(ex => ex.id === newInv.id)) {
                            // Saat menambah baru di halaman edit, nilai-nilai awalnya 0
                            newInv.pembayaran = newInv
                                .sisa_hutang; // Set default pembayaran = sisa hutang
                            newInv.potongan = 0;
                            newInv.biaya_lain = 0;
                            selectedInvoices.push(newInv);
                            addedCount++;
                        }
                    });

                    if (addedCount > 0) {
                        toastr.success(`${addedCount} invoice baru berhasil ditambahkan.`);
                        renderInvoiceTable();
                    } else {
                        toastr.info('Tidak ada invoice baru yang ditambahkan (mungkin sudah ada).');
                    }

                    if (paymentPopupWindow) paymentPopupWindow.close();
                });

                // Menghapus baris invoice
                $('#invoice-list').on('click', '.btn-remove-invoice', function() {
                    const invoiceId = $(this).closest('tr').data('id');
                    selectedInvoices = selectedInvoices.filter(inv => inv.id !== invoiceId);
                    toastr.warning('Satu invoice telah dihapus dari pembayaran.');
                    renderInvoiceTable();
                });

                // Mengetik di input numerik (delegasi event)
                $('#payment-form').on('input', '.payment-input, .potongan-input, .biaya-lain-input, #pembulatan-input',
                    function() {
                        const $row = $(this).closest('tr[data-id]');
                        if ($row.length) {
                            updateRowCalculation($row);
                        }
                        updateAllTotals();
                    });

                // Format ulang setelah selesai mengetik
                $('#payment-form').on('blur', '.payment-input, .potongan-input, .biaya-lain-input, #pembulatan-input',
                    function() {
                        $(this).val(formatCurrency($(this).val()));
                    });

                // Sebelum form disubmit
                $('#payment-form').on('submit', function(e) {
                    if (selectedInvoices.length === 0) {
                        e.preventDefault();
                        Swal.fire('Validasi Gagal', 'Harus ada minimal satu invoice untuk dibayar.', 'error');
                        return;
                    }

                    let isValid = true;
                    $('#invoice-list tr[data-id]').each(function() {
                        const sisaHutangAwal = parseCurrency($(this).find('.sisa-hutang-awal-display')
                            .text());
                        const pembayaran = parseCurrency($(this).find('.payment-input').val());

                        // VALIDASI FINAL: Hanya pembayaran yang diperiksa terhadap sisa hutang.
                        if (pembayaran > sisaHutangAwal + 0.01) {
                            const kodeAp = $(this).find('td:nth-child(2)').text();
                            Swal.fire('Validasi Gagal',
                                `Pembayaran untuk invoice ${kodeAp} tidak boleh melebihi sisa hutang awal.`,
                                'error');
                            isValid = false;
                            return false; // Hentikan loop .each
                        }
                    });

                    if (!isValid) {
                        e.preventDefault();
                        return;
                    }

                    // Ubah semua nilai yang diformat kembali ke angka murni sebelum submit
                    $('.payment-input, .potongan-input, .biaya-lain-input, #pembulatan-input').each(function() {
                        $(this).val(parseCurrency($(this).val()));
                    });
                });

                // Menutup popup jika halaman utama ditutup/refresh
                $(window).on('beforeunload', function() {
                    if (paymentPopupWindow && !paymentPopupWindow.closed) {
                        paymentPopupWindow.close();
                    }
                });

                // =========================================================================
                // 5. INISIALISASI HALAMAN
                // =========================================================================
                renderInvoiceTable();
                // Format input pembulatan saat pertama kali load
                $('#pembulatan-input').val(formatCurrency($('#pembulatan-input').val()));
            });


            $('#btn-cancel-payment').on('click', function() {
                const cancelUrl = $(this).data('url');

                Swal.fire({
                    title: 'Anda Yakin?',
                    text: "Tindakan ini akan membatalkan pembayaran dan mengembalikan sisa hutang invoice. Ini tidak bisa diurungkan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Batalkan Pembayaran!',
                    cancelButtonText: 'Tidak'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Tampilkan loading overlay jika ada
                        // $('.loading-overlay').show(); 

                        $.ajax({
                            url: cancelUrl,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}' // Penting untuk keamanan CSRF
                            },
                            success: function(response) {
                                if (response.success) {
                                    toastr.success(response.message);
                                    // Redirect ke halaman index setelah beberapa saat
                                    setTimeout(() => {
                                        window.location.href = response.redirect_url;
                                    }, 1500);
                                } else {
                                    // Sembunyikan loading overlay
                                    Swal.fire('Gagal!', response.message || 'Terjadi kesalahan.',
                                        'error');
                                }
                            },
                            error: function(xhr) {
                                // Sembunyikan loading overlay
                                const errorMsg = xhr.responseJSON ? xhr.responseJSON.message :
                                    'Gagal menghubungi server.';
                                Swal.fire('Error!', errorMsg, 'error');
                            }
                        });
                    }
                });
            });
        </script>
    @endsection
