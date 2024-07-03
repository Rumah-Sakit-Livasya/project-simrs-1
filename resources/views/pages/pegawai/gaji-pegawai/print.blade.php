<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Gaji</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap');

        * {
            font-family: 'Roboto', sans-serif !important;
        }

        body {
            font-family: 'Roboto', sans-serif;
        }

        body,
        html {
            margin: 0;
            padding: 5px;
            width: 98.7%;
            height: 100%;
            font-family: 'Roboto', sans-serif;
        }

        @page {
            margin: 0;
            padding: 5px;
        }

        @media print {
            @page {
                size: 210mm 297mm;
                /* Ukuran halaman A4 */
                margin: 0;
                page-break-after: always;
                transform: scale(0.97);
                /* Skala cetakan 9.7 */
                transform-origin: center;
            }

            .slip-gaji {
                height: auto;
                max-height: auto;
                overflow: hidden;
                padding: 5px;
                margin-top: 8px !important;
            }

            h1 {
                margin-top: 5px;
                margin-bottom: 5px;
            }
        }

        .slip-gaji {
            position: relative;
            height: auto;
            max-height: auto;
            overflow: hidden;
            padding: 5px;
            border: 1px solid rgba(0, 0, 0, 0.884);
            margin-top: 8px !important;
        }

        img.background {
            position: absolute;
            top: 0;
            left: 16%;
            right: 0;
            bottom: 0;
            margin: auto;
            width: 530px;
            height: 530px;
            opacity: 0.27;
            z-index: -4;
        }

        .judul-wrapper {
            border: 1px solid rgba(0, 0, 0, 0.884);
        }

        .judul-wrapper h1.judul-pay-slip,
        .judul-wrapper small {
            font-size: 12pt;
            font-family: 'Roboto', sans-serif;
            text-align: center;
            margin-top: 5px;
            margin-bottom: 5px;
        }

        .content-wrapper {
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 5px;
            border: 1px solid rgba(0, 0, 0, 0.884);
            font-size: 10pt;
            font-family: 'Roboto', sans-serif;
        }

        .content-wrapper .profile,
        .content-wrapper .total-gaji {
            font-weight: 600;
            margin: 0;
            font-family: 'Roboto', sans-serif !important;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            padding: 1px;
            font-family: 'Roboto', sans-serif !important;
        }

        .payslip-wrapper {
            display: flex;
            font-size: 10pt !important;
            font-family: 'Roboto', sans-serif;
            /* Menggunakan flexbox */
        }

        td span.child {
            margin-left: 16px;
        }

        .align-left {
            float: left;
        }

        .align-right {
            float: right;
        }

        .text-green {
            color: rgb(24, 173, 24);
        }

        .text-red {
            color: rgb(201, 38, 38);
        }
    </style>
</head>

<body>
    @foreach ($payrolls as $payroll)
        <div class="slip-gaji">
            <img src="{{ asset('img/logo-payslip.jpg') }}" alt="background-logo" class="background"
                style="text-align: center">
            <div class="judul-wrapper">
                <h1 class="judul-pay-slip">
                    SLIP GAJI KARYAWAN <br>
                    PERIODE ({{ $payroll->periode }})
                </h1>
            </div>
            <div class="content-wrapper" style="padding-top: 3px; padding-bottom: 3px">
                <table class="profile" width="100%">
                    <tr>
                        <td style="width: 50% !important; padding-left: 10px">
                            <table>
                                <tr>
                                    <td width="30%">
                                        <span>Nama Karyawan</span>
                                    </td>
                                    <td width="5%">:</td>
                                    <td width="65%">
                                        <span>{{ $payroll->employee->fullname }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%">
                                        <span>Bagian</span>
                                    </td>
                                    <td width="5%">:</td>
                                    <td width="65%">
                                        <span>{{ $payroll->employee->organization->name ?? '*belum setting' }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%">
                                        <span>Jabatan</span>
                                    </td>
                                    <td width="5%">:</td>
                                    <td width="65%">
                                        <span>{{ $payroll->employee->jobPosition->name ?? '*belum setting' }}</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 50% !important; padding-left: 10px">
                            <table>
                                <tr>
                                    <td width="45%">
                                        <span>NIK</span>
                                    </td>
                                    <td width="5%">:</td>
                                    <td width="50%">
                                        <span>{{ $payroll->employee->identity_number }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="45%%">
                                        <span>Status</span>
                                    </td>
                                    <td width="5%">:</td>
                                    <td width="50%">
                                        <span>{{ $payroll->employee->employment_status }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="45%%">
                                        <span>Jumlah Masuk Kerja</span>
                                    </td>
                                    <td width="5%">:</td>
                                    <td width="50%">
                                        <span>{{ $payroll->hari_kerja }} Hari</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="payslip-wrapper content-wrapper" style="padding-top: 6px;">
                <table class="payroll-content" width="100%">
                    <tr>
                        <td style="width: 50% !important; padding-right: 10px">
                            <table style="width: 100%" style="font-size: 9pt !important">
                                <tbody>
                                    <tr>
                                        <td colspan="3"><b><u>KOMPONEN PENERIMAAN</b></u></td>
                                    </tr>
                                    <tr class="text-green">
                                        <td width="65%">
                                            <b>a. Gaji Pokok</b>
                                        </td>
                                        <td width="5%">:</td>
                                        <td width="30%">
                                            <span class="align-left">Rp.</span>
                                            <span class="align-right">{{ rp2($payroll->basic_salary) }}</span>
                                        </td>
                                    </tr>
                                    <tr class="text-green">
                                        <td width="65%">
                                            <b>b. Tunjangan</b>
                                        </td>
                                        <td width="5%">:</td>
                                        <td width="30%"></td>
                                    </tr>
                                    <tr class="text-green">
                                        <td width="65%">
                                            <span class="child">- Tunjangan Makan & Transport</span>
                                        </td>
                                        <td width="5%">:</td>
                                        <td width="30%">
                                            <span class="align-left">Rp.</span>
                                            <span
                                                class="align-right">{{ rp2($payroll->tunjangan_makan_dan_transport) }}</span>
                                        </td>
                                    </tr>
                                    <tr class="text-green">
                                        <td width="65%">
                                            <span class="child">- Tunjangan Profesi</span>
                                        </td>
                                        <td width="5%">:</td>
                                        <td width="30%">
                                            <span class="align-left">Rp.</span>
                                            <span class="align-right">{{ rp2($payroll->tunjangan_profesi) }}</span>
                                        </td>
                                    </tr>
                                    <tr class="text-green">
                                        <td width="65%">
                                            <span class="child">- Tunjangan Masa Kerja</span>
                                        </td>
                                        <td width="5%">:</td>
                                        <td width="30%" style="border-bottom: 1px solid rgba(0, 0, 0, 0.884)">
                                            <span class="align-left">Rp.</span>
                                            <span class="align-right">{{ rp2($payroll->tunjangan_masa_kerja) }}</span>
                                        </td>
                                    </tr>
                                    <tr class="text-green">
                                        <td width="65%">
                                            <b>c. Jumlah ( a + b )</b>
                                        </td>
                                        <td width="5%">:</td>
                                        <td width="30%">
                                            <span class="align-left"><b>Rp.</b></span>
                                            <span
                                                class="align-right"><b>{{ rp2($payroll->basic_salary + $payroll->tunjangan_makan_dan_transport + $payroll->tunjangan_profesi + $payroll->tunjangan_masa_kerja) }}</b></span>

                                        </td>
                                    </tr>
                                    <tr class="text-green">
                                        <td width="65%">
                                            <b>d. Tunjangan Tidak Tetap</b>
                                        </td>
                                        <td width="5%">:</td>
                                        <td width="30%"></td>
                                    </tr>
                                    <tr class="text-green">
                                        <td width="65%">
                                            <span class="child">- Tunjangan Jabatan</span>
                                        </td>
                                        <td width="5%">:</td>
                                        <td width="30%">
                                            <span class="align-left">Rp.</span>
                                            <span class="align-right">{{ rp2($payroll->tunjangan_jabatan) }}</span>
                                        </td>
                                    </tr>
                                    <tr class="text-green">
                                        <td width="65%">
                                            <span class="child">- Guarantee Fee</span>
                                        </td>
                                        <td width="5%">:</td>
                                        <td width="30%">
                                            <span class="align-left">Rp.</span>
                                            <span class="align-right">{{ rp2($payroll->guarantee_fee) }}</span>
                                        </td>
                                    </tr>
                                    <tr class="text-green">
                                        <td width="65%">
                                            <span class="child">- Uang Duduk</span>
                                        </td>
                                        <td width="5%">:</td>
                                        <td width="30%" style="border-bottom: 1px solid rgba(0, 0, 0, 0.884)">
                                            <span class="align-left">Rp.</span>
                                            <span class="align-right">{{ rp2($payroll->uang_duduk) }}</span>
                                        </td>
                                    </tr>
                                    <tr class="text-green">
                                        <td width="65%">
                                            <b>e. Jumlah ( c + d )</b>
                                        </td>
                                        <td width="5%">:</td>
                                        <td width="30%">
                                            <span class="align-left"><b>Rp.</b></span>
                                            <span
                                                class="align-right"><b>{{ rp2($payroll->tunjangan_jabatan + $payroll->guarantee_fee + $payroll->uang_duduk) }}</b></span>

                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        <td style="width: 50% !important; padding-left: 10px; font-size: 9pt !important"
                            valign="top">
                            <table width="100%">
                                <tbody>
                                    <tr>
                                        <td colspan="3"><b><u>KOMPONEN POTONGAN</b></u></td>
                                    </tr>
                                    <tr class="text-red">
                                        <td width="60%">
                                            <b>f. Simpanan Pokok Koperasi</b>
                                        </td>
                                        <td width="5%">:</td>
                                        <td width="35%">
                                            <span class="align-left">Rp.</span>
                                            <span class="align-right">{{ rp2($payroll->simpanan_pokok) }}</span>
                                        </td>
                                    </tr>
                                    <tr class="text-red">
                                        <td width="60%">
                                            <b>g. BPJS Kesehatan</b>
                                        </td>
                                        <td width="5%">:</td>
                                        <td width="35%">

                                            <span class="align-left">Rp.</span>
                                            <span
                                                class="align-right">{{ rp2($payroll->potongan_bpjs_kesehatan) }}</span>
                                        </td>
                                    </tr>
                                    <tr class="text-red">
                                        <td width="60%">
                                            <b>h. BPJS TK (JHT)</b>
                                        </td>
                                        <td width="5%">:</td>
                                        <td width="35%">

                                            <span class="align-left">Rp.</span>
                                            <span
                                                class="align-right">{{ rp2($payroll->potongan_bpjs_ketenagakerjaan) }}</span>
                                        </td>
                                    </tr>
                                    <tr class="text-red">
                                        <td width="60%">
                                            <b>i. Pinjaman Koperasi</b>
                                        </td>
                                        <td width="5%">:</td>
                                        <td width="35%">

                                            <span class="align-left">Rp.</span>
                                            <span class="align-right">{{ rp2($payroll->potongan_koperasi) }}</span>
                                        </td>
                                    </tr>
                                    <tr class="text-red">
                                        <td width="60%">
                                            <b>j. Potongan Keterlambatan</b>
                                        </td>
                                        <td width="5%">:</td>
                                        <td width="35%">

                                            <span class="align-left">Rp.</span>
                                            <span
                                                class="align-right">{{ rp2($payroll->potongan_keterlambatan) }}</span>
                                        </td>
                                    </tr>
                                    <tr class="text-red">
                                        <td width="60%">
                                            <b>k. Potongan Izin</b>
                                        </td>
                                        <td width="5%">:</td>
                                        <td width="35%">

                                            <span class="align-left">Rp.</span>
                                            <span class="align-right">{{ rp2($payroll->potongan_izin) }}</span>
                                        </td>
                                    </tr>
                                    <tr class="text-red">
                                        <td width="60%">
                                            <b>l. Potongan Pajak</b>
                                        </td>
                                        <td width="5%">:</td>
                                        <td width="35%">

                                            <span class="align-left">Rp.</span>
                                            <span class="align-right">{{ rp2($payroll->potongan_pajak) }}</span>
                                        </td>
                                    </tr>
                                    <tr class="text-red">
                                        <td width="60%">
                                            <b>m. Jumlah (f s.d. m)</b>
                                        </td>
                                        <td width="5%">:</td>
                                        <td width="35%">
                                            <span class="align-left"><b>Rp.</b></span>
                                            <span
                                                class="align-right"><b>{{ rp2($payroll->total_deduction) }}</b></span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="content-wrapper"
                style="padding-top: 5px; padding-bottom: 8px; font-weight: 600; margin-top: -9px;">
                <table width="100%" class="total-gaji" style="font-family: 'Roboto', sans-serif !important">
                    <tr>
                        <td width="80.5%">
                            <span>TOTAL GAJI DITERIMA</span>
                        </td>
                        <td width="2%">:</td>
                        <td width="17.5%">
                            <span class="align-left">Rp.</span>
                            <span class="align-right">{{ rp2($payroll->take_home_pay) }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td width="80.5%">
                            <span>PEMBULATAN</span>
                        </td>
                        <td width="2%">:</td>
                        <td width="17.5%">
                            <span class="align-left">Rp.</span>
                            @php
                                $amount = $payroll->take_home_pay; // Ganti dengan nilai yang sesuai
                                $roundedAmount = floor($amount / 500) * 500;
                            @endphp
                            <span class="align-right">{{ rp2($roundedAmount) }}</span>
                        </td>
                    </tr>

                </table>
            </div>
            <div class="content-wrapper" style="display: flex; font-weight: 600; margin-top: -9px;">
                <table width="100%">
                    <tr>
                        <td style="width: 50% !important">
                            <div class="text-red"
                                style="border: 1px solid rgba(0, 0, 0, 0.884); padding: 5px 10px 5px 20px; font-family: 'Roboto', sans-serif;">
                                *) Gaji Selama 1 bulan <br>
                                **)
                            </div>
                        </td>
                        <td style="width: 50% !important">
                            <table style="margin-left: 70px">
                                <tr>
                                    <td>Majalengka,
                                        {{ \Carbon\Carbon::parse($payroll->updated_at)->translatedFormat('d F Y') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td><img src="{{ asset('img/ttd-ibu.png') }}" alt="TTD Ibu" width="60">
                                    </td>
                                </tr>
                                <tr>
                                    <td><u>apt. Lia Valini, S. Farm.</u></td>
                                </tr>
                                <tr>
                                    <td>Wakil Direktur RS Livasya</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    @endforeach
</body>

</html>
