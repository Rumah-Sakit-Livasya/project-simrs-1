<div class="modal fade" id="modal-tambah-persalinan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document"> <!-- Menggunakan kelas modal-xl untuk ukuran ekstra besar -->
        <div class="modal-content">
            <form autocomplete="off" novalidate action="javascript:void(0)" method="post" id="store-form">
                @method('post')
                @csrf
                <div class="modal-header pb-1 mb-0">
                    <h5 class="modal-title font-weight-bold">Tambah Persalinan</h5>
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
                                    <label for="tipe">Tipe <span class="help-block text-danger">*</span></label>
                                    <select class="select2 form-control w-100" id="tipe" name="tipe">
                                        <option value="Tarif Operator">Tarif Operator</option>
                                        <option value="Tarif Anestesi">Tarif Anestesi</option>
                                        <option value="Tarif Resusitasi">Tarif Resusitasi</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="kode">Kode <span class="text-danger fw-bold">*</span></label>
                                    <input type="text" value="{{ old('kode') }}" class="form-control"
                                        name="kode" placeholder="Masukan kode persalinan...">
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="nama_persalinan">Nama Persalinan <span
                                            class="text-danger fw-bold">*</span></label>
                                    <input type="text" value="{{ old('nama_persalinan') }}" class="form-control"
                                        name="nama_persalinan" placeholder="Masukan nama persalinan...">
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="nama_billing">Nama Billing <span
                                            class="text-danger fw-bold">*</span></label>
                                    <input type="text" value="{{ old('nama_billing') }}" class="form-control"
                                        name="nama_billing" placeholder="Masukan nama persalinan...">
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
