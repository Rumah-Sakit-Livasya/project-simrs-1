<!-- Signature Modal Many -->
<div class="modal fade" id="signatureModalMany" tabindex="-1" role="dialog" aria-labelledby="signatureModalManyLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content border-0">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="signatureModalManyLabel">Tanda Tangan Digital</h5>
                <button type="button" class="btn-close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-center text-muted mb-2">
                    Silakan tanda tangan dalam kotak di bawah ini. Gunakan mouse atau sentuhan jari.
                </p>

                <div class="border border-secondary rounded" style="position: relative; width: 100%; overflow: hidden;">
                    <canvas id="canvas-many" width="1156" height="500" class="d-block mx-auto m-0"
                        style="touch-action: none; cursor: crosshair;"></canvas>
                    <div style="position: absolute; top: 10px; left: 10px; color: #999; font-size: 14px;">Area tanda
                        tangan</div>
                </div>

                <div class="text-center mt-3">
                    {{-- Panggil fungsi yang telah diganti namanya --}}
                    <button class="btn btn-sm btn-outline-danger" onclick="clearCanvasMany()">
                        🗑 Bersihkan
                    </button>
                    <button class="btn btn-sm btn-outline-secondary" onclick="undoMany()">
                        ↩️ Batal
                    </button>
                    <button class="btn btn-sm btn-success" onclick="saveSignatureMany()">
                        💾 Simpan Tanda Tangan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- HAPUS SEMUA JAVASCRIPT DARI SINI --}}
