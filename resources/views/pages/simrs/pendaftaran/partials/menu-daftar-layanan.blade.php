<div class="panel-hdr border-top">
    <h2 class="text-light">
        <i class="fas fa-address-card mr-3 ml-2 text-primary" style="transform: scale(2.1)"></i>
        <span class="text-primary">Daftar Layanan</span>
    </h2>
</div>
<div class="panel-container show">
    <div class="card">
        <div class="card-body style-default-bright">
            <div class="row">
                <ul id="patient-menu-icon">
                    <li class="text-center">
                        @php
                            $catatanMedisRoute = match ($registration->registration_type) {
                                'rawat-jalan' => route('poliklinik.daftar-pasien', [
                                    'menu' => 'pengkajian_perawat',
                                    'registration' => $registration->registration_number,
                                ]),
                                'rawat-inap' => route('rawat-inap.daftar-pasien', [
                                    'menu' => 'pengkajian_perawat',
                                    'registration' => $registration->registration_number,
                                ]),
                                'igd' => route('igd.catatan-medis', [
                                    'menu' => 'pengkajian_perawat',
                                    'registration' => $registration->registration_number,
                                ]),
                                default => '#',
                            };
                        @endphp
                        <a href="{{ $catatanMedisRoute }}">
                            <center>
                                <div class="circle-menu waves-effect pink accent-2"
                                    data-layanan="pengkajian-nurse-rajal">
                                    <i class="mdi mdi-clipboard-pulse"></i>
                                </div>
                                <span>Catatan Medis</span>
                            </center>
                        </a>
                    </li>
                    <li class="text-center">
                        <a
                            href="{{ route('detail.registrasi.pasien.layanan', ['registrations' => $registration->id, 'layanan' => 'tindakan-medis']) }}">
                            <div class="circle-menu waves-effect light-green accent-3 menu-layanan"
                                data-layanan="tindakan-medis">
                                <i class="mdi mdi-needle"></i>
                            </div>
                            <span>Tindakan Medis</span>
                        </a>
                    </li>
                    <li>
                        <div class="circle-menu waves-effect amber lighten-1 menu-layanan"
                            data-layanan="obatalkes_ruangan/list_obat_alkes">
                            <i class="mdi mdi-pill"></i>
                        </div>
                        <span>Obat/Alkes</span>
                    </li>
                    <li class="text-center">
                        <a
                            href="{{ route('detail.registrasi.pasien.layanan', ['registrations' => $registration->id, 'layanan' => 'laboratorium']) }}">
                            <div class="circle-menu waves-effect purple accent-3 menu-layanan"
                                data-layanan="laboratorium">
                                <i class="mdi mdi-flask-outline"></i>
                            </div>
                            <span>Laboratorium</span>
                        </a>
                    </li>
                    <li class="text-center">
                        <a
                            href="{{ route('detail.registrasi.pasien.layanan', ['registrations' => $registration->id, 'layanan' => 'radiologi']) }}">
                            <div class="circle-menu waves-effect red accent-3 menu-layanan" data-layanan="radiologi">
                                <i class="mdi mdi-radioactive"></i>
                            </div>
                            <span>Radiologi</span>
                        </a>
                    </li>
                    <li class="text-center">
                        <a
                            href="{{ route('detail.registrasi.pasien.layanan', ['registrations' => $registration->id, 'layanan' => 'pemakaian-alat']) }}">
                            <div class="circle-menu waves-effect blue accent-3 menu-layanan"
                                data-layanan="pemakaian-alat">
                                <i class="mdi mdi-source-merge"></i>
                            </div>
                            <span>Pemakaian Alat</span>
                        </a>
                    </li>
                    <li class="text-center">
                        <a
                            href="{{ route('detail.registrasi.pasien.layanan', ['registrations' => $registration->id, 'layanan' => 'visite-dokter']) }}">
                            <div class="circle-menu waves-effect blue accent-3 menu-layanan"
                                data-layanan="visite-dokter">
                                <i class="mdi mdi-stethoscope"></i>
                            </div>
                            <span>Visite</span>
                        </a>
                    </li>
                    <li>
                        <div class="circle-menu waves-effect pink accent-2 menu-layanan"
                            data-layanan="bhp_ruangan/list_bhp">
                            <i class="mdi mdi-pill"></i>
                        </div>
                        <span>BMHP</span>
                    </li>
                    <li class="text-center">
                        <a
                            href="{{ route('detail.registrasi.pasien.layanan', ['registrations' => $registration->id, 'layanan' => 'operasi']) }}">
                            <div class="circle-menu waves-effect light-green accent-3 menu-layanan"
                                data-layanan="operasi">
                                <i class="mdi mdi-heart-pulse"></i>
                            </div>
                            <span>Operasi</span>
                        </a>
                    </li>
                    <li class="text-center">
                        <a
                            href="{{ route('detail.registrasi.pasien.layanan', ['registrations' => $registration->id, 'layanan' => 'persalinan']) }}">
                            <div class="circle-menu waves-effect teal accent-3 menu-layanan" data-layanan="persalinan">
                                <i class="mdi mdi-seat-flat-angled"></i>
                            </div>
                            <span>Persalinan (VK)</span>
                        </a>
                    </li>
                    <li class="text-center">
                        <a
                            href="{{ route('detail.registrasi.pasien.layanan', ['registrations' => $registration->id, 'layanan' => 'gizi']) }}">
                            <div class="circle-menu waves-effect red accent-3 menu-layanan" data-layanan="gizi">
                                <i class="mdi mdi-bowl"></i>
                            </div>
                            <span>Gizi</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div><!--end .card-body -->
    </div>
</div>
