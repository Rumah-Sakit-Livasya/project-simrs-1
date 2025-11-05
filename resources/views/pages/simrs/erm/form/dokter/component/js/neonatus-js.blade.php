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

        // FUNGSI KALKULASI SKOR APGAR
        function calculateApgarScore(className) {
            let totalScore = 0;
            $('.' + className + ':checked').each(function() {
                totalScore += parseInt($(this).data('skor')) || 0;
            });
            return totalScore;
        }

        function updateAllApgarScores() {
            $('#skor1').val(calculateApgarScore('skor_1'));
            $('#skor2').val(calculateApgarScore('skor_2'));
            $('#skor3').val(calculateApgarScore('skor_3'));
        }

        $('.skor_1, .skor_2, .skor_3').on('change', function() {
            updateAllApgarScores();
        });

        // Panggil saat halaman dimuat untuk menghitung skor dari data yang sudah ada
        updateAllApgarScores();

        // AJAX UNTUK SUBMIT FORM UTAMA
        $('#form-pengkajian-awal-neonatus').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const saveButton = $('#btn-save-pengkajian-neonatus');

            saveButton.prop('disabled', true).html(
                '<span class="spinner-border spinner-border-sm"></span> Menyimpan...');

            $.ajax({
                url: "{{ route('erm.pengkajian-awal-neonatus.store') }}",
                type: 'POST',
                data: form.serialize(),
                success: function(response) {
                    Swal.fire('Sukses!', response.success, 'success').then(() => {
                        // Opsi: Anda bisa reload tab aktif atau halaman
                        // location.reload();
                    });
                },
                error: function(jqXHR) {
                    let errorMsg = 'Terjadi kesalahan saat menyimpan data.';
                    if (jqXHR.status === 422) {
                        const errors = jqXHR.responseJSON.errors;
                        errorMsg = '<ul>';
                        $.each(errors, function(key, value) {
                            errorMsg += '<li>' + value[0] + '</li>';
                        });
                        errorMsg += '</ul>';
                        Swal.fire({
                            icon: 'error',
                            title: 'Error Validasi!',
                            html: errorMsg
                        });
                    } else if (jqXHR.responseJSON && jqXHR.responseJSON.error) {
                        Swal.fire('Error!', jqXHR.responseJSON.error, 'error');
                    } else {
                        Swal.fire('Error!', errorMsg, 'error');
                    }
                },
                complete: function() {
                    saveButton.prop('disabled', false).html('Simpan Data');
                }
            });
        });

        // Inisialisasi plugin jika belum ada di template utama
        $('.datepicker').datepicker({
            todayHighlight: true,
            orientation: "bottom left",
            format: 'yyyy-mm-dd', // Format yang disimpan adalah YYYY-MM-DD
            autoclose: true
        });
        // Pastikan plugin datetimepicker sudah dimuat sebelum inisialisasi
        $('.timepicker').daterangepicker({
            // --- Opsi Inti ---
            singleDatePicker: true, // Wajib: hanya pilih satu titik waktu
            timePicker: true, // Wajib: aktifkan pilihan waktu
            timePicker24Hour: true, // Wajib: gunakan format 24 jam (00-23)
            timePickerSeconds: false, // Opsional: nonaktifkan pilihan detik
            autoUpdateInput: true, // Wajib: otomatis update nilai di input field

            // --- Opsi Tampilan & Format ---
            locale: {
                format: 'HH:mm', // Format yang akan ditampilkan dan disimpan di input
                cancelLabel: 'Batal',
                applyLabel: 'Pilih'
            }
        }).on('show.daterangepicker', function(ev, picker) {
            // Trik untuk menyembunyikan kalender dan hanya fokus pada jam
            // Ini membuat UI-nya terlihat seperti timepicker murni
            picker.container.find('.calendar-table').hide();
        });

    });
</script>
