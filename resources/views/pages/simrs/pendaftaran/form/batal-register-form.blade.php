<div class="modal fade" id="batal-registrasi" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white">Batal Registrasi Pasien</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fal fa-times"></i></span>
                </button>
            </div>
            <form action="{{ route('batal.register', $registration->id) }}" method="POST" autocomplete="off"
                novalidate="">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning fade show">
                        <div class="d-flex align-items-center">
                            <div class="alert-icon width-6">
                                <div class='icon-stack icon-stack-lg'>
                                    <i class="base base-7 icon-stack-3x opacity-100 color-warning-500 "></i> <i
                                        class="base base-14 icon-stack-2x opacity-50 color-warning-300 "></i> <i
                                        class="fal fa-info icon-stack-1x opacity-100 color-white "></i>
                                </div>
                            </div>
                            <div class="flex-1 text-center">
                                <span class="h3">PERHATIAN</span>
                                <br>
                                FORM INI AKAN MENGHAPUS DATA REGISTRASI PASIEN BERSANGKUTAN!
                                <br>
                                (TERMASUK DATA REKAM MEDIS, CATATAN DOKTER, DLL.)
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-xl-3 text-right">
                                <label for="tgl_batal">Tanggal Batal</label>
                            </div>
                            <div class="col-xl">
                                <input type="text" class="form-control" readonly id="tgl_batal"
                                    value="{{ \Carbon\Carbon::now() }}" name="tgl_batal">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-xl-3 text-right">
                                <label class="form-label font-weight-normal" for="alasan-batal-regis">Alasan</label>
                            </div>
                            <div class="col-xl text-right">
                                <textarea class="form-control" id="alasan-batal-regis" rows="5" name="alasan"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-xl-3 text-right">
                                <label for="email">User Cancel</label>
                            </div>
                            <div class="col-xl text-right">
                                <input type="text" class="form-control" id="email" name="email"
                                    value="{{ auth()->user()->email }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt-3">
                        <div class="row">
                            <div class="col-xl-3 text-right">
                                <label for="password">Password</label>
                            </div>
                            <div class="col-xl text-right">
                                <input type="password" class="form-control" name="password" id="password"
                                    placeholder="Password" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
