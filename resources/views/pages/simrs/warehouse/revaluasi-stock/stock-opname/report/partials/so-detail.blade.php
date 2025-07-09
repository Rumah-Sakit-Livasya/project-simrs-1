<table class="table">
    <thead>
        <tr>
            <th>Nama Barang</th>
            <th>Stok Sistem</th>
            <th>Stok Fisik</th>
            <th>Selisih</th>
            <th>HNA (Satuan)</th>
            <th>Nominal</th>
        </tr>
    </thead>
    <tbody>
        @php
            $total = 0;
        @endphp


        @foreach ($sog->stored_items as $item)
            @if ($item->opname && $item->opname->qty != $item->frozen)
                <tr>
                    <td>{{ $item->pbi->item->nama }}</td>
                    <td align="right">{{ $item->frozen }}</td>
                    <td align="right">{{ $item->opname->qty }}</td>
                    <td align="right">{{ $item->opname->qty - $item->frozen }}</td>
                    <td align="right">{{ rp($item->pbi->item->hna) }}</td>
                    <td align="right">{{ rp($item->pbi->item->hna * ($item->opname->qty - $item->frozen)) }}
                    </td>
                </tr>

                @php
                    $total += $item->pbi->item->hna * ($item->opname->qty - $item->frozen);
                @endphp
            @endif
        @endforeach

    </tbody>
    <tfoot>
        <tr style="text-align: right;">
            <td colspan="5">Total</td>
            <td>{{ rp($total) }}</td>
        </tr>
    </tfoot>
</table>
