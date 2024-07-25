<div class="modal fade" id="tambah-dokumen-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document"> <!-- Menggunakan kelas modal-xl untuk ukuran ekstra besar -->
        <div class="modal-content">
            <form autocomplete="off" novalidate action="javascript:void(0)" method="post" id="tambah-dokumen-form">
                @method('post')
                @csrf
                <div class="modal-header pb-1 mb-0">
                    <h4 class="modal-title font-weight-bold text-primary">Tambah Dokumen</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body pt-1 row">
                    <div class="col-md-12">
                        <hr class="mt-1" style="border-color: #fd3995;">
                    </div>
                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <label class="form-label" for="nama">Nama Dokumen<span
                                    class="text-danger fw-bold">*</span></label>
                            <input type="text" value="{{ old('nama') }}"
                                class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama"
                                placeholder="nama">
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <label class="form-label">File<span class="text-danger fw-bold">*</span></label>
                            <div class="custom-file">
                                <input type="file" name="file" class="custom-file-input" id="file">
                                <label class="custom-file-label" for="customFile">Unggah File</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <label class="form-label">Hard Copy<span class="text-danger fw-bold">*</span></label>
                            <div class="frame-wrap">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input" id="defaultInline1Radio"
                                        name="hard_copy">
                                    <label class="custom-control-label" for="defaultInline1Radio">Ada</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input" id="defaultInline2Radio"
                                        name="hard_copy" checked="">
                                    <label class="custom-control-label" for="defaultInline2Radio">Tidak Ada</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" data-backdrop="static" data-keyboard="false" id="btn-edit"
                        class="btn mx-1 btn-edit btn-primary text-white" title="Hapus">
                        <div class="ikon-edit">
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
