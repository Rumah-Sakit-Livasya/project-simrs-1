@extends('inc.layout')
@section('title', 'Detail AP Supplier')
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

        /* Styling untuk status badges */
        .status-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-belum-lunas {
            background-color: #ffeaa7;
            color: #2d3436;
        }

        .status-lunas {
            background-color: #00b894;
            color: white;
        }

        .status-dibatalkan {
            background-color: #e17055;
            color: white;
        }
    </style>
    <main id="js-page-content" role="main" class="page-content">
        {{-- Form untuk details - hanya untuk tampilan, tidak akan disubmit --}}
        <form id="details-ap-form">
            @csrf
            <div class="row justify-content-center align-item-center">
                {{-- Panel 1: Form Input Utama (col-xl-10) --}}
                <div class="col-xl-10">
                    <div class="panel">
                        <div class="panel-hdr">
                            <h2>Detail AP Supplier - {{ $apSupplier->kode_ap }}</h2>
                            <div class="panel-toolbar">
                                <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $apSupplier->status_pembayaran)) }}">
                                    {{ $apSupplier->status_pembayaran }}
                                </span>
                            </div>
                        </div>
                        <div class="panel-container show">
                            <div class="panel-content">
                                <div class="form-row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Tanggal AP <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control form-control-sm datepicker"
                                                name="tanggal_ap" value="{{ $apSupplier->tanggal_ap->format('d-m-Y') }}"
                                                autocomplete="off" required>
                                            <div class="input-group-append"><span class="input-group-text fs-sm"><i
                                                        class="fal fa-calendar"></i></span></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Due Date <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control form-control-sm datepicker"
                                                name="due_date" value="{{ $apSupplier->due_date->format('d-m-Y') }}" autocomplete="off" required>
                                            <div class="input-group-append"><span class="input-group-text fs-sm"><i
                                                        class="fal fa-calendar"></i></span></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Tanggal Faktur Pajak</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control form-control-sm datepicker"
                                                name="tanggal_faktur_pajak" value="{{ $apSupplier->tanggal_faktur_pajak ? $apSupplier->tanggal_faktur_pajak->format('d-m-Y') : '' }}"
                                                autocomplete="off">
                                            <div class="input-group-append"><span class="input-group-text fs-sm"><i
                                                        class="fal fa-calendar"></i></span></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Supplier <span class="text-danger">*</span></label>
                                        <select class="form-control form-control-sm select2" id="supplier_id"
                                            name="supplier_id" required>
                                            <option value="{{ $apSupplier->supplier->id }}" selected>
                                                {{ $apSupplier->supplier->nama }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">No Invoice Supplier <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm" name="no_invoice"
                                            value="{{ $apSupplier->no_invoice_supplier }}" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">No Faktur Pajak</label>
                                        <input type="text" class="form-control form-control-sm" name="no_faktur_pajak"
                                            value="{{ $apSupplier->no_faktur_pajak ?? '' }}">
                                    </div>
                                </div>
                                
                                {{-- Info tambahan untuk details --}}
                             
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
                                        @forelse($apSupplier->details as $index => $detail)
                                            <tr class="text-center">
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td>{{ $detail->penerimaanBarang->tanggal_penerimaan ? $detail->penerimaanBarang->tanggal_penerimaan->format('d M Y') : 'N/A' }}</td>
                                                <td>{{ $detail->penerimaanBarang->no_grn ?? 'N/A' }}</td>
                                                <td>{{ $apSupplier->po_numbers }}</td>
                                                <td>
                                                    {{ $apSupplier->notes }}
                                                </td>
                                                <td class="item-calculation-display">{{ number_format($detail->penerimaanBarang->diskon ?? 0, 0, ',', '.') }}</td>
                                                <td class="item-calculation-display">{{ number_format($detail->penerimaanBarang->biaya_lainnya ?? 0, 0, ',', '.') }}</td>
                                                <td class="text-center">{{ number_format($detail->nominal_grn, 0, ',', '.') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center text-muted py-4">Tidak ada data GRN.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        <tr class="font-weight-bold" style="background-color: #f3f3f3;">
                                            <td colspan="5" class="text-center">Total Item</td>
                                            <td id="total-diskon-item" class="text-center">{{ number_format($apSupplier->details->sum(function($detail) { return $detail->penerimaanBarang->diskon ?? 0; }), 0, ',', '.') }}</td>
                                            <td id="total-biaya-lain" class="text-center">{{ number_format($apSupplier->details->sum(function($detail) { return $detail->penerimaanBarang->biaya_lainnya ?? 0; }), 0, ',', '.') }}</td>
                                            <td id="total-nominal" class="text-center">{{ number_format($apSupplier->subtotal, 0, ',', '.') }}</td>
                                        </tr>
                                    </tfoot>
                                </table>

                                <div class="row mt-4">
                                    <div class="col-md-7">
                                        <label class="form-label">Notes</label>
                                        <textarea class="form-control" name="notes" rows="4" placeholder="Catatan tambahan...">{{ $apSupplier->notes }}</textarea>
                                        <label class="mt-3 form-label">Kelengkapan Dokumen</label>
                                        <div class="d-flex flex-wrap">
                                            <div class="form-check mr-3">
                                                <input class="form-check-input" type="checkbox" name="ada_kwitansi" value="1" @if($apSupplier->ada_kwitansi) checked @endif> Kwitansi
                                            </div>
                                            <div class="form-check mr-3">
                                                <input class="form-check-input" type="checkbox" name="ada_faktur_pajak" value="1" @if($apSupplier->ada_faktur_pajak) checked @endif> Faktur Pajak
                                            </div>
                                            <div class="form-check mr-3">
                                                <input class="form-check-input" type="checkbox" name="ada_surat_jalan" value="1" @if($apSupplier->ada_surat_jalan) checked @endif> Surat Jalan
                                            </div>
                                            <div class="form-check mr-3">
                                                <input class="form-check-input" type="checkbox" name="ada_salinan_po" value="1" @if($apSupplier->ada_salinan_po) checked @endif> Salinan PO
                                            </div>
                                            <div class="form-check mr-3">
                                                <input class="form-check-input" type="checkbox" name="ada_tanda_terima_barang" value="1" @if($apSupplier->ada_tanda_terima_barang) checked @endif> Tanda Terima
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="ada_berita_acara" value="1" @if($apSupplier->ada_berita_acara) checked @endif> Berita Acara
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-5">
                                        <table class="table table-sm table-borderless table-calculation">
                                            <tbody>
                                                <tr>
                                                    <td>Retur</td>
                                                    <td><input type="text" name="retur" id="retur"
                                                            class="form-control text-right calculation-field"
                                                            value="{{ number_format($apSupplier->retur ?? 0, 0, ',', '.') }}"></td>
                                                </tr>
                                                <tr>
                                                    <td>Diskon Final</td>
                                                    <td><input type="text" name="diskon_final" id="diskon_final"
                                                            class="form-control text-right calculation-field"
                                                            value="{{ number_format($apSupplier->diskon_final, 0, ',', '.') }}"></td>
                                                </tr>
                                                <tr>
                                                    <td>PPN %</td>
                                                    <td>
                                                        <div class="input-group">
                                                            <!-- Persen di start -->
                                                            <input type="text" name="ppn_persen" id="ppn_persen"
                                                                class="form-control text-end calculation-field"
                                                                style="max-width: 60px;" value="{{ $apSupplier->ppn_persen }}">
                                                            
                                                            <!-- Nominal di end -->
                                                            <input type="text" id="ppn_nominal"
                                                                class="form-control text-end text-right bg-light" 
                                                                value="{{ number_format($apSupplier->ppn_nominal, 0, ',', '.') }}" readonly>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Materai</td>
                                                    <td><input type="text" name="materai" id="materai"
                                                            class="form-control text-right calculation-field"
                                                            value="{{ number_format($apSupplier->materai ?? 0, 0, ',', '.') }}"></td>
                                                </tr>
                                                <tr class="font-weight-bold" style="background-color: #f3f3f3;">
                                                    <td >Grand Total</td>
                                                    <td class=" text-right"><span id="grand_total">{{ number_format($apSupplier->grand_total, 0, ',', '.') }}</span></td>
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
                                    @if($apSupplier->status_pembayaran === 'Belum Lunas')
                                        <button type="button" class="btn btn-danger" id="btn-cancel-invoice">
                                            <i class="fal fa-times-circle mr-1"></i> Cancel Invoice
                                        </button>
                                    @endif
                                    <a href="{{ route('keuangan.ap-supplier.partials.create') }}" class="btn btn-primary ml-2">
                                        <i class="fal fa-plus-circle mr-1"></i> Tambah Baru
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        {{-- Modal Konfirmasi Cancel --}}
        <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="cancelModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="cancelModalLabel">Konfirmasi Pembatalan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin membatalkan AP Supplier <strong>{{ $apSupplier->kode_ap }}</strong>?</p>
                        <p class="text-warning"><small><i class="fal fa-exclamation-triangle"></i> Tindakan ini akan mengubah status GRN kembali menjadi "Belum AP" dan tidak dapat dibatalkan.</small></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <form action="{{ route('keuangan.ap-supplier.cancel', $apSupplier->id) }}" method="POST" class="d-inline">
                            @csrf
                        
                            <button type="submit" class="btn btn-danger">Ya, Batalkan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
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

    {{-- Script Khusus Halaman Details --}}
    <script>
        $(document).ready(function() {
            // Inisialisasi plugin
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
                allowClear: false // Tidak perlu clear karena ini details
            });

            // Fungsi untuk format mata uang (sama dengan create)
            function formatCurrency(number) {
                return new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }).format(number);
            }

            // Fungsi untuk parse nilai dari format mata uang
            function parseCurrency(value) {
                return parseFloat(String(value).replace(/[^0-9,-]+/g, "").replace(",", ".")) || 0;
            }

            // Kalkulasi untuk preview saja (tidak akan disimpan)
          // Fungsi kalkulasi di halaman details
function calculateTotals() {
    // Ambil data dari tabel yang sudah ada
    let subtotal = 0;
    let totalItemDiskon = 0;
    let totalItemBiayaLain = 0;
    
    // Hitung dari data yang ada di tabel
    $('#grn-selected-list tr').each(function() {
        const nominal = parseCurrency($(this).find('td:eq(7)').text());
        const diskon = parseCurrency($(this).find('td:eq(5)').text());
        const biayaLain = parseCurrency($(this).find('td:eq(6)').text());
        
        subtotal += nominal;
        totalItemDiskon += diskon;
        totalItemBiayaLain += biayaLain;
    });

    // Ambil nilai dari input
    const retur = parseCurrency($('#retur').val());
    const diskonFinal = parseCurrency($('#diskon_final').val());
    const materai = parseCurrency($('#materai').val());
    const ppnPersen = parseCurrency($('#ppn_persen').val());

    const dpp = subtotal - totalItemDiskon - diskonFinal - retur;
    const ppnNominal = dpp * (ppnPersen / 100);
    const grandTotal = dpp + ppnNominal + totalItemBiayaLain + materai;

    // Update tampilan
    $('#total-diskon-item').text(formatCurrency(totalItemDiskon));
    $('#total-biaya-lain').text(formatCurrency(totalItemBiayaLain));
    $('#total-nominal').text(formatCurrency(subtotal));
    $('#ppn_nominal').val(formatCurrency(ppnNominal));
    $('#grand_total').text(formatCurrency(grandTotal));
}

            // Event Listener untuk perubahan nilai input numerik agar terformat
            $('.panel').on('blur', '.calculation-field', function() {
                const value = parseCurrency($(this).val());
                $(this).val(formatCurrency(value));
            }).on('input', '.calculation-field', calculateTotals);

            // Event handler untuk tombol cancel
            $('#btn-cancel-invoice').on('click', function(e) {
                e.preventDefault();
                $('#cancelModal').modal('show');
            });

            // Kalkulasi awal
            calculateTotals();

            // Notifikasi bahwa ini adalah halaman details
            toastr.info('Halaman detail AP Supplier. Perubahan input tidak akan disimpan.', 'Informasi', {
                timeOut: 3000
            });
        });
    </script>
@endsection