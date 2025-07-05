<div class="row justify-content-center">
    <div class="col-xl-8">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    Stock Opname: <span class="fw-300"><i>Draft</i></span>
                    &nbsp;
                    <i id="loading-spinner-head" class="loading fas fa-spinner fa-spin"></i>
                    <span class="loading-message loading text-info">Loading...</span>
                </h2>
            </div>
            <div class="panel-container show">
                <div class="loading loading-page"></div>
                <div class="panel-content">

                    @csrf

                    <div class="row justify-content-center">
                        <div class="col-xl-6">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xl-4" style="text-align: right">
                                        <label class="form-label text-end" for="gudang">
                                            Gudang
                                        </label>
                                    </div>
                                    <div class="col-xl">
                                        <select id="gudang" class="form-control select2">
                                            <option value="" selected disabled hidden>Pilih Gudang</option>
                                            @foreach ($ogs as $og)
                                                <option value="{{ $og->id }}">{{ $og->gudang->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xl-4" style="text-align: right">
                                        <label class="form-label text-end" for="jenis_barang">
                                            Jenis Barang
                                        </label>
                                    </div>
                                    <div class="col-xl">
                                        <select id="jenis_barang" class="form-control">
                                            <option value="">Semua</option>
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
                                    <div class="col-xl-4" style="text-align: right">
                                        <label class="form-label text-end" for="kategori_barang">
                                            Kategori Barang
                                        </label>
                                    </div>
                                    <div class="col-xl">
                                        <select id="kategori_barang" class="form-control select2">
                                            <option value="">Semua</option>
                                            @foreach ($kategoris as $kategori)
                                                <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xl-4" style="text-align: right">
                                        <label class="form-label text-end" for="satuan_barang">
                                            Satuan Barang
                                        </label>
                                    </div>
                                    <div class="col-xl">
                                        <select id="satuan_barang" class="form-control select2">
                                            <option value="">Semua</option>
                                            @foreach ($satuans as $satuan)
                                                <option value="{{ $satuan->id }}">{{ $satuan->nama }}</option>
                                            @endforeach
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
                                        <label class="form-label text-end" for="batch_kosong">
                                            Batch Kosong
                                        </label>
                                    </div>
                                    <div class="col-xl">
                                        <select id="batch_kosong" class="form-control">
                                            <option value="hide">Sembunyikan</option>
                                            <option value="show">Tampilkan</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xl-4" style="text-align: right">
                                        <label class="form-label text-end" for="batch_expired">
                                            Batch Expired
                                        </label>
                                    </div>
                                    <div class="col-xl">
                                        <select id="batch_expired" class="form-control">
                                            <option value="">Semua</option>
                                            <option value="no">Current Only</option>
                                            <option value="exp">Expired Only</option>
                                        </select>
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
