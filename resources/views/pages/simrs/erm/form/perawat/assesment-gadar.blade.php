@extends('pages.simrs.erm.index')
@section('erm')
    @if (isset($registration) || $registration != null)
        <div class="tab-content p-3">
            <div class="tab-pane fade show active" id="tab_default-1" role="tabpanel">
                <form action="javascript:void(0)" id="assesment-keperawatan-gadar" method="POST">
                    @csrf
                    @method('POST')
                    @include('pages.simrs.erm.partials.detail-pasien')
                    <input type="hidden" name="registration_id" value="{{ $registration->id }}">
                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                    <hr style="border-color: #868686; margin-bottom: 50px;">
                    <header class="text-primary text-center mt-5">
                        <h2 class="font-weight-bold mt-5">ASSESMENT KEPERAWATAN GAWAT DARURAT</h2>
                    </header>
                    <div class="row mt-5">
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label class="control-label text-primary">Tanggal & Jam Masuk</label>
                                <div class="input-group">
                                    <input type="date" name="tgl_masuk" class="form-control"
                                        value="{{ $pengkajian?->tgl_masuk?->format('Y-m-d') ?? now()->format('Y-m-d') }}">
                                    <input type="time" name="jam_masuk" class="form-control"
                                        value="{{ $pengkajian?->jam_masuk ?? '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label class="control-label text-primary">Tanggal & Jam Dilayani</label>
                                <div class="input-group">
                                    <input type="date" name="tgl_dilayani" class="form-control"
                                        value="{{ $pengkajian?->tgl_dilayani?->format('Y-m-d') ?? now()->format('Y-m-d') }}">
                                    <input type="time" name="jam_dilayani" class="form-control"
                                        value="{{ $pengkajian?->jam_dilayani ?? '' }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-5">
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label class="control-label text-primary">Keluhan Utama</label>
                                <textarea class="form-control" name="keluhan_utama" rows="2">{{ $pengkajian?->keluhan_utama }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label class="control-label text-primary">Riwayat pengobatan / perawatan sebelumnya</label>
                                <textarea class="form-control" name="riwayat_pengobatan" rows="2">{{ $pengkajian?->riwayat_pengobatan }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label class="control-label text-primary">Riwayat penyakit mayor dalam keluarga</label>
                                <textarea class="form-control" name="riwayat_penyakit_keluarga" rows="2">{{ $pengkajian?->riwayat_penyakit_keluarga }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label class="control-label text-primary">Diagnosa Keperawatan 1</label>
                                <select name="diagnosa_keperawatan_1" class="select2 form-select">
                                    <option></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label class="control-label text-primary">Rencana Tindak Lanjut 1</label>
                                <select name="rencana_tindak_lanjut_1" class="select2 form-select">
                                    <option></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label class="control-label text-primary">Diagnosa Keperawatan 2</label>
                                <select name="diagnosa_keperawatan_2" class="select2 form-select">
                                    <option></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label class="control-label text-primary">Rencana Tindak Lanjut 2</label>
                                <select name="rencana_tindak_lanjut_2" class="select2 form-select">
                                    <option></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label class="control-label text-primary">Diagnosa Keperawatan 3</label>
                                <select name="diagnosa_keperawatan_3" class="select2 form-select">
                                    <option></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label class="control-label text-primary">Rencana Tindak Lanjut 3</label>
                                <select name="rencana_tindak_lanjut_3" class="select2 form-select">
                                    <option></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <header class="text-warning mt-4">
                        <h4 class="font-weight-bold">KASUS</h4>
                    </header>
                    <div class="row mt-3">
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox custom-control-inline">
                                    <input type="checkbox" class="custom-control-input" name="kasus_trauma"
                                        id="kasus_trauma" value="1"
                                        {{ $pengkajian?->kasus_trauma == 1 ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="kasus_trauma">Trauma</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox custom-control-inline">
                                    <input type="checkbox" class="custom-control-input" name="kasus_non_trauma"
                                        id="kasus_non_trauma" value="1"
                                        {{ $pengkajian?->kasus_non_trauma == 1 ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="kasus_non_trauma">Non Trauma</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox custom-control-inline">
                                    <input type="checkbox" class="custom-control-input" name="kasus_obstetri"
                                        id="kasus_obstetri" value="1"
                                        {{ $pengkajian?->kasus_obstetri == 1 ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="kasus_obstetri">Obstetri</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox custom-control-inline">
                                    <input type="checkbox" class="custom-control-input" name="kasus_rujukan"
                                        id="kasus_rujukan" value="1"
                                        {{ $pengkajian?->kasus_rujukan == 1 ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="kasus_rujukan">Rujukan Dari</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox custom-control-inline">
                                    <input type="checkbox" class="custom-control-input" name="kasus_tanda_kedukaan"
                                        id="kasus_tanda_kedukaan" value="1"
                                        {{ $pengkajian?->kasus_tanda_kedukaan == 1 ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="kasus_tanda_kedukaan">Tanda Tanda
                                        Kedukaan</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox custom-control-inline">
                                    <input type="checkbox" class="custom-control-input" name="kasus_apneu"
                                        id="kasus_apneu" value="1"
                                        {{ $pengkajian?->kasus_apneu == 1 ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="kasus_apneu">Apneu</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2 mb-3">
                            <div class="form-group">
                                <label class="control-label text-primary">Transportasi ke IGD</label>
                                <select name="transportasi_igd" class="select2 form-select">
                                    <option></option>
                                </select>
                            </div>
                            <div class="form-group mt-2">
                                <label class="control-label text-primary">Spesialistik</label>
                                <select name="spesialistik" class="select2 form-select">
                                    <option></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="control-label text-primary">Hambatan Pasien</label>
                        </div>
                        <div class="col-md-2 mb-3">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox custom-control-inline">
                                    <input type="checkbox" class="custom-control-input" name="hambatan_tidak_ada"
                                        id="hambatan_tidak_ada" value="1"
                                        {{ $pengkajian?->hambatan_tidak_ada == 1 ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="hambatan_tidak_ada">Tidak Ada</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 mb-3">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox custom-control-inline">
                                    <input type="checkbox" class="custom-control-input" name="hambatan_bahasa"
                                        id="hambatan_bahasa" value="1"
                                        {{ $pengkajian?->hambatan_bahasa == 1 ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="hambatan_bahasa">Bahasa</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 mb-3">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox custom-control-inline">
                                    <input type="checkbox" class="custom-control-input" name="hambatan_fisik"
                                        id="hambatan_fisik" value="1"
                                        {{ $pengkajian?->hambatan_fisik == 1 ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="hambatan_fisik">Fisik</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 mb-3">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox custom-control-inline">
                                    <input type="checkbox" class="custom-control-input" name="hambatan_tuli"
                                        id="hambatan_tuli" value="1"
                                        {{ $pengkajian?->hambatan_tuli == 1 ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="hambatan_tuli">Tuli</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 mb-3">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox custom-control-inline">
                                    <input type="checkbox" class="custom-control-input" name="hambatan_bisu"
                                        id="hambatan_bisu" value="1"
                                        {{ $pengkajian?->hambatan_bisu == 1 ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="hambatan_bisu">Bisu</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 mb-3">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox custom-control-inline">
                                    <input type="checkbox" class="custom-control-input" name="hambatan_buta"
                                        id="hambatan_buta" value="1"
                                        {{ $pengkajian?->hambatan_buta == 1 ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="hambatan_buta">Buta</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <header class="text-warning mt-4">
                        <h4 class="font-weight-bold">KEADAAN PRA HOSPITAL</h4>
                    </header>
                    <div class="row mt-3">
                        <div class="col-md-4 mb-3">
                            <div class="form-group"><label class="text-primary">Tinggi Badan</label><input type="text"
                                    name="pra_tinggi_badan" class="form-control"
                                    value="{{ $pengkajian?->pra_tinggi_badan }}"></div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group"><label class="text-primary">Berat Badan</label><input type="text"
                                    name="pra_berat_badan" class="form-control"
                                    value="{{ $pengkajian?->pra_berat_badan }}"></div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group"><label class="text-primary">GCS</label><input type="text"
                                    name="pra_gcs" class="form-control" value="{{ $pengkajian?->pra_gcs }}"></div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group"><label class="text-primary">Tekanan Darah</label><input
                                    type="text" name="pra_tekanan_darah" class="form-control"
                                    value="{{ $pengkajian?->pra_tekanan_darah }}"></div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group"><label class="text-primary">Nadi</label><input type="text"
                                    name="pra_nadi" class="form-control" value="{{ $pengkajian?->pra_nadi }}"></div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group"><label class="text-primary">Suhu</label><input type="text"
                                    name="pra_suhu" class="form-control" value="{{ $pengkajian?->pra_suhu }}"></div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group"><label class="text-primary">RR</label><input type="text"
                                    name="pra_rr" class="form-control" value="{{ $pengkajian?->pra_rr }}"></div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group"><label class="text-primary">SP02</label><input type="text"
                                    name="pra_sp02" class="form-control" value="{{ $pengkajian?->pra_sp02 }}"></div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group"><label class="text-primary">O2</label><input type="text"
                                    name="pra_o2" class="form-control" value="{{ $pengkajian?->pra_o2 }}"></div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group"><label class="text-primary">Data Penunjang</label><input
                                    type="text" name="pra_data_penunjang" class="form-control"
                                    value="{{ $pengkajian?->pra_data_penunjang }}"></div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group"><label class="text-primary">Obat / Infus</label><input type="text"
                                    name="pra_obat_infus" class="form-control"
                                    value="{{ $pengkajian?->pra_obat_infus }}"></div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group"><label class="text-primary">Alasan / Indikasi Dirujuk</label><input
                                    type="text" name="pra_alasan_dirujuk" class="form-control"
                                    value="{{ $pengkajian?->pra_alasan_dirujuk }}"></div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group"><label class="text-primary">Lain-lain</label><input type="text"
                                    name="pra_lain_lain" class="form-control" value="{{ $pengkajian?->pra_lain_lain }}">
                            </div>
                        </div>
                    </div>
                    <header class="text-warning mt-4">
                        <h4 class="font-weight-bold">RIWAYAT PSIKOSOSIAL, SPIRITUAL & KEPERCAYAAN</h4>
                    </header>
                    <div class="row mt-3">
                        <div class="col-md-3 mb-3">
                            <div class="form-group">
                                <label for="status_psikologis" class="control-label text-primary">Status
                                    psikologis</label>
                                <select name="status_psikologis" id="status_psikologis" class="select2 form-select">
                                    <option></option>
                                    <option value="Tenang"
                                        {{ ($pengkajian?->status_psikologis ?? '') == 'Tenang' ? 'selected' : '' }}>Tenang
                                    </option>
                                    <option value="Cemas"
                                        {{ ($pengkajian?->status_psikologis ?? '') == 'Cemas' ? 'selected' : '' }}>Cemas
                                    </option>
                                    <option value="Takut"
                                        {{ ($pengkajian?->status_psikologis ?? '') == 'Takut' ? 'selected' : '' }}>Takut
                                    </option>
                                    <option value="Marah"
                                        {{ ($pengkajian?->status_psikologis ?? '') == 'Marah' ? 'selected' : '' }}>Marah
                                    </option>
                                    <option value="Sedih"
                                        {{ ($pengkajian?->status_psikologis ?? '') == 'Sedih' ? 'selected' : '' }}>Sedih
                                    </option>
                                </select>
                            </div>
                            <div class="form-group mt-2">
                                <label for="hubungan_keluarga" class="control-label text-primary">Hubungan dengan anggota
                                    keluarga</label>
                                <input type="text" name="hubungan_keluarga" id="hubungan_keluarga"
                                    class="form-control" value="{{ $pengkajian?->hubungan_keluarga }}">
                            </div>
                            <div class="form-group mt-2">
                                <label for="status_perkawinan" class="control-label text-primary">Status
                                    perkawinan</label>
                                <input type="text" name="status_perkawinan" id="status_perkawinan"
                                    class="form-control" value="{{ $pengkajian?->status_perkawinan }}">
                            </div>
                            <div class="form-group mt-2">
                                <label for="pendidikan" class="control-label text-primary">Pendidikan</label>
                                <input type="text" name="pendidikan" id="pendidikan" class="form-control"
                                    value="{{ $pengkajian?->pendidikan }}">
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="form-group">
                                <label for="status_mental" class="control-label text-primary">Status Mental</label>
                                <input type="text" name="status_mental" id="status_mental" class="form-control"
                                    value="{{ $pengkajian?->status_mental }}">
                            </div>
                            <div class="form-group mt-2">
                                <label for="tempat_tinggal" class="control-label text-primary">Tempat tinggal
                                    (rumah/panti/kos/dll)</label>
                                <input type="text" name="tempat_tinggal" id="tempat_tinggal" class="form-control"
                                    value="{{ $pengkajian?->tempat_tinggal }}">
                            </div>
                            <div class="form-group mt-2">
                                <label for="pekerjaan" class="control-label text-primary">Pekerjaan</label>
                                <input type="text" name="pekerjaan" id="pekerjaan" class="form-control"
                                    value="{{ $pengkajian?->pekerjaan }}">
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="form-group">
                                <label for="masalah_perilaku" class="control-label text-primary">Masalah perilaku (bila
                                    ada)</label>
                                <input type="text" name="masalah_perilaku" id="masalah_perilaku" class="form-control"
                                    value="{{ $pengkajian?->masalah_perilaku }}">
                            </div>
                            <div class="form-group mt-2">
                                <label for="kerabat_dihubungi" class="control-label text-primary">Kerabat yang dapat
                                    dihubungi</label>
                                <input type="text" name="kerabat_dihubungi" id="kerabat_dihubungi"
                                    class="form-control" value="{{ $pengkajian?->kerabat_dihubungi }}">
                            </div>
                            <div class="form-group mt-2">
                                <label for="agama" class="control-label text-primary">Agama</label>
                                <input type="text" name="agama" id="agama" class="form-control"
                                    value="{{ $pengkajian?->agama }}">
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="form-group">
                                <label for="kekerasan" class="control-label text-primary">Kekerasan yg pernah
                                    dialami</label>
                                <input type="text" name="kekerasan" id="kekerasan" class="form-control"
                                    value="{{ $pengkajian?->kekerasan }}">
                            </div>
                            <div class="form-group mt-2">
                                <label for="kontak_kerabat" class="control-label text-primary">Kontak kerabat yang dapat
                                    dihubungi</label>
                                <input type="text" name="kontak_kerabat" id="kontak_kerabat" class="form-control"
                                    value="{{ $pengkajian?->kontak_kerabat }}">
                            </div>
                            <div class="form-group mt-2">
                                <label for="penghasilan" class="control-label text-primary">Penghasilan</label>
                                <select name="penghasilan" id="penghasilan" class="select2 form-select">
                                    <option></option>
                                    <option value="< 1 Juta"
                                        {{ ($pengkajian?->penghasilan ?? '') == '< 1 Juta' ? 'selected' : '' }}>&lt; 1 Juta
                                    </option>
                                    <option value="1 - 2,9 Juta"
                                        {{ ($pengkajian?->penghasilan ?? '') == '1 - 2,9 Juta' ? 'selected' : '' }}>1 - 2,9
                                        Juta</option>
                                    <option value="3 - 4,9 Juta"
                                        {{ ($pengkajian?->penghasilan ?? '') == '3 - 4,9 Juta' ? 'selected' : '' }}>3 - 4,9
                                        Juta</option>
                                    <option value="5 - 9,9 Juta"
                                        {{ ($pengkajian?->penghasilan ?? '') == '5 - 9,9 Juta' ? 'selected' : '' }}>5 - 9,9
                                        Juta</option>
                                    <option value="10 - 14,9 Juta"
                                        {{ ($pengkajian?->penghasilan ?? '') == '10 - 14,9 Juta' ? 'selected' : '' }}>10 -
                                        14,9 Juta</option>
                                    <option value="15 - 19.5 Juta"
                                        {{ ($pengkajian?->penghasilan ?? '') == '15 - 19.5 Juta' ? 'selected' : '' }}>15 -
                                        19.5 Juta</option>
                                    <option value="> 20 Juta"
                                        {{ ($pengkajian?->penghasilan ?? '') == '> 20 Juta' ? 'selected' : '' }}>&gt; 20
                                        Juta</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <header class="text-warning mt-4">
                        <h4 class="font-weight-bold">SKALA FLACC</h4>
                    </header>
                    <div class="row mt-3">
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label class="control-label text-primary">Wajah</label>
                                <select name="flacc_wajah" class="select2 form-select">
                                    <option></option>
                                    <option value="0"
                                        {{ ($pengkajian?->flacc_wajah ?? '') == '0' ? 'selected' : '' }}>0</option>
                                    <option value="1"
                                        {{ ($pengkajian?->flacc_wajah ?? '') == '1' ? 'selected' : '' }}>1</option>
                                    <option value="2"
                                        {{ ($pengkajian?->flacc_wajah ?? '') == '2' ? 'selected' : '' }}>2</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label class="control-label text-primary">Kaki</label>
                                <select name="flacc_kaki" class="select2 form-select">
                                    <option></option>
                                    <option value="0"
                                        {{ ($pengkajian?->flacc_kaki ?? '') == '0' ? 'selected' : '' }}>
                                        0</option>
                                    <option value="1"
                                        {{ ($pengkajian?->flacc_kaki ?? '') == '1' ? 'selected' : '' }}>
                                        1</option>
                                    <option value="2"
                                        {{ ($pengkajian?->flacc_kaki ?? '') == '2' ? 'selected' : '' }}>
                                        2</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label class="control-label text-primary">Aktivitas</label>
                                <select name="flacc_aktivitas" class="select2 form-select">
                                    <option></option>
                                    <option value="0"
                                        {{ ($pengkajian?->flacc_aktivitas ?? '') == '0' ? 'selected' : '' }}>0</option>
                                    <option value="1"
                                        {{ ($pengkajian?->flacc_aktivitas ?? '') == '1' ? 'selected' : '' }}>1</option>
                                    <option value="2"
                                        {{ ($pengkajian?->flacc_aktivitas ?? '') == '2' ? 'selected' : '' }}>2</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label class="control-label text-primary">Menangis</label>
                                <select name="flacc_menangis" class="select2 form-select">
                                    <option></option>
                                    <option value="0"
                                        {{ ($pengkajian?->flacc_menangis ?? '') == '0' ? 'selected' : '' }}>0</option>
                                    <option value="1"
                                        {{ ($pengkajian?->flacc_menangis ?? '') == '1' ? 'selected' : '' }}>1</option>
                                    <option value="2"
                                        {{ ($pengkajian?->flacc_menangis ?? '') == '2' ? 'selected' : '' }}>2</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label class="control-label text-primary">Bersuara</label>
                                <select name="flacc_bersuara" class="select2 form-select">
                                    <option></option>
                                    <option value="0"
                                        {{ ($pengkajian?->flacc_bersuara ?? '') == '0' ? 'selected' : '' }}>0</option>
                                    <option value="1"
                                        {{ ($pengkajian?->flacc_bersuara ?? '') == '1' ? 'selected' : '' }}>1</option>
                                    <option value="2"
                                        {{ ($pengkajian?->flacc_bersuara ?? '') == '2' ? 'selected' : '' }}>2</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label class="control-label text-primary">Skor</label>
                                <input type="text" name="flacc_skor" class="form-control"
                                    value="{{ $pengkajian?->flacc_skor }}">
                            </div>
                        </div>
                    </div>

                    <header class="text-warning mt-4">
                        <h4 class="font-weight-bold">KEADAAN UMUM</h4>
                    </header>
                    <div class="row mt-3">
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label class="control-label text-primary">Keaadaan Umum</label>
                                <select name="keadaan_umum" class="select2 form-select">
                                    <option></option>
                                    <option value="Baik"
                                        {{ ($pengkajian?->keadaan_umum ?? '') == 'Baik' ? 'selected' : '' }}>Baik</option>
                                    <option value="Sedang"
                                        {{ ($pengkajian?->keadaan_umum ?? '') == 'Sedang' ? 'selected' : '' }}>Sedang
                                    </option>
                                    <option value="Buruk"
                                        {{ ($pengkajian?->keadaan_umum ?? '') == 'Buruk' ? 'selected' : '' }}>Buruk
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <header class="text-warning mt-4">
                        <h4 class="font-weight-bold">SKRINING RESIKO JATUH - GET UP & GO</h4>
                    </header>
                    <div class="row mt-3">
                        <div class="col-md-12 mb-3">
                            <label class="control-label text-primary">Cara Berjalan</label>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox custom-control-inline">
                                    <input type="checkbox" class="custom-control-input" name="berjalan_stabil"
                                        id="berjalan_stabil" value="1"
                                        {{ $pengkajian?->berjalan_stabil == 1 ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="berjalan_stabil">Tidak
                                        seimbang/sempoyongan/limbung</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox custom-control-inline">
                                    <input type="checkbox" class="custom-control-input" name="alat_bantu"
                                        id="alat_bantu" value="1"
                                        {{ $pengkajian?->alat_bantu == 1 ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="alat_bantu">Alat bantu: kruk/kursi
                                        roda/dibantu</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox custom-control-inline">
                                    <input type="checkbox" class="custom-control-input" name="pegang_meja"
                                        id="pegang_meja" value="1"
                                        {{ $pengkajian?->pegang_meja == 1 ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="pegang_meja">Pegang pinggiran meja/kursi/saat
                                        bantu untuk duduk</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <header class="text-warning mt-4">
                        <h4 class="font-weight-bold">SKRINING GIZI</h4>
                    </header>
                    <div class="row mt-3">
                        <div class="col-md-3 mb-3">
                            <div class="form-group">
                                <label class="control-label text-primary">Penurunan Berat Badan 6 Bulan Terakhir</label>
                                <select name="gizi_penurunan_bb" class="select2 form-select">
                                    <option></option>
                                    <option value="Tidak"
                                        {{ ($pengkajian?->gizi_penurunan_bb ?? '') == 'Tidak' ? 'selected' : '' }}>Tidak
                                    </option>
                                    <option value="Tidak Yakin"
                                        {{ ($pengkajian?->gizi_penurunan_bb ?? '') == 'Tidak Yakin' ? 'selected' : '' }}>
                                        Tidak Yakin</option>
                                    <option value="Ya"
                                        {{ ($pengkajian?->gizi_penurunan_bb ?? '') == 'Ya' ? 'selected' : '' }}>Ya</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="form-group">
                                <label class="control-label text-primary">Asupan Makanan Pasien</label>
                                <select name="gizi_asupan_makanan" class="select2 form-select">
                                    <option></option>
                                    <option value="Tidak Ada Penurunan"
                                        {{ ($pengkajian?->gizi_asupan_makanan ?? '') == 'Tidak Ada Penurunan' ? 'selected' : '' }}>
                                        Tidak Ada Penurunan</option>
                                    <option value="Ada Penurunan"
                                        {{ ($pengkajian?->gizi_asupan_makanan ?? '') == 'Ada Penurunan' ? 'selected' : '' }}>
                                        Ada Penurunan</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12 mb-3">
                            <label class="control-label text-primary">Pasien dalam kondisi khusus</label>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" name="gizi_kondisi_anak"
                                                id="gizi_kondisi_anak" value="1"
                                                {{ $pengkajian?->gizi_kondisi_anak == 1 ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="gizi_kondisi_anak">Anak usia 1-5
                                                tahun</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input"
                                                name="gizi_kondisi_lansia" id="gizi_kondisi_lansia" value="1"
                                                {{ $pengkajian?->gizi_kondisi_lansia == 1 ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="gizi_kondisi_lansia">Lansia &gt; 60
                                                tahun</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input"
                                                name="gizi_kondisi_komplikasi" id="gizi_kondisi_komplikasi"
                                                value="1"
                                                {{ $pengkajian?->gizi_kondisi_komplikasi == 1 ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="gizi_kondisi_komplikasi">Penyakit
                                                kronis
                                                dengan komplikasi</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input"
                                                name="gizi_kondisi_kanker" id="gizi_kondisi_kanker" value="1"
                                                {{ $pengkajian?->gizi_kondisi_kanker == 1 ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="gizi_kondisi_kanker">Kanker stadium
                                                III/IV</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" name="gizi_kondisi_hiv"
                                                id="gizi_kondisi_hiv" value="1"
                                                {{ $pengkajian?->gizi_kondisi_hiv == 1 ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="gizi_kondisi_hiv">HIV/AIDS</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" name="gizi_kondisi_tb"
                                                id="gizi_kondisi_tb" value="1"
                                                {{ $pengkajian?->gizi_kondisi_tb == 1 ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="gizi_kondisi_tb">TB</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" name="gizi_kondisi_bedah"
                                                id="gizi_kondisi_bedah" value="1"
                                                {{ $pengkajian?->gizi_kondisi_bedah == 1 ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="gizi_kondisi_bedah">Bedah mayor
                                                digestif</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" name="gizi_kondisi_luka"
                                                id="gizi_kondisi_luka" value="1"
                                                {{ $pengkajian?->gizi_kondisi_luka == 1 ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="gizi_kondisi_luka">Luka bakar &gt;
                                                20%</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <header class="text-warning mt-4">
                        <h4 class="font-weight-bold">BARTHEL INDEX (STATUS FUNGSIONAL)</h4>
                    </header>
                    <div class="row mt-3">
                        <div class="col-md-3 mb-3">
                            <div class="form-group">
                                <label class="control-label text-primary">Makan</label>
                                <select name="barthel_makan" class="select2 form-select">
                                    <option></option>
                                    <option value="Mandiri"
                                        {{ ($pengkajian?->barthel_makan ?? '') == 'Mandiri' ? 'selected' : '' }}>
                                        Mandiri
                                    </option>
                                    <option value="Bantuan Sebagian"
                                        {{ ($pengkajian?->barthel_makan ?? '') == 'Bantuan Sebagian' ? 'selected' : '' }}>
                                        Bantuan Sebagian</option>
                                    <option value="Tergantung"
                                        {{ ($pengkajian?->barthel_makan ?? '') == 'Tergantung' ? 'selected' : '' }}>
                                        Tergantung</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="form-group">
                                <label class="control-label text-primary">Mandi</label>
                                <select name="barthel_mandi" class="select2 form-select">
                                    <option></option>
                                    <option value="Mandiri"
                                        {{ ($pengkajian?->barthel_mandi ?? '') == 'Mandiri' ? 'selected' : '' }}>
                                        Mandiri
                                    </option>
                                    <option value="Bantuan Sebagian"
                                        {{ ($pengkajian?->barthel_mandi ?? '') == 'Bantuan Sebagian' ? 'selected' : '' }}>
                                        Bantuan Sebagian</option>
                                    <option value="Tergantung"
                                        {{ ($pengkajian?->barthel_mandi ?? '') == 'Tergantung' ? 'selected' : '' }}>
                                        Tergantung</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="form-group">
                                <label class="control-label text-primary">Berhias</label>
                                <select name="barthel_berhias" class="select2 form-select">
                                    <option></option>
                                    <option value="Mandiri"
                                        {{ ($pengkajian?->barthel_berhias ?? '') == 'Mandiri' ? 'selected' : '' }}>
                                        Mandiri
                                    </option>
                                    <option value="Bantuan Sebagian"
                                        {{ ($pengkajian?->barthel_berhias ?? '') == 'Bantuan Sebagian' ? 'selected' : '' }}>
                                        Bantuan Sebagian</option>
                                    <option value="Tergantung"
                                        {{ ($pengkajian?->barthel_berhias ?? '') == 'Tergantung' ? 'selected' : '' }}>
                                        Tergantung</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="form-group">
                                <label class="control-label text-primary">Berpakaian</label>
                                <select name="barthel_berpakaian" class="select2 form-select">
                                    <option></option>
                                    <option value="Mandiri"
                                        {{ ($pengkajian?->barthel_berpakaian ?? '') == 'Mandiri' ? 'selected' : '' }}>
                                        Mandiri</option>
                                    <option value="Bantuan Sebagian"
                                        {{ ($pengkajian?->barthel_berpakaian ?? '') == 'Bantuan Sebagian' ? 'selected' : '' }}>
                                        Bantuan Sebagian</option>
                                    <option value="Tergantung"
                                        {{ ($pengkajian?->barthel_berpakaian ?? '') == 'Tergantung' ? 'selected' : '' }}>
                                        Tergantung</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-3 mb-3">
                            <div class="form-group">
                                <label class="control-label text-primary">BAB</label>
                                <select name="barthel_bab" class="select2 form-select">
                                    <option></option>
                                    <option value="Mandiri"
                                        {{ ($pengkajian?->barthel_bab ?? '') == 'Mandiri' ? 'selected' : '' }}>Mandiri
                                    </option>
                                    <option value="Bantuan Sebagian"
                                        {{ ($pengkajian?->barthel_bab ?? '') == 'Bantuan Sebagian' ? 'selected' : '' }}>
                                        Bantuan Sebagian</option>
                                    <option value="Tergantung"
                                        {{ ($pengkajian?->barthel_bab ?? '') == 'Tergantung' ? 'selected' : '' }}>
                                        Tergantung</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="form-group">
                                <label class="control-label text-primary">BAK</label>
                                <select name="barthel_bak" class="select2 form-select">
                                    <option></option>
                                    <option value="Mandiri"
                                        {{ ($pengkajian?->barthel_bak ?? '') == 'Mandiri' ? 'selected' : '' }}>Mandiri
                                    </option>
                                    <option value="Bantuan Sebagian"
                                        {{ ($pengkajian?->barthel_bak ?? '') == 'Bantuan Sebagian' ? 'selected' : '' }}>
                                        Bantuan Sebagian</option>
                                    <option value="Tergantung"
                                        {{ ($pengkajian?->barthel_bak ?? '') == 'Tergantung' ? 'selected' : '' }}>
                                        Tergantung</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="form-group">
                                <label class="control-label text-primary">Toileting</label>
                                <select name="barthel_toileting" class="select2 form-select">
                                    <option></option>
                                    <option value="Mandiri"
                                        {{ ($pengkajian?->barthel_toileting ?? '') == 'Mandiri' ? 'selected' : '' }}>
                                        Mandiri</option>
                                    <option value="Bantuan Sebagian"
                                        {{ ($pengkajian?->barthel_toileting ?? '') == 'Bantuan Sebagian' ? 'selected' : '' }}>
                                        Bantuan Sebagian</option>
                                    <option value="Tergantung"
                                        {{ ($pengkajian?->barthel_toileting ?? '') == 'Tergantung' ? 'selected' : '' }}>
                                        Tergantung</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="form-group">
                                <label class="control-label text-primary">Transfer</label>
                                <select name="barthel_transfer" class="select2 form-select">
                                    <option></option>
                                    <option value="Mandiri"
                                        {{ ($pengkajian?->barthel_transfer ?? '') == 'Mandiri' ? 'selected' : '' }}>
                                        Mandiri
                                    </option>
                                    <option value="Bantuan Sebagian"
                                        {{ ($pengkajian?->barthel_transfer ?? '') == 'Bantuan Sebagian' ? 'selected' : '' }}>
                                        Bantuan Sebagian</option>
                                    <option value="Tergantung"
                                        {{ ($pengkajian?->barthel_transfer ?? '') == 'Tergantung' ? 'selected' : '' }}>
                                        Tergantung</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-3 mb-3">
                            <div class="form-group">
                                <label class="control-label text-primary">Mobilisasi</label>
                                <select name="barthel_mobilitas" class="select2 form-select">
                                    <option></option>
                                    <option value="Mandiri"
                                        {{ ($pengkajian?->barthel_mobilitas ?? '') == 'Mandiri' ? 'selected' : '' }}>
                                        Mandiri</option>
                                    <option value="Bantuan Sebagian"
                                        {{ ($pengkajian?->barthel_mobilitas ?? '') == 'Bantuan Sebagian' ? 'selected' : '' }}>
                                        Bantuan Sebagian</option>
                                    <option value="Tergantung"
                                        {{ ($pengkajian?->barthel_mobilitas ?? '') == 'Tergantung' ? 'selected' : '' }}>
                                        Tergantung</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="form-group">
                                <label class="control-label text-primary">Naik Tangga</label>
                                <select name="barthel_naik_tangga" class="select2 form-select">
                                    <option></option>
                                    <option value="Mandiri"
                                        {{ ($pengkajian?->barthel_naik_tangga ?? '') == 'Mandiri' ? 'selected' : '' }}>
                                        Mandiri</option>
                                    <option value="Bantuan Sebagian"
                                        {{ ($pengkajian?->barthel_naik_tangga ?? '') == 'Bantuan Sebagian' ? 'selected' : '' }}>
                                        Bantuan Sebagian</option>
                                    <option value="Tergantung"
                                        {{ ($pengkajian?->barthel_naik_tangga ?? '') == 'Tergantung' ? 'selected' : '' }}>
                                        Tergantung</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="form-group">
                                <label class="control-label text-primary">Skor</label>
                                <input type="text" name="barthel_skor" class="form-control"
                                    value="{{ $pengkajian?->barthel_skor }}">
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="form-group">
                                <label class="control-label text-primary">Analisa</label>
                                <input type="text" name="barthel_analisa" class="form-control"
                                    value="{{ $pengkajian?->barthel_analisa }}">
                            </div>
                        </div>
                    </div>

                    <header class="text-warning mt-4">
                        <h4 class="font-weight-bold">PERENCANAAN PULANG (DISCHARGE PLANNING)</h4>
                    </header>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label class="control-label text-primary d-block">Kondisi Discharge Planning</label>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox mb-2">
                                    <input type="checkbox" class="custom-control-input" id="discharge_kondisi_umur65"
                                        name="discharge_kondisi_umur65" value="1"
                                        {{ $pengkajian?->discharge_kondisi_umur65 == 1 ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="discharge_kondisi_umur65">Umur &gt; 65
                                        Tahun</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox mb-2">
                                    <input type="checkbox" class="custom-control-input" id="discharge_kondisi_mobilitas"
                                        name="discharge_kondisi_mobilitas" value="1"
                                        {{ $pengkajian?->discharge_kondisi_mobilitas == 1 ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="discharge_kondisi_mobilitas">Keterbatasan
                                        Mobilitas</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox mb-2">
                                    <input type="checkbox" class="custom-control-input" id="discharge_perawatan_lanjutan"
                                        name="discharge_perawatan_lanjutan" value="1"
                                        {{ $pengkajian?->discharge_perawatan_lanjutan == 1 ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="discharge_perawatan_lanjutan">Perawatan atau
                                        pengobatan
                                        lanjutan</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox mb-2">
                                    <input type="checkbox" class="custom-control-input" id="discharge_bantuan_aktivitas"
                                        name="discharge_bantuan_aktivitas" value="1"
                                        {{ $pengkajian?->discharge_bantuan_aktivitas == 1 ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="discharge_bantuan_aktivitas">Bantuan
                                        beraktivitas sehari hari</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label class="control-label text-primary d-block">Perencanaan Pulang</label>
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group mt-2">
                                        <div class="custom-control custom-checkbox mb-2">
                                            <input type="checkbox" class="custom-control-input"
                                                id="discharge_perawatan_diri" name="discharge_perawatan_diri"
                                                value="1"
                                                {{ $pengkajian?->discharge_perawatan_diri == 1 ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="discharge_perawatan_diri">Perawatan
                                                diri (mandi, BAK, BAB)</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group mt-2">
                                        <div class="custom-control custom-checkbox mb-2">
                                            <input type="checkbox" class="custom-control-input"
                                                id="discharge_pemberian_obat" name="discharge_pemberian_obat"
                                                value="1"
                                                {{ $pengkajian?->discharge_pemberian_obat == 1 ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="discharge_pemberian_obat">Pemantauan
                                                pemberian obat</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group mt-2">
                                        <div class="custom-control custom-checkbox mb-2">
                                            <input type="checkbox" class="custom-control-input"
                                                id="discharge_pemantauan_diet" name="discharge_pemantauan_diet"
                                                value="1"
                                                {{ $pengkajian?->discharge_pemantauan_diet == 1 ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="discharge_pemantauan_diet">Pemantauan
                                                diet</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group mt-2">
                                        <div class="custom-control custom-checkbox mb-2">
                                            <input type="checkbox" class="custom-control-input"
                                                id="discharge_perawatan_luka" name="discharge_perawatan_luka"
                                                value="1"
                                                {{ $pengkajian?->discharge_perawatan_luka == 1 ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="discharge_perawatan_luka">Perawatan
                                                Luka</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group mt-2">
                                        <div class="custom-control custom-checkbox mb-2">
                                            <input type="checkbox" class="custom-control-input"
                                                id="discharge_latihan_fisik" name="discharge_latihan_fisik"
                                                value="1"
                                                {{ $pengkajian?->discharge_latihan_fisik == 1 ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="discharge_latihan_fisik">Latihan
                                                Fisik Lanjutan</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group mt-2">
                                        <div class="custom-control custom-checkbox mb-2">
                                            <input type="checkbox" class="custom-control-input"
                                                id="discharge_pendamping_tenaga" name="discharge_pendamping_tenaga"
                                                value="1"
                                                {{ $pengkajian?->discharge_pendamping_tenaga == 1 ? 'checked' : '' }}>
                                            <label class="custom-control-label"
                                                for="discharge_pendamping_tenaga">Pendampingan
                                                tenaga khusus
                                                dirumah</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group mt-2">
                                        <div class="custom-control custom-checkbox mb-2">
                                            <input type="checkbox" class="custom-control-input"
                                                id="discharge_bantuan_medis" name="discharge_bantuan_medis"
                                                value="1"
                                                {{ $pengkajian?->discharge_bantuan_medis == 1 ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="discharge_bantuan_medis">Bantuan
                                                Medis/perawatan dirumah</label>
                                        </div>
                                    </div>
                                </div>
                                {{-- BUatkan input baru --}}

                                <div class="col-md-3">
                                    <div class="form-group mt-2">
                                        <div class="custom-control custom-checkbox mb-2">
                                            <input type="checkbox" class="custom-control-input"
                                                id="discharge_bantuan_aktivitas_fisik"
                                                name="discharge_bantuan_aktivitas_fisik" value="1"
                                                {{ $pengkajian?->discharge_bantuan_aktivitas_fisik == 1 ? 'checked' : '' }}>
                                            <label class="custom-control-label"
                                                for="discharge_bantuan_aktivitas_fisik">Bantuan
                                                aktivitas fisik</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

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
                                        {{-- <button type="button"
                                            class="btn mr-2 btn-warning waves-effect text-white waves-light save-form d-flex align-items-center"
                                            data-dismiss="modal" data-status="0" id="sd-pengkajian-nurse-rajal">
                                            <span class="mdi mdi-content-save mr-2"></span> Simpan (draft)
                                        </button> --}}
                                        <button type="submit"
                                            class="btn btn-primary waves-effect waves-light save-form d-flex align-items-center"
                                            data-dismiss="modal" data-status="1" id="sf-triage">
                                            <span class="mdi mdi-content-save mr-2"></span> Simpan (final)
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
    <script>
        $(document).ready(function() {
            if (registrationId) {
                $.ajax({
                    url: `/api/simrs/erm/assesment-keperawatan-gadar/${registrationId}`,
                    type: "GET",
                    dataType: "json",
                    beforeSend: function() {
                        $('#loading-indicator').show();
                    },
                    success: function(response) {
                        if (response.data) {
                            let data = response.data;

                            $('input[name="tgl"]').val(data.tgl);
                            $('input[name="jam"]').val(data.jam);
                            $('input[name="skor_total"]').val(data.skor_total);

                            $('input[name="keadaan_umum"]').prop('checked', false);
                            $('input[name="keadaan_umum"][value="' + data.keadaan_umum + '"]').prop(
                                'checked', true);

                            $('input[name="kardio_vaskular"]').prop('checked', false);
                            $('input[name="kardio_vaskular"][value="' + data.kardio_vaskular + '"]')
                                .prop('checked', true);

                            $('input[name="respirasi"]').prop('checked', false);
                            $('input[name="respirasi"][value="' + data.respirasi + '"]').prop(
                                'checked', true);
                        }
                    },
                    error: function(xhr) {
                        console.error('Load data error:', xhr.responseText);
                    },
                    complete: function() {
                        $('#loading-indicator').hide();
                    }
                });
            }

            $('#assesment-keperawatan-gadar').on('submit', function(e) {
                e.preventDefault();

                // Ambil semua data dari form
                var formData = new FormData(this);

                $.ajax({
                    url: `/api/simrs/erm/assesment-keperawatan-gadar`, // Endpoint untuk menyimpan data
                    type: "POST",
                    data: formData,
                    beforeSend: function() {
                        $('#loading-indicator').show();
                    },
                    success: function(response) {
                        alert("Data berhasil disimpan!");
                    },
                    error: function(xhr) {
                        console.error('Simpan data error:', xhr.responseText);
                        alert("Terjadi kesalahan saat menyimpan data.");
                    },
                    complete: function() {
                        $('#loading-indicator').hide();
                    }
                });
            });


        });
    </script>
@endsection
