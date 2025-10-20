@extends('inc.layout-no-side')
@section('title', 'Ubah Data Pegawai')

@section('extended-css')
    {{-- Anda bisa menambahkan CSS khusus untuk halaman ini jika perlu --}}
@endsection

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="subheader">
            <h1 class="subheader-title">
                <i class='subheader-icon fal fa-pencil-alt'></i> Ubah Data Pegawai
                <small>
                    Mengubah detail untuk: <strong>{{ $employee->fullname }}</strong>
                </small>
            </h1>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form id="edit-employee-form">
                                @csrf

                                {{-- TAMPILKAN SELURUH FORM DALAM SATU HALAMAN TANPA STEP/SELANJUTNYA --}}
                                @include('pages.pegawai.daftar-pegawai.partials.edit-form-content', [
                                    'full_page' => true,
                                ])

                                <div
                                    class="panel-content border-faded border-left-0 border-right-0 border-bottom-0 d-flex flex-row align-items-center">
                                    <button class="btn btn-primary ml-auto" type="submit">
                                        <span class="ikon-simpan">
                                            <i class="fal fa-save"></i> Simpan Perubahan
                                        </span>
                                        <span class="spinner-text d-none">
                                            <span class="spinner-border spinner-border-sm" role="status"
                                                aria-hidden="true"></span>
                                            Menyimpan...
                                        </span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('plugin')
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>

    <script>
        $(document).ready(function() {
            // Inisialisasi semua plugin
            $('.select2').select2({
                placeholder: 'Pilih data...',
            });
            $('.datepicker').datepicker({
                todayBtn: "linked",
                clearBtn: true,
                todayHighlight: true,
                format: "yyyy-mm-dd",
                autoclose: true
            });

            // Handler untuk submit form
            $('#edit-employee-form').on('submit', function(e) {
                e.preventDefault();
                let formData = $(this).serialize();
                let employeeId = {{ $employee->id }};
                const submitButton = $(this).find('button[type="submit"]');

                $.ajax({
                    url: `/api/dashboard/employee/${employeeId}`,
                    type: 'PUT',
                    data: formData,
                    beforeSend: function() {
                        submitButton.prop('disabled', true);
                        submitButton.find('.ikon-simpan').addClass('d-none');
                        submitButton.find('.spinner-text').removeClass('d-none');
                    },
                    success: function(response) {
                        showSuccessAlert(response.message);
                        // Tutup popup setelah 1.5 detik
                        setTimeout(function() {
                            window.close();
                        }, 1500);
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let errorMessage = 'Terdapat kesalahan input:\n<ul>';
                            $.each(errors, function(key, value) {
                                errorMessage += `<li>${value[0]}</li>`;
                            });
                            errorMessage += '</ul>';
                            showErrorAlertNoRefresh(errorMessage);
                        } else {
                            showErrorAlert('Terjadi kesalahan pada server. Silakan coba lagi.');
                        }
                    },
                    complete: function() {
                        submitButton.prop('disabled', false);
                        submitButton.find('.ikon-simpan').removeClass('d-none');
                        submitButton.find('.spinner-text').addClass('d-none');
                    }
                });
            });
        });
    </script>
@endsection
