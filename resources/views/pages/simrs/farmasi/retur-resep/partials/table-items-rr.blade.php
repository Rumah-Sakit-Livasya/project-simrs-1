{{-- 
    <th>Kode Barang</th>
    <th>Nama Barang</th>
    <th>Satuan</th>
    <th>Tanggal Exp.</th>
    <th>No Batch</th>
    <th>No Resep</th>
    <th>Gudang</th>
    <th>Telah Diretur</th>
    <th>Qty</th>
    <th>Harga</th>
--}}

@foreach ($items as $item)
    <tr class="pointer item-pilih-obat" onclick='ReturResepClass.addItem({{ json_encode($item) }})'>
        <td class='item-code'>{{ $item->stored->pbi->kode_barang }}</td>
        <td class='item-code'>{{ $item->stored->pbi->nama_barang }}</td>
        <td>{{ $item->stored->pbi->unit_barang }}</td>
        <td>{{ tgl($item->stored->pbi->tanggal_exp) }}</td>
        <td class="batch-no">{{ $item->stored->pbi->batch_no }}</td>
        <td class="recipe-no">{{ $item->resep->kode_resep }}</td>
        <td>{{ $item->stored->gudang->nama }}</td>
        <td>{{ $item->returned_qty }}</td>
        <td>{{ $item->qty }}</td>
        <td>{{ rp($item->subtotal) }}</td>
    </tr>
@endforeach
