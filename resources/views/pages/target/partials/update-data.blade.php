<div class="modal fade p-0" id="ubah-data" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form autocomplete="off" novalidate method="post" id="update-form">
                @method('PUT')
                @csrf
                <div class="modal-header">
                    <h5 class="font-weight-bold">Edit OKR</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body py-0">
                    {{-- Form Fields --}}
                    <input type="hidden" class="form-control" id="organization_id" name="organization_id" required>
                    <input type="hidden" class="form-control" id="user_id" name="user_id" required>

                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" id="title" name="title"
                            placeholder="Masukkan Title" required>
                        <div class="invalid-feedback">Title wajib diisi.</div>
                    </div>
                    <div class="form-group">
                        <label for="actual">Actual</label>
                        <input type="number" step="any" class="form-control" id="actual" name="actual"
                            placeholder="Masukkan Actual" required>
                        <div class="invalid-feedback">Actual wajib diisi dengan angka atau desimal.</div>
                    </div>
                    <div class="form-group">
                        <label for="target">Target</label>
                        <input type="number" step="any" class="form-control" id="target" name="target"
                            placeholder="Masukkan Target" required>
                        <div class="invalid-feedback">Target wajib diisi dengan angka atau desimal.</div>
                    </div>
                    <div class="form-group">
                        <label for="min_target">Min Target</label>
                        <input type="number" step="any" class="form-control" id="min_target" name="min_target"
                            placeholder="Masukkan Min Target" required>
                        <div class="invalid-feedback">Min Target wajib diisi dengan angka atau desimal.</div>
                    </div>
                </div>
                <div class="modal-footer mt-2">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">
                        <div class="ikon-tambah">
                            <span class="fal fa-save mr-1"></span>
                            Update
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
