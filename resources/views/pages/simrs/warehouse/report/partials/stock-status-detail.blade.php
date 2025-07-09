<table class="table">
    <thead>
        <tr>
            <th scope="col">Nama Gudang</th>
            <th scope="col">Stock</th>
            <th scope="col">Expired</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($item->gudangs as $gudang)
            @php
                $stock = 0;
                $expired_qty = 0;

                $item->stored_items->map(function ($stored_item) use ($gudang, &$stock, &$expired_qty) {
                    if (request('gudang_id') && request('gudang_id') !== null) {
                        if ($stored_item->gudang_id != request('gudang_id')) {
                            return;
                        }
                    }
                    if ($stored_item->gudang_id == $gudang->id) {
                        $movements = $stored_item->calculateMovementSince(request('tanggal_end') ?: now());

                        $stock += $stored_item->qty - $movements;
                        $expired = \Carbon\Carbon::parse($stored_item->pbi->tanggal_exp)->startOfDay();

                        if ($expired->lt(request('tanggal_end') ?: \Carbon\Carbon::today())) {
                            $expired_qty += $stored_item->qty;
                        }
                    }
                });
            @endphp
            @if ($stock > 0 || $expired_qty > 0)
                <tr>
                    <td>{{ $gudang->nama }}</td>
                    <td>{{ $stock }}</td>
                    <td>{{ $expired_qty }}</td>
                </tr>
            @endif
        @endforeach
    </tbody>
</table>
