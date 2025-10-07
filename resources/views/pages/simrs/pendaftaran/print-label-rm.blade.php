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
            box-sizing: border-box;
        }

        html,
        body {
            width: 200px;
            height: 300px;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        body {
            width: 200px;
            height: 300px;
            margin: 0;
            padding: 0;
            overflow: hidden;
            /* Center content after rotation */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            width: 100%;
            height: 280px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            /* Rotate label as before */
            transform: rotate(90deg);
            /* Center after rotation */
            position: absolute;
            right: 40px;
            top: -40;
            /* Add margin from border */
            margin: 10px;
            /* To keep the rotated content inside the page */
            box-sizing: border-box;
        }

        .container p {
            margin: 8px 0;
            padding-left: 8px;
            /* Text left by default */
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
