<div class="p-3 bg-gray-100">
    <h5>Detail Item untuk PO: {{ $po->kode_po }}</h5>
    <table class="table table-sm table-bordered table-striped">
        <thead class="bg-info-200">
            <tr>
                <th>#</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Qty</th>
                <th>Bonus</th>
                <th>Harga</th>
                <th>Diskon</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($po->items as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->kode_barang }}</td>
                    <td>{{ $item->nama_barang }}</td>
                    <td class="text-right">{{ $item->qty }}</td>
                    <td class="text-right">{{ $item->qty_bonus }}</td>
                    <td class="text-right">{{ rp($item->harga_barang) }}</td>
                    <td class="text-right">{{ rp($item->discount_nominal) }}</td>
                    <td class="text-right">{{ rp($item->subtotal) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
