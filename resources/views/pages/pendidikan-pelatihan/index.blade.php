@extends('inc.layout')
@section('title', 'Pelatihan & Pendidikan')
@section('extended-css')
    <style>
        .select2-search__field {
            width: 300px !important;
        }
    </style>
@endsection
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
                            Tambah Pendidikan Pelatihan
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
                                                <th style="white-space: nowrap">Peserta</th>
                                                <th style="white-space: nowrap">Judul Pelatihan</th>
                                                <th style="white-space: nowrap">Tipe</th>
                                                <th style="white-space: nowrap">Pembicara</th>
                                                <th style="white-space: nowrap">Tempat</th>
                                                <th style="white-space: nowrap">Tanggal</th>
                                                <th style="white-space: nowrap">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pendidikanPelatihans as $row)
                                                <tr>
                                                    {{-- <td style="white-space: nowrap">{{ $user->template_user->foto }}</td> --}}
                                                    <td style="white-space: nowrap">{{ $loop->iteration }}</td>
                                                    <td style="white-space: nowrap; text-align: center">
                                                        <button class="btn btn-sm btn-success px-2 py-1 btn-peserta"
                                                            data-id="{{ $row->id }}">
                                                            <i class="fas fa-users"></i>
                                                        </button>
                                                    </td>

                                                    <td style="white-space: nowrap">{{ $row->judul }}</td>
                                                    <td style="white-space: nowrap">{{ ucfirst($row->type) }}</td>
                                                    <td style="white-space: nowrap">{{ $row->pembicara }}</td>
                                                    <td style="white-space: nowrap">{{ $row->tempat }}</td>
                                                    <td style="white-space: nowrap">{{ tgl_waktu($row->datetime) }}</td>
                                                    <td style="white-space: nowrap; text-align: center;">

                                                        <a href="#"
                                                            class="badge mx-1 bg-danger p-2 border-0 text-white btn-konfirmasi-peserta"
                                                            data-toggle="modal" title="Konfirmasi Absensi"
                                                            data-id="{{ $row->id }}"
                                                            data-target="#modal-konfirmasi-peserta">
                                                            <span class="bx bxs-user-detail m-0 ikon-edit"></span>
                                                        </a>

                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr class="text-center">
                                                <th style="white-space: nowrap">No</th>
                                                <th style="white-space: nowrap">Peserta</th>
                                                <th style="white-space: nowrap">Judul Pelatihan</th>
                                                <th style="white-space: nowrap">Tipe</th>
                                                <th style="white-space: nowrap">Pembicara</th>
                                                <th style="white-space: nowrap">Tempat</th>
                                                <th style="white-space: nowrap">Tanggal</th>
                                                <th style="white-space: nowrap">Aksi</th>
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

    @include('pages.pendidikan-pelatihan.partials.create-data')
    @include('pages.pendidikan-pelatihan.partials.list-peserta')
    @include('pages.pendidikan-pelatihan.partials.konfirmasi-peserta')
@endsection
@section('plugin')
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        function toggleRoomName() {
            const isOnlineCheckbox = document.getElementById('is_online');
            const roomNameInput = document.getElementById('room_name');
            roomNameInput.readOnly = !isOnlineCheckbox.checked;
        }
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
                    url: '/api/dashboard/pendidikan-pelatihan/store',
                    data: formData,
                    processData: false, // Jangan proses data karena kita mengirim FormData
                    contentType: false, // Jangan atur konten tipe agar boundary otomatis
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
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
                    url: '/api/dashboard/pendidikan-pelatihan/get-peserta/' + rapatId,
                    type: 'GET',
                    success: function(response) {
                        // Menghapus data sebelumnya untuk menghindari duplikasi
                        $('#list-peserta').empty();
                        $('#list-peserta').append(
                            '<li class="list-group-item list-group-item-info">' + response
                            .pembicara);

                        // Menampilkan data peserta rapat
                        response.peserta_rapat.forEach(function(peserta) {
                            $('#list-peserta').append('<li class="list-group-item">' +
                                peserta.fullname + '<span class="float-right">' +
                                peserta.organization_name + '</span></li>');
                        });

                        // Menampilkan data yang mengundang
                    },
                    error: function(xhr, status, error) {
                        showErrorAlert('Terjadi kesalahan: ' + error);
                    }
                });
            });

            $('.btn-konfirmasi-peserta').click(function() {
                $('#modal-konfirmasi-peserta').modal('show');
                let diklatId = $(this).attr('data-id');
                $.ajax({
                    url: '/api/dashboard/pendidikan-pelatihan/get-konfirmasi-peserta/' + diklatId,
                    type: 'GET',
                    success: function(response) {
                        // Menghapus data sebelumnya untuk menghindari duplikasi
                        $('#list-konfirmasi-peserta').empty();
                        $('#list-konfirmasi-peserta').append(
                            '<li class="list-group-item list-group-item-info text-center font-weight-bold">Konfirmasi Absensi</li>'
                        );

                        // Menampilkan data peserta diklat
                        response.peserta.forEach(function(peserta) {
                            $('#list-konfirmasi-peserta').append(
                                '<li class="list-group-item d-flex justify-content-between align-items-center">' +
                                '<div class="custom-control custom-checkbox">' +
                                '<input type="checkbox" class="custom-control-input verifikasi" id="checkbox-' +
                                peserta.employee_id + '" data-id="' +
                                peserta.employee_id + '" />' +
                                '<label class="custom-control-label" for="checkbox-' +
                                peserta.employee_id + '">' + peserta.fullname +
                                '</label>' +
                                '</div>' +
                                '<span class="badge badge-info">' + peserta
                                .organization_name + '</span>' +
                                '</li>'
                            );
                        });

                        // Tambahkan tombol untuk verifikasi
                        $('#list-konfirmasi-peserta').append(
                            '<li class="list-group-item text-center">' +
                            '<button class="btn btn-success btn-verifikasi mx-1">Verifikasi</button>' +
                            '</li>'
                        );
                    },
                    error: function(xhr, status, error) {
                        showErrorAlert('Terjadi kesalahan: ' + error);
                    }
                });
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
