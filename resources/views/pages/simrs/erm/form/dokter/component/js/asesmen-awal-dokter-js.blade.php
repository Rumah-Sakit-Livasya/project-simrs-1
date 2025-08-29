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

        // Inisialisasi Select2 dan Datepicker
        $('.select2').select2();
        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            todayHighlight: true
        });

        // Fungsi Kalkulasi BMI
        function calculateBmi() {
            let height = parseFloat($('#height_badan').val());
            let weight = parseFloat($('#weight_badan').val());
            let bmiField = $('#bmi');
            let catBmiField = $('#kat_bmi');

            if (height > 0 && weight > 0) {
                let bmi = weight / ((height / 100) ** 2);
                bmi = Math.round(bmi * 10) / 10;
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
        const initPainterro = (id, inputId, defaultImgUrl) => {
    // 1. Tentukan sumber gambar DULUAN
    const existingImage = $(inputId).val();
    // Jika 'existingImage' punya isi (bukan string kosong), gunakan itu. Jika tidak, gunakan 'defaultImgUrl'.
    const imageSource = existingImage || defaultImgUrl;

    // 2. Inisialisasi instance Painterro
    const painterroInstance = Painterro({
        id: id,
        defaultTool: 'brush',
        defaultLineWidth: 2,
        hiddenTools: ['rotate', 'close', 'select', 'crop', 'pixelize', 'resize', 'settings', 'save'],
        onChange: (ptro) => {
            // Handler ini sudah benar dan akan tetap berfungsi
            $(inputId).val(ptro.image.asDataURL());
        }
    });

    // 3. Tampilkan Painterro dengan sumber gambar yang sudah ditentukan
    //    Ini cara yang paling andal. Painterro akan menangani sisanya.
    painterroInstance.show(imageSource, (img) => {
        // Kita bisa gunakan callback ini untuk memeriksa jika ada error saat memuat gambar
        if (!img) {
            console.error('Painterro gagal memuat gambar. Sumber:', imageSource);
            // Anda bisa menampilkan pesan error ke pengguna di sini jika perlu
        }
    });
};

// Pastikan sekali lagi path dan ekstensi file sudah benar di sini
initPainterro('img-tubuh', '#myimage-tubuh', '{{ asset('img/simrs/tubuh.png') }}');
initPainterro('img-kepala', '#myimage-kepala', '{{ asset('img/simrs/kepala.png') }}');

        // Aksi Simpan Form (Draft & Final)
        $('.save-form').on('click', function(e) {
            e.preventDefault();
            let isValid = true;
            let errorMessages = [];

            // Validasi input numerik di Tanda Vital
            $('input[name^="tanda_vital"]').each(function() {
                const value = $(this).val();
                // Cek jika field tidak kosong dan bukan angka (kecuali untuk BMI yang readonly)
                if (value && !$(this).prop('readonly') && isNaN(parseFloat(value))) {
                    isValid = false;
                    // Dapatkan label atau nama untuk pesan error
                    const fieldName = $(this).closest('.form-group').find('label').text() || $(this).attr('name');
                    errorMessages.push(`Input '${fieldName}' harus berupa angka.`);
                    $(this).addClass('is-invalid'); // Beri highlight merah
                } else {
                    $(this).removeClass('is-invalid');
                }
            });

            if (!isValid) {
                Swal.fire({
                    icon: 'error',
                    title: 'Data Tidak Valid!',
                    html: errorMessages.join('<br>'),
                });
                return; // Hentikan proses simpan
            }

            var status = $(this).data('status');
            var formData = new FormData($('#form-asesmen-awal-dokter')[0]);
            formData.append('status', status);

            // Disable button untuk mencegah double click
            $('.save-form').prop('disabled', true);

            $.ajax({
                url: "{{ route('erm.store.asesmen-awal-dokter') }}",
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
                     // Opsional: reload atau redirect jika perlu
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: xhr.responseJSON.error || 'Terjadi kesalahan sistem.'
                    });
                },
                complete: function() {
                     $('.save-form').prop('disabled', false);
                }
            });
        });
    });
</script>
