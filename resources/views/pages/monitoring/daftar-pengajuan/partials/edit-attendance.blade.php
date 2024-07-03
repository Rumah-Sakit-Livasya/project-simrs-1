<div class="modal fade p-0" id="edit-pengajuan-absen" tabindex="-2" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form autocomplete="off" novalidate method="post" id="update-form-attendance" enctype="multipart/form-data">
                @method('post')
                @csrf
                {{-- <input type="hidden" name="employee_id" value="{{ auth()->user()->employee->id }}"> --}}
                <div class="modal-header">
                    <h5 class="font-weight-bold">Edit Pengajuan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body py-0">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group mb-3">
                                <label for="date">Tanggal</label>
                                <div class="input-group">
                                    <input type="text" name="date"
                                        class="form-control @error('date') is-invalid @enderror" placeholder="Tanggal"
                                        id="date">
                                    <div class="input-group-append">
                                        <span class="input-group-text fs-xl">
                                            <i class="fal fa-calendar-alt"></i>
                                        </span>
                                    </div>
                                </div>
                                @error('date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group mb-3">
                                <label for="clockin">Clock In</label>
                                <div class="input-group">
                                    <input type="time" name="clockin"
                                        class="form-control @error('clockin') is-invalid @enderror"
                                        placeholder="Tanggal" id="clockin">
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <div class="ml-2 custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="check-clockin"
                                                    value="on">
                                                <label class="custom-control-label" for="check-clockin"></label>
                                            </div>
                                        </span>
                                    </div>
                                </div>
                                @error('clockin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group mb-3">
                                <label for="clockout">Clock Out</label>
                                <div class="input-group">
                                    <input type="time" name="clockout"
                                        class="form-control @error('clockout') is-invalid @enderror"
                                        placeholder="Tanggal" id="clockout">
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <div class="ml-2 custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="check-clockout"
                                                    value="on">
                                                <label class="custom-control-label" for="check-clockout"></label>
                                            </div>
                                        </span>
                                    </div>
                                </div>
                                @error('clockout')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group mb-3">
                                <label class="form-label">File <small>(opsional)</small></label>
                                <div class="img-preview-container">
                                    <img class="img-preview img-fluid mb-3 col-sm-5 p-0" style="border-radius: 11px">
                                </div>
                                <div class="custom-file">
                                    <input type="file" onchange="previewImageAbsen()" name="file"
                                        class="custom-file-input" id="file">
                                    <label class="custom-file-label" for="customFile">Unggah File</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group mb-3">
                                <label class="form-label" for="example-textarea">Deskripsi</label>
                                <textarea class="form-control" name="description" id="description" rows="5"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group mb-3">
                                <label class="form-label">Status</label>
                                <select class="select2 form-control w-100" id="is_approved_attendance"
                                    name="is_approved">
                                    <option value="Pending">Pending</option>
                                    <option value="Verifikasi">Verifikasi</option>
                                    <option value="Disetujui">Disetujui</option>
                                    <option value="Ditolak">Ditolak</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">
                        <div class="ikon-tambah">
                            <span class="fal fa-plus-circle mr-1"></span>
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
