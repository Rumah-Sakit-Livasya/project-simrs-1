<!DOCTYPE HTML>
<html>

<head>
    <title>Laporan Penerimaan Kasir</title>
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
    {{-- Menggunakan CSS dari sistem Anda --}}
    <link rel="stylesheet/less" type="text/css" media="all" href="{{ asset('css/print.css') }}">
    <script src="{{ asset('js/jquery.js') }}" type="text/javascript"></script>
</head>

<body>
    <!-- B: Functions -->
    <div id="functions">
        <ul>
            <li><a href="#" onclick="window.print();">Print</a></li>
            <li><a href="#" onclick="window.close();">Close</a></li>
        </ul>
    </div>
    <!-- E: Functions -->

    <!-- B: Print View -->
    <div id="previews">
        <h2 class="bdr">
            LAPORAN PENERIMAAN KASIR
            <span>Tanggal : {{ $periodeAwal }} - {{ $periodeAkhir }}</span>
        </h2>

        @php $grandTotal = 0; @endphp

        {{-- Logika diubah: Loop tetap dijalankan, pengecekan data dilakukan di dalam --}}
        @foreach ($groupedData as $namaKasir => $penjaminGroups)
            <h3 class="nul" style="margin-top: 20px;">Petugas Kasir : {{ $namaKasir }}</h3>
            <table width="100%" class="bdr2 pad">
                <thead>
                    <tr>
                        <th width="3%">NO</th>
                        <th width="12%">Tanggal</th>
                        <th>No.Bill/No.Reg<br>Nama Pasien</th>
                        <th width="15%">Poly/Ruang</th>
                        <th width="15%">Metode</th>
                        <th width="20%">Keterangan</th>
                        <th width="12%">Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalKasir = 0;
                        $counter = 1;
                    @endphp

                    @foreach ($penjaminGroups as $namaPenjamin => $transactions)
                        @foreach ($transactions as $item)
                            <tr>
                                <td align="center">{{ $counter++ }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tgl_bayar)->format('d-m-Y H:i') }}</td>
                                <td>
                                    {{ $item->bill_no ?? '' }}/{{ $item->no_reg ?? '' }}<br>
                                    <span>[{{ $item->no_rm ?? '' }}] {{ $item->nama_pasien ?? '' }}</span>
                                </td>
                                <td>{{ $item->nama_poli }}</td>
                                <td>{{ $item->nama_penjamin }}</td>
                                <td>{{ $item->keterangan }}</td>
                                <td align="right">{{ number_format($item->nominal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    @endforeach

                    {{-- Bagian ini hanya akan muncul jika ada data transaksi untuk kasir ini --}}
                    @if ($counter > 1)
                        {{-- Subtotals per payment method --}}
                        @foreach ($penjaminGroups as $namaPenjamin => $transactions)
                            @php
                                $subtotalPenjamin = $transactions->sum('nominal');
                                $totalKasir += $subtotalPenjamin;
                            @endphp
                            <tr style="font-weight: bold;">
                                <td colspan="6" align="right">{{ $namaPenjamin }}</td>
                                <td align="right">{{ number_format($subtotalPenjamin, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach

                        {{-- Total per cashier --}}
                        <tr style="font-weight: bold; background-color: #f0f0f0;">
                            <td colspan="6" align="right">Total {{ $namaKasir }}</td>
                            <td align="right">{{ number_format($totalKasir, 0, ',', '.') }}</td>
                        </tr>
                    @else
                        {{-- Pesan jika tidak ada transaksi untuk kasir ini --}}
                        <tr>
                            <td colspan="7" style="text-align: center;">Tidak ada data transaksi untuk kasir ini.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
            @php $grandTotal += $totalKasir; @endphp
        @endforeach

        {{-- Logika baru: Jika $groupedData benar-benar kosong dari awal --}}
        @if ($groupedData->isEmpty())
            <table width="100%" class="bdr2 pad">
                <thead>
                    <tr>
                        <th width="3%">NO</th>
                        <th width="12%">Tanggal</th>
                        <th>No.Bill/No.Reg<br>Nama Pasien</th>
                        <th width="15%">Poly/Ruang</th>
                        <th width="15%">Metode</th>
                        <th width="20%">Keterangan</th>
                        <th width="12%">Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="7" style="text-align: center;">Data tidak ditemukan !</td>
                    </tr>
                </tbody>
            </table>
        @else
            {{-- Grand Total Section hanya ditampilkan jika ada data --}}
            <table width="100%" style="margin-top: 20px;">
                <tr style="font-weight: bold; font-size: 1.1em;">
                    <td align="right" width="88%">Grand Total</td>
                    <td align="right" width="12%">{{ number_format($grandTotal, 0, ',', '.') }}</td>
                </tr>
            </table>
        @endif
    </div>
</body>

</html>
