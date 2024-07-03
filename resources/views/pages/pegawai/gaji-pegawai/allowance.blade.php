{{-- @dd($employees->first()->deduction) --}}
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
                                            <a class="nav-link active" data-toggle="tab" href="#js_pill_border_icon-4"
                                                role="tab">
                                                <i class="fal fa-plus-circle mr-1"></i>Import / Export Gaji & Tunjangan
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#js_pill_border_icon-6"
                                                role="tab">
                                                <i class="fal fa-plus-circle mr-1"></i>Daftar Gaji & Tunjangan
                                            </a>
                                        </li>
                                    </ul>

                                    <div class="tab-content px-0">
                                        <div class="tab-pane fade show active" id="js_pill_border_icon-4" role="tabpanel">
                                            @include('pages.pegawai.daftar-pegawai.partials.payroll.import-salary')
                                        </div>
                                        <div class="tab-pane fade show" id="js_pill_border_icon-6" role="tabpanel">
                                            @include('pages.pegawai.daftar-pegawai.partials.payroll.allowance')
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
    @include('pages.pegawai.daftar-pegawai.partials.payroll.edit-component')
@endsection
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script>
        document.getElementById('downloadTemplateBtn').addEventListener('click', function() {
            $('#downloadTemplateSalaryModal').modal('show');
        });

        let employeeId = null;

        function btnSalary(event) {
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
                url: `/api/dashboard/payroll/salary/get/${employeeId}`, // Isi dengan url/path file php yang dituju
                dataType: "json",
                success: function(data) {
                    ikonEdit.classList.remove('d-none');
                    ikonEdit.classList.add('d-block');
                    spinnerText.classList.add('d-none');
                    $('#ubah-salary').modal('show');
                    $('#ubah-salary #basic_salary').val(data.salary.basic_salary);
                    $('#ubah-salary #tunjangan_jabatan').val(data.salary.tunjangan_jabatan);
                    $('#ubah-salary #tunjangan_profesi').val(data.salary.tunjangan_profesi);
                    $('#ubah-salary #tunjangan_makan_dan_transport').val(data.salary
                        .tunjangan_makan_dan_transport);
                    $('#ubah-salary #tunjangan_masa_kerja').val(data.salary.tunjangan_masa_kerja);
                    $('#ubah-salary #guarantee_fee').val(data.salary.guarantee_fee);
                    $('#ubah-salary #uang_duduk').val(data.salary.uang_duduk);
                    $('#ubah-salary #tax_allowance').val(data.salary.tax_allowance);
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });

        }

        $(document).ready(function() {
            $('#update-form').on("submit", function(e) {
                e.preventDefault();
                let formData = $(this).serialize();
                console.log(formData);
                $.ajax({
                    type: "PUT",
                    url: '/api/dashboard/payroll/salary/update/' + employeeId,
                    data: formData,
                    beforeSend: function() {
                        $('#update-form').find('.ikon-edit').hide();
                        $('#update-form').find('.spinner-text').removeClass(
                            'd-none');
                    },
                    success: function(response) {
                        $('#ubah-salary').modal('hide');
                        showSuccessAlert(response.message)
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    },
                    error: function(xhr) {
                        $('#ubah-salary').modal('hide');
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

            $('#import-salary').submit(function(event) {
                event.preventDefault();
                var formData = new FormData($(this)[0]);
                $.ajax({
                    url: '/api/dashboard/employee/salary/import', // Ganti dengan endpoint API Anda
                    type: 'POST',
                    data: formData,
                    async: true, // Set async menjadi true untuk melakukan operasi secara asynchronous
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#import-salary').find('.ikon-tambah').hide();
                        $('#import-salary').find('.spinner-text').removeClass('d-none');
                    },
                    success: function(response) {
                        $('#import-salary').find('.ikon-edit').show();
                        $('#import-salary').find('.spinner-text').addClass('d-none');
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
            $('#downloadTemplateSalaryModal #organization-option').select2({
                dropdownParent: $('#downloadTemplateSalaryModal'),
                placeholder: "kosongkan jika semua",
                allowClear: true
            });
            $('#downloadTemplateSalaryModal #employee-option').select2({
                dropdownParent: $('#downloadTemplateSalaryModal'),
                placeholder: "kosongkan jika semua",
                allowClear: true
            });

            $('#organization-option').change(function() {
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
                            $('#employee-option').empty();
                            $('#employee-option').append('<option value=""></option>');
                            $.each(employees, function(key, value) {
                                $('#employee-option').append('<option value="' + key +
                                    '">' + value + '</option>');
                            });
                        }
                    });
                } else {
                    $('#employee-option').empty();
                }
            });
        });
    </script>
@endsection
