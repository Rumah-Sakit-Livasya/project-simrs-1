<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@medic Information System - Surat Keterangan Lahir</title>
    <style>
        body {
            background-color: #f0f0f0;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .page-wrapper {
            width: 18.5cm;
            margin: 0 auto;
            background-color: #fff;
            padding: 2cm 1.5cm;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.15);
        }

        .print-actions {
            margin-bottom: 20px;
            background-color: #f1f1f1;
            border: 1px solid #ccc;
            padding: 8px;
            border-radius: 4px;
        }

        .print-actions button {
            padding: 5px 15px;
            margin-right: 5px;
            cursor: pointer;
        }

        .print-actions input[type="text"] {
            border: 1px solid #ccc;
            padding: 3px;
        }

        .print-actions label {
            margin-left: 10px;
        }

        /* --- Header --- */
        .header-table {
            width: 100%;
            border-bottom: double 3px #000;
            margin-bottom: 15px;
        }

        .header-table .logo {
            height: 80px;
        }

        .header-table .hospital-info {
            text-align: left;
            vertical-align: middle;
        }

        .header-table .hospital-name {
            font-size: 1.6em;
            font-weight: bold;
        }

        .header-table .hospital-address {
            font-size: 1em;
        }

        /* --- Title --- */
        .title-section {
            text-align: center;
            margin-bottom: 20px;
        }

        .title-section h1 {
            font-size: 1.6em;
            font-weight: bold;
            margin: 0;
        }

        .title-section h2 {
            font-size: 1.2em;
            font-weight: bold;
            font-style: italic;
            margin: 0;
        }

        .title-section .doc-number {
            font-size: 1.3em;
            font-weight: bold;
            margin-top: 5px;
        }

        /* --- Content --- */
        .content-table {
            width: 100%;
            vertical-align: top;
            font-size: 1.1em;
            line-height: 1.8;
        }

        .content-table td {
            padding: 4px 0;
        }

        .content-table .label-group .main-label {
            border-bottom: 1px solid #000;
            display: inline;
        }

        .content-table .label-group .sub-label {
            display: block;
            font-style: italic;
            font-size: 0.9em;
            line-height: 1.2;
        }

        .biodata-table {
            width: 90%;
            font-size: 1em;
        }

        .attention-box {
            border: 1px solid #000;
            padding: 8px;
            font-size: 0.8em;
            margin-top: 50px;
        }

        .signature-section {
            text-align: center;
            margin-top: 25px;
        }

        .signature-section img {
            height: 75px;
            display: block;
            margin: 0 auto;
        }

        /* --- Back Page --- */
        #show_belakang {
            display: none;
        }

        .footprint-section h3,
        .footprint-section h4 {
            margin: 5px 0;
            font-weight: bold;
        }

        .footprint-section h3 {
            font-size: 1.6em;
            text-decoration: underline;
        }

        .footprint-section h4 {
            font-size: 1.5em;
            font-style: italic;
        }

        .footprint-box {
            height: 220px;
            border: 2px solid #000;
            margin: 10px 20px;
            padding: 4px;
        }

        .footprint-box .inner-box {
            height: 100%;
            border: 4px double #000;
        }

        .thumbprint-box {
            height: 100px;
        }

        .grid-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            text-align: center;
        }


        @media print {
            body {
                background-color: #fff;
                padding: 0;
            }

            .no-print,
            .page-wrapper {
                box-shadow: none;
                margin: 0;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    @php
        // Helper for date formatting
        use Carbon\Carbon;

        // Default values if not set
        $noSurat = old('no_surat', $registration->no_surat ?? '023/SKL/LVS/IX/2025');
        $namaDokter = old('nama_dokter', $registration->doctor->name ?? 'dr. Mohammad Yudhistira Surya N');
        $tanggalLahir = $registration->date ?? ($registration->registration_date ?? null);
        $jamLahir = $registration->registration_date
            ? Carbon::parse($registration->registration_date)->format('H:i')
            : null;
        $hariLahir = $tanggalLahir ? \Illuminate\Support\Carbon::parse($tanggalLahir)->isoFormat('dddd') : '-';
        $tanggalLahirFormat = $tanggalLahir
            ? \Illuminate\Support\Carbon::parse($tanggalLahir)->translatedFormat('d F Y')
            : '-';
        $jamLahirFormat = $jamLahir ? $jamLahir . ' WIB' : '-';

        // Biodata bayi
        $jenisKelamin = $registration->jenis_kelamin ?? 'Perempuan';
        $jenisKelaminEn = strtolower($jenisKelamin) == 'laki-laki' ? 'Male' : 'Female';
        $namaBayi = $registration->nama_bayi ?? 'Anindya Putri';
        $namaIbu = $registration->nama_ibu ?? ($registration->patient->nama_ibu ?? 'NONIH');
        $alamat = $registration->alamat ?? ($registration->patient->alamat ?? 'Jl. Mawar No. 5, Majalengka');

        // Biodata detail
        $berat = $registration->berat_bayi ?? '3100 Gr';
        $panjang = $registration->panjang_bayi ?? '49 Cm';
        $jenisPersalinan = $registration->jenis_persalinan ?? 'Spontan';
        $tindakan = $registration->tindakan_persalinan ?? '-';
        $kembar = $registration->kembar ?? 'Tunggal';
        $anakKe = $registration->anak_ke ?? '1';

        // Tanggal surat
        $tempatSurat = $registration->tempat_surat ?? 'Kab. Majalengka';
        $tanggalSurat = $tanggalLahirFormat;

        // Fallback for doctor name on signature
        $namaDokterSignature = $namaDokter;
    @endphp

    <div class="print-actions no-print">
        <button onclick="printPage();">Print</button>
        <button onclick="window.close();">Tutup</button>
        <label><input type="radio" name="pilih_print" onclick="show_depan();" checked> Print Depan</label>
        <label><input type="radio" name="pilih_print" onclick="show_belakang();"> Print Belakang</label>
        <br>
        <div style="margin-top: 5px;">
            <label for="vnomor">No. Surat:</label>
            <input type="text" id="vnomor" value="{{ $noSurat }}" size="25">
            <label for="vdokter">Nama Dokter:</label>
            <input type="text" id="vdokter" value="{{ $namaDokter }}" size="30">
        </div>
    </div>

    <div class="page-wrapper">
        <!-- HALAMAN DEPAN -->
        <div id="show_depan">
            <table class="header-table">
                <tr>
                    <td style="width: 100px;">
                        <img src="/img/logo.png" alt="Logo RS" class="logo">
                    </td>
                    <td class="hospital-info">
                        <div class="hospital-name">RUMAH SAKIT LIVASYA</div>
                        <span class="hospital-address">Jl. Raya Timur III Dawuan No. 875 Kab. Majalengka<br>Telp. :
                            081211151300 (Hunting) Fax : -</span>
                    </td>
                </tr>
            </table>

            <div class="title-section">
                <h1>SURAT KETERANGAN LAHIR</h1>
                <h2>BIRTH CERTIFICATE</h2>
                <div class="doc-number" id="vnomor_text">No. : {{ $noSurat }}</div>
            </div>

            <table class="content-table">
                <tr>
                    <td style="width:45%;">
                        <div class="label-group">
                            <span class="main-label">Yang bertanda tangan dibawah ini</span>
                            <span class="sub-label">The undersigned obstetrician</span>
                        </div>
                    </td>
                    <td style="width:2%;">:</td>
                    <td id="vdokter_text_display">{{ $namaDokter }}</td>
                </tr>
                <tr>
                    <td>
                        <div class="label-group">
                            <span class="main-label">Menerangkan bahwa telah lahir seorang bayi</span>
                            <span class="sub-label">Herewith certify the birth of a baby/infant</span>
                        </div>
                    </td>
                    <td>:</td>
                    <td>
                        <div class="label-group">
                            <span class="main-label">{{ $jenisKelamin }}</span>
                            <span class="sub-label">{{ $jenisKelaminEn }}</span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="label-group">
                            <span class="main-label">Nama Bayi</span>
                            <span class="sub-label">Baby's Name</span>
                        </div>
                    </td>
                    <td>:</td>
                    <td><b>{{ $namaBayi }}</b></td>
                </tr>
                <tr>
                    <td>
                        <div class="label-group">
                            <span class="main-label">Nama Ibu</span>
                            <span class="sub-label">Mother's Name</span>
                        </div>
                    </td>
                    <td>:</td>
                    <td>{{ $namaIbu }}</td>
                </tr>
                <tr>
                    <td>
                        <div class="label-group">
                            <span class="main-label">Alamat Rumah</span>
                            <span class="sub-label">Home Address</span>
                        </div>
                    </td>
                    <td>:</td>
                    <td>{{ $alamat }}</td>
                </tr>
                <tr>
                    <td colspan="3" style="padding-top: 20px;">
                        <table class="biodata-table">
                            <tr>
                                <td style="width:15%;">Pada / on</td>
                                <td style="width:1%;">:</td>
                                <td style="width:30%;">Hari / day</td>
                                <td style="width:1%;">:</td>
                                <td>{{ $hariLahir }}</td>
                            </tr>
                            <tr>
                                <td colspan="2"></td>
                                <td>Tanggal / date</td>
                                <td>:</td>
                                <td>{{ $tanggalLahirFormat }}</td>
                            </tr>
                            <tr>
                                <td colspan="2"></td>
                                <td>Jam / time</td>
                                <td>:</td>
                                <td>{{ $jamLahirFormat }}</td>
                            </tr>
                            <tr>
                                <td style="vertical-align: baseline;">Biodata</td>
                                <td style="vertical-align: baseline;">:</td>
                                <td colspan="3">
                                    <table>
                                        <tr>
                                            <td style="width: 200px;">1. Berat / weight</td>
                                            <td style="width:1%;">:</td>
                                            <td>{{ $berat }}</td>
                                        </tr>
                                        <tr>
                                            <td>2. Panjang / length</td>
                                            <td>:</td>
                                            <td>{{ $panjang }}</td>
                                        </tr>
                                        <tr>
                                            <td>3. Jenis Persalinan / labor</td>
                                            <td>:</td>
                                            <td>{{ $jenisPersalinan }}</td>
                                        </tr>
                                        <tr>
                                            <td>4. Kelahiran dengan Tindakan</td>
                                            <td>:</td>
                                            <td>{{ $tindakan }}</td>
                                        </tr>
                                        <tr>
                                            <td style="padding-left:15px;"><span class="sub-label">Pathological
                                                    labor</span></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>5. Kembar</td>
                                            <td>:</td>
                                            <td>{{ $kembar }}</td>
                                        </tr>
                                        <tr>
                                            <td style="padding-left:15px;"><span class="sub-label">Multiple labor</span>
                                            </td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>6. Anak ke</td>
                                            <td>:</td>
                                            <td>{{ $anakKe }}</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="attention-box">
                            <b>Perhatian:</b><br>
                            Surat keterangan ini harus segera dilaporkan ke lurah atau administratif berwenang dalam
                            waktu 14 (empat belas) hari sejak tanggal kelahiran bayi.
                        </div>
                    </td>
                    <td></td>
                    <td>
                        <div class="signature-section">
                            {{ $tempatSurat }}, {{ $tanggalSurat }}
                            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII="
                                alt="Tanda Tangan" id="img_ttd">
                            <div id="vdokter_text_display">( {{ $namaDokterSignature }} )</div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- HALAMAN BELAKANG -->
        <div id="show_belakang">
            <div class="footprint-section" style="text-align:center;">
                <h3>Sidik Telapak Kaki Bayi</h3>
                <h4>Baby's Foot Print</h4>
            </div>

            <div class="grid-container">
                <div class="footprint-area">
                    <div style="text-align:center;">
                        <h3>Sidik Telapak Kaki Kiri</h3>
                        <h4>Left Foot Print</h4>
                    </div>
                    <div class="footprint-box">
                        <div class="inner-box"></div>
                    </div>
                </div>
                <div class="footprint-area">
                    <div style="text-align:center;">
                        <h3>Sidik Telapak Kaki Kanan</h3>
                        <h4>Right Foot Print</h4>
                    </div>
                    <div class="footprint-box">
                        <div class="inner-box"></div>
                    </div>
                </div>
            </div>

            <div class="footprint-section" style="text-align:center; margin-top:20px;">
                <h3>Sidik Ibu Jari Tangan Ibu</h3>
                <h4>Mother's Thumb Print</h4>
            </div>

            <div class="grid-container">
                <div class="thumbprint-area">
                    <div style="text-align:center;">
                        <h3>Sidik Ibu Jari Tangan Kiri</h3>
                        <h4>Left Thumb Print</h4>
                    </div>
                    <div class="footprint-box thumbprint-box">
                        <div class="inner-box"></div>
                    </div>
                </div>
                <div class="thumbprint-area">
                    <div style="text-align:center;">
                        <h3>Sidik Ibu Jari Tangan Kanan</h3>
                        <h4>Right Thumb Print</h4>
                    </div>
                    <div class="footprint-box thumbprint-box">
                        <div class="inner-box"></div>
                    </div>
                </div>
            </div>

            <div class="signature-section" style="float:right; width: 40%; margin-top: 30px;">
                {{ $tempatSurat }}, {{ $tanggalSurat }}
                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII="
                    alt="Tanda Tangan" id="img_ttd_2">
                <div id="vdokter_text_display_2">( {{ $namaDokterSignature }} )</div>
            </div>

        </div>
    </div>

    <script>
        function show_depan() {
            document.getElementById('show_depan').style.display = 'block';
            document.getElementById('show_belakang').style.display = 'none';
        }

        function show_belakang() {
            document.getElementById('show_depan').style.display = 'none';
            document.getElementById('show_belakang').style.display = 'block';
        }

        function printPage() {
            // Get values from input fields
            const noSurat = document.getElementById('vnomor').value;
            const namaDokter = document.getElementById('vdokter').value;

            // Update display elements before printing
            document.getElementById('vnomor_text').innerText = 'No. : ' + noSurat;
            document.getElementById('vdokter_text_display').innerText = '( ' + namaDokter + ' )';
            document.getElementById('vdokter_text_display_2').innerText = '( ' + namaDokter + ' )';

            // Hide buttons and print
            document.querySelector('.no-print').style.display = 'none';
            window.print();
            document.querySelector('.no-print').style.display = 'block';
        }
    </script>

</body>

</html>
