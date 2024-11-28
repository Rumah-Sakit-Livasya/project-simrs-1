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
                padding-left: 15px;
                z-index: 1;
            }

            .tab-content {
                margin-top: 55px;
            }
        }

        .slide-on-mobile {
            width: 20rem;
        }

        .text-decoration-underline {
            text-decoration: underline;
        }

        .text-secondary {
            font-size: 12px;
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

        /* #toggle-pasien {
            position: absolute;
            top: 10px;
            right: -60px;
            z-index: 2;
            background: #fff;
        } */

        #toggle-pasien i {
            color: #3366b9;
        }

        #js-slide-left {
            border-right: 1px solid rgba(0, 0, 0, 0.3);
            background: white;
        }

        #js-slide-left.hide {
            display: none;
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
                    <li class="nav-item mr-2">
                        <a class="btn btn-outline-primary" id="toggle-pasien" data-action="toggle"
                            data-class="slide-on-mobile-left-show" data-target="#js-slide-left">
                            <i class="ni ni-menu"></i>
                        </a>
                    </li>
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
                            <a class="dropdown-item" data-toggle="tab" href="#tab_default-2" role="tab">Pengkajian</a>
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

                {{-- content start --}}
                <div class="tab-content p-3">
                    <div class="tab-pane fade show active" id="tab_default-1" role="tabpanel">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="row">
                                    <div class="col-lg-3 d-flex align-items-center">
                                        <img src="http://192.168.1.253/real/include/avatar/man-icon.png" alt=""
                                            width="100%">
                                    </div>
                                    <div class="col-lg-9">
                                        <a href="#">
                                            <h5 class="text-danger text-decoration-underline">KIRANA HANNAH ADZKIYA
                                            </h5>
                                        </a>
                                        <p class="text-small text-secondary mb-1">13 Jun 2019 (5thn 5bln 9hr)</p>
                                        <p class="text-small text-secondary mb-1">RM 05-76-94</p>
                                        <p class="text-small text-secondary mb-1">BPJS KESEHATAN</p>
                                        <p class="text-small text-secondary mb-1">Info Billing: 30.000</p>
                                        <p class="text-small text-secondary mb-1">Tidak ada alergi</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="row d-flex align-items-center">
                                    <div class="col-lg-3 d-flex align-items-center h-100">
                                        <img src="http://192.168.1.253/real/include/avatar/woman-doctor.png"
                                            alt="" width="100%">
                                    </div>
                                    <div class="col-lg-9">
                                        <a href="#">
                                            <h5 class="text-danger text-decoration-underline">dr. Ratih Eka Pujasari Sp.A
                                            </h5>
                                        </a>
                                        <p class="text-small text-secondary mb-1">KLINIK ANAK</p>
                                        <p class="text-small text-secondary mb-1">Reg 2411220092 (22 Nov 2024)</p>
                                        <p class="text-small text-secondary mb-1">Rawat Jalan</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row my-3">
                            <div class="col-lg-12">
                                <div class="card-actionbar">
                                    <div class="card-actionbar-row-left">
                                        <button type="button"
                                            class="btn btn-outline-primary waves-effect waves-light margin-left-xl"
                                            id="panggil" onclick="panggil()"><span
                                                class="glyphicon glyphicon-music "></span>&nbsp;&nbsp;Panggil
                                            Antrian</button>
                                        <button class="btn btn-warning text-white"
                                            onclick="popupFull('http://192.168.1.253/real/antrol_bpjs/update_waktu_antrean_vclaim/2411055632','p_card', 900,600,'no'); return false;">
                                            <i class="mdi mdi-update"></i> Antrol BPJS
                                        </button>
                                        <button class="btn btn-danger waves-effect waves-light" onclick="showIcare();"><i
                                                class="mdi mdi-account-convert"></i> Bridging Icare</button>
                                        <button class="btn btn-info margin-left-md" id="popup_klpcm">
                                            <i class="mdi mdi-file" id="mdi-chk"></i> KLPCM
                                        </button>
                                        <button class="btn btn-danger"
                                            onclick="popupFull('http://192.168.1.253/real/vclaim/form_rencana_kontrol/2/197892'); return false;"><i
                                                class="mdi mdi-printer"></i> Rencana Kontrol</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tgl_masuk" class="control-label text-primary">Tanggal &amp; jam
                                        masuk</label>
                                    <div class="form-group mb-3">
                                        <div class="input-group">
                                            <input type="date" name="tgl_masuk" class="form-control "
                                                placeholder="Tanggal" id="tgl_masuk" value="">
                                            <input type="time" name="jam_masuk" class="form-control "
                                                placeholder="Jam" id="jam_masuk" value="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="tgl_masuk" class="control-label text-primary">Tanggal &amp; jam
                                        masuk</label>
                                    <div class="input-group">
                                        <input type="date" name="tgl_dilayani" class="form-control"
                                            placeholder="Tanggal" id="tgl_dilayani" value="">
                                        <input type="time" name="jam_dilayani" class="form-control" placeholder="Jam"
                                            id="jam_dilayani" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="keluhan_utama" class="control-label text-primary">Keluhan utama *</label>
                                    <textarea class="form-control" id="keluhan_utama" name="keluhan_utama" rows="3" required=""
                                        data-label="Keluhan utama"></textarea>
                                </div>
                            </div>
                        </div>
                        <header class="text-warning margin-top-lg mt-3">
                            <h4 class=" mt-5 font-weight-bold">TANDA TANDA VITAL</h4>
                        </header>
                        <div class="row mt-3">
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="text-primary" for="pr">Nadi (PR)</label>
                                    <div class="input-group">
                                        <div class="input-group">
                                            <input id="pr" type="text" name="pr" class="form-control">
                                            <div class="input-group-append">
                                                <span class="input-group-text">x/menit</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="rr" class="text-primary">Respirasi (RR)</label>
                                    <div class="input-group">
                                        <input class="form-control numeric" id="rr" name="rr"
                                            type="text">
                                        <div class="input-group-append">
                                            <span class="input-group-text">x/menit</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="bp" class="text-primary">Tensi (BP)</label>
                                    <div class="input-group">
                                        <input class="form-control numeric" id="bp" name="bp"
                                            type="text">
                                        <div class="input-group-append">
                                            <span class="input-group-text">mmHg</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="temperatur" class="text-primary">Suhu (T)</label>
                                    <div class="input-group">
                                        <input class="form-control numeric" id="temperatur" name="temperatur"
                                            type="text">
                                        <div class="input-group-append">
                                            <span class="input-group-text">C°</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="height" class="text-primary">Tinggi Badan</label>
                                    <div class="input-group">
                                        <input class="form-control numeric calc-bmi" id="body_height" name="body_height"
                                            type="text">
                                        <div class="input-group-append">
                                            <span class="input-group-text">Cm</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="weight" class="text-primary">Berat Badan</label>
                                    <div class="input-group">
                                        <input class="form-control numeric calc-bmi" id="body_weight" name="body_weight"
                                            type="text">
                                        <div class="input-group-append">
                                            <span class="input-group-text">Kg</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="bmi" class="text-primary">Index Massa Tubuh</label>
                                    <div class="input-group">
                                        <input class="form-control numeric" id="bmi" name="bmi"
                                            readonly="readonly" type="text">
                                        <div class="input-group-append">
                                            <span class="input-group-text">Kg/m²</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="kat_bmi" class="text-primary">Kategori IMT</label>
                                    <div class="input-group">
                                        <input class="form-control" id="kat_bmi" name="kat_bmi" readonly="readonly"
                                            type="text">
                                        <div class="input-group-append">
                                            <span class="input-group-text"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="sp02" class="text-primary">SP 02</label>
                                    <div class="input-group">
                                        <input class="form-control" id="sp02" name="sp02" type="text">
                                        <div class="input-group-append">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="lingkar_kepala" class="text-primary">Lingkar Kepala</label>
                                    <div class="input-group">
                                        <input class="form-control" id="lingkar_kepala" name="lingkar_kepala"
                                            type="text">
                                        <div class="input-group-append">
                                            <span class="input-group-text">Cm</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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

            $('#toggle-pasien').on('click', function() {
                var target = $('#js-slide-left'); // Mengambil elemen target berdasarkan data-target
                var backdrop = $('.slide-backdrop'); // Mengambil backdrop

                // Toggle kelas untuk menampilkan atau menyembunyikan panel dan backdrop
                target.toggleClass('hide');
                backdrop.toggleClass('show');
            });

            // Close the panel if the backdrop is clicked
            $('.slide-backdrop').on('click', function() {
                $('#js-slide-left').removeClass('slide-on-mobile-left-show');
                $(this).removeClass('show');
            });
        });
    </script>
@endsection
