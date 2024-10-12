{{-- @include('pages.simrs.pendaftaran.partials.menu')
@include('pages.simrs.pendaftaran.partials.header-pasien') --}}
<div id="transfer-pasien-antar-ruangan" class="tab-pane fade" role="tabpanel">
    <form autocomplete="off" novalidate method="post" id="nurse-rajal">
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
                    <h2>TRANSFER PASIEN ANTAR RUANGAN</h4>
                </header>
                <header class="text-success">
                    <h4 class="mt-5 font-weight-bold text-center">MASUK RUMAH SAKIT</h4>
                </header>
                <div class="row mt-3 justify-content-center">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="tgl" class="text-primary d-block text-center">Tanggal &amp; jam
                                masuk</label>
                            <div class="form-group mb-3">
                                <div class="input-group">
                                    <input type="text" name="tgl" class="form-control " placeholder="Tanggal"
                                        id="tgl" value="{{ $registration->created_at->format('d-m-Y') }}">
                                    <input type="time" name="jam" class="form-control " placeholder="Jam"
                                        id="jam" value="{{ $registration->created_at->format('h:i') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6"></div>
                    <div class="col-md-6">
                        <header class="text-warning">
                            <h4 class="mt-5 font-weight-bold text-center">ASAL PASIEN</h4>
                        </header>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tgl_masuk" class="text-primary d-block">Tanggal &amp; jam
                                Transfer Pasien :</label>
                            <div class="form-group mb-3">
                                <div class="input-group">
                                    <input type="text" name="tgl_masuk_pasien" class="form-control"
                                        placeholder="Tanggal" id="tgl_masuk_pasien"
                                        value="{{ now()->format('d-m-Y') }}">
                                    <input type="time" name="jam_masuk_pasien" class="form-control" placeholder="Jam"
                                        id="jam_masuk_pasien" value="{{ now()->format('h:i') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ruangan_asal" class="control-label text-primary ">Ruangan:
                                    </label>
                                    <input name="ruangan_asal" id="ruangan_asal" class="form-control alergi"
                                        type="text">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kelas_asal" class="control-label text-primary ">Kelas: </label>
                                    <input name="kelas_asal" id="kelas_asal" class="form-control alergi" type="text">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="asesmen" class="control-label text-primary">DX Medis</label>
                            <textarea class="form-control" id="asesmen" name="asesmen" rows="1" data-label="Keluhan utama"></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ruangan_pindah" class="control-label text-primary ">Pindah
                                        Ruangan:
                                    </label>
                                    <input name="ruangan_pindah" id="ruangan_pindah" class="form-control alergi"
                                        type="text">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kelas_pindah" class="control-label text-primary ">Kelas:
                                    </label>
                                    <input name="kelas_pindah" id="kelas_pindah" class="form-control alergi"
                                        type="text">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="masalah_keperawatan" class="control-label text-primary">Masalah
                                Keperawatan</label>
                            <textarea class="form-control" id="masalah_keperawatan" name="masalah_keperawatan" rows="1"
                                data-label="Keluhan utama"></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="rr" class="text-primary">Tiba di Ruangan:</label>
                            <div class="input-group">
                                <input type="time" name="tiba_diruangan" class="form-control" placeholder="Jam"
                                    id="tiba_diruangan" value="{{ now()->format('h:i') }}">
                                <div class="input-group-append">
                                    <span class="input-group-text">wib</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <header class="text-warning margin-top-lg mt-3">
                    <h4 class=" mt-5 font-weight-bold text-center">DOKTER YANG MERAWAT</h4>
                </header>
                <div class="row mt-3">
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label class="text-primary" for="dokter">Dokter 1</label>
                            <div class="input-group">
                                <div class="input-group">
                                    <input id="dokter" type="text" name="dokter" class="form-control"
                                        value="{{ $registration->doctor->employee->fullname }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="fas fa-user-md"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label class="text-primary" for="dokter2">Dokter 2</label>
                            <div class="input-group">
                                <div class="input-group">
                                    <input id="dokter2" type="text" name="dokter2" class="form-control"">
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="fas fa-user-md"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label class="text-primary" for="dokter3">Dokter 3</label>
                            <div class="input-group">
                                <div class="input-group">
                                    <input id="dokter3" type="text" name="dokter3" class="form-control"">
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="fas fa-user-md"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6"></div>
                    <div class="col-md-6">
                        <header class="text-danger">
                            <h4 class="mt-5 font-weight-bold text-center">ALASAN PEMINDAHAN PASIEN</h4>
                        </header>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="keluhan_utama" class="control-label text-primary">Keluhan utama *</label>
                            <textarea class="form-control" id="keluhan_utama" name="keluhan_utama" rows="6" data-label="Keluhan utama"></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="kondisi_pasien1" class="control-label text-primary mt-3">Kondisi Pasien:</label>
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="form-radio">
                                        <label class="custom-control custom-radio custom-control-inline">
                                            <input name="kondisi_pasien" id="kondisi_pasien1" value="Stabil"
                                                type="radio" class="custom-control-input">
                                            <span class="custom-control-label text-primary">Stabil</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="form-radio">
                                        <label class="custom-control custom-radio custom-control-inline">
                                            <input name="kondisi_pasien" id="kondisi_pasien2" value="Memburuk"
                                                type="radio" class="custom-control-input">
                                            <span class="custom-control-label text-primary">Memburuk</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-radio">
                                        <label class="custom-control custom-radio custom-control-inline">
                                            <input name="kondisi_pasien" id="kondisi_pasien3"
                                                value="Tidak ada perubahan" type="radio"
                                                class="custom-control-input">
                                            <span class="custom-control-label text-primary">Tidak ada perubahan</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <label for="tindakan1" class="control-label text-primary mt-3">Tindakan:</label>
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="form-radio">
                                        <label class="custom-control custom-radio custom-control-inline">
                                            <input name="tindakan" id="tindakan1" value="OK" type="radio"
                                                class="custom-control-input">
                                            <span class="custom-control-label text-primary">OK</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="form-radio">
                                        <label class="custom-control custom-radio custom-control-inline">
                                            <input name="tindakan" id="tindakan2" value="VK" type="radio"
                                                class="custom-control-input">
                                            <span class="custom-control-label text-primary">VK</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-radio">
                                        <label class="custom-control custom-radio custom-control-inline">
                                            <input name="tindakan" id="tindakan3" value="Lainnya" type="radio"
                                                class="custom-control-input">
                                            <span class="custom-control-label text-primary">Lainnya</span>
                                            <input name="ket_lainnya" id="ket_lainnya"
                                                style="margin-right: 20px; width: 100px;border-left: none;border-right: none;border-top: none;border-bottom-color: rgba(12, 12, 12, 0.12);"
                                                type="text">
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <div class="form-checkbox">
                                        <label class="custom-control custom-radio custom-control-inline">
                                            <input name="app_lainnya" id="app_lainnya3" value="Lainnya"
                                                type="checkbox" class="custom-control-input">
                                            <span class="custom-control-label text-primary">Lainnya</span>
                                            <input name="app_lainnya_text" id="app_lainnya_text"
                                                style="margin-right: 20px; width: 367px;border-left: none;border-right: none;border-top: none;border-bottom-color: rgba(12, 12, 12, 0.12);"
                                                type="text">
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <header class="text-danger">
                    <h4 class="mt-5 font-weight-bold text-center">KEADAAN PASIEN SAAT PINDAH</h4>
                </header>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <label for="kondisi_khusus1" class="control-label text-primary mt-3">Keadaan Umum:</label>
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="form-radio">
                                        <label class="custom-control custom-radio custom-control-inline">
                                            <input name="kondisi_khusus" id="kondisi_khusus1" value="Baik"
                                                type="radio" class="custom-control-input">
                                            <span class="custom-control-label text-primary">Baik</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="form-radio">
                                        <label class="custom-control custom-radio custom-control-inline">
                                            <input name="kondisi_khusus" id="kondisi_khusus2" value="Sedang"
                                                type="radio" class="custom-control-input">
                                            <span class="custom-control-label text-primary">Sedang</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="form-radio">
                                        <label class="custom-control custom-radio custom-control-inline">
                                            <input name="kondisi_khusus" id="kondisi_khusus3" value="Berat"
                                                type="radio" class="custom-control-input">
                                            <span class="custom-control-label text-primary">Berat</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="form-radio">
                                        <label class="custom-control custom-radio custom-control-inline">
                                            <input name="keadaan_umum_gcs" id="keadaan_umum_gcs" value="GCS"
                                                type="radio" class="custom-control-input">
                                            <span class="custom-control-label text-primary">GCS:</span>
                                            <input name="ket_gcs" id="ket_gcs"
                                                style="margin-right: 20px; width: 50px;border-left: none;border-right: none;border-top: none;border-bottom-color: rgba(12, 12, 12, 0.12);"
                                                type="text">
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="text-primary" for="td">TD:</label>
                                    <div class="input-group">
                                        <div class="input-group">
                                            <input id="td" type="text" name="td"
                                                class="form-control">
                                            <div class="input-group-append">
                                                <span class="input-group-text">x/menit</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nd" class="text-primary">ND: </label>
                                    <div class="input-group">
                                        <input class="form-control numeric" id="nd" name="nd"
                                            type="text">
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
                                    <label for="rr" class="text-primary">RR: </label>
                                    <div class="input-group">
                                        <input class="form-control numeric" id="rr" name="rr"
                                            type="text">
                                        <div class="input-group-append">
                                            <span class="input-group-text">x/menit</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sb" class="text-primary">SB: </label>
                                    <div class="input-group">
                                        <input class="form-control numeric" id="sb" name="sb"
                                            type="text">
                                        <div class="input-group-append">
                                            <span class="input-group-text">C°</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bb" class="text-primary">BB: </label>
                                    <div class="input-group">
                                        <input class="form-control numeric calc-bmi" id="bb" name="bb"
                                            type="text">
                                        <div class="input-group-append">
                                            <span class="input-group-text">Kg</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="tb" class="text-primary">TB: </label>
                                    <div class="input-group">
                                        <input class="form-control numeric calc-bmi" id="tb" name="tb"
                                            type="text">
                                        <div class="input-group-append">
                                            <span class="input-group-text">Cm</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6" style="margin-top: 3rem;">
                        <div class="form-group">
                            <label for="spo2" class="control-label text-primary">SPO2: </label>
                            <input name="spo2" id="spo2" class="form-control alergi" type="text">
                        </div>
                        <div class="form-group">
                            <label for="status_nyeri" class="control-label text-primary">Status Nyeri: </label>
                            <input name="status_nyeri" id="status_nyeri" class="form-control alergi" type="text">
                        </div>
                        <div class="form-group">
                            <label for="kesadaran" class="control-label text-primary">Kesadaran: </label>
                            <input name="kesadaran" id="kesadaran" class="form-control alergi" type="text">
                        </div>
                        <label for="kondisi_khusus1" class="control-label text-primary mt-3">Metode pemindahan
                            pasien:</label>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="form-checkbox">
                                        <label class="custom-control custom-checkbox custom-control-inline">
                                            <input name="kondisi_khusus" id="kondisi_khusus1" value="Kursi Roda"
                                                type="checkbox" class="custom-control-input">
                                            <span class="custom-control-label text-primary">Kursi Roda</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="form-checkbox">
                                        <label class="custom-control custom-checkbox custom-control-inline">
                                            <input name="kondisi_khusus" id="kondisi_khusus2" value="Tempat Tidur"
                                                type="checkbox" class="custom-control-input">
                                            <span class="custom-control-label text-primary">Tempat Tidur</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="form-checkbox">
                                        <label class="custom-control custom-checkbox custom-control-inline">
                                            <input name="kondisi_khusus" id="kondisi_khusus3" value="Brangkar"
                                                type="checkbox" class="custom-control-input">
                                            <span class="custom-control-label text-primary">Brangkar</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="form-checkbox">
                                        <label class="custom-control custom-checkbox custom-control-inline">
                                            <input name="kondisi_khusus" id="kondisi_khusus1" value="Bok bayi"
                                                type="checkbox" class="custom-control-input">
                                            <span class="custom-control-label text-primary">Bok bayi</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="form-checkbox">
                                        <label class="custom-control custom-checkbox custom-control-inline">
                                            <input name="kondisi_khusus" id="kondisi_khusus2" value="Jalan/Gendong"
                                                type="checkbox" class="custom-control-input">
                                            <span class="custom-control-label text-primary">Jalan/Gendong</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <label for="kondisi_khusus1" class="control-label text-primary mt-3">Risiko Jatuh:</label>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="form-checkbox">
                                        <label class="custom-control custom-checkbox custom-control-inline">
                                            <input name="kondisi_khusus" id="kondisi_khusus1" value="Tidak Beresiko"
                                                type="checkbox" class="custom-control-input">
                                            <span class="custom-control-label text-primary">Tidak Beresiko</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="form-checkbox">
                                        <label class="custom-control custom-checkbox custom-control-inline">
                                            <input name="kondisi_khusus" id="kondisi_khusus2" value="Rendah"
                                                type="checkbox" class="custom-control-input">
                                            <span class="custom-control-label text-primary">Rendah</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="form-checkbox">
                                        <label class="custom-control custom-checkbox custom-control-inline">
                                            <input name="kondisi_khusus" id="kondisi_khusus3" value="Tinggi"
                                                type="checkbox" class="custom-control-input">
                                            <span class="custom-control-label text-primary">Tinggi</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <label for="kondisi_khusus1" class="control-label text-primary mt-3">Kewaspadaan
                            transmisi/infeksi:</label>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="form-checkbox">
                                        <label class="custom-control custom-checkbox custom-control-inline">
                                            <input name="kondisi_khusus" id="kondisi_khusus1" value="Kontak"
                                                type="checkbox" class="custom-control-input">
                                            <span class="custom-control-label text-primary">Kontak</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="form-checkbox">
                                        <label class="custom-control custom-checkbox custom-control-inline">
                                            <input name="kondisi_khusus" id="kondisi_khusus2" value="Percikan"
                                                type="checkbox" class="custom-control-input">
                                            <span class="custom-control-label text-primary">Percikan</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="form-checkbox">
                                        <label class="custom-control custom-checkbox custom-control-inline">
                                            <input name="kondisi_khusus" id="kondisi_khusus3" value="Udara"
                                                type="checkbox" class="custom-control-input">
                                            <span class="custom-control-label text-primary">Udara</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <label for="kondisi_khusus1" class="control-label text-primary mt-3">Memerlukan perawatan
                            isolasi:
                        </label>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="form-checkbox">
                                        <label class="custom-control custom-checkbox custom-control-inline">
                                            <input name="kondisi_khusus" id="kondisi_khusus1" value="Ya"
                                                type="checkbox" class="custom-control-input">
                                            <span class="custom-control-label text-primary">Ya</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="form-checkbox">
                                        <label class="custom-control custom-checkbox custom-control-inline">
                                            <input name="kondisi_khusus" id="kondisi_khusus2" value="Tidak"
                                                type="checkbox" class="custom-control-input">
                                            <span class="custom-control-label text-primary">Tidak</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <label for="kondisi_khusus1" class="control-label text-primary mt-3">Peralatan yang menyertai
                            saat pemindahan:
                        </label>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="form-checkbox">
                                        <label class="custom-control custom-checkbox custom-control-inline">
                                            <input name="kondisi_khusus" id="kondisi_khusus1" value="Oksigen"
                                                type="checkbox" class="custom-control-input">
                                            <span class="custom-control-label text-primary">Oksigen</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <input class="form-control numeric calc-bmi" id="body_weight" name="body_weight"
                                        type="text">
                                    <div class="input-group-append">
                                        <span class="input-group-text">ltr/mnt</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mt-3">
                                <div class="form-group">
                                    <div class="form-checkbox">
                                        <label class="custom-control custom-checkbox custom-control-inline">
                                            <input name="kondisi_khusus" id="kondisi_khusus1" value="Cateter urine"
                                                type="checkbox" class="custom-control-input">
                                            <span class="custom-control-label text-primary">Cateter urine</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mt-3">
                                <div class="form-group">
                                    <div class="form-checkbox">
                                        <label class="custom-control custom-checkbox custom-control-inline">
                                            <input name="kondisi_khusus" id="kondisi_khusus1" value="NGT"
                                                type="checkbox" class="custom-control-input">
                                            <span class="custom-control-label text-primary">NGT</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-3">
                                <div class="form-group">
                                    <label for="keluhan_utama" class="control-label text-primary">Intruksi dokter
                                        Umum</label>
                                    <textarea class="form-control" id="keluhan_utama" name="keluhan_utama" rows="1"
                                        data-label="Intruksi dokter Umum"></textarea>
                                </div>
                            </div>
                            <div class="col-md-12 mt-3">
                                <div class="form-group">
                                    <label for="keluhan_utama" class="control-label text-primary">Advice DPJP</label>
                                    <textarea class="form-control" id="keluhan_utama" name="keluhan_utama" rows="4" data-label="Advice DPJP"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="kondisi_khusus1" class="control-label text-primary mt-3">Pasien atau keluarga
                            mengetahui alasan pemindahan:
                        </label>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="form-checkbox">
                                        <label class="custom-control custom-checkbox custom-control-inline">
                                            <input name="kondisi_khusus" id="kondisi_khusus1" value="Ya"
                                                type="checkbox" class="custom-control-input">
                                            <span class="custom-control-label text-primary">Ya</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="form-checkbox">
                                        <label class="custom-control custom-checkbox custom-control-inline">
                                            <input name="kondisi_khusus" id="kondisi_khusus2" value="Tidak"
                                                type="checkbox" class="custom-control-input">
                                            <span class="custom-control-label text-primary">Tidak</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="kesadaran" class="control-label text-primary">Bila ya: Nama </label>
                                    <input name="kesadaran" id="kesadaran" class="form-control alergi"
                                        type="text">
                                </div>
                            </div>
                            <div class="col-md-12 mt-3">
                                <div class="form-group">
                                    <label for="kesadaran" class="control-label text-primary">Hubungan Keluarga
                                    </label>
                                    <input name="kesadaran" id="kesadaran" class="form-control alergi"
                                        type="text">
                                </div>
                            </div>
                            <div class="col-md-12 mt-3">
                                <label for="kondisi_khusus1" class="control-label text-primary mt-3">Status fungsional
                                    pasien:</label>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="form-checkbox">
                                                <label class="custom-control custom-checkbox custom-control-inline">
                                                    <input name="kondisi_khusus" id="kondisi_khusus1" value="Mandiri"
                                                        type="checkbox" class="custom-control-input">
                                                    <span class="custom-control-label text-primary">Mandiri</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="form-checkbox">
                                                <label class="custom-control custom-checkbox custom-control-inline">
                                                    <input name="kondisi_khusus" id="kondisi_khusus2"
                                                        value="Partial care" type="checkbox"
                                                        class="custom-control-input">
                                                    <span class="custom-control-label text-primary">Partial care</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="form-checkbox">
                                                <label class="custom-control custom-checkbox custom-control-inline">
                                                    <input name="kondisi_khusus" id="kondisi_khusus3"
                                                        value="Total care" type="checkbox"
                                                        class="custom-control-input">
                                                    <span class="custom-control-label text-primary">Total care</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-3">
                                        <div class="form-group mt-5">
                                            <label for="keluhan_utama" class="control-label text-primary">Hasil
                                                Pemeriksaan Tindakan & penunjang/diagnostik yang sudah dilakukan
                                                (lab,ekg dll):</label>
                                            <textarea class="form-control" id="keluhan_utama" name="keluhan_utama" rows="1"
                                                data-label="Hasil Pemeriksaan Tindakan & penunjang/diagnostik yang sudah dilakukan (lab,ekg dll)"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-3">
                                        <div class="form-group">
                                            <label for="keluhan_utama" class="control-label text-primary">
                                                Diet (bila pindah ruangan):</label>
                                            <textarea class="form-control" id="keluhan_utama" name="keluhan_utama" rows="4"
                                                data-label="Diet (bila pindah ruangan)">
Jenis Diet : 
Puasa : 
Terakhir minum : 
Terakhir makan : 
                            
                                            </textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <header class="text-warning">
                    <h4 class="mt-5 font-weight-bold text-center">PEMBERIAN THERAPI SEBELUM PINDAH</h4>
                </header>
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-checkbox">
                                        <label class="custom-control custom-checkbox custom-control-inline">
                                            <input name="kondisi_khusus" id="kondisi_khusus1" value="Infus"
                                                type="checkbox" class="custom-control-input">
                                            <span class="custom-control-label text-primary">Infus</span>
                                        </label>
                                    </div>
                                </div>
                                <input class="form-control numeric calc-bmi" id="body_weight" name="body_weight"
                                    type="text">
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kesadaran" class="control-label text-primary">Kesadaran: </label>
                                    <input name="kesadaran" id="kesadaran" class="form-control alergi mt-4"
                                        type="text">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <header class="text-warning">
                    <h4 class="mt-5 font-weight-bold text-center">TERAPI DAN TINDAKAN YANG DILAKUKAN</h4>
                </header>
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="form-group mb-3">
                                <div class="input-group">
                                    <input type="text" name="tgl_masuk" class="form-control "
                                        placeholder="TERAPI DAN TINDAKAN 1" id="tgl_masuk">
                                    <input type="time" name="jam_masuk" class="form-control"
                                        style="width: 100px !important" placeholder="Jam" id="jam_masuk">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-group mb-3">
                                <div class="input-group">
                                    <input type="text" name="tgl_masuk" class="form-control "
                                        placeholder="TERAPI DAN TINDAKAN 2" id="tgl_masuk">
                                    <input type="time" name="jam_masuk" class="form-control"
                                        style="width: 100px !important" placeholder="Jam" id="jam_masuk">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-group mb-3">
                                <div class="input-group">
                                    <input type="text" name="tgl_masuk" class="form-control "
                                        placeholder="TERAPI DAN TINDAKAN 3" id="tgl_masuk">
                                    <input type="time" name="jam_masuk" class="form-control"
                                        style="width: 100px !important" placeholder="Jam" id="jam_masuk">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-group mb-3">
                                <div class="input-group">
                                    <input type="text" name="tgl_masuk" class="form-control "
                                        placeholder="TERAPI DAN TINDAKAN 4" id="tgl_masuk">
                                    <input type="time" name="jam_masuk" class="form-control"
                                        style="width: 100px !important" placeholder="Jam" id="jam_masuk">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-group mb-3">
                                <div class="input-group">
                                    <input type="text" name="tgl_masuk" class="form-control "
                                        placeholder="TERAPI DAN TINDAKAN 5" id="tgl_masuk">
                                    <input type="time" name="jam_masuk" class="form-control"
                                        style="width: 100px !important" placeholder="Jam" id="jam_masuk">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="form-group mb-3">
                                <div class="input-group">
                                    <input type="text" name="tgl_masuk" class="form-control "
                                        placeholder="TERAPI DAN TINDAKAN 1" id="tgl_masuk">
                                    <input type="time" name="jam_masuk" class="form-control"
                                        style="width: 100px !important" placeholder="Jam" id="jam_masuk">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-group mb-3">
                                <div class="input-group">
                                    <input type="text" name="tgl_masuk" class="form-control "
                                        placeholder="TERAPI DAN TINDAKAN 2" id="tgl_masuk">
                                    <input type="time" name="jam_masuk" class="form-control"
                                        style="width: 100px !important" placeholder="Jam" id="jam_masuk">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-group mb-3">
                                <div class="input-group">
                                    <input type="text" name="tgl_masuk" class="form-control "
                                        placeholder="TERAPI DAN TINDAKAN 3" id="tgl_masuk">
                                    <input type="time" name="jam_masuk" class="form-control"
                                        style="width: 100px !important" placeholder="Jam" id="jam_masuk">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-group mb-3">
                                <div class="input-group">
                                    <input type="text" name="tgl_masuk" class="form-control "
                                        placeholder="TERAPI DAN TINDAKAN 4" id="tgl_masuk">
                                    <input type="time" name="jam_masuk" class="form-control"
                                        style="width: 100px !important" placeholder="Jam" id="jam_masuk">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-group mb-3">
                                <div class="input-group">
                                    <input type="text" name="tgl_masuk" class="form-control "
                                        placeholder="TERAPI DAN TINDAKAN 5" id="tgl_masuk">
                                    <input type="time" name="jam_masuk" class="form-control"
                                        style="width: 100px !important" placeholder="Jam" id="jam_masuk">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-4 text-center">
                        <span>Perawat yang mengirim,</span>
                        <div id="tombol-1" class="mt-3">
                            <a class="btn btn-primary btn-sm text-white ttd" onclick="openSignaturePad(1)"
                                id="ttd_pegawai">Tanda tangan</a>
                        </div>
                        <div class="mt-3">
                            <img id="signature-display-1" src="" alt="Signature Image"
                                style="display:none; max-width:60%;">
                        </div>
                        <div class="mt-3">
                            <span>{{ auth()->user()->employee->fullname }}</span>
                        </div>

                    </div>
                    <div class="col-md-4 text-center">
                    </div>
                    <div class="col-md-4 text-center">
                        <span>Perawat yang menerima,</span>
                        <div id="tombol-2" class="mt-3">
                            <a class="btn btn-primary btn-sm text-white ttd" onclick="openSignaturePad(2)"
                                id="ttd_pegawai">Tanda tangan</a>
                        </div>
                        <div class="mt-3">
                            <img id="signature-display-2" src="" alt="Signature Image"
                                style="display:none; max-width:60%;">
                        </div>
                        <div class="mt-3">
                            <span>{{ auth()->user()->employee->fullname }}</span>
                        </div>

                    </div>
                </div>

                <header class="text-warning">
                    <h4 class="mt-5 font-weight-bold text-center">DI ISI UNTUK PASIEN YANG KEMBALI KE RUANG SEMULA
                        PASCA
                        TINDAKAN / PROSEDUR
                    </h4>
                </header>
                <div class="row mt-3">
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label class="text-primary" for="pr">Pasien kembali ke ruang semula pukul:</label>
                            <div class="input-group">
                                <div class="input-group">
                                    <input id="pr" type="text" name="pr" class="form-control">
                                    <div class="input-group-append">
                                        <span class="input-group-text">wib</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label class="text-primary" for="pr">Keadaan Umum:</label>
                            <input id="pr" type="text" name="pr" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label class="text-primary" for="pr">TD:</label>
                            <div class="input-group">
                                <div class="input-group">
                                    <input id="pr" type="text" name="pr" class="form-control">
                                    <div class="input-group-append">
                                        <span class="input-group-text">mmHg</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label class="text-primary" for="pr">ND:</label>
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
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label class="text-primary" for="pr">RR:</label>
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
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label class="text-primary" for="pr">SB:</label>
                            <div class="input-group">
                                <div class="input-group">
                                    <input id="pr" type="text" name="pr" class="form-control">
                                    <div class="input-group-append">
                                        <span class="input-group-text">°C</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <label for="kondisi_khusus1" class="control-label text-primary mt-3">Risiko Jatuh:</label>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="form-checkbox">
                                        <label class="custom-control custom-checkbox custom-control-inline">
                                            <input name="kondisi_khusus" id="kondisi_khusus1" value="Tidak Beresiko"
                                                type="checkbox" class="custom-control-input">
                                            <span class="custom-control-label text-primary">Tidak Beresiko</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="form-checkbox">
                                        <label class="custom-control custom-checkbox custom-control-inline">
                                            <input name="kondisi_khusus" id="kondisi_khusus2" value="Rendah"
                                                type="checkbox" class="custom-control-input">
                                            <span class="custom-control-label text-primary">Rendah</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="form-checkbox">
                                        <label class="custom-control custom-checkbox custom-control-inline">
                                            <input name="kondisi_khusus" id="kondisi_khusus3" value="Tinggi"
                                                type="checkbox" class="custom-control-input">
                                            <span class="custom-control-label text-primary">Tinggi</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="text-primary" for="pr">Diet:</label>
                            <input id="pr" type="text" name="pr" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-4 text-center">
                        <span>Perawat yang mengirim,</span>
                        <div id="tombol-1" class="mt-3">
                            <a class="btn btn-primary btn-sm text-white ttd" onclick="openSignaturePad(1)"
                                id="ttd_pegawai">Tanda tangan</a>
                        </div>
                        <div class="mt-3">
                            <img id="signature-display-1" src="" alt="Signature Image"
                                style="display:none; max-width:60%;">
                        </div>
                        <div class="mt-3">
                            <span>{{ auth()->user()->employee->fullname }}</span>
                        </div>

                    </div>
                    <div class="col-md-4 text-center">
                    </div>
                    <div class="col-md-4 text-center">
                        <span>Perawat yang menerima,</span>
                        <div id="tombol-2" class="mt-3">
                            <a class="btn btn-primary btn-sm text-white ttd" onclick="openSignaturePad(2)"
                                id="ttd_pegawai">Tanda tangan</a>
                        </div>
                        <div class="mt-3">
                            <img id="signature-display-2" src="" alt="Signature Image"
                                style="display:none; max-width:60%;">
                        </div>
                        <div class="mt-3">
                            <span>{{ auth()->user()->employee->fullname }}</span>
                        </div>

                    </div>
                </div>
            </div>

            <div class="card-actionbar m-3">
                <div class="card-actionbar-row mt-3">
                    <!-- Tambahkan Tombol Kembali -->
                    <button type="button" class="btn btn-secondary waves-effect waves-light btn-kembali"
                        data-dismiss='modal'>
                        <span class="mdi mdi-arrow-left"></span> Kembali
                    </button>
                    <a href="#!" class="btn btn-primary">
                        <span class="mdi mdi-printer"></span> Print
                    </a>
                    <button type="submit" class="btn btn-success waves-effect waves-light save-form">
                        <div class="ikon-tambah">
                            <span class="fal fa-plus-circle mr-1"></span>
                            Tambah
                        </div>
                        <div class="span spinner-text d-none">
                            <span class="spinner-border spinner-border-sm" role="status"
                                aria-hidden="true"></span>
                            Loading...
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@include('pages.kpi.penilaian.partials.ttd')
