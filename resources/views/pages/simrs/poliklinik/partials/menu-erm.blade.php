<ul class="nav nav-tabs action-erm" role="tablist">
    <li class="nav-item mr-2">
        <a class="btn btn-outline-primary" id="toggle-pasien" data-action="toggle" data-class="slide-on-mobile-left-show"
            data-target="#js-slide-left">
            <i class="ni ni-menu"></i>
        </a>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
            aria-expanded="false">Perawat</a>
        <div class="dropdown-menu">
            <a class="dropdown-item"
                href="{{ route('poliklinik.daftar-pasien', ['registration' => 2412170001, 'menu' => 'pengkajian_perawat']) }}"
                role="tab">Pengkajian</a>
            <a class="dropdown-item"
                href="{{ route('poliklinik.daftar-pasien', ['registration' => 2412170001, 'menu' => 'cppt_perawat']) }}">CPPT</a>
            <a class="dropdown-item"
                href="{{ route('poliklinik.daftar-pasien', ['registration' => 2412170001, 'menu' => 'transfer_pasien_perawat']) }}">Transfer
                Pasien Antar Ruangan</a>
        </div>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
            aria-expanded="false">Dokter</a>
        <div class="dropdown-menu">
            <a class="dropdown-item"
                href="{{ route('poliklinik.daftar-pasien', ['registration' => 2412170001, 'menu' => 'pengkajian_dokter']) }}">Pengkajian</a>
            <a class="dropdown-item"
                href="{{ route('poliklinik.daftar-pasien', ['registration' => 2412170001, 'menu' => 'cppt_dokter']) }}">CPPT</a>
            <a class="dropdown-item"
                href="{{ route('poliklinik.daftar-pasien', ['registration' => 2412170001, 'menu' => 'resume_medis_rajal']) }}">Transfer
                Pasien Antar Ruangan</a>
        </div>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
            aria-expanded="false">Gizi</a>
        <div class="dropdown-menu">
            <a class="dropdown-item"
                href="{{ route('poliklinik.daftar-pasien', ['registration' => 2412170001, 'menu' => 'pengkajian_gizi']) }}">Pengkajian</a>
        </div>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
            aria-expanded="false">Farmasi Klinis</a>
        <div class="dropdown-menu">
            <a class="dropdown-item"
                href="{{ route('poliklinik.daftar-pasien', ['registration' => 2412170001, 'menu' => 'cppt_farmasi']) }}">CPPT</a>
            <a class="dropdown-item"
                href="{{ route('poliklinik.daftar-pasien', ['registration' => 2412170001, 'menu' => 'pengkajian_resep']) }}">Pengkajian
                Resep</a>
            <a class="dropdown-item"
                href="{{ route('poliklinik.daftar-pasien', ['registration' => 2412170001, 'menu' => 'rekonsiliasi_obat']) }}">Form
                Rekonsiliasi Obat</a>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('poliklinik.daftar-pasien', ['registration' => 2412170001, 'menu' => 'pengkajian_lanjutan']) }}">Pengkajian Lanjutan</a>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
            aria-expanded="false">Layanan</a>
        <div class="dropdown-menu">
            <a class="dropdown-item" href="{{ route('poliklinik.daftar-pasien', ['registration' => 2412170001, 'menu' => 'tindakan_medis']) }}">Tindakan Medis</a>
            <a class="dropdown-item" href="{{ route('poliklinik.daftar-pasien', ['registration' => 2412170001, 'menu' => 'pemakaian_alat']) }}">Pemakaian Alat</a>
            <a class="dropdown-item" href="{{ route('poliklinik.daftar-pasien', ['registration' => 2412170001, 'menu' => 'patologi_klinik']) }}">Patologi Klinik</a>
            <a class="dropdown-item" href="{{ route('poliklinik.daftar-pasien', ['registration' => 2412170001, 'menu' => 'radiologi']) }}">Radiologi</a>
        </div>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
            aria-expanded="false">Lain-lain</a>
        <div class="dropdown-menu">
            <a class="dropdown-item" data-toggle="tab" href="#tab_default-2" role="tab">Pengkajian</a>
            <a class="dropdown-item" href="#">CPPT</a>
            <a class="dropdown-item" href="#">Transfer Pasien Antar Ruangan</a>
        </div>
    </li>
</ul>
