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

                    <form>
                        @csrf

                        <div class="row justify-content-center">
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-2" style="text-align: right">
                                            <label class="form-label text-end" for="tanggal_terima">
                                                Tanggal Terima
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <input type="text" class="form-control" id="datepicker-1"
                                                placeholder="mm/dd/yyyy - mm/dd/yyyy" name="tanggal_terima">

                                            @error('tanggal_terima')
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
                                            <label class="form-label text-end" for="tanggal_faktur">
                                                Tanggal Faktur
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <input type="text" class="form-control" id="datepicker-1"
                                                placeholder="mm/dd/yyyy - mm/dd/yyyy" name="tanggal_faktur">

                                            @error('tanggal_faktur')
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
                                            <label class="form-label text-end" for="supplier_id">
                                                Supplier
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <select name="supplier_id" id="supplier" class="form-control select2">
                                                <option value="" selected>Semua</option>
                                                @foreach ($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}">{{ $supplier->nama }}</option>
                                                @endforeach
                                            </select>
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
                                            <select name="kategori_id" id="kategori" class="form-control select2">
                                                <option value="" selected>Semua</option>
                                                @foreach ($kategoris as $kategori)
                                                    <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
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
                                        <div class="col-xl-2" style="text-align: right">
                                            <label class="form-label text-end" for="tipe_terima">
                                                Tipe Terima
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <select name="tipe_terima" id="tipe-terima" class="form-control select2">
                                                <option value="" selected>Semua</option>
                                                <option value="po">PO-Based</option>
                                                <option value="npo">Non PO-Based</option>
                                            </select>
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
                                            <select name="jenis" id="jenis" class="form-control select2">
                                                <option value="" selected>Semua</option>
                                                <option value="f">Barang Farmasi</option>
                                                <option value="nf">Barang Non Farmasi</option>
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
                                            <label class="form-label text-end" for="kode_po">
                                                Kode PO
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <input type="text" class="form-control" id="kode-po"
                                                name="kode_po">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-2" style="text-align: right">
                                            <label class="form-label text-end" for="nama_barang">
                                                Nama Barang
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <input type="text" class="form-control" id="nama-barang"
                                                name="nama_barang">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row justify-content-end mt-3">
                            <div class="col-xl-2">
                                <button id="detailPBBtn" class="btn btn-outline-primary waves-effect waves-themed">
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
