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
        // FUNGSI KALKULASI SKOR (KHUSUS LANSIA)
        // ==========================================================

        // 1. ASESMEN SKOR NYERI (Wong-Baker Scale - Lansia)
        $('.wong-baker-scale-lansia .pointer').on('click', function() {
            $('#skor_nyeri_lansia').val($(this).data('skor'));
        });

        // 2. STATUS FUNGSIONAL (BARTHEL INDEX)
        function calculateBarthelIndexScore() {
            let score = 0;
            $('input.skor_fungsional:checked').each(function() {
                score += parseInt($(this).data('skor')) || 0;
            });
            $('#hasil_skor_fungsional').val(score);

            let analisis = 'Mandiri'; // Default
            if (score <= 20) analisis = 'Ketergantungan Total';
            else if (score >= 21 && score <= 60) analisis =
                'Ketergantungan Berat'; // Sesuaikan rentang jika perlu
            else if (score >= 61 && score <= 90) analisis = 'Ketergantungan Sedang';
            else if (score >= 91 && score <= 99) analisis = 'Ketergantungan Ringan';

            $('#analisis_skor_fungsional').val(analisis);
        }
        $('input.skor_fungsional').on('change', calculateBarthelIndexScore);


        // 3. PENGKAJIAN RESIKO JATUH LANSIA
        function calculateLansiaFallRiskScore() {
            let totalSkor = 0;

            // Hitung grup checkbox
            $('.checkbox-skor-lansia').each(function() {
                const group = $(this).data('group');
                let groupScore = 0;
                $(`input.checkbox-skor-lansia[data-group="${group}"]:checked`).each(function() {
                    // Ambil skor tertinggi jika ada beberapa checkbox terpilih di grup yang sama
                    let currentSkor = parseInt($(this).data('skor')) || 0;
                    if (currentSkor > groupScore) {
                        groupScore = currentSkor;
                    }
                });
                $(`#skor_jatuh_${group}`).val(groupScore);
            });

            // Hitung grup select (Transfer & Mobilitas)
            let transferMobilitasValue = 0;
            $('.transfer-mobilitas').each(function() {
                transferMobilitasValue += parseInt($(this).val()) || 0;
            });

            let transferMobilitasSkor = 0;
            if (transferMobilitasValue >= 4 && transferMobilitasValue <= 6) {
                transferMobilitasSkor = 7;
            } else if (transferMobilitasValue >= 0 && transferMobilitasValue <= 3) {
                transferMobilitasSkor = 3;
            }
            $('#skor_jatuh_transfer_mobilitas').val(transferMobilitasSkor);


            // Jumlahkan semua skor grup
            $('.skor-jatuh-group').each(function() {
                totalSkor += parseInt($(this).val()) || 0;
            });

            $('#total_skor_jatuh_lansia').val(totalSkor);

            let analisis = 'Resiko Jatuh Tinggi';
            if (totalSkor >= 0 && totalSkor <= 5) analisis = 'Resiko Jatuh Rendah';
            else if (totalSkor >= 6 && totalSkor <= 16) analisis = 'Resiko Jatuh Sedang';

            $('#analisis_jatuh_lansia').val(analisis);
        }
        $('.checkbox-skor-lansia, .transfer-mobilitas').on('change', calculateLansiaFallRiskScore);


        // ==========================================================
        // PEMANGGILAN FUNGSI KALKULASI SAAT HALAMAN DIMUAT
        // ==========================================================
        calculateBarthelIndexScore();
        calculateLansiaFallRiskScore();


        // ==========================================================
        // AJAX UNTUK SUBMIT FORM UTAMA (LANSIA)
        // ==========================================================
        $('#form-asesmen-awal-ranap-lansia').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const saveButton = $('#btn-save-asesmen-lansia');

            saveButton.prop('disabled', true).html(
                '<span class="spinner-border spinner-border-sm"></span> Menyimpan...');

            $.ajax({
                url: "{{ route('erm.asesmen-awal-ranap-lansia.store') }}",
                type: 'POST',
                data: form.serialize(),
                success: function(response) {
                    Swal.fire('Sukses!', response.success, 'success');
                },
                error: function(jqXHR) {
                    let errorMsg = 'Terjadi kesalahan saat menyimpan data.';
                    if (jqXHR.status === 422) {
                        errorMsg = Object.values(jqXHR.responseJSON.errors).flat().join(
                            '<br>');
                        Swal.fire({
                            icon: 'error',
                            title: 'Error Validasi!',
                            html: errorMsg
                        });
                    } else {
                        Swal.fire('Error!', jqXHR.responseJSON.error || errorMsg, 'error');
                    }
                },
                complete: function() {
                    saveButton.prop('disabled', false).html('Simpan Data');
                }
            });
        });
    });
</script>
