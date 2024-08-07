<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/logo.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/logo.png') }}">

    <title>SMART HR - GUEST LOGIN/REGISTER</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: url('{{ asset('img/rs.jpg') }}') no-repeat center center fixed;
            background-size: cover;
        }

        /* Ensure labels and text inputs are white */
        .text-white {
            color: #ffffff;
        }

        /* Ensure the background and text contrast properly */
        .form-container {
            background-color: rgba(43, 43, 43, 0.66);
            /* Dark background with opacity */
            color: #ffffff;
            /* Ensure text is white */
        }

        .input-label {
            color: #ffffff;
            /* White text for labels */
        }

        .dark .input-label,
        .dark .text-white {
            color: #e0e0e0;
            /* Light text color for dark mode */
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(0, 123, 255, 0.8), rgb(255 121 0 / 80%));
            z-index: 1;
        }

        .container {
            position: relative;
            z-index: 2;
            width: 95%;
            max-width: 420px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: transparent;
            border-radius: 10px;
            border: 3px solid #ffffff;
            box-shadow: transparent;
        }

        .logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo img {
            display: block;
            margin: 0 auto;
        }

        .logo p {
            margin: 0;
            font-size: 2rem;
            line-height: 2.5rem;
            color: white;
        }

        @media (min-width: 640px) {
            .logo p {
                font-size: 2rem;
                line-height: 3rem;
            }
        }

        @media (min-width: 768px) {
            .logo p {
                font-size: 2.8rem;
                line-height: 3rem;
            }
        }

        @media (min-width: 1024px) {
            .logo p {
                font-size: 2.8rem;
                line-height: 3rem;
            }
        }

        .form-container {
            width: 100%;
            max-width: 100%;
            background-color: rgb(43 43 43 / 66%);
            border-radius: 10px;
            padding: 20px;
        }
    </style>
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="overlay"></div>
    <div class="container">
        <div class="logo">
            <img src="{{ asset('img/logo.png') }}" alt="Logo RS" width="100" height="100" class="mb-2">
            <p>RUMAH SAKIT<br>LIVASYA</p>
        </div>

        <div class="form-container">
            {{ $slot }}
        </div>
    </div>
</body>

</html>
