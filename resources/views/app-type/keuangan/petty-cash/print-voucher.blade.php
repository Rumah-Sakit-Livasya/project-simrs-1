@extends('app-type.keuangan.cash-advance.pencairan.print.template')

@section('style')
    <style>
        /* Styling utama untuk halaman print */
        body {
            font-family: 'Arial', sans-serif;
            font-size: 10px;
            color: #000;
        }

        /* Styling untuk Header Container (diambil dari referensi) */
        .header-container {
            display: flex;
            align-items: flex-start;
            /* Ganti ke flex-start agar sejajar atas */
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #000;
            position: relative;
            /* Diperlukan untuk posisi absolut anak */
        }

        .logo {
            width: 80px;
            /* Sedikit lebih kecil agar proporsional */
            height: auto;
            margin-right: 20px;
        }

        .header-info {
            flex-grow: 1;
        }

        .hospital-name {
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .hospital-address {
            font-size: 10pt;
            line-height: 1.4;
        }

        .invoice-number-box {
            position: absolute;
            top: 0;
            right: 0;
            text-align: right;
        }

        .invoice-label {
            font-size: 10pt;
            font-weight: bold;
        }

        .invoice-value {
            font-size: 12pt;
            font-weight: bold;
            border: 1px solid #000;
            padding: 5px 10px;
            display: inline-block;
            margin-top: 5px;
        }

        /* Styling untuk Judul Dokumen */
        .document-title {
            text-align: center;
            font-size: 16pt;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 25px;
            text-decoration: underline;
        }

        /* Styling untuk bagian informasi */
        .info-details {
            margin-bottom: 20px;
            font-size: 10pt;
        }

        .info-details ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .info-details li {
            margin-bottom: 5px;
        }

        .info-details li span:first-child {
            display: inline-block;
            width: 120px;
            font-weight: bold;
        }

        /* Styling untuk tabel konten */
        .content-table {
            width: 100%;
            border-collapse: collapse;
        }

        .content-table thead th {
            border: 1px solid #000;
            padding: 8px;
            background-color: #e9e9e9;
            font-weight: bold;
            text-align: center;
        }

        .content-table tbody td,
        .content-table tfoot td {
            border: 1px solid #000;
            padding: 8px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        /* Styling untuk checklist dokumen */
        .document-checklist {
            border: 1px solid #000;
            border-top: none;
            padding: 10px;
            ;
        }

        .document-checklist img {
            width: 12px;
            height: 12px;
            vertical-align: middle;
            margin-right: 5px;
        }

        /* Styling untuk area tanda tangan */
        .signature-area {
            margin-top: 40px;
            display: flex;
            justify-content: space-around;
            text-align: center;
            width: 100%;
        }

        .signature-box {
            width: 45%;
        }

        .signature-box .signature-name {
            margin-top: 70px;
            display: inline-block;
            padding-top: 5px;
        }
    </style>
@endsection

@section('content')
    <div class="print-container">
        {{-- Fungsi --}}


        {{-- Header --}}
        <div class="header-container">
            <img src="{{ asset('img/logo.png') }}" alt="Logo RS" class="logo"> {{-- Sesuaikan path logo --}}
            <div class="header-info">
                <div class="hospital-name">Rumah Sakit Livasya</div>
                <div class="hospital-address">
                    Jl. Raya Timur III Dawuan No. 875 Kab. Majalengka<br>
                    Phone: 081211151300 | Kab. Majalengka - Jawa Barat
                </div>
            </div>
            <div class="invoice-number-box">
                <div class="invoice-label">KODE VOUCHER</div>
                <div class="invoice-value">{{ $pettycash->kode_transaksi }}</div>
            </div>
        </div>

        {{-- Judul --}}
        <div class="document-title">Voucher Pengeluaran</div>

        {{-- Info --}}
        <div class="info-details">
            <ul>
                <li><span>Tanggal</span>: {{ \Carbon\Carbon::parse($pettycash->tanggal)->format('d F Y') }}</li>
                <li><span>Kas</span>: {{ $pettycash->kas_nama ?? 'N/A' }}</li>
                <li><span>Status</span>: {{ $pettycash->status }}</li>
            </ul>
        </div>

        {{-- Tabel --}}
        <table class="content-table">
            <thead>
                <tr>
                    <th width="5%">NO</th>
                    <th>Tipe Transaksi</th>
                    <th>Keterangan</th>
                    <th width="25%">Nominal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($details as $index => $detail)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $detail->coa_code . ' ' . $detail->coa_name }}</td>
                        <td>{{ $detail->keterangan ?? '' }}</td>
                        <td class="text-right">{{ number_format($detail->nominal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="font-weight: bold; font-style: italic; font-size: 14px;">
                    <td colspan="4" class="text-center">
                        # {{ ucwords($terbilang) }} Rupiah #
                    </td>
                </tr>
                <tr style="font-weight: bold;">
                    <td colspan="3" class="text-right">Total</td>
                    <td class="text-right">{{ number_format($totalAmount, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        {{-- Tanda Tangan --}}
        <div class="signature-area">
            <div class="signature-box">
                <p>Mengetahui,</p>
                <div class="signature-name">(...................................................)</div>
            </div>
            <div class="signature-box">
                <p>Dibukukan Oleh,</p>
                <div class="signature-name">(...................................................)</div>
            </div>
        </div>
    </div>
@endsection
