<table class="table">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Kode Barang</th>
            <th scope="col">Nama Barang</th>
            <th scope="col">Unit</th>
            <th scope="col">Qty DB</th>
            <th scope="col">Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($db->items as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->barang->kode }}</td>
                <td>{{ $item->barang->nama }}</td>
                <td>{{ $item->satuan->nama }}</td>
                <td>{{ $item->qty }}</td>
                <td>{{ $item->keterangan }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
