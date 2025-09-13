<!DOCTYPE HTML>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Laporan Hapus SEP</title>
    {{-- Anda bisa menyederhanakan CSS atau tetap menggunakan link eksternal --}}
    <link type="text/css" rel="stylesheet" href="http://192.168.1.253/testing/include/styles/ma/bootstrap.css" />
    <link type="text/css" rel="stylesheet" href="http://192.168.1.253/testing/include/styles/ma/custom_style.css?7" />
    <style type="text/css">
        body {
            background-color: #fff;
        }

        a {
            text-decoration: none;
            color: #000;
        }

        #functions {
            margin-bottom: 20px;
        }

        @media print {
            #functions {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <!-- B: Functions -->
        <div id="functions">
            <ul>
                <li><a href="#" onClick="window.print();">Print</a></li>
                {{-- Link XLS sekarang dinamis --}}
                <li><a href="{{ request()->fullUrlWithQuery(['export' => 'xls']) }}">Xls</a></li>
                <li><a href="#" onClick="window.close();">Close</a></li>
            </ul>
        </div>
        <!-- E: Functions -->

        <!-- B: Print View -->
        <div id="previews">
            <h2 class="bdr">
                Laporan Hapus SEP <span><br>
                    Periode Hapus SEP: {{ $awal_periode }} - {{ $akhir_periode }}</span><br>
                <span>Tipe Rawat : {{ $tipe_rawat }}</span><br>
                <span>No. RM / Nama Pasien : {{ $no_rm_pasien ?? '-' }}</span>
            </h2>
            <table width="100%" class="bdr4 pad">
                <thead>
                    <tr>
                        <th width="3%">NO</th>
                        <th width="5%">No. RM</th>
                        <th>Nama Pasien</th>
                        <th width="10%">Tgl. Del</th>
                        {{-- Tambahkan header lain jika ada --}}
                    </tr>
                </thead>
                <tbody>
                    {{-- Gunakan @forelse untuk handle jika data kosong --}}
                    @forelse ($dataLaporan as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->no_rm }}</td>
                            <td>{{ $item->nama_pasien }}</td>
                            <td>{{ $item->tgl_delete }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center;">Data tidak ditemukan !</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
