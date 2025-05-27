<table class="table">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Kode Barang</th>
            <th scope="col">Nama Barang</th>
            <th scope="col">Unit</th>
            <th scope="col">Qty PR</th>
            <th scope="col">Harga/Unit</th>
            <th scope="col">Subtotal</th>
            <th scope="col">Status</th>
            <th scope="col">Qty Approved</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($pr->items as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->barang->kode }}</td>
                <td>{{ $item->barang->nama }}</td>
                <td>{{ $item->unit_barang }}</td>
                <td>{{ $item->qty }}</td>
                <td>{{ rp($item->barang->hna) }}</td>
                <td>{{ rp($item->barang->hna * $item->qty) }}</td>
                <td>{{ ucfirst($item->status) }}</td>
                <td>{{ $item->approved_qty }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
