@extends('app-type.keuangan.pembayaran-asuransi.template.print')
@section('title', 'Cetak Rekap Laporan Piutang Penjamin')
@section('content')
    <div class="container-fluid">
        <h4 class="mb-4">Rekap Laporan Piutang Penjamin</h4>
        <table class="table table-bordered table-sm text-center">
            <thead class="thead-dark">
                <tr>
                    <th rowspan="2" class="align-middle">Penjamin</th>
                    <th rowspan="2" class="align-middle">Saldo Awal</th>
                    <th colspan="48">Periode Piutang Tahun {{ $tahun ?? date('Y') }}</th>
                    <th rowspan="2" class="align-middle">Saldo Akhir</th>
                </tr>
                <tr>
                    @foreach (['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'] as $bulan)
                        <th colspan="4">{{ $bulan }} {{ $tahun ?? date('Y') }}</th>
                    @endforeach
                </tr>
                <tr>
                    <th></th>
                    <th></th>
                    @for ($i = 0; $i < 12; $i++)
                        <th>Saldo Awal</th>
                        <th>Piutang</th>
                        <th>Pembayaran</th>
                        <th>Saldo Akhir</th>
                    @endfor
                    <th></th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
@endsection
