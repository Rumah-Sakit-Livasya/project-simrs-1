@extends('inc.layout')
@section('title', 'Tambah AP Supplier')
@section('content')
    <style>
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

        .input-item {
            width: 100px;
            text-align: right;
        }
    </style>
    <main id="js-page-content" role="main" class="page-content">
        <div class="row justify-content-center">
            <div class="col-xl-12">
                <div class="panel">
                    <div class="panel-hdr">
                        <h2>Proses <span class="fw-300"><i>AP Supplier</i></span></h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form action="{{ route('keuangan.ap-supplier.store') }}" method="post" id="create-ap-form">
                                @csrf
                                {{-- Form Header (Tanggal, Supplier, dll.) --}}
                                <div class="form-row">
                                    <div class="col-md-4 mb-3">
                                        <label class="mb-1">Tanggal AP <span class="text-danger">*</span></label>
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control form-control-sm datepicker"
                                                name="tanggal_ap" placeholder="Pilih Tanggal AP"
                                                value="{{ old('tanggal_ap', date('d-m-Y')) }}" autocomplete="off" required>
                                            <div class="input-group-append"><span class="input-group-text fs-sm"><i
                                                        class="fal fa-calendar"></i></span></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="mb-1">Due Date <span class="text-danger">*</span></label>
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control form-control-sm datepicker"
                                                name="due_date" placeholder="Atur Jatuh Tempo" value="{{ old('due_date') }}"
                                                autocomplete="off" required>
                                            <div class="input-group-append"><span class="input-group-text fs-sm"><i
                                                        class="fal fa-calendar"></i></span></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="mb-1">Tanggal Faktur Pajak</label>
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control form-control-sm datepicker"
                                                name="tanggal_faktur_pajak" placeholder="Tanggal Faktur Pajak"
                                                value="{{ old('tanggal_faktur_pajak') }}" autocomplete="off">
                                            <div class="input-group-append"><span class="input-group-text fs-sm"><i
                                                        class="fal fa-calendar"></i></span></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-6 mb-3">
                                        <label class="mb-1">Supplier <span class="text-danger">*</span></label>
                                        <select class="form-control form-control-sm select2" id="supplier_id"
                                            name="supplier_id" required>
                                            <option value="" disabled selected>Pilih Supplier</option>
                                            @foreach ($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}" data-ppn="{{ $supplier->ppn ?? 0 }}"
                                                    {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                                    {{ $supplier->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="mb-1">No Invoice <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm" name="no_invoice"
                                            placeholder="Masukkan No Invoice" value="{{ old('no_invoice') }}" required>
                                    </div>
                                </div>

                                {{-- Tabel Item GRN yang Dipilih --}}
                                <table class="table table-bordered mt-3">
                                    <thead class="bg-primary-600 text-white">
                                        <tr>
                                            <th width="5%">#</th>
                                            <th>Tgl GRN</th>
                                            <th>No. GRN</th>
                                            <th>No. PO</th>
                                            <th class="text-right">Nominal</th>
                                            <th class="text-right">Diskon</th>
                                            <th class="text-right">Biaya Lain</th>
                                            <th width="5%" class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="grn-selected-list">
                                        <tr id="grn-placeholder">
                                            <td colspan="8" class="text-center">Belum ada GRN yang dipilih.</td>
                                        </tr>
                                    </tbody>
                                </table>

                                <div class="row mt-3">
                                    {{-- Kolom Notes dan Kelengkapan Dokumen --}}
                                    <div class="col-md-6">
                                        <label class="mb-1">Notes</label>
                                        <textarea class="form-control" name="notes" rows="4" placeholder="Catatan tambahan...">{{ old('notes') }}</textarea>
                                        <label class="mt-2 mb-1">Kelengkapan Dokumen</label>
                                        <div class="d-flex flex-wrap">
                                            <div class="form-check mr-3"><input class="form-check-input" type="checkbox"
                                                    name="ada_kwitansi" value="1"> Kwitansi</div>
                                            <div class="form-check mr-3"><input class="form-check-input" type="checkbox"
                                                    name="ada_faktur_pajak" value="1"> Faktur Pajak</div>
                                            <div class="form-check mr-3"><input class="form-check-input" type="checkbox"
                                                    name="ada_surat_jalan" value="1"> Surat Jalan</div>
                                            <div class="form-check mr-3"><input class="form-check-input" type="checkbox"
                                                    name="ada_salinan_po" value="1"> Salinan PO</div>
                                            <div class="form-check mr-3"><input class="form-check-input" type="checkbox"
                                                    name="ada_tanda_terima_barang" value="1"> Tanda Terima</div>
                                            <div class="form-check"><input class="form-check-input" type="checkbox"
                                                    name="ada_berita_acara" value="1"> Berita Acara</div>
                                        </div>
                                    </div>
                                    {{-- Kolom Kalkulasi Total --}}
                                    <div class="col-md-6">
                                        <div class="form-group row"><label
                                                class="col-md-6 col-form-label">Subtotal</label>
                                            <div class="col-md-6"><input type="text" class="form-control text-right"
                                                    id="subtotal" readonly></div>
                                        </div>
                                        <div class="form-group row"><label class="col-md-6 col-form-label">Diskon
                                                Final</label>
                                            <div class="col-md-6"><input type="number"
                                                    class="form-control text-right calculation-field" name="diskon_final"
                                                    id="diskon_final" value="0" step="any"></div>
                                        </div>
                                        <div class="form-group row"><label class="col-md-6 col-form-label">Total Setelah
                                                Diskon</label>
                                            <div class="col-md-6"><input type="text" class="form-control text-right"
                                                    id="total_after_discount" readonly></div>
                                        </div>
                                        <div class="form-group row"><label class="col-md-6 col-form-label">PPN (%)</label>
                                            <div class="col-md-6"><input type="number"
                                                    class="form-control text-right calculation-field" name="ppn_persen"
                                                    id="ppn_persen" value="0" step="any"></div>
                                        </div>
                                        <div class="form-group row"><label class="col-md-6 col-form-label">PPN
                                                (Nominal)</label>
                                            <div class="col-md-6"><input type="text" class="form-control text-right"
                                                    id="ppn_nominal" readonly></div>
                                        </div>
                                        <div class="form-group row font-weight-bold"><label
                                                class="col-md-6 col-form-label">Grand Total</label>
                                            <div class="col-md-6"><input type="text"
                                                    class="form-control text-right font-weight-bold" id="grand_total"
                                                    readonly></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row mt-3">
                                    <div class="col-md-12 text-right">
                                        <a href="{{ route('keuangan.ap-supplier.index') }}"
                                            class="btn btn-secondary">Kembali</a>
                                        <button type="button" class="btn btn-info" id="btn-index-grn" disabled>Pilih
                                            GRN</button>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('plugin')
    {{-- Hanya sertakan script yang dibutuhkan --}}
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/dependency/moment/moment.js"></script>

    <script>
        // =========================================================================
        // === BAGIAN SCRIPT YANG DIPERBAIKI (HANYA ADA SATU DOCUMENT.READY) ===
        // =========================================================================

        // --- Fungsi Global (di luar document.ready) agar bisa dipanggil oleh pop-up ---
        let selectedGrns = [];
        let popupWindow = null; // Untuk tracking window pop-up

        function addGrnFromPopup(grnData) {
            const isExist = selectedGrns.some(g => g.id === grnData.id);
            if (isExist) {
                alert('GRN ' + grnData.no_grn + ' sudah dipilih.');
                return;
            }
            grnData.diskon = 0;
            grnData.biaya_lain = 0;
            selectedGrns.push(grnData);
            renderSelectedGrns();
            calculateTotals();
        }

        // --- Logika Utama (di dalam document.ready) ---
        $(document).ready(function() {
            // -- Helper Functions (di dalam scope document.ready) --
            function renderSelectedGrns() {
                const list = $('#grn-selected-list');
                $('input[name^="grn_ids"], input[name^="diskon_item"], input[name^="biaya_lain_item"]').remove();
                list.empty();

                if (selectedGrns.length > 0) {
                    selectedGrns.forEach(function(grn, index) {
                        const form = $('#create-ap-form');
                        form.append(`<input type="hidden" name="grn_ids[${grn.id}]" value="${grn.id}">`);
                        list.append(`
                            <tr>
                                <td>${index + 1}</td>
                                <td>${moment(grn.tanggal_penerimaan).format('DD MMM YYYY')}</td>
                                <td>${grn.no_grn}</td>
                                <td>${grn.purchasable ? grn.purchasable.no_po : 'N/A'}</td>
                                <td class="text-right">${formatCurrency(grn.total_nilai_barang)}</td>
                                <td><input type="number" class="form-control input-item item-calculation" name="diskon_item[${grn.id}]" data-id="${grn.id}" data-field="diskon" value="${grn.diskon}" step="any"></td>
                                <td><input type="number" class="form-control input-item item-calculation" name="biaya_lain_item[${grn.id}]" data-id="${grn.id}" data-field="biaya_lain" value="${grn.biaya_lain}" step="any"></td>
                                <td class="text-center"><button type="button" class="btn btn-xs btn-danger remove-grn-btn" data-id="${grn.id}"><i class="fal fa-trash"></i></button></td>
                            </tr>
                        `);
                    });
                } else {
                    list.html(
                        '<tr id="grn-placeholder"><td colspan="8" class="text-center">Belum ada GRN yang dipilih.</td></tr>'
                    );
                }
            }

            function calculateTotals() {
                let subtotal = selectedGrns.reduce((sum, grn) => sum + parseFloat(grn.total_nilai_barang), 0);
                let totalItemDiskon = 0;
                let totalItemBiayaLain = 0;
                selectedGrns.forEach(grn => {
                    totalItemDiskon += parseFloat(grn.diskon) || 0;
                    totalItemBiayaLain += parseFloat(grn.biaya_lain) || 0;
                });
                let diskonFinal = parseFloat($('#diskon_final').val()) || 0;
                let totalAfterDiscount = subtotal - totalItemDiskon - diskonFinal;
                let ppnPersen = parseFloat($('#ppn_persen').val()) || 0;
                let ppnNominal = totalAfterDiscount * (ppnPersen / 100);
                let grandTotal = totalAfterDiscount + ppnNominal + totalItemBiayaLain;

                $('#subtotal').val(formatCurrency(subtotal));
                $('#total_after_discount').val(formatCurrency(totalAfterDiscount));
                $('#ppn_nominal').val(formatCurrency(ppnNominal));
                $('#grand_total').val(formatCurrency(grandTotal));
            }

            function formatCurrency(number) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'decimal',
                    minimumFractionDigits: 2
                }).format(number);
            }

            // -- Inisialisasi Plugin --
            $('.select2').select2({
                placeholder: "Pilih opsi",
                allowClear: true
            });
            $('.datepicker').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true,
                orientation: "bottom left"
            });

            // -- Event Listeners --
            $('#supplier_id').on('change', function() {
                const supplierId = $(this).val();
                const ppn = $(this).find(':selected').data('ppn') || 0;
                $('#ppn_persen').val(ppn);
                $('#btn-index-grn').prop('disabled', !supplierId);
                selectedGrns = [];
                renderSelectedGrns();
                calculateTotals();
            });

            // BAGIAN UTAMA YANG MEMBUAT POP-UP BERFUNGSI - DIPERBAIKI
            $('#btn-index-grn').on('click', function(e) {
                e.preventDefault(); // Mencegah default behavior

                const supplierId = $('#supplier_id').val();
                if (!supplierId) {
                    alert('Silakan pilih supplier terlebih dahulu.');
                    return;
                }

                // Tutup pop-up sebelumnya jika masih terbuka
                if (popupWindow && !popupWindow.closed) {
                    popupWindow.close();
                }

                // URL untuk pop-up - pastikan route ini ada di web.php
                const baseUrl = "{{ url('/') }}";

                let urlPopup =
                    `{{ route('keuangan.ap-supplier.indexGrn') }}?initial_supplier_id=${supplierId}`;
                // Konfigurasi pop-up
                const popupWidth = 1000;
                const popupHeight = 700;
                const left = Math.max(0, (screen.width - popupWidth) / 2);
                const top = Math.max(0, (screen.height - popupHeight) / 2);

                const windowFeatures = [
                    `width=${popupWidth}`,
                    `height=${popupHeight}`,
                    `left=${left}`,
                    `top=${top}`,
                    'scrollbars=yes',
                    'resizable=yes',
                    'status=yes',
                    'toolbar=no',
                    'menubar=no',
                    'location=no'
                ].join(',');

                try {
                    // Buka pop-up dengan nama window unik
                    const windowName = `indexGrn_${supplierId}_${Date.now()}`;
                    popupWindow = window.open(urlPopup, windowName, windowFeatures);

                    // Fokus ke pop-up jika berhasil dibuka
                    if (popupWindow) {
                        popupWindow.focus();

                        // Optional: Monitor jika pop-up ditutup
                        const checkClosed = setInterval(() => {
                            if (popupWindow.closed) {
                                clearInterval(checkClosed);
                                popupWindow = null;
                            }
                        }, 1000);
                    } else {
                        alert('Pop-up diblokir oleh browser. Silakan izinkan pop-up untuk situs ini.');
                    }
                } catch (error) {
                    console.error('Error membuka pop-up:', error);
                    alert('Gagal membuka pop-up. Periksa pengaturan browser Anda.');
                }
            });

            // Event listener untuk menghapus GRN
            $('#grn-selected-list').on('click', '.remove-grn-btn', function(e) {
                e.preventDefault();
                const grnId = $(this).data('id');
                selectedGrns = selectedGrns.filter(g => g.id !== grnId);
                renderSelectedGrns();
                calculateTotals();
            });

            // Event listener untuk perubahan nilai item
            $('#grn-selected-list').on('change keyup', '.item-calculation', function() {
                const grnId = $(this).data('id');
                const field = $(this).data('field');
                const value = $(this).val();
                const grnIndex = selectedGrns.findIndex(g => g.id === grnId);
                if (grnIndex > -1) {
                    selectedGrns[grnIndex][field] = value;
                }
                calculateTotals();
            });

            // Event listener untuk field kalkulasi
            $('.calculation-field').on('keyup change', calculateTotals);
        });
    </script>
@endsection
