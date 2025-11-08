@extends('inc.layout-no-side')

@section('title', $sr ? 'Edit Stock Request' : 'Buat Stock Request')

@section('extended-css')
    <link rel="stylesheet" href="/css/formplugins/select2/select2.bundle.css">
    <style>
        .form-label {
            font-weight: 500;
        }

        .panel-content {
            padding: 1.5rem;
        }

        #loading-page {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background: rgba(255, 255, 255, 0.8);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #loading-spinner {
            display: none;
        }

        .btn-disabled {
            pointer-events: none;
            opacity: 0.6;
        }
    </style>
@endsection

@section('content')
    {{-- <div id="loading-page">
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div> --}}

    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">
                <div class="panel">
                    <div class="panel-hdr">
                        <h2 class="panel-title">
                            {{ $sr ? 'Edit Stock Request (' . $sr->kode_sr . ')' : 'Buat Stock Request Baru' }}
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form id="form-sr"
                                action="{{ $sr ? route('warehouse.stock-request.pharmacy.update', $sr->id) : route('warehouse.stock-request.pharmacy.store') }}"
                                method="post">
                                @csrf
                                @if ($sr)
                                    @method('PUT')
                                @endif
                                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                <input type="hidden" name="status" id="status-input">

                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label class="form-label" for="tanggal_sr">Tanggal SR*</label>
                                        <input type="date" class="form-control" name="tanggal_sr"
                                            value="{{ old('tanggal_sr', $sr->tanggal_sr ?? now()->toDateString()) }}"
                                            required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="form-label" for="tujuan_gudang_id">Gudang Tujuan (Peminta)*</label>
                                        <select name="tujuan_gudang_id" class="form-control select2" required
                                            style="width:100%">
                                            @foreach ($gudangs as $gudang)
                                                <option value="{{ $gudang->id }}"
                                                    {{ old('tujuan_gudang_id', $sr->tujuan_gudang_id ?? '') == $gudang->id ? 'selected' : '' }}>
                                                    {{ $gudang->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="form-label" for="asal_gudang_id">Gudang Asal (Dimintai)*</label>
                                        <select name="asal_gudang_id" class="form-control select2" required
                                            style="width:100%">
                                            @foreach ($gudang_asals as $gudang)
                                                <option value="{{ $gudang->id }}"
                                                    {{ old('asal_gudang_id', $sr->asal_gudang_id ?? '') == $gudang->id ? 'selected' : '' }}>
                                                    {{ $gudang->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="form-label" for="tipe">Tipe Request*</label>
                                        <select name="tipe" class="form-control select2" style="width:100%">
                                            <option value="normal"
                                                {{ old('tipe', $sr->tipe ?? 'normal') == 'normal' ? 'selected' : '' }}>
                                                Normal</option>
                                            <option value="urgent"
                                                {{ old('tipe', $sr->tipe ?? '') == 'urgent' ? 'selected' : '' }}>Urgent
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-8">
                                        <label class="form-label" for="keterangan">Keterangan</label>
                                        <input type="text" class="form-control" name="keterangan"
                                            value="{{ old('keterangan', $sr->keterangan ?? '') }}"
                                            placeholder="Keterangan tambahan...">
                                    </div>
                                </div>
                                <hr>
                                <h5 class="frame-heading">Item yang Diminta</h5>
                                <table class="table table-bordered table-hover">
                                    <thead class="bg-primary-600">
                                        <tr>
                                            <th class="text-center" style="width:5%">Aksi</th>
                                            <th>Nama Barang</th>
                                            <th style="width:15%">Satuan</th>
                                            <th style="width:12%">Qty</th>
                                            <th style="width:12%">Qty Request</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tableItems">
                                        @if ($sr)
                                            @foreach ($sr->items as $item)
                                                <tr id="item{{ $loop->index }}">
                                                    <input type="hidden" name="item_id[{{ $loop->index }}]"
                                                        value="{{ $item->id }}">
                                                    <input type="hidden" name="barang_id[{{ $loop->index }}]"
                                                        value="{{ $item->barang_id }}">
                                                    <input type="hidden" name="satuan_id[{{ $loop->index }}]"
                                                        value="{{ $item->satuan_id }}">
                                                    <td class="text-center">
                                                        <input type="text" name="poi_id[{{ $key }}]"
                                                            value="{{ $item->poi_id }}">
                                                        <a class="btn btn-danger btn-xs delete-btn"
                                                            data-key-cache="{{ $item->barang_id }}/{{ $item->satuan_id }}">
                                                            <i class="fal fa-times"></i>
                                                        </a>
                                                    </td>
                                                    <td>{{ $item->barang->nama }}</td>
                                                    <td>{{ $item->satuan->nama }}</td>
                                                    <td>
                                                        {{-- Tampilkan total qty stokGudang --}}
                                                        {{ $item->barang->stokGudang->first()->qty ?? '-' }}
                                                    </td>
                                                    <td>
                                                        <input type="number" name="qty[{{ $loop->index }}]"
                                                            class="form-control" value="{{ $item->qty }}"
                                                            min="1">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="keterangan_item[{{ $loop->index }}]"
                                                            class="form-control" value="{{ $item->keterangan }}">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                                <div
                                    class="d-flex justify-content-end p-3 border-faded border-left-0 border-right-0 border-top-0">
                                    <button type="button" id="add-btn" class="btn btn-primary waves-effect waves-themed">
                                        <span class="fal fa-plus mr-1"></span>Tambah Item
                                    </button>
                                </div>

                                <div
                                    class="panel-content border-faded border-left-0 border-right-0 border-bottom-0 d-flex flex-row align-items-center mt-3">
                                    <button type="button" class="btn btn-secondary"
                                        onclick="window.close()">Batal</button>
                                    <div class="ml-auto">
                                        <button type="button" id="order-submit-draft" class="btn btn-primary">
                                            <span class="btn-text">Simpan Draft</span>
                                            <span class="btn-spinner d-none">
                                                <i class="fas fa-spinner fa-spin"></i> Menyimpan...
                                            </span>
                                        </button>
                                        <button type="button" id="order-submit-final" class="btn btn-success">
                                            <span class="btn-text">Simpan Final</span>
                                            <span class="btn-spinner d-none">
                                                <i class="fas fa-spinner fa-spin"></i> Menyimpan...
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('pages.simrs.warehouse.stock-request.partials.modal-add-item')
    </main>
@endsection

@section('plugin')
    <script src="/js/vendors.bundle.js"></script>
    <script src="/js/app.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(document).ready(function() {
            // Inisialisasi semua select2 di halaman ini
            $('.select2').select2();

        });
    </script>
    <script src="{{ asset('js/simrs/warehouse/stock-request/popup-pharmacy.js') }}?v={{ time() }}"></script>
@endsection
