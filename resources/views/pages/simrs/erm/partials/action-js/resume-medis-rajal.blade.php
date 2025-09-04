<script>
    $(document).ready(function() {
        // Initialize form handlers
        initializeFormHandlers();
    });

    /**
     * Initialize all form event handlers
     */
    function initializeFormHandlers() {
        // Handle draft save button
        $('.bsd-resume-medis-rajal').on('click', function() {
            submitFormResume('draft');
        });

        // Handle final save button
        $('.bsf-resume-medis-rajal').on('click', function() {
            submitFormResume('final');
        });

        // Handle signature button
        $('.btn-ttd-resume-medis').on('click', function() {
            handleSignature($(this).attr('data-id'));
        });
    }

    /**
     * Handle signature functionality
     * @param {string} userId - User ID for signature
     */
    function handleSignature(userId) {
        const token = "{{ csrf_token() }}";
        const signaturePath = "{{ auth()->user()->employee->ttd ?? '' }}";

        if (signaturePath) {
            const path = "/api/simrs/signature/" + signaturePath + "?token=" + token;
            $('.btn-ttd-resume-medis').hide();
            $('input[name=is_ttd]').val(1);
            $('#signature-display').attr('src', path).show();
        } else {
            showErrorAlertNoRefresh('Tanda tangan tidak ditemukan!');
        }
    }

    /**
     * Submit the resume medical form
     * @param {string} actionType - Either 'draft' or 'final'
     */
    function submitFormResume(actionType) {
        const form = $('#resume-medis-rajal-form');
        const url = "{{ route('resume-medis.dokter-rajal.store') }}";

        // Serialize form data
        let formData = form.serialize();
        formData += '&action_type=' + actionType + '&registration_id=' + "{{ $registration->id }}";

        // Show loading state
        showLoadingState(true);

        $.ajax({
            type: 'POST',
            url: url,
            data: formData,
            success: function(response) {
                handleSubmitSuccess(actionType);
            },
            error: function(response) {
                handleSubmitError(response);
            },
            complete: function() {
                showLoadingState(false);
            }
        });
    }

    /**
     * Handle successful form submission
     * @param {string} actionType - Action type that was successful
     */
    function handleSubmitSuccess(actionType) {
        const message = actionType === 'draft' ?
            'Data berhasil disimpan sebagai draft!' :
            'Data berhasil disimpan sebagai final!';

        showSuccessAlert(message);

        setTimeout(() => {
            window.location.reload();
        }, 1000);
    }

    /**
     * Handle form submission error
     * @param {object} response - AJAX error response
     */
    function handleSubmitError(response) {
        if (response.status === 422) {
            showErrorAlertNoRefresh("Kolom yang wajib diisi belum ter isi!");
        } else {
            const errorMessage = response.responseJSON?.error || 'Terjadi kesalahan saat menyimpan data!';
            showErrorAlertNoRefresh(errorMessage);
        }
    }

    /**
     * Show or hide loading state on buttons
     * @param {boolean} isLoading - Whether to show loading state
     */
    function showLoadingState(isLoading) {
        const buttons = $('.bsd-resume-medis-rajal, .bsf-resume-medis-rajal');

        if (isLoading) {
            buttons.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Menyimpan...');
        } else {
            buttons.prop('disabled', false).html(function() {
                return $(this).hasClass('bsd-resume-medis-rajal') ?
                    '<span class="mdi mdi-content-save"></span> Simpan (draft)' :
                    '<span class="mdi mdi-content-save"></span> Simpan (final)';
            });
        }
    }
</script>
