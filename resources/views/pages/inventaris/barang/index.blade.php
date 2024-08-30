@extends('inc.layout')
@section('title', 'Barang')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row justify-content-center"> {{-- FORM PENCARIAN --}}
            <div class="col-lg-10">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Form <span class="fw-300"><i>Pencarian</i></span>
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form action="{{ route('inventaris.barang.search') }}" method="get">
                                @csrf
                                <div class="row justify-content-center mt-5">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-lg-4" style="text-align: right">
                                                    <label for="custom_name" class="form-label">Nama Barang</label>
                                                </div>
                                                <div class="col-lg">
                                                    <input type="text" value="{{ request('custom_name') }}"
                                                        style="border: 0; border-bottom: 1.9px solid #FD61AA; margin-top: -.5rem; border-radius: 0"
                                                        class="form-control" id="custom_name" name="custom_name">
                                                    @error('custom_name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-lg-5" style="text-align: right">
                                                    <label for="identitas_barang" class="form-label">Identitas
                                                        Barang</label>
                                                </div>
                                                <div class="col-lg">
                                                    <input type="text" value="{{ request('identitas_barang') }}"
                                                        style="border: 0; border-bottom: 1.9px solid #FD61AA; margin-top: -.5rem; border-radius: 0"
                                                        class="form-control" id="identitas_barang" name="identitas_barang">
                                                    @error('identitas_barang')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center mt-5">
                                    <div class="col-lg-4" style="text-align: right">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <label class="form-label" for="barang_category_id">
                                                        Kategori Barang
                                                    </label>
                                                </div>
                                                <div class="col-lg">
                                                    <select
                                                        class="form-control w-100 @error('barang_category_id') is-invalid @enderror"
                                                        id="barang_category_id" name="barang_category_id"
                                                        style="border: 0; border-bottom: 1.9px solid #FD61AA; margin-top: -.5rem; border-radius: 0">
                                                        <option value=""> </option>
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}"
                                                                {{ request('barang_category_id') == $category->id ? 'selected' : '' }}>
                                                                {{ strtoupper($category->name) }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('barang_category_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-lg-5" style="text-align: right">
                                                    <label class="form-label" for="template_barang_id">
                                                        Template Barang
                                                    </label>
                                                </div>
                                                <div class="col-lg">
                                                    <select
                                                        class="form-control w-100 @error('template_barang_id') is-invalid @enderror"
                                                        id="template_barang_id" name="template_barang_id"
                                                        style="border: 0; border-bottom: 1.9px solid #FD61AA; margin-top: -.5rem; border-radius: 0">
                                                        <option value=""> </option>
                                                        @foreach ($templates as $template)
                                                            <option value="{{ $template->id }}"
                                                                {{ request('template_barang_id') == $template->id ? 'selected' : '' }}>
                                                                {{ strtoupper($template->name) }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('template_barang_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-end mt-5">
                                    <div class="col-lg-8">
                                        <div class="row justify-content-end">
                                            <div class="col-lg-5">
                                                <button type="submit"
                                                    class="btn btn-outline-primary waves-effect waves-themed">
                                                    <span class="fal fa-search mr-1"></span>
                                                    Cari
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-5"> {{-- Tombol Tambah Barang dan Barang Belum di Ruangan --}}
            <div class="col-lg-12">
                <button type="button" class="btn btn-primary waves-effect waves-themed" onclick="toggleForm()"
                    id="toggle-form-btn">
                    Tambah Barang
                </button>
                {{-- <a href="/barang/belum-di-ruangan/show" class="btn btn-primary waves-effect waves-themed">
                    <span class="fal fa-box mr-1"></span>
                    Barang Belum di Ruangan
                </a> --}}
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div id="form-container" style="display: none;" class="panel form-container"> {{-- Form Tambah Barang --}}
                    <div class="panel-hdr">
                        <h2>
                            Form Tambah Barang
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form autocomplete="off" novalidate action="javascript:void(0)" method="post"
                                enctype="multipart/form-data" id="store-form">
                                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="custom_name">Nama Barang <sup>(Opsional)</sup></label>
                                            <input type="text" value="{{ old('custom_name') }}"
                                                class="form-control @error('custom_name') is-invalid @enderror"
                                                id="custom_name" name="custom_name" placeholder="Nama Barang">
                                            @error('custom_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="merk">Merk <sup>(Opsional)</sup></label>
                                            <input type="text" value="{{ old('merk') }}"
                                                class="form-control @error('merk') is-invalid @enderror" id="merk"
                                                name="merk" placeholder="Merk">
                                            @error('merk')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label" for="tambahBarang">
                                                Barang
                                            </label>
                                            <select
                                                class="form-control w-100 @error('template_barang_id') is-invalid @enderror"
                                                id="tambahBarang" name="template_barang_id">
                                                <optgroup label="TEMPLATE BARANG">
                                                    <option value="" selected disabled></option>
                                                    @foreach ($templates as $template)
                                                        <option value="{{ $template->id }}">
                                                            {{ strtoupper($template->name) }}
                                                        </option>
                                                    @endforeach
                                                </optgroup>
                                            </select>
                                            @error('template_barang_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label" for="kondisiBarang">
                                                Kondisi Barang
                                            </label>
                                            <select class="form-control w-100 @error('condition') is-invalid @enderror"
                                                id="kondisiBarang" name="condition">
                                                <optgroup label="KONDISI BARANG">
                                                    <option disabled selected></option>
                                                    <option value="Baik">BAIK</option>
                                                    <option value="Rusak">RUSAK</option>
                                                </optgroup>
                                            </select>
                                            @error('condition')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label" for="tahunPengadaan">
                                                Tahun Pengadaan
                                            </label>
                                            <select class="form-control w-100 @error('bidding_year') is-invalid @enderror"
                                                id="tahunPengadaan" name="bidding_year">
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
                                        <div class="form-group">
                                            <label for="jumlah">Jumlah <sup>(Opsional)</sup></label>
                                            <input type="number" value="{{ old('jumlah', 1) }}"
                                                class="form-control @error('jumlah') is-invalid @enderror" id="jumlah"
                                                name="jumlah" placeholder="Jumlah">
                                            @error('jumlah')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col">
                                        <div class="form-group">
                                            <label class="form-label" for="room_id">
                                                Ruangan
                                            </label>
                                            <select class="form-control w-100 @error('room_id') is-invalid @enderror"
                                                id="room_id" name="room_id">
                                                <optgroup label="Ruangan">
                                                    @foreach ($rooms as $row)
                                                        <option value="{{ $row->id }}">{{ strtoupper($row->name) }}
                                                        </option>
                                                    @endforeach
                                                </optgroup>
                                            </select>
                                            @error('room_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label class="form-label" for="company_id">
                                                Perusahaan
                                            </label>
                                            <select class="form-control w-100 @error('company_id') is-invalid @enderror"
                                                id="company_id" name="company_id">
                                                <optgroup label="Perusahaan">
                                                    @foreach ($companies as $row)
                                                        <option value="{{ $row->id }}">{{ $row->name }}</option>
                                                    @endforeach
                                                </optgroup>
                                            </select>
                                            @error('company_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
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

                <div id="panel-2" class="panel"> {{-- Table Barang --}}
                    <div class="panel-hdr">
                        <h2>
                            Total Barang : {{ $jumlah }}
                        </h2>
                        @include('pages.partials.panel-toolbar')
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <!-- datatable start -->
                            @include('pages.inventaris.barang.partials.barang-table')
                            <!-- datatable end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@section('plugin')
    <script></script>
@endsection
