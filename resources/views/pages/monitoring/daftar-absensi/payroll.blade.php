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
                        <div class="panel-content">

                            {{-- Ontime --}}
                            {{-- <button type="button" class="btn btn-primary my-3" data-toggle="modal"
                                data-target="#filterModal" id="btnFilter">
                                <span class="fal fa-clock ikon-ontime-all"></span>
                                Ontime All
                                <div class="span spinner-text d-none">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                </div>
                            </button> --}}

                            <div class="table-responsive">
                                <!-- datatable start -->
                                <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
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
                                                        class="badge {{ $row->shift->name == 'dayoff' || $row->shift->name == 'National Holiday' ? 'badge-danger' : 'badge-secondary' }} badge-pill">
                                                        {{ $row->shift->name }}
                                                    </span>
                                                </td>
                                                <td style="white-space: nowrap">
                                                    {{ $row->shift->time_in }}
                                                </td>
                                                <td style="white-space: nowrap">
                                                    {{ $row->shift->time_out }}
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
                                                            class="badge mx-1 badge-success p-2 border-0 text-white btn-ontime"
                                                            data-id="{{ $row->id }}" title="On time">
                                                            <span class="fal fa-clock ikon-ontime"></span>
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
@endsection
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/statistics/chartist/chartist.js"></script>
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
