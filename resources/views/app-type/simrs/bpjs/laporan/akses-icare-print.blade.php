<!DOCTYPE HTML>
<html>

<head>
    <title>Laporan Akses ICare</title>
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
    <style type="text/css">
        body {
            font-family: sans-serif;
            font-size: 10px;
            background-color: #fff;
        }

        a {
            text-decoration: none;
            color: #000;
        }

        #functions {
            padding: 10px;
            border-bottom: 1px solid #ccc;
            margin-bottom: 20px;
        }

        #functions ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        #functions ul li {
            display: inline;
            margin-right: 15px;
        }

        #previews {
            width: 100%;
            overflow-x: auto;
        }

        .bdr {
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        table.bdr4 {
            border-collapse: collapse;
            width: 100%;
        }

        .bdr4 th,
        .bdr4 td {
            border: 1px solid #ccc;
            padding: 4px;
            text-align: center;
        }

        .bdr4 th {
            background-color: #f2f2f2;
        }

        .bdr4 td:nth-child(2) {
            text-align: left;
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
            <li><a href="#" onClick="window.print();">Print</a></li>
            <li><a href="#" onClick="window.close();">Close</a></li>
        </ul>
    </div>

    <div id="previews">
        <h2 class="bdr">
            Laporan Rekap Akses ICare<br>
            <span>Periode Akses ICare: {{ $awal_periode }} - {{ $akhir_periode }}</span>
        </h2>
        <table class="bdr4 pad">
            <thead>
                <tr>
                    <th rowspan="2" width="3%">NO</th>
                    <th rowspan="2">Nama Dokter</th>
                    <th rowspan="2" width="5%">Total Akses</th>
                    <th colspan="{{ $jumlah_hari }}">{{ ucfirst($nama_bulan) }}</th>
                </tr>
                <tr>
                    @for ($i = 1; $i <= $jumlah_hari; $i++)
                        <th>{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</th>
                    @endfor
                </tr>
            </thead>
            <tbody>
                @forelse ($dataLaporan as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item['nama_dokter'] }}</td>
                        <td>{{ $item['total'] }}</td>
                        @foreach ($item['harian'] as $jumlah)
                            <td>{{ $jumlah }}</td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $jumlah_hari + 3 }}">Data tidak ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>

</html>
