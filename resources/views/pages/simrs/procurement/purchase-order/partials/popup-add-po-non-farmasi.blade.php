@extends('inc.layout-no-side')
@section('title', 'Purchase Order Form')
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

        .form-control {
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
                            Purchase Order Form
                            &nbsp; <i id="loading-spinner" class="fas fa-spinner fa-spin"></i>
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div id="loading-page"></div>
                        <div class="panel-content">
                            <form id="form-po" name="form-po"
                                action="{{ route('procurement.purchase-order.non-pharmacy.store') }}" method="post">
                                @csrf
                                @method('post')
                                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                <input type="hidden" name="employee_id" value="{{ auth()->user()->employee->id }}">

                                <div class="row justify-content-center">
                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-2" style="text-align: right">
                                                    <label class="form-label text-end" for="tanggal_po">
                                                        Tanggal PO
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="date" class="form-control" id="datepicker-1"
                                                        placeholder="Select date" name="tanggal_po"
                                                        value="{{ \Carbon\Carbon::now()->toDateString() }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-2" style="text-align: right">
                                                    <label class="form-label text-end" for="supplier_id">
                                                        Supplier
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <select name="supplier_id" id="supplier-select"
                                                        class="form-control select2">
                                                        <option value="" hidden disabled selected>Pilih Supplier
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
                                                <div class="col-xl-2" style="text-align: right">
                                                    <label class="form-label text-end" for="top">
                                                        Term Of Payment
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <select name="top" id="top-select" class="form-control">
                                                        <option value="" hidden disabled selected>Pilih Term of
                                                            Payment
                                                        </option>
                                                        <option value="COD">COD</option>
                                                        <option value="7HARI">7 Hari</option>
                                                        <option value="14HARI">14 Hari</option>
                                                        <option value="21HARI">21 Hari</option>
                                                        <option value="24HARI">24 Hari</option>
                                                        <option value="30HARI">30 Hari</option>
                                                        <option value="37HARI">37 Hari</option>
                                                        <option value="40HARI">40 Hari</option>
                                                        <option value="45HARI">45 Hari</option>
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
                                                <div class="col-xl-2" style="text-align: right">
                                                    <label class="form-label text-end" for="tanggal_kirim">
                                                        Tanggal Kirim
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="date" class="form-control" id="datepicker-1"
                                                        placeholder="Select date" name="tanggal_kirim">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-2" style="text-align: right">
                                                    <label class="form-label text-end" for="pic_terima">
                                                        PIC Terima
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <input type="text" class="form-control" name="pic_terima">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-2" style="text-align: right">
                                                    <label class="form-label text-end" for="tipe_top">
                                                        Tipe TOP
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <select name="tipe_top" id="tipe_top-select" class="form-control">
                                                        <option value="SETELAH_TUKAR_FAKTUR" selected>SETELAH TUKAR FAKTUR
                                                        </option>
                                                        <option value="SETELAH_TERIMA_BARANG">SETELAH TERIMA BARANG
                                                        </option>
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
                                                <div class="col-xl-2" style="text-align: right">
                                                    <label class="form-label text-end" for="tipe">
                                                        Tipe Order
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <select name="tipe" class="form-control" required>
                                                        {{-- normal, urgent --}}
                                                        <option value="normal"
                                                            {{ !old('tipe') || old('tipe') == 'normal' ? 'selected' : '' }}>
                                                            Normal
                                                        </option>
                                                        <option value="urgent"
                                                            {{ old('tipe') == 'urgent' ? 'selected' : '' }}>Urgent
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-xl-2" style="text-align: right">
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
                                                <div class="col-xl-2" style="text-align: right">
                                                    <label class="form-label text-end" for="keterangan">
                                                        PPN (%)
                                                    </label>
                                                </div>
                                                <div class="col-xl">
                                                    <input id="ppn-input" type="number" min="0" step="1"
                                                        class="form-control" name="ppn" value="12">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="row justify-content-center">
                                    <table class="table table-bordered table-hover table-striped w-100">
                                        <thead class="bg-primary-600">
                                            <tr>
                                                <th>Kode PR</th>
                                                <th>Nama Barang</th>
                                                <th>Satuan</th>
                                                <th>Qty</th>
                                                <th>Qty Bonus</th>
                                                <th>Harga</th>
                                                <th>Disc(%)</th>
                                                <th>Disc(Rp)</th>
                                                <th>Subtotal</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableItems">

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td class="text-right" colspan="4">
                                                    <button type="button" id="add-btn"
                                                        class="btn btn-primary waves-effect waves-themed">
                                                        <span class="fal fa-plus mr-1"></span>
                                                        Tambah Item
                                                    </button>
                                                    @include('pages.simrs.procurement.purchase-order.partials.modal-add-item')
                                                </td>
                                                <td class="text-right">Total
                                                    <input type="hidden" value="0" name="nominal">
                                                </td>
                                                <td id="harga-display">Rp 0</td>
                                                <td>{{--  --}}</td>
                                                <td id="discount-display">Rp 0</td>
                                                <td>
                                                    (+PPN)
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
    <script src="{{ asset('js/simrs/procurement/purchase-order/popup-non-pharmacy.js') }}?v={{ time() }}"></script>

@endsection
