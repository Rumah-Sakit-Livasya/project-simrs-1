<div class="row justify-content-center">
    <div class="col-xl-8">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    Form <span class="fw-300"><i>Pencarian</i></span>
                </h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">

                    <form action="{{ route('gizi.daftar-pasien.list-pasien') }}" method="get">
                        @csrf

                        <div class="row justify-content-center">
                            <div class="col-xl-4">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-5" style="text-align: right">
                                            <label class="form-label text-end" for="medical_record_number">
                                                No. RM
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <input type="text" value="{{ request('medical_record_number') }}"
                                                style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                class="form-control" id="medical_record_number"
                                                name="medical_record_number" onkeyup="formatAngka(this)">
                                            @error('medical_record_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-4">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-5" style="text-align: right">
                                            <label class="form-label text-end" for="registration_number">
                                                No. Registrasi
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <input type="text" value="{{ request('registration_number') }}"
                                                style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                class="form-control" id="registration_number" name="registration_number"
                                                onkeyup="formatAngka(this)">
                                            @error('registration_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-4">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-5" style="text-align: right">
                                            <label class="form-label text-end" for="patient_name">
                                                Nama Pasien
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <input type="text" value="{{ request('patient_name') }}"
                                                style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                class="form-control" id="patient_name" name="patient_name">
                                            @error('patient_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center mt-4">
                            <div class="col-xl-4">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-5" style="text-align: right">
                                            <label class="form-label text-end" for="penjamin_id">
                                                Asuransi
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <div class="form-group">
                                                <select class="select2 form-control w-100" id="penjamin_id" name="penjamin_id">
                                                    <option selected></option>
                                                    @foreach ($penjamins as $penjamin)
                                                        <option value="{{ $penjamin->id }}"
                                                            {{ old('penjamin_id') == $penjamin->id ? 'selected' : '' }}>
                                                            {{ $penjamin->nama_perusahaan }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-4">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-5" style="text-align: right">
                                            <label class="form-label text-end" for="kelas_id">
                                                Kelas
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <div class="form-group">
                                                <select class="select2 form-control w-100" id="kelas_id" name="kelas_id">
                                                    <option selected></option>
                                                    @foreach ($kelasRawats as $kelas)
                                                        <option value="{{ $kelas->id }}"
                                                            {{ old('kelas_id') == $kelas->id ? 'selected' : '' }}>
                                                            {{ $kelas->kelas }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-4">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-4" style="text-align: right">
                                            <label for="room_id">Ruang</label>
                                        </div>
                                        <div class="col-xl">
                                            <select class="select2 form-control w-100" id="room_id" name="room_id">
                                                <option selected></option>
                                                @foreach ($rooms as $room)
                                                    <option value="{{ $room->id }}"
                                                        {{ old('room_id') == $room->id ? 'selected' : '' }}>
                                                        {{ $room->ruangan }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center mt-4">
                            <div class="col-xl-4">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-5" style="text-align: right">
                                            <label class="form-label text-end" for="keluarga_pj">
                                                Keluarga PJ
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <input type="text" value="{{ request('keluarga_pj') }}"
                                                style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                class="form-control" id="keluarga_pj" name="keluarga_pj">
                                            @error('keluarga_pj')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-8">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-2" style="text-align: right">
                                            <label class="form-label text-end" for="addresss">
                                                Alamat
                                            </label>
                                        </div>
                                        <div class="col-xl-10">
                                            <input type="text" value="{{ request('addresss') }}"
                                                style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                class="form-control" id="addresss" name="addresss">
                                            @error('addresss')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-end mt-3">
                            <div class="col-xl-2">
                                <button type="submit" class="btn btn-outline-primary waves-effect waves-themed">
                                    <span class="fal fa-search mr-1"></span>
                                    Cari
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
