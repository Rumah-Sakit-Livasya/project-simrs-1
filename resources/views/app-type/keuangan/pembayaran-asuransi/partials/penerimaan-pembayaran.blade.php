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
                                    <input type="text" id="total_penerimaan" class="form-control" readonly>
                                    <input type="hidden" id="total_penerimaan_hidden" name="total_penerimaan_hidden">

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
