<div class="modal fade" id="tambah-coa" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form autocomplete="off" novalidate id="store-form" method="post">
                @method('post')
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Chart Of Account</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="group_id">Group COA</label>
                                <select class="form-control select2" id="group_id" name="group_id" required>
                                    <option value="" disabled selected>Pilih Group COA</option>
                                    @foreach ($groupCOA as $group)
                                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="parent_id">Parent COA</label>
                                <select class="form-control select2" id="parent_id" name="parent_id">
                                    <option value="" disabled selected>Pilih Parent COA</option>
                                    @foreach ($chartOfAccounts as $coa)
                                        <option value="{{ $coa->id }}">{{ $coa->code }} - {{ $coa->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Apakah Header?</label>
                                <div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="header_yes" name="header" value="1"
                                            class="custom-control-input" required>
                                        <label class="custom-control-label" for="header_yes">Ya</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="header_no" name="header" value="0"
                                            class="custom-control-input" required>
                                        <label class="custom-control-label" for="header_no">Tidak</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="code">Kode COA</label>
                                <input type="text" class="form-control" id="code" name="code"
                                    placeholder="Masukkan Kode COA" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Nama COA</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Masukkan Nama COA" required>
                            </div>
                            <div class="form-group">
                                <label>Default</label>
                                <div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="default_debet" name="default" value="Debet"
                                            class="custom-control-input" required>
                                        <label class="custom-control-label" for="default_debet">Debet</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="default_credit" name="default" value="Credit"
                                            class="custom-control-input" required>
                                        <label class="custom-control-label" for="default_credit">Credit</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Status</label>
                                <div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="status_active" name="status" value="Active"
                                            class="custom-control-input" required>
                                        <label class="custom-control-label" for="status_active">Aktif</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="status_inactive" name="status" value="Inactive"
                                            class="custom-control-input" required>
                                        <label class="custom-control-label" for="status_inactive">Tidak Aktif</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="description">Deskripsi</label>
                                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Masukkan Deskripsi"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">
                        <span class="fal fa-plus-circle mr-1"></span>
                        Tambah
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
