<!DOCTYPE html>
<html>

<head>
    <style>
        @page {
            size: 0.79in 7.09in;
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
            top: 400px;
            left: -80px;
        }

        body {
            width: 500px;
            height: 7.08in;
            margin: 0;
            overflow: hidden;
        }
    </style>
    <title>Kartu Pasien</title>
</head>

<body class="relative">
    <div class="container absolute">
        <strong @style('transform: scale(.7);')>
            <p>RS Livasya</p>
            <hr>
            <p>{{ strtoupper($patient->name) }} ({{ $patient->gender = 'Perempuan' ? 'P' : 'L' }})</p>
            <p>{{ $patient->medical_record_number }}</p>
            <p>{{ \Carbon\Carbon::parse($patient->date_of_birth)->format('d-m-Y') }}</p>
        </strong>
    </div>
</body>

</html>
