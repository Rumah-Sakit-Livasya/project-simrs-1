<div class="modal fade" id="infusion-modal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">Form Infus</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="infusion-form">
                    @csrf
                    <input type="hidden" name="id" id="infusion_id">
                    <input type="hidden" name="registration_id" value="{{ $registration->id }}">

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Hari/Tanggal & Jam</label>
                            <input type="datetime-local" class="form-control" name="waktu_infus" id="waktu_infus"
                                required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Kolf Ke-</label>
                            <input type="text" class="form-control" name="kolf_ke" id="kolf_ke"
                                placeholder="Contoh: 1">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Jenis Cairan dan Kecepatan Tetesan</label>
                        <textarea class="form-control" name="jenis_cairan" id="jenis_cairan" rows="3" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Cairan Masuk (cc)</label>
                            <input type="number" class="form-control" name="cairan_masuk" id="cairan_masuk" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Sisa Cairan (cc)</label>
                            <input type="number" class="form-control" name="cairan_sisa" id="cairan_sisa">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea class="form-control" name="keterangan" id="keterangan" rows="2"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Nama Perawat</label>
                        <input type="text" class="form-control" name="nama_perawat" id="nama_perawat"
                            value="{{ auth()->user()->name }}" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="btn-save-infusion">Simpan</button>
            </div>
        </div>
    </div>
</div>
