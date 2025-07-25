@extends('app-type.keuangan.pembayaran-asuransi.template.print')

@section('title', 'LAPORAN PEMBAYARAN ASURANSI')

@section('content')
    <div class="report-header">
        <div class="report-title">LAPORAN PEMBAYARAN ASURANSI</div>
        <div class="report-period">
            PERIODE: {{ \Carbon\Carbon::parse($period_start)->format('d-m-Y') }} s/d
            {{ \Carbon\Carbon::parse($period_end)->format('d-m-Y') }}
        </div>
        <div class="report-info">Penjamin: {{ $penjamin->nama_perusahaan ?? 'Semua Penjamin' }}</div>
        <div class="report-info">Tanggal Cetak: {{ $print_date }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Penjamin</th>
                <th>Tgl. Invoice</th>
                <th>No. Invoice</th>
                <th>Tgl. Kirim</th>
                <th>Jatuh Tempo</th>
                <th>Tgl. Bayar</th>
                <th class="right">Total Tagihan</th>
                <th>KAS/BANK</th>
                <th class="right">Jumlah Bayar</th>
            </tr>
        </thead>
        <tbody>
            @php
                $grandTotalTagihan = 0;
                $grandTotalBayar = 0;
            @endphp

            @foreach ($data as $index => $pembayaran)
                @php
                    // Ambil invoice pertama dari detail
                    $invoice = $pembayaran->details->first()?->konfirmasiAsuransi;
                    $tagihan = $pembayaran->details->sum(fn($d) => $d->konfirmasiAsuransi->jumlah ?? 0);
                    $grandTotalTagihan += $tagihan;
                    $grandTotalBayar += $pembayaran->jumlah;
                @endphp
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td>{{ $pembayaran->penjamin->nama_perusahaan ?? '-' }}</td>
                    <td>{{ optional($invoice?->created_at)->format('d M Y') ?? '-' }}</td>
                    <td>{{ $invoice?->invoice ?? '-' }}</td>
                    <td>{{ optional($invoice?->created_at)->format('d M Y') ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($invoice?->jatuh_tempo)->format('d M Y') ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($pembayaran->tanggal)->format('d M Y') }}</td>
                    <td class="right">{{ number_format($tagihan, 0, ',', '.') }}</td>
                    <td>{{ $pembayaran->bank->name ?? '-' }}</td>
                    <td class="right">{{ number_format($pembayaran->jumlah, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="7" class="right"><strong>Total :</strong></td>
                <td class="right"><strong>{{ number_format($grandTotalTagihan, 0, ',', '.') }}</strong></td>
                <td></td>
                <td class="right"><strong>{{ number_format($grandTotalBayar, 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>
@endsection
