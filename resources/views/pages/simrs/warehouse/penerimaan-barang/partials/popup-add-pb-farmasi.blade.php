@extends('inc.layout-no-side')
@section('title', 'Form Penerimaan Barang')
@section('extended-css')
    <style>
        .display-none {
            display: none;
        }

        .popover {
            max-width: 100%;
        }

        .modal-dialog {
            max-width: 70%;
        }

        .borderless-input {
            border: 0;
            border-bottom: 1.9px solid #eaeaea;
            margin-top: -.5rem;
            border-radius: 0;
            background: transparent;
            box-shadow: none;
        }

        .qty {
            min-width: 100px;
            margin-left: 10px;
        }

        input {
            border: 0;
            border-bottom: 1.9px solid #eaeaea;
            margin-top: -.5rem;
            border-radius: 0;
        }

        /* Table input khusus: hanya border bawah */
        .table-input,
        .table-input:focus,
        .table-input:active {
            border: none !important;
            border-bottom: 1.9px solid #eaeaea !important;
            border-radius: 0 !important;
            background: transparent !important;
            box-shadow: none !important;
            padding-left: 0.25rem;
            padding-right: 0.25rem;
        }

        .table-input[readonly],
        .table-input:disabled {
            background-color: #f8f9fa !important;
        }

        #loading-page {
            position: absolute;
            min-height: 100%;
            min-width: 100%;
            background: rgba(0, 0, 0, 0.75);
            border-radius: 0 0 4px 4px;
            z-index: 1000;
        }

        .form-section-title {
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 1rem;
            color: #495057;
        }

        .form-label {
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 1.2rem;
        }

        .form-control:disabled,
        .form-control[readonly] {
            background-color: #f8f9fa;
        }

        .table thead th {
            vertical-align: middle;
            text-align: center;
        }

        .table tfoot td {
            vertical-align: middle;
        }

        .btn-action-group {
            display: flex;
            gap: 0.5rem;
            justify-content: flex-end;
        }

        /* Remove border from table cells except bottom */
        .table-bordered tbody td,
        .table-bordered tfoot td {
            border-left: none !important;
            border-right: none !important;
            border-top: none !important;
            border-bottom: 1px solid #dee2e6 !important;
        }

        @media (max-width: 1200px) {
            .modal-dialog {
                max-width: 95%;
            }
        }
    </style>
@endsection
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="px-2">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div id="panel-1" class="panel shadow-sm">
                        <div class="panel-hdr d-flex align-items-center justify-content-between">
                            <h2 class="mb-0">
                                <span class="form-section-title">Form Penerimaan Barang</span>
                            </h2>
                            <i id="loading-spinner" class="fas fa-spinner fa-spin"></i>
                        </div>
                        <div class="panel-container show">
                            <div id="loading-page"></div>
                            <div class="panel-content">
                                <form id="form-pr" name="form-pr"
                                    action="{{ route('warehouse.penerimaan-barang.pharmacy.store') }}" method="post">
                                    @csrf
                                    @method('post')
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                    <input type="hidden" name="employee_id" value="{{ auth()->user()->employee->id }}">

                                    {{-- Informasi Utama --}}
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-3">
                                            <label for="tanggal_terima"
                                                class="block text-sm font-medium text-gray-700 mb-1">
                                                Tanggal Penerimaan
                                            </label>
                                            <input readonly type="date" id="tanggal_terima" name="tanggal_terima"
                                                value="{{ \Carbon\Carbon::now()->toDateString() }}"
                                                class="form-control table-input bg-gray-100 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500 w-full"
                                                placeholder="Select date">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="no_faktur" class="block text-sm font-medium text-gray-700 mb-1">
                                                No Faktur / Surat Jalan<span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="no_faktur" id="no_faktur" required
                                                class="form-control table-input border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500 w-full"
                                                placeholder="Masukkan No Faktur / Surat Jalan" autocomplete="off">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="tipe_terima" class="block text-sm font-medium text-gray-700 mb-1">
                                                Tipe Terima
                                            </label>
                                            <select name="tipe_terima" id="tipe_terima"
                                                class="form-control table-input border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500 w-full">
                                                <option value="po" {{ old('tipe_terima') == 'po' ? 'selected' : '' }}>
                                                    Purchase Order
                                                </option>
                                                <option value="npo" {{ old('tipe_terima') == 'npo' ? 'selected' : '' }}>
                                                    Non Purchase Order
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="kode_po" class="block text-sm font-medium text-gray-700 mb-1">
                                                    Kode PO<span class="text-danger">*</span>
                                                </label>
                                                <div class="input-group">
                                                    <input type="hidden" name="po_id" value="" required>
                                                    <input type="text" name="kode_po" id="kode_po" readonly required
                                                        class="form-control table-input border border-gray-300 focus:ring-primary-500 mt-2 focus:border-primary-500"
                                                        placeholder="Pilih PO">
                                                    <button class="btn btn-outline-secondary" type="button"
                                                        id="pilih-po-btn" title="Pilih Purchase Order"
                                                        data-bs-toggle="modal" data-bs-target="#pilihPOModal"
                                                        tabindex="-1">
                                                        <span class="fal fa-search text-primary"></span>
                                                    </button>
                                                </div>
                                            </div>
                                            @include('pages.simrs.warehouse.penerimaan-barang.partials.modal-pilih-po-pharmacy')
                                        </div>
                                    </div>

                                    <div class="row g-3 mb-3">
                                        <div class="col-md-3">
                                            <label for="pic_penerima" class="block text-sm font-medium text-gray-700 mb-1">
                                                PIC Penerima
                                            </label>
                                            <input type="text" name="pic_penerima" id="pic_penerima"
                                                class="form-control table-input border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500 w-full"
                                                placeholder="Nama PIC Penerima" autocomplete="off">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="tipe_bayar" class="block text-sm font-medium text-gray-700 mb-1">
                                                Tipe Pembayaran
                                            </label>
                                            <select id="tipe_bayar" name="tipe_bayar"
                                                class="form-control table-input border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500 w-full">
                                                <option value="non_cash">Non Cash</option>
                                                <option value="cash">Cash</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="supplier" class="block text-sm font-medium text-gray-700 mb-1">
                                                Supplier<span class="text-danger">*</span>
                                            </label>
                                            <input type="hidden" name="supplier_id" value="">
                                            <select id="supplier"
                                                class="form-select select2 table-input border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500 w-full bg-gray-100 cursor-not-allowed"
                                                disabled required>
                                                <option value="" disabled selected hidden>Pilih Supplier</option>
                                                @foreach ($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}">{{ $supplier->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-1">
                                                Keterangan
                                            </label>
                                            <input type="text" name="keterangan" id="keterangan"
                                                class="form-control table-input border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500 w-full"
                                                placeholder="Keterangan (opsional)" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="row g-3 mb-3">
                                        <div class="col-md-3">
                                            <label for="kas" class="block text-sm font-medium text-gray-700 mb-1">
                                                Kas
                                            </label>
                                            <input type="text" name="kas" id="kas" disabled
                                                class="form-control table-input bg-gray-100 border border-gray-300 rounded-md w-full"
                                                placeholder="Kas">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="tanggal_faktur"
                                                class="block text-sm font-medium text-gray-700 mb-1">
                                                Tanggal Faktur
                                            </label>
                                            <input type="date" name="tanggal_faktur" id="tanggal_faktur"
                                                class="form-control table-input border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500 w-full"
                                                placeholder="Select date">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="gudang_id" class="block text-sm font-medium text-gray-700 mb-1">
                                                Gudang<span class="text-danger">*</span>
                                            </label>
                                            <select name="gudang_id" id="gudang" required
                                                class="form-control select2 table-input border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500 w-full">
                                                <option value="" selected disabled hidden>Pilih Gudang</option>
                                                @foreach ($gudangs as $gudang)
                                                    <option value="{{ $gudang->id }}">{{ $gudang->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            {{-- Kosong --}}
                                        </div>
                                    </div>

                                    <hr class="my-4">

                                    {{-- Tabel Item --}}
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover table-striped align-middle">
                                                    <thead class="bg-primary-600 text-white">
                                                        <tr>
                                                            <th>Bonus?</th>
                                                            <th>Kode</th>
                                                            <th>Nama</th>
                                                            <th>Satuan</th>
                                                            <th>Exp Date*</th>
                                                            <th>No Batch*</th>
                                                            <th>Jumlah PO</th>
                                                            <th>Belum Terima</th>
                                                            <th>Jumlah Terima*</th>
                                                            <th>Harga Sistem</th>
                                                            <th>Harga Supplier (Rp)</th>
                                                            <th>Diskon (%)</th>
                                                            <th>Diskon (Rp)</th>
                                                            <th>Harga Dibayar*</th>
                                                            <th>Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tableItems">
                                                        {{--
                                                            Pastikan input di dalam table row menggunakan class "table-input"
                                                            Contoh:
                                                            <input type="text" class="table-input" ...>
                                                        --}}
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td class="text-end" colspan="8">
                                                                <button type="button" id="add-btn"
                                                                    class="btn btn-primary waves-effect waves-themed">
                                                                    <span class="fal fa-plus mr-1"></span>
                                                                    Tambah Item
                                                                </button>
                                                                @include('pages.simrs.warehouse.penerimaan-barang.partials.modal-add-item-pharmacy')
                                                            </td>
                                                            <td>
                                                                <label class="form-label mb-1" for="diskon_faktur">
                                                                    Diskon Faktur (Rp)
                                                                </label>
                                                                <input type="number" class="form-control table-input"
                                                                    id="diskon-faktur" name="diskon_faktur"
                                                                    value="0" min="0">
                                                            </td>
                                                            <td>
                                                                <label class="form-label mb-1" for="materai">
                                                                    Materai (Rp)
                                                                </label>
                                                                <input type="number" class="form-control table-input"
                                                                    id="materai" name="materai" value="0"
                                                                    min="0">
                                                            </td>
                                                            <td>
                                                                <label class="form-label mb-1" for="ppn">
                                                                    PPN (%)
                                                                </label>
                                                                <input type="hidden" name="ppn_nominal" value="0">
                                                                <input type="number" class="form-control table-input"
                                                                    id="ppn" name="ppn" value="0"
                                                                    min="0" max="100">
                                                            </td>
                                                            <td class="text-end align-middle">
                                                                <div>Total</div>
                                                                <input type="hidden" value="0" name="total">
                                                                <input type="hidden" value="0" name="total_final">
                                                            </td>
                                                            <td id="discount-display" class="align-middle">Rp 0</td>
                                                            <td class="align-middle">
                                                                (+PPN)
                                                                <span id="total-display">Rp 0</span>
                                                            </td>
                                                            <td></td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Tombol Aksi --}}
                                    <div class="row mt-4">
                                        <div class="col-md-6 d-flex align-items-center">
                                            <a onclick="window.close()"
                                                class="btn btn-lg btn-default waves-effect waves-themed">
                                                <span class="fal fa-arrow-left mr-1 text-primary"></span>
                                                <span class="text-primary">Kembali</span>
                                            </a>
                                        </div>
                                        <div class="col-md-6 btn-action-group">
                                            <button type="submit" id="order-submit-draft"
                                                class="btn btn-lg btn-primary waves-effect waves-themed">
                                                <span class="fal fa-save mr-1"></span>
                                                Simpan Draft
                                            </button>
                                            <button type="submit" id="order-submit-final"
                                                class="btn btn-lg btn-success waves-effect waves-themed">
                                                <span class="fal fa-save mr-1"></span>
                                                Simpan Final
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@section('plugin')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    {{-- Select 2 --}}
    <script src="{{ asset('js/jquery.js') }}"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(".select2").select2();
    </script>
    <script src="{{ asset('js/simrs/warehouse/penerimaan-barang/popup-pharmacy.js') }}?v={{ time() }}"></script>
@endsection
