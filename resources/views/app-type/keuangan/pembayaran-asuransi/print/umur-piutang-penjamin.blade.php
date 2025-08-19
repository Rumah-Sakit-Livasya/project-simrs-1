@extends('app-type.keuangan.pembayaran-asuransi.template.print')
@section('title', 'Cetak Umur Piutang Penjamin')
@section('content')

    <div class="report-header">
        <div class="report-title">LAPORAN UMUR PIUTANG PENJAMIN</div>
        <div class="report-period">
            PERIODE TGL {{ \Carbon\Carbon::parse($period_start)->format('d-m-Y') ?? '-' }} s/d
            {{ \Carbon\Carbon::parse($period_end)->format('d-m-Y') ?? '-' }}
        </div>
        <div class="report-info">Tanggal Cetak: {{ $print_date ?? now()->format('d-m-Y H:i') }}</div>
    </div>

    <div class="container-fluid mt-4">
        <h5 class="mb-3">Laporan Umur Piutang Penjamin</h5>

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
                    <th rowspan="2">Sisa Tagihan</th>
                    <th colspan="4">Umur Piutang</th>
                </tr>
                <tr>
                    <th>&le; 30 Hari</th>
                    <th>31–60 Hari</th>
                    <th>61–90 Hari</th>
                    <th>> 90 Hari</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $item)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal ?? now())->format('d-m-Y') }}</td>
                        <td>{{ $item->invoice ?? '-' }}</td>
                        <td>{{ $item->penjamin->nama_perusahaan ?? '-' }}</td>
                        <td>{{ $item->registration->registration_number ?? '-' }}</td>
                        <td>{{ $item->registration->patient->name ?? '-' }}</td>
                        <td>{{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') : '-' }}
                        </td>
                        <td>{{ $item->jatuh_tempo ? \Carbon\Carbon::parse($item->jatuh_tempo)->format('d-m-Y') : '-' }}
                        </td>
                        <td class="text-end">{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                        <td class="text-end">{{ number_format($item->jumlah_bayar ?? 0, 0, ',', '.') }}</td>
                        <td class="text-end">{{ number_format($item->sisa_tagihan ?? 0, 0, ',', '.') }}</td>
                        <td class="text-end">{{ number_format($item->umur_30, 0, ',', '.') }}</td>
                        <td class="text-end">{{ number_format($item->umur_60, 0, ',', '.') }}</td>
                        <td class="text-end">{{ number_format($item->umur_90, 0, ',', '.') }}</td>
                        <td class="text-end">{{ number_format($item->umur_over, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="15" class="text-center text-danger">Tidak ada data tersedia</td>
                    </tr>
                @endforelse

                @if ($data->isNotEmpty())
                    <tr class="fw-bold bg-light">
                        <td colspan="8" class="text-end">TOTAL</td>
                        <td class="text-end">{{ number_format($periodTotals['tagihan'], 0, ',', '.') }}</td>
                        <td class="text-end">{{ number_format($periodTotals['bayar'], 0, ',', '.') }}</td>
                        <td class="text-end">{{ number_format($periodTotals['sisa'], 0, ',', '.') }}</td>
                        <td class="text-end">{{ number_format($periodTotals['umur_30'], 0, ',', '.') }}</td>
                        <td class="text-end">{{ number_format($periodTotals['umur_60'], 0, ',', '.') }}</td>
                        <td class="text-end">{{ number_format($periodTotals['umur_90'], 0, ',', '.') }}</td>
                        <td class="text-end">{{ number_format($periodTotals['umur_over'], 0, ',', '.') }}</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
@endsection
