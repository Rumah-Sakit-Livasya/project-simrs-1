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
            margin-top: -30px;
        }

        .card {
            cursor: pointer;
            transition: transform 0.3s ease;
            background: #fff;
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .card-header {
            background: linear-gradient(135deg, rgb(6 0 255), rgb(255 118 0));
            padding: 12px;
            color: #ffffff;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-header .fa {
            margin-right: 10px;
        }

        .card-body {
            padding: 1rem;
            background-color: #ffffff;
            text-align: center;
        }

        .card-body p {
            margin: 0;
        }

        #logo img {
            width: 100px;
            height: 100px;
        }

        #logo h1 {
            margin: 0;
            font-size: 2.07rem;
        }

        /* Responsive font sizes */
        @media (max-width: 1200px) {
            .card-header h3 {
                font-size: 1.3rem;
            }

            .card-body {
                font-size: 0.9rem;
            }

            #logo h1 {
                font-size: 1.8rem;
            }

            #logo p {
                font-size: 1rem;
            }
        }

        @media (max-width: 992px) {
            .card-header h3 {
                font-size: 1.2rem;
            }

            .card-body {
                font-size: 0.8rem;
            }

            #logo h1 {
                font-size: 1.6rem;
            }

            #logo p {
                font-size: 0.9rem;
            }
        }

        @media (max-width: 768px) {
            .card-header h3 {
                font-size: 1.1rem;
            }

            .card-body {
                font-size: 0.7rem;
            }

            #logo h1 {
                font-size: 1.4rem;
            }

            #logo p {
                font-size: 0.8rem;
            }
        }

        @media (max-width: 576px) {
            .card-header h3 {
                font-size: 1rem;
            }

            .card-body {
                font-size: 0.6rem;
            }

            #logo h1 {
                font-size: 1.5rem;
            }

            #logo p {
                font-size: 0.7rem;
            }
        }

        /* Hide original cards on mobile and display mobile cards */
        .original-cards {
            display: block;
        }

        .mobile-cards {
            display: none;
        }

        .mobile-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            /* background: rgb(25 20 102 / 64%); */
            background: rgb(6 0 102 / 64%);
            /* background: linear-gradient(135deg, rgb(6 0 255), rgb(255 118 0)); */
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 1rem;
            margin: 0.5rem;
            text-align: center;
            height: 130px;
            /* Fixed height */
            width: 130px;
            /* Fixed width */
            box-sizing: border-box;
            /* Ensure padding is included in width */
        }

        .mobile-cards form {
            display: flex !important;
            justify-content: center !important;
        }

        .mobile-card i {
            font-size: 3rem;
            margin-bottom: 0.5rem;
            position: relative;
            background: linear-gradient(90deg, rgb(180 178 255), rgb(255, 118, 0));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        /* .mobile-card i {
            font-size: 3rem;
            margin-bottom: 0.5rem;
            color: blue;
        } */

        .mobile-card h3 {
            font-size: 1.2rem;
            margin: 0;
            font-weight: bold;
            margin-top: 5px;
            /* color: rgb(255, 136, 0); */
            color: #ffffff;
        }

        /* Responsive design adjustments */
        @media (max-width: 992px) {
            .original-cards {
                display: none;
            }

            .mobile-cards {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
            }
        }
    </style>
</head>

<body>
    <div class="overlay"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12 d-flex justify-content-center mb-3">
                <div id="logo">
                    <center>
                        <img src="{{ asset('img/logo.png') }}" alt="Logo RS">
                        <h1 class="font-weight-bold text-white mb-0 mt-2">RUMAH SAKIT LIVASYA</h1>
                        <p class="text-white mb-1">Melayani Sepenuh Hati Kepuasan Anda Adalah Prioritas Kami</p>
                    </center>
                </div>
            </div>
            <!-- Original Cards -->
            <div class="col-md-6 my-1 original-cards">
                <form action="{{ route('set-app') }}" method="POST">
                    @csrf
                    <input type="hidden" name="app_type" value="hr">
                    <div class="card" onclick="this.closest('form').submit()">
                        <div class="card-header d-flex align-items-center">
                            <i class="fas fa-users fa-2x mr-2"></i>
                            <h3 class="mb-0">SMART HR</h3>
                        </div>
                        <div class="card-body">
                            <p>Manage your company's HR-related activities.</p>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-6 my-1 original-cards">
                <form action="{{ route('set-app') }}" method="POST">
                    @csrf
                    <input type="hidden" name="app_type" value="simrs">
                    <div class="card" onclick="this.closest('form').submit()">
                        <div class="card-header d-flex align-items-center">
                            <i class="fas fa-notes-medical fa-2x mr-2"></i>
                            <h3 class="mb-0">SIMRS</h3>
                        </div>
                        <div class="card-body">
                            <p>Manage your hospital's information system.</p>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Mobile Cards -->
        <div class="row mobile-cards">
            <div class="col-6">
                <form action="{{ route('set-app') }}" method="POST">
                    @csrf
                    <input type="hidden" name="app_type" value="hr">
                    <div class="mobile-card" onclick="this.closest('form').submit()">
                        <i class="fas fa-users"></i>
                        <h3 class="mb-0">SMART HR</h3>
                    </div>
                </form>
            </div>
            <div class="col-6">
                <form action="{{ route('set-app') }}" method="POST">
                    @csrf
                    <input type="hidden" name="app_type" value="simrs">
                    <div class="mobile-card" onclick="this.closest('form').submit()">
                        <i class="fas fa-notes-medical"></i>
                        <h3 class="mb-0">SIMRS</h3>
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
