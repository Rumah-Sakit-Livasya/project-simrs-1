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
            width: 100%;
        }

        .qty-input {
            max-width: 80px;
            text-align: center;
        }

        /* ========================================================== */
        /* TAMBAHKAN BLOK CSS INI UNTUK BORDER-BOTTOM PADA SEMUA INPUT */
        /* ========================================================== */
        .panel-content .form-control,
        .panel-content .select2-container--default .select2-selection--single {
            border: none;
            /* Hapus semua border default */
            border-bottom: 1px solid #ced4da;
            /* Tambahkan hanya border bawah */
            border-radius: 0;
            /* Hapus lengkungan sudut */
            box-shadow: none;
            /* Hapus shadow saat focus */
            background-color: transparent;
            /* Buat background transparan agar menyatu */
            padding-left: 0;
            /* Hapus padding kiri agar rata */
            padding-right: 0;
        }

        .panel-content .form-control:focus {
            border-bottom: 2px solid #86b7fe;
            box-shadow: none;
        }

        .panel-content .select2-container--default .select2-selection--single {
            background-color: transparent !important;
        }

        .panel-content .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 26px;
            position: absolute;
            top: 5px;
            right: 0px;
        }

        .panel-content .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding-left: 0;
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
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tanggal PR</label>
                            <input type="date" class="form-control" name="tanggal_pr"
                                value="{{ old('tanggal_pr', $pr ? \Carbon\Carbon::parse($pr->tanggal_pr)->format('Y-m-d') : now()->format('Y-m-d')) }}"
                                required>
                        </div>
                        <div class="col-md-4 mb-3">
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
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tipe</label>
                            <select name="tipe" class="form-control" required>
                                <option value="normal" {{ old('tipe', $pr->tipe ?? '') == 'normal' ? 'selected' : '' }}>
                                    Normal</option>
                                <option value="urgent"
                                    {{ old('tipe', $pr->tipe ?? '') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                            </select>
                        </div>
                        {{-- Dropdown Status dihilangkan --}}
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
                            <thead class="bg-primary-600">
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama Barang</th>
                                    <th>Qty</th>
                                    <th>Satuan</th>
                                    <th>HNA</th>
                                    <th>Keterangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="item-container">
                                {{-- Konten di-generate oleh JS --}}
                            </tbody>
                        </table>
                    </div>
                    <button type="button" class="btn btn-primary" id="add-item-popup-btn"><i class="fal fa-plus"></i>
                        Tambah Item</button>

                    <div class="d-flex justify-content-end mt-3">
                        <strong>Total: <span id="total-nominal">Rp 0</span></strong>
                        <input type="hidden" name="nominal" value="{{ $pr->nominal ?? 0 }}">
                    </div>
                    <hr>

                    {{-- Tombol simpan mode draft/final --}}
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" onclick="window.close()">Tutup</button>
                        <div>
                            <button type="submit" name="status" value="draft" class="btn btn-info"><i
                                    class="fal fa-save"></i> Simpan Draft</button>
                            <button type="submit" name="status" value="final" class="btn btn-success"><i
                                    class="fal fa-check"></i> Simpan Final</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="/js/vendors.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        // Data untuk mode edit
        const editData = @json($pr->items ?? []);
    </script>
    <script src="{{ asset('js/simrs/warehouse/purchase-request/form-handler.js') }}?v={{ time() }}"></script>
</body>

</html>
