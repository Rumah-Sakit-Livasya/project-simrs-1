<div class="modal fade" id="modal-edit-penjamin" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document"> <!-- Menggunakan kelas modal-xl untuk ukuran ekstra besar -->
        <div class="modal-content">
            <form autocomplete="off" novalidate action="javascript:void(0)" method="post" id="update-form">
                @method('post')
                @csrf
                <div class="modal-header pb-1 mb-0">
                    <h5 class="modal-title font-weight-bold">Edit Peralatan</h5>
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
                                    <label for="kode">Kode <span class="text-danger fw-bold">*</span></label>
                                    <input type="text" value="{{ old('kode') }}" class="form-control"
                                        name="kode" placeholder="Masukan kode alat...">
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="nama">Nama Alat <span class="text-danger fw-bold">*</span></label>
                                    <input type="text" value="{{ old('nama') }}" class="form-control"
                                        name="nama" placeholder="Masukan nama alat...">
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
                                        <input type="radio" name="is_req_dokter" value=1 class="custom-control-input"
                                            id="is_req_dokter_ya">
                                        <label class="custom-control-label" for="is_req_dokter_ya">Ya</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" class="custom-control-input" name="is_req_dokter"
                                            id="is_req_dokter_tidak" checked="" value=0>
                                        <label class="custom-control-label" for="is_req_dokter_tidak">Tidak</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer pt-0">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" data-backdrop="static" data-keyboard="false" id="btn-edit"
                        class="btn mx-1 btn-edit btn-primary text-white" title="Hapus">
                        <div class="ikon-edit">
                            <span class="fal fa-plus-circle mr-1"></span>Update
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
