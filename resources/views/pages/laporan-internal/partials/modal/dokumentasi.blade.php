<!-- Modal for showing documentation -->
<div class="modal fade" id="dokumentasiModal" tabindex="-1" role="dialog" aria-labelledby="dokumentasiModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dokumentasiModalLabel">Dokumentasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <div id="noDocument" class="py-5" style="display: none;">
                    <i class="fas fa-file-excel fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Tidak ada dokumentasi tersedia</p>
                </div>
                <div id="unsupportedFormat" class="py-5" style="display: none;">
                    <i class="fas fa-file-excel fa-3x text-danger mb-3"></i>
                    <p class="text-danger">Format file tidak didukung. Hanya JPG, PNG, dan PDF yang bisa
                        ditampilkan.</p>
                </div>
                <img id="dokumentasiImage" src="" class="img-fluid" style="max-height: 70vh; display: none;"
                    alt="Dokumentasi">
                <div id="dokumentasiPdf" class="w-100" style="height: 70vh; display: none;">
                    <iframe id="pdfViewer" src="" style="width: 100%; height: 100%; border: none;"></iframe>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <a id="downloadDokumentasi" href="#" class="btn btn-primary" download style="display: none;">
                    <i class="fas fa-download"></i> Unduh
                </a>
            </div>
        </div>
    </div>
</div>
