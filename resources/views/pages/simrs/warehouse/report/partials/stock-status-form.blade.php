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
                    &nbsp; <i id="loading-spinner-head" class="fas fa-spinner fa-spin"></i>
                    <span id="loading-message" class="text-info">Loading...</span>
                </h2>
            </div>
            <div class="panel-container show">
                <div id="loading-page"></div>
                <div class="panel-content">

                    <form action="{{ route('warehouse.report.stock-status') }}"
                        method="get">
                        @csrf

                        <div class="row justify-content-center">
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-2" style="text-align: right">
                                            <label class="form-label text-end" for="tanggal_end">
                                                Sampai Tanggal
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <input type="date" class="form-control"
                                                value="{{ request('tanggal_end') ? $tanggal_end : now()->format('Y-m-d') }}"
                                                id="tanggal_end" placeholder="Masukkan Tanggal Akhir" autocomplete="off"
                                                name="tanggal_end">

                                            @error('tanggal_end')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-2" style="text-align: right">
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
                                        <div class="col-xl-2" style="text-align: right">
                                            <label class="form-label text-end" for="nama_barang">
                                                Nama Barang
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <input type="text" value="{{ request('nama_barang') }}"
                                                style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                class="form-control" id="nama_barang" name="nama_barang">
                                            @error('nama_barang')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-2" style="text-align: right">
                                            <label class="form-label text-end" for="jenis">
                                                Jenis Barang
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <select name="jenis" id="jenis" class="form-control">
                                                <option value="" selected>Semua</option>
                                                <option value="f">Farmasi</option>
                                                <option value="nf">Non Farmasi</option>
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
                                        <div class="col-xl-2" style="text-align: right">
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
                                        <div class="col-xl-2" style="text-align: right">
                                            <label class="form-label text-end" for="gudang_id">
                                                Gudang
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <select name="gudang_id" id="gudang" class="form-control select2">
                                                <option value="" selected hidden disabled>Pilih Gudang
                                                </option>
                                                @foreach ($gudangs as $gudang)
                                                    <option value="{{ $gudang->id }}">{{ $gudang->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row justify-content-end mt-3">
                            <div class="col-xl-2">
                                <button type="submit" class="btn btn-outline-primary waves-effect waves-themed">
                                    <span class="fal fa-search mr-1"></span>
                                    Cari
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
