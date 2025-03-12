<table class="table">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Parameter</th>
            <th scope="col">Jumlah</th>
            <th scope="col">Harga</th>
            <th scope="col">Catatan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($order->order_parameter_radiologi as $orderParameter)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $orderParameter->parameter_radiologi->parameter }}</td>
                <td>{{ $orderParameter->qty }}</td>
                <td> {{ (new NumberFormatter('id_ID', NumberFormatter::CURRENCY))->formatCurrency($orderParameter->nominal_rupiah, 'IDR') }}
                </td>
                <td>{{ $orderParameter->catatan }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
