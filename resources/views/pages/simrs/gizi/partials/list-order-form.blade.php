<div class="row">
    <div class="col-xl-12">
        <div class="panel">
            <div class="panel-hdr">
                <h2>Filter Pencarian</h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <form id="filter-form">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label" for="medical_record_number">No. RM</label>
                                    <input type="text" class="form-control" id="medical_record_number"
                                        name="medical_record_number">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label" for="registration_number">No. Registrasi</label>
                                    <input type="text" class="form-control" id="registration_number"
                                        name="registration_number">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label" for="patient_name">Nama Pasien</label>
                                    <input type="text" class="form-control" id="patient_name" name="patient_name">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label" for="nama_pemesan">Nama Pemesan</label>
                                    <input type="text" class="form-control" id="nama_pemesan" name="nama_pemesan">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label" for="tanggal_order">Tanggal Order</label>
                                    <input type="date" class="form-control" id="tanggal_order" name="tanggal_order"
                                        value="{{ now()->toDateString() }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label" for="waktu_makan">Waktu Makan</label>
                                    <select name="waktu_makan" id="waktu_makan" class="select2 form-control w-100">
                                        <option value="">Semua</option>
                                        <option value="pagi">Pagi</option>
                                        <option value="siang">Siang</option>
                                        <option value="sore">Sore</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label" for="kelas_id">Kelas</label>
                                    <select class="select2 form-control w-100" id="kelas_id" name="kelas_id">
                                        <option value="">Semua</option>
                                        @foreach ($kelasRawats as $kelas)
                                            <option value="{{ $kelas->id }}">{{ $kelas->kelas }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label" for="status_order">Status Pesanan</label>
                                    <select class="select2 form-control w-100" id="status_order" name="status_order">
                                        <option value="">Semua</option>
                                        <option value="0">Process</option>
                                        <option value="1">Delivered</option>
                                    </select>
                                </div>
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
