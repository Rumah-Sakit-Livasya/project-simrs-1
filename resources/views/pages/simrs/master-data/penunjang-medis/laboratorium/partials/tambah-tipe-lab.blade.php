<div class="modal fade" id="modal-tambah-tipe-laboratorium" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document"> <!-- Menggunakan kelas modal-xl untuk ukuran ekstra besar -->
        <div class="modal-content">
            <form autocomplete="off" novalidate action="javascript:void(0)" method="post" id="store-form">
                @method('post')
                @csrf
                <div class="modal-header pb-1 mb-0">
                    <h5 class="modal-title font-weight-bold">Tambah Tipe Laboratorium</h5>
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
                            <div class="col-md-12 mt-3">
                                <div class="form-group">
                                    <label for="nama_tipe">Tipe Kategori <span class="text-danger fw-bold">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="nama_tipe" name="nama_tipe">
                                </div>
                            </div>
                            <div class="col-md-12 mt-3">
                                <div class="form-group">
                                    <label class="d-block">Status</label>
                                    <div class="custom-control d-inline-block custom-radio mt-2 mr-2">
                                        <input type="radio" checked="" class="custom-control-input"
                                            id="status_aktif_tambah" name="status" value="1">
                                        <label class="custom-control-label" for="status_aktif_tambah">Aktif</label>
                                    </div>
                                    <div class="custom-control d-inline-block custom-radio mt-2">
                                        <input type="radio" class="custom-control-input" id="status_tidak_tambah"
                                            name="status" value="0">
                                        <label class="custom-control-label" for="status_tidak_tambah">Tidak</label>
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
