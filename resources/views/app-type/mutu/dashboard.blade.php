@extends('inc.layout')
@section('title', 'Logistik - Dashboard')
@section('extended-css')
    <style>
        .gradient-text {
            font-size: 1.5rem;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
            background: linear-gradient(135deg, rgba(0, 123, 255, 1), rgb(255 121 0 / 100%));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            display: block;
        }

        .spaced-text {
            letter-spacing: 0.4em;
            font-weight: bold;
            background: linear-gradient(135deg, rgba(0, 123, 255, 1), rgb(255 121 0 / 100%));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            display: block;
        }

        .logo-dashboard-simrs {
            width: 100%;
        }
    </style>
@endsection

@section('content')
    <main id="js-page-content" role="main" class="page-content">

        <div class="row" style="height: 90%">
            <div class="col-lg-12 d-flex align-items-center justify-content-center">
                <div class="logo-dashboard-simrs text-center">
                    <h3 class="text-center spaced-text gradient-text">COMING SOON</h3>
                    <img src="{{ asset('img/logo.png') }}" width="130" height="130" alt="Logo RS">
                    <h3 class="text-center spaced-text mt-3">RUMAH SAKIT LIVASYA</h3>
                    <p style="letter-spacing: 0.2em">Jl. Raya Timur III Dawuan No. 875 Kab. Majalengka Telp 081211151300</p>
                </div>
            </div>
        </div>
    </main>
    @include('pages.partials.show')
@endsection
