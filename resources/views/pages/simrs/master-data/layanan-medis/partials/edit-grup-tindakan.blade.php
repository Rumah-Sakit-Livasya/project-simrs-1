<div class="modal fade" id="modal-edit-grup-tindakan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document"> <!-- Menggunakan kelas modal-xl untuk ukuran ekstra besar -->
        <div class="modal-content">
            <form autocomplete="off" novalidate action="javascript:void(0)" method="POST" id="update-form">
                @method('PATCH')
                @csrf
                <div class="modal-header pb-1 mb-0">
                    <h5 class="modal-title font-weight-bold">Edit Grup Tindakan Medis</h5>
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
                                    <label for="departement_id">Departement <span
                                            class="help-block text-danger">*</span></label>
                                    <select class="select2 form-control w-100" id="departement_id1"
                                        name="departement_id">
                                        <option value=""></option>
                                        @foreach ($departements as $row)
                                            <option value="{{ $row->id }}">
                                                {{ $row->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="nama_grup">Nama Grup <span class="text-danger fw-bold">*</span></label>
                                    <input type="text" value="{{ old('nama_grup') }}" class="form-control"
                                        name="nama_grup" placeholder="Masukan nama grup...">
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="coa_pendapatan">COA Pendapatan <span
                                            class="text-danger fw-bold">*</span> </label>
                                    <input type="text" class="form-control" id="coa_pendapatan"
                                        name="coa_pendapatan">
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="coa_biaya">COA Biaya <span class="text-danger fw-bold">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="coa_biaya" name="coa_biaya">
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="coa_prasarana">COA Prasarana
                                    </label>
                                    <input type="text" class="form-control" id="coa_prasarana" name="coa_prasarana">
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="coa_bhp">COA BHP
                                    </label>
                                    <input type="text" class="form-control" id="coa_bhp" name="coa_bhp">
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="address" class="d-block">Status <span
                                            class="text-danger fw-bold">*</span></label>
                                    <div class="custom-control d-inline-block custom-radio mt-2 mr-2">
                                        <input type="radio" class="custom-control-input" id="Aktif_edit"
                                            name="status" checked="" value="1">
                                        <label class="custom-control-label" for="Aktif_edit">Aktif</label>
                                    </div>
                                    <div class="custom-control d-inline-block custom-radio mt-2">
                                        <input type="radio" class="custom-control-input" id="Tidak_edit"
                                            name="status" value="0">
                                        <label class="custom-control-label" for="Tidak_edit">Tidak Aktif</label>
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
