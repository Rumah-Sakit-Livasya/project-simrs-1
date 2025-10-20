<input type="text" class="form-control mb-3" id="search-item-modal" placeholder="Cari nama barang...">

<div class="table-responsive" style="max-height: 400px;">
    <table class="table table-bordered table-hover table-striped w-100">
        <thead class="bg-primary-600">
            <tr>
                <th>Nama Barang</th>
                <th>Satuan</th>
                <th>Total Stok Tersedia</th>
                <th style="width: 5%;">Pilih</th>
            </tr>
        </thead>
        <tbody id="table-modal-items-body">
            {{-- Loop menggunakan variabel $items yang sudah dikelompokkan --}}
            @forelse ($items as $item)
                <tr class="item-row">
                    <td class="item-name">{{ $item->barang->nama }}</td>
                    <td>{{ $item->satuan->nama }}</td>
                    <td>{{ $item->total_qty }}</td>
                    <td class="text-center">
                        {{-- Data yang dikirim sekarang lebih bersih --}}
                        <button class="btn btn-success btn-xs btn-pilih-item"
                            data-item='{{ json_encode($item->barang) }}' data-satuan='{{ json_encode($item->satuan) }}'
                            data-stok='{{ $item->total_qty }}'>
                            <i class="fal fa-check"></i>
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Tidak ada item stok yang tersedia di gudang asal.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
    $('#search-item-modal').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $("#table-modal-items-body tr.item-row").filter(function() {
            $(this).toggle($(this).find('td.item-name').text().toLowerCase().indexOf(value) > -1)
        });
    });
</script>
