{{-- PATH: resources/views/pages/simrs/poliklinik/pengkajian_lanjutan/show_form.blade.php --}}

@extends('inc.layout-no-side') {{-- Sesuaikan dengan layout Anda --}}
@section('content')
    <form id="create-new-form" method="POST">
        @csrf
        {{-- Variabel $formTemplate di sini berisi string HTML mentah dari controller --}}
        {!! $formTemplate !!}
    </form>

    <div class="mt-3">
        <div class="card">
            <div class="card-body d-flex justify-content-end">
                <div>
                    <button type="button" class="btn btn-warning waves-effect waves-light save-form text-white"
                        data-status="0">
                        <i class="fas fa-save"></i> Simpan (Draft)
                    </button>
                    <button type="button" class="btn btn-success waves-effect waves-light save-form" data-status="1">
                        <i class="fas fa-check-circle"></i> Simpan (Final)
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('plugin')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.save-form').on('click', function(e) {
                e.preventDefault();

                let isFinal = $(this).data('status') == 1;
                const form = $('#create-new-form');
                const formValues = {};
                const formDataArray = form.serializeArray();

                // Mengumpulkan data dari form menjadi objek
                $.each(formDataArray, function(i, field) {
                    if (field.name.endsWith('[]')) {
                        let cleanName = field.name.slice(0, -2);
                        if (!formValues[cleanName]) {
                            formValues[cleanName] = [];
                        }
                        formValues[cleanName].push(field.value);
                    } else if (field.name !== '_token') { // Abaikan token CSRF di sini
                        formValues[field.name] = field.value;
                    }
                });

                // Payload untuk dikirim ke rute 'store'
                const payload = {
                    registration_id: '{{ $registrationId }}',
                    form_template_id: '{{ $formTemplateId }}',
                    form_values: formValues,
                    is_final: isFinal
                };

                // Mengirim data via AJAX
                $.ajax({
                    url: "{{ route('poliklinik.pengkajian-lanjutan.store') }}", // Rute untuk CREATE
                    method: 'POST',
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: JSON.stringify(payload),
                    success: function(response) {
                        Swal.fire('Berhasil!', response.message, 'success').then(() => {
                            if (window.opener) window.opener.location.reload();
                            window.close();
                        });
                    },
                    error: function(xhr) {
                        let errorMessage = xhr.responseJSON ? xhr.responseJSON.message :
                            'Terjadi kesalahan.';
                        Swal.fire('Error!', errorMessage, 'error');
                    }
                });
            });
        });
    </script>
@endsection
