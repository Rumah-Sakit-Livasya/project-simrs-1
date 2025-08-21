<table class="table">
    <thead>
        <tr>
            <th scope="col">No Resep</th>
            <th scope="col">Gudang Asal</th>
            <th scope="col">Kode Barang</th>
            <th scope="col">Nama Barang</th>
            <th scope="col">Unit</th>
            <th scope="col">Qty</th>
            <th scope="col">Harga</th>
            <th scope="col">Subtotal</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($retur->items as $item)
            <tr>
                <td scope="col">{{ $item->ri->resep->kode_resep }}</td>
                <td scope="col">{{ $item->ri->stored->gudang->nama }}</td>
                <td scope="col">{{ $item->ri->stored->pbi->kode_barang }}</td>
                <td scope="col">{{ $item->ri->stored->pbi->nama_barang }}</td>
                <td scope="col">{{ $item->ri->stored->pbi->unit_barang }}</td>
                <td scope="col">{{ $item->qty }}</td>
                <td scope="col">{{ rp($item->harga) }}</td>
                <td scope="col">{{ rp($item->subtotal) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
