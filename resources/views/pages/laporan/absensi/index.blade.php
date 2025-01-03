@extends('inc.layout')
@section('title', 'Rekap Absensi')
@section('extended-css')
    <style>
        /* Gaya untuk heading */
        .style45 {
            font-size: 14px;
            /* Ukuran font */
            font-weight: bold;
            /* Ketebalan font */
            text-align: center;
            /* Pusatkan teks */
            color: white;
            /* Warna teks putih */
            background-color: #ef6800;
            /* Warna latar belakang orange */
            height: 27px;
            /* Tinggi baris */
        }

        /* Gaya untuk baris kedua */
        .style43 {
            background-color: #f0f0f0;
            /* Warna latar belakang abu-abu cerah */
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
                            <h5 class="font-weight-bold text-primary">Bulan
                                @if (\Carbon\Carbon::now()->day > 26)
                                    {{ \Carbon\Carbon::now()->addMonth()->translatedFormat('F Y') }}
                                @else
                                    {{ \Carbon\Carbon::parse($endDateReport)->translatedFormat('F Y') }}
                                @endif
                            </h5>
                            <span style="font-size: 1.1em">Semua laporan absensi bisa dilihat pada menu dibawah ini. </span>
                        </div>
                        <div class="col-xl-2 col-sm-2 mb-2">
                            <span class="title-sm d-inline-block mb-2 font-weight-bold text-primary">Total Pegawai</span>
                            <h1 style="font-size: 2em">{{ isset($employees) ? $employees->count() : '0' }}</h1>
                        </div>
                        <div class="col-xl-1 col-sm-1 mb-2">
                            <span class="title-sm d-inline-block mb-2 font-weight-bold text-primary">On Time</span>
                            <h1 style="font-size: 2em">
                                {{ isset($total_ontime_this_month) ? $total_ontime_this_month : '0' }}</h1>
                        </div>
                        <div class="col-xl-1 col-sm-1 mb-2">
                            <span class="title-sm d-inline-block mb-2 font-weight-bold text-primary">Late In</span>
                            <h1 style="font-size: 2em">
                                {{ isset($total_latein_this_month) ? $total_latein_this_month : '0' }}</h1>
                        </div>
                        <div class="col-xl-2 col-sm-2 mb-2">
                            <span class="title-sm d-inline-block mb-2 font-weight-bold text-primary">No Check In</span>
                            <h1 style="font-size: 2em">
                                {{ isset($total_nocheckin_this_month) ? $total_nocheckin_this_month : '0' }}</h1>
                        </div>
                        <div class="col-xl-1 col-sm-1 mb-2">
                            <span class="title-sm d-inline-block mb-2 font-weight-bold text-primary">Absent</span>
                            <h1 style="font-size: 2em">
                                {{ isset($total_absent_this_month) ? $total_absent_this_month : '0' }}</h1>
                        </div>
                        <div class="col-xl-1 col-sm-1 mb-2">
                            <span class="title-sm d-inline-block mb-2 font-weight-bold text-primary">Time Off</span>
                            <h1 style="font-size: 2em">
                                {{ isset($total_timeoff_this_month) ? $total_timeoff_this_month : '0' }}</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Filter
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form action="" method="GET">
                                @method('GET')
                                @csrf
                                <div class="row" id="step-1">
                                    <div class="col-md-10">
                                        <div class="form-group mb-3">
                                            <label for="year">Tahun</label>
                                            <!-- Mengubah input menjadi select2 -->
                                            <select class="select2 form-control @error('year') is-invalid @enderror"
                                                name="year" id="year">
                                                <option value="2024"
                                                    {{ isset($selectedTahun) && $selectedTahun == 2024 ? 'selected' : '' }}>
                                                    2024</option>
                                                <option value="2023"
                                                    {{ isset($selectedTahun) && $selectedTahun == 2023 ? 'selected' : '' }}>
                                                    2023</option>
                                                <option value="2025"
                                                    {{ isset($selectedTahun) && $selectedTahun == 2025 ? 'selected' : '' }}>
                                                    2025</option>
                                                <option value="2026"
                                                    {{ isset($selectedTahun) && $selectedTahun == 2026 ? 'selected' : '' }}>
                                                    2026</option>
                                                <option value="2027"
                                                    {{ isset($selectedTahun) && $selectedTahun == 2027 ? 'selected' : '' }}>
                                                    2027</option>
                                                <option value="2028"
                                                    {{ isset($selectedTahun) && $selectedTahun == 2028 ? 'selected' : '' }}>
                                                    2028</option>
                                                <option value="2029"
                                                    {{ isset($selectedTahun) && $selectedTahun == 2029 ? 'selected' : '' }}>
                                                    2029</option>
                                                <option value="2030"
                                                    {{ isset($selectedTahun) && $selectedTahun == 2030 ? 'selected' : '' }}>
                                                    2030</option>
                                            </select>
                                            @error('tahun')
                                                <div class="invalid-feedback">{{ $message }}</div>
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
        </div>
        <div class="row">
            <div class="col-xl-6">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Grafik Absensi Tahun {{ \Carbon\Carbon::now()->translatedFormat('Y') }}
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <!-- Stacked Bar -->
                            <div class="panel-tag">
                                <div class="row d-flex justify-content-center align-items-center">
                                    <div class="col-sm-3 col-md-3 my-1 d-flex align-items-center">
                                        <span class="d-inline-block ml-2 bg-danger"
                                            style="height: 15px; width: 25px"></span> <span
                                            class="ml-2 d-inline-block">Absent</span>
                                    </div>
                                    <div class="col-sm-3 col-md-3 my-1 d-flex align-items-center">
                                        <span class="d-inline-block ml-2 bg-info" style="height: 15px; width: 25px"></span>
                                        <span class="ml-2 d-inline-block"> On Time </span>
                                    </div>
                                    <div class="col-sm-3 col-md-3 my-1 d-flex align-items-center">
                                        <span class="d-inline-block ml-2 bg-warning"
                                            style="height: 15px; width: 25px"></span> <span class="ml-2 d-inline-block">
                                            Telat
                                        </span>
                                    </div>
                                    <div class="col-sm-3 col-md-3 my-1 d-flex align-items-center">
                                        <span class="d-inline-block ml-2 bg-success"
                                            style="height: 15px; width: 25px"></span> <span class="ml-2 d-inline-block">
                                            Time Off
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div id="barStacked">
                                <canvas style="width:100%; height:300px;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            On Time Rank
                        </h2>
                        {{-- <div class="panel-toolbar">
                            <button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip"
                                data-offset="0,10" data-original-title="Collapse"></button>
                            <button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip"
                                data-offset="0,10" data-original-title="Fullscreen"></button>
                            <button class="btn btn-panel" data-action="panel-close" data-toggle="tooltip" data-offset="0,10"
                                data-original-title="Close"></button>
                        </div> --}}
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <div class="panel-tag">
                                Top 5 Pegawai On Time Sepanjang Waktu
                            </div>
                            <div id="pieChart" style="width:100%; height:300px;"></div>
                            <div class="text-right mb-2">
                                {{-- <button id="pieChartUnload" onclick="pieChartUnload();"
                                    class="btn btn-sm btn-dark ml-auto">Unload Data</button> --}}
                            </div>
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
                            Filter
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form action="{{ route('attendances.reports.filter') }}" method="POST">
                                @method('POST')
                                @csrf
                                <div class="row" id="step-1">
                                    <div class="col-md-5">
                                        <div class="form-group mb-3">
                                            <label for="bulan">Bulan</label>
                                            <!-- Mengubah input menjadi select2 -->
                                            <select class="select2 form-control @error('bulan') is-invalid @enderror"
                                                name="bulan" id="bulan">
                                                <option value="1"
                                                    {{ isset($selectedBulan) && $selectedBulan == 1 ? 'selected' : '' }}>
                                                    Januari</option>
                                                <option value="2"
                                                    {{ isset($selectedBulan) && $selectedBulan == 2 ? 'selected' : '' }}>
                                                    Februari</option>
                                                <option value="3"
                                                    {{ isset($selectedBulan) && $selectedBulan == 3 ? 'selected' : '' }}>
                                                    Maret</option>
                                                <option value="4"
                                                    {{ isset($selectedBulan) && $selectedBulan == 4 ? 'selected' : '' }}>
                                                    April</option>
                                                <option value="5"
                                                    {{ isset($selectedBulan) && $selectedBulan == 5 ? 'selected' : '' }}>
                                                    Mei</option>
                                                <option value="6"
                                                    {{ isset($selectedBulan) && $selectedBulan == 6 ? 'selected' : '' }}>
                                                    Juni</option>
                                                <option value="7"
                                                    {{ isset($selectedBulan) && $selectedBulan == 7 ? 'selected' : '' }}>
                                                    Juli</option>
                                                <option value="8"
                                                    {{ isset($selectedBulan) && $selectedBulan == 8 ? 'selected' : '' }}>
                                                    Agustus</option>
                                                <option value="9"
                                                    {{ isset($selectedBulan) && $selectedBulan == 9 ? 'selected' : '' }}>
                                                    September</option>
                                                <option value="10"
                                                    {{ isset($selectedBulan) && $selectedBulan == 10 ? 'selected' : '' }}>
                                                    Oktober</option>
                                                <option value="11"
                                                    {{ isset($selectedBulan) && $selectedBulan == 11 ? 'selected' : '' }}>
                                                    November</option>
                                                <option value="12"
                                                    {{ isset($selectedBulan) && $selectedBulan == 12 ? 'selected' : '' }}>
                                                    Desember</option>
                                            </select>
                                            @error('bulan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group mb-3">
                                            <label for="tahun">Tahun</label>
                                            <!-- Mengubah input menjadi select2 -->
                                            <select class="select2 form-control @error('tahun') is-invalid @enderror"
                                                name="tahun" id="tahun">
                                                <option value="2024"
                                                    {{ isset($selectedTahun) && $selectedTahun == 2024 ? 'selected' : '' }}>
                                                    2024</option>
                                                <option value="2023"
                                                    {{ isset($selectedTahun) && $selectedTahun == 2023 ? 'selected' : '' }}>
                                                    2023</option>
                                                <option value="2025"
                                                    {{ isset($selectedTahun) && $selectedTahun == 2025 ? 'selected' : '' }}>
                                                    2025</option>
                                                <option value="2026"
                                                    {{ isset($selectedTahun) && $selectedTahun == 2026 ? 'selected' : '' }}>
                                                    2026</option>
                                                <option value="2027"
                                                    {{ isset($selectedTahun) && $selectedTahun == 2027 ? 'selected' : '' }}>
                                                    2027</option>
                                                <option value="2028"
                                                    {{ isset($selectedTahun) && $selectedTahun == 2028 ? 'selected' : '' }}>
                                                    2028</option>
                                                <option value="2029"
                                                    {{ isset($selectedTahun) && $selectedTahun == 2029 ? 'selected' : '' }}>
                                                    2029</option>
                                                <option value="2030"
                                                    {{ isset($selectedTahun) && $selectedTahun == 2030 ? 'selected' : '' }}>
                                                    2030</option>
                                            </select>
                                            @error('tahun')
                                                <div class="invalid-feedback">{{ $message }}</div>
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
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Grafik Absensi Per Kategori
                            @isset($selectedBulan)
                                {{ \Carbon\Carbon::createFromDate($selectedTahun, $selectedBulan, 1)->translatedFormat('F Y') }}
                            @else
                                @if (\Carbon\Carbon::now()->day > 26)
                                    {{ \Carbon\Carbon::now()->addMonth()->translatedFormat('F Y') }}
                                @else
                                    {{ \Carbon\Carbon::parse($endDateReport)->translatedFormat('F Y') }}
                                @endif
                            @endisset
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <div class="panel-tag">
                                <div class="row d-flex justify-content-center align-items-center">
                                    <div class="col-sm-3 col-md-3 my-1 d-flex align-items-center">
                                        <span class="d-inline-block ml-2 bg-danger"
                                            style="height: 15px; width: 25px"></span> <span
                                            class="ml-2 d-inline-block">Absent</span>
                                    </div>
                                    <div class="col-sm-3 col-md-3 my-1 d-flex align-items-center">
                                        <span class="d-inline-block ml-2 bg-info"
                                            style="height: 15px; width: 25px"></span>
                                        <span class="ml-2 d-inline-block"> On Time </span>
                                    </div>
                                    <div class="col-sm-3 col-md-3 my-1 d-flex align-items-center">
                                        <span class="d-inline-block ml-2 bg-warning"
                                            style="height: 15px; width: 25px"></span> <span class="ml-2 d-inline-block">
                                            Telat
                                        </span>
                                    </div>
                                    <div class="col-sm-3 col-md-3 my-1 d-flex align-items-center">
                                        <span class="d-inline-block ml-2 bg-success"
                                            style="height: 15px; width: 25px"></span> <span class="ml-2 d-inline-block">
                                            Time Off
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div id="laporanPerKategori">
                                <canvas style="width:100%; height: 600px;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-6">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Rekap Absensi Bulan
                            @isset($selectedBulan)
                                {{ \Carbon\Carbon::createFromDate($selectedTahun, $selectedBulan, 1)->translatedFormat('F Y') }}
                            @else
                                @if (\Carbon\Carbon::now()->day > 26)
                                    {{ \Carbon\Carbon::now()->addMonth()->translatedFormat('F Y') }}
                                @else
                                    {{ \Carbon\Carbon::parse($endDateReport)->translatedFormat('F Y') }}
                                @endif
                            @endisset
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <div class="table-responsive">
                                <!-- datatable start -->
                                <table id="detail-absensi" class="table table-bordered table-hover table-striped w-100">
                                    <thead>
                                        <tr>
                                            {{-- <th style="white-space: nowrap">Foto</th> --}}
                                            <th style="white-space: nowrap">Nama</th>
                                            <th style="white-space: nowrap">Unit</th>
                                            <th style="white-space: nowrap">Telat Masuk (menit)</th>
                                            <th style="white-space: nowrap">Early Out (menit)</th>
                                            <th style="white-space: nowrap">Hadir</th>
                                            <th style="white-space: nowrap">Izin</th>
                                            <th style="white-space: nowrap">Sakit</th>
                                            <th style="white-space: nowrap">Alfa</th>
                                            <th style="white-space: nowrap">Cuti</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($attendances as $row)
                                            <tr>
                                                <td style="white-space: nowrap">
                                                    {{ $row['employee_name'] }}
                                                </td>
                                                <td style="white-space: nowrap">
                                                    {{ $row['organization_name'] ?? '-' }}
                                                </td>
                                                <td style="white-space: nowrap">
                                                    {{ $row['total_late_in'] }}
                                                </td>
                                                <td style="white-space: nowrap">
                                                    {{ $row['total_early_out'] }}
                                                </td>
                                                <td style="white-space: nowrap">
                                                    {{ $row['total_hadir'] }}
                                                </td>
                                                <td style="white-space: nowrap">
                                                    {{ $row['total_izin'] }}
                                                </td>
                                                <td style="white-space: nowrap">
                                                    {{ $row['total_sakit'] }}
                                                </td>
                                                <td style="white-space: nowrap">
                                                    {{ $row['total_absent'] }}
                                                </td>
                                                <td style="white-space: nowrap">
                                                    {{ $row['total_cuti'] }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th style="white-space: nowrap">Nama</th>
                                            <th style="white-space: nowrap">Unit</th>
                                            <th style="white-space: nowrap">Kehadiran</th>
                                            <th style="white-space: nowrap">Telat Masuk (menit)</th>
                                            <th style="white-space: nowrap">Early Out (menit)</th>
                                            <th style="white-space: nowrap">Izin</th>
                                            <th style="white-space: nowrap">Sakit</th>
                                            <th style="white-space: nowrap">Alfa</th>
                                            <th style="white-space: nowrap">Cuti</th>
                                        </tr>
                                    </tfoot>
                                </table>
                                <!-- datatable end -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Detail Absensi Bulan
                            @isset($selectedBulan)
                                {{ \Carbon\Carbon::createFromDate($selectedTahun, $selectedBulan, 1)->translatedFormat('F Y') }}
                            @else
                                @if (\Carbon\Carbon::now()->day > 26)
                                    {{ \Carbon\Carbon::now()->addMonth()->translatedFormat('F Y') }}
                                @else
                                    {{ \Carbon\Carbon::parse($endDateReport)->translatedFormat('F Y') }}
                                @endif
                            @endisset
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <div class="table-responsive">
                                <!-- datatable start -->
                                <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                                    <thead>
                                        <tr>
                                            <th style="white-space: nowrap">Nama</th>
                                            <th style="white-space: nowrap">Unit</th>
                                            @foreach ($employees[0]->attendance->whereBetween('date', [$startDateReport->toDateString(), $endDateReport->toDateString()]) as $absensi)
                                                <th style="white-space: nowrap">{{ $absensi->date }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($employees as $employee)
                                            <tr>
                                                <td style="white-space: nowrap">{{ $employee->fullname }}</td>
                                                <td style="white-space: nowrap">{{ $employee->organization->name }}</td>

                                                @php
                                                    $dateRange = \Carbon\CarbonPeriod::create(
                                                        $startDateReport,
                                                        $endDateReport,
                                                    );
                                                    $attendanceByDate = $employee->attendance
                                                        ->whereBetween('date', [
                                                            $startDateReport->toDateString(),
                                                            $endDateReport->toDateString(),
                                                        ])
                                                        ->keyBy('date');
                                                @endphp

                                                @foreach ($dateRange as $date)
                                                    @php
                                                        $dateString = $date->toDateString();
                                                        $absensi = $attendanceByDate[$dateString] ?? null;
                                                    @endphp

                                                    @if ($absensi)
                                                        <td style="white-space: nowrap">
                                                            @if (isset($absensi->day_off) || isset($absensi->attendance_code))
                                                                {{ $absensi->day_off->attendance_code->code ?? $absensi->attendance_code->code }}
                                                            @elseif ($absensi->clock_in == null && $absensi->is_day_off == 1)
                                                                {{ $absensi->shift->name }}
                                                            @elseif ($absensi->clock_in != null && $absensi->is_day_off == null)
                                                                H
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                    @else
                                                        <td>Belum ada shift</td>
                                                    @endif
                                                @endforeach
                                            </tr>
                                        @endforeach

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th style="white-space: nowrap">Nama</th>
                                            <th style="white-space: nowrap">Unit</th>
                                            @foreach ($employees[0]->attendance->whereBetween('date', [$startDateReport->toDateString(), $endDateReport]) as $absensi)
                                                <th style="white-space: nowrap">{{ $absensi->date }}</th>
                                            @endforeach
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
        {{-- @include('pages.master-data.banks.partials.create-data')
        @include('pages.master-data.banks.partials.update-data') --}}
    </main>
@endsection
@section('plugin')
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.export.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/statistics/chartjs/chartjs.bundle.js"></script>
    <!-- dependency for c3 charts : this dependency is a BSD license with clause 3 -->
    <script src="/js/statistics/d3/d3.js"></script>
    <!-- c3 charts : MIT license -->
    <script src="/js/statistics/c3/c3.js"></script>
    <script src="/js/statistics/demo-data/demo-c3.js"></script>
    <script>
        function convertMonthNameToNumber(monthName) {
            // Mendapatkan tahun saat ini
            const currentYear = new Date().getFullYear();
            // Membuat tanggal dengan nama bulan dan tanggal arbitrer menggunakan tahun saat ini
            const date = new Date(`${monthName} 1, ${currentYear}`);
            // Mengambil angka bulan dari tanggal tersebut (0-11, sehingga perlu +1)
            return date.getMonth() + 1;
        }

        var colors = [myapp_get_color.success_500, myapp_get_color.danger_500, myapp_get_color.info_500, myapp_get_color
            .primary_500, myapp_get_color.warning_500
        ];
        var barStacked = function() {
            let bulan = @json($bulan);
            let attendancesAllMonths = @json($attendancesAllMonths);
            let total_ontime = [];
            let total_latein = [];
            let total_timeoff = [];
            let total_absent = [];
            let currentMonth = new Date().getMonth() + 1;

            console.log(attendancesAllMonths);

            for (let nama_bulan in attendancesAllMonths) {
                if (attendancesAllMonths.hasOwnProperty(nama_bulan) && convertMonthNameToNumber(nama_bulan) <=
                    currentMonth) {
                    console.log(nama_bulan);
                    let total_all = 0;
                    total_all = total_all + attendancesAllMonths[nama_bulan]["total_ontime_all"] +
                        attendancesAllMonths[nama_bulan]["total_latein_all"] +
                        attendancesAllMonths[nama_bulan]["total_time_off_all"] +
                        attendancesAllMonths[nama_bulan]["total_absent_all"];

                    total_ontime.push((attendancesAllMonths[nama_bulan]["total_ontime_all"] / total_all) * 100);
                    total_latein.push((attendancesAllMonths[nama_bulan]["total_latein_all"] / total_all) * 100);
                    total_timeoff.push((attendancesAllMonths[nama_bulan]["total_time_off_all"] / total_all) * 100);
                    total_absent.push((attendancesAllMonths[nama_bulan]["total_absent_all"] / total_all) * 100);
                }
            }

            console.log(total_ontime, true);

            var barStackedData = {
                labels: bulan,
                datasets: [{
                        label: "Izin/Sakit/Cuti",
                        backgroundColor: myapp_get_color.success_300,
                        borderColor: myapp_get_color.success_500,
                        borderWidth: 1,
                        data: total_timeoff
                    },
                    {
                        label: "Terlambat",
                        backgroundColor: myapp_get_color.warning_300,
                        borderColor: myapp_get_color.warning_500,
                        borderWidth: 1,
                        data: total_latein
                    },
                    {
                        label: "Ontime",
                        backgroundColor: myapp_get_color.primary_300,
                        borderColor: myapp_get_color.primary_500,
                        borderWidth: 1,
                        data: total_ontime
                    },
                    {
                        label: "Absent",
                        backgroundColor: myapp_get_color.danger_300,
                        borderColor: myapp_get_color.danger_500,
                        borderWidth: 1,
                        data: total_absent
                    },
                ]
            };

            var config = {
                type: 'bar',
                data: barStackedData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: false,
                        labels: {
                            display: false
                        }
                    },
                    scales: {
                        yAxes: [{
                            stacked: true,
                            gridLines: {
                                display: true,
                                color: "#f2f2f2"
                            },
                            ticks: {
                                beginAtZero: true,
                                fontSize: 11,
                                callback: function(value) {
                                    return value + "%";
                                }
                            }
                        }],
                        xAxes: [{
                            stacked: true,
                            gridLines: {
                                display: true,
                                color: "#f2f2f2"
                            },
                            ticks: {
                                beginAtZero: true,
                                fontSize: 11
                            }
                        }]
                    },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, data) {
                                var dataset = data.datasets[tooltipItem.datasetIndex];
                                var currentValue = dataset.data[tooltipItem.index];
                                return dataset.label + ": " + currentValue.toFixed(2) + "%";
                            }
                        }
                    }
                }
            }

            new Chart($("#barStacked > canvas").get(0).getContext("2d"), config);
        }

        var LaporanPerKategori = function() {
            // Mendapatkan data JSON dari PHP (Laravel)
            let laporan_per_kategori = {!! $grafik_report_per_unit !!};

            // Menyiapkan array untuk menyimpan nilai persentase
            let cutiValues = [];
            let lateInValues = [];
            let onTimeValues = [];
            let absentValues = [];

            // Iterasi melalui data untuk menghitung persentase
            Object.values(laporan_per_kategori).forEach(data => {
                let total = data.Izin + data.Sakit + data.Cuti + data.LateIn + data.OnTime + data.Absent;

                // Menghitung persentase jika total tidak nol
                if (total !== 0) {
                    cutiValues.push((data.Izin + data.Sakit + data.Cuti) / total * 100);
                    lateInValues.push((data.LateIn) / total * 100);
                    onTimeValues.push((data.OnTime) / total * 100);
                    absentValues.push((data.Absent) / total * 100);
                } else {
                    cutiValues.push(0);
                    lateInValues.push(0);
                    onTimeValues.push(0);
                    absentValues.push(0);
                }
            });

            // Menyiapkan data untuk grafik
            var barStackedData = {
                labels: Object.keys(laporan_per_kategori), // Menggunakan nama grup sebagai label
                datasets: [{
                        label: "Izin/Sakit/Cuti",
                        backgroundColor: myapp_get_color.success_300,
                        borderColor: myapp_get_color.success_500,
                        borderWidth: 1,
                        data: cutiValues,
                        rawDataValues: Object.values(laporan_per_kategori).map(data => data.Izin + data.Sakit +
                            data.Cuti)
                    },
                    {
                        label: "Terlambat",
                        backgroundColor: myapp_get_color.warning_300,
                        borderColor: myapp_get_color.warning_500,
                        borderWidth: 1,
                        data: lateInValues, // Terlambat dalam persentase
                        rawDataValues: Object.values(laporan_per_kategori).map(data => data.LateIn)
                    },
                    {
                        label: "Ontime",
                        backgroundColor: myapp_get_color.primary_300,
                        borderColor: myapp_get_color.primary_500,
                        borderWidth: 1,
                        data: onTimeValues, // Ontime dalam persentase
                        rawDataValues: Object.values(laporan_per_kategori).map(data => data.OnTime)
                    },
                    {
                        label: "Absent",
                        backgroundColor: myapp_get_color.danger_300,
                        borderColor: myapp_get_color.danger_500,
                        borderWidth: 1,
                        data: absentValues, // Absent dalam persentase
                        rawDataValues: Object.values(laporan_per_kategori).map(data => data.Absent)
                    }
                ]
            };

            var config = {
                type: 'bar',
                data: barStackedData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: true, // Menampilkan legend
                        position: 'top', // Posisi legend di atas grafik
                    },
                    scales: {
                        yAxes: [{
                            stacked: true,
                            gridLines: {
                                display: true,
                                color: "#f2f2f2"
                            },
                            ticks: {
                                beginAtZero: true,
                                fontSize: 14,
                                callback: function(value) {
                                    return value + '%'; // Menambahkan '%' di akhir nilai sumbu y
                                }
                            }
                        }],
                        xAxes: [{
                            stacked: true,
                            gridLines: {
                                display: true,
                                color: "#f2f2f2"
                            },
                            ticks: {
                                beginAtZero: true,
                                fontSize: 14
                            }
                        }]
                    },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, data) {
                                var dataset = data.datasets[tooltipItem.datasetIndex];
                                var currentValue = dataset.data[tooltipItem.index];
                                var dataValues = dataset.rawDataValues;

                                return [
                                    dataset.label + ": " + currentValue.toFixed(2) + '%',
                                    "Jumlah: " + dataValues[tooltipItem
                                        .index] // Menampilkan jumlah sebelum persentase
                                ];
                            }
                        }
                    }
                }
            };

            // Menggambar grafik menggunakan Chart.js
            new Chart($("#laporanPerKategori > canvas").get(0).getContext("2d"), config);
        };



        barStacked();
        LaporanPerKategori();

        /* bar stacked -- end */

        var pieChart = c3.generate({
            bindto: "#pieChart",
            data: {
                // iris data from R
                columns: @json($top_5_ontime_reports),
                type: 'pie' //,
                /*onclick: function (d, i) { console.log("onclick", d, i); },
                onmouseover: function (d, i) { console.log("onmouseover", d, i); },
                onmouseout: function (d, i) { console.log("onmouseout", d, i); }*/
            },
            color: {
                pattern: colors
            },
            pie: {
                label: {
                    format: function(value, ratio, id) {
                        return value; // Menampilkan nilai aktual
                    }
                }
            }
        });

        var pieChartUnload = function() {
            $("#pieChartUnload").attr("disabled", true);
            $("#pieChartUnload").text("unloading datasets...")
            setTimeout(function() {
                pieChart.unload({
                    ids: 'virtigo'
                });
                pieChart.unload({
                    ids: 'clarfy'
                });
            }, 1000);
            setTimeout(function() {
                $("#pieChartUnload").text("unload complete")
            }, 2000);
        };
    </script>
    <script>
        /* demo scripts for change table color */
        /* change background */
        $(document).ready(function() {
            $(function() {
                $('.select2').select2({
                    placeholder: 'Pilih Data Berikut',
                });
            });
            $('.btn-edit').click(function(e) {
                e.preventDefault();
                let button = $(this);
                console.log('clicked');
                let id = button.attr('data-id');
                button.find('.ikon-edit').hide();
                button.find('.spinner-text').removeClass('d-none');

                $.ajax({
                    type: "GET", // Method pengiriman data bisa dengan GET atau POST
                    url: `/api/dashboard/banks/get/${id}`, // Isi dengan url/path file php yang dituju
                    dataType: "json",
                    success: function(data) {
                        button.find('.ikon-edit').show();
                        button.find('.spinner-text').addClass('d-none');
                        $('#ubah-data').modal('show');
                        $('#ubah-data #name').val(data.name)
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });

                $('#update-form').on('submit', function(e) {
                    e.preventDefault();
                    let formData = $(this).serialize();
                    $.ajax({
                        type: "POST",
                        url: '/api/dashboard/banks/update/' + id,
                        data: formData,
                        beforeSend: function() {
                            $('#update-form').find('.ikon-edit').hide();
                            $('#update-form').find('.spinner-text')
                                .removeClass(
                                    'd-none');
                        },
                        success: function(response) {
                            $('#ubah-data').modal('hide');
                            showSuccessAlert(response.message)
                            setTimeout(function() {
                                location.reload();
                            }, 500);
                        },
                        error: function(xhr) {
                            console.log(xhr.responseText);
                        }
                    });
                });
            });
            $('#store-form').on('submit', function(e) {
                e.preventDefault();
                let formData = $(this).serialize();
                $.ajax({
                    type: "POST",
                    url: '/api/dashboard/banks/store/',
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
                        }, 500);
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });

            $('.btn-hapus').click(function(e) {
                e.preventDefault();
                let button = $(this);
                alert('Yakin ingin menghapus ini ?');
                let id = button.attr('data-id');
                $.ajax({
                    type: "GET",
                    url: '/api/dashboard/banks/delete/' + id,
                    beforeSend: function() {
                        button.find('.ikon-hapus').hide();
                        button.find('.spinner-text').removeClass(
                            'd-none');
                    },
                    success: function(response) {
                        button.find('.ikon-edit').show();
                        button.find('.spinner-text').addClass('d-none');
                        showSuccessAlert(response.message)
                        setTimeout(function() {
                            location.reload();
                        }, 500);
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });

            $('#dt-basic-example').dataTable({
                responsive: false,
                "pageLength": 5,
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'B>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                        extend: 'excelHtml5',
                        text: 'Excel',
                        title: 'Rekap Absensi Bulan ' + new Date().toLocaleString('default', {
                            month: 'long',
                        }) + ' ' + new Date().getFullYear(),
                        titleAttr: 'Export to Excel',
                        className: 'btn-outline-default',
                        exportOptions: {
                            columns: ':visible',
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
                                'text-align: center;'
                            ); // Mengatur gaya untuk heading
                            // $('row c', sheet).attr('s', '25'); // Memberikan border pada sel
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

            $('#detail-absensi').dataTable({
                responsive: false,
                "pageLength": 5,
                dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'B>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                        extend: 'excelHtml5',
                        text: 'Excel',
                        title: 'Rekap Absensi Bulan ' + new Date().toLocaleString('default', {
                            month: 'long',
                        }) + ' ' + new Date().getFullYear(),
                        titleAttr: 'Export to Excel',
                        className: 'btn-outline-default',
                        exportOptions: {
                            columns: ':visible',
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
                                'text-align: center;'
                            ); // Mengatur gaya untuk heading
                            // $('row c', sheet).attr('s', '25'); // Memberikan border pada sel
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
                $('#dt-basic-example thead').removeClassPrefix('bg-').addClass(theadColor);
            });

            $('.js-tbody-colors a').on('click', function() {
                var theadColor = $(this).attr("data-bg");
                $('#dt-basic-example').removeClassPrefix('bg-').addClass(theadColor);
            });

        });
    </script>
@endsection
