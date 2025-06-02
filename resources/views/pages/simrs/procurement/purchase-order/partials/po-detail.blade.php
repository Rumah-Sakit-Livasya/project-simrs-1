<table class="table">
    <thead>
        <tr>
            <th scope="col">Kode PR</th>
            <th scope="col">Kode Barang</th>
            <th scope="col">Nama Barang</th>
            <th scope="col">Unit</th>
            <th scope="col">Qty PO</th>
            <th scope="col">Qty Bonus</th>
            <th scope="col">Harga/Unit</th>
            <th scope="col">Disc (%)</th>
            <th scope="col">Disc (Rp)</th>
            <th scope="col">Subtotal</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($po->items as $item)
            @php
                $total = $item->barang->hna * $item->qty;
                $discount_percentage = $item->discount_nominal ? ($item->discount_nominal / $total) * 100 : 0;
            @endphp
            <tr>
                <td>{{ $item->pr_item && $item->pr_item->pr->kode_pr }}</td>
                <td>{{ $item->barang->kode }}</td>
                <td>{{ $item->barang->nama }}</td>
                <td>{{ $item->unit_barang }}</td>
                <td>{{ $item->qty }}</td>
                <td>{{ $item->qty_bonus }}</td>
                <td>{{ rp($item->subtotal) }}</td>
                <td>{{ $discount_percentage }}%</td>
                <td>{{ rp($item->discount_nominal) }}</td>
                <td>{{ rp($total - $item->discount_nominal) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
