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
            top: 80px;
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
        <h4>{{ strtoupper($patient->name) }}</h4>
        <h4>{{ $patient->medical_record_number }}</h4>
        <p>{{ $patient->place }} , {{ tgl($patient->date_of_birth) }}</p>
        <p>{{ $patient->gender }}</p>
        <p>{{ $patient->mobile_phone_number }}</p>
    </div>

</body>

</html>
