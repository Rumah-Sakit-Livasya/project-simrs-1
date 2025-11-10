<!DOCTYPE HTML>
<html>

<head>
    <title>Laporan Sisa DP Pasien</title>
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
    {{-- Menggunakan CSS dari template report sistem Anda --}}
    <link rel="stylesheet/less" type="text/css" media="all" href="{{ asset('css/print.css') }}" />
    <script src="{{ asset('js/jquery.js') }}" type="text/javascript"></script>
</head>

<body>
    <!-- B: Functions -->
    <div id="functions">
        <ul>
            <li><a href="#" onClick="window.print();">Print</a></li>
            <li><a href="#" onClick="window.close();">Close</a></li>
        </ul>
    </div>
    <!-- E: Functions -->

    <!-- B: Print View -->
    <div id="previews">
        <h2 class="bdr">
            LAPORAN SISA DP (UANG MUKA) PASIEN
            <span>
                PERIODE TGL INPUT SAMPAI :
                {{ \Carbon\Carbon::parse($filters['sd_tanggal'] ?? now())->format('d M Y') }}
            </span>
            <span>Tipe Kunjungan : {{ $filters['layanan'] ?? 'ALL' }}</span>
        </h2>

        <table width="100%" class="bdr2 pad">
            <thead>
                <tr>
                    <th>No</th>
                    <th>No RM</th>
                    <th>Pasien</th>
                    <th>No Reg</th>
                    <th>Ruang</th>
                    <th>Tgl Reg</th>
                    <th>Tgl DP</th>
                    <th align="right">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @forelse($hasilLaporan as $item)
                    <tr>
                        <td align="center">{{ $loop->iteration }}</td>
                        <td align="center">{{ $item->bilingan->registration->patient->medical_record_number ?? 'N/A' }}
                        </td>
                        <td>{{ $item->bilingan->registration->patient->name ?? 'N/A' }}</td>
                        <td align="center">{{ $item->bilingan->registration->registration_number ?? 'N/A' }}</td>
                        <td>{{ $item->bilingan->registration->departement->name ?? 'N/A' }}</td>
                        <td align="center">
                            {{ \Carbon\Carbon::parse($item->bilingan->registration->created_at)->format('d-m-Y') }}</td>
                        <td align="center">{{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y') }}</td>
                        <td align="right">{{ number_format($item->nominal, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center;">Data tidak ditemukan !</td>
                    </tr>
                @endforelse
            </tbody>
            @if ($hasilLaporan->isNotEmpty())
                <tfoot style="font-weight: bold;">
                    <tr>
                        <td colspan="7" align="right">TOTAL</td>
                        <td align="right">{{ number_format($hasilLaporan->sum('nominal'), 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>
    <!-- E: Print View -->

</body>

</html>
