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
            border-radius: 0
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

        #loading-page {
            position: absolute;
            min-height: 100%;
            min-width: 100%;
            background: rgba(0, 0, 0, 0.75);
            border-radius: 0 0 4px 4px;
            z-index: 1000;
        }
    </style>
@endsection
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Form Penerimaan Barang
                            &nbsp; <i id="loading-spinner" class="fas fa-spinner fa-spin"></i>
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div id="loading-page"></div>
                        <div class="panel-content">
                            <form id="form-pr" name="form-pr"
                                action="{{ route('warehouse.penerimaan-barang.non-pharmacy.store') }}" method="post">
                                @csrf
                                @method('post')
                                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                <input type="hidden" name="employee_id" value="{{ auth()->user()->employee->id }}">

                                <div class="row justify-content-center">
                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label class="form-label text-end" for="tanggal_terima">
                                                        Tanggal Penerimaan
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <input readonly type="date" class="form-control" id="datepicker-1"
                                                        placeholder="Select date" name="tanggal_terima"
                                                        value="{{ \Carbon\Carbon::now()->toDateString() }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label class="form-label text-end" for="no_faktur">
                                                        No Faktur / Surat Jalan*
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="text" class="form-control" name="no_faktur" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label class="form-label text-end" for="tipe_terima">
                                                        Tipe Terima
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <select class="form-control" name="tipe_terima" id="tipe_terima">
                                                        {{-- po / npo --}}
                                                        <option value="po"
                                                            {{ old('tipe_terima') == 'po' ? 'selected' : '' }}>Purchase
                                                            Order</option>
                                                        <option value="npo"
                                                            {{ old('tipe_terima') == 'npo' ? 'selected' : '' }}>Non Purchase
                                                            Order</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label class="form-label text-end" for="kode_po">
                                                        Kode PO*
                                                    </label>
                                                </div>

                                                <div class="col-xl">
                                                    <input type="hidden" name="po_id" value="" required>
                                                    <input type="text" class="form-control" name="kode_po" readonly
                                                        required>
                                                </div>
                                                <div class="col-xl-1" id="select-po-btn">
                                                    <i class="pointer" id="pilih-po-btn" title="Pilih Purchase Order"
                                                        data-bs-toggle="modal" data-bs-target="#pilihPOModal">
                                                        <span class="fal fa-search mr-1 text-primary"></span></i>
                                                    @include('pages.simrs.warehouse.penerimaan-barang.partials.modal-pilih-po-non-pharmacy')
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label class="form-label text-end" for="pic_penerima">
                                                        PIC Penerima
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="text" class="form-control" name="pic_penerima">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label class="form-label text-end" for="tipe_bayar">
                                                        Tipe Pembayaran
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    {{-- cash / non_cash --}}
                                                    <select class="form-control form-select" id="tipe_bayar"
                                                        name="tipe_bayar">
                                                        <option value="non_cash">Non Cash</option>
                                                        <option value="cash">Cash</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label class="form-label text-end" for="supplier">
                                                        Supplier*
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="hidden" name="supplier_id" value="">
                                                    <select class="form-select select2" id="supplier" disabled required>
                                                        <option value="" disabled selected hidden>Pilih Supplier
                                                        </option>
                                                        @foreach ($suppliers as $supplier)
                                                            <option value="{{ $supplier->id }}">{{ $supplier->nama }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label class="form-label text-end" for="keterangan">
                                                        Keterangan
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="text" class="form-control" name="keterangan">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label class="form-label text-end" for="kas">
                                                        Kas
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="text" disabled class="form-control" name="kas">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label class="form-label text-end" for="tanggal_faktur">
                                                        Tanggal Faktur
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="date" class="form-control" id="datepicker-1"
                                                        placeholder="Select date" name="tanggal_faktur">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-4" style="text-align: right">
                                                    <label class="form-label text-end" for="gudang_id">
                                                        Gudang*
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <select class="form-control select2" name="gudang_id" id="gudang"
                                                        required>
                                                        <option value="" selected disabled hidden>Pilih Gudang
                                                        </option>
                                                        @foreach ($gudangs as $gudang)
                                                            <option value="{{ $gudang->id }}">{{ $gudang->nama }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        {{-- /// --}}
                                    </div>
                                </div>

                                <hr>

                                <div class="row justify-content-center">
                                    <table class="table table-bordered table-hover table-striped w-100">
                                        <thead class="bg-primary-600">
                                            <tr>
                                                <th>Bonus?</th>
                                                <th>Kode</th>
                                                <th>Nama</th>
                                                <th>Satuan</th>
                                                <th>Exp Date</th>
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

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td class="text-right" colspan="8">
                                                    <button type="button" id="add-btn"
                                                        class="btn btn-primary waves-effect waves-themed">
                                                        <span class="fal fa-plus mr-1"></span>
                                                        Tambah Item
                                                    </button>
                                                    @include('pages.simrs.warehouse.penerimaan-barang.partials.modal-add-item-non-pharmacy')
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-xl-6" style="text-align: right">
                                                                <label class="form-label text-end" for="diskon_faktur">
                                                                    Diskon Faktur (Rp)
                                                                </label>
                                                            </div>
                                                            <div class="col-xl">
                                                                <input type="number" class="form-control" id="diskon-faktur"
                                                                    name="diskon_faktur" value="0" min="0">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-xl-3" style="text-align: right">
                                                                <label class="form-label text-end" for="materai">
                                                                    Materai (Rp)
                                                                </label>
                                                            </div>
                                                            <div class="col-xl">
                                                                <input type="number" class="form-control" id="materai"
                                                                    name="materai" value="0" min="0">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-xl-3" style="text-align: right">
                                                                <label class="form-label text-end" for="ppn">
                                                                    PPN (%)
                                                                </label>
                                                            </div>
                                                            <div class="col-xl">
                                                                <input type="hidden" name="ppn_nominal" value="0">
                                                                <input type="number" class="form-control" id="ppn"
                                                                    name="ppn" value="0" min="0"
                                                                    max="100">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-right">Total
                                                    <input type="hidden" value="0" name="total">
                                                    <input type="hidden" value="0" name="total_final">
                                                </td>
                                                <td id="discount-display">Rp 0</td>
                                                <td>
                                                    (+ PPN)
                                                    <span id="total-display">Rp 0</span>
                                                </td>
                                                <td>{{--  --}}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <div class="col-xl-12 mt-5">
                                    <div class="row">
                                        <div class="col-xl">
                                            <a onclick="window.close()"
                                                class="btn btn-lg btn-default waves-effect waves-themed">
                                                <span class="fal fa-arrow-left mr-1 text-primary"></span>
                                                <span class="text-primary">Kembali</span>
                                            </a>
                                        </div>
                                        <div class="col-xl text-right">
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    {{-- Select 2 --}}
    <script src="{{ asset('js/jquery.js') }}"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(".select2").select2();
    </script>
    <script src="{{ asset('js/simrs/warehouse/penerimaan-barang/popup-non-pharmacy.js') }}?v={{ time() }}"></script>
@endsection
