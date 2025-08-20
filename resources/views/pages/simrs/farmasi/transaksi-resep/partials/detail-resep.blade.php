<table class="table">
    <thead>
        <tr>
            <th scope="col">Kode Barang</th>
            <th scope="col">Nama Barang</th>
            <th scope="col">Satuan</th>
            <th scope="col">Qty</th>
            <th scope="col">Harga</th>
            <th scope="col">Embalase</th>
            <th scope="col">Subtotal</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($resep->items as $item)
            @if ($item->tipe == 'obat' && $item->racikan_id === null)
                <tr>
                    <td>{{ $item->stored->pbi->kode_barang }}</td>
                    <td>{{ $item->stored->pbi->nama_barang }}</td>
                    <td>{{ $item->stored->pbi->unit_barang }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>{{ rp($item->harga) }}</td>
                    <td>{{ rp($item->embalase) }}</td>
                    <td>{{ rp($item->subtotal) }}</td>
                </tr>
            @endif
        @endforeach

        @foreach ($resep->items as $item)
            @if ($item->tipe == 'racikan')
                <tr>
                    <td>RACIKAN</td>
                    <td>{{ $item->nama_racikan }}</td>
                    <td></td>
                    <td>{{ $item->qty }}</td>
                    <td>{{ rp($item->harga) }}</td>
                    <td>{{ rp($item->embalase) }}</td>
                    <td>{{ rp($item->subtotal) }}</td>
                </tr>

                @foreach ($resep->items as $item2)
                    @if ($item2->racikan_id == $item->id)
                        <tr>
                            <td>{{ $item2->stored->pbi->kode_barang }}</td>
                            <td><span
                                    class="mdi mdi-subdirectory-arrow-right mdi-24px text-info"></span>{{ $item2->stored->pbi->nama_barang }}</td>
                            <td>{{ $item2->stored->pbi->unit_barang }}</td>
                            <td>{{ $item2->qty }}</td>
                            <td>{{ rp($item2->harga) }}</td>
                            <td>{{ rp($item2->embalase) }}</td>
                            <td>{{ rp($item2->subtotal) }}</td>
                        </tr>
                    @endif
                @endforeach
            @endif
        @endforeach
    </tbody>
</table>
