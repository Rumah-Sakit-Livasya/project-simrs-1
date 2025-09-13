@extends('app-type.keuangan.konfirmasi-asuransi.cetak.template')

@section('title', 'Cetak Kwitansi Pembayaran')

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

        .hospital-name {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .hospital-address {
            font-size: 12px;
            line-height: 1.3;
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
            font-size: 15px;
            font-weight: bold;
            border: 1px solid #000;
            padding: 5px 10px;
            display: inline-block;
        }

        .title {
            text-align: center;
            margin: 20px 0;
        }

        .title h1 {
            text-decoration: underline;
            font-size: 18px;
            margin: 0;
        }

        table.receipt-details {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .receipt-details td {
            border: 1px solid #aaa;
            padding: 8px;
            vertical-align: top;
            font-size: 12px;
        }

        .label {
            width: 30%;
            font-weight: bold;
            background: #f3f3f3;
        }

        .keterangan-cell {
            padding: 0 !important;
        }

        .keterangan-wrapper {
            display: block;
            height: 80px;
            padding: 8px;
        }

        textarea.keterangan-input {
            width: 100%;
            height: 100%;
            border: none;
            outline: none;
            background: transparent;
            resize: none;
            font-size: 12px;
            font-family: Arial, sans-serif;
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        .keterangan-print {
            display: none;
            white-space: pre-wrap;
            font-size: 12px;
            height: 100%;
        }

        .signature {
            margin-top: 40px;
            text-align: right;
        }

        .signature .name {
            font-weight: bold;
            text-decoration: underline;
            margin-top: 70px;
        }

        .signature .position {
            font-size: 11px;
        }

        @media print {
            textarea.keterangan-input {
                display: none !important;
            }

            .keterangan-print {
                display: block !important;
            }

            .print_function {
                display: none !important;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="print">


            <div class="header-container">
                <img src="http://192.168.1.253/real/include/images/logocx.png" class="logo"
                    style="width:100px; height:100px;">
                <div class="header-info">
                    <div class="hospital-name">Rumah Sakit Livasya</div>
                    <div class="hospital-address">
                        Jl. Raya Timur III Dawuan No. 875 Kab. Majalengka<br>
                        Telp. 081211151300 - Jawa Barat, Indonesia
                    </div>
                </div>
                <div class="invoice-number">
                    <div class="invoice-label">No. Invoice</div>
                    <div class="invoice-value">{{ $bilingan->pembayaran_tagihan->no_transaksi ?? '-' }}</div>
                </div>
            </div>
            <div class="title">
                <h1>KWITANSI</h1>
            </div>
            <table class="receipt-details">
                <tr>
                    <td class="label">Sudah Terima Dari</td>
                    <td>{{ $bilingan->registration->patient->name }}</td>
                </tr>
                <tr>
                    <td class="label">Nama Pasien</td>
                    <td><strong>{{ $bilingan->registration->patient->name }}</strong></td>
                </tr>
                <tr>
                    <td class="label">Nominal</td>
                    <td><strong>Rp. {{ number_format($bilingan->wajib_bayar, 0, ',', '.') }}</strong></td>
                </tr>
                <tr>
                    <td class="label">Uang Sejumlah</td>
                    <td><strong>{{ ucwords(terbilangRp($bilingan->wajib_bayar)) }}</strong></td>
                </tr>
                <tr>
                    <td class="label">Untuk Pembayaran</td>
                    <td class="keterangan-cell">
                        <div class="keterangan-wrapper">
                            <textarea id="keteranganInput" class="keterangan-input">{{ $bilingan->keterangan ?? 'PEMBAYARAN PELAYANAN RAWAT INAP' }}</textarea>
                            <div id="keteranganPrint" class="keterangan-print">
                                {{ $bilingan->keterangan ?? 'PEMBAYARAN PELAYANAN RAWAT INAP' }}</div>
                        </div>
                    </td>
                </tr>
            </table>

            <div class="signature">
                <div>Kab. Majalengka, {{ \Carbon\Carbon::now()->format('d M Y') }}</div>
                <div class="name">{{ auth()->user()->employee->fullname }}</div>
                <div class="position">Petugas</div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function printPage() {
            document.getElementById('divButtons').style.display = 'none';
            window.print();
            document.getElementById('divButtons').style.display = '';
            return false;
        }

        const textarea = document.getElementById('keteranganInput');
        const textDiv = document.getElementById('keteranganPrint');

        function syncText() {
            textDiv.innerText = textarea.value;
        }

        textarea.addEventListener('input', syncText);
        window.onbeforeprint = syncText;

        // Set default value for textarea if empty
        window.onload = function() {
            if (!textarea.value) {
                textarea.value = 'PEMBAYARAN PELAYANAN RAWAT INAP';
                syncText();
            }
        };
    </script>
@endsection
