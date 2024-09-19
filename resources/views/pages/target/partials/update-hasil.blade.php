<div class="modal fade p-0" id="ubah-data-hasil" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form autocomplete="off" novalidate method="post" id="update-form-hasil">
                @method('PUT')
                @csrf
                <div class="modal-header">
                    <h5 class="font-weight-bold">Edit OKR</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body py-0">
                    <div class="form-group">
                        <label class="form-label" for="hasil">Hasil</label>
                        <textarea class="form-control" id="hasil" rows="8" name="hasil"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="evaluasi">Evaluasi</label>
                        <textarea class="form-control" id="evaluasi" rows="8" name="evaluasi"></textarea>
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
