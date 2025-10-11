<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pr ? 'Edit' : 'Tambah' }} Purchase Request</title>
    <link rel="stylesheet" media="screen, print" href="/css/vendors.bundle.css">
    <link rel="stylesheet" media="screen, print" href="/css/app.bundle.css">
    <link rel="stylesheet" media="screen, print" href="/css/formplugins/select2/select2.bundle.css">
    <style>
        body {
            padding: 1.5rem;
            background-color: #f3f3f3;
        }

        .table-input {
            border: 1px solid #ced4da;
            border-radius: .25rem;
            padding: .375rem .75rem;
        }
    </style>
</head>

<body>
    <div class="panel">
        <div class="panel-hdr">
            <h2>{{ $pr ? 'Edit' : 'Tambah' }} Purchase Request (Farmasi)</h2>
        </div>
        <div class="panel-container show">
            <div class="panel-content">
                <form id="pr-form" action="{{ $action }}" method="POST">
                    @csrf
                    @method($method)
                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                    {{-- Form Header --}}
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Tanggal PR</label>
                            <input type="date" class="form-control" name="tanggal_pr"
                                value="{{ old('tanggal_pr', $pr->tanggal_pr ?? now()->format('Y-m-d')) }}" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Gudang</label>
                            <select name="gudang_id" class="form-control select2" required>
                                <option value="" disabled selected>Pilih Gudang</option>
                                @foreach ($gudangs as $gudang)
                                    <option value="{{ $gudang->id }}"
                                        {{ old('gudang_id', $pr->gudang_id ?? '') == $gudang->id ? 'selected' : '' }}>
                                        {{ $gudang->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Tipe</label>
                            <select name="tipe" class="form-control" required>
                                <option value="normal" {{ old('tipe', $pr->tipe ?? '') == 'normal' ? 'selected' : '' }}>
                                    Normal</option>
                                <option value="urgent"
                                    {{ old('tipe', $pr->tipe ?? '') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-control" required>
                                <option value="draft"
                                    {{ old('status', $pr->status ?? '') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="final"
                                    {{ old('status', $pr->status ?? '') == 'final' ? 'selected' : '' }}>Final</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" class="form-control">{{ old('keterangan', $pr->keterangan ?? '') }}</textarea>
                        </div>
                    </div>
                    <hr>
                    {{-- Form Items --}}
                    <h5>Item Permintaan</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Barang</th>
                                    <th>Qty</th>
                                    <th>Satuan</th>
                                    <th>HNA</th>
                                    <th>Keterangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="item-container">
                                @if ($pr && $pr->items)
                                    @foreach ($pr->items as $item)
                                        @include(
                                            'pages.simrs.warehouse.purchase-request.partials.form-item-row',
                                            ['item' => $item]
                                        )
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <button type="button" class="btn btn-primary" id="add-item-row"><i class="fal fa-plus"></i> Tambah
                        Baris</button>
                    <div class="d-flex justify-content-end mt-3">
                        <strong>Total: <span id="total-nominal">Rp 0</span></strong>
                        <input type="hidden" name="nominal" value="{{ old('nominal', $pr->nominal ?? 0) }}">
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" onclick="window.close()">Tutup</button>
                        <button type="submit" class="btn btn-success"><i class="fal fa-save"></i> Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Template untuk baris baru (disembunyikan) --}}
    <table style="display: none;">
        <tbody id="template-row">
            @include('pages.simrs.warehouse.purchase-request.partials.form-item-row', ['item' => null])
        </tbody>
    </table>

    <script src="/js/vendors.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="{{ asset('js/simrs/warehouse/purchase-request/form-handler.js') }}?v={{ time() }}"></script>
</body>

</html>
