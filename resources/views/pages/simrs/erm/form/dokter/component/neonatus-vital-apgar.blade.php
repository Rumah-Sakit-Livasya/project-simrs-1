{{-- =================================================================================================== --}}
{{-- FILE: resources/views/pages/simrs/erm/form/dokter/component/neonatus-vital-apgar.blade.php         --}}
{{-- =================================================================================================== --}}

{{-- TANDA-TANDA VITAL & APGAR SCORE --}}
<h4 class="text-primary mt-4 font-weight-bold">II. Tanda Tanda Vital & Pengukuran</h4>
<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-content">
                    <input class="form-control" name="data[vital_signs][nadi]"
                        value="{{ $data['vital_signs']['nadi'] ?? '' }}" type="text">
                    <label>Nadi</label>
                </div>
                <span class="input-group-addon grey-text">x/mnt</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-content">
                    <input class="form-control" name="data[vital_signs][rr]"
                        value="{{ $data['vital_signs']['rr'] ?? '' }}" type="text">
                    <label>RR</label>
                </div>
                <span class="input-group-addon grey-text">x/mnt</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-content">
                    <input class="form-control" name="data[vital_signs][sb]"
                        value="{{ $data['vital_signs']['sb'] ?? '' }}" type="text">
                    <label>SB</label>
                </div>
                <span class="input-group-addon grey-text">°C</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-content">
                    <input class="form-control" name="data[vital_signs][spo2]"
                        value="{{ $data['vital_signs']['spo2'] ?? '' }}" type="text">
                    <label>SpO2</label>
                </div>
                <span class="input-group-addon grey-text">%</span>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">Menangis</label>
            <div class="form-radio mt-2">
                <label class="radio-styled radio-info">
                    <input value="Kuat" name="data[vital_signs][menangis]" type="radio"
                        {{ ($data['vital_signs']['menangis'] ?? null) == 'Kuat' ? 'checked' : '' }}><span>Kuat</span>
                </label>&nbsp;&nbsp;
                <label class="radio-styled radio-info">
                    <input value="Merintih" name="data[vital_signs][menangis]" type="radio"
                        {{ ($data['vital_signs']['menangis'] ?? null) == 'Merintih' ? 'checked' : '' }}><span>Merintih</span>
                </label>&nbsp;&nbsp;
                <label class="radio-styled radio-info">
                    <input value="Lemah" name="data[vital_signs][menangis]" type="radio"
                        {{ ($data['vital_signs']['menangis'] ?? null) == 'Lemah' ? 'checked' : '' }}><span>Lemah</span>
                </label>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">Jenis Kelamin</label>
            <div class="form-radio mt-2">
                <label class="radio-styled radio-info">
                    <input value="Laki-Laki" name="data[vital_signs][jenis_kelamin]" type="radio"
                        {{ ($data['vital_signs']['jenis_kelamin'] ?? null) == 'Laki-Laki' ? 'checked' : '' }}><span>Laki-Laki</span>
                </label>&nbsp;&nbsp;
                <label class="radio-styled radio-info">
                    <input value="Perempuan" name="data[vital_signs][jenis_kelamin]" type="radio"
                        {{ ($data['vital_signs']['jenis_kelamin'] ?? null) == 'Perempuan' ? 'checked' : '' }}><span>Perempuan</span>
                </label>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <input class="form-control" name="data[measurements][as]" type="text"
                value="{{ $data['measurements']['as'] ?? '' }}">
            <label>A/S</label>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-content">
                    <input class="form-control" name="data[measurements][bb]"
                        value="{{ $data['measurements']['bb'] ?? '' }}" type="text">
                    <label>BB</label>
                </div>
                <span class="input-group-addon grey-text">Gram</span>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-content">
                    <input class="form-control" name="data[measurements][tb]"
                        value="{{ $data['measurements']['tb'] ?? '' }}" type="text">
                    <label>TB</label>
                </div>
                <span class="input-group-addon grey-text">Cm</span>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-content">
                    <input class="form-control" name="data[measurements][lk]"
                        value="{{ $data['measurements']['lk'] ?? '' }}" type="text">
                    <label>LK</label>
                </div>
                <span class="input-group-addon grey-text">Cm</span>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-content">
                    <input class="form-control" name="data[measurements][ld]"
                        value="{{ $data['measurements']['ld'] ?? '' }}" type="text">
                    <label>LD</label>
                </div>
                <span class="input-group-addon grey-text">Cm</span>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-content">
                    <input class="form-control" name="data[measurements][lp]"
                        value="{{ $data['measurements']['lp'] ?? '' }}" type="text">
                    <label>LP</label>
                </div>
                <span class="input-group-addon grey-text">Cm</span>
            </div>
        </div>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-md-12">
        <h5 class="text-info font-weight-bold">Apgar Score</h5>
        <table class="table table-bordered text-center" style="width: 100%;">
            <thead class="bg-light">
                <tr>
                    <th rowspan="3" style="vertical-align: middle;">Apgar</th>
                    <th colspan="9" style="vertical-align: middle;">Angka Penilaian</th>
                </tr>
                <tr>
                    <th colspan="3" style="width: 30%;">1 Menit</th>
                    <th colspan="3" style="width: 30%;">5 Menit</th>
                    <th colspan="3" style="width: 30%;">10 Menit</th>
                </tr>
                <tr>
                    <th>0</th>
                    <th>1</th>
                    <th>2</th>
                    <th>0</th>
                    <th>1</th>
                    <th>2</th>
                    <th>0</th>
                    <th>1</th>
                    <th>2</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-left">Bunyi Jantung</td>
                    @foreach ([1, 5, 10] as $menit)
                        @php
                            $key_menit = 'menit_' . $menit;
                            $skor_class = 'skor_' . ($loop->index + 1);
                        @endphp
                        <td><input type="radio" name="data[apgar][{{ $key_menit }}][jantung]"
                                class="{{ $skor_class }}" value="0" data-skor="0"
                                {{ ($data['apgar'][$key_menit]['jantung'] ?? null) == '0' ? 'checked' : '' }}></td>
                        <td><input type="radio" name="data[apgar][{{ $key_menit }}][jantung]"
                                class="{{ $skor_class }}" value="1" data-skor="1"
                                {{ ($data['apgar'][$key_menit]['jantung'] ?? null) == '1' ? 'checked' : '' }}></td>
                        <td><input type="radio" name="data[apgar][{{ $key_menit }}][jantung]"
                                class="{{ $skor_class }}" value="2" data-skor="2"
                                {{ ($data['apgar'][$key_menit]['jantung'] ?? null) == '2' ? 'checked' : '' }}></td>
                    @endforeach
                </tr>
                <tr>
                    <td class="text-left">Pernapasan</td>
                    @foreach ([1, 5, 10] as $menit)
                        @php
                            $key_menit = 'menit_' . $menit;
                            $skor_class = 'skor_' . ($loop->index + 1);
                        @endphp
                        <td><input type="radio" name="data[apgar][{{ $key_menit }}][pernapasan]"
                                class="{{ $skor_class }}" value="0" data-skor="0"
                                {{ ($data['apgar'][$key_menit]['pernapasan'] ?? null) == '0' ? 'checked' : '' }}></td>
                        <td><input type="radio" name="data[apgar][{{ $key_menit }}][pernapasan]"
                                class="{{ $skor_class }}" value="1" data-skor="1"
                                {{ ($data['apgar'][$key_menit]['pernapasan'] ?? null) == '1' ? 'checked' : '' }}></td>
                        <td><input type="radio" name="data[apgar][{{ $key_menit }}][pernapasan]"
                                class="{{ $skor_class }}" value="2" data-skor="2"
                                {{ ($data['apgar'][$key_menit]['pernapasan'] ?? null) == '2' ? 'checked' : '' }}></td>
                    @endforeach
                </tr>
                <tr>
                    <td class="text-left">Tonus Otot</td>
                    @foreach ([1, 5, 10] as $menit)
                        @php
                            $key_menit = 'menit_' . $menit;
                            $skor_class = 'skor_' . ($loop->index + 1);
                        @endphp
                        <td><input type="radio" name="data[apgar][{{ $key_menit }}][otot]"
                                class="{{ $skor_class }}" value="0" data-skor="0"
                                {{ ($data['apgar'][$key_menit]['otot'] ?? null) == '0' ? 'checked' : '' }}></td>
                        <td><input type="radio" name="data[apgar][{{ $key_menit }}][otot]"
                                class="{{ $skor_class }}" value="1" data-skor="1"
                                {{ ($data['apgar'][$key_menit]['otot'] ?? null) == '1' ? 'checked' : '' }}></td>
                        <td><input type="radio" name="data[apgar][{{ $key_menit }}][otot]"
                                class="{{ $skor_class }}" value="2" data-skor="2"
                                {{ ($data['apgar'][$key_menit]['otot'] ?? null) == '2' ? 'checked' : '' }}></td>
                    @endforeach
                </tr>
                <tr>
                    <td class="text-left">Reflek</td>
                    @foreach ([1, 5, 10] as $menit)
                        @php
                            $key_menit = 'menit_' . $menit;
                            $skor_class = 'skor_' . ($loop->index + 1);
                        @endphp
                        <td><input type="radio" name="data[apgar][{{ $key_menit }}][reflek]"
                                class="{{ $skor_class }}" value="0" data-skor="0"
                                {{ ($data['apgar'][$key_menit]['reflek'] ?? null) == '0' ? 'checked' : '' }}></td>
                        <td><input type="radio" name="data[apgar][{{ $key_menit }}][reflek]"
                                class="{{ $skor_class }}" value="1" data-skor="1"
                                {{ ($data['apgar'][$key_menit]['reflek'] ?? null) == '1' ? 'checked' : '' }}></td>
                        <td><input type="radio" name="data[apgar][{{ $key_menit }}][reflek]"
                                class="{{ $skor_class }}" value="2" data-skor="2"
                                {{ ($data['apgar'][$key_menit]['reflek'] ?? null) == '2' ? 'checked' : '' }}></td>
                    @endforeach
                </tr>
                <tr>
                    <td class="text-left">Warna Kulit</td>
                    @foreach ([1, 5, 10] as $menit)
                        @php
                            $key_menit = 'menit_' . $menit;
                            $skor_class = 'skor_' . ($loop->index + 1);
                        @endphp
                        <td><input type="radio" name="data[apgar][{{ $key_menit }}][kulit]"
                                class="{{ $skor_class }}" value="0" data-skor="0"
                                {{ ($data['apgar'][$key_menit]['kulit'] ?? null) == '0' ? 'checked' : '' }}></td>
                        <td><input type="radio" name="data[apgar][{{ $key_menit }}][kulit]"
                                class="{{ $skor_class }}" value="1" data-skor="1"
                                {{ ($data['apgar'][$key_menit]['kulit'] ?? null) == '1' ? 'checked' : '' }}></td>
                        <td><input type="radio" name="data[apgar][{{ $key_menit }}][kulit]"
                                class="{{ $skor_class }}" value="2" data-skor="2"
                                {{ ($data['apgar'][$key_menit]['kulit'] ?? null) == '2' ? 'checked' : '' }}></td>
                    @endforeach
                </tr>
                <tr class="bg-light font-weight-bold">
                    <td class="text-left"><strong>SKOR</strong></td>
                    <td colspan="3">
                        <input name="data[apgar][skor][menit_1]" id="skor1"
                            class="form-control text-center bg-white" readonly
                            value="{{ $data['apgar']['skor']['menit_1'] ?? '0' }}">
                    </td>
                    <td colspan="3">
                        <input name="data[apgar][skor][menit_5]" id="skor2"
                            class="form-control text-center bg-white" readonly
                            value="{{ $data['apgar']['skor']['menit_5'] ?? '0' }}">
                    </td>
                    <td colspan="3">
                        <input name="data[apgar][skor][menit_10]" id="skor3"
                            class="form-control text-center bg-white" readonly
                            value="{{ $data['apgar']['skor']['menit_10'] ?? '0' }}">
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
