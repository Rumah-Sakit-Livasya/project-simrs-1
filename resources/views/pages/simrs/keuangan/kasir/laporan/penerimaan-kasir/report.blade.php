<!DOCTYPE HTML>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Laporan Penerimaan Kasir</title>
    {{-- Using a simplified style similar to the reference --}}
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11pt;
            background-color: #FFF;
        }

        .print-container {
            padding: 10px;
        }

        .print-buttons {
            margin-bottom: 20px;
        }

        .print-buttons input {
            padding: 4px 8px;
            cursor: pointer;
        }

        h1 {
            font-size: 12pt;
            text-align: center;
            margin: 0;
            padding: 0;
        }

        h1 span {
            display: block;
            font-size: 11pt;
            font-weight: normal;
        }

        .bordered {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 10pt;
        }

        .bordered th,
        .bordered td {
            border: 1px solid #c8c9c9;
            padding: 5px;
            vertical-align: top;
        }

        .bordered th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .subtotal-row td,
        .grandtotal-row td {
            font-weight: bold;
            border-top: 1px solid #c8c9c9;
        }

        @media print {
            .print-buttons {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="print-container">
        <div class="print-buttons" id="divButtons">
            <input type="button" value="Print" onclick="printPage();" />
            <input type="button" value="Close" onclick="window.close();" />
        </div>

        <h1>
            LAPORAN PENERIMAAN KASIR
            <br />
            <span>Tanggal : {{ $periodeAwal }} - {{ $periodeAkhir }}</span>
        </h1>

        @php $grandTotal = 0; @endphp

        @if ($groupedData->isEmpty())
            <p style="text-align:center; margin-top: 20px;">Tidak ada data yang ditemukan untuk kriteria yang dipilih.
            </p>
        @else
            @foreach ($groupedData as $namaKasir => $penjaminGroups)
                <table width="100%" style="font-size: 11pt; margin-top: 20px;">
                    <tr>
                        <td><strong>Petugas Kasir : {{ $namaKasir }}</strong></td>
                    </tr>
                    <tr>
                        <td>
                            <table class="bordered">
                                <thead>
                                    <tr>
                                        <th width="3%">NO</th>
                                        <th width="12%">Tanggal</th>
                                        <th>No.Bill/No.Reg<br />Nama Pasien</th>
                                        <th width="15%">Poly/Ruang</th>
                                        <th width="15%">Metode</th>
                                        <th width="20%">Keterangan</th>
                                        <th width="12%">Nominal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $totalKasir = 0; @endphp

                                    @foreach ($penjaminGroups as $namaPenjamin => $transactions)
                                        @foreach ($transactions as $index => $item)
                                            <tr>
                                                <td>{{ $loop->parent->parent->iteration }}</td>
                                                <td>{{ \Carbon\Carbon::parse($item->tgl_bayar)->format('d-m-Y H:i') }}
                                                </td>
                                                <td>
                                                    {{ $item->bill_no }}/{{ $item->no_reg }}<br />
                                                    [{{ $item->no_rm }}] {{ $item->nama_pasien }}
                                                </td>
                                                <td>{{ $item->nama_poli }}</td>
                                                <td>{{ $item->nama_penjamin }}</td>
                                                <td>{{ $item->keterangan }}</td>
                                                <td class="text-right">{{ number_format($item->nominal, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach

                                    {{-- Subtotals per payment method --}}
                                    @foreach ($penjaminGroups as $namaPenjamin => $transactions)
                                        @php
                                            $subtotalPenjamin = $transactions->sum('nominal');
                                            $totalKasir += $subtotalPenjamin;
                                        @endphp
                                        <tr class="subtotal-row">
                                            <td colspan="6" class="text-right">{{ $namaPenjamin }}</td>
                                            <td class="text-right">{{ number_format($subtotalPenjamin, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach

                                    {{-- Total per cashier --}}
                                    <tr class="subtotal-row">
                                        <td colspan="6" class="text-right">Total {{ $namaKasir }}</td>
                                        <td class="text-right">{{ number_format($totalKasir, 0, ',', '.') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </table>
                @php $grandTotal += $totalKasir; @endphp
            @endforeach

            {{-- Grand Total Section --}}
            <table class="bordered" style="margin-top:20px; width: 50%; float: right;">
                <tr class="grandtotal-row">
                    <td class="text-right" style="width: 70%;">Grand Total</td>
                    <td class="text-right">{{ number_format($grandTotal, 0, ',', '.') }}</td>
                </tr>
            </table>
        @endif
    </div>

    <script type="text/javascript">
        function printPage() {
            document.getElementById('divButtons').style.display = 'none';
            window.print();
            document.getElementById('divButtons').style.display = '';
            return false;
        }
    </script>
</body>

</html>
