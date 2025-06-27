<!DOCTYPE html>
<html>

<head>
    <style>
        @page {
            size: 237px 203px;
            margin: 0;
        }

        * {
            font-family: sans-serif;
            margin: 0;
        }

        .relative {
            /* position: relative; */
        }

        .absolute {
            /* position: absolute; */
        }

        .container {
            /* top: 80px;
            left: -15px; */
        }

        body {
            width: 237px;
            height: 203px;
            margin: 0;
            overflow: hidden;
        }
    </style>
    <title>Label Order</title>
</head>

<body class="relative">
    <div class="container absolute">
        <h4>{{ strtoupper($order->nama_pemesan) }}</h4>
        @if ($order->untuk == 'pasien')
            <h4>{{ $order->registration->patient->date_of_birth }} /
                {{ $order->registration->patient->medical_record_number }}
                ({{ strtoupper(substr($order->registration->patient->gender, 0, 1)) }})</h4>
        @endif
        <p>{{ $order->registration->kelas_rawat->kelas }} / {{ $order->registration->patient->bed->room->ruangan }}</p>
        <p>{{ \Carbon\Carbon::parse($order->tanggal_order)->format('d-m-Y') }}</p>
        @if ($order->waktu_makan)
            <p>Makan {{ $order->waktu_makan }}</p>
        @endif
        <p>{{ $order->category->nama }}</p>
        <p>
            @foreach ($order->foods as $food)
                {{-- if there's more after this, add coma --}}
                {{ $food->food->nama }}@if (!$loop->last)
                    ,
                @endif
            @endforeach
        </p>
    </div>
    <script type="text/javascript">
        try {
            this.print();
        } catch (e) {
            window.onload = window.print;
        }
    </script>
</body>




</html>
