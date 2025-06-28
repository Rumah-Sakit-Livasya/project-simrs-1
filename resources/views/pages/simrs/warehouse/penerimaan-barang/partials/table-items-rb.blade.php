{{-- <tr>
     <th>#</th>
    <th>Tanggal Terima</th>
    <th>Tanggal Expired</th>
    <th>Kode PB</th>
    <th>No Faktur</th>
    <th>No Batch</th>
    <th>Nama Barang</th>
    <th>Satuan</th>
    <th>Gudang</th>
    <th>Qty</th>
    <th>Telah Diretur</th>
    <th>Harga</th>
</tr> --}}
@foreach ($sbs as $barang)
    <tr class="pointer item" onclick="PopupReturBarangClass.addItem({{ json_encode($barang) }})">
        <td>{{ $loop->iteration }}</td>
        {{-- @dd($sbs); --}}
        <td>{{ tgl($barang->pbi->pb->tanggal_terima) }}</td>
        <td>{{ tgl($barang->pbi->tanggal_exp) }}</td>
        <td class="kode-pb">{{ $barang->pbi->pb->kode_penerimaan }}</td>
        <td class="no-faktur">{{ $barang->pbi->pb->no_faktur }}</td>
        <td>{{ $barang->pbi->batch_no }}</td>
        <td class="item-name">{{ $barang->pbi->nama_barang }}</td>
        <td>{{ $barang->pbi->unit_barang }}</td>
        <td>{{ $barang->gudang->nama }}</td>
        <td>{{ $barang->qty }}</td>
        <td>Coming Soon!</td>
        <td>
            @php
                $harga = 0;
                if ($barang->pbi->diskon_nominal == 0) {
                    $harga = $barang->pbi->harga;
                } else {
                    // we only know the total of the discount in nominal
                    // we need to know what's the discount percentage
                    $full_price = $barang->pbi->subtotal + $barang->pbi->diskon_nominal;
                    $discount_percentage = ($barang->pbi->diskon_nominal / $full_price) * 100;
                    $harga = $barang->pbi->harga - $harga * ($discount_percentage / 100);
                }
            @endphp
            {{ rp($harga) }}
        </td>
    </tr>
@endforeach
