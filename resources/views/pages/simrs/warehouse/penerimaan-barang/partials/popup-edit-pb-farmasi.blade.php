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
            min-width: 80px;
            width: 80px;
            margin-left: 0;
        }

        input,
        select,
        .form-control,
        .form-select {
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

        .table th,
        .table td {
            vertical-align: middle !important;
        }

        .table thead th {
            text-align: center;
        }

        .table tfoot td {
            vertical-align: middle !important;
        }

        .form-label {
            font-weight: 500;
        }

        .btn-action {
            min-width: 120px;
        }
    </style>
@endsection

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row justify-content-center">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Edit Penerimaan Barang
                            <i id="loading-spinner" class="fas fa-spinner fa-spin ml-2"></i>
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div id="loading-page"></div>
                        <div class="panel-content">
                            <form id="form-pr" name="form-pr"
                                action="{{ route('warehouse.penerimaan-barang.pharmacy.update', ['id' => $pb->id]) }}"
                                method="post" autocomplete="off">
                                @csrf
                                @method('put')
                                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                <input type="hidden" name="employee_id" value="{{ auth()->user()->employee->id }}">
                                <input type="hidden" name="pb_id" value="{{ $pb->id }}">

                                <div class="row mb-3">
                                    <div class="col-md-4 mb-2">
                                        <label class="form-label" for="tanggal_terima">Tanggal Penerimaan</label>
                                        <input readonly type="date" class="form-control" name="tanggal_terima"
                                            value="{{ $pb->tanggal_terima }}">
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label class="form-label" for="no_faktur">No Faktur / Surat Jalan*</label>
                                        <input type="text" class="form-control" name="no_faktur" required
                                            value="{{ $pb->no_faktur }}">
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label class="form-label" for="tipe_terima">Tipe Terima</label>
                                        <select class="form-control" name="tipe_terima" id="tipe_terima" disabled>
                                            <option value="po" {{ $pb->tipe_terima == 'po' ? 'selected' : '' }}>Purchase
                                                Order</option>
                                            <option value="npo" {{ $pb->tipe_terima == 'npo' ? 'selected' : '' }}>Non
                                                Purchase Order</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4 mb-2">
                                        <label class="form-label" for="kode_po">Kode PO*</label>
                                        <input type="hidden" name="po_id" value="{{ $pb->po_id }}" required>
                                        <input type="text" class="form-control" name="kode_po" readonly required
                                            value="{{ $pb->po?->kode_po }}">
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label class="form-label" for="pic_penerima">PIC Penerima</label>
                                        <input type="text" class="form-control" name="pic_penerima"
                                            value="{{ $pb->pic_penerima }}">
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label class="form-label" for="tipe_bayar">Tipe Pembayaran</label>
                                        <select class="form-control form-select" id="tipe_bayar" name="tipe_bayar">
                                            <option value="non_cash" {{ $pb->tipe_bayar == 'non_cash' ? 'selected' : '' }}>
                                                Non Cash</option>
                                            <option value="cash" {{ $pb->tipe_bayar == 'cash' ? 'selected' : '' }}>Cash
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4 mb-2">
                                        <label class="form-label" for="supplier">Supplier*</label>
                                        <input type="hidden" name="supplier_id" value="{{ $pb->supplier_id }}">
                                        <select class="form-select select2" id="supplier" required
                                            {{ $pb->po_id ? 'disabled' : '' }}>
                                            <option value="" disabled selected hidden>Pilih Supplier</option>
                                            @foreach ($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}"
                                                    {{ $supplier->id == $pb->supplier_id ? 'selected' : '' }}>
                                                    {{ $supplier->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label class="form-label" for="keterangan">Keterangan</label>
                                        <input type="text" class="form-control" name="keterangan"
                                            value="{{ $pb->keterangan }}">
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label class="form-label" for="kas">Kas</label>
                                        <input type="text" disabled class="form-control" name="kas"
                                            value="{{ $pb->kas }}">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4 mb-2">
                                        <label class="form-label" for="tanggal_faktur">Tanggal Faktur</label>
                                        <input type="date" class="form-control" name="tanggal_faktur"
                                            value="{{ $pb->tanggal_faktur }}">
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label class="form-label" for="gudang_id">Gudang*</label>
                                        <select class="form-control select2" name="gudang_id" id="gudang" required>
                                            <option value="" selected disabled hidden>Pilih Gudang</option>
                                            @foreach ($gudangs as $gudang)
                                                <option value="{{ $gudang->id }}"
                                                    {{ $gudang->id == $pb->gudang_id ? 'selected' : '' }}>
                                                    {{ $gudang->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        {{-- Kosong --}}
                                    </div>
                                </div>

                                <hr>

                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="table-responsive">
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
                                                            <td class="text-center">
                                                                <input type="checkbox" class="form-control"
                                                                    name="is_bonus[{{ $item->id }}]"
                                                                    onclick="PopupPBPharmacyClass.refreshTotal()"
                                                                    {{ $item->is_bonus ? 'checked' : '' }}>
                                                            </td>
                                                            <td class="text-center">{{ $item->kode_barang }}</td>
                                                            <td>{{ $item->nama_barang }}</td>
                                                            <td class="text-center">{{ $item->unit_barang }}</td>
                                                            <td>
                                                                <input type="date"
                                                                    name="tanggal_exp[{{ $item->id }}]"
                                                                    class="form-control" required
                                                                    value="{{ $item->tanggal_exp }}">
                                                            </td>
                                                            <td>
                                                                <input type="text"
                                                                    name="batch_no[{{ $item->id }}]"
                                                                    class="form-control" required
                                                                    value="{{ $item->batch_no }}">
                                                            </td>
                                                            <td class="text-center">
                                                                @if (isset($pb->po_id) && !$pb->po->is_auto)
                                                                    {{ $item->poi->qty }}
                                                                @else
                                                                    -
                                                                @endif
                                                            </td>
                                                            <td class="text-center">
                                                                @if (isset($pb->po_id) && !$pb->po->is_auto)
                                                                    {{ $item->poi->qty - $item->poi->qty_received }}
                                                                @else
                                                                    -
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <input type="number" name="qty[{{ $item->id }}]"
                                                                    class="form-control qty" min="0"
                                                                    step="1"
                                                                    @if (isset($pb->po_id) && !$pb->po->is_auto) max="{{ $item->poi->qty - $item->poi->qty_received }}" @endif
                                                                    onkeyup="PopupPBPharmacyClass.enforceNumberLimit(event).refreshTotal()"
                                                                    onchange="PopupPBPharmacyClass.enforceNumberLimit(event).refreshTotal()"
                                                                    required value="{{ $item->qty }}">
                                                            </td>
                                                            <td class="text-right">{{ rp($item->harga) }}</td>
                                                            <td>
                                                                <input type="number" name="harga[{{ $item->id }}]"
                                                                    class="form-control" value="{{ $item->harga }}"
                                                                    required>
                                                            </td>
                                                            <td>
                                                                <input type="number"
                                                                    name="diskon_percent[{{ $item->id }}]"
                                                                    class="form-control" min="0"
                                                                    value="{{ ($item->diskon_nominal / ($item->harga * $item->qty)) * 100 }}"
                                                                    step="1" max="100"
                                                                    onkeyup="PopupPBPharmacyClass.diskonPercentChange(event)"
                                                                    onchange="PopupPBPharmacyClass.diskonPercentChange(event)">
                                                            </td>
                                                            <td>
                                                                <input type="number"
                                                                    name="diskon_nominal[{{ $item->id }}]"
                                                                    min="0" step="1"
                                                                    value="{{ $item->diskon_nominal }}"
                                                                    class="form-control"
                                                                    onkeyup="PopupPBPharmacyClass.diskonNominalChange(event)"
                                                                    onchange="PopupPBPharmacyClass.diskonNominalChange(event)">
                                                            </td>
                                                            <td class="subtotal-display text-right">
                                                                Rp
                                                                {{ rp($item->harga * $item->qty - $item->diskon_nominal) }}
                                                            </td>
                                                            <td class="text-center">
                                                                @if (isset($pb->po_id) && !$pb->po->is_auto)
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
                                                                class="btn btn-primary waves-effect waves-themed btn-action">
                                                                <span class="fal fa-plus mr-1"></span>
                                                                Tambah Item
                                                            </button>
                                                            @include('pages.simrs.warehouse.penerimaan-barang.partials.modal-add-item-pharmacy')
                                                        </td>
                                                        <td colspan="2">
                                                            <label class="form-label" for="diskon_faktur">Diskon Faktur
                                                                (Rp)</label>
                                                            <input type="number" class="form-control" id="diskon-faktur"
                                                                name="diskon_faktur" value="{{ $pb->diskon_faktur }}"
                                                                min="0">
                                                        </td>
                                                        <td>
                                                            <label class="form-label" for="materai">Materai (Rp)</label>
                                                            <input type="number" class="form-control" id="materai"
                                                                name="materai" value="{{ $pb->materai }}"
                                                                min="0">
                                                        </td>
                                                        <td>
                                                            <label class="form-label" for="ppn">PPN (%)</label>
                                                            <input type="hidden" name="ppn_nominal"
                                                                value="{{ $pb->ppn_nominal }}">
                                                            <input type="number" class="form-control" id="ppn"
                                                                name="ppn" value="{{ $pb->ppn }}"
                                                                min="0" max="100">
                                                        </td>
                                                        <td class="text-right">
                                                            <div>Total</div>
                                                            <input type="hidden" value="{{ $pb->total }}"
                                                                name="total">
                                                            <input type="hidden" value="{{ $pb->total_final }}"
                                                                name="total_final">
                                                        </td>
                                                        <td id="discount-display" class="text-right">
                                                            {{ rp($pb->items->sum('diskon_nominal')) }}
                                                        </td>
                                                        <td class="text-right">
                                                            (+PPN)
                                                            <span id="total-display">{{ rp($pb->total_final) }}</span>
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-6">
                                        <a onclick="window.close()"
                                            class="btn btn-lg btn-default waves-effect waves-themed">
                                            <span class="fal fa-arrow-left mr-1 text-primary"></span>
                                            <span class="text-primary">Kembali</span>
                                        </a>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        @if ($pb->canBeEdited())
                                            <button type="submit" id="order-submit-draft"
                                                class="btn btn-lg btn-primary waves-effect waves-themed btn-action">
                                                <span class="fal fa-save mr-1"></span>
                                                Simpan Draft
                                            </button>
                                            <button type="submit" id="order-submit-final"
                                                class="btn btn-lg btn-success waves-effect waves-themed btn-action">
                                                <span class="fal fa-save mr-1"></span>
                                                Simpan Final
                                            </button>
                                        @else
                                            <h4 class="text-danger">Data sudah final</h4>
                                            <p>Tidak dapat diubah lagi</p>
                                        @endif
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
    <script src="{{ asset('js/jquery.js') }}"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(".select2").select2();
    </script>
    <script src="{{ asset('js/simrs/warehouse/penerimaan-barang/popup-pharmacy.js') }}?v={{ time() }}"></script>
@endsection
