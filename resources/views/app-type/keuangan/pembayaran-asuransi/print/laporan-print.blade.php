<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Laporan')</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            margin: 20px;
            color: #000;
        }

        h1,
        h2,
        h3 {
            margin-bottom: 5px;
        }

        .subtitle {
            margin-bottom: 20px;
            font-size: 10pt;
        }

        .tools {
            margin-bottom: 20px;
        }

        .tools button,
        .tools a {
            margin-right: 10px;
            padding: 5px 12px;
            font-size: 9pt;
            border: none;
            background-color: #007bff;
            color: white;
            border-radius: 3px;
            cursor: pointer;
            text-decoration: none;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table,
        th,
        td {
            border: 1px solid #333;
        }

        th,
        td {
            padding: 5px;
            text-align: left;
        }

        .no-data {
            margin-top: 20px;
            color: red;
            font-weight: bold;
        }

        @media print {
            .tools {
                display: none;
            }
        }
    </style>
    @yield('style')
</head>

<body>

    <div class="tools">
        <button onclick="window.print()">🖨️ Print</button>
        <a href="#" onclick="alert('Export XLS belum diaktifkan'); return false;">📄 Export XLS</a>
        <button onclick="window.close()">❌ Close</button>
    </div>

    <h2>@yield('title', 'Laporan')</h2>
    <p class="subtitle">@yield('subtitle')</p>

    @yield('content')

</body>

</html>
