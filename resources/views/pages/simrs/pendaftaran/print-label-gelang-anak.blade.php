<!DOCTYPE html>
<html>

<head>
    <style>
        @page {
            size: 0.78in 7.08in;
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
        <p>RS Livasya</p>
        <hr>
        <p>{{ strtoupper($patient->name) }}</p>
    </div>
</body>

</html>
