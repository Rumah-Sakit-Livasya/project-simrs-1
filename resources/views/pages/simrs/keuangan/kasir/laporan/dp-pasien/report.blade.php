<!DOCTYPE HTML>
<html>

<head>
    <title>Print</title>
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
            /* text-align: center; */
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

        .pad {
            padding: 5px;
        }

        @media print {
            #functions {
                display: none;
            }
        }
    </style>
</head>

<body>
    <!-- B: Functions -->
    <div id="functions">
        <ul>
            <li><a href="#" onClick="window.print();">Print</a></li>
            <li><a href="#" onClick="exportToExcel()">xls</a></li>
            <li><a href="#" onClick="window.close();">Close</a></li>
        </ul>
    </div>
    <!-- E: Functions -->

    <!-- B: Print View -->
    <div id="previews">
        <h2 class="bdr">
            LAPORAN DP (UANG MUKA)
            <span>PERIODE TGL INPUT : {{ \Carbon\Carbon::parse($filters['periode_awal'] ?? now())->format('d-m-Y') }}
                s/d {{ \Carbon\Carbon::parse($filters['periode_akhir'] ?? now())->format('d-m-Y') }}</span>
            <span>Tipe Kunjungan : {{ $filters['tipe_kunjungan'] ?? '-' }}</span>
            <span>Status Kunjungan : {{ $filters['status_kunjungan'] ?? '-' }}</span>
            <span>No. RM / Nama Pasien : {{ $filters['no_rm'] ?? '-' }} / {{ $filters['nama_pasien'] ?? '-' }}</span>
            <span>No. Reg : {{ $filters['no_registrasi'] ?? '-' }}</span>
        </h2>

        <table width="100%" class="bdr2 pad">
            <thead>
                <tr>
                    <th>No</th>
                    <th>No RM</th>
                    <th>Pasien</th>
                    <th>No Reg</th>
                    <th>Tgl Reg</th>
                    <th>Ruang</th>
                    <th>Tgl DP</th>
                    <th>Keterangan</th>
                    <th>Tipe DP</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @forelse($hasilLaporan as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->bilingan->registration->patient->medical_record_number ?? 'N/A' }}</td>
                        <td>{{ $item->bilingan->registration->patient->name ?? 'N/A' }}</td>
                        <td>{{ $item->bilingan->registration->registration_number ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->bilingan->registration->created_at)->format('d M Y') }}
                        </td>
                        <td>{{ $item->bilingan->registration->departement->name ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</td>
                        <td>{{ $item->keterangan }}</td>
                        <td>{{ $item->tipe }}</td>
                        <td class="text-right">{{ number_format($item->nominal, 2, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" style="text-align: center;">Data tidak ditemukan !</td>
                    </tr>
                @endforelse
            </tbody>
            @if ($hasilLaporan->count() > 0)
                <tfoot>
                    <tr style="font-weight: bold;">
                        <td colspan="9" class="text-right">Total</td>
                        <td class="text-right">{{ number_format($hasilLaporan->sum('nominal'), 2, ',', '.') }}</td>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>
    <!-- E: Print View -->

    <script>
        function exportToExcel() {
            // Get current URL and parameters
            const currentUrl = window.location.href;
            const excelUrl = currentUrl.replace('/report', '/export-excel');

            // Open Excel export in new window/tab
            window.open(excelUrl, '_blank');
        }

        // Auto print if needed
        @if (request('auto_print'))
            window.onload = function() {
                window.print();
            }
        @endif
    </script>
</body>

</html>
