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
            padding-bottom: 15px;
            padding-left: 20px;
            font-weight: 500;
            border-bottom: 1px solid rgba(0, 0, 0, .1);
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

            @php
                $cuti = $day_off->filter(function ($item) {
                    $desc =
                        $item->day_off->attendance_code->description ??
                        ($item->attendance_code->description ?? 'Libur');
                    return stripos($desc, 'cuti') !== false;
                });
                $libur = $day_off->filter(function ($item) {
                    $desc =
                        $item->day_off->attendance_code->description ??
                        ($item->attendance_code->description ?? 'Libur');
                    return stripos($desc, 'cuti') === false;
                });
            @endphp

            <div class="col-lg-8">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>Kalender</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <div id="calendar"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 ">
                <div id="panel-1" class="panel">
                    <div>
                        <h2 class="panel-heading">Daftar Pegawai yang Ulang Tahun Hari Ini</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content pt-3" style="height: 45rem; overflow-x: auto; white-space: normal;">
                            @if ($birthdays->isEmpty())
                                <p class="text-center">Tidak ada pegawai yang ulang tahun hari ini.</p>
                            @else
                                <div style="white-space: nowrap;">
                                    @php
                                        $todayBirthdays = $birthdays->filter(function ($employee) {
                                            return \Carbon\Carbon::parse($employee->birthdate)->format('m-d') ==
                                                now()->format('m-d');
                                        });

                                        $thisMonthBirthdays = $birthdays
                                            ->filter(function ($employee) {
                                                return \Carbon\Carbon::parse($employee->birthdate)->format('m') ==
                                                    now()->format('m');
                                            })
                                            ->diff($todayBirthdays);
                                    @endphp

                                    @foreach ($todayBirthdays as $employee)
                                        <div
                                            class="daftar-pegawai d-flex align-items-center ml-1 mr-1 p-2 border rounded shadow-sm">
                                            @if ($employee->foto != null && Storage::exists('employee/profile/' . $employee->foto))
                                                <img src="{{ asset('storage/employee/profile/' . $employee->foto) }}"
                                                    class="rounded-circle mr-2" alt=""
                                                    style="width: 60px; height: 60px; object-fit: cover; z-index: 100;">
                                            @else
                                                <img src="{{ $employee->gender == 'Laki-laki' ? '/img/demo/avatars/avatar-c.png' : '/img/demo/avatars/avatar-p.png' }}"
                                                    class="rounded-circle mr-2" alt=""
                                                    style="width: 60px; z-index: 100;">
                                            @endif
                                            <div class="flex-grow-1">
                                                <div class="name font-weight-bold">
                                                    {{ $employee->fullname }}
                                                    <div class="badge badge-success ml-2">🎉 Ulang Tahun!</div>
                                                </div>
                                                <div class="organization text-muted">
                                                    {{ $employee->organization->name }}
                                                </div>
                                                <div class="birthday text-muted">
                                                    {{ formatTanggalBulan($employee->birthdate) }}
                                                </div>
                                            </div>
                                            <a href="https://wa.me/{{ phone($employee->mobile_phone) }}"
                                                class="badge badge-success p-2" target="_blank"><i
                                                    class='bx bxl-whatsapp m-0'></i></a>
                                        </div>
                                    @endforeach

                                    @if ($todayBirthdays->isNotEmpty() && $thisMonthBirthdays->isNotEmpty())
                                        <h4 class="mt-4">Lainnya di Bulan Ini</h4>
                                    @endif

                                    @foreach ($thisMonthBirthdays as $employee)
                                        <div
                                            class="daftar-pegawai d-flex align-items-center ml-1 mr-1 p-2 border rounded shadow-sm">
                                            @if ($employee->foto != null && Storage::exists('employee/profile/' . $employee->foto))
                                                <img src="{{ asset('storage/employee/profile/' . $employee->foto) }}"
                                                    class="rounded-circle mr-2" alt=""
                                                    style="width: 60px; height: 60px; object-fit: cover; z-index: 100;">
                                            @else
                                                <img src="{{ $employee->gender == 'Laki-laki' ? '/img/demo/avatars/avatar-c.png' : '/img/demo/avatars/avatar-p.png' }}"
                                                    class="rounded-circle mr-2" alt=""
                                                    style="width: 60px; z-index: 100;">
                                            @endif
                                            <div class="flex-grow-1">
                                                <div class="name font-weight-bold">
                                                    {{ $employee->fullname }}
                                                </div>
                                                <div class="organization text-muted">
                                                    {{ $employee->organization->name }}
                                                </div>
                                                <div class="birthday text-muted">
                                                    {{ formatTanggalBulan($employee->birthdate) }}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-5 d-flex chart" style="overflow-x: auto;">
            <div class="col-lg-6 mt-0 mb-2">
                <div class="row">
                    <div class="col-lg-12">
                        <div id="panel-cuti-libur" class="panel">
                            <div class="panel-container show">
                                <h2 class="panel-heading">Daftar Pegawai Cuti & Libur</h2>
                                <div class="panel-content pt-0 d-flex flex-column"
                                    style="overflow-y: auto; max-height: 300px; white-space: nowrap;">
                                    <div class="flex-fill mb-4">
                                        <h4 class="mb-2">Cuti</h4>
                                        <div class="d-flex flex-column">
                                            @foreach ($cuti as $item)
                                                <a href="#" data-backdrop="static" data-keyboard="false"
                                                    class="btn-show-day-off mb-3" data-id="{{ $item->id }}"
                                                    title="{{ $item->day_off->attendance_code->description ?? ($item->attendance_code->description ?? 'Cuti') }}">
                                                    <div
                                                        class="daftar-pegawai d-flex align-items-center p-2 border rounded">
                                                        @if ($item->employees->foto != null && Storage::exists('employee/profile/' . $item->employees->foto))
                                                            <img src="{{ asset('storage/employee/profile/' . $item->employees->foto) }}"
                                                                class="rounded-circle mr-3" alt=""
                                                                style="width: 60px; height: 60px; object-fit: cover;">
                                                        @else
                                                            <img src="{{ $item->employees->gender == 'Laki-laki' ? '/img/demo/avatars/avatar-c.png' : '/img/demo/avatars/avatar-p.png' }}"
                                                                class="rounded-circle mr-3" alt=""
                                                                style="width: 60px;">
                                                        @endif
                                                        <div>
                                                            <div class="name mb-1">
                                                                {{ $item->employees->fullname }}
                                                            </div>
                                                            <div>
                                                                <small class="badge badge-info">
                                                                    {{ $item->day_off->attendance_code->description ?? ($item->attendance_code->description ?? 'Cuti') }}
                                                                </small>
                                                            </div>
                                                            <div>
                                                                @if ($item->employees->user->isOnline())
                                                                    <span
                                                                        class="text-green-600 font-semibold">Online</span>
                                                                @else
                                                                    <span class="text-gray-500 text-sm">
                                                                        {{ $item->employees->user->lastSeenHuman() }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            @endforeach
                                            @if ($cuti->isEmpty())
                                                <p class="text-center">Tidak ada pegawai yang sedang cuti.</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex-fill">
                                        <h4 class="mb-2">Libur</h4>
                                        <div class="d-flex flex-column">
                                            @foreach ($libur as $item)
                                                <a href="#" data-backdrop="static" data-keyboard="false"
                                                    class="btn-show-day-off mb-3" data-id="{{ $item->id }}"
                                                    title="{{ $item->day_off->attendance_code->description ?? ($item->attendance_code->description ?? 'Libur') }}">
                                                    <div
                                                        class="daftar-pegawai d-flex align-items-center p-2 border rounded">
                                                        @if ($item->employees->foto != null && Storage::exists('employee/profile/' . $item->employees->foto))
                                                            <img src="{{ asset('storage/employee/profile/' . $item->employees->foto) }}"
                                                                class="rounded-circle mr-3" alt=""
                                                                style="width: 60px; height: 60px; object-fit: cover;">
                                                        @else
                                                            <img src="{{ $item->employees->gender == 'Laki-laki' ? '/img/demo/avatars/avatar-c.png' : '/img/demo/avatars/avatar-p.png' }}"
                                                                class="rounded-circle mr-3" alt=""
                                                                style="width: 60px;">
                                                        @endif
                                                        <div>
                                                            <div class="name mb-1">
                                                                {{ $item->employees->fullname }}
                                                            </div>
                                                            <div>
                                                                <small class="badge badge-info">
                                                                    {{ $item->day_off->attendance_code->description ?? ($item->attendance_code->description ?? 'Libur') }}
                                                                </small>
                                                            </div>
                                                            <div>
                                                                @if ($item->employees->user->isOnline())
                                                                    <span
                                                                        class="text-green-600 font-semibold">Online</span>
                                                                @else
                                                                    <span class="text-gray-500 text-sm">
                                                                        {{ $item->employees->user->lastSeenHuman() }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            @endforeach
                                            @if ($libur->isEmpty())
                                                <p class="text-center">Tidak ada pegawai yang sedang libur.</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mt-0 mb-2">
                <div id="panel-2" class="panel h-100">
                    <div>
                        <h2 class="panel-heading">Organisasi</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content pt-3" style="overflow-y: auto; max-height: 300px; white-space: nowrap;">
                            @if ($employees->isEmpty())
                                <p class="text-center">Tidak ada data karyawan.</p>
                            @else
                                <div style="white-space: nowrap;">
                                    <div class="demography-report">
                                        <div class="row">
                                            <div class="col-md-12">
                                                @foreach ($employees->pluck('organization_id')->unique() as $organizationId)
                                                    <div class="card" data-toggle="modal"
                                                        data-target="#organizationModal{{ $organizationId }}">
                                                        <div class="card-body">
                                                            <h5 class="card-title">
                                                                {{ $employees->firstWhere('organization_id', $organizationId)->organization->name }}
                                                                {{ $organizationId }}
                                                            </h5>
                                                            <p class="card-text">
                                                                {{ $employees->where('organization_id', $organizationId)->count() }}
                                                                pegawai</p>
                                                        </div>
                                                    </div>
                                                    <!-- Modal -->
                                                    <div class="modal fade" id="organizationModal{{ $organizationId }}"
                                                        tabindex="-1" role="dialog"
                                                        aria-labelledby="organizationModalLabel{{ $organizationId }}"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"
                                                                        id="organizationModalLabel{{ $organizationId }}">
                                                                        {{ $employees->firstWhere('organization_id', $organizationId)->organization->name }}
                                                                        {}
                                                                    </h5>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    {{-- <ul>
                                                                        @foreach ($employees->where('organization_id', $organizationId)->sortBy(function ($employee) {
        return $employee->jobPosition->name == 'Penanggung Jawab' ? 0 : 1;
    }) as $employee)
                                                                            <li>{{ $employee->fullname }}</li>
                                                                        @endforeach
                                                                    </ul> --}}
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 mt-0 mb-2">
                <div id="panel-2" class="panel h-100">
                    <div>
                        <h2 class="panel-heading">Daftar Pegawai Habis Kontrak Bulan Ini</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content pt-3" style="overflow-y: auto; max-height: 400px; white-space: nowrap;">
                            @if ($pegawaiHabisKontrakBulanIni->isEmpty())
                                <p class="text-center">Tidak ada data karyawan.</p>
                            @else
                                <div style="white-space: nowrap;">
                                    <div class="demography-report">
                                        <div class="row">
                                            <div class="col-md-12">
                                                @foreach ($pegawaiHabisKontrakBulanIni as $pegawai)
                                                    <div
                                                        class="daftar-pegawai d-flex align-items-center ml-1 mr-1 p-2 border rounded shadow-sm">
                                                        @if ($pegawai->foto != null && Storage::exists('employee/profile/' . $pegawai->foto))
                                                            <img src="{{ asset('storage/employee/profile/' . $pegawai->foto) }}"
                                                                class="rounded-circle mr-2" alt=""
                                                                style="width: 60px; height: 60px; object-fit: cover; z-index: 100;">
                                                        @else
                                                            <img src="{{ $pegawai->gender == 'Laki-laki' ? '/img/demo/avatars/avatar-c.png' : '/img/demo/avatars/avatar-p.png' }}"
                                                                class="rounded-circle mr-2" alt=""
                                                                style="width: 60px; z-index: 100;">
                                                        @endif
                                                        <div class="flex-grow-1">
                                                            <div class="name font-weight-bold">
                                                                {{ $pegawai->fullname }}
                                                            </div>
                                                            <div class="organization text-muted">
                                                                {{ $pegawai->organization->name }}
                                                            </div>
                                                            <div class="birthday text-muted">
                                                                {{ formatTanggalBulan($pegawai->end_status_date) }}
                                                            </div>
                                                        </div>
                                                        <a href="https://wa.me/{{ phone($pegawai->mobile_phone) }}"
                                                            class="badge badge-success p-2" target="_blank"><i
                                                                class='bx bxl-whatsapp m-0'></i></a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mt-0 mb-2">
                <div id="panel-2" class="panel h-100">
                    <div>
                        <h2 class="panel-heading">Top 10 Pegawai Paling Lama Mengabdi di Livasya</h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content pt-3" style="overflow-y: auto; max-height: 400px; white-space: nowrap;">
                            @if ($PegawaiTerlama->isEmpty())
                                <p class="text-center">Tidak ada data karyawan.</p>
                            @else
                                <div style="white-space: nowrap;">
                                    <div class="demography-report">
                                        <div class="row">
                                            <div class="col-md-12">
                                                @foreach ($PegawaiTerlama as $pegawai)
                                                    <div
                                                        class="daftar-pegawai d-flex align-items-center ml-1 mr-1 p-2 border rounded shadow-sm">
                                                        @if ($pegawai->foto != null && Storage::exists('employee/profile/' . $pegawai->foto))
                                                            <img src="{{ asset('storage/employee/profile/' . $pegawai->foto) }}"
                                                                class="rounded-circle mr-2" alt=""
                                                                style="width: 60px; height: 60px; object-fit: cover; z-index: 100;">
                                                        @else
                                                            <img src="{{ $pegawai->gender == 'Laki-laki' ? '/img/demo/avatars/avatar-c.png' : '/img/demo/avatars/avatar-p.png' }}"
                                                                class="rounded-circle mr-2" alt=""
                                                                style="width: 60px; z-index: 100;">
                                                        @endif
                                                        <div class="flex-grow-1">
                                                            <div class="name font-weight-bold">
                                                                {{ $pegawai->fullname }}
                                                            </div>
                                                            <div class="organization text-muted">
                                                                {{ $pegawai->organization->name }}
                                                            </div>
                                                            <div class="birthday text-muted">
                                                                Bergabung
                                                                {{ \Carbon\Carbon::parse($pegawai->join_date)->diffForHumans() }}
                                                                <br>
                                                                pada
                                                                {{ tgl($pegawai->join_date) }}
                                                            </div>
                                                        </div>
                                                        <a href="https://wa.me/{{ phone($pegawai->mobile_phone) }}"
                                                            class="badge badge-success p-2" target="_blank"><i
                                                                class='bx bxl-whatsapp m-0'></i></a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-5 d-flex chart">
            <div class="col-lg-4 mt-0 mb-2">
                <!--Default-->
                <div id="panel-1" class="panel h-100" draggable="false">
                    <div>
                        <h2 class="panel-heading">
                            Status Kepegawaian
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content" draggable="false">
                            <div class="progress">
                                <div class="progress-bar bg-info" role="progressbar" style="width: 65%"
                                    aria-valuenow="65" aria-valuemin="0" aria-valuemax="100" title=""></div>

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

                                <div class="d-flex justify-content-between my-3">
                                    <div class="label-circle bg-warning d-inline-block" style="width: 20px;">&nbsp;</div>
                                    <span class="ml-1">Kontrak</span>
                                    <span class="ml-auto">{{ $statusKepegawaian['jmlKontrak'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- <div class="col-lg-4">
                <!--Default-->
                <div id="panel-1" class="panel h-100">
                    <div>
                        <h2 class="panel-heading">
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
                    <div>
                        <h2 class="panel-heading">
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
                    <div>
                        <h2 class="panel-heading">
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

        <div class="row">
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">URL Shortener</h6>
                    </div>
                    <div class="card-body">
                        <!-- Form Create Short URL -->
                        <form id="shortenForm" method="POST" action="{{ route('dashboard.url_shortener.store') }}">
                            @csrf
                            <div class="form-row">
                                <div class="col-md-8 mb-3">
                                    <label for="original_url">URL Asli</label>
                                    <input type="url" class="form-control" id="original_url" name="original_url"
                                        required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="custom_code">Kode Kustom (opsional)</label>
                                    <input type="text" class="form-control" id="custom_code" name="custom_code">
                                </div>
                                <div class="col-md-1 mb-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary">Buat</button>
                                </div>
                            </div>
                        </form>

                        <!-- Hasil Short URL -->
                        @if (session('short_url'))
                            <div class="alert alert-success mt-3">
                                <p>Short URL berhasil dibuat:</p>
                                <div class="input-group">
                                    <input type="text" class="form-control" value="{{ session('short_url') }}"
                                        id="shortUrlInput" readonly>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button"
                                            onclick="copyShortUrl()">
                                            <i class="fas fa-copy"></i> Salin
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Daftar Link -->
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Daftar Link Saya</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="linksTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>URL Asli</th>
                                        <th>Short URL</th>
                                        <th>Kode</th>
                                        <th>Klik</th>
                                        <th>Dibuat</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($links as $link)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="text-truncate" style="max-width: 200px;">
                                                <a href="{{ $link->original_url }}"
                                                    target="_blank">{{ $link->original_url }}</a>
                                            </td>
                                            <td>
                                                <a href="{{ url('/links/' . $link->short_code) }}" target="_blank">
                                                    {{ url('/links/' . $link->short_code) }}
                                                </a>
                                            </td>
                                            <td>{{ $link->short_code }}</td>
                                            <td>{{ $link->clicks }}</td>
                                            <td>{{ $link->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-danger delete-link"
                                                    data-id="{{ $link->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div id="panel-10" class="panel">
            <div>
                <h2 class="panel-heading">
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
            <div>
                <h2 class="panel-heading">
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
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Ambil employee id dari user yang sedang login
            var employeeId = "{{ auth()->user()->employee->id }}";
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                editable: true,
                selectable: true,
                eventColor: '#dc3545', // Warna danger untuk employee-leaves
                events: '/api/employee-leaves/' + employeeId,
                dayCellDidMount: function(info) {
                    // Perbesar ukuran tanggal di setiap sel
                    var dayNumberEl = info.el.querySelector('.fc-daygrid-day-number');
                    if (dayNumberEl) {
                        dayNumberEl.style.fontSize = '20px';
                    }

                    // Tambahkan pin khusus di sel hari ini (bukan pada event)
                    var today = new Date();
                    if (info.date.toDateString() === today.toDateString()) {
                        var pin = document.createElement('span');
                        pin.innerHTML = '📌';
                        pin.style.position = 'absolute';
                        pin.style.top = '2px';
                        pin.style.left = '2px';
                        pin.style.fontSize = '24px';
                        info.el.style.position = 'relative';
                        info.el.appendChild(pin);
                    }
                },
                eventDidMount: function(info) {
                    // Center-kan teks pada event dan atur white-space
                    info.el.style.textAlign = 'center';
                    info.el.style.whiteSpace = 'normal';
                    // Jika event adalah ulang tahun, hanya perbesar mahkota dan biarkan nama pegawai kecil
                    if (info.event.extendedProps.eventType === 'birthday') {
                        info.el.innerHTML =
                            '<div style="display: flex; flex-direction: column; align-items: center;">' +
                            '<span style="font-size: 15pt;">🎂</span>' +
                            '</div>';
                    }
                },
                eventDrop: function(info) {
                    updateEvent(info.event);
                },
                eventResize: function(info) {
                    updateEvent(info.event);
                },
                select: function(info) {
                    Swal.fire({
                        title: 'Masukkan judul acara:',
                        input: 'text',
                        inputAttributes: {
                            style: 'height:40px; font-size:9px;'
                        },
                        showCancelButton: true,
                        confirmButtonText: 'Simpan',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.value) {
                            createEvent(result.value, info.startStr, info.endStr);
                        }
                        calendar.unselect();
                    });
                },
                eventClick: function(info) {
                    // Tampilkan info acara terlebih dahulu
                    Swal.fire({
                        title: info.event.title,
                        text: `Tanggal: ${info.event.start.toLocaleDateString()}`,
                        icon: 'info',
                        showDenyButton: true,
                        confirmButtonText: 'Tutup',
                        denyButtonText: 'Hapus acara'
                    }).then((result) => {
                        if (result.isDenied) {
                            Swal.fire({
                                title: 'Hapus acara ini?',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Ya',
                                cancelButtonText: 'Tidak'
                            }).then((deleteResult) => {
                                if (deleteResult.isConfirmed) {
                                    deleteEvent(info.event.id);
                                    info.event.remove();
                                }
                            });
                        }
                    });
                }
            });

            // Ambil data ulang tahun dan tambahkan ke kalender sebagai event
            fetch('/api/employee-birthdays')
                .then(response => response.json())
                .then(data => {
                    data.forEach(function(employee) {
                        calendar.addEvent({
                            id: employee.id,
                            title: employee.title,
                            start: employee.start,
                            allDay: true,
                            backgroundColor: employee.color,
                            borderColor: employee.color,
                            extendedProps: {
                                eventType: 'birthday'
                            }
                        });
                    });
                });

            calendar.render();

            // Fungsi untuk membuat acara
            function createEvent(title, start, end) {
                fetch('/api/events', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            title: title,
                            start: start,
                            end: end,
                            employee_id: employeeId
                        })
                    })
                    .then(response => response.json())
                    .then(event => calendar.addEvent(event));
            }

            // Fungsi untuk mengupdate acara
            function updateEvent(event) {
                fetch(`/api/events/${event.id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        title: event.title,
                        start: event.start.toISOString(),
                        end: event.end ? event.end.toISOString() : null,
                        employee_id: employeeId
                    })
                });
            }

            // Fungsi untuk menghapus acara
            function deleteEvent(id) {
                fetch(`/api/events/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json'
                    }
                });
            }
        });
    </script>

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
                    console.log("Konteks kanvas tidak ditemukan");
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

    {{-- Shortlink --}}
    <script>
        // Inisialisasi DataTable
        $(document).ready(function() {
            $('#linksTable').DataTable({
                responsive: true
            });
        });

        // Fungsi Copy URL
        function copyShortUrl() {
            const copyText = document.getElementById("shortUrlInput");
            copyText.select();
            document.execCommand("copy");
            alert("URL berhasil disalin: " + copyText.value);
        }

        // Delete Link
        $('.delete-link').click(function() {
            const linkId = $(this).data('id');

            if (confirm('Apakah Anda yakin ingin menghapus link ini?')) {
                $.ajax({
                    url: "{{ route('dashboard.url_shortener.delete', '') }}/" + linkId,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        location.reload();
                    },
                    error: function(xhr) {
                        alert('Gagal menghapus link');
                    }
                });
            }
        });
    </script>
@endsection
