<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@medic Information System - Brave</title>
    <style>
        body {
            background-color: #E6E6E6;
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .page-container {
            max-width: 90%;
            margin: 20px auto;
            background-color: #fff;
            padding: 25px;
            border: 1px solid #ccc;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding-bottom: 10px;
            border-bottom: 2px solid #000;
        }

        .hospital-info {
            display: flex;
            align-items: center;
        }

        .hospital-logo {
            width: 60px;
            height: 60px;
            margin-right: 15px;
        }

        .hospital-details .name {
            font-weight: bold;
            font-size: 14px;
        }

        .hospital-details .address {
            font-size: 12px;
        }

        .tracer-info {
            text-align: right;
        }

        .tracer-info .title {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }

        .tracer-info .subtitle {
            font-size: 16px;
            font-weight: bold;
            margin: 0;
        }

        .patient-info-container {
            padding: 15px 0;
            display: flex;
            justify-content: space-between;
        }

        .patient-info-container table {
            width: 100%;
            border-collapse: collapse;
        }

        .patient-info-container th,
        .patient-info-container td {
            padding: 2px 5px;
            vertical-align: top;
        }

        .patient-info-container th {
            text-align: left;
            font-weight: normal;
        }

        .patient-info-container .label {
            width: 120px;
        }

        .patient-info-container .separator {
            width: 10px;
        }

        .checkbox-item {
            display: flex;
            align-items: center;
        }

        .checkbox {
            width: 15px;
            height: 15px;
            border: 1px solid #000;
            margin-right: 8px;
        }

        .section {
            margin-bottom: 10px;
        }

        .section-title {
            font-weight: bold;
            border-bottom: 1px solid #000;
            padding-bottom: 3px;
            margin-bottom: 8px;
        }

        .grid-table,
        .details-table {
            width: 100%;
            border-collapse: collapse;
        }

        .grid-table td {
            padding: 4px;
        }

        .details-table th,
        .details-table td {
            padding: 4px;
            border: 1px solid #000;
        }

        .details-table th {
            text-align: left;
            font-weight: normal;
        }

        .note {
            font-size: 10px;
            padding-top: 5px;
        }

        .dotted-line {
            border-bottom: 1px dotted #000;
            display: inline-block;
            width: 150px;
        }

        .signatures-container {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
        }

        .signature-box {
            width: 45%;
            text-align: center;
        }

        .signature-box .placeholder {
            padding-bottom: 60px;
        }

        .action-buttons {
            padding-bottom: 15px;
        }

        .action-buttons button {
            padding: 5px 15px;
            margin-right: 5px;
        }

        @media print {
            body {
                background-color: #fff;
            }

            .action-buttons,
            .page-container {
                box-shadow: none;
                margin: 0;
                border: none;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="page-container">
        <div class="action-buttons no-print">
            <button onclick="window.print()">Print</button>
            <button onclick="window.close()">Tutup</button>
        </div>
        <div class="header-container">
            <div class="hospital-info">
                <img src="/img/logo.png" alt="Logo Rumah Sakit" class="hospital-logo">
                <div class="hospital-details">
                    <div class="name">Rumah Sakit Livasya</div>
                    <div class="address">Jl. Raya Timur III Dawuan No. 875 Kab. Majalengka, Jawa Barat</div>
                </div>
            </div>
            <div class="tracer-info">
                <h1 class="title">TRACER</h1>
                <h2 class="subtitle">ASLI</h2>
            </div>
        </div>

        <div class="patient-info-container">
            <table>
                <tr>
                    <td class="label">Tanggal</td>
                    <td class="separator">:</td>
                    <td>
                        {{ \Carbon\Carbon::parse($registration['registration_date'] ?? $registration['date'])->translatedFormat('d M Y') }}
                        Jam :
                        {{ \Carbon\Carbon::parse($registration['registration_date'] ?? $registration['date'])->format('H:i:s') }}
                    </td>
                    <td class="label">No Reg</td>
                    <td class="separator">:</td>
                    <td>{{ $registration['registration_number'] ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">No RM</td>
                    <td class="separator">:</td>
                    <td>
                        @if (isset($registration['patient']) && isset($registration['patient']['medical_record_number']))
                            {{ $registration['patient']['medical_record_number'] }}
                        @else
                            {{ $registration['patient_id'] ?? '-' }}
                        @endif
                    </td>
                    <td class="label">Ttl</td>
                    <td class="separator">:</td>
                    <td>
                        @if (isset($registration['patient']) && isset($registration['patient']['birth_date']))
                            {{ \Carbon\Carbon::parse($registration['patient']['birth_date'])->translatedFormat('d M Y') }}
                            Umur :
                            {{ \Carbon\Carbon::parse($registration['patient']['birth_date'])->diff(\Carbon\Carbon::parse($registration['registration_date'] ?? $registration['date']))->y }}thn,
                            {{ \Carbon\Carbon::parse($registration['patient']['birth_date'])->diff(\Carbon\Carbon::parse($registration['registration_date'] ?? $registration['date']))->m }}bln,
                            {{ \Carbon\Carbon::parse($registration['patient']['birth_date'])->diff(\Carbon\Carbon::parse($registration['registration_date'] ?? $registration['date']))->d }}hr
                        @else
                            -
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="label">Nama Pasien</td>
                    <td class="separator">:</td>
                    <td>
                        @if (isset($registration['patient']) && isset($registration['patient']['name']))
                            {{ strtoupper($registration['patient']['name']) }}
                            @if (isset($registration['patient']['gender']))
                                , ({{ strtoupper(substr($registration['patient']['gender'], 0, 1)) }})
                            @endif
                        @else
                            -
                        @endif
                    </td>
                    <td class="label">Dokter</td>
                    <td class="separator">:</td>
                    <td>
                        @if (isset($registration['doctor']) && isset($registration['doctor']['name']))
                            dr. {{ $registration['doctor']['name'] }}
                        @else
                            @if (isset($registration['doctor_id']))
                                {{ $registration['doctor_id'] }}
                            @else
                                -
                            @endif
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="label">No. KTP/SIM</td>
                    <td class="separator">:</td>
                    <td>
                        @if (isset($registration['patient']) && isset($registration['patient']['id_card']))
                            {{ $registration['patient']['id_card'] }}
                        @else
                            -
                        @endif
                        @if (isset($registration['patient']) && isset($registration['patient']['mobile_phone_number']))
                            Hp : {{ $registration['patient']['mobile_phone_number'] }}
                        @endif
                    </td>
                    <td class="label">Poli</td>
                    <td class="separator">:</td>
                    <td>{{ $registration['poliklinik'] ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Penanggung Jawab</td>
                    <td class="separator">:</td>
                    <td>
                        @if (isset($registration['patient']) && isset($registration['patient']['family']['family_name']))
                            {{ $registration['patient']['family']['family_name'] }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="label">Cara Bayar</td>
                    <td class="separator">:</td>
                    <td>
                        @if (isset($registration['penjamin']))
                            {{ $registration['penjamin']['nama_perusahaan'] }}
                        @elseif(isset($registration['penjamin_id']))
                            {{ $registration['penjamin_id'] }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            </table>
        </div>

        <div class="section">
            <div class="section-title">Biaya Administrasi Rawat Jalan</div>
            <table class="grid-table">
                <tr>
                    <td>
                        <div class="checkbox-item">
                            <div class="checkbox" style="@if ($registration['registration_type'] === 'rawat-jalan') background:#000; @endif">
                            </div> Pendaftaran
                        </div>
                    </td>
                    <td>
                        <div class="checkbox-item">
                            <div class="checkbox" style="@if (isset($registration['kartu_pasien']) && $registration['kartu_pasien']) background:#000; @endif">
                            </div> Kartu Pasien
                        </div>
                    </td>
                    <td>
                        <div class="checkbox-item">
                            <div class="checkbox"></div> Buku Paspor
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="section">
            <div class="section-title">Jenis Tindakan</div>
            <table class="grid-table">
                <tr>
                    <td style="width: 33.33%;">
                        <div class="checkbox-item">
                            <div class="checkbox" style="@if (isset($registration['tindakan']) && str_contains(strtolower($registration['tindakan']), 'konsultasi')) background:#000; @endif">
                            </div> Konsultasi
                        </div>
                    </td>
                    <td style="width: 66.67%;">
                        <div class="checkbox-item">
                            <div class="checkbox" style="@if (isset($registration['tindakan']) && str_contains(strtolower($registration['tindakan']), 'tindakan medis')) background:#000; @endif">
                            </div> Tindakan Medis <span class="dotted-line"></span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="checkbox-item">
                            <div class="checkbox" style="@if (isset($registration['tindakan']) && str_contains(strtolower($registration['tindakan']), 'tindakan medis')) background:#000; @endif">
                            </div> Tindakan Medis <span class="dotted-line"></span>
                        </div>
                    </td>
                    <td></td>
                </tr>
            </table>
        </div>

        <div class="section">
            <table class="details-table">
                <tr>
                    <th style="width: 15%;">Resep</th>
                    <td style="width: 18.33%;">Ada / Tidak Ada*</td>
                    <th style="width: 15%;">Radiologi</th>
                    <td style="width: 18.33%;">Ada / Tidak Ada*</td>
                    <th style="width: 15%;">Laboratorium</th>
                    <td style="width: 18.33%;">Ada / Tidak Ada*</td>
                </tr>
                <tr>
                    <th>EKG</th>
                    <td>Ada / Tidak Ada*</td>
                    <td colspan="4"></td>
                </tr>
            </table>
            <div class="note">Note : * (diisi oleh staf)</div>
        </div>

        <div class="signatures-container">
            <div class="signature-box">
                <div class="placeholder">Paraf Verifikasi</div>
                <div>( Kasir / Administrasi )</div>
            </div>
            <div class="signature-box">
                <div class="placeholder">Paraf Assisten Dokter</div>
                <div>
                    ( Zr/Bd
                    <span style="display: inline-block; width: 180px; border-bottom: 1px dotted #000;"></span>
                    )
                </div>
            </div>
        </div>
    </div>
</body>

</html>
