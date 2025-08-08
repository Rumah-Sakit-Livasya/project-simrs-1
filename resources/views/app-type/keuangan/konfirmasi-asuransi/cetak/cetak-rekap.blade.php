@extends('app-type.keuangan.konfirmasi-asuransi.cetak.template')

@section('title', 'Laporan Rekap Konfirmasi Asuransi')

@section('style')
    <style>
        .header-container {
            position: relative;
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #000;
        }

        .logo {
            width: 100px;
            margin-right: 15px;
        }

        .header-info {
            flex-grow: 1;
        }

        .invoice-number {
            position: absolute;
            top: 20px;
            right: 30px;
            text-align: right;
        }

        .invoice-label {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .invoice-value {
            font-size: 16px;
            font-weight: bold;
            border: 1px solid #000;
            padding: 5px 10px;
            display: inline-block;
        }

        .hospital-name {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .hospital-address {
            font-size: 11px;
            line-height: 1.2;
        }

        h4 {
            text-align: center;
            margin: 0;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px 6px;
            font-size: 11px;
        }

        .footer-total {
            font-weight: bold;
            background-color: #f0f0f0;
        }

        .info {
            margin-top: 10px;
        }

        .note-section {
            margin-top: 25px;
            font-size: 11px;
        }

        .note-section table {
            width: 100%;
            border-collapse: collapse;
        }

        .note-section td {
            border: none;
            padding: 3px 5px;
            vertical-align: top;
        }

        .note-title {
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 5px;
        }
    </style>
@endsection

@section('content')
    <div class="header-container">
        <img src="/img/logo.png" class="logo">
        <div class="header-info">
            <div class="hospital-name">Rumah Sakit Livasya</div>
            <div class="hospital-address">
                Jl. Raya Timur III Dawuan No. 875 Kab. Majalengka<br>
                Phone: 081211151300 | Fax: -<br>
                Kab. Majalengka - Jawa Barat
            </div>
        </div>
        <div class="invoice-number">
            <div class="invoice-label">No. Invoice</div>
            <div class="invoice-value">{{ $data->first()->invoice ?? '-' }}</div>
        </div>
    </div>

    <h4>Rekapitulasi Tagihan</h4>

    <div class="info">
        <p><strong>Periode:</strong>
            @if (!empty($period_start) && !empty($period_end))
                {{ \Carbon\Carbon::parse($period_start)->format('d M Y') }} -
                {{ \Carbon\Carbon::parse($period_end)->format('d M Y') }}
            @else
                Semua Periode
            @endif
        </p>
        @if ($penjamin)
            <p><strong>Penjamin:</strong> {{ $penjamin->nama_perusahaan ?? '-' }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Pasien</th>
                <th>Dokter</th>
                <th>Total Tagihan (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php $totalKeseluruhan = 0; @endphp

            @forelse ($data as $index => $item)
                @php
                    $tagihan = $item->jumlah ?? 0;
                    $totalKeseluruhan += $tagihan;
                @endphp
                <tr class="text-center">
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                    <td>{{ $item->registration->patient->name ?? '-' }}</td>
                    <td>{{ $item->registration->doctor->employee->fullname ?? '-' }}</td>
                    <td> Rp. {{ number_format($tagihan, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>

    </table>

    <div class="note-section">
        <div class="note-title">NOTE :</div>
        <table>
            <tr>
                <td style="width: 20px;">1.</td>
                <td>Mohon 1 (Satu) Invoice dilakukan dalam 1 (Satu) kali pembayaran yang sama.</td>
            </tr>
            <tr>
                <td>2.</td>
                <td>Bila dalam 1 (Satu) Invoice ada tagihan yang belum dibayarkan mohon konfirmasi ke No.
                    <strong>081211151300</strong> (Ghina Harunnita Sukma, S.Ak) atau Email:
                    <strong>rsialivasya114@gmail.com</strong>.
                </td>
            </tr>
            <tr>
                <td>3.</td>
                <td>Bukti transfer pembayaran/Rekap pembayaran harap dikirim melalui Fax atau Email.</td>
            </tr>
        </table>
    </div>
@endsection
