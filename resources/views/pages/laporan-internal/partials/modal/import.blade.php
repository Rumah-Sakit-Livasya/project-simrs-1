<!-- Modal Import Excel Laporan Internal -->
<div class="modal fade" id="importLaporan" tabindex="-1" aria-labelledby="importLaporanLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="importLaporanLabel">Import Laporan Internal</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <form id="import-form-laporan" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="file" class="form-label">Pilih File Excel</label>
                        <input type="file" class="form-control" id="file" name="file"
                            accept=".xlsx,.xls,.csv" required>
                        <div class="invalid-feedback d-block text-danger small" id="file-error" style="display: none;">
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-file-import me-1"></i> Import Excel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
