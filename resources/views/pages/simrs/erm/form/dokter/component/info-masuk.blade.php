<h4 class="text-primary mt-4 font-weight-bold">II. INFORMASI MASUK RUMAH SAKIT</h4>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Tanggal & Jam Masuk</label>
            <div class="row">
                <div class="col-6">
                    <input type="text" name="tgl_masuk" class="form-control datepicker" value="{{ $pengkajian->waktu_masuk ? $pengkajian->waktu_masuk->format('d-m-Y') : '' }}">
                </div>
                <div class="col-6">
                    <input type="time" name="jam_masuk" class="form-control" value="{{ $pengkajian->waktu_masuk ? $pengkajian->waktu_masuk->format('H:i') : '' }}">
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Tanggal & Jam Dilayani</label>
             <div class="row">
                <div class="col-6">
                    <input type="text" name="tgl_dilayani" class="form-control datepicker" value="{{ $pengkajian->waktu_dilayani ? $pengkajian->waktu_dilayani->format('d-m-Y') : '' }}">
                </div>
                <div class="col-6">
                    <input type="time" name="jam_dilayani" class="form-control" value="{{ $pengkajian->waktu_dilayani ? $pengkajian->waktu_dilayani->format('H:i') : '' }}">
                </div>
            </div>
        </div>
    </div>
</div>
