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
                            Tambah Aspek Penilaian
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <div class="row">
                                <div class="col-lg-12 mt-2">
                                    <h5 class="font-weight-bold text-primary mb-0" style="font-size: 0.875rem">Aspek
                                        Penilaian
                                    </h5>
                                    <small>*Total bobot semuanya harus 100%</small>
                                    <hr style="border-color: #fd3995">
                                </div>
                                <div class="col-lg-12">
                                    <form id="aspek-penilaian-form" method="POST">
                                        @csrf
                                        @method('POST')
                                        <table id="aspek-penilaian">
                                            <tr id="heading">
                                                <th>Aspek Penilaian</th>
                                                <th>Bobot</th>
                                                <th></th>
                                            </tr>
                                            <tr id="btn-tambah-wrapper">
                                                <td colspan="2">
                                                    <a href="#" class="btn-tambah-aspek mt-2">[+] Tambah Aspek
                                                        Penilaian</a>
                                                    <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                                                </td>
                                            </tr>
                                        </table>
                                    </form>
                                </div>
                            </div>
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
            let number = 0;
            let input = null;
            $('.btn-tambah-aspek').click(function() {
                number++;

                input = `
                <td>
                    <input type="text" name="aspek_penilaian[]" class="form-control @error('aspek_penilaian[]') is-invalid @enderror" placeholder="Aspek Penilaian ke-${number}">
                </td>
                <td>
                    <input type="text" name="bobot[]" class="form-control @error('bobot[]') is-invalid @enderror" placeholder="Bobot ke-${number}">
                </td>
                <td>
                    <button class="btn btn-danger px-2 py-1"><i class="fal fa-trash"></i></button>
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

            $('#aspek-penilaian-form').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                formData.append("employee_id", "{{ auth()->user()->employee->id }}");
                formData.append("approved_line_child",
                    "{{ auth()->user()->employee->approval_line }}");
                formData.append("approved_line_parent",
                    "{{ auth()->user()->employee->approval_line_parent }}");

                $.ajax({
                    type: "POST",
                    url: '/employee/request/attendance',
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $('#store-form').find('.ikon-tambah').hide();
                        $('#store-form').find('.spinner-text').removeClass(
                            'd-none');
                    },
                    success: function(response) {
                        $('#store-form').find('.ikon-edit').show();
                        $('#store-form').find('.spinner-text').addClass('d-none');
                        $('#create-attendance-form').modal('hide');
                        showSuccessAlert(response.message)
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    },
                    error: function(xhr) {
                        $('#create-attendance-form').modal('hide');
                        showErrorAlert(xhr.responseJSON.error);
                    }
                });
            });

        });
    </script>
@endsection
