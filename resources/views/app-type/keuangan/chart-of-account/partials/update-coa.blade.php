<!-- Modal Edit -->
<div class="modal fade" id="edit-coa" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Chart of Account</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fal fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <form id="update-form">
                    <input type="hidden" id="edit_coa_id" name="id">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="form-label" for="edit_group_id">Grup COA</label>
                            <select class="form-control select2" id="edit_group_id" name="group_id" required>
                                <option value="" disabled>Pilih Grup...</option>
                                @foreach ($groupCOA as $group)
                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback" id="edit_group_id_error"></span>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-label" for="edit_parent_id">Induk Akun (Parent)</label>
                            <select class="form-control select2" id="edit_parent_id" name="parent_id">
                                <option value="">Tidak Ada Induk (Root)</option>
                            </select>
                            <span class="invalid-feedback" id="edit_parent_id_error"></span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="form-label" for="edit_code">Kode Akun</label>
                            <input type="text" class="form-control" id="edit_code" name="code" required>
                            <span class="invalid-feedback" id="edit_code_error"></span>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-label" for="edit_name">Nama Akun</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                            <span class="invalid-feedback" id="edit_name_error"></span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label class="form-label" for="edit_default">Saldo Normal</label>
                            <select class="form-control" id="edit_default" name="default" required>
                                <option value="" disabled>Pilih Saldo...</option>
                                <option value="Debet">Debet</option>
                                <option value="Credit">Credit</option>
                            </select>
                            <span class="invalid-feedback" id="edit_default_error"></span>
                        </div>
                        <div class="form-group col-md-4 d-flex align-items-center pt-3">
                            <div class="custom-control custom-switch">
                                <input type="hidden" name="header" value="0">
                                <input type="checkbox" class="custom-control-input" id="edit_header" name="header"
                                    value="1">
                                <label class="custom-control-label" for="edit_header">Jadikan Header?</label>
                            </div>
                        </div>
                        <div class="form-group col-md-4 d-flex align-items-center pt-3">
                            <div class="custom-control custom-switch">
                                <input type="hidden" name="status" value="0">
                                <input type="checkbox" class="custom-control-input" id="edit_status" name="status"
                                    value="1">
                                <label class="custom-control-label" for="edit_status">Aktif</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="edit_description">Deskripsi</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary" form="update-form">Simpan Perubahan</button>
            </div>
        </div>
    </div>
</div>
