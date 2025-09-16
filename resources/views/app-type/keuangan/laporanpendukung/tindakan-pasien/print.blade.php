<!DOCTYPE HTML>
<html>

<head>
    <title>Print Laporan Tindakan Medis</title>
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            margin: 0;
        }

        #functions {
            background: #EDEDED;
            border-bottom: 1px solid #CCCCCC;
            box-shadow: 0 0 2px rgba(0, 0, 0, .5);
            padding: 10px 5px;
            position: relative;
            overflow: hidden;
            top: 0;
            width: 100%;
        }

        #functions ul li {
            display: inline-block;
            margin: 2px 5px 2px 0;
        }

        #functions ul li a {
            background: #FFFFFF;
            text-decoration: none;
            padding: 5px 10px;
            border: 1px solid #CCCCCC;
            box-shadow: 0 0 2px rgba(0, 0, 0, .2);
            color: #000000;
        }

        #previews {
            width: 98%;
            margin: 10px auto;
        }

        h2 {
            text-align: center;
            margin: 0 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 1px solid #000;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th,
        td {
            padding: 5px;
            border: 1px solid #000;
            vertical-align: top;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .no-border td {
            border: none;
            padding: 2px 5px;
        }
    </style>
    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</head>

<body>
    <div id="functions">
        <ul>
            <li><a href="#" onClick="window.print();">Print</a></li>
            <li><a href="#" onClick="window.close();">Close</a></li>
        </ul>
    </div>

    <div id="previews">
        <h2>LAPORAN TINDAKAN PASIEN</h2>
        <table width="100%" class="no-border">
            <tr>
                <td style="width: 25%;">PERIODE TGL. PEMERIKSAAN :</td>
                <td>{{ $tanggalAwal }} s.d. {{ $tanggalAkhir }}</td>
            </tr>
            <tr>
                <td>TIPE RAWAT :</td>
                <td>{{ $tipeRawatNama }}</td>
            </tr>
            <tr>
                <td>DOKTER :</td>
                <td>{{ $dokterNama }}</td>
            </tr>
        </table>

        <table>
            <thead>
                <tr>
                    <th width="15%">TANGGAL PEMERIKSAAN</th>
                    <th>NAMA PASIEN</th>
                    <th width="8%">NO RM</th>
                    <th width="25%">NAMA TINDAKAN</th>
                    <th width="15%">DOKTER (DPJP)</th>
                    <th width="5%">JML</th>
                    <th width="10%">HARGA</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($laporanData as $item)
                    <tr>
                        <td class="text-center">{{ $item->tanggal }}</td>
                        <td>{{ $item->nama_pasien }}</td>
                        <td class="text-center">{{ $item->no_rm }}</td>
                        <td>{{ $item->nama_tindakan }}</td>
                        <td>{{ $item->nama_dokter ?? '-' }}</td>
                        <td class="text-center">{{ $item->jumlah }}</td>
                        <td class="text-right">{{ number_format($item->harga, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada data untuk periode dan filter yang dipilih.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>

</html>
