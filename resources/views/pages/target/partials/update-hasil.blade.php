<div class="modal fade p-0" id="ubah-data-hasil" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
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
                    <div class="row">
                        <div class="col-lg-6 mt-3">
                            <div class="form-group">
                                <label class="form-label" for="initiative">Initiative <i
                                        class="fas fa-info-circle text-primary"
                                        data-template="<div class='tooltip' role='tooltip'><div class='tooltip-inner bg-primary-500'></div></div>"
                                        data-toggle="tooltip"
                                        title="Jelaskan apa saja yang akan dikerjakan."></i></label>
                                <textarea class="form-control" id="initiative" rows="8" name="initiative"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-6 mt-3">
                            <div class="form-group">
                                <label class="form-label" for="key_result">Key Result <i
                                        class="fas fa-info-circle text-primary"
                                        data-template="<div class='tooltip' role='tooltip'><div class='tooltip-inner bg-primary-500'></div></div>"
                                        data-toggle="tooltip" title="Sebutkan indikator yang digunakan."></i></label>
                                <textarea class="form-control" id="key_result" rows="8" name="key_result"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-6 mt-3">
                            <div class="form-group">
                                <label class="form-label" for="hasil">Hasil <i
                                        class="fas fa-info-circle text-primary"
                                        data-template="<div class='tooltip' role='tooltip'><div class='tooltip-inner bg-primary-500'></div></div>"
                                        data-toggle="tooltip" title="Tuliskan hasil yang dicapai."></i></label>
                                <textarea class="form-control" id="hasil" rows="8" name="hasil"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-6 mt-3">
                            <div class="form-group">
                                <label class="form-label" for="goal">Goal <i class="fas fa-info-circle text-primary"
                                        data-template="<div class='tooltip' role='tooltip'><div class='tooltip-inner bg-primary-500'></div></div>"
                                        data-toggle="tooltip" title="Tentukan sasaran yang ingin dicapai."></i></label>
                                <textarea class="form-control" id="goal" rows="8" name="goal"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-6 mt-3">
                            <div class="form-group">
                                <label class="form-label" for="evaluasi">Evaluasi <i
                                        class="fas fa-info-circle text-primary"
                                        data-template="<div class='tooltip' role='tooltip'><div class='tooltip-inner bg-primary-500'></div></div>"
                                        data-toggle="tooltip"
                                        title="Jelaskan bagaimana evaluasi dilakukan."></i></label>
                                <textarea class="form-control" id="evaluasi" rows="8" name="evaluasi"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-6 mt-3">
                            <div class="form-group">
                                <label class="form-label" for="anggaran">Anggaran <i
                                        class="fas fa-info-circle text-primary"
                                        data-template="<div class='tooltip' role='tooltip'><div class='tooltip-inner bg-primary-500'></div></div>"
                                        data-toggle="tooltip"
                                        title="Tentukan anggaran yang akan dialokasikan."></i></label>
                                <textarea class="form-control" id="anggaran" rows="8" name="anggaran"></textarea>
                            </div>
                        </div>
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
