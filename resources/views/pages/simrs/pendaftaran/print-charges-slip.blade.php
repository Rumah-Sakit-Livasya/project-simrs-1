<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@medic Information System - Slip Jasa Dokter</title>
    <style>
        body {
            background-color: #f0f0f0;
            font-family: Calibri, Arial, sans-serif;
            font-size: 14px;
            margin: 0;
            padding: 20px;
        }

        .slip-wrapper {
            width: 7cm;
            margin: 0 auto;
            background-color: #fff;
            padding: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .print-actions {
            padding-bottom: 15px;
            text-align: left;
        }

        .print-actions button {
            padding: 5px 15px;
            margin-right: 5px;
            cursor: pointer;
        }

        .slip-header {
            text-align: center;
            margin-bottom: 15px;
        }

        .slip-header h1 {
            font-size: 1.2em;
            font-weight: bold;
            margin: 0 0 5px 0;
        }

        .slip-header span {
            display: block;
            font-size: 1.1em;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 0.9em;
        }

        .info-table td {
            padding: 2px 0;
        }

        .info-table td:first-child {
            width: 30%;
        }

        .dotted-lines {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .dotted-lines .dot {
            border-bottom: 1px dotted #000;
            height: 20px;
        }

        .slip-footer {
            font-size: 0.8em;
            font-family: Arial, sans-serif;
            padding: 10px 0;
        }

        .slip-footer span {
            display: block;
        }

        .signature {
            padding-top: 60px;
            font-family: Arial, sans-serif;
            font-size: 0.9em;
            text-align: center;
        }

        .signature strong u {
            text-decoration: none;
            border-bottom: 1px solid #000;
            padding-bottom: 2px;
        }

        @media print {
            body {
                background-color: #fff;
                padding: 0;
            }

            .print-actions,
            .slip-wrapper {
                box-shadow: none;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="slip-wrapper">
        <div class="print-actions no-print">
            <button onclick="window.print()">Print</button>
            <button onclick="window.close()">Tutup</button>
        </div>

        <div class="slip-header">
            <h1>SLIP JASA DOKTER</h1>
            <span>NO. ANTRIAN : {{ $registration['no_urut'] ?? '-' }}</span>
        </div>

        <table class="info-table">
            <tbody>
                <tr>
                    <td>Bagian/ poli</td>
                    <td>: {{ $registration['poliklinik'] ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Nomor RM</td>
                    <td>:
                        @if (isset($registration['patient']) && isset($registration['patient']['no_rm']))
                            {{ $registration['patient']['no_rm'] }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Nama Pasien</td>
                    <td>:
                        @if (isset($registration['patient']) && isset($registration['patient']['name']))
                            {{ $registration['patient']['name'] }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Nama Dokter</td>
                    <td>:
                        @if (isset($registration['doctor']) && isset($registration['doctor']['name']))
                            {{ $registration['doctor']['name'] }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td>:
                        @php
                            use Illuminate\Support\Carbon;
                            $date = $registration['registration_date'] ?? ($registration['date'] ?? null);
                        @endphp
                        {{ $date ? \Illuminate\Support\Carbon::parse($date)->translatedFormat('d M Y H:i:s') : '-' }}
                    </td>
                </tr>
                <tr>
                    <td>No. Registrasi</td>
                    <td>: {{ $registration['registration_number'] ?? '-' }}</td>
                </tr>
            </tbody>
        </table>

        <table class="dotted-lines">
            <tbody>
                @for ($i = 0; $i < 8; $i++)
                    <tr>
                        <td>
                            <div class="dot"></div>
                        </td>
                    </tr>
                @endfor
            </tbody>
        </table>

        <div class="slip-footer">
            <span>Petugas :
                @if (isset($registration['user']) && isset($registration['user']['name']))
                    {{ $registration['user']['name'] }}
                @else
                    -
                @endif
            </span>
            <span>Dicetak : {{ now()->translatedFormat('d M Y') }}</span>
        </div>

        <div class="signature">
            <strong>
                <u>(
                    @if (isset($registration['doctor']) && isset($registration['doctor']['name']))
                        {{ $registration['doctor']['name'] }}
                    @else
                        -
                    @endif
                    )
                </u>
            </strong>
        </div>

    </div>

</body>

</html>
