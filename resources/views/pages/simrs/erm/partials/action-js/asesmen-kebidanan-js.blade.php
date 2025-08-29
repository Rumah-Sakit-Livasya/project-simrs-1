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
        // FUNGSI KALKULASI SKOR (jika ada di masa depan)
        // ==========================================================
        // Saat ini tidak ada skor yang dihitung secara otomatis di form kebidanan.
        // Namun, jika Anda menambahkan skala seperti Bishop Score atau lainnya,
        // logikanya bisa ditambahkan di sini.

        // Contoh: event listener untuk skor nyeri yang bisa digunakan kembali
        $('.wong-baker-scale .pointer').on('click', function() {
            const skor = $(this).data('skor');
            $('#skor_nyeri').val(skor);
        });


        // ==========================================================
        // AJAX UNTUK SUBMIT FORM UTAMA (KEBIDANAN)
        // ==========================================================
        $('#form-asesmen-awal-kebidanan').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const saveButton = $('#btn-save-asesmen-kebidanan');

            saveButton.prop('disabled', true).html(
                '<span class="spinner-border spinner-border-sm"></span> Menyimpan...');

            $.ajax({
                url: "{{ route('erm.asesmen-awal-kebidanan.store') }}",
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
