@forelse ($sbs as $barang)
    {{-- Hapus 'onclick' dari <tr> ini. Juga hapus class 'pointer' agar tidak membingungkan. --}}
    <tr class="item">
        <td>{{ $loop->iteration }}</td>
        <td>{{ tgl($barang->pbi->pb->tanggal_terima) }}</td>
        <td>{{ isset($barang->pbi->tanggal_exp) ? tgl($barang->pbi->tanggal_exp) : '-' }}</td>
        <td class="kode-pb">{{ $barang->pbi->pb->kode_penerimaan }}</td>
        <td class="no-faktur">{{ $barang->pbi->pb->no_faktur }}</td>
        <td>{{ $barang->pbi->batch_no }}</td>
        <td class="item-name">{{ $barang->pbi->nama_barang }}</td>
        <td>{{ $barang->pbi->satuan->nama ?? $barang->pbi->unit_barang }}</td>
        <td>{{ $barang->gudang->nama }}</td>
        <td class="text-center">{{ $barang->qty }}</td>
        <td>-</td> {{-- Kolom Telah Diretur --}}
        <td class="text-right">
            @php
                // Kalkulasi harga retur (harga setelah diskon per item)
                $hargaRetur = $barang->pbi->qty > 0 ? $barang->pbi->subtotal / $barang->pbi->qty : $barang->pbi->harga;
            @endphp
            {{ rp($hargaRetur) }}
        </td>
        {{-- TAMBAHKAN KOLOM AKSI DENGAN TOMBOL "PILIH" --}}
        <td class="text-center">
            <button type="button" class="btn btn-xs btn-primary choose-item-btn" data-item='{{ json_encode($barang) }}'
                title="Pilih item ini"> Pilih
            </button>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="13" class="text-center">Tidak ada item yang dapat diretur untuk supplier ini.</td>
    </tr>
@endforelse
