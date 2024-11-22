@extends('inc.layout')
@section('tmp_body', 'layout-composed')
@section('extended-css')
    <style>
        @media (min-width: 992px) {
            .nav-function-hidden:not(.nav-function-top) .page-sidebar:hover {
                left: -16.25rem;
                -webkit-transition: 450ms cubic-bezier(0.9, 0.01, 0.09, 1);
                transition: 450ms cubic-bezier(0.9, 0.01, 0.09, 1);
            }

            .nav.nav-tabs.action-erm {
                position: fixed;
                background: #ffffff;
                width: 100%;
                padding-top: 10px;
                padding-bottom: 10px;
                padding-left: 75px;
            }

            .tab-content {
                margin-top: 55px;
            }
        }

        .slide-on-mobile {
            width: 20rem;
        }

        @media only screen and (max-width: 992px) {
            .slide-on-mobile-left {
                border-right: 1px solid rgba(0, 0, 0, 0.09);
                left: 0;
            }

            .slide-on-mobile {
                width: 17rem;
            }
        }

        #toggle-pasien {
            position: absolute;
            top: 10px;
            right: -60px;
            z-index: 2;
            background: #fff;
        }

        #toggle-pasien i {
            color: #3366b9;
        }

        #js-slide-left {
            border-right: 1px solid rgba(0, 0, 0, 0.3);
            background: white;
        }

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
        <!-- notice the utilities added to the wrapper below -->
        <div class="d-flex flex-grow-1 p-0 shadow-1 layout-composed">
            <!-- left slider panel : must have unique ID-->
            <div id="js-slide-left"
                class="flex-wrap flex-shrink-0 position-relative slide-on-mobile slide-on-mobile-left bg-primary-200 pattern-0 p-3">
                <a href="javascript:void(0);" class="btn btn-outline-primary" id="toggle-pasien" data-action="toggle"
                    data-class="slide-on-mobile-left-show" data-target="#js-slide-left">
                    <i class="ni ni-menu"></i>
                </a>
                <form action="javascript:void(0)" method="POST">
                    @csrf
                    <div class="form-group mb-2">
                        <select class="select2 form-control @error('departement_id') is-invalid @enderror"
                            name="departement_id" id="departement_id">
                            <option value=""></option>
                            @foreach ($departements as $departement)
                                <option value="{{ $departement->id }}">{{ $departement->name }}</option>
                            @endforeach
                        </select>
                        @error('departement_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-2">
                        <select class="select2 form-control @error('doctor_id') is-invalid @enderror" name="doctor_id"
                            id="doctor_id">
                            <option value=""></option>
                            @foreach ($jadwal_dokter as $jadwal)
                                <option value="{{ $jadwal->doctor_id }}">{{ $jadwal->doctor->employee->fullname }}</option>
                            @endforeach
                        </select>
                        @error('doctor_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-2">
                        <input type="text" id="nama_pasien" name="nama_pasien" class="form-control"
                            placeholder="Nama Pasien">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary w-100">Submit</button>
                    </div>
                </form>
            </div>
            <!-- left slider panel backdrop : activated on mobile, must be place immideately after left slider closing tag -->
            <div class="slide-backdrop" data-action="toggle" data-class="slide-on-mobile-left-show"
                data-target="#js-slide-left"></div>
            <!-- middle content area -->
            <div class="d-flex flex-column flex-grow-1 bg-white">

                {{-- <div class="row" style="height: 90%">
                    <div class="col-lg-12 d-flex align-items-center justify-content-center">
                        <div class="logo-dashboard-simrs text-center">
                            <h3 class="text-center spaced-text gradient-text">MODUL POLIKLINIK</h3>
                            <img src="{{ asset('img/logo.png') }}" width="130" height="130" alt="Logo RS">
                            <h3 class="text-center spaced-text mt-3">RUMAH SAKIT LIVASYA</h3>
                            <p style="letter-spacing: 0.2em">Jl. Raya Timur III Dawuan No. 875 Kab. Majalengka Telp
                                081211151300</p>
                        </div>
                    </div>
                </div> --}}

                <ul class="nav nav-tabs action-erm" role="tablist">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                            aria-haspopup="true" aria-expanded="false">Perawat</a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" data-toggle="tab" href="#tab_default-2" role="tab">Pengkajian</a>
                            <a class="dropdown-item" href="#">CPPT</a>
                            <a class="dropdown-item" href="#">Transfer Pasien Antar Ruangan</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                            aria-haspopup="true" aria-expanded="false">Dokter</a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" data-toggle="tab" href="#tab_default-2" role="tab">Pengkajian</a>
                            <a class="dropdown-item" href="#">CPPT</a>
                            <a class="dropdown-item" href="#">Transfer Pasien Antar Ruangan</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                            aria-haspopup="true" aria-expanded="false">Gizi</a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" data-toggle="tab" href="#tab_default-2" role="tab">Pengkajian</a>
                            <a class="dropdown-item" href="#">CPPT</a>
                            <a class="dropdown-item" href="#">Transfer Pasien Antar Ruangan</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                            aria-haspopup="true" aria-expanded="false">Farmasi Klinis</a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" data-toggle="tab" href="#tab_default-2"
                                role="tab">Pengkajian</a>
                            <a class="dropdown-item" href="#">CPPT</a>
                            <a class="dropdown-item" href="#">Transfer Pasien Antar Ruangan</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                            aria-haspopup="true" aria-expanded="false">Pengkajian Lanjutan</a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" data-toggle="tab" href="#tab_default-2"
                                role="tab">Pengkajian</a>
                            <a class="dropdown-item" href="#">CPPT</a>
                            <a class="dropdown-item" href="#">Transfer Pasien Antar Ruangan</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                            aria-haspopup="true" aria-expanded="false">Layanan</a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" data-toggle="tab" href="#tab_default-2"
                                role="tab">Pengkajian</a>
                            <a class="dropdown-item" href="#">CPPT</a>
                            <a class="dropdown-item" href="#">Transfer Pasien Antar Ruangan</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                            aria-haspopup="true" aria-expanded="false">Lain-lain</a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" data-toggle="tab" href="#tab_default-2"
                                role="tab">Pengkajian</a>
                            <a class="dropdown-item" href="#">CPPT</a>
                            <a class="dropdown-item" href="#">Transfer Pasien Antar Ruangan</a>
                        </div>
                    </li>
                </ul>
                <div class="tab-content p-3">
                    <div class="tab-pane fade show active" id="tab_default-1" role="tabpanel">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="row">
                                    <div class="col-lg-3 d-flex align-items-center">
                                        <img src="http://192.168.1.253/real/include/avatar/man-icon.png" alt="" width="100%">
                                    </div>
                                    <div class="col-lg-9">
                                        <h5>KIRANA HANNAH ADZKIYA</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="tab_default-2" role="tabpanel">
                        Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid.
                        Exercitation +1 labore velit, blog sartorial PBR leggings next level wes anderson
                        artisan four loko farm-to-table craft beer twee. Qui photo booth letterpress, commodo
                        enim craft beer mlkshk aliquip jean shorts ullamco ad vinyl cillum PBR. Homo nostrud
                        organic.
                    </div>
                    <div class="tab-pane fade" id="tab_default-3" role="tabpanel">
                        Etsy mixtape wayfarers, ethical wes anderson tofu before they sold out mcsweeney's
                        organic lomo retro fanny pack lo-fi farm-to-table readymade. Messenger bag gentrify
                        pitchfork tattooed craft beer, iphone skateboard locavore carles etsy salvia banksy
                        hoodie helvetica. DIY synth PBR banksy irony. Leggings gentrify squid 8-bit cred
                        pitchfork.
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@section('plugin')
    <script script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(document).ready(function() {
            $('body').addClass('layout-composed');
            $('#departement_id').select2({
                placeholder: 'Pilih Klinik',
            });
            $('#doctor_id').select2({
                placeholder: 'Pilih Dokter',
            });
        });
    </script>
@endsection
