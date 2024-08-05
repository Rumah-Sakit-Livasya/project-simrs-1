@extends('inc.layout')
@section('content')
<style>
    .biodata-pasien {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .btn-biodata {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        grid-gap: 5px;
        margin: 10px;
    }

    .btn-flatcx {
        width: 30px;
        height: 30px;
        line-height: 30px;
        border: 1px solid #ccc;
        color: var(--primary-color);
        font-size: 1.5em;
        border-radius: 50%;
        text-align: center;
        vertical-align: middle;
    }

    .form-control[disabled],
    .form-control[readonly],
    fieldset[disabled] .form-control {
        background-color: transparent;
        border-bottom-color: rgba(12, 12, 12, 0.2);
        border-bottom-style: dashed;
        outline: none;
        border-top: none;
        border-right: none;
        border-left: none;
    }


    li.blue-box {
        background: #eef5fd;
        color: #3F51B5;
    }

    li.red-box {
        background: #fff4f7;
        color: #F44336;
    }

    li.green-box {
        background: #f1fdda;
        color: #8BC34A;
    }

    li.cyan-box {
        background: #edfbfd;
        color: #00BCD4;
    }

    li.orange-box {
        background: #fff1dc;
        color: #FF9800;
    }

    li.purple-box {
        background: #f5e8f7;
        color: #9C27B0;
    }

    li.brown-box {
        background: #efdad2;
        color: #ab6e58;
    }

    .box-menu li {
        padding: 20px 30px;
        margin: 20px;
        width: 200px;
        background: #f2f0f5;
        text-align: center;
        cursor: pointer;
        border: 1px solid #e5e5e5;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        box-shadow: 0 3px 3px 0 rgba(0, 0, 0, 0.33);
    }

    .box-menu .circle-menu {
        height: 50px;
        width: 50px;
        line-height: 50px;
        font-size: 2.5em;
        transition: all .15s linear;
    }
</style>
<main id="js-page-content" role="main" class="page-content">
    <div class="row">
        <div class="col-xl-3">
            <div id="panel-1" class="panel">
                <div class="panel-container show">
                    <div class="panel-content">
                        <div class="row">
                            <div class="col biodata-pasien">
                                <img src="http://192.168.1.253/real/include/avatar/woman-icon.png"
                                    style="width: 120px; height: 120px;">
                                <h3 class="text-center mt-3 text-black">
                                    {{ strtoupper($patient->name) }}
                                    <small class="text-danger text-accent-2 mt-1">
                                        No RM : {{ $patient->medical_record_number }}
                                    </small>
                                </h3>
                            </div>

                            <div class="col-md-10 col-bg-10">

                                <ul class="nav nav-tabs nav-tabs-clean" role="tablist">
                                    <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#biodata"
                                            role="tab">Biodata</a></li>
                                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#kunjungan"
                                            role="tab">Kunjungan</a></li>
                                </ul>
                                <div class="tab-content p-3">
                                    <div class="tab-pane fade show active" id="biodata" role="tabpanel"
                                        aria-labelledby="biodata">
                                        <div class="mt-3 row align-items-center">
                                            <div class="col-xl-2">
                                                <i class="fas fa-calendar text-warning mr-2"
                                                    style="transform: scale(1.4)"></i>
                                            </div>
                                            <div class="col">
                                                <span class="d-block">{{ $patient->place }}, {{ $patient->date_of_birth
                                                    }}</span>
                                                <span class="text-primary">Tempat, Tanggal Lahir</span>
                                            </div>
                                        </div>
                                        <div class="mt-3 row align-items-center">
                                            <div class="col-xl-2">
                                                <i class="fas fa-calendar-alt text-warning mr-2"
                                                    style="transform: scale(1.4)"></i>
                                            </div>
                                            <div class="col">
                                                <span class="d-block">{{ $age }}</span>
                                                <span class="text-primary">Umur</span>
                                            </div>
                                        </div>
                                        <div class="mt-3 row align-items-center">
                                            <div class="col-xl-2">
                                                <i class="fas fa-venus-mars text-warning mr-2"
                                                    style="transform: scale(1.4)"></i>
                                            </div>
                                            <div class="col">
                                                <span class="d-block">{{ $patient->gender }}</span>
                                                <span class="text-primary">Jenis Kelamin</span>
                                            </div>
                                        </div>
                                        <div class="mt-3 row align-items-center">
                                            <div class="col-xl-2">
                                                <i class="fas fa-map-marker-alt text-warning mr-2"
                                                    style="transform: scale(1.4)"></i>
                                            </div>
                                            <div class="col">
                                                <span class="d-block">{{ $patient->address }}</span>
                                                <span class="text-primary">Alamat</span>
                                            </div>
                                        </div>
                                        <div class="mt-3 row align-items-center">
                                            <div class="col-xl-2">
                                                <i class="fas fa-mobile-android-alt text-warning mr-2"
                                                    style="transform: scale(1.4)"></i>
                                            </div>
                                            <div class="col">
                                                <span class="d-block">{{ $patient->mobile_phone_number }}</span>
                                                <span class="text-primary">Telp/HP</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="kunjungan" role="tabpanel"
                                        aria-labelledby="kunjungan">
                                        Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin
                                        coffee squid.
                                        Exercitation +1 labore velit, blog sartorial PBR leggings next level wes
                                        anderson
                                        artisan four loko farm-to-table craft beer twee. Qui photo booth letterpress,
                                        commodo
                                        enim craft beer mlkshk aliquip jean shorts ullamco ad vinyl cillum PBR. Homo
                                        nostrud
                                        organic.
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-9">
            <div id="panel-1" class="panel">
                <div class="panel-hdr">
                    <h2 class="text-light">
                        <i class="fas fa-address-card mr-3 ml-2 text-primary" style="transform: scale(2.1)"></i>
                        <span class="text-primary">Data Registrasi</span>
                    </h2>
                </div>
                <div class="panel-container show">
                    <div class="panel-content">
                        <ul class="box-menu">
                            <div class="row justify-content-center">
                                <div class="col-md-6">
                                    <div class="row justify-content-center">

                                    </div>
                                </div>
                            </div>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

</main>
@endsection