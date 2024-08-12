<div class="modal fade" id="modal-tambah-tindakan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document"> <!-- Menggunakan kelas modal-xl untuk ukuran ekstra besar -->
        <div class="modal-content">
            <form autocomplete="off" novalidate action="javascript:void(0)" method="post" id="store-form">
                @method('post')
                @csrf
                <div class="modal-header pb-1 mb-0">
                    <h5 class="modal-title font-weight-bold">Tambah Tindakan Medis</h5>
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
                                    <label for="departement_id">Grup Tindakan <span
                                            class="help-block text-danger">*</span></label>
                                    <select class="select2 form-control w-100" id="departement_id"
                                        name="grup_tindakan_medis_id">
                                        <option value=""></option>
                                        @foreach ($grup_tindakan as $row)
                                            <option value="{{ $row->id }}">
                                                {{ $row->nama_grup }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="kode">Kode <span class="text-danger fw-bold">*</span></label>
                                    <input type="text" class="form-control" name="kode"
                                        placeholder="Masukan nama grup...">
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="nama_tindakan">Nama Tindakan <span class="text-danger fw-bold">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="nama_tindakan" name="nama_tindakan">
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="nama_billing">Nama Billing <span class="text-danger fw-bold">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="nama_billing" name="nama_billing">
                                </div>
                            </div>
                            <div class="col-md-4 mt-3">
                                <div class="form-group">
                                    <label class="d-block">Apakah Konsul ?</label>
                                    <div class="custom-control d-inline-block custom-radio mt-2 mr-2">
                                        <input type="radio" class="custom-control-input" id="is_konsul_aktif"
                                            name="is_konsul" value="1">
                                        <label class="custom-control-label" for="is_konsul_aktif">Ya</label>
                                    </div>
                                    <div class="custom-control d-inline-block custom-radio mt-2">
                                        <input type="radio" class="custom-control-input" id="is_konsul_tidak"
                                            name="is_konsul" checked="" value="0">
                                        <label class="custom-control-label" for="is_konsul_tidak">Tidak</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mt-3">
                                <div class="form-group">
                                    <label class="d-block">Auto Charge</label>
                                    <div class="custom-control d-inline-block custom-radio mt-2 mr-2">
                                        <input type="radio" class="custom-control-input" id="auto_charge_aktif"
                                            name="auto_charge" value="1">
                                        <label class="custom-control-label" for="auto_charge_aktif">Ya</label>
                                    </div>
                                    <div class="custom-control d-inline-block custom-radio mt-2">
                                        <input type="radio" class="custom-control-input" id="auto_charge_tidak"
                                            name="auto_charge" checked="" value="0">
                                        <label class="custom-control-label" for="auto_charge_tidak">Tidak</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mt-3">
                                <div class="form-group">
                                    <label class="d-block">Apakah Vaksin ?</label>
                                    <div class="custom-control d-inline-block custom-radio mt-2 mr-2">
                                        <input type="radio" class="custom-control-input" id="is_vaksin_aktif"
                                            name="is_vaksin" value="1">
                                        <label class="custom-control-label" for="is_vaksin_aktif">Ya</label>
                                    </div>
                                    <div class="custom-control d-inline-block custom-radio mt-2">
                                        <input type="radio" class="custom-control-input" id="is_vaksin_tidak"
                                            name="is_vaksin" checked="" value="0">
                                        <label class="custom-control-label" for="is_vaksin_tidak">Tidak</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="mapping_rl_13">Mapping RL (1.3 dan 3.1) <span
                                            class="help-block text-danger">*</span></label>
                                    <select class="select2 form-control w-100" id="mapping_rl_13"
                                        name="mapping_rl_13">
                                        <option value="1">123</option>
                                        <option value="2">321</option>
                                        <option value="3">1234</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="mapping_rl_34">Mapping RL (3.4) <span
                                            class="help-block text-danger">*</span></label>
                                    <select class="select2 form-control w-100" id="mapping_rl_34"
                                        name="mapping_rl_34">
                                        <option value="1">123</option>
                                        <option value="2">321</option>
                                        <option value="3">1234</option>
                                    </select>
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
