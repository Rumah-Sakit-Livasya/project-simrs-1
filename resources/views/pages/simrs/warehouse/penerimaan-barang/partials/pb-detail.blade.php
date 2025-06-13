<table class="table">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Kode Barang</th>
            <th scope="col">Nama Barang</th>
            <th scope="col">Unit</th>
            <th scope="col">Expire</th>
            <th scope="col">No Batch</th>
            <th scope="col">Qty</th>
            <th scope="col">Harga</th>
            <th scope="col">Diskon</th>
            <th scope="col">Subtotal</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($pb->items as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->kode_barang }}</td>
                <td>{{ $item->nama_barang }}</td>
                <td>{{ $item->unit_barang }}</td>
                <td>{{ tgl($item->tanggal_exp) }}</td>
                <td>{{ $item->no_batch }}</td>
                <td>{{ $item->qty }}</td>
                <td>{{ rp($item->harga) }}</td>
                <td>{{ rp($item->diskon_nominal) }}</td>
                <td>{{ rp($item->subtotal) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
