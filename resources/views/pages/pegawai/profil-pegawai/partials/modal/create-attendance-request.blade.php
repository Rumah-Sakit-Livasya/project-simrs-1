<div class="modal fade font-weight-bold p-0" id="create-attendance-form" tabindex="-2" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form autocomplete="off" novalidate method="post" id="store-attendance-request"
                enctype="multipart/form-data">
                @method('post')
                @csrf
                <div class="modal-header">
                    <h5 class="font-weight-bold">Pengajuan Perubahan</h5>
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
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="customFile">
                                    <label class="custom-file-label" for="customFile">Unggah File</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group mb-3">
                                <label class="form-label" for="example-textarea">Deskripsi</label>
                                <textarea class="form-control" id="example-textarea" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
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

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var clockin = document.getElementById("check-clockin");
        var clockout = document.getElementById("check-clockout");
        var inputClockIn = document.getElementById("clockin");
        var inputClockOut = document.getElementById("clockout");

        // Mengecek status awal clockin
        if (!clockin.checked) {
            inputClockIn.disabled = true;
        }
        if (!clockout.checked) {
            inputClockOut.disabled = true;
        }

        // Menambahkan event listener untuk perubahan pada clockin
        clockin.addEventListener("change", function() {
            if (!clockin.checked) {
                inputClockIn.disabled = true;
            } else {
                inputClockIn.disabled = false;
            }
        });

        clockout.addEventListener("change", function() {
            if (!clockout.checked) {
                inputClockOut.disabled = true;
            } else {
                inputClockOut.disabled = false;
            }
        });
    });
</script>
