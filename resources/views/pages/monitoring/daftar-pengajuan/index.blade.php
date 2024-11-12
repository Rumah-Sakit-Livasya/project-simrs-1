@extends('inc.layout')
@section('title', 'Monitoring Dashboard')
@section('extended-css')
    <style>
        @media screen and (min-width: 680px) {

            #absensi #dt-basic-example tbody td:first-child,
            #absensi #dt-basic-example thead th:first-child,
            #absensi #dt-basic-example tfoot th:first-child {
                position: sticky;
                left: 0;
                z-index: 2;
                /* Ditingkatkan agar kolom "Action" tetap di atas kolom lainnya */
                background-color: #f9f9f9;
            }

            #absensi #dt-basic-example thead th:nth-child(2),
            #absensi #dt-basic-example tbody td:nth-child(2),
            #absensi #dt-basic-example tfoot th:nth-child(2) {
                position: sticky;
                left: 62px;
                /* Sesuaikan dengan lebar kolom pertama */
                z-index: 2;
                /* Ditingkatkan agar kolom "Action" tetap di atas kolom lainnya */
                background-color: #f9f9f9;
                vertical-align: middle
            }


            #absensi #dt-basic-example thead th:last-child,
            #absensi #dt-basic-example tbody td:last-child,
            #absensi #dt-basic-example tfoot th:last-child {
                position: sticky;
                right: 0;
                z-index: 2;
                /* Ditingkatkan agar kolom "Action" tetap di atas kolom lainnya */
                background-color: #f9f9f9;
            }
        }

        #dt-basic-example thead th,
        #dt-basic-example tbody td,
        #dt-basic-example tfoot th {
            vertical-align: middle;
        }

        a.link_nama:hover {
            text-decoration: underline !important;
        }
    </style>
@endsection
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">
                <div class="card pt-2">
                    <div class="card-body row">
                        <div class="col-xl-4 col-sm-4 mb-2">
                            <h5 class="font-weight-bold text-primary">
                                {{ \Carbon\Carbon::now()->translatedFormat('l, j F Y') }}
                            </h5>
                            <span style="font-size: 1.1em">Semua laporan pengajuan bisa dilihat pada menu dibawah ini.
                            </span>
                        </div>
                        <div class="col-xl-2 col-sm-2 mb-2">
                            <span class="title-sm d-inline-block mb-2 font-weight-bold text-primary">Pengajuan Libur</span>
                            <h1 style="font-size: 2em">
                                {{ isset($day_off_requests) ? $day_off_requests->count() : '0' }}
                            </h1>
                        </div>
                        <div class="col-xl-2 col-sm-2 mb-2">
                            <span class="title-sm d-inline-block mb-2 font-weight-bold text-primary">Pengajuan Absen</span>
                            <h1 style="font-size: 2em">
                                {{ isset($attendance_requests) ? $attendance_requests->count() : '0' }}
                            </h1>
                        </div>
                        <div class="col-xl-1 col-sm-1 mb-2">
                            <span class="title-sm d-inline-block mb-2 font-weight-bold text-primary">Pending</span>
                            <h1 style="font-size: 2em">{{ isset($total_pending) ? $total_pending : '0' }}</h1>
                        </div>
                        <div class="col-xl-1 col-sm-1 mb-2">
                            <span class="title-sm d-inline-block mb-2 font-weight-bold text-primary">Verifikasi</span>
                            <h1 style="font-size: 2em">{{ isset($total_verifikasi) ? $total_verifikasi : '0' }}</h1>
                        </div>
                        <div class="col-xl-1 col-sm-1 mb-2">
                            <span class="title-sm d-inline-block mb-2 font-weight-bold text-primary">Disetujui</span>
                            <h1 style="font-size: 2em">{{ isset($total_disetujui) ? $total_disetujui : '0' }}</h1>
                        </div>
                        <div class="col-xl-1 col-sm-1 mb-2">
                            <span class="title-sm d-inline-block mb-2 font-weight-bold text-primary">Ditolak</span>
                            <h1 style="font-size: 2em">{{ isset($total_ditolak) ? $total_ditolak : '0' }}</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            {{-- Daftar yang libur dan Cuti/Izin/Sakit  --}}
            <div class="col-xl-12 mb-3">
                <button id="tambah_pengajuan_cuti" class="btn btn-primary">Tambah Pengajuan Cuti/Izin Sakit</button>
            </div>
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Daftar Pengajuan Cuti/Izin/Sakit
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <!-- datatable start -->
                            <table id="libur" class="table table-bordered table-hover table-striped w-100">
                                <thead>
                                    <tr>
                                        <th style="white-space: nowrap">No</th>
                                        <th style="white-space: nowrap">Waktu Pengajuan</th>
                                        <th style="white-space: nowrap">Nama</th>
                                        <th style="white-space: nowrap">Tanggal</th>
                                        <th style="white-space: nowrap">Keterangan</th>
                                        <th style="white-space: nowrap">Status</th>
                                        @if (auth()->user()->hasRole('super admin'))
                                            <th style="white-space: nowrap">Action</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($day_off_requests as $row)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td style="white-space: nowrap">
                                                {{ tgl_waktu($row->created_at) }}
                                            </td>
                                            <td style="white-space: nowrap">
                                                {{ $row->employee->fullname }}
                                            </td>
                                            <td style="white-space: nowrap">
                                                {{ tgl($row->start_date) . ' - ' . tgl($row->end_date) }}
                                            </td>
                                            <td style="white-space: nowrap">
                                                <span class="badge badge-pill badge-danger">
                                                    {{ $row->attendance_code->description }}
                                                </span>
                                            </td>
                                            <td style="white-space: nowrap">
                                                @isset($row->is_approved)
                                                    <span
                                                        class="badge {{ $row->is_approved == 'Pending' ? 'badge-warning' : ($row->is_approved == 'Disetujui' ? 'badge-success' : ($row->is_approved == 'Ditolak' ? 'badge-danger' : ($row->is_approved == 'Verifikasi' ? 'badge-primary' : ''))) }}">
                                                        {{ $row->is_approved }} </span>
                                                @else
                                                    -
                                                @endisset
                                            </td>
                                            @if (auth()->user()->hasRole('super admin'))
                                                <td>
                                                    <button data-backdrop="static" data-keyboard="false"
                                                        class="badge mx-1 badge-success p-2 border-0 text-white btn-edit"
                                                        data-id="{{ $row->id }}" title="Edit Absensi"
                                                        data-aksi="dayoff" data-name="{{ $row->employee->fullname }}"
                                                        data-employee-id="{{ $row->employee->id }}"
                                                        onclick="btnEdit(event)">
                                                        <span class="fal fa-pencil ikon-edit"></span>
                                                        <div class="span spinner-text d-none">
                                                            <span class="spinner-border spinner-border-sm" role="status"
                                                                aria-hidden="true"></span>
                                                        </div>
                                                    </button>
                                                    <button type="button" data-backdrop="static" data-keyboard="false"
                                                        onclick="btnDelete(event)" data-aksi="day-off"
                                                        class="badge mx-1 badge-danger p-2 border-0 text-white btn-hapus"
                                                        data-id="{{ $row->id }}" title="Hapus">
                                                        <span class="fal fa-trash ikon-hapus"></span>
                                                        <div class="span spinner-text d-none">
                                                            <span class="spinner-border spinner-border-sm" role="status"
                                                                aria-hidden="true"></span>
                                                        </div>
                                                    </button>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th style="white-space: nowrap">No</th>
                                        <th style="white-space: nowrap">Waktu Pengajuan</th>
                                        <th style="white-space: nowrap">Nama</th>
                                        <th style="white-space: nowrap">Tanggal</th>
                                        <th style="white-space: nowrap">Keterangan</th>
                                        <th style="white-space: nowrap">Status</th>
                                        @if (auth()->user()->hasRole('super admin'))
                                            <th style="white-space: nowrap">Action</th>
                                        @endif
                                    </tr>
                                </tfoot>
                            </table>
                            <!-- datatable end -->
                        </div>
                    </div>
                </div>
            </div>
            {{-- Daftar No Clock In --}}

            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Daftar Pengajuan Absensi
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <!-- datatable start -->
                            <table id="absen" class="table table-bordered table-hover table-striped w-100">
                                <thead>
                                    <tr>
                                        <th style="white-space: nowrap">No</th>
                                        <th style="white-space: nowrap">Nama</th>
                                        <th style="white-space: nowrap">Tanggal</th>
                                        <th style="white-space: nowrap">CLock In</th>
                                        <th style="white-space: nowrap">Clock Out</th>
                                        <th style="white-space: nowrap">Status</th>
                                        @if (auth()->user()->hasRole('super admin') ||
                                                auth()->user()->can(['monitoring edit pengajuan absensi', 'monitoring delete pengajuan absensi']))
                                            <th style="white-space: nowrap">Action</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($attendance_requests as $row)
                                        @if (!isset($row->clock_in) && $row->is_day_off == null)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td style="white-space: nowrap">
                                                    {{ $row->employee->fullname }}
                                                </td>
                                                <td style="white-space: nowrap">
                                                    {{ tgl($row->date) }}
                                                </td>
                                                <td style="white-space: nowrap">
                                                    @isset($row->clockin)
                                                        <span class="badge badge-primary badge-pill">
                                                            {{ $row->clockin }}
                                                        </span>
                                                    @else
                                                        -
                                                    @endisset
                                                </td>
                                                <td style="white-space: nowrap">
                                                    @isset($row->clockout)
                                                        <span class="badge badge-primary badge-pill">
                                                            {{ $row->clockout }}
                                                        </span>
                                                    @else
                                                        -
                                                    @endisset
                                                </td>
                                                <td style="white-space: nowrap">
                                                    <span
                                                        class="badge {{ $row->is_approved == 'Pending' ? 'badge-warning' : ($row->is_approved == 'Disetujui' ? 'badge-success' : ($row->is_approved == 'Ditolak' ? 'badge-danger' : ($row->is_approved == 'Verifikasi' ? 'badge-primary' : ''))) }}">
                                                        {{ $row->is_approved }} </span>
                                                </td>
                                                @if (auth()->user()->hasRole('super admin') ||
                                                        auth()->user()->can(['monitoring edit pengajuan absensi', 'monitoring delete pengajuan absensi']))
                                                    <td>
                                                        @if (auth()->user()->hasRole('super admin') || auth()->user()->can('monitoring edit pengajuan absensi'))
                                                            <button data-backdrop="static" data-keyboard="false"
                                                                class="badge mx-1 badge-success p-2 border-0 text-white btn-edit"
                                                                data-id="{{ $row->id }}" title="Edit Absensi"
                                                                data-name="{{ $row->employee->fullname }}"
                                                                data-employee-id="{{ $row->employee->id }}"
                                                                data-aksi="attendance" onclick="btnEdit(event)">
                                                                <span class="fal fa-pencil ikon-edit"></span>
                                                                <div class="span spinner-text d-none">
                                                                    <span class="spinner-border spinner-border-sm"
                                                                        role="status" aria-hidden="true"></span>
                                                                </div>
                                                            </button>
                                                        @endif
                                                        @if (auth()->user()->hasRole('super admin') || auth()->user()->can('monitoring delete pengajuan absensi'))
                                                            <button type="button" data-backdrop="static"
                                                                data-keyboard="false" data-aksi="attendance"
                                                                class="badge mx-1 badge-danger p-2 border-0 text-white btn-hapus"
                                                                data-id="{{ $row->id }}" title="Hapus"
                                                                onclick="btnDelete(event)">
                                                                <span class="fal fa-trash ikon-hapus"></span>
                                                                <div class="span spinner-text d-none">
                                                                    <span class="spinner-border spinner-border-sm"
                                                                        role="status" aria-hidden="true"></span>
                                                                </div>
                                                            </button>
                                                        @endif
                                                    </td>
                                                @endif

                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th style="white-space: nowrap">No</th>
                                        <th style="white-space: nowrap">Nama</th>
                                        <th style="white-space: nowrap">Tanggal</th>
                                        <th style="white-space: nowrap">Clock In</th>
                                        <th style="white-space: nowrap">Clock Out</th>
                                        <th style="white-space: nowrap">Status</th>
                                        @if (auth()->user()->hasRole('super admin') ||
                                                auth()->user()->can(['monitoring edit pengajuan absensi', 'monitoring delete pengajuan absensi']))
                                            <th style="white-space: nowrap">Action</th>
                                        @endif
                                    </tr>
                                </tfoot>
                            </table>
                            <!-- datatable end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('pages.monitoring.daftar-pengajuan.partials.edit-attendance')
        @include('pages.monitoring.daftar-pengajuan.partials.edit-day-off')
        @include('pages.monitoring.daftar-pengajuan.partials.tambah-day-off')

    </main>
@endsection
@section('plugin')
    <script src="/js/dependency/moment/moment.js"></script>
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/formplugins/bootstrap-daterangepicker/bootstrap-daterangepicker.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>

    <script>
        let dataId = null;

        function previewImageAbsen() {
            const image = document.querySelector('#update-form-attendance #file');
            const imgPreview = document.querySelector('#update-form-attendance .img-preview')

            imgPreview.style.display = 'block';

            const oFReader = new FileReader();
            oFReader.readAsDataURL(image.files[0])

            oFReader.onload = function(oFREvent) {
                imgPreview.src = oFREvent.target.result;
            }
        }

        function previewImageDayOff() {
            const image = document.querySelector('#update-day-off-req-form #photo');
            const imgPreview = document.querySelector('#update-day-off-req-form .img-preview')

            imgPreview.style.display = 'block';

            const oFReader = new FileReader();
            oFReader.readAsDataURL(image.files[0])

            oFReader.onload = function(oFREvent) {
                imgPreview.src = oFREvent.target.result;
            }
        }

        function previewImageDayOffTambah() {
            const image = document.querySelector('#update-day-off-req-form #photo_tambah');
            const imgPreview = document.querySelector('#update-day-off-req-form .img-preview')

            imgPreview.style.display = 'block';

            const oFReader = new FileReader();
            oFReader.readAsDataURL(image.files[0])

            oFReader.onload = function(oFREvent) {
                imgPreview.src = oFREvent.target.result;
            }
        }

        function btnEdit(event) {
            let button = event.currentTarget;
            let id = button.getAttribute('data-id');
            dataId = id;
            let employee_id = button.getAttribute('data-employee-id');
            let name = button.getAttribute('data-name');
            let aksi = button.getAttribute('data-aksi');
            let ikonEdit = button.querySelector('.ikon-edit');
            let spinnerText = button.querySelector('.spinner-text');
            ikonEdit.classList.add('d-none');
            spinnerText.classList.remove('d-none');

            if (aksi == "dayoff") {
                $.ajax({
                    type: "GET", // Method pengiriman data bisa dengan GET atau POST
                    url: `/api/dashboard/requests/day-off/${id}`, // Isi dengan url/path file php yang dituju
                    dataType: "json",
                    success: function(data) {
                        // Di sini Anda mendapatkan data dari AJAX response
                        let startDate = new Date(data.start_date);
                        let endDate = new Date(data.end_date);
                        ikonEdit.classList.remove('d-none');
                        ikonEdit.classList.add('d-block');
                        spinnerText.classList.add('d-none');
                        $('#edit-pengajuan-libur').modal('show');
                        $('#update-day-off-req-form #name').val(name)
                        $('#update-day-off-req-form #attendance_code_id').val(data
                                .attendance_code_id)
                            .select2({
                                dropdownParent: $('#edit-pengajuan-libur')
                            });
                        $('#update-day-off-req-form #is_approved').val(data.is_approved)
                            .select2({
                                dropdownParent: $('#edit-pengajuan-libur')
                            });
                        $('#update-day-off-req-form #date').daterangepicker({
                            opens: 'left',
                            startDate: startDate,
                            endDate: endDate
                        }, function(start, end, label) {
                            console.log("A new date selection was made: " + start.format(
                                    'YYYY-MM-DD') +
                                ' to ' + end
                                .format('YYYY-MM-DD'));
                        });
                        $('#update-day-off-req-form #keterangan').val(data.description);
                        if (data.photo != null) {
                            $('#update-day-off-req-form .img-preview').attr('src',
                                '/storage/img/pengajuan/cuti/' + data.photo)
                        }
                    },
                    error: function(xhr) {
                        showErrorAlert(xhr.responseText);
                    }
                });
            } else if (aksi == "attendance") {
                $.ajax({
                    type: "GET", // Method pengiriman data bisa dengan GET atau POST
                    url: `/api/dashboard/requests/attendance/${id}`, // Isi dengan url/path file php yang dituju
                    dataType: "json",
                    success: function(data) {
                        ikonEdit.classList.remove('d-none');
                        ikonEdit.classList.add('d-block');
                        spinnerText.classList.add('d-none');
                        $('#edit-pengajuan-absen').modal('show');
                        $('#edit-pengajuan-absen #date').datepicker({
                            todayBtn: "linked",
                            clearBtn: false,
                            todayHighlight: true,
                            setDate: data.date,
                            format: "yyyy-mm-dd",
                        });
                        $('#edit-pengajuan-absen #date').datepicker('update', data.date);
                        var clockin = document.getElementById("check-clockin");
                        var clockout = document.getElementById("check-clockout");
                        var inputClockIn = document.getElementById("clockin");
                        var inputClockOut = document.getElementById("clockout");

                        $('#clockin').val(data.clockin);
                        $('#clockout').val(data.clockout);
                        $('#description').val(data.description);
                        $('#check-clockin').prop('checked', true);
                        $('#check-clockout').prop('checked', true);
                        inputClockIn.disabled = false;
                        inputClockOut.disabled = false;
                        // Mengecek status awal clockin
                        if (data.clockin == null) {
                            $('#check-clockin').prop('checked', false);
                            inputClockIn.disabled = true;
                        }
                        if (data.clockout == null) {
                            inputClockOut.disabled = true;
                            $('#check-clockout').prop('checked', false);
                        }


                        // Menambahkan event listener untuk perubahan pada clockin
                        clockin.addEventListener("change", function() {
                            if (!clockin.checked) {
                                $('#clockin').val("");
                                inputClockIn.disabled = true;
                            } else {
                                inputClockIn.disabled = false;
                            }
                        });

                        clockout.addEventListener("change", function() {
                            if (!clockout.checked) {
                                $('#clockout').val("");
                                inputClockOut.disabled = true;
                            } else {
                                inputClockOut.disabled = false;
                            }
                        });
                        $('#update-form-attendance #is_approved_attendance').val(data.is_approved)
                            .select2({
                                dropdownParent: $('#edit-pengajuan-absen'),
                            });
                        // $('#ubah-data #name').val(data.name);
                        if (data.file != null) {
                            $('#update-form-attendance .img-preview').attr('src',
                                '/storage/img/pengajuan/absensi/' + data.file)
                        }
                    },
                    error: function(xhr) {
                        showErrorAlert(xhr.responseText);
                    }
                });
            }

        }

        function btnDelete(event) {
            let button = event.currentTarget;
            alert('Yakin ingin menghapus ini ?');
            let id = button.getAttribute('data-id');
            let aksi = button.getAttribute('data-aksi');
            let ikonHapus = button.querySelector('.ikon-hapus');
            let spinnerText = button.querySelector('.spinner-text');
            if (aksi == "day-off") {
                $.ajax({
                    type: "GET",
                    url: '/api/dashboard/requests/day-off/delete/' + id,
                    beforeSend: function() {
                        ikonHapus.classList.add('d-none');
                        spinnerText.classList.remove('d-none');
                        // button.find('.ikon-hapus').hide();
                        // button.find('.spinner-text').removeClass(
                        //     'd-none');
                    },
                    success: function(response) {
                        ikonHapus.classList.remove('d-none');
                        ikonHapus.classList.add('d-block');
                        spinnerText.classList.add('d-none');
                        showSuccessAlert(response.message)
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            }
            if (aksi == "attendance") {
                $.ajax({
                    type: "GET",
                    url: '/api/dashboard/requests/attendance/delete/' + id,
                    beforeSend: function() {
                        ikonHapus.classList.add('d-none');
                        spinnerText.classList.remove('d-none');
                        // button.find('.ikon-hapus').hide();
                        // button.find('.spinner-text').removeClass(
                        //     'd-none');
                    },
                    success: function(response) {
                        ikonHapus.classList.remove('d-none');
                        ikonHapus.classList.add('d-block');
                        spinnerText.classList.add('d-none');
                        showSuccessAlert(response.message)
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            }
        }

        $(document).ready(function() {

            $('#update-day-off-req-form').on('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                formData.append("employee_id", "{{ auth()->user()->employee->id }}");
                $.ajax({
                    type: "POST",
                    url: '/api/dashboard/requests/day-off/update/' + dataId,
                    processData: false,
                    contentType: false,
                    data: formData,
                    beforeSend: function() {
                        $('#update-day-off-req-form').find('.ikon-tambah').hide();
                        $('#update-day-off-req-form').find('.spinner-text').removeClass(
                            'd-none');
                    },
                    success: function(response) {
                        $('#edit-pengajuan-libur').modal('hide');
                        showSuccessAlert(response.message);
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    },
                    error: function(xhr) {
                        showErrorAlert(xhr.responseText);
                    }
                });
            });

            $('#update-form-attendance').on('submit', function(e) {
                e.preventDefault();

                // Buat objek FormData
                const formData = new FormData(this);
                formData.append("employee_id", "{{ auth()->user()->employee->id }}");

                // Ambil nilai checkbox
                const clockinChecked = $('#check-clockin').prop('checked');
                const clockoutChecked = $('#check-clockout').prop('checked');

                // Jika checkbox dicentang, tambahkan nilainya ke formData
                if (clockinChecked) {
                    formData.append('clockin', $('#clockin').val());
                }
                if (clockoutChecked) {
                    formData.append('clockout', $('#clockout').val());
                }

                // Kirim permintaan AJAX
                $.ajax({
                    type: "POST",
                    url: '/api/dashboard/requests/attendance/update/' + dataId,
                    data: formData,
                    processData: false, // Set false agar jQuery tidak memproses FormData secara otomatis
                    contentType: false, // Set false agar jQuery tidak mengatur tipe konten secara otomatis
                    beforeSend: function() {
                        $('#update-form-attendance').find('.ikon-tambah').hide();
                        $('#update-form-attendance').find('.spinner-text').removeClass(
                            'd-none');
                    },
                    success: function(response) {
                        $('#edit-pengajuan-absen').modal('hide');
                        showSuccessAlert(response.message);
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    },
                    error: function(xhr) {
                        showErrorAlert(xhr.responseText);
                    }
                });
            });

            $('#tambah_pengajuan_cuti').click(function() {
                console.log('clicked');
                $('#tambah-pengajuan-cuti-modal').modal('show');
                $('#attendance_code_id_tambah').select2({
                    dropdownParent: $('#tambah-pengajuan-cuti-modal')
                });
                $('#employee_id_tambah').select2({
                    dropdownParent: $('#tambah-pengajuan-cuti-modal')
                });
                $('#is_approved_tambah').select2({
                    dropdownParent: $('#tambah-pengajuan-cuti-modal')
                });

                $('#date_tambah').daterangepicker({
                    opens: 'left',
                }, function(start, end, label) {
                    console.log("A new date selection was made: " + start.format('YYYY-MM-DD') +
                        ' to ' + end
                        .format('YYYY-MM-DD'));
                });
            });

            $('#tambah-day-off-req-form').on('submit', function(e) {

                e.preventDefault();
                if ($('#attendance_code_id_tambah').val() == 3 && totalCT < 1) {
                    $('#tambah-pengajuan-cuti-modal').modal('hide');
                    showErrorAlert('Jatah Cuti Tahunan sudah habis!');
                } else if ($('#attendance_code_id_tambah').val() == 7 && totalCM < 1) {
                    $('#tambah-pengajuan-cuti-modal').modal('hide');
                    showErrorAlert('Jatah Cuti Menikah sudah habis!');
                } else if ($('#attendance_code_id_tambah').val() == 8 && totalCMA < 1) {
                    $('#tambah-pengajuan-cuti-modal').modal('hide');
                    showErrorAlert('Jatah Cuti Menikahkan Anak sudah habis!');
                } else if ($('#attendance_code_id_tambah').val() == 9 && totalCKA < 1) {
                    $('#tambah-pengajuan-cuti-modal').modal('hide');
                    showErrorAlert('Jatah Cuti Khitanan Anak sudah habis!');
                } else if ($('#attendance_code_id_tambah').val() == 10 && totalCIM < 1) {
                    $('#tambah-pengajuan-cuti-modal').modal('hide');
                    showErrorAlert('Jatah Cuti Izin Istri Melahirkan sudah habis!');
                } else if ($('#attendance_code_id_tambah').val() == 12 && totalCK < 1) {
                    $('#tambah-pengajuan-cuti-modal').modal('hide');
                    showErrorAlert('Jatah Cuti Keguguran sudah habis!');
                } else if ($('#attendance_code_id_tambah').val() == 13 && totalCKM < 1) {
                    $('#tambah-pengajuan-cuti-modal').modal('hide');
                    showErrorAlert('Jatah Cuti Keluarga Meninggal sudah habis!');
                } else if ($('#attendance_code_id_tambah').val() == 14 && totalCRM < 1) {
                    $('#tambah-pengajuan-cuti-modal').modal('hide');
                    showErrorAlert('Jatah Cuti Keluarga Meninggal se-Rumah sudah habis!');
                } else if ($('#attendance_code_id_tambah').val() == 15 && totalCRM < 1) {
                    $('#tambah-pengajuan-cuti-modal').modal('hide');
                    showErrorAlert('Jatah Cuti Melahirkan sudah habis!');
                } else {
                    e.preventDefault();
                    let formData = new FormData(this);

                    $.ajax({
                        type: "POST",
                        url: '/attendances/request/day-off',
                        data: formData,
                        processData: false,
                        contentType: false,
                        beforeSend: function() {
                            $('#tambah-day-off-req-form').find('.ikon-tambah').hide();
                            $('#tambah-day-off-req-form').find('.spinner-text').removeClass(
                                'd-none');
                        },
                        success: function(response) {
                            $('#tambah-day-off-req-form').find('.ikon-tambah').show();
                            $('#tambah-day-off-req-form').find('.spinner-text').addClass(
                                'd-none');
                            $('#tambah-pengajuan-cuti-modal').modal('hide');
                            showSuccessAlert(response.message)
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        },
                        error: function(xhr) {
                            $('#tambah-day-off-req-form').find('.ikon-tambah').show();
                            $('#tambah-day-off-req-form').find('.spinner-text').addClass(
                                'd-none');
                            $('#tambah-pengajuan-cuti-modal').modal('hide');
                            let errorMessage =
                                "Terjadi kesalahan saat menyimpan data."; // Pesan default
                            if (xhr.responseJSON && xhr.responseJSON.error) {
                                errorMessage = xhr.responseJSON.error;
                            }
                            showErrorAlert(errorMessage);
                        }
                    });
                }

            });

            $('.table').dataTable({
                "pageLength": 5,
                responsive: true,
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'B>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                        extend: 'excelHtml5',
                        text: 'Excel',
                        title: 'Daftar Pengajuan ' + new Date().toLocaleString('default', {
                            month: 'long',
                        }) + ' ' + new Date().getFullYear(),
                        titleAttr: 'Export to Excel',
                        className: 'btn-outline-default',
                        exportOptions: {
                            columns: ':visible:not(:last-child):not(:nth-last-child(2))', // Mengabaikan kolom "Action" dan "Status"
                            format: {
                                body: function(data, row, column, node) {
                                    // Menghapus tag HTML dari data sebelum mengekspor ke Excel
                                    return $('<div/>').html(data).text();
                                }
                            }
                        },
                        customize: function(xlsx) {
                            var sheet = xlsx.xl.worksheets['sheet1.xml'];
                            $('row:first c', sheet).attr('style',
                                'text-align: center;'); // Mengatur gaya untuk heading
                            $('row:nth-child(2) c', sheet).attr('s', '43');
                            $('row:nth-child(2) c', sheet).attr('class', 'style43');
                        }
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        titleAttr: 'Print Table',
                        className: 'btn-outline-default'
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
    </script>
@endsection
