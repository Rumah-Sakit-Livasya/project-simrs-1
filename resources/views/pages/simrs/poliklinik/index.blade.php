@extends('inc.layout')
@section('tmp_body', 'layout-composed')
@section('extended-css')
    @include('pages.simrs.poliklinik.partials.css-sidebar-custom')
    <style>
        main {
            overflow-x: hidden;
        }

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
            @include('pages.simrs.poliklinik.partials.filter-poli')

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

                @include('pages.simrs.poliklinik.partials.menu-erm')

                {{-- content start --}}

                @if (isset($registration) || $registration != null)
                    <div class="tab-content p-3">
                        <div class="tab-pane fade show active" id="tab_default-1" role="tabpanel">
                            <form action="javascript:void(0)" id="pengkajian_perawat_form" method="POST">
                                @csrf
                                @method('POST')
                                @include('pages.simrs.poliklinik.partials.detail-pasien')
                                <hr style="border-color: #868686; margin-bottom: 50px;">
                                <header class="text-primary text-center mt-5">
                                    <h2 class="font-weight-bold mt-5">PENGKAJIAN PERAWAT</h2>
                                </header>
                                <div class="row mt-5">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tgl_masuk" class="control-label text-primary">Tanggal &amp; jam
                                                masuk</label>
                                            <div class="form-group mb-3">
                                                <div class="input-group">
                                                    <input type="date" name="tgl_masuk" class="form-control "
                                                        placeholder="Tanggal" id="tgl_masuk"
                                                        value="{{ $pengkajian?->tgl_masuk?->format('Y-m-d') ?? now()->format('Y-m-d') }}">
                                                    <input type="time" name="jam_masuk" class="form-control "
                                                        placeholder="Jam" id="jam_masuk" value="{{ $pengkajian?->jam_masuk ?? '' }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="tgl_masuk" class="control-label text-primary">Tanggal &amp; jam
                                                dilayani</label>
                                            <div class="input-group">
                                                <input type="date" name="tgl_dilayani" class="form-control"
                                                    placeholder="Tanggal" id="tgl_dilayani" value="">
                                                <input type="time" name="jam_dilayani" class="form-control"
                                                    placeholder="Jam" id="jam_dilayani" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="keluhan_utama" class="control-label text-primary">Keluhan utama
                                                *</label>
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
                                                    <input id="pr" type="text" name="pr"
                                                        class="form-control">
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
                                                <input class="form-control numeric calc-bmi" id="body_height"
                                                    name="body_height" type="text">
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
                                                <input class="form-control numeric calc-bmi" id="body_weight"
                                                    name="body_weight" type="text">
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
                                                <input class="form-control" id="kat_bmi" name="kat_bmi"
                                                    readonly="readonly" type="text">
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
                                                <input class="form-control" id="sp02" name="sp02"
                                                    type="text">
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
                                                <option value="Bersihan jalan nafas tidak efektif">Bersihan jalan nafas
                                                    tidak
                                                    efektif
                                                </option>
                                                <option value="Nyeri Akut">Nyeri Akut</option>
                                                <option value="Nyeri Kronis">Nyeri Kronis</option>
                                                <option value="Resiko Infeksi">Resiko Infeksi</option>
                                                <option value="Harga diri Rendah">Harga diri Rendah</option>
                                                <option value="Resiko Perilaku Kekerasan">Resiko Perilaku Kekerasan
                                                </option>
                                                <option value="Halusinasi">Halusinasi</option>
                                                <option value="Isolasi Sosial">Isolasi Sosial</option>
                                                <option value="Resiko Bunuh Diri">Resiko Bunuh Diri</option>
                                                <option value="Waham">Waham</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="rencana-tindak-lanjut" class="control-label text-primary">Rencana
                                                Tindak
                                                Lanjut</label>
                                            <select name="rencana_tindak_lanjut" id="rencana-tindak-lanjut"
                                                class="select2 form-select">
                                                <option value="-">-</option>
                                                <option value="Kolaborasi Dokter">Kolaborasi Dokter</option>
                                                <option value="Perawatan Luka">Perawatan Luka</option>
                                                <option value="Memberikan Edukasi">Memberikan Edukasi</option>
                                                <option value="Mengukur tanda - tanda vital">Mengukur tanda - tanda vital
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <header class="text-secondary mt-3">
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
                                                <label class="custom-control-label text-primary"
                                                    for="alergi_obat1">Ya</label>
                                            </div>
                                            <input name="ket_alergi_obat" id="ket_alergi_obat"
                                                style="margin-right: 10px; width: 200px;border-left: none;border-right: none;border-top: none;border-bottom-color: rgba(12, 12, 12, 0.12);"
                                                type="text">
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" value="Tidak"
                                                    name="alergi_obat" id="alergi_obat2">
                                                <label class="custom-control-label text-primary"
                                                    for="alergi_obat2">Tidak</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="alergi_makanan"
                                                class="control-label text-primary margin-tb-10 d-block">Alergi
                                                Makanan</label>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" class="custom-control-input" value="Ya"
                                                    name="alergi_makanan" id="alergi_makanan1">
                                                <label class="custom-control-label text-primary"
                                                    for="alergi_makanan1">Ya</label>
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
                                                <label class="custom-control-label text-primary"
                                                    for="alergi_makanan1">Ya</label>
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
                                            <label for="reaksi_alergi_obat" class="control-label text-primary ">Reaksi
                                                terhadap
                                                alergi
                                                obat</label>
                                            <input name="reaksi_alergi_obat" id="reaksi_alergi_obat"
                                                class="form-control alergi" type="text">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="reaksi_alergi_makanan" class="control-label text-primary">Reaksi
                                                terhadap
                                                alergi
                                                makanan</label>
                                            <input name="reaksi_alergi_makanan" id="reaksi_alergi_makanan"
                                                class="form-control alergi" type="text">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="reaksi_alergi_lainnya" class="control-label text-primary">Reaksi
                                                terhadap
                                                alergi
                                                lainnya</label>
                                            <input name="reaksi_alergi_lainnya" id="reaksi_alergi_lainnya"
                                                class="form-control alergi" type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="kondisi_khusus1"
                                                class="control-label text-primary margin-tb-10">Gelang
                                                tanda
                                                alergi</label>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" value="1"
                                                    name="gelang" id="gelang1">
                                                <label class="custom-control-label text-primary" for="gelang1">Dipasang
                                                    (warna
                                                    merah)</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <header class="text-secondary">
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
                                            <input name="skor_nyeri" id="skor_nyeri"
                                                class="form-control text-center mt-3"
                                                style="font-size: 3rem; height: 60px;" type="text">
                                            <label for="skor_nyeri" class="control-label text-primary">Skor</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group">
                                            <label for="provokatif" class="control-label text-primary">Provokatif</label>
                                            <input name="provokatif" id="provokatif" class="form-control"
                                                type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group">
                                            <label for="quality" class="control-label text-primary">Quality</label>
                                            <input name="quality" id="quality" class="form-control" type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group">
                                            <label for="region" class="control-label text-primary">Region</label>
                                            <input name="region" id="region" class="form-control" type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group">
                                            <label for="time" class="control-label text-primary">Time</label>
                                            <input name="time" id="time" class="form-control" type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
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
                                    <div class="col-md-9 mb-3">
                                        <div class="form-group">
                                            <label for="nyeri_hilang" class="control-label text-primary">Nyeri hilang
                                                apabila</label>
                                            <input name="nyeri_hilang" id="nyeri_hilang" class="form-control"
                                                type="text">
                                        </div>
                                    </div>
                                </div>
                                <header class="text-secondary">
                                    <h4 class="mt-5 font-weight-bold">SKRINING GIZI</h4>
                                </header>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="penurunan_bb" class="control-label text-primary">Penurunan berat
                                                badan
                                                6
                                                bln
                                                terakhir</label>
                                            <select name="penurunan_bb" id="penurunan_bb" class="select2">
                                                <option></option>
                                                <option value="Tidak">Tidak</option>
                                                <option value="Tidak yakin / Ragu-ragu">Tidak yakin / Ragu-ragu</option>
                                                <option value="Ya, 1-5 Kg">Ya, 1-5 Kg</option>
                                                <option value="Ya, 6-10 Kg">Ya, 6-10 Kg</option>
                                                <option value="Ya, 11-15 Kg">Ya, 11-15 Kg</option>
                                                <option value="Ya, > 15 Kg">Ya, &gt; 15 Kg</option>
                                                <option value="Ya, tidak tahu berapa Kg">Ya, tidak tahu berapa Kg</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="asupan_makan" class="control-label text-primary">Asupan makanan
                                                pasien</label>
                                            <select name="asupan_makan" id="asupan_makan" class="select2">
                                                <option></option>
                                                <option value="Normal">Normal</option>
                                                <option value="Berkurang, penurunan nafsu makan/kesulitan menerima makan"
                                                    data-skor="1">
                                                    Berkurang, penurunan nafsu makan/kesulitan menerima makan</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <label for="kondisi_khusus1" class="control-label text-primary mt-3">Pasien dalam kondisi
                                    khusus</label>
                                <div class="row mt-3">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-radio">
                                                <label class="custom-control custom-checkbox custom-control-inline">
                                                    <input name="kondisi_khusus1" id="kondisi_khusus1"
                                                        value="Anak usia 1-5 tahun" type="checkbox"
                                                        class="custom-control-input">
                                                    <span class="custom-control-label text-primary">Anak usia 1-5
                                                        tahun</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-radio">
                                                <label class="custom-control custom-checkbox custom-control-inline">
                                                    <input name="kondisi_khusus2" id="kondisi_khusus2"
                                                        value="Lansia > 60 tahun" type="checkbox"
                                                        class="custom-control-input">
                                                    <span class="custom-control-label text-primary">Lansia &gt; 60
                                                        tahun</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-radio">
                                                <label class="custom-control custom-checkbox custom-control-inline">
                                                    <input name="kondisi_khusus3" id="kondisi_khusus3"
                                                        value="Penyakit kronis dengan komplikasi" type="checkbox"
                                                        class="custom-control-input">
                                                    <span class="custom-control-label text-primary">Penyakit kronis dengan
                                                        komplikasi</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-radio">
                                                <label class="custom-control custom-checkbox custom-control-inline">
                                                    <input name="kondisi_khusus4" id="kondisi_khusus4"
                                                        value="Kanker stadium III/IV" type="checkbox"
                                                        class="custom-control-input">
                                                    <span class="custom-control-label text-primary">Kanker stadium
                                                        III/IV</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-radio">
                                                <label class="custom-control custom-checkbox custom-control-inline">
                                                    <input name="kondisi_khusus5" id="kondisi_khusus5" value="HIV/AIDS"
                                                        type="checkbox" class="custom-control-input">
                                                    <span class="custom-control-label text-primary">HIV/AIDS</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-radio">
                                                <label class="custom-control custom-checkbox custom-control-inline">
                                                    <input name="kondisi_khusus6" id="kondisi_khusus6" value="TB"
                                                        type="checkbox" class="custom-control-input">
                                                    <span class="custom-control-label text-primary">TB</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-radio">
                                                <label class="custom-control custom-checkbox custom-control-inline">
                                                    <input name="kondisi_khusus7" id="kondisi_khusus7"
                                                        value="Bedah mayor degestif" type="checkbox"
                                                        class="custom-control-input">
                                                    <span class="custom-control-label text-primary">Bedah mayor
                                                        degestif</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-radio">
                                                <label class="custom-control custom-checkbox custom-control-inline">
                                                    <input name="kondisi_khusus8" id="kondisi_khusus8"
                                                        value="Luka bakar > 20%" type="checkbox"
                                                        class="custom-control-input">
                                                    <span class="custom-control-label text-primary">Luka bakar &gt;
                                                        20%</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <header class="text-secondary">
                                    <h4 class="mt-5 font-weight-bold">RIWAYAT IMUNISASI DASAR</h4>
                                </header>
                                <div class="row mt-3">
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group">
                                            <div class="form-radio">
                                                <label class="custom-control custom-checkbox custom-control-inline">
                                                    <input name="imunisasi_dasar1" id="imunisasi_dasar1" value="BCG"
                                                        type="checkbox" class="custom-control-input">
                                                    <span class="custom-control-label text-primary">BCG</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group">
                                            <div class="form-radio">
                                                <label class="custom-control custom-checkbox custom-control-inline">
                                                    <input name="imunisasi_dasar2" id="imunisasi_dasar2" value="DPT"
                                                        type="checkbox" class="custom-control-input">
                                                    <span class="custom-control-label text-primary">DPT</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group">
                                            <div class="form-radio">
                                                <label class="custom-control custom-checkbox custom-control-inline">
                                                    <input name="imunisasi_dasar3" id="imunisasi_dasar3"
                                                        value="Hepatitis B" type="checkbox" class="custom-control-input">
                                                    <span class="custom-control-label text-primary">Hepatitis B</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group">
                                            <div class="form-radio">
                                                <label class="custom-control custom-checkbox custom-control-inline">
                                                    <input name="imunisasi_dasar4" id="imunisasi_dasar4" value="Polio"
                                                        type="checkbox" class="custom-control-input">
                                                    <span class="custom-control-label text-primary">Polio</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group">
                                            <div class="form-radio">
                                                <label class="custom-control custom-checkbox custom-control-inline">
                                                    <input name="imunisasi_dasar5" id="imunisasi_dasar5" value="Campak"
                                                        type="checkbox" class="custom-control-input">
                                                    <span class="custom-control-label text-primary">Campak</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <header class="text-secondary">
                                    <h4 class="mt-5 font-weight-bold">SKRINING RESIKO JATUH - GET UP & GO</h4>
                                </header>
                                <div class="row mt-3">
                                    <div class="col-md-12 mb-3">
                                        <label for="resiko_jatuh3" class="control-label text-primary margin-tb-10">A. Cara
                                            Berjalan</label>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <div class="form-radio">
                                                <label class="custom-control custom-checkbox custom-control-inline">
                                                    <input onclick="resiko_jatuh()" name="resiko_jatuh1"
                                                        id="resiko_jatuh1" value="Tidak seimbang/sempoyongan/limbung"
                                                        type="checkbox" class="custom-control-input">
                                                    <span class="custom-control-label text-primary">Tidak
                                                        seimbang/sempoyongan/limbung</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <div class="form-radio">
                                                <label class="custom-control custom-checkbox custom-control-inline">
                                                    <input onclick="resiko_jatuh()" name="resiko_jatuh2"
                                                        id="resiko_jatuh2" value="Alat bantu: kruk,kursi roda/dibantu"
                                                        type="checkbox" class="custom-control-input">
                                                    <span class="custom-control-label text-primary">Jalan dengan alat
                                                        bantu(kruk,kursi
                                                        roda/dibantu)</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="resiko_jatuh3"
                                                class="control-label mb-3 text-primary margin-tb-10">B.
                                                Menopang saat duduk</label>
                                            <div class="form-radio">
                                                <label class="custom-control custom-checkbox custom-control-inline">
                                                    <input onclick="resiko_jatuh()" name="resiko_jatuh3"
                                                        id="resiko_jatuh3"
                                                        value="Pegang pinggiran meja/kursi/alat bantu untuk duduk"
                                                        type="checkbox" class="custom-control-input">
                                                    <span class="custom-control-label text-primary">Pegang pinggiran
                                                        meja/kursi/alat bantu untuk duduk</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <span class="input-group-addon grey-text">Hasil : </span>
                                            <div class="input-group-content">
                                                <input class="form-control" name="resiko_jatuh_hasil"
                                                    id="resiko_jatuh_hasil" type="text" readonly="">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <header class="text-secondary">
                                    <h4 class="mt-5 font-weight-bold">RIWAYAT PSIKOSOSIAL, SPIRITUAL &amp; KEPERCAYAAN</h4>
                                </header>
                                <div class="row mt-3">
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group">
                                            <label for="status_psikologis" class="control-label text-primary">Status
                                                psikologis</label>
                                            <select name="status_psikologis" id="status_psikologis" class="select2">
                                                <option></option>
                                                <option value="Tenang">Tenang</option>
                                                <option value="Cemas">Cemas</option>
                                                <option value="Takut">Takut</option>
                                                <option value="Marah">Marah</option>
                                                <option value="Sedih">Sedih</option>
                                                <option value="Kecenderungan bunuh diri">Kecenderungan bunuh diri</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group">
                                            <label for="status_spiritual" class="control-label text-primary">Status
                                                spiritual</label>
                                            <select name="status_spiritual" id="status_spiritual" class="select2">
                                                <option></option>
                                                <option value="Percaya Nilai-nilai dan kepercayaan">Percaya Nilai-nilai dan
                                                    kepercayaan
                                                </option>
                                                <option value="Tidak Percaya Nilai-nilai dan kepercayaan">Tidak Percaya
                                                    Nilai-nilai
                                                    dan
                                                    kepercayaan</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group">
                                            <label for="masalah_prilaku" class="control-label text-primary">Masalah
                                                prilaku(bila
                                                ada)</label>
                                            <input name="masalah_prilaku" id="masalah_prilaku" class="form-control"
                                                type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group">
                                            <label for="kekerasan_dialami" class="control-label text-primary">Kekerasan yg
                                                pernah
                                                dialami</label>
                                            <input name="kekerasan_dialami" id="kekerasan_dialami" class="form-control"
                                                type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group">
                                            <label for="hub_dengan_keluarga" class="control-label text-primary">Hubungan
                                                dengan
                                                anggota
                                                keluarga</label>
                                            <input name="hub_dengan_keluarga" id="hub_dengan_keluarga"
                                                class="form-control" type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group">
                                            <label for="tempat_tinggal" class="control-label text-primary">Tempat tinggal
                                                (rumah/panti/kos/dll)</label>
                                            <input name="tempat_tinggal" id="tempat_tinggal" class="form-control"
                                                type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group">
                                            <label for="kerabat_dihub" class="control-label text-primary">Kerabat yang
                                                dapat
                                                dihubungi</label>
                                            <input name="kerabat_dihub" id="kerabat_dihub" class="form-control"
                                                type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group">
                                            <label for="no_kontak_kerabat" class="control-label text-primary">Kontak
                                                kerabat
                                                yang
                                                dapat
                                                dihubungi</label>
                                            <input name="no_kontak_kerabat" id="no_kontak_kerabat" class="form-control"
                                                type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group">
                                            <label for="status_perkawinan" class="control-label text-primary">Status
                                                perkawinan</label>
                                            <input name="status_perkawinan" id="status_perkawinan" class="form-control"
                                                value="Belum Nikah" disabled="" type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group">
                                            <label for="pekerjaan" class="control-label text-primary">Pekerjaan</label>
                                            <input name="pekerjaan" id="pekerjaan" class="form-control" value=""
                                                disabled="" type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group">
                                            <label for="penghasilan"
                                                class="control-label text-primary">Penghasilan</label>
                                            <select name="penghasilan" id="penghasilan" class="select2">
                                                <option></option>
                                                <option value="< 1 Juta">&lt; 1 Juta</option>
                                                <option value="1 - 2,9 Juta">1 - 2,9 Juta</option>
                                                <option value="3 - 4,9 Juta">3 - 4,9 Juta</option>
                                                <option value="5 - 9,9 Juta">5 - 9,9 Juta</option>
                                                <option value="10 - 14,9 Juta">10 - 14,9 Juta</option>
                                                <option value="15 - 19.5 Juta">15 - 19.5 Juta</option>
                                                <option value="> 20 Juta">&gt; 20 Juta</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group">
                                            <label for="pendidikan" class="control-label text-primary">Pendidikan</label>
                                            <input name="pendidikan" id="pendidikan" class="form-control" type="text"
                                                value="Belum / Tidak tamat SD">
                                        </div>
                                    </div>
                                </div>
                                <header class="text-secondary">
                                    <h4 class="mt-5 font-weight-bold">KEBUTUHAN EDUKASI</h4>
                                    <label for="hambatan_belajar1"
                                        class="control-label font-weight-bold text-primary margin-tb-10">Hambatan
                                        dalam
                                        pembelajaran</label>
                                </header>
                                <div class="row mt-3">
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group">
                                            <div class="form-radio">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" name="hambatan_belajar1"
                                                        id="hambatan_belajar1" value="Pendengaran" type="checkbox">
                                                    <label for="hambatan_belajar1"
                                                        class="custom-control-label text-primary">Pendengaran</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group">
                                            <div class="form-radio">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" name="hambatan_belajar2"
                                                        id="hambatan_belajar2" value="Penglihatan" type="checkbox">
                                                    <label for="hambatan_belajar2"
                                                        class="custom-control-label text-primary">Penglihatan</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group">
                                            <div class="form-radio">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" name="hambatan_belajar3"
                                                        id="hambatan_belajar3" value="Kognitif" type="checkbox">
                                                    <label for="hambatan_belajar3"
                                                        class="custom-control-label text-primary">Kognitif</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group">
                                            <div class="form-radio">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" name="hambatan_belajar4"
                                                        id="hambatan_belajar4" value="Fisik" type="checkbox">
                                                    <label for="hambatan_belajar4"
                                                        class="custom-control-label text-primary">Fisik</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group">
                                            <div class="form-radio">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" name="hambatan_belajar5"
                                                        id="hambatan_belajar5" value="Budaya" type="checkbox">
                                                    <label for="hambatan_belajar5"
                                                        class="custom-control-label text-primary">Budaya</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group">
                                            <div class="form-radio">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" name="hambatan_belajar6"
                                                        id="hambatan_belajar6" value="Agama" type="checkbox">
                                                    <label for="hambatan_belajar6"
                                                        class="custom-control-label text-primary">Agama</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group">
                                            <div class="form-radio">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" name="hambatan_belajar7"
                                                        id="hambatan_belajar7" value="Emosi" type="checkbox">
                                                    <label for="hambatan_belajar7"
                                                        class="custom-control-label text-primary">Emosi</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group">
                                            <div class="form-radio">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" name="hambatan_belajar8"
                                                        id="hambatan_belajar8" value="Bahasa" type="checkbox">
                                                    <label for="hambatan_belajar8"
                                                        class="custom-control-label text-primary">Bahasa</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group">
                                            <div class="form-radio">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" name="hambatan_belajar9"
                                                        id="hambatan_belajar9" value="Tidak ada Hamabatan"
                                                        type="checkbox">
                                                    <label for="hambatan_belajar9"
                                                        class="custom-control-label text-primary">Tidak
                                                        ada
                                                        Hamabatan</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group">
                                            <label for="hambatan_lainnya" class="control-label text-primary">Hambatan
                                                lainnya</label>
                                            <input name="hambatan_lainnya" id="hambatan_lainnya" class="form-control"
                                                type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="kebutuhan_penerjemah" class="control-label text-primary">Kebutuhan
                                                penerjemah</label>
                                            <input name="kebutuhan_penerjemah" id="kebutuhan_penerjemah"
                                                class="form-control" type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="kebuthan_pembelajaran1"
                                            class="control-label font-weight-bold margin-tb-10 text-primary mt-3">Kebutuhan
                                            pembelajaran</label>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-radio">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" name="kebuthan_pembelajaran1"
                                                        id="kebuthan_pembelajaran1" value="Diagnosa managemen"
                                                        type="checkbox">
                                                    <label for="kebuthan_pembelajaran1"
                                                        class="custom-control-label text-primary">Diagnosa
                                                        managemen</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-radio">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" name="kebuthan_pembelajaran2"
                                                        id="kebuthan_pembelajaran2" value="Obat-obatan" type="checkbox">
                                                    <label for="kebuthan_pembelajaran2"
                                                        class="custom-control-label text-primary">Obat-obatan</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-radio">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" name="kebuthan_pembelajaran3"
                                                        id="kebuthan_pembelajaran3" value="Perawatan luka"
                                                        type="checkbox">
                                                    <label for="kebuthan_pembelajaran3"
                                                        class="custom-control-label text-primary">Perawatan luka</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-radio">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" name="kebuthan_pembelajaran4"
                                                        id="kebuthan_pembelajaran4" value="Rehabilitasi" type="checkbox">
                                                    <label for="kebuthan_pembelajaran4"
                                                        class="custom-control-label text-primary">Rehabilitasi</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-radio">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" name="kebuthan_pembelajaran5"
                                                        id="kebuthan_pembelajaran5" value="Manajemen nyeri"
                                                        type="checkbox">
                                                    <label for="kebuthan_pembelajaran5"
                                                        class="custom-control-label text-primary">Manajemen nyeri</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-radio">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" name="kebuthan_pembelajaran6"
                                                        id="kebuthan_pembelajaran6" value="Diet &amp; nutrisi"
                                                        type="checkbox">
                                                    <label for="kebuthan_pembelajaran6"
                                                        class="custom-control-label text-primary">Diet
                                                        &amp; nutrisi</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-radio">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" name="kebuthan_pembelajaran7"
                                                        id="kebuthan_pembelajaran7" value="Tidak ada Hamabatan"
                                                        type="checkbox">
                                                    <label for="kebuthan_pembelajaran7"
                                                        class="custom-control-label text-primary">Tidak ada
                                                        Hamabatan</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-3">
                                        <label for="pembelajaran_lainnya"
                                            class="control-label font-weight-bold margin-tb-10 text-primary">Kebutuhan
                                            pembelajaran
                                            lainnya</label>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <input name="pembelajaran_lainnya" id="pembelajaran_lainnya"
                                                class="form-control" type="text">
                                        </div>
                                    </div>
                                </div>

                                <header class="text-secondary">
                                    <h4 class="mt-5 font-weight-bold">Assesment Fungsional (Pengkajian Fungsi)</h4>
                                </header>
                                <header class="text-danger">
                                    <h4 class="mt-5 font-weight-bold">Sensorik</h4>
                                </header>
                                <div class="row mt-3">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td>Penglihatan</td>
                                                <td>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input name="sensorik_penglihatan" id="sensorik_penglihatan1"
                                                            value="Normal" data-skor="0" class="custom-control-input"
                                                            type="radio">
                                                        <label class="custom-control-label"
                                                            for="sensorik_penglihatan1">Normal</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input name="sensorik_penglihatan" id="sensorik_penglihatan2"
                                                            value="Kabur" data-skor="1" class="custom-control-input"
                                                            type="radio">
                                                        <label class="custom-control-label"
                                                            for="sensorik_penglihatan2">Kabur</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input name="sensorik_penglihatan" id="sensorik_penglihatan3"
                                                            value="Kaca Mata" data-skor="2"
                                                            class="custom-control-input" type="radio">
                                                        <label class="custom-control-label"
                                                            for="sensorik_penglihatan3">Kaca
                                                            Mata</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input name="sensorik_penglihatan" id="sensorik_penglihatan4"
                                                            value="Lensa Kontak" data-skor="3"
                                                            class="custom-control-input" type="radio">
                                                        <label class="custom-control-label"
                                                            for="sensorik_penglihatan4">Lensa
                                                            Kontak</label>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Penciuman</td>
                                                <td>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input name="sensorik_penciuman" id="sensorik_penciuman1"
                                                            value="Normal" data-skor="0" class="custom-control-input"
                                                            type="radio">
                                                        <label class="custom-control-label"
                                                            for="sensorik_penciuman1">Normal</label>
                                                    </div>
                                                </td>
                                                <td colspan="3">
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input name="sensorik_penciuman" id="sensorik_penciuman2"
                                                            value="Tidak" data-skor="1" class="custom-control-input"
                                                            type="radio">
                                                        <label class="custom-control-label"
                                                            for="sensorik_penciuman2">Tidak</label>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Pendengaran</td>
                                                <td>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input name="sensorik_pendengaran" id="sensorik_pendengaran1"
                                                            value="Normal" data-skor="0" class="custom-control-input"
                                                            type="radio">
                                                        <label class="custom-control-label"
                                                            for="sensorik_pendengaran1">Normal</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input name="sensorik_pendengaran" id="sensorik_pendengaran2"
                                                            value="Tuli Ka / Ki" data-skor="1"
                                                            class="custom-control-input" type="radio">
                                                        <label class="custom-control-label"
                                                            for="sensorik_pendengaran2">Tuli
                                                            Ka
                                                            /
                                                            Ki</label>
                                                    </div>
                                                </td>
                                                <td colspan="2">
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input name="sensorik_pendengaran" id="sensorik_pendengaran3"
                                                            value="Ada alat bantu dengar ka/ki" data-skor="2"
                                                            class="custom-control-input" type="radio">
                                                        <label class="custom-control-label"
                                                            for="sensorik_pendengaran3">Ada
                                                            alat
                                                            bantu dengar ka/ki</label>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <header class="text-danger">
                                    <h4 class="mt-5 font-weight-bold">Kognitif</h4>
                                </header>
                                <div class="row mt-3">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input name="kognitif" class="custom-control-input"
                                                            id="kognitif1" value="Normal" data-skor="0"
                                                            type="radio">
                                                        <label class="custom-control-label"
                                                            for="kognitif1">Normal</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input name="kognitif" class="custom-control-input"
                                                            id="kognitif2" value="Bingung" data-skor="1"
                                                            type="radio">
                                                        <label class="custom-control-label"
                                                            for="kognitif2">Bingung</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input name="kognitif" class="custom-control-input"
                                                            id="kognitif3" value="Pelupa" data-skor="2"
                                                            type="radio">
                                                        <label class="custom-control-label"
                                                            for="kognitif3">Pelupa</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input name="kognitif" class="custom-control-input"
                                                            id="kognitif4" value="Tidak Dapat dimengerti"
                                                            data-skor="3" type="radio">
                                                        <label class="custom-control-label" for="kognitif4">Tidak Dapat
                                                            dimengerti</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    &nbsp;
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <header class="text-danger">
                                    <h4 class="mt-5 font-weight-bold">Motorik</h4>
                                </header>
                                <div class="row mt-3">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td>Aktifitas Sehari - hari</td>
                                                <td>
                                                    <div class="custom-control custom-radio">
                                                        <input name="motorik_aktifitas" id="motorik_aktifitas1"
                                                            value="Mandiri" data-skor="0"
                                                            class="custom-control-input" type="radio">
                                                        <label class="custom-control-label text-primary"
                                                            for="motorik_aktifitas1">Mandiri</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="custom-control custom-radio">
                                                        <input name="motorik_aktifitas" id="motorik_aktifitas2"
                                                            value="Bantuan Minimal" data-skor="1"
                                                            class="custom-control-input" type="radio">
                                                        <label class="custom-control-label text-primary"
                                                            for="motorik_aktifitas2">Bantuan Minimal</label>
                                                    </div>
                                                </td>
                                                <td colspan="2">
                                                    <div class="custom-control custom-radio">
                                                        <input name="motorik_aktifitas" id="motorik_aktifitas3"
                                                            value="Bantuan Ketergantungan Total" data-skor="2"
                                                            class="custom-control-input" type="radio">
                                                        <label class="custom-control-label text-primary"
                                                            for="motorik_aktifitas3">Bantuan Ketergantungan Total</label>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Berjalan</td>
                                                <td>
                                                    <div class="custom-control custom-radio">
                                                        <input name="motorik_berjalan" id="motorik_berjalan1"
                                                            value="Tidak Ada kesulitan" data-skor="0"
                                                            class="custom-control-input" type="radio">
                                                        <label class="custom-control-label text-primary"
                                                            for="motorik_berjalan1">Tidak Ada kesulitan</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="custom-control custom-radio">
                                                        <input name="motorik_berjalan" id="motorik_berjalan2"
                                                            value="Perlu Bantuan" data-skor="1"
                                                            class="custom-control-input" type="radio">
                                                        <label class="custom-control-label text-primary"
                                                            for="motorik_berjalan2">Perlu Bantuan</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="custom-control custom-radio">
                                                        <input name="motorik_berjalan" id="motorik_berjalan3"
                                                            value="Sering Jatuh" data-skor="0"
                                                            class="custom-control-input" type="radio">
                                                        <label class="custom-control-label text-primary"
                                                            for="motorik_berjalan3">Sering Jatuh</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="custom-control custom-radio">
                                                        <input name="motorik_berjalan" id="motorik_berjalan4"
                                                            value="Kelumpuhan" data-skor="1"
                                                            class="custom-control-input" type="radio">
                                                        <label class="custom-control-label text-primary"
                                                            for="motorik_berjalan4">Kelumpuhan</label>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="row mt-5">
                                    <div class="col-md-4 text-center">
                                        <span>Perawat,</span>
                                        <div id="tombol-1" class="mt-3">
                                            <a class="btn btn-primary btn-sm text-white ttd"
                                                onclick="openSignaturePad(1)" id="ttd_pegawai">Tanda tangan</a>
                                        </div>
                                        <div class="mt-3">
                                            <img id="signature-display-1" src="" alt="Signature Image"
                                                style="display:none; max-width:60%;">
                                        </div>
                                        <div class="mt-3">
                                            <span>{{ auth()->user()->employee->fullname }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-5">
                                    <div class="col-md-12 px-3">
                                        <div class="card-actionbar">
                                            <div
                                                class="card-actionbar-row d-flex justify-content-between align-items-center">
                                                <button type="button"
                                                    class="btn btn-primary waves-effect waves-light save-form d-flex align-items-center"
                                                    data-dismiss="modal" data-status="0">
                                                    <span class="mdi mdi-printer mr-2"></span> Print
                                                </button>
                                                <div style="width: 40%" class="d-flex justify-content-end">
                                                    <button type="button"
                                                        class="btn mr-2 btn-warning waves-effect text-white waves-light save-form d-flex align-items-center"
                                                        data-dismiss="modal" data-status="0"
                                                        id="sd-pengkajian-nurse-rajal">
                                                        <span class="mdi mdi-content-save mr-2"></span> Simpan (draft)
                                                    </button>
                                                    <button type="button"
                                                        class="btn btn-primary waves-effect waves-light save-form d-flex align-items-center"
                                                        data-dismiss="modal" data-status="1"
                                                        id="sf-pengkajian-nurse-rajal">
                                                        <span class="mdi mdi-content-save mr-2"></span> Simpan (final)
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="row" style="height: 90%">
                        <div class="col-lg-12 d-flex align-items-center justify-content-center">
                            <div class="logo-dashboard-simrs text-center">
                                <h3 class="text-center spaced-text gradient-text">COMING SOON</h3>
                                <img src="{{ asset('img/logo.png') }}" width="130" height="130" alt="Logo RS">
                                <h3 class="text-center spaced-text mt-3">RUMAH SAKIT LIVASYA</h3>
                                <p style="letter-spacing: 0.2em">Jl. Raya Timur III Dawuan No. 875 Kab. Majalengka Telp
                                    081211151300</p>
                            </div>
                        </div>
                    </div>
                @endif
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

            $('.slide-backdrop').on('click', function() {
                $('#js-slide-left').removeClass('slide-on-mobile-left-show');
                $(this).removeClass('show');
            });

        });
    </script>
    @include('pages.simrs.poliklinik.partials.js-filter')
    @include('pages.simrs.poliklinik.partials.action-js.pengkajian-perawat')
@endsection
