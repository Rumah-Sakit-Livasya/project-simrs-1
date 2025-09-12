<!-- Modal Tambah -->
<div class="modal fade" id="tambah-coa" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Chart of Account</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fal fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <form id="store-form">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="form-label" for="group_id">Grup COA</label>
                            <select class="form-control select2" id="group_id" name="group_id" required>
                                <option value="" disabled selected>Pilih Grup...</option>
                                @foreach ($groupCOA as $group)
                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback" id="group_id_error"></span>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-label" for="parent_id">Induk Akun (Parent)</label>
                            <select class="form-control select2" id="parent_id" name="parent_id">
                                <option value="">Tidak Ada Induk (Root)</option>
                            </select>
                            <span class="invalid-feedback" id="parent_id_error"></span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="form-label" for="code">Kode Akun</label>
                            <input type="text" class="form-control" id="code" name="code" required>
                            <span class="invalid-feedback" id="code_error"></span>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-label" for="name">Nama Akun</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                            <span class="invalid-feedback" id="name_error"></span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label class="form-label" for="default">Saldo Normal</label>
                            <select class="form-control" id="default" name="default" required>
                                <option value="" disabled selected>Pilih Saldo...</option>
                                <option value="Debet">Debet</option>
                                <option value="Credit">Credit</option>
                            </select>
                            <span class="invalid-feedback" id="default_error"></span>
                        </div>
                        <div class="form-group col-md-4 d-flex align-items-center pt-3">
                            <div class="custom-control custom-switch">
                                <input type="hidden" name="header" value="0">
                                <input type="checkbox" class="custom-control-input" id="header" name="header"
                                    value="1">
                                <label class="custom-control-label" for="header">Jadikan Header?</label>
                            </div>
                        </div>
                        <div class="form-group col-md-4 d-flex align-items-center pt-3">
                            <div class="custom-control custom-switch">
                                <input type="hidden" name="status" value="0">
                                <input type="checkbox" class="custom-control-input" id="status" name="status"
                                    value="1" checked>
                                <label class="custom-control-label" for="status">Aktif</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="description">Deskripsi</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary" form="store-form">Simpan</button>
            </div>
        </div>
    </div>
</div>
