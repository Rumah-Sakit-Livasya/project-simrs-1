<!DOCTYPE HTML>
<html>

<head>
    <title>Laporan Biaya Lain-Lain</title>
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
            LAPORAN BIAYA LAIN-LAIN
            <span>
                PERIODE TGL INPUT :
                {{ \Carbon\Carbon::parse($filters['periode_awal'] ?? now())->format('d-m-Y') }} s/d
                {{ \Carbon\Carbon::parse($filters['periode_akhir'] ?? now())->format('d-m-Y') }}
            </span>
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
                    <th>Penjamin</th>
                    <th>Tgl Trans</th>
                    <th>Keterangan</th>
                    <th class="text-right">Tagihan</th>
                    <th class="text-right">Share Dokter</th>
                </tr>
            </thead>
            <tbody>
                @forelse($hasilLaporan as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->registration->patient->medical_record_number ?? 'N/A' }}</td>
                        <td>{{ $item->registration->patient->name ?? 'N/A' }}</td>
                        <td>{{ $item->registration->registration_number ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->registration->created_at)->format('d-m-Y') }}</td>
                        <td>{{ $item->registration->departement->name ?? 'N/A' }}</td>
                        <td>{{ $item->registration->penjamin->nama_perusahaan ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->date)->format('d-m-Y H:i') }}</td>
                        <td>{{ $item->tagihan }}</td>
                        <td class="text-right">{{ number_format($item->nominal, 2, ',', '.') }}</td>
                        {{-- Kolom Share Dokter bisa diisi jika ada datanya, jika tidak, biarkan 0 --}}
                        <td class="text-right">0.00</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" style="text-align: center;">Data tidak ditemukan !</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr style="font-weight: bold;">
                    <td colspan="9" class="text-right">TOTAL</td>
                    <td class="text-right">{{ number_format($hasilLaporan->sum('nominal'), 2, ',', '.') }}</td>
                    <td class="text-right">0.00</td>
                </tr>
            </tfoot>
        </table>
    </div>
</body>

</html>
