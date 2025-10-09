@extends('inc.layout')
@section('content')
    <style>
        .biodata-pasien {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .btn-biodata {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            grid-gap: 5px;
            margin: 10px;
        }

        .btn-flatcx {
            width: 30px;
            height: 30px;
            line-height: 30px;
            border: 1px solid #ccc;
            color: var(--primary-color);
            font-size: 1.5em;
            border-radius: 50%;
            text-align: center;
            vertical-align: middle;
        }

        .form-control[disabled],
        .form-control[readonly],
        fieldset[disabled] .form-control {
            background-color: transparent;
            border-bottom-color: rgba(12, 12, 12, 0.2);
            border-bottom-style: dashed;
            outline: none;
            border-top: none;
            border-right: none;
            border-left: none;
        }


        li.blue-box {
            background: #eef5fd;
            color: #3F51B5;
        }

        li.red-box {
            background: #fff4f7;
            color: #F44336;
        }

        li.green-box {
            background: #f1fdda;
            color: #8BC34A;
        }

        li.cyan-box {
            background: #edfbfd;
            color: #00BCD4;
        }

        li.orange-box {
            background: #fff1dc;
            color: #FF9800;
        }

        li.purple-box {
            background: #f5e8f7;
            color: #9C27B0;
        }

        li.brown-box {
            background: #efdad2;
            color: #ab6e58;
        }

        .box-menu li {
            padding: 20px 30px;
            margin: 20px;
            width: 200px;
            background: #f2f0f5;
            text-align: center;
            cursor: pointer;
            border: 1px solid #e5e5e5;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            box-shadow: 0 3px 3px 0 rgba(0, 0, 0, 0.33);
        }

        .box-menu .circle-menu {
            height: 50px;
            width: 50px;
            line-height: 50px;
            font-size: 2.5em;
            transition: all .15s linear;
        }

        @media (min-width: 992px) {
            .custom-modal {
                max-width: 1300px !important;
            }
        }
    </style>

    <main id="js-page-content" role="main" class="page-content">
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            <i class='bx bxs-id-card' style="transform: scale(1.5); margin-right: .5rem;"></i>
                            Biodata <span class="fw-300"><i>Pasien</i></span>
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <div class="row">
                                <div class="col-md-2 biodata-pasien">
                                    @if ($patient->gender == 'Laki-laki')
                                        <img src="/img/user/man-icon.png" style="width: 120px; height: 120px;">
                                    @else
                                        <img src="/img/user/woman-icon.png" style="width: 120px; height: 120px;">
                                    @endif
                                    <div class="btn-biodata">
                                        <button class="btn-flatcx pointer" data-toggle="modal"
                                            data-target="#riwayat-kunjungan" title="Riwayat Kunjungan">
                                            <i class="mdi mdi-clipboard-pulse"></i>
                                        </button>
                                        <button class="btn-flatcx" id="button" alt="Detail Biodata Pasien"
                                            title="Detail Biodata Pasien"><i class="mdi mdi-account-edit"></i></button>
                                    </div>
                                </div>
                                <div class="col-md-10 col-bg-10">
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="row align-items-center">
                                                    <label for="s_tgl_1" class="col-md-4 control-label">No Rekam
                                                        Medis</label>
                                                    <div class="col-md">
                                                        <input class="form-control" type="text"
                                                            value="{{ $patient->medical_record_number }}"
                                                            readonly="readonly">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="row align-items-center">
                                                    <label for="s_tgl_1" class="col-md-4 control-label">Jenis
                                                        Kelamin</label>
                                                    <div class="col-md">
                                                        <input class="form-control" type="text"
                                                            value="{{ $patient->gender }}" readonly="readonly">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="row align-items-center">
                                                    <label for="s_tgl_1" class="col-md-4 control-label">Nama Pasien</label>
                                                    <div class="col-md">
                                                        <input class="form-control" type="text"
                                                            value="{{ $patient->name }}" readonly="readonly">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="row align-items-center">
                                                    <label for="s_tgl_1" class="col-md-4 control-label">Alamat</label>
                                                    <div class="col-md">
                                                        <input class="form-control" type="text"
                                                            value="{{ $patient->address }}" readonly="readonly">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="row align-items-center">
                                                    <label for="s_tgl_1" class="col-md-4 control-label">Tempat, Tgl.
                                                        Lahir</label>
                                                    <div class="col-md">
                                                        <input class="form-control" type="text"
                                                            value="{{ $patient->place }}, {{ $patient->date_of_birth }}"
                                                            readonly="readonly">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="row align-items-center">
                                                    <label for="s_tgl_1" class="col-md-4 control-label">Telp/HP</label>
                                                    <div class="col-md">
                                                        <input class="form-control" type="text"
                                                            value="{{ $patient->mobile_phone_number }}" readonly="readonly">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="row align-items-center">
                                                    <label for="s_tgl_1" class="col-md-4 control-label">Umur</label>
                                                    <div class="col-md">
                                                        <input class="form-control" type="text"
                                                            value="{{ $age }}" readonly="readonly">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="row align-items-center">
                                                    <label for="s_tgl_1" class="col-md-4 control-label">Catatan
                                                        Penting</label>
                                                    <div class="col-md">
                                                        <input class="form-control" type="text" value=""
                                                            readonly="readonly">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row justify-content-end mt-3">
                                        <div class="col-md-4">
                                            <button class="btn btn-primary pull-right waves-effect" id="kartu">
                                                <i class="mdi mdi-printer"></i> Kartu Pasien
                                            </button>
                                            <button class="btn btn-primary pull-right waves-effect" id="identitas"><i
                                                    class="mdi mdi-printer"></i> Identitas Pasien</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div id="panel-2" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            <i class='bx bxs-user-plus' style="transform: scale(1.5); margin-right: .5rem;"></i>
                            Pendaftaran <span class="fw-300"><i>Registrasi Baru</i></span>
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">

                            {{-- ======================================================= --}}
                            {{-- ===== LOGIKA UTAMA ADA DI SINI ======================== --}}
                            {{-- ======================================================= --}}

                            @if ($patient->status == 'digabung')
                                <div class="d-flex flex-column align-items-center gap-2">
                                    <div class="mb-3">
                                        <span
                                            class="d-inline-flex align-items-center justify-content-center rounded-circle bg-white border border-info shadow-sm"
                                            style="width: 64px; height: 64px;">
                                            <i class="mdi mdi-information-outline text-info"
                                                style="font-size: 2.2rem;"></i>
                                        </span>
                                    </div>
                                    <h3 class="fw-bold text-info mb-2">
                                        Data Pasien Digabungkan
                                    </h3>
                                    <p class="fs-5 text-secondary mb-3" style="max-width: 420px; margin: 0 auto;">
                                        Pasien ini <span class="fw-semibold text-danger">tidak dapat didaftarkan</span>
                                        karena datanya telah digabungkan dengan nomor rekam medis lain.
                                    </p>
                                    <div class="mb-2">
                                        <span class="text-muted small">Gunakan Nomor Rekam Medis baru berikut:</span>
                                    </div>
                                    @php
                                        $mergedPatient = \App\Models\SIMRS\Patient::where(
                                            'medical_record_number',
                                            $patient->merged_to_rm,
                                        )->first();
                                    @endphp
                                    <a href="{{ route('detail.pendaftaran.pasien', ['patient' => $mergedPatient?->id]) }}"
                                        class="btn btn-outline-info btn-lg fw-semibold px-5 py-2 d-inline-flex align-items-center gap-2 mt-2 shadow-sm"
                                        style="font-size: 1.15rem;">
                                        <i class="mdi mdi-account-card-details-outline"></i>
                                        <span>{{ $patient->merged_to_rm }}</span>
                                    </a>
                                </div>
                            @else
                                {{-- Jika status pasien AKTIF, tampilkan menu registrasi --}}
                                <ul class="box-menu list-unstyled row text-center gy-3 justify-content-center">
                                    <li class="col-lg-2 col-md-3 col-sm-4 col-6 p-3">
                                        <a href="{{ route('form.registrasi', ['patient' => $patient->id, 'registrasi' => 'rawat-jalan']) }}"
                                            class="d-block text-decoration-none service-link">
                                            <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center bg-primary shadow border border-white"
                                                style="width: 64px; height: 64px;">
                                                <i class="mdi mdi-stethoscope text-white h1 mt-2"></i>
                                            </div>
                                            <h5 class="mt-3 fw-semibold text-primary">Rawat Jalan</h5>
                                        </a>
                                    </li>
                                    <li class="col-lg-2 col-md-3 col-sm-4 col-6 p-3">
                                        <a href="{{ route('form.registrasi', ['patient' => $patient->id, 'registrasi' => 'igd']) }}"
                                            class="d-block text-decoration-none service-link">
                                            <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center bg-danger shadow border border-white"
                                                style="width: 64px; height: 64px;">
                                                <i class="mdi mdi-hospital text-white h1 mt-2"></i>
                                            </div>
                                            <h5 class="mt-3 fw-semibold text-danger">IGD</h5>
                                        </a>
                                    </li>
                                    <li class="col-lg-2 col-md-3 col-sm-4 col-6 p-3">
                                        <a href="{{ route('form.registrasi', ['patient' => $patient->id, 'registrasi' => 'odc']) }}"
                                            class="d-block text-decoration-none service-link">
                                            <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center bg-success shadow border border-white"
                                                style="width: 64px; height: 64px;">
                                                <i class="mdi mdi-bed text-white h1 mt-2"></i>
                                            </div>
                                            <h5 class="mt-3 fw-semibold text-success">ODC</h5>
                                        </a>
                                    </li>
                                    <li class="col-lg-2 col-md-3 col-sm-4 col-6 p-3">
                                        <a href="{{ route('form.registrasi', ['patient' => $patient->id, 'registrasi' => 'rawat-inap']) }}"
                                            class="d-block text-decoration-none service-link">
                                            <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center bg-info shadow border border-white"
                                                style="width: 64px; height: 64px;">
                                                <i class="mdi mdi-bed text-white h1 mt-2"></i>
                                            </div>
                                            <h5 class="mt-3 fw-semibold text-info">Rawat Inap</h5>
                                        </a>
                                    </li>
                                    <li class="col-lg-2 col-md-3 col-sm-4 col-6 p-3">
                                        <a href="{{ route('form.registrasi', ['patient' => $patient->id, 'registrasi' => 'laboratorium']) }}"
                                            class="d-block text-decoration-none service-link">
                                            <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center bg-warning shadow border border-white"
                                                style="width: 64px; height: 64px;">
                                                <i class="mdi mdi-flask-outline text-white h1 mt-2"></i>
                                            </div>
                                            <h5 class="mt-3 fw-semibold text-warning">Laboratorium</h5>
                                        </a>
                                    </li>
                                    <li class="col-lg-2 col-md-3 col-sm-4 col-6 p-3">
                                        <a href="{{ route('form.registrasi', ['patient' => $patient->id, 'registrasi' => 'radiologi']) }}"
                                            class="d-block text-decoration-none service-link">
                                            <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center bg-purple shadow border border-white"
                                                style="width: 64px; height: 64px; background-color: #6f42c1;">
                                                <i class="mdi mdi-radioactive text-white h1 mt-2"></i>
                                            </div>
                                            <h5 class="mt-3 fw-semibold" style="color: #6f42c1;">Radiologi</h5>
                                        </a>
                                    </li>
                                    <li class="col-lg-2 col-md-3 col-sm-4 col-6 p-3">
                                        <a href="{{ route('form.registrasi', ['patient' => $patient->id, 'registrasi' => 'hemodialisa']) }}"
                                            class="d-block text-decoration-none service-link">
                                            <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center bg-secondary shadow border border-white"
                                                style="width: 64px; height: 64px; background-color: #ffc107;">
                                                <i class="mdi mdi-high-definition-box text-white h1 mt-2"></i>
                                            </div>
                                            <h5 class="mt-3 fw-semibold" style="color: #ffc107;">Hemodialisa</h5>
                                        </a>
                                    </li>
                                </ul>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    @include('pages.simrs.pendaftaran.form.riwayat-kunjungan-form')
@endsection
@section('plugin')
    <!-- JavaScript untuk menampilkan pop-up Edit -->
    <script>
        // Mendapatkan referensi tombol berdasarkan ID
        var button = document.getElementById('button');
        var identitas = document.getElementById('identitas');
        var kunjungan = document.getElementById('kunjungan');
        var kartu = document.getElementById('kartu');
        var width = window.screen.width;
        var height = window.screen.height;

        // Menambahkan event listener untuk tombol
        button.addEventListener('click', function() {
            // Membuka pop-up window saat tombol diklik
            window.open('{{ route('edit.pendaftaran.pasien', $patient->id) }}', '_blank', 'width=500' + width +
                ',height=' + height);
        });
        identitas.addEventListener('click', function() {
            // Membuka pop-up window saat tombol diklik
            window.open('{{ route('print.identitas.pasien', $patient->id) }}', '_blank', 'width=500' + width +
                ',height=' + height);
        });
        kartu.addEventListener('click', function() {
            // Membuka pop-up window untuk mencetak langsung
            window.open('{{ route('print.kartu.pasien', $patient->id) }}', '_blank',
                'width=400,height=400');
        });
        kunjungan.addEventListener('click', function() {
            // Membuka pop-up window saat tombol diklik
            window.open('{{ route('history.kunjungan.pasien', $patient->id) }}', '_blank', 'width=500' + width +
                ',height=' + height);
        });
    </script>

    {{-- TAMBAHKAN SCRIPT BARU DI BAWAH INI --}}
    <script>
        // Pastikan dokumen sudah dimuat sepenuhnya
        document.addEventListener('DOMContentLoaded', function() {
            // Pilih semua tautan menu layanan berdasarkan class yang kita tambahkan
            const serviceLinks = document.querySelectorAll('.service-link');

            // Loop melalui setiap tautan dan tambahkan event listener 'click'
            serviceLinks.forEach(function(link) {
                link.addEventListener('click', function(event) {
                    // Temukan ikon <i> di dalam tautan yang diklik
                    const icon = link.querySelector('i');

                    // Jika ikon ditemukan
                    if (icon) {
                        // Ganti kelas ikon menjadi kelas spinner dari FontAwesome
                        // Template Anda sudah menggunakan ikon 'fal', jadi kita gunakan ini
                        icon.className = 'fal fa-spinner fa-spin';
                    }

                    // Navigasi ke halaman tujuan akan dilanjutkan secara otomatis
                });
            });
        });
    </script>
@endsection
