<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tanda Tangan Digital</title>
    {{-- Kita akan menggunakan CSS Bootstrap dari CDN agar terlihat rapi --}}
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body,
        html {
            height: 100%;
            margin: 0;
            padding: 10px;
            background-color: #f8f9fa;
            overflow: hidden;
        }

        .signature-container {
            width: 100%;
            height: calc(100% - 70px);
            /* Tinggi dikurangi area tombol */
            border: 2px dashed #6c757d;
            border-radius: 8px;
            cursor: crosshair;
            touch-action: none;
        }

        canvas {
            width: 100%;
            height: 100%;
            display: block;
        }

        .signature-actions {
            position: absolute;
            bottom: 10px;
            left: 10px;
            right: 10px;
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="signature-container">
        <canvas id="signature-canvas"></canvas>
    </div>

    <div class="signature-actions">
        <button class="btn btn-danger" id="clear-btn">Bersihkan</button>
        <button class="btn btn-success" id="save-btn">Simpan & Tutup</button>
    </div>

    {{-- Kita memerlukan library Signature Pad untuk fungsionalitas menggambar yang lebih baik --}}
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const canvas = document.getElementById('signature-canvas');

            // Atur ukuran canvas sesuai dengan containernya
            function resizeCanvas() {
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                canvas.getContext("2d").scale(ratio, ratio);
            }
            window.addEventListener("resize", resizeCanvas);
            resizeCanvas();

            const signaturePad = new SignaturePad(canvas, {
                backgroundColor: 'rgb(255, 255, 255)' // Latar belakang putih
            });

            // Tombol Bersihkan
            document.getElementById('clear-btn').addEventListener('click', function() {
                signaturePad.clear();
            });

            // Tombol Simpan
            document.getElementById('save-btn').addEventListener('click', function() {
                if (signaturePad.isEmpty()) {
                    alert("Mohon buat tanda tangan terlebih dahulu.");
                    return;
                }

                const dataURL = signaturePad.toDataURL('image/png');

                // Ambil target dari URL query string
                const urlParams = new URLSearchParams(window.location.search);
                const targetInputId = urlParams.get('targetInput');
                const targetPreviewId = urlParams.get('targetPreview');

                // Kirim data kembali ke halaman utama (opener)
                if (window.opener) {
                    window.opener.updateSignature(targetInputId, targetPreviewId, dataURL);
                }

                // Tutup popup window
                window.close();
            });
        });
    </script>
</body>

</html>
