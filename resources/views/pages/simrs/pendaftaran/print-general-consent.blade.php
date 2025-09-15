<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@medic Information System - General Consent</title>
    <style>
        body {
            background-color: #f0f0f0;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .page-wrapper {
            max-width: 21cm;
            min-height: 29.7cm;
            margin: 0 auto;
            background-color: #fff;
            padding: 1.5cm;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .print-actions {
            background-color: #f1f1f1;
            border: 1px solid #ccc;
            padding: 8px 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .print-actions button {
            padding: 5px 15px;
            cursor: pointer;
        }

        /* --- Header --- */
        .header-table {
            width: 100%;
            border: 1px solid #000;
            border-collapse: collapse;
        }

        .header-table .logo-cell {
            width: 50%;
            padding: 15px;
            border-right: 3px solid green;
            text-align: center;
            vertical-align: middle;
        }

        .header-table .logo-cell img {
            max-width: 5cm;
        }

        .header-table .patient-info-cell {
            width: 50%;
            padding: 10px 15px;
            vertical-align: top;
        }

        .patient-info-table {
            width: 100%;
        }

        .patient-info-table td {
            padding: 2px 0;
            font-size: 1.1em;
        }

        .patient-info-table input {
            border: none;
            background: none;
            width: 100%;
            font-size: 1em;
            font-family: Arial, sans-serif;
        }

        .sticker-label {
            font-size: 0.8em;
            text-align: left;
            padding-top: 10px;
        }

        /* --- Content --- */
        .content-section {
            margin-top: 20px;
        }

        .main-title {
            text-align: center;
            font-weight: bold;
            font-size: 1.3em;
            margin-bottom: 25px;
        }

        .content-table {
            width: 100%;
            border-collapse: collapse;
        }

        .content-table .number-cell {
            width: 3%;
            font-weight: bold;
            font-size: 1.2em;
            text-align: center;
            vertical-align: top;
            padding-top: 5px;
        }

        .content-table .text-cell {
            vertical-align: top;
        }

        .content-table h3 {
            font-weight: bold;
            font-size: 1.2em;
            margin: 0 0 5px 0;
        }

        .content-table p,
        .content-table ol {
            font-size: 1.0em;
            line-height: 1.5;
            text-align: justify;
        }

        .content-table input[type="radio"],
        .content-table input[type="checkbox"] {
            margin: 0 5px;
        }

        .content-table input[type="text"] {
            border: none;
            border-bottom: 1px dotted #000;
            background: none;
            width: 250px;
        }

        .final-statement {
            font-size: 1em;
            text-decoration: underline;
            font-weight: bold;
        }

        .date-signature {
            font-size: 1.1em;
            margin-top: 15px;
        }

        .date-signature input {
            border: none;
            background: none;
            font-size: 1em;
        }

        /* --- Signature Section --- */
        .signature-table {
            width: 100%;
            margin-top: 20px;
            text-align: center;
        }

        .signature-table td {
            width: 50%;
            font-size: 1.1em;
        }

        .signature-box {
            height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-style: italic;
            color: #aaa;
        }

        .signature-name {
            border-top: 1px solid #000;
            padding-top: 5px;
        }

        .footnote {
            margin-top: 20px;
            font-style: italic;
        }

        @media print {
            body {
                background: none;
                padding: 0;
            }

            .no-print,
            .page-wrapper {
                box-shadow: none;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="no-print print-actions">
        <button onclick="window.print()">Print</button>
        <button onclick="window.close()">Tutup</button>
    </div>

    <div class="page-wrapper">
        <table class="header-table">
            <tbody>
                <tr>
                    <td class="logo-cell">
                        <img src="/img/logo.png" alt="Logo RS">
                    </td>
                    <td class="patient-info-cell">
                        <table class="patient-info-table">
                            <tbody>
                                <tr>
                                    <td style="width: 30%;">Nama</td>
                                    <td style="width: 5%;">:</td>
                                    <td><input type="text" value="SANTI" readonly></td>
                                </tr>
                                <tr>
                                    <td>Tanggal lahir</td>
                                    <td>:</td>
                                    <td><input type="text" value="19-01-2004" readonly></td>
                                </tr>
                                <tr>
                                    <td>Kelamin</td>
                                    <td>:</td>
                                    <td><input type="text" value="Perempuan" readonly></td>
                                </tr>
                                <tr>
                                    <td>No.RM</td>
                                    <td>:</td>
                                    <td><input type="text" value="06-38-74" readonly></td>
                                </tr>
                                <tr>
                                    <td>Ruang/Kelas</td>
                                    <td>:</td>
                                    <td><input type="text" value="KLINIK OBGYN" readonly></td>
                                </tr>
                                <tr>
                                    <td>DPJP</td>
                                    <td>:</td>
                                    <td><input type="text" value="dr. Dindadikusuma Sp.OG" readonly></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="sticker-label">Tempel sticker identitas disini</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="content-section">
            <h2 class="main-title">FORMULIR PEMBERIAN INFORMASI DAN PERSETUJUAN UMUM<br>(<i>GENERAL CONSENT</i>) UNTUK
                MENERIMA PELAYANAN KESEHATAN</h2>

            <table class="content-table">
                <tbody>
                    <!-- Bagian 1 -->
                    <tr>
                        <td class="number-cell">1.</td>
                        <td class="text-cell">
                            <h3>HAK DAN KEWAJIBAN SEBAGAI PASIEN</h3>
                            <p>
                                Dengan menandatangani dokumen ini saya menyatakan bahwa saya telah
                                (<i><input type="radio" name="hak_kewajiban"><b>Mendapat</b> / <input type="radio"
                                        name="hak_kewajiban"><b>Belum mendapat</b></i>)
                                informasi dan edukasi tentang hak pasien dan keluarga pada proses pendaftaran pasien di
                                RS Livasya.
                            </p>
                        </td>
                    </tr>
                    <!-- Bagian 2 -->
                    <tr>
                        <td class="number-cell">2.</td>
                        <td class="text-cell">
                            <h3>PERSETUJUAN PELAYANAN KESEHATAN</h3>
                            <p>
                                Saya (<i><input type="radio" name="persetujuan"><b>Setuju</b> / <input type="radio"
                                        name="persetujuan"><b>Tidak setuju</b></i>)
                                untuk mendapatkan pelayanan kesehatan di RS Livasya dengan menyetujui pernyataan ini
                                saya meminta dan memberikan kuasa kepada
                                RS Livasya, dokter dan perawat, dan tenaga kesehatan lainnya untuk memberikan asuhan
                                perawatan, pemeriksaan fisik yang dilakukan
                                oleh dokter dan perawat dan melakukan prosedur diagnostik, radiologi dan/atau terapi dan
                                tatalaksana sesuai pertimbangan dokter yang diperlukan atau
                                disarankan pada perawatan saya. Hal ini mencakup seluruh pemeriksaan dan prosedur
                                diagnostik rutin, termasuk x-ray, pemberian dan/atau tindakan medis
                                serta penyuntikan (intramuskular, intravena dan prosedur invasif lainnya) produk farmasi
                                dan obat-obatan, pemasangan alat kesehatan (kecuali
                                yang membutuhkan persetujuan khusus/tertulis), dan pengambilan darah untuk pemeriksaan
                                laboratorium atau pemeriksaan patologi yang dibutuhkan
                                untuk pengobatan dan tindakan yang aman. Bila secara kondisi medis pasien membutuhkan
                                perawatan dengan fasilitas yang tidak dimiliki oleh
                                Rumah Sakit maka Rumah Sakit akan merujuk pasien ke Rumah Sakit yang memiliki fasilitas
                                kesehatan sesuai kebutuhan medis pasien.
                            </p>
                        </td>
                    </tr>
                    <!-- Bagian 3 -->
                    <tr>
                        <td class="number-cell">3.</td>
                        <td class="text-cell">
                            <h3>AKSES INFORMASI KESEHATAN</h3>
                            <p>
                                Saya (<i><input type="radio" name="akses_informasi"><b>Memberikan</b> / <input
                                        type="radio" name="akses_informasi"><b>Tidak memberikan</b></i>)
                                kuasa kepada setiap dan seluruh orang yang merawat saya untuk memeriksa dan atau
                                memberitahukan informasi kesehatan saya kepada pemberi
                                kesehatan lain yang turut merawat saya selama di RS Livasya.
                            </p>
                        </td>
                    </tr>
                    <!-- Bagian 4 -->
                    <tr>
                        <td class="number-cell">4.</td>
                        <td class="text-cell">
                            <h3>PERSETUJUAN PELEPASAN INFORMASI</h3>
                            <p>
                                Saya memahami informasi yang ada didalam diri saya, termasuk diagnosis, hasil
                                laboratorium dan hasil tes diagnostik yang akan digunakan untuk perawatan medis, RS
                                Livasya akan menjamin kerahasiaannya.
                                <br><br>
                                Saya (<i><input type="radio" name="pelepasan1"><b>Memberi</b> / <input type="radio"
                                        name="pelepasan1"><b>Tidak memberi</b></i>) wewenang kepada RS Livasya untuk
                                memberikan informasi tentang diagnosis, hasil pelayanan dan pengobatan bila diperlukan
                                untuk memproses klaim asuransi/perusahaan dan atau lembaga pemerintah.
                                <br>
                                Saya (<i><input type="radio" name="pelepasan2"><b>Memberi</b> / <input type="radio"
                                        name="pelepasan2"><b>Tidak memberi</b></i>) wewenang kepada RS untuk memberikan
                                informasi tentang diagnosis, hasil pelayanan dan pengobatan saya kepada anggota keluarga
                                dan kepada:
                                <br>
                                1. <input type="text"
                                    placeholder="...................................................................................."><br>
                                2. <input type="text"
                                    placeholder="....................................................................................">
                            </p>
                        </td>
                    </tr>
                    <!-- Bagian 5 & 6 & 7 -->
                    <tr>
                        <td class="number-cell">5.</td>
                        <td class="text-cell">
                            <h3>RAHASIA MEDIS</h3>
                            <p>Saya (<i><input type="radio" name="rahasia_medis"><b>Setuju</b> / <input type="radio"
                                        name="rahasia_medis"><b>Tidak setuju</b></i>) RS Livasya wajib menjamin
                                kerahasiaan informasi medis saya baik untuk kepentingan perawatan dan pengobatan,
                                pendidikan maupun penelitian kecuali saya mengungkapkan sendiri atau orang yang lain
                                yang saya beri kuasa untuk itu.</p>
                        </td>
                    </tr>
                    <tr>
                        <td class="number-cell">6.</td>
                        <td class="text-cell">
                            <h3>PRIVASI</h3>
                            <p>Saya (<i><input type="radio" name="privasi"><b>Memberi</b> / <input type="radio"
                                        name="privasi"><b>Tidak memberi</b></i>) kuasa kepada RS Livasya untuk menjaga
                                privasi dan kerahasiaan penyakit saya selama dalam perawatan dalam hal:</p>
                            <ol type="a">
                                <li>Pengambilan dokumentasi saya berupa foto, rekaman wawancara diluar kepentingan
                                    keperawatan dan pengobatan harus seijin saya.</li>
                                <li>Memberi informasi tentang penyakit saya kepada siapapun tanpa seijin saya baik
                                    terhadap keluarga saya (<i><input type="checkbox"><b>Orang tua kandung</b> / <input
                                            type="checkbox"><b>Suami istri</b> / <input type="checkbox"><b>Kakak</b> /
                                        <input type="checkbox"><b>Adik saya</b></i>)</li>
                                <li><b>Tidak ingin dikunjungi oleh:</b> <input type="text"
                                        placeholder=".......................................................................">
                                </li>
                            </ol>
                        </td>
                    </tr>
                    <tr>
                        <td class="number-cell">7.</td>
                        <td class="text-cell">
                            <h3>BARANG PRIBADI</h3>
                            <p>Saya (<i><input type="radio" name="barang_pribadi"><b>Bersedia</b> / <input
                                        type="radio" name="barang_pribadi"><b>Tidak bersedia</b></i>) untuk ketentuan
                                dari RS Livasya bahwa saya tidak boleh membawa barang-barang berharga yang tidak
                                diperlukan (seperti: perhiasan, elektronik, dll) dan jika saya membawanya maka RS
                                Livasya tidak bertanggung jawab terhadap kehilangan, kerusakan dan pencurian.</p>
                        </td>
                    </tr>
                    <!-- Bagian 8 & 9 -->
                    <tr>
                        <td class="number-cell">8.</td>
                        <td class="text-cell">
                            <h3>PENGAJUAN KELUHAN</h3>
                            <p>Saya menyatakan bahwa saya (<i><input type="radio" name="keluhan"><b>Telah menerima</b>
                                    / <input type="radio" name="keluhan"><b>Belum menerima</b></i>) informasi tentang
                                adanya tatacara mengajukan dan mengatasi keluhan terkait pelayanan medik yang diberikan
                                terhadap diri saya. Saya setuju untuk mengikuti tatacara mengajukan keluhan sesuai
                                prosedur yang ada.</p>
                        </td>
                    </tr>
                    <tr>
                        <td class="number-cell">9.</td>
                        <td class="text-cell">
                            <h3>KEWAJIBAN PEMBAYARAN</h3>
                            <p>Saya menyatakan (<i><input type="radio" name="bayar1"><b>Setuju</b> / <input
                                        type="radio" name="bayar1"><b>Tidak setuju</b></i>) baik sebagai wali atau
                                sebagai pasien, bahwa sesuai pertimbangan pelayanan yang diberikan kepada pasien, maka
                                saya wajib untuk membayar total biaya pelayanan. Biaya pelayanan berdasarkan acuan biaya
                                dan ketentuan RS Livasya.<br><br>Melalui dokumen ini, saya menegaskan kembali bahwa saya
                                (<i><input type="radio" name="bayar2"><b>Mempercayakan</b> / <input type="radio"
                                        name="bayar2"><b>Tidak mempercayakan</b></i>) kepada semua tenaga kesehatan
                                rumah sakit untuk memberikan perawatan diagnostik dan terapi kepada saya sebagai pasien
                                rawat inap, rawat jalan atau instalasi gawat darurat (IGD) termasuk semua pemeriksaan
                                penunjang yang dibutuhkan untuk pengobatan dan tindakan yang aman.<br><br>Saya
                                (<i><input type="radio" name="bayar3"><b>Mengerti dan memahami</b> / <input
                                        type="radio" name="bayar3"><b>Belum mengerti dan belum memahami</b></i>)
                                bahwa dalam tindakan kedokteran ada hal-hal yang mungkin terjadi dan tidak dapat diduga
                                sebelumnya / tidak diharapkan yang merupakan efek samping dari tindakan kedokteran
                                (antara lain steven johson syndrome dan syok anafilaktik, dll). Saya mengerti bahwa
                                hasil asuhan dan pengobatan termasuk kejadian yang tidak terduga / tidak diharapkan akan
                                diberitahukan kepada saya dan keluarga oleh dokter penanggung jawab pasien
                                (DPJP).<br><br><span class="final-statement">SAYA TELAH MEMBACA dan sepenuhnya setuju
                                    dengan setiap pernyataan yang terdapat pada formulir ini dan menandatangani tanpa
                                    paksaan dan dengan kesadaran.</span></p>
                            <div class="date-signature">Kab. Majalengka, <input type="text" value="15-09-2025"
                                    style="width:150px;"></div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <table class="signature-table">
                <tbody>
                    <tr>
                        <td>Pasien/Keluarga/<br>Penanggung jawab</td>
                        <td>Pemberi Informasi</td>
                    </tr>
                    <tr>
                        <td>
                            <div class="signature-box">(Tanda Tangan)</div>
                        </td>
                        <td>
                            <div class="signature-box">(Tanda Tangan)</div>
                        </td>
                    </tr>
                    <tr>
                        <td class="signature-name">( SANTI )</td>
                        <td class="signature-name">( Petugas Pendaftaran )</td>
                    </tr>
                </tbody>
            </table>

            <div class="footnote">
                <i>*Coret yang tidak perlu</i>
            </div>
        </div>
    </div>
</body>

</html>
