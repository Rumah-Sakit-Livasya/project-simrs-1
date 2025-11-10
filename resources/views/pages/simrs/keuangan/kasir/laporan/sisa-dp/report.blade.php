<!DOCTYPE HTML>
<html>

<head>
    <title>Laporan Sisa DP Pasien</title>
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            margin: 10px;
        }

        #functions {
            margin-bottom: 20px;
        }

        #functions ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        #functions li {
            display: inline;
            margin-right: 10px;
        }

        #functions a {
            text-decoration: none;
            padding: 5px 10px;
            border: 1px solid #999;
            background: #eee;
            color: #000;
        }

        #previews {
            width: 100%;
        }

        h2.bdr {
            font-size: 14pt;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
            margin-bottom: 15px;
            text-align: center;
        }

        h2.bdr span {
            display: block;
            font-size: 10pt;
            font-weight: normal;
        }

        .bdr2 {
            width: 100%;
            border-collapse: collapse;
        }

        .bdr2 th,
        .bdr2 td {
            border: 1px solid #000;
            padding: 5px;
        }

        .bdr2 thead th {
            background: #e0e0e0;
            font-weight: bold;
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        @media print {
            #functions {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div id="functions">
        <ul>
            <li><a href="#" onclick="window.print();">Print</a></li>
            <li><a href="#" onclick="window.close();">Close</a></li>
        </ul>
    </div>

    <div id="previews">
        <h2 class="bdr">
            LAPORAN SISA DP (UANG MUKA) PASIEN
            <span>
                PERIODE TGL INPUT SAMPAI :
                {{ \Carbon\Carbon::parse($filters['sd_tanggal'] ?? now())->format('d M Y') }}
            </span>
            <span>Tipe Kunjungan : {{ $filters['layanan'] ?? 'ALL' }}</span>
        </h2>

        <table class="bdr2 pad">
            <thead>
                <tr>
                    <th>No</th>
                    <th>No RM</th>
                    <th>Pasien</th>
                    <th>No Reg</th>
                    <th>Ruang</th>
                    <th>Tgl Reg</th>
                    <th>Tgl DP</th>
                    <th class="text-right">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @forelse($hasilLaporan as $item)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">
                            {{ $item->bilingan->registration->patient->medical_record_number ?? 'N/A' }}</td>
                        <td>{{ $item->bilingan->registration->patient->name ?? 'N/A' }}</td>
                        <td class="text-center">{{ $item->bilingan->registration->registration_number ?? 'N/A' }}</td>
                        <td>{{ $item->bilingan->registration->departement->name ?? 'N/A' }}</td>
                        <td class="text-center">
                            {{ \Carbon\Carbon::parse($item->bilingan->registration->created_at)->format('d-m-Y') }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y') }}</td>
                        <td class="text-right">{{ number_format($item->nominal, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">Data tidak ditemukan !</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr style="font-weight: bold;">
                    <td colspan="7" class="text-right">TOTAL</td>
                    <td class="text-right">{{ number_format($hasilLaporan->sum('nominal'), 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</body>

</html>
