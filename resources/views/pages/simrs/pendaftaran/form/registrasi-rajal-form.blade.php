<form action="{{ route('simpan.registrasi') }}" method="post">
    @csrf
    <input type="hidden" name="patient_id" value="{{ $patient->id }}">
    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
    <input type="hidden" name="employee_id" value="{{ auth()->user()->employee->id }}">
    <input type="hidden" name="registration_type" value="rawat-jalan">
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
                                @foreach ($groupedDoctors as $department => $doctors)
                                    <optgroup label="{{ $department }}">
                                        @foreach ($doctors as $doctor)
                                            <option value="{{ $doctor->id }}" data-departement="{{ $department }}">
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
                <div class="row align-items-center">
                    <div class="col-xl-4 text-right">
                        <label class="form-label" for="poliklinik">Poliklinik</label>
                    </div>
                    <div class="col-xl-8">
                        <input type="text"
                            style="border: 0; border-bottom: 1.9px dashed #aaa; margin-top: -.5rem; border-radius: 0"
                            class="form-control" id="poliklinik" readonly name="poliklinik">
                        @error('poliklinik')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
                                        {{ $penjamin->name }}</option>
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
                            Paket
                        </label>
                    </div>
                    <div class="col-xl-8">
                        <div class="form-group">
                            <select class="form-control w-100" id="paket">
                                <option selected></option>
                                <option value="">Paket Skin Care</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row align-items-center">
                    <div class="col-xl-4 text-right">
                        <label class="form-label" for="registration_date">
                            Tipe Jadwal
                        </label>
                    </div>
                    <div class="col-xl-8">
                        <div class="form-group">
                            <select class="form-control w-100" id="type">
                                <option value="OR">Sesuai Jadwal Praktek</option>
                                <option value="WA">On Call</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
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
                        <label class="form-label" for="">
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
                                    <label class="custom-control-label" for="dalam_rs">Dalam RS</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input" id="luar_rs" name="rujukan"
                                        value="luar rs">
                                    <label class="custom-control-label" for="luar_rs">Luar RS</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input" id="rujukan_bpjs"
                                        name="rujukan" value="rujukan bpjs">
                                    <label class="custom-control-label" for="rujukan_bpjs">Rujukan BPJS</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group mt-3 d-none" id="dokter_perujuk_container">
                <div class="row align-items-center">
                    <div class="col-xl-4 text-right">
                        <label class="form-label" for="dokter_perujuk">Dokter
                            Perujuk</label>
                    </div>
                    <div class="col-xl-8">
                        <div class="form-group">
                            <select class="select2 form-control w-100" id="dokter_perujuk" name="dokter_perujuk">
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

            <div class="row d-none" id="luar_dan_rujuk_bpjs_container">
                <div class="col-12">
                    <div class="form-group mt-3">
                        <div class="row align-items-center">
                            <div class="col-xl-4 text-right">
                                <label class="form-label" for="tipe_rujukan">Tipe
                                    Rujuk</label>
                            </div>
                            <div class="col-xl-8">
                                <div class="form-group">
                                    <select class="select2 form-control w-100" id="tipe_rujukan" name="tipe_rujukan">
                                        <option value="rsu/rsk/rb">RSU/RSK/RB</option>
                                        <option value="puskesmas">PUSKESMAS</option>
                                        <option value="bidan/perawat">BIDAN/PERAWAT
                                        </option>
                                        <option value="dokter">DOKTER</option>
                                        <option value="dukun terlatih">DUKUN TERLATIH
                                        </option>
                                        <option value="kasus polisi">KASUS POLISI</option>
                                        <option value="klinik">KLINIK</option>
                                        <option value="lain-lain">LAIN-LAIN</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group mt-3">
                        <div class="row align-items-center">
                            <div class="col-xl-4 text-right">
                                <label class="form-label" for="nama perujuk">Nama
                                    Perujuk</label>
                            </div>
                            <div class="col-xl-8">
                                <input type="text" class="form-control" id="nama_perujuk" name="nama_perujuk">
                                @error('nama_perujuk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group mt-3">
                        <div class="row align-items-center">
                            <div class="col-xl-4 text-right">
                                <label class="form-label" for="telp_perujuk">Telp/HP
                                    Perujuk</label>
                            </div>
                            <div class="col-xl-8">
                                <input type="text" class="form-control" id="telp_perujuk" name="telp_perujuk">
                                @error('telp_perujuk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group mt-3">
                        <div class="row align-items-center">
                            <div class="col-xl-4 text-right">
                                <label class="form-label" for="alamat_perujuk">Alamat</label>
                            </div>
                            <div class="col-xl-8">
                                <input type="text" class="form-control" id="alamat_perujuk"
                                    name="alamat_perujuk">
                                @error('alamat_perujuk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-12 mt-4">
            <div class="form-group">
                <div class="row align-items-center">
                    <div class="col-xl-2 text-right">
                        <label class="form-label" for="diagnosa-awal">
                            Diagnosa Awal
                        </label>
                    </div>
                    <div class="col-xl-10">
                        <textarea class="form-control" id="diagnosa-awal" name="diagnosa_awal" rows="5"></textarea>
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
