@extends('app-type.keuangan.cash-advance.pencairan.print.template') {{-- Atau layout polos Anda --}}

@section('title', 'Bukti Pencairan - ' . $pencairan->kode_pencairan)

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
        {{-- BAGIAN HEADER --}}
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
                {{-- Data diganti dengan kode pencairan --}}
                <div class="invoice-value">{{ $pencairan->kode_pencairan }}</div>
            </div>
        </div>

        {{-- Judul Dokumen --}}
        <div class="document-title">
            BUKTI PENCARIAN DANA (CASH ADVANCE)
        </div>

        {{-- Info Detail --}}
        <div class="info-details">
            <ul>
                {{-- Data diganti dengan data pencairan --}}
                <li><span>Tanggal</span>: {{ \Carbon\Carbon::parse($pencairan->tanggal_pencairan)->format('d F Y') }}</li>
                <li><span>Penerima</span>: {{ $pencairan->pengajuan->pengaju->name ?? 'N/A' }}</li>
                <li><span>Sumber Kas/Bank</span>: {{ $pencairan->bank->name ?? 'N/A' }}</li>
                <li><span>Ref. Pengajuan</span>: {{ $pencairan->pengajuan->kode_pengajuan ?? 'N/A' }}</li>
            </ul>
        </div>

        {{-- Tabel Utama --}}
        <table class="content-table">
            <thead>
                <tr>
                    <th width="5%">NO</th>
                    <th>KETERANGAN</th>
                    <th width="25%">NOMINAL</th>
                </tr>
            </thead>
            <tbody>
                {{-- Hanya ada satu baris untuk keterangan --}}
                <tr>
                    <td class="text-center">1</td>
                    {{-- Keterangan diambil dari pengajuan --}}
                    <td>{{ $pencairan->pengajuan->keterangan ?? 'Tidak ada keterangan.' }}</td>
                    {{-- Nominal diambil dari pencairan --}}
                    <td class="text-right">{{ number_format($pencairan->nominal_pencairan, 0, ',', '.') }}</td>
                </tr>
            </tbody>
            <tfoot>
                {{-- Baris terbilang --}}
                <tr style="font-weight: bold; font-style: italic; font-size:18px;">
                    <td colspan="6" class="text-center">
                        # {{ ucwords($terbilang) }} Rupiah #
                    </td>
                </tr>
                {{-- Baris total --}}
                <tr style="font-weight: bold;">
                    <td colspan="2" class="text-right">Total</td>
                    <td class="text-right">{{ number_format($pencairan->nominal_pencairan, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        {{-- Area Tanda Tangan (diadaptasi menjadi 3 kolom) --}}
        <div class="signature-area">
            <div class="signature-box">
                <p>Mengetahui,</p>
                <div class="signature-name">(...................................................)</div>
            </div>
            <div class="signature-box">
                <p>Penerima,</p>
                <div class="signature-name">(...................................................)</div>

            </div>
            <div class="signature-box">
                <p>Dibukukan Oleh,</p>
                <div class="signature-name">(...................................................)</div>

            </div>
        </div>
    </div>
@endsection
