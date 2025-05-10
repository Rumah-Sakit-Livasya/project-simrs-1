@extends('app-type.keuangan.pembayaran-asuransi.template.print')
@section('title', 'Cetak Umur Piutang Penjamin')
@section('content')

    <div class="report-header">
        <div class="report-title">@yield('report_title', 'LAPORAN UMUR PIUTANG PINJAMAN')</div>
        <div class="report-period">
            PERIODE TGL {{ \Carbon\Carbon::parse($period_start)->format('d-m-Y') ?? '-' }} s/d
            {{ \Carbon\Carbon::parse($period_end)->format('d-m-Y') ?? '-' }}
        </div>
        <div class="report-info">
            Penjamin:
            @if (request('penjamin_id') && $query->isNotEmpty())
                {{ optional($query->first()->penjamin)->nama_perusahaan ?? '-' }}
            @else
                Semua Penjamin
            @endif
        </div>
    </div>

    <div class="container-fluid">
        <h4 class="mb-4">Laporan Umur Piutang Penjamin</h4>
        <table class="table table-bordered table-sm">
            <thead class="thead-dark text-center align-middle">
                <tr>
                    <th rowspan="2">No</th>
                    <th rowspan="2">Tgl. Invoice</th>
                    <th rowspan="2">No. Invoice</th>
                    <th rowspan="2">Penjamin</th>
                    <th rowspan="2">No. Registrasi</th>
                    <th rowspan="2">Nama Pasien</th>
                    <th rowspan="2">Tgl. Kirim</th>
                    <th rowspan="2">Jatuh Tempo</th>
                    <th rowspan="2">Total Tagihan</th>
                    <th rowspan="2">Jumlah Bayar</th>
                    <th colspan="4">Sisa Tagihan</th>
                </tr>
                <tr>
                    <th>&le; 30 Hari</th>
                    <th>31–60 Hari</th>
                    <th>61–90 Hari</th>
                    <th>> 90 Hari</th>
                </tr>
            </thead>
            <tbody>
                @forelse($query as $item)
                    @php
                        $jatuhTempo = \Carbon\Carbon::parse($item->jatuh_tempo);
                        $tglInvoice = \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y');
                        $tglKirim = \Carbon\Carbon::parse($item->tanggal_kirim)->format('d-m-Y');
                        $jatuhTempoFormatted = $jatuhTempo->format('d-m-Y');
                        $umurHari = \Carbon\Carbon::now()->diffInDays($jatuhTempo, false);
                        $sisaTagihan = $item->jumlah - $item->jumlah_bayar;
                        $umur30 = $umur60 = $umur90 = $umurOver = 0;

                        if ($umurHari <= 30) {
                            $umur30 = $sisaTagihan;
                        } elseif ($umurHari <= 60) {
                            $umur60 = $sisaTagihan;
                        } elseif ($umurHari <= 90) {
                            $umur90 = $sisaTagihan;
                        } else {
                            $umurOver = $sisaTagihan;
                        }
                    @endphp
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $tglInvoice }}</td>
                        <td>{{ $item->invoice ?? '-' }}</td>
                        <td>{{ $item->penjamin->nama_perusahaan ?? '-' }}</td>
                        <td>{{ $item->registration->registration_number ?? '-' }}</td>
                        <td>{{ $item->registration->patient->name ?? '-' }}</td>
                        <td>{{ $tglKirim ?? '-' }}</td>
                        <td>{{ $jatuhTempoFormatted }}</td>
                        <td class="text-right">{{ number_format($item->jumlah ?? 0) }}</td>
                        <td class="text-right">{{ number_format($item->jumlah_bayar ?? 0) }}</td>
                        <td class="text-right">{{ number_format($umur30) }}</td>
                        <td class="text-right">{{ number_format($umur60) }}</td>
                        <td class="text-right">{{ number_format($umur90) }}</td>
                        <td class="text-right">{{ number_format($umurOver) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="14" class="text-center">Tidak ada data tersedia</td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>
@endsection
