<table class="table">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Nama Makanan</th>
            <th scope="col">Harga</th>
            <th scope="col">Aktif?</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($menu->makanan_menu as $makanan)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $makanan->makanan->nama }}</td>
                <td> {{ rp($makanan->makanan->harga) }}
                </td>
                <td>{{ $makanan->aktif ? 'Aktif' : 'Non Aktif' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
