<div class="p-3 bg-light border rounded">
    <h5>Detail Item untuk {{ $sr->kode_sr }}</h5>
    <table class="child-row-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Barang</th>
                <th>Satuan</th>
                <th>Qty Diminta</th>
                <th>Qty Dipenuhi</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($sr->items as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->barang->nama ?? 'N/A' }}</td>
                    <td>{{ $item->satuan->nama ?? 'N/A' }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>{{ $item->qty_fulfilled }}</td>
                    <td>{{ $item->keterangan }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada item.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
