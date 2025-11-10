<!DOCTYPE HTML>
<html>

<head>
    <title>Print Rekap Penerimaan Kasir</title>
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
    {{-- Menggunakan asset() helper Laravel agar path menjadi dinamis dan benar --}}
    <link rel="stylesheet/less" type="text/css" media="all" href="{{ asset('testing/include/styles/print.css') }}" />
    <script src="{{ asset('testing/include/js/jquerynless.js') }}" type="text/javascript"></script>
    <style type="text/css">
        /* Style tambahan bisa diletakkan di sini jika perlu */
        #previews {
            overflow: visible;
        }

        h2 {
            overflow: visible;
        }

        /* Pastikan sel tabel tidak terpotong saat mencetak */
        td,
        th {
            word-wrap: break-word;
        }
    </style>
</head>

<body>

    <!-- Tombol Fungsi Print dan Tutup -->
    <div id="functions">
        <ul>
            <li><a href="#" onclick="window.print();">Print</a></li>
            <li><a href="#" onclick="window.close()">Close</a></li>
        </ul>
    </div>

    <!-- Area Konten Laporan -->
    <div id="previews">
        <h2 class="bdr">
            Rekap Penerimaan Kasir
            {{-- Mengambil periode dari controller --}}
            <span>Periode : {{ $periodeAwal }} s/d {{ $periodeAkhir }}</span>
        </h2>

        <table width="100%" class="bdr2 pad">
            <thead>
                <tr>
                    <th>REVENUE CENTER</th>
                    {{-- Loop untuk membuat header kolom metode pembayaran secara dinamis --}}
                    @foreach ($paymentMethods as $method)
                        <th style="width: 9%">{{ strtoupper($method) }}</th>
                    @endforeach
                    <th style="width: 9%">SUBTOTAL</th>
                </tr>
            </thead>
            <tbody>
                {{-- Loop utama untuk menampilkan data laporan per baris (per revenue center) --}}
                @forelse ($hasilLaporan as $row)
                    <tr style="text-align: right;">
                        {{-- Kolom pertama: Nama Revenue Center --}}
                        <td style="text-align: left;">
                            {{-- Membuat format nama lebih rapi, contoh: 'rawat-jalan' -> 'Rawat Jalan' --}}
                            {{ \Illuminate\Support\Str::title(str_replace(['-', '_'], ' ', $row->revenue_center)) }}
                        </td>

                        {{-- Loop untuk menampilkan data nominal per metode pembayaran --}}
                        @foreach ($paymentMethods as $method)
                            @php
                                // Membuat nama alias kolom yang sesuai dengan yang dibuat di controller
                                // contoh: 'BCA' -> 'total_bca'
                                $alias = 'total_' . strtolower(str_replace(' ', '_', $method));
                            @endphp
                            <td>
                                {{-- Menampilkan nominal dengan format ribuan --}}
                                {{ number_format($row->{$alias} ?? 0, 0, ',', '.') }}
                            </td>
                        @endforeach

                        {{-- Kolom terakhir: Subtotal per baris --}}
                        <td>
                            {{ number_format($row->subtotal ?? 0, 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    {{-- Tampil jika tidak ada data sama sekali --}}
                    <tr>
                        <td colspan="{{ count($paymentMethods) + 2 }}" style="text-align: center; font-style: italic;">
                            Tidak ada data penerimaan pada periode yang dipilih.
                        </td>
                    </tr>
                @endforelse

                {{-- Baris Total di bagian bawah (Footer) --}}
                @if (count($hasilLaporan) > 0)
                    <tr style="text-align: right; font-weight: bold;">
                        <td style="text-align: left;">Total</td>

                        {{-- Loop untuk menampilkan total per kolom metode pembayaran --}}
                        @foreach ($paymentMethods as $method)
                            @php
                                $alias = 'total_' . strtolower(str_replace(' ', '_', $method));
                            @endphp
                            <td>
                                {{ number_format($totals->{$alias} ?? 0, 0, ',', '.') }}
                            </td>
                        @endforeach

                        {{-- Kolom terakhir: Grand Total --}}
                        <td>
                            {{ number_format($totals->grand_total ?? 0, 0, ',', '.') }}
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

</body>

</html>
