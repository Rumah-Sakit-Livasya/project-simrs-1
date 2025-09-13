<div class="modal fade" id="modal-tambah-tipe" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document"> <!-- Menggunakan kelas modal-xl untuk ukuran ekstra besar -->
        <div class="modal-content">
            <form autocomplete="off" novalidate action="javascript:void(0)" method="post" id="store-form">
                @method('post')
                @csrf
                <div class="modal-header pb-1 mb-0">
                    <h5 class="modal-title font-weight-bold">Tambah Tipe</h5>
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
                            <div class="col-md-6 mt-1">
                                <div class="form-group">
                                    <label for="tipe">Nama Tipe <span class="text-danger fw-bold">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="tipe" name="tipe">
                                </div>
                            </div>
                            <div class="col-md-6 mt-1">
                                <div class="form-group">
                                    <label for="persentase">Persentase <span class="text-danger fw-bold">*</span>
                                    </label>
                                    <input type="number" class="form-control" id="persentase" name="persentase">
                                </div>
                            </div>
                            <div class="col-md-3 mt-3">
                                <div class="form-group">
                                    <label class="d-block">Operator</label>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" name="operator" class="custom-control-input"
                                            id="is_operator_ya_tambah" value=1>
                                        <label class="custom-control-label" for="is_operator_ya_tambah">Ya</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" value=0 class="custom-control-input" name="operator"
                                            id="is_operator_tidak_tambah" checked="">
                                        <label class="custom-control-label" for="is_operator_tidak_tambah">Tidak</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mt-3">
                                <div class="form-group">
                                    <label class="d-block">Anestesi</label>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" name="anestesi" class="custom-control-input"
                                            id="is_anestesi_ya_tambah" value=1>
                                        <label class="custom-control-label" for="is_anestesi_ya_tambah">Ya</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" value=0 class="custom-control-input" name="anestesi"
                                            id="is_anestesi_tidak_tambah" checked="">
                                        <label class="custom-control-label" for="is_anestesi_tidak_tambah">Tidak</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mt-3">
                                <div class="form-group">
                                    <label class="d-block">Prediatric</label>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" name="prediatric" class="custom-control-input"
                                            id="is_prediatric_ya_tambah" value=1>
                                        <label class="custom-control-label" for="is_prediatric_ya_tambah">Ya</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" value=0 class="custom-control-input" name="prediatric"
                                            id="is_prediatric_tidak_tambah" checked="">
                                        <label class="custom-control-label"
                                            for="is_prediatric_tidak_tambah">Tidak</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mt-3">
                                <div class="form-group">
                                    <label class="d-block">Room</label>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" name="room" class="custom-control-input"
                                            id="is_room_ya_tambah" value=1>
                                        <label class="custom-control-label" for="is_room_ya_tambah">Ya</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" value=0 class="custom-control-input" name="room"
                                            id="is_room_tidak_tambah" checked="">
                                        <label class="custom-control-label" for="is_room_tidak_tambah">Tidak</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mt-3">
                                <div class="form-group">
                                    <label class="d-block">Observasi</label>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" name="observasi" class="custom-control-input"
                                            id="is_observasi_ya_tambah" value=1>
                                        <label class="custom-control-label" for="is_observasi_ya_tambah">Ya</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" value=0 class="custom-control-input" name="observasi"
                                            id="is_observasi_tidak_tambah" checked="">
                                        <label class="custom-control-label"
                                            for="is_observasi_tidak_tambah">Tidak</label>
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
