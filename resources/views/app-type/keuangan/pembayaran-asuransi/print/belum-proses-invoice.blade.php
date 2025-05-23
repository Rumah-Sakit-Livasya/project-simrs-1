<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Belum Proses Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            margin: 20px;
            padding: 0;
        }

        .tools {
            margin-bottom: 20px;
            display: flex;
            gap: 5px;
        }

        .tools button,
        .tools a {
            background-color: #f0f0f0;
            color: #000;
            padding: 5px 15px;
            border: 1px solid #ccc;
            border-radius: 0;
            text-decoration: none;
            font-size: 11pt;
            cursor: pointer;
        }

        .report-header {
            margin-bottom: 20px;
        }

        .report-title {
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 5px;
            color: #006400;
            /* Dark green color */
        }

        .report-period {
            margin-bottom: 5px;
        }

        .report-info {
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
            font-size: 10pt;
        }

        th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }

        td.right {
            text-align: right;
        }

        td.center {
            text-align: center;
        }

        .total-row td {
            text-align: right;
            font-weight: bold;
        }

        .report-divider {
            border-top: 1px solid #000;
            margin: 10px 0;
        }

        @media print {
            .tools {
                display: none;
            }

            body {
                margin: 0;
                padding: 15px;
            }
        }
    </style>
</head>

<body>
    <div class="tools">
        <button onclick="window.print()">Print</button>
        <button onclick="exportToExcel()">xls</button>
        <button onclick="window.close()">Close</button>
    </div>

    <div class="report-header">
        <div class="report-title">LAPORAN BELUM PROSES INVOICE</div>
        <div class="report-period">
            PERIODE TGL {{ \Carbon\Carbon::parse($period_start)->format('d-m-Y') ?? '-' }} s/d
            {{ \Carbon\Carbon::parse($period_end)->format('d-m-Y') ?? '-' }}
        </div>
        <div class="report-info">
            Penjamin:
            @if (request('penjamin_id') && $data->isNotEmpty())
                {{ optional($data->first()->penjamin)->nama_perusahaan ?? '-' }}
            @else
                Semua Penjamin
            @endif
        </div>
    </div>

    <div class="report-divider"></div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Poliklinik</th>
                <th>Tgl. Bill</th>
                <th>No. Bill</th>
                <th>No. Reg</th>
                <th>No. RM</th>
                <th>Nama Pasien</th>
                <th>Penjamin</th>
                <th>Jumlah Tagihan</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @forelse ($data as $index => $item)
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td> {{ $item->registration->departement->name ?? '_' }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                    <td>{{ $item->no_bill ?? '-' }}</td>
                    <td>{{ $item->registration->registration_number ?? '-' }}</td>
                    <td>{{ $item->registration->patient->medical_record_number ?? '-' }}</td>
                    <td>{{ $item->registration->patient->name ?? '-' }}</td>
                    <td>{{ $item->penjamin->nama_perusahaan ?? '-' }}</td>
                    <td class="right">{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                </tr>
                @php $total += $item->jumlah; @endphp
            @empty
                <tr>
                    <td colspan="8" class="center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="8" style="text-align: right;">Total :</td>
                <td class="right">{{ number_format($total, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <script>
        function exportToExcel() {
            // Implementasi export Excel bisa ditambahkan nanti
            alert('Export XLS belum tersedia');
        }
    </script>
</body>

</html>
