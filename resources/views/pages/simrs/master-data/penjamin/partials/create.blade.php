<div class="modal fade" id="modal-tambah-penjamin" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document"> <!-- Menggunakan kelas modal-xl untuk ukuran ekstra besar -->
        <div class="modal-content">
            <form autocomplete="off" novalidate action="javascript:void(0)" method="post" id="store-form">
                @method('post')
                @csrf
                <div class="modal-header pb-1 mb-0">
                    <h5 class="modal-title font-weight-bold">Tambah Penjamin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body py-2 row">
                    <div class="col-md-12">
                        <hr style="border-color: #dedede;" class="mb-1 mt-1">
                    </div>
                    <div class="col-md-12 mb-3">
                        <div class="row">
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="kode">
                                        Mulai Kerjasama <span class="text-danger fw-bold">*</span>
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text fs-xl"><i class="fal fa-calendar"></i></span>
                                        </div>
                                        <input type="text" name="mulai_kerjasama" id="mulai_kerjasama"
                                            class="form-control date-picker-modal" placeholder="Select a date"
                                            aria-label="date" aria-describedby="mulai_kerjasama">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="kode">
                                        Akhir Kerjasama <span class="text-danger fw-bold">*</span>
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text fs-xl"><i class="fal fa-calendar"></i></span>
                                        </div>
                                        <input type="text" name="akhir_kerjasama" id="akhir_kerjasama"
                                            class="form-control date-picker-modal" placeholder="Select a date"
                                            aria-label="date" aria-describedby="akhir_kerjasama">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="kode_perusahaan">Kode Perusahaan</label>
                                    <input type="text" value="{{ old('kode_perusahaan') }}" class="form-control"
                                        name="kode_perusahaan" placeholder="Masukan kode perusahaan...">
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="tipe_perusahaan">Tipe Perusahaan</label>
                                    <select name="tipe_perusahaan" id="tipe_perusahaan" class="form-control select2">
                                        <option value="Asuransi Non Penjamin">Asuransi Non Penjamin</option>
                                        <option value="Asuransi Penjamin">Asuransi Penjamin</option>
                                        <option value="Lain-lain">Lain-lain</option>
                                        <option value="Perorangan / Pejabat Teras">Perorangan / Pejabat Teras</option>
                                        <option value="Perusahaan BUMN">Perusahaan BUMN</option>
                                        <option value="Perusahaan Kartu Kredit">Perusahaan Kartu Kredit</option>
                                        <option value="Perusahaan Swasta">Perusahaan Swasta</option>
                                        <option value="TPA">TPA</option>
                                        <option value="Yayasan">Yayasan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="satuan_pakai">Satuan Pakai <span
                                            class="text-danger fw-bold">*</span></label>
                                    <input type="text" value="{{ old('satuan_pakai') }}" class="form-control"
                                        name="satuan_pakai" placeholder="Masukan satuan pakai alat...">
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label class="d-block">Membutuhkan Dokter?</label>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" name="is_req_dokter" class="custom-control-input"
                                            id="is_req_dokter_ya_tambah" value=1>
                                        <label class="custom-control-label" for="is_req_dokter_ya_tambah">Ya</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" value=0 class="custom-control-input"
                                            name="is_req_dokter" id="is_req_dokter_tidak_tambah" checked="">
                                        <label class="custom-control-label"
                                            for="is_req_dokter_tidak_tambah">Tidak</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer pt-0">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" data-backdrop="static" data-keyboard="false" id="btn-tambah"
                        class="btn mx-1 btn-tambah btn-primary text-white" title="Hapus">
                        <div class="ikon-tambah">
                            <span class="fal fa-plus-circle mr-1"></span>Tambah
                        </div>
                        <div class="span spinner-text d-none">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Loading...
                        </div>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
