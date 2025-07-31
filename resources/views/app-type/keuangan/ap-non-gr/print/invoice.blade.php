@extends('app-type.keuangan.konfirmasi-asuransi.cetak.template') {{-- Atau layout polos Anda --}}

@section('title', 'Cetak Bukti Tukar Faktur - ' . $apSupplier->kode_ap)

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
        {{-- BAGIAN HEADER BARU (SESUAI REFERENSI) --}}
        <div class="header-container">
            {{-- Ganti dengan path logo Anda yang benar --}}
            <img src="{{ asset('img/logo.png') }}" alt="Logo RS" class="logo">
            <div class="header-info">
                <div class="hospital-name">Rumah Sakit Livasya</div>
                <div class="hospital-address">
                    Jl. Raya Timur III Dawuan No. 875 Kab. Majalengka<br>
                    Phone: 081211151300 | Kab. Majalengka - Jawa Barat
                </div>
            </div>
            <div class="invoice-number-box">
                <div class="invoice-label">KODE AP</div>
                <div class="invoice-value">{{ $apSupplier->kode_ap }}</div>
            </div>
        </div>

        {{-- Judul Dokumen --}}
        <div class="document-title">
            BUKTI TUKAR FAKTUR
        </div>

        {{-- Info Supplier dan Tanggal --}}
        <div class="info-details">
            <ul>
                <li><span>Tanggal</span>: {{ $apSupplier->tanggal_ap->format('d M Y') }}</li>
                <li><span>Supplier</span>: {{ $apSupplier->supplier->nama }}</li>
                <li><span>Jatuh Tempo</span>: {{ $apSupplier->due_date->format('d M Y') }}</li>
            </ul>
        </div>

        {{-- Tabel Utama --}}
        <table class="content-table">
            <thead>
                <tr>
                    <th width="5%">NO</th>
                    <th>Kode PO</th>
                    <th>Nomor Invoice</th>
                    <th width="25%">Nominal</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($lineItems as $item)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>

                        {{-- Tampilkan Kode PO yang sudah disiapkan --}}
                        <td class="text-center">{{ $item->po_code }}</td>

                        {{-- Tampilkan Nomor Invoice Supplier --}}
                        <td class="text-center">{{ $item->invoice_no }}</td>

                        {{-- Tampilkan Total untuk baris tersebut --}}
                        <td class="text-right">{{ number_format($apSupplier->grand_total, 2, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Data invoice tidak valid atau tidak ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr style="font-weight: bold;">
                    <td colspan="3" class="text-right">Total</td>
                    <td class="text-right">{{ number_format($apSupplier->grand_total, 2, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        {{-- Checklist Dokumen --}}
        <div class="document-checklist">
            <label>
                <input type="checkbox" disabled {{ $apSupplier->ada_kwitansi ? 'checked' : '' }}>
                Kwitansi
            </label>
            <label>
                <input type="checkbox" disabled {{ $apSupplier->ada_faktur_pajak ? 'checked' : '' }}>
                Faktur Pajak
            </label>
            <label>
                <input type="checkbox" disabled {{ $apSupplier->ada_salinan_po ? 'checked' : '' }}>
                Salinan PO
            </label>
            <label>
                <input type="checkbox" disabled {{ $apSupplier->ada_surat_jalan ? 'checked' : '' }}>
                Surat Jalan
            </label>
            <label>
                <input type="checkbox" disabled {{ $apSupplier->ada_tanda_terima_barang ? 'checked' : '' }}>
                Tanda Terima Barang
            </label>
        </div>


        {{-- Area Tanda Tangan --}}
        <div class="signature-area">
            <div class="signature-box">
                <p>Diterima Oleh,</p>
                <div class="signature-name">(...............................................................)</div>
            </div>
            <div class="signature-box">
                <p>Diserahkan Oleh,</p>
                <div class="signature-name">(..................................................................)</div>
            </div>
        </div>
    </div>
@endsection
