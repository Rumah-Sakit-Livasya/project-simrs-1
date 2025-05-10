@extends('app-type.keuangan.pembayaran-asuransi.template.print')
@section('title', 'Cetak Laporan Pembayaran Asuransi')
@section('content')


    <div class="report-header">
        <div class="report-title">LAPORAN PEMBAYARAN ASURANSI</div>
        <div class="report-period">
            PERIODE TGL {{ \Carbon\Carbon::parse($period_start)->format('d-m-Y') ?? '-' }} s/d
            {{ \Carbon\Carbon::parse($period_end)->format('d-m-Y') ?? '-' }}
        </div>
        <div class="report-info">
            Penjamin:
            @if (request('penjamin_id'))
                {{ optional($query->first()->penjamin)->nama_perusahaan ?? '-' }}
            @else
                Semua Penjamin
            @endif
        </div>

    </div>

    <div class="container-fluid">
        <h4 class="mb-4">Laporan Pembayaran Asuransi</h4>
        <table class="table table-bordered table-sm">
            <thead class="thead-dark text-center">
                <tr>
                    <th>No</th>
                    <th>Penjamin</th>
                    <th>Tgl. Invoice</th>
                    <th>No. Invoice</th>
                    <th>Tgl. Kirim</th>
                    <th>Jatuh Tempo</th>
                    <th>Tgl. Bayar</th>
                    <th>Total Tagihan</th>
                    <th>Tgl Bayar</th>
                    <th>KAS/BANK</th>
                    <th>Jumlah Bayar</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
@endsection
