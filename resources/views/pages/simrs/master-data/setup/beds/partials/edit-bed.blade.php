<div class="modal fade" id="modal-edit-bed" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document"> <!-- Menggunakan kelas modal-xl untuk ukuran ekstra besar -->
        <div class="modal-content">
            <form autocomplete="off" novalidate action="javascript:void(0)" method="POST" id="update-form">
                @method('PATCH')
                @csrf
                <div class="modal-header pb-1 mb-0">
                    <h5 class="modal-title font-weight-bold">Edit Grup Kelas Medis</h5>
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
                                    <label for="nama_tt">Nama T. Tidur <span
                                            class="text-danger fw-bold">*</span></label>
                                    <input type="text" class="form-control" name="nama_tt" id="nama_tt"
                                        placeholder="Masukan nama tempat tidur...">
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="no_tt">
                                        No T. Tidur <span class="text-danger fw-bold">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="no_tt" name="no_tt">
                                </div>
                            </div>
                            <div class="col-md-12 mt-3">
                                <div class="form-group">
                                    <label class="d-block">Is Tambahan ?</label>
                                    <div class="custom-control d-inline-block custom-radio mt-2 mr-2">
                                        <input type="radio" class="custom-control-input" id="is_tambahan_aktif"
                                            name="is_tambahan" value="1">
                                        <label class="custom-control-label" for="is_tambahan_aktif">Ya</label>
                                    </div>
                                    <div class="custom-control d-inline-block custom-radio mt-2">
                                        <input type="radio" class="custom-control-input" id="is_tambahan_tidak"
                                            name="is_tambahan" checked="" value="0">
                                        <label class="custom-control-label" for="is_tambahan_tidak">Tidak</label>
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
