<tr class="item-row">
    <input type="hidden" name="item_id[]" value="{{ $item->id ?? '' }}">
    <td>
        <select name="barang_id[]" class="form-control item-barang select2-dynamic" required>
            <option value="" disabled selected>Pilih Barang...</option>
            @foreach ($barangs as $barang)
                <option value="{{ $barang->id }}" data-satuan="{{ $barang->satuan->nama ?? '' }}"
                    data-satuan-id="{{ $barang->satuan_id ?? '' }}" data-hna="{{ $barang->hna ?? 0 }}"
                    {{ ($item->barang_id ?? '') == $barang->id ? 'selected' : '' }}>
                    {{ $barang->nama }} ({{ $barang->kode }})
                </option>
            @endforeach
        </select>
    </td>
    <td><input type="number" name="qty[]" class="form-control item-qty" value="{{ $item->qty ?? 1 }}" min="1"
            required></td>
    <td>
        <input type="text" class="form-control item-satuan-text" value="{{ $item->unit_barang ?? '' }}" readonly>
        <input type="hidden" name="satuan_id[]" class="item-satuan-id" value="{{ $item->satuan_id ?? '' }}">
    </td>
    <td><input type="number" name="hna[]" class="form-control item-hna" value="{{ $item->harga_barang ?? 0 }}"
            min="0" required></td>
    <td><input type="text" name="keterangan_item[]" class="form-control" value="{{ $item->keterangan ?? '' }}"></td>
    <td class="text-center"><button type="button" class="btn btn-xs btn-danger remove-item-row"><i
                class="fal fa-trash"></i></button></td>
</tr>
