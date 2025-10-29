<div class="p-4 bg-gray-100 w-full">
    @if ($stockDetails->isNotEmpty())
        <h6 class="mb-2">Rincian Stok per Gudang:</h6>
        <table class="table table-sm table-bordered w-100">
            <thead class="thead-light">
                <tr>
                    <th>Nama Gudang</th>
                    <th class="text-right">Jumlah Stok</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($stockDetails as $detail)
                    <tr>
                        <td>{{ $detail->warehouseMasterGudang->nama }}</td>
                        <td class="text-right">{{ number_format($detail->quantity, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="text-muted">Tidak ada stok untuk item ini di gudang manapun.</div>
    @endif
</div>
