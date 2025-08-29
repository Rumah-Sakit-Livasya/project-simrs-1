<script>
    // =====================================================================
    // SCRIPT TUNGGAL UNTUK SEMUA TANDA TANGAN (BAIK SINGLE MAUPUN MANY)
    // =====================================================================
    (function() {
        const canvasManyElement = document.getElementById('canvas-many');

        // Cek jika canvas tidak ada atau script sudah diinisialisasi
        if (!canvasManyElement || window.signaturePadManyInitialized) return;

        const ctxMany = canvasManyElement.getContext('2d', {
            willReadFrequently: true
        });
        let signatureState = {}; // Menyimpan data base64 untuk setiap ttd
        let currentSession = {
            painting: false,
            history: [],
            hasDrawn: false,
            index: null // Ini akan menjadi 'prefix' unik
        };

        function startNewSession(index) {
            currentSession = {
                painting: false,
                history: [],
                hasDrawn: false,
                index: index
            };
            ctxMany.clearRect(0, 0, canvasManyElement.width, canvasManyElement.height);
        }

        function startPositionMany(e) {
            e.preventDefault();
            currentSession.painting = true;
            drawMany(e);
        }

        function endPositionMany(e) {
            e.preventDefault();
            if (!currentSession.painting) return;
            currentSession.painting = false;
            ctxMany.beginPath();
            if (currentSession.hasDrawn) {
                currentSession.history.push(ctxMany.getImageData(0, 0, canvasManyElement.width, canvasManyElement
                    .height));
            }
        }

        function drawMany(e) {
            if (!currentSession.painting) return;
            const rect = canvasManyElement.getBoundingClientRect();
            const x = (e.clientX || e.touches?.[0]?.clientX) - rect.left;
            const y = (e.clientY || e.touches?.[0]?.clientY) - rect.top;
            ctxMany.lineWidth = 3;
            ctxMany.lineCap = 'round';
            ctxMany.strokeStyle = '#000';
            ctxMany.lineTo(x, y);
            ctxMany.stroke();
            ctxMany.beginPath();
            ctxMany.moveTo(x, y);
            currentSession.hasDrawn = true;
        }

        function undoMany() {
            if (currentSession.history.length > 0) {
                currentSession.history.pop();
                if (currentSession.history.length > 0) {
                    ctxMany.putImageData(currentSession.history[currentSession.history.length - 1], 0, 0);
                } else {
                    ctxMany.clearRect(0, 0, canvasManyElement.width, canvasManyElement.height);
                    currentSession.hasDrawn = false;
                }
            }
        }

        // Fungsi global untuk membuka modal
        window.openSignaturePadMany = function(index) {
            startNewSession(index);
            $('#signatureModalMany').modal('show');
        }

        // Fungsi global untuk menyimpan tanda tangan
        window.saveSignatureMany = function() {
            if (!currentSession.hasDrawn) {
                alert("Silakan buat tanda tangan terlebih dahulu.");
                return;
            }
            const dataURL = canvasManyElement.toDataURL('image/png');
            const currentIndex = currentSession.index; // Menggunakan prefix unik

            // Menargetkan elemen preview dan input berdasarkan `currentIndex`
            const preview = document.getElementById(`signature_preview_${currentIndex}`);
            const input = document.getElementById(`signature_image_${currentIndex}`);

            if (preview) {
                preview.src = dataURL;
                preview.style.display = 'block';
            }
            if (input) {
                input.value = dataURL;
            }

            // Simpan state jika perlu, tapi update input sudah cukup
            signatureState[currentIndex] = dataURL;

            $('#signatureModalMany').modal('hide');
        }

        // Fungsi global untuk membersihkan canvas
        window.clearCanvasMany = function() {
            ctxMany.clearRect(0, 0, canvasManyElement.width, canvasManyElement.height);
            currentSession.history = [];
            currentSession.hasDrawn = false;
        };

        // Fungsi global untuk undo
        window.undoCanvasMany = undoMany; // Cukup alias-kan fungsi undoMany

        // Event listeners untuk canvas
        canvasManyElement.addEventListener('mousedown', startPositionMany);
        canvasManyElement.addEventListener('mouseup', endPositionMany);
        canvasManyElement.addEventListener('mousemove', drawMany);
        canvasManyElement.addEventListener('touchstart', startPositionMany, {
            passive: false
        });
        canvasManyElement.addEventListener('touchend', endPositionMany, {
            passive: false
        });
        canvasManyElement.addEventListener('touchmove', drawMany, {
            passive: false
        });

        window.signaturePadManyInitialized = true;
    })();
</script>
