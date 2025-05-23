@extends('app-type.keuangan.pembayaran-asuransi.template.print')
@section('title', 'Cetak Rekap Pembayaran Asuransi')
@section('content')

    <div class="report-header">
        <div class="report-title">@yield('report_title', 'REKAP LAPORAN PEMBAYARAN ASURANSI')</div>
        <div class="report-period">
            PERIODE TGL {{ $period_start ? \Carbon\Carbon::parse($period_start)->format('d-m-Y') : '-' }} s/d
            {{ $period_end ? \Carbon\Carbon::parse($period_end)->format('d-m-Y') : '-' }}
        </div>
    </div>

    <div class="container-fluid">
        <h4 class="mb-4">Rekap Pembayaran Asuransi</h4>
        <table class="table table-bordered table-sm">
            <thead class="thead-dark text-center">
                <tr>
                    <th>No</th>
                    <th>Nama Asuransi</th>
                    <th>Total Tagihan</th>
                    <th>Jumlah Bayar</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $grand_tagihan = 0;
                    $grand_bayar = 0;
                @endphp
                @forelse ($summary as $index => $row)
                    @php
                        // Ambil nama penjamin dari model jika tersedia
                        $penjamin_model = \App\Models\SIMRS\Penjamin::find($row['penjamin_id']);
                        $penjamin_name =
                            $penjamin_model->nama_perusahaan ?? ($row['penjamin_name'] ?? 'Tidak Diketahui');

                        $total_tagihan = $row['total'] ?? 0;
                        $jumlah_bayar = $row['jumlah_bayar'] ?? 0;

                        $grand_tagihan += $total_tagihan;
                        $grand_bayar += $jumlah_bayar;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $penjamin_name }}</td>
                        <td class="text-end">{{ number_format($total_tagihan, 0, ',', '.') }}</td>
                        <td class="text-end">{{ number_format($jumlah_bayar, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada data pembayaran</td>
                    </tr>
                @endforelse
                <tr class="fw-bold bg-light">
                    <td colspan="2" class="text-center">TOTAL</td>
                    <td class="text-end">{{ number_format($grand_tagihan, 0, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($grand_bayar, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
