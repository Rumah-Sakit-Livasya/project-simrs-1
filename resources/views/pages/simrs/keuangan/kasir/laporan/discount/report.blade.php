<!DOCTYPE HTML>
<html>

<head>
    <title>Laporan Discount</title>
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
            LAPORAN DISCOUNT
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
                    <th>No RM</th>
                    <th>Pasien</th>
                    <th>No Reg</th>
                    <th>Tgl Reg</th>
                    <th>Ruang</th>
                    <th>Penjamin</th>
                    <th>Tgl Trans</th>
                    <th>Keterangan</th>
                    <th align="right">Tarif</th>
                    <th align="right">Disc.</th>
                    <th align="right">Tagihan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($hasilLaporan as $item)
                    @php
                        // Hitung total diskon dari nominal (kolom 'diskon') dan persen (kolom 'disc')
                        $diskon_nominal_langsung = (float) ($item->diskon ?? 0);
                        $diskon_dari_persen = ((float) ($item->disc ?? 0) / 100) * (float) $item->nominal;
                        $total_diskon = $diskon_nominal_langsung + $diskon_dari_persen;
                    @endphp
                    <tr>
                        <td align="center">{{ $item->registration->patient->medical_record_number ?? 'N/A' }}</td>
                        <td>{{ $item->registration->patient->name ?? 'N/A' }}</td>
                        <td align="center">{{ $item->registration->registration_number ?? 'N/A' }}</td>
                        <td align="center">
                            {{ \Carbon\Carbon::parse($item->registration->created_at)->format('d-m-Y') }}</td>
                        <td>{{ $item->registration->departement->name ?? 'N/A' }}</td>
                        <td>{{ $item->registration->penjamin->nama_perusahaan ?? 'N/A' }}</td>
                        <td align="center">{{ \Carbon\Carbon::parse($item->date)->format('d-m-Y') }}</td>
                        <td>{{ $item->tagihan }}</td>
                        <td align="right">{{ number_format($item->nominal, 2, ',', '.') }}</td>
                        <td align="right">{{ number_format($total_diskon, 2, ',', '.') }}</td>
                        <td align="right">{{ number_format($item->wajib_bayar, 2, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" style="text-align: center;">Data tidak ditemukan !</td>
                    </tr>
                @endforelse
            </tbody>
            {{-- Footer tidak ditampilkan karena tidak ada di referensi HTML Anda --}}
        </table>
    </div>
    <!-- E: Print View -->
</body>

</html>
