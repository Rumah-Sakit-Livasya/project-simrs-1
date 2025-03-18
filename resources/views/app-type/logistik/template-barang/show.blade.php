@php
    use App\Models\Inventaris\Barang;
@endphp

@extends('inc.layout')
@section('title', 'Barang')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row mb-5">
            <div class="col-xl-6">
                <a href="{{ url()->previous() }}" class="btn btn-primary waves-effect waves-themed">
                    <span class="fal fa-arrow-left mr-1"></span>
                    Kembali
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Total Barang : {{ $jumlah }} <span class="fw-300"></span>
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <!-- datatable start -->
                            @include('app-type.logistik.barang.partials.barang-table')
                            <!-- datatable end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- Modal Large -->
    <div class="modal fade" id="default-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form autocomplete="off" novalidate action="/barang" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Barang</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="fal fa-times"></i></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="custom_name">Nama Barang <sup>(Opsional)</sup></label>
                            <input type="text" value="{{ old('custom_name') }}"
                                class="form-control @error('custom_name') is-invalid @enderror" id="custom_name"
                                name="custom_name" placeholder="Nama Barang">
                            @error('custom_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="single-default">
                                Kondisi Barang
                            </label>
                            <select class="form-control w-100 @error('condition') is-invalid @enderror" id="single-default"
                                name="condition">
                                <optgroup label="Kondisi Barang">
                                    <option value="Baik">Baik</option>
                                    <option value="Rusak">Rusak</option>
                                </optgroup>
                            </select>
                            @error('condition')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="single-default">
                                Tahun Pengadaan
                            </label>
                            <select class="form-control w-100 @error('bidding_year') is-invalid @enderror"
                                id="single-default" name="bidding_year">
                                <optgroup label="Tahun Pengadaan">
                                    <option value="2010">2010</option>
                                    <option value="2011">2011</option>
                                    <option value="2012">2012</option>
                                    <option value="2013">2013</option>
                                    <option value="2014">2014</option>
                                    <option value="2015">2015</option>
                                    <option value="2016">2016</option>
                                    <option value="2017">2017</option>
                                    <option value="2018">2018</option>
                                    <option value="2019">2019</option>
                                    <option value="2020">2020</option>
                                    <option value="2021">2021</option>
                                    <option value="2022">2022</option>
                                    <option value="2023">2023</option>
                                    <option value="2024">2024</option>
                                    <option value="2025">2025</option>
                                </optgroup>
                            </select>
                            @error('bidding_year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">
                            <span class="fal fa-plus-circle mr-1"></span>
                            Tambah
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
