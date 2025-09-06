{{-- Ganti dengan layout polos Anda, misalnya 'inc.layout-polos' --}}
@extends('inc.layout-no-side')
@section('title', 'Pilih Invoice')

@section('content')
    <style>
        table {
            font-size: 8pt !important;
        }

        .selected-invoice {
            background-color: #f8f9fa !important;
            opacity: 0.7;
        }

        .btn-pilih-invoice {
            transition: all 0.3s ease;
        }

        .btn-pilih-invoice.disabled {
            pointer-events: none;
            opacity: 0.5;
        }

        .floating-action-bar {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .counter-badge {
            background: #007bff;
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            margin-left: 10px;
        }
    </style>

    <main id="js-page-content" role="main" class="page-content">
        {{-- Container utama --}}
        <div class="row justify-content-center">
            <div class="col-xl-10">
                {{-- Panel Pencarian --}}
                <div class="panel">
                    <div class="panel-hdr">
                        <h2>Form Cari Invoice - Supplier: {{ $supplier->nama }}</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form action="{{ route('keuangan.pembayaran-ap-supplier.pilihInvoice') }}" method="GET">
                                <input type="hidden" name="supplier_id" value="{{ $supplier->id }}">

                                <div class="form-row">
                                    <div class="col-md-6 mb-3">
                                        <label>Due Date Awal</label>
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control datepicker" name="due_date_awal"
                                                value="{{ request('due_date_awal') }}" placeholder="Pilih Due Date Awal">
                                            <div class="input-group-append">
                                                <span class="input-group-text fs-sm"><i class="fal fa-calendar"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Due Date Akhir</label>
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control datepicker" name="due_date_akhir"
                                                value="{{ request('due_date_akhir') }}" placeholder="Pilih Due Date Akhir">
                                            <div class="input-group-append">
                                                <span class="input-group-text fs-sm"><i class="fal fa-calendar"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="no_invoice" class="form-label">No Invoice</label>
                                        <input type="text" id="no_invoice" name="no_invoice"
                                            class="form-control form-control-sm" value="{{ request('no_invoice') }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="kode_ap" class="form-label">Kode AP</label>
                                        <input type="text" id="kode_ap" name="kode_ap"
                                            class="form-control form-control-sm" value="{{ request('kode_ap') }}">
                                    </div>
                                </div>

                                <div class="form-group text-right">
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        <i class="fal fa-search"></i> Cari
                                    </button>
                                    <button type="button" id="btn-reset-pilihan" class="btn btn-sm btn-warning">
                                        <i class="fal fa-undo"></i> Reset Pilihan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Panel Daftar Invoice --}}
        <div class="row">
            <div class="col-xl-12">
                <div class="panel mt-4">
                    <div class="panel-hdr">
                        <div class="d-flex align-items-center justify-content-between w-100">
                            {{-- Judul di sebelah kiri --}}
                            <h2 class="d-inline-block">
                                Daftar Hutang Supplier: {{ $supplier->nama }}
                            </h2>

                            {{-- Badge counter di sebelah kanan --}}
                            <span id="selected-count" class="badge bg-primary-500 ml-auto">0 invoice dipilih</span>
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table class="table table-sm table-bordered table-hover">
                                <thead class="bg-primary-600">
                                    <tr class="text-center">
                                        <th width="5%">Aksi</th>
                                        <th>Tgl AP</th>
                                        <th>Due Date</th>
                                        <th>No Invoice</th>
                                        <th>Kode AP</th>
                                        <th>Nominal Hutang</th>
                                        <th>Telah Dibayar</th>
                                        <th>Sisa Hutang</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($invoices as $invoice)
                                        <tr data-invoice='{{ $invoice->toJson() }}' data-invoice-id="{{ $invoice->id }}">
                                            <td class="text-center">
                                                <a href="javascript:void(0);" class="btn-pilih-invoice text-success"
                                                    title="Pilih Invoice Ini" data-invoice-id="{{ $invoice->id }}">
                                                    <i class="fal fa-plus-circle fa-lg"></i>
                                                </a>
                                            </td>
                                            <td class="text-center">{{ $invoice->tanggal_ap->format('d-m-Y') }}</td>
                                            <td class="text-center">{{ $invoice->due_date->format('d-m-Y') }}</td>
                                            <td>{{ $invoice->no_invoice_supplier }}</td>
                                            <td>{{ $invoice->kode_ap }}</td>
                                            <td class="text-right">{{ number_format($invoice->grand_total, 2, ',', '.') }}
                                            </td>
                                            <td class="text-right">
                                                {{ number_format($invoice->total_dibayar, 2, ',', '.') }}</td>
                                            <td class="text-right font-weight-bold">
                                                {{ number_format($invoice->sisa_hutang, 2, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-muted">Tidak ditemukan data hutang
                                                untuk supplier ini.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Floating Action Bar --}}
        {{-- <div class="floating-action-bar">
            <button type="button" id="btn-konfirmasi-pilihan" class="btn btn-success">
                <i class="fal fa-check"></i> Konfirmasi Pilihan
            </button>
            <button type="button" id="btn-batal" class="btn btn-secondary ml-2">
                <i class="fal fa-times"></i> Batal
            </button>
        </div> --}}
    </main>
@endsection

@section('plugin')
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script>
        // Script ini berjalan di dalam POPUP WINDOW
        $(document).ready(function() {
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true
            });

            // Array untuk menyimpan invoice yang sudah dipilih
            let selectedInvoices = [];

            // Event handler untuk tombol pilih invoice individual
            $(document).on('click', '.btn-pilih-invoice', function(e) {
                e.preventDefault();

                const $button = $(this);
                const $row = $button.closest('tr');
                const invoiceData = JSON.parse($row.attr('data-invoice'));

                // Cek apakah invoice sudah dipilih sebelumnya
                const isAlreadySelected = selectedInvoices.some(item => item.id === invoiceData.id);

                if (isAlreadySelected) {
                    alert('Invoice ini sudah dipilih sebelumnya.');
                    return;
                }

                // Tambahkan ke array selected
                selectedInvoices.push(invoiceData);

                // Update UI - ubah ikon dan disable tombol
                $button.removeClass('text-success').addClass('text-muted');
                $button.find('i').removeClass('fa-plus-circle').addClass('fa-check-circle');
                $button.attr('title', 'Invoice Sudah Dipilih');
                $button.addClass('disabled');

                // Fade out row untuk menunjukkan bahwa sudah dipilih
                $row.addClass('selected-invoice');

                // Update counter
                updateSelectedCounter();

                // Kirim data ke parent window
                if (window.opener && !window.opener.closed) {
                    window.opener.postMessage({
                        type: 'INVOICE_SELECTED',
                        data: [invoiceData] // Kirim hanya invoice yang baru dipilih
                    }, '*');
                } else {
                    alert(
                        'Tidak dapat mengirim data ke halaman utama. Silakan refresh halaman utama dan coba lagi.'
                    );
                }

                console.log('Invoice dipilih:', invoiceData.kode_ap);
            });

            // Fungsi untuk update counter
            function updateSelectedCounter() {
                const count = selectedInvoices.length;
                $('#selected-count').text(count + ' invoice dipilih');
            }

            // Event untuk tombol batal
            $('#btn-batal').on('click', function() {
                window.close();
            });

            // Tombol untuk reset pilihan
            $('#btn-reset-pilihan').on('click', function() {
                selectedInvoices = [];

                // Reset UI
                $('.btn-pilih-invoice').removeClass('text-muted disabled').addClass('text-success');
                $('.btn-pilih-invoice i').removeClass('fa-check-circle').addClass('fa-plus-circle');
                $('.btn-pilih-invoice').attr('title', 'Pilih Invoice Ini');
                $('tbody tr').removeClass('selected-invoice');

                updateSelectedCounter();

                console.log('Pilihan direset');
            });

            // Inisialisasi
            updateSelectedCounter();
        });
    </script>
@endsection
