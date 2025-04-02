@if ($registration != null)
    @php
        $routePrefix = 'poliklinik';
        if ($registration->registration_type == 'rawat-inap') {
            $routePrefix = 'rawat-inap';
        } elseif ($registration->registration_type == 'igd') {
            $routePrefix = 'igd';
        }
    @endphp

    <ul class="nav nav-tabs action-erm" role="tablist">
        <li class="nav-item mr-2">
            <a class="btn btn-outline-primary" id="toggle-pasien" data-action="toggle"
                data-class="slide-on-mobile-left-show" data-target="#js-slide-left">
                <i class="ni ni-menu"></i>
            </a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                aria-haspopup="true" aria-expanded="false">Perawat</a>
            <div class="dropdown-menu">
                <a class="dropdown-item"
                    href="{{ route($routePrefix . '.daftar-pasien', ['registration' => $registration->registration_number, 'menu' => 'pengkajian_perawat']) }}"
                    role="tab">Pengkajian</a>
                <a class="dropdown-item"
                    href="{{ route($routePrefix . '.daftar-pasien', ['registration' => $registration->registration_number, 'menu' => 'cppt_perawat']) }}">CPPT</a>
                <a class="dropdown-item"
                    href="{{ route($routePrefix . '.daftar-pasien', ['registration' => $registration->registration_number, 'menu' => 'transfer_pasien_perawat']) }}">Transfer
                    Pasien Antar Ruangan</a>
            </div>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                aria-haspopup="true" aria-expanded="false">Dokter</a>
            <div class="dropdown-menu">
                <a class="dropdown-item"
                    href="{{ route($routePrefix . '.daftar-pasien', ['registration' => $registration->registration_number, 'menu' => 'pengkajian_dokter']) }}">Pengkajian</a>
                <a class="dropdown-item"
                    href="{{ route($routePrefix . '.daftar-pasien', ['registration' => $registration->registration_number, 'menu' => 'cppt_dokter']) }}">CPPT</a>
                <a class="dropdown-item"
                    href="{{ route($routePrefix . '.daftar-pasien', ['registration' => $registration->registration_number, 'menu' => 'resume_medis_rajal']) }}">Resume
                    Medis</a>
                <a class="dropdown-item"
                    href="{{ route($routePrefix . '.daftar-pasien', ['registration' => $registration->registration_number, 'menu' => 'profil_ringkas_rajal']) }}">Profil
                    Ringkas Rawat Jalan</a>
            </div>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                aria-haspopup="true" aria-expanded="false">Gizi</a>
            <div class="dropdown-menu">
                <a class="dropdown-item"
                    href="{{ route($routePrefix . '.daftar-pasien', ['registration' => $registration->registration_number, 'menu' => 'pengkajian_gizi']) }}">Pengkajian</a>
            </div>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                aria-haspopup="true" aria-expanded="false">Farmasi Klinis</a>
            <div class="dropdown-menu">
                <a class="dropdown-item"
                    href="{{ route($routePrefix . '.daftar-pasien', ['registration' => $registration->registration_number, 'menu' => 'cppt_farmasi']) }}">CPPT</a>
                <a class="dropdown-item"
                    href="{{ route($routePrefix . '.daftar-pasien', ['registration' => $registration->registration_number, 'menu' => 'pengkajian_resep']) }}">Pengkajian
                    Resep</a>
                <a class="dropdown-item"
                    href="{{ route($routePrefix . '.daftar-pasien', ['registration' => $registration->registration_number, 'menu' => 'rekonsiliasi_obat']) }}">Form
                    Rekonsiliasi Obat</a>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link"
                href="{{ route($routePrefix . '.daftar-pasien', ['registration' => $registration->registration_number, 'menu' => 'pengkajian_lanjutan']) }}">Pengkajian
                Lanjutan</a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                aria-haspopup="true" aria-expanded="false">Layanan</a>
            <div class="dropdown-menu">
                <a class="dropdown-item"
                    href="{{ route($routePrefix . '.daftar-pasien', ['registration' => $registration->registration_number, 'menu' => 'tindakan_medis']) }}">Tindakan
                    Medis</a>
                <a class="dropdown-item"
                    href="{{ route($routePrefix . '.daftar-pasien', ['registration' => $registration->registration_number, 'menu' => 'pemakaian_alat']) }}">Pemakaian
                    Alat</a>
                <a class="dropdown-item"
                    href="{{ route($routePrefix . '.daftar-pasien', ['registration' => $registration->registration_number, 'menu' => 'patologi_klinik']) }}">Patologi
                    Klinik</a>
                <a class="dropdown-item"
                    href="{{ route($routePrefix . '.daftar-pasien', ['registration' => $registration->registration_number, 'menu' => 'radiologi']) }}">Radiologi</a>
            </div>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button">Lain-lain</a>
            <div class="dropdown-menu">
                <a class="dropdown-item">Pengkajian</a>
                <a class="dropdown-item" href="#">CPPT</a>
                <a class="dropdown-item" href="#">Transfer Pasien Antar Ruangan</a>
            </div>
        </li>
    </ul>
@endif
