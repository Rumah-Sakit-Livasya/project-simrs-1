<script>
    let actionType = '';

    function get_bmi() {
        var pdA = $('#body_height').val();
        var pdB = $('#body_weight').val();
        console.log(pdA);

        if (pdA !== '' && pdB !== '') {
            pdA = pdA / 100; // Mengonversi tinggi dari cm ke m
            var pdC = pdB / (pdA * pdA); // Menghitung BMI
            pdC = Math.round(pdC * 10) / 10; // Membulatkan BMI

            // Menentukan kategori BMI
            if (pdC < 18.5) {
                $('#kat_bmi').val('Kurus');
            } else if (pdC > 24.9) {
                $('#kat_bmi').val('Gemuk');
            } else {
                $('#kat_bmi').val('Normal');
            }

            // Mengatur nilai BMI
            $('#bmi').val(pdC);

            // Menandai input sebagai 'dirty'
            $('#bmi, #kat_bmi').addClass('dirty');
        } else {
            // Reset nilai jika input tidak valid
            $('#bmi').val('');
            $('#kat_bmi').val('');
            $('#bmi, #kat_bmi').removeClass('dirty');
        }
    }

    // Memanggil fungsi get_bmi_pd pada saat halaman dimuat
    get_bmi();


    // Mengikat fungsi get_bmi_pd ke event change pada elemen dengan kelas calc-bmi
    $('.calc-bmi').on('change', get_bmi);


    function resiko_jatuh() {
        var resiko_jatuh1 = document.getElementById('resiko_jatuh1').checked;
        var resiko_jatuh2 = document.getElementById('resiko_jatuh2').checked;
        var resiko_jatuh3 = document.getElementById('resiko_jatuh3').checked;

        if (resiko_jatuh1 == false && resiko_jatuh2 == false && resiko_jatuh3 == false) {
            $('#resiko_jatuh_hasil').val("Tidak Beresiko");
        } else if (resiko_jatuh1 == true || resiko_jatuh2 == true) {
            if (resiko_jatuh3 == true) {
                $('#resiko_jatuh_hasil').val("Resiko Tinggi");
            } else if (resiko_jatuh3 == false) {
                $('#resiko_jatuh_hasil').val("Resiko Sedang");
            }
        } else if (resiko_jatuh1 == false || resiko_jatuh2 == false) {
            if (resiko_jatuh3 == true) {
                $('#resiko_jatuh_hasil').val("Resiko Sedang");
            } else if (resiko_jatuh3 == false) {
                $('#resiko_jatuh_hasil').val("Resiko Tinggi");
            }
        }
    };
    resiko_jatuh();



    $('#sd-pengkajian-nurse-rajal').off('click').on('click', function(event) {
        event.preventDefault();
        actionType = 'draft';
        submitForm(actionType);
    });

    $('#sf-pengkajian-nurse-rajal').off('click').on('click', function(event) {
        event.preventDefault();
        actionType = 'final';
        submitForm(actionType);
    });

    let isSubmitting = false;

    function submitForm(actionType) {
        console.log(`submitForm called with actionType: ${actionType}`); // Tambahkan log

        if (isSubmitting) return; // Jika sudah mengirim, keluar dari fungsi
        isSubmitting = true; // Set flag menjadi true

        const form = $('#pengkajian_perawat_form'); // Ambil form
        const url = "{{ route('pengkajian.nurse-rajal.store') }}" // Ambil URL dari action form

        let formData = form.serialize(); // Ambil data dari form

        // Tambahkan tipe aksi (draft atau final) ke data form
        formData += '&action_type=' + actionType + '&registration_id=' + "{{ $registration?->id }}";
        formData += '&user_id=' + "{{ auth()->user()->id }}";

        $.ajax({
            type: 'POST',
            url: url,
            data: formData,
            success: function(response) {
                isSubmitting = false; // Reset flag setelah berhasil
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
                isSubmitting = false; // Reset flag jika terjadi error
                // Tangani error
                var errors = response.responseJSON.errors;
                $.each(errors, function(key, value) {
                    showErrorAlert(value[0]);
                });
            }
        });
    }
</script>
