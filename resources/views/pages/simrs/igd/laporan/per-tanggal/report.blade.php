<!DOCTYPE HTML>
<html>

<head>
    <title>Print Rekap Pasien Rawat Inap Per Tanggal</title>
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
        <h2 class="bdr">Laporan Rekap Pasien Rawat Inap Per Tanggal
            <span>Periode Tgl. Registrasi : {{ $params['month_name'] }} {{ $params['year'] }}</span>
            <span>Kelas : {{ $params['kelas'] }}</span>
            <span>Penjamin : {{ $params['penjamin'] }}</span>
            <span>Dokter : {{ $params['dokter'] }}</span>
        </h2>

        <table width="100%" class="bdr4 pad">
            <thead>
                <tr>
                    <th width="1%">No</th>
                    <th>Kelas</th>
                    {{-- Generate kolom tanggal secara dinamis --}}
                    @for ($day = 1; $day <= $daysInMonth; $day++)
                        <th width="2%">{{ str_pad($day, 2, '0', STR_PAD_LEFT) }}</th>
                    @endfor
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php $grandTotal = array_fill(1, $daysInMonth, 0); @endphp
                @forelse ($records as $record)
                    @php $rowTotal = 0; @endphp
                    <tr>
                        <td align="center">{{ $loop->iteration }}</td>
                        <td>{{ $record['nama_kelas'] }}</td>
                        @for ($day = 1; $day <= $daysInMonth; $day++)
                            @php
                                $count = $record['counts'][$day] ?? 0;
                                $rowTotal += $count;
                                $grandTotal[$day] += $count;
                            @endphp
                            <td align="center">{{ $count > 0 ? $count : '-' }}</td>
                        @endfor
                        <td align="center"><b>{{ $rowTotal }}</b></td>
                    </tr>
                @empty
                    <tr>
                        {{-- Colspan dinamis: 2 kolom statis + jumlah hari --}}
                        <td colspan="{{ 2 + $daysInMonth + 1 }}" style="text-align: center;">Data tidak ditemukan !
                        </td>
                    </tr>
                @endforelse

                {{-- Baris Grand Total --}}
                @if (count($records) > 0)
                    <tr>
                        <td colspan="2" align="right"><b>TOTAL</b></td>
                        @for ($day = 1; $day <= $daysInMonth; $day++)
                            <td align="center"><b>{{ $grandTotal[$day] > 0 ? $grandTotal[$day] : '-' }}</b></td>
                        @endfor
                        <td align="center"><b>{{ array_sum($grandTotal) }}</b></td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</body>

</html>
