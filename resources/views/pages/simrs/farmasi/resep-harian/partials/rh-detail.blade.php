@if (isset($resep->resep_manual))
    <p>Resep Manual: {{$resep->resep_manual}}</p>
@endif
<table class="table">
    <thead>
        <tr>
            <th scope="col">Kode Barang</th>
            <th scope="col">Nama Barang</th>
            <th scope="col">Unit</th>
            <th scope="col">Qty Perhari</th>
            <th scope="col">Jumlah Hari</th>
            <th scope="col">Jumlah Diberi</th>
            <th scope="col">Terakhir Diberi</th>
            <th scope="col">Signa</th>
            <th scope="col">Status</th>
            <th scope="col">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($resep->items as $item)
            <tr>
                <td scope="col">{{$item->barang->kode}}</td>
                <td scope="col">{{$item->barang->nama}}</td>
                <td scope="col">{{$item->barang->satuan->nama}}</td>
                <td scope="col">{{$item->qty_perhari}}</td>
                <td scope="col">{{$item->qty_hari}}</td>
                <td scope="col">{{$item->qty_diberi}}</td>
                <td scope="col">{{isset($item->terakhir_diberi) ? tgl_waktu($item->terakhir_diberi) : 'Belum Pernah'}}</td>
                <td scope="col">{{$item->signa}}</td>
                <td scope="col">{{$item->selesai ? 'Selesai' : 'Belum Selesai'}}</td>
                <td scope="col">Coming Soon!</td>
            </tr>
        @endforeach
    </tbody>
</table>
