@extends('inc.layout-no-side')
@section('title', 'Monitoring Dashboard')
@section('extended-css')
    <style>
        @media screen and (min-width: 680px) {

            #dt-basic-example thead th:first-child,
            #dt-basic-example tbody td:first-child,
            #dt-basic-example tfoot th:first-child {
                position: sticky;
                left: 0;
                z-index: 2;
                /* Ditingkatkan agar kolom "Action" tetap di atas kolom lainnya */
                background-color: #f9f9f9;
            }

            #dt-basic-example thead th:nth-child(2),
            #dt-basic-example tbody td:nth-child(2),
            #dt-basic-example tfoot th:nth-child(2) {
                position: sticky;
                left: 62px;
                /* Sesuaikan dengan lebar kolom pertama */
                z-index: 2;
                /* Ditingkatkan agar kolom "Action" tetap di atas kolom lainnya */
                background-color: #f9f9f9;
            }


            #dt-basic-example thead th:last-child,
            #dt-basic-example tbody td:last-child,
            #dt-basic-example tfoot th:last-child {
                position: sticky;
                right: 0;
                z-index: 2;
                /* Ditingkatkan agar kolom "Action" tetap di atas kolom lainnya */
                background-color: #f9f9f9;
            }
        }

        a.link_nama:hover {
            text-decoration: underline !important;
        }
    </style>
@endsection
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="row mt-2">
            <div class="col-xl-12">
                <button class="btn btn-primary mb-3" onclick="window.close()">
                    <i class='bx bx-x-circle'></i>Close
                </button>
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Filter
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form action="" method="get" class="mx-5">
                                @method('POST')
                                @csrf
                                <div id="step-1">
                                    <div class="form-group mb-3">
                                        <label for="periode">Periode</label>
                                        <!-- Mengubah input menjadi select2 -->
                                        <select class="select2 form-control @error('periode') is-invalid @enderror"
                                            name="periode" id="periode">
                                            @php
                                                $currentYear = date('Y');
                                                $nextYear = $currentYear + 1;
                                                $months = [
                                                    'January',
                                                    'February',
                                                    'March',
                                                    'April',
                                                    'May',
                                                    'June',
                                                    'July',
                                                    'August',
                                                    'September',
                                                    'October',
                                                    'November',
                                                    'December',
                                                ];
                                                $lastSearchPeriod = $request->periode ?? ''; // Mendapatkan periode terakhir yang dicari

                                                foreach ($months as $index => $month) {
                                                    $nextIndex = ($index + 1) % 12; // Menyesuaikan indeks bulan berikutnya
                                                    $nextMonth = $months[$nextIndex];
                                                    $year = $index < 11 ? $currentYear : $nextYear; // Menentukan tahun

                                                    $period = "{$month} {$currentYear} - {$nextMonth} {$year}";
                                                    $selected = $period == $lastSearchPeriod ? 'selected' : ''; // Menandai opsi yang sesuai

                                                    echo "<option value=\"{$period}\" {$selected}>{$period}</option>";
                                                }
                                            @endphp
                                        </select>
                                        @error('periode')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <!-- Hanya menampilkan data payroll ketika periode telah diisi -->
                                    <div class="btn-next mt-3 text-right">
                                        <button type="submit" class="btn-next-step btn btn-primary btn-sm ml-2">
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
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Daftar Absen {{ $attendances[0]->employees->fullname ?? 'No Name~' }}
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="row panel-content">
                            <div class="col-xl-12 mb-3">
                                <button id="tambah_pengajuan_cuti" class="btn btn-primary">Tambah Pengajuan Cuti/Izin
                                    Sakit</button>
                            </div>
                            <div class="col-xl-12">
                                <div class="table-responsive">
                                    <!-- datatable start -->
                                    <table id="dt-basic-example"
                                        class="table table-bordered table-hover table-striped w-100">
                                        <thead>
                                            <tr>
                                                <th style="white-space: nowrap">No</th>
                                                <th style="white-space: nowrap">Tanggal</th>
                                                <th style="white-space: nowrap">Shift</th>
                                                <th style="white-space: nowrap">Time In</th>
                                                <th style="white-space: nowrap">Time Out</th>
                                                <th style="white-space: nowrap">Clock In</th>
                                                <th style="white-space: nowrap">Clock Out</th>
                                                <th style="white-space: nowrap">Late Clock In</th>
                                                <th style="white-space: nowrap">Early Clock Out</th>
                                                <th style="white-space: nowrap">Libur</th>
                                                <th style="white-space: nowrap">Keterangan</th>
                                                @if (auth()->user()->hasRole('super admin') || auth()->user()->hasRole('hr'))
                                                    <th style="white-space: nowrap">Action</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($attendances as $row)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td style="white-space: nowrap">
                                                        {{ \Carbon\Carbon::parse($row->date)->translatedFormat('D, j M Y') }}
                                                    </td>
                                                    <td style="white-space: nowrap">
                                                        <span
                                                            class="badge {{ $row->shift && ($row->shift->name == 'dayoff' || $row->shift->name == 'National Holiday') ? 'badge-danger' : 'badge-secondary' }} badge-pill">
                                                            {{ $row->shift->name ?? '-' }}
                                                        </span>
                                                    </td>
                                                    <td style="white-space: nowrap">
                                                        {{ $row->shift->time_in ?? '-' }}
                                                    </td>
                                                    <td style="white-space: nowrap">
                                                        {{ $row->shift->time_out ?? '-' }}
                                                    </td>
                                                    <td style="white-space: nowrap"
                                                        class="{{ $row->clock_in && $row->late_clock_in ? 'text-danger' : '' }}"
                                                        style="vertical-align: middle;">
                                                        @isset($row->clock_in)
                                                            {{ \Carbon\Carbon::parse($row->clock_in)->format('H:i') }}
                                                        @else
                                                            -
                                                        @endisset
                                                    </td>
                                                    <td style="white-space: nowrap"
                                                        class="{{ $row->clock_out && $row->early_clock_out ? 'text-danger' : '' }}"
                                                        style="vertical-align: middle;">
                                                        @isset($row->clock_out)
                                                            {{ \Carbon\Carbon::parse($row->clock_out)->format('H:i') }}
                                                        @else
                                                            -
                                                        @endisset
                                                    </td>
                                                    <td style="white-space: nowrap"
                                                        class="{{ $row->clock_in && $row->late_clock_in ? 'text-danger' : '' }}"
                                                        style="vertical-align: middle;">
                                                        @isset($row->late_clock_in)
                                                            {{ $row->late_clock_in }} Menit
                                                        @else
                                                            -
                                                        @endisset
                                                    </td>
                                                    <td style="white-space: nowrap"
                                                        class="{{ $row->clock_out && $row->early_clock_out ? 'text-danger' : '' }}"
                                                        style="vertical-align: middle;">
                                                        @isset($row->late_clock_in)
                                                            {{ $row->early_clock_out }} Menit
                                                        @else
                                                            -
                                                        @endisset
                                                    </td>
                                                    <td style="white-space: nowrap">
                                                        {{ $row->is_day_off == 1 ? 'Ya' : '-' }}
                                                    </td>
                                                    <td style="white-space: nowrap">
                                                        @isset($row->day_off)
                                                            {{ $row->day_off->attendance_code->description }}
                                                        @else
                                                            -
                                                        @endisset
                                                    </td>
                                                    @if (auth()->user()->hasRole('super admin') || auth()->user()->hasRole('hr'))
                                                        <td>
                                                            <a href="#" data-backdrop="static" data-keyboard="false"
                                                                class="badge mx-1 badge-success p-2 border-0 text-white btn-edit"
                                                                data-id="{{ $row->id }}" title="Edit Absensi">
                                                                <span class="fal fa-pencil ikon-edit"></span>
                                                                <div class="span spinner-text d-none">
                                                                    <span class="spinner-border spinner-border-sm"
                                                                        role="status" aria-hidden="true"></span>
                                                                    Loading...
                                                                </div>
                                                            </a>

                                                            <a href="javascript:void(0)" data-backdrop="static"
                                                                data-keyboard="false"
                                                                class="badge mx-1 badge-primary p-2 border-0 text-white btn-ontime"
                                                                data-id="{{ $row->id }}" title="On time">
                                                                <span class="fas fa-clock ikon-ontime"></span>
                                                                <div class="span spinner-text d-none">
                                                                    <span class="spinner-border spinner-border-sm"
                                                                        role="status" aria-hidden="true"></span>
                                                                </div>
                                                            </a>

                                                            <a href="javascript:void(0)" data-backdrop="static"
                                                                data-keyboard="false"
                                                                class="badge mx-1 badge-danger p-2 border-0 text-white btn-alfa"
                                                                data-id="{{ $row->id }}" title="Alfa">
                                                                <span class="fas fa-minus-circle ikon-alfa"></span>
                                                                <div class="span spinner-text d-none">
                                                                    <span class="spinner-border spinner-border-sm"
                                                                        role="status" aria-hidden="true"></span>
                                                                </div>
                                                            </a>
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th style="white-space: nowrap">No</th>
                                                <th style="white-space: nowrap">Tanggal</th>
                                                <th style="white-space: nowrap">Shift</th>
                                                <th style="white-space: nowrap">Time In</th>
                                                <th style="white-space: nowrap">Time Out</th>
                                                <th style="white-space: nowrap">Clock In</th>
                                                <th style="white-space: nowrap">Clock Out</th>
                                                <th style="white-space: nowrap">Late Clock In</th>
                                                <th style="white-space: nowrap">Early Clock Out</th>
                                                <th style="white-space: nowrap">Libur</th>
                                                <th style="white-space: nowrap">Keterangan</th>
                                                @if (auth()->user()->hasRole('super admin') || auth()->user()->hasRole('hr'))
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
            </div>
        </div>
        @if (auth()->user()->hasRole('super admin') || auth()->user()->hasRole('hr'))
            @include('pages.monitoring.daftar-absensi.partials.edit')
        @endif

        <div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="filterModalLabel">Filter Periode</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Form filter periode -->
                        <form action="{{ route('attendance.ontimeAll') }}" method="get">
                            @method('POST')
                            @csrf
                            <div class="form-group">
                                <label for="periode">Periode</label>
                                <select class="select2 form-control @error('periode') is-invalid @enderror" name="periode"
                                    id="periode-modal">
                                    @php
                                        $currentYear = date('Y');
                                        $nextYear = $currentYear + 1;
                                        $months = [
                                            'January',
                                            'February',
                                            'March',
                                            'April',
                                            'May',
                                            'June',
                                            'July',
                                            'August',
                                            'September',
                                            'October',
                                            'November',
                                            'December',
                                        ];
                                        $lastSearchPeriod = $request->periode ?? ''; // Mendapatkan periode terakhir yang dicari

                                        foreach ($months as $index => $month) {
                                            $nextIndex = ($index + 1) % 12; // Menyesuaikan indeks bulan berikutnya
                                            $nextMonth = $months[$nextIndex];
                                            $year = $index < 11 ? $currentYear : $nextYear; // Menentukan tahun

                                            $period = "{$month} {$currentYear} - {$nextMonth} {$year}";
                                            $selected = $period == $lastSearchPeriod ? 'selected' : ''; // Menandai opsi yang sesuai

                                            echo "<option value=\"{$period}\" {$selected}>{$period}</option>";
                                        }
                                    @endphp
                                </select>
                                @error('periode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary" id="filterSubmit">Cari</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
    @include('pages.monitoring.daftar-absensi.partials.tambah-day-off')
@endsection
@section('plugin')
    <script src="/js/dependency/moment/moment.js"></script>
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/statistics/chartist/chartist.js"></script>
    <script src="/js/formplugins/bootstrap-daterangepicker/bootstrap-daterangepicker.js"></script>
    <script src="/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>
    <script>
        document.getElementById('filterSubmit').addEventListener('click', function(event) {
            event.preventDefault(); // Menghentikan perilaku default pengiriman formulir
            $('#filterModal').modal('hide');

            // Tampilkan SweetAlert2 confirmation dialog
            Swal.fire({
                title: 'Anda Yakin Akan Merubah Data?',
                text: 'Data akan disubmit untuk diproses.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Lanjutkan!',
                cancelButtonText: 'Tidak, Batalkan!',
            }).then((result) => {
                // Jika pengguna mengonfirmasi
                if (result.isConfirmed) {
                    // Ambil formulir
                    var form = document.querySelector('#filterModal form');
                    // Kirim formulir
                    form.submit();
                }
            });
        });
        /* demo scripts for change table color */
        /* change background */
        $(document).ready(function() {
            // stackedBar();
            $('#btnFilter').click(function(e) {
                $('#filterModal').modal('show');
                $('#filterModal #periode-modal').select2({
                    dropdownParent: $('#filterModal'),
                    placeholder: "Pilih Periode"
                });
            });

            $('#tambah_pengajuan_cuti').click(function() {
                const employee_id = "{{ $employees[0]->id }}";
                $('#tambah-pengajuan-cuti-modal').modal('show');
                $('#employee_id_tambah').val(employee_id).select2({
                    dropdownParent: $('#tambah-pengajuan-cuti-modal')
                });
                $('#attendance_code_id_tambah').val(2).select2({
                    dropdownParent: $('#tambah-pengajuan-cuti-modal')
                });
                $('#employee_id_tambah').select2({
                    dropdownParent: $('#tambah-pengajuan-cuti-modal')
                });
                $('#is_approved_tambah').val('Disetujui').select2({
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

            $('.btn-edit').click(function(e) {
                e.preventDefault();
                let button = $(this);
                let id = button.attr('data-id');
                button.find('.ikon-edit').hide();
                button.find('.spinner-text').removeClass('d-none');

                $.ajax({
                    type: "GET", // Method pengiriman data bisa dengan GET atau POST
                    url: `/api/dashboard/attendances/${id}`, // Isi dengan url/path file php yang dituju

                    dataType: "json",
                    success: function(data) {
                        button.find('.ikon-edit').show();
                        button.find('.spinner-text').addClass('d-none');
                        $('#ubah-data').modal('show');
                        $('#update-form').attr('data-id', data.attendance.id);
                        $('#ubah-data #date').val(data.attendance.date);
                        $('#ubah-data #clock_in').val(data.attendance.clock_in);
                        $('#ubah-data #clock_out').val(data.attendance.clock_out);
                        $('#ubah-data #shift_id').val(data.attendance.shift_id).select2({
                            dropdownParent: $('#ubah-data')
                        });

                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });

                $('#update-form').on('submit', function(e) {
                    e.preventDefault();
                    let formData = $(this).serialize();
                    const id = $(this).attr('data-id');
                    $.ajax({
                        type: "PUT",
                        url: '/api/dashboard/attendances/update/' + id,
                        data: formData,
                        beforeSend: function() {
                            $('#update-form').find('.ikon-edit').hide();
                            $('#update-form').find('.spinner-text')
                                .removeClass(
                                    'd-none');
                        },
                        success: function(response) {
                            $('#ubah-data').modal('hide');
                            showSuccessAlert(response.message);
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        },
                        error: function(xhr) {
                            console.log(xhr.responseJSON.error);
                        }
                    });
                });
            });

            $(".btn-ontime-all").click(function(e) {
                e.preventDefault();
                let button = $(this);

                // Tampilkan loading spinner
                button.find('.ikon-ontime-all').hide();
                button.find('.spinner-text').removeClass('d-none');

                // Dapatkan filter dari form atau variabel lain
                let filter = {
                    periode: $('#periode').val()
                };

                $.ajax({
                    url: '{{ route('attendance.ontimeAll') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        periode: filter.periode
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message,
                            }).then(() => {
                                // Reload halaman atau tabel untuk memperbarui data
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.error,
                            });
                        }
                    },
                    error: function(response) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan, silakan coba lagi.',
                        });
                    },
                    complete: function() {
                        // Sembunyikan loading spinner
                        button.find('.spinner-text').addClass('d-none');
                        button.find('.ikon-ontime-all').show();
                    }
                });
            });

            $('.btn-ontime').click(function(e) {
                e.preventDefault();

                let button = $(this);
                let id = button.attr('data-id');

                button.find('.ikon-ontime').hide();
                button.find('.spinner-text').removeClass('d-none');

                $.ajax({
                    type: 'POST',
                    url: '/api/dashboard/attendances/update/' + id + '/ontime',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        button.find('.spinner-text').addClass('d-none');
                        button.find('.ikon-ontime').show();
                        showSuccessAlert(response.message);
                        setTimeout(function() {
                            location.reload();
                        }, 100);
                    },
                    error: function(xhr) {
                        console.log(xhr.responseJSON.error);
                    }
                });
            });

            $('.btn-alfa').click(function(e) {
                e.preventDefault();

                let button = $(this);
                let id = button.attr('data-id');

                button.find('.ikon-ontime').hide();
                button.find('.spinner-text').removeClass('d-none');

                $.ajax({
                    type: 'POST',
                    url: '/api/dashboard/attendances/update/' + id + '/alfa',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        button.find('.spinner-text').addClass('d-none');
                        button.find('.ikon-ontime').show();
                        showSuccessAlert(response.message);
                        setTimeout(function() {
                            location.reload();
                        }, 100);
                    },
                    error: function(xhr) {
                        console.log(xhr.responseJSON.error);
                    }
                });
            });

            $('#dt-basic-example').dataTable({
                "pageLength": 31
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

            $('.select2').select2({
                placeholder: 'Pilih Periode',
            }).val('{{ $lastSearchPeriod }}').trigger('change');

        });
    </script>
@endsection
