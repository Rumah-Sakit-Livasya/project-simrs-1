<div class="row">
    <div class="col-xl-12">
        <div class="panel">
            <div class="panel-hdr">
                <h2>Filter Pencarian Pasien</h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <form id="filter-form">
                        <div class="row">
                            <div class="col-md-3 form-group">
                                <label class="form-label" for="medical_record_number">No. RM</label>
                                <input type="text" class="form-control" id="medical_record_number"
                                    name="medical_record_number">
                            </div>
                            <div class="col-md-3 form-group">
                                <label class="form-label" for="registration_number">No. Registrasi</label>
                                <input type="text" class="form-control" id="registration_number"
                                    name="registration_number">
                            </div>
                            <div class="col-md-3 form-group">
                                <label class="form-label" for="patient_name">Nama Pasien</label>
                                <input type="text" class="form-control" id="patient_name" name="patient_name">
                            </div>
                            <div class="col-md-3 form-group">
                                <label class="form-label" for="penjamin_id">Asuransi</label>
                                <select class="select2 form-control w-100" id="penjamin_id" name="penjamin_id">
                                    <option value="">Semua</option>
                                    @foreach ($penjamins as $penjamin)
                                        <option value="{{ $penjamin->id }}">{{ $penjamin->nama_perusahaan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 form-group">
                                <label class="form-label" for="kelas_id">Kelas</label>
                                <select class="select2 form-control w-100" id="kelas_id" name="kelas_id">
                                    <option value="">Semua</option>
                                    @foreach ($kelasRawats as $kelas)
                                        <option value="{{ $kelas->id }}">{{ $kelas->kelas }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 form-group">
                                <label class="form-label" for="room_id">Ruang</label>
                                <select class="select2 form-control w-100" id="room_id" name="room_id">
                                    <option value="">Semua</option>
                                    @foreach ($rooms as $room)
                                        <option value="{{ $room->id }}">{{ $room->ruangan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button type="submit" class="btn btn-primary">Cari</button>
                                <button type="reset" id="reset-filter-btn" class="btn btn-secondary">Reset</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
