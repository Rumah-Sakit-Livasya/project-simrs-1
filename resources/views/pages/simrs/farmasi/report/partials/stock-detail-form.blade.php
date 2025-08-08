<style>
    #loading-page {
        position: absolute;
        min-height: 100%;
        min-width: 100%;
        background: rgba(0, 0, 0, 0.75);
        border-radius: 0 0 4px 4px;
        z-index: 1000;
    }
</style>
<div class="row justify-content-center">
    <div class="col-xl-8">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    Form <span class="fw-300"><i>Pencarian</i></span>
                    &nbsp;
                    <i id="loading-spinner-head" class="loading fas fa-spinner fa-spin"></i>
                    <span class="loading-message loading text-info">Loading...</span>
                </h2>
            </div>
            <div class="panel-container show">
                <div class="loading loading-page"></div>
                <div class="panel-content">
                    <div class="row justify-content-center">
                        <div class="col-xl-6">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xl-4" style="text-align: right">
                                        <label class="form-label text-end" for="tanggal">
                                            Tanggal
                                        </label>
                                    </div>
                                    <div class="col-xl">
                                        <input type="string" class="form-control"
                                            value="{{ request('tanggal') ? request('tanggal') : now()->format('Y-m-d') }}"
                                            id="datepicker-1" placeholder="Masukkan Tanggal Akhir" autocomplete="off"
                                            name="tanggal">

                                        @error('tanggal')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xl-4" style="text-align: right">
                                        <label class="form-label text-end" for="kategori_id">
                                            Kategori Barang
                                        </label>
                                    </div>
                                    <div class="col-xl">
                                        <select name="kategori_id" class="form-control select2">
                                            <option value="">Semua</option>
                                            @foreach ($kategoris as $kategori)
                                                <option value="{{ $kategori->id }}"
                                                    {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                                    {{ $kategori->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('kategori_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-xl-6">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xl-4" style="text-align: right">
                                        <label class="form-label text-end" for="nama">
                                            Nama Barang
                                        </label>
                                    </div>
                                    <div class="col-xl">
                                        <input type="text" value="{{ request('nama') }}"
                                            style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                            class="form-control" id="nama" name="nama">
                                        @error('nama')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xl-4" style="text-align: right">
                                        <label class="form-label text-end" for="jenis">
                                            Jenis Barang
                                        </label>
                                    </div>
                                    <div class="col-xl">
                                        <select name="jenis" id="jenis" class="form-control">
                                            <option value="" {{ request('jenis') == '' ? 'selected' : '' }}>Semua
                                            </option>
                                            <option value="f" {{ request('jenis') == 'f' ? 'selected' : '' }}>
                                                Farmasi</option>
                                            <option value="nf" {{ request('jenis') == 'nf' ? 'selected' : '' }}>Non
                                                Farmasi</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-xl-6">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xl-4" style="text-align: right">
                                        <label class="form-label text-end" for="golongan_id">
                                            Golongan Barang
                                        </label>
                                    </div>
                                    <div class="col-xl">
                                        <select name="golongan_id" class="form-control select2">
                                            <option value="">Semua</option>
                                            @foreach ($golongans as $golongan)
                                                <option value="{{ $kategori->id }}"
                                                    {{ request('golongan_id') == $kategori->id ? 'selected' : '' }}>
                                                    {{ $kategori->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('golongan_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xl-4" style="text-align: right">
                                        <label class="form-label text-end" for="gudang_id">
                                            Gudang
                                        </label>
                                    </div>
                                    <div class="col-xl">
                                        <select name="gudang_id" id="gudang" class="form-control select2">
                                            <option value="" {{ request('gudang_id') == '' ? 'selected' : '' }}>
                                                Semua
                                            </option>
                                            @foreach ($gudangs as $gudang)
                                                <option value="{{ $gudang->id }}"
                                                    {{ request('gudang_id') == $gudang->id ? 'selected' : '' }}>
                                                    {{ $gudang->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row justify-content-end mt-3">
                        <div class="col-xl-3">
                            <button class="btn btn-primary waves-effect waves-themed" id="print-btn">
                                <span class="fal fa-print mr-1"></span>
                                Print Full Report
                            </button>
                        </div>
                        <div class="col-xl-2">
                            <button class="btn btn-primary waves-effect waves-themed" id="search-btn">
                                <span class="fal fa-search mr-1"></span>
                                Cari
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
