@extends('pages.simrs.erm.index')
@section('erm')
    @if (isset($registration) || $registration != null)
        {{-- content start --}}
        <div class="tab-content p-3">
            <div class="tab-pane fade show active" id="tab_default-1" role="tabpanel">
                @include('pages.simrs.poliklinik.partials.detail-pasien')
                <hr style="border-color: #868686; margin-top: 50px; margin-bottom: 30px;">
                <header class="text-primary text-center font-weight-bold mb-4">
                    <div id="alert-pengkajian"></div>
                    <h2 class="font-weight-bold">FORMULIR REKONSILIASI OBAT</h4>
                </header>
                <div class="row mt-5">
                    <div class="col-md-12">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="norm">No RM</label>
                                    <input id="norm" name="norm" type="text" placeholder="No RM"
                                        class="form-control">
                                </div>

                                <div class="form-group">
                                    <label class="control-label" for="nama_pasien">Nama</label>
                                    <input id="nama_pasien" name="nama_pasien" type="text" placeholder="Nama Pasien"
                                        class="form-control">
                                </div>

                                <div class="form-group">
                                    <label class="control-label" for="tgl_lahir">Tanggal Lahir</label>
                                    <input id="tgl_lahir" name="tgl_lahir" type="text" placeholder="Tanggal Lahir"
                                        class="form-control">
                                </div>

                                <div class="form-group">
                                    <label class="control-label" for="kelamin">Jenis Kelamin</label>
                                    <input id="kelamin" name="kelamin" type="text" placeholder="Jenis Kelamin"
                                        class="form-control">
                                </div>

                                <div class="form-group">
                                    <label class="control-label" for="asal_pasien">Asal Kedatangan
                                        Pasien</label>
                                    <input id="asal_pasien" name="asal_pasien" type="text"
                                        placeholder="Asal Kedatangan Pasien" class="form-control reset">
                                    <span class="help-block">(Diisi saat admisi)</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="riwayat_alergi">Riwayat Alergi
                                        Obat</label>
                                    <input id="riwayat_alergi" name="riwayat_alergi" type="text"
                                        placeholder="Riwayat Alergi Obat" class="form-control reset">
                                </div>

                                <div class="form-group">
                                    <label class="control-label" for="ruang_rawat1">Ruang Rawat
                                        Sebelumnya</label>
                                    <input id="ruang_rawat1" name="ruang_rawat1" type="text"
                                        placeholder="Ruang Rawat Sebelumnya" class="form-control reset">
                                </div>

                                <div class="form-group">
                                    <label class="control-label" for="ruang_rawat2">Ruang Rawat
                                        Sekarang</label>
                                    <input id="ruang_rawat2" name="ruang_rawat2" type="text"
                                        placeholder="Ruang Rawat Sekarang" class="form-control reset">
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="form-group ">
                            <label class="control-label" for="penggunaan_obat">Penggunaan Obat Sebelum
                                Admisi (Masuk RS Livasya)</label>
                            <div class="checkbox">
                                <label for="penggunaan_obat-0">
                                    <input type="checkbox" name="penggunaan_obat" id="penggunaan_obat-0" value="1"
                                        class="a">
                                    Ya, dengan rincian sebagai berikut
                                </label>
                            </div>
                            <div class="checkbox">
                                <label for="penggunaan_obat-1">
                                    <input type="checkbox" name="penggunaan_obat" id="penggunaan_obat-1" value="2"
                                        class="a">
                                    Tidak menggunakan obat sebelum Admisi
                                </label>
                            </div>
                        </div>
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Obat</th>
                                    <th>Dosis</th>
                                    <th>Cara Pemberian</th>
                                    <th>Waktu Pemberian</th>
                                    <th>Lama Pemberian</th>
                                    <th>Waktu Rekonsiliasi</th>
                                    <th>Tindak Lanjut Oleh DPJP</th>
                                    <th>Perubahan Aturan Pakai</th>
                                    <th>Aturan Pakai Obat Pulang</th>
                                </tr>
                            </thead>
                            <tbody class="form-group">
                                <tr>
                                    <td>1</td>
                                    <td><input id="kolom1" name="kolom1" type="text" placeholder="Nama Obat"
                                            class="form-control reset"></td>
                                    <td><input id="kolom1a" name="kolom1a" type="text" placeholder="Dosis"
                                            class="form-control reset"></td>
                                    <td><input id="kolom1b" name="kolom1b" type="text" placeholder="Cara Pemberian"
                                            class="form-control reset"></td>
                                    <td><input id="kolom1c" name="kolom1c" type="text"
                                            placeholder="Waktu Pemberian" class="form-control reset"></td>
                                    <td><input id="kolom1d" name="kolom1d" type="text" placeholder="Lama Pemberian"
                                            class="form-control reset"></td>
                                    <td>
                                        <div class="checkbox">
                                            <label for="kolom1e-0">
                                                <input type="checkbox" name="kolom1e" id="kolom1e-0" value="1"
                                                    class="a">
                                                Admisi
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label for="kolom1f-1">
                                                <input type="checkbox" name="kolom1f" id="kolom1f-1" value="2"
                                                    class="a">
                                                Discharge
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label for="kolom1g-1">
                                                <input type="checkbox" name="kolom1g" id="kolom1g-1" value="3"
                                                    class="a">
                                                Transfer
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="checkbox">
                                            <label for="kolom1h-0">
                                                <input type="checkbox" name="kolom1h" id="kolom1h-0" value="1"
                                                    class="a">
                                                Lanjut aturan pakai sama
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label for="kolom1i-1">
                                                <input type="checkbox" name="kolom1i" id="kolom1i-1" value="2"
                                                    class="a">
                                                Lanjut aturan pakai berubah
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label for="kolom1j-2">
                                                <input type="checkbox" name="kolom1j" id="kolom1j-2" value="3"
                                                    class="a">
                                                STOP
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label for="kolom1k-3">
                                                <input type="checkbox" name="kolom1k" id="kolom1k-3" value="4"
                                                    class="a">
                                                Obat Baru
                                            </label>
                                        </div>

                                    </td>

                                    <td><input id="kolom1l" name="kolom1l" type="text"
                                            placeholder="Perubahan Aturan Pakai" class="form-control reset"></td>
                                    <td><input id="kolom1m" name="kolom1m" type="text"
                                            placeholder="Aturan Pakai Obat Pulang" class="form-control reset">
                                    </td>
                                </tr>

                                <tr>
                                    <td>2</td>
                                    <td><input id="kolom2" name="kolom2" type="text" placeholder="Nama Obat"
                                            class="form-control reset"></td>
                                    <td><input id="kolom2a" name="kolom2a" type="text" placeholder="Dosis"
                                            class="form-control reset"></td>
                                    <td><input id="kolom2b" name="kolom2b" type="text" placeholder="Cara Pemberian"
                                            class="form-control reset"></td>
                                    <td><input id="kolom2c" name="kolom2c" type="text"
                                            placeholder="Waktu Pemberian" class="form-control reset"></td>
                                    <td><input id="kolom2d" name="kolom2d" type="text" placeholder="Lama Pemberian"
                                            class="form-control reset"></td>
                                    <td>
                                        <div class="checkbox">
                                            <label for="kolom2e-0">
                                                <input type="checkbox" name="kolom2e" id="kolom2e-0" value="1"
                                                    class="a">
                                                Admisi
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label for="kolom2f-1">
                                                <input type="checkbox" name="kolom2f" id="kolom2f-1" value="2"
                                                    class="a">
                                                Discharge
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label for="kolom2g-1">
                                                <input type="checkbox" name="kolom2g" id="kolom2g-1" value="3"
                                                    class="a">
                                                Transfer
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="checkbox">
                                            <label for="kolom2h-0">
                                                <input type="checkbox" name="kolom2h" id="kolom2h-0" value="1"
                                                    class="a">
                                                Lanjut aturan pakai sama
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label for="kolom2i-1">
                                                <input type="checkbox" name="kolom2i" id="kolom2i-1" value="2"
                                                    class="a">
                                                Lanjut aturan pakai berubah
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label for="kolom2j-2">
                                                <input type="checkbox" name="kolom2j" id="kolom2j-2" value="3"
                                                    class="a">
                                                STOP
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label for="kolom2k-3">
                                                <input type="checkbox" name="kolom2k" id="kolom2k-3" value="4"
                                                    class="a">
                                                Obat Baru
                                            </label>
                                        </div>

                                    </td>

                                    <td><input id="kolom2l" name="kolom2l" type="text"
                                            placeholder="Perubahan Aturan Pakai" class="form-control reset"></td>
                                    <td><input id="kolom2m" name="kolom2m" type="text"
                                            placeholder="Aturan Pakai Obat Pulang" class="form-control reset">
                                    </td>
                                </tr>

                                <tr>
                                    <td>3</td>
                                    <td><input id="kolom3" name="kolom3" type="text" placeholder="Nama Obat"
                                            class="form-control reset"></td>
                                    <td><input id="kolom3a" name="kolom3a" type="text" placeholder="Dosis"
                                            class="form-control reset"></td>
                                    <td><input id="kolom3b" name="kolom3b" type="text" placeholder="Cara Pemberian"
                                            class="form-control reset"></td>
                                    <td><input id="kolom3c" name="kolom3c" type="text"
                                            placeholder="Waktu Pemberian" class="form-control reset"></td>
                                    <td><input id="kolom3d" name="kolom3d" type="text" placeholder="Lama Pemberian"
                                            class="form-control reset"></td>
                                    <td>
                                        <div class="checkbox">
                                            <label for="kolom3e-0">
                                                <input type="checkbox" name="kolom3e" id="kolom3e-0" value="1"
                                                    class="a">
                                                Admisi
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label for="kolom3f-1">
                                                <input type="checkbox" name="kolom3f" id="kolom3f-1" value="2"
                                                    class="a">
                                                Discharge
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label for="kolom3g-1">
                                                <input type="checkbox" name="kolom3g" id="kolom3g-1" value="3"
                                                    class="a">
                                                Transfer
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="checkbox">
                                            <label for="kolom3h-0">
                                                <input type="checkbox" name="kolom3h" id="kolom3h-0" value="1"
                                                    class="a">
                                                Lanjut aturan pakai sama
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label for="kolom3i-1">
                                                <input type="checkbox" name="kolom3i" id="kolom3i-1" value="2"
                                                    class="a">
                                                Lanjut aturan pakai berubah
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label for="kolom3j-2">
                                                <input type="checkbox" name="kolom3j" id="kolom3j-2" value="3"
                                                    class="a">
                                                STOP
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label for="kolom3k-3">
                                                <input type="checkbox" name="kolom3k" id="kolom3k-3" value="4"
                                                    class="a">
                                                Obat Baru
                                            </label>
                                        </div>

                                    </td>

                                    <td><input id="kolom3l" name="kolom3l" type="text"
                                            placeholder="Perubahan Aturan Pakai" class="form-control reset"></td>
                                    <td><input id="kolom3m" name="kolom3m" type="text"
                                            placeholder="Aturan Pakai Obat Pulang" class="form-control reset">
                                    </td>
                                </tr>

                                <tr>
                                    <td>4</td>
                                    <td><input id="kolom4" name="kolom4" type="text" placeholder="Nama Obat"
                                            class="form-control reset"></td>
                                    <td><input id="kolom4a" name="kolom4a" type="text" placeholder="Dosis"
                                            class="form-control reset"></td>
                                    <td><input id="kolom4b" name="kolom4b" type="text" placeholder="Cara Pemberian"
                                            class="form-control reset"></td>
                                    <td><input id="kolom4c" name="kolom4c" type="text"
                                            placeholder="Waktu Pemberian" class="form-control reset"></td>
                                    <td><input id="kolom4d" name="kolom4d" type="text" placeholder="Lama Pemberian"
                                            class="form-control reset"></td>
                                    <td>
                                        <div class="checkbox">
                                            <label for="kolom4e-0">
                                                <input type="checkbox" name="kolom4e" id="kolom4e-0" value="1"
                                                    class="a">
                                                Admisi
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label for="kolom4f-1">
                                                <input type="checkbox" name="kolom4f" id="kolom4f-1" value="2"
                                                    class="a">
                                                Discharge
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label for="kolom4g-1">
                                                <input type="checkbox" name="kolom4g" id="kolom4g-1" value="3"
                                                    class="a">
                                                Transfer
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="checkbox">
                                            <label for="kolom4h-0">
                                                <input type="checkbox" name="kolom4h" id="kolom4h-0" value="1"
                                                    class="a">
                                                Lanjut aturan pakai sama
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label for="kolom4i-1">
                                                <input type="checkbox" name="kolom4i" id="kolom4i-1" value="2"
                                                    class="a">
                                                Lanjut aturan pakai berubah
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label for="kolom4j-2">
                                                <input type="checkbox" name="kolom4j" id="kolom4j-2" value="3"
                                                    class="a">
                                                STOP
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label for="kolom4k-3">
                                                <input type="checkbox" name="kolom4k" id="kolom4k-3" value="4"
                                                    class="a">
                                                Obat Baru
                                            </label>
                                        </div>

                                    </td>

                                    <td><input id="kolom4l" name="kolom4l" type="text"
                                            placeholder="Perubahan Aturan Pakai" class="form-control reset"></td>
                                    <td><input id="kolom4m" name="kolom4m" type="text"
                                            placeholder="Aturan Pakai Obat Pulang" class="form-control reset">
                                    </td>
                                </tr>

                                <tr>
                                    <td>5</td>
                                    <td><input id="kolom5" name="kolom5" type="text" placeholder="Nama Obat"
                                            class="form-control reset"></td>
                                    <td><input id="kolom5a" name="kolom5a" type="text" placeholder="Dosis"
                                            class="form-control reset"></td>
                                    <td><input id="kolom5b" name="kolom5b" type="text" placeholder="Cara Pemberian"
                                            class="form-control reset"></td>
                                    <td><input id="kolom5c" name="kolom5c" type="text"
                                            placeholder="Waktu Pemberian" class="form-control reset"></td>
                                    <td><input id="kolom5d" name="kolom5d" type="text" placeholder="Lama Pemberian"
                                            class="form-control reset"></td>
                                    <td>
                                        <div class="checkbox">
                                            <label for="kolom5e-0">
                                                <input type="checkbox" name="kolom5e" id="kolom5e-0" value="1"
                                                    class="a">
                                                Admisi
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label for="kolom5f-1">
                                                <input type="checkbox" name="kolom5f" id="kolom5f-1" value="2"
                                                    class="a">
                                                Discharge
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label for="kolom5g-1">
                                                <input type="checkbox" name="kolom5g" id="kolom5g-1" value="3"
                                                    class="a">
                                                Transfer
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="checkbox">
                                            <label for="kolom5h-0">
                                                <input type="checkbox" name="kolom5h" id="kolom5h-0" value="1"
                                                    class="a">
                                                Lanjut aturan pakai sama
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label for="kolom5i-1">
                                                <input type="checkbox" name="kolom5i" id="kolom5i-1" value="2"
                                                    class="a">
                                                Lanjut aturan pakai berubah
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label for="kolom5j-2">
                                                <input type="checkbox" name="kolom5j" id="kolom5j-2" value="3"
                                                    class="a">
                                                STOP
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label for="kolom5k-3">
                                                <input type="checkbox" name="kolom5k" id="kolom5k-3" value="4"
                                                    class="a">
                                                Obat Baru
                                            </label>
                                        </div>

                                    </td>

                                    <td><input id="kolom5l" name="kolom5l" type="text"
                                            placeholder="Perubahan Aturan Pakai" class="form-control reset"></td>
                                    <td><input id="kolom5m" name="kolom5m" type="text"
                                            placeholder="Aturan Pakai Obat Pulang" class="form-control reset">
                                    </td>
                                </tr>

                                <!-- <tr>
                                                            <td>6</td>
                                                            <td><input id="kolom6" name="kolom6" type="text" placeholder="Nama Obat" class="form-control reset"></td>
                                                            <td><input id="kolom6a" name="kolom6a" type="text" placeholder="Dosis" class="form-control reset"></td>
                                                            <td><input id="kolom6b" name="kolom6b" type="text" placeholder="Cara Pemberian" class="form-control reset"></td>
                                                            <td><input id="kolom6c" name="kolom6c" type="text" placeholder="Waktu Pemberian" class="form-control reset"></td>
                                                            <td><input id="kolom6d" name="kolom6d" type="text" placeholder="Lama Pemberian" class="form-control reset"></td>
                                                            <td>
                                                                <div class="checkbox">
                                                                <label for="kolom6e-0">
                                                                    <input type="checkbox" name="kolom6e" id="kolom6e-0" value="1" class="a">
                                                                    Admisi
                                                                </label>
                                                                </div>
                                                                <div class="checkbox">
                                                                <label for="kolom6f-1">
                                                                    <input type="checkbox" name="kolom6f" id="kolom6f-1" value="2" class="a">
                                                                    Discharge
                                                                </label>
                                                                </div>
                                                                <div class="checkbox">
                                                                <label for="kolom6g-1">
                                                                    <input type="checkbox" name="kolom6g" id="kolom6g-1" value="3" class="a">
                                                                    Transfer
                                                                </label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="checkbox">
                                                                <label for="kolom6h-0">
                                                                    <input type="checkbox" name="kolom6h" id="kolom6h-0" value="1" class="a">
                                                                    Lanjut aturan pakai sama
                                                                  </label>
                                                                </div>
                                                                <div class="checkbox">
                                                                <label for="kolom6i-1">
                                                                    <input type="checkbox" name="kolom6i" id="kolom6i-1" value="2" class="a">
                                                                    Lanjut aturan pakai berubah
                                                                </label>
                                                                </div>
                                                                <div class="checkbox">
                                                                <label for="kolom6j-2">
                                                                    <input type="checkbox" name="kolom6j" id="kolom6j-2" value="3" class="a">
                                                                    STOP
                                                                </label>
                                                                </div>
                                                                <div class="checkbox">
                                                                <label for="kolom6k-3">
                                                                    <input type="checkbox" name="kolom6k" id="kolom6k-3" value="4" class="a">
                                                                    Obat Baru
                                                                </label>
                                                                </div>
                                                                
                                                            </td>
                                                            
                                                            <td><input id="kolom6l" name="kolom6l" type="text" placeholder="Asal Kedatangan Pasien" class="form-control reset"></td>
                                                            <td><input id="kolom6m" name="kolom6m" type="text" placeholder="Asal Kedatangan Pasien" class="form-control reset"></td>
                                                          </tr> -->

                            </tbody>
                        </table>

                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="col-md-1">Pelaksanaan</th>
                                    <th class="col-md-1">Admisi</th>
                                    <th class="col-md-1">Transfer I</th>
                                    <th class="col-md-1">Transfer II</th>
                                    <th class="col-md-1">Transfer III</th>
                                    <th class="col-md-1">Transfer IV</th>
                                    <th class="col-md-1">Transfer V</th>
                                    <th class="col-md-1">Discharge</th>
                                </tr>
                            </thead>
                            <tbody class="form-group">
                                <tr>
                                    <td><input id="kolom7" name="kolom7" type="text"
                                            class="form-control datepicker reset" data-inputmask-alias="datetime"
                                            data-inputmask-inputformat="dd-mm-yyyy" im-insert="false">
                                    </td>
                                    <td>
                                        <select name="kolom7a" size="1" id="kolom7a"
                                            class="sel2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                            <option></option>
                                            <option value="862">Apt. Agnes Yuliasari S.Farm</option>
                                            <option value="1083">Apt. Ditha Octaviana, S.Farm</option>
                                            <option value="963">apt. Dwiman Nugraha Sutanto, S.farm
                                            </option>
                                            <option value="1064">apt. Nisa Fitri Rahmadiani, S. Farm.
                                            </option>
                                            <option value="838">apt. Nurani Eka Gumilang, S.Farm
                                            </option>
                                            <option value="26">Apt. Lia Vallini, S.Farm.</option>
                                        </select><span class="select2 select2-container select2-container--default"
                                            dir="ltr" style="width: 254px;"><span class="selection"><span
                                                    class="select2-selection select2-selection--single" role="combobox"
                                                    aria-haspopup="true" aria-expanded="false" tabindex="0"
                                                    aria-labelledby="select2-kolom7a-container"><span
                                                        class="select2-selection__rendered" id="select2-kolom7a-container"
                                                        title=""></span><span class="select2-selection__arrow"
                                                        role="presentation"><b
                                                            role="presentation"></b></span></span></span><span
                                                class="dropdown-wrapper" aria-hidden="true"></span></span>

                                    </td>
                                    <td>
                                        <select name="kolom7b" size="1" id="kolom7b"
                                            class="sel2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                            <option></option>
                                            <option value="862">Apt. Agnes Yuliasari S.Farm</option>
                                            <option value="1083">Apt. Ditha Octaviana, S.Farm</option>
                                            <option value="963">apt. Dwiman Nugraha Sutanto, S.farm
                                            </option>
                                            <option value="1064">apt. Nisa Fitri Rahmadiani, S. Farm.
                                            </option>
                                            <option value="838">apt. Nurani Eka Gumilang, S.Farm
                                            </option>
                                            <option value="26">Apt. Lia Vallini, S.Farm.</option>
                                        </select><span class="select2 select2-container select2-container--default"
                                            dir="ltr" style="width: 254px;"><span class="selection"><span
                                                    class="select2-selection select2-selection--single" role="combobox"
                                                    aria-haspopup="true" aria-expanded="false" tabindex="0"
                                                    aria-labelledby="select2-kolom7b-container"><span
                                                        class="select2-selection__rendered" id="select2-kolom7b-container"
                                                        title=""></span><span class="select2-selection__arrow"
                                                        role="presentation"><b
                                                            role="presentation"></b></span></span></span><span
                                                class="dropdown-wrapper" aria-hidden="true"></span></span>

                                    </td>
                                    <td>
                                        <select name="kolom7c" size="1" id="kolom7c"
                                            class="sel2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                            <option></option>
                                            <option value="862">Apt. Agnes Yuliasari S.Farm</option>
                                            <option value="1083">Apt. Ditha Octaviana, S.Farm</option>
                                            <option value="963">apt. Dwiman Nugraha Sutanto, S.farm
                                            </option>
                                            <option value="1064">apt. Nisa Fitri Rahmadiani, S. Farm.
                                            </option>
                                            <option value="838">apt. Nurani Eka Gumilang, S.Farm
                                            </option>
                                            <option value="26">Apt. Lia Vallini, S.Farm.</option>
                                        </select><span class="select2 select2-container select2-container--default"
                                            dir="ltr" style="width: 254px;"><span class="selection"><span
                                                    class="select2-selection select2-selection--single" role="combobox"
                                                    aria-haspopup="true" aria-expanded="false" tabindex="0"
                                                    aria-labelledby="select2-kolom7c-container"><span
                                                        class="select2-selection__rendered" id="select2-kolom7c-container"
                                                        title=""></span><span class="select2-selection__arrow"
                                                        role="presentation"><b
                                                            role="presentation"></b></span></span></span><span
                                                class="dropdown-wrapper" aria-hidden="true"></span></span>

                                    </td>
                                    <td>
                                        <select name="kolom7d" size="1" id="kolom7d"
                                            class="sel2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                            <option></option>
                                            <option value="862">Apt. Agnes Yuliasari S.Farm</option>
                                            <option value="1083">Apt. Ditha Octaviana, S.Farm</option>
                                            <option value="963">apt. Dwiman Nugraha Sutanto, S.farm
                                            </option>
                                            <option value="1064">apt. Nisa Fitri Rahmadiani, S. Farm.
                                            </option>
                                            <option value="838">apt. Nurani Eka Gumilang, S.Farm
                                            </option>
                                            <option value="26">Apt. Lia Vallini, S.Farm.</option>
                                        </select><span class="select2 select2-container select2-container--default"
                                            dir="ltr" style="width: 254px;"><span class="selection"><span
                                                    class="select2-selection select2-selection--single" role="combobox"
                                                    aria-haspopup="true" aria-expanded="false" tabindex="0"
                                                    aria-labelledby="select2-kolom7d-container"><span
                                                        class="select2-selection__rendered" id="select2-kolom7d-container"
                                                        title=""></span><span class="select2-selection__arrow"
                                                        role="presentation"><b
                                                            role="presentation"></b></span></span></span><span
                                                class="dropdown-wrapper" aria-hidden="true"></span></span>

                                    </td>
                                    <td>
                                        <select name="kolom7e" size="1" id="kolom7e"
                                            class="sel2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                            <option></option>
                                            <option value="862">Apt. Agnes Yuliasari S.Farm</option>
                                            <option value="1083">Apt. Ditha Octaviana, S.Farm</option>
                                            <option value="963">apt. Dwiman Nugraha Sutanto, S.farm
                                            </option>
                                            <option value="1064">apt. Nisa Fitri Rahmadiani, S. Farm.
                                            </option>
                                            <option value="838">apt. Nurani Eka Gumilang, S.Farm
                                            </option>
                                            <option value="26">Apt. Lia Vallini, S.Farm.</option>
                                        </select><span class="select2 select2-container select2-container--default"
                                            dir="ltr" style="width: 254px;"><span class="selection"><span
                                                    class="select2-selection select2-selection--single" role="combobox"
                                                    aria-haspopup="true" aria-expanded="false" tabindex="0"
                                                    aria-labelledby="select2-kolom7e-container"><span
                                                        class="select2-selection__rendered" id="select2-kolom7e-container"
                                                        title=""></span><span class="select2-selection__arrow"
                                                        role="presentation"><b
                                                            role="presentation"></b></span></span></span><span
                                                class="dropdown-wrapper" aria-hidden="true"></span></span>

                                    </td>
                                    <td>
                                        <select name="kolom7f" size="1" id="kolom7f"
                                            class="sel2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                            <option></option>
                                            <option value="862">Apt. Agnes Yuliasari S.Farm</option>
                                            <option value="1083">Apt. Ditha Octaviana, S.Farm</option>
                                            <option value="963">apt. Dwiman Nugraha Sutanto, S.farm
                                            </option>
                                            <option value="1064">apt. Nisa Fitri Rahmadiani, S. Farm.
                                            </option>
                                            <option value="838">apt. Nurani Eka Gumilang, S.Farm
                                            </option>
                                            <option value="26">Apt. Lia Vallini, S.Farm.</option>
                                        </select><span class="select2 select2-container select2-container--default"
                                            dir="ltr" style="width: 254px;"><span class="selection"><span
                                                    class="select2-selection select2-selection--single" role="combobox"
                                                    aria-haspopup="true" aria-expanded="false" tabindex="0"
                                                    aria-labelledby="select2-kolom7f-container"><span
                                                        class="select2-selection__rendered" id="select2-kolom7f-container"
                                                        title=""></span><span class="select2-selection__arrow"
                                                        role="presentation"><b
                                                            role="presentation"></b></span></span></span><span
                                                class="dropdown-wrapper" aria-hidden="true"></span></span>

                                    </td>
                                    <td>
                                        <select name="kolom7g" size="1" id="kolom7g"
                                            class="sel2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                            <option></option>
                                            <option value="862">Apt. Agnes Yuliasari S.Farm</option>
                                            <option value="1083">Apt. Ditha Octaviana, S.Farm</option>
                                            <option value="963">apt. Dwiman Nugraha Sutanto, S.farm
                                            </option>
                                            <option value="1064">apt. Nisa Fitri Rahmadiani, S. Farm.
                                            </option>
                                            <option value="838">apt. Nurani Eka Gumilang, S.Farm
                                            </option>
                                            <option value="26">Apt. Lia Vallini, S.Farm.</option>
                                        </select><span class="select2 select2-container select2-container--default"
                                            dir="ltr" style="width: 254px;"><span class="selection"><span
                                                    class="select2-selection select2-selection--single" role="combobox"
                                                    aria-haspopup="true" aria-expanded="false" tabindex="0"
                                                    aria-labelledby="select2-kolom7g-container"><span
                                                        class="select2-selection__rendered" id="select2-kolom7g-container"
                                                        title=""></span><span class="select2-selection__arrow"
                                                        role="presentation"><b
                                                            role="presentation"></b></span></span></span><span
                                                class="dropdown-wrapper" aria-hidden="true"></span></span>

                                    </td>
                                </tr>
                                <tr>
                                    <td><input id="kolom8" name="kolom8" type="text"
                                            class="form-control datepicker reset" data-inputmask-alias="datetime"
                                            data-inputmask-inputformat="dd-mm-yyyy" im-insert="false">
                                    </td>
                                    <td>
                                        <select name="kolom8a" size="1" id="kolom8a"
                                            class="sel2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                            <option></option>
                                            <option value="862">Apt. Agnes Yuliasari S.Farm</option>
                                            <option value="1083">Apt. Ditha Octaviana, S.Farm</option>
                                            <option value="963">apt. Dwiman Nugraha Sutanto, S.farm
                                            </option>
                                            <option value="1064">apt. Nisa Fitri Rahmadiani, S. Farm.
                                            </option>
                                            <option value="838">apt. Nurani Eka Gumilang, S.Farm
                                            </option>
                                            <option value="26">Apt. Lia Vallini, S.Farm.</option>
                                        </select><span class="select2 select2-container select2-container--default"
                                            dir="ltr" style="width: 254px;"><span class="selection"><span
                                                    class="select2-selection select2-selection--single" role="combobox"
                                                    aria-haspopup="true" aria-expanded="false" tabindex="0"
                                                    aria-labelledby="select2-kolom8a-container"><span
                                                        class="select2-selection__rendered" id="select2-kolom8a-container"
                                                        title=""></span><span class="select2-selection__arrow"
                                                        role="presentation"><b
                                                            role="presentation"></b></span></span></span><span
                                                class="dropdown-wrapper" aria-hidden="true"></span></span>

                                    </td>
                                    <td>
                                        <select name="kolom8b" size="1" id="kolom8b"
                                            class="sel2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                            <option></option>
                                            <option value="862">Apt. Agnes Yuliasari S.Farm</option>
                                            <option value="1083">Apt. Ditha Octaviana, S.Farm</option>
                                            <option value="963">apt. Dwiman Nugraha Sutanto, S.farm
                                            </option>
                                            <option value="1064">apt. Nisa Fitri Rahmadiani, S. Farm.
                                            </option>
                                            <option value="838">apt. Nurani Eka Gumilang, S.Farm
                                            </option>
                                            <option value="26">Apt. Lia Vallini, S.Farm.</option>
                                        </select><span class="select2 select2-container select2-container--default"
                                            dir="ltr" style="width: 254px;"><span class="selection"><span
                                                    class="select2-selection select2-selection--single" role="combobox"
                                                    aria-haspopup="true" aria-expanded="false" tabindex="0"
                                                    aria-labelledby="select2-kolom8b-container"><span
                                                        class="select2-selection__rendered" id="select2-kolom8b-container"
                                                        title=""></span><span class="select2-selection__arrow"
                                                        role="presentation"><b
                                                            role="presentation"></b></span></span></span><span
                                                class="dropdown-wrapper" aria-hidden="true"></span></span>

                                    </td>
                                    <td>
                                        <select name="kolom8c" size="1" id="kolom8c"
                                            class="sel2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                            <option></option>
                                            <option value="862">Apt. Agnes Yuliasari S.Farm</option>
                                            <option value="1083">Apt. Ditha Octaviana, S.Farm</option>
                                            <option value="963">apt. Dwiman Nugraha Sutanto, S.farm
                                            </option>
                                            <option value="1064">apt. Nisa Fitri Rahmadiani, S. Farm.
                                            </option>
                                            <option value="838">apt. Nurani Eka Gumilang, S.Farm
                                            </option>
                                            <option value="26">Apt. Lia Vallini, S.Farm.</option>
                                        </select><span class="select2 select2-container select2-container--default"
                                            dir="ltr" style="width: 254px;"><span class="selection"><span
                                                    class="select2-selection select2-selection--single" role="combobox"
                                                    aria-haspopup="true" aria-expanded="false" tabindex="0"
                                                    aria-labelledby="select2-kolom8c-container"><span
                                                        class="select2-selection__rendered" id="select2-kolom8c-container"
                                                        title=""></span><span class="select2-selection__arrow"
                                                        role="presentation"><b
                                                            role="presentation"></b></span></span></span><span
                                                class="dropdown-wrapper" aria-hidden="true"></span></span>

                                    </td>
                                    <td>
                                        <select name="kolom8d" size="1" id="kolom8d"
                                            class="sel2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                            <option></option>
                                            <option value="862">Apt. Agnes Yuliasari S.Farm</option>
                                            <option value="1083">Apt. Ditha Octaviana, S.Farm</option>
                                            <option value="963">apt. Dwiman Nugraha Sutanto, S.farm
                                            </option>
                                            <option value="1064">apt. Nisa Fitri Rahmadiani, S. Farm.
                                            </option>
                                            <option value="838">apt. Nurani Eka Gumilang, S.Farm
                                            </option>
                                            <option value="26">Apt. Lia Vallini, S.Farm.</option>
                                        </select><span class="select2 select2-container select2-container--default"
                                            dir="ltr" style="width: 254px;"><span class="selection"><span
                                                    class="select2-selection select2-selection--single" role="combobox"
                                                    aria-haspopup="true" aria-expanded="false" tabindex="0"
                                                    aria-labelledby="select2-kolom8d-container"><span
                                                        class="select2-selection__rendered" id="select2-kolom8d-container"
                                                        title=""></span><span class="select2-selection__arrow"
                                                        role="presentation"><b
                                                            role="presentation"></b></span></span></span><span
                                                class="dropdown-wrapper" aria-hidden="true"></span></span>

                                    </td>
                                    <td>
                                        <select name="kolom8e" size="1" id="kolom8e"
                                            class="sel2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                            <option></option>
                                            <option value="862">Apt. Agnes Yuliasari S.Farm</option>
                                            <option value="1083">Apt. Ditha Octaviana, S.Farm</option>
                                            <option value="963">apt. Dwiman Nugraha Sutanto, S.farm
                                            </option>
                                            <option value="1064">apt. Nisa Fitri Rahmadiani, S. Farm.
                                            </option>
                                            <option value="838">apt. Nurani Eka Gumilang, S.Farm
                                            </option>
                                            <option value="26">Apt. Lia Vallini, S.Farm.</option>
                                        </select><span class="select2 select2-container select2-container--default"
                                            dir="ltr" style="width: 254px;"><span class="selection"><span
                                                    class="select2-selection select2-selection--single" role="combobox"
                                                    aria-haspopup="true" aria-expanded="false" tabindex="0"
                                                    aria-labelledby="select2-kolom8e-container"><span
                                                        class="select2-selection__rendered" id="select2-kolom8e-container"
                                                        title=""></span><span class="select2-selection__arrow"
                                                        role="presentation"><b
                                                            role="presentation"></b></span></span></span><span
                                                class="dropdown-wrapper" aria-hidden="true"></span></span>

                                    </td>
                                    <td>
                                        <select name="kolom8f" size="1" id="kolom8f"
                                            class="sel2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                            <option></option>
                                            <option value="862">Apt. Agnes Yuliasari S.Farm</option>
                                            <option value="1083">Apt. Ditha Octaviana, S.Farm</option>
                                            <option value="963">apt. Dwiman Nugraha Sutanto, S.farm
                                            </option>
                                            <option value="1064">apt. Nisa Fitri Rahmadiani, S. Farm.
                                            </option>
                                            <option value="838">apt. Nurani Eka Gumilang, S.Farm
                                            </option>
                                            <option value="26">Apt. Lia Vallini, S.Farm.</option>
                                        </select><span class="select2 select2-container select2-container--default"
                                            dir="ltr" style="width: 254px;"><span class="selection"><span
                                                    class="select2-selection select2-selection--single" role="combobox"
                                                    aria-haspopup="true" aria-expanded="false" tabindex="0"
                                                    aria-labelledby="select2-kolom8f-container"><span
                                                        class="select2-selection__rendered"
                                                        id="select2-kolom8f-container" title=""></span><span
                                                        class="select2-selection__arrow" role="presentation"><b
                                                            role="presentation"></b></span></span></span><span
                                                class="dropdown-wrapper" aria-hidden="true"></span></span>

                                    </td>
                                    <td>
                                        <select name="kolom8g" size="1" id="kolom8g"
                                            class="sel2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                            <option></option>
                                            <option value="862">Apt. Agnes Yuliasari S.Farm</option>
                                            <option value="1083">Apt. Ditha Octaviana, S.Farm</option>
                                            <option value="963">apt. Dwiman Nugraha Sutanto, S.farm
                                            </option>
                                            <option value="1064">apt. Nisa Fitri Rahmadiani, S. Farm.
                                            </option>
                                            <option value="838">apt. Nurani Eka Gumilang, S.Farm
                                            </option>
                                            <option value="26">Apt. Lia Vallini, S.Farm.</option>
                                        </select><span class="select2 select2-container select2-container--default"
                                            dir="ltr" style="width: 254px;"><span class="selection"><span
                                                    class="select2-selection select2-selection--single" role="combobox"
                                                    aria-haspopup="true" aria-expanded="false" tabindex="0"
                                                    aria-labelledby="select2-kolom8g-container"><span
                                                        class="select2-selection__rendered"
                                                        id="select2-kolom8g-container" title=""></span><span
                                                        class="select2-selection__arrow" role="presentation"><b
                                                            role="presentation"></b></span></span></span><span
                                                class="dropdown-wrapper" aria-hidden="true"></span></span>

                                    </td>
                                </tr>
                                <tr>
                                    <td><input id="kolom9" name="kolom9" type="text"
                                            class="form-control datepicker reset" data-inputmask-alias="datetime"
                                            data-inputmask-inputformat="dd-mm-yyyy" im-insert="false">
                                    </td>
                                    <td>
                                        <select name="kolom9a" size="1" id="kolom9a"
                                            class="sel2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                            <option></option>
                                            <option value="862">Apt. Agnes Yuliasari S.Farm</option>
                                            <option value="1083">Apt. Ditha Octaviana, S.Farm</option>
                                            <option value="963">apt. Dwiman Nugraha Sutanto, S.farm
                                            </option>
                                            <option value="1064">apt. Nisa Fitri Rahmadiani, S. Farm.
                                            </option>
                                            <option value="838">apt. Nurani Eka Gumilang, S.Farm
                                            </option>
                                            <option value="26">Apt. Lia Vallini, S.Farm.</option>
                                        </select><span class="select2 select2-container select2-container--default"
                                            dir="ltr" style="width: 254px;"><span class="selection"><span
                                                    class="select2-selection select2-selection--single" role="combobox"
                                                    aria-haspopup="true" aria-expanded="false" tabindex="0"
                                                    aria-labelledby="select2-kolom9a-container"><span
                                                        class="select2-selection__rendered"
                                                        id="select2-kolom9a-container" title=""></span><span
                                                        class="select2-selection__arrow" role="presentation"><b
                                                            role="presentation"></b></span></span></span><span
                                                class="dropdown-wrapper" aria-hidden="true"></span></span>

                                    </td>
                                    <td>
                                        <select name="kolom9b" size="1" id="kolom9b"
                                            class="sel2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                            <option></option>
                                            <option value="862">Apt. Agnes Yuliasari S.Farm</option>
                                            <option value="1083">Apt. Ditha Octaviana, S.Farm</option>
                                            <option value="963">apt. Dwiman Nugraha Sutanto, S.farm
                                            </option>
                                            <option value="1064">apt. Nisa Fitri Rahmadiani, S. Farm.
                                            </option>
                                            <option value="838">apt. Nurani Eka Gumilang, S.Farm
                                            </option>
                                            <option value="26">Apt. Lia Vallini, S.Farm.</option>
                                        </select><span class="select2 select2-container select2-container--default"
                                            dir="ltr" style="width: 254px;"><span class="selection"><span
                                                    class="select2-selection select2-selection--single" role="combobox"
                                                    aria-haspopup="true" aria-expanded="false" tabindex="0"
                                                    aria-labelledby="select2-kolom9b-container"><span
                                                        class="select2-selection__rendered"
                                                        id="select2-kolom9b-container" title=""></span><span
                                                        class="select2-selection__arrow" role="presentation"><b
                                                            role="presentation"></b></span></span></span><span
                                                class="dropdown-wrapper" aria-hidden="true"></span></span>

                                    </td>
                                    <td>
                                        <select name="kolom9c" size="1" id="kolom9c"
                                            class="sel2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                            <option></option>
                                            <option value="862">Apt. Agnes Yuliasari S.Farm</option>
                                            <option value="1083">Apt. Ditha Octaviana, S.Farm</option>
                                            <option value="963">apt. Dwiman Nugraha Sutanto, S.farm
                                            </option>
                                            <option value="1064">apt. Nisa Fitri Rahmadiani, S. Farm.
                                            </option>
                                            <option value="838">apt. Nurani Eka Gumilang, S.Farm
                                            </option>
                                            <option value="26">Apt. Lia Vallini, S.Farm.</option>
                                        </select><span class="select2 select2-container select2-container--default"
                                            dir="ltr" style="width: 254px;"><span class="selection"><span
                                                    class="select2-selection select2-selection--single" role="combobox"
                                                    aria-haspopup="true" aria-expanded="false" tabindex="0"
                                                    aria-labelledby="select2-kolom9c-container"><span
                                                        class="select2-selection__rendered"
                                                        id="select2-kolom9c-container" title=""></span><span
                                                        class="select2-selection__arrow" role="presentation"><b
                                                            role="presentation"></b></span></span></span><span
                                                class="dropdown-wrapper" aria-hidden="true"></span></span>

                                    </td>
                                    <td>
                                        <select name="kolom9d" size="1" id="kolom9d"
                                            class="sel2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                            <option></option>
                                            <option value="862">Apt. Agnes Yuliasari S.Farm</option>
                                            <option value="1083">Apt. Ditha Octaviana, S.Farm</option>
                                            <option value="963">apt. Dwiman Nugraha Sutanto, S.farm
                                            </option>
                                            <option value="1064">apt. Nisa Fitri Rahmadiani, S. Farm.
                                            </option>
                                            <option value="838">apt. Nurani Eka Gumilang, S.Farm
                                            </option>
                                            <option value="26">Apt. Lia Vallini, S.Farm.</option>
                                        </select><span class="select2 select2-container select2-container--default"
                                            dir="ltr" style="width: 254px;"><span class="selection"><span
                                                    class="select2-selection select2-selection--single" role="combobox"
                                                    aria-haspopup="true" aria-expanded="false" tabindex="0"
                                                    aria-labelledby="select2-kolom9d-container"><span
                                                        class="select2-selection__rendered"
                                                        id="select2-kolom9d-container" title=""></span><span
                                                        class="select2-selection__arrow" role="presentation"><b
                                                            role="presentation"></b></span></span></span><span
                                                class="dropdown-wrapper" aria-hidden="true"></span></span>

                                    </td>
                                    <td>
                                        <select name="kolom9e" size="1" id="kolom9e"
                                            class="sel2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                            <option></option>
                                            <option value="862">Apt. Agnes Yuliasari S.Farm</option>
                                            <option value="1083">Apt. Ditha Octaviana, S.Farm</option>
                                            <option value="963">apt. Dwiman Nugraha Sutanto, S.farm
                                            </option>
                                            <option value="1064">apt. Nisa Fitri Rahmadiani, S. Farm.
                                            </option>
                                            <option value="838">apt. Nurani Eka Gumilang, S.Farm
                                            </option>
                                            <option value="26">Apt. Lia Vallini, S.Farm.</option>
                                        </select><span class="select2 select2-container select2-container--default"
                                            dir="ltr" style="width: 254px;"><span class="selection"><span
                                                    class="select2-selection select2-selection--single" role="combobox"
                                                    aria-haspopup="true" aria-expanded="false" tabindex="0"
                                                    aria-labelledby="select2-kolom9e-container"><span
                                                        class="select2-selection__rendered"
                                                        id="select2-kolom9e-container" title=""></span><span
                                                        class="select2-selection__arrow" role="presentation"><b
                                                            role="presentation"></b></span></span></span><span
                                                class="dropdown-wrapper" aria-hidden="true"></span></span>

                                    </td>
                                    <td>
                                        <select name="kolom9f" size="1" id="kolom9f"
                                            class="sel2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                            <option></option>
                                            <option value="862">Apt. Agnes Yuliasari S.Farm</option>
                                            <option value="1083">Apt. Ditha Octaviana, S.Farm</option>
                                            <option value="963">apt. Dwiman Nugraha Sutanto, S.farm
                                            </option>
                                            <option value="1064">apt. Nisa Fitri Rahmadiani, S. Farm.
                                            </option>
                                            <option value="838">apt. Nurani Eka Gumilang, S.Farm
                                            </option>
                                            <option value="26">Apt. Lia Vallini, S.Farm.</option>
                                        </select><span class="select2 select2-container select2-container--default"
                                            dir="ltr" style="width: 254px;"><span class="selection"><span
                                                    class="select2-selection select2-selection--single" role="combobox"
                                                    aria-haspopup="true" aria-expanded="false" tabindex="0"
                                                    aria-labelledby="select2-kolom9f-container"><span
                                                        class="select2-selection__rendered"
                                                        id="select2-kolom9f-container" title=""></span><span
                                                        class="select2-selection__arrow" role="presentation"><b
                                                            role="presentation"></b></span></span></span><span
                                                class="dropdown-wrapper" aria-hidden="true"></span></span>

                                    </td>
                                    <td>
                                        <select name="kolom9g" size="1" id="kolom9g"
                                            class="sel2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                            <option></option>
                                            <option value="862">Apt. Agnes Yuliasari S.Farm</option>
                                            <option value="1083">Apt. Ditha Octaviana, S.Farm</option>
                                            <option value="963">apt. Dwiman Nugraha Sutanto, S.farm
                                            </option>
                                            <option value="1064">apt. Nisa Fitri Rahmadiani, S. Farm.
                                            </option>
                                            <option value="838">apt. Nurani Eka Gumilang, S.Farm
                                            </option>
                                            <option value="26">Apt. Lia Vallini, S.Farm.</option>
                                        </select><span class="select2 select2-container select2-container--default"
                                            dir="ltr" style="width: 254px;"><span class="selection"><span
                                                    class="select2-selection select2-selection--single" role="combobox"
                                                    aria-haspopup="true" aria-expanded="false" tabindex="0"
                                                    aria-labelledby="select2-kolom9g-container"><span
                                                        class="select2-selection__rendered"
                                                        id="select2-kolom9g-container" title=""></span><span
                                                        class="select2-selection__arrow" role="presentation"><b
                                                            role="presentation"></b></span></span></span><span
                                                class="dropdown-wrapper" aria-hidden="true"></span></span>

                                    </td>
                                </tr>
                                <tr>
                                    <td><input id="kolom10" name="kolom10" type="text"
                                            class="form-control datepicker reset" data-inputmask-alias="datetime"
                                            data-inputmask-inputformat="dd-mm-yyyy" im-insert="false">
                                    </td>
                                    <td>
                                        <select name="kolom10a" size="1" id="kolom10a"
                                            class="sel2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                            <option></option>
                                            <option value="862">Apt. Agnes Yuliasari S.Farm</option>
                                            <option value="1083">Apt. Ditha Octaviana, S.Farm</option>
                                            <option value="963">apt. Dwiman Nugraha Sutanto, S.farm
                                            </option>
                                            <option value="1064">apt. Nisa Fitri Rahmadiani, S. Farm.
                                            </option>
                                            <option value="838">apt. Nurani Eka Gumilang, S.Farm
                                            </option>
                                            <option value="26">Apt. Lia Vallini, S.Farm.</option>
                                        </select><span class="select2 select2-container select2-container--default"
                                            dir="ltr" style="width: 254px;"><span class="selection"><span
                                                    class="select2-selection select2-selection--single" role="combobox"
                                                    aria-haspopup="true" aria-expanded="false" tabindex="0"
                                                    aria-labelledby="select2-kolom10a-container"><span
                                                        class="select2-selection__rendered"
                                                        id="select2-kolom10a-container" title=""></span><span
                                                        class="select2-selection__arrow" role="presentation"><b
                                                            role="presentation"></b></span></span></span><span
                                                class="dropdown-wrapper" aria-hidden="true"></span></span>

                                    </td>
                                    <td>
                                        <select name="kolom10b" size="1" id="kolom10b"
                                            class="sel2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                            <option></option>
                                            <option value="862">Apt. Agnes Yuliasari S.Farm</option>
                                            <option value="1083">Apt. Ditha Octaviana, S.Farm</option>
                                            <option value="963">apt. Dwiman Nugraha Sutanto, S.farm
                                            </option>
                                            <option value="1064">apt. Nisa Fitri Rahmadiani, S. Farm.
                                            </option>
                                            <option value="838">apt. Nurani Eka Gumilang, S.Farm
                                            </option>
                                            <option value="26">Apt. Lia Vallini, S.Farm.</option>
                                        </select><span class="select2 select2-container select2-container--default"
                                            dir="ltr" style="width: 254px;"><span class="selection"><span
                                                    class="select2-selection select2-selection--single" role="combobox"
                                                    aria-haspopup="true" aria-expanded="false" tabindex="0"
                                                    aria-labelledby="select2-kolom10b-container"><span
                                                        class="select2-selection__rendered"
                                                        id="select2-kolom10b-container" title=""></span><span
                                                        class="select2-selection__arrow" role="presentation"><b
                                                            role="presentation"></b></span></span></span><span
                                                class="dropdown-wrapper" aria-hidden="true"></span></span>

                                    </td>
                                    <td>
                                        <select name="kolom10c" size="1" id="kolom10c"
                                            class="sel2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                            <option></option>
                                            <option value="862">Apt. Agnes Yuliasari S.Farm</option>
                                            <option value="1083">Apt. Ditha Octaviana, S.Farm</option>
                                            <option value="963">apt. Dwiman Nugraha Sutanto, S.farm
                                            </option>
                                            <option value="1064">apt. Nisa Fitri Rahmadiani, S. Farm.
                                            </option>
                                            <option value="838">apt. Nurani Eka Gumilang, S.Farm
                                            </option>
                                            <option value="26">Apt. Lia Vallini, S.Farm.</option>
                                        </select><span class="select2 select2-container select2-container--default"
                                            dir="ltr" style="width: 254px;"><span class="selection"><span
                                                    class="select2-selection select2-selection--single" role="combobox"
                                                    aria-haspopup="true" aria-expanded="false" tabindex="0"
                                                    aria-labelledby="select2-kolom10c-container"><span
                                                        class="select2-selection__rendered"
                                                        id="select2-kolom10c-container" title=""></span><span
                                                        class="select2-selection__arrow" role="presentation"><b
                                                            role="presentation"></b></span></span></span><span
                                                class="dropdown-wrapper" aria-hidden="true"></span></span>

                                    </td>
                                    <td>
                                        <select name="kolom10d" size="1" id="kolom10d"
                                            class="sel2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                            <option></option>
                                            <option value="862">Apt. Agnes Yuliasari S.Farm</option>
                                            <option value="1083">Apt. Ditha Octaviana, S.Farm</option>
                                            <option value="963">apt. Dwiman Nugraha Sutanto, S.farm
                                            </option>
                                            <option value="1064">apt. Nisa Fitri Rahmadiani, S. Farm.
                                            </option>
                                            <option value="838">apt. Nurani Eka Gumilang, S.Farm
                                            </option>
                                            <option value="26">Apt. Lia Vallini, S.Farm.</option>
                                        </select><span class="select2 select2-container select2-container--default"
                                            dir="ltr" style="width: 254px;"><span class="selection"><span
                                                    class="select2-selection select2-selection--single" role="combobox"
                                                    aria-haspopup="true" aria-expanded="false" tabindex="0"
                                                    aria-labelledby="select2-kolom10d-container"><span
                                                        class="select2-selection__rendered"
                                                        id="select2-kolom10d-container" title=""></span><span
                                                        class="select2-selection__arrow" role="presentation"><b
                                                            role="presentation"></b></span></span></span><span
                                                class="dropdown-wrapper" aria-hidden="true"></span></span>

                                    </td>
                                    <td>
                                        <select name="kolom10e" size="1" id="kolom10e"
                                            class="sel2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                            <option></option>
                                            <option value="862">Apt. Agnes Yuliasari S.Farm</option>
                                            <option value="1083">Apt. Ditha Octaviana, S.Farm</option>
                                            <option value="963">apt. Dwiman Nugraha Sutanto, S.farm
                                            </option>
                                            <option value="1064">apt. Nisa Fitri Rahmadiani, S. Farm.
                                            </option>
                                            <option value="838">apt. Nurani Eka Gumilang, S.Farm
                                            </option>
                                            <option value="26">Apt. Lia Vallini, S.Farm.</option>
                                        </select><span class="select2 select2-container select2-container--default"
                                            dir="ltr" style="width: 254px;"><span class="selection"><span
                                                    class="select2-selection select2-selection--single" role="combobox"
                                                    aria-haspopup="true" aria-expanded="false" tabindex="0"
                                                    aria-labelledby="select2-kolom10e-container"><span
                                                        class="select2-selection__rendered"
                                                        id="select2-kolom10e-container" title=""></span><span
                                                        class="select2-selection__arrow" role="presentation"><b
                                                            role="presentation"></b></span></span></span><span
                                                class="dropdown-wrapper" aria-hidden="true"></span></span>

                                    </td>
                                    <td>
                                        <select name="kolom10f" size="1" id="kolom10f"
                                            class="sel2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                            <option></option>
                                            <option value="862">Apt. Agnes Yuliasari S.Farm</option>
                                            <option value="1083">Apt. Ditha Octaviana, S.Farm</option>
                                            <option value="963">apt. Dwiman Nugraha Sutanto, S.farm
                                            </option>
                                            <option value="1064">apt. Nisa Fitri Rahmadiani, S. Farm.
                                            </option>
                                            <option value="838">apt. Nurani Eka Gumilang, S.Farm
                                            </option>
                                            <option value="26">Apt. Lia Vallini, S.Farm.</option>
                                        </select><span class="select2 select2-container select2-container--default"
                                            dir="ltr" style="width: 254px;"><span class="selection"><span
                                                    class="select2-selection select2-selection--single" role="combobox"
                                                    aria-haspopup="true" aria-expanded="false" tabindex="0"
                                                    aria-labelledby="select2-kolom10f-container"><span
                                                        class="select2-selection__rendered"
                                                        id="select2-kolom10f-container" title=""></span><span
                                                        class="select2-selection__arrow" role="presentation"><b
                                                            role="presentation"></b></span></span></span><span
                                                class="dropdown-wrapper" aria-hidden="true"></span></span>

                                    </td>
                                    <td>
                                        <select name="kolom10g" size="1" id="kolom10g"
                                            class="sel2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                            <option></option>
                                            <option value="862">Apt. Agnes Yuliasari S.Farm</option>
                                            <option value="1083">Apt. Ditha Octaviana, S.Farm</option>
                                            <option value="963">apt. Dwiman Nugraha Sutanto, S.farm
                                            </option>
                                            <option value="1064">apt. Nisa Fitri Rahmadiani, S. Farm.
                                            </option>
                                            <option value="838">apt. Nurani Eka Gumilang, S.Farm
                                            </option>
                                            <option value="26">Apt. Lia Vallini, S.Farm.</option>
                                        </select><span class="select2 select2-container select2-container--default"
                                            dir="ltr" style="width: 254px;"><span class="selection"><span
                                                    class="select2-selection select2-selection--single" role="combobox"
                                                    aria-haspopup="true" aria-expanded="false" tabindex="0"
                                                    aria-labelledby="select2-kolom10g-container"><span
                                                        class="select2-selection__rendered"
                                                        id="select2-kolom10g-container" title=""></span><span
                                                        class="select2-selection__arrow" role="presentation"><b
                                                            role="presentation"></b></span></span></span><span
                                                class="dropdown-wrapper" aria-hidden="true"></span></span>

                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
@section('plugin-erm')
    <script script src="/js/formplugins/select2/select2.bundle.js"></script>
    <script>
        $(document).ready(function() {

            $('#cppt_doctor_id').val("{{ $registration->doctor_id }}")
            $('.btnAdd').click(function() {
                $('#add_soap').collapse('show');
            });

            $('#tutup').on('click', function() {
                $('#add_soap').collapse('hide');

                $('.btnAdd').attr('aria-expanded', 'false');
                $('.btnAdd').addClass('collapsed');
            });

            // Saat tombol Save Final diklik
            $('#bsSOAP').on('click', function() {
                submitFormCPPT(); // Panggil fungsi submitForm dengan parameter final
            });

            function loadCPPTData() {
                $.ajax({
                    // url: '{{-- route('cppt.get') --}}', // Mengambil route Laravel
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        // Bersihkan tabel
                        $('#list_soap').empty();

                        // Iterasi setiap data dan tambahkan ke dalam tabel
                        $.each(response, function(index, data) {
                            var row = `
                            <tr>
                                <td class="text-center">
                                    <div class="deep-purple-text">${data.created_at}<br>
                                        <span class="green-text" style="font-weight:400;">${data.tipe_rawat}</span><br>
                                        <b style="font-weight: 400;">Dokter ID: ${data.doctor_id}</b><br>
                                        <div class="input-oleh deep-orange-text">Input oleh: ${data.user_id}</div>
                                        <a href="javascript:void(0)" class="d-block text-uppercase badge badge-primary"><i class="mdi mdi-plus-circle"></i> Verifikasi</a>
                                        <div>
                                            <img src="http://192.168.1.253/real/include/images/ttd_blank.png" width="200px;" height="100px;">
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <table width="100%" class="table-soap nurse">
                                        <tbody>
                                            <tr><td colspan="3" class="soap-text title">CPPT</td></tr>
                                            <tr><td class="soap-text deep-purple-text text-center" width="8%">S</td><td>${data.subjective.replace(/\n/g, "<br>")}</td></tr>
                                            <tr><td class="soap-text deep-purple-text text-center">O</td><td>${data.objective.replace(/\n/g, "<br>")}</td></tr>
                                            <tr><td class="soap-text deep-purple-text text-center">A</td><td>${data.assesment}</td></tr>
                                            <tr><td class="soap-text deep-purple-text text-center">P</td><td>${data.planning}</td></tr>
                                            <tr><td class="soap-text deep-purple-text text-center">I</td><td>${data.instruksi}</td></tr>
                                        </tbody>
                                    </table>
                                </td>
                                <td>
                                    <i class="mdi mdi-content-copy blue-text pointer mdi-18px copy-soap" data-id="${data.id}" title="Copy"></i>
                                    <i class="mdi mdi-delete-forever red-text pointer mdi-18px hapus-soap" data-id="${data.id}" title="Hapus"></i>
                                    <i class="mdi mdi-pencil red-text pointer mdi-18px edit-soap" data-id="${data.id}" title="Edit SOAP & Resep Elektronik"></i>
                                    <i class="mdi mdi-printer blue-text pointer mdi-18px print-antrian" data-id="${data.id}" title="Print Antrian Resep"></i>
                                </td>
                            </tr>
                        `;
                            // Tambahkan ke dalam tabel
                            $('#list_soap').append(row);
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            }

            function submitFormCPPT(actionType) {
                const form = $('#cppt-dokter-rajal-form');
                const registrationNumber = "{{ $registration->registration_number }}";

                const url =
                    "{{ route('cppt.dokter-rajal.store', ['type' => 'rawat-jalan', 'registration_number' => '__registration_number__']) }}"
                    .replace('__registration_number__', registrationNumber);

                // Now you can use `url` in your form submission or AJAX request

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
        });
    </script>
    <script>
        $(document).ready(function() {
            $('body').addClass('layout-composed');
            $('.select2').select2({
                placeholder: 'Pilih Item',
            });
            $('#departement_id').select2({
                placeholder: 'Pilih Klinik',
            });
            $('#doctor_id').select2({
                placeholder: 'Pilih Dokter',
            });

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
