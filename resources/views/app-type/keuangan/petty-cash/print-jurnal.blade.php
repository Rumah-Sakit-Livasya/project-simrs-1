<!DOCTYPE HTML>
<html>

<head>
    <title>Jurnal Petty Cash - {{ $pettycash->kode_transaksi }}</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <style>
        /* Print-friendly styling */
        @page {
            margin: 15mm;
            size: A4;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', serif;
            font-size: 11pt;
            line-height: 1.3;
            margin: 0;
            padding: 15px;
            color: #000;
            background: white;

        }

        /* Header Section */
        .document-header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
        }

        .company-name {
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .company-address {
            font-size: 10pt;
            margin-bottom: 10px;
            color: #333;
        }

        .document-title {
            font-size: 14pt;
            font-weight: bold;
            margin-top: 10px;
        }

        /* Info Section */
        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .info-left,
        .info-right {
            width: 48%;
        }

        .info-item {
            margin-bottom: 5px;
            font-size: 10pt;
        }

        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 120px;
        }

        .info-value {
            color: #333;
        }

        .document-id {
            text-align: center;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .document-id .label {
            font-size: 10pt;
            color: #666;
            display: block;
        }

        .document-id .value {
            font-size: 12pt;
        }

        /* Journal Table */
        .journal-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 10pt;
        }

        .journal-table th {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
            font-weight: bold;
        }

        .journal-table td {
            border: 1px solid #000;
            padding: 8px;
            vertical-align: top;
        }

        /* Column alignments */
        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        /* Amount formatting */
        .amount {
            font-family: 'Courier New', monospace;
            text-align: right;
            white-space: nowrap;
        }

        /* Balance row styling */
        .balance-row {
            font-weight: bold;
        }

        .balance-row td {
            border-top: 2px solid #000;
        }

        /* Signature Section */
        .signature-section {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
        }

        .signature-box {
            width: 45%;
            text-align: center;
        }

        .signature-title {
            font-weight: bold;
            margin-bottom: 60px;
            font-size: 10pt;
        }

        .signature-line {
            border-bottom: 1px solid #000;
            width: 150px;
            margin: 0 auto 5px;
        }

        .signature-name {
            font-size: 9pt;
        }

        /* Print-specific styles */
        @media print {
            body {
                font-size: 10pt;
            }

            .document-header {
                margin-bottom: 15px;
            }

            .signature-section {
                page-break-inside: avoid;
                margin-top: 20px;
            }
        }

        /* Responsive adjustments */
        @media screen and (max-width: 768px) {
            .info-section {
                flex-direction: column;
            }

            .info-left,
            .info-right {
                width: 100%;
                margin-bottom: 10px;
            }

            .journal-table {
                font-size: 9pt;
            }

            .journal-table th,
            .journal-table td {
                padding: 6px;
            }
        }
    </style>
</head>

<body>
    <!-- Document Content -->
    <div class="document-content">
        <!-- Header -->
        <div class="document-header">
            <div class="company-name">RS LIVASYA</div>
            <div class="company-address">
                Jl. Raya Timur III Dawuan No. 875, Kab. Majalengka<br>
                Phone: 081211151300 | Kab. Majalengka - Jawa Barat
            </div>
            <div class="document-title">JURNAL UMUM</div>
        </div>

        <!-- Document ID -->


        <!-- Info Section -->
        <div class="info-section">
            <div class="info-left">
                <div class="info-item">
                    <span class="info-label">Tanggal Transaksi</span>
                    : <span class="info-value">{{ \Carbon\Carbon::parse($pettycash->tanggal)->format('d F Y') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Keterangan</span>
                    : <span class="info-value">{{ $pettycash->keterangan ?: '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Kas/Bank</span>
                    : <span class="info-value">{{ $pettycash->kas_nama ?: 'N/A' }}</span>
                </div>
            </div>
            <div class="info-right">
                <div class="info-item">
                    <span class="info-label">Tanggal Entry</span>
                    : <span
                        class="info-value">{{ \Carbon\Carbon::parse($pettycash->created_at)->format('d F Y H:i') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">User Entry</span>
                    : <span class="info-value">{{ $pettycash->user_name ?: 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Doc</span>
                    : <span class="info-value">{{ $pettycash->kode_transaksi }}</span>
                </div>
            </div>
        </div>

        <!-- Journal Table -->
        <table class="journal-table">
            <thead>
                <tr>
                    <th width="8%">No</th>
                    <th width="12%">Kode COA</th>
                    <th width="22%">Nama Akun</th>
                    <th width="25%">Keterangan</th>
                    <th width="13%">Debet</th>
                    <th width="13%">Kredit</th>
                    <th width="7%">Cost Center</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($details as $index => $detail)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-center">{{ $detail->coa_code ?: '-' }}</td>
                        <td>{{ $detail->coa_name ?: 'N/A' }}</td>
                        <td>{{ $detail->keterangan ?: '-' }}</td>
                        <td class="amount">{{ number_format($detail->nominal, 2, ',', '.') }}</td>
                        <td class="amount">0,00</td>
                        <td class="text-center">{{ $detail->nama_rnc ?: '-' }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td class="text-center">{{ count($details) + 1 }}</td>
                    <td class="text-center">{{ $pettycash->kas_code ?? '-' }}</td>
                    <td>{{ $pettycash->kas_nama ?: 'Kas/Bank' }}</td>
                    <td>{{ $pettycash->keterangan ?: 'Pengeluaran Petty Cash' }}</td>
                    <td class="amount">0,00</td>
                    <td class="amount">{{ number_format($pettycash->total_nominal, 2, ',', '.') }}</td>
                    <td class="text-center">-</td>
                </tr>
                <tr class="balance-row">
                    <td colspan="4" class="text-center"><strong>TOTAL SALDO</strong></td>
                    <td class="amount">
                        <strong>{{ number_format($totalDebet ?? $pettycash->total_nominal, 2, ',', '.') }}</strong>
                    </td>
                    <td class="amount">
                        <strong>{{ number_format($totalKredit ?? $pettycash->total_nominal, 2, ',', '.') }}</strong>
                    </td>
                    <td class="text-center">-</td>
                </tr>
            </tbody>
        </table>

        <!-- Additional Information -->
        <div style="margin: 15px 0; font-size: 10pt; color: #666;">
            <p><strong>Catatan:</strong></p>
            <ul style="margin: 5px 0; padding-left: 20px;">
                <li>Dokumen ini dicetak otomatis dari sistem pada {{ now()->format('d F Y H:i:s') }}</li>
                <li>Dokumen ini sah tanpa tanda tangan jika dicetak dari sistem</li>
            </ul>
        </div>

        <!-- Signature Section -->
        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-title">Diperiksa Oleh</div>
                <div class="signature-line"></div>
            </div>
            <div class="signature-box">
                <div class="signature-title">Mengetahui</div>
                <div class="signature-line"></div>
            </div>
        </div>
    </div>
</body>

</html>
