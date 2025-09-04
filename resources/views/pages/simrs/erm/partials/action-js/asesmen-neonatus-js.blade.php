<script>
    $(function() {

        // Fungsi ini dipanggil dari window popup untuk mengupdate halaman utama
        window.updateSignature = function(targetInputId, targetPreviewId, dataURL) {
            // Cari elemen di halaman utama dan isi nilainya
            const inputField = document.getElementById(targetInputId);
            const previewImage = document.getElementById(targetPreviewId);

            if (inputField) {
                inputField.value = dataURL;
            }
            if (previewImage) {
                previewImage.src = dataURL;
                previewImage.style.display = 'block';
            }
        };

        // Fungsi ini dipanggil oleh tombol "Tanda Tangan" untuk membuka popup
        window.openSignaturePopup = function(targetInputId, targetPreviewId) {
            const windowWidth = screen.availWidth;
            const windowHeight = screen.availHeight;
            const left = 0;
            const top = 0;

            // Bangun URL dengan query string untuk memberitahu popup elemen mana yang harus diupdate
            const url =
                `{{ route('signature.pad') }}?targetInput=${targetInputId}&targetPreview=${targetPreviewId}`;

            // Buka popup window
            window.open(
                url,
                'SignatureWindow',
                `width=${windowWidth},height=${windowHeight},top=${top},left=${left},resizable=yes,scrollbars=yes`
            );
        };

        // ==========================================================
        // FUNGSI KALKULASI SKOR (KHUSUS NEONATUS)
        // ==========================================================

        // 1. SKALA APGAR SCORE
        function calculateApgarScore(minute) {
            let score = 0;
            // Target radio button berdasarkan class menit (apgar_1mnt, apgar_5mnt, apgar_10mnt)
            $(`input.apgar_${minute}:checked`).each(function() {
                score += parseInt($(this).data('skor')) || 0;
            });
            // Isi input total skor yang sesuai
            $(`#apgar_score_${minute}`).val(score);
        }
        // Pemicu untuk kalkulasi skor Apgar
        $('input[class^="apgar_"]').on('change', function() {
            if ($(this).hasClass('apgar_1mnt')) calculateApgarScore('1mnt');
            if ($(this).hasClass('apgar_5mnt')) calculateApgarScore('5mnt');
            if ($(this).hasClass('apgar_10mnt')) calculateApgarScore('10mnt');
        });

        // 2. SKALA DOWN SCORE (Gawat Napas)
        function calculateDownScore() {
            let score = 0;
            $('input.kesadaran:checked').each(function() {
                score += parseInt($(this).data('skor')) || 0;
            });
            $('#skor_kesadaran').val(score);

            let analisis = 'Tidak Ada Gawat Nafas';
            if (score >= 4 && score <= 6) analisis = 'Gawat Nafas';
            else if (score > 6) analisis = 'Ancaman Gagal Nafas';
            $('#analisis_kesadaran').val(analisis);
        }
        // Pemicu untuk kalkulasi Down Score
        $('input.kesadaran').on('change', calculateDownScore);

        // 3. SKALA NYERI FLACC
        function calculateFlaccScore() {
            let score = 0;
            $('input.nyeri:checked').each(function() {
                score += parseInt($(this).data('skor')) || 0;
            });
            $('#skor_nyeri').val(score);

            let analisis = 'Nyaman';
            if (score >= 1 && score <= 3) analisis = 'Kurang Nyaman';
            else if (score >= 4 && score <= 6) analisis = 'Nyeri Sedang';
            else if (score >= 7) analisis = 'Nyeri Berat';
            $('#analisis_nyeri').val(analisis);
        }
        // Pemicu untuk kalkulasi skor FLACC
        $('input.nyeri').on('change', calculateFlaccScore);


        // ==========================================================
        // PEMANGGILAN FUNGSI KALKULASI SAAT HALAMAN DIMUAT
        // ==========================================================
        calculateApgarScore('1mnt');
        calculateApgarScore('5mnt');
        calculateApgarScore('10mnt');
        calculateDownScore();
        calculateFlaccScore();


        // ==========================================================
        // AJAX UNTUK SUBMIT FORM UTAMA (NEONATUS)
        // ==========================================================
        $('#form-asesmen-awal-ranap-neonatus').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const saveButton = $('#btn-save-asesmen-neonatus');

            saveButton.prop('disabled', true).html(
                '<span class="spinner-border spinner-border-sm"></span> Menyimpan...');

            $.ajax({
                url: "{{ route('erm.asesmen-awal-ranap-neonatus.store') }}",
                type: 'POST',
                data: form.serialize(),
                success: function(response) {
                    Swal.fire('Sukses!', response.success, 'success').then(() => {
                        // Opsi: Anda bisa reload halaman untuk melihat data terbaru
                        // window.location.reload();
                    });
                },
                error: function(jqXHR) {
                    let errorMsg = 'Terjadi kesalahan saat menyimpan data.';
                    if (jqXHR.status === 422 && jqXHR.responseJSON && jqXHR.responseJSON
                        .errors) {
                        // Format pesan error validasi
                        errorMsg = Object.values(jqXHR.responseJSON.errors).flat().join(
                            '<br>');
                        Swal.fire({
                            icon: 'error',
                            title: 'Error Validasi!',
                            html: errorMsg
                        });
                    } else if (jqXHR.responseJSON && jqXHR.responseJSON.error) {
                        errorMsg = jqXHR.responseJSON.error;
                        Swal.fire('Error!', errorMsg, 'error');
                    } else {
                        Swal.fire('Error!', errorMsg, 'error');
                    }
                },
                complete: function() {
                    saveButton.prop('disabled', false).html('Simpan Data');
                }
            });
        });
    });
</script>
