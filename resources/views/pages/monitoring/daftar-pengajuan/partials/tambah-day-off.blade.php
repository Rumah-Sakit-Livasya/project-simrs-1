<div class="modal fade p-0" id="tambah-pengajuan-cuti-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form autocomplete="off" action="#" novalidate method="post" id="tambah-day-off-req-form"
                enctype="multipart/form-data">
                @method('POST')
                @csrf
                <div class="modal-header">
                    <h5 class="font-weight-bold">Tambah Cuti</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body py-0 mt-2">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="Tanggal">Nama</label>
                                <select class="select2 form-control w-100" id="employee_id_tambah" name="employee_id">
                                    @foreach ($employees as $item)
                                        <option value="{{ $item->id }}">{{ $item->fullname }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="Tanggal">Jenis Cuti</label>
                                <div class="input-group">
                                    <select class="select2 form-control w-100" id="attendance_code_id_tambah"
                                        name="attendance_code_id">
                                        @foreach ($attendance_codes as $item)
                                            <option value="{{ $item->id }}">{{ $item->code }} -
                                                {{ $item->description }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-body py-0 mt-2">
                    <div class="form-group">
                        <label for="Tanggal">Tanggal</label>
                        <div class="input-group">
                            <input type="text" id="date_tambah" class="form-control" placeholder="Select a date"
                                aria-label="date" aria-describedby="datepicker-modal-2" name="date">
                            <div class="input-group-prepend">
                                <span class="input-group-text fs-xl"><i class="fal fa-calendar"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-body py-0 mt-2">
                    <div class="form-group">
                        <label class="form-label" for="keterangan">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-body py-0 mt-2">
                    <div class="form-group mb-3">
                        <label class="form-label">File <small>(opsional)</small></label>
                        <div class="img-preview-container">
                            <img class="img-preview img-fluid mb-3 col-sm-5 p-0" style="border-radius: 11px">
                        </div>
                        <div class="custom-file">
                            <input type="file" onchange="previewImageDayOffTambah()" name="photo"
                                class="custom-file-input" id="photo_tambah">
                            <label class="custom-file-label" for="customFile">Unggah File</label>
                        </div>
                    </div>
                </div>
                <div class="modal-body py-0 mt-2">
                    <div class="form-group">
                        <label for="Status">Status</label>
                        <select class="select2 form-control w-100" id="is_approved_tambah" name="is_approved">
                            <option value="Pending">Pending</option>
                            <option value="Verifikasi">Verifikasi</option>
                            <option value="Disetujui" selected>Disetujui</option>
                            <option value="Ditolak">Ditolak</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer mt-3">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm btn-primary">
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
