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

        html,
        body {
            width: 400px;
            height: 400px;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 400px;
            height: 400px;
            margin: 0;
            overflow: hidden;
            position: relative;
        }

        .container {
            transform: rotate(90deg);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
    </style>
    <title>Kartu Pasien</title>
</head>

<body>
    <div class="container">
        <p>
            <strong>No RM: {{ $patient->medical_record_number }}</strong>
        </p>
        <p>
            <strong>Nama: {{ strtoupper($patient->name) }}</strong>
        </p>
        <p>
            <strong>Tgl/Umur: {{ \Carbon\Carbon::parse($patient->date_of_birth)->format('d-m-Y') }}
                ({{ hitungUmur($patient->date_of_birth) }})</strong>
        </p>
        <p>
            <strong>Suami: {{ $patient->husband_name ?? '-' }}</strong>
        </p>
        <p>
            <strong>No. Telp: {{ $patient->mobile_phone_number ?? '-' }}</strong>
        </p>
    </div>
</body>

</html>
