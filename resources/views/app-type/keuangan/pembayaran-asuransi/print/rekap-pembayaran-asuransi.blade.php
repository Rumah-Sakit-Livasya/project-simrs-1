@extends('app-type.keuangan.pembayaran-asuransi.template.print')
@section('title', 'Cetak Rekap Pembayaran Asuransi')
@section('content')

    <div class="report-header">
        <div class="report-title">@yield('report_title', 'REKAP LAPORAN PEMBAYARAN ASURANSI')</div>
        <div class="report-period">
            PERIODE TGL {{ \Carbon\Carbon::parse($period_start)->format('d-m-Y') ?? '-' }} s/d
            {{ \Carbon\Carbon::parse($period_end)->format('d-m-Y') ?? '-' }}
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

            </tbody>
        </table>
    </div>
@endsection
