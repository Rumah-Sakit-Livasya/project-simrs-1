@if ($registration != null)
    @if ($path === 'igd')
        <ul class="nav nav-tabs action-erm" role="tablist">
            <li class="nav-item mr-2">
                <a class="btn btn-outline-primary" id="toggle-pasien" data-action="toggle"
                    data-class="slide-on-mobile-left-show" data-target="#js-slide-left">
                    <i class="ni ni-menu"></i>
                </a>
            </li>
            @if ($path == 'igd')
                <li class="nav-item dropdown">
                    <a class="dropdown-item nav-link"
                        href="{{ route($path . '.catatan-medis', ['registration' => $registration->registration_number, 'menu' => 'triage']) }}"
                        role="tab">Triage</a>
                </li>
            @endif
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                    aria-haspopup="true" aria-expanded="false">Perawat</a>
                <div class="dropdown-menu">
                    <a class="dropdown-item"
                        href="{{ route($path . '.catatan-medis', ['registration' => $registration->registration_number, 'menu' => 'ews_anak']) }}"
                        role="tab">Early Warning Scoring System (Anak)</a>
                    <a class="dropdown-item"
                        href="{{ route($path . '.catatan-medis', ['registration' => $registration->registration_number, 'menu' => 'ews_dewasa']) }}"
                        role="tab">Early Warning Scoring System (Dewasa)</a>
                    <a class="dropdown-item"
                        href="{{ route($path . '.catatan-medis', ['registration' => $registration->registration_number, 'menu' => 'ews_obstetri']) }}"
                        role="tab">Early Warning Scoring System (Obstetri)</a>
                    <a class="dropdown-item"
                        href="{{ route($path . '.catatan-medis', ['registration' => $registration->registration_number, 'menu' => 'assesment_gadar']) }}"
                        role="tab">Assesment Keperawatan Gawat Darurat</a>
                    <a class="dropdown-item"
                        href="{{ route($path . '.catatan-medis', ['registration' => $registration->registration_number, 'menu' => 'cppt_perawat']) }}">CPPT</a>
                    <a class="dropdown-item"
                        href="{{ route($path . '.catatan-medis', ['registration' => $registration->registration_number, 'menu' => 'transfer_pasien_perawat']) }}">Transfer
                        Pasien Antar Ruangan</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                    aria-haspopup="true" aria-expanded="false">Dokter</a>
                <div class="dropdown-menu">
                    <a class="dropdown-item"
                        href="{{ route($path . '.catatan-medis', ['registration' => $registration->registration_number, 'menu' => 'ews_anak']) }}"
                        role="tab">Early Warning Scoring System (Anak)</a>
                    <a class="dropdown-item"
                        href="{{ route($path . '.catatan-medis', ['registration' => $registration->registration_number, 'menu' => 'ews_dewasa']) }}"
                        role="tab">Early Warning Scoring System (Dewasa)</a>
                    <a class="dropdown-item"
                        href="{{ route($path . '.catatan-medis', ['registration' => $registration->registration_number, 'menu' => 'ews_obstetri']) }}"
                        role="tab">Early Warning Scoring System (Obstetri)</a>
                    <a class="dropdown-item"
                        href="{{ route($path . '.catatan-medis', ['registration' => $registration->registration_number, 'menu' => 'pengkajian_dokter']) }}">Pengkajian</a>
                    <a class="dropdown-item"
                        href="{{ route($path . '.catatan-medis', ['registration' => $registration->registration_number, 'menu' => 'cppt_dokter']) }}">CPPT</a>
                    <a class="dropdown-item"
                        href="{{ route($path . '.catatan-medis', ['registration' => $registration->registration_number, 'menu' => 'resume_medis']) }}">Resume
                        Medis</a>
                    <a class="dropdown-item"
                        href="{{ route($path . '.catatan-medis', ['registration' => $registration->registration_number, 'menu' => 'rujuk_antar_rs']) }}">Rujuk
                        Antar Rumah Sakit</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                    aria-haspopup="true" aria-expanded="false">Gizi</a>
                <div class="dropdown-menu">
                    <a class="dropdown-item"
                        href="{{ route($path . '.catatan-medis', ['registration' => $registration->registration_number, 'menu' => 'pengkajian_gizi']) }}">Pengkajian</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                    aria-haspopup="true" aria-expanded="false">Farmasi Klinis</a>
                <div class="dropdown-menu">
                    <a class="dropdown-item"
                        href="{{ route($path . '.catatan-medis', ['registration' => $registration->registration_number, 'menu' => 'cppt_farmasi']) }}">CPPT</a>
                    <a class="dropdown-item"
                        href="{{ route($path . '.catatan-medis', ['registration' => $registration->registration_number, 'menu' => 'pengkajian_resep']) }}">Pengkajian
                        Resep</a>
                    <a class="dropdown-item"
                        href="{{ route($path . '.catatan-medis', ['registration' => $registration->registration_number, 'menu' => 'rekonsiliasi_obat']) }}">Form
                        Rekonsiliasi Obat</a>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link"
                    href="{{ route($path . '.catatan-medis', ['registration' => $registration->registration_number, 'menu' => 'pengkajian_lanjutan']) }}">Pengkajian
                    Lanjutan</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                    aria-haspopup="true" aria-expanded="false">Layanan</a>
                <div class="dropdown-menu">
                    <a class="dropdown-item"
                        href="{{ route($path . '.catatan-medis', ['registration' => $registration->registration_number, 'menu' => 'tindakan_medis']) }}">Tindakan
                        Medis</a>
                    <a class="dropdown-item"
                        href="{{ route($path . '.catatan-medis', ['registration' => $registration->registration_number, 'menu' => 'pemakaian_alat']) }}">Pemakaian
                        Alat</a>
                    <a class="dropdown-item"
                        href="{{ route($path . '.catatan-medis', ['registration' => $registration->registration_number, 'menu' => 'patologi_klinik']) }}">Patologi
                        Klinik</a>
                    <a class="dropdown-item"
                        href="{{ route($path . '.catatan-medis', ['registration' => $registration->registration_number, 'menu' => 'radiologi']) }}">Radiologi</a>
                </div>
            </li>
        </ul>
    @elseif($path === 'poliklinik')
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
                        href="{{ route($path . '.daftar-pasien', ['registration' => $registration->registration_number, 'menu' => 'pengkajian_perawat']) }}"
                        role="tab">Pengkajian</a>
                    <a class="dropdown-item"
                        href="{{ route($path . '.daftar-pasien', ['registration' => $registration->registration_number, 'menu' => 'cppt_perawat']) }}">CPPT</a>
                    <a class="dropdown-item"
                        href="{{ route($path . '.daftar-pasien', ['registration' => $registration->registration_number, 'menu' => 'transfer_pasien_perawat']) }}">Transfer
                        Pasien Antar Ruangan</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                    aria-haspopup="true" aria-expanded="false">Dokter</a>
                <div class="dropdown-menu">
                    <a class="dropdown-item"
                        href="{{ route($path . '.daftar-pasien', ['registration' => $registration->registration_number, 'menu' => 'pengkajian_dokter']) }}">Pengkajian</a>
                    <a class="dropdown-item"
                        href="{{ route($path . '.daftar-pasien', ['registration' => $registration->registration_number, 'menu' => 'cppt_dokter']) }}">CPPT</a>
                    <a class="dropdown-item"
                        href="{{ route($path . '.daftar-pasien', ['registration' => $registration->registration_number, 'menu' => 'resume_medis']) }}">Resume
                        Medis</a>
                    <a class="dropdown-item"
                        href="{{ route($path . '.daftar-pasien', ['registration' => $registration->registration_number, 'menu' => 'rujuk_antar_rs']) }}">Rujuk
                        Antar Rumah Sakit</a>
                    <a class="dropdown-item"
                        href="{{ route($path . '.daftar-pasien', ['registration' => $registration->registration_number, 'menu' => 'profil_ringkas_rajal']) }}">Profil
                        Ringkas Rawat Jalan</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                    aria-haspopup="true" aria-expanded="false">Gizi</a>
                <div class="dropdown-menu">
                    <a class="dropdown-item"
                        href="{{ route($path . '.daftar-pasien', ['registration' => $registration->registration_number, 'menu' => 'pengkajian_gizi']) }}">Pengkajian</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                    aria-haspopup="true" aria-expanded="false">Farmasi Klinis</a>
                <div class="dropdown-menu">
                    <a class="dropdown-item"
                        href="{{ route($path . '.daftar-pasien', ['registration' => $registration->registration_number, 'menu' => 'cppt_farmasi']) }}">CPPT</a>
                    <a class="dropdown-item"
                        href="{{ route($path . '.daftar-pasien', ['registration' => $registration->registration_number, 'menu' => 'pengkajian_resep']) }}">Pengkajian
                        Resep</a>
                    <a class="dropdown-item"
                        href="{{ route($path . '.daftar-pasien', ['registration' => $registration->registration_number, 'menu' => 'rekonsiliasi_obat']) }}">Form
                        Rekonsiliasi Obat</a>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link"
                    href="{{ route($path . '.daftar-pasien', ['registration' => $registration->registration_number, 'menu' => 'pengkajian_lanjutan']) }}">Pengkajian
                    Lanjutan</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                    aria-haspopup="true" aria-expanded="false">Layanan</a>
                <div class="dropdown-menu">
                    <a class="dropdown-item"
                        href="{{ route($path . '.daftar-pasien', ['registration' => $registration->registration_number, 'menu' => 'tindakan_medis']) }}">Tindakan
                        Medis</a>
                    <a class="dropdown-item"
                        href="{{ route($path . '.daftar-pasien', ['registration' => $registration->registration_number, 'menu' => 'pemakaian_alat']) }}">Pemakaian
                        Alat</a>
                    <a class="dropdown-item"
                        href="{{ route($path . '.daftar-pasien', ['registration' => $registration->registration_number, 'menu' => 'patologi_klinik']) }}">Patologi
                        Klinik</a>
                    <a class="dropdown-item"
                        href="{{ route($path . '.daftar-pasien', ['registration' => $registration->registration_number, 'menu' => 'radiologi']) }}">Radiologi</a>
                </div>
            </li>
        </ul>
    @elseif($path === 'rawat-inap')
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
                        href="{{ route($path . '.catatan-medis', ['registration' => $registration->registration_number, 'menu' => 'pengkajian_perawat']) }}"
                        role="tab">Pengkajian</a>
                    <a class="dropdown-item"
                        href="{{ route($path . '.catatan-medis', ['registration' => $registration->registration_number, 'menu' => 'cppt_perawat']) }}">CPPT</a>
                    <a class="dropdown-item"
                        href="{{ route($path . '.catatan-medis', ['registration' => $registration->registration_number, 'menu' => 'transfer_pasien_perawat']) }}">Transfer
                        Pasien Antar Ruangan</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                    aria-haspopup="true" aria-expanded="false">Dokter</a>
                <div class="dropdown-menu">
                    <a class="dropdown-item"
                        href="{{ route($path . '.catatan-medis', ['registration' => $registration->registration_number, 'menu' => 'pengkajian_dokter']) }}">Pengkajian</a>
                    <a class="dropdown-item"
                        href="{{ route($path . '.catatan-medis', ['registration' => $registration->registration_number, 'menu' => 'cppt_dokter']) }}">CPPT</a>
                    <a class="dropdown-item"
                        href="{{ route($path . '.catatan-medis', ['registration' => $registration->registration_number, 'menu' => 'resume_medis']) }}">Resume
                        Medis</a>
                    <a class="dropdown-item"
                        href="{{ route($path . '.catatan-medis', ['registration' => $registration->registration_number, 'menu' => 'rujuk_antar_rs']) }}">Rujuk
                        Antar Rumah Sakit</a>
                    <a class="dropdown-item"
                        href="{{ route($path . '.catatan-medis', ['registration' => $registration->registration_number, 'menu' => 'profil_ringkas_rajal']) }}">Profil
                        Ringkas Rawat Jalan</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                    aria-haspopup="true" aria-expanded="false">Gizi</a>
                <div class="dropdown-menu">
                    <a class="dropdown-item"
                        href="{{ route($path . '.catatan-medis', ['registration' => $registration->registration_number, 'menu' => 'pengkajian_gizi']) }}">Pengkajian</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                    aria-haspopup="true" aria-expanded="false">Farmasi Klinis</a>
                <div class="dropdown-menu">
                    <a class="dropdown-item"
                        href="{{ route($path . '.catatan-medis', ['registration' => $registration->registration_number, 'menu' => 'cppt_farmasi']) }}">CPPT</a>
                    <a class="dropdown-item"
                        href="{{ route($path . '.catatan-medis', ['registration' => $registration->registration_number, 'menu' => 'pengkajian_resep']) }}">Pengkajian
                        Resep</a>
                    <a class="dropdown-item"
                        href="{{ route($path . '.catatan-medis', ['registration' => $registration->registration_number, 'menu' => 'rekonsiliasi_obat']) }}">Form
                        Rekonsiliasi Obat</a>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link"
                    href="{{ route($path . '.catatan-medis', ['registration' => $registration->registration_number, 'menu' => 'resep_harian']) }}">Resep
                    Harian</a>
            </li>
            <li class="nav-item">
                <a class="nav-link"
                    href="{{ route($path . '.catatan-medis', ['registration' => $registration->registration_number, 'menu' => 'pengkajian_lanjutan']) }}">Pengkajian
                    Lanjutan</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                    aria-haspopup="true" aria-expanded="false">Layanan</a>
                <div class="dropdown-menu">
                    <a class="dropdown-item"
                        href="{{ route($path . '.catatan-medis', ['registration' => $registration->registration_number, 'menu' => 'tindakan_medis']) }}">Tindakan
                        Medis</a>
                    <a class="dropdown-item"
                        href="{{ route($path . '.catatan-medis', ['registration' => $registration->registration_number, 'menu' => 'pemakaian_alat']) }}">Pemakaian
                        Alat</a>
                    <a class="dropdown-item"
                        href="{{ route($path . '.catatan-medis', ['registration' => $registration->registration_number, 'menu' => 'patologi_klinik']) }}">Patologi
                        Klinik</a>
                    <a class="dropdown-item"
                        href="{{ route($path . '.catatan-medis', ['registration' => $registration->registration_number, 'menu' => 'radiologi']) }}">Radiologi</a>
                </div>
            </li>
        </ul>
    @endif
@endif
