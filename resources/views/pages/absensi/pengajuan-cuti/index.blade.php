@extends('inc.layout')
@section('title', 'Pengajuan Cuti/Izin/Sakit')
@section('extended-css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <style>
        .icon-dashboard-report {
            font-size: 2em;
            text-align: center;
        }

        .text-dashboard-report {
            font-size: 1em;
            text-align: center;
            color: #666666 !important;
        }

        .bg-opacity-50 {
            background-color: #fd3994a5 !important;
            /* Merah dengan opacity 50% */
        }

        .badge.pos-top.pos-right.dashboard-report {
            font-size: 0.9em;
            top: 8px;
            right: 6px;
            border-radius: 50%;
            height: 20px !important;
            width: 20px !important;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .row .col-md-3 .card {
            height: 100%;
        }

        @media screen and (max-width: 500px) {

            .badge.pos-top.pos-right.dashboard-report {
                font-size: 0.9em;
                height: 15px;
                width: 15px;
            }

            .text-dashboard-report {
                font-size: 0.8em;
                text-align: center;
                color: #666666 !important;
            }
        }
    </style>
@endsection
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row mb-5">
            <div class="col-xl-12">
                <button type="button" class="btn btn-primary waves-effect waves-themed" data-backdrop="static"
                    data-keyboard="false" data-toggle="modal" data-target="#tambah-data" title="Tambah User">
                    <span class="fal fa-plus-circle mr-1"></span>
                    Ajukan Cuti/Izin/Sakit
                </button>
            </div>
        </div>

        <div class="row d-flex mb-3">
            <div class="col-md-4 col-xs-4 col-sm-4 mb-2 pr-1" style="width: 33%;">
                <div class="card" style="height: 100%;">
                    <div class="card-body p-2">
                        <span class="badge badge-icon pos-top pos-right dashboard-report">{{ $day_off['ct'] ?? '0' }}</span>
                        <div class="icon-dashboard-report text-primary">
                            <i class="fal fa-clock hadir"></i>
                        </div>
                        <div class="text-dashboard-report">
                            Cuti Tahunan
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-xs-4 col-sm-4 mb-2 px-1" style="width: 33%">
                <div class="card" style="height: 100%;">
                    <div class="card-body p-2">
                        <span class="badge badge-icon pos-top pos-right dashboard-report">{{ $day_off['cl'] ?? '0' }}</span>
                        <div class="icon-dashboard-report text-success">
                            <i class="fas fa-procedures"></i>
                        </div>
                        <div class="text-dashboard-report">
                            Cuti Melahirkan
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-xs-4 col-sm-4 mb-2 pl-1" style="width: 33%">
                <div class="card" style="height: 100%;">
                    <div class="card-body p-2">
                        <span class="badge badge-icon pos-top pos-right dashboard-report">{{ $day_off['ck'] ?? '0' }}</span>
                        <div class="icon-dashboard-report text-warning">
                            <i class="fas fa-ambulance"></i>
                        </div>
                        <div class="text-dashboard-report">
                            Cuti Keguguran
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-xs-4 col-sm-4 mb-2 pr-1" style="width: 33%;">
                <div class="card" style="height: 100%;">
                    <div class="card-body p-2">
                        <span class="badge badge-icon pos-top pos-right dashboard-report">{{ $day_off['cm'] ?? '0' }}</span>
                        <div class="icon-dashboard-report text-danger">
                            <i class="fas fa-heart"></i>
                        </div>
                        <div class="text-dashboard-report">
                            Cuti Menikah
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-xs-4 col-sm-4 mb-2 px-1" style="width: 33%;">
                <div class="card" style="height: 100%;">
                    <div class="card-body p-2">
                        <span
                            class="badge badge-icon pos-top pos-right dashboard-report">{{ $day_off['cma'] ?? '0' }}</span>
                        <div class="icon-dashboard-report text-primary">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <div class="text-dashboard-report">
                            Cuti Menikahkan Anak
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-xs-4 col-sm-4 mb-2 pl-1" style="width: 33%">
                <div class="card" style="height: 100%;">
                    <div class="card-body p-2">
                        <span
                            class="badge badge-icon pos-top pos-right dashboard-report">{{ $day_off['cka'] ?? '0' }}</span>
                        <div class="icon-dashboard-report text-success">
                            <i class="fas fa-syringe"></i>
                        </div>
                        <div class="text-dashboard-report">
                            Cuti Khitanan Anak
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-xs-4 col-sm-4 mb-2 pr-1" style="width: 33%">
                <div class="card" style="height: 100%;">
                    <div class="card-body p-2">
                        <span
                            class="badge badge-icon pos-top pos-right dashboard-report">{{ $day_off['ckm'] ?? '0' }}</span>
                        <div class="icon-dashboard-report text-primary">
                            <i class="fas fa-plus"></i>
                        </div>
                        <div class="text-dashboard-report">
                            Cuti Keluarga Meninggal
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-xs-4 col-sm-4 mb-2 px-1" style="width: 33%;">
                <div class="card" style="height: 100%;">
                    <div class="card-body p-2">
                        <span
                            class="badge badge-icon pos-top pos-right dashboard-report">{{ $day_off['crm'] ?? '0' }}</span>
                        <div class="icon-dashboard-report text-warning">
                            <i class="fas fa-plus-circle"></i>
                        </div>
                        <div class="text-dashboard-report">
                            Cuti Keluarga se-Rumah Meninggal
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-xs-4 col-sm-4 mb-2 pl-1" style="width: 33%;">
                <div class="card" style="height: 100%;">
                    <div class="card-body p-2">
                        <span
                            class="badge badge-icon pos-top pos-right dashboard-report">{{ $day_off['cim'] ?? '0' }}</span>
                        <div class="icon-dashboard-report text-danger">
                            <i class="fas fa-hospital-alt"></i>
                        </div>
                        <div class="text-dashboard-report">
                            Cuti Istri Melahirkan/Keguguran
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            History Cuti
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <!-- datatable start -->
                            <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                                <thead>
                                    <tr>
                                        <th style="white-space: nowrap">No</th>
                                        <th style="white-space: nowrap">Tanggal</th>
                                        <th style="white-space: nowrap">Jenis Cuti</th>
                                        <th style="white-space: nowrap">Keterangan</th>
                                        <th style="white-space: nowrap">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($day_off_requests as $row)
                                        <tr>
                                            <td style="white-space: nowrap">{{ $loop->iteration }}</td>
                                            <td style="white-space: nowrap">
                                                {{ $row->start_date . ' - ' . $row->end_date }}
                                            </td>
                                            <td style="white-space: nowrap">{{ $row->attendance_code->description }}
                                            </td>
                                            <td style="white-space: nowrap">{{ $row->description }}
                                            </td>
                                            <td style="white-space: nowrap">
                                                <span
                                                    class="badge {{ $row->is_approved == 'Pending' ? 'badge-warning' : ($row->is_approved == 'Disetujui' ? 'badge-success' : ($row->is_approved == 'Ditolak' ? 'badge-danger' : ($row->is_approved == 'Verifikasi' ? 'badge-primary' : ''))) }}">
                                                    {{ $row->is_approved }} </span>

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th style="white-space: nowrap">No</th>
                                        <th style="white-space: nowrap">Tanggal</th>
                                        <th style="white-space: nowrap">Jenis Cuti</th>
                                        <th style="white-space: nowrap">Keterangan</th>
                                        <th style="white-space: nowrap">Status</th>
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
    @include('pages.absensi.pengajuan-cuti.partials.create')
@endsection
@section('plugin')
    <script src="/js/dependency/moment/moment.js"></script>
    <script src="/js/formplugins/bootstrap-daterangepicker/bootstrap-daterangepicker.js"></script>
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        // Preview Image Update Profile
        function previewImage() {
            const image = document.querySelector('#file');
            const imgPreview = document.querySelector('.img-preview')

            imgPreview.style.display = 'block';

            const oFReader = new FileReader();
            oFReader.readAsDataURL(image.files[0])

            oFReader.onload = function(oFREvent) {
                imgPreview.src = oFREvent.target.result;
            }
        }

        $(document).ready(function() {

            const totalCT = @json($day_off['ct']);
            const totalCM = @json($day_off['cm']);
            const totalCL = @json($day_off['cl']);
            const totalCK = @json($day_off['ck']);
            const totalCMA = @json($day_off['cma']);
            const totalCKA = @json($day_off['cka']);
            const totalCKM = @json($day_off['ckm']);
            const totalCRM = @json($day_off['crm']);
            const totalCIM = @json($day_off['cim']);

            $(function() {
                $('.select2').select2({
                    placeholder: 'Pilih Data Berikut',
                    dropdownParent: $('#tambah-data')
                });
            });
            $('#datepicker-modal-2').daterangepicker({
                opens: 'left'
            }, function(start, end, label) {
                console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end
                    .format('YYYY-MM-DD'));
            });

            $('#store-form').on('submit', function(e) {

                e.preventDefault();
                if ($('#attendance_code_id').val() == 3 && totalCT < 1) {
                    $('#tambah-data').modal('hide');
                    showErrorAlert('Jatah Cuti Tahunan sudah habis!');
                } else if ($('#attendance_code_id').val() == 7 && totalCM < 1) {
                    $('#tambah-data').modal('hide');
                    showErrorAlert('Jatah Cuti Menikah sudah habis!');
                } else if ($('#attendance_code_id').val() == 8 && totalCMA < 1) {
                    $('#tambah-data').modal('hide');
                    showErrorAlert('Jatah Cuti Menikahkan Anak sudah habis!');
                } else if ($('#attendance_code_id').val() == 9 && totalCKA < 1) {
                    $('#tambah-data').modal('hide');
                    showErrorAlert('Jatah Cuti Khitanan Anak sudah habis!');
                } else if ($('#attendance_code_id').val() == 10 && totalCIM < 1) {
                    $('#tambah-data').modal('hide');
                    showErrorAlert('Jatah Cuti Izin Istri Melahirkan sudah habis!');
                } else if ($('#attendance_code_id').val() == 12 && totalCK < 1) {
                    $('#tambah-data').modal('hide');
                    showErrorAlert('Jatah Cuti Keguguran sudah habis!');
                } else if ($('#attendance_code_id').val() == 13 && totalCKM < 1) {
                    $('#tambah-data').modal('hide');
                    showErrorAlert('Jatah Cuti Keluarga Meninggal sudah habis!');
                } else if ($('#attendance_code_id').val() == 14 && totalCRM < 1) {
                    $('#tambah-data').modal('hide');
                    showErrorAlert('Jatah Cuti Keluarga Meninggal se-Rumah sudah habis!');
                } else if ($('#attendance_code_id').val() == 15 && totalCRM < 1) {
                    $('#tambah-data').modal('hide');
                    showErrorAlert('Jatah Cuti Melahirkan sudah habis!');
                } else {
                    let formData = new FormData(this);
                    formData.append("employee_id", "{{ auth()->user()->employee->id }}");
                    formData.append("approved_line_child",
                        "{{ auth()->user()->employee->approval_line }}");
                    formData.append("approved_line_parent",
                        "{{ auth()->user()->employee->approval_line_parent }}");

                    $.ajax({
                        type: "POST",
                        url: '/attendances/request/day-off',
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
                            }, 1000);
                        },
                        error: function(xhr) {
                            $('#store-form').find('.ikon-tambah').show();
                            $('#store-form').find('.spinner-text').addClass('d-none');
                            $('#tambah-data').modal('hide');
                            let errorMessage =
                                "Terjadi kesalahan saat menyimpan data."; // Default message
                            if (xhr.status === 422) {
                                // Validation error from the server
                                let errors = xhr.responseJSON.errors;
                                errorMessage = Object.values(errors).map(function(error) {
                                    return error.join('<br>');
                                }).join('<br>');
                            } else if (xhr.responseJSON && xhr.responseJSON.error) {
                                // General error from the server
                                errorMessage = xhr.responseJSON.error;
                            }
                            showErrorAlert(errorMessage);
                        }
                    });
                }
            });


            $('.btn-accept').on('click', function(e) {
                e.preventDefault();
                console.log("click");
                let formData = {
                    employee_id: "{{ auth()->user()->employee->id }}"
                }
                let id = $(this).attr('data-id');
                $.ajax({
                    type: "PUT",
                    url: '/attendances/approve/day-off/' + id,
                    data: formData,
                    beforeSend: function() {
                        $('#approve-request').find('.ikon-edit').hide();
                        $('#approve-request').find('.spinner-text')
                            .removeClass(
                                'd-none');
                    },
                    success: function(response) {
                        showSuccessAlert(response.message)
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    },
                    error: function(xhr) {
                        showErrorAlert(xhr.responseJSON.error);
                    }
                });
            })

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
    </script>
@endsection
