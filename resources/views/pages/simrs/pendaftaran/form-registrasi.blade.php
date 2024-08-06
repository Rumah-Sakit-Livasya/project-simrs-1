{{-- @dd($penjamins) --}}
@extends('inc.layout')
@section('content')
    @php
        use Carbon\Carbon;
        $today = Carbon::today()->format('d-m-Y');
    @endphp
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
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            <i class='bx bxs-id-card' style="transform: scale(1.5); margin-right: .5rem;"></i>
                            Biodata <span class="fw-300"><i>Pasien</i></span>
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <div class="row">
                                <div class="col-md-2 biodata-pasien">
                                    <img src="/img/user/woman-icon.png" style="width: 120px; height: 120px;">
                                    <div class="btn-biodata">
                                        <button class="btn-flatcx pointer" id="kunjungan" alt="Riwayat Kunjungan"
                                            title="Riwayat Kunjungan"><i class="mdi mdi-clipboard-pulse"></i></button>
                                        <button class="btn-flatcx" id="button" alt="Detail Biodata Pasien"
                                            title="Detail Biodata Pasien"><i class="mdi mdi-account-edit"></i></button>
                                    </div>
                                </div>
                                <div class="col-md-10 col-bg-10">
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="row align-items-center">
                                                    <label for="s_tgl_1" class="col-md-4 control-label">No Rekam
                                                        Medis</label>
                                                    <div class="col-md">
                                                        <input class="form-control" type="text"
                                                            value="{{ $patient->medical_record_number }}"
                                                            readonly="readonly">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="row align-items-center">
                                                    <label for="s_tgl_1" class="col-md-4 control-label">Jenis
                                                        Kelamin</label>
                                                    <div class="col-md">
                                                        <input class="form-control" type="text"
                                                            value="{{ $patient->gender }}" readonly="readonly">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="row align-items-center">
                                                    <label for="s_tgl_1" class="col-md-4 control-label">Nama Pasien</label>
                                                    <div class="col-md">
                                                        <input class="form-control" type="text"
                                                            value="{{ $patient->name }}" readonly="readonly">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="row align-items-center">
                                                    <label for="s_tgl_1" class="col-md-4 control-label">Alamat</label>
                                                    <div class="col-md">
                                                        <input class="form-control" type="text"
                                                            value="{{ $patient->address }}" readonly="readonly">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="row align-items-center">
                                                    <label for="s_tgl_1" class="col-md-4 control-label">Tempat, Tgl.
                                                        Lahir</label>
                                                    <div class="col-md">
                                                        <input class="form-control" type="text"
                                                            value="{{ $patient->place }}, {{ $patient->date_of_birth }}"
                                                            readonly="readonly">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="row align-items-center">
                                                    <label for="s_tgl_1" class="col-md-4 control-label">Telp/HP</label>
                                                    <div class="col-md">
                                                        <input class="form-control" type="text"
                                                            value="{{ $patient->mobile_phone_number }}" readonly="readonly">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="row align-items-center">
                                                    <label for="s_tgl_1" class="col-md-4 control-label">Umur</label>
                                                    <div class="col-md">
                                                        <input class="form-control" type="text"
                                                            value="{{ $age }}" readonly="readonly">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="row align-items-center">
                                                    <label for="s_tgl_1" class="col-md-4 control-label">Catatan
                                                        Penting</label>
                                                    <div class="col-md">
                                                        <input class="form-control" type="text" value=""
                                                            readonly="readonly">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row justify-content-end mt-3">
                                        <div class="col-md-4">
                                            <button class="btn btn-primary pull-right waves-effect"
                                                onclick="popupwindow('http://192.168.1.253/real/regprint/print_kartu_pdf/4459','p_card', 400,400,'no'); return false"><i
                                                    class="mdi mdi-printer"></i> Kartu pasien</button>
                                            <button class="btn btn-primary pull-right waves-effect" id="identitas"><i
                                                    class="mdi mdi-printer"></i> Identitas Pasien</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr bg-primary">
                        <h2 class="text-light">
                            <i class="mdi mdi-hospital-building mdi-24px"></i> Formulir <span
                                class="fw-300"><i>{{ $title }}</i></span>
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <ul class="box-menu">

                                @switch($case)
                                    @case('rawat-jalan')
                                        <form action="{{ route('simpan.registrasi.rajal') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                                            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                            <input type="hidden" name="employee_id" value="{{ auth()->user()->employee->id }}">
                                            <input type="hidden" name="registration_type" value="rawat-jalan">
                                            <div class="row">
                                                <div class="col-xl-6">
                                                    <div class="form-group">
                                                        <div class="row align-items-center">
                                                            <div class="col-xl-4 text-right">
                                                                <label class="form-label" for="registration_date">
                                                                    Tanggal Registrasi
                                                                </label>
                                                            </div>
                                                            <div class="col-xl-8">
                                                                <input type="text"
                                                                    style="border: 0; border-bottom: 1.9px dashed #aaa; margin-top: -.5rem; border-radius: 0"
                                                                    class="form-control" id="registration_date" readonly
                                                                    value="{{ $today }}" name="registration_date">
                                                                @error('registration_date')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="row align-items-center">
                                                            <div class="col-xl-4 text-right">
                                                                <label class="form-label" for="doctor_id">Dokter</label>
                                                            </div>
                                                            <div class="col-xl-8">
                                                                <div class="form-group">
                                                                    <select class="select2 form-control w-100" id="doctor_id"
                                                                        name="doctor_id">
                                                                        <option value=""></option>
                                                                        @foreach ($groupedDoctors as $department => $doctors)
                                                                            <optgroup label="{{ $department }}">
                                                                                @foreach ($doctors as $doctor)
                                                                                    <option value="{{ $doctor->id }}"
                                                                                        data-departement="{{ $department }}">
                                                                                        {{ $doctor->employee->fullname }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </optgroup>
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="row align-items-center">
                                                            <div class="col-xl-4 text-right">
                                                                <label class="form-label" for="poliklinik">Poliklinik</label>
                                                            </div>
                                                            <div class="col-xl-8">
                                                                <input type="text"
                                                                    style="border: 0; border-bottom: 1.9px dashed #aaa; margin-top: -.5rem; border-radius: 0"
                                                                    class="form-control" id="poliklinik" readonly
                                                                    name="poliklinik">
                                                                @error('poliklinik')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="row align-items-center">
                                                            <div class="col-xl-4 text-right">
                                                                <label class="form-label" for="penjamin">
                                                                    Penjamin
                                                                </label>
                                                            </div>
                                                            <div class="col-xl-8">
                                                                <div class="form-group">
                                                                    <select class="select2 form-control w-100" id="penjamin"
                                                                        name="penjamin_id">
                                                                        <option selected></option>
                                                                        @foreach ($penjamins as $penjamin)
                                                                            <option value="{{ $penjamin->id }}"
                                                                                {{ $penjamin->id === old('penjamin') ? 'selected' : '' }}>
                                                                                {{ $penjamin->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6">
                                                    <div class="form-group">
                                                        <div class="row align-items-center">
                                                            <div class="col-xl-4 text-right">
                                                                <label class="form-label" for="registration_date">
                                                                    Paket
                                                                </label>
                                                            </div>
                                                            <div class="col-xl-8">
                                                                <div class="form-group">
                                                                    <select class="form-control w-100" id="paket">
                                                                        <option selected></option>
                                                                        <option value="">Paket Skin Care</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="row align-items-center">
                                                            <div class="col-xl-4 text-right">
                                                                <label class="form-label" for="registration_date">
                                                                    Tipe Jadwal
                                                                </label>
                                                            </div>
                                                            <div class="col-xl-8">
                                                                <div class="form-group">
                                                                    <select class="form-control w-100" id="type">
                                                                        <option value="OR">Sesuai Jadwal Praktek</option>
                                                                        <option value="WA">On Call</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="row align-items-center">
                                                            <div class="col-xl-4 text-right">
                                                                <label class="form-label" for="registration_date">
                                                                    Kartu Pasien
                                                                </label>
                                                            </div>
                                                            <div class="col-xl-8">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input"
                                                                        id="patient_card" name="patient_card">
                                                                    <label class="custom-control-label"
                                                                        for="patient_card">Ya</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="row align-items-center">
                                                            <div class="col-xl-4 text-right">
                                                                <label class="form-label" for="">
                                                                    Rujukan
                                                                </label>
                                                            </div>
                                                            <div class="col-xl-8">
                                                                <div class="custom-control custom-checkbox">
                                                                    <div class="frame-wrap">
                                                                        <div
                                                                            class="custom-control custom-radio custom-control-inline p-0">
                                                                            <input type="radio" class="custom-control-input"
                                                                                id="inisiatif_pribadi" name="rujukan"
                                                                                value="inisiatif pribadi">
                                                                            <label class="custom-control-label"
                                                                                for="inisiatif_pribadi">Inisiatif Pribadi</label>
                                                                        </div>
                                                                        <div
                                                                            class="custom-control custom-radio custom-control-inline">
                                                                            <input type="radio" class="custom-control-input"
                                                                                id="dalam_rs" name="rujukan" value="dalam rs">
                                                                            <label class="custom-control-label"
                                                                                for="dalam_rs">Dalam RS</label>
                                                                        </div>
                                                                        <div
                                                                            class="custom-control custom-radio custom-control-inline">
                                                                            <input type="radio" class="custom-control-input"
                                                                                id="luar_rs" name="rujukan" value="luar rs">
                                                                            <label class="custom-control-label"
                                                                                for="luar_rs">Luar RS</label>
                                                                        </div>
                                                                        <div
                                                                            class="custom-control custom-radio custom-control-inline">
                                                                            <input type="radio" class="custom-control-input"
                                                                                id="rujukan_bpjs" name="rujukan"
                                                                                value="rujukan bpjs">
                                                                            <label class="custom-control-label"
                                                                                for="rujukan_bpjs">Rujukan BPJS</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group mt-3 d-none" id="dokter_perujuk_container">
                                                        <div class="row align-items-center">
                                                            <div class="col-xl-4 text-right">
                                                                <label class="form-label" for="dokter_perujuk">Dokter
                                                                    Perujuk</label>
                                                            </div>
                                                            <div class="col-xl-8">
                                                                <div class="form-group">
                                                                    <select class="select2 form-control w-100" id="dokter_perujuk"
                                                                        name="dokter_perujuk">
                                                                        <option value=""></option>
                                                                        @foreach ($groupedDoctors as $department => $doctors)
                                                                            <optgroup label="{{ $department }}">
                                                                                @foreach ($doctors as $doctor)
                                                                                    <option value="{{ $doctor->id }}"
                                                                                        data-departement="{{ $department }}">
                                                                                        {{ $doctor->employee->fullname }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </optgroup>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row d-none" id="luar_dan_rujuk_bpjs_container">
                                                        <div class="col-12">
                                                            <div class="form-group mt-3">
                                                                <div class="row align-items-center">
                                                                    <div class="col-xl-4 text-right">
                                                                        <label class="form-label" for="tipe_rujukan">Tipe
                                                                            Rujuk</label>
                                                                    </div>
                                                                    <div class="col-xl-8">
                                                                        <div class="form-group">
                                                                            <select class="select2 form-control w-100"
                                                                                id="tipe_rujukan" name="tipe_rujukan">
                                                                                <option value="rsu/rsk/rb">RSU/RSK/RB</option>
                                                                                <option value="puskesmas">PUSKESMAS</option>
                                                                                <option value="bidan/perawat">BIDAN/PERAWAT
                                                                                </option>
                                                                                <option value="dokter">DOKTER</option>
                                                                                <option value="dukun terlatih">DUKUN TERLATIH
                                                                                </option>
                                                                                <option value="kasus polisi">KASUS POLISI</option>
                                                                                <option value="klinik">KLINIK</option>
                                                                                <option value="lain-lain">LAIN-LAIN</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-12">
                                                            <div class="form-group mt-3">
                                                                <div class="row align-items-center">
                                                                    <div class="col-xl-4 text-right">
                                                                        <label class="form-label" for="nama perujuk">Nama
                                                                            Perujuk</label>
                                                                    </div>
                                                                    <div class="col-xl-8">
                                                                        <input type="text" class="form-control"
                                                                            id="nama_perujuk" name="nama_perujuk">
                                                                        @error('nama_perujuk')
                                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="form-group mt-3">
                                                                <div class="row align-items-center">
                                                                    <div class="col-xl-4 text-right">
                                                                        <label class="form-label" for="telp_perujuk">Telp/HP
                                                                            Perujuk</label>
                                                                    </div>
                                                                    <div class="col-xl-8">
                                                                        <input type="text" class="form-control"
                                                                            id="telp_perujuk" name="telp_perujuk">
                                                                        @error('telp_perujuk')
                                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="form-group mt-3">
                                                                <div class="row align-items-center">
                                                                    <div class="col-xl-4 text-right">
                                                                        <label class="form-label"
                                                                            for="alamat_perujuk">Alamat</label>
                                                                    </div>
                                                                    <div class="col-xl-8">
                                                                        <input type="text" class="form-control"
                                                                            id="alamat_perujuk" name="alamat_perujuk">
                                                                        @error('alamat_perujuk')
                                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-12 mt-4">
                                                    <div class="form-group">
                                                        <div class="row align-items-center">
                                                            <div class="col-xl-2 text-right">
                                                                <label class="form-label" for="diagnosa-awal">
                                                                    Diagnosa Awal
                                                                </label>
                                                            </div>
                                                            <div class="col-xl-10">
                                                                <textarea class="form-control" id="diagnosa-awal" name="diagnosa_awal" rows="5"></textarea>
                                                                @error('diagnosa_awal')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-12 mt-5">
                                                    <div class="row">
                                                        <div class="col-xl-6">
                                                            <a href="/patients/{{ $patient->id }}"
                                                                class="btn btn-lg btn-default waves-effect waves-themed">
                                                                <span class="fal fa-arrow-left mr-1 text-primary"></span>
                                                                <span class="text-primary">Kembali</span>
                                                            </a>
                                                        </div>
                                                        <div class="col-xl-6 text-right">
                                                            <button type="submit"
                                                                class="btn btn-lg btn-primary waves-effect waves-themed">
                                                                <span class="fal fa-save mr-1"></span>
                                                                Simpan
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    @break

                                    @case('igd')
                                        <div class="row">
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <div class="row align-items-center">
                                                        <div class="col-xl-4 text-right">
                                                            <label class="form-label" for="registration_date">
                                                                Tanggal Registrasi
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-8">
                                                            <input type="text"
                                                                style="border: 0; border-bottom: 1.9px dashed #aaa; margin-top: -.5rem; border-radius: 0"
                                                                class="form-control" id="registration_date" readonly
                                                                value="{{ $today }}" name="registration_date">
                                                            @error('registration_date')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row align-items-center">
                                                        <div class="col-xl-4 text-right">
                                                            <label class="form-label" for="registration_date">
                                                                Dokter
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-8">
                                                            <div class="form-group">
                                                                <select class="form-control w-100" id="dokter">
                                                                    <option selected></option>
                                                                    <optgroup label="Alaskan/Hawaiian Time Zone">
                                                                        <option value="AK">Alaska</option>
                                                                        <option value="HI">Hawaii</option>
                                                                    </optgroup>
                                                                    <optgroup label="Pacific Time Zone">
                                                                        <option value="CA">California</option>
                                                                        <option value="NV">Nevada</option>
                                                                        <option value="OR">Oregon</option>
                                                                        <option value="WA">Washington</option>
                                                                    </optgroup>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row align-items-center">
                                                        <div class="col-xl-4 text-right">
                                                            <label class="form-label" for="registration_type">
                                                                Tipe *
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-8">
                                                            <div class="form-group">
                                                                <select class="form-control w-100" id="type"
                                                                    name="registration_type">
                                                                    <option selected></option>
                                                                    <option value="AK">Alaska</option>
                                                                    <option value="HI">Hawaii</option>
                                                                    <option value="CA">California</option>
                                                                    <option value="NV">Nevada</option>
                                                                    <option value="OR">Oregon</option>
                                                                    <option value="WA">Washington</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row align-items-center">
                                                        <div class="col-xl-4 text-right">
                                                            <label class="form-label" for="penjamin">
                                                                Penjamin
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-8">
                                                            <div class="form-group">
                                                                <select class="select2 form-control w-100" id="penjamin"
                                                                    name="penjamin_id">
                                                                    <option selected></option>
                                                                    @foreach ($penjamins as $penjamin)
                                                                        <option value="{{ $penjamin->id }}"
                                                                            {{ $penjamin->id === old('penjamin') ? 'selected' : '' }}>
                                                                            {{ $penjamin->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <div class="row align-items-center">
                                                        <div class="col-xl-4 text-right">
                                                            <label class="form-label" for="registration_date">
                                                                Kartu Pasien
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-8">
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input"
                                                                    id="patient_card" name="patient_card">
                                                                <label class="custom-control-label" for="patient_card">Ya</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row align-items-center">
                                                        <div class="col-xl-4 text-right">
                                                            <label class="form-label" for="registration_date">
                                                                Rujukan
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-8">
                                                            <div class="custom-control custom-checkbox">
                                                                <div class="frame-wrap">
                                                                    <div
                                                                        class="custom-control custom-radio custom-control-inline p-0">
                                                                        <input type="radio" class="custom-control-input"
                                                                            id="inisiatif_pribadi" name="rujukan"
                                                                            value="inisiatif pribadi">
                                                                        <label class="custom-control-label"
                                                                            for="inisiatif_pribadi">Inisiatif
                                                                            Pribadi</label>
                                                                    </div>
                                                                    <div class="custom-control custom-radio custom-control-inline">
                                                                        <input type="radio" class="custom-control-input"
                                                                            id="dalam_rs" name="rujukan" value="dalam rs">
                                                                        <label class="custom-control-label" for="dalam_rs">Dalam
                                                                            RS</label>
                                                                    </div>
                                                                    <div class="custom-control custom-radio custom-control-inline">
                                                                        <input type="radio" class="custom-control-input"
                                                                            id="luar_rs" name="rujukan" value="luar rs">
                                                                        <label class="custom-control-label" for="luar_rs">Luar
                                                                            RS</label>
                                                                    </div>
                                                                    <div class="custom-control custom-radio custom-control-inline">
                                                                        <input type="radio" class="custom-control-input"
                                                                            id="rujukan_bpjs" name="rujukan"
                                                                            value="rujukan bpjs">
                                                                        <label class="custom-control-label"
                                                                            for="rujukan_bpjs">Rujukan
                                                                            BPJS</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group mt-3">
                                                        <div class="row align-items-center">
                                                            <div class="col-xl-4 text-right">
                                                                <label class="form-label" for="registration_date">
                                                                    Pelayanan *
                                                                </label>
                                                            </div>
                                                            <div class="col-xl-8">
                                                                <div class="form-group">
                                                                    <select class="form-control w-100" id="pelayanan">
                                                                        <option selected></option>
                                                                        <option value="AK">Alaska</option>
                                                                        <option value="HI">Hawaii</option>
                                                                        <option value="CA">California</option>
                                                                        <option value="NV">Nevada</option>
                                                                        <option value="OR">Oregon</option>
                                                                        <option value="WA">Washington</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="row align-items-center">
                                                        <div class="col-xl-4 text-right">
                                                            <label class="form-label" for="diagnosa_awal">
                                                                Diagnosa Awal
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-8">
                                                            <textarea class="form-control" id="diagnosa_awal" name="diagnosa_awal" rows="5"></textarea>
                                                            @error('diagnosa_awal')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-12 mt-5">
                                                <div class="row">
                                                    <div class="col-xl-6">
                                                        <a href="/patients/{{ $patient->id }}"
                                                            class="btn btn-lg btn-default waves-effect waves-themed">
                                                            <span class="fal fa-arrow-left mr-1 text-primary"></span>
                                                            <span class="text-primary">Kembali</span>
                                                        </a>
                                                    </div>
                                                    <div class="col-xl-6 text-right">
                                                        <button type="submit"
                                                            class="btn btn-lg btn-primary waves-effect waves-themed">
                                                            <span class="fal fa-save mr-1"></span>
                                                            Simpan
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @break

                                    @case('odc')
                                        <div class="row">
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <div class="row align-items-center">
                                                        <div class="col-xl-4 text-right">
                                                            <label class="form-label" for="registration_date">
                                                                Tanggal Registrasi
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-8">
                                                            <input type="text"
                                                                style="border: 0; border-bottom: 1.9px dashed #aaa; margin-top: -.5rem; border-radius: 0"
                                                                class="form-control" id="registration_date" readonly
                                                                value="{{ $today }}" name="registration_date">
                                                            @error('registration_date')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row align-items-center">
                                                        <div class="col-xl-4 text-right">
                                                            <label class="form-label" for="registration_date">
                                                                Dokter
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-8">
                                                            <div class="form-group">
                                                                <select class="form-control w-100" id="dokter">
                                                                    <option selected></option>
                                                                    <optgroup label="Alaskan/Hawaiian Time Zone">
                                                                        <option value="AK">Alaska</option>
                                                                        <option value="HI">Hawaii</option>
                                                                    </optgroup>
                                                                    <optgroup label="Pacific Time Zone">
                                                                        <option value="CA">California</option>
                                                                        <option value="NV">Nevada</option>
                                                                        <option value="OR">Oregon</option>
                                                                        <option value="WA">Washington</option>
                                                                    </optgroup>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row align-items-center">
                                                        <div class="col-xl-4 text-right">
                                                            <label class="form-label" for="registration_date">
                                                                Penjamin
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-8">
                                                            <div class="form-group">
                                                                <select class="select2 form-control w-100" id="penjamin"
                                                                    name="penjamin_id">
                                                                    <option selected></option>
                                                                    @foreach ($penjamins as $penjamin)
                                                                        <option value="{{ $penjamin->id }}"
                                                                            {{ $penjamin->id === old('penjamin') ? 'selected' : '' }}>
                                                                            {{ $penjamin->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row align-items-center">
                                                        <div class="col-xl-4 text-right">
                                                            <label class="form-label" for="diagnosa-awal">
                                                                Diagnosa Awal
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-8">
                                                            <textarea class="form-control" id="diagnosa-awal" name="diagnosa-awal" rows="5"></textarea>
                                                            @error('diagnosa-awal')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <div class="row align-items-center">
                                                        <div class="col-xl-4 text-right">
                                                            <label class="form-label" for="registration_date">
                                                                Kartu Pasien
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-8">
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input"
                                                                    id="patient_card" name="patient_card">
                                                                <label class="custom-control-label" for="patient_card">Ya</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row align-items-center">
                                                        <div class="col-xl-4 text-right">
                                                            <label class="form-label" for="registration_date">
                                                                Rujukan
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-8">
                                                            <div class="custom-control custom-checkbox">
                                                                <div class="frame-wrap">
                                                                    <div
                                                                        class="custom-control custom-radio custom-control-inline p-0">
                                                                        <input type="radio" class="custom-control-input"
                                                                            id="inisiatif_pribadi" name="rujukan">
                                                                        <label class="custom-control-label"
                                                                            for="inisiatif_pribadi">Inisiatif
                                                                            Pribadi</label>
                                                                    </div>
                                                                    <div class="custom-control custom-radio custom-control-inline">
                                                                        <input type="radio" class="custom-control-input"
                                                                            id="dalam_rs" name="rujukan">
                                                                        <label class="custom-control-label" for="dalam_rs">Dalam
                                                                            RS</label>
                                                                    </div>
                                                                    <div class="custom-control custom-radio custom-control-inline">
                                                                        <input type="radio" class="custom-control-input"
                                                                            id="luar_rs" name="rujukan">
                                                                        <label class="custom-control-label" for="luar_rs">Luar
                                                                            RS</label>
                                                                    </div>
                                                                    <div class="custom-control custom-radio custom-control-inline">
                                                                        <input type="radio" class="custom-control-input"
                                                                            id="rujukan_bpjs" name="rujukan">
                                                                        <label class="custom-control-label"
                                                                            for="rujukan_bpjs">Rujukan
                                                                            BPJS</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row align-items-center">
                                                        <div class="col-xl-4 text-right">
                                                            <label class="form-label" for="registration_date">
                                                                Kamar Tujuan
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-8">
                                                            <div class="custom-control custom-checkbox">
                                                                <div class="frame-wrap">
                                                                    <div
                                                                        class="custom-control custom-radio custom-control-inline p-0">
                                                                        <input type="radio" class="custom-control-input"
                                                                            id="OK" name="rujukan">
                                                                        <label class="custom-control-label"
                                                                            for="OK">OK</label>
                                                                    </div>
                                                                    <div class="custom-control custom-radio custom-control-inline">
                                                                        <input type="radio" class="custom-control-input"
                                                                            id="VK" name="rujukan">
                                                                        <label class="custom-control-label" for="VK">VK
                                                                            RS</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-12 mt-5">
                                                <div class="row">
                                                    <div class="col-xl-6">
                                                        <a href="/patients/{{ $patient->id }}"
                                                            class="btn btn-lg btn-default waves-effect waves-themed">
                                                            <span class="fal fa-arrow-left mr-1 text-primary"></span>
                                                            <span class="text-primary">Kembali</span>
                                                        </a>
                                                    </div>
                                                    <div class="col-xl-6 text-right">
                                                        <button type="submit"
                                                            class="btn btn-lg btn-primary waves-effect waves-themed">
                                                            <span class="fal fa-save mr-1"></span>
                                                            Simpan
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @break

                                    @case('rawat-inap')
                                        <div class="row">
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <div class="row align-items-center">
                                                        <div class="col-xl-4 text-right">
                                                            <label class="form-label" for="registration_date">
                                                                Tanggal Registrasi
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-8">
                                                            <input type="text"
                                                                style="border: 0; border-bottom: 1.9px dashed #aaa; margin-top: -.5rem; border-radius: 0"
                                                                class="form-control" id="registration_date" readonly
                                                                value="{{ $today }}" name="registration_date">
                                                            @error('registration_date')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row align-items-center">
                                                        <div class="col-xl-4 text-right">
                                                            <label class="form-label" for="registration_date">
                                                                Dokter
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-8">
                                                            <div class="form-group">
                                                                <select class="form-control w-100" id="dokter">
                                                                    <option selected></option>
                                                                    <optgroup label="Alaskan/Hawaiian Time Zone">
                                                                        <option value="AK">Alaska</option>
                                                                        <option value="HI">Hawaii</option>
                                                                    </optgroup>
                                                                    <optgroup label="Pacific Time Zone">
                                                                        <option value="CA">California</option>
                                                                        <option value="NV">Nevada</option>
                                                                        <option value="OR">Oregon</option>
                                                                        <option value="WA">Washington</option>
                                                                    </optgroup>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row align-items-center">
                                                        <div class="col-xl-4 text-right">
                                                            <label class="form-label" for="poliklinik">
                                                                Kelas / Kamar Rawat
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-8">
                                                            <div class="input-group bg-white shadow-inset-2">
                                                                <input type="text"
                                                                    class="form-control border-right-0 bg-transparent pr-0"
                                                                    placeholder="">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text bg-transparent border-left-0">
                                                                        <i class="fal fa-search" style="cursor: pointer"
                                                                            data-toggle="modal"
                                                                            data-target=".example-modal-default-transparent"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            @error('poliklinik')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row align-items-center">
                                                        <div class="col-xl-4 text-right">
                                                            <label class="form-label" for="registration_date">
                                                                Kartu Pasien
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-8">
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input"
                                                                    id="patient_card" name="patient_card">
                                                                <label class="custom-control-label" for="patient_card">Ya</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row align-items-center">
                                                        <div class="col-xl-4 text-right">
                                                            <label class="form-label" for="registration_date">
                                                                Prosedur Masuk
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-8">
                                                            <div class="custom-control custom-checkbox">
                                                                <div class="frame-wrap">
                                                                    <div
                                                                        class="custom-control custom-radio custom-control-inline p-0">
                                                                        <input type="radio" class="custom-control-input"
                                                                            id="rawat-jalan" name="prosedur_masuk">
                                                                        <label class="custom-control-label"
                                                                            for="rawat-jalan">Rawat
                                                                            Jalan</label>
                                                                    </div>
                                                                    <div class="custom-control custom-radio custom-control-inline">
                                                                        <input type="radio" class="custom-control-input"
                                                                            id="igd" name="prosedur_masuk">
                                                                        <label class="custom-control-label"
                                                                            for="igd">IGD</label>
                                                                    </div>
                                                                    <div class="custom-control custom-radio custom-control-inline">
                                                                        <input type="radio" class="custom-control-input"
                                                                            id="vk" name="prosedur_masuk">
                                                                        <label class="custom-control-label"
                                                                            for="vk">VK</label>
                                                                    </div>
                                                                    <div class="custom-control custom-radio custom-control-inline">
                                                                        <input type="radio" class="custom-control-input"
                                                                            id="ok" name="prosedur_masuk">
                                                                        <label class="custom-control-label"
                                                                            for="ok">OK</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <div class="row align-items-center">
                                                        <div class="col-xl-4 text-right">
                                                            <label class="form-label" for="registration_date">
                                                                Paket
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-8">
                                                            <div class="form-group">
                                                                <select class="form-control w-100" id="paket">
                                                                    <option selected></option>
                                                                    <option value="">Paket Skin Care</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row align-items-center">
                                                        <div class="col-xl-4 text-right">
                                                            <label class="form-label" for="registration_date">
                                                                Penjamin
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-8">
                                                            <div class="form-group">
                                                                <select class="select2 form-control w-100" id="penjamin"
                                                                    name="penjamin_id">
                                                                    <option selected></option>
                                                                    @foreach ($penjamins as $penjamin)
                                                                        <option value="{{ $penjamin->id }}"
                                                                            {{ $penjamin->id === old('penjamin') ? 'selected' : '' }}>
                                                                            {{ $penjamin->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row align-items-center">
                                                        <div class="col-xl-4 text-right">
                                                            <label class="form-label" for="registration_date">
                                                                Kelas Titipan
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-8">
                                                            <div class="form-group">
                                                                <select class="form-control w-100" id="type">
                                                                    <option></option>
                                                                    <option value="WA">On Call</option>
                                                                </select>
                                                                <i class="text-danger" style="font-size: 8pt;">
                                                                    Secara tarif kamar tetap mengikuti tarif kelas yang diinginkan
                                                                    pasien -> yaitu: Kelas Titipan dari
                                                                </i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row align-items-center">
                                                        <div class="col-xl-4 text-right">
                                                            <label class="form-label" for="registration_date">
                                                                Rujukan
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-8">
                                                            <div class="custom-control custom-checkbox">
                                                                <div class="frame-wrap">
                                                                    <div
                                                                        class="custom-control custom-radio custom-control-inline p-0">
                                                                        <input type="radio" class="custom-control-input"
                                                                            id="inisiatif_pribadi" name="rujukan">
                                                                        <label class="custom-control-label"
                                                                            for="inisiatif_pribadi">Inisiatif
                                                                            Pribadi</label>
                                                                    </div>
                                                                    <div class="custom-control custom-radio custom-control-inline">
                                                                        <input type="radio" class="custom-control-input"
                                                                            id="dalam_rs" name="rujukan">
                                                                        <label class="custom-control-label" for="dalam_rs">Dalam
                                                                            RS</label>
                                                                    </div>
                                                                    <div class="custom-control custom-radio custom-control-inline">
                                                                        <input type="radio" class="custom-control-input"
                                                                            id="luar_rs" name="rujukan">
                                                                        <label class="custom-control-label" for="luar_rs">Luar
                                                                            RS</label>
                                                                    </div>
                                                                    <div class="custom-control custom-radio custom-control-inline">
                                                                        <input type="radio" class="custom-control-input"
                                                                            id="rujukan_bpjs" name="rujukan">
                                                                        <label class="custom-control-label"
                                                                            for="rujukan_bpjs">Rujukan
                                                                            BPJS</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-12 mt-4">
                                                <div class="form-group">
                                                    <div class="row align-items-center">
                                                        <div class="col-xl-2 text-right">
                                                            <label class="form-label" for="diagnosa-awal">
                                                                Diagnosa Awal
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-10">
                                                            <textarea class="form-control" id="diagnosa-awal" name="diagnosa-awal" rows="5"></textarea>
                                                            @error('diagnosa-awal')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-12 mt-5">
                                                <div class="row">
                                                    <div class="col-xl-6">
                                                        <a href="/patients/{{ $patient->id }}"
                                                            class="btn btn-lg btn-default waves-effect waves-themed">
                                                            <span class="fal fa-arrow-left mr-1 text-primary"></span>
                                                            <span class="text-primary">Kembali</span>
                                                        </a>
                                                    </div>
                                                    <div class="col-xl-6 text-right">
                                                        <button type="submit"
                                                            class="btn btn-lg btn-primary waves-effect waves-themed">
                                                            <span class="fal fa-save mr-1"></span>
                                                            Simpan
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @break

                                    @case('laboratorium')
                                        Laboratorium
                                    @break

                                    @case('radiologi')
                                        Radiologi
                                    @break

                                    @case('hemodialisa')
                                        <div class="row">
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <div class="row align-items-center">
                                                        <div class="col-xl-4 text-right">
                                                            <label class="form-label" for="registration_date">
                                                                Tanggal Registrasi
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-8">
                                                            <input type="text"
                                                                style="border: 0; border-bottom: 1.9px dashed #aaa; margin-top: -.5rem; border-radius: 0"
                                                                class="form-control" id="registration_date" readonly
                                                                value="{{ $today }}" name="registration_date">
                                                            @error('registration_date')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row align-items-center">
                                                        <div class="col-xl-4 text-right">
                                                            <label class="form-label" for="registration_date">
                                                                Dokter
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-8">
                                                            <div class="form-group">
                                                                <select class="form-control w-100" id="dokter">
                                                                    <option selected></option>
                                                                    <optgroup label="Alaskan/Hawaiian Time Zone">
                                                                        <option value="AK">Alaska</option>
                                                                        <option value="HI">Hawaii</option>
                                                                    </optgroup>
                                                                    <optgroup label="Pacific Time Zone">
                                                                        <option value="CA">California</option>
                                                                        <option value="NV">Nevada</option>
                                                                        <option value="OR">Oregon</option>
                                                                        <option value="WA">Washington</option>
                                                                    </optgroup>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row align-items-center">
                                                        <div class="col-xl-4 text-right">
                                                            <label class="form-label" for="registration_date">
                                                                Penjamin
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-8">
                                                            <div class="form-group">
                                                                <select class="select2 form-control w-100" id="penjamin"
                                                                    name="penjamin_id">
                                                                    <option selected></option>
                                                                    @foreach ($penjamins as $penjamin)
                                                                        <option value="{{ $penjamin->id }}"
                                                                            {{ $penjamin->id === old('penjamin') ? 'selected' : '' }}>
                                                                            {{ $penjamin->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row align-items-center">
                                                        <div class="col-xl-4 text-right">
                                                            <label class="form-label" for="diagnosa-awal">
                                                                Diagnosa Awal
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-8">
                                                            <textarea class="form-control" id="diagnosa-awal" name="diagnosa-awal" rows="5"></textarea>
                                                            @error('diagnosa-awal')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <div class="row align-items-center">
                                                        <div class="col-xl-4 text-right">
                                                            <label class="form-label" for="registration_date">
                                                                Kartu Pasien
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-8">
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input"
                                                                    id="patient_card" name="patient_card">
                                                                <label class="custom-control-label" for="patient_card">Ya</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row align-items-center">
                                                        <div class="col-xl-4 text-right">
                                                            <label class="form-label" for="registration_date">
                                                                Rujukan
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-8">
                                                            <div class="custom-control custom-checkbox">
                                                                <div class="frame-wrap">
                                                                    <div
                                                                        class="custom-control custom-radio custom-control-inline p-0">
                                                                        <input type="radio" class="custom-control-input"
                                                                            id="inisiatif_pribadi" name="rujukan">
                                                                        <label class="custom-control-label"
                                                                            for="inisiatif_pribadi">Inisiatif
                                                                            Pribadi</label>
                                                                    </div>
                                                                    <div class="custom-control custom-radio custom-control-inline">
                                                                        <input type="radio" class="custom-control-input"
                                                                            id="dalam_rs" name="rujukan">
                                                                        <label class="custom-control-label" for="dalam_rs">Dalam
                                                                            RS</label>
                                                                    </div>
                                                                    <div class="custom-control custom-radio custom-control-inline">
                                                                        <input type="radio" class="custom-control-input"
                                                                            id="luar_rs" name="rujukan">
                                                                        <label class="custom-control-label" for="luar_rs">Luar
                                                                            RS</label>
                                                                    </div>
                                                                    <div class="custom-control custom-radio custom-control-inline">
                                                                        <input type="radio" class="custom-control-input"
                                                                            id="rujukan_bpjs" name="rujukan">
                                                                        <label class="custom-control-label"
                                                                            for="rujukan_bpjs">Rujukan
                                                                            BPJS</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row align-items-center">
                                                        <div class="col-xl-4 text-right">
                                                            <label class="form-label" for="registration_date">
                                                                Tipe Keperawatan
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-8">
                                                            <div class="custom-control custom-checkbox">
                                                                <div class="frame-wrap">
                                                                    <div
                                                                        class="custom-control custom-radio custom-control-inline p-0">
                                                                        <input type="radio" class="custom-control-input"
                                                                            id="bukan-paket" name="tipe_keperawatan"
                                                                            onclick="enableInput()">
                                                                        <label class="custom-control-label"
                                                                            for="bukan-paket">Bukan
                                                                            Paket /
                                                                            Terapi</label>
                                                                    </div>
                                                                    <div class="custom-control custom-radio custom-control-inline">
                                                                        <input type="radio" class="custom-control-input"
                                                                            id="paket-terapi" name="tipe_keperawatan"
                                                                            onclick="enableInput()">
                                                                        <label class="custom-control-label"
                                                                            for="paket-terapi">Paket
                                                                            /
                                                                            Terapi, Jml Terapi:</label>
                                                                    </div>
                                                                    <input style="width: 1cm;" name="qty" id="qty"
                                                                        type="text" disabled>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row align-items-center">
                                                        <div class="col-xl-4 text-right">
                                                            <label class="form-label" for="registration_date">
                                                                Tindakan *
                                                            </label>
                                                        </div>
                                                        <div class="col-xl-8">
                                                            <div class="form-group">
                                                                <select class="form-control w-100" id="tindakan">
                                                                    <option selected></option>
                                                                    <option value="AK">Alaska</option>
                                                                    <option value="HI">Hawaii</option>
                                                                    <option value="CA">California</option>
                                                                    <option value="NV">Nevada</option>
                                                                    <option value="OR">Oregon</option>
                                                                    <option value="WA">Washington</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-12 mt-5">
                                                <div class="row">
                                                    <div class="col-xl-6">
                                                        <a href="/patients/{{ $patient->id }}"
                                                            class="btn btn-lg btn-default waves-effect waves-themed">
                                                            <span class="fal fa-arrow-left mr-1 text-primary"></span>
                                                            <span class="text-primary">Kembali</span>
                                                        </a>
                                                    </div>
                                                    <div class="col-xl-6 text-right">
                                                        <button type="submit"
                                                            class="btn btn-lg btn-primary waves-effect waves-themed">
                                                            <span class="fal fa-save mr-1"></span>
                                                            Simpan
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @break

                                    @default
                                @endswitch

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade example-modal-default-transparent" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-transparent" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-white">
                            Basic Modals
                            <small class="m-0 text-white opacity-70">
                                Below is a static modal example
                            </small>
                        </h4>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="fal fa-times"></i></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        ...
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>

    </main>
@endsection
@section('plugin')
    <!-- JavaScript untuk menampilkan pop-up Edit -->
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(document).ready(function() {
            // poliklinik sesuai dokter yang dipilih
            $('#doctor_id').change(function() {
                var selectedDoctor = $(this).find('option:selected');
                var departement = selectedDoctor.data('departement') || '';
                $('#poliklinik').val(departement);
            });

            $('input[name="rujukan"]').change(function() {
                var value = $(this).val();

                // Tampilkan atau sembunyikan Dokter Perujuk
                if (value === 'dalam rs') {
                    $('#dokter_perujuk_container').removeClass('d-none');
                } else {
                    $('#dokter_perujuk_container').addClass('d-none');
                }

                // Tampilkan atau sembunyikan Luar dan Rujukan BPJS
                if (value === 'luar rs' || value === 'rujukan bpjs') {
                    $('#luar_dan_rujuk_bpjs_container').removeClass('d-none');
                } else {
                    $('#luar_dan_rujuk_bpjs_container').addClass('d-none');
                }
            });

            $(function() {
                $('.select2').select2();
                $('#dokter').select2();
                $('#penjamin').select2();
                $('#penjamin2').select2();
                $('#paket').select2();
                $('#pelayanan').select2();
                $('#type').select2();
                $('#tindakan').select2();
            });
        });

        function enableInput() {
            var radioPaket = document.getElementById("paket-terapi");
            var inputQty = document.getElementById("qty");

            if (radioPaket.checked) {
                inputQty.disabled = false; // Mengaktifkan input jika pilihan Paket / Terapi dipilih
            } else {
                inputQty.disabled = true; // Menonaktifkan input jika pilihan Bukan Paket / Terapi dipilih
            }
        }

        // Mendapatkan referensi tombol berdasarkan ID
        var button = document.getElementById('button');
        var identitas = document.getElementById('identitas');
        var kunjungan = document.getElementById('kunjungan');
        var width = window.screen.width;
        var height = window.screen.height;

        // Menambahkan event listener untuk tombol
        button.addEventListener('click', function() {
            // Membuka pop-up window saat tombol diklik
            window.open('{{ route('edit.pendaftaran.pasien', $patient->id) }}', '_blank', 'width=500' + width +
                ',height=' + height);
        });
        identitas.addEventListener('click', function() {
            // Membuka pop-up window saat tombol diklik
            window.open('{{ route('print.identitas.pasien', $patient->id) }}', '_blank', 'width=500' + width +
                ',height=' + height);
        });
        kunjungan.addEventListener('click', function() {
            // Membuka pop-up window saat tombol diklik
            window.open('{{ route('history.kunjungan.pasien', $patient->id) }}', '_blank', 'width=500' + width +
                ',height=' + height);
        });
    </script>
@endsection
