<script>
    $(document).ready(function() {

        $('.datepicker-input').datepicker({
            format: 'dd/mm/yyyy', // Format tampilan tanggal
            autoclose: true, // Menutup datepicker otomatis setelah memilih tanggal
            todayHighlight: true, // Menyoroti tanggal hari ini
            language: 'id', // Locale Indonesia untuk hari dan bulan
        });


        $('.bsd-resume-medis-rajal').on('click', function() {
            submitFormResume('draft'); // Panggil fungsi submitFormResume dengan parameter final
        });
        $('.bsf-resume-medis-rajal').on('click', function() {
            submitFormResume('final'); // Panggil fungsi submitForm dengan parameter final
        });

        $('.btn-ttd-resume-medis').on('click', function() {
            const idUser = $(this).attr('data-id');
            const token = "{{ csrf_token() }}";
            const ttd = "{{ auth()->user()->employee->ttd ? auth()->user()->employee->ttd : '' }}";

            if (ttd) {
                const path = "/api/simrs/signature/" + ttd + "?token=" + token;
                $(this).hide();
                $('input[name=is_ttd]').val(1);
                $('#signature-display').attr('src', path).show();
            } else {
                showErrorAlert('Tanda tangan tidak ditemukan!');
            }
        });

        function submitFormResume(actionType) {
            const form = $('#resume-medis-rajal-form'); // Ambil form
            const url = "{{ route('resume-medis.dokter-rajal.store') }}" // Ambil URL dari action form

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
                    showErrorAlert('Gagal Disimpan!');
                }
            });
        }
    });
</script>