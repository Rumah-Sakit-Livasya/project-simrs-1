<!DOCTYPE HTML>
<html>

<head>
    <title>Laporan DP (Uang Muka)</title>
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
                        <td align="center">
                            {{ \Carbon\Carbon::parse($item->bilingan->registration->created_at)->format('d M Y') }}
                        </td>
                        <td>{{ $item->bilingan->registration->departement->name ?? 'N/A' }}</td>
                        <td align="center">{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</td>
                        <td>{{ $item->keterangan }}</td>
                        <td align="center">{{ $item->tipe }}</td>
                        <td align="right">{{ number_format($item->nominal, 2, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" style="text-align: center;">Data tidak ditemukan !</td>
                    </tr>
                @endforelse
            </tbody>
            @if ($hasilLaporan->isNotEmpty())
                <tfoot style="font-weight: bold;">
                    <tr>
                        <td colspan="9" align="right">Total</td>
                        <td align="right">{{ number_format($hasilLaporan->sum('nominal'), 2, ',', '.') }}</td>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>
    <!-- E: Print View -->

    <script>
        function exportToExcel() {
            // Logika ini mengasumsikan Anda memiliki route untuk export
            const currentUrl = new URL(window.location.href);
            currentUrl.pathname = currentUrl.pathname.replace('/report', '/export');
            window.open(currentUrl.toString(), '_blank');
        }

        @if (request('auto_print'))
            window.onload = function() {
                window.print();
            }
        @endif
    </script>
</body>

</html>
