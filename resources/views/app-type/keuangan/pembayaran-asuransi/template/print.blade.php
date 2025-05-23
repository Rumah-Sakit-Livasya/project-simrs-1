<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Laporan')</title>
    <style>
        body {
            font-family: 'Open Sans';
            src: url('opensans.ttf');
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
            font-weight: bold;
            text-align: right;
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


    <div class="report-divider"></div>

    @yield('content')

    <script>
        function exportToExcel() {
            // Implementasi export Excel bisa ditambahkan nanti
            alert('Export XLS belum tersedia');
        }
    </script>
</body>

</html>
