@extends('inc.layout')
@section('title', 'Riwayat Kerusakan')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row mb-5">
            <div class="col-xl-12">
                <a href="{{ url()->previous() }}" class="btn btn-primary waves-effect waves-themed">
                    <span class="fal fa-arrow-left mr-1"></span>
                    Kembali
                </a>
                <button type="button" data-backdrop="static" data-keyboard="false"
                    class="btn btn-primary waves-effect waves-themed" data-toggle="modal" data-target="#tambah-data">
                    <span class="fal fa-plus-circle mr-1"></span>
                    Tambah
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Riwayat Kerusakan <span
                                class="fw-300"><i>{{ $barang->custom_name ?? $barang->template_barang->name }}</i></span>
                        </h2>
                        @include('pages.partials.panel-toolbar')
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <!-- datatable start -->
                            <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                                <thead>
                                    <tr>
                                        <th style="white-space: nowrap">No</th>
                                        <th style="white-space: nowrap">Tanggal</th>
                                        <th style="white-space: nowrap">Petugas</th>
                                        <th style="white-space: nowrap">Kondisi</th>
                                        <th style="white-space: nowrap">Hasil Pengecekan</th>
                                        <th style="white-space: nowrap">Rencana Tindak Lanjut</th>
                                        <th style="white-space: nowrap">Dokumentasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($maintenances as $row)
                                        <tr>
                                            <td style="white-space: nowrap">{{ $loop->iteration }}</td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($row->tanggal)->format('j F Y') }}
                                            </td>
                                            <td style="white-space: nowrap">{{ $row->user->name }}</td>
                                            <td>{{ $row->kondisi }}</td>
                                            <td>{{ $row->hasil }}</td>
                                            <td>{{ $row->rtl }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-success px-2 py-1 btn-dokumentasi"
                                                    data-id="{{ $row->id }}">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th style="white-space: nowrap">No</th>
                                        <th style="white-space: nowrap">Tanggal</th>
                                        <th style="white-space: nowrap">Petugas</th>
                                        <th style="white-space: nowrap">Kondisi</th>
                                        <th style="white-space: nowrap">Hasil Pengecekan</th>
                                        <th style="white-space: nowrap">Rencana Tindak Lanjut</th>
                                        <th style="white-space: nowrap">Dokumentasi</th>
                                    </tr>
                                </tfoot>
                            </table>
                            <!-- datatable end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div class="form-group row">
        <label class="col-form-label col-12 col-lg-3 form-label text-lg-right">Minimum Setup</label>
        <div class="col-12 col-lg-6 ">
            <input type="text" id="datepicker-1">
        </div>
    </div>

    @include('pages.inventaris.maintenance.partials.tambah-data')
    @include('pages.inventaris.maintenance.partials.view-foto')
@endsection
@section('plugin')
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datatable/jszip.min.js"></script>
    <script>
        // Class definition

        var controls = {
            leftArrow: '<i class="fal fa-angle-left" style="font-size: 1.25rem"></i>',
            rightArrow: '<i class="fal fa-angle-right" style="font-size: 1.25rem"></i>'
        }

        var runDatePicker = function() {
            // input group layout for modal demo
            $('#datepicker-modal-2').datepicker({
                todayHighlight: true,
                orientation: "bottom left",
                templates: controls,
                format: "dd-mm-yyyy"
            });
            $('#datepicker-modal-3').datepicker({
                todayHighlight: true,
                orientation: "bottom left",
                templates: controls,
                format: "dd-mm-yyyy"
            });
        }

        $(document).ready(function() {
            $("form").on("submit", function(e) {
                // Disable tombol submit setelah form dikirim
                $(this).find("button[type='submit']").prop("disabled", true);
            });

            $('#store-form').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);

                $.ajax({
                    type: "POST",
                    url: '/api/inventaris/maintenance',
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $('#store-form').find('.ikon-tambah').hide();
                        $('#store-form').find('.spinner-text').removeClass('d-none');
                    },
                    success: function(response) {
                        $('#store-form').find('.ikon-edit').show();
                        $('#store-form').find('.spinner-text').addClass('d-none');
                        $('#tambah-data').modal('hide');
                        showSuccessAlert(response.message)
                        setTimeout(function() {
                            location.reload();
                        }, 500);
                    },
                    error: function(xhr, status, error) {
                        $('#tambah-data').modal('hide');
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        showErrorAlert(errorMessage);
                    }
                });
            });

            $('.btn-dokumentasi').click(function() {
                $('#view-foto').modal('show');
                var maintenanceId = $(this).attr('data-id');

                $.ajax({
                    url: '/api/inventaris/maintenance/' + maintenanceId + '/dokumentasi',
                    type: 'GET',
                    success: function(response) {
                        // Assuming the response contains the URL of the image
                        var foto = response.foto;
                        $('#modal-image').attr('src', '/storage/' + foto);
                    },
                    error: function(xhr, status, error) {
                        showErrorAlert('Terjadi kesalahan: ' + error);
                    }
                });
            });


            runDatePicker();
            $('#dt-basic-example').dataTable({
                responsive: true,
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'print',
                        text: 'Print',
                        className: 'float-right btn btn-primary',
                        exportOptions: {
                            columns: ':not(.no-export)'
                        }
                    },
                    {
                        extend: 'excel',
                        text: 'Download as Excel',
                        className: 'float-right btn btn-success',
                        exportOptions: {
                            columns: ':not(.no-export)'
                        }
                    },
                    {
                        extend: 'colvis',
                        text: 'Column Visibility',
                        titleAttr: 'Col visibility',
                        className: 'float-right mb-3 btn btn-warning',
                        exportOptions: {
                            columns: ':not(.no-export)'
                        },
                        postfixButtons: [{
                                extend: 'print',
                                text: 'Print',
                                exportOptions: {
                                    columns: ':visible:not(.no-export)'
                                }
                            },
                            {
                                extend: 'excel',
                                text: 'Download as Excel',
                                exportOptions: {
                                    columns: ':visible:not(.no-export)'
                                }
                            }
                        ]
                    }
                ]
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

        // Fungsi untuk mengontrol visibilitas input Estimasi dan Keterangan
        function toggleKeterangan(showKeterangan) {
            var estimasiGroup = document.getElementById('estimasi-group');
            var keteranganGroup = document.getElementById('keterangan-group');

            // Jika input radio memilih Menunggu Sparepart atau Dalam Proses, maka input Estimasi harus ditampilkan
            if (document.getElementById('menunggu-sparepart').checked || document.getElementById('dalam-proses').checked) {
                estimasiGroup.style.display = 'block';
                keteranganGroup.style.display = showKeterangan ? 'block' : 'none';
            }
            // Jika input radio memilih Diperlukan Persetujuan atau Tidak Dapat Diperbaiki, maka input Keterangan harus ditampilkan
            else if (document.getElementById('diperlukan-persetujuan').checked || document.getElementById(
                    'tidak-dapat-diperbaiki').checked) {
                estimasiGroup.style.display = 'none';
                keteranganGroup.style.display = 'block';
            }
            // Jika input radio memilih Ditunda, maka input Estimasi dan Keterangan harus ditampilkan
            else if (document.getElementById('ditunda').checked) {
                estimasiGroup.style.display = 'block';
                keteranganGroup.style.display = 'block';
            }
            // Jika input radio memilih Selesai, maka input Estimasi dan Keterangan harus disembunyikan
            else {
                estimasiGroup.style.display = 'none';
                keteranganGroup.style.display = 'none';
            }
        }

        // Panggil fungsi toggleKeterangan pada saat radio button diubah
        document.querySelectorAll('input[type="radio"][name="status"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                toggleKeterangan(true);
            });
        });

        // Panggil fungsi toggleKeterangan pada saat halaman dimuat
        toggleKeterangan(false);
    </script>


@endsection
