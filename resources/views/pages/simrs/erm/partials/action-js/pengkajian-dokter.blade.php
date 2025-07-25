<script>
    $(document).ready(function() {

        function get_bmi_pd() {
            var pdA = $('#pengkajian-dokter-rajal-form #body_height').val();
            var pdB = $('#pengkajian-dokter-rajal-form #body_weight').val();
            console.log(pdA);

            if (pdA !== '' && pdB !== '') {
                pdA = pdA / 100; // Mengonversi tinggi dari cm ke m
                var pdC = pdB / (pdA * pdA); // Menghitung BMI
                pdC = Math.round(pdC * 10) / 10; // Membulatkan BMI

                // Menentukan kategori BMI
                if (pdC < 18.5) {
                    $('#pengkajian-dokter-rajal-form #kat_bmi').val('Kurus');
                } else if (pdC > 24.9) {
                    $('#pengkajian-dokter-rajal-form #kat_bmi').val('Gemuk');
                } else {
                    $('#pengkajian-dokter-rajal-form #kat_bmi').val('Normal');
                }

                // Mengatur nilai BMI
                $('#pengkajian-dokter-rajal-form #bmi').val(pdC);

                // Menandai input sebagai 'dirty'
                $('#pengkajian-dokter-rajal-form #bmi, #pengkajian-dokter-rajal-form #kat_bmi').addClass(
                    'dirty');
            } else {
                // Reset nilai jika input tidak valid
                $('#pengkajian-dokter-rajal-form #bmi').val('');
                $('#pengkajian-dokter-rajal-form #kat_bmi').val('');
                $('#pengkajian-dokter-rajal-form #bmi, #pengkajian-dokter-rajal-form #kat_bmi').removeClass(
                    'dirty');
            }
        }

        // Memanggil fungsi get_bmi_pd pada saat halaman dimuat
        get_bmi_pd();


        // Mengikat fungsi get_bmi_pd ke event change pada elemen dengan kelas calc-bmi
        $('.calc-bmi-pd').on('change', get_bmi_pd);

        // Saat tombol Save Draft diklik
        $('#sd-pengkajian-dokter-rajal').on('click', function() {
            submitForm('draft'); // Panggil fungsi submitForm dengan parameter draft
        });


        // Saat tombol Save Final diklik
        $('#sf-pengkajian-dokter-rajal').on('click', function() {
            submitForm('final'); // Panggil fungsi submitForm dengan parameter final
        });

        function submitForm(actionType) {
            const form = $('#pengkajian-dokter-rajal-form'); // Ambil form
            const url = "{{ route('pengkajian.dokter-rajal.store') }}" // Ambil URL dari action form

            let formData = form.serialize(); // Ambil data dari form

            // Tambahkan tipe aksi (draft atau final) ke data form
            formData += '&action_type=' + actionType + '&registration_id=' + "{{ $registration->id }}";

            $.ajax({
                type: 'POST',
                url: url,
                data: formData,
                success: function(response) {
                    if (actionType === 'draft') {
                        showSuccessAlert('Data berhasil disimpan sebagai draft!');
                    } else {
                        showSuccessAlert('Data berhasil disimpan sebagai final!');
                    }
                    setTimeout(() => {
                        console.log('Reloading the page now.');
                        window.location.reload();
                    }, 1000);
                },
                error: function(response) {
                    // Tangani error
                    var errors = response.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        showErrorAlertNoRefresh(value[0]);
                    });
                }
            });
        }
    })
</script>
