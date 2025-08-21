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
        // FUNGSI KALKULASI SKOR (KHUSUS ANAK)
        // ==========================================================

        // 1. ASESMEN SKOR NYERI (Wong-Baker Scale - Anak, usia > 6 thn)
        $('.wong-baker-scale-anak .pointer').on('click', function() {
            const skor = $(this).data('skor');
            $('#skor_nyeri_anak').val(skor);
        });

        // 2. SKALA NYERI FLACC (usia < 6 thn)
        function calculateFlaccScore() {
            let score = 0;
            $('input.skor_flacc:checked').each(function() {
                score += parseInt($(this).data('skor')) || 0;
            });
            $('#jumlah_skor_flacc').val(score);

            let analisis = 'Nyaman';
            if (score >= 1 && score <= 3) analisis = 'Kurang Nyaman';
            else if (score >= 4 && score <= 6) analisis = 'Nyeri Sedang';
            else if (score >= 7) analisis = 'Nyeri Berat';
            $('#analisis_flacc').val(analisis);
        }
        // Pemicu untuk kalkulasi skor FLACC
        $('input.skor_flacc').on('change', calculateFlaccScore);

        // 3. SKALA JATUH HUMPTY DUMPTY (anak 1bln - 17thn)
        function calculateHumptyScore() {
            let score = 0;
            $('input.humpty:checked').each(function() {
                score += parseInt($(this).data('skor')) || 0;
            });
            $('#skor_humpty').val(score);

            let analisis = 'Resiko Tinggi'; // Default
            if (score >= 7 && score <= 11) {
                analisis = 'Resiko Rendah';
            }
            $('#analisis_humpty').val(analisis);
        }
        // Pemicu untuk kalkulasi skor Humpty Dumpty
        $('input.humpty').on('change', calculateHumptyScore);


        // ==========================================================
        // PEMANGGILAN FUNGSI KALKULASI SAAT HALAMAN DIMUAT
        // ==========================================================
        // Ini untuk mengisi nilai skor dan analisis jika data sudah ada
        calculateFlaccScore();
        calculateHumptyScore();


        // ==========================================================
        // AJAX UNTUK SUBMIT FORM UTAMA (ANAK)
        // ==========================================================
        $('#form-asesmen-awal-ranap-anak').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const saveButton = $('#btn-save-asesmen-anak');

            saveButton.prop('disabled', true).html(
                '<span class="spinner-border spinner-border-sm"></span> Menyimpan...');

            $.ajax({
                url: "{{ route('erm.asesmen-awal-ranap-anak.store') }}",
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
