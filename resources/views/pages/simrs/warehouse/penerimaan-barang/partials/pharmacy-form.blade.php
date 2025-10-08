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

                    <form action="{{ route('warehouse.penerimaan-barang.pharmacy.index') }}" method="get">
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
                                            <input type="date" value="{{ request('tanggal_terima') }}"
                                                style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                class="form-control" id="tanggal_terima" name="tanggal_terima">
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
                                            <label class="form-label text-end" for="kode_po">
                                                Kode PO
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <input type="text" value="{{ request('kode_po') }}"
                                                style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                class="form-control" id="kode_po" name="kode_po">
                                            @error('kode_po')
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
                                            <label class="form-label text-end" for="batch_no">
                                                No Batch
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <input type="text" value="{{ request('batch_no') }}"
                                                style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                class="form-control" id="batch_no" name="batch_no">
                                            @error('batch_no')
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
                                            <label class="form-label text-end" for="kode_penerimaan">
                                                Kode Penerimaan
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <input type="text" value="{{ request('kode_penerimaan') }}"
                                                style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                class="form-control" id="kode_penerimaan" name="kode_penerimaan">
                                            @error('kode_penerimaan')
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
                                            <label class="form-label text-end" for="no_faktur">
                                                No Faktur
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <input type="text" value="{{ request('no_faktur') }}"
                                                style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                class="form-control" id="no_faktur" name="no_faktur">
                                            @error('no_faktur')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
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
                            <div class="col-xl-3">
                                <button type="button" class="btn btn-primary waves-effect waves-themed"
                                    id="tambah-btn">
                                    <span class="fal fa-plus mr-1"></span>
                                    Penerimaan Barang
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
