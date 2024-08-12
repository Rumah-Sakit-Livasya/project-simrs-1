<form action="{{ route('simpan.registrasi') }}" method="post">
    @csrf
    <input type="hidden" name="patient_id" value="{{ $patient->id }}">
    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
    <input type="hidden" name="employee_id" value="{{ auth()->user()->employee->id }}">
    <input type="hidden" name="registration_type" value="igd">
    <input type="hidden" name="poliklinik" value="UGD">
    <div class="row">
        <div class="col-xl-6">
            <div class="form-group">
                <div class="row align-items-center">
                    <div class="col-xl-4 text-right">
                        <label class="form-label" for="registration_date">
                            Tanggal Registrasi
                        </label>
                    </div>
                    <div class="col-xl-8">
                        <input type="text"
                            style="border: 0; border-bottom: 1.9px dashed #aaa; margin-top: -.5rem; border-radius: 0"
                            class="form-control" id="registration_date" readonly value="{{ $today }}"
                            name="registration_date">
                        @error('registration_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row align-items-center">
                    <div class="col-xl-4 text-right">
                        <label class="form-label" for="doctor_id">Dokter</label>
                    </div>
                    <div class="col-xl-8">
                        <div class="form-group">
                            <select class="select2 form-control w-100" id="doctor_id" name="doctor_id">
                                <option value=""></option>
                                @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor->id }}">
                                        {{ $doctor->employee->fullname }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row align-items-center">
                    <div class="col-xl-4 text-right">
                        <label class="form-label" for="igd_type">
                            Tipe *
                        </label>
                    </div>
                    <div class="col-xl-8">
                        <div class="form-group">
                            <select class="form-control w-100" id="type" name="igd_type">
                                <option selected></option>
                                <option value="darurat">Darurat</option>
                                <option value="darurat-tidak-gawat">Darurat Tidak Gawat</option>
                                <option value="gawat-darurat">Gawat Darurat</option>
                                <option value="gawat-tidak-darurat">Gawat Tidak Darurat</option>
                                <option value="tidak-gawat-tidak-darurat">Tidak Gawat Tidak Darurat</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row align-items-center">
                    <div class="col-xl-4 text-right">
                        <label class="form-label" for="penjamin">
                            Penjamin
                        </label>
                    </div>
                    <div class="col-xl-8">
                        <div class="form-group">
                            <select class="select2 form-control w-100" id="penjamin" name="penjamin_id">
                                <option selected></option>
                                @foreach ($penjamins as $penjamin)
                                    <option value="{{ $penjamin->id }}"
                                        {{ $penjamin->id === old('penjamin') ? 'selected' : '' }}>
                                        {{ $penjamin->nama_perusahaan }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="form-group">
                <div class="row align-items-center">
                    <div class="col-xl-4 text-right">
                        <label class="form-label" for="registration_date">
                            Kartu Pasien
                        </label>
                    </div>
                    <div class="col-xl-8">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="patient_card" name="patient_card">
                            <label class="custom-control-label" for="patient_card">Ya</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row align-items-center">
                    <div class="col-xl-4 text-right">
                        <label class="form-label" for="registration_date">
                            Rujukan
                        </label>
                    </div>
                    <div class="col-xl-8">
                        <div class="custom-control custom-checkbox">
                            <div class="frame-wrap">
                                <div class="custom-control custom-radio custom-control-inline p-0">
                                    <input type="radio" class="custom-control-input" id="inisiatif_pribadi"
                                        name="rujukan" value="inisiatif pribadi">
                                    <label class="custom-control-label" for="inisiatif_pribadi">Inisiatif
                                        Pribadi</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input" id="dalam_rs" name="rujukan"
                                        value="dalam rs">
                                    <label class="custom-control-label" for="dalam_rs">Dalam
                                        RS</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input" id="luar_rs" name="rujukan"
                                        value="luar rs">
                                    <label class="custom-control-label" for="luar_rs">Luar
                                        RS</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input" id="rujukan_bpjs"
                                        name="rujukan" value="rujukan bpjs">
                                    <label class="custom-control-label" for="rujukan_bpjs">Rujukan
                                        BPJS</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group mt-3">
                    <div class="row align-items-center">
                        <div class="col-xl-4 text-right">
                            <label class="form-label" for="pelayanan">
                                Pelayanan *
                            </label>
                        </div>
                        <div class="col-xl-8">
                            <div class="form-group">
                                <select class="form-control w-100" id="pelayanan">
                                    <option selected></option>
                                    <option value="bedah">Bedah</option>
                                    <option value="non-bedah">Non Bedah</option>
                                    <option value="psikiatrik">Psikiatrik</option>
                                    <option value="anak">Anak</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row align-items-center">
                    <div class="col-xl-4 text-right">
                        <label class="form-label" for="diagnosa_awal">
                            Diagnosa Awal
                        </label>
                    </div>
                    <div class="col-xl-8">
                        <textarea class="form-control" id="diagnosa_awal" name="diagnosa_awal" rows="5"></textarea>
                        @error('diagnosa_awal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-12 mt-5">
            <div class="row">
                <div class="col-xl-6">
                    <a href="/patients/{{ $patient->id }}" class="btn btn-lg btn-default waves-effect waves-themed">
                        <span class="fal fa-arrow-left mr-1 text-primary"></span>
                        <span class="text-primary">Kembali</span>
                    </a>
                </div>
                <div class="col-xl-6 text-right">
                    <button type="submit" class="btn btn-lg btn-primary waves-effect waves-themed">
                        <span class="fal fa-save mr-1"></span>
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
