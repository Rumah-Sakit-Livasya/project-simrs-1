@extends('inc.layout')
@section('title', 'Pegawai')
@section('extended-css')
    <style>
        .upload-container {
            width: 100%;
        }

        .upload-wrapper {
            border: 2px dashed #ccc;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            background-color: #fff;
        }

        .upload-icon {
            font-size: 48px;
            margin-bottom: 10px;
        }

        .upload-text p {
            margin: 0;
        }

        .button {
            margin-top: 10px;
            display: inline-block;
            padding: 6px 15px;
            background-color: #fd1381;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: #0056b3;
        }

        #fileList {
            margin-top: 10px;
            margin-bottom: 10px;
            text-align: center;
        }

        #fileListDeduction {
            margin-top: 10px;
            margin-bottom: 10px;
            text-align: center;
        }
    </style>
@endsection
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="panel-container show">
            <div class="panel-content">
                <div class="row mb-5">
                    <div class="col-xl-12">
                        <div id="panel-1" class="panel">
                            <div class="panel-container show">

                                <div class="panel-content tab-content">

                                    <ul class="nav nav-pills mb-5 mt-2" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#js_pill_border_icon-5"
                                                role="tab">
                                                <i class="fal fa-plus-circle mr-1"></i>Import / Export Potongan
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#js_pill_border_icon-7"
                                                role="tab">
                                                <i class="fal fa-plus-circle mr-1"></i>Daftar Potongan
                                            </a>
                                        </li>
                                    </ul>

                                    <div class="tab-content px-0">
                                        <div class="tab-pane fade show active" id="js_pill_border_icon-5" role="tabpanel">
                                            @include('pages.pegawai.daftar-pegawai.partials.payroll.import-deduction')
                                        </div>
                                        <div class="tab-pane fade show" id="js_pill_border_icon-7" role="tabpanel">
                                            @include('pages.pegawai.daftar-pegawai.partials.payroll.deduction')
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    @include('pages.pegawai.daftar-pegawai.partials.payroll.modal-payroll-template')
    @include('pages.pegawai.daftar-pegawai.partials.payroll.edit-deduction')
@endsection
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script>
        document.getElementById('downloadTemplateDeductionBtn').addEventListener('click', function() {
            $('#downloadTemplateDeductionModal').modal('show');
        });

        let employeeId = null;

        function btnDeduction(event) {
            event.preventDefault();
            let button = event.currentTarget;
            let id = button.getAttribute('data-id');
            employeeId = id;
            let ikonEdit = button.querySelector('.ikon-edit');
            let spinnerText = button.querySelector('.spinner-text');
            ikonEdit.classList.add('d-none');
            spinnerText.classList.remove('d-none');
            $.ajax({
                type: "GET", // Method pengiriman data bisa dengan GET atau POST
                url: `/api/dashboard/payroll/deduction/get/${employeeId}`, // Isi dengan url/path file php yang dituju
                dataType: "json",
                success: function(data) {
                    ikonEdit.classList.remove('d-none');
                    ikonEdit.classList.add('d-block');
                    spinnerText.classList.add('d-none');
                    $('#ubah-deduction').modal('show');
                    $('#ubah-deduction #potongan_keterlambatan').val(data.deduction.potongan_keterlambatan);
                    $('#ubah-deduction #potongan_izin').val(data.deduction.potongan_izin);
                    $('#ubah-deduction #simpanan_pokok').val(data.deduction.simpanan_pokok);
                    $('#ubah-deduction #potongan_koperasi').val(data.deduction.potongan_koperasi);
                    $('#ubah-deduction #potongan_absensi').val(data.deduction.potongan_absensi);
                    $('#ubah-deduction #potongan_bpjs_kesehatan').val(data.deduction.potongan_bpjs_kesehatan);
                    $('#ubah-deduction #potongan_bpjs_ketenagakerjaan').val(data.deduction
                        .potongan_bpjs_ketenagakerjaan);
                    $('#ubah-deduction #potongan_pajak').val(data.deduction.potongan_pajak);
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });

        }

        $(document).ready(function() {
            $('#update-deduction-form').on("submit", function(e) {
                e.preventDefault();
                let formData = $(this).serialize();
                console.log(formData);
                $.ajax({
                    type: "PUT",
                    url: '/api/dashboard/payroll/deduction/update/' + employeeId,
                    data: formData,
                    beforeSend: function() {
                        $('#update-form').find('.ikon-edit').hide();
                        $('#update-form').find('.spinner-text').removeClass(
                            'd-none');
                    },
                    success: function(response) {
                        $('#ubah-deduction').modal('hide');
                        showSuccessAlert(response.message)
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    },
                    error: function(xhr) {
                        $('#ubah-deduction').modal('hide');
                        showErrorAlert(xhr.responseJSON.error);
                    }
                });
            })
            $('.datatable').dataTable({
                // responsive: true,
                paginate: false,
                // scrollX: true, // Menambahkan scroll horizontal
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'B>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                        extend: 'colvis',
                        text: 'Column Visibility',
                        titleAttr: 'Col visibility',
                        className: 'btn-outline-default'
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        titleAttr: 'Print Table',
                        className: 'btn-outline-default',
                        exportOptions: {
                            columns: ':visible' // Menggunakan kolom yang terlihat sesuai pengaturan ColVis
                        },
                    }
                ]
            });


            // Input change
            $("#fileElem").on("change", function() {
                var files = $(this)[0].files;
                handleFiles(files);
            });

            function handleFiles(files) {
                console.log(files);
                var allowedExtensions = /(\.xls|\.xlsx)$/i;
                var fileList = $("#fileList");
                fileList.empty();

                for (var i = 0; i < files.length; i++) {
                    var file = files[i];

                    if (!allowedExtensions.test(file.name)) {
                        alert("Only Excel files (.xls, .xlsx) are allowed.");
                        continue;
                    }

                    // Display file name
                    fileList.append("<div>" + file.name + "</div>");

                    // You can handle the file here
                    console.log("File uploaded:", file.name);
                }
            }

            $('#import-pegawai').submit(function(event) {
                event.preventDefault();

                var formData = new FormData($(this)[0]);

                $.ajax({
                    url: '/api/dashboard/employee/import', // Ganti dengan endpoint API Anda
                    type: 'POST',
                    data: formData,
                    async: true, // Set async menjadi true untuk melakukan operasi secara asynchronous
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#import-pegawai').find('.ikon-tambah').hide();
                        $('#import-pegawai').find('.spinner-text').removeClass('d-none');
                    },
                    success: function(response) {
                        $('#import-pegawai').find('.ikon-edit').show();
                        $('#import-pegawai').find('.spinner-text').addClass('d-none');
                        $('#tambah-data').modal('hide');
                        showSuccessAlert(response.message)
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    },
                    error: function(xhr) {
                        showErrorAlert(xhr.responseJSON.error);
                    }
                });
            });

            $('#import-deduction').submit(function(event) {
                event.preventDefault();
                var formData = new FormData($(this)[0]);
                $.ajax({
                    url: '/api/dashboard/employee/deduction/import', // Ganti dengan endpoint API Anda
                    type: 'POST',
                    data: formData,
                    async: true, // Set async menjadi true untuk melakukan operasi secara asynchronous
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#import-deduction').find('.ikon-tambah').hide();
                        $('#import-deduction').find('.spinner-text').removeClass('d-none');
                    },
                    success: function(response) {
                        $('#import-deduction').find('.ikon-edit').show();
                        $('#import-deduction').find('.spinner-text').addClass('d-none');
                        $('#tambah-data').modal('hide');
                        showSuccessAlert(response.message)
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    },
                    error: function(xhr) {
                        showErrorAlert(xhr.responseJSON.error);
                    }
                });
            });

            $('#sama-alamat').change(function() {
                if ($(this).is(':checked')) {
                    $('#residental_address').val($('#citizen_id_address').val());
                    // Lakukan sesuatu jika checkbox tercentang di sini
                } else {
                    $('#residental_address').val("");
                    // Lakukan sesuatu jika checkbox tidak tercentang di sini
                }
            });
            $('.btn-next .btn-prev').click(function(e) {
                e.preventDefault();
                let parent = $(this).parent().parent();
                if (parent.attr('id') == 'step-2') {
                    $('#step-round-1-done').addClass('d-none');
                    $('#step-round-1').removeClass('d-none');
                    $('#step-round-2').removeClass('btn-primary');
                    $('#step-round-2').addClass('btn-outline-primary');
                    $("html, body").animate({
                        scrollTop: 0
                    }, 500);
                    $('#step-2').fadeOut(300, function() {
                        $('#step-1').fadeIn(300, function() {
                            $('#step-1').removeClass('hidden-content')
                        })
                    });
                } else if (parent.attr('id') == 'step-3') {
                    $('#step-round-2-done').addClass('d-none');
                    $('#step-round-2').removeClass('d-none');
                    $('#step-round-3').removeClass('btn-primary');
                    $('#step-round-3').addClass('btn-outline-primary');
                    $("html, body").animate({
                        scrollTop: 0
                    }, 500);
                    $('#step-3').fadeOut(300, function() {
                        $('#step-2').fadeIn(300, function() {
                            $('#step-2').removeClass('hidden-content')
                        })
                    });
                } else if (parent.attr('id') == 'step-4') {
                    $('#step-round-3-done').addClass('d-none');
                    $('#step-round-3').removeClass('d-none');
                    $('#step-round-4').removeClass('btn-primary');
                    $('#step-round-4').addClass('btn-outline-primary');
                    $("html, body").animate({
                        scrollTop: 0
                    }, 500);
                    $('#step-4').fadeOut(300, function() {
                        $('#step-3').fadeIn(300, function() {
                            $('#step-3').removeClass('hidden-content')
                        })
                    });
                }
            });
            $('.btn-next .btn-next-step').click(function(e) {
                e.preventDefault();
                let parent = $(this).parent().parent();
                $("html, body").animate({
                    scrollTop: 0
                }, 500);
                parent.fadeOut(300, function() {
                    // Callback akan dipanggil setelah animasi selesai
                    parent.addClass('hidden-content');
                    parent.removeAttr('style');

                    if (parent.attr('id') == 'step-1') {
                        $('#step-2').fadeIn(300);
                        $('#step-round-1-done').removeClass('d-none');
                        $('#step-round-1').addClass('d-none');
                        $('#step-round-2').removeClass('btn-outline-primary');
                        $('#step-round-2').addClass('btn-primary');
                    } else if (parent.attr('id') == 'step-2') {
                        $('#step-2').fadeOut(300, function() {
                            $('#step-2').addClass('hidden-content');
                            $('#step-2').removeAttr('style');
                        });
                        $('#step-3').fadeIn(300);
                        $('#step-round-2').addClass('d-none');
                        $('#step-round-2-done').removeClass('d-none');
                        $('#step-round-3').removeClass('btn-outline-primary');
                        $('#step-round-3').addClass('btn-primary');
                    } else if (parent.attr('id') == 'step-3') {
                        $('#step-3').fadeOut(300, function() {
                            $('#step-3').addClass('hidden-content');
                            $('#step-3').removeAttr('style');
                        });
                        $('#step-4').fadeIn(300);
                        $('#step-round-3').addClass('d-none');
                        $('#step-round-3-done').removeClass('d-none');
                        $('#step-round-4').removeClass('btn-outline-primary');
                        $('#step-round-4').addClass('btn-primary');
                    }
                });

            });

            // Select2
            $('#downloadTemplateDeductionModal #organization-option-deduction').select2({
                dropdownParent: $('#downloadTemplateDeductionModal'),
                placeholder: "kosongkan jika semua",
                allowClear: true
            });
            $('#downloadTemplateDeductionModal #employee-option-deduction').select2({
                dropdownParent: $('#downloadTemplateDeductionModal'),
                placeholder: "kosongkan jika semua",
                allowClear: true
            });

            $('#organization-option-deduction').change(function() {
                var organizationId = $(this).val();
                if (organizationId) {
                    $.ajax({
                        type: "GET",
                        url: "{{ route('getEmployeesByOrganization') }}",
                        data: {
                            organization_id: organizationId
                        },
                        success: function(response) {
                            var employees = response.data;
                            $('#employee-option-deduction').empty();
                            $('#employee-option-deduction').append(
                                '<option value=""></option>');
                            $.each(employees, function(key, value) {
                                $('#employee-option-deduction').append(
                                    '<option value="' + key +
                                    '">' + value + '</option>');
                            });
                        }
                    });
                } else {
                    $('#employee-option-deduction').empty();
                }
            });
        });
    </script>
@endsection
