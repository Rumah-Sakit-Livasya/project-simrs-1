<div class="modal fade  p-0" id="tambah-data" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form autocomplete="off" novalidate method="post" id="store-form">
                @method('post')
                @csrf
                <div class="modal-header">
                    <h5 class="font-weight-bold">Tambah Target</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body py-0">
                    {{-- Form Fields --}}
                    <div class="form-group">
                        <input type="hidden" class="form-control" id="organization_id" name="organization_id"
                            placeholder="Masukkan Organization ID"
                            value="{{ auth()->user()->employee->organization->id }}" required>
                        <div class="invalid-feedback">Organization ID wajib diisi.</div>
                    </div>
                    <div class="form-group">
                        <input type="hidden" class="form-control" id="user_id" name="user_id"
                            placeholder="Masukkan User ID" value="{{ auth()->user()->id }}" required>
                        <div class="invalid-feedback">User ID wajib diisi.</div>
                    </div>
                    <div class="form-group">
                        <label clas for="title">Title</label>
                        <input type="text" class="form-control" id="title" name="title"
                            placeholder="Masukkan Title" required>
                        <div class="invalid-feedback">Title wajib diisi.</div>
                    </div>
                    <div class="form-group">
                        <label clas for="actual">Actual</label>
                        <input type="number" step="any" class="form-control" id="actual" name="actual"
                            placeholder="Masukkan Actual" required>
                        <div class="invalid-feedback">Actual wajib diisi dengan angka atau desimal.</div>
                    </div>
                    <div class="form-group">
                        <label clas for="target">Target</label>
                        <input type="number" step="any" class="form-control" id="target" name="target"
                            placeholder="Masukkan Target" required>
                        <div class="invalid-feedback">Target wajib diisi dengan angka atau desimal.</div>
                    </div>
                    <div class="form-group">
                        <label clas for="min_target">Min Target</label>
                        <input type="number" step="any" class="form-control" id="min_target" name="min_target"
                            placeholder="Masukkan Min Target" required>
                        <div class="invalid-feedback">Min Target wajib diisi dengan angka atau desimal.</div>
                    </div>
                    <div class="form-group">
                        <label clas for="max_target">Max Target</label>
                        <input type="number" step="any" class="form-control" id="max_target" name="max_target"
                            placeholder="Masukkan Max Target" required>
                        <div class="invalid-feedback">Max Target wajib diisi dengan angka atau desimal.</div>
                    </div>
                </div>
                <div class="modal-footer mt-2">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">
                        <div class="ikon-tambah">
                            <span class="fal fa-plus-circle mr-1"></span>
                            Tambah
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
