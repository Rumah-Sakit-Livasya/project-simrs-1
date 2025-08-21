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
        // FUNGSI KALKULASI SKOR
        // ==========================================================

        // 1. ASESMEN SKOR NYERI (Wong-Baker Scale)
        $('.wong-baker-scale .pointer').on('click', function() {
            const skor = $(this).data('skor');
            $('#skor_nyeri').val(skor);
        });

        // 2. SKRINING GIZI (Malnutrition Screening Tool - MST)
        function calculateMstScore() {
            let scoreA = 0;
            let scoreB = 0;

            const radioPenurunanBB = $('input[name="nutrisi[skrining_mst][penurunan_bb]"]:checked');
            // Jika ada radio button utama (tidak, ragu, tidak tahu) yang dipilih
            if (radioPenurunanBB.length > 0) {
                scoreA = parseInt(radioPenurunanBB.data('skor')) || 0;
            } else {
                // Jika tidak ada, cek pilihan detail (1-5kg, dst.), karena memilih salah satunya
                // secara implisit berarti jawabannya "Ya".
                const radioDetail = $('input[name="nutrisi[skrining_mst][penurunan_bb_detail]"]:checked');
                if (radioDetail.length > 0) {
                    scoreA = parseInt(radioDetail.data('skor')) || 0;
                }
            }

            const radioAsupan = $('input[name="nutrisi[skrining_mst][asupan_berkurang]"]:checked');
            if (radioAsupan.length > 0) {
                scoreB = parseInt(radioAsupan.data('skor')) || 0;
            }

            const totalSkor = scoreA + scoreB;
            $('#hasil_skor_mst').val(totalSkor);

            let analisis = '-';
            if (totalSkor >= 2) {
                analisis = 'Lapor ahli gizi untuk asesmen lanjutan';
            } else if (totalSkor === 1 || totalSkor === 0) {
                analisis = 'Tidak beresiko malnutrisi';
            }
            $('#analisis_skor_mst').val(analisis);
        }

        // Pemicu untuk kalkulasi skor MST
        $('input.skor_mst').on('change', function() {
            // Jika user memilih salah satu detail penurunan BB, uncheck pilihan utama "Tidak", "Ragu", dll.
            if ($(this).attr('name') === 'nutrisi[skrining_mst][penurunan_bb_detail]') {
                $('input[name="nutrisi[skrining_mst][penurunan_bb]"]').prop('checked', false);
            }
            // Jika user memilih pilihan utama, uncheck pilihan detail
            if ($(this).attr('name') === 'nutrisi[skrining_mst][penurunan_bb]') {
                $('input[name="nutrisi[skrining_mst][penurunan_bb_detail]"]').prop('checked', false);
            }
            calculateMstScore();
        });


        // 3. PENGKAJIAN RESIKO JATUH (Morse Fall Score)
        function calculateMorseFallScore() {
            let score = 0;
            // Loop melalui setiap grup radio dan tambahkan skor dari yang terpilih
            $('input.morse_fall:checked').each(function() {
                score += parseInt($(this).data('skor')) || 0;
            });

            $('#skor_morse_fall').val(score);

            let analisis = 'Resiko Jatuh Tinggi';
            if (score >= 0 && score <= 24) {
                analisis = 'Resiko Jatuh Rendah';
            } else if (score >= 25 && score <= 44) { // Di beberapa literatur 25-50
                analisis = 'Resiko Jatuh Sedang';
            }
            $('#analisis_morse_fall').val(analisis);
        }

        // Pemicu untuk kalkulasi skor Morse Fall
        $('input.morse_fall').on('change', calculateMorseFallScore);


        // ==========================================================
        // PEMANGGILAN FUNGSI KALKULASI SAAT HALAMAN DIMUAT
        // ==========================================================
        // Ini untuk mengisi nilai skor dan analisis jika data sudah ada
        calculateMstScore();
        calculateMorseFallScore();


        // ==========================================================
        // AJAX UNTUK SUBMIT FORM UTAMA
        // ==========================================================
        $('#form-asesmen-awal-ranap').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const saveButton = $('#btn-save-asesmen');

            saveButton.prop('disabled', true).html(
                '<span class="spinner-border spinner-border-sm"></span> Menyimpan...');

            $.ajax({
                url: "{{ route('erm.asesmen-awal-ranap.store') }}",
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
