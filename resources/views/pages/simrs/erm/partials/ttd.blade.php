<!-- Signature Modal -->
<div class="modal fade" id="signatureModal" tabindex="-1" role="dialog" aria-labelledby="signatureModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content border-0">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="signatureModalLabel">Tanda Tangan Digital</h5>
                <button type="button" class="btn-close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <p class="text-center text-muted mb-2">
                    Silakan tanda tangan dalam kotak di bawah ini. Gunakan mouse atau sentuhan jari.
                </p>

                <div class="border border-secondary rounded" style="position: relative; width: 100%; overflow: hidden;">
                    <canvas id="canvas" width="1156" height="500" class="d-block mx-auto m-0"
                        style="touch-action: none; cursor: crosshair;"></canvas>
                    <div style="position: absolute; top: 10px; left: 10px; color: #999; font-size: 14px;">Area tanda
                        tangan</div>
                </div>

                <div class="text-center mt-3">
                    <button class="btn btn-sm btn-outline-danger" onclick="clearCanvas()">
                        🗑 Bersihkan
                    </button>
                    <button class="btn btn-sm btn-outline-secondary" onclick="undo()">
                        ↩️ Batal
                    </button>
                    <button class="btn btn-sm btn-success" onclick="saveSignature()" id="btn_save_ttd"
                        data-id="{{ $pengkajian?->id }}" data-target="mek">
                        💾 Simpan Tanda Tangan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@section('signature')
    <script>
        let idSignature = null;
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');
        let painting = false;
        let history = [];
        let hasDrawn = false;

        function startPosition(e) {
            painting = true;
            draw(e);
        }

        function endPosition() {
            painting = false;
            ctx.beginPath();
            history.push(ctx.getImageData(0, 0, canvas.width, canvas.height));
        }

        function draw(e) {
            if (!painting) return;

            const rect = canvas.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            ctx.lineWidth = 3;
            ctx.lineCap = 'round';
            ctx.strokeStyle = '#000';

            ctx.lineTo(x, y);
            ctx.stroke();
            ctx.beginPath();
            ctx.moveTo(x, y);

            hasDrawn = true;
        }

        function clearCanvas() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            history = [];
            hasDrawn = false;
        }

        function undo() {
            if (history.length > 0) {
                ctx.putImageData(history.pop(), 0, 0);
            }
        }

        function saveSignature() {
            if (!hasDrawn) {
                alert("Silakan buat tanda tangan terlebih dahulu.");
                return;
            }

            const dataURL = canvas.toDataURL('image/png');

            // Tampilkan preview & simpan ke input hidden
            $('#signature_preview').attr('src', dataURL).show();
            // $('#signature_preview').attr('src', dataURL + '?v=' + new Date().getTime()).show();
            $('#signature_image').val(dataURL);


            // Tutup modal
            $('#signatureModal').modal('hide');
        }


        // Event Binding
        canvas.addEventListener('mousedown', startPosition);
        canvas.addEventListener('mouseup', endPosition);
        canvas.addEventListener('mousemove', draw);
    </script>
@endsection
