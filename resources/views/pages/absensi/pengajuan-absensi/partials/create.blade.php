<div class="modal fade font-weight-bold p-0" id="create-attendance-form" tabindex="-2" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form autocomplete="off" novalidate method="post" id="store-form" enctype="multipart/form-data">
                @method('post')
                @csrf
                {{-- <input type="hidden" name="employee_id" value="{{ auth()->user()->employee->id }}"> --}}
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
                                        class="form-control @error('date') is-invalid @enderror" placeholder="Tanggal" readonly
                                        value="{{ $pengajuan ? \Carbon\Carbon::parse($pengajuan->tanggal)->format('Y-m-d') : now()->format('Y-m-d') }}"
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
                                    <input type="text" name="clockin" value="{{ $pengajuan->clock_in }}"
                                        class="form-control @error('clockin') is-invalid @enderror" readonly
                                        placeholder="Tanggal" id="clockin" value="">
                                    {{-- <div class="input-group-append">
                                        <span class="input-group-text">
                                            <div class="ml-2 custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="check-clockin"
                                                    name="check-clockin" value="on">
                                                <label class="custom-control-label" for="check-clockin"></label>
                                            </div>
                                        </span>
                                    </div> --}}
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
                                    <input type="time" name="clockout" value="{{ $pengajuan->clock_out }}"
                                        class="form-control @error('clockout') is-invalid @enderror" readonly
                                        placeholder="Tanggal" id="clockout">
                                    {{-- <div class="input-group-append">
                                        <span class="input-group-text">
                                            <div class="ml-2 custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="check-clockout"
                                                    name="check-clockout" value="on">
                                                <label class="custom-control-label" for="check-clockout"></label>
                                            </div>
                                        </span>
                                    </div> --}}
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
                                    <input type="file" onchange="previewImage()" name="file"
                                        class="custom-file-input" id="file">
                                    <label class="custom-file-label" for="customFile">Unggah File</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group mb-3">
                                <label class="form-label" for="example-textarea">Deskripsi</label>
                                <textarea class="form-control" name="description" id="example-textarea" rows="5"></textarea>
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
