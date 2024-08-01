<div class="modal fade p-0 show active" id="tambah-potongan-modal" tabindex="-2" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="font-weight-bold">Update Potongan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fal fa-times"></i></span>
                </button>
            </div>
            <form method="post" id="form-update-potongan-payroll" enctype="multipart/form-data">
                <div class="modal-body py-0">
                    <div class="row">
                        <hr style="width: 100%; border-color: rgba(0, 0, 0, 0.387); margin-top: -3px">
                        <div class="col-md-12">

                            <div class="form-group mb-3">
                                <label class="form-label">File (Browser)</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="potongan" name="potongan">
                                    <label class="custom-file-label" for="potongan">Choose file</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer pt-0 pb-3">
                    <button type="button" id="update-potongan-tombol" class="btn btn-primary">Update Potongan</button>
                </div>
            </form>
        </div>
    </div>
</div>
