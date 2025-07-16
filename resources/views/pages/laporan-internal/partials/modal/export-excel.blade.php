<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="exportModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exportModalLabel">Download Laporan Harian</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="exportForm" action="{{ route('laporan.internal.export.harian') }}" method="GET">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="exportTanggal">Tanggal Laporan</label>
                        <input type="date" class="form-control" id="exportTanggal" name="tanggal" required
                            value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="form-group">
                        <label for="exportJenis">Jenis Laporan</label>
                        <select class="form-control" id="exportJenis" name="jenis">
                            <option value="">Semua Jenis</option>
                            <option value="kegiatan">Kegiatan</option>
                            <option value="kendala">Kendala</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-download mr-2"></i>Download
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
