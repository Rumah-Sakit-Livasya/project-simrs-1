<!DOCTYPE HTML>
<html>

<head>
    <title>Klaim Kwitansi</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <style>
        @page {
            size: A4 landscape;
            margin: 1.5cm;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .print-actions {
            margin-bottom: 20px;
        }

        .print-actions a {
            text-decoration: none;
            color: #06c;
            margin-right: 15px;
        }

        .header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .header img {
            max-width: 80px;
            margin-right: 20px;
        }

        .hospital-info h2 {
            margin: 0 0 5px 0;
            font-size: 1.3em;
        }

        .hospital-address {
            font-size: 0.9em;
            color: #555;
        }

        .title {
            text-align: center;
            margin: 10px 0 20px 0;
            position: relative;
        }

        .title h1 {
            text-decoration: underline;
            margin: 0;
            font-size: 1.4em;
        }

        .invoice-number {
            position: absolute;
            right: 0;
            top: 0;
            font-weight: bold;
        }

        table.receipt-details {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .receipt-details td {
            border: 1px solid #ccc;
            padding: 8px;
            vertical-align: top;
        }

        .label {
            width: 30%;
            font-weight: bold;
            background: #f9f9f9;
        }

        .amount-in-words {
            font-style: italic;
            margin: 10px 0;
        }

        .description {
            margin: 15px 0;
        }

        textarea {
            width: 100%;
            height: 60px;
            font-family: Arial, sans-serif;
            font-size: 14px;
            resize: none;
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
            font-size: 0.9em;
        }

        @media print {

            .print-actions,
            textarea {
                display: none;
            }

            #keterangan-text {
                display: block;
                white-space: pre-wrap;
            }
        }

        #keterangan-text {
            display: none;
        }
    </style>
</head>

<body>

    <!-- Print Actions -->
    {{-- <div class="print-actions">
        <a href="#" onclick="preparePrint()">Print</a>
        <a href="#" onclick="window.close()">Close</a>
    </div> --}}

    <!-- Receipt Content -->
    <div class="receipt">
        <!-- Header -->
        <div class="header">
            <img src="http://192.168.1.253/testing/include/images/logocx.png" alt="Hospital Logo" />
            <div class="hospital-info">
                <h2>Rumah Sakit Livasya</h2>
                <div class="hospital-address">
                    Jl. Raya Timur III Dawuan No. 875 Kab. Majalengka<br>
                    Telp. 081211151300 Fax. Kab. Majalengka, Jawa Barat, Indonesia
                </div>
            </div>
        </div>

        <!-- Title -->
        <div class="title">
            <h1>KWITANSI</h1>
            <div class="invoice-number">
                No. {{ $konfirmasi->first()->invoice ?? '-' }}
            </div>
        </div>

        <!-- Receipt Details -->
        <table class="receipt-details">
            <tr>
                <td class="label">Sudah Terima Dari</td>
                <td>{{ $konfirmasi->penjamin->nama_perusahaan }}</td>
            </tr>
            <tr>
                <td class="label">Nominal</td>
                <td><strong>Rp. {{ number_format($konfirmasi->jumlah, 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td class="label">Uang Sejumlah</td>
                <td><strong>{{ ucwords(terbilangRp($konfirmasi->jumlah)) }} </strong></td>
            </tr>
            <tr>
                <td class="label">Untuk Pembayaran</td>
                <strong>
                    <textarea id="keterangan-input">{{ $konfirmasi->keterangan }}</textarea>
                    <div id="keterangan-text"></div>
                </strong>

            </tr>
        </table>

        <!-- Amount in words -->

        <!-- Signature -->
        <div class="signature">
            <div>Kab. Majalengka, {{ \Carbon\Carbon::now()->format('d M Y') }}</div>
            <div class="name">Ghina Harunnita Sukma, S.Ak</div>
            <div class="position">PJ Keuangan</div>
        </div>
    </div>
    <script>
        // Simpan perubahan keterangan ke localStorage
        const textarea = document.getElementById('keterangan-input');
        const textDiv = document.getElementById('keterangan-text');

        // Load dari localStorage kalau ada
        if (localStorage.getItem('keteranganKwitansi')) {
            textarea.value = localStorage.getItem('keteranganKwitansi');
        }

        // Update localStorage setiap kali user mengetik
        textarea.addEventListener('input', function() {
            localStorage.setItem('keteranganKwitansi', this.value);
        });

        function preparePrint() {
            const text = textarea.value;
            textDiv.innerText = text;
            textarea.style.display = 'none';
            textDiv.style.display = 'block';
            window.print();
        }

        // Kosongkan localStorage saat halaman ditutup agar data tidak terus tersimpan
        window.addEventListener('beforeunload', () => {
            localStorage.removeItem('keteranganKwitansi');
        });
    </script>

</body>

</html>
