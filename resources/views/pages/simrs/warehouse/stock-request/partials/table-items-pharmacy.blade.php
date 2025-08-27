{{-- <tr>
    <th>Kode Barang</th>
    <th>Nama Barang</th>
    <th>Satuan</th>
    <th>Stok Gudang Asal</th>
    <th>Stok Gudang Tujuan</th>
    <th>Min Stok</th>
    <th>Max Stok</th>
    <th>Qty</th>
</tr> --}}
@php
    $si_cache = [];
@endphp
@foreach ($sis_asal as $si)
    @php
        $item = $si->pbi->item;
        $stok_tujuan = 0;

        foreach ($sis_tujuan as $sit) {
            if ($sit->pbi->item->id == $item->id) {
                $stok_tujuan = $sit->qty;
                break;
            }
        }

        $stok_asal = 0;
        foreach ($sis_asal as $sia) {
            if ($sia->pbi->item->id == $item->id) {
                $stok_asal += $sia->qty;
            }
        }

        // barang_id/satuan_id
        $key = $item->id . '/' . $si->pbi->satuan->id;
    @endphp

    @if (isset($si_cache[$key]))
        @continue
    @endif

    @php
        $si_cache[$key] = true; // cache untuk menghindari duplikasi item yang sama dengan satuan yang sama
    @endphp

    <tr class="item pointer stock-based" id="stock{{ $si->id }}" data-type="stock"
        ondblclick="PopupSRPharmacyClass.addItem('stock', {{ $si->id }})" data-item="{{ json_encode($item) }}">
        <td>{{ $item->kode }}</td>
        <td class="item-name">{{ $item->nama }}</td>
        <td>
            <select name="satuan{{ $item->id }}" class="form-control" value="{{ $si->pbi->satuan->id }}">
                <option data-satuan="{{ json_encode($si->pbi->satuan) }}" value="{{ $si->pbi->satuan->id }}" selected>
                    {{ $si->pbi->satuan->nama }}</option>
            </select>
        </td>
        <td class="stock">{{ $stok_asal }}</td>
        <td>{{ $stok_tujuan }}</td>
        <td>Coming Soon!</td>
        <td>Coming Soon!</td>
        <td><input type="number" min="1" name="qty{{ $item->id }}" class="form-control qty" value="1">
        </td>
    </tr>
@endforeach
@foreach ($items as $item)
    <tr class="item pointer barang-based" id="barang{{ $item->id }}" data-type="barang"
        ondblclick="PopupSRPharmacyClass.addItem('barang', {{ $item->id }})" data-item="{{ json_encode($item) }}">
        <td>{{ $item->kode }}</td>
        <td class="item-name">{{ $item->nama }}</td>
        <td>
            <select name="satuan{{ $item->id }}" class="form-control">
                <option data-satuan="{{ json_encode($item->satuan) }}" value="{{ $item->satuan->id }}">
                    {{ $item->satuan->nama }}</option>
                @foreach ($item->satuan_tambahan as $satuan)
                    <option data-satuan="{{ json_encode($satuan) }}" value="{{ $satuan->id }}">{{ $satuan->nama }}
                    </option>
                @endforeach
            </select>
        </td>
        <td>-</td>
        <td>-</td>
        <td>Coming Soon!</td>
        <td>Coming Soon!</td>
        <td><input type="number" min="1" name="qty{{ $item->id }}" class="form-control qty" value="1">
        </td>
    </tr>
@endforeach
