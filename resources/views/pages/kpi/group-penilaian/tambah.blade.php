@extends('inc.layout')
@section('title', 'Live Attendace')
@section('extended-css')
    <style>
        /* Styling for table with id "aspek-penilaian" */
        #aspek-penilaian {
            border-collapse: collapse;
            width: 100%;
        }

        /* Styling for table heading row */
        #aspek-penilaian #heading th {
            background-color: #4679cc;
            color: white;
            padding: 10px;
            text-align: left;
            margin-bottom: 5px !important;
        }

        /* Styling for "Tambah Aspek Penilaian" button row */
        #aspek-penilaian td:first-child {
            padding: 5px 5px 5px 0px;
        }

        #aspek-penilaian td {
            padding: 5px 5px 5px 0px;
            /* Padding */
        }

        #aspek-penilaian #btn-tambah-wrapper td {
            padding-top: 10px;
            /* Padding */
        }

        /* Styling for "Tambah Aspek Penilaian" button */
        .btn-tambah-aspek,
        .btn-tambah-indikator {
            background-color: #4679cc;
            /* Warna latar belakang biru */
            color: white;
            /* Warna teks putih */
            padding: 8px 12px;
            /* Padding */
            text-decoration: none;
            /* Hapus dekorasi tautan */
            border-radius: 4px;
            /* Border radius */
        }

        /* Hover effect for "Tambah Aspek Penilaian" button */
        .btn-tambah-aspek:hover,
        .btn-tambah-indikator:hover {
            background-color: #335999;
            /* Warna latar belakang biru yang lebih gelap saat dihover */
        }
    </style>
@endsection
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Tambah Group Form Penilaian
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form action="" method="POST" id="group_penilaian_form">
                                @csrf
                                @method('POST')
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h5 class="font-weight-bold text-primary" style="font-size: 0.875rem">Group
                                            Penilaian
                                        </h5>
                                        <hr style="border-color: #fd3995">
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group mb-3">
                                            <label for="nama_group">Nama Group</label>
                                            <div class="input-group">
                                                <input type="text" name="nama_group"
                                                    class="form-control @error('nama_group') is-invalid @enderror"
                                                    placeholder="Pelayanan/Administrasi Umum" id="nama_group">
                                            </div>
                                            @error('nama_group')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group mb-3">
                                            <label for="status_penilaian">Status Penilaian</label>
                                            <div class="input-group">
                                                <select
                                                    class="select2 form-control mb-3 w-100  @error('periode') is-invalid @enderror"
                                                    id="status_penilaian" name="status_penilaian">
                                                    <option value="karyawan">Karyawan</option>
                                                    <option value="orientasi">Orientasi</option>
                                                </select>
                                            </div>
                                            @error('status_penilaian')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group mb-3">
                                            <label for="rumus_penilaian">Rumus Penilaian</label>
                                            <div class="input-group">
                                                <select
                                                    class="select2 form-control mb-3 w-100  @error('periode') is-invalid @enderror"
                                                    id="rumus_penilaian" name="rumus_penilaian">
                                                    <option value="rata-rata">Rata-rata</option>
                                                    <option value="kostum" disabled>Kostum (Belum Tersedia)</option>
                                                </select>
                                            </div>
                                            @error('rumus_penilaian')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mt-2">
                                        <h5 class="font-weight-bold text-primary" style="font-size: 0.875rem">Aspek
                                            Penilaian
                                        </h5>
                                        <hr style="border-color: #fd3995">
                                    </div>
                                    <div class="col-lg-12">
                                        <table id="aspek-penilaian">
                                            <tr id="heading">
                                                <th>Aspek Penilaian</th>
                                                <th>Indikator Penilaian</th>
                                            </tr>
                                            <tr id="btn-tambah-wrapper">
                                                <td>
                                                    <a href="#" class="btn-tambah-aspek">[+] Tambah Aspek
                                                        Penilaian</a>
                                                </td>
                                                <td>
                                                    <a href="#" class="btn-tambah-indikator">[+] Tambah Indikator
                                                        Penilaian</a>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-info btn-block">
                                            <div class="ikon-tambah">
                                                <span class="fal fa-upload mr-1"></span>
                                                Tambah
                                            </div>
                                            <div class="span spinner-text d-none">
                                                <span class="spinner-border spinner-border-sm" role="status"
                                                    aria-hidden="true"></span>
                                                Loading...
                                            </div>
                                        </button>
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
    <script src="/js/dependency/moment/moment.js"></script>
    <script src="/js/formplugins/bootstrap-daterangepicker/bootstrap-daterangepicker.js"></script>
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(document).ready(function() {
            // Menangkap klik pada tombol "Tambah Aspek Penilaian"
            $(function() {
                $('.select2').select2({
                    placeholder: 'Pilih Data Berikut',
                });
            });

            $('#group_penilaian_form').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);

                $.ajax({
                    type: "POST",
                    url: '/api/dashboard/kpi/store',
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $('#group_penilaian_form').find('.ikon-tambah').hide();
                        $('#group_penilaian_form').find('.spinner-text').removeClass(
                            'd-none');
                    },
                    success: function(response) {
                        $('#group_penilaian_form').find('.ikon-edit').show();
                        $('#group_penilaian_form').find('.spinner-text').addClass('d-none');
                        showSuccessAlert(response.message)
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    },
                    error: function(xhr) {
                        // $('#create-attendance-form').modal('hide');
                        var errors = JSON.parse(xhr.responseText);
                        var errorMessage = '';

                        $.each(errors, function(key, value) {
                            errorMessage += value +
                                '. '; // Menambahkan setiap pesan kesalahan
                        });
                        showErrorAlert(
                            errorMessage);
                    }
                });
            });

            let number = 0;
            let numberIndikator = 0;
            let checkNumber = 0;
            let input = null;
            $('.btn-tambah-aspek').click(function(e) {
                e.preventDefault();

                number++;
                input = `
                <td style="width:50% !important;">
                    <input type="text" name="aspek_penilaian[]" class="form-control @error('aspek_penilaian[]') is-invalid @enderror" placeholder="Aspek Penilaian ke-${number}" id="nama_group" style="width: 70%; float:left">
                    <input type="text" name="bobot[]" class="form-control" placeholder="Bobot ke-${number}" style="width: 30%">
                </td>
                `;
                var newRow = $('<tr></tr>');
                newRow.attr('id', 'aspek_penilaian_' + number);
                newRow.append(input);
                newRow.append(
                    '<td></td>'
                );
                // Memasukkan baris baru setelah baris yang berisi tombol "Tambah Aspek Penilaian"
                $('#btn-tambah-wrapper').before(newRow);
            });

            $('.btn-tambah-indikator').click(function(e) {
                e.preventDefault();
                if (number != checkNumber) {
                    numberIndikator = 0;
                }
                checkNumber = number;
                numberIndikator++;

                // Mengambil baris sebelumnya dari tombol "Tambah Indikator Penilaian"
                var lastRow = $(this).closest('tr').prev('tr');

                // Mengambil td kedua dari baris terakhir
                var lastRowSecondTd = lastRow.find('td:eq(1)');

                // Mengambil input dalam td kedua
                var input = lastRowSecondTd.find('input');

                let inputText = `
                <td style="width:50% !important;">
                    <input type="text" name="indikator_${number}[]" class="form-control @error('indikator_${number}[]') is-invalid @enderror" placeholder="Indikator Penilaian ke-${numberIndikator}" style="width: 100%; float:left">

                </td>
                `;

                let inputTextFirst = `
                    <input type="text" name="indikator_${number}[]" class="form-control @error('indikator_${number}[]') is-invalid @enderror" placeholder="Indikator Penilaian ke-${numberIndikator}" style="width: 100%; float:left">


                `;

                // Jika td kedua memiliki input
                if (input.length) {
                    // Menambahkan baris baru dengan td kosong
                    var newRow = $('<tr></tr>');
                    newRow.append('<td></td>');
                    newRow.append(inputText);
                    lastRow.after(newRow);
                } else {
                    // Menambahkan input ke td yang sudah ada
                    lastRowSecondTd.append(inputTextFirst);
                }

            });

        });
    </script>
@endsection
