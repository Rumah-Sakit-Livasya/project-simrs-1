<table class="table">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Parameter</th>
            <th scope="col">Harga</th>
            <th scope="col">Catatan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($order->order_parameter_radiologi as $orderParameter)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $orderParameter->parameter_radiologi->parameter }}</td>
                <td> {{ rp($orderParameter->nominal_rupiah) }}
                </td>
                <td>{{ $orderParameter->catatan }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
