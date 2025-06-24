<table class="table">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Kode Barang</th>
            <th scope="col">Nama Barang</th>
            <th scope="col">No Batch</th>
            <th scope="col">Tanggal Terima</th>
            <th scope="col">Tanggal Exp</th>
            <th scope="col">Qty Adjustment</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($sa->items as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->stored->pbi->item->kode }}</td>
                <td>{{ $item->stored->pbi->item->nama }}</td>
                <td>{{ $item->stored->pbi->batch_no }}</td>
                <td>{{ tgl($item->stored->pbi->pb->tanggal_terima) }}</td>
                <td>{{ $item->stored->pbi->tanggal_exp ? tgl($item->stored->pbi->tanggal_exp) : '' }}</td>
                <td>{{ $item->qty }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
