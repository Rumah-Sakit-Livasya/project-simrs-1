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

        // Inisialisasi Tags Input
        $('#allergy_medicine, #allergy_food').tagsinput();

        // Fungsi Kalkulasi BMI (sama seperti sebelumnya)
        function calculateBmi() {
            let height = parseFloat($('#height_badan').val());
            let weight = parseFloat($('#weight_badan').val());
            let bmiField = $('#bmi');
            let catBmiField = $('#kat_bmi');

            if (height > 0 && weight > 0) {
                let bmi = weight / ((height / 100) ** 2);
                bmi = bmi.toFixed(2);
                bmiField.val(bmi);

                let category = '';
                if (bmi < 18.5) category = 'Kurus';
                else if (bmi >= 18.5 && bmi <= 24.9) category = 'Normal';
                else if (bmi >= 25 && bmi <= 29.9) category = 'Gemuk';
                else category = 'Obesitas';
                catBmiField.val(category);
            } else {
                bmiField.val('');
                catBmiField.val('');
            }
        }

        $('.calc-bmi').on('keyup change', calculateBmi);
        calculateBmi(); // Panggil saat pertama kali load

        // Aksi Simpan Form
        $('#form-pemeriksaan-awal-ranap').on('submit', function(e) {
            e.preventDefault();

            const btn = $('#btn-save');
            const formData = new FormData(this);

            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Menyimpan...');

            $.ajax({
                url: "{{ route('erm.store.pemeriksaan-awal-ranap') }}",
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
                    if (xhr.status === 422) { // Handle validation errors
                        errorText = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                    }
                    Swal.fire({ icon: 'error', title: 'Gagal!', html: errorText });
                },
                complete: function() {
                    btn.prop('disabled', false).html('<i class="fas fa-save"></i> Simpan Data');
                }
            });
        });
    });
</script>
