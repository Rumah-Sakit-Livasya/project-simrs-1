<style>
    #loading-page {
        position: absolute;
        min-height: 100%;
        min-width: 100%;
        background: rgba(0, 0, 0, 0.75);
        border-radius: 0 0 4px 4px;
        z-index: 1000;
    }

    input {
        border: 0;
        border-bottom: 1.9px solid #eaeaea;
        margin-top: -.5rem;
        border-radius: 0;
    }
</style>
<div class="row justify-content-center">
    <div class="col-xl-8">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    Form <span class="fw-300"><i>Pencarian</i></span>
                </h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">

                    <form action="{{ route('warehouse.report.histori-perubahan-master-data') }}" method="get">
                        @csrf

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
                                                value="{{ request('tanggal') ? request('tanggal') : '' }}"
                                                id="datepicker-1" autocomplete="off" name="tanggal">

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
                                            <label class="form-label text-end" for="kode_barang">
                                                Kode Barang
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <input type="text" value="{{ request('kode_barang') }}"
                                                name="kode_barang" class="form-control">
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
                                            <label class="form-label text-end" for="nama_barang">
                                                Nama Barang
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <input type="text" value="{{ request('nama_barang') }}"
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
                                        <div class="col-xl-4" style="text-align: right">
                                            <label class="form-label text-end" for="jenis">
                                                Jenis Barang
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <select name="jenis" id="jenis" class="form-control">
                                                <option value="" {{ request('jenis') == '' ? 'selected' : '' }}>
                                                    Semua</option>
                                                <option value="f" {{ request('jenis') == 'f' ? 'selected' : '' }}>
                                                    Farmasi</option>
                                                <option value="nf" {{ request('jenis') == 'nf' ? 'selected' : '' }}>
                                                    Non Farmasi
                                                </option>
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
