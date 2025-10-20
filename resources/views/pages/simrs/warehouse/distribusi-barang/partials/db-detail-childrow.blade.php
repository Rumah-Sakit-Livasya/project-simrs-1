<div class="p-3 bg-light border rounded">
    <h5>Detail Item untuk {{ $db->kode_db }}</h5>
    <table class="child-row-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Barang</th>
                <th>Satuan</th>
                <th>Qty</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($db->items as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->barang->nama }}</td>
                    <td>{{ $item->satuan->nama }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>{{ $item->keterangan }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada item.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
