        @extends('inc.layout')
        @section('title', 'Tambah AP Supplier')
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

                /* Efek hover untuk child row */
                /* Warna berbeda untuk child row */
                #dt-basic-example tbody tr.child-row:hover {
                    background-color: #f1f1f1;
                }

                .diskon-input,
                .biaya-lain-input {
                    width: 100%;
                    min-width: 80px;
                    padding: 0.25rem 0.5rem;
                    text-align: right;
                    border: 1px solid #d1d1d1;
                    border-radius: 3px;
                    background-color: #fff;
                }

                .diskon-input:focus,
                .biaya-lain-input:focus {
                    border-color: #5c9bd1;
                    box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
                }

                #grn-table tbody tr td {
                    vertical-align: middle;
                }

                #grn-table .form-control-sm {
                    height: calc(1.5em + 0.5rem + 2px);
                    /* padding: 0.25rem 0.5rem; */
                    /* font-size: 0.875rem; */
                    line-height: 1.5;
                }
            </style>
            <main id="js-page-content" role="main" class="page-content">
                {{-- FIX: Route form action disesuaikan dengan standar route Anda --}}
                <form action="{{ route('keuangan.ap-supplier.store') }}" method="post" id="create-ap-form">
                    @csrf
                    <div class="row justify-content-center align-item-center">
                        {{-- Panel 1: Form Input Utama (col-xl-10) --}}
                        <div class="col-xl-10">
                            <div class="panel">
                                <div class="panel-hdr">
                                    <h2>Form AP Supplier</h2>
                                </div>
                                <div class="panel-container show">
                                    <div class="panel-content">
                                        <div class="form-row">
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Tanggal AP <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control form-control-sm datepicker"
                                                        name="tanggal_ap" value="{{ old('tanggal_ap', date('d-m-Y')) }}"
                                                        autocomplete="off" required>
                                                    <div class="input-group-append"><span class="input-group-text fs-sm"><i
                                                                class="fal fa-calendar"></i></span></div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Due Date <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control form-control-sm datepicker"
                                                        name="due_date"
                                                        value="{{ old('due_date', date('d-m-Y', strtotime('+1 days'))) }}"
                                                        autocomplete="off" required>
                                                    <div class="input-group-append"><span class="input-group-text fs-sm"><i
                                                                class="fal fa-calendar"></i></span></div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Tanggal Faktur Pajak</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control form-control-sm datepicker"
                                                        name="tanggal_faktur_pajak"
                                                        value="{{ old('tanggal_faktur_pajak', date('d-m-Y')) }}"
                                                        autocomplete="off">
                                                    <div class="input-group-append"><span class="input-group-text fs-sm"><i
                                                                class="fal fa-calendar"></i></span></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Supplier <span
                                                        class="text-danger">*</span></label>
                                                <select class="form-control form-control-sm select2" id="supplier_id"
                                                    name="supplier_id" required>
                                                    <option value="" disabled selected>Pilih Supplier</option>
                                                    @foreach ($suppliers as $supplier)
                                                        <option value="{{ $supplier->id }}"
                                                            data-ppn="{{ $supplier->ppn ?? 0 }}"
                                                            {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                                            {{ $supplier->nama }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">No Invoice Supplier <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-control-sm" name="no_invoice"
                                                    value="{{ old('no_invoice') }}" required>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">No Faktur Pajak</label>
                                                <input type="text" class="form-control" name="no_faktur_pajak"
                                                    value="{{ old('no_faktur_pajak') }}" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Panel 2: Tabel dan Total (col-xl-12) --}}
                        <div class="col-xl-12 mt-4">
                            <div class="panel">
                                <div class="panel-hdr">
                                    <h2>Detail Penerimaan Barang (GRN)</h2>
                                </div>
                                <div class="panel-container show">
                                    <div class="panel-content">
                                        <table class="table table-bordered table-hover" id="grn-table">
                                            <thead class="bg-primary-600 text-white">
                                                <tr class="text-center">
                                                    <th width="3%">#</th>
                                                    <th width="10%">Tgl GRN</th>
                                                    <th width="12%">No. GRN</th>
                                                    <th width="13%">No. PO</th>
                                                    <th width="29%">Keterangan</th>
                                                    <th width="10%" class="">Diskon</th>
                                                    <th width="10%" class="">Biaya Lainnya</th>
                                                    <th width="13%" class="">Nominal</th>
                                                </tr>
                                            </thead>
                                            <tbody id="grn-selected-list">
                                                {{-- Penanganan data kosong dilakukan oleh JavaScript karena data di-load secara dinamis --}}
                                                <tr id="grn-placeholder">
                                                    <td colspan="8" class="text-center text-muted py-4">Belum ada GRN
                                                        yang
                                                        dipilih. Silakan pilih supplier terlebih dahulu.</td>
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr class="font-weight-bold" style="background-color: #f3f3f3;">
                                                    <td colspan="5" class="text-center">Total Item</td>
                                                    <td id="total-diskon-item" class="text-center">0,00</td>
                                                    <td id="total-biaya-lain" class="text-center">0,00</td>
                                                    <td id="total-nominal" class="text-center">0,00</td>
                                                </tr>
                                            </tfoot>
                                        </table>

                                        <div class="row mt-4">
                                            <div class="col-md-7">
                                                <label class="form-label">Notes</label>
                                                <textarea class="form-control" name="notes" rows="4" placeholder="Catatan tambahan...">{{ old('notes') }}</textarea>
                                                <label class="mt-3 form-label">Kelengkapan Dokumen</label>
                                                <div class="d-flex flex-wrap">
                                                    <div class="form-check mr-3"><input class="form-check-input"
                                                            type="checkbox" name="ada_kwitansi" value="1"
                                                            @if (old('ada_kwitansi', true)) checked @endif> Kwitansi</div>
                                                    <div class="form-check mr-3"><input class="form-check-input"
                                                            type="checkbox" name="ada_faktur_pajak" value="1"
                                                            @if (old('ada_faktur_pajak', true)) checked @endif> Faktur Pajak
                                                    </div>
                                                    <div class="form-check mr-3"><input class="form-check-input"
                                                            type="checkbox" name="ada_surat_jalan" value="1"
                                                            @if (old('ada_surat_jalan')) checked @endif> Surat Jalan
                                                    </div>
                                                    <div class="form-check mr-3"><input class="form-check-input"
                                                            type="checkbox" name="ada_salinan_po" value="1"
                                                            @if (old('ada_salinan_po', true)) checked @endif> Salinan PO
                                                    </div>
                                                    <div class="form-check mr-3"><input class="form-check-input"
                                                            type="checkbox" name="ada_tanda_terima_barang" value="1"
                                                            @if (old('ada_tanda_terima_barang', true)) checked @endif> Tanda Terima
                                                    </div>
                                                    <div class="form-check"><input class="form-check-input"
                                                            type="checkbox" name="ada_berita_acara" value="1"
                                                            @if (old('ada_berita_acara')) checked @endif> Berita Acara
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-5">
                                                <table class="table table-sm table-borderless table-calculation">
                                                    <tbody>
                                                        <tr id="row-faktur-pajak-retur" style="display: none;">
                                                            <td>Faktur Pajak Retur</td>
                                                            <td>
                                                                <input type="text" name="faktur_pajak_retur"
                                                                    class="form-control"
                                                                    placeholder="No. Faktur Pajak Retur">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                Retur
                                                                <!-- IKON BARU DITAMBAHKAN DI SINI -->
                                                                <a href="#" id="btn-open-retur-popup"
                                                                    class="ml-2" title="Pilih Retur Barang">
                                                                    <i
                                                                        class="ni ni-calendar-fine"></i><!-- Icon dari Font Awesome -->
                                                                </a>
                                                            </td>
                                                            <td>
                                                                <input type="text" name="retur" id="retur"
                                                                    class="form-control text-right calculation-field"
                                                                    value="{{ old('retur', 0) }}">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Diskon Final</td>
                                                            <td><input type="text" name="diskon_final"
                                                                    id="diskon_final"
                                                                    class="form-control text-right calculation-field"
                                                                    value="{{ old('diskon_final', 0) }}"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>PPN %</td>
                                                            <td>
                                                                <div class="input-group">
                                                                    <!-- Persen di start -->
                                                                    <input type="text" name="ppn_persen"
                                                                        id="ppn_persen"
                                                                        class="form-control text-end calculation-field"
                                                                        style="max-width: 60px;"
                                                                        value="{{ old('ppn', 0) }}">

                                                                    <!-- Nominal di end -->
                                                                    <input type="text" id="ppn_nominal"
                                                                        class="form-control text-end text-right bg-light"
                                                                        readonly>
                                                                </div>
                                                            </td>

                                                        </tr>
                                                        <tr>
                                                            <td>Materai</td>
                                                            <td><input type="text" name="materai" id="materai"
                                                                    class="form-control text-right calculation-field"
                                                                    value="{{ old('materai', 0) }}"></td>
                                                        </tr>
                                                        <tr class="font-weight-bold" style="background-color: #f3f3f3;">
                                                            <td>Grand Total</td>
                                                            <td class=" text-right"><span id="grand_total">0,00</span>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="panel-content border-faded border-left-0 border-right-0 border-bottom-0 d-flex flex-row align-items-center">
                                        <a href="{{ route('keuangan.ap-supplier.index') }}"
                                            class="btn btn-secondary">Back</a>
                                        <div class="ml-auto">
                                            <button type="button" class="btn btn-info" id="btn-index-grn" disabled>
                                                <i class="fal fa-plus-circle mr-1"></i> Pilih GRN
                                            </button>
                                            <button type="submit" class="btn btn-primary ml-2">Simpan</button>
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
            <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
            <script src="/js/formplugins/select2/select2.bundle.js"></script>
            <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
            <script src="/js/dependency/moment/moment.js"></script>
            <script src="/js/formplugins/sweetalert2/sweetalert2.bundle.js"></script>
            <script src="/js/notifications/toastr/toastr.js"></script>
            <link rel="stylesheet" media="screen, print" href="/css/notifications/toastr/toastr.css">

            {{-- Script Khusus Halaman Ini --}}
            <script>
                $(document).ready(function() {
                    // =========================================================================
                    // INISIALISASI PLUGIN & VARIABEL GLOBAL
                    // =========================================================================
                    $('.datepicker').datepicker({
                        format: 'dd-mm-yyyy',
                        autoclose: true,
                        todayHighlight: true,
                        language: 'id',
                        orientation: 'bottom auto',
                        templates: {
                            leftArrow: '<i class="fal fa-angle-left"></i>',
                            rightArrow: '<i class="fal fa-angle-right"></i>'
                        }
                    });

                    $('.select2').select2({
                        placeholder: "Pilih opsi",
                        allowClear: true
                    });

                    let selectedGrns = [];
                    let grnPopupWindow = null;
                    let selectedReturs = [];
                    let returPopupWindow = null;
                    let defaultPpnPersen = 0;

                    // =========================================================================
                    // FUNGSI HELPER
                    // =========================================================================
                    function formatCurrency(number) {
                        return new Intl.NumberFormat('id-ID', {
                            minimumFractionDigits: 0,
                            maximumFractionDigits: 0
                        }).format(number);
                    }

                    function formatDecimal(number) {
                        return new Intl.NumberFormat('id-ID', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }).format(number);
                    }

                    function parseCurrency(value) {
                        return parseFloat(String(value).replace(/[^0-9,-]+/g, "").replace(",", ".")) || 0;
                    }

                    // =========================================================================
                    // FUNGSI UTAMA
                    // =========================================================================
                    function calculateTotals() {
                        // Calculate subtotal from all GRNs (original amount)
                        let subtotal = selectedGrns.reduce((total, grn) => total + (grn.total_nilai_barang || 0), 0);

                        // Calculate total discounts from all GRNs
                        let totalItemDiskon = selectedGrns.reduce((total, grn) => total + (grn.diskon || 0), 0);

                        // Calculate total other costs from all GRNs
                        let totalItemBiayaLain = selectedGrns.reduce((total, grn) => total + (grn.biaya_lainnya || 0), 0);

                        // Get values from form inputs
                        const retur = parseCurrency($('#retur').val());
                        const diskonFinal = parseCurrency($('#diskon_final').val());
                        const materai = parseCurrency($('#materai').val());
                        let ppnPersen = parseCurrency($('#ppn_persen').val());

                        // Make sure PPN percentage is not lost
                        if (ppnPersen === 0 && selectedGrns.length > 0) {
                            ppnPersen = defaultPpnPersen;
                        }

                        // Calculate subtotal after item discounts and other costs
                        const subtotalSetelahDiskon = subtotal - totalItemDiskon + totalItemBiayaLain;

                        // Calculate DPP (after retur and final discount)
                        const dpp = subtotalSetelahDiskon - retur - diskonFinal;
                        const finalDpp = dpp > 0 ? dpp : 0;
                        const ppnNominal = finalDpp * (ppnPersen / 100);
                        const grandTotal = finalDpp + ppnNominal + materai;

                        // Update display
                        $('#total-diskon-item').text(formatCurrency(totalItemDiskon));
                        $('#total-biaya-lain').text(formatCurrency(totalItemBiayaLain));
                        $('#total-nominal').text(formatCurrency(
                            subtotal)); // Show original subtotal without discounts/other costs
                        $('#ppn_nominal').val(formatDecimal(ppnNominal));
                        $('#grand_total').text(formatDecimal(grandTotal));
                    }

                    function renderSelectedGrns() {
                        const $list = $('#grn-selected-list');
                        $list.empty();
                        $('#create-ap-form input[name^="grn_ids"]').remove();

                        if (selectedGrns.length === 0) {
                            $list.html(
                                '<tr id="grn-placeholder"><td colspan="8" class="text-center text-muted py-4">Belum ada GRN yang dipilih.</td></tr>'
                            );
                        } else {
                            selectedGrns.forEach((grn) => {
                                const grnIdentifier = grn.type.includes('non_farmasi') ? `non_farmasi_${grn.id}` :
                                    `farmasi_${grn.id}`;
                                $('#create-ap-form').append(
                                    `<input type="hidden" name="grn_ids[]" value="${grnIdentifier}">`);

                                const formattedDate = grn.tanggal_penerimaan ? moment(grn.tanggal_penerimaan,
                                    'DD MMM YYYY').format('DD MMM YYYY') : 'N/A';

                                $list.append(`
                        <tr data-grn-id="${grn.id}" class="text-center">
                            <td><button type="button" class="btn btn-xs btn-outline-danger remove-grn-btn" data-id="${grn.id}" title="Hapus"><i class="fal fa-times"></i></button></td>
                            <td>${formattedDate}</td>
                            <td>${grn.no_grn || 'N/A'}</td>
                            <td>${grn.no_po || 'N/A'}</td>
                            <td>${grn.keterangan || ''}</td>
                            <td class="text-right">
                                <input type="text" name="diskon[${grn.id}]" class="form-control form-control-sm text-right diskon-input" 
                                    value="${formatCurrency(grn.diskon || 0)}" data-id="${grn.id}">
                            </td>
                            <td class="text-right">
                                <input type="text" name="biaya_lain[${grn.id}]" class="form-control form-control-sm text-right biaya-lain-input" 
                                    value="${formatCurrency(grn.biaya_lainnya || 0)}" data-id="${grn.id}">
                            </td>
                            <td class="text-right">${formatCurrency(grn.total_nilai_barang || 0)}</td>
                        </tr>
                    `);
                            });
                        }
                        calculateTotals();
                    }

                    // =========================================================================
                    // FUNGSI UNTUK MENANGANI DATA DARI POPUP
                    // =========================================================================
                    function addGrnFromPopup(data) {
                        if (selectedGrns.some(g => g.id === parseInt(data.grnId) && g.type === data.grnType)) {
                            toastr.warning('GRN sudah dipilih sebelumnya.', 'Peringatan');
                            return;
                        }

                        if (selectedGrns.length === 0) {
                            defaultPpnPersen = parseFloat(data.ppnPersen) || 0;
                            if (data.supplierId) {
                                $('#supplier_id').val(data.supplierId).trigger('change.select2');
                            }
                            $('input[name="no_invoice"]').val(data.noInvoice || '');
                        }

                        const newGrn = {
                            id: parseInt(data.grnId),
                            type: data.grnType,
                            no_grn: data.grnNo,
                            no_po: data.poNo,
                            tanggal_penerimaan: data.tanggalPenerimaan,
                            total_nilai_barang: parseFloat(data.totalNilai) || 0,
                            ppn_persen: parseFloat(data.ppnPersen) || 0,
                            diskon_faktur: parseFloat(data.diskonFaktur) || 0,
                            materai: parseFloat(data.materai) || 0,
                            diskon: 0, // Default diskon per item 0
                            biaya_lainnya: 0, // Default biaya lainnya per item 0
                            keterangan: data.keterangan || ''
                        };

                        selectedGrns.push(newGrn);
                        renderSelectedGrns();

                        $('#diskon_final').val(formatCurrency(newGrn.diskon_faktur));
                        $('#ppn_persen').val(newGrn.ppn_persen);
                        $('#materai').val(formatCurrency(newGrn.materai));
                        calculateTotals();
                        toastr.success('GRN berhasil ditambahkan.', 'Sukses');
                    }

                    function handleReturSelection(data) {
                        selectedReturs = data;
                        const totalReturNominal = selectedReturs.reduce((total, retur) => total + retur.nominal, 0);

                        $('#retur').val(formatCurrency(totalReturNominal));

                        if (totalReturNominal > 0) {
                            $('#row-faktur-pajak-retur').slideDown();
                        } else {
                            $('#row-faktur-pajak-retur').slideUp();
                            $('input[name="faktur_pajak_retur"]').val('');
                        }

                        calculateTotals();
                        toastr.success(`${selectedReturs.length} retur berhasil dipilih.`, 'Sukses');
                    }

                    // =========================================================================
                    // EVENT LISTENERS
                    // =========================================================================
                    window.addEventListener('message', function(event) {
                        if (event.origin !== window.location.origin || !event.data || !event.data.type) return;

                        switch (event.data.type) {
                            case 'GRN_SELECTED':
                                addGrnFromPopup(event.data.data);
                                if (grnPopupWindow && !grnPopupWindow.closed) grnPopupWindow.close();
                                break;
                            case 'RETUR_SELECTED':
                                handleReturSelection(event.data.data);
                                if (returPopupWindow && !returPopupWindow.closed) returPopupWindow.close();
                                break;
                        }
                    }, false);

                    // Event untuk input diskon dan biaya lainnya per item
                    $('#grn-selected-list').on('input', '.diskon-input, .biaya-lain-input', function() {
                        const grnId = $(this).data('id');
                        const value = parseCurrency($(this).val());

                        // Temukan GRN yang sesuai dalam array selectedGrns
                        const grnIndex = selectedGrns.findIndex(g => g.id == grnId);
                        if (grnIndex !== -1) {
                            if ($(this).hasClass('diskon-input')) {
                                selectedGrns[grnIndex].diskon = value;
                            } else {
                                selectedGrns[grnIndex].biaya_lainnya = value;
                            }
                            calculateTotals();
                        }
                    });

                    $('#btn-index-grn').on('click', function(e) {
                        e.preventDefault();
                        const supplierId = $('#supplier_id').val();
                        if (!supplierId) {
                            toastr.error('Pilih supplier terlebih dahulu.', 'Error');
                            return;
                        }
                        const url =
                            `{{ route('keuangan.ap-supplier.indexGrn') }}?initial_supplier_id=${supplierId}`;
                        grnPopupWindow = window.open(url, `grnSelector_${Date.now()}`,
                            'width=1200,height=800,scrollbars=yes');
                    });

                    $('#btn-open-retur-popup').on('click', function(e) {
                        e.preventDefault();
                        const supplierId = $('#supplier_id').val();
                        if (!supplierId) {
                            toastr.error('Pilih supplier terlebih dahulu untuk melihat daftar retur.', 'Error');
                            return;
                        }
                        const url = `{{ route('keuangan.ap-supplier.pilihRetur') }}?supplier_id=${supplierId}`;
                        returPopupWindow = window.open(url, `returSelector_${Date.now()}`,
                            'width=1000,height=700,scrollbars=yes');
                    });

                    $('#grn-selected-list').on('click', '.remove-grn-btn', function() {
                        const grnId = parseInt($(this).data('id'));
                        selectedGrns = selectedGrns.filter(g => g.id !== grnId);
                        if (selectedGrns.length === 0) {
                            defaultPpnPersen = 0;
                        }
                        renderSelectedGrns();
                    });

                    $('#supplier_id').on('change', function() {
                        const supplierId = $(this).val();
                        const ppn = $(this).find(':selected').data('ppn') || 0;
                        $('#ppn_persen').val(ppn).trigger('input');
                        $('#btn-index-grn').prop('disabled', !supplierId);

                        if (selectedGrns.length > 0 || selectedReturs.length > 0) {
                            Swal.fire({
                                title: 'Ganti Supplier?',
                                text: "Tindakan ini akan menghapus semua GRN dan Retur yang sudah dipilih. Lanjutkan?",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Ya, Lanjutkan!',
                                cancelButtonText: 'Batal'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    selectedGrns = [];
                                    selectedReturs = [];
                                    defaultPpnPersen = 0;
                                    renderSelectedGrns();
                                    handleReturSelection([]);
                                }
                            });
                        }
                    });

                    $('.panel').on('input', '.calculation-field', calculateTotals);

                    $('#create-ap-form').on('submit', function(e) {
                        if (selectedGrns.length === 0) {
                            e.preventDefault();
                            toastr.error('Anda harus memilih setidaknya satu GRN.', 'Error Validasi');
                            return;
                        }

                        // Format semua nilai sebelum submit
                        $('.calculation-field, #ppn_nominal').each(function() {
                            $(this).val(parseCurrency($(this).val()));
                        });

                        // Format input diskon dan biaya lainnya per item
                        $('.diskon-input, .biaya-lain-input').each(function() {
                            const value = parseCurrency($(this).val());
                            $(this).val(value.toFixed(2));
                        });
                    });

                    // Format nilai awal
                    $('.calculation-field').each(function() {
                        $(this).val(formatCurrency(parseCurrency($(this).val())));
                    });
                    calculateTotals();
                });
            </script>
        @endsection
