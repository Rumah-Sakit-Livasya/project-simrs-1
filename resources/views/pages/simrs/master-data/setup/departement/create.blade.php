{{-- @dd($provinces) --}}
@extends('inc.layout')
@section('title', 'Tambah Departemen Baru')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="subheader">
            @component('inc.subheader', ['subheader_title' => 'st_type_2', 'sh_icon' => 'home'])
                @slot('sh_descipt')
                    add new departement
                @endslot
            @endcomponent
        </div>

        <form autocomplete="off" action="{{ route('master-data.setup.departemen.store') }}" method="post">
            @csrf
            <div class="row align-items-center">
                <div class="col-xl-12">
                    <div id="panel-1" class="panel">
                        <div class="panel-hdr bg-primary">
                            <h2 class="text-white">
                                Departemen Baru
                            </h2>
                        </div>
                        <div class="panel-container show">
                            <div class="panel-content">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-2" style="text-align: left">
                                                    <label for="kode" class="form-label">Kode *</label>
                                                </div>
                                                <div class="col-sm-10">
                                                    <input type="text"
                                                        class="@error('kode') is-invalid @enderror form-control"
                                                        id="kode" name="kode" placeholder="Masukan Kode"
                                                        value="{{ old('kode') }}">
                                                    @error('kode')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-2" style="text-align: left">
                                                    <label for="name" class="form-label">Nama Departemen *</label>
                                                </div>
                                                <div class="col-sm-10">
                                                    <input type="text"
                                                        class="@error('name') is-invalid @enderror form-control"
                                                        id="name" name="name" placeholder="Masukan Nama Departemen"
                                                        value="{{ old('name') }}">
                                                    @error('name')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-2" style="text-align: left">
                                                    <label for="keterangan" class="form-label">Keterangan *</label>
                                                </div>
                                                <div class="col-sm-10">
                                                    <input type="text"
                                                        class="@error('keterangan') is-invalid @enderror form-control"
                                                        id="keterangan" name="keterangan" placeholder="Masukan Keterangan"
                                                        value="{{ old('keterangan') }}">
                                                    @error('keterangan')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-2" style="text-align: left">
                                                    <label for="quota" class="form-label">Quota *</label>
                                                </div>
                                                <div class="col-sm-10">
                                                    <input type="number"
                                                        class="@error('quota') is-invalid @enderror form-control"
                                                        id="quota" name="quota" placeholder="Masukan Quota"
                                                        value="{{ old('quota') }}">
                                                    @error('quota')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-2" style="text-align: left">
                                                    <label for="Kode Poli" class="form-label">Kode Poli *</label>
                                                </div>
                                                <div class="col-sm-10">
                                                    <select class="form-control w-100" id="kode_poli" name="kode_poli">
                                                        <option value="" selected></option>
                                                    </select>
                                                    @error('kode_poli')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-2" style="text-align: left">
                                                    <label for="Default Dokter" class="form-label">Default Dokter *</label>
                                                </div>
                                                <div class="col-sm-10">
                                                    <select class="form-control w-100" id="default_dokter"
                                                        name="default_dokter">

                                                        @foreach ($doctors as $row)
                                                            <option value="{{ $row->id }}" selected>
                                                                {{ $row->employee->fullname }}</option>
                                                        @endforeach
                                                        {{-- <option value="ANAK">ANAK</option>
                                                        <option value="PENYAKIT DALAM">PENYAKIT DALAM</option> --}}
                                                    </select>
                                                    @error('default_dokter')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-2" style="text-align: left">
                                                    <label for="Publish Online" class="form-label">Publish Online
                                                        *</label>
                                                </div>
                                                <div class="col-sm-10">
                                                    <input type="checkbox"
                                                        class="@error('publish_online') is-invalid @enderror form-control"
                                                        id="publish_online" name="publish_online"
                                                        placeholder="Masukan Nama Departemen"
                                                        style="width: 18px; margin-top: -10px; margin-left: 13px;"
                                                        value="1">
                                                    @error('publish_online')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-2" style="text-align: left">
                                                    <label for="Revenue & Cost Center" class="form-label">Revenue & Cost
                                                        Center *</label>
                                                </div>
                                                <div class="col-sm-10">
                                                    <select class="form-control w-100" id="revenue_and_cost_center"
                                                        name="revenue_and_cost_center">
                                                        <option value=""></option>
                                                        <option value="ADMIN DAN KASIR"
                                                            {{ old('revenue_and_cost_center') === 'ADMIN DAN KASIR' ? 'selected' : '' }}>
                                                            ADMIN DAN KASIR</option>
                                                        <option value="AMBULANCE"
                                                            {{ old('revenue_and_cost_center') === 'AMBULANCE' ? 'selected' : '' }}>
                                                            AMBULANCE</option>
                                                        <option value="ANASTESI"
                                                            {{ old('revenue_and_cost_center') === 'ANASTESI' ? 'selected' : '' }}>
                                                            ANASTESI</option>
                                                        <option value="APOTIK"
                                                            {{ old('revenue_and_cost_center') === 'APOTIK' ? 'selected' : '' }}>
                                                            APOTIK</option>
                                                        <option value="BACKOFFICE"
                                                            {{ old('revenue_and_cost_center') === 'BACKOFFICE' ? 'selected' : '' }}>
                                                            BACKOFFICE</option>
                                                        <option value="CATHLAB"
                                                            {{ old('revenue_and_cost_center') === 'CATHLAB' ? 'selected' : '' }}>
                                                            CATHLAB</option>
                                                        <option value="DAPUR DAN GIZI"
                                                            {{ old('revenue_and_cost_center') === 'DAPUR DAN GIZI' ? 'selected' : '' }}>
                                                            DAPUR DAN GIZI</option>
                                                        <option value="DELUXE"
                                                            {{ old('revenue_and_cost_center') === 'DELUXE' ? 'selected' : '' }}>
                                                            DELUXE</option>
                                                        <option value="DEPO CSSD"
                                                            {{ old('revenue_and_cost_center') === 'DEPO CSSD' ? 'selected' : '' }}>
                                                            DEPO CSSD</option>
                                                        <option value="DEPO KTD"
                                                            {{ old('revenue_and_cost_center') === 'DEPO KTD' ? 'selected' : '' }}>
                                                            DEPO KTD</option>
                                                        <option value="DEPO OK"
                                                            {{ old('revenue_and_cost_center') === 'DEPO OK' ? 'selected' : '' }}>
                                                            DEPO OK</option>
                                                        <option value="DEPO VK"
                                                            {{ old('revenue_and_cost_center') === 'DEPO VK' ? 'selected' : '' }}>
                                                            DEPO VK</option>
                                                        <option value="DRIVER DAN SATPAM"
                                                            {{ old('revenue_and_cost_center') === 'DRIVER DAN SATPAM' ? 'selected' : '' }}>
                                                            DRIVER DAN SATPAM</option>
                                                        <option value="EKSEKUTIF"
                                                            {{ old('revenue_and_cost_center') === 'EKSEKUTIF' ? 'selected' : '' }}>
                                                            EKSEKUTIF</option>
                                                    </select>
                                                    @error('revenue_and_cost_center')
                                                        <p class="invalid-feedback">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <div class="row">
                                                <div class="col-sm-2" style="text-align: left">
                                                    <label for="Master Layanan RL" class="form-label">Master Layanan RL
                                                        *</label>
                                                </div>
                                                <div class="col-sm-10">
                                                    <select class="form-control w-100" id="master_layanan_rl"
                                                        name="master_layanan_rl">
                                                        <option value=""></option>
                                                        <option value="Ibu"
                                                            {{ old('master_layanan_rl') === 'Penyakit Dalam' ? 'selected' : '' }}>
                                                            Penyakit Dalam</option>
                                                        <option value="Bedah"
                                                            {{ old('master_layanan_rl') === 'Bedah' ? 'selected' : '' }}>
                                                            Bedah</option>
                                                        <option value="Syaraf"
                                                            {{ old('master_layanan_rl') === 'Syaraf' ? 'selected' : '' }}>
                                                            Syaraf</option>
                                                    </select>
                                                    @error('master_layanan_rl')
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
                <div class="col-md-12 d-flex justify-content-between">
                    <div>
                        <a href="{{ redirect()->back() }}" class="btn btn-outline-primary waves-effect waves-themed">
                            <span class="fal fa-arrow-left mr-1"></span>
                            Kembali
                        </a>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary waves-effect waves-themed">
                            <span class="fal fa-user-plus"></span>
                            Simpan
                        </button>
                    </div>
                </div>
            </div>

        </form>
    </main>
@endsection
@section('plugin')
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(document).ready(function() {
            $('#master_layanan_rl').select2({
                'placeholder': 'Pilih Master Layanan RL',
            });

            $('#kode_poli').select2({
                'placeholder': 'Pilih Kode Poli',
            });

            $('#revenue_and_cost_center').select2({
                'placeholder': 'Pilih Revenue & Cost Center',
            });
        });
    </script>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
