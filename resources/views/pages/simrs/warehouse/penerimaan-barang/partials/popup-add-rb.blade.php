@extends('inc.layout-no-side')
@section('title', 'Form Retur Barang')

@section('extended-css')
    {{-- CSS untuk halaman create --}}
    <link rel="stylesheet" media="screen, print" href="/css/formplugins/select2/select2.bundle.css">
    <style>
        /* CSS untuk halaman create, ringkas hanya baris penting */
        .qty {
            min-width: 80px;
            text-align: center;
        }

        .table-input {
            border: 0;
            border-bottom: 1px solid #eee;
            border-radius: 0;
            padding: .25rem 0;
        }

        .table-input:focus {
            box-shadow: none;
            border-bottom: 1px solid #86b7fe;
        }

        #loading-page {
            position: fixed;
            z-index: 1055;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, .75);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
    </style>
@endsection

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div id="loading-page" style="display: none;">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <h4 class="mt-2 text-primary">Mohon Tunggu...</h4>
        </div>
        <div class="row" id="main-content-row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Form Retur Barang</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            {{-- Menampilkan error validasi --}}
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form id="form-retur" action="{{ route('warehouse.penerimaan-barang.retur-barang.store') }}"
                                method="post">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label" for="tanggal_retur">Tanggal Retur</label>
                                        <input readonly type="date" class="form-control" name="tanggal_retur"
                                            value="{{ \Carbon\Carbon::now()->toDateString() }}">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label" for="supplier_id">Supplier <span
                                                class="text-danger">*</span></label>
                                        <select name="supplier_id" id="supplier" class="form-control select2" required>
                                            <option value="" selected disabled>Pilih Supplier</option>
                                            @foreach ($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}">{{ $supplier->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label" for="gudang_id">Gudang Asal Barang <span
                                                class="text-danger">*</span></label>
                                        <select name="gudang_id_filter" id="gudang" class="form-control select2"
                                            required>
                                            <option value="" selected disabled>Pilih Gudang</option>
                                            @foreach ($gudangs as $gudang)
                                                <option value="{{ $gudang->id }}">{{ $gudang->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label" for="keterangan">Keterangan</label>
                                        <input type="text" class="form-control" name="keterangan"
                                            placeholder="Keterangan">
                                    </div>
                                </div>
                                <hr>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-striped w-100">
                                        <thead class="bg-primary-600">
                                            <tr>
                                                <th>Kode Barang</th>
                                                <th>Nama Barang</th>
                                                <th>No Faktur</th>
                                                <th>Exp Date</th>
                                                <th>No Batch</th>
                                                <th class="text-center">Stok</th>
                                                <th style="width: 10%;">Qty Retur</th>
                                                <th class="text-right">Harga</th>
                                                <th class="text-right">Subtotal</th>
                                                <th class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableItems"></tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="6">
                                                    <button type="button" id="add-btn" class="btn btn-primary" disabled>
                                                        <i class="fal fa-plus"></i> Tambah Item
                                                    </button>
                                                </td>
                                                <td class="align-middle">
                                                    <label class="form-label mb-0" for="ppn">PPN (%)</label>
                                                    <input type="number" class="form-control table-input" id="ppn"
                                                        name="ppn" value="0" min="0" max="100">
                                                    <input type="hidden" name="ppn_nominal" value="0">
                                                </td>
                                                <td class="text-right font-weight-bold align-middle">Total</td>
                                                <td class="text-right font-weight-bold align-middle">(+PPN) <span
                                                        id="total-display">Rp 0</span></td>
                                                <input type="hidden" name="nominal" value="0">
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-md-12 d-flex justify-content-between">
                                        <a href="{{ route('warehouse.penerimaan-barang.retur-barang') }}"
                                            class="btn btn-secondary"><i class="fal fa-arrow-left"></i> Kembali</a>
                                        <button type="submit" id="submit-final" class="btn btn-danger"><i
                                                class="fal fa-save"></i> Simpan Final</button>
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
    {{-- JS untuk halaman create --}}
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="{{ asset('js/simrs/warehouse/penerimaan-barang/popup-retur-barang.js') }}?v={{ time() }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sembunyikan loading ketika DOM siap, tampilkan konten utama
            document.getElementById('loading-page').style.display = 'none';
            document.getElementById('main-content-row').style.display = '';
        });
    </script>
@endsection
