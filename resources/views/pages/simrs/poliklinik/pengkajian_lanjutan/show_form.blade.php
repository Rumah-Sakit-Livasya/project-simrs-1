@extends('inc.layout-no-side')
@section('content')
    <style>
        .form-control2 {
            border-left: none;
            border-right: none;
            border-top: none;
            borerder-bottom: 1px solid #ced4da !important;
            width: 100%;
        }
    </style>
    <form action="#" method="POST">
        @csrf
        @method('post')
        {!! $formTemplate !!}
    </form>

    <div class="mt-3">
        <div class="card">
            <div class="card-body d-flex justify-content-between">
                <!-- Tombol Print di Kiri -->
                <a href="#!" class="btn btn-primary waves-effect waves-light" {dis_none}="">
                    <span class="mdi mdi-printer print-pengkajian" data-pkid="" data-pregid="216320" data-ftid="155"
                        data-printtype="{print_type}" data-link="{link}"> Print</span>
                </a>

                <!-- Tombol Simpan di Kanan -->
                <div>
                    <button type="button" class="btn btn-warning waves-effect waves-light save-form text-white"
                        data-dismiss="modal" data-status="0">
                        <span class="mdi mdi-content-save"></span> Simpan (draft)
                    </button>
                    <button type="button" class="btn btn-success btn-save-final waves-effect waves-light save-form"
                        data-dismiss="modal" data-status="1">
                        <span class="mdi mdi-content-save"></span> Simpan (final)
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('plugin')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.save-form').on('click', function() {
                let status = $(this).data('status');
                let formData = new FormData();

                // Append all form data
                $('form').each(function() {
                    let form = $(this).closest('form')[0];
                    let formElements = form.elements;
                    for (let i = 0; i < formElements.length; i++) {
                        if (formElements[i].name) {
                            if (formElements[i].type === 'radio' && !formElements[i].checked) {
                                continue;
                            }
                            formData.append(formElements[i].name, formElements[i].value);
                        }
                    }
                });

                formData.append('form_template_id', '{{ $formTemplateId }}');
                formData.append('registration_id', '{{ $registrationId }}');
                formData.append('status', status);
                formData.append('_token', '{{ csrf_token() }}'); // Add CSRF token

                $.ajax({
                    url: "{{ route('pengkajian.lanjutan.store') }}",
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Data has been saved successfully.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.close();
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'An error occurred while saving the data.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });

                $('#impersonateModal').on('shown.bs.modal', function() {
                    $('#impersonate').select2({
                        placeholder: "Select a user",
                        dropdownParent: $('#impersonateModal'),
                        allowClear: true,
                    });
                });

                $('.employeeId').click(function() {
                    var employeeId = $(this).data('employee-id');
                    var width = screen.width;
                    var height = screen.height;
                    var popupWindow = window.open('/dashboard/attendances/employee/' + employeeId +
                        '/payroll',
                        'popupWindow',
                        'width=' + width + ',height=' + height + ',scrollbars=yes');

                    popupWindow.onbeforeunload = function() {
                        location.reload();
                    };
                });
            });
        });
    </script>
@endsection
