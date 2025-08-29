@php
    $kelahiran = $data['riwayat_kelahiran_imunisasi']['kelahiran'] ?? [];
    $imunisasi = $data['riwayat_kelahiran_imunisasi']['imunisasi'] ?? [];
    $tumbang = $data['riwayat_tumbuh_kembang'] ?? [];
    $psikologis = $data['riwayat_psikososial'] ?? []; // Menggunakan kembali dari psikososial
@endphp

<hr>
<h4 class="text-primary mt-4 font-weight-bold">RIWAYAT KELAHIRAN & IMUNISASI</h4>

{{-- Riwayat Kelahiran --}}
<div class="card">
    <div class="card-header bg-light"><b>A. Riwayat Kelahiran</b></div>
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-5 d-flex align-items-center form-group">
                <label class="mr-2">Anak ke</label>
                <input type="number" name="riwayat_kelahiran_imunisasi[kelahiran][anak_ke]"
                    class="form-control form-control-sm mx-2" style="width: 70px;"
                    value="{{ $kelahiran['anak_ke'] ?? '' }}">
                <label class="mr-2">dari</label>
                <input type="number" name="riwayat_kelahiran_imunisasi[kelahiran][dari_saudara]"
                    class="form-control form-control-sm mx-2" style="width: 70px;"
                    value="{{ $kelahiran['dari_saudara'] ?? '' }}">
                <label>Saudara</label>
            </div>
            <div class="col-md-7 form-group">
                <label>ANC (ante natal care) teratur:</label>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="anc_ya" name="riwayat_kelahiran_imunisasi[kelahiran][anc_teratur]"
                        value="ya" class="custom-control-input" @checked(isset($kelahiran['anc_teratur']) && $kelahiran['anc_teratur'] == 'ya')>
                    <label class="custom-control-label" for="anc_ya">Ya</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="anc_tidak" name="riwayat_kelahiran_imunisasi[kelahiran][anc_teratur]"
                        value="tidak" class="custom-control-input" @checked(isset($kelahiran['anc_teratur']) && $kelahiran['anc_teratur'] == 'tidak')>
                    <label class="custom-control-label" for="anc_tidak">Tidak</label>
                </div>
            </div>
            <div class="col-md-5 form-group">
                <label>Cara Lahir:</label>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="lahir_spontan" name="riwayat_kelahiran_imunisasi[kelahiran][cara_lahir]"
                        value="Spontan" class="custom-control-input" @checked(isset($kelahiran['cara_lahir']) && $kelahiran['cara_lahir'] == 'Spontan')>
                    <label class="custom-control-label" for="lahir_spontan">Spontan</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline d-flex align-items-center">
                    <input type="radio" id="lahir_lainnya" name="riwayat_kelahiran_imunisasi[kelahiran][cara_lahir]"
                        value="Lainnya" class="custom-control-input" @checked(isset($kelahiran['cara_lahir']) && $kelahiran['cara_lahir'] == 'Lainnya')>
                    <label class="custom-control-label" for="lahir_lainnya">Lainnya:</label>
                    <input type="text" name="riwayat_kelahiran_imunisasi[kelahiran][cara_lahir_ket]"
                        class="form-control form-control-sm ml-2" style="width: 150px;"
                        value="{{ $kelahiran['cara_lahir_ket'] ?? '' }}">
                </div>
            </div>
            <div class="col-md-7 form-group">
                <label>Umur Kelahiran:</label>
                @foreach (['Cukup bulan', 'Kurang bulan'] as $item)
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="umur_lahir_{{ Str::slug($item) }}"
                            name="riwayat_kelahiran_imunisasi[kelahiran][umur_kelahiran]" value="{{ $item }}"
                            class="custom-control-input" @checked(isset($kelahiran['umur_kelahiran']) && $kelahiran['umur_kelahiran'] == $item)>
                        <label class="custom-control-label"
                            for="umur_lahir_{{ Str::slug($item) }}">{{ $item }}</label>
                    </div>
                @endforeach
            </div>
            <div class="col-md-12 form-group">
                <label>Kelainan Bawaan:</label>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="kelainan_tidak"
                        name="riwayat_kelahiran_imunisasi[kelahiran][kelainan_bawaan]" value="tidak"
                        class="custom-control-input" @checked(isset($kelahiran['kelainan_bawaan']) && $kelahiran['kelainan_bawaan'] == 'tidak')>
                    <label class="custom-control-label" for="kelainan_tidak">Tidak ada</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline d-flex align-items-center">
                    <input type="radio" id="kelainan_ada"
                        name="riwayat_kelahiran_imunisasi[kelahiran][kelainan_bawaan]" value="ada"
                        class="custom-control-input" @checked(isset($kelahiran['kelainan_bawaan']) && $kelahiran['kelainan_bawaan'] == 'ada')>
                    <label class="custom-control-label" for="kelainan_ada">Ada:</label>
                    <input type="text" name="riwayat_kelahiran_imunisasi[kelahiran][kelainan_bawaan_ket]"
                        class="form-control form-control-sm ml-2" style="width: 250px;"
                        value="{{ $kelahiran['kelainan_bawaan_ket'] ?? '' }}">
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Riwayat Imunisasi --}}
<div class="card mt-3">
    <div class="card-header bg-light"><b>B. Riwayat Imunisasi</b></div>
    <div class="card-body">
        <div class="row">
            @foreach (['BCG', 'DPT', 'Polio', 'Campak', 'Varicela', 'Thypoid', 'Hep B', 'PCV', 'Rotavirus', 'HiB', 'MMR', 'Meningitis', 'Influenza', 'Pneumokokus', 'HPV', 'Tetanus', 'Zooster'] as $item)
                <div class="col-md-3">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" id="imunisasi_{{ Str::slug($item) }}"
                            name="riwayat_kelahiran_imunisasi[imunisasi][{{ Str::slug($item) }}]" value="1"
                            class="custom-control-input" @checked(isset($imunisasi[Str::slug($item)]))>
                        <label class="custom-control-label"
                            for="imunisasi_{{ Str::slug($item) }}">{{ $item }}</label>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Riwayat Tumbuh Kembang --}}
<div class="card mt-3">
    <div class="card-header bg-light"><b>C. Riwayat Tumbuh Kembang</b></div>
    <div class="card-body">
        <div class="row">
            @foreach ([
        'pertumbuhan_gigi' => 'Pertumbuhan gigi pertama',
        'mulai_duduk' => 'Mulai bisa duduk',
        'berjalan_sendiri' => 'Berjalan sendiri',
        'mulai_bicara' => 'Mulai bicara',
        'mulai_membaca' => 'Mulai bisa membaca',
    ] as $key => $label)
                <div class="col-md-4 form-group">
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text">{{ $label }},
                                Usia</span></div>
                        <input type="text" name="riwayat_tumbuh_kembang[{{ $key }}]"
                            class="form-control" value="{{ $tumbang[$key] ?? '' }}">
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Riwayat Psikologis Pengasuh --}}
<div class="card mt-3">
    <div class="card-header bg-light"><b>D. Riwayat Psikologis Yang Mengasuh</b></div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 form-group"><label>Jenis Sekolah</label>
                <div>
                    @foreach (['Sekolah umum', 'Sekolah asrama', 'Tidak sekolah'] as $item)
                        <div class="custom-control custom-checkbox custom-control-inline"><input type="checkbox"
                                id="sekolah_{{ Str::slug($item) }}"
                                name="riwayat_psikososial[jenis_sekolah][{{ Str::slug($item) }}]" value="1"
                                class="custom-control-input" @checked(isset($psikologis['jenis_sekolah'][Str::slug($item)]))><label
                                class="custom-control-label"
                                for="sekolah_{{ Str::slug($item) }}">{{ $item }}</label></div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-4 form-group"><label>Yang Mengasuh</label>
                <div>
                    @foreach (['Orang tua', 'Nenek/Kakek', 'Pembantu', 'Klg lain'] as $item)
                        <div class="custom-control custom-checkbox custom-control-inline"><input type="checkbox"
                                id="pengasuh_{{ Str::slug($item) }}"
                                name="riwayat_psikososial[pengasuh][{{ Str::slug($item) }}]" value="1"
                                class="custom-control-input" @checked(isset($psikologis['pengasuh'][Str::slug($item)]))><label
                                class="custom-control-label"
                                for="pengasuh_{{ Str::slug($item) }}">{{ $item }}</label></div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-4 form-group"><label>Status Mental/Emosional</label>
                <div>
                    @foreach (['Tenang, Kooperatif', 'Gelisah, murung, cengeng/rewel', 'Ketakutan, Agresif, Hiperaktif'] as $item)
                        <div class="custom-control custom-checkbox custom-control-inline"><input type="checkbox"
                                id="mental_{{ Str::slug($item) }}"
                                name="riwayat_psikososial[status_mental][{{ Str::slug($item) }}]" value="1"
                                class="custom-control-input" @checked(isset($psikologis['status_mental'][Str::slug($item)]))><label
                                class="custom-control-label"
                                for="mental_{{ Str::slug($item) }}">{{ $item }}</label></div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
