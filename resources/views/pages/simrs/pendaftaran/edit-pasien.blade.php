@extends('inc.layout-no-side')
@section('title', 'Edit Data Pasien')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        {{-- Header --}}
        <div class="subheader">
            @component('inc.subheader', ['subheader_title' => 'st_type_2', 'sh_icon' => 'home'])
                @slot('sh_descipt')
                    Halaman untuk mengubah data pasien
                @endslot
            @endcomponent
        </div>

        {{-- PERBAIKAN: Action form diubah ke route update --}}
        <form autocomplete="off" action="{{ route('update.pendaftaran.pasien', $patient->id) }}" method="post">
            @csrf
            @method('PUT') {{-- Tambahkan method PUT untuk update --}}
            <div class="row align-items-center">
                {{-- Panel Biodata Pasien --}}
                <div class="col-xl-12">
                    <div id="panel-1" class="panel">
                        <div class="panel-hdr bg-primary">
                            <h2 class="text-white">
                                Biodata<span class="fw-300"><i>Pasien</i></span>
                            </h2>
                        </div>
                        <div class="panel-container show">
                            <div class="panel-content">
                                {{-- Konten Biodata (sudah benar, tidak ada perubahan signifikan) --}}
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
                                                        required value="{{ old('name', $patient->name) }}">
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
                                                        value="{{ old('nickname', $patient->nickname) }}">
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
                                                        required id="title" name="title">
                                                        <option value="" disabled></option>
                                                        <option value="Tn."
                                                            {{ old('title', $patient->title) === 'Tn.' ? 'selected' : '' }}>
                                                            Tuan (Tn)
                                                        </option>
                                                        <option value="Ny."
                                                            {{ old('title', $patient->title) === 'Ny.' ? 'selected' : '' }}>
                                                            Nyonya (Ny)
                                                        </option>
                                                        <option value="Sdr."
                                                            {{ old('title', $patient->title) === 'Sdr.' ? 'selected' : '' }}>
                                                            Saudara (Sdr)
                                                        </option>
                                                        <option value="Sdri."
                                                            {{ old('title', $patient->title) === 'Sdri.' ? 'selected' : '' }}>
                                                            Saudari (Sdri)
                                                        </option>
                                                        <option value="An."
                                                            {{ old('title', $patient->title) === 'An.' ? 'selected' : '' }}>
                                                            Anak (An)
                                                        </option>
                                                        <option value="By."
                                                            {{ old('title', $patient->title) === 'By.' ? 'selected' : '' }}>
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
                                                        <input type="radio" required
                                                            class="@error('gender') is-invalid @enderror custom-control-input"
                                                            value="Laki-laki" id="laki-laki" name="gender"
                                                            {{ old('gender', $patient->gender) == 'Laki-laki' ? 'checked' : '' }}>
                                                        <label class="custom-control-label"
                                                            for="laki-laki">Laki-Laki</label>
                                                    </div>
                                                    <div class="custom-control custom-radio d-inline">
                                                        <input type="radio"
                                                            class="@error('gender') is-invalid @enderror custom-control-input"
                                                            value="Perempuan" id="perempuan" name="gender"
                                                            {{ old('gender', $patient->gender) == 'Perempuan' ? 'checked' : '' }}>
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
                                                                required id="place" placeholder="Tempat"
                                                                name="place"
                                                                value="{{ old('place', $patient->place) }}">
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
                                                        <option value="" disabled></option>
                                                        <option value="Islam"
                                                            {{ old('religion', $patient->religion) === 'Islam' ? 'selected' : '' }}>
                                                            Islam
                                                        </option>
                                                        <option value="Kristen Protestan"
                                                            {{ old('religion', $patient->religion) === 'Kristen Protestan' ? 'selected' : '' }}>
                                                            Kristen Protestan
                                                        </option>
                                                        <option value="Katholik"
                                                            {{ old('religion', $patient->religion) === 'Katholik' ? 'selected' : '' }}>
                                                            Katholik
                                                        </option>
                                                        <option value="Budha"
                                                            {{ old('religion', $patient->religion) === 'Budha' ? 'selected' : '' }}>
                                                            Budha
                                                        </option>
                                                        <option value="Hindu"
                                                            {{ old('religion', $patient->religion) === 'Hindu' ? 'selected' : '' }}>
                                                            Hindu
                                                        </option>
                                                        <option value="Kong Hu Cu"
                                                            {{ old('religion', $patient->religion) === 'Kong Hu Cu' ? 'selected' : '' }}>
                                                            Kong Hu Cu
                                                        </option>
                                                        <option value="Lain lain"
                                                            {{ old('religion', $patient->religion) === 'Lain lain' ? 'selected' : '' }}>
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
                                                            {{ old('blood_group', $patient->blood_group) === 'O' ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="o">O</label>
                                                    </div>
                                                    <div class="custom-control custom-radio d-inline mr-4">
                                                        <input type="radio" class="custom-control-input" id="a"
                                                            name="blood_group" value="A"
                                                            {{ old('blood_group', $patient->blood_group) === 'A' ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="a">A</label>
                                                    </div>
                                                    <div class="custom-control custom-radio d-inline mr-4">
                                                        <input type="radio" class="custom-control-input" id="b"
                                                            name="blood_group" value="B"
                                                            {{ old('blood_group', $patient->blood_group) === 'B' ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="b">B</label>
                                                    </div>
                                                    <div class="custom-control custom-radio d-inline mr-4">
                                                        <input type="radio" class="custom-control-input" id="ab"
                                                            name="blood_group" value="AB"
                                                            {{ old('blood_group', $patient->blood_group) === 'AB' ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="ab">AB</label>
                                                    </div>
                                                    @error('blood_group')
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
                                                        value="{{ old('allergy', $patient->allergy) }}">
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
                                                            {{ old('married_status', $patient->married_status) === 'Belum Menikah' ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="belum_menikah">Belum
                                                            Menikah</label>
                                                    </div>
                                                    <div class="custom-control custom-radio d-inline mr-4">
                                                        <input type="radio" class="custom-control-input" id="menikah"
                                                            name="married_status" value="Menikah"
                                                            {{ old('married_status', $patient->married_status) === 'Menikah' ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="menikah">Menikah</label>
                                                    </div>
                                                    <div class="custom-control custom-radio d-inline mr-4">
                                                        <input type="radio" class="custom-control-input" id="janda"
                                                            name="married_status" value="Janda"
                                                            {{ old('married_status', $patient->married_status) === 'Janda' ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="janda">Janda</label>
                                                    </div>
                                                    <div class="custom-control custom-radio d-inline mr-4">
                                                        <input type="radio" class="custom-control-input" id="duda"
                                                            name="married_status" value="Duda"
                                                            {{ old('married_status', $patient->married_status) === 'Duda' ? 'checked' : '' }}>
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
                                                        id="language" placeholder="Pasien Menggunakan Bahasa" required
                                                        value="{{ old('language', $patient->language ?? 'Indonesia') }}"
                                                        name="language">
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
                                                        id="citizenship" placeholder="Kewarganegaraan Pasien" required
                                                        value="{{ old('citizenship', $patient->citizenship ?? 'Indonesia') }}"
                                                        name="citizenship">
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
                                                    <label for="id_card" class="form-label">No. KTP/SIM/Paspor *</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="text"
                                                        class="@error('id_card') is-invalid @enderror form-control"
                                                        id="id_card" placeholder="No. KTP/SIM/Paspor Pasien" required
                                                        name="id_card" value="{{ old('id_card', $patient->id_card) }}">
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
                                                        required value="{{ old('address', $patient->address) }}">
                                                    @error('address')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-sm-4" style="text-align: right">
                                                    <label for="province" class="form-label">Provinsi *</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <select disabled
                                                        class="@error('province') is-invalid @enderror form-control w-100"
                                                        id="province" name="province">
                                                        <option value="" selected></option>
                                                        @foreach ($provinces as $province)
                                                            <option
                                                                value="{{ $province['id'] }}  {{ old('province') == $province['id'] ? 'selected' : '' }}">
                                                                {{ $province['name'] }}
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
                                                    <select disabled
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
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-sm-4" style="text-align: right">
                                                    <label class="form-label" for="ward">Kelurahan *</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    @if (old('ward', $patient->ward) && $patient->kelurahan)
                                                        <option value="{{ $patient->ward }}" selected>
                                                            {{ $patient->kelurahan->name }} -
                                                            {{ $patient->kelurahan->kecamatan->name }}
                                                        </option>
                                                    @endif
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
                                                        required name="mobile_phone_number"
                                                        value="{{ old('mobile_phone_number', $patient->mobile_phone_number) }}">
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
                                                        value="{{ old('email', $patient->email) }}">
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
                                                        required id="last_education" name="last_education">
                                                        <option value="" disabled selected></option>
                                                        <option value="Tidak Sekolah"
                                                            {{ old('last_education', $patient->last_education) === 'Tidak Sekolah' ? 'selected' : '' }}>
                                                            Tidak Sekolah</option>
                                                        <option value="Belum / Tidak tamat SD"
                                                            {{ old('last_education', $patient->last_education) === 'Belum / Tidak tamat SD' ? 'selected' : '' }}>
                                                            Belum / Tidak tamat SD
                                                        </option>
                                                        <option value="Tamat SD"
                                                            {{ old('last_education', $patient->last_education) === 'Tamat SD' ? 'selected' : '' }}>
                                                            Tamat SD</option>
                                                        <option value="Tamat SMTP"
                                                            {{ old('last_education', $patient->last_education) === 'Tamat SMTP' ? 'selected' : '' }}>
                                                            Tamat SMTP</option>
                                                        <option value="Tamat SLTA"
                                                            {{ old('last_education', $patient->last_education) === 'Tamat SLTA' ? 'selected' : '' }}>
                                                            Tamat SLTA</option>
                                                        <option value="Tamat D3"
                                                            {{ old('last_education', $patient->last_education) === 'Tamat D3' ? 'selected' : '' }}>
                                                            Tamat D3</option>
                                                        <option value="Tamat S1"
                                                            {{ old('last_education', $patient->last_education) === 'Tamat S1' ? 'selected' : '' }}>
                                                            Tamat S1</option>
                                                        <option value="Tamat S2"
                                                            {{ old('last_education', $patient->last_education) === 'Tamat S2' ? 'selected' : '' }}>
                                                            Tamat S2</option>
                                                        <option value="Tamat PT"
                                                            {{ old('last_education', $patient->last_education) === 'Tamat PT' ? 'selected' : '' }}>
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
                                                        required id="ethnic" name="ethnic">
                                                        <option value="" selected disabled></option>
                                                        @foreach ($ethnics as $ethnic)
                                                            <option value="{{ $ethnic->id }}"
                                                                {{ old('ethnic', $patient->ethnic) == $ethnic->id ? 'selected' : '' }}>
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
                                                        required id="job" name="job">
                                                        <option value="" disabled selected></option>
                                                        <option value="Belum Bekerja"
                                                            {{ old('job', $patient->job) === 'Belum Bekerja' ? 'selected' : '' }}>
                                                            Belum Bekerja</option>
                                                        <option value="Tidak Bekerja"
                                                            {{ old('job', $patient->job) === 'Tidak Bekerja' ? 'selected' : '' }}>
                                                            Tidak Bekerja</option>
                                                        <option value="Ibu Rumah Tangga"
                                                            {{ old('job', $patient->job) === 'Ibu Rumah Tangga' ? 'selected' : '' }}>
                                                            Ibu Rumah Tangga</option>
                                                        <option value="Petani"
                                                            {{ old('job', $patient->job) === 'Petani' ? 'selected' : '' }}>
                                                            Petani</option>
                                                        <option value="Nelayan"
                                                            {{ old('job', $patient->job) === 'Nelayan' ? 'selected' : '' }}>
                                                            Nelayan
                                                        </option>
                                                        <option value="Buruh Harian"
                                                            {{ old('job', $patient->job) === 'Buruh Harian' ? 'selected' : '' }}>
                                                            Buruh Harian</option>
                                                        <option value="PNS"
                                                            {{ old('job', $patient->job) === 'PNS' ? 'selected' : '' }}>
                                                            PNS
                                                        </option>
                                                        <option value="BUMN"
                                                            {{ old('job', $patient->job) === 'BUMN' ? 'selected' : '' }}>
                                                            BUMN</option>
                                                        <option value="POLRI"
                                                            {{ old('job', $patient->job) === 'POLRI' ? 'selected' : '' }}>
                                                            POLRI</option>
                                                        <option value="TNI"
                                                            {{ old('job', $patient->job) === 'TNI' ? 'selected' : '' }}>
                                                            TNI
                                                        </option>
                                                        <option value="Swasta / Karyawan Kontrak"
                                                            {{ old('job', $patient->job) === 'Swasta / Karyawan Kontrak' ? 'selected' : '' }}>
                                                            Swasta / Karyawan
                                                            Kontrak
                                                        </option>
                                                        <option value="Wirausaha"
                                                            {{ old('job', $patient->job) === 'Wirausaha' ? 'selected' : '' }}>
                                                            Wirausaha</option>
                                                        <option value="Lain-lain"
                                                            {{ old('job', $patient->job) === 'Lain-lain' ? 'selected' : '' }}>
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

                {{-- Panel Informasi Keluarga --}}
                <div class="col-xl-12">
                    <div id="panel-1" class="panel">
                        <div class="panel-hdr bg-info-500">
                            <h2 class="text-white">Informasi<span class="fw-300"><i>Keluarga</i></span></h2>
                        </div>
                        <div class="panel-container show">
                            <div class="panel-content">
                                <div class="row align-items-center">
                                    {{-- Kolom Kiri Keluarga --}}
                                    <div class="col-lg-6">
                                        {{-- PERBAIKAN: Mengambil data dari relasi $patient->family --}}
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-sm-4" style="text-align: right">
                                                    <label for="family_name" class="form-label">Nama Keluarga</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="family_name"
                                                        placeholder="Nama Keluarga" name="family_name"
                                                        value="{{ old('family_name', $patient->family->family_name ?? '') }}">
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
                                                        value="{{ old('family_age', $patient->family->family_age ?? '') }}">
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
                                                        value="{{ old('family_job', $patient->family->family_job ?? '') }}">
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
                                                        value="{{ old('father_name', $patient->family->father_name ?? '') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Kolom Kanan Keluarga --}}
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
                                                        {{-- Opsi-opsi... --}}
                                                        <option value="Ibu"
                                                            {{ old('family_relation', $patient->family->family_relation ?? '') === 'Ibu' ? 'selected' : '' }}>
                                                            Ibu</option>
                                                        {{-- ...tambahkan untuk opsi lainnya --}}
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-sm-4" style="text-align: right">
                                                    <label for="family_number" class="form-label">No. HP / Telp</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="family_number"
                                                        placeholder="No. HP / Telp Keluarga" name="family_number"
                                                        value="{{ old('family_number', $patient->family->family_number ?? '') }}">
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
                                                        value="{{ old('family_address', $patient->family->family_address ?? '') }}">
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
                                                        value="{{ old('mother_name', $patient->family->mother_name ?? '') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Panel Informasi Penjamin --}}
                <div class="col-xl-12">
                    <div id="panel-1" class="panel">
                        <div class="panel-hdr bg-primary-500">
                            <h2 class="text-white">Informasi<span class="fw-300"><i>Penjamin</i></span></h2>
                        </div>
                        <div class="panel-container show">
                            <div class="panel-content">
                                <div class="row align-items-center">
                                    {{-- Kolom Kiri Penjamin --}}
                                    <div class="col-lg-6">
                                        {{-- PERBAIKAN: Mengambil data dari $patient itu sendiri --}}
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-sm-4" style="text-align: right">
                                                    <label for="guarantor_name" class="form-label">Nama Penjamin</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <select class="form-control w-100" id="guarantor_name"
                                                        name="penjamin_id">
                                                        @foreach ($penjamins as $penjamin)
                                                            <option value="{{ $penjamin->id }}"
                                                                {{ old('penjamin_id', $patient->penjamin_id) == $penjamin->id ? 'selected' : '' }}>
                                                                {{ $penjamin->nama_perusahaan }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- Input lainnya --}}
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-sm-4" style="text-align: right">
                                                    <label for="nomor_penjamin" class="form-label">No. Polis /
                                                        BPJS</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="nomor_penjamin"
                                                        placeholder="Nomor Polis / BPJS" name="nomor_penjamin"
                                                        value="{{ old('nomor_penjamin', $patient->nomor_penjamin) }}">
                                                </div>
                                            </div>
                                        </div>
                                        {{-- ... (lakukan hal yang sama untuk nama_pegawai, hubungan_pegawai) ... --}}
                                    </div>
                                    {{-- Kolom Kanan Penjamin --}}
                                    <div class="col-lg-6">
                                        {{-- ... (lakukan hal yang sama untuk nama_perusahaan_pegawai, nomor_kepegawaian, bagian_pegawai, grup_pegawai) ... --}}
                                        <div class="form-group">
                                            <div class="row align-items-center">
                                                <div class="col-sm-4" style="text-align: right">
                                                    <label for="nomor_kepegawaian" class="form-label">No. Pegawai</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="nomor_kepegawaian"
                                                        placeholder="Nomor Kepegawaian" name="nomor_kepegawaian"
                                                        value="{{ old('nomor_kepegawaian', $patient->nomor_kepegawaian) }}">
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

            {{-- Tombol Simpan dan Kembali --}}
            <div class="row align-items-center">
                <div class="col-6">
                    <button type="button" class="btn btn-lg btn-outline-danger waves-effect waves-themed"
                        onclick="window.close();">
                        <span class="fal fa-times mr-1"></span>
                        Tutup Jendela
                    </button>
                </div>
                <div class="col-5" style="text-align: right">
                    <button type="submit" class="btn btn-lg btn-primary waves-effect waves-themed">
                        <span class="fal fa-save mr-1"></span> {{-- Ganti ikon --}}
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </main>
@endsection

@section('plugin')
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(document).ready(function() {
            $('#date_of_birth').datepicker({
                format: 'dd-mm-yyyy', // Format yang ditampilkan ke pengguna
                autoclose: true,
                todayHighlight: true,
                orientation: "bottom left" // Atur posisi popup
            });
            // =================================================================
            // INISIALISASI SEMUA SELECT MENJADI SELECT2
            // =================================================================
            $('#title').select2({
                placeholder: 'Pilih Title'
            });
            $('#religion').select2({
                placeholder: 'Pilih Agama'
            });
            $('#last_education').select2({
                placeholder: 'Pilih Pendidikan Terakhir'
            });
            $('#ethnic').select2({
                placeholder: 'Pilih Suku / Etnis'
            });
            $('#job').select2({
                placeholder: 'Pilih Pekerjaan'
            });
            $('#family_relationship').select2({
                placeholder: 'Pilih Hubungan Keluarga'
            });
            $('#guarantor_name').select2({
                placeholder: 'Pilih Penjamin'
            });

            // Inisialisasi Select2 untuk alamat (awalnya kosong)
            $('#province').select2({
                placeholder: 'Provinsi (Otomatis)'
            });
            $('#regency').select2({
                placeholder: 'Kabupaten/Kota (Otomatis)'
            });
            $('#subdistrict').select2({
                placeholder: 'Kecamatan (Otomatis)'
            });

            // =================================================================
            // LOGIKA UNTUK ALAMAT
            // =================================================================

            // 1. Inisialisasi Select2 untuk Kelurahan dengan AJAX
            $('#ward').select2({
                placeholder: 'Cari dan Pilih Kelurahan',
                ajax: {
                    url: "{{ route('getKelurahan') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    id: item.id,
                                    text: item.name + ' - ' + item.kecamatan.name,
                                    // Kirim data tambahan
                                    full_data: item
                                };
                            })
                        };
                    },
                    cache: true
                }
            });

            // 2. Fungsi untuk mengisi dropdown Kecamatan, Kabupaten, dan Provinsi
            function populateAddressFields(data) {
                const provinceSelect = $('#province');
                const regencySelect = $('#regency');
                const subdistrictSelect = $('#subdistrict');

                if (!data || !data.kecamatan) {
                    console.log("Data alamat tidak lengkap diterima:", data);
                    return;
                }

                const kecamatan = data.kecamatan;
                const kabupaten = kecamatan.kabupaten;
                const provinsi = kabupaten.provinsi;

                // Buat option baru jika belum ada
                if (provinceSelect.find("option[value='" + provinsi.id + "']").length === 0) {
                    provinceSelect.append(new Option(provinsi.name, provinsi.id, true, true));
                }
                if (regencySelect.find("option[value='" + kabupaten.id + "']").length === 0) {
                    regencySelect.append(new Option(kabupaten.name, kabupaten.id, true, true));
                }
                if (subdistrictSelect.find("option[value='" + kecamatan.id + "']").length === 0) {
                    subdistrictSelect.append(new Option(kecamatan.name, kecamatan.id, true, true));
                }

                // Pilih nilainya dan trigger change agar Select2 update
                provinceSelect.val(provinsi.id).trigger('change');
                regencySelect.val(kabupaten.id).trigger('change');
                subdistrictSelect.val(kecamatan.id).trigger('change');
            }

            // 3. Event listener saat pengguna memilih kelurahan BARU dari hasil pencarian
            $('#ward').on('select2:select', function(e) {
                var data = e.params.data.full_data;
                populateAddressFields(data);
            });

            // 4. OTOMATISASI: Jalankan saat halaman dimuat untuk menampilkan data yang ada
            @if ($patient->ward && $patient->kelurahan)
                (function() {
                    // Buat objek data palsu yang strukturnya sama dengan hasil AJAX
                    // untuk digunakan oleh fungsi populateAddressFields
                    const existingAddressData = {
                        kecamatan: {
                            id: '{{ $patient->kelurahan->kecamatan->id }}',
                            name: '{{ $patient->kelurahan->kecamatan->name }}',
                            kabupaten: {
                                id: '{{ $patient->kelurahan->kecamatan->kabupaten->id }}',
                                name: '{{ $patient->kelurahan->kecamatan->kabupaten->name }}',
                                provinsi: {
                                    id: '{{ $patient->kelurahan->kecamatan->kabupaten->provinsi->id }}',
                                    name: '{{ $patient->kelurahan->kecamatan->kabupaten->provinsi->name }}'
                                }
                            }
                        }
                    };
                    populateAddressFields(existingAddressData);
                })();
            @endif
        });
    </script>
@endsection
