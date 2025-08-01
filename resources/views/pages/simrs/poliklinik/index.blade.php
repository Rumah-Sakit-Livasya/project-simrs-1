@extends('pages.simrs.erm.index')
@section('erm')
    @if (isset($registration) || $registration != null)
        <div class="tab-content p-3">
            <div class="tab-pane fade show active" id="tab_default-1" role="tabpanel">
                <form action="javascript:void(0)" id="pengkajian_perawat_form" method="POST">
                    @csrf
                    @method('POST')
                    @include('pages.simrs.poliklinik.partials.detail-pasien')
                    <hr style="border-color: #868686; margin-bottom: 50px;">
                    <header class="text-primary text-center mt-5">
                        <h2 class="font-weight-bold mt-5">PENGKAJIAN PERAWAT</h2>
                    </header>
                    <div class="row mt-5">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tgl_masuk" class="control-label text-primary">Tanggal &amp;
                                    jam
                                    masuk</label>
                                <div class="form-group mb-3">
                                    <div class="input-group">
                                        <input type="date" name="tgl_masuk" class="form-control " placeholder="Tanggal"
                                            id="tgl_masuk"
                                            value="{{ $pengkajian?->tgl_masuk?->format('Y-m-d') ?? now()->format('Y-m-d') }}">
                                        <input type="time" name="jam_masuk" class="form-control " placeholder="Jam"
                                            id="jam_masuk" value="{{ $pengkajian?->jam_masuk ?? '' }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="tgl_masuk" class="control-label text-primary">Tanggal &amp;
                                    jam
                                    dilayani</label>
                                <div class="input-group">
                                    <input type="date" name="tgl_dilayani" class="form-control" placeholder="Tanggal"
                                        id="tgl_dilayani"
                                        value="{{ $pengkajian?->tgl_dilayani?->format('Y-m-d') ?? now()->format('Y-m-d') }}">
                                    <input type="time" name="jam_dilayani" class="form-control" placeholder="Jam"
                                        id="jam_dilayani" value="{{ $pengkajian?->jam_dilayani ?? '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label for="keluhan_utama" class="control-label text-primary">Keluhan
                                    utama
                                    *</label>
                                <textarea class="form-control" id="keluhan_utama" name="keluhan_utama" rows="3" required=""
                                    data-label="Keluhan utama">{{ $pengkajian?->keluhan_utama }}</textarea>
                            </div>
                        </div>
                    </div>
                    <header class="text-warning margin-top-lg mt-3">
                        <h4 class=" mt-5 font-weight-bold">TANDA TANDA VITAL</h4>
                    </header>
                    <div class="row mt-3">
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label class="text-primary" for="pr">Nadi (PR)</label>
                                <div class="input-group">
                                    <div class="input-group">
                                        <input id="pr" type="text" name="pr" class="form-control"
                                            value="{{ $pengkajian?->pr }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text">x/menit</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="rr" class="text-primary">Respirasi (RR)</label>
                                <div class="input-group">
                                    <input class="form-control numeric" id="rr" name="rr" type="text"
                                        value="{{ $pengkajian?->rr }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">x/menit</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="bp" class="text-primary">Tensi (BP)</label>
                                <div class="input-group">
                                    <input class="form-control numeric" id="bp" name="bp" type="text"
                                        value="{{ $pengkajian?->bp }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">mmHg</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="temperatur" class="text-primary">Suhu (T)</label>
                                <div class="input-group">
                                    <input class="form-control numeric" id="temperatur" name="temperatur" type="text"
                                        value="{{ $pengkajian?->temperatur }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">C°</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="height" class="text-primary">Tinggi Badan</label>
                                <div class="input-group">
                                    <input class="form-control numeric calc-bmi" id="body_height" name="body_height"
                                        type="text" value="{{ $pengkajian?->body_height }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">Cm</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="weight" class="text-primary">Berat Badan</label>
                                <div class="input-group">
                                    <input class="form-control numeric calc-bmi" id="body_weight" name="body_weight"
                                        type="text" value="{{ $pengkajian?->body_weight }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">Kg</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="bmi" class="text-primary">Index Massa Tubuh</label>
                                <div class="input-group">
                                    <input class="form-control numeric" id="bmi" name="bmi"
                                        readonly="readonly" type="text" value="{{ $pengkajian?->bmi }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">Kg/m²</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="kat_bmi" class="text-primary">Kategori IMT</label>
                                <div class="input-group">
                                    <input class="form-control" id="kat_bmi" name="kat_bmi" readonly="readonly"
                                        type="text" value="{{ $pengkajian?->kat_bmi }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="sp02" class="text-primary">SP 02</label>
                                <div class="input-group">
                                    <input class="form-control" id="sp02" name="sp02" type="text"
                                        value="{{ $pengkajian?->sp02 }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="lingkar_kepala" class="text-primary">Lingkar
                                    Kepala</label>
                                <div class="input-group">
                                    <input class="form-control" id="lingkar_kepala" name="lingkar_kepala" type="text"
                                        value="{{ $pengkajian?->lingkar_kepala }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">Cm</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="diagnosa-keperawatan" class="control-label text-primary">Diagnosa
                                    Keperawatan</label>
                                <select name="diagnosa_keperawatan" id="diagnosa-keperawatan"
                                    class="select2 form-select">
                                    <option value="-">-</option>
                                    <option value="Gangguan rasa nyaman">Gangguan rasa nyaman</option>`
                                    <option value="Nyeri">Nyeri</option>
                                    <option value="Pola Nafas tidak efektif">Pola Nafas tidak efektif
                                    </option>
                                    <option value="Bersihan jalan nafas tidak efektif">Bersihan jalan
                                        nafas
                                        tidak
                                        efektif
                                    </option>
                                    <option value="Nyeri Akut">Nyeri Akut</option>
                                    <option value="Nyeri Kronis">Nyeri Kronis</option>
                                    <option value="Resiko Infeksi">Resiko Infeksi</option>
                                    <option value="Harga diri Rendah">Harga diri Rendah</option>
                                    <option value="Resiko Perilaku Kekerasan">Resiko Perilaku Kekerasan
                                    </option>
                                    <option value="Halusinasi">Halusinasi</option>
                                    <option value="Isolasi Sosial">Isolasi Sosial</option>
                                    <option value="Resiko Bunuh Diri">Resiko Bunuh Diri</option>
                                    <option value="Waham">Waham</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="rencana-tindak-lanjut" class="control-label text-primary">Rencana
                                    Tindak
                                    Lanjut</label>
                                <select name="rencana_tindak_lanjut" id="rencana-tindak-lanjut"
                                    class="select2 form-select">
                                    <option value="-">-</option>
                                    <option value="Kolaborasi Dokter">Kolaborasi Dokter</option>
                                    <option value="Perawatan Luka">Perawatan Luka</option>
                                    <option value="Memberikan Edukasi">Memberikan Edukasi</option>
                                    <option value="Mengukur tanda - tanda vital">Mengukur tanda - tanda
                                        vital
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <header class="text-secondary mt-3">
                        <h4 class="mt-5 font-weight-bold">ALERGI DAN REAKSI</h4>
                    </header>
                    <div class="row mt-3">
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="alergi_obat" class="control-label text-primary margin-tb-10 d-block">Alergi
                                    Obat</label>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input" value="Ya" name="alergi_obat"
                                        id="alergi_obat1" @if ($pengkajian?->alergi_obat == 'Ya') checked @endif>
                                    <label class="custom-control-label text-primary" for="alergi_obat1">Ya</label>
                                </div>
                                <input name="ket_alergi_obat" id="ket_alergi_obat"
                                    style="margin-right: 10px; width: 200px;border-left: none;border-right: none;border-top: none;border-bottom-color: rgba(12, 12, 12, 0.12);"
                                    type="text"
                                    @if ($pengkajian?->alergi_obat == 'Ya') value="{{ $pengkajian?->ket_alergi_obat }}" @endif>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input" value="Tidak" name="alergi_obat"
                                        id="alergi_obat2" @if ($pengkajian?->alergi_obat == 'Tidak') checked @endif>
                                    <label class="custom-control-label text-primary" for="alergi_obat2">Tidak</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="alergi_makanan" class="control-label text-primary margin-tb-10 d-block">Alergi
                                    Makanan</label>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input" value="Ya"
                                        name="alergi_makanan" id="alergi_makanan1"
                                        @if ($pengkajian?->alergi_makanan == 'Ya') checked @endif>
                                    <label class="custom-control-label text-primary" for="alergi_makanan1">Ya</label>
                                </div>
                                <input name="ket_alergi_makanan" id="ket_alergi_makanan"
                                    style="margin-right: 10px; width: 200px;border-left: none;border-right: none;border-top: none;border-bottom-color: rgba(12, 12, 12, 0.12);"
                                    type="text"
                                    @if ($pengkajian?->alergi_makanan == 'Ya') value="{{ $pengkajian?->ket_alergi_makanan }}" @endif>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input" value="Tidak"
                                        name="alergi_makanan" id="alergi_makanan2"
                                        @if ($pengkajian?->alergi_makanan == 'Tidak') checked @endif>
                                    <label class="custom-control-label text-primary" for="alergi_makanan2">Tidak</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="alergi_lainnya" class="control-label text-primary margin-tb-10 d-block">Alergi
                                    Lainnya</label>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input" value="Ya"
                                        name="alergi_lainnya" id="alergi_lainnya1"
                                        @if ($pengkajian?->alergi_lainnya == 'Ya') checked @endif>
                                    <label class="custom-control-label text-primary" for="alergi_lainnya1">Ya</label>
                                </div>
                                <input name="ket_alergi_lainnya" id="ket_alergi_lainnya"
                                    style="margin-right: 10px; width: 200px;border-left: none;border-right: none;border-top: none;border-bottom-color: rgba(12, 12, 12, 0.12);"
                                    type="text"
                                    @if ($pengkajian?->alergi_lainnya == 'Ya') value="{{ $pengkajian?->ket_alergi_lainnya }}" @endif>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" class="custom-control-input" value="Tidak"
                                        name="alergi_lainnya" id="alergi_lainnya2"
                                        @if ($pengkajian?->alergi_lainnya == 'Tidak') checked @endif>
                                    <label class="custom-control-label text-primary" for="alergi_lainnya2">Tidak</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group mb-3">
                                <label for="reaksi_alergi_obat" class="control-label text-primary ">Reaksi
                                    terhadap
                                    alergi
                                    obat</label>
                                <input name="reaksi_alergi_obat" id="reaksi_alergi_obat" class="form-control alergi"
                                    type="text" value="{{ $pengkajian?->reaksi_alergi_obat }}">
                            </div>
                            <div class="form-group mb-3">
                                <label for="reaksi_alergi_makanan" class="control-label text-primary">Reaksi
                                    terhadap
                                    alergi
                                    makanan</label>
                                <input name="reaksi_alergi_makanan" id="reaksi_alergi_makanan"
                                    class="form-control alergi" type="text"
                                    value="{{ $pengkajian?->reaksi_alergi_makanan }}">
                            </div>
                            <div class="form-group mb-3">
                                <label for="reaksi_alergi_lainnya" class="control-label text-primary">Reaksi
                                    terhadap
                                    alergi
                                    lainnya</label>
                                <input name="reaksi_alergi_lainnya" id="reaksi_alergi_lainnya"
                                    class="form-control alergi" type="text"
                                    value="{{ $pengkajian?->reaksi_alergi_lainnya }}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label for="kondisi_khusus1" class="control-label text-primary margin-tb-10">Gelang tanda
                                    alergi</label>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" value="1" name="gelang"
                                        id="gelang1" {{ $pengkajian?->gelang == 1 ? 'checked' : '' }}>
                                    <label class="custom-control-label text-primary" for="gelang1">Dipasang
                                        (warna
                                        merah)</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <header class="text-secondary">
                        <h4 class="mt-5 font-weight-bold">SKRINING NYERI</h4>
                    </header>
                    <div class="row mt-3">
                        <div class="col-md-12 mb-4 d-flex flex-wrap justify-content-between">
                            <div class="img-baker d-flex flex-column align-items-center" style="width: 14%;">
                                <img src="{{ asset('img/emoticon/1.jpg') }}" class="mb-2 img-fluid">
                                <div class="text-center">
                                    <span class="badge badge-warning text-white" data-skor="0">0</span>
                                </div>
                            </div>
                            <div class="img-baker d-flex flex-column align-items-center" style="width: 14%;">
                                <img src="{{ asset('img/emoticon/2.jpg') }}" class="mb-2 img-fluid">
                                <div class="text-center">
                                    <span class="badge badge-success" data-skor="1">1</span>
                                    <span class="badge badge-success" data-skor="2">2</span>
                                </div>
                            </div>
                            <div class="img-baker d-flex flex-column align-items-center" style="width: 14%;">
                                <img src="{{ asset('img/emoticon/3.jpg') }}" class="mb-2 img-fluid">
                                <div class="text-center">
                                    <span class="badge badge-primary" data-skor="3">3</span>
                                    <span class="badge badge-primary" data-skor="4">4</span>
                                </div>
                            </div>
                            <div class="img-baker d-flex flex-column align-items-center" style="width: 14%;">
                                <img src="{{ asset('img/emoticon/4.jpg') }}" class="mb-2 img-fluid">
                                <div class="text-center">
                                    <span class="badge badge-info" data-skor="5">5</span>
                                    <span class="badge badge-info" data-skor="6">6</span>
                                </div>
                            </div>
                            <div class="img-baker d-flex flex-column align-items-center" style="width: 14%;">
                                <img src="{{ asset('img/emoticon/5.jpg') }}" class="mb-2 img-fluid">
                                <div class="text-center">
                                    <span class="badge badge-orange" data-skor="7">7</span>
                                    <span class="badge badge-orange" data-skor="8">8</span>
                                </div>
                            </div>
                            <div class="img-baker d-flex flex-column align-items-center" style="width: 14%;">
                                <img src="{{ asset('img/emoticon/6.jpg') }}" class="mb-2 img-fluid">
                                <div class="text-center">
                                    <span class="badge badge-red" data-skor="9">9</span>
                                    <span class="badge badge-red" data-skor="10">10</span>
                                </div>
                            </div>
                            <div class="img-baker d-flex flex-column align-items-center" style="width: 14%;">
                                <input name="skor_nyeri" id="skor_nyeri" class="form-control text-center mt-3"
                                    style="font-size: 3rem; height: 60px;" type="text"
                                    value="{{ $pengkajian?->skor_nyeri }}">
                                <label for="skor_nyeri" class="control-label text-primary">Skor</label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="form-group">
                                <label for="provokatif" class="control-label text-primary">Provokatif</label>
                                <input name="provokatif" id="provokatif" class="form-control" type="text"
                                    value="{{ $pengkajian?->provokatif }}">
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="form-group">
                                <label for="quality" class="control-label text-primary">Quality</label>
                                <input name="quality" id="quality" class="form-control" type="text"
                                    value="{{ $pengkajian?->quality }}">
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="form-group">
                                <label for="region" class="control-label text-primary">Region</label>
                                <input name="region" id="region" class="form-control" type="text"
                                    value="{{ $pengkajian?->region }}">
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="form-group">
                                <label for="time" class="control-label text-primary">Time</label>
                                <input name="time" id="time" class="form-control" type="text"
                                    value="{{ $pengkajian?->time }}">
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="form-group">
                                <label for="nyeri" class="control-label text-primary">Nyeri</label>
                                <select name="nyeri" id="nyeri" class="select2">
                                    <option value="-">-</option>
                                    <option value="Nyeri kronis">Nyeri kronis</option>
                                    <option value="Nyeri akut">Nyeri akut</option>
                                    <option value="TIdak ada nyeri">TIdak ada nyeri</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-9 mb-3">
                            <div class="form-group">
                                <label for="nyeri_hilang" class="control-label text-primary">Nyeri
                                    hilang
                                    apabila</label>
                                <input name="nyeri_hilang" id="nyeri_hilang" class="form-control"
                                    value="{{ $pengkajian?->nyeri_hilang }}" type="text">
                            </div>
                        </div>
                    </div>
                    <header class="text-secondary">
                        <h4 class="mt-5 font-weight-bold">SKRINING GIZI</h4>
                    </header>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="penurunan_bb" class="control-label text-primary">Penurunan
                                    berat
                                    badan
                                    6
                                    bln
                                    terakhir</label>
                                <select name="penurunan_bb" id="penurunan_bb" class="select2">
                                    <option></option>
                                    <option value="Tidak">Tidak</option>
                                    <option value="Tidak yakin / Ragu-ragu">Tidak yakin / Ragu-ragu
                                    </option>
                                    <option value="Ya, 1-5 Kg">Ya, 1-5 Kg</option>
                                    <option value="Ya, 6-10 Kg">Ya, 6-10 Kg</option>
                                    <option value="Ya, 11-15 Kg">Ya, 11-15 Kg</option>
                                    <option value="Ya, > 15 Kg">Ya, &gt; 15 Kg</option>
                                    <option value="Ya, tidak tahu berapa Kg">Ya, tidak tahu berapa Kg
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="asupan_makan" class="control-label text-primary">Asupan
                                    makanan
                                    pasien</label>
                                <select name="asupan_makan" id="asupan_makan" class="select2">
                                    <option></option>
                                    <option value="Normal">Normal</option>
                                    <option value="Berkurang, penurunan nafsu makan/kesulitan menerima makan"
                                        data-skor="1">
                                        Berkurang, penurunan nafsu makan/kesulitan menerima makan
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <label for="kondisi_khusus1" class="control-label text-primary mt-3">Pasien dalam
                        kondisi
                        khusus</label>
                    @php
                        $kondisi_khusus_terpilih = json_decode($pengkajian?->kondisi_khusus ?? '[]', true);
                    @endphp

                    <div class="row mt-3">
                        @foreach (['Anak usia 1-5 tahun', 'Lansia > 60 tahun', 'Penyakit kronis dengan komplikasi', 'Kanker stadium III/IV', 'HIV/AIDS', 'TB', 'Bedah mayor degestif', 'Luka bakar > 20%'] as $index => $kondisi)
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="form-radio">
                                        <label class="custom-control custom-checkbox custom-control-inline">
                                            <input name="kondisi_khusus[]" id="kondisi_khusus{{ $index + 1 }}"
                                                value="{{ $kondisi }}" type="checkbox" class="custom-control-input"
                                                {{ in_array($kondisi, $kondisi_khusus_terpilih) ? 'checked' : '' }}>
                                            <span class="custom-control-label text-primary">{{ $kondisi }}</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <header class="text-secondary">
                        <h4 class="mt-5 font-weight-bold">RIWAYAT IMUNISASI DASAR</h4>
                    </header>
                    @php
                        $imunisasi_dasar_terpilih = json_decode($pengkajian?->imunisasi_dasar ?? '[]', true);
                    @endphp

                    <div class="row mt-3">
                        @foreach (['BCG', 'DPT', 'Hepatitis B', 'Polio', 'Campak'] as $index => $imunisasi)
                            <div class="col-md-3 mb-3">
                                <div class="form-group">
                                    <div class="form-radio">
                                        <label class="custom-control custom-checkbox custom-control-inline">
                                            <input name="imunisasi_dasar[]" id="imunisasi_dasar{{ $index + 1 }}"
                                                value="{{ $imunisasi }}" type="checkbox" class="custom-control-input"
                                                {{ in_array($imunisasi, $imunisasi_dasar_terpilih) ? 'checked' : '' }}>
                                            <span class="custom-control-label text-primary">{{ $imunisasi }}</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <header class="text-secondary">
                        <h4 class="mt-5 font-weight-bold">SKRINING RESIKO JATUH - GET UP & GO</h4>
                    </header>
                    @php
                        $resiko_jatuh_terpilih = json_decode($pengkajian?->resiko_jatuh ?? '[]', true);
                    @endphp

                    <div class="row mt-3">
                        <div class="col-md-12 mb-3">
                            <label for="resiko_jatuh3" class="control-label text-primary margin-tb-10">A.
                                Cara
                                Berjalan</label>
                        </div>

                        @foreach (['Tidak seimbang/sempoyongan/limbung', 'Alat bantu: kruk,kursi roda/dibantu', 'Pegang pinggiran meja/kursi/alat bantu untuk duduk'] as $index => $resiko)
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    @if ($index == 2)
                                        <label for="resiko_jatuh{{ $index + 1 }}"
                                            class="control-label mb-3 text-primary margin-tb-10">B.
                                            Menopang
                                            saat duduk</label>
                                    @endif
                                    <div class="form-radio">
                                        <label class="custom-control custom-checkbox custom-control-inline">
                                            <input onclick="resiko_jatuh()" name="resiko_jatuh[]"
                                                id="resiko_jatuh{{ $index + 1 }}" value="{{ $resiko }}"
                                                type="checkbox" class="custom-control-input"
                                                {{ in_array($resiko, $resiko_jatuh_terpilih) ? 'checked' : '' }}>
                                            <span class="custom-control-label text-primary">{{ $resiko }}</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <span class="input-group-addon grey-text">Hasil : </span>
                                <div class="input-group-content">
                                    <input class="form-control" name="hasil_resiko_jatuh" id="resiko_jatuh_hasil"
                                        type="text" readonly>
                                </div>
                            </div>
                        </div>
                    </div>


                    <header class="text-secondary">
                        <h4 class="mt-5 font-weight-bold">RIWAYAT PSIKOSOSIAL, SPIRITUAL &amp;
                            KEPERCAYAAN
                        </h4>
                    </header>
                    <div class="row mt-3">
                        <div class="col-md-3 mb-3">
                            <div class="form-group">
                                <label for="status_psikologis" class="control-label text-primary">Status
                                    psikologis</label>
                                <select name="status_psikologis" id="status_psikologis" class="select2">
                                    <option></option>
                                    <option value="Tenang">Tenang</option>
                                    <option value="Cemas">Cemas</option>
                                    <option value="Takut">Takut</option>
                                    <option value="Marah">Marah</option>
                                    <option value="Sedih">Sedih</option>
                                    <option value="Kecenderungan bunuh diri">Kecenderungan bunuh diri
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="form-group">
                                <label for="status_spiritual" class="control-label text-primary">Status
                                    spiritual</label>
                                <select name="status_spiritual" id="status_spiritual" class="select2">
                                    <option></option>
                                    <option value="Percaya Nilai-nilai dan kepercayaan">Percaya
                                        Nilai-nilai
                                        dan
                                        kepercayaan
                                    </option>
                                    <option value="Tidak Percaya Nilai-nilai dan kepercayaan">Tidak
                                        Percaya
                                        Nilai-nilai
                                        dan
                                        kepercayaan</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="form-group">
                                <label for="masalah_prilaku" class="control-label text-primary">Masalah
                                    prilaku(bila
                                    ada)</label>
                                <input name="masalah_prilaku" id="masalah_prilaku" class="form-control"
                                    value="{{ $pengkajian?->masalah_prilaku }}" type="text">
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="form-group">
                                <label for="kekerasan_dialami" class="control-label text-primary">Kekerasan yg
                                    pernah
                                    dialami</label>
                                <input name="kekerasan_dialami" id="kekerasan_dialami" class="form-control"
                                    value="{{ $pengkajian?->kekerasan_dialami }}" type="text">
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="form-group">
                                <label for="hub_dengan_keluarga" class="control-label text-primary">Hubungan
                                    dengan
                                    anggota
                                    keluarga</label>
                                <input name="hub_dengan_keluarga" id="hub_dengan_keluarga"
                                    value="{{ $pengkajian?->hub_dengan_keluarga }}" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="form-group">
                                <label for="tempat_tinggal" class="control-label text-primary">Tempat
                                    tinggal
                                    (rumah/panti/kos/dll)</label>
                                <input name="tempat_tinggal" id="tempat_tinggal" class="form-control"
                                    value="{{ $pengkajian?->tempat_tinggal }}" type="text">
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="form-group">
                                <label for="kerabat_dihub" class="control-label text-primary">Kerabat
                                    yang
                                    dapat
                                    dihubungi</label>
                                <input name="kerabat_dihub" id="kerabat_dihub" class="form-control"
                                    value="{{ $pengkajian?->kerabat_dihub }}" type="text">
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="form-group">
                                <label for="no_kontak_kerabat" class="control-label text-primary">Kontak
                                    kerabat
                                    yang
                                    dapat
                                    dihubungi</label>
                                <input name="no_kontak_kerabat" id="no_kontak_kerabat" class="form-control"
                                    value="{{ $pengkajian?->no_kontak_kerabat }}" type="text">
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="form-group">
                                <label for="status_perkawinan" class="control-label text-primary">Status
                                    perkawinan</label>
                                <input name="status_perkawinan" id="status_perkawinan" class="form-control"
                                    value="{{ $pengkajian?->registration?->patient?->married_status }}" readonly
                                    type="text">
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="form-group">
                                <label for="pekerjaan" class="control-label text-primary">Pekerjaan</label>
                                <input name="pekerjaan" id="pekerjaan" class="form-control"
                                    value="{{ $pengkajian?->registration?->patient?->job }}" readonly type="text">
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="form-group">
                                <label for="penghasilan" class="control-label text-primary">Penghasilan</label>
                                <select name="penghasilan" id="penghasilan" class="select2">
                                    <option></option>
                                    <option value="< 1 Juta">&lt; 1 Juta</option>
                                    <option value="1 - 2,9 Juta">1 - 2,9 Juta</option>
                                    <option value="3 - 4,9 Juta">3 - 4,9 Juta</option>
                                    <option value="5 - 9,9 Juta">5 - 9,9 Juta</option>
                                    <option value="10 - 14,9 Juta">10 - 14,9 Juta</option>
                                    <option value="15 - 19.5 Juta">15 - 19.5 Juta</option>
                                    <option value="> 20 Juta">&gt; 20 Juta</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="form-group">
                                <label for="pendidikan" class="control-label text-primary">Pendidikan</label>
                                <input name="pendidikan" id="pendidikan" class="form-control" type="text"
                                    value="{{ $pengkajian?->registration?->patient?->last_education }}" readonly>
                            </div>
                        </div>
                    </div>
                    <header class="text-secondary">
                        <h4 class="mt-5 font-weight-bold">KEBUTUHAN EDUKASI</h4>
                        <label for="hambatan_belajar1"
                            class="control-label font-weight-bold text-primary margin-tb-10">Hambatan
                            dalam
                            pembelajaran</label>
                    </header>
                    <div class="row mt-3">
                        @php
                            $hambatan_belajar_terpilih = json_decode($pengkajian?->hambatan_belajar ?? '[]', true);
                            $options = [
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

                        @foreach ($options as $key => $option)
                            <div class="col-md-3 mb-3">
                                <div class="form-group">
                                    <div class="form-radio">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" name="hambatan_belajar[]"
                                                id="hambatan_belajar{{ $key + 1 }}" value="{{ $option }}"
                                                type="checkbox"
                                                {{ in_array($option, $hambatan_belajar_terpilih) ? 'checked' : '' }}>
                                            <label for="hambatan_belajar{{ $key + 1 }}"
                                                class="custom-control-label text-primary">{{ $option }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="col-md-3 mb-3">
                            <div class="form-group">
                                <label for="hambatan_lainnya" class="control-label text-primary">Hambatan
                                    lainnya</label>
                                <input name="hambatan_lainnya" id="hambatan_lainnya" class="form-control" type="text"
                                    value="{{ $pengkajian?->hambatan_lainnya }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="kebutuhan_penerjemah" class="control-label text-primary">Kebutuhan
                                    penerjemah</label>
                                <input name="kebutuhan_penerjemah" id="kebutuhan_penerjemah" class="form-control"
                                    type="text" value="{{ $pengkajian?->kebutuhan_penerjemah }}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="kebuthan_pembelajaran1"
                                class="control-label font-weight-bold margin-tb-10 text-primary mt-3">Kebutuhan
                                pembelajaran</label>
                        </div>
                        @php
                            $kebutuhan_pembelajaran_terpilih = json_decode(
                                $pengkajian?->kebutuhan_pembelajaran ?? '[]',
                                true,
                            ); // Data dari database
                            $options = [
                                'Diagnosa managemen',
                                'Obat-obatan',
                                'Perawatan luka',
                                'Rehabilitasi',
                                'Diet & nutrisi',
                                'Tidak ada Hambatan',
                            ];
                        @endphp

                        @foreach ($options as $key => $option)
                            <div class="col-md-3 mt-3">
                                <div class="form-group">
                                    <div class="form-radio">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" name="kebutuhan_pembelajaran[]"
                                                id="kebutuhan_pembelajaran{{ $key + 1 }}"
                                                value="{{ $option }}" type="checkbox"
                                                {{ in_array($option, $kebutuhan_pembelajaran_terpilih) ? 'checked' : '' }}>
                                            <label for="kebutuhan_pembelajaran{{ $key + 1 }}"
                                                class="custom-control-label text-primary">{{ $option }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="col-md-12 mt-3">
                            <label for="pembelajaran_lainnya"
                                class="control-label font-weight-bold margin-tb-10 text-primary">Kebutuhan
                                pembelajaran
                                lainnya</label>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <input name="pembelajaran_lainnya" id="pembelajaran_lainnya" class="form-control"
                                    type="text" value="{{ $pengkajian?->pembelajaran_lainnya }}">
                            </div>
                        </div>
                    </div>

                    <header class="text-secondary">
                        <h4 class="mt-5 font-weight-bold">Assesment Fungsional (Pengkajian Fungsi)</h4>
                    </header>
                    <header class="text-danger">
                        <h4 class="mt-5 font-weight-bold">Sensorik</h4>
                    </header>
                    <div class="row mt-3">
                        @php
                            if ($pengkajian) {
                                $data = json_decode($pengkajian->sensorik, true);

                                if (json_last_error() !== JSON_ERROR_NONE) {
                                    dd('JSON Error: ' . json_last_error_msg());
                                }
                            } else {
                                $data = [];
                            }

                            $opsi = [
                                'sensorik_penglihatan' => ['Normal', 'Kabur', 'Kaca Mata', 'Lensa Kontak'],
                                'sensorik_penciuman' => ['Normal', 'Tidak'],
                                'sensorik_pendengaran' => ['Normal', 'Tuli Ka / Ki', 'Ada alat bantu dengar ka/ki'],
                            ];

                        @endphp
                        <table class="table">
                            <tbody>
                                @foreach ($opsi as $kategori => $listOpsional)
                                    <tr>
                                        <td>{{ Str::of($kategori)->after('sensorik_')->ucfirst() }}
                                        </td>
                                        @foreach ($listOpsional as $i => $opsiValue)
                                            <td {{ $loop->remaining < 3 - count($listOpsional) ? 'colspan=2' : '' }}>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input name="{{ $kategori }}" id="{{ $kategori . $i }}"
                                                        value="{{ $opsiValue }}" data-skor="{{ $i }}"
                                                        class="custom-control-input" type="radio"
                                                        @checked(($data[$kategori] ?? '') == $opsiValue)>
                                                    <label class="custom-control-label" for="{{ $kategori . $i }}">
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

                    <header class="text-danger">
                        <h4 class="mt-5 font-weight-bold">Kognitif</h4>
                    </header>
                    <div class="row mt-3">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input name="kognitif" class="custom-control-input" id="kognitif1"
                                                value="Normal" data-skor="0" type="radio"
                                                @checked(($pengkajian->kognitif ?? '') == 'Normal')>
                                            <label class="custom-control-label" for="kognitif1">Normal</label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input name="kognitif" class="custom-control-input" id="kognitif2"
                                                value="Bingung" data-skor="1" type="radio"
                                                @checked(($pengkajian->kognitif ?? '') == 'Bingung')>
                                            <label class="custom-control-label" for="kognitif2">Bingung</label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input name="kognitif" class="custom-control-input" id="kognitif3"
                                                value="Pelupa" data-skor="2" type="radio"
                                                @checked(($pengkajian->kognitif ?? '') == 'Pelupa')>
                                            <label class="custom-control-label" for="kognitif3">Pelupa</label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input name="kognitif" class="custom-control-input" id="kognitif4"
                                                value="Tidak Dapat dimengerti" data-skor="3" type="radio"
                                                @checked(($pengkajian->kognitif ?? '') == 'Tidak Dapat dimengerti')>
                                            <label class="custom-control-label" for="kognitif4">Tidak
                                                Dapat
                                                dimengerti</label>
                                        </div>
                                    </td>
                                    <td>
                                        &nbsp;
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <header class="text-danger">
                        <h4 class="mt-5 font-weight-bold">Motorik</h4>
                    </header>
                    <div class="row mt-3">
                        @php
                            if ($pengkajian) {
                                $data = json_decode($pengkajian->motorik, true);

                                if (json_last_error() !== JSON_ERROR_NONE) {
                                    dd('JSON Error: ' . json_last_error_msg());
                                }
                            } else {
                                $data = [];
                            }

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
                        <table class="table">
                            <tbody>
                                @foreach ($opsiMotorik as $kategori => $opsiList)
                                    <tr>
                                        <td>{{ Str::of($kategori)->after('motorik_')->replace('_', ' ')->ucfirst() }}
                                        </td>
                                        @foreach ($opsiList as $i => $opsiValue)
                                            <td @if ($loop->last && $loop->count < 4) colspan="{{ 5 - $loop->count }}" @endif>
                                                <div class="custom-control custom-radio">
                                                    <input name="{{ $kategori }}" id="{{ $kategori . $i }}"
                                                        value="{{ $opsiValue }}" data-skor="{{ $i }}"
                                                        class="custom-control-input" type="radio"
                                                        @checked(($data[$kategori] ?? '') == $opsiValue)>
                                                    <label class="custom-control-label text-primary"
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

                    {{-- Contoh pemanggilan yang sudah diperbaiki --}}
                    @include('pages.simrs.erm.partials.signature-field', [
                        'judul' => 'Perawat,',
                        'pic' => auth()->user()->employee->fullname,
                        'role' => 'perawat',
                        'prefix' => 'triage', // Berikan prefix unik
                        'signature_model' => $pengkajian?->signature, // Kirim model data tanda tangan yang relevan
                    ])


                    <div class="row mt-5">
                        <div class="col-md-12 px-3">
                            <div class="card-actionbar">
                                <div class="card-actionbar-row d-flex justify-content-between align-items-center">
                                    <button type="button"
                                        class="btn btn-primary waves-effect waves-light save-form d-flex align-items-center"
                                        data-dismiss="modal" data-status="0">
                                        <span class="mdi mdi-printer mr-2"></span> Print
                                    </button>
                                    <div style="width: 40%" class="d-flex justify-content-end">
                                        <button type="button"
                                            class="btn mr-2 btn-warning waves-effect text-white waves-light save-form d-flex align-items-center"
                                            data-dismiss="modal" data-status="0" id="sd-pengkajian-nurse-rajal">
                                            <span class="mdi mdi-content-save mr-2"></span> Simpan
                                            (draft)
                                        </button>
                                        <button type="button"
                                            class="btn btn-primary waves-effect waves-light save-form d-flex align-items-center"
                                            data-dismiss="modal" data-status="1" id="sf-pengkajian-nurse-rajal">
                                            <span class="mdi mdi-content-save mr-2"></span> Simpan
                                            (final)
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
    <script script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(document).ready(function() {
            const pengkajian = @json($pengkajian ?? []);

            $('body').addClass('layout-composed');
            $('.select2').select2({
                placeholder: 'Pilih Item',
            });
            $('#departement_id').select2({
                placeholder: 'Pilih Klinik',
            });
            // $('#doctor_id').select2({
            //     placeholder: 'Pilih Dokter',
            // });

            if (pengkajian) {
                $('#diagnosa-keperawatan').val(pengkajian.diagnosa_keperawatan).select2();
                $('#rencana-tindak-lanjut').val(pengkajian.rencana_tindak_lanjut).select2();
                $('#nyeri').val(pengkajian.nyeri).select2();
                $('#penurunan_bb').val(pengkajian.penurunan_bb).select2();
                $('#asupan_makan').val(pengkajian.asupan_makan).select2();
                $('#status_psikologis').val(pengkajian.status_psikologis).select2();
                $('#status_spiritual').val(pengkajian.status_spiritual).select2();
                $('#penghasilan').val(pengkajian.penghasilan).select2();
            }

            $('#toggle-pasien').on('click', function() {
                var target = $('#js-slide-left'); // Mengambil elemen target berdasarkan data-target
                var backdrop = $('.slide-backdrop'); // Mengambil backdrop

                // Toggle kelas untuk menampilkan atau menyembunyikan panel dan backdrop
                target.toggleClass('hide');
                backdrop.toggleClass('show');
            });

            $('.slide-backdrop').on('click', function() {
                $('#js-slide-left').removeClass('slide-on-mobile-left-show');
                $(this).removeClass('show');
            });

            // Filter Pasien
            // $('.filter-pasien').on('change', function(e) {
            //     e.preventDefault(); // Mencegah form submit langsung
            //     console.log('changed')
            //     $.ajax({
            //         url: "{{ route('poliklinik.filter-pasien') }}",
            //         type: "POST",
            //         data: {
            //             _token: "{{ csrf_token() }}", // Tambahkan token CSRF
            //             route: window.location.href,
            //             departement_id: $('#filter_pasien #departement_id').val(),
            //             doctor_id: $('#filter_pasien #doctor_id').val()
            //         },

            //         dataType: "json",
            //         beforeSend: function() {
            //             $('#daftar-pasien .col-12').html(
            //                 '<p>Sedang memuat...</p>'); // Tambahkan loading
            //         },
            //         success: function(response) {
            //             if (response.success) {
            //                 $('#daftar-pasien .col-12').html(response.html);
            //             } else {
            //                 $('#daftar-pasien .col-12').html(
            //                     '<p>Tidak ada data pasien.</p>');
            //             }
            //         },
            //         error: function(xhr, status, error) {
            //             alert("Terjadi kesalahan, silakan coba lagi.");
            //         }
            //     });
            // });


            if ($('#filter_pasien #departement_id').val() != null || $('#filter_pasien #doctor_id').val() !=
                null) {
                $.ajax({
                    url: "{{ route('poliklinik.filter-pasien') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}", // Tambahkan token CSRF
                        route: window.location.href,
                        departement_id: $('#filter_pasien #departement_id').val(),
                        doctor_id: $('#filter_pasien #doctor_id').val()
                    },
                    dataType: "json",
                    beforeSend: function() {
                        $('#daftar-pasien .col-12').html(
                            '<p>Sedang memuat...</p>'); // Tambahkan loading
                    },
                    success: function(response) {


                        if (response.success) {
                            $('#daftar-pasien .col-12').html(response.html);
                        } else {
                            $('#daftar-pasien .col-12').html(
                                '<p>Tidak ada data pasien.</p>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                        alert("Terjadi kesalahan, silakan coba lagi.");
                    }
                });
            }
        });
    </script>
    @include('pages.simrs.poliklinik.partials.action-js.pengkajian-perawat')
@endsection
