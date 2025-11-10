<!DOCTYPE HTML>
<html>

<head>
    <title>Print Laporan Transfer Pasien</title>
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
    <link rel="stylesheet" type="text/css" media="all" href="{{ asset('css/print.css') }}" />
    <script src="{{ asset('js/jquery.js') }}" type="text/javascript"></script>
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
            LAPORAN PASIEN TRANSFER
            <span>PERIODE : {{ \Carbon\Carbon::parse($params['periode_awal'])->format('d-m-Y') }} s/d
                {{ \Carbon\Carbon::parse($params['periode_akhir'])->format('d-m-Y') }}</span>
            <span>Kelas : {{ $params['kelas'] }}</span>
            <span>No. RM / Nama Pasien : {{ $params['no_rm_nama'] }}</span>
        </h2>

        <table width="100%" class="bdr4 pad">
            <thead>
                <tr align="center">
                    <th width="7%" rowspan="2">Tgl. Transfer</th>
                    <th width="7%" rowspan="2">Tgl. Registrasi</th>
                    <th width="5%" rowspan="2">No. RM</th>
                    <th rowspan="2">Nama Pasien</th>
                    <th width="15%" rowspan="2">Penjamin</th>
                    <th colspan="2">Transfer dari</th>
                    <th colspan="2">Transfer Ke</th>
                    <th width="13%" rowspan="2">User</th>
                </tr>
                <tr align="center">
                    <th width="10%" height="24">Ruangan</th>
                    <th width="5%">No Bed</th>
                    <th width="10%">Ruangan</th>
                    <th width="5%">No Bed</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($records as $item)
                    <tr>
                        <td align="center">{{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y H:i') }}</td>
                        <td align="center">
                            {{ \Carbon\Carbon::parse($item->registration->registration_date)->format('d-m-Y') }}</td>
                        <td align="center">{{ $item->registration->patient->medical_record_number ?? 'N/A' }}</td>
                        <td>{{ $item->registration->patient->name ?? 'N/A' }}</td>
                        <td>{{ $item->registration->penjamin->nama_perusahaan ?? 'N/A' }}</td>
                        <td>{{ $item->ruangan_asal->ruangan ?? 'N/A' }}</td>
                        <td align="center">{{ $item->no_bed_asal ?? 'N/A' }}</td>
                        <td>{{ $item->ruangan_tujuan->ruangan ?? 'N/A' }}</td>
                        <td align="center">{{ $item->no_bed_tujuan ?? 'N/A' }}</td>
                        <td>{{ $item->user->name ?? 'N/A' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" style="text-align: center;">Data tidak ditemukan !</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>

</html>
