<table class="table">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Kode Penerimaan</th>
            <th scope="col">Kode PO</th>
            <th scope="col">Kode Barang</th>
            <th scope="col">Nama Barang</th>
            <th scope="col">Satuan</th>
            <th scope="col">Exp Date</th>
            <th scope="col">No Batch</th>
            <th scope="col">Qty</th>
            <th scope="col">Harga</th>
            <th scope="col">Subtotal</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($rb->items as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->stored->pbi->pb->kode_penerimaan }}</td>
                <td>{{ $item->stored->pbi->pb->po->kode_po }}</td>
                <td>{{ $item->stored->pbi->kode_barang }}</td>
                <td>{{ $item->stored->pbi->nama_barang }}</td>
                <td>{{ $item->stored->pbi->unit_barang }}</td>
                <td>{{ $item->stored->pbi->tanggal_exp ? tgl($item->stored->pbi->tanggal_exp) : '' }}</td>
                <td>{{ $item->stored->pbi->batch_no }}</td>
                <td>{{ $item->qty }}</td>
                <td>{{ rp($item->harga) }}</td>
                <td>{{ rp($item->subtotal) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
