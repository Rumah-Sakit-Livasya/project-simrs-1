@extends('inc.layout')
@section('title', 'Time Schedule')
@section('content')
    <main id="js-page-content" role="main" class="page-content">

        <div class="panel-container show">
            <div class="panel-content">
                <div class="row mb-5">
                    <div class="col-xl-12">
                        <button type="button" id="btn-tambah" class="btn btn-primary waves-effect waves-themed"
                            data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#tambah-data"
                            title="Tambah Data">
                            <span class="fal fa-plus-circle mr-1"></span>
                            Tambah Agenda Rapat
                        </button>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12">
                        <div id="panel-1" class="panel">
                            <div class="panel-hdr">
                                <h2>
                                    Tabel Survei
                                </h2>
                            </div>
                            <div class="panel-container show">
                                <div class="panel-content">
                                    <!-- datatable start -->
                                    <table id="dt-basic-example"
                                        class="table table-bordered table-hover table-striped w-100">
                                        <thead>
                                            <tr class="text-center">
                                                {{-- <th style="white-space: nowrap">Foto</th> --}}
                                                <th style="white-space: nowrap">No</th>
                                                <th style="white-space: nowrap">Tanggal</th>
                                                <th style="white-space: nowrap">Judul Rapat</th>
                                                <th style="white-space: nowrap">Perihal</th>
                                                <th style="white-space: nowrap">Peserta Rapat</th>
                                                <th style="white-space: nowrap">UMAN</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($timeSchedules as $row)
                                                <tr>
                                                    {{-- <td style="white-space: nowrap">{{ $user->template_user->foto }}</td> --}}
                                                    <td style="white-space: nowrap">{{ $loop->iteration }}</td>
                                                    <td style="white-space: nowrap">{{ tgl_waktu($row->datetime) }}</td>
                                                    <td style="white-space: nowrap">{{ $row->title }}</td>
                                                    <td>{{ $row->perihal }}</td>
                                                    <td style="white-space: nowrap; text-align: center">
                                                        <button class="btn btn-sm btn-success px-2 py-1 btn-peserta"
                                                            data-id="{{ $row->id }}">
                                                            <i class="fas fa-users"></i>
                                                        </button>
                                                    </td>
                                                    <td style="white-space: nowrap">
                                                        @if ($row->undangan)
                                                            <!-- Cek apakah file undangan ada -->
                                                            <a href="{{ route('time.schedule.rapat.download', ['id' => Crypt::encrypt($row->id), 'type' => 'undangan']) }}"
                                                                class="badge mx-1 bg-primary p-2 border-0 text-white"
                                                                title="Undangan">
                                                                <span class="bx bxs-user-plus m-0 ikon-edit"></span>
                                                            </a>
                                                        @else
                                                            <a href="#"
                                                                class="badge mx-1 bg-danger p-2 border-0 text-white"
                                                                data-toggle="modal" title="Upload Undangan"
                                                                data-target="#uploadFileModal"
                                                                onclick="setFileType('undangan', {{ $row->id }}); $('#uploadFileModal').modal('show');">
                                                                <span class="bx bxs-user-plus m-0 ikon-edit"></span>
                                                            </a>
                                                        @endif

                                                        @if ($row->materi)
                                                            <!-- Cek apakah file materi ada -->
                                                            <a href="{{ route('time.schedule.rapat.download', ['id' => Crypt::encrypt($row->id), 'type' => 'materi']) }}"
                                                                class="badge mx-1 bg-primary p-2 border-0 text-white"
                                                                title="Materi">
                                                                <span class="bx bxs-book-bookmark m-0 ikon-edit"></span>
                                                            </a>
                                                        @else
                                                            <a href="#"
                                                                class="badge mx-1 bg-danger p-2 border-0 text-white"
                                                                data-toggle="modal" title="Upload Materi"
                                                                data-target="#uploadFileModal"
                                                                onclick="setFileType('materi', {{ $row->id }}); $('#uploadFileModal').modal('show');">
                                                                <span class="bx bxs-book-bookmark m-0 ikon-edit"></span>
                                                            </a>
                                                        @endif

                                                        @if ($row->absensi)
                                                            <!-- Cek apakah file absensi ada -->
                                                            <a href="{{ route('time.schedule.rapat.download', ['id' => Crypt::encrypt($row->id), 'type' => 'absensi']) }}"
                                                                class="badge mx-1 bg-primary p-2 border-0 text-white"
                                                                data-toggle="tooltip" title="Absensi">
                                                                <span class="bx bxs-user-detail m-0 ikon-edit"></span>
                                                            </a>
                                                        @else
                                                            <a href="#"
                                                                class="badge mx-1 bg-danger p-2 border-0 text-white"
                                                                data-toggle="tooltip" title="Upload Absensi"
                                                                data-target="#uploadFileModal"
                                                                onclick="setFileType('absensi', {{ $row->id }}); $('#uploadFileModal').modal('show');">
                                                                <span class="bx bxs-user-detail m-0 ikon-edit"></span>
                                                            </a>
                                                        @endif

                                                        @if ($row->notulen)
                                                            <!-- Cek apakah file notulen ada -->
                                                            <a href="{{ route('time.schedule.rapat.download', ['id' => Crypt::encrypt($row->id), 'type' => 'notulen']) }}"
                                                                class="badge mx-1 bg-primary p-2 border-0 text-white"
                                                                data-toggle="tooltip" title="Notulen">
                                                                <span class="bx bxs-file m-0 ikon-edit"></span>
                                                            </a>
                                                        @else
                                                            <a href="#"
                                                                class="badge mx-1 bg-danger p-2 border-0 text-white"
                                                                data-toggle="tooltip" title="Upload Notulen"
                                                                data-target="#uploadFileModal"
                                                                onclick="setFileType('notulen', {{ $row->id }}); $('#uploadFileModal').modal('show');">
                                                                <span class="bx bxs-file m-0 ikon-edit"></span>
                                                            </a>
                                                        @endif

                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr class="text-center">
                                                <th style="white-space: nowrap">No</th>
                                                <th style="white-space: nowrap">Tanggal</th>
                                                <th style="white-space: nowrap">Judul Rapat</th>
                                                <th style="white-space: nowrap">Perihal</th>
                                                <th style="white-space: nowrap">Peserta Rapat</th>
                                                <th style="white-space: nowrap">UMAN</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <!-- datatable end -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('pages.time-schedule.partials.create-data')
    @include('pages.time-schedule.partials.upload-file')
    @include('pages.time-schedule.partials.list-peserta')
@endsection
@section('plugin')
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        // Fungsi untuk mengisi nilai type pada input hidden
        function setFileType(fileType, id) {
            // Set nilai type pada input hidden sesuai dengan file yang dipilih
            $('#upload-type').val(fileType);
            $('#ts-id').val(id);
        }
        $(document).ready(function() {
            // $("form").on("submit", function(e) {
            //     // Disable tombol submit setelah form dikirim
            //     $(this).find("button[type='submit']").prop("disabled", true);
            // });
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('.custom-file-input').on('change', function() {
                var fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').html(fileName);
            });

            $(function() {
                $('#tambah-data #create-peserta').select2({
                    placeholder: 'Pilih data berikut',
                    allowClear: true,
                    dropdownParent: $("#tambah-data")
                });
                $('#tambah-data #create-employee_id').select2({
                    placeholder: 'Pilih data berikut',
                    allowClear: true,
                    dropdownParent: $("#tambah-data")
                });
            });

            let dataId = null;

            $('#update-form').on('submit', function(e) {
                e.preventDefault();
                let formData = $(this).serialize();
                $.ajax({
                    type: "POST",
                    url: '/api/dashboard/banks/update/' + dataId,
                    data: formData,
                    beforeSend: function() {
                        $('#update-form').find('.ikon-edit').hide();
                        $('#update-form').find('.spinner-text')
                            .removeClass(
                                'd-none');
                    },
                    success: function(response) {
                        $('#ubah-data').modal('hide');
                        showSuccessAlert(response.message)
                        setTimeout(function() {
                            location.reload();
                        }, 500);
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });

            $('#store-form').on('submit', function(e) {
                e.preventDefault();

                // Ambil elemen form
                let form = $(this)[0];
                let formData = new FormData(form); // Gunakan FormData untuk mendukung file upload

                $.ajax({
                    type: "POST",
                    url: '/api/dashboard/time-schedules/rapat/store',
                    data: formData,
                    processData: false, // Jangan proses data karena kita mengirim FormData
                    contentType: false, // Jangan atur konten tipe agar boundary otomatis
                    beforeSend: function() {
                        // Tampilkan indikator loading
                        $('#store-form').find('.ikon-tambah').hide();
                        $('#store-form').find('.spinner-text').removeClass('d-none');
                    },
                    success: function(response) {
                        // Reset tampilan tombol setelah sukses
                        $('#store-form').find('.ikon-tambah').show();
                        $('#store-form').find('.spinner-text').addClass('d-none');
                        $('#tambah-data').modal('hide');

                        // Tampilkan notifikasi sukses
                        showSuccessAlert(response.message);

                        // Reload halaman setelah jeda singkat
                        setTimeout(function() {
                            location.reload();
                        }, 500);
                    },
                    error: function(xhr) {
                        // Tangani error dan tampilkan pesan error
                        $('#store-form').find('.btn-primary').attr('disabled', false);
                        console.error(xhr.responseText);
                    }
                });
            });

            $('.btn-peserta').click(function() {
                $('#modal-peserta').modal('show');
                let rapatId = $(this).attr('data-id');
                $.ajax({
                    url: '/api/dashboard/time-schedules/rapat/get-peserta/' + rapatId,
                    type: 'GET',
                    success: function(response) {
                        // Menghapus data sebelumnya untuk menghindari duplikasi
                        $('#list-peserta').empty();
                        $('#list-peserta').append(
                            '<li class="list-group-item list-group-item-info">' +
                            response.yang_mengundang + '<span class="float-right">' +
                            response.organisasi_yang_mengundang + '</span></li>');

                        // Menampilkan data peserta rapat
                        response.peserta_rapat.forEach(function(peserta) {
                            $('#list-peserta').append(
                                '<li class="list-group-item d-flex align-items-center">' +
                                '<input type="checkbox" class="peserta-checkbox mr-2" value="' +
                                peserta.id + '">' +
                                peserta.fullname + '<span class="float-right">' +
                                peserta.organization_name + '</span></li>');
                        });

                        // Tambahkan tombol verifikasi dengan styling Bootstrap
                        $('#list-peserta').append(
                            '<div class="text-center mt-3">' +
                            '<button id="btn-verifikasi" class="btn btn-primary">Verifikasi Kehadiran</button>' +
                            '</div>'
                        );

                        // Event listener untuk tombol verifikasi
                        $('#btn-verifikasi').click(function() {
                            let hadirIds = $('.peserta-checkbox:checked').map(
                                function() {
                                    return $(this).val();
                                }).get();

                            if (hadirIds.length > 0) {
                                $.ajax({
                                    url: '/api/dashboard/time-schedules/rapat/verifikasi',
                                    type: 'POST',
                                    data: {
                                        rapat_id: rapatId,
                                        hadir_ids: hadirIds
                                    },
                                    success: function(response) {
                                        showSuccessAlert(response.message);
                                        $('#modal-peserta').modal('hide');
                                    },
                                    error: function(xhr, status, error) {
                                        showErrorAlert(
                                            'Terjadi kesalahan: ' +
                                            error);
                                    }
                                });
                            } else {
                                showErrorAlert('Silakan pilih peserta yang hadir.');
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        showErrorAlert('Terjadi kesalahan: ' + error);
                    }
                });
            });

            $('.btn-hapus').click(function() {
                let id = $(this).data('id')
                let url = "{{ route('delete.survei.kebersihan-kamar', ':id') }}".replace(':id', id);

                let confirmationMessage = 'Yakin ingin menghapus survei ini?';

                if (confirm(confirmationMessage)) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            showSuccessAlert(response.message);

                            setTimeout(() => {
                                console.log('Reloading the page now.');
                                window.location.reload();
                            }, 1000);
                        },
                        error: function(xhr, status, error) {
                            showErrorAlert('Terjadi kesalahan: ' + error);
                        }
                    });
                } else {
                    console.log('Penghapusan dibatalkan oleh pengguna.');
                }
            });

            $('#dt-basic-example').dataTable({
                responsive: true
            });

            $('.js-thead-colors a').on('click', function() {
                var theadColor = $(this).attr("data-bg");
                console.log(theadColor);
                $('#dt-basic-example thead').removeClassPrefix('bg-').addClass(theadColor);
            });

            $('.js-tbody-colors a').on('click', function() {
                var theadColor = $(this).attr("data-bg");
                console.log(theadColor);
                $('#dt-basic-example').removeClassPrefix('bg-').addClass(theadColor);
            });

        });

        function previewImage() {
            const image = document.querySelector('#foto');
            const imgPreview = document.querySelector('.image-preview')

            imgPreview.style.display = 'block';

            const oFReader = new FileReader();
            oFReader.readAsDataURL(image.files[0])

            oFReader.onload = function(oFREvent) {
                imgPreview.src = oFREvent.target.result;
            }
        }
    </script>
@endsection
