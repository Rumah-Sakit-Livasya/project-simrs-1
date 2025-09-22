<div class="modal fade" id="ganti-diagnosa" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white">Edit Dokter Pasien</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fal fa-times"></i></span>
                </button>
            </div>
            <form action="{{ route('ganti.diagnosa', $registration->id) }}" method="POST" autocomplete="off"
                novalidate="">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-xl-3 text-right">
                                <label for="medical_record_number">No RM</label>
                            </div>
                            <div class="col-xl">
                                <input type="text" class="form-control" readonly id="medical_record_number"
                                    value="{{ $patient->medical_record_number }}" name="medical_record_number">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-xl-3 text-right">
                                <label for="registration_number">No Registrasi</label>
                            </div>
                            <div class="col-xl">
                                <input type="text" class="form-control" readonly id="registration_number"
                                    value="{{ $registration->registration_number }}" name="registration_number">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-xl-3 text-right">
                                <label class="form-label font-weight-normal" for="diagnosa_awal">Diagnosa</label>
                            </div>
                            <div class="col-xl text-right">
                                <textarea class="form-control" id="diagnosa_awal" rows="5" name="diagnosa_awal">{{ $registration->diagnosa_awal }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-xl-3 text-right">
                                <label class="form-label font-weight-normal" for="alasan-ganti-diagnosa">Alasan</label>
                            </div>
                            <div class="col-xl text-right">
                                <textarea class="form-control" id="alasan-ganti-diagnosa" rows="5" name="alasan"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-xl-3 text-right">
                                <label for="email">User</label>
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
