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
                                    {{-- Kolom Kiri Biodata --}}
                                    <div class="col-lg-6">
                                        {{-- Nama Lengkap --}}
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-sm-4" style="text-align: right">
                                                    <label for="name" class="form-label">Nama Lengkap *</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="text"
                                                        class="@error('name') is-invalid @enderror form-control" required
                                                        id="name" name="name" placeholder="Nama Lengkap Pasien"
                                                        value="{{ old('name') }}">
                                                    @error('name')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        {{-- Nama Panggilan --}}
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
                                        {{-- Title --}}
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-sm-4" style="text-align: right">
                                                    <label class="form-label" for="title">
                                                        Title *
                                                    </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <select class="@error('title') is-invalid @enderror form-control w-100"
                                                        required id="title" name="title">
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
                                        {{-- Jenis Kelamin --}}
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
                                                            required value="Laki-laki" id="laki-laki" name="gender"
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
                                        {{-- Tempat, Tgl. Lahir --}}
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
                                                                required value="{{ old('place') }}">
                                                            @error('place')
                                                                <p class="invalid-feedback">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="input-group">
                                                                <input type="text"
                                                                    class="form-control @error('date_of_birth') is-invalid @enderror"
                                                                    placeholder="dd-mm-yyyy" id="date_of_birth"
                                                                    name="date_of_birth" required
                                                                    value="{{ old('date_of_birth') }}" maxlength="10"
                                                                    autocomplete="off"
                                                                    oninput="
                                                                        let v = this.value.replace(/[^0-9]/g, '').slice(0,8);
                                                                        if(v.length >= 5){
                                                                            this.value = v.slice(0,2)+'-'+v.slice(2,4)+'-'+v.slice(4,8);
                                                                        }else if(v.length >= 3){
                                                                            this.value = v.slice(0,2)+'-'+v.slice(2,4)+(v.length > 4 ? '-'+v.slice(4,8) : '');
                                                                        }else{
                                                                            this.value = v;
                                                                        }
                                                                    ">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text fs-xl">
                                                                        <i class="fal fa-calendar-alt"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            @error('date_of_birth')
                                                                <p class="invalid-feedback d-block">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- Agama --}}
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
                                                        required id="religion" name="religion">
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
                                        {{-- Golongan Darah --}}
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
                                        {{-- Alergi --}}
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
                                        {{-- Status Pernikahan --}}
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
                                        {{-- Bahasa --}}
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-sm-4" style="text-align: right">
                                                    <label for="language" class="form-label">Bahasa *</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="text"
                                                        class="@error('language') is-invalid @enderror form-control"
                                                        id="language" placeholder="Pasien Menggunakan Bahasa" required
                                                        value="{{ old('language', 'Indonesia') }}" name="language">
                                                    @error('language')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        {{-- Kewarganegaraan --}}
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
                                    {{-- Kolom Kanan Biodata --}}
                                    <div class="col-lg-6">
                                        {{-- No KTP --}}
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
                                        {{-- Alamat --}}
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-sm-4" style="text-align: right">
                                                    <label for="address" class="form-label">Alamat *</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="text"
                                                        class="@error('address') is-invalid @enderror form-control"
                                                        id="address" placeholder="Alamat Pasien" name="address"
                                                        required value="{{ old('address') }}">
                                                    @error('address')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        {{-- Provinsi --}}
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-sm-4" style="text-align: right">
                                                    <label for="province" class="form-label">Provinsi *</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    {{-- Diisi oleh AJAX --}}
                                                    <select disabled
                                                        class="@error('province') is-invalid @enderror form-control w-100"
                                                        required id="province" name="province">
                                                        <option value="" selected></option>
                                                    </select>
                                                    @error('province')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        {{-- Kota/Kabupaten --}}
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-sm-4" style="text-align: right">
                                                    <label class="form-label" for="regency">Kota / Kabupaten
                                                        *</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    {{-- Diisi oleh AJAX --}}
                                                    <select disabled
                                                        class="@error('regency') is-invalid @enderror form-control w-100"
                                                        required id="regency" name="regency">
                                                        <option value="" disabled selected></option>
                                                    </select>
                                                    @error('regency')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        {{-- Kecamatan --}}
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-sm-4" style="text-align: right">
                                                    <label class="form-label" for="subdistrict">Kecamatan *</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    {{-- Diisi oleh AJAX --}}
                                                    <select disabled
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
                                        {{-- Kelurahan --}}
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-sm-4" style="text-align: right">
                                                    <label class="form-label" for="ward">Kelurahan *</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <select class="@error('ward') is-invalid @enderror form-control w-100"
                                                        id="ward" name="ward">
                                                        {{-- Opsi untuk old value agar bisa dipilih kembali --}}
                                                        @if (old('ward'))
                                                            <option value="{{ old('ward') }}" selected>Kelurahan Dipilih
                                                                Sebelumnya</option>
                                                        @endif
                                                    </select>
                                                    @error('ward')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        {{-- No HP --}}
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
                                                        name="mobile_phone_number" required
                                                        value="{{ old('mobile_phone_number') }}">
                                                    @error('mobile_phone_number')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        {{-- Email --}}
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
                                        {{-- Pendidikan Terakhir --}}
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
                                                        required id="last_education" name="last_education">
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
                                        {{-- Suku --}}
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
                                                        required id="ethnic" name="ethnic">
                                                        <option value="" selected disabled></option>
                                                        @foreach ($ethnics as $ethnic)
                                                            {{-- PERBAIKAN: sintaks 'selected' dipisah dari 'value' --}}
                                                            <option value="{{ $ethnic->id }}"
                                                                {{ old('ethnic') == $ethnic->id ? 'selected' : '' }}>
                                                                {{ $ethnic->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('ethnic')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        {{-- Pekerjaan --}}
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-sm-4" style="text-align: right">
                                                    <label class="form-label" for="job">
                                                        Pekerjaan *
                                                    </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <select class="@error('job') is-invalid @enderror form-control w-100"
                                                        required id="job" name="job">
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
                                    {{-- Kolom Kiri Penjamin --}}
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-sm-4" style="text-align: right">
                                                    <label for="guarantor_name" class="form-label">Nama Penjamin</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <select class="form-control w-100" id="guarantor_name"
                                                        name="penjamin_id">
                                                        @foreach ($penjamins as $penjamin)
                                                            {{-- MODIFIED: Menambahkan old() untuk penjamin --}}
                                                            <option value="{{ $penjamin->id }}"
                                                                {{ old('penjamin_id') == $penjamin->id ? 'selected' : '' }}>
                                                                {{ $penjamin->nama_perusahaan }}
                                                            </option>
                                                        @endforeach
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
                                                    {{-- MODIFIED: Menambahkan value old() --}}
                                                    <input type="text" class="form-control" id="nomor_penjamin"
                                                        placeholder="Nomor Polis / BPJS" name="nomor_penjamin"
                                                        value="{{ old('nomor_penjamin') }}">
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
                                                    {{-- MODIFIED: Menambahkan value old() --}}
                                                    <input type="text" class="form-control" id="nama_pegawai"
                                                        placeholder="Nama Pegawai (Pasien)" name="nama_pegawai"
                                                        value="{{ old('nama_pegawai') }}">
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
                                                    {{-- MODIFIED: Menambahkan value old() --}}
                                                    <input type="text" class="form-control" id="hubungan_pegawai"
                                                        placeholder="Hubungan Pasien" name="hubungan_pegawai"
                                                        value="{{ old('hubungan_pegawai') }}">
                                                    @error('hubungan_pegawai')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Kolom Kanan Penjamin --}}
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-sm-4" style="text-align: right">
                                                    <label for="company" class="form-label">Perusahaan</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    {{-- MODIFIED: Menambahkan value old() --}}
                                                    <input type="text" class="form-control"
                                                        id="nama_perusahaan_pegawai" placeholder="Nama Perusahaan"
                                                        name="nama_perusahaan_pegawai"
                                                        value="{{ old('nama_perusahaan_pegawai') }}">
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
                                                    {{-- MODIFIED: Menambahkan value old() --}}
                                                    <input type="text" class="form-control" id="nomor_kepegawaian"
                                                        placeholder="Nomor Kepegawaian Pasien" name="nomor_kepegawaian"
                                                        value="{{ old('nomor_kepegawaian') }}">
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
                                                    {{-- MODIFIED: Menambahkan value old() --}}
                                                    <input type="text" class="form-control" id="bagian_pegawai"
                                                        placeholder="Bagian" name="bagian_pegawai"
                                                        value="{{ old('bagian_pegawai') }}">
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
                                                    {{-- MODIFIED: Menambahkan value old() --}}
                                                    <input type="text" class="form-control" id="grup_pegawai"
                                                        placeholder="Grup" name="grup_pegawai"
                                                        value="{{ old('grup_pegawai') }}">
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
    {{-- (Script Javascript Anda tidak saya ubah, jadi saya hapus dari sini agar lebih ringkas) --}}
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(document).ready(function() {
            // Inisialisasi Bootstrap Datepicker
            $('#date_of_birth').datepicker({
                format: 'dd-mm-yyyy', // Format yang ditampilkan ke pengguna
                autoclose: true,
                todayHighlight: true,
                orientation: "bottom left" // Atur posisi popup
            });

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

        $(document).ready(function() {
            // Inisialisasi Select2 untuk Kelurahan
            $('#ward').select2({
                placeholder: 'Pilih Kelurahan',
                ajax: {
                    url: "{{ route('getKelurahan') }}", // Route untuk mendapatkan data kelurahan
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term // Kata kunci pencarian
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    id: item.id,
                                    text: item.name + ' - ' + item.kecamatan.name
                                };
                            })
                        };
                    },
                    cache: true
                }
            });

            // Fungsi untuk memuat data alamat berdasarkan ID Kelurahan
            function loadAddressData(wardId) {
                var subdistrict = $('#subdistrict');
                var regency = $('#regency');
                var province = $('#province');

                if (wardId) {
                    // Set status loading
                    subdistrict.prop('disabled', true).html('<option value="">Loading...</option>');
                    regency.prop('disabled', true).html('<option value="">Loading...</option>');
                    province.prop('disabled', true).html('<option value="">Loading...</option>');

                    $.ajax({
                        url: "{{ route('getKecamatanByKelurahan') }}", // Route untuk mendapatkan data lengkap
                        type: 'GET',
                        data: {
                            kelurahan_id: wardId
                        },
                        success: function(data) {
                            // Isi kecamatan
                            subdistrict.prop('disabled', false).empty();
                            subdistrict.append(new Option(data.kecamatan.name, data.kecamatan.id, true,
                                true));

                            // Isi kabupaten
                            regency.prop('disabled', false).empty();
                            regency.append(new Option(data.kabupaten.name, data.kabupaten.id, true,
                                true));

                            // Isi provinsi
                            province.prop('disabled', false).empty();
                            province.append(new Option(data.provinsi.name, data.provinsi.id, true,
                                true));
                        }
                    });
                }
            }

            // Event ketika kelurahan dipilih
            $('#ward').change(function() {
                var wardId = $(this).val();
                loadAddressData(wardId);
            });

            // Cek jika ada old value untuk 'ward', panggil fungsi load data saat halaman dimuat
            var oldWardId = "{{ old('ward') }}";
            if (oldWardId) {
                loadAddressData(oldWardId);
            }
        });
    </script>
@endsection
