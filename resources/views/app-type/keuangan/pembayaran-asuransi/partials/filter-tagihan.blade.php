<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>Filter <span class="fw-300"><i>Tagihan</i></span></h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="form-group row">
                                <label class="col-xl-4 text-center col-form-label">Periode Awal</label>
                                <div class="col-xl-8">
                                    <input type="text" name="tanggal_awal" class="form-control datepicker"
                                        value="{{ date('d-m-Y') }}" placeholder="Pilih tanggal awal" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-xl-4 text-center col-form-label">Penjamin</label>
                                <div class="col-xl-8">
                                    <select class="form-control select2 w-100" id="penjamin_id" name="penjamin_id">
                                        <option value="">Pilih Penjamin</option>
                                        @foreach ($penjamins as $penjamin)
                                            <option value="{{ $penjamin->id }}">{{ $penjamin->nama_perusahaan }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="form-group row">
                                <label class="col-xl-4 text-center col-form-label">Periode Akhir</label>
                                <div class="col-xl-8">
                                    <input type="text" name="tanggal_akhir" class="form-control datepicker"
                                        value="{{ date('d-m-Y') }}" placeholder="Pilih tanggal akhir"
                                        autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-xl-4 text-center col-form-label">No. Invoice</label>
                                <div class="col-xl-8">
                                    <input type="text" id="invoice" name="invoice" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-end mt-3">
                        <div class="col-auto">
                            <button type="button" class="btn bg-primary-600 mb-3" id="search-btn">
                                <span class="fal fa-search mr-1"></span> Cari
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-xl-12">
        <div id="panel-2" class="panel">
            <div class="panel-hdr">
                <h2>Penerimaan <span class="fw-300"><i>Pembayaran</i></span></h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="form-group row">
                                <label class="col-xl-4 text-center col-form-label">Cash / Bank Account</label>
                                <div class="col-xl-8">
                                    <select class="form-control select2 w-100" id="bank_account_id"
                                        name="bank_account_id" required>
                                        <option value="">Pilih Bank Account</option>
                                        @foreach ($banks as $bank)
                                            <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-xl-4 text-center col-form-label">Total Penerimaan</label>
                                <div class="col-xl-8">
                                    <input type="text" class="form-control money" id="total_penerimaan"
                                        value="Rp 0" readonly>
                                    <input type="hidden" name="total_penerimaan" id="total_penerimaan_hidden"
                                        value="0">
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="form-group row">
                                <label class="col-xl-4 text-center col-form-label">Tgl. Jurnal</label>
                                <div class="col-xl-8">
                                    <input type="text" name="tanggal_jurnal" class="form-control datepicker"
                                        value="{{ date('d-m-Y') }}" placeholder="Pilih tanggal jurnal"
                                        autocomplete="off" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
