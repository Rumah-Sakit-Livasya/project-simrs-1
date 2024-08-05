@extends('inc.layout')
@section('title', 'Pendaftaran Pasien Baru')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="subheader">
            @component('inc.subheader', ['subheader_title' => 'st_type_2', 'sh_icon' => 'home'])
                @slot('sh_descipt')
                    Your first page for content division
                @endslot
            @endcomponent
        </div>

        <form autocomplete="off" action="{{ route('simpan.pendaftaran.pasien') }}" method="post">
            @csrf
            <div class="row align-items-center">
                <div class="col-xl-12">
                    <div id="panel-1" class="panel">
                        <div class="panel-hdr bg-primary">
                            <h2 class="text-white">
                                Biodata<span class="fw-300"><i>Pasien</i></span>
                            </h2>
                        </div>
                        <div class="panel-container show">
                            <div class="panel-content">
                                <div class="row align-items-center">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-sm-4" style="text-align: right">
                                                    <label for="name" class="form-label">Nama Lengkap *</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="text"
                                                        class="@error('name') is-invalid @enderror form-control"
                                                        id="name" name="name" placeholder="Nama Lengkap Pasien"
                                                        value="{{ old('name') }}">
                                                    @error('name')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-sm-4" style="text-align: right">
                                                    <label for="nickname" name="nickname" class="form-label">Nama
                                                        Panggilan</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="text"
                                                        class="@error('nickname') is-invalid @enderror form-control"
                                                        id="nickname" placeholder="Nama Pangilan Pasien" name="nickname"
                                                        value="{{ old('nickname') }}">
                                                    @error('nickname')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-sm-4" style="text-align: right">
                                                    <label class="form-label" for="title">
                                                        Title *
                                                    </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <select class="@error('title') is-invalid @enderror form-control w-100"
                                                        id="title" name="title">
                                                        <option value="" disabled selected></option>
                                                        <option value="Tn."
                                                            {{ old('title') === 'Tn.' ? 'selected' : '' }}>
                                                            Tuan (Tn)
                                                        </option>
                                                        <option value="Ny."
                                                            {{ old('title') === 'Ny.' ? 'selected' : '' }}>
                                                            Nyonya (Ny)
                                                        </option>
                                                        <option value="Sdr."
                                                            {{ old('title') === 'Sdr.' ? 'selected' : '' }}>
                                                            Saudara (Sdr)
                                                        </option>
                                                        <option value="Sdri."
                                                            {{ old('title') === 'Sdri.' ? 'selected' : '' }}>
                                                            Saudari (Sdri)
                                                        </option>
                                                        <option value="An."
                                                            {{ old('title') === 'An.' ? 'selected' : '' }}>
                                                            Anak (An)
                                                        </option>
                                                        <option value="By."
                                                            {{ old('title') === 'By.' ? 'selected' : '' }}>
                                                            Bayi (By)
                                                        </option>
                                                    </select>
                                                    @error('title')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-sm-4" style="text-align: right">
                                                    <label class="form-label d-block" for="gender">
                                                        Jenis Kelamin *
                                                    </label>
                                                </div>
                                                <div class="col-sm-8 pl-3">
                                                    <div class="custom-control custom-radio d-inline mr-2">
                                                        <input type="radio"
                                                            class="@error('gender') is-invalid @enderror custom-control-input"
                                                            value="Laki-laki" id="laki-laki" name="gender"
                                                            {{ old('gender') == 'Laki-laki' ? 'checked' : '' }}>
                                                        <label class="custom-control-label"
                                                            for="laki-laki">Laki-Laki</label>
                                                    </div>
                                                    <div class="custom-control custom-radio d-inline">
                                                        <input type="radio"
                                                            class="@error('gender') is-invalid @enderror custom-control-input"
                                                            value="Perempuan" id="perempuan" name="gender"
                                                            {{ old('gender') == 'Perempuan' ? 'checked' : '' }}>
                                                        <label class="custom-control-label"
                                                            for="perempuan">Perempuan</label>
                                                        @error('gender')
                                                            <p class="invalid-feedback">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-sm-4" style="text-align: right">
                                                    <label for="" class="form-label">Tempat, Tgl. Lahir *</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <div class="row align-items-center">
                                                        <div class="col-lg-6">
                                                            <input type="text"
                                                                class="@error('place') is-invalid @enderror form-control"
                                                                id="place" placeholder="Tempat" name="place"
                                                                value="{{ old('place') }}">
                                                            @error('place')
                                                                <p class="invalid-feedback">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <input type="date"
                                                                class="@error('date_of_birth') is-invalid @enderror form-control"
                                                                id="date_of_birth" placeholder="Tanggal Lahir"
                                                                name="date_of_birth" value="{{ old('date_of_birth') }}">
                                                            @error('date_of_birth')
                                                                <p class="invalid-feedback">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-sm-4" style="text-align: right">
                                                    <label class="form-label" for="religion">
                                                        Agama *
                                                    </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <select
                                                        class="@error('religion') is-invalid @enderror form-control w-100"
                                                        id="religion" name="religion">
                                                        <option value="" disabled selected></option>
                                                        <option value="Islam"
                                                            {{ old('religion') === 'Islam' ? 'selected' : '' }}>
                                                            Islam
                                                        </option>
                                                        <option value="Kristen Protestan"
                                                            {{ old('religion') === 'Kristen Protestan' ? 'selected' : '' }}>
                                                            Kristen Protestan
                                                        </option>
                                                        <option value="Katholik"
                                                            {{ old('religion') === 'Katholik' ? 'selected' : '' }}>
                                                            Katholik
                                                        </option>
                                                        <option value="Budha"
                                                            {{ old('religion') === 'Budha' ? 'selected' : '' }}>
                                                            Budha
                                                        </option>
                                                        <option value="Hindu"
                                                            {{ old('religion') === 'Hindu' ? 'selected' : '' }}>
                                                            Hindu
                                                        </option>
                                                        <option value="Kong Hu Cu"
                                                            {{ old('religion') === 'Kong u Chu' ? 'selected' : '' }}>
                                                            Kong Hu Cu
                                                        </option>
                                                        <option value="Lain lain"
                                                            {{ old('religion') === 'Lain lain' ? 'selected' : '' }}>
                                                            Lain lain
                                                        </option>
                                                    </select>
                                                    @error('religion')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-sm-4" style="text-align: right">
                                                    <label class="form-label d-block"
                                                        style="margin-bottom: 1.4rem !important" id="blood_group">
                                                        Golongan Darah
                                                    </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <div class="custom-control custom-radio d-inline mr-4">
                                                        <input type="radio" class="custom-control-input" id="o"
                                                            name="blood_group" value="O"
                                                            {{ old('blood_group') === 'O' ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="o">O</label>
                                                    </div>
                                                    <div class="custom-control custom-radio d-inline mr-4">
                                                        <input type="radio" class="custom-control-input" id="a"
                                                            name="blood_group" value="A"
                                                            {{ old('blood_group') === 'A' ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="a">A</label>
                                                    </div>
                                                    <div class="custom-control custom-radio d-inline mr-4">
                                                        <input type="radio" class="custom-control-input" id="b"
                                                            name="blood_group" value="B"
                                                            {{ old('blood_group') === 'B' ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="b">B</label>
                                                    </div>
                                                    <div class="custom-control custom-radio d-inline mr-4">
                                                        <input type="radio" class="custom-control-input" id="ab"
                                                            name="blood_group" value="AB"
                                                            {{ old('blood_group') === 'AB' ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="ab">AB</label>
                                                    </div>
                                                    @error('religion')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-sm-4" style="text-align: right">
                                                    <label for="allergy" class="form-label">Alergi</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="text"
                                                        class="@error('allergy') is-invalid @enderror form-control"
                                                        id="allergy" placeholder="Alergi Pasien" name="allergy"
                                                        value="{{ old('allergy') }}">
                                                    @error('allergy')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-sm-4" style="text-align: right">
                                                    <label class="form-label mr-5 d-block"
                                                        style="margin-bottom: 1.4rem !important">
                                                        Status Pernikahan
                                                    </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <div class="custom-control custom-radio d-inline mr-4">
                                                        <input type="radio" class="custom-control-input"
                                                            id="belum_menikah" name="married_status"
                                                            value="Belum Menikah"
                                                            {{ old('married_status') === 'Belum Menikah' ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="belum_menikah">Belum
                                                            Menikah</label>
                                                    </div>
                                                    <div class="custom-control custom-radio d-inline mr-4">
                                                        <input type="radio" class="custom-control-input" id="menikah"
                                                            name="married_status" value="Menikah"
                                                            {{ old('married_status') === 'Menikah' ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="menikah">Menikah</label>
                                                    </div>
                                                    <div class="custom-control custom-radio d-inline mr-4">
                                                        <input type="radio" class="custom-control-input" id="janda"
                                                            name="married_status" value="Janda"
                                                            {{ old('married_status') === 'Janda' ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="janda">Janda</label>
                                                    </div>
                                                    <div class="custom-control custom-radio d-inline mr-4">
                                                        <input type="radio" class="custom-control-input" id="duda"
                                                            name="married_status" value="Duda"
                                                            {{ old('married_status') === 'Duda' ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="duda">Duda</label>
                                                    </div>
                                                    @error('married_status')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-sm-4" style="text-align: right">
                                                    <label for="language" class="form-label">Bahasa *</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="text"
                                                        class="@error('language') is-invalid @enderror form-control"
                                                        id="language" placeholder="Pasien Menggunakan Bahasa"
                                                        value="{{ old('language', 'Indonesia') }}" name="language">
                                                    @error('language')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-sm-4" style="text-align: right">
                                                    <label for="citizenship" class="form-label">Kewarganegaraan</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="text"
                                                        class="@error('citizenship') is-invalid @enderror form-control"
                                                        id="citizenship" placeholder="Kewarganegaraan Pasien"
                                                        value="{{ old('citizenship', 'Indonesia') }}" name="citizenship">
                                                    @error('citizenship')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-sm-4" style="text-align: right">
                                                    <label for="id_card" class="form-label">No. KTP/SIM/Paspor</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="text"
                                                        class="@error('id_card') is-invalid @enderror form-control"
                                                        id="id_card" placeholder="No. KTP/SIM/Paspor Pasien"
                                                        name="id_card" value="{{ old('id_card') }}">
                                                    @error('id_card')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-sm-4" style="text-align: right">
                                                    <label for="address" class="form-label">Alamat *</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="text"
                                                        class="@error('address') is-invalid @enderror form-control"
                                                        id="address" placeholder="Alamat Pasien" name="address"
                                                        value="{{ old('address') }}">
                                                    @error('address')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-sm-4" style="text-align: right">
                                                    <label for="province" class="form-label">Provinsi</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <select
                                                        class="@error('province') is-invalid @enderror form-control w-100"
                                                        id="province" name="province">
                                                        <option value="" disabled selected></option>
                                                        @foreach ($provinces as $province)
                                                            <option
                                                                value="{{ $province['id'] }} 
                                                        {{ old('province') == $province['id'] ? 'selected' : '' }}">
                                                                {{ $province['nama'] }}
                                                                {{ $province['id'] }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('province')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-sm-4" style="text-align: right">
                                                    <label class="form-label" for="regency">Kota / Kabupaten
                                                        *</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <select
                                                        class="@error('regency') is-invalid @enderror form-control w-100"
                                                        id="regency" name="regency">
                                                        <option value="" disabled selected></option>
                                                    </select>
                                                    @error('regency')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-sm-4" style="text-align: right">
                                                    <label class="form-label" for="subdistrict">Kecamatan *</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <select
                                                        class="@error('subdistrict') is-invalid @enderror form-control w-100"
                                                        id="subdistrict" name="subdistrict">
                                                        <option value="" disabled selected></option>
                                                    </select>
                                                    @error('subdistrict')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-sm-4" style="text-align: right">
                                                    <label class="form-label" for="ward">Kelurahan *</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <select class="@error('ward') is-invalid @enderror form-control w-100"
                                                        id="ward" name="ward">
                                                        <option value="" disabled selected></option>
                                                    </select>
                                                    @error('ward')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-sm-4" style="text-align: right">
                                                    <label for="mobile_phone_number" class="form-label">No. HP/Telp
                                                        *</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="text"
                                                        class="@error('mobile_phone_number') is-invalid @enderror form-control"
                                                        id="mobile_phone_number" placeholder="No. HP / Telp Pasien"
                                                        name="mobile_phone_number"
                                                        value="{{ old('mobile_phone_number') }}">
                                                    @error('mobile_phone_number')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-sm-4" style="text-align: right">
                                                    <label for="email"
                                                        class="@error('email') is-invalid @enderror form-label">Email</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="email" class="form-control" id="email"
                                                        placeholder="Alamat Email Pasien" name="email"
                                                        value="{{ old('email') }}">
                                                    @error('email')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-sm-4" style="text-align: right">
                                                    <label class="form-label" for="last_education">
                                                        Pendidikan Terakhir *
                                                    </label>
                                                </div>
                                                <div class="col-sm-8">

                                                    <select
                                                        class="@error('last_education') is-invalid @enderror form-control w-100"
                                                        id="last_education" name="last_education">
                                                        <option value="" disabled selected></option>
                                                        <option value="Tidak Sekolah"
                                                            {{ old('last_education') === 'Tidak Sekolah' ? 'selected' : '' }}>
                                                            Tidak Sekolah</option>
                                                        <option value="Belum / Tidak tamat SD"
                                                            {{ old('last_education') === 'Belum / Tidak tamat SD' ? 'selected' : '' }}>
                                                            Belum / Tidak tamat SD
                                                        </option>
                                                        <option value="Tamat SD"
                                                            {{ old('last_education') === 'Tamat SD' ? 'selected' : '' }}>
                                                            Tamat SD</option>
                                                        <option value="Tamat SMTP"
                                                            {{ old('last_education') === 'Tamat SMTP' ? 'selected' : '' }}>
                                                            Tamat SMTP</option>
                                                        <option value="Tamat SLTA"
                                                            {{ old('last_education') === 'Tamat SLTA' ? 'selected' : '' }}>
                                                            Tamat SLTA</option>
                                                        <option value="Tamat D3"
                                                            {{ old('last_education') === 'Tamat D3' ? 'selected' : '' }}>
                                                            Tamat D3</option>
                                                        <option value="Tamat S1"
                                                            {{ old('last_education') === 'Tamat S1' ? 'selected' : '' }}>
                                                            Tamat S1</option>
                                                        <option value="Tamat S2"
                                                            {{ old('last_education') === 'Tamat S2' ? 'selected' : '' }}>
                                                            Tamat S2</option>
                                                        <option value="Tamat PT"
                                                            {{ old('last_education') === 'Tamat PT' ? 'selected' : '' }}>
                                                            Tamat PT</option>
                                                    </select>
                                                    @error('last_education')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-sm-4" style="text-align: right">
                                                    <label class="form-label" for="ethnic">
                                                        Suku / Etnis *
                                                    </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <select
                                                        class="@error('ethnic') is-invalid @enderror form-control w-100"
                                                        id="ethnic" name="ethnic">
                                                        <option value="" selected disabled></option>
                                                        @foreach ($ethnics as $ethnic)
                                                            <option
                                                                value="{{ $ethnic->id }} {{ old('ethnic') === $ethnic->id ? 'selected' : '' }}">
                                                                {{ $ethnic->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('ethnic')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-sm-4" style="text-align: right">
                                                    <label class="form-label" for="job">
                                                        Pekerjaan *
                                                    </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <select class="@error('job') is-invalid @enderror form-control w-100"
                                                        id="job" name="job">
                                                        <option value="" disabled selected></option>
                                                        <option value="Belum Bekerja"
                                                            {{ old('job') === 'Belum Bekerja' ? 'selected' : '' }}>
                                                            Belum Bekerja</option>
                                                        <option value="Tidak Bekerja"
                                                            {{ old('job') === 'Tidak Bekerja' ? 'selected' : '' }}>
                                                            Tidak Bekerja</option>
                                                        <option value="Ibu Rumah Tangga"
                                                            {{ old('job') === 'Ibu Rumah Tangga' ? 'selected' : '' }}>
                                                            Ibu Rumah Tangga</option>
                                                        <option value="Petani"
                                                            {{ old('job') === 'Petani' ? 'selected' : '' }}>Petani</option>
                                                        <option value="Nelayan"
                                                            {{ old('job') === 'Nelayan' ? 'selected' : '' }}>Nelayan
                                                        </option>
                                                        <option value="Buruh Harian"
                                                            {{ old('job') === 'Buruh Harian' ? 'selected' : '' }}>
                                                            Buruh Harian</option>
                                                        <option value="PNS"
                                                            {{ old('job') === 'PNS' ? 'selected' : '' }}>
                                                            PNS
                                                        </option>
                                                        <option value="BUMN"
                                                            {{ old('job') === 'BUMN' ? 'selected' : '' }}>
                                                            BUMN</option>
                                                        <option value="POLRI"
                                                            {{ old('job') === 'POLRI' ? 'selected' : '' }}>
                                                            POLRI</option>
                                                        <option value="TNI"
                                                            {{ old('job') === 'TNI' ? 'selected' : '' }}>
                                                            TNI
                                                        </option>
                                                        <option value="Swasta / Karyawan Kontrak"
                                                            {{ old('job') === 'Swasta / Karyawan Kontrak' ? 'selected' : '' }}>
                                                            Swasta / Karyawan
                                                            Kontrak
                                                        </option>
                                                        <option value="Wirausaha"
                                                            {{ old('job') === 'Wirausaha' ? 'selected' : '' }}>
                                                            Wirausaha</option>
                                                        <option value="Lain-lain"
                                                            {{ old('job') === 'Lain-lain' ? 'selected' : '' }}>
                                                            Lain-lain</option>
                                                    </select>
                                                    @error('job')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-12">
                    <div id="panel-1" class="panel">
                        <div class="panel-hdr bg-info-500">
                            <h2 class="text-white">
                                Informasi<span class="fw-300"><i>Keluarga</i></span>
                            </h2>
                        </div>
                        <div class="panel-container show">
                            <div class="panel-content">
                                <div class="row align-items-center">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-sm-4" style="text-align: right">
                                                    <label for="family_name" class="form-label">Nama Keluarga</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="family_name"
                                                        placeholder="Nama Keluarga yang bisa dihubungi" name="family_name"
                                                        value="{{ old('family_name') }}">
                                                    @error('family_name')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-sm-4" style="text-align: right">
                                                    <label for="family_age" class="form-label">Usia</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="family_age"
                                                        placeholder="Usia" name="family_age"
                                                        value="{{ old('family_age') }}">
                                                    @error('family_age')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-sm-4" style="text-align: right">
                                                    <label for="family_job" class="form-label">Pekerjaan</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="family_job"
                                                        placeholder="Pekerjaan" name="family_job"
                                                        value="{{ old('family_job') }}">
                                                    @error('family_job')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-sm-4" style="text-align: right">
                                                    <label for="father_name" class="form-label">Nama Ayah</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="father_name"
                                                        placeholder="Nama Ayah Pasien" name="father_name"
                                                        value="{{ old('father_name') }}">
                                                    @error('father_name')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-sm-4" style="text-align: right">
                                                    <label for="family_relation" class="form-label">Hubungan
                                                        Keluarga</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <select class="form-control w-100" id="family_relationship"
                                                        name="family_relation">
                                                        <option value="" selected></option>
                                                        <option value="Ibu"
                                                            {{ old('family_relation') === 'Ibu' ? 'selected' : '' }}>
                                                            Ibu</option>
                                                        <option value="Ayah"
                                                            {{ old('family_relation') === 'Ayah' ? 'selected' : '' }}>
                                                            Ayah</option>
                                                        <option value="Suami"
                                                            {{ old('family_relation') === 'Suami' ? 'selected' : '' }}>
                                                            Suami</option>
                                                        <option value="Istri"
                                                            {{ old('family_relation') === 'Istri' ? 'selected' : '' }}>
                                                            Istri</option>
                                                        <option value="Saudara Kandung Laki-laki"
                                                            {{ old('family_relation') === 'Saudara Kandung Laki-laki' ? 'selected' : '' }}>
                                                            Saudara Kandung Laki-laki
                                                        </option>
                                                        <option value="Saudara Kandung Perempuan"
                                                            {{ old('family_relation') === 'Saudara Kandung Perempuan' ? 'selected' : '' }}>
                                                            Saudara Kandung Perempuan
                                                        </option>
                                                        <option value="Anak"
                                                            {{ old('family_relation') === 'Anak' ? 'selected' : '' }}>
                                                            Anak</option>
                                                        <option value="Lainnya"
                                                            {{ old('family_relation') === 'Lainnya' ? 'selected' : '' }}>
                                                            Lainnya</option>
                                                    </select>
                                                    @error('family_relation')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-sm-4" style="text-align: right">
                                                    <label for="family_number" class="form-label">No. HP /
                                                        Telp</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="family_number"
                                                        placeholder="No. HP / Telp Keluarga" name="family_number"
                                                        value="{{ old('family_number') }}">
                                                    @error('family_number')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-sm-4" style="text-align: right">
                                                    <label for="family_address" class="form-label">Alamat</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="family_address"
                                                        placeholder="Alamat Keluarga" name="family_address"
                                                        value="{{ old('family_address') }}">
                                                    @error('family_address')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-sm-4" style="text-align: right">
                                                    <label for="mother_name" class="form-label">Nama Ibu</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="mother_name"
                                                        placeholder="Nama Ibu Pasien" name="mother_name"
                                                        value="{{ old('mother_name') }}">
                                                    @error('mother_name')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-12">
                    <div id="panel-1" class="panel">
                        <div class="panel-hdr bg-primary-500">
                            <h2 class="text-white">
                                Informasi<span class="fw-300"><i>Penjamin</i></span>
                            </h2>
                        </div>
                        <div class="panel-container show">
                            <div class="panel-content">
                                <div class="row align-items-center">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-sm-4" style="text-align: right">
                                                    <label for="guarantor_name" class="form-label">Nama Penjamin</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <select class="form-control w-100" id="guarantor_name"
                                                        name="penjamin_id">
                                                        <option value="" disabled selected></option>
                                                        <option value="1">BPJS KESEHATAN</option>
                                                        <option value="2">ASURANSI</option>
                                                        <option value="3">UMUM</option>
                                                    </select>
                                                    @error('penjamin_id')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-sm-4" style="text-align: right">
                                                    <label for="nomor_penjamin" class="form-label">No. Polis /
                                                        BPJS</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="nomor_penjamin"
                                                        placeholder="Nomor Polis / BPJS" name="nomor_penjamin">
                                                    @error('nomor_penjamin')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-sm-4" style="text-align: right">
                                                    <label for="employee_name" class="form-label">Nama Pegawai</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="nama_pegawai"
                                                        placeholder="Nama Pegawai (Pasien)" name="nama_pegawai">
                                                    @error('nama_pegawai')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-sm-4" style="text-align: right">
                                                    <label for="patient_relationship" class="form-label">Hubungan
                                                        Pegawai</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="hubungan_pegawai"
                                                        placeholder="Hubungan Pasien" name="hubungan_pegawai">
                                                    @error('hubungan_pegawai')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-sm-4" style="text-align: right">
                                                    <label for="company" class="form-label">Perusahaan</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control"
                                                        id="nama_perusahaan_pegawai" placeholder="Nama Perusahaan"
                                                        name="nama_perusahaan_pegawai">
                                                    @error('nama_perusahaan_pegawai')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-sm-4" style="text-align: right">
                                                    <label for="nomor_kepegawaian" class="form-label">No. Pegawai</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="nomor_kepegawaian"
                                                        placeholder="Nomor Kepegawaian Pasien" name="nomor_kepegawaian">
                                                    @error('nomor_kepegawaian')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-sm-4" style="text-align: right">
                                                    <label for="bagian_pegawai" class="form-label">Bagian</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="bagian_pegawai"
                                                        placeholder="Bagian" name="bagian_pegawai">
                                                    @error('bagian_pegawai')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-sm-4" style="text-align: right">
                                                    <label for="grup_pegawai" class="form-label">Grup</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="grup_pegawai"
                                                        placeholder="Grup" name="grup_pegawai">
                                                    @error('grup_pegawai')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row align-items-center">
                <div class="col-6">
                    <a href="{{ route('pendaftaran.pasien.daftar_rm') }}"
                        class="btn btn-lg btn-outline-primary waves-effect waves-themed">
                        <span class="fal fa-arrow-left mr-1"></span>
                        Kembali
                    </a>
                </div>
                <div class="col-5" style="text-align: right">
                    <button type="submit" class="btn btn-lg btn-primary waves-effect waves-themed">
                        <span class="fal fa-user-plus mr-1"></span>
                        Simpan
                    </button>
                </div>
            </div>

        </form>

    </main>
@endsection
@section('plugin')
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(document).ready(function() {
            $('#title').select2({
                'placeholder': 'Pilih Title',
            });

            $('#religion').select2({
                'placeholder': 'Pilih Agama',
            });

            $('#subdistrict').select2({
                'placeholder': 'Pilih Kecamatan',
            });

            $('#ward').select2({
                'placeholder': 'Pilih Kelurahan',
            });

            $('#regency').select2({
                'placeholder': 'Pilih Kabupaten',
            });

            $('#province').select2({
                'placeholder': 'Pilih Provinsi',
            });

            $('#last_education').select2({
                'placeholder': 'Pilih Pendidikan Terakhir',
            });

            $('#ethnic').select2({
                'placeholder': 'Pilih Suku / Etnis',
            });

            $('#job').select2({
                'placeholder': 'Pilih Pekerjaan',
            });

            $('#guarantor_name').select2({
                'placeholder': 'Pilih Penjamin',
            });

            $('#family_relationship').select2({
                'placeholder': 'Pilih Hubungan Keluarga',
            });
        });
    </script>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#province').change(function() {
            var provinceId = $(this).val();
            if (provinceId) {
                $('#regency').prop('disabled', true);
                $('#regency').html('<option value="">Loading...</option>');

                $.ajax({
                    url: 'https://dev.farizdotid.com/api/daerahindonesia/kota?id_provinsi=' +
                        provinceId,
                    type: 'GET',
                    success: function(response) {
                        if (response.kota_kabupaten && response.kota_kabupaten.length > 0) {
                            $('#regency').prop('disabled', false);
                            var options = '<option value="">Pilih Kota/Kabupaten</option>';
                            response.kota_kabupaten.forEach(function(city) {
                                options += '<option value="' + city.id + '">' + city
                                    .nama + '</option>';
                            });
                            $('#regency').html(options);
                            $('#subdistrict').html(
                                '<option value="">Pilih Kecamatan</option>');
                            $('#subdistrict').html(
                                '<option value="">Pilih Kelurahan</option>');
                        } else {
                            $('#regency').html('<option value="">No cities found</option>');
                            $('#subdistrict').html(
                                '<option value="">Pilih Kecamatan</option>');
                            $('#ward').html('<option value="">Pilih Kelurahan</option>');
                        }
                    }
                });
            } else {
                $('#regency').prop('disabled', true);
                $('#regency').html('<option value="">Pilih Kota/Kabupaten</option>');
                $('#subdistrict').html('<option value="">Pilih Kecamatan</option>');
                $('#ward').html('<option value="">Pilih Kelurahan</option>');
            }
        });

        $('#regency').change(function() {
            var cityId = $(this).val();
            if (cityId) {
                console.log(cityId);
                $('#subdistrict').prop('disabled', true);
                $('#subdistrict').html('<option value="">Loading...</option>');

                $.ajax({
                    url: 'https://dev.farizdotid.com/api/daerahindonesia/kecamatan?id_kota=' +
                        cityId,
                    type: 'GET',
                    success: function(response) {
                        if (response.kecamatan && response.kecamatan.length > 0) {
                            $('#subdistrict').prop('disabled', false);
                            var options = '<option value="">Pilih Kota/Kabupaten</option>';
                            response.kecamatan.forEach(function(subdistrict) {
                                options += '<option value="' + subdistrict.id +
                                    '">' + subdistrict.nama + '</option>';
                            });
                            $('#subdistrict').html(options);
                            $('#ward').html('<option value="">Pilih Kecamatan</option>');
                            $('#ward').html('<option value="">Pilih Kelurahan</option>');
                        } else {
                            $('#subdistrict').html(
                                '<option value="">No districts found</option>');
                            $('#ward').html('<option value="">Pilih Kelurahan</option>');
                        }
                    }
                });
            } else {
                $('#subdistrict').prop('disabled', true);
                $('#subdistrict').html('<option value="">Pilih Kecamatan</option>');
                $('#ward').html('<option value="">Pilih Kelurahan</option>');
            }
        });

        $('#subdistrict').change(function() {
            var subDistrictId = $(this).val();
            console.log(subDistrictId);
            if (subDistrictId) {
                $('#ward').prop('disabled', true);
                $('#ward').html('<option value="">Loading...</option>');

                $.ajax({
                    url: 'https://dev.farizdotid.com/api/daerahindonesia/kelurahan?id_kecamatan=' +
                        subDistrictId,
                    type: 'GET',
                    success: function(response) {
                        if (response.kelurahan && response.kelurahan.length > 0) {
                            $('#ward').prop('disabled', false);
                            var options = '<option value="">Pilih Kelurahan</option>';
                            response.kelurahan.forEach(function(ward) {
                                options += '<option value="' + ward.id + '">' + ward
                                    .nama + '</option>';
                            });
                            $('#ward').html(options);
                        } else {
                            $('#ward').html('<option value="">No wards found</option>');
                        }
                    }
                });
            } else {
                $('#ward').prop('disabled', true);
                $('#ward').html('<option value="">Pilih Kelurahan</option>');
            }
        });
    });
</script>
