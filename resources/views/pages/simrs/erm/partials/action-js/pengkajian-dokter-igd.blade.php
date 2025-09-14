<script>
    $(document).ready(function() {
        /**
         * Menangani event klik pada tombol 'Simpan (draft)'.
         * Akan memanggil fungsi submitForm dengan tipe 'draft'.
         */
        $('#sd-pengkajian-dokter-igd').on('click', function() {
            submitForm('draft');
        });

        /**
         * Menangani event klik pada tombol 'Simpan (final)'.
         * Akan memanggil fungsi submitForm dengan tipe 'final'.
         */
        $('#sf-pengkajian-dokter-igd').on('click', function() {
            submitForm('final');
        });

        /**
         * Fungsi utama untuk mengirim data form melalui AJAX.
         * @param {string} actionType - Menentukan tipe penyimpanan ('draft' atau 'final').
         */
        function submitForm(actionType) {
            // Ambil elemen form berdasarkan ID
            const form = $('#pengkajian-dokter-igd-form');

            // Tentukan URL tujuan. Pastikan Anda sudah membuat route ini di file web.php
            const url = "{{ route('pengkajian.dokter-igd.store') }}";

            // Ambil elemen tombol untuk menonaktifkannya selama proses submit
            const draftBtn = $('#sd-pengkajian-dokter-igd');
            const finalBtn = $('#sf-pengkajian-dokter-igd');

            // Nonaktifkan tombol dan tampilkan status loading
            draftBtn.prop('disabled', true).html(
                '<span class="mdi mdi-loading mdi-spin mr-2"></span> Menyimpan...');
            finalBtn.prop('disabled', true).html(
                '<span class="mdi mdi-loading mdi-spin mr-2"></span> Menyimpan...');

            // Ambil semua data dari form
            let formData = form.serialize();

            // Tambahkan tipe aksi (draft/final) dan registration_id ke data yang akan dikirim
            formData += '&action_type=' + actionType + '&registration_id=' + "{{ $registration->id }}";

            // Kirim data menggunakan AJAX
            $.ajax({
                type: 'POST',
                url: url,
                data: formData,
                success: function(response) {
                    // Jika berhasil, tampilkan notifikasi sukses
                    if (actionType === 'draft') {
                        showSuccessAlert('Data berhasil disimpan sebagai draft!');
                    } else {
                        showSuccessAlert('Data berhasil disimpan sebagai final!');
                    }

                    // Muat ulang halaman setelah 1 detik untuk menampilkan data terbaru
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                },
                error: function(response) {
                    // Jika terjadi error, aktifkan kembali tombol dan kembalikan teks aslinya
                    draftBtn.prop('disabled', false).html(
                        '<span class="mdi mdi-content-save mr-2"></span> Simpan (draft)');
                    finalBtn.prop('disabled', false).html(
                        '<span class="mdi mdi-content-save mr-2"></span> Simpan (final)');

                    // Tampilkan notifikasi error validasi
                    if (response.responseJSON && response.responseJSON.errors) {
                        var errors = response.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            // Tampilkan setiap pesan error
                            showErrorAlertNoRefresh(value[0]);
                        });
                    } else {
                        // Tampilkan error umum jika format tidak sesuai
                        showErrorAlertNoRefresh('Terjadi kesalahan. Silakan coba lagi.');
                    }
                }
            });
        }
    });
</script>
