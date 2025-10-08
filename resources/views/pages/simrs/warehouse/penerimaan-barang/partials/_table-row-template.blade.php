{{-- Template ini disembunyikan dan akan di-clone oleh JavaScript --}}
<tr class="item-row-template" style="display: none;">
    {{-- Hidden Inputs untuk data yang tidak perlu ditampilkan tapi harus di-submit --}}
    <input type="hidden" name="item_id[]" class="item-id" value="">
    <input type="hidden" name="poi_id[]" class="poi-id" value="">
    <input type="hidden" name="barang_id[]" class="barang-id" value="">
    <input type="hidden" name="kode_barang[]" class="kode-barang" value="">
    <input type="hidden" name="nama_barang[]" class="nama-barang" value="">
    <input type="hidden" name="satuan_id[]" class="satuan-id" value="">
    <input type="hidden" name="subtotal[]" class="subtotal-hidden" value="0">

    {{-- Kolom yang Terlihat oleh Pengguna --}}
    <td class="text-center">
        <div class="form-check">
            <input class="form-check-input is-bonus" type="checkbox" name="is_bonus_flag[]" value="1">
            <label class="form-check-label">Bonus</label>
        </div>
    </td>
    <td>
        <span class="nama-barang-display font-weight-bold">Nama Barang</span>
        <small class="d-block text-muted kode-barang-display">KODE_BARANG</small>
        <div class="mt-1">
            <small class="d-block">PO: <span class="qty-po">0</span> | Sisa: <span class="qty-sisa">0</span></small>
        </div>
    </td>
    <td>
        <input type="text" name="batch_no[]" class="form-control form-control-sm batch-no" placeholder="No. Batch"
            required>
        <input type="date" name="tanggal_exp[]" class="form-control form-control-sm mt-1 tanggal-exp" required>
    </td>
    <td>
        <input type="number" name="qty[]" class="form-control form-control-sm qty" value="0" min="0"
            required>
    </td>
    <td>
        <div class="input-group input-group-sm">
            <div class="input-group-prepend">
                <span class="input-group-text">Rp</span>
            </div>
            <input type="number" name="harga[]" class="form-control harga" value="0" min="0" required>
        </div>
    </td>
    <td>
        <div class="input-group input-group-sm">
            <input type="number" name="diskon_nominal[]" class="form-control diskon-nominal" value="0"
                min="0">
            <div class="input-group-append">
                <span class="input-group-text">Rp</span>
            </div>
        </div>
    </td>
    <td class="text-right font-weight-bold subtotal-display">Rp 0</td>
    <td class="text-center">
        <button type="button" class="btn btn-danger btn-xs btn-icon waves-effect waves-themed btn-delete-item"
            title="Hapus Item">
            <i class="fal fa-times"></i>
        </button>
    </td>
</tr>
