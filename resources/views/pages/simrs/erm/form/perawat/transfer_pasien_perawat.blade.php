@extends('pages.simrs.erm.index')
@section('erm')
    @if (isset($registration) || $registration != null)
        {{-- content start --}}
        <div class="tab-content p-3">
            <div class="tab-pane fade show active" id="tab_default-1" role="tabpanel">
                <form action="javascript:void(0)" id="transfer_pasien_antar_ruangan">
                    @csrf
                    @method('POST')
                    @include('pages.simrs.erm.partials.detail-pasien')
                    <hr style="border-color: #868686; margin-bottom: 50px;">
                    <header class="text-primary text-center mt-5">
                        <h2 class="font-weight-bold mt-5">TRANSFER PASIEN ANTAR RUANGAN</h2>
                    </header>
                    <header class="text-success">
                        <h4 class="mt-5 font-weight-bold text-center">MASUK RUMAH SAKIT</h4>
                    </header>
                    <div class="row justify-content-center">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tgl" class="text-primary d-block text-center">Tanggal &amp; jam
                                    masuk</label>
                                <div class="form-group mb-3">
                                    <div class="input-group">
                                        <input type="date" name="tgl" class="form-control" placeholder="Tanggal"
                                            id="tgl"
                                            value="{{ old('tgl', isset($pengkajian->tgl) ? $pengkajian->tgl : '') }}">
                                        <input type="time" name="jam" class="form-control" placeholder="Jam"
                                            id="jam"
                                            value="{{ old('jam', isset($pengkajian->jam) ? \Carbon\Carbon::parse($pengkajian->jam)->format('H:i') : '') }}">
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
                                <label for="tgl_masuk_pasien" class="text-primary d-block">Tanggal &amp; jam Transfer Pasien
                                    :</label>
                                <div class="form-group mb-3">
                                    <div class="input-group">
                                        <input type="text" name="tgl_masuk_pasien" class="form-control"
                                            placeholder="Tanggal" id="tgl_masuk_pasien"
                                            value="{{ old('tgl_masuk_pasien', isset($pengkajian->tgl_masuk_pasien) ? $pengkajian->tgl_masuk_pasien : '') }}">
                                        <input type="time" name="jam_masuk_pasien" class="form-control" placeholder="Jam"
                                            id="jam_masuk_pasien"
                                            value="{{ old('jam_masuk_pasien', isset($pengkajian->jam_masuk_pasien) ? $pengkajian->jam_masuk_pasien : '') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="ruangan_asal" class="control-label text-primary">Ruangan:</label>
                                        <input name="ruangan_asal" id="ruangan_asal" class="form-control alergi"
                                            type="text"
                                            value="{{ old('ruangan_asal', $pengkajian->ruangan_asal ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="kelas_asal" class="control-label text-primary">Kelas:</label>
                                        <input name="kelas_asal" id="kelas_asal" class="form-control alergi" type="text"
                                            value="{{ old('kelas_asal', $pengkajian->kelas_asal ?? '') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="asesmen" class="control-label text-primary">DX Medis</label>
                                <textarea class="form-control" id="asesmen" name="asesmen" rows="1" data-label="Keluhan utama">{{ old('asesmen', $pengkajian->asesmen ?? '') }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="ruangan_pindah" class="control-label text-primary">Pindah
                                            Ruangan:</label>
                                        <input name="ruangan_pindah" id="ruangan_pindah" class="form-control alergi"
                                            type="text"
                                            value="{{ old('ruangan_pindah', $pengkajian->ruangan_pindah ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="kelas_pindah" class="control-label text-primary">Kelas:</label>
                                        <input name="kelas_pindah" id="kelas_pindah" class="form-control alergi"
                                            type="text"
                                            value="{{ old('kelas_pindah', $pengkajian->kelas_pindah ?? '') }}">
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
                                    data-label="Keluhan utama">{{ old('masalah_keperawatan', $pengkajian->masalah_keperawatan ?? '') }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tiba_diruangan" class="text-primary">Tiba di Ruangan:</label>
                                <div class="input-group">
                                    <input type="time" name="tiba_diruangan" class="form-control" placeholder="Jam"
                                        id="tiba_diruangan"
                                        value="{{ old('tiba_diruangan', isset($pengkajian->tiba_diruangan) ? \Carbon\Carbon::parse($pengkajian->tiba_diruangan)->format('H:i') : now()->format('H:i')) }}">
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
                                    <input id="dokter" type="text" name="dokter" class="form-control"
                                        value="{{ old('dokter', $pengkajian->dokter ?? $registration->doctor->employee->fullname) }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="fas fa-user-md"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label class="text-primary" for="dokter2">Dokter 2</label>
                                <div class="input-group">
                                    <input id="dokter2" type="text" name="dokter2" class="form-control"
                                        value="{{ old('dokter2', $pengkajian->dokter2 ?? '') }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="fas fa-user-md"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label class="text-primary" for="dokter3">Dokter 3</label>
                                <div class="input-group">
                                    <input id="dokter3" type="text" name="dokter3" class="form-control"
                                        value="{{ old('dokter3', $pengkajian->dokter3 ?? '') }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="fas fa-user-md"></i>
                                        </span>
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
                                <textarea class="form-control" id="keluhan_utama" name="keluhan_utama" rows="6" data-label="Keluhan utama">{{ old('keluhan_utama', $pengkajian->keluhan_utama ?? '') }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="kondisi_pasien1" class="control-label text-primary mt-3">Kondisi Pasien:</label>
                            <div class="row mt-3">
                                @php
                                    $kondisiPasien = old('kondisi_pasien', $pengkajian->kondisi_pasien ?? '');
                                @endphp
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="form-radio">
                                            <label class="custom-control custom-radio custom-control-inline">
                                                <input name="kondisi_pasien" id="kondisi_pasien1" value="Stabil"
                                                    type="radio" class="custom-control-input"
                                                    {{ $kondisiPasien == 'Stabil' ? 'checked' : '' }}>
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
                                                    type="radio" class="custom-control-input"
                                                    {{ $kondisiPasien == 'Memburuk' ? 'checked' : '' }}>
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
                                                    class="custom-control-input"
                                                    {{ $kondisiPasien == 'Tidak ada perubahan' ? 'checked' : '' }}>
                                                <span class="custom-control-label text-primary">Tidak ada perubahan</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <label for="tindakan1" class="control-label text-primary mt-3">Tindakan:</label>
                            <div class="row mt-3">
                                @php
                                    $tindakan = old('tindakan', $pengkajian->tindakan ?? '');
                                    $ketLainnya = old('ket_lainnya', $pengkajian->ket_lainnya ?? '');
                                @endphp
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="form-radio">
                                            <label class="custom-control custom-radio custom-control-inline">
                                                <input name="tindakan" id="tindakan1" value="OK" type="radio"
                                                    class="custom-control-input" {{ $tindakan == 'OK' ? 'checked' : '' }}>
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
                                                    class="custom-control-input" {{ $tindakan == 'VK' ? 'checked' : '' }}>
                                                <span class="custom-control-label text-primary">VK</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="form-radio d-flex align-items-center">
                                            <label class="custom-control custom-radio custom-control-inline mb-0">
                                                <input name="tindakan" id="ket_tindakan" value="Lainnya" type="radio"
                                                    class="custom-control-input"
                                                    {{ $tindakan == 'Lainnya' ? 'checked' : '' }}>
                                                <span class="custom-control-label text-primary">Lainnya</span>
                                            </label>
                                            <input name="ket_lainnya" id="ket_lainnya"
                                                style="margin-left: 10px; width: 100px; border-left: none; border-right: none; border-top: none; border-bottom-color: rgba(12, 12, 12, 0.12);"
                                                type="text" value="{{ $tindakan == 'Lainnya' ? $ketLainnya : '' }}">
                                        </div>
                                    </div>
                                </div>

                                @php
                                    $appLainnyaChecked =
                                        old('app_lainnya', $pengkajian->app_lainnya ?? '') == 'Lainnya';
                                    $appLainnyaText = old('app_lainnya_text', $pengkajian->app_lainnya_text ?? '');
                                @endphp
                                <div class="col-md-6 mt-3">
                                    <div class="form-group">
                                        <div class="form-checkbox d-flex align-items-center">
                                            <label class="custom-control custom-checkbox custom-control-inline mb-0">
                                                <input name="app_lainnya" id="app_lainnya" value="Lainnya"
                                                    type="checkbox" class="custom-control-input"
                                                    {{ $appLainnyaChecked ? 'checked' : '' }}>
                                                <span class="custom-control-label text-primary">Lainnya</span>
                                            </label>
                                            <input name="app_lainnya_text" id="app_lainnya_text"
                                                style="margin-left: 10px; width: 367px; border-left: none; border-right: none; border-top: none; border-bottom-color: rgba(12, 12, 12, 0.12);"
                                                type="text" value="{{ $appLainnyaText }}">
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
                            <label for="keadaan_umum" class="control-label text-primary mt-3">Keadaan Umum:</label>
                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="form-radio">
                                            <label class="custom-control custom-radio custom-control-inline">
                                                <input name="keadaan_umum" id="keadaan_umum" value="Baik"
                                                    type="radio" class="custom-control-input"
                                                    {{ old('keadaan_umum', $pengkajian->keadaan_umum ?? '') == 'Baik' ? 'checked' : '' }}>
                                                <span class="custom-control-label text-primary">Baik</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="form-radio">
                                            <label class="custom-control custom-radio custom-control-inline">
                                                <input name="keadaan_umum" id="keadaan_umum2" value="Sedang"
                                                    type="radio" class="custom-control-input"
                                                    {{ old('keadaan_umum', $pengkajian->keadaan_umum ?? '') == 'Sedang' ? 'checked' : '' }}>
                                                <span class="custom-control-label text-primary">Sedang</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="form-radio">
                                            <label class="custom-control custom-radio custom-control-inline">
                                                <input name="keadaan_umum" id="keadaan_umum3" value="Berat"
                                                    type="radio" class="custom-control-input"
                                                    {{ old('keadaan_umum', $pengkajian->keadaan_umum ?? '') == 'Berat' ? 'checked' : '' }}>
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
                                                    type="radio" class="custom-control-input"
                                                    {{ old('keadaan_umum_gcs', $pengkajian->keadaan_umum_gcs ?? '') == 'GCS' ? 'checked' : '' }}>
                                                <span class="custom-control-label text-primary">GCS:</span>
                                                <input name="ket_gcs" id="ket_gcs" type="text"
                                                    style="margin-right: 20px; width: 50px; border-left: none; border-right: none; border-top: none; border-bottom-color: rgba(12, 12, 12, 0.12);"
                                                    value="{{ old('ket_gcs', $pengkajian->ket_gcs ?? '') }}">
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
                                            <input id="td" type="text" name="td" class="form-control"
                                                value="{{ old('td', $pengkajian->td ?? '') }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text">x/menit</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nd" class="text-primary">ND: </label>
                                        <div class="input-group">
                                            <input class="form-control numeric" id="nd" name="nd"
                                                type="text" value="{{ old('nd', $pengkajian->nd ?? '') }}">
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
                                                type="text" value="{{ old('rr', $pengkajian->rr ?? '') }}">
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
                                                type="text" value="{{ old('sb', $pengkajian->sb ?? '') }}">
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
                                                type="text" value="{{ old('bb', $pengkajian->bb ?? '') }}">
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
                                                type="text" value="{{ old('tb', $pengkajian->tb ?? '') }}">
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
                                <input name="spo2" id="spo2" class="form-control alergi" type="text"
                                    value="{{ old('spo2', $pengkajian->spo2 ?? '') }}">
                            </div>
                            <div class="form-group">
                                <label for="status_nyeri" class="control-label text-primary">Status Nyeri:</label>
                                <input name="status_nyeri" id="status_nyeri" class="form-control alergi" type="text"
                                    value="{{ old('status_nyeri', $pengkajian->status_nyeri ?? '') }}">
                            </div>
                            <div class="form-group">
                                <label for="kesadaran" class="control-label text-primary">Kesadaran: </label>
                                <input name="kesadaran" id="kesadaran" class="form-control alergi" type="text"
                                    value="{{ old('kesadaran', $pengkajian->kesadaran ?? '') }}">
                            </div>

                            <label for="mpp_kuro" class="control-label text-primary mt-3">Metode pemindahan
                                pasien:</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-radio">
                                            <label class="custom-control custom-radio custom-control-inline"
                                                for="mpp_kuro">
                                                <input name="mpp" id="mpp_kuro" value="Kursi Roda" type="radio"
                                                    class="custom-control-input"
                                                    {{ old('mpp', $pengkajian->mpp ?? '') == 'Kursi Roda' ? 'checked' : '' }}>
                                                <span class="custom-control-label text-primary">Kursi Roda</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-radio">
                                            <label class="custom-control custom-radio custom-control-inline"
                                                for="mpp_temti">
                                                <input name="mpp" id="mpp_temti" value="Tempat Tidur" type="radio"
                                                    class="custom-control-input"
                                                    {{ old('mpp', $pengkajian->mpp ?? '') == 'Tempat Tidur' ? 'checked' : '' }}>
                                                <span class="custom-control-label text-primary">Tempat Tidur</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-radio">
                                            <label class="custom-control custom-radio custom-control-inline"
                                                for="mpp_brangkar">
                                                <input name="mpp" id="mpp_brangkar" value="Brangkar" type="radio"
                                                    class="custom-control-input"
                                                    {{ old('mpp', $pengkajian->mpp ?? '') == 'Brangkar' ? 'checked' : '' }}>
                                                <span class="custom-control-label text-primary">Brangkar</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-radio">
                                            <label class="custom-control custom-radio custom-control-inline"
                                                for="mpp_bok_bayi">
                                                <input name="mpp" id="mpp_bok_bayi" value="Bok bayi" type="radio"
                                                    class="custom-control-input"
                                                    {{ old('mpp', $pengkajian->mpp ?? '') == 'Bok bayi' ? 'checked' : '' }}>
                                                <span class="custom-control-label text-primary">Bok bayi</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="rj_tidak_berisiko" class="control-label text-primary mt-3">Risiko Jatuh:</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-radio">
                                            <label class="custom-control custom-radio custom-control-inline">
                                                <input name="rj" id="rj_tidak_berisiko" value="Tidak Beresiko"
                                                    type="radio" class="custom-control-input"
                                                    {{ old('rj', $pengkajian->rj ?? '') == 'Tidak Beresiko' ? 'checked' : '' }}>
                                                <span class="custom-control-label text-primary">Tidak Beresiko</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-radio">
                                            <label class="custom-control custom-radio custom-control-inline"
                                                for="rj_rendah">
                                                <input name="rj" id="rj_rendah" value="Rendah" type="radio"
                                                    class="custom-control-input"
                                                    {{ old('rj', $pengkajian->rj ?? '') == 'Rendah' ? 'checked' : '' }}>
                                                <span class="custom-control-label text-primary">Rendah</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-radio">
                                            <label class="custom-control custom-radio custom-control-inline">
                                                <input name="rj" id="rj_tinggi" value="Tinggi" type="radio"
                                                    class="custom-control-input"
                                                    {{ old('rj', $pengkajian->rj ?? '') == 'Tinggi' ? 'checked' : '' }}>
                                                <span class="custom-control-label text-primary">Tinggi</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <label for="kti_kontak" class="control-label text-primary mt-3">Kewaspadaan
                                transmisi/infeksi:</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-radio">
                                            <label class="custom-control custom-radio custom-control-inline">
                                                <input name="kti" id="kti_kontak" value="Kontak" type="radio"
                                                    class="custom-control-input"
                                                    {{ old('kti', $pengkajian->kti ?? '') == 'Kontak' ? 'checked' : '' }}>
                                                <span class="custom-control-label text-primary">Kontak</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-radio">
                                            <label class="custom-control custom-radio custom-control-inline">
                                                <input name="kti" id="kti_percikan" value="Percikan" type="radio"
                                                    class="custom-control-input"
                                                    {{ old('kti', $pengkajian->kti ?? '') == 'Percikan' ? 'checked' : '' }}>
                                                <span class="custom-control-label text-primary">Percikan</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-radio">
                                            <label class="custom-control custom-radio custom-control-inline">
                                                <input name="kti" id="kti_udara" value="Udara" type="radio"
                                                    class="custom-control-input"
                                                    {{ old('kti', $pengkajian->kti ?? '') == 'Udara' ? 'checked' : '' }}>
                                                <span class="custom-control-label text-primary">Udara</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <label for="kondisi_khusus1" class="control-label text-primary mt-3">Memerlukan perawatan
                                isolasi:</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-radio">
                                            <label class="custom-control custom-radio custom-control-inline">
                                                <input name="mpi" id="mpi_ya" value="Ya" type="radio"
                                                    class="custom-control-input"
                                                    {{ old('mpi', $pengkajian->mpi ?? '') == 'Ya' ? 'checked' : '' }}>
                                                <span class="custom-control-label text-primary">Ya</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-radio">
                                            <label class="custom-control custom-radio custom-control-inline">
                                                <input name="mpi" id="mpi_tidak" value="Tidak" type="radio"
                                                    class="custom-control-input"
                                                    {{ old('mpi', $pengkajian->mpi ?? '') == 'Tidak' ? 'checked' : '' }}>
                                                <span class="custom-control-label text-primary">Tidak</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <label for="pmp_kuro" class="control-label text-primary mt-3">Peralatan yang menyertai saat
                                pemindahan:</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-checkbox">
                                            <label class="custom-control custom-checkbox custom-control-inline">
                                                <input name="pmp_kuro" id="pmp_kuro" value="pmp_kuro" type="checkbox"
                                                    class="custom-control-input"
                                                    {{ old('pmp_kuro', $pengkajian->pmp_kuro ?? false) ? 'checked' : '' }}>
                                                <span class="custom-control-label text-primary">Oksigen</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-10">
                                    <div class="input-group">
                                        <input class="form-control numeric calc-bmi" id="pmp_text" name="pmp_text"
                                            type="text" value="{{ old('pmp_text', $pengkajian->pmp_text ?? '') }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text">ltr/mnt</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mt-3">
                                    <div class="form-group">
                                        <div class="form-checkbox">
                                            <label class="custom-control custom-checkbox custom-control-inline">
                                                <input name="pmp_cateter_urine" id="pmp_cateter_urine"
                                                    value="pmp_cateter_urine" type="checkbox"
                                                    class="custom-control-input"
                                                    {{ old('pmp_cateter_urine', $pengkajian->pmp_cateter_urine ?? false) ? 'checked' : '' }}>
                                                <span class="custom-control-label text-primary">Cateter urine</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mt-3">
                                    <div class="form-group">
                                        <div class="form-checkbox">
                                            <label class="custom-control custom-checkbox custom-control-inline"
                                                for="pmp_ngt">
                                                <input name="pmp_ngt" id="pmp_ngt" value="pmp_ngt" type="checkbox"
                                                    class="custom-control-input"
                                                    {{ old('pmp_ngt', $pengkajian->pmp_ngt ?? false) ? 'checked' : '' }}>
                                                <span class="custom-control-label text-primary">NGT</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <div class="form-group">
                                        <label for="alasan_pdh_temuan_anamesis"
                                            class="control-label text-primary">Intruksi dokter Umum</label>
                                        <textarea class="form-control" id="alasan_pdh_temuan_anamesis" name="alasan_pdh_temuan_anamesis" rows="1"
                                            data-label="Intruksi dokter Umum">{{ old('alasan_pdh_temuan_anamesis', $pengkajian->alasan_pdh_temuan_anamesis ?? '') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <div class="form-group">
                                        <label for="intervensi_tindakan" class="control-label text-primary">Advice
                                            DPJP</label>
                                        <textarea class="form-control" id="intervensi_tindakan" name="intervensi_tindakan" rows="4"
                                            data-label="Advice DPJP">{{ old('intervensi_tindakan', $pengkajian->intervensi_tindakan ?? '') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="ap_ya" class="control-label text-primary mt-3">Pasien atau keluarga mengetahui
                                alasan pemindahan:</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-radio">
                                            <label class="custom-control custom-radio custom-control-inline">
                                                <input name="ap" id="ap_ya" value="ap_ya" type="radio"
                                                    class="custom-control-input"
                                                    {{ old('ap', $pengkajian->ap ?? '') == 'ap_ya' ? 'checked' : '' }}>
                                                <span class="custom-control-label text-primary">Ya</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-radio">
                                            <label class="custom-control custom-radio custom-control-inline"
                                                for="ap_tidak">
                                                <input name="ap" id="ap_tidak" value="ap_tidak" type="radio"
                                                    class="custom-control-input"
                                                    {{ old('ap', $pengkajian->ap ?? '') == 'ap_tidak' ? 'checked' : '' }}>
                                                <span class="custom-control-label text-primary">Tidak</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="ap_nama" class="control-label text-primary">Bila ya: Nama</label>
                                        <input name="ap_nama" id="ap_nama" class="form-control alergi" type="text"
                                            value="{{ old('ap_nama', $pengkajian->ap_nama ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <div class="form-group">
                                        <label for="ap_hubungan" class="control-label text-primary">Hubungan
                                            Keluarga</label>
                                        <input name="ap_hubungan" id="ap_hubungan" class="form-control alergi"
                                            type="text"
                                            value="{{ old('ap_hubungan', $pengkajian->ap_hubungan ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <label for="sfp_mandiri" class="control-label text-primary mt-3">Status fungsional
                                        pasien:</label>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <div class="form-radio">
                                                    <label class="custom-control custom-radio custom-control-inline"
                                                        for="sfp_mandiri">
                                                        <input name="sfp" id="sfp_mandiri" value="Mandiri"
                                                            type="radio" class="custom-control-input"
                                                            {{ old('sfp', $pengkajian->sfp ?? '') == 'Mandiri' ? 'checked' : '' }}>
                                                        <span class="custom-control-label text-primary">Mandiri</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <div class="form-radio">
                                                    <label class="custom-control custom-radio custom-control-inline"
                                                        for="sfp_partial_care">
                                                        <input name="sfp" id="sfp_partial_care" value="Partial care"
                                                            type="radio" class="custom-control-input"
                                                            {{ old('sfp', $pengkajian->sfp ?? '') == 'Partial care' ? 'checked' : '' }}>
                                                        <span class="custom-control-label text-primary">Partial care</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <div class="form-radio">
                                                    <label class="custom-control custom-radio custom-control-inline"
                                                        for="sfp_total_care">
                                                        <input name="sfp" id="sfp_total_care" value="Total care"
                                                            type="radio" class="custom-control-input"
                                                            {{ old('sfp', $pengkajian->sfp ?? '') == 'Total care' ? 'checked' : '' }}>
                                                        <span class="custom-control-label text-primary">Total care</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-3">
                                            <div class="form-group mt-5">
                                                <label for="pemeriksaan_penunjang"
                                                    class="control-label text-primary">Hasil Pemeriksaan Tindakan &
                                                    penunjang/diagnostik yang sudah dilakukan (lab,ekg dll):</label>
                                                <textarea class="form-control" id="pemeriksaan_penunjang" name="pemeriksaan_penunjang" rows="1"
                                                    data-label="Hasil Pemeriksaan Tindakan & penunjang/diagnostik yang sudah dilakukan (lab,ekg dll)">{{ old('pemeriksaan_penunjang', $pengkajian->pemeriksaan_penunjang ?? '') }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-3">
                                            <div class="form-group">
                                                <label for="diet" class="control-label text-primary">Diet (bila pindah
                                                    ruangan):</label>
                                                <textarea class="form-control" id="diet" name="diet" rows="4"
                                                    data-label="Diet (bila pindah ruangan)">{{ old('diet', $pengkajian->diet ?? '') }}</textarea>
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
                                            <label class="custom-control custom-checkbox custom-control-inline"
                                                for="ptsp_infus">
                                                <input name="ptsp_infus" id="ptsp_infus" value="Infus" type="checkbox"
                                                    class="custom-control-input"
                                                    {{ old('ptsp_infus', $pengkajian->ptsp_infus ?? false) ? 'checked' : '' }}>
                                                <span class="custom-control-label text-primary">Infus</span>
                                            </label>
                                        </div>
                                    </div>
                                    <input class="form-control numeric calc-bmi" id="ptsp_infus_text"
                                        name="ptsp_infus_text" type="text"
                                        value="{{ old('ptsp_infus_text', $pengkajian->ptsp_infus_text ?? '') }}">
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="ptsp_infus_tetesan"
                                            class="control-label text-primary">Tetesan:</label>
                                        <input name="ptsp_infus_tetesan" id="ptsp_infus_tetesan"
                                            class="form-control alergi mt-4" type="text"
                                            value="{{ old('ptsp_infus_tetesan', $pengkajian->ptsp_infus_tetesan ?? '') }}">
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
                                        <input type="text" name="resep1" class="form-control"
                                            placeholder="TERAPI DAN TINDAKAN 1" id="resep1"
                                            value="{{ old('resep1', $pengkajian->resep1 ?? '') }}">
                                        <input type="time" name="jam_pemberian1" class="form-control"
                                            style="width: 100px !important" placeholder="Jam" id="jam_pemberian1"
                                            value="{{ old('jam_pemberian1', $pengkajian->jam_pemberian1 ?? '') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group mb-3">
                                    <div class="input-group">
                                        <input type="text" name="resep2" class="form-control"
                                            placeholder="TERAPI DAN TINDAKAN 2" id="resep2"
                                            value="{{ old('resep2', $pengkajian->resep2 ?? '') }}">
                                        <input type="time" name="jam_pemberian2" class="form-control"
                                            style="width: 100px !important" placeholder="Jam" id="jam_pemberian2"
                                            value="{{ old('jam_pemberian2', $pengkajian->jam_pemberian2 ?? '') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group mb-3">
                                    <div class="input-group">
                                        <input type="text" name="resep3" class="form-control"
                                            placeholder="TERAPI DAN TINDAKAN 3" id="resep3"
                                            value="{{ old('resep3', $pengkajian->resep3 ?? '') }}">
                                        <input type="time" name="jam_pemberian3" class="form-control"
                                            style="width: 100px !important" placeholder="Jam" id="jam_pemberian3"
                                            value="{{ old('jam_pemberian3', $pengkajian->jam_pemberian3 ?? '') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group mb-3">
                                    <div class="input-group">
                                        <input type="text" name="resep4" class="form-control"
                                            placeholder="TERAPI DAN TINDAKAN 4" id="resep4"
                                            value="{{ old('resep4', $pengkajian->resep4 ?? '') }}">
                                        <input type="time" name="jam_pemberian4" class="form-control"
                                            style="width: 100px !important" placeholder="Jam" id="jam_pemberian4"
                                            value="{{ old('jam_pemberian4', $pengkajian->jam_pemberian4 ?? '') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group mb-3">
                                    <div class="input-group">
                                        <input type="text" name="resep5" class="form-control"
                                            placeholder="TERAPI DAN TINDAKAN 5" id="resep5"
                                            value="{{ old('resep5', $pengkajian->resep5 ?? '') }}">
                                        <input type="time" name="jam_pemberian5" class="form-control"
                                            style="width: 100px !important" placeholder="Jam" id="jam_pemberian5"
                                            value="{{ old('jam_pemberian5', $pengkajian->jam_pemberian5 ?? '') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-group mb-3">
                                    <div class="input-group">
                                        <input type="text" name="resep6" class="form-control"
                                            placeholder="TERAPI DAN TINDAKAN 6" id="resep6"
                                            value="{{ old('resep6', $pengkajian->resep6 ?? '') }}">
                                        <input type="time" name="jam_pemberian6" class="form-control"
                                            style="width: 100px !important" placeholder="Jam" id="jam_pemberian6"
                                            value="{{ old('jam_pemberian6', $pengkajian->jam_pemberian6 ?? '') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group mb-3">
                                    <div class="input-group">
                                        <input type="text" name="resep7" class="form-control"
                                            placeholder="TERAPI DAN TINDAKAN 7" id="resep7"
                                            value="{{ old('resep7', $pengkajian->resep7 ?? '') }}">
                                        <input type="time" name="jam_pemberian7" class="form-control"
                                            style="width: 100px !important" placeholder="Jam" id="jam_pemberian7"
                                            value="{{ old('jam_pemberian7', $pengkajian->jam_pemberian7 ?? '') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group mb-3">
                                    <div class="input-group">
                                        <input type="text" name="resep8" class="form-control"
                                            placeholder="TERAPI DAN TINDAKAN 8" id="resep8"
                                            value="{{ old('resep8', $pengkajian->resep8 ?? '') }}">
                                        <input type="time" name="jam_pemberian8" class="form-control"
                                            style="width: 100px !important" placeholder="Jam" id="jam_pemberian8"
                                            value="{{ old('jam_pemberian8', $pengkajian->jam_pemberian8 ?? '') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group mb-3">
                                    <div class="input-group">
                                        <input type="text" name="resep9" class="form-control"
                                            placeholder="TERAPI DAN TINDAKAN 9" id="resep9"
                                            value="{{ old('resep9', $pengkajian->resep9 ?? '') }}">
                                        <input type="time" name="jam_pemberian9" class="form-control"
                                            style="width: 100px !important" placeholder="Jam" id="jam_pemberian9"
                                            value="{{ old('jam_pemberian9', $pengkajian->jam_pemberian9 ?? '') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group mb-3">
                                    <div class="input-group">
                                        <input type="text" name="resep10" class="form-control"
                                            placeholder="TERAPI DAN TINDAKAN 10" id="resep10"
                                            value="{{ old('resep10', $pengkajian->resep10 ?? '') }}">
                                        <input type="time" name="jam_pemberian10" class="form-control"
                                            style="width: 100px !important" placeholder="Jam" id="jam_pemberian10"
                                            value="{{ old('jam_pemberian10', $pengkajian->jam_pemberian10 ?? '') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3 justify-content-center">
                        <div class="col-md-4 text-center">
                            @include('pages.simrs.erm.partials.signature-many', [
                                'judul' => 'Perawat yang mengirim,',
                                'name_prefix' => 'pengirim',
                                'index' => 1,
                                'signature_model' => $pengkajian?->signature_pengirim,
                            ])
                        </div>
                        <div class="col-md-4 text-center">
                        </div>
                        <div class="col-md-4 text-center">
                            @include('pages.simrs.erm.partials.signature-many', [
                                'judul' => 'Perawat yang menerima,',
                                'name_prefix' => 'penerima',
                                'index' => 2,
                                'signature_model' => $pengkajian?->signature_penerima,
                            ])
                        </div>
                    </div>

                    <header class="text-warning">
                        <h4 class="mt-5 font-weight-bold text-center">
                            DI ISI UNTUK PASIEN YANG KEMBALI KE RUANG
                            SEMULA
                            PASCA
                            TINDAKAN / PROSEDUR
                        </h4>
                    </header>
                    <div class="row mt-3">
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label class="text-primary" for="pasien_kembali">Pasien kembali ke ruang semula
                                    pukul:</label>
                                <div class="input-group">
                                    <div class="input-group">
                                        <input id="pasien_kembali" type="text" name="pasien_kembali"
                                            class="form-control"
                                            value="{{ old('pasien_kembali', $pengkajian->pasien_kembali ?? '') }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text">wib</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label class="text-primary" for="keadaan_umum_after">Keadaan Umum:</label>
                                <input id="keadaan_umum_after" type="text" name="keadaan_umum_after"
                                    class="form-control"
                                    value="{{ old('keadaan_umum_after', $pengkajian->keadaan_umum_after ?? '') }}">
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label class="text-primary" for="td_after">TD:</label>
                                <div class="input-group">
                                    <div class="input-group">
                                        <input id="td_after" type="text" name="td_after" class="form-control"
                                            value="{{ old('td_after', $pengkajian->td_after ?? '') }}">
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
                                <label class="text-primary" for="nd_after">ND:</label>
                                <div class="input-group">
                                    <div class="input-group">
                                        <input id="nd_after" type="text" name="nd_after" class="form-control"
                                            value="{{ old('nd_after', $pengkajian->nd_after ?? '') }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text">x/menit</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label class="text-primary" for="rr_after">RR:</label>
                                <div class="input-group">
                                    <div class="input-group">
                                        <input id="rr_after" type="text" name="rr_after" class="form-control"
                                            value="{{ old('rr_after', $pengkajian->rr_after ?? '') }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text">x/menit</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label class="text-primary" for="sb_after">SB:</label>
                                <div class="input-group">
                                    <div class="input-group">
                                        <input id="sb_after" type="text" name="sb_after" class="form-control"
                                            value="{{ old('sb_after', $pengkajian->sb_after ?? '') }}">
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
                            <label for="rj_tidak_beresiko" class="control-label text-primary mt-3">Resiko Jatuh:</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-radio">
                                            <label class="custom-control custom-radio custom-control-inline">
                                                <input name="rj_after" id="rj_tidak_beresiko" value="Tidak Beresiko"
                                                    type="radio" class="custom-control-input"
                                                    {{ old('rj_after', $pengkajian->rj_after ?? '') == 'Tidak Beresiko' ? 'checked' : '' }}>
                                                <span class="custom-control-label text-primary">Tidak Beresiko</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-radio">
                                            <label class="custom-control custom-radio custom-control-inline">
                                                <input name="rj_after" id="rj_rendah2" value="Rendah" type="radio"
                                                    class="custom-control-input"
                                                    {{ old('rj_after', $pengkajian->rj_after ?? '') == 'Rendah' ? 'checked' : '' }}>
                                                <span class="custom-control-label text-primary">Rendah</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-radio">
                                            <label class="custom-control custom-radio custom-control-inline">
                                                <input name="rj_after" id="rj_tinggi2" value="Tinggi" type="radio"
                                                    class="custom-control-input"
                                                    {{ old('rj_after', $pengkajian->rj_after ?? '') == 'Tinggi' ? 'checked' : '' }}>
                                                <span class="custom-control-label text-primary">Tinggi</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-primary" for="diet_after">Diet:</label>
                                <input id="diet_after" type="text" name="diet_after" class="form-control"
                                    value="{{ old('diet_after', $pengkajian->diet_after ?? '') }}">
                            </div>
                        </div>
                    </div>


                    <div class="row mt-3">
                        <div class="col-md-4 text-center">
                            @include('pages.simrs.erm.partials.signature-many', [
                                'judul' => 'Perawat yang mengirim,',
                                'name_prefix' => 'pengirim_balik',
                                'index' => 3,
                                'signature_model' => $pengkajian?->signature_pengirim_balik,
                            ])
                        </div>
                        <div class="col-md-4 text-center">
                        </div>
                        <div class="col-md-4 text-center">
                            @include('pages.simrs.erm.partials.signature-many', [
                                'judul' => 'Perawat yang menerima,',
                                'name_prefix' => 'penerima_balik',
                                'index' => 4,
                                'signature_model' => $pengkajian?->signature_penerima_balik,
                            ])
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
                                    <div style="width: 33%" class="d-flex justify-content-between">
                                        <button type="button"
                                            class="btn btn-warning waves-effect text-white waves-light save-form d-flex align-items-center"
                                            data-dismiss="modal" data-status="0"
                                            id="sd-transfer-pasien-antar-ruangan">
                                            <span class="mdi mdi-content-save mr-2"></span> Simpan (draft)
                                        </button>
                                        <button type="button"
                                            class="btn btn-primary waves-effect waves-light save-form d-flex align-items-center"
                                            data-dismiss="modal" data-status="1"
                                            id="sf-transfer-pasien-antar-ruangan">
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
    <script script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(document).ready(function() {

            let actionType = '';

            // Saat tombol Save Draft diklik
            $('#sd-transfer-pasien-antar-ruangan').on('click', function() {
                actionType = 'draft';
                submitForm(actionType); // Panggil fungsi submitForm dengan parameter draft
            });

            // Saat tombol Save Final diklik
            $('#sf-transfer-pasien-antar-ruangan').on('click', function() {
                actionType = 'final';
                console.log('clicked');
                submitForm(actionType); // Panggil fungsi submitForm dengan parameter final
            });

            function submitForm(actionType) {
                const form = $('#transfer_pasien_antar_ruangan'); // Ambil form
                const registration_id = "{{ $registration->id }}";
                console.log(registration_id);
                const url =
                    "{{ route('transfer-pasien-antar-ruangan.store') }}" // Ambil URL dari action form
                let formData = form.serialize(); // Ambil data dari form

                // Tambahkan tipe aksi (draft atau final) ke data form
                formData += '&action_type=' + actionType;
                formData += '&registration_id=' + registration_id;

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
                    error: function(xhr, status, error) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            var errorMessages = '';

                            $.each(errors, function(key, value) {
                                errorMessages += value +
                                    '\n';
                            });

                            // $('#modal-tambah-grup-tindakan').modal('hide');
                            showErrorAlert('Terjadi kesalahan:\n' +
                                errorMessages);
                        } else {
                            // $('#modal-tambah-grup-tindakan').modal('hide');
                            showErrorAlert('Terjadi kesalahan: ' + error);
                            console.log(error);
                        }
                    }
                });
            }

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

            $('#toggle-pasien').on('click', function() {
                var target = $('#js-slide-left'); // Mengambil elemen target berdasarkan data-target
                var backdrop = $('.slide-backdrop'); // Mengambil backdrop

                // Toggle kelas untuk menampilkan atau menyembunyikan panel dan backdrop
                target.toggleClass('hide');
                backdrop.toggleClass('show');
            });

            // Close the panel if the backdrop is clicked
            $('.slide-backdrop').on('click', function() {
                $('#js-slide-left').removeClass('slide-on-mobile-left-show');
                $(this).removeClass('show');
            });
        });
    </script>
@endsection
