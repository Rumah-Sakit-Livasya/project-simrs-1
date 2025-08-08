@extends('app-type.keuangan.konfirmasi-asuransi.cetak.template')

@section('title', 'Cetak Klaim Tagihan')

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
            top: 0;
            right: 0;
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
            font-size: 12px;
            line-height: 1.3;
        }

        .document-info {
            margin: 15px 0;
        }

        .document-info ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .document-info li {
            margin-bottom: 3px;
        }

        .document-info span.label {
            font-weight: bold;
            display: inline-block;
            width: 70px;
        }

        .content-section {
            margin-bottom: 8px;
        }

        .recipient-address textarea {
            width: 100%;
            border: 1px solid #ddd;
            padding: 5px;
            font-size: 12px;
            resize: none;
            height: 50px;
        }

        .bank-info {
            font-family: Arial, sans-serif;
            margin: 15px 0;
        }

        .bank-info p {
            font-size: 12px;
            margin: 0;
        }

        .bank-info strong {
            display: block;
            margin-bottom: 5px;
        }

        .bank-info .form-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 5px 0;
        }

        .bank-info label {
            font-weight: bold;
            width: 80px;
        }

        .bank-info .value {
            border: 1px solid #ccc;
            padding: 3px 6px;
            font-size: 12px;
            min-width: 180px;
            background-color: #f9f9f9;
        }


        .signature-area {
            text-align: right;
            margin-top: 40px;
            width: 100%;
        }


        .signature-space {
            height: 60px;
            display: block;
            margin: 10px 0;
        }

        #functions {
            position: fixed;
            top: 10px;
            right: 10px;
            background: #fff;
            padding: 15px;
            border: 1px solid #ddd;
            z-index: 1000;
        }

        #functions ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            gap: 10px;
        }

        #functions a,
        #functions button {
            color: #333;
            text-decoration: none;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 12px;
        }

        #functions input {
            padding: 3px;
            width: 120px;
        }

        .form-row {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .form-row label {
            font-weight: bold;
        }

        .form-row .value {
            border: 1px solid #ccc;
            padding: 3px 6px;
            font-size: 12px;
            min-width: 180px;
            background-color: #f9f9f9;
        }

        .recipient-address {
            width: 30%;
            min-height: 50px;
            padding: 5px;
            font-size: 12px;
            border: 1px solid #000;
            resize: both;
            /* aktifkan resize ke atas-bawah */
        }


        .recipient-address-print {
            display: none;
            white-space: pre-wrap;
            font-size: 12px;
            border: 1px solid #ccc;
            padding: 5px;
            min-height: 50px;
            overflow: auto;
            box-sizing: border-box;
        }



        @media print {

            html,
            body {
                margin: 0;
                padding: 0;
                height: 100%;
                overflow: hidden;
            }

            * {
                page-break-inside: avoid;
                page-break-before: auto;
                page-break-after: auto;
            }

            .no-break {
                page-break-inside: avoid;
            }

            .recipient-address {
                display: none !important;
            }

            .recipient-address-print {
                display: block !important;
            }
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
            <div class="invoice-value">{{ $konfirmasi->invoice ?? '-' }}</div>
        </div>
    </div>

    <div class="document-info">
        <table>
            <tr>
                <td>Perihal :</td>
                <td>{{ $konfirmasi->keterangan }}</td>
            </tr>
        </table>
    </div>

    <div class="content-section">
        <p>
            Kepada Yth,<br>
            <strong>{{ $konfirmasi->penjamin->nama_perusahaan }}</strong><br>
            <strong>Up. Claim Department</strong><br>
        <div class="recipient-address-wrapper">
            <textarea id="alamatInput" class="recipient-address" placeholder=""></textarea>
            <div id="alamatText" class="recipient-address-print"></div>
        </div>

        </p>
    </div>

    <div class="content-section">
        <p>Dengan Hormat,</p>
        <p style="text-align: justify;">
            Bersama surat ini kami kirimkan jumlah (klaim) atas biaya pemeriksaan pasien Rawat Jalan bagi
            {{ $konfirmasi->penjamin->nama }} pada Rumah Sakit Livasya. Adapun jumlah tagihan Rawat Jalan
            sebesar: <b>Rp. {{ number_format($konfirmasi->jumlah, 0, ',', '.') }}
                ({{ ucwords(terbilangRp($konfirmasi->jumlah)) }} rupiah)</b>. Perincian seperti: Kwitansi pembayaran,
            salinan resep, pengantar bayar resep dan rincian tagihan Rawat Jalan juga kami lampirkan bersama surat ini.
        </p>
        <p>Tagihan tersebut mohon ditransfer ke rekening:</p>
    </div>

    <div class="bank-info">
        <p><strong>A/N PT LIVASYA SUDJONO BERSAUDARA</strong></p>
        <div class="form-row">
            <label>No. Acc.</label>
            <div class="value">1104660076</div>
        </div>
        <div class="form-row">
            <label>Bank</label>
            <div class="value">Bank BNI</div>
        </div>
    </div>

    <div class="content-section">
        <p style="text-align: justify;">
            Demikian surat tagihan ini kami sampaikan, terima kasih atas kerjasama yang telah terjalin dengan baik
            selama ini.
        </p>
    </div>

    <div class="signature-area">
        <p>
            Kab. Majalengka, {{ \Carbon\Carbon::now()->format('d M Y') }}<br>
            Rumah Sakit Livasya
            <span class="signature-space"></span>
            <strong>Ghina Harunnita Sukma, S.Ak</strong><br>
            PJ Keuangan
        </p>
    </div>
@endsection

@section('script')
    <script>
        const textarea = document.getElementById('alamatInput');
        const textDiv = document.getElementById('alamatText');

        function syncPrintDiv() {
            textDiv.innerText = textarea.value;
            textDiv.style.width = `${textarea.offsetWidth}px`;
            textDiv.style.height = `${textarea.offsetHeight}px`;
        }

        textarea.addEventListener('input', syncPrintDiv);

        window.onbeforeprint = function() {
            syncPrintDiv();
            textarea.style.display = 'none';
            textDiv.style.display = 'block';
        };

        window.onafterprint = function() {
            textDiv.style.display = 'none';
            textarea.style.display = 'block';
        };
    </script>
@endsection
