<!DOCTYPE html>
<html>

<head>
    <style>
        @page {
            size: 200px 300px;
            margin: 0;
        }

        * {
            font-family: sans-serif;
            margin: 0;
        }

        .relative {
            position: relative;
        }

        .absolute {
            position: absolute;
        }

        .container {
            transform: rotate(90deg);
            top: 100px;
            left: -15px;
        }

        body {
            width: 400px;
            height: 400px;
            margin: 0;
            overflow: hidden;
        }
    </style>
    <title>Kartu Pasien</title>
</head>

<body class="relative">
    <div class="container absolute">
        <p>
            <strong>No RM: {{ $patient->medical_record_number }}</strong>
        </p>
        <br>
        <p>
            <strong>Nama: {{ strtoupper($patient->name) }}</strong>
        </p>
        <br>
        <p>
            <strong>Tgl/Umur: {{ \Carbon\Carbon::parse($patient->date_of_birth)->format('d-m-Y') }}
                ({{ hitungUmur($patient->date_of_birth) }})</strong>
        </p>
    </div>

</body>

</html>
