<table class="table">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Kode Barang</th>
            <th scope="col">Nama Barang</th>
            <th scope="col">Unit</th>
            <th scope="col">Qty SR</th>
            <th scope="col">Qty Fulfilled</th>
            <th scope="col">Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($sr->items as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->barang->kode }}</td>
                <td>{{ $item->barang->nama }}</td>
                <td>{{ $item->satuan->nama }}</td>
                <td>{{ $item->qty }}</td>
                <td>{{ $item->qty_fulfilled }}</td>
                <td>{{ $item->qty - $item->qty_fulfilled == 0 ? 'Fulfilled' : 'Pending' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
