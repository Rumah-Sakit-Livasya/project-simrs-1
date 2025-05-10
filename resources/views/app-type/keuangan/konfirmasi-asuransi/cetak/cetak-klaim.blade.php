<!DOCTYPE HTML>
<html>

<head>
    <title>Kwitansi {{ $konfirmasi->invoice }}</title>
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #000;
            margin: 10px;
            padding: 15px;
        }

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
            font-size: 12px margin: 0;
        }

        .bank-info strong {
            display: block;
        }

        .bank-info-row {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-top: 8px;
        }

        .signature-area {
            float: right;
            width: 30%;
            text-align: center;
            margin-top: 30px;
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
    </style>
</head>

<body>

    <div class="header-container">
        <img src="http://192.168.1.253/testing/include/images/logocx.png" class="logo">
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
            <div class="invoice-value">{{ $konfirmasi->first()->invoice ?? '-' }}</div>
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
            <textarea class="recipient-address" cols="50" rows="3"></textarea>
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

    <script type="text/javascript">
        function save_new_inv() {
            var newinv = document.getElementById('newinv').value;
            if (!newinv) {
                alert('Masukkan nomor invoice terlebih dahulu');
                return;
            }

            $.ajax({
                url: 'http://192.168.1.253/testing/ar/save_new_inv',
                data: {
                    "newinv": newinv,
                    "iarid": "{{ $konfirmasi->id ?? '' }}"
                },
                type: 'POST',
                dataType: 'JSON',
                success: function(hasil) {
                    if (hasil[0].status == 0) {
                        alert('Nomor invoice berhasil diperbarui');
                    } else {
                        alert('Gagal memperbarui nomor invoice');
                    }
                    window.location.reload();
                },
                error: function() {
                    alert('Terjadi kesalahan saat menyimpan');
                }
            });
        }

        function printPage() {
            document.getElementById('functions').style.display = 'none';
            window.print();
            setTimeout(function() {
                document.getElementById('functions').style.display = '';
            }, 500);
        }
    </script>
</body>

</html>
