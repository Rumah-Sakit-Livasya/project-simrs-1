@extends('inc.layout')
@section('title', 'Dashboard')
@section('extended-css')
    <style>
        /* Gaya scrollbar saat hover untuk browser WebKit (misalnya Chrome, Safari) */
        .panel-content::-webkit-scrollbar-thumb {
            background-color: rgba(0, 0, 0, 0.3);
            /* Warna thumb scrollbar */
            border-radius: 4px;
            /* Sudut border thumb scrollbar */
            visibility: hidden;
            display: none !important;
            /* Sembunyikan thumb scrollbar secara default */
            transition: visibility 0s linear 0.3s;
            /* Efek transisi untuk membuat scrollbar muncul dengan sedikit penundaan */
        }

        .panel-content:hover::-webkit-scrollbar-thumb {
            visibility: visible;
            display: block !important;
            /* Tampilkan thumb scrollbar saat hover */
            transition-delay: 0s;
            /* Hapus penundaan transisi saat dihover */
        }

        /* Gaya scrollbar saat hover untuk browser selain WebKit */
        .panel-content {
            scrollbar-width: thin;
            /* Lebar scrollbar */
        }

        .panel-content::-webkit-scrollbar-thumb {
            background-color: rgba(0, 0, 0, 0.3);
            /* Warna thumb scrollbar */
            border-radius: 4px;
            /* Sudut border thumb scrollbar */
            visibility: hidden;
            display: none !important;
            /* Sembunyikan thumb scrollbar secara default */
            transition: visibility 0s linear 0.3s;
            /* Efek transisi untuk membuat scrollbar muncul dengan sedikit penundaan */
        }

        .panel-content:hover::-webkit-scrollbar-thumb {
            visibility: visible;
            display: block !important;
            /* Tampilkan thumb scrollbar saat hover */
            transition-delay: 0s;
            /* Hapus penundaan transisi saat dihover */
        }

        /* Mengatur gaya scrollbar */
        .legend-custom-cart::-webkit-scrollbar {
            width: 12px;
            /* Lebar scrollbar */
        }

        /* Menyembunyikan indikator scrollbar */
        .legend-custom-cart::-webkit-scrollbar-thumb {
            display: none !important;
            /* Menyembunyikan indikator scrollbar */
        }

        .panel-toolbar {
            display: none;
        }

        .panel-heading {
            font-size: 0.875rem;
            padding-top: 15px;
            padding-bottom: 5px;
            padding-left: 20px;
            font-weight: 500;
        }

        .page-content .panel {
            margin-bottom: 1rem;
        }

        @media only screen and (min-width: 601px) {
            .status-kepegawaian {
                width: 200px !important;
                overflow: auto;

            }

            .chart .panel {
                height: 400px !important;
            }

            .day-off img {
                width: 70px !important;
                height: 70px !important;
            }

            .chart .col-lg-3 {
                margin: 0px !important;
            }
        }

        #dt-basic-example th,
        #dt-basic-example td {
            white-space: nowrap;
        }

        .dataTables_wrapper .dataTables_scrollHeadInner,
        .dataTables_wrapper .dataTables_scrollHeadInner table {
            width: 100% !important;
        }

        .dataTables_wrapper .dataTables_scrollBody {
            overflow-x: auto !important;
        }
    </style>
@endsection

@section('content')
    <main id="js-page-content" role="main" class="page-content">

        <div class="row">
            <div class="col-lg-12">
                <div id="panel-1" class="panel">
                    <div class="panel-container show">
                        <div class="panel-content pb-2">
                            <div class="tl-card mb-3 tl-dashboard-header">
                                <h1>{{ greetings() }}, {{ auth()->user()->name }}!</h1>
                                <div class="text-slate mt-1">{{ tgl_waktu(now()) }}</div>
                                <div class="mt-5"><small class="text-dark font-weight-bold">Shortcut</small>
                                    <div class="tl-dashboard-request mt-2">
                                        <a href="{{ route('attendances') }}" class="badge badge-success p-2">Absen
                                            Sekarang</a>
                                        <a href="{{ route('attendance-requests') }}"
                                            class="badge badge-success p-2">Pengajuan
                                            Absensi</a>
                                        <a href="{{ route('day-off-requests') }}"
                                            class="badge badge-success p-2 mt-2">Request
                                            time
                                            off</a>
                                        {{-- <div class="btn-group">
                                            <a href="javascript:void(0)" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false"
                                                class="badge badge-success p-2 dropdown-toggle dropdown-toggle-bold">
                                                More request
                                            </a>
                                            <div x-placement="bottom-end"
                                                class="dropdown-menu dropdown-menu-sm dropdown-menu-right"><a
                                                    href="/my-info/over-time-info?id=2334757"
                                                    class="dropdown-item">Overtime</a><a
                                                    href="/my-info/attendance?id=2334757"
                                                    class="dropdown-item">Attendance</a><a
                                                    href="/my-info/attendance?id=2334757" class="dropdown-item">Change
                                                    shift</a></div>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row day-off">
            {{-- <div class="col-lg-12">
                <div id="panel-1" class="panel">
                    <div class="panel-container show">
                        <h2 class="panel-heading">Daftar Pegawai</h2>
                        <div class="panel-content pt-0" style="overflow-x: auto; white-space: nowrap;">
                            @foreach ($employees as $item)
                                <a type="button" href="#" data-backdrop="static" data-keyboard="false"
                                    class="btn-show-pegawai" data-id="{{ $item->id }}" title="">
                                    <div class="daftar-pegawai text-center d-inline-block ml-1 mr-1">
                                        @if ($item->foto != null && Storage::exists('public/employee/profile/' . $item->foto))
                                            <img src="{{ asset('storage/employee/profile/' . $item->foto) }}"
                                                class="rounded-circle mr-2" alt=""
                                                style="width: 60px; height: 60px; object-fit: cover; z-index: 100;">
                                        @else
                                            <img src="{{ $item->gender == 'Laki-laki' ? '/img/demo/avatars/avatar-c.png' : '/img/demo/avatars/avatar-p.png' }}"
                                                class="rounded-circle mr-2" alt=""
                                                style="width: 60px; z-index: 100;">
                                        @endif
                                        <div class="name mt-2">{{ Str::limit($item->fullname, 15) }}</div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div> --}}

            <div class="col-lg-12">
                <div id="panel-1" class="panel">
                    <div class="panel-container show">
                        <h2 class="panel-heading">Daftar Pegawai yang Libur</h2>
                        <div class="panel-content pt-0" style="overflow-x: auto; white-space: nowrap;">
                            @foreach ($day_off as $item)
                                <a type="button" href="#" data-backdrop="static" data-keyboard="false"
                                    class="btn-show-day-off" data-id="{{ $item->id }}"
                                    title="{{ $item->day_off->attendance_code->description ?? ($item->attendance_code->description ?? 'Libur') }}">
                                    <div class="daftar-pegawai text-center d-inline-block ml-1 mr-1">
                                        @if ($item->employees->foto != null && Storage::exists('employee/profile/' . $item->employees->foto))
                                            <img src="{{ asset('storage/employee/profile/' . $item->employees->foto) }}"
                                                class="rounded-circle mr-2" alt=""
                                                style="width: 60px; height: 60px; object-fit: cover; z-index: 100;">
                                        @else
                                            <img src="{{ $item->employees->gender == 'Laki-laki' ? '/img/demo/avatars/avatar-c.png' : '/img/demo/avatars/avatar-p.png' }}"
                                                class="rounded-circle mr-2" alt=""
                                                style="width: 60px; z-index: 100;">
                                        @endif
                                        <div class="name mt-2">{{ Str::limit($item->employees->fullname, 15) }}</div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-5 d-flex chart">
            <div class="col-lg-4 mt-0 mb-2">
                <!--Default-->
                <div id="panel-1" class="panel h-100">
                    <div class="panel-hdr">
                        <h2>
                            Status Kepegawaian
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <div class="progress">
                                <div class="progress-bar bg-info" role="progressbar" style="width: 65%" aria-valuenow="65"
                                    aria-valuemin="0" aria-valuemax="100" title=""></div>

                                <div class="progress-bar bg-warning" role="progressbar" style="width: 35%"
                                    aria-valuenow="35" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <small>{{ $statusKepegawaian['persentasePermanen'] }}%</small>
                                <small>{{ $statusKepegawaian['persentaseKontrak'] }}%</small>
                            </div>

                            <div class="legend-custom-cart status-kepegawaian mt-4">
                                <p class="d-flex">
                                    <span>Total</span>
                                    <span class="ml-auto">{{ $statusKepegawaian['totalKaryawan'] }}</span>
                                </p>

                                <div class="d-flex justify-content-between my-3">
                                    <div class="label-circle bg-info d-inline-block" style="width: 20px;">&nbsp;</div>
                                    <span class="ml-1">Permanen</span>
                                    <span class="ml-auto">{{ $statusKepegawaian['jmlPermanen'] }}</span>
                                </div>

                                {{-- <div class="d-inline-block"> --}}
                                <div class="d-flex justify-content-between my-3">
                                    <div class="label-circle bg-warning d-inline-block" style="width: 20px;">&nbsp;</div>
                                    <span class="ml-1">Kontrak</span>
                                    <span class="ml-auto">{{ $statusKepegawaian['jmlKontrak'] }}</span>
                                </div>
                                {{-- </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- <div class="col-lg-4">
                <!--Default-->
                <div id="panel-1" class="panel h-100">
                    <div class="panel-hdr">
                        <h2>
                            Masa jabatan
                        </h2>
                    </div>
                    <div class="panel-container-custom show">
                        <div class="panel-content-custom d-flex align-items-start" id="panelContent-custom">
                            <canvas id="myChart-custom"></canvas>
                        </div>
                    </div>
                </div>
            </div> --}}

            <div class="col-lg-4">
                <!--Default-->
                <div id="panel-1" class="panel h-100">
                    <div class="panel-hdr">
                        <h2>
                            Status Kepegawaian
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <div class="progress">
                                <div class="progress-bar" role="progressbar"
                                    style="background-color: rgb(0, 155, 222); width: {{ $jobLevel['persentase-director'] }}%"
                                    aria-valuenow="{{ $jobLevel['director'] }}" aria-valuemin="0"
                                    aria-valuemax="{{ $jobLevel['totalKaryawan'] }}" title=""></div>

                                <div class="progress-bar" role="progressbar"
                                    style="background-color: rgb(249, 109, 1); width: {{ $jobLevel['persentase-owner'] }}%"
                                    aria-valuenow="{{ $jobLevel['owner'] }}" aria-valuemin="0"
                                    aria-valuemax="{{ $jobLevel['totalKaryawan'] }}" title=""></div>

                                <div class="progress-bar" role="progressbar"
                                    style="background-color: rgb(91, 55, 212); width: {{ $jobLevel['persentase-head'] }}%"
                                    aria-valuenow="{{ $jobLevel['persentase-head'] }}" aria-valuemin="0"
                                    aria-valuemax="{{ $jobLevel['totalKaryawan'] }}"></div>

                                <div class="progress-bar" role="progressbar"
                                    style="background-color: rgb(192, 42, 52); width: {{ $jobLevel['persentase-supervisor'] }}%"
                                    aria-valuenow="{{ $jobLevel['supervisor'] }}" aria-valuemin="0"
                                    aria-valuemax="{{ $jobLevel['totalKaryawan'] }}"></div>

                                <div class="progress-bar" role="progressbar"
                                    style="background-color: rgb(255, 183, 0); width: {{ $jobLevel['persentase-coordinator'] }}%"
                                    aria-valuenow="{{ $jobLevel['coordinator'] }}" aria-valuemin="0"
                                    aria-valuemax="{{ $jobLevel['totalKaryawan'] }}"></div>

                                <div class="progress-bar" role="progressbar"
                                    style="background-color: rgb(0, 95, 191); width: {{ $jobLevel['persentase-staff'] }}%"
                                    aria-valuenow="{{ $jobLevel['staff'] }}" aria-valuemin="0"
                                    aria-valuemax="{{ $jobLevel['totalKaryawan'] }}"></div>

                                <div class="progress-bar" role="progressbar"
                                    style="background-color: rgb(0, 159, 97); width: {{ $jobLevel['persentase-non-staff'] }}%"
                                    aria-valuenow="{{ $jobLevel['non-staff'] }}" aria-valuemin="0"
                                    aria-valuemax="{{ $jobLevel['totalKaryawan'] }}"></div>

                                <div class="progress-bar" role="progressbar"
                                    style="background-color: rgb(215, 64, 34); width: {{ $jobLevel['persentase-dokter-full-time'] }}%"
                                    aria-valuenow="{{ $jobLevel['dokter-full-time'] }}" aria-valuemin="0"
                                    aria-valuemax="{{ $jobLevel['totalKaryawan'] }}"></div>

                                <div class="progress-bar" role="progressbar"
                                    style="background-color: rgb(176, 211, 217); width: {{ $jobLevel['persentase-dokter-part-time'] }}%"
                                    aria-valuenow="{{ $jobLevel['dokter-part-time'] }}" aria-valuemin="0"
                                    aria-valuemax="{{ $jobLevel['totalKaryawan'] }}"></div>

                            </div>
                            <div class="d-flex justify-content-between">
                                <small>0%</small> <small>100%</small>
                            </div>

                            <div class="legend-custom-cart mt-4" style="height: 230px;overflow: auto;">
                                <p class="d-flex">
                                    <span>Total</span>
                                    <span class="ml-auto">{{ $jobLevel['totalKaryawan'] }}</span>
                                </p>

                                <div class="d-flex justify-content-between my-3">
                                    <div class="label-circle d-inline-block"
                                        style="background-color: rgb(0, 155, 222); width: 20px;">&nbsp;</div>
                                    <span class="ml-1">Direktur</span>
                                    <span class="ml-auto">{{ $jobLevel['director'] }}</span>
                                </div>
                                <div class="d-flex justify-content-between my-3">
                                    <div class="label-circle d-inline-block"
                                        style="background-color: rgb(249, 109, 1); width: 20px;">&nbsp;</div>
                                    <span class="ml-1">Owner</span>
                                    <span class="ml-auto">{{ $jobLevel['owner'] }}</span>
                                </div>
                                <div class="d-flex justify-content-between my-3">
                                    <div class="label-circle d-inline-block"
                                        style="background-color: rgb(91, 55, 212); width: 20px;">&nbsp;</div>
                                    <span class="ml-1">Head</span>
                                    <span class="ml-auto">{{ $jobLevel['head'] }}</span>
                                </div>
                                <div class="d-flex justify-content-between my-3">
                                    <div class="label-circle d-inline-block"
                                        style="background-color: rgb(192, 42, 52); width: 20px;">&nbsp;</div>
                                    <span class="ml-1">Supervisor</span>
                                    <span class="ml-auto">{{ $jobLevel['supervisor'] }}</span>
                                </div>
                                <div class="d-flex justify-content-between my-3">
                                    <div class="label-circle d-inline-block"
                                        style="background-color: rgb(255, 183, 0); width: 20px;">&nbsp;</div>
                                    <span class="ml-1">Coordinator</span>
                                    <span class="ml-auto">{{ $jobLevel['coordinator'] }}</span>
                                </div>
                                <div class="d-flex justify-content-between my-3">
                                    <div class="label-circle d-inline-block"
                                        style="background-color: rgb(0, 95, 191); width: 20px;">&nbsp;</div>
                                    <span class="ml-1">Staff</span>
                                    <span class="ml-auto">{{ $jobLevel['staff'] }}</span>
                                </div>
                                <div class="d-flex justify-content-between my-3">
                                    <div class="label-circle d-inline-block"
                                        style="background-color: rgb(0, 159, 97); width: 20px;">&nbsp;</div>
                                    <span class="ml-1">Non Staff</span>
                                    <span class="ml-auto">{{ $jobLevel['non-staff'] }}</span>
                                </div>
                                <div class="d-flex justify-content-between my-3">
                                    <div class="label-circle d-inline-block"
                                        style="background-color: rgb(215, 64, 34); width: 20px;">&nbsp;</div>
                                    <span class="ml-1">Dokter Full Time</span>
                                    <span class="ml-auto">{{ $jobLevel['dokter-full-time'] }}</span>
                                </div>
                                <div class="d-flex justify-content-between my-3">
                                    <div class="label-circle d-inline-block"
                                        style="background-color: rgb(176, 211, 217); width: 20px;">&nbsp;</div>
                                    <span class="ml-1">Dokter Part Time</span>
                                    <span class="ml-auto">{{ $jobLevel['dokter-part-time'] }}</span>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <!--Default-->
                <div id="panel-1" class="panel h-100">
                    <div class="panel-hdr">
                        <h2>
                            Jenis Kelamin
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content ">
                            <div class="d-flex justify-content-center">
                                <canvas id="genderDiversity" height="145"></canvas>
                            </div>
                            <div class="legend-custom-cart mt-4" style="height: 100px;overflow: auto;">
                                <p class="d-flex">
                                    <span>Total</span>
                                    <span class="ml-auto">{{ $genderDiversity['totalKaryawan'] }}</span>
                                </p>

                                <div class="d-flex justify-content-between my-3">
                                    <div class="label-circle d-inline-block"
                                        style="width: 20px; background-color: rgba(255, 99, 132, 0.5); border: 1px solid rgba(255,99,132,1)">
                                        &nbsp;</div>
                                    <span class="ml-1">Laki Laki</span>
                                    <span class="ml-auto">{{ $genderDiversity['lakiLaki'] }}</span>
                                    <span class="ml-auto">{{ $genderDiversity['persentaseLakiLaki'] }}%</span>
                                </div>

                                {{-- <div class="d-inline-block"> --}}
                                <div class="d-flex justify-content-between my-3">
                                    <div class="label-circle d-inline-block"
                                        style="width: 20px; background-color: rgba(54, 162, 235, 0.2); border: 1px solid rgba(54, 162, 235, 1)">
                                        &nbsp;</div>
                                    <span class="ml-1">Perempuan</span>
                                    <span class="ml-auto">{{ $genderDiversity['perempuan'] }}</span>
                                    <span class="ml-auto">{{ $genderDiversity['persentasePerempuan'] }}%</span>
                                </div>
                                {{-- </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div id="panel-10" class="panel">
            <div class="panel-hdr">
                <h2>
                    Combination <span class="fw-300"><i>Chart (Bar & Line)</i></span>
                </h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="panel-container show">
                        <div class="panel-content">
                            <form action="{{ route('dashboard') }}" method="post">
                                @method('get')
                                @csrf
                                <div class="row" id="step-1">
                                    <div class="col-md-10">
                                        <div class="form-group mb-3">
                                            <label for="tahun">Tahun</label>
                                            <!-- Mengubah input menjadi select2 -->
                                            <select class="select2 form-control @error('tahun') is-invalid @enderror"
                                                name="tahun" id="tahun">
                                                <option value=""></option>
                                                @php
                                                    $currentYear = date('Y');
                                                @endphp
                                                @for ($year = 2024; $year <= $currentYear; $year++)
                                                    <option value="{{ $year }}">{{ $year }}</option>
                                                @endfor
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

            <div id="barlineCombineCanvas">
                <canvas id="barlineCombine" style="width:100%; height:300px;"></canvas>
                <p class="text-center h4 mt-3 text-bold">Tahun 2024</p>
            </div>
        </div> --}}

        {{-- <div id="panel-11" class="panel">
            <div class="panel-hdr">
                <h2>
                    Daftar Pegawai <span class="fw-300"><i>yang sering telat</i></span>
                </h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="table-responsive">
                        <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Pegawai</th>
                                    <th>Unit</th>
                                    <th>Januari</th>
                                    <th>Februari</th>
                                    <th>Maret</th>
                                    <th>April</th>
                                    <th>Mei</th>
                                    <th>Juni</th>
                                    <th>Juli</th>
                                    <th>Agustus</th>
                                    <th>September</th>
                                    <th>Oktober</th>
                                    <th>November</th>
                                    <th>Desember</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($lateCount as $employeeId => $monthlyLateCount)
                                    @php
                                        $employee = \App\Models\Employee::findOrFail($employeeId);
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $employee->fullname }}</td>
                                        <td>{{ $employee->organization->name ?? '-' }}</td>
                                        @foreach ($monthlyLateCount as $month => $lateMinutes)
                                            <td>{{ $lateMinutes }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Pegawai</th>
                                    <th>Unit</th>
                                    <th>Januari</th>
                                    <th>Februari</th>
                                    <th>Maret</th>
                                    <th>April</th>
                                    <th>Mei</th>
                                    <th>Juni</th>
                                    <th>Juli</th>
                                    <th>Agustus</th>
                                    <th>September</th>
                                    <th>Oktober</th>
                                    <th>November</th>
                                    <th>Desember</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div> --}}
    </main>
    @include('pages.partials.show')
@endsection
@section('plugin')

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script src="/js/datagrid/datatables/datatables.bundle.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const panelContent = document.getElementById('panelContent-custom');
            const myChartElement = document.getElementById('myChart-custom');

            function resizeCanvas() {
                myChartElement.style.height = panelContent.clientHeight + 'px';
                myChartElement.style.width = panelContent.clientWidth + 'px';
            }

            resizeCanvas();

            window.addEventListener('resize', resizeCanvas);

            // Mengambil konteks 2d dari canvas
            const ctx = myChartElement.getContext('2d');

            // Membuat chart setelah ukuran kanvas disesuaikan
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['< 1th', '1-3 th', '3-5 th', '5-10 th', '> 10 th', 'belum setting'],
                    datasets: [{
                        label: 'tahun',
                        data: [{{ $masaJabatan['less_than_1_year'] }},
                            {{ $masaJabatan['1_to_3_years'] }},
                            {{ $masaJabatan['3_to_5_years'] }},
                            {{ $masaJabatan['5_to_10_years'] }},
                            {{ $masaJabatan['more_than_10_years'] }},
                            {{ $masaJabatan['unassigned'] }}
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });

        var chartjs = document.getElementById("genderDiversity");
        var doughnut = new Chart(chartjs, {
            type: 'doughnut',
            data: {
                labels: ['Laki-laki', 'Perempuan'],
                datasets: [{
                    data: [{{ $genderDiversity['lakiLaki'] }}, {{ $genderDiversity['perempuan'] }}],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(54, 162, 235, 0.2)',
                    ],
                    borderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                //cutoutPercentage: 40,
                responsive: false,

            }
        });
        /* doughnut chart -- end */

        $(document).ready(function() {
            $('.select2').select2({
                placeholder: 'Pilih Data berikut',
            });
            // Convert PHP data to JavaScript
            var lateCount = @json($lateCount);
            var totalEmployees = @json($totalEmployees);

            // Aggregate the late counts per month
            var aggregatedLateCount = Array(12).fill(0);
            for (var employeeId in lateCount) {
                if (lateCount.hasOwnProperty(employeeId)) {
                    var monthlyCounts = lateCount[employeeId];
                    for (var month in monthlyCounts) {
                        if (monthlyCounts.hasOwnProperty(month)) {
                            aggregatedLateCount[month - 1] += monthlyCounts[month];
                        }
                    }
                }
            }

            // Calculate the percentage of late employees per month
            var percentageLate = aggregatedLateCount.map(function(count) {
                return (count / totalEmployees) * 100;
            });

            var barlineCombine = function() {
                var barlineCombineData = {
                    labels: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus",
                        "September", "Oktober", "November", "Desember"
                    ],
                    datasets: [{
                            type: 'line',
                            label: 'Target',
                            borderColor: 'rgba(255,99,132,0.2)',
                            pointBackgroundColor: 'rgba(255,99,132,1)',
                            pointBorderColor: 'rgba(255,99,132,1)',
                            pointBorderWidth: 1,
                            borderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 5,
                            fill: false,
                            data: [
                                10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10
                            ]
                        },
                        {
                            type: 'bar',
                            label: 'Capaian',
                            backgroundColor: 'rgba(54,162,235,0.2)',
                            borderColor: 'rgba(54,162,235,1)',
                            data: percentageLate,
                            borderWidth: 1
                        }
                    ]
                };

                var config = {
                    type: 'bar',
                    data: barlineCombineData,
                    options: {
                        responsive: true,
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Grafik Kombinasi Bar & Line Chart'
                        },
                        scales: {
                            xAxes: [{
                                display: true,
                                gridLines: {
                                    display: true,
                                    color: "#f2f2f2"
                                },
                                ticks: {
                                    beginAtZero: true,
                                    fontSize: 11
                                }
                            }],
                            yAxes: [{
                                display: true,
                                gridLines: {
                                    display: true,
                                    color: "#f2f2f2"
                                },
                                ticks: {
                                    beginAtZero: true,
                                    fontSize: 11,
                                    callback: function(value) {
                                        return value + "%"
                                    }
                                },
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Percentage'
                                }
                            }]
                        },
                        tooltips: {
                            callbacks: {
                                label: function(tooltipItem, data) {
                                    var datasetLabel = data.datasets[tooltipItem.datasetIndex].label ||
                                        '';
                                    var dataValue = data.datasets[tooltipItem.datasetIndex].data[
                                        tooltipItem.index];
                                    if (datasetLabel === 'Capaian') {
                                        return 'Telat: ' + dataValue +
                                            '%'; // Ubah sesuai kebutuhan informasi karyawan yang ingin ditampilkan
                                    } else {
                                        return datasetLabel + ': ' + dataValue;
                                    }
                                }
                            }
                        }
                    }
                };

                var ctx = document.getElementById("barlineCombine").getContext("2d");
                if (ctx) {
                    new Chart(ctx, config);
                } else {
                    console.error("Konteks kanvas tidak ditemukan");
                }
            };

            // Panggil fungsi untuk membuat grafik
            barlineCombine();

            $('#dt-basic-example').DataTable({
                responsive: true,
                autoWidth: false,
                pageLength: 10,
                fixedColumns: {
                    leftColumns: 2
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
                        extend: 'print',
                        text: 'Print',
                        titleAttr: 'Print Table',
                        className: 'btn-outline-default',
                        exportOptions: {
                            columns: ':visible'
                        },
                        customize: function(win) {
                            $(win.document.body).find('table').addClass('display').css('font-size',
                                '12px');
                            $(win.document.body).find('thead').addClass('thead-light');
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        text: 'Excel',
                        titleAttr: 'Export to Excel',
                        className: 'btn-outline-default',
                        exportOptions: {
                            columns: ':visible'
                        }
                    }
                ]
            });
        });
    </script>
    <script>
        function formatPhoneNumber(phone) {
            if (phone.startsWith('0')) {
                return '62' + phone.substring(1);
            }
            return phone;
        }
        $(document).ready(function() {
            $('.btn-show-day-off').click(function(event) {
                event.preventDefault();
                const id = $(this).attr('data-id');
                $.ajax({
                    type: "GET", // Method pengiriman data bisa dengan GET atau POST
                    url: `/api/dashboard/attendances/${id}`, // Isi dengan url/path file php yang dituju
                    dataType: "json",
                    success: function(data) {
                        $('#show-day-off').modal('show');
                        $('#avatar').attr('src', '/storage/employee/profile/' + data.foto);
                        $('#nama-pegawai').text(data.fullname);
                        $('#jabatan').text(data.jabatan);
                        $('#status-libur').text(data.status);
                        $('#start-date').text(data.start_date);
                        $('#end-date').text(data.end_date);
                        $('#email').text(data.email);
                        $('#phone').text(data.phone);
                        $('#organisasi').text(data.organisasi);
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });

            $('.btn-show-pegawai').click(function(event) {
                event.preventDefault();
                const id = $(this).attr('data-id');
                $.ajax({
                    type: "GET", // Method pengiriman data bisa dengan GET atau POST
                    url: `/api/dashboard/employee/pegawai/${id}`, // Isi dengan url/path file php yang dituju
                    dataType: "json",
                    success: function(data) {
                        $('#show-pegawai').modal('show');
                        $('#show-pegawai #avatar').attr('src', '/storage/employee/profile/' +
                            data.foto);
                        $('#show-pegawai #nama-pegawai').text(data.fullname);
                        $('#show-pegawai #jabatan').text(data.jabatan);
                        $('#show-pegawai #email').text(data.email);
                        $('#show-pegawai #phone').text(data.phone);
                        $('#show-pegawai #organisasi').text(data.organisasi);
                        $('#show-pegawai #phone').each(function() {
                            var phoneSpan = $(this);
                            var rawPhone = phoneSpan
                                .text(); // Get the raw phone number text
                            var formattedPhone = formatPhoneNumber(
                                rawPhone); // Format the phone number

                            var whatsappLink = 'https://wa.me/' +
                                formattedPhone; // Create the WhatsApp link

                            phoneSpan.html('<a href="' + whatsappLink +
                                '" target="_blank">' + rawPhone + '</a>'
                            ); // Update the HTML
                        });
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });

            lineChart();
            areaChart();
            horizontalBarChart();
            barChart();
            barStacked();
            barHorizontalStacked();
            bubbleChart();
            barlineCombine();
            polarArea();
            radarChart();
            pieChart();
            doughnutChart();
        });
    </script>
@endsection
