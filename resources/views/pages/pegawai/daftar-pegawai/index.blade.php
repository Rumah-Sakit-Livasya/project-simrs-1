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

        .DTFC_LeftHeadWrapper:before,
        .DTFC_LeftBodyWrapper:before,
        .DTFC_LeftFootWrapper:before {
            background: transparent !important;
            box-shadow: -1px 0px 1px rgba(0, 0, 0, 0.4);
        }

        div.DTFC_RightHeadWrapper:before,
        div.DTFC_RightFootWrapper:before,
        div.DTFC_RightBodyWrapper:before {
            background: transparent !important;
            box-shadow: -10px 0px 1px rgba(0, 0, 0, 0.4);
        }

        /* Untuk WebKit (Chrome, Safari, Opera) */
        .dataTables_scrollBody::-webkit-scrollbar {
            width: 5px;
            /* Lebar scrollbar */
            height: 5px;
            /* Tinggi scrollbar */
        }

        /* Untuk thumb (bagian yang dapat digeser) */
        .dataTables_scrollBody::-webkit-scrollbar-thumb {
            background: #4679cc;
            /* Warna latar belakang thumb */
            border-radius: 5px;
            /* Sudut border thumb */
        }

        /* Untuk track (jalur scroll) */
        .dataTables_scrollBody::-webkit-scrollbar-track {
            background: #f1f1f1;
            /* Warna latar belakang track */
        }

        /* Untuk corner (sudut scrollbar) */
        .dataTables_scrollBody::-webkit-scrollbar-corner {
            background: transparent;
            /* Warna latar belakang corner */
        }

        /* Media query untuk layar ponsel */
        @media only screen and (max-width: 765px) {

            .DTFC_LeftWrapper,
            .DTFC_RightWrapper {
                display: none !important;
                /* Sembunyikan fitur fixedColumns */
            }
        }
    </style>
@endsection
@section('content')
    <main id="js-page-content" role="main" class="page-content">

        <div class="panel-container show">
            <div class="panel-content">
                <div class="row mb-5">
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-users text-primary" style="font-size: 36pt"></i>
                                    <h3 class="mb-0 ml-2 font-weight-bold text-primary" style="bottom: 10px;">Total Pegawai
                                    </h3>
                                </div>
                                <div class="" id="total_pegawai">
                                    <h3 class="mb-0 ml-2 font-weight-bold float-right text-primary mr-2"
                                        style="font-size: 28pt">
                                        {{ $employees->count() + $employees_non_aktif }}
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-user-times text-danger" style="font-size: 36pt"></i>
                                    <h3 class="mb-0 ml-2 font-weight-bold text-danger" style="bottom: 10px;">Pegawai Non
                                        Aktif
                                    </h3>
                                </div>
                                <div class="" id="total_nonaktif">
                                    <h3 class="mb-0 ml-2 font-weight-bold float-right text-danger mr-2"
                                        style="font-size: 28pt">
                                        {{ $employees_non_aktif }}
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-user text-success" style="font-size: 36pt"></i>
                                    <h3 class="mb-0 ml-2 font-weight-bold text-success" style="bottom: 10px;">Pegawai Aktif
                                    </h3>
                                </div>
                                <div class="" id="total_aktif">
                                    <h3 class="mb-0 ml-2 font-weight-bold float-right text-success mr-2"
                                        style="font-size: 28pt">
                                        {{ $employees->count() }}
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-building text-info" style="font-size: 36pt"></i>
                                    <h3 class="mb-0 ml-2 font-weight-bold text-info" style="bottom: 10px;">Pegawai Aktif
                                        RS
                                    </h3>
                                </div>
                                <div class="" id="total_aktif">
                                    <h3 class="mb-0 ml-2 font-weight-bold float-right text-info mr-2"
                                        style="font-size: 28pt">
                                        {{ $employees->where('company_id', 1)->count() }}
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-building text-info" style="font-size: 36pt"></i>
                                    <h3 class="mb-0 ml-2 font-weight-bold text-info" style="bottom: 10px;">Pegawai Aktif
                                        PT
                                    </h3>
                                </div>
                                <div class="" id="total_aktif">
                                    <h3 class="mb-0 ml-2 font-weight-bold float-right text-info mr-2"
                                        style="font-size: 28pt">
                                        {{ $employees->where('company_id', 2)->count() }}
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-12 mt-2">
                        <div id="panel-1" class="panel">
                            <div class="panel-hdr">
                                <h2>
                                    Filter
                                </h2>
                            </div>
                            <div class="panel-container show">
                                <div class="panel-content">
                                    <form action="{{ route('get.non-aktif-pegawai') }}" method="POST">
                                        @method('POST')
                                        @csrf
                                        <div class="row" id="step-1">
                                            <div class="col-md-5">
                                                <div class="form-group mb-3">
                                                    <label for="status">Status</label>
                                                    <!-- Mengubah input menjadi select2 -->
                                                    <select
                                                        class="select2 form-control @error('status') is-invalid @enderror"
                                                        name="status" id="status">
                                                        <option value=""></option>
                                                        <option value="1">Aktif</option>
                                                        <option value="0">Non Aktif</option>
                                                    </select>
                                                    @error('status')
                                                        <div class="invalid-feedback">{{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="form-group mb-3">
                                                    <label for="organization_id">Unit</label>
                                                    <!-- Mengubah input menjadi select2 -->
                                                    <select
                                                        class="select2 form-control @error('organization_id') is-invalid @enderror"
                                                        name="organization_id" id="organization_id_1">
                                                        <option value=""></option>
                                                        @foreach ($organizations as $item)
                                                            <option value="{{ $item->id }}">{{ $item->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('organization_id')
                                                        <div class="invalid-feedback">{{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-2 d-flex align-items-center">
                                                <button type="submit" class="btn btn-primary btn-block w-100">
                                                    <div class="ikon-tambah">
                                                        <span class="fal fa-search mr-1"></span>Cari
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
                    <div class="col-xl-12 mt-2">
                        <div id="panel-1" class="panel">
                            <div class="panel-container show">
                                <div class="panel-content tab-content">
                                    <ul class="nav nav-pills mb-5 mt-2" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-toggle="tab" href="#js_pill_border_icon-1"
                                                role="tab">
                                                <i class="fal fa-user-circle mr-1"></i> Daftar Pegawai
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#js_pill_border_icon-2"
                                                role="tab">
                                                <i class="fal fa-plus-circle mr-1"></i> Tambah Pegawai
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#js_pill_border_icon-3"
                                                role="tab">
                                                <i class="fal fa-plus-circle mr-1"></i>Import Pegawai
                                            </a>
                                        </li>
                                        {{-- <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#js_pill_border_icon-4"
                                                role="tab">
                                                <i class="fal fa-plus-circle mr-1"></i>Gaji Pegawai
                                            </a>
                                        </li> --}}
                                    </ul>
                                    <div class="tab-content px-0">
                                        <div class="tab-pane fade show active" id="js_pill_border_icon-1"
                                            role="tabpanel">
                                            @include('pages.pegawai.daftar-pegawai.partials.employee-table')
                                        </div>
                                        <div class="tab-pane p-3 fade" id="js_pill_border_icon-2" role="tabpanel">
                                            @include('pages.pegawai.daftar-pegawai.partials.create-employee-page')
                                        </div>
                                        <div class="tab-pane fade show" id="js_pill_border_icon-3" role="tabpanel">
                                            @include('pages.pegawai.daftar-pegawai.partials.create-employee')
                                        </div>
                                        {{-- <div class="tab-pane fade show" id="js_pill_border_icon-4" role="tabpanel">
                                            @include('pages.pegawai.daftar-pegawai.partials.employee-salary')
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    @include('pages.pegawai.daftar-pegawai.partials.non-aktif')
    @include('pages.pegawai.daftar-pegawai.partials.approval_line_edit')
    @include('pages.pegawai.daftar-pegawai.partials.location')
    @include('pages.pegawai.daftar-pegawai.partials.edit-organization')
    @include('pages.pegawai.daftar-pegawai.partials.payroll.modal-payroll-template')
@endsection
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script>
        // document.getElementById('downloadTemplateBtn').addEventListener('click', function() {
        //     $('#downloadTemplateModal').modal('show');
        // });

        $(document).ready(function() {
            let idPegawai = null;
            $('.select2').select2({
                placeholder: 'Pilih Data berikut',
            });

            $('#ubah-organisasi #is_doctor').change(function() {
                if ($(this).is(':checked')) {
                    $('#ubah-organisasi #departement_id').prop('disabled', false);
                    $('#ubah-organisasi #kode_dpjp').prop('disabled', false);
                } else {
                    $('#ubah-organisasi #departement_id').prop('disabled', true);
                    $('#ubah-organisasi #kode_dpjp').prop('disabled', true);
                }
            });

            $('#organization-option').select2({
                dropdownParent: $('#downloadTemplateModal'),
                placeholder: "kosongkan jika semua",
                allowClear: true
            });
            $('#employee-option').select2({
                dropdownParent: $('#downloadTemplateModal'),
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

        function btnNonAktifPegawai(event) {
            event.preventDefault();
            let button = event.currentTarget;
            let id = button.getAttribute('data-id');
            idPegawai = id;
            let nonAktif = button.querySelector('.ikon-non-aktif');
            let spinnerText = button.querySelector('.spinner-text');
            nonAktif.classList.add('d-none');
            spinnerText.classList.remove('d-none');

            $('#non-aktif-modal').modal('show');
            nonAktif.classList.remove('d-none');
            spinnerText.classList.add('d-none');
        }

        $('#non-aktif-form').on('submit', function(e) {
            e.preventDefault();
            let formData = $(this).serialize();
            formData += '&userLogin={{ auth()->user()->name }}';
            $.ajax({
                type: "POST",
                url: '/api/dashboard/employee/non-aktif/' + idPegawai,
                data: formData,
                beforeSend: function() {
                    $('#non-aktif-modal').find('.btn-non-aktif-form').hide();
                    $('#non-aktif-form').find('.spinner-text')
                        .removeClass(
                            'd-none');
                },
                success: function(response) {
                    $('#non-aktif-modal').modal('hide');
                    showSuccessAlert(response.message)
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                },
                error: function(xhr) {
                    $('#non-aktif-modal').modal('hide');
                    showErrorAlert(xhr.responseJSON.error);
                    console.log(xhr);
                }
            });
        });

        function btnOrganisasi(event) {
            event.preventDefault();
            let button = event.currentTarget;
            let id = button.getAttribute('data-id');
            let employeeId = button.getAttribute('data-employee-id');
            idPegawai = employeeId;
            let ikonEdit = button.querySelector('.ikon-organisasi');
            let ikonUbah = button.querySelector('.ikon-ubah-organisasi');
            let spinnerText = button.querySelector('.spinner-text');
            ikonEdit.classList.add('d-none');
            spinnerText.classList.remove('d-none');
            $.ajax({
                type: "GET", // Method pengiriman data bisa dengan GET atau POST
                url: `/api/dashboard/employee/organization/${employeeId}`, // Isi dengan url/path file php yang dituju
                dataType: "json",
                success: function(data) {
                    ikonEdit.classList.remove('d-none');
                    ikonEdit.classList.add('d-block');
                    spinnerText.classList.add('d-none');
                    // button.find('.ikon-edit').show();
                    // button.find('.spinner-text').addClass('d-none');
                    $('#ubah-organisasi').modal('show');
                    $('#ubah-organisasi #fullname').val(data.fullname);
                    $('#ubah-organisasi #email').val(data.email);
                    $('#ubah-organisasi #birthdate').val(data.birthdate);
                    $('#ubah-organisasi #mobile_phone').val(data.mobile_phone);
                    $('#ubah-organisasi #nik').val(data.identity_number);
                    $('#ubah-organisasi #employee_code').val(data.employee_code);
                    $('#ubah-organisasi #organization_id').val(data.organization_id).select2({
                        dropdownParent: $('#ubah-organisasi')
                    });
                    $('#ubah-organisasi #company_id').val(data.company_id).select2({
                        dropdownParent: $('#ubah-organisasi')
                    });
                    $('#ubah-organisasi #departement_id').val(data.doctor).select2({
                        placeholder: 'Pilih Department',
                        dropdownParent: $('#ubah-organisasi')
                    });
                    $('#ubah-organisasi #job_position_id').val(data.job_position_id).select2({
                        dropdownParent: $('#ubah-organisasi')
                    });
                    $('#ubah-organisasi #employment_status').val(data.employment_status).select2({
                        dropdownParent: $('#ubah-organisasi')
                    });
                    $('#ubah-organisasi #join_date').val(data.join_date);
                    $('#ubah-organisasi #end_status_date').val(data.end_status_date);

                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });

        }

        $('#update-form-organization').on("submit", function(e) {
            e.preventDefault();
            let formData = $(this).serialize();
            $.ajax({
                type: "PUT",
                url: '/api/dashboard/employee/organization/' + idPegawai,
                data: formData,
                beforeSend: function() {
                    $('#update-form-organization').find('.ikon-ubah-organisasi').hide();
                    $('#update-form-organization').find('.spinner-text')
                        .removeClass(
                            'd-none');
                },
                success: function(response) {
                    $('#ubah-organisasi').modal('hide');
                    showSuccessAlert(response.message)
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                },
                error: function(xhr) {
                    $('#ubah-organisasi').modal('hide');
                    showErrorAlert(xhr.responseJSON.error);
                    console.log(xhr);
                }
            });
        })

        function btnLink(event) {
            event.preventDefault();
            let button = event.currentTarget;
            let id = button.getAttribute('data-id');
            idPegawai = id;
            let ikonEdit = button.querySelector('.ikon-link');
            let spinnerText = button.querySelector('.spinner-text');
            ikonEdit.classList.add('d-none');
            spinnerText.classList.remove('d-none');
            $.ajax({
                type: "GET", // Method pengiriman data bisa dengan GET atau POST
                url: `/api/dashboard/employee/get/${id}`, // Isi dengan url/path file php yang dituju
                dataType: "json",
                success: function(data) {
                    ikonEdit.classList.remove('d-none');
                    ikonEdit.classList.add('d-block');
                    spinnerText.classList.add('d-none');
                    // button.find('.ikon-edit').show();
                    // button.find('.spinner-text').addClass('d-none');
                    $('#ubah-data').modal('show');
                    $('#ubah-data #approval_line_edit').val(data.approval_line).select2({
                        dropdownParent: $('#ubah-data')
                    });
                    $('#ubah-data #approval_line_parent_edit').val(data.approval_line_parent).select2({
                        dropdownParent: $('#ubah-data')
                    });
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });

        }

        $('#update-form-link').on("submit", function(e) {
            e.preventDefault();
            let formData = $(this).serialize();
            $.ajax({
                type: "PUT",
                url: '/api/dashboard/employee/approval_line/' + idPegawai,
                data: formData,
                beforeSend: function() {
                    $('#update-form-link').find('.ikon-edit').hide();
                    $('#update-form-link').find('.spinner-text').removeClass(
                        'd-none');
                },
                success: function(response) {
                    $('#ubah-data').modal('hide');
                    showSuccessAlert(response.message)
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                },
                error: function(xhr) {
                    $('#ubah-data').modal('hide');
                    showErrorAlert(xhr.responseJSON.error);
                    console.log(xhr);
                }
            });
        })

        function btnEditLocation(event) {
            event.preventDefault();
            let button = event.currentTarget;
            let id = button.getAttribute('data-id');
            let ikonEdit = button.querySelector('.ikon-edit');
            let spinnerText = button.querySelector('.spinner-text');
            ikonEdit.classList.add('d-none');
            spinnerText.classList.remove('d-none');
            // ikonEdit.classList.add('d-none');
            // spinnerText.classList.remove('d-none');

            $.ajax({
                type: "GET", // Method pengiriman data bisa dengan GET atau POST
                url: '/api/dashboard/employee/lokasi/' + id, // Isi dengan url/path file php yang dituju
                dataType: "json",
                success: function(data) {
                    ikonEdit.classList.remove('d-none');
                    ikonEdit.classList.add('d-block');
                    spinnerText.classList.add('d-none');
                    $('#tambah-lokasi').modal('show');
                    $('#tambah-lokasi #name').val(data[0].fullname);
                    if (data[1] != null) {
                        let idLocation = [];

                        // Check if data[1] is defined and is an array
                        if (Array.isArray(data[1])) {
                            // Iterate over each element in data[1]
                            data[1].forEach(element => {
                                // Check if element has an 'id' property
                                if (element && element.id !== undefined) {
                                    // Push 'id' property value into idLocation array
                                    idLocation.push(element.id);
                                } else {
                                    console.error("Element does not have an 'id' property:", element);
                                    // Handle the case where 'id' property is missing
                                }
                            });
                        }
                        $('#tambah-lokasi #location_name').val(idLocation).select2({
                            dropdownParent: $('#tambah-lokasi')
                        });
                    } else {
                        $('#tambah-lokasi #location_name').select2({
                            dropdownParent: $('#tambah-lokasi')
                        });
                    }
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });

            $('#store-form-location').on('submit', function(e) {
                e.preventDefault();
                const fd = new FormData(this);
                fd.append('id', id);
                const submitButton = $('#store-form-location').find('button[type="submit"]');
                let button = $('tbh-lokasi');

                let ikonTambah = $('#store-form-location .ikon-tambah');
                let spinnerText = $('#store-form-location .spinner-text');
                ikonTambah.addClass('d-none');
                spinnerText.removeClass('d-none');
                submitButton.prop('disabled', true);

                $.ajax({
                    type: 'POST',
                    url: '/api/dashboard/employee/location/store',
                    processData: false,
                    contentType: false,
                    data: fd,
                    success: function(response) {
                        $('#tambah-lokasi').modal('hide');
                        showSuccessAlert(response.message);
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    },
                    error: function(xhr, status, error) {
                        $('#tambah-lokasi').modal('hide');
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        showErrorAlert(errorMessage);
                    },
                    complete: function() {
                        submitButton.prop('disabled', false); // Re-enable the submit button
                    }
                });
            });
        }


        $(document).ready(function() {
            $('#store-form').submit(function(event) {
                event.preventDefault();

                var formData = new FormData($(this)[0]);

                $.ajax({
                    url: '/api/dashboard/employee/salary/export', // Ganti dengan endpoint API Anda
                    type: 'POST',
                    data: formData,
                    async: true, // Set async menjadi true untuk melakukan operasi secara asynchronous
                    cache: false,
                    contentType: false,
                    processData: false,
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
                        }, 1000);
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        showErrorAlert(xhr.responseJSON.error);
                        // Tampilkan pesan error kepada pengguna
                    }
                });
            });


            $('#dt-basic-example').dataTable({
                // responsive: true, // not compatible
                // scrollY: 400,
                scrollX: true,
                scrollCollapse: true,
                paging: true,
                //fixedColumns:   true,
                fixedColumns: {
                    leftColumns: 2,
                    rightColumns: 1
                },
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
                        extend: 'excelHtml5', // Menggunakan 'excelHtml5' untuk ekspor Excel
                        text: 'Excel', // Mengubah teks tombol menjadi 'Excel'
                        title: 'Daftar Pegawai',
                        titleAttr: 'Export to Excel', // Mengubah atribut judul tombol
                        className: 'btn-outline-default', // Mengatur kelas tombol
                        exportOptions: {
                            columns: ':not(.no-print)',
                            customize: function(xlsx) {
                                var sheet = xlsx.xl.worksheets['sheet1.xml'];
                                // Menambahkan gaya border ke setiap sel
                                $('row c', sheet).css('border', '1px solid black');
                            }
                        }
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        titleAttr: 'Print Table',
                        title: 'Daftar Pegawai',
                        className: 'btn-outline-default',
                        exportOptions: {
                            columns: ':not(.no-print)',
                            scale: 0.54, // Atur skala menjadi 50%
                            customize: function(win) {
                                $(win.document.body)
                                    .find('table')
                                    .addClass('table')
                                    .css('margin', '10px'); // Atur margin sebesar 10px
                                $(win.document.body).find('table').css('transform',
                                    'rotate(90deg)'); // Putar tabel 90 derajat (mode landscape)
                                $(win.document.body).find('table').css('width',
                                    '100%'); // Menyesuaikan lebar tabel dengan ukuran kertas
                                $(win.document.body).find('table').css('font-size',
                                    '10pt'
                                ); // Menyesuaikan ukuran font agar sesuai dengan ukuran kertas
                            }
                        },
                        customize: function(win) {
                            $(win.document.body).css('margin',
                                '10px'); // Atur margin sebesar 10px untuk seluruh halaman
                            $(win.document.body).css('transform',
                                'rotate(0deg)'); // Kembalikan orientasi halaman ke potrait
                        }
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
            $('#store-form-employee').on('submit', function(e) {
                e.preventDefault();
                let formData = $(this).serialize();
                $.ajax({
                    type: "POST",
                    url: '/api/dashboard/employee/store',
                    data: formData,
                    beforeSend: function() {
                        $('#store-form').find('.ikon-tambah').hide();
                        $('#store-form').find('.spinner-text').removeClass(
                            'd-none');
                    },
                    success: function(response) {
                        $('#store-form').find('.ikon-edit').show();
                        $('#store-form').find('.spinner-text').addClass('d-none');
                        $('#tambah-data').modal('hide');
                        showSuccessAlert(response.message)
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    },
                    error: function(xhr) {
                        var errors = JSON.parse(xhr.responseText);
                        var errorMessage = '';

                        $.each(errors, function(key, value) {
                            errorMessage += value +
                                '. '; // Menambahkan setiap pesan kesalahan
                        });

                        showErrorAlertNoRefresh(
                            errorMessage); // Menampilkan pesan kesalahan yang sudah dirapikan
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
            $('#datepicker-3').datepicker({
                todayBtn: "linked",
                clearBtn: true,
                todayHighlight: true,
                format: "yyyy-mm-dd"
            });
            $('#identity_expire_date').datepicker({
                todayBtn: "linked",
                clearBtn: true,
                todayHighlight: true,
                format: "yyyy-mm-dd"
            });
            $('#join_date').datepicker({
                todayBtn: "linked",
                clearBtn: true,
                todayHighlight: true,
                format: "yyyy-mm-dd"
            });
            $('#birthdate').datepicker({
                todayBtn: "linked",
                clearBtn: true,
                todayHighlight: true,
                format: "yyyy-mm-dd"
            });
            $('.datepicker').datepicker({
                todayBtn: "linked",
                clearBtn: true,
                todayHighlight: true,
                format: "yyyy-mm-dd"
            });

            // IS MANAGEMENT BUTTON
            $('.btn-toggle-management').on('click', function() {
                let button = $(this);
                let employeeId = button.data('id');
                let currentStatus = button.data('status');
                let newStatus = currentStatus === 1 ? 0 : 1;

                button.prop('disabled', true);

                $.ajax({
                    url: '/api/dashboard/employee/toggle-management/' + employeeId,
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        is_management: newStatus
                    },
                    success: function(response) {
                        if (response.success) {
                            button.data('status', newStatus);
                            showSuccessAlert('Status berhasil diperbarui');

                        } else {
                            showErrorAlertNoRefresh('Gagal memperbarui status');
                        }
                    },
                    error: function() {
                        showErrorAlertNoRefresh('Terjadi kesalahan saat memproses');
                    },
                    complete: function() {
                        button.prop('disabled', false);
                    }
                });
            });
        });
    </script>
@endsection
