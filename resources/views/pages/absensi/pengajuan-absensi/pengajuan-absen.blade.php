@extends('inc.layout')
@section('title', 'Pengajuan Absensi')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            DAFTAR FORM PENGAJUAN ABSENSI
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form action="javascript:void(0)" method="POST" enctype="multipart/form-data"
                                id="form-pengajuan-absen">
                                @csrf
                                <div class="row" id="employee-forms-container">
                                    <div class="col-md-12 employee-form-group">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group mb-3">
                                                    <label for="employee_id">Pegawai</label>
                                                    <input type="text" name="employee_id[]" class="form-control"
                                                        placeholder="Masukan minimal 5 huruf...">
                                                    <input type="hidden" name="employee_id_fix[]"
                                                        class="employee_id_hidden">
                                                    <!-- Dropdown untuk hasil pencarian -->
                                                    <div class="dropdown-pegawai position-absolute bg-white border w-100"
                                                        style="display: none; z-index: 1000;"></div>

                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group mb-3">
                                                    <label for="date">Tanggal</label>
                                                    <div class="input-group">
                                                        <input type="text" name="tanggal[]"
                                                            class="form-control @error('date') is-invalid @enderror"
                                                            placeholder="Tanggal" id="date" value="{{ now() }}"
                                                            readonly>
                                                        <div class="input-group-append">
                                                            <span class="input-group-text fs-xl">
                                                                <i class="fal fa-calendar-alt"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    @error('date')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group mb-3">
                                                    <label for="clockin">Clock In</label>
                                                    <input type="time" class="form-control" name="clockin[]">
                                                    @error('clockin')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group mb-3">
                                                    <label for="clockout">Clock Out</label>
                                                    <input type="time" class="form-control" name="clockout[]">
                                                    @error('clockout')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <button class="btn btn-primary add-employee-btn">[+] Tambah Pegawai</button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mt-3">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Lampiran</label>
                                            <div class="custom-file">
                                                <input type="file" name="lampiran" class="custom-file-input"
                                                    id="lampiran">
                                                <label class="custom-file-label" for="customLampiran">Unggah
                                                    File</label>
                                            </div>
                                        </div>
                                        <button class="btn btn-block btn-primary" id="ajukan">Ajukan</button>
                                    </div>
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
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(document).ready(function() {
            $('.add-employee-btn').click(function(e) {
                e.preventDefault(); // Mencegah submit form jika tombol berada di dalam form

                // Duplikat elemen employee-form-group
                let $lastFormGroup = $('.employee-form-group').last(); // Ambil form group terakhir
                let $clone = $lastFormGroup.clone(); // Duplikat form group

                // Reset nilai input di duplikat, kecuali input tanggal
                $clone.find('input').each(function() {
                    if ($(this).attr('name') === 'tanggal[]') {
                        // Pastikan input tanggal tetap readonly, clone value, dan tipe text
                        $(this).attr('type', 'text'); // Pastikan tipe tetap text
                        $(this).val($(this).val()); // Pertahankan nilai
                        return; // Skip iterasi untuk input tanggal
                    }
                    $(this).val(''); // Kosongkan nilai input lainnya
                    $(this).removeAttr('readonly'); // Hapus readonly jika ada
                });

                // Tambahkan duplikat tepat di bawah elemen employee-form-group terakhir
                $clone.insertAfter($lastFormGroup);
            });

            $(document).on('input', 'input[name="employee_id[]"]', function() {
                let $input = $(this); // Input aktif
                let query = $input.val(); // Nilai input
                let $dropdown = $input.siblings('.dropdown-pegawai'); // Dropdown terkait input ini

                // Tampilkan dropdown jika karakter lebih dari atau sama dengan 3
                if (query.length >= 5) {
                    $.ajax({
                        url: '/api/dashboard/user/getByName', // Endpoint Laravel
                        method: 'GET',
                        data: {
                            q: query
                        }, // Kirim parameter pencarian
                        success: function(data) {
                            $dropdown.empty();
                            if (data.data.length > 0) {
                                data.data.forEach(function(pegawai) {
                                    $dropdown.append(`
            <div class="dropdown-item" data-id="${pegawai.id}" data-fullname="${pegawai.fullname}">
                ${pegawai.fullname}
            </div>
        `);
                                });
                                $dropdown.show(); // Tampilkan dropdown
                            } else {
                                $dropdown.append(
                                    '<div class="dropdown-item">Tidak ditemukan</div>');
                                $dropdown.show();
                            }

                        }
                    });
                } else {
                    $dropdown.hide(); // Sembunyikan dropdown jika kurang dari 3 karakter
                }
            });

            // Event untuk klik pada item dropdown
            $(document).on('click', '.dropdown-pegawai .dropdown-item', function() {
                let $item = $(this);
                let employeeId = $item.data('id');
                let employeeFullname = $item.data('fullname');
                let $parent = $item.closest('.form-group');

                // Set nilai ke input yang sesuai
                $parent.find('input[name="employee_id_fix[]"]').val(employeeId);
                $parent.find('input[name="employee_id[]"]').val(employeeFullname);
                $item.parent().hide();
            });

            $('#ajukan').click(function(e) {
                e.preventDefault(); // Mencegah form di-submit secara otomatis

                // Ambil form
                let form = document.getElementById('form-pengajuan-absen');

                // Debug: Cek apakah form ditemukan dengan benar
                console.log(form);

                if (form) {
                    // Buat instance FormData
                    let formData = new FormData(form);

                    $.ajax({
                        url: "{{ route('attendances.form.submit') }}",
                        method: 'POST',
                        data: formData,
                        processData: false, // Jangan olah data secara otomatis
                        contentType: false, // Jangan tentukan content-type (untuk FormData)
                        success: function(response) {
                            showSuccessAlert('Form berhasil diajukan!');
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        },
                        error: function(xhr) {
                            // Menangani error jika terjadi error di server
                            if (xhr.status === 422) {
                                // Mendapatkan respons error dari server (misalnya dari ValidationException)
                                var response = JSON.parse(xhr.responseText);
                                var firstError = response.message || 'Terjadi kesalahan.';

                                // Tampilkan pesan error pertama
                                alert(
                                firstError); // Bisa diganti dengan menampilkan di elemen tertentu
                            } else {
                                console.error('Terjadi kesalahan:', xhr.responseText);
                            }
                        }
                    });
                } else {
                    console.error('Form tidak ditemukan!');
                }
            });

            // $('#ajukan').click(function(e) {
            //     e.preventDefault();
            //     let form = $('#form-pengajuan-absen')[0];
            //     let formData = new FormData(form);
            //     console.log(form);

            //     $.ajax({
            //         url: "{{ route('attendances.form.submit') }}",
            //         method: 'POST',
            //         data: formData,
            //         success: function(response) {
            //             alert('Form berhasil diajukan!');
            //             console.log(response);
            //         },
            //         error: function(xhr, status, error) {
            //             alert('Gagal mengajukan form. Silakan coba lagi.');
            //             console.error(xhr.responseText);
            //         }
            //     });
            // });
        });
        // $(document).ready(function() {

        //     $('.add-employee-btn').click(function() {
        //         $('.employee-form-group:last').clone().insertAfter('.employee-form-group:last');
        //     });

        //     $(document).on('change', '.employee_id', function() {
        //         var employeeId = $(this).val();

        //         if (employeeId.length >= 3) {
        //             console.log('Sending AJAX request for employee ID:', employeeId);

        //             $.ajax({
        //                 url: '/api/user/' + employeeId +
        //                 '/get', // Replace with your actual API endpoint
        //                 type: 'GET', // Assuming you're using a GET request
        //                 headers: {
        //                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
        //                         'content') // Add CSRF token if needed
        //                 },
        //                 success: function(response) {
        //                     console.log('AJAX request successful:', response);
        //                     // Handle the response data, e.g., update the form fields
        //                 },
        //                 error: function(error) {
        //                     console.error('AJAX request failed:', error);
        //                 }
        //             });
        //         }
        //     });

        //     // ... rest of your JavaScript code ...
        // });
    </script>
@endsection
