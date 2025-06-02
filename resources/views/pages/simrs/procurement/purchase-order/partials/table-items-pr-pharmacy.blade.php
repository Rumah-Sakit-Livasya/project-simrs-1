{{-- <tr>
    <th>Kode PR</th>
    <th>Nama Barang</th>
    <th>Satuan</th>
    <th>Stok All</th>
    <th>Qty APP</th>
    <th>Telah di Order</th>
    <th>Belum di Order</th>
    <th>Qty Order</th>
    <th>Aksi</th>
</tr> --}}
@foreach ($pris as $pri)
    <tr class="item" id="{{ $pri->id }}" data-id_pr="{{ $pri->id }}" data-max_qty="{{ $pri->approved_qty - $pri->ordered_qty }}" data-item="{{ json_encode($pri->barang) }}"
        data-kode_pr="{{ $pri->pr->kode_pr }}">
        <td>{{ $pri->pr->kode_pr }}</td>
        <td class="item-name">{{ $pri->nama_barang }}</td>
        <td>
            <select name="satuan{{ $pri->id }}" class="form-control" hidden>
                <option selected data-satuan="{{ json_encode($pri->satuan) }}" value="{{ $pri->satuan->id }}">
                    {{ $pri->unit_barang }}</option>
            </select>
            <input type="hidden" name="satuan{{ $pri->id }}" value="{{ $pri->satuan_id }}">
            {{ $pri->unit_barang }}
        </td>
        <td>Coming Soon!</td>
        <td>{{ $pri->approved_qty }}</td>
        <td>{{ $pri->ordered_qty }}</td>
        <td>{{ $pri->approved_qty - $pri->ordered_qty }}</td>
        <td><input type="number" min="0" oninput="PopupPOPharmacyClass.enforceNumberLimit(event)" max="{{ $pri->approved_qty - $pri->ordered_qty }}"
                name="qty{{ $pri->id }}" class="form-control qty"
                value="{{ $pri->approved_qty - $pri->ordered_qty }}">
        </td>
        <td><button type="button" class="btn btn-primary btn-sm" data-id="{{ $pri->id }}"
                onclick="PopupPOPharmacyClass.addItem({{ $pri->id }})">
                <i class="fa fa-plus"></i>
            </button></td>
    </tr>
@endforeach
