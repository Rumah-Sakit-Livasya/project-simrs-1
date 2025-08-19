{{-- <tr>
    <th>Kode Barang</th>
    <th>Nama Barang</th>
    <th>Satuan</th>
    <th>Stok Gudang</th>
    <th>Stok All</th>
    <th>Stok Request</th>
    <th>Min Stok</th>
    <th>Max Stok</th>
    <th>Qty</th>
    <th>Aksi</th>
</tr> --}}
@foreach ($items as $item)
    <tr class="item" id="{{ $item->id }}" data-item="{{ json_encode($item) }}">
        <td>{{ $item->kode }}</td>
        <td class="item-name">{{ $item->nama }}</td>
        <td>
            <select name="satuan{{ $item->id }}" class="form-control">
                <option data-satuan="{{ json_encode($item->satuan) }}" value="{{ $item->satuan->id }}">{{ $item->satuan->nama }}</option>
                @foreach ($item->satuan_tambahan as $satuan)
                    <option data-satuan="{{ json_encode($satuan) }}" value="{{ $satuan->id }}">{{ $satuan->nama }}</option>
                @endforeach
            </select>
        </td>
        <td>Coming Soon!</td>
        <td>Coming Soon!</td>
        <td>Coming Soon!</td>
        <td>Coming Soon!</td>
        <td>Coming Soon!</td>
        <td><input type="number" min="0" name="qty{{ $item->id }}" class="form-control qty" value="1">
        </td>
        <td><button type="button" class="btn btn-primary btn-sm" data-id="{{ $item->id }}"
                onclick="PopupPRNPharmacyClass.addItem({{ $item->id }})">
                <i class="fa fa-plus"></i>
            </button></td>
    </tr>
@endforeach
