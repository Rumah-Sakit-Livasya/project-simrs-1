{{-- @include('pages.simrs.pendaftaran.partials.menu')
@include('pages.simrs.pendaftaran.partials.header-pasien') --}}
<div id="pengkajian-perawat-rajal" class="tab-pane fade show active" role="tabpanel">
    <form autocomplete="off" novalidate method="post" id="pengkajian-nurse-rajal-form">
        @method('post')
        @csrf
        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
        <input type="hidden" name="registration_id" value="{{ $registration->id }}">
        <div class="card-actionbar">
            <div class="card-actionbar-row mt-3">
                <button class="btn btn-primary m-3" id="histori_pengkajian" type="button"><i
                        class="mdi mdi-history"></i>
                    Histori</button>
            </div>
        </div>
        <div class="card" style="box-shadow: none; border: none;">
            <div class="card-body">
                <header class="text-primary text-center font-weight-bold mb-4">
                    <h2>PENGKAJIAN PERAWAT</h4>
                </header>
                <header class="text-success">
                    <h4 class="mt-5 font-weight-bold">MASUK RUMAH SAKIT</h4>
                </header>
                <div class="row mt-3">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="tgl_masuk" class="control-label text-primary">Tanggal &amp; jam masuk</label>
                            <div class="form-group mb-3">
                                <div class="input-group">
                                    <input type="text" name="tgl_masuk" class="form-control " placeholder="Tanggal"
                                        id="tgl_masuk" value="{{ $registration->created_at->format('d-m-Y') }}">
                                    <input type="time" name="jam_masuk" class="form-control " placeholder="Jam"
                                        id="jam_masuk" value="{{ $registration->created_at->format('h:i') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <label for="tgl_masuk" class="control-label text-primary">Tanggal &amp; jam masuk</label>
                            <div class="input-group">
                                <input type="text" name="tgl_dilayani" class="form-control" placeholder="Tanggal"
                                    id="tgl_dilayani" value="{{ now()->format('d-m-Y') }}">
                                <input type="time" name="jam_dilayani" class="form-control" placeholder="Jam"
                                    id="jam_dilayani" value="{{ now()->format('h:i') }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="keluhan_utama" class="control-label text-primary">Keluhan utama *</label>
                            <textarea class="form-control" id="keluhan_utama" name="keluhan_utama" rows="3" required=""
                                data-label="Keluhan utama"></textarea>
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
                                    <input id="pr" type="text" name="pr" class="form-control">
                                    <div class="input-group-append">
                                        <span class="input-group-text">x/menit</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="rr" class="text-primary">Respirasi (RR)</label>
                            <div class="input-group">
                                <input class="form-control numeric" id="rr" name="rr" type="text">
                                <div class="input-group-append">
                                    <span class="input-group-text">x/menit</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="bp" class="text-primary">Tensi (BP)</label>
                            <div class="input-group">
                                <input class="form-control numeric" id="bp" name="bp" type="text">
                                <div class="input-group-append">
                                    <span class="input-group-text">mmHg</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="temperatur" class="text-primary">Suhu (T)</label>
                            <div class="input-group">
                                <input class="form-control numeric" id="temperatur" name="temperatur"
                                    type="text">
                                <div class="input-group-append">
                                    <span class="input-group-text">C°</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="height" class="text-primary">Tinggi Badan</label>
                            <div class="input-group">
                                <input class="form-control numeric calc-bmi" id="body_height" name="body_height"
                                    type="text">
                                <div class="input-group-append">
                                    <span class="input-group-text">Cm</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="weight" class="text-primary">Berat Badan</label>
                            <div class="input-group">
                                <input class="form-control numeric calc-bmi" id="body_weight" name="body_weight"
                                    type="text">
                                <div class="input-group-append">
                                    <span class="input-group-text">Kg</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="bmi" class="text-primary">Index Massa Tubuh</label>
                            <div class="input-group">
                                <input class="form-control numeric" id="bmi" name="bmi"
                                    readonly="readonly" type="text">
                                <div class="input-group-append">
                                    <span class="input-group-text">Kg/m²</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="kat_bmi" class="text-primary">Kategori IMT</label>
                            <div class="input-group">
                                <input class="form-control" id="kat_bmi" name="kat_bmi" readonly="readonly"
                                    type="text">
                                <div class="input-group-append">
                                    <span class="input-group-text"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="sp02" class="text-primary">SP 02</label>
                            <div class="input-group">
                                <input class="form-control" id="sp02" name="sp02" type="text">
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="lingkar_kepala" class="text-primary">Lingkar Kepala</label>
                            <div class="input-group">
                                <input class="form-control" id="lingkar_kepala" name="lingkar_kepala"
                                    type="text">
                                <div class="input-group-append">
                                    <span class="input-group-text">Cm</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="nyeri" class="control-label text-primary">Diagnosa Keperawatan</label>
                            <select name="diagnosa_keperawatan" id="diagnosa_keperawatan" class="select2">
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
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nyeri" class="control-label text-primary">Rencana Tindak Lanjut</label>
                            <select name="rencana_tindak_lanjut" id="rencana_tindak_lanjut" class="select2">
                                <option value="-">-</option>
                                <option value="Kolaborasi Dokter">Kolaborasi Dokter</option>
                                <option value="Perawatan Luka">Perawatan Luka</option>
                                <option value="Memberikan Edukasi">Memberikan Edukasi</option>
                                <option value="Mengukur tanda - tanda vital">Mengukur tanda - tanda vital</option>
                            </select>
                        </div>
                    </div>
                </div>

                <header class="text-danger">
                    <h4 class="mt-5 font-weight-bold">ALERGI DAN REAKSI</h4>
                </header>
                <div class="row mt-3">
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="alergi_obat" class="control-label text-primary margin-tb-10 d-block">Alergi
                                Obat</label>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" value="Ya" name="alergi_obat"
                                    id="alergi_obat1">
                                <label class="custom-control-label text-primary" for="alergi_obat1">Ya</label>
                            </div>
                            <input name="ket_alergi_obat" id="ket_alergi_obat"
                                style="margin-right: 10px; width: 200px;border-left: none;border-right: none;border-top: none;border-bottom-color: rgba(12, 12, 12, 0.12);"
                                type="text">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" value="Tidak" name="alergi_obat"
                                    id="alergi_obat2">
                                <label class="custom-control-label text-primary" for="alergi_obat2">Tidak</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="reaksi_alergi_obat" class="control-label text-primary ">Reaksi terhadap alergi
                                obat</label>
                            <input name="reaksi_alergi_obat" id="reaksi_alergi_obat" class="form-control alergi"
                                type="text">
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="alergi_makanan" class="control-label text-primary margin-tb-10 d-block">Alergi
                                Makanan</label>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" value="Ya"
                                    name="alergi_makanan" id="alergi_makanan1">
                                <label class="custom-control-label text-primary" for="alergi_makanan1">Ya</label>
                            </div>
                            <input name="ket_alergi_makanan" id="ket_alergi_makanan"
                                style="margin-right: 10px; width: 200px;border-left: none;border-right: none;border-top: none;border-bottom-color: rgba(12, 12, 12, 0.12);"
                                type="text">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" value="Tidak"
                                    name="alergi_makanan" id="alergi_makanan2">
                                <label class="custom-control-label text-primary" for="alergi_makanan2">Tidak</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="reaksi_alergi_makanan" class="control-label text-primary">Reaksi terhadap
                                alergi
                                makanan</label>
                            <input name="reaksi_alergi_makanan" id="reaksi_alergi_makanan"
                                class="form-control alergi" type="text">
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="alergi_lainnya" class="control-label text-primary margin-tb-10 d-block">Alergi
                                Lainnya</label>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" value="Ya"
                                    name="alergi_lainnya" id="alergi_lainnya1">
                                <label class="custom-control-label text-primary" for="alergi_lainnya1">Ya</label>
                            </div>
                            <input name="ket_alergi_lainnya" id="ket_alergi_lainnya"
                                style="margin-right: 10px; width: 200px;border-left: none;border-right: none;border-top: none;border-bottom-color: rgba(12, 12, 12, 0.12);"
                                type="text">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" value="Tidak"
                                    name="alergi_lainnya" id="alergi_lainnya2">
                                <label class="custom-control-label text-primary" for="alergi_lainnya2">Tidak</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="reaksi_alergi_lainnya" class="control-label text-primary">Reaksi terhadap
                                alergi
                                lainnya</label>
                            <input name="reaksi_alergi_lainnya" id="reaksi_alergi_lainnya"
                                class="form-control alergi" type="text">
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="kondisi_khusus1" class="control-label text-primary margin-tb-10">Gelang tanda
                                alergi</label>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" value="warna merah"
                                    name="gelang" id="gelang1">
                                <label class="custom-control-label text-primary" for="gelang1">Dipasang (warna
                                    merah)</label>
                            </div>
                        </div>
                    </div>
                </div>

                <header class="red-text">
                    <h4 class="mt-5">SKRINING NYERI</h4>
                </header>
                <div class="row mt-3">
                    <div class="col-md-11">
                        <div class="wongbaker">
                            <div class="img-baker">
                                <img src="http://103.191.196.126:8888/testing/include/images/wongbaker/1.jpg">
                                <div class="text-center">
                                    <span class="badge pink accent-2 pointer" data-skor="0">0</span>
                                </div>
                            </div>
                            <div class="img-baker">
                                <img src="http://103.191.196.126:8888/testing/include/images/wongbaker/2.jpg">
                                <div class="text-center">
                                    <span class="badge green pointer" data-skor="1">1</span>
                                    <span class="badge green pointer" data-skor="2">2</span>
                                </div>
                            </div>
                            <div class="img-baker">
                                <img src="http://103.191.196.126:8888/testing/include/images/wongbaker/3.jpg">
                                <div class="text-center">
                                    <span class="badge blue pointer" data-skor="3">3</span>
                                    <span class="badge blue pointer" data-skor="4">4</span>
                                </div>
                            </div>
                            <div class="img-baker">
                                <img src="http://103.191.196.126:8888/testing/include/images/wongbaker/4.jpg">
                                <div class="text-center">
                                    <span class="badge purple pointer" data-skor="5">5</span>
                                    <span class="badge purple pointer" data-skor="6">6</span>
                                </div>
                            </div>
                            <div class="img-baker">
                                <img src="http://103.191.196.126:8888/testing/include/images/wongbaker/5.jpg">
                                <div class="text-center">
                                    <span class="badge orange pointer" data-skor="7">7</span>
                                    <span class="badge orange pointer" data-skor="8">8</span>
                                </div>
                            </div>
                            <div class="img-baker">
                                <img src="http://103.191.196.126:8888/testing/include/images/wongbaker/6.jpg">
                                <div class="text-center">
                                    <span class="badge pointer red" data-skor="9">9</span>
                                    <span class="badge pointer red" data-skor="10">10</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <input name="skor_nyeri" id="skor_nyeri" class="form-control"
                                style="font-size: 3rem; height: 60px;" type="text">
                            <label for="skor_nyeri" class="control-label text-primary">Skor</label>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="provokatif" class="control-label text-primary">Provokatif</label>
                            <input name="provokatif" id="provokatif" class="form-control" type="text">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="quality" class="control-label text-primary">Quality</label>
                            <input name="quality" id="quality" class="form-control" type="text">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="region" class="control-label text-primary">Region</label>
                            <input name="region" id="region" class="form-control" type="text">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="time" class="control-label text-primary">Time</label>
                            <input name="time" id="time" class="form-control" type="text">
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-3">
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
                    <div class="col-md-9">
                        <div class="form-group">
                            <label for="nyeri_hilang" class="control-label text-primary">Nyeri hilang apabila</label>
                            <input name="nyeri_hilang" id="nyeri_hilang" class="form-control" type="text">
                        </div>
                    </div>
                </div>

                <header class="green-text">
                    <h4 class="mt-5">SKRINING GIZI</h4>
                </header>
                <div class="row mt-3">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="penurunan_bb" class="control-label text-primary">Penurunan berat badan 6 bln
                                terakhir</label>
                            <select name="penurunan_bb" id="penurunan_bb" class="select2">
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
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="asupan_makan" class="control-label text-primary">Asupan makanan pasien</label>
                            <select name="asupan_makan" id="asupan_makan" class="select2">
                                <option></option>
                                <option value="Normal">Normal</option>
                                <option value="Berkurang, penurunan nafsu makan/kesulitan menerima makan"
                                    data-skor="1">
                                    Berkurang, penurunan nafsu makan/kesulitan menerima makan</option>
                            </select>
                        </div>
                    </div>
                </div>
                <label for="kondisi_khusus1" class="control-label text-primary mt-3">Pasien dalam kondisi
                    khusus</label>
                <div class="row mt">
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="form-radio">
                                <label class="custom-control custom-checkbox custom-control-inline">
                                    <input name="kondisi_khusus1" id="kondisi_khusus1" value="Anak usia 1-5 tahun"
                                        type="checkbox" class="custom-control-input">
                                    <span class="custom-control-label text-primary">Anak usia 1-5 tahun</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="form-radio">
                                <label class="custom-control custom-checkbox custom-control-inline">
                                    <input name="kondisi_khusus2" id="kondisi_khusus2" value="Lansia > 60 tahun"
                                        type="checkbox" class="custom-control-input">
                                    <span class="custom-control-label text-primary">Lansia &gt; 60 tahun</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="form-radio">
                                <label class="custom-control custom-checkbox custom-control-inline">
                                    <input name="kondisi_khusus3" id="kondisi_khusus3"
                                        value="Penyakit kronis dengan komplikasi" type="checkbox"
                                        class="custom-control-input">
                                    <span class="custom-control-label text-primary">Penyakit kronis dengan
                                        komplikasi</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="form-radio">
                                <label class="custom-control custom-checkbox custom-control-inline">
                                    <input name="kondisi_khusus4" id="kondisi_khusus4" value="Kanker stadium III/IV"
                                        type="checkbox" class="custom-control-input">
                                    <span class="custom-control-label text-primary">Kanker stadium III/IV</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="form-radio">
                                <label class="custom-control custom-checkbox custom-control-inline">
                                    <input name="kondisi_khusus5" id="kondisi_khusus5" value="HIV/AIDS"
                                        type="checkbox" class="custom-control-input">
                                    <span class="custom-control-label text-primary">HIV/AIDS</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="form-radio">
                                <label class="custom-control custom-checkbox custom-control-inline">
                                    <input name="kondisi_khusus6" id="kondisi_khusus6" value="TB"
                                        type="checkbox" class="custom-control-input">
                                    <span class="custom-control-label text-primary">TB</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="form-radio">
                                <label class="custom-control custom-checkbox custom-control-inline">
                                    <input name="kondisi_khusus7" id="kondisi_khusus7" value="Bedah mayor degestif"
                                        type="checkbox" class="custom-control-input">
                                    <span class="custom-control-label text-primary">Bedah mayor degestif</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="form-radio">
                                <label class="custom-control custom-checkbox custom-control-inline">
                                    <input name="kondisi_khusus8" id="kondisi_khusus8" value="Luka bakar > 20%"
                                        type="checkbox" class="custom-control-input">
                                    <span class="custom-control-label text-primary">Luka bakar &gt; 20%</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <header class="purple-text">
                    <h4 class="mt-5">RIWAYAT IMUNISASI DASAR</h4>
                </header>
                <div class="row mt-3">
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="form-radio">
                                <label class="custom-control custom-checkbox custom-control-inline">
                                    <input name="imunisasi_dasar1" id="imunisasi_dasar1" value="BCG"
                                        type="checkbox" class="custom-control-input">
                                    <span class="custom-control-label text-primary">BCG</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="form-radio">
                                <label class="custom-control custom-checkbox custom-control-inline">
                                    <input name="imunisasi_dasar2" id="imunisasi_dasar2" value="DPT"
                                        type="checkbox" class="custom-control-input">
                                    <span class="custom-control-label text-primary">DPT</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="form-radio">
                                <label class="custom-control custom-checkbox custom-control-inline">
                                    <input name="imunisasi_dasar3" id="imunisasi_dasar3" value="Hepatitis B"
                                        type="checkbox" class="custom-control-input">
                                    <span class="custom-control-label text-primary">Hepatitis B</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="form-radio">
                                <label class="custom-control custom-checkbox custom-control-inline">
                                    <input name="imunisasi_dasar4" id="imunisasi_dasar4" value="Polio"
                                        type="checkbox" class="custom-control-input">
                                    <span class="custom-control-label text-primary">Polio</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="form-radio">
                                <label class="custom-control custom-checkbox custom-control-inline">
                                    <input name="imunisasi_dasar5" id="imunisasi_dasar5" value="Campak"
                                        type="checkbox" class="custom-control-input">
                                    <span class="custom-control-label text-primary">Campak</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <header class="purple-text">
                    <h4 class="mt-5">SKRINING RESIKO JATUH - GET UP & GO</h4>
                    <label for="resiko_jatuh1" class="control-label text-primary margin-tb-10">A. Cara
                        Berjalan</label>
                </header>
                <div class="row mt-3">
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <div class="form-radio">
                                <label class="custom-control custom-checkbox custom-control-inline">
                                    <input onclick="resiko_jatuh()" name="resiko_jatuh1" id="resiko_jatuh1"
                                        value="Tidak seimbang/sempoyongan/limbung" type="checkbox"
                                        class="custom-control-input">
                                    <span class="custom-control-label text-primary">Tidak
                                        seimbang/sempoyongan/limbung</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="form-radio">
                                <label class="custom-control custom-checkbox custom-control-inline">
                                    <input onclick="resiko_jatuh()" name="resiko_jatuh2" id="resiko_jatuh2"
                                        value="Alat bantu: kruk,kursi roda/dibantu" type="checkbox"
                                        class="custom-control-input">
                                    <span class="custom-control-label text-primary">Jalan dengan alat bantu(kruk,kursi
                                        roda/dibantu)</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="resiko_jatuh3" class="control-label text-primary margin-tb-10">B. Menopang
                                saat
                                duduk</label>
                            <div class="form-radio">
                                <label class="custom-control custom-checkbox custom-control-inline">
                                    <input onclick="resiko_jatuh()" name="resiko_jatuh3" id="resiko_jatuh3"
                                        value="Pegang pinggiran meja/kursi/alat bantu untuk duduk" type="checkbox"
                                        class="custom-control-input">
                                    <span class="custom-control-label text-primary">Pegang pinggiran meja/kursi/alat
                                        bantu
                                        untuk
                                        duduk</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <span class="input-group-addon grey-text">Hasil : </span>
                            <div class="input-group-content">
                                <input class="form-control" name="resiko_jatuh_hasil" id="resiko_jatuh_hasil"
                                    type="text" readonly="">
                            </div>
                        </div>
                    </div>
                </div>
                <header class="orange-text">
                    <h4 class="mt-5">RIWAYAT PSIKOSOSIAL, SPIRITUAL &amp; KEPERCAYAAN</h4>
                </header>
                <div class="row mt-3">
                    <div class="col-md-3">
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
                                <option value="Kecenderungan bunuh diri">Kecenderungan bunuh diri</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="status_spiritual" class="control-label text-primary">Status spiritual</label>
                            <select name="status_spiritual" id="status_spiritual" class="select2">
                                <option></option>
                                <option value="Percaya Nilai-nilai dan kepercayaan">Percaya Nilai-nilai dan kepercayaan
                                </option>
                                <option value="Tidak Percaya Nilai-nilai dan kepercayaan">Tidak Percaya Nilai-nilai dan
                                    kepercayaan</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="masalah_prilaku" class="control-label text-primary">Masalah prilaku(bila
                                ada)</label>
                            <input name="masalah_prilaku" id="masalah_prilaku" class="form-control" type="text">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="kekerasan_dialami" class="control-label text-primary">Kekerasan yg pernah
                                dialami</label>
                            <input name="kekerasan_dialami" id="kekerasan_dialami" class="form-control"
                                type="text">
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="hub_dengan_keluarga" class="control-label text-primary">Hubungan dengan
                                anggota
                                keluarga</label>
                            <input name="hub_dengan_keluarga" id="hub_dengan_keluarga" class="form-control"
                                type="text">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="tempat_tinggal" class="control-label text-primary">Tempat tinggal
                                (rumah/panti/kos/dll)</label>
                            <input name="tempat_tinggal" id="tempat_tinggal" class="form-control" type="text">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="kerabat_dihub" class="control-label text-primary">Kerabat yang dapat
                                dihubungi</label>
                            <input name="kerabat_dihub" id="kerabat_dihub" class="form-control" type="text">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="no_kontak_kerabat" class="control-label text-primary">Kontak kerabat yang
                                dapat
                                dihubungi</label>
                            <input name="no_kontak_kerabat" id="no_kontak_kerabat" class="form-control"
                                type="text">
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="status_perkawinan" class="control-label text-primary">Status
                                perkawinan</label>
                            <input name="status_perkawinan" id="status_perkawinan" class="form-control"
                                value="Belum Nikah" disabled="" type="text">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="pekerjaan" class="control-label text-primary">Pekerjaan</label>
                            <input name="pekerjaan" id="pekerjaan" class="form-control" value=""
                                disabled="" type="text">
                        </div>
                    </div>
                    <div class="col-md-3">
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
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="pendidikan" class="control-label text-primary">Pendidikan</label>
                            <input name="pendidikan" id="pendidikan" class="form-control" type="text"
                                value="Belum / Tidak tamat SD">
                        </div>
                    </div>
                </div>
                <header class="brown-text">
                    <h4 class="mt-5">KEBUTUHAN EDUKASI</h4>
                    <label for="hambatan_belajar1" class="control-label text-primary margin-tb-10">Hambatan
                        dalam
                        pembelajaran</label>
                </header>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="form-radio">
                                <label class="checkbox-styled checkbox-success no-margin">
                                    <input name="hambatan_belajar1" id="hambatan_belajar1" value="Pendengaran"
                                        type="checkbox"> <span>Pendengaran</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="form-radio">
                                <label class="checkbox-styled checkbox-success no-margin">
                                    <input name="hambatan_belajar2" id="hambatan_belajar2" value="Penglihatan"
                                        type="checkbox"> <span>Penglihatan</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="form-radio">
                                <label class="checkbox-styled checkbox-success no-margin">
                                    <input name="hambatan_belajar3" id="hambatan_belajar3" value="Kognitif"
                                        type="checkbox"> <span>Kognitif</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="form-radio">
                                <label class="checkbox-styled checkbox-success no-margin">
                                    <input name="hambatan_belajar4" id="hambatan_belajar4" value="Fisik"
                                        type="checkbox"> <span>Fisik</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="form-radio">
                                <label class="checkbox-styled checkbox-success no-margin">
                                    <input name="hambatan_belajar5" id="hambatan_belajar5" value="Budaya"
                                        type="checkbox"> <span>Budaya</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="form-radio">
                                <label class="checkbox-styled checkbox-success no-margin">
                                    <input name="hambatan_belajar6" id="hambatan_belajar6" value="Agama"
                                        type="checkbox"> <span>Agama</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="form-radio">
                                <label class="checkbox-styled checkbox-success no-margin">
                                    <input name="hambatan_belajar7" id="hambatan_belajar7" value="Emosi"
                                        type="checkbox"> <span>Emosi</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="form-radio">
                                <label class="checkbox-styled checkbox-success no-margin">
                                    <input name="hambatan_belajar8" id="hambatan_belajar8" value="Bahasa"
                                        type="checkbox"> <span>Bahasa</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="form-radio">
                                <label class="checkbox-styled checkbox-success no-margin">
                                    <input name="ambatan_belajar9" id="ambatan_belajar9" value="Tidak ada Hamabatan"
                                        type="checkbox"> <span>Tidak ada Hamabatan</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="hambatan_lainnya" class="control-label text-primary">Hambatan lainnya</label>
                            <input name="hambatan_lainnya" id="hambatan_lainnya" class="form-control"
                                type="text">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="kebutuhan_penerjemah" class="control-label text-primary">Kebutuhan
                                penerjemah</label>
                            <input name="kebutuhan_penerjemah" id="kebutuhan_penerjemah" class="form-control"
                                type="text">
                        </div>
                    </div>
                </div>
                <label for="kebuthan_pembelajaran1" class="control-label text-primary mt-3">Kebutuhan
                    pembelajaran</label>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="form-radio">
                                <label class="checkbox-styled checkbox-success no-margin">
                                    <input name="kebuthan_pembelajaran1" id="kebuthan_pembelajaran1"
                                        value="Diagnosa managemen" type="checkbox"> <span>Diagnosa managemen</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="form-radio">
                                <label class="checkbox-styled checkbox-success no-margin">
                                    <input name="kebuthan_pembelajaran2" id="kebuthan_pembelajaran2"
                                        value="Obat-obatan" type="checkbox"> <span>Obat-obatan</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="form-radio">
                                <label class="checkbox-styled checkbox-success no-margin">
                                    <input name="kebuthan_pembelajaran3" id="kebuthan_pembelajaran3"
                                        value="Perawatan luka" type="checkbox"> <span>Perawatan luka</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="form-radio">
                                <label class="checkbox-styled checkbox-success no-margin">
                                    <input name="kebuthan_pembelajaran4" id="kebuthan_pembelajaran4"
                                        value="Rehabilitasi" type="checkbox"> <span>Rehabilitasi</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="form-radio">
                                <label class="checkbox-styled checkbox-success no-margin">
                                    <input name="kebuthan_pembelajaran5" id="kebuthan_pembelajaran5"
                                        value="Manajemen nyeri" type="checkbox"> <span>Manajemen nyeri</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="form-radio">
                                <label class="checkbox-styled checkbox-success no-margin">
                                    <input name="kebuthan_pembelajaran6" id="kebuthan_pembelajaran6"
                                        value="Diet &amp; nutrisi" type="checkbox"> <span>Diet &amp; nutrisi</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="form-radio">
                                <label class="checkbox-styled checkbox-success no-margin">
                                    <input name="kebuthan_pembelajaran7" id="kebuthan_pembelajaran7"
                                        value="Tidak ada Hamabatan" type="checkbox"> <span>Tidak ada Hamabatan</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="pembelajaran_lainnya" class="control-label text-primary">Kebutuhan
                                pembelajaran
                                lainnya</label>
                            <input name="pembelajaran_lainnya" id="pembelajaran_lainnya" class="form-control"
                                type="text">
                        </div>
                    </div>
                </div>

                <header class="orange-text">
                    <h4 class="mt-5">Assesment Fungsional (Pengkajian Fungsi)</h4>
                </header>
                <header class="orange-text">
                    <h4 class="mt-5">Sensorik</h4>
                </header>
                <div class="row mt-3">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>Penglihatan</td>
                                <td>
                                    <div class="radio radio-styled">
                                        <label>
                                            <input name="sensorik_penglihatan" value="Normal" data-skor="0"
                                                class="apgar" type="radio">
                                            <span>Normal</span>
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <div class="radio radio-styled">
                                        <label>
                                            <input name="sensorik_penglihatan" value="Kabur" data-skor="1"
                                                class="apgar" type="radio">
                                            <span>Kabur</span>
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <div class="radio radio-styled">
                                        <label>
                                            <input name="sensorik_penglihatan" value="Kaca Mata" data-skor="2"
                                                class="apgar" type="radio">
                                            <span>Kaca Mata</span>
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <div class="radio radio-styled">
                                        <label>
                                            <input name="sensorik_penglihatan" value="Lensa Kontak" data-skor="3"
                                                class="apgar" type="radio">
                                            <span>Lensa Kontak</span>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Penciuman</td>
                                <td>
                                    <div class="radio radio-styled">
                                        <label>
                                            <input name="sensorik_penciuman" value="Normal" data-skor="0"
                                                class="apgar" type="radio">
                                            <span>Normal</span>
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <div class="radio radio-styled">
                                        <label>
                                            <input name="sensorik_penciuman" value="Tidak" data-skor="1"
                                                class="apgar" type="radio">
                                            <span>Tidak</span>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Pendengaran</td>
                                <td>
                                    <div class="radio radio-styled">
                                        <label>
                                            <input name="sensorik_pendengaran" value="Normal" data-skor="0"
                                                class="apgar" type="radio">
                                            <span>Normal</span>
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <div class="radio radio-styled">
                                        <label>
                                            <input name="sensorik_pendengaran" value="Tuli Ka / Ki" data-skor="1"
                                                class="apgar" type="radio">
                                            <span>Tuli Ka / Ki</span>
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <div class="radio radio-styled">
                                        <label>
                                            <input name="sensorik_pendengaran" value="Ada alat bantu dengar ka/ki"
                                                data-skor="2" class="apgar" type="radio">
                                            <span>Ada alat bantu dengar ka/ki</span>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <header class="orange-text">
                    <h4 class="mt-5">Kognitif</h4>
                </header>
                <div class="row mt-3">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>&nbsp;</td>
                                <td>
                                    <div class="radio radio-styled">
                                        <label>
                                            <input name="kognitif" value="Normal" data-skor="0" class="apgar"
                                                type="radio">
                                            <span>Normal</span>
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <div class="radio radio-styled">
                                        <label>
                                            <input name="kognitif" value="Bingung" data-skor="1" class="apgar"
                                                type="radio">
                                            <span>Bingung</span>
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <div class="radio radio-styled">
                                        <label>
                                            <input name="kognitif" value="Pelupa" data-skor="2" class="apgar"
                                                type="radio">
                                            <span>Pelupa</span>
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <div class="radio radio-styled">
                                        <label>
                                            <input name="kognitif" value="Tidak Dapat dimengerti" data-skor="3"
                                                class="apgar" type="radio">
                                            <span>Tidak Dapat dimengerti</span>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <header class="orange-text">
                    <h4 class="mt-5">Motorik</h4>
                </header>
                <div class="row mt-3">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>Aktifitas Sehari - hari</td>
                                <td>
                                    <div class="radio radio-styled">
                                        <label>
                                            <input name="motorik_aktifitas" value="Mandiri" data-skor="0"
                                                class="apgar" type="radio">
                                            <span>Mandiri</span>
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <div class="radio radio-styled">
                                        <label>
                                            <input name="motorik_aktifitas" value="Bantuan Minimal" data-skor="1"
                                                class="apgar" type="radio">
                                            <span>Bantuan Minimal</span>
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <div class="radio radio-styled">
                                        <label>
                                            <input name="motorik_aktifitas" value="Bantuan Ketergantungan Total"
                                                data-skor="2" class="apgar" type="radio">
                                            <span>Bantuan Ketergantungan Total</span>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Berjalan</td>
                                <td>
                                    <div class="radio radio-styled">
                                        <label>
                                            <input name="motorik_berjalan" value="Tidak Ada kesulitan"
                                                data-skor="0" class="apgar" type="radio">
                                            <span>Tidak Ada kesulitan</span>
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <div class="radio radio-styled">
                                        <label>
                                            <input name="motorik_berjalan" value="Perlu Bantuan" data-skor="1"
                                                class="apgar" type="radio">
                                            <span>Perlu Bantuan</span>
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <div class="radio radio-styled">
                                        <label>
                                            <input name="motorik_berjalan" value="Sering Jatuh" data-skor="0"
                                                class="apgar" type="radio">
                                            <span>Sering Jatuh</span>
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <div class="radio radio-styled">
                                        <label>
                                            <input name="motorik_berjalan" value="Kelumpuhan" data-skor="1"
                                                class="apgar" type="radio">
                                            <span>Kelumpuhan</span>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="row mt-3">
                    <div class="form-group">
                        <div class="input-group">
                            <div
                                style="min-height: 150px; display: flex; justify-content: flex-end; flex-direction: column; align-items: center;">
                                <div>Perawat,</div>
                                <div>
                                    <input type="hidden" name="data_ttd" id="data_ttd" value="{ttd_perawat}"
                                        data-imgview="img_ttd">
                                    <img src="http://103.191.196.126:8888/testing/include/images/ttd_blank.png"
                                        id="img_ttd" style="width: 200px; height:100px;"
                                        onerror="this.onerror=null; this.src='http://103.191.196.126:8888/testing/include/images/ttd_blank.png'">
                                </div>
                                <div style="width: 70%;">
                                    <input type="text" name="nama_dokter" class="form-control text-center">
                                </div>
                                <div>
                                    <span class="badge blue pointer" id="btn-ttd">TTD Pen Tablet</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 px-3">
                    <div class="card-actionbar">
                        <div class="card-actionbar-row d-flex justify-content-between align-items-center">
                            <button type="button"
                                class="btn btn-primary waves-effect waves-light save-form d-flex align-items-center"
                                data-dismiss="modal" data-status="0">
                                <span class="mdi mdi-printer mr-2"></span> Print
                            </button>
                            <div style="width: 33%" class="d-flex justify-content-between">
                                <button type="button"
                                    class="btn btn-warning waves-effect text-white waves-light save-form d-flex align-items-center"
                                    data-dismiss="modal" data-status="0" id="sd-pengkajian-nurse-rajal">
                                    <span class="mdi mdi-content-save mr-2"></span> Simpan (draft)
                                </button>
                                <button type="button"
                                    class="btn btn-primary waves-effect waves-light save-form d-flex align-items-center"
                                    data-dismiss="modal" data-status="1" id="sf-pengkajian-nurse-rajal">
                                    <span class="mdi mdi-content-save mr-2"></span> Simpan (final)
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@include('pages.kpi.penilaian.partials.ttd')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Ketika elemen dengan kelas .menu-layanan diklik
        $('.menu-layanan').on('click', function() {
            // Sembunyikan menu layanan dengan efek fade out
            $('#menu-layanan').fadeOut(500); // 500ms untuk transisi

            // Ambil data-layanan untuk menentukan ID elemen yang akan ditampilkan
            var namaLayanan = $(this).data('layanan');
            var pengkajianId = $('#pengkajian-rajal-id').val();

            // Tampilkan elemen layanan yang dipilih dengan efek fade in
            $('#' + namaLayanan).delay(500).fadeIn(500); // 500ms untuk transisi
            if (namaLayanan == 'pengkajian-nurse-rajal') {
                if (pengkajianId !== undefined) {
                    // $('#pengkajian-nurse-rajal').show();
                    $.ajax({
                        type: "GET", // Method pengiriman data bisa dengan GET atau POST
                        url: `/api/simrs/pengkajian/nurse-rajal/${pengkajianId}/get`, // Isi dengan url/path file php yang dituju
                        dataType: "json",
                        success: function(data) {
                            $('pengkajian-nurse-rajal-form #tgl_masuk').val(data.tgl_masuk);
                            $('pengkajian-nurse-rajal-form #jam_masuk').val(data.jam_masuk);
                            $('pengkajian-nurse-rajal-form #tgl_dilayani').val(data
                                .tgl_dilayani);
                            $('pengkajian-nurse-rajal-form #jam_dilayani').val(data
                                .jam_dilayani);
                            $('pengkajian-nurse-rajal-form #keluhan_utama').val(data
                                .keluhan_utama);
                            $('pengkajian-nurse-rajal-form #pr').val(data.pr);
                            $('pengkajian-nurse-rajal-form #rr').val(data.rr);
                            $('pengkajian-nurse-rajal-form #bp').val(data.bp);
                            $('pengkajian-nurse-rajal-form #temperatur').val(data
                                .temperatur);
                            $('pengkajian-nurse-rajal-form #body_height').val(data
                                .body_height);
                            $('pengkajian-nurse-rajal-form #body_weight').val(data
                                .body_weight);
                            $('pengkajian-nurse-rajal-form #bmi').val(data.bmi);
                            $('pengkajian-nurse-rajal-form #kat_bmi').val(data.kat_bmi);
                            $('pengkajian-nurse-rajal-form #sp02').val(data.sp02);
                            $('pengkajian-nurse-rajal-form #lingkar_kepala').val(data
                                .lingkar_kepala);
                            $('pengkajian-nurse-rajal-form #lingkar_kepala').val(data
                                .lingkar_kepala);
                            // Set the value for Select2 elements
                            $('pengkajian-nurse-rajal-form #diagnosa_keperawatan').val(data
                                .diagnosa_keperawatan).trigger('change');
                            $('pengkajian-nurse-rajal-form #rencana_tindak_lanjut').val(data
                                .rencana_tindak_lanjut).trigger('change');
                            // Assuming 'data' is the object retrieved from the database
                            if (data.alergi_obat === "Ya") {
                                $('pengkajian-nurse-rajal-form #ket_alergi_obat').val(data
                                    .ket_alergi_obat);
                                $('pengkajian-nurse-rajal-form #alergi_obat1').prop(
                                    'checked', true);
                            } else if (data.alergi_obat === "Tidak") {
                                $('pengkajian-nurse-rajal-form #alergi_obat2').prop(
                                    'checked', true);
                            }
                            if (data.alergi_makanan === "Ya") {
                                $('pengkajian-nurse-rajal-form #ket_alergi_makanan').val(
                                    data
                                    .ket_alergi_makanan);
                                $('pengkajian-nurse-rajal-form #alergi_makanan1').prop(
                                    'checked', true);
                            } else if (data.alergi_makanan === "Tidak") {
                                $('pengkajian-nurse-rajal-form #alergi_makanan2').prop(
                                    'checked', true);
                            }
                            if (data.alergi_lainnya === "Ya") {
                                $('pengkajian-nurse-rajal-form #ket_alergi_lainnya').val(
                                    data
                                    .ket_alergi_lainnya);
                                $('pengkajian-nurse-rajal-form #alergi_lainnya1').prop(
                                    'checked', true);
                            } else if (data.alergi_lainnya === "Tidak") {
                                $('pengkajian-nurse-rajal-form #alergi_lainnya2').prop(
                                    'checked', true);
                            }
                        },
                        error: function(xhr) {
                            console.log(xhr.responseText);
                        }
                    });
                }
            }
        });

        // Fungsi untuk tombol kembali
        $('.btn-kembali').on('click', function() {
            // Sembunyikan div layanan yang aktif dan tampilkan kembali menu layanan
            $('#pengkajian-nurse-rajal').fadeOut(500, function() {
                $('#menu-layanan').fadeIn(500);
            });
        });

        // ===================================================
        // :                    STORE                        :
        // ===================================================
        let actionType = '';

        // Saat tombol Save Draft diklik
        $('#sd-pengkajian-nurse-rajal').on('click', function() {
            actionType = 'draft';
            submitForm(actionType); // Panggil fungsi submitForm dengan parameter draft
        });

        // Saat tombol Save Final diklik
        $('#sf-pengkajian-nurse-rajal').on('click', function() {
            actionType = 'final';
            submitForm(actionType); // Panggil fungsi submitForm dengan parameter final
        });

        function submitForm(actionType) {
            const form = $('#pengkajian-nurse-rajal-form'); // Ambil form
            const url =
                "{{ route('pengkajian.nurse-rajal.store') }}" // Ambil URL dari action form
            let formData = form.serialize(); // Ambil data dari form

            // Tambahkan tipe aksi (draft atau final) ke data form
            formData += '&action_type=' + actionType;

            $.ajax({
                type: 'POST',
                url: url,
                data: formData,
                success: function(response) {
                    if (actionType === 'draft') {
                        showSuccessAlert('Data berhasil disimpan sebagai draft!');
                    } else {
                        showSuccessAlert('Data berhasil disimpan sebagai final!');
                    }
                    setTimeout(() => {
                        console.log('Reloading the page now.');
                        window.location.reload();
                    }, 1000);
                },
                error: function(response) {
                    // Tangani error
                    var errors = response.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        showErrorAlert(value[0]);
                    });
                }
            });
        }
    })
</script>
