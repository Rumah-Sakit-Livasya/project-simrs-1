<!DOCTYPE HTML>
<html>

<head>
    <title>Laporan Biaya Lain-Lain</title>
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
                    <th align="right">Tagihan</th>
                    <th align="right">Share Dokter</th>
                </tr>
            </thead>
            <tbody>
                @forelse($hasilLaporan as $item)
                    <tr>
                        <td align="center">{{ $loop->iteration }}</td>
                        <td align="center">{{ $item->registration->patient->medical_record_number ?? 'N/A' }}</td>
                        <td>{{ $item->registration->patient->name ?? 'N/A' }}</td>
                        <td align="center">{{ $item->registration->registration_number ?? 'N/A' }}</td>
                        <td align="center">
                            {{ \Carbon\Carbon::parse($item->registration->created_at)->format('d-m-Y') }}</td>
                        <td>{{ $item->registration->departement->name ?? 'N/A' }}</td>
                        <td>{{ $item->registration->penjamin->nama_perusahaan ?? 'N/A' }}</td>
                        <td align="center">{{ \Carbon\Carbon::parse($item->date)->format('d-m-Y H:i') }}</td>
                        <td>{{ $item->tagihan }}</td>
                        <td align="right">{{ number_format($item->nominal, 2, ',', '.') }}</td>
                        {{-- Kolom Share Dokter bisa diisi jika ada datanya, jika tidak, biarkan 0 --}}
                        <td align="right">0.00</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" style="text-align: center;">Data tidak ditemukan !</td>
                    </tr>
                @endforelse
            </tbody>
            @if ($hasilLaporan->isNotEmpty())
                <tfoot style="font-weight: bold;">
                    <tr>
                        <td colspan="9" align="right">TOTAL</td>
                        <td align="right">{{ number_format($hasilLaporan->sum('nominal'), 2, ',', '.') }}</td>
                        <td align="right">0.00</td>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>
    <!-- E: Print View -->
</body>

</html>
