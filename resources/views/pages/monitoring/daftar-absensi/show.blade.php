@extends('inc.layout')
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
                                            @if (auth()->user()->hasRole('super admin') || auth()->user()->can('monitoring edit absensi'))
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
                                                        class="badge {{ isset($row->shift) ? ($row->shift->name == 'dayoff' || $row->shift->name == 'National Holiday' ? 'badge-danger' : 'badge-secondary') : '' }} badge-pill">
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
                                                @if (auth()->user()->hasRole('super admin') || auth()->user()->can('monitoring edit absensi'))
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
                                            @if (auth()->user()->hasRole('super admin') || auth()->user()->can('monitoring edit absensi'))
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
        @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('hr'))
            @include('pages.monitoring.daftar-absensi.partials.edit')
        @endif

    </main>
@endsection
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/statistics/chartist/chartist.js"></script>
    {{-- <script>

        /* stacked bar */
        let data = @json($attendancesAllMonths);
        // console.log(data)
        let result = [];
        Object.values(data).forEach(monthData => {
            let monthArray = [];
            Object.values(monthData).forEach(value => {
                monthArray.push(value);
            });
            result.push(monthArray);
        });
        let formattedResult = [];

        for (let i = 0; i < result[0].length; i++) {
            let columnValues = [];
            for (let j = 0; j < result.length; j++) {
                columnValues.push(result[j][i]);
            }
            formattedResult.push(columnValues);
        }
        var stackedBar = function() {
            new Chartist.Bar('#stackedBar', {
                labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September',
                    'Oktober', 'November', 'Desember'
                ],
                series: formattedResult
            }, {
                stackBars: true,
                axisY: {
                    labelInterpolationFnc: function(value) {
                        return (value);
                    }
                }
            }).on('draw', function(data) {
                if (data.type === 'bar') {
                    data.element.attr({
                        style: 'stroke-width: 30px'
                    });
                }
            });
        }
        /* stacked bar -- end */
    </script> --}}

    <script>
        /* demo scripts for change table color */
        /* change background */
        $(document).ready(function() {
            // stackedBar();
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
            // $('#store-form').on('submit', function(e) {
            //     e.preventDefault();
            //     let formData = $(this).serialize();
            //     $.ajax({
            //         type: "POST",
            //         url: '/api/dashboard/banks/store/',
            //         data: formData,
            //         beforeSend: function() {
            //             $('#store-form').find('.ikon-tambah').hide();
            //             $('#store-form').find('.spinner-text').removeClass(
            //                 'd-none');
            //         },
            //         success: function(response) {
            //             $('#store-form').find('.ikon-edit').show();
            //             $('#store-form').find('.spinner-text').addClass('d-none');
            //             $('#tambah-data').modal('hide');
            //             showSuccessAlert(response.message)
            //             setTimeout(function() {
            //                 location.reload();
            //             }, 500);
            //         },
            //         error: function(xhr) {
            //             console.log(xhr.responseText);
            //         }
            //     });
            // });

            // $('.btn-hapus').click(function(e) {
            //     e.preventDefault();
            //     let button = $(this);
            //     alert('Yakin ingin menghapus ini ?');
            //     let id = button.attr('data-id');
            //     $.ajax({
            //         type: "GET",
            //         url: '/api/dashboard/banks/delete/' + id,
            //         beforeSend: function() {
            //             button.find('.ikon-hapus').hide();
            //             button.find('.spinner-text').removeClass(
            //                 'd-none');
            //         },
            //         success: function(response) {
            //             button.find('.ikon-edit').show();
            //             button.find('.spinner-text').addClass('d-none');
            //             showSuccessAlert(response.message)
            //             setTimeout(function() {
            //                 location.reload();
            //             }, 500);
            //         },
            //         error: function(xhr) {
            //             console.log(xhr.responseText);
            //         }
            //     });
            // });

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
