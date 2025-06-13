@extends('inc.layout-no-side')
@section('title', 'Form Edit Penerimaan Barang')
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
            width: 60px;
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

        .qty {
            min-width: 80px;
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
                                action="{{ route('warehouse.penerimaan-barang.pharmacy.update', ['id' => $pb->id]) }}"
                                method="post">
                                @csrf
                                @method('put')
                                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                <input type="hidden" name="employee_id" value="{{ auth()->user()->employee->id }}">
                                <input type="hidden" name="pb_id" value="{{ $pb->id }}">


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
                                                        value="{{ $pb->tanggal_terima }}">
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
                                                    <input type="text" class="form-control" name="no_faktur" required
                                                        value="{{ $pb->no_faktur }}">
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
                                                    <select class="form-control" name="tipe_terima" id="tipe_terima"
                                                        disabled>
                                                        {{-- po / npo --}}
                                                        <option value="po"
                                                            {{ $pb->tipe_terima == 'po' ? 'selected' : '' }}>Purchase
                                                            Order</option>
                                                        <option value="npo"
                                                            {{ $pb->tipe_terima == 'npo' ? 'selected' : '' }}>Non Purchase
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
                                                    <input type="hidden" name="po_id" value="{{ $pb->po_id }}"
                                                        required>
                                                    <input type="text" class="form-control" name="kode_po" readonly
                                                        required value="{{ $pb->po?->kode_po }}">
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
                                                    <input type="text" class="form-control" name="pic_penerima"
                                                        value="{{ $pb->pic_penerima }}">
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
                                                        <option value="non_cash"
                                                            {{ $pb->tipe_bayar == 'non_cash' ? 'selected' : '' }}>Non Cash
                                                        </option>
                                                        <option value="cash"
                                                            {{ $pb->tipe_bayar == 'cash' ? 'selected' : '' }}>Cash</option>
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
                                                    <input type="hidden" name="supplier_id"
                                                        value="{{ $pb->supplier_id }}">
                                                    <select class="form-select select2" id="supplier" required
                                                        {{ $pb->po_id ? 'disabled' : '' }}>
                                                        <option value="" disabled selected hidden>Pilih Supplier
                                                        </option>
                                                        @foreach ($suppliers as $supplier)
                                                            <option value="{{ $supplier->id }}"
                                                                {{ $supplier->id == $pb->supplier_id ? 'selected' : '' }}>
                                                                {{ $supplier->nama }}
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
                                                    <input type="text" class="form-control" name="keterangan"
                                                        value="{{ $pb->keterangan }}">
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
                                                    <input type="text" disabled class="form-control" name="kas"
                                                        value="{{ $pb->kas }}">
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
                                                        placeholder="Select date" name="tanggal_faktur"
                                                        value="{{ $pb->tanggal_faktur }}">
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
                                                            <option value="{{ $gudang->id }}"
                                                                {{ $gudang->id == $pb->gudang_id ? 'selected' : '' }}>
                                                                {{ $gudang->nama }}
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
                                            @foreach ($pb->items as $item)
                                                <tr id="item{{ $item->id }}">
                                                    <input type="hidden" name="kode_barang[{{ $item->id }}]"
                                                        value="{{ $item->kode_barang }}">
                                                    <input type="hidden" name="nama_barang[{{ $item->id }}]"
                                                        value="{{ $item->nama_barang }}">
                                                    <input type="hidden" name="barang_id[{{ $item->id }}]"
                                                        value="{{ $item->barang_id }}">
                                                    <input type="hidden" name="unit_barang[{{ $item->id }}]"
                                                        value="{{ $item->unit_barang }}">
                                                    <input type="hidden" name="satuan_id[{{ $item->id }}]"
                                                        value="{{ $item->satuan_id }}">
                                                    <input type="hidden" name="subtotal[{{ $item->id }}]"
                                                        value="{{ $item->subtotal }}">
                                                    <input type="hidden" name="item_id[{{ $item->id }}]"
                                                        value="{{ $item->id }}">

                                                    @if (isset($pb->po_id) && !$pb->po->is_auto)
                                                        <input type="hidden" name="poi_id[{{ $item->id }}]"
                                                            value="{{ $item->poi_id }}">
                                                    @endif

                                                    <td><input type="checkbox" class="form-control"
                                                            name="is_bonus[{{ $item->id }}]"
                                                            onclick="PopupPBPharmacyClass.refreshTotal()"
                                                            {{ $item->is_bonus ? 'checked' : '' }}></td>
                                                    <td>{{ $item->kode_barang }}</td>
                                                    <td>{{ $item->nama_barang }}</td>
                                                    <td>{{ $item->unit_barang }}</td>
                                                    <td><input type="date" name="tanggal_exp[{{ $item->id }}]"
                                                            class="form-control" required
                                                            value="{{ $item->tanggal_exp }}"></td>
                                                    <td><input type="text" name="batch_no[{{ $item->id }}]"
                                                            class="form-control" required value="{{ $item->batch_no }}">
                                                    </td>
                                                    <td>
                                                        @if (isset($pb->po_id) && !$pb->po->is_auto)
                                                            {{ $item->poi->qty }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if (isset($pb->po_id) && !$pb->po->is_auto)
                                                            {{ $item->poi->qty - $item->poi->qty_received }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td><input type="number" name="qty[{{ $item->id }}]"
                                                            class="form-control qty" min="0" step="1"
                                                            @if (isset($pb->po_id) && !$pb->po->is_auto) max="{{ $item->poi->qty - $item->poi->qty_received }}" @endif
                                                            onkeyup="PopupPBPharmacyClass.enforceNumberLimit(event).refreshTotal()"
                                                            onchange="PopupPBPharmacyClass.enforceNumberLimit(event).refreshTotal()"
                                                            required value="{{ $item->qty }}"></td>
                                                    <td>{{ rp($item->harga) }}</td>
                                                    <td><input type="number" name="harga[{{ $item->id }}]"
                                                            class="form-control" value="{{ $item->harga }}" required>
                                                    </td>
                                                    <td><input type="number" name="diskon_percent[{{ $item->id }}]"
                                                            class="form-control" min="0"
                                                            value="{{ ($item->diskon_nominal / ($item->harga * $item->qty)) * 100 }}"
                                                            step="1" max="100"
                                                            onkeyup="PopupPBPharmacyClass.diskonPercentChange(event)"
                                                            onchange="PopupPBPharmacyClass.diskonPercentChange(event)">
                                                    </td>
                                                    <td><input type="number" name="diskon_nominal[{{ $item->id }}]"
                                                            min="0" step="1"
                                                            value="{{ $item->diskon_nominal }}" class="form-control"
                                                            onkeyup="PopupPBPharmacyClass.diskonNominalChange(event)"
                                                            onchange="PopupPBPharmacyClass.diskonNominalChange(event)">
                                                    </td>
                                                    <td class="subtotal-display">Rp
                                                        {{ rp($item->harga * $item->qty - $item->diskon_nominal) }}</td>
                                                    <td>
                                                        @if (!isset($pb->po_id) && !$pb->po->is_auto)
                                                            <a class="mdi mdi-close pointer mdi-24px text-danger delete-btn"
                                                                title="Hapus"
                                                                onclick="PopupPBPharmacyClass.deleteItem({{ $item->id }})"></a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td class="text-right" colspan="7">
                                                    <button type="button" id="add-btn"
                                                        class="btn btn-primary waves-effect waves-themed">
                                                        <span class="fal fa-plus mr-1"></span>
                                                        Tambah Item
                                                    </button>
                                                    @include('pages.simrs.warehouse.penerimaan-barang.partials.modal-add-item-pharmacy')
                                                </td>
                                                <td colspan="2">
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-xl-4" style="text-align: right">
                                                                <label class="form-label text-end" for="diskon_faktur">
                                                                    Diskon Faktur (Rp)
                                                                </label>
                                                            </div>
                                                            <div class="col-xl">
                                                                <input type="number" class="form-control"
                                                                    id="diskon-faktur" name="diskon_faktur"
                                                                    value="{{ $pb->diskon_faktur }}" min="0">
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
                                                                    name="materai" value="{{ $pb->materai }}"
                                                                    min="0">
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
                                                                <input type="hidden" name="ppn_nominal"
                                                                    value="{{ $pb->ppn_nominal }}">
                                                                <input type="number" class="form-control" id="ppn"
                                                                    name="ppn" value="{{ $pb->ppn }}"
                                                                    min="0" max="100">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-right">Total
                                                    <input type="hidden" value="{{ $pb->total }}" name="total">
                                                    <input type="hidden" value="{{ $pb->total_final }}"
                                                        name="total_final">
                                                </td>
                                                <td id="discount-display">{{ rp($pb->items->sum('diskon_nominal')) }}</td>
                                                <td>
                                                    (+ PPN)
                                                    <span id="total-display">{{ rp($pb->total_final) }}</span>
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
                                            @if ($pb->status != 'final')
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
                                            @else
                                                <h1 style="color: red">Data sudah final</h1>
                                                <p>Tidak dapat diubah lagi</p>
                                            @endif
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
    <script src="{{ asset('js/simrs/warehouse/penerimaan-barang/popup-pharmacy.js') }}?v={{ time() }}"></script>
@endsection
