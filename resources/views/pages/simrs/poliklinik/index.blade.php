@extends('inc.layout')
@section('tmp_body', 'layout-composed')
@section('extended-css')
    <style>
        input[type="time"] {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }

        .badge {
            cursor: pointer;
        }

        .badge.badge-orange {
            background-color: #ff5722;
            color: #ffffff;
        }

        .badge.badge-red {
            background-color: #f44336;
            color: #ffffff;
        }

        @media (max-width: 768px) {
            .img-baker {
                width: 45%;
                margin-bottom: 1rem;
            }
        }


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
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="diagnosa-keperawatan" class="control-label text-primary">Diagnosa
                                        Keperawatan</label>
                                    <select name="diagnosa_keperawatan" id="diagnosa-keperawatan"
                                        class="select2 form-select">
                                        <option value="-">-</option>
                                        <option value="Gangguan rasa nyaman">Gangguan rasa nyaman</option>`
                                        <option value="Nyeri">Nyeri</option>
                                        <option value="Pola Nafas tidak efektif">Pola Nafas tidak efektif</option>
                                        <option value="Bersihan jalan nafas tidak efektif">Bersihan jalan nafas tidak
                                            efektif
                                        </option>
                                        <option value="Nyeri Akut">Nyeri Akut</option>
                                        <option value="Nyeri Kronis">Nyeri Kronis</option>
                                        <option value="Resiko Infeksi">Resiko Infeksi</option>
                                        <option value="Harga diri Rendah">Harga diri Rendah</option>
                                        <option value="Resiko Perilaku Kekerasan">Resiko Perilaku Kekerasan</option>
                                        <option value="Halusinasi">Halusinasi</option>
                                        <option value="Isolasi Sosial">Isolasi Sosial</option>
                                        <option value="Resiko Bunuh Diri">Resiko Bunuh Diri</option>
                                        <option value="Waham">Waham</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="rencana-tindak-lanjut" class="control-label text-primary">Rencana Tindak
                                        Lanjut</label>
                                    <select name="rencana_tindak_lanjut" id="rencana-tindak-lanjut"
                                        class="select2 form-select">
                                        <option value="-">-</option>
                                        <option value="Kolaborasi Dokter">Kolaborasi Dokter</option>
                                        <option value="Perawatan Luka">Perawatan Luka</option>
                                        <option value="Memberikan Edukasi">Memberikan Edukasi</option>
                                        <option value="Mengukur tanda - tanda vital">Mengukur tanda - tanda vital</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <header class="text-danger mt-3">
                            <h4 class="mt-5 font-weight-bold">ALERGI DAN REAKSI</h4>
                        </header>
                        <div class="row mt-3">
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="alergi_obat"
                                        class="control-label text-primary margin-tb-10 d-block">Alergi
                                        Obat</label>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" class="custom-control-input" value="Ya"
                                            name="alergi_obat" id="alergi_obat1">
                                        <label class="custom-control-label text-primary" for="alergi_obat1">Ya</label>
                                    </div>
                                    <input name="ket_alergi_obat" id="ket_alergi_obat"
                                        style="margin-right: 10px; width: 200px;border-left: none;border-right: none;border-top: none;border-bottom-color: rgba(12, 12, 12, 0.12);"
                                        type="text">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" class="custom-control-input" value="Tidak"
                                            name="alergi_obat" id="alergi_obat2">
                                        <label class="custom-control-label text-primary" for="alergi_obat2">Tidak</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="alergi_makanan"
                                        class="control-label text-primary margin-tb-10 d-block">Alergi
                                        Makanan</label>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" class="custom-control-input" value="Ya"
                                            name="alergi_makanan" id="alergi_makanan1">
                                        <label class="custom-control-label text-primary" for="alergi_makanan1">Ya</label>
                                    </div>
                                    <input name="ket_alergi_makanan" id="ket_alergi_makanan"
                                        style="margin-right: 10px; width: 200px;border-left: none;border-right: none;border-top: none;border-bottom-color: rgba(12, 12, 12, 0.12);"
                                        type="text">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" class="custom-control-input" value="Tidak"
                                            name="alergi_makanan" id="alergi_makanan2">
                                        <label class="custom-control-label text-primary"
                                            for="alergi_makanan2">Tidak</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="alergi_makanan"
                                        class="control-label text-primary margin-tb-10 d-block">Alergi
                                        Makanan</label>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" class="custom-control-input" value="Ya"
                                            name="alergi_makanan" id="alergi_makanan1">
                                        <label class="custom-control-label text-primary" for="alergi_makanan1">Ya</label>
                                    </div>
                                    <input name="ket_alergi_makanan" id="ket_alergi_makanan"
                                        style="margin-right: 10px; width: 200px;border-left: none;border-right: none;border-top: none;border-bottom-color: rgba(12, 12, 12, 0.12);"
                                        type="text">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" class="custom-control-input" value="Tidak"
                                            name="alergi_makanan" id="alergi_makanan2">
                                        <label class="custom-control-label text-primary"
                                            for="alergi_makanan2">Tidak</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group mb-3">
                                    <label for="reaksi_alergi_obat" class="control-label text-primary ">Reaksi terhadap
                                        alergi
                                        obat</label>
                                    <input name="reaksi_alergi_obat" id="reaksi_alergi_obat" class="form-control alergi"
                                        type="text">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="reaksi_alergi_makanan" class="control-label text-primary">Reaksi terhadap
                                        alergi
                                        makanan</label>
                                    <input name="reaksi_alergi_makanan" id="reaksi_alergi_makanan"
                                        class="form-control alergi" type="text">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="reaksi_alergi_lainnya" class="control-label text-primary">Reaksi terhadap
                                        alergi
                                        lainnya</label>
                                    <input name="reaksi_alergi_lainnya" id="reaksi_alergi_lainnya"
                                        class="form-control alergi" type="text">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="kondisi_khusus1" class="control-label text-primary margin-tb-10">Gelang
                                        tanda
                                        alergi</label>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" value="1"
                                            name="gelang" id="gelang1">
                                        <label class="custom-control-label text-primary" for="gelang1">Dipasang (warna
                                            merah)</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <header class="text-danger">
                            <h4 class="mt-5 font-weight-bold">SKRINING NYERI</h4>
                        </header>
                        <div class="row mt-3">
                            <div class="col-md-12 mb-4 d-flex flex-wrap justify-content-between">
                                <div class="img-baker d-flex flex-column align-items-center" style="width: 14%;">
                                    <img src="http://103.191.196.126:8888/testing/include/images/wongbaker/1.jpg"
                                        class="mb-2 img-fluid">
                                    <div class="text-center">
                                        <span class="badge badge-warning text-white" data-skor="0">0</span>
                                    </div>
                                </div>
                                <div class="img-baker d-flex flex-column align-items-center" style="width: 14%;">
                                    <img src="http://103.191.196.126:8888/testing/include/images/wongbaker/2.jpg"
                                        class="mb-2 img-fluid">
                                    <div class="text-center">
                                        <span class="badge badge-success" data-skor="1">1</span>
                                        <span class="badge badge-success" data-skor="2">2</span>
                                    </div>
                                </div>
                                <div class="img-baker d-flex flex-column align-items-center" style="width: 14%;">
                                    <img src="http://103.191.196.126:8888/testing/include/images/wongbaker/3.jpg"
                                        class="mb-2 img-fluid">
                                    <div class="text-center">
                                        <span class="badge badge-primary" data-skor="3">3</span>
                                        <span class="badge badge-primary" data-skor="4">4</span>
                                    </div>
                                </div>
                                <div class="img-baker d-flex flex-column align-items-center" style="width: 14%;">
                                    <img src="http://103.191.196.126:8888/testing/include/images/wongbaker/4.jpg"
                                        class="mb-2 img-fluid">
                                    <div class="text-center">
                                        <span class="badge badge-info" data-skor="5">5</span>
                                        <span class="badge badge-info" data-skor="6">6</span>
                                    </div>
                                </div>
                                <div class="img-baker d-flex flex-column align-items-center" style="width: 14%;">
                                    <img src="http://103.191.196.126:8888/testing/include/images/wongbaker/5.jpg"
                                        class="mb-2 img-fluid">
                                    <div class="text-center">
                                        <span class="badge badge-orange" data-skor="7">7</span>
                                        <span class="badge badge-orange" data-skor="8">8</span>
                                    </div>
                                </div>
                                <div class="img-baker d-flex flex-column align-items-center" style="width: 14%;">
                                    <img src="http://103.191.196.126:8888/testing/include/images/wongbaker/6.jpg"
                                        class="mb-2 img-fluid">
                                    <div class="text-center">
                                        <span class="badge badge-red" data-skor="9">9</span>
                                        <span class="badge badge-red" data-skor="10">10</span>
                                    </div>
                                </div>
                                <div class="img-baker d-flex flex-column align-items-center" style="width: 14%;">
                                    <input name="skor_nyeri" id="skor_nyeri" class="form-control text-center mt-3"
                                        style="font-size: 3rem; height: 60px;" type="text">
                                    <label for="skor_nyeri" class="control-label text-primary">Skor</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="provokatif" class="control-label text-primary">Provokatif</label>
                                    <input name="provokatif" id="provokatif" class="form-control" type="text">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="quality" class="control-label text-primary">Quality</label>
                                    <input name="quality" id="quality" class="form-control" type="text">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="region" class="control-label text-primary">Region</label>
                                    <input name="region" id="region" class="form-control" type="text">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="time" class="control-label text-primary">Time</label>
                                    <input name="time" id="time" class="form-control" type="text">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="nyeri" class="control-label text-primary">Nyeri</label>
                                    <select name="nyeri" id="nyeri" class="select2">
                                        <option value="-">-</option>
                                        <option value="Nyeri kronis">Nyeri kronis</option>
                                        <option value="Nyeri akut">Nyeri akut</option>
                                        <option value="TIdak ada nyeri">TIdak ada nyeri</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="nyeri_hilang" class="control-label text-primary">Nyeri hilang apabila</label>
                                    <input name="nyeri_hilang" id="nyeri_hilang" class="form-control" type="text">
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
            $('.select2').select2({
                placeholder: 'Pilih Item',
            });
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
