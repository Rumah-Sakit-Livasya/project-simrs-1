@extends('app-type.keuangan.pembayaran-asuransi.template.print')

@section('title', 'LAPORAN PROSES INVOICE')

@section('content')
    <div class="report-header">
        <div class="report-title">LAPORAN PROSES INVOICE</div>
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

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tgl AR</th>
                <th>Penjamin</th>
                <th>Tgl. Bill</th>
                <th>No. Bill</th>
                <th>Kunjungan</th>
                <th>No. Reg</th>
                <th>No. RM</th>
                <th>Nama Pasien</th>
                <th>No. Invoice</th>
                <th class="right">Jumlah Tagihan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($query as $index => $item)
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                    <td>{{ $item->penjamin->nama_perusahaan ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->registration->tanggal_masuk ?? $item->tanggal)->format('d-m-Y') }}
                    </td>
                    <td>{{ $item->registration->no_bill ?? '-' }}</td>
                    <td>{{ $item->registration->registration_tipe ?? '-' }}</td>
                    <td>{{ $item->registration->registration_number ?? '-' }}</td>
                    <td>{{ $item->registration->patient->medical_record_number ?? '-' }}</td>
                    <td>{{ $item->registration->patient->name ?? '-' }}</td>
                    <td>{{ $item->invoice ?? '-' }}</td>
                    <td class="right">{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="center" style="color: red;">Tidak ada data ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
