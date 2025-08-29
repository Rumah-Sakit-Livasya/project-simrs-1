@extends('pages.simrs.erm.index')
@section('erm')
    @if (isset($registration) || $registration != null)
        <div class="tab-content p-3">
            <div class="tab-pane fade show active" id="tab_default-1" role="tabpanel">
                <form action="javascript:void(0)" id="pengkajian_perawat_form" method="POST" autocomplete="off">
                    @csrf
                    @method('POST')
                    @include('pages.simrs.erm.partials.detail-pasien')
                    <hr class="mb-4" style="border-color: #868686;">
                    <header class="text-primary text-center mb-4">
                        <h2 class="fw-bold">PENGKAJIAN PERAWAT</h2>
                    </header>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="tgl_masuk" class="form-label text-primary">Tanggal Masuk</label>
                            <input type="date" name="tgl_masuk" class="form-control" id="tgl_masuk"
                                value="{{ $pengkajian?->tgl_masuk?->format('Y-m-d') ?? now()->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="jam_masuk" class="form-label text-primary">Jam Masuk</label>
                            <input type="time" name="jam_masuk" class="form-control" id="jam_masuk"
                                value="{{ $pengkajian?->jam_masuk ?? '' }}">
                        </div>
                        <div class="col-md-3">
                            <label for="tgl_dilayani" class="form-label text-primary">Tanggal Dilayani</label>
                            <input type="date" name="tgl_dilayani" class="form-control" id="tgl_dilayani"
                                value="{{ $pengkajian?->tgl_dilayani?->format('Y-m-d') ?? now()->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="jam_dilayani" class="form-label text-primary">Jam Dilayani</label>
                            <input type="time" name="jam_dilayani" class="form-control" id="jam_dilayani"
                                value="{{ $pengkajian?->jam_dilayani ?? '' }}">
                        </div>
                        <div class="col-md-12">
                            <label for="keluhan_utama" class="form-label text-primary">Keluhan Utama <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control" id="keluhan_utama" name="keluhan_utama" rows="2" required
                                data-label="Keluhan utama" placeholder="Tuliskan keluhan utama pasien...">{{ $pengkajian?->keluhan_utama }}</textarea>
                        </div>
                    </div>

                    <header class="text-warning mt-4 mb-2">
                        <h4 class="fw-bold">TANDA TANDA VITAL</h4>
                    </header>
                    <div class="row g-3">
                        @php
                            $vitalSigns = [
                                ['label' => 'Nadi (PR)', 'name' => 'pr', 'unit' => 'x/menit'],
                                ['label' => 'Respirasi (RR)', 'name' => 'rr', 'unit' => 'x/menit'],
                                ['label' => 'Tensi (BP)', 'name' => 'bp', 'unit' => 'mmHg'],
                                ['label' => 'Suhu (T)', 'name' => 'temperatur', 'unit' => 'C°'],
                                [
                                    'label' => 'Tinggi Badan',
                                    'name' => 'body_height',
                                    'unit' => 'Cm',
                                    'class' => 'calc-bmi',
                                ],
                                [
                                    'label' => 'Berat Badan',
                                    'name' => 'body_weight',
                                    'unit' => 'Kg',
                                    'class' => 'calc-bmi',
                                ],
                                [
                                    'label' => 'Index Massa Tubuh',
                                    'name' => 'bmi',
                                    'unit' => 'Kg/m²',
                                    'readonly' => true,
                                ],
                                [
                                    'label' => 'Kategori IMT',
                                    'name' => 'kat_bmi',
                                    'unit' => '',
                                    'readonly' => true,
                                    'type' => 'text',
                                ],
                                ['label' => 'SP 02', 'name' => 'sp02', 'unit' => '%'],
                                ['label' => 'Lingkar Kepala', 'name' => 'lingkar_kepala', 'unit' => 'Cm'],
                            ];
                        @endphp
                        @foreach ($vitalSigns as $vs)
                            <div class="col-md-4 col-6">
                                <label for="{{ $vs['name'] }}" class="form-label text-primary">{{ $vs['label'] }}</label>
                                <div class="input-group mb-2">
                                    <input class="form-control numeric {{ $vs['class'] ?? '' }}" id="{{ $vs['name'] }}"
                                        name="{{ $vs['name'] }}" type="{{ $vs['type'] ?? 'number' }}"
                                        value="{{ $pengkajian?->{$vs['name']} }}"
                                        @if (!empty($vs['readonly'])) readonly @endif>
                                    @if ($vs['unit'])
                                        <span class="input-group-text bg-light">{{ $vs['unit'] }}</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                        <div class="col-md-4 col-12">
                            <label for="diagnosa-keperawatan" class="form-label text-primary">Diagnosa Keperawatan</label>
                            <select name="diagnosa_keperawatan" id="diagnosa-keperawatan" class="form-select select2">
                                <option value="-">-</option>
                                <option value="Gangguan rasa nyaman">Gangguan rasa nyaman</option>
                                <option value="Nyeri">Nyeri</option>
                                <option value="Pola Nafas tidak efektif">Pola Nafas tidak efektif</option>
                                <option value="Bersihan jalan nafas tidak efektif">Bersihan jalan nafas tidak efektif
                                </option>
                                <option value="Nyeri Akut">Nyeri Akut</option>
                                <option value="Nyeri Kronis">Nyeri Kronis</option>
                                <option value="Resiko Infeksi">Resiko Infeksi</option>
                                <option value="Harga diri Rendah">Harga diri Rendah</option>
                                <option value="Resiko Perilaku Kekerasan">Resiko Perilaku Kekerasan</option>
                                <option value="Halusinasi">Halusinasi</option>
                                <option value="Isolasi Sosial">Isolasi Sosial</option>
                                <option value="Resiko Bunuh Diri">Resiko Bunuh Diri</option>
                                <option value="Waham">Waham</option>
                            </select>
                        </div>
                        <div class="col-md-4 col-12">
                            <label for="rencana-tindak-lanjut" class="form-label text-primary">Rencana Tindak Lanjut</label>
                            <select name="rencana_tindak_lanjut" id="rencana-tindak-lanjut" class="form-select select2">
                                <option value="-">-</option>
                                <option value="Kolaborasi Dokter">Kolaborasi Dokter</option>
                                <option value="Perawatan Luka">Perawatan Luka</option>
                                <option value="Memberikan Edukasi">Memberikan Edukasi</option>
                                <option value="Mengukur tanda - tanda vital">Mengukur tanda - tanda vital</option>
                            </select>
                        </div>
                    </div>

                    <header class="text-secondary mt-4 mb-2">
                        <h4 class="fw-bold">ALERGI DAN REAKSI</h4>
                    </header>
                    <div class="row g-3">
                        <div class="col-md-6">
                            @foreach (['obat' => 'Alergi Obat', 'makanan' => 'Alergi Makanan', 'lainnya' => 'Alergi Lainnya'] as $key => $label)
                                <div class="mb-3">
                                    <label class="form-label text-primary">{{ $label }}</label>
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio"
                                                name="alergi_{{ $key }}" id="alergi_{{ $key }}1"
                                                value="Ya" @if ($pengkajian?->{'alergi_' . $key} == 'Ya') checked @endif>
                                            <label class="form-check-label" for="alergi_{{ $key }}1">Ya</label>
                                        </div>
                                        <input name="ket_alergi_{{ $key }}" id="ket_alergi_{{ $key }}"
                                            class="form-control form-control-sm mx-2"
                                            style="max-width: 180px; border-radius: 0.25rem;" type="text"
                                            @if ($pengkajian?->{'alergi_' . $key} == 'Ya') value="{{ $pengkajian?->{'ket_alergi_' . $key} }}" @endif
                                            placeholder="Keterangan">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio"
                                                name="alergi_{{ $key }}" id="alergi_{{ $key }}2"
                                                value="Tidak" @if ($pengkajian?->{'alergi_' . $key} == 'Tidak') checked @endif>
                                            <label class="form-check-label"
                                                for="alergi_{{ $key }}2">Tidak</label>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <div class="form-check mt-3">
                                <input class="form-check-input" type="checkbox" value="1" name="gelang"
                                    id="gelang1" {{ $pengkajian?->gelang == 1 ? 'checked' : '' }}>
                                <label class="form-check-label text-primary" for="gelang1">Gelang tanda alergi (warna
                                    merah)</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            @foreach (['obat' => 'Reaksi terhadap alergi obat', 'makanan' => 'Reaksi terhadap alergi makanan', 'lainnya' => 'Reaksi terhadap alergi lainnya'] as $key => $label)
                                <div class="form-floating mb-3">
                                    <input name="reaksi_alergi_{{ $key }}"
                                        id="reaksi_alergi_{{ $key }}" class="form-control" type="text"
                                        value="{{ $pengkajian?->{'reaksi_alergi_' . $key} }}">
                                    <label for="reaksi_alergi_{{ $key }}"
                                        class="text-primary">{{ $label }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <header class="text-secondary mt-4 mb-2">
                        <h4 class="fw-bold">SKRINING NYERI</h4>
                    </header>
                    <div class="row g-3">
                        <div class="col-12 mb-4">
                            <div class="d-flex flex-wrap justify-content-between align-items-end wong-baker-scale">
                                @for ($i = 0; $i < 6; $i++)
                                    <div class="p-2">
                                        <img src="/img/nyeri/{{ $i + 1 }}.jpg" class="img-fluid"
                                            style="max-width: 100px; border-radius: 5px; cursor: pointer;"
                                            alt="Skala Nyeri Wong Baker">
                                        <div class="mt-2 d-flex justify-content-center">
                                            @if ($i == 0)
                                                <span class="badge badge-pill badge-success pointer"
                                                    data-skor="0">0</span>
                                            @elseif($i == 1)
                                                <span class="badge badge-pill badge-success pointer"
                                                    data-skor="1">1</span>
                                                <span class="badge badge-pill badge-success pointer"
                                                    data-skor="2">2</span>
                                            @elseif($i == 2)
                                                <span class="badge badge-pill badge-info pointer" data-skor="3">3</span>
                                                <span class="badge badge-pill badge-info pointer" data-skor="4">4</span>
                                            @elseif($i == 3)
                                                <span class="badge badge-pill badge-primary pointer"
                                                    data-skor="5">5</span>
                                                <span class="badge badge-pill badge-primary pointer"
                                                    data-skor="6">6</span>
                                            @elseif($i == 4)
                                                <span class="badge badge-pill badge-warning pointer"
                                                    data-skor="7">7</span>
                                                <span class="badge badge-pill badge-warning pointer"
                                                    data-skor="8">8</span>
                                            @else
                                                <span class="badge badge-pill badge-danger pointer"
                                                    data-skor="9">9</span>
                                                <span class="badge badge-pill badge-danger pointer"
                                                    data-skor="10">10</span>
                                            @endif
                                        </div>
                                    </div>
                                @endfor
                                <div class="img-baker d-flex flex-column align-items-center" style="width: 13%;">
                                    <input name="skor_nyeri" id="skor_nyeri" class="form-control text-center mt-3"
                                        style="font-size: 2rem; height: 50px;" type="number"
                                        value="{{ $pengkajian?->skor_nyeri }}">
                                    <label for="skor_nyeri" class="control-label text-primary">Skor</label>
                                </div>
                            </div>
                        </div>

                        @foreach ([['provokatif', 'Provokatif'], ['quality', 'Quality'], ['region', 'Region'], ['time', 'Time']] as [$name, $label])
                            <div class="col-md-3">
                                <label for="{{ $name }}"
                                    class="form-label text-primary">{{ $label }}</label>
                                <input name="{{ $name }}" id="{{ $name }}" class="form-control"
                                    type="text" value="{{ $pengkajian?->$name }}">
                            </div>
                        @endforeach
                        <div class="col-md-3">
                            <label for="nyeri" class="form-label text-primary">Nyeri</label>
                            <select name="nyeri" id="nyeri" class="form-select select2">
                                <option value="-">-</option>
                                <option value="Nyeri kronis">Nyeri kronis</option>
                                <option value="Nyeri akut">Nyeri akut</option>
                                <option value="TIdak ada nyeri">TIdak ada nyeri</option>
                            </select>
                        </div>
                        <div class="col-md-9">
                            <label for="nyeri_hilang" class="form-label text-primary">Nyeri hilang apabila</label>
                            <input name="nyeri_hilang" id="nyeri_hilang" class="form-control"
                                value="{{ $pengkajian?->nyeri_hilang }}" type="text">
                        </div>
                    </div>

                    <header class="text-secondary mt-4 mb-2">
                        <h4 class="fw-bold">SKRINING GIZI</h4>
                    </header>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="penurunan_bb" class="form-label text-primary">Penurunan berat badan 6 bln
                                terakhir</label>
                            <select name="penurunan_bb" id="penurunan_bb" class="form-select select2">
                                <option></option>
                                <option value="Tidak">Tidak</option>
                                <option value="Tidak yakin / Ragu-ragu">Tidak yakin / Ragu-ragu</option>
                                <option value="Ya, 1-5 Kg">Ya, 1-5 Kg</option>
                                <option value="Ya, 6-10 Kg">Ya, 6-10 Kg</option>
                                <option value="Ya, 11-15 Kg">Ya, 11-15 Kg</option>
                                <option value="Ya, > 15 Kg">Ya, &gt; 15 Kg</option>
                                <option value="Ya, tidak tahu berapa Kg">Ya, tidak tahu berapa Kg</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="asupan_makan" class="form-label text-primary">Asupan makanan pasien</label>
                            <select name="asupan_makan" id="asupan_makan" class="form-select select2">
                                <option></option>
                                <option value="Normal">Normal</option>
                                <option value="Berkurang, penurunan nafsu makan/kesulitan menerima makan" data-skor="1">
                                    Berkurang, penurunan nafsu makan/kesulitan menerima makan
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-3">
                        <label for="kondisi_khusus1" class="form-label text-primary">Pasien dalam kondisi khusus</label>
                        <div class="row g-2">
                            @php
                                $kondisi_khusus_terpilih = is_array($pengkajian?->kondisi_khusus)
                                    ? $pengkajian?->kondisi_khusus
                                    : json_decode($pengkajian?->kondisi_khusus ?? '[]', true);
                                $kondisi_khusus_list = [
                                    'Anak usia 1-5 tahun',
                                    'Lansia > 60 tahun',
                                    'Penyakit kronis dengan komplikasi',
                                    'Kanker stadium III/IV',
                                    'HIV/AIDS',
                                    'TB',
                                    'Bedah mayor degestif',
                                    'Luka bakar > 20%',
                                ];
                            @endphp
                            @foreach ($kondisi_khusus_list as $index => $kondisi)
                                <div class="col-md-3 col-6">
                                    <div class="form-check">
                                        <input name="kondisi_khusus[]" id="kondisi_khusus{{ $index + 1 }}"
                                            value="{{ $kondisi }}" type="checkbox" class="form-check-input"
                                            {{ in_array($kondisi, $kondisi_khusus_terpilih) ? 'checked' : '' }}>
                                        <label class="form-check-label text-primary"
                                            for="kondisi_khusus{{ $index + 1 }}">{{ $kondisi }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <header class="text-secondary mt-4 mb-2">
                        <h4 class="fw-bold">RIWAYAT IMUNISASI DASAR</h4>
                    </header>
                    @php
                        $imunisasi_dasar_terpilih = [];
                        if ($pengkajian?->imunisasi_dasar) {
                            if (is_string($pengkajian->imunisasi_dasar)) {
                                $imunisasi_dasar_terpilih = json_decode($pengkajian->imunisasi_dasar, true) ?? [];
                            } else {
                                $imunisasi_dasar_terpilih = $pengkajian->imunisasi_dasar;
                            }
                        }
                        $imunisasi_list = ['BCG', 'DPT', 'Hepatitis B', 'Polio', 'Campak'];
                    @endphp
                    <div class="row g-2">
                        @foreach ($imunisasi_list as $index => $imunisasi)
                            <div class="col-md-2 col-6">
                                <div class="form-check">
                                    <input name="imunisasi_dasar[]" id="imunisasi_dasar{{ $index + 1 }}"
                                        value="{{ $imunisasi }}" type="checkbox" class="form-check-input"
                                        {{ in_array($imunisasi, $imunisasi_dasar_terpilih) ? 'checked' : '' }}>
                                    <label class="form-check-label text-primary"
                                        for="imunisasi_dasar{{ $index + 1 }}">{{ $imunisasi }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <header class="text-secondary mt-4 mb-2">
                        <h4 class="fw-bold">SKRINING RESIKO JATUH - GET UP & GO</h4>
                    </header>
                    @php
                        $resiko_jatuh_terpilih = is_array($pengkajian?->resiko_jatuh)
                            ? $pengkajian->resiko_jatuh
                            : json_decode($pengkajian?->resiko_jatuh ?? '[]', true);
                        $resiko_jatuh_list = [
                            'Tidak seimbang/sempoyongan/limbung',
                            'Alat bantu: kruk,kursi roda/dibantu',
                            'Pegang pinggiran meja/kursi/alat bantu untuk duduk',
                        ];
                    @endphp
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label text-primary">A. Cara Berjalan</label>
                        </div>
                        @foreach ($resiko_jatuh_list as $index => $resiko)
                            <div class="col-md-6">
                                @if ($index == 2)
                                    <label class="form-label text-primary">B. Menopang saat duduk</label>
                                @endif
                                <div class="form-check">
                                    <input onclick="resiko_jatuh()" name="resiko_jatuh[]"
                                        id="resiko_jatuh{{ $index + 1 }}" value="{{ $resiko }}"
                                        type="checkbox" class="form-check-input"
                                        {{ in_array($resiko, $resiko_jatuh_terpilih) ? 'checked' : '' }}>
                                    <label class="form-check-label text-primary"
                                        for="resiko_jatuh{{ $index + 1 }}">{{ $resiko }}</label>
                                </div>
                            </div>
                        @endforeach
                        <div class="col-md-6">
                            <div class="input-group mb-3">
                                <span class="input-group-text bg-light">Hasil</span>
                                <input class="form-control" name="hasil_resiko_jatuh" id="resiko_jatuh_hasil"
                                    type="text" readonly>
                            </div>
                        </div>
                    </div>

                    <header class="text-secondary mt-4 mb-2">
                        <h4 class="fw-bold">RIWAYAT PSIKOSOSIAL, SPIRITUAL &amp; KEPERCAYAAN</h4>
                    </header>
                    <div class="row g-3">
                        @php
                            $psikososial_fields = [
                                [
                                    'status_psikologis',
                                    'Status psikologis',
                                    'select',
                                    ['', 'Tenang', 'Cemas', 'Takut', 'Marah', 'Sedih', 'Kecenderungan bunuh diri'],
                                ],
                                [
                                    'status_spiritual',
                                    'Status spiritual',
                                    'select',
                                    [
                                        '',
                                        'Percaya Nilai-nilai dan kepercayaan',
                                        'Tidak Percaya Nilai-nilai dan kepercayaan',
                                    ],
                                ],
                                ['masalah_prilaku', 'Masalah prilaku (bila ada)', 'text'],
                                ['kekerasan_dialami', 'Kekerasan yg pernah dialami', 'text'],
                                ['hub_dengan_keluarga', 'Hubungan dengan anggota keluarga', 'text'],
                                ['tempat_tinggal', 'Tempat tinggal (rumah/panti/kos/dll)', 'text'],
                                ['kerabat_dihub', 'Kerabat yang dapat dihubungi', 'text'],
                                ['no_kontak_kerabat', 'Kontak kerabat yang dapat dihubungi', 'text'],
                                [
                                    'status_perkawinan',
                                    'Status perkawinan',
                                    'text',
                                    [],
                                    true,
                                    $pengkajian?->registration?->patient?->married_status,
                                ],
                                [
                                    'pekerjaan',
                                    'Pekerjaan',
                                    'text',
                                    [],
                                    true,
                                    $pengkajian?->registration?->patient?->job,
                                ],
                                [
                                    'penghasilan',
                                    'Penghasilan',
                                    'select',
                                    [
                                        '',
                                        '< 1 Juta',
                                        '1 - 2,9 Juta',
                                        '3 - 4,9 Juta',
                                        '5 - 9,9 Juta',
                                        '10 - 14,9 Juta',
                                        '15 - 19.5 Juta',
                                        '> 20 Juta',
                                    ],
                                ],
                                [
                                    'pendidikan',
                                    'Pendidikan',
                                    'text',
                                    [],
                                    true,
                                    $pengkajian?->registration?->patient?->last_education,
                                ],
                            ];
                        @endphp
                        @foreach ($psikososial_fields as $f)
                            <div class="col-md-4 col-12">
                                @if ($f[2] == 'select')
                                    <label for="{{ $f[0] }}"
                                        class="form-label text-primary">{{ $f[1] }}</label>
                                    <select name="{{ $f[0] }}" id="{{ $f[0] }}"
                                        class="form-select select2">
                                        @foreach ($f[3] as $opt)
                                            <option value="{{ $opt }}">{{ $opt }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <label for="{{ $f[0] }}"
                                        class="form-label text-primary">{{ $f[1] }}</label>
                                    <input name="{{ $f[0] }}" id="{{ $f[0] }}" class="form-control"
                                        type="text" value="{{ $f[5] ?? $pengkajian?->{$f[0]} }}"
                                        @if (!empty($f[4])) readonly @endif>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <header class="text-secondary mt-4 mb-2">
                        <h4 class="fw-bold">KEBUTUHAN EDUKASI</h4>
                        <label class="fw-bold text-primary">Hambatan dalam pembelajaran</label>
                    </header>
                    <div class="row g-3">
                        @php
                            $hambatan_belajar = $pengkajian?->hambatan_belajar;
                            $hambatan_belajar_terpilih = is_array($hambatan_belajar)
                                ? $hambatan_belajar
                                : json_decode($hambatan_belajar ?? '[]', true);
                            $hambatan_options = [
                                'Pendengaran',
                                'Penglihatan',
                                'Kognitif',
                                'Fisik',
                                'Budaya',
                                'Agama',
                                'Emosi',
                                'Bahasa',
                                'Tidak ada Hambatan',
                            ];
                        @endphp
                        @foreach ($hambatan_options as $key => $option)
                            <div class="col-md-3 col-6">
                                <div class="form-check">
                                    <input class="form-check-input" name="hambatan_belajar[]"
                                        id="hambatan_belajar{{ $key + 1 }}" value="{{ $option }}"
                                        type="checkbox"
                                        {{ in_array($option, $hambatan_belajar_terpilih) ? 'checked' : '' }}>
                                    <label for="hambatan_belajar{{ $key + 1 }}"
                                        class="form-check-label text-primary">{{ $option }}</label>
                                </div>
                            </div>
                        @endforeach
                        <div class="col-md-3 col-12">
                            <label for="hambatan_lainnya" class="form-label text-primary">Hambatan lainnya</label>
                            <input name="hambatan_lainnya" id="hambatan_lainnya" class="form-control" type="text"
                                value="{{ $pengkajian?->hambatan_lainnya }}">
                        </div>
                        <div class="col-md-3 col-12">
                            <label for="kebutuhan_penerjemah" class="form-label text-primary">Kebutuhan penerjemah</label>
                            <input name="kebutuhan_penerjemah" id="kebutuhan_penerjemah" class="form-control"
                                type="text" value="{{ $pengkajian?->kebutuhan_penerjemah }}">
                        </div>
                        <div class="col-md-12">
                            <label class="fw-bold text-primary mt-3">Kebutuhan pembelajaran</label>
                        </div>
                        @php
                            $kebutuhan_pembelajaran = $pengkajian?->kebutuhan_pembelajaran;
                            $kebutuhan_pembelajaran_terpilih = is_array($kebutuhan_pembelajaran)
                                ? $kebutuhan_pembelajaran
                                : json_decode($kebutuhan_pembelajaran ?? '[]', true);
                            $kebutuhan_options = [
                                'Diagnosa managemen',
                                'Obat-obatan',
                                'Perawatan luka',
                                'Rehabilitasi',
                                'Diet & nutrisi',
                                'Tidak ada Hambatan',
                            ];
                        @endphp
                        @foreach ($kebutuhan_options as $key => $option)
                            <div class="col-md-3 col-6">
                                <div class="form-check mt-3">
                                    <input class="form-check-input" name="kebutuhan_pembelajaran[]"
                                        id="kebutuhan_pembelajaran{{ $key + 1 }}" value="{{ $option }}"
                                        type="checkbox"
                                        {{ in_array($option, $kebutuhan_pembelajaran_terpilih) ? 'checked' : '' }}>
                                    <label for="kebutuhan_pembelajaran{{ $key + 1 }}"
                                        class="form-check-label text-primary">{{ $option }}</label>
                                </div>
                            </div>
                        @endforeach
                        <div class="col-md-12 mt-3">
                            <label for="pembelajaran_lainnya" class="fw-bold text-primary">Kebutuhan pembelajaran
                                lainnya</label>
                        </div>
                        <div class="col-md-3 col-12">
                            <input name="pembelajaran_lainnya" id="pembelajaran_lainnya" class="form-control"
                                type="text" value="{{ $pengkajian?->pembelajaran_lainnya }}">
                        </div>
                    </div>

                    <header class="text-secondary mt-4 mb-2">
                        <h4 class="fw-bold">Assesment Fungsional (Pengkajian Fungsi)</h4>
                    </header>
                    <header class="text-danger mt-3 mb-2">
                        <h4 class="fw-bold">Sensorik</h4>
                    </header>
                    <div class="row g-3">
                        @php
                            $sensorik_penglihatan = $pengkajian->sensorik_penglihatan ?? '';
                            $sensorik_penciuman = $pengkajian->sensorik_penciuman ?? '';
                            $sensorik_pendengaran = $pengkajian->sensorik_pendengaran ?? '';
                            $opsi_sensorik = [
                                'sensorik_penglihatan' => ['Normal', 'Kabur', 'Kaca Mata', 'Lensa Kontak'],
                                'sensorik_penciuman' => ['Normal', 'Tidak'],
                                'sensorik_pendengaran' => ['Normal', 'Tuli Ka / Ki', 'Ada alat bantu dengar ka/ki'],
                            ];
                            $sensorik_db_value = [
                                'sensorik_penglihatan' => $sensorik_penglihatan,
                                'sensorik_penciuman' => $sensorik_penciuman,
                                'sensorik_pendengaran' => $sensorik_pendengaran,
                            ];
                        @endphp
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle">
                                    <tbody>
                                        @foreach ($opsi_sensorik as $kategori => $listOpsional)
                                            <tr>
                                                <td class="fw-bold text-primary" style="width: 180px;">
                                                    {{ Str::of($kategori)->after('sensorik_')->ucfirst() }}</td>
                                                @foreach ($listOpsional as $i => $opsiValue)
                                                    <td>
                                                        <div class="form-check form-check-inline">
                                                            <input name="{{ $kategori }}" id="{{ $kategori . $i }}"
                                                                value="{{ $opsiValue }}"
                                                                data-skor="{{ $i }}" class="form-check-input"
                                                                type="radio" @checked(($sensorik_db_value[$kategori] ?? null) == $opsiValue)>
                                                            <label class="form-check-label" for="{{ $kategori . $i }}">
                                                                {{ $opsiValue }}
                                                            </label>
                                                        </div>
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <header class="text-danger mt-3 mb-2">
                        <h4 class="fw-bold">Kognitif</h4>
                    </header>
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle">
                                    <tbody>
                                        <tr>
                                            @foreach (['Normal', 'Bingung', 'Pelupa', 'Tidak Dapat dimengerti'] as $i => $val)
                                                <td>
                                                    <div class="form-check form-check-inline">
                                                        <input name="kognitif" class="form-check-input"
                                                            id="kognitif{{ $i + 1 }}" value="{{ $val }}"
                                                            data-skor="{{ $i }}" type="radio"
                                                            @checked(($pengkajian->kognitif ?? '') == $val)>
                                                        <label class="form-check-label"
                                                            for="kognitif{{ $i + 1 }}">{{ $val }}</label>
                                                    </div>
                                                </td>
                                            @endforeach
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <header class="text-danger mt-3 mb-2">
                        <h4 class="fw-bold">Motorik</h4>
                    </header>
                    <div class="row g-3">
                        @php
                            $opsiMotorik = [
                                'motorik_aktifitas' => ['Mandiri', 'Bantuan Minimal', 'Bantuan Ketergantungan Total'],
                                'motorik_berjalan' => [
                                    'Tidak Ada kesulitan',
                                    'Perlu Bantuan',
                                    'Sering Jatuh',
                                    'Kelumpuhan',
                                ],
                            ];
                        @endphp
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle">
                                    <tbody>
                                        @foreach ($opsiMotorik as $kategori => $opsiList)
                                            <tr>
                                                <td class="fw-bold text-primary" style="width: 180px;">
                                                    {{ Str::of($kategori)->after('motorik_')->replace('_', ' ')->ucfirst() }}
                                                </td>
                                                @foreach ($opsiList as $i => $opsiValue)
                                                    <td>
                                                        <div class="form-check">
                                                            <input name="{{ $kategori }}" id="{{ $kategori . $i }}"
                                                                value="{{ $opsiValue }}"
                                                                data-skor="{{ $i }}" class="form-check-input"
                                                                type="radio" @checked(($pengkajian?->{$kategori} ?? null) == $opsiValue)>
                                                            <label class="form-check-label text-primary"
                                                                for="{{ $kategori . $i }}">
                                                                {{ $opsiValue }}
                                                            </label>
                                                        </div>
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    @include('pages.simrs.erm.partials.signature-field', [
                        'judul' => 'Perawat,',
                        'pic' => auth()->user()->employee->fullname,
                        'role' => 'perawat',
                        'prefix' => 'pengkajian_nurse',
                        'signature_model' => $pengkajian?->signature,
                    ])

                    <div class="row mt-5">
                        <div class="col-md-12 px-3">
                            <div class="card-actionbar">
                                <div
                                    class="card-actionbar-row d-flex flex-wrap justify-content-between align-items-center gap-2">
                                    <button type="button"
                                        class="btn btn-outline-primary waves-effect save-form d-flex align-items-center"
                                        data-dismiss="modal" data-status="0">
                                        <span class="mdi mdi-printer me-2"></span> Print
                                    </button>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <button type="button"
                                            class="btn btn-warning text-white waves-effect save-form d-flex align-items-center"
                                            data-dismiss="modal" data-status="0" id="sd-pengkajian-nurse-rajal">
                                            <span class="mdi mdi-content-save me-2"></span> Simpan (draft)
                                        </button>
                                        <button type="button"
                                            class="btn btn-primary waves-effect save-form d-flex align-items-center"
                                            data-dismiss="modal" data-status="1" id="sf-pengkajian-nurse-rajal">
                                            <span class="mdi mdi-content-save me-2"></span> Simpan (final)
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endsection
@section('plugin-erm')
    <script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(document).ready(function() {
            $('.wong-baker-scale .pointer').on('click', function() {
                const skor = $(this).data('skor');
                $('#skor_nyeri').val(skor);
            });

            const pengkajian = @json($pengkajian ?? []);

            $('body').addClass('layout-composed');
            $('.select2').select2({
                placeholder: 'Pilih Item',
                width: '100%'
            });
            $('#departement_id').select2({
                placeholder: 'Pilih Klinik',
                width: '100%'
            });

            if (pengkajian) {
                $('#diagnosa-keperawatan').val(pengkajian.diagnosa_keperawatan).trigger('change');
                $('#rencana-tindak-lanjut').val(pengkajian.rencana_tindak_lanjut).trigger('change');
                $('#nyeri').val(pengkajian.nyeri).trigger('change');
                $('#penurunan_bb').val(pengkajian.penurunan_bb).trigger('change');
                $('#asupan_makan').val(pengkajian.asupan_makan).trigger('change');
                $('#status_psikologis').val(pengkajian.status_psikologis).trigger('change');
                $('#status_spiritual').val(pengkajian.status_spiritual).trigger('change');
                $('#penghasilan').val(pengkajian.penghasilan).trigger('change');
            }

            $('#toggle-pasien').on('click', function() {
                var target = $('#js-slide-left');
                var backdrop = $('.slide-backdrop');
                target.toggleClass('hide');
                backdrop.toggleClass('show');
            });

            $('.slide-backdrop').on('click', function() {
                $('#js-slide-left').removeClass('slide-on-mobile-left-show');
                $(this).removeClass('show');
            });

            // Optional: Add focus/scroll to first invalid input on submit, etc.
        });
    </script>
@endsection
