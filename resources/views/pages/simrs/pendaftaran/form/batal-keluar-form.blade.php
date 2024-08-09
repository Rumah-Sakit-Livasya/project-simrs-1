<div class="modal fade" id="batal-keluar" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white">Batal Keluar Pasien</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fal fa-times"></i></span>
                </button>
            </div>
            <form action="{{ route('batal.keluar', $registration->id) }}" method="POST" autocomplete="off"
                novalidate="">
                @csrf
                <input type="hidden" name="user_id" value="{{ $registration->user->id }}">
                <div class="modal-body">
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
                                <label class="form-label font-weight-normal" for="alasan">Alasan</label>
                            </div>
                            <div class="col-xl text-right">
                                <textarea class="form-control" id="alasan" rows="5" name="alasan"></textarea>
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
                                    value="{{ $registration->user->email }}" readonly>
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
