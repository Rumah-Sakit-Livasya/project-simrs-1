<div class="modal fade p-0" id="non-aktif-modal" tabindex="-2" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form autocomplete="off" novalidate method="post" id="non-aktif-form">
                @method('post')
                @csrf
                <div class="modal-header">
                    <h5 class="font-weight-bold">Nonaktif Form</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body py-0">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="location">Tanggal Resign <small class="text-danger">(*kosongkan jika bukan
                                    resign)</small></label>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Masukan tanggal"
                                    name="tgl_resign" id="datepicker-3">
                                <div class="input-group-append">
                                    <span class="input-group-text fs-xl">
                                        <i class="fal fa-calendar-alt"></i>
                                    </span>
                                </div>
                            </div>
                            @error('tgl_resign')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-12 mt-2">
                            <div class="form-group">
                                <label for="location">Keterangan</label>
                                <textarea class="form-control" id="keterangan" name="keterangan" rows="5"></textarea>
                                @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm btn-danger" id="non-aktif-pegawai">
                        <div class="ikon-non-aktif-form btn-non-aktif-form">
                            <span class="fal fa-eye-slash mr-1"></span>
                            Non Aktifkan
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
