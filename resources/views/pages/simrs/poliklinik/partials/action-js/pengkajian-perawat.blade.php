<script>
    let actionType = '';

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
        formData += '&action_type=' + actionType + '&registration_id=' + "{{ $registration->id }}";
        formData += '&user_id=' +  "{{ auth()->user()->id }}";

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
