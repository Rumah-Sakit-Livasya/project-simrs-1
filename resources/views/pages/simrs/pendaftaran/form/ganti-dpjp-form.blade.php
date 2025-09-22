<div class="modal fade" id="ganti-dpjp" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white">Edit Dokter Pasien</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fal fa-times"></i></span>
                </button>
            </div>
            <form action="{{ route('ganti.dpjp', $registration->id) }}" method="POST" autocomplete="off"
                novalidate="">
                @csrf
                <input type="hidden" readonly value="{{ $today }}" name="registration_date">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-xl-3 text-right">
                                <label for="tgl_ubah">Tanggal</label>
                            </div>
                            <div class="col-xl">
                                <input type="text" class="form-control" readonly id="tgl_ubah"
                                    value="{{ \Carbon\Carbon::now() }}" name="tgl_ubah">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-xl-3 text-right">
                                <label class="form-label font-weight-normal" for="alasan-ganti-dpjp">Alasan</label>
                            </div>
                            <div class="col-xl text-right">
                                <textarea class="form-control" id="alasan-ganti-dpjp" rows="5" name="alasan"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt-3" id="dokter_container">
                        <div class="row align-items-center">
                            <div class="col-xl-3 text-right">
                                <label class="form-label font-weight-normal" for="doctor_id">Dokter
                                </label>
                            </div>
                            <div class="col-xl">
                                <div class="form-group">
                                    <select class="form-control w-100" id="doctor_id" name="doctor_id">
                                        <option value=""></option>
                                        @foreach ($groupedDoctors as $department => $doctors)
                                            <optgroup label="{{ $department }}">
                                                @foreach ($doctors as $doctor)
                                                    <option value="{{ $doctor->id }}"
                                                        data-departement="{{ $department }}">
                                                        {{ $doctor->employee->fullname }}
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                </div>
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
