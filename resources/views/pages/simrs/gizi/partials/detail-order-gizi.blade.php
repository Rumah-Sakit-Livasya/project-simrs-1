<table class="table">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Nama Makanan</th>
            <th scope="col">Harga</th>
            <th scope="col">% Habis</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($order->foods as $ordered_food)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $ordered_food->food->nama }}</td>
                <td> {{ rp($ordered_food->harga) }}
                </td>
                <td>
                    {{ $ordered_food->persentase_habis }}%
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
