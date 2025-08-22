<script>
    $(document).ready(function() {

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

        // Inisialisasi Select2
        $('.select2').select2({
            placeholder: 'Pilih...',
            allowClear: true
        });

        // Logika untuk menampilkan/menyembunyikan input 'Other' pada Aorta
        function toggleAortaOther() {
            if ($('#aorta_value_select').val() === 'Other') {
                $('#aorta_value_other').show();
            } else {
                $('#aorta_value_other').hide().val(''); // Sembunyikan dan kosongkan nilainya
            }
        }

        // Panggil saat pertama kali load
        toggleAortaOther();

        // Panggil setiap kali select berubah
        $('#aorta_value_select').on('change', function() {
            toggleAortaOther();
        });

        // Aksi Simpan Form (AJAX) - sama seperti form sebelumnya
        $('.save-form').on('click', function(e) {
            e.preventDefault();
            var status = $(this).data('status');
            var formData = new FormData($('#form-echocardiography')[0]);
            formData.append('status', status);

            $('.save-form').prop('disabled', true);

            $.ajax({
                url: "{{ route('erm.store.echocardiography') }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.success,
                        timer: 2000,
                        showConfirmButton: false
                    });
                },
                error: function(xhr) {
                    let errorText = 'Terjadi kesalahan sistem.';
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorText = xhr.responseJSON.error;
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        // Menangani error validasi dari Laravel
                         errorText = 'Silakan periksa kembali isian Anda.';
                         // Anda bisa loop error validasi di sini jika ingin lebih detail
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: errorText
                    });
                },
                complete: function() {
                    $('.save-form').prop('disabled', false);
                }
            });
        });
    });
</script>
