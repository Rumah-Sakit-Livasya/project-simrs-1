{{-- resources/views/pages/simrs/utility/signature_pad.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tanda Tangan</title>
    {{-- Gunakan Bootstrap dari CDN untuk styling sederhana --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
        }

        .wrapper {
            max-width: 550px;
            margin: 20px auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .signature-pad-container {
            border: 1px dashed #ccc;
            touch-action: none;
            -ms-touch-action: none;
        }
    </style>
</head>

<body>
    <div class="wrapper p-4" style="max-width: 100%; height: 100vh; margin: 0;">
        <h5 class="text-center mb-3">Silakan bubuhkan tanda tangan di area bawah</h5>
        <div class="signature-pad-container" style="height: calc(100vh - 150px);">
            <canvas id="signature-canvas" style="width: 100%; height: 100%;"></canvas>
        </div>
        <div class="d-flex justify-content-between mt-3">
            <button id="clear-button" class="btn btn-secondary">Ulangi</button>
            <button id="save-button" class="btn btn-primary">Simpan Tanda Tangan</button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const canvas = document.getElementById('signature-canvas');
            const signaturePad = new SignaturePad(canvas, {
                backgroundColor: 'rgb(255, 255, 255)'
            });

            // Fungsi untuk resize canvas
            function resizeCanvas() {
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                canvas.getContext("2d").scale(ratio, ratio);
                signaturePad.clear(); // Hapus tanda tangan saat resize
            }
            window.addEventListener("resize", resizeCanvas);
            resizeCanvas();

            // Tombol Ulangi
            document.getElementById('clear-button').addEventListener('click', function() {
                signaturePad.clear();
            });

            // Tombol Simpan
            document.getElementById('save-button').addEventListener('click', function() {
                if (signaturePad.isEmpty()) {
                    alert("Mohon bubuhkan tanda tangan terlebih dahulu.");
                    return;
                }

                const dataURL = signaturePad.toDataURL('image/png');

                // Kirim data kembali ke JENDELA INDUK (opener)
                // dan panggil fungsi `window.updateSignature` yang ada di sana
                if (window.opener && !window.opener.closed) {
                    window.opener.updateSignature('{{ $inputTarget }}', '{{ $previewTarget }}', dataURL);
                }

                // Tutup jendela popup ini
                window.close();
            });
        });
    </script>
</body>

</html>
