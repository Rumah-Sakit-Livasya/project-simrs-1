{{-- PATH: resources/views/pages/simrs/poliklinik/pengkajian_lanjutan/show.blade.php --}}

@extends('inc.layout-no-side') {{-- Sesuaikan dengan layout Anda --}}
@section('content')
    {{-- Form ini menargetkan rute 'update' --}}
    <form id="edit-existing-form" method="POST"
        action="{{ route('poliklinik.pengkajian-lanjutan.update', $pengkajian->id) }}">
        @csrf
        @method('PUT')

        {{-- Variabel $formTemplate di sini adalah OBJEK, jadi kita ambil properti 'form_source'-nya --}}
        {!! $formTemplate->form_source !!}
    </form>

    {{-- Tombol hanya muncul jika dalam mode edit --}}
    @if ($isEditMode)
        <div class="mt-3">
            <div class="card">
                <div class="card-body d-flex justify-content-end">
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="is_final_checkbox" value="1">
                            <label class="form-check-label" for="is_final_checkbox">Jadikan Final & Kunci</label>
                        </div>
                        <button type="button" class="btn btn-success waves-effect waves-light save-edit-form">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- Tambahkan tombol Print jika dalam mode lihat (read-only) --}}
        <div class="mt-3 no-print">
            <div class="card">
                <div class="card-body d-flex justify-content-end">
                    <a href="{{ route('poliklinik.pengkajian-lanjutan.edit', $pengkajian->id) }}"
                        class="btn btn-warning mr-2">
                        <i class="fas fa-pencil-alt"></i> Edit
                    </a>
                    <button type="button" class="btn btn-primary" onclick="window.print()">
                        <i class="fas fa-print"></i> Cetak
                    </button>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('plugin')
    <script type="text/javascript">
        $(document).ready(function() {
            const form = $('#edit-existing-form');
            const formValues = @json($formValues);

            // 1. MENGISI FORM DENGAN DATA YANG ADA
            $.each(formValues, function(name, value) {
                const $element = form.find(`[name="${name}"], [name="${name}[]"]`);
                if ($element.length > 0) {
                    const type = $element.attr('type');
                    if (type === 'radio') {
                        $element.filter(`[value="${value}"]`).prop('checked', true);
                    } else if (type === 'checkbox') {
                        if (Array.isArray(value)) { // Untuk grup checkbox
                            value.forEach(val => $element.filter(`[value="${val}"]`).prop('checked', true));
                        } else { // Untuk single checkbox
                            $element.prop('checked', value == 1 || value === true);
                        }
                    } else { // Untuk text, select, textarea, dll.
                        $element.val(value);
                    }
                }
            });

            // 2. LOGIKA MENYIMPAN PERUBAHAN (HANYA JIKA DALAM MODE EDIT)
            $('.save-edit-form').on('click', function(e) {
                e.preventDefault();

                // Kumpulkan data form yang sudah diubah
                const updatedFormValues = {};
                const formDataArray = form.serializeArray();
                $.each(formDataArray, function(i, field) {
                    if (field.name.endsWith('[]')) {
                        let cleanName = field.name.slice(0, -2);
                        if (!updatedFormValues[cleanName]) updatedFormValues[cleanName] = [];
                        updatedFormValues[cleanName].push(field.value);
                    } else if (field.name !== '_token' && field.name !== '_method') {
                        updatedFormValues[field.name] = field.value;
                    }
                });

                // Payload untuk UPDATE
                const payload = {
                    form_values: updatedFormValues,
                    is_final: $('#is_final_checkbox').is(':checked'),
                };

                $.ajax({
                    url: form.attr('action'), // Ambil URL dari atribut action form
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
                            'Gagal menyimpan perubahan.';
                        Swal.fire('Error!', errorMessage, 'error');
                    }
                });
            });

            // 3. BUAT FORM READ-ONLY JIKA TIDAK DALAM MODE EDIT
            const isEditMode = {{ $isEditMode ? 'true' : 'false' }};
            if (!isEditMode) {
                form.find(':input').prop('disabled', true);
            }
        });
    </script>
@endsection
