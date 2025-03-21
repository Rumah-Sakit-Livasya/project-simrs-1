<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Internal Livasya</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- base css -->
    <link rel="stylesheet" href="{{ asset('css/vendors.bundle.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.bundle.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/logo.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/logo.png') }}">
    <link rel="mask-icon" href="{{ asset('img/logo.png') }}" color="#5bbad5">
    <!-- Custom CSS -->
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

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(0, 123, 255, 0.7), rgb(255 121 0 / 70%));
            z-index: 1;
        }

        .container {
            position: relative;
            z-index: 2;
        }

        .card {
            cursor: pointer;
            transition: transform 0.3s ease;
            background: #fff;
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            padding: 1rem;
            margin: 0.5rem;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .card i {
            font-size: 3rem;
            margin-bottom: 0.5rem;
            color: #007bff;
        }

        .card h3 {
            font-size: 1.2rem;
            margin: 0;
            font-weight: bold;
            color: #333;
        }

        /* Responsive adjustments */
        @media (max-width: 992px) {
            .card i {
                font-size: 2.5rem;
            }

            .card h3 {
                font-size: 1rem;
            }
        }

        @media (max-width: 768px) {
            .card i {
                font-size: 2rem;
            }

            .card h3 {
                font-size: 0.9rem;
            }
        }

        @media (max-width: 576px) {
            .card i {
                font-size: 1.8rem;
            }

            .card h3 {
                font-size: 0.8rem;
            }
        }
    </style>
</head>

<body>
    <div class="overlay"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center mb-3">
                <div id="logo">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo RS" class="img-fluid mb-2"
                        style="max-width: 150px; height: auto;">
                    <h1 class="font-weight-bold text-white mb-0">RUMAH SAKIT LIVASYA</h1>
                    <p class="text-white mb-0">Melayani Sepenuh Hati Kepuasan Anda Adalah Prioritas Kami</p>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-6 col-lg-4">
                <form action="{{ route('set-app') }}" method="POST">
                    @csrf
                    <input type="hidden" name="app_type" value="hr">
                    <div class="card" onclick="this.closest('form').submit()">
                        <i class="fas fa-users"></i>
                        <h3>SMART HR</h3>
                    </div>
                </form>
            </div>
            <div class="col-6 col-lg-4">
                <form action="{{ route('set-app') }}" method="POST">
                    @csrf
                    <input type="hidden" name="app_type" value="simrs">
                    <div class="card" onclick="this.closest('form').submit()">
                        <i class="fas fa-notes-medical"></i>
                        <h3>SIMRS</h3>
                    </div>
                </form>
            </div>
            <div class="col-6 col-lg-4">
                <form action="{{ route('set-app') }}" method="POST">
                    @csrf
                    <input type="hidden" name="app_type" value="inventaris">
                    <div class="card" onclick="this.closest('form').submit()">
                        <i class="fas fa-boxes"></i>
                        <h3>Inventaris</h3>
                    </div>
                </form>
            </div>
            <div class="col-6 col-lg-4">
                <form action="{{ route('set-app') }}" method="POST">
                    @csrf
                    <input type="hidden" name="app_type" value="kepustakaan">
                    <div class="card" onclick="this.closest('form').submit()">
                        <i class="fas fa-folder"></i>
                        <h3>Kepustakaan</h3>
                    </div>
                </form>
            </div>
            <div class="col-6 col-lg-4">
                <form action="{{ route('set-app') }}" method="POST">
                    @csrf
                    <input type="hidden" name="app_type" value="keuangan">
                    <div class="card" onclick="this.closest('form').submit()">
                        <i class="fas fa-money-bill-alt"></i>
                        <h3>Keuangan</h3>
                    </div>
                </form>
            </div>
            <div class="col-6 col-lg-4">
                <form action="{{ route('set-app') }}" method="POST">
                    @csrf
                    <input type="hidden" name="app_type" value="mutu">
                    <div class="card" onclick="this.closest('form').submit()">
                        <i class="fas fa-chart-line"></i>
                        <h3>Mutu</h3>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- base vendor bundle -->
    <script src="{{ asset('js/vendors.bundle.js') }}"></script>
    <script src="{{ asset('js/app.bundle.js') }}"></script>
</body>

</html>
