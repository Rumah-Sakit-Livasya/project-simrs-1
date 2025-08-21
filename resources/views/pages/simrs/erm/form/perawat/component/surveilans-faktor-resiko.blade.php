<div class="table-responsive">
    <table class="table table-bordered">
        <thead class="text-center bg-light">
            <tr>
                <th rowspan="2" class="align-middle">No</th>
                <th rowspan="2" class="align-middle">Jenis Tindakan / Alkes</th>
                <th rowspan="2" class="align-middle">Lokasi</th>
                <th colspan="2">Tanggal Pemasangan</th>
                <th rowspan="2" class="align-middle">Total Hari</th>
                <th rowspan="2" class="align-middle">Tanggal Infeksi</th>
                <th rowspan="2" class="align-middle">Catatan</th>
            </tr>
            <tr>
                <th>Mulai</th>
                <th>s/d</th>
            </tr>
        </thead>
        <tbody>
            {{-- 1. INTRA VENA KATETER --}}
            @php
                // Definisikan item-item untuk Intra Vena Kateter agar mudah di-loop
                $ivCatheterItems = [
                    'vena_sentral' => 'Vena Sentral',
                    'vena_perifer' => 'Vena Perifer',
                    'arteri' => 'Arteri',
                    'umbilikal' => 'Umbilikal',
                ];
                $ivRows = 4; // Jumlah baris untuk rowspan
            @endphp
            <tr>
                <td rowspan="{{ $ivRows }}" class="text-center align-middle font-weight-bold">1</td>
                <td class="font-weight-bold" colspan="7">Intra Vena Kateter</td>
            </tr>
            @foreach ($ivCatheterItems as $key => $label)
                <tr>
                    <td>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="fr_{{ $key }}"
                                name="faktor_resiko[iv_catheter][{{ $key }}][terpasang]" value="1"
                                @checked(isset($data['iv_catheter'][$key]['terpasang']))>
                            <label class="custom-control-label" for="fr_{{ $key }}">{{ $label }}</label>
                        </div>
                    </td>
                    <td><input class="form-control" type="text"
                            name="faktor_resiko[iv_catheter][{{ $key }}][lokasi]"
                            value="{{ $data['iv_catheter'][$key]['lokasi'] ?? '' }}"></td>
                    <td><input class="form-control" type="date"
                            name="faktor_resiko[iv_catheter][{{ $key }}][tgl_mulai]"
                            value="{{ $data['iv_catheter'][$key]['tgl_mulai'] ?? '' }}"></td>
                    <td><input class="form-control" type="date"
                            name="faktor_resiko[iv_catheter][{{ $key }}][tgl_selesai]"
                            value="{{ $data['iv_catheter'][$key]['tgl_selesai'] ?? '' }}"></td>
                    <td><input class="form-control" type="number"
                            name="faktor_resiko[iv_catheter][{{ $key }}][total_hari]"
                            value="{{ $data['iv_catheter'][$key]['total_hari'] ?? '' }}"></td>
                    <td><input class="form-control" type="date"
                            name="faktor_resiko[iv_catheter][{{ $key }}][tgl_infeksi]"
                            value="{{ $data['iv_catheter'][$key]['tgl_infeksi'] ?? '' }}"></td>
                    <td><input class="form-control" type="text"
                            name="faktor_resiko[iv_catheter][{{ $key }}][catatan]"
                            value="{{ $data['iv_catheter'][$key]['catatan'] ?? '' }}"></td>
                </tr>
            @endforeach

            {{-- 2. URINE KATETER --}}
            @php
                $urineCatheterItems = [
                    'urine_kateter' => 'Urine Kateter',
                    'suprapubik_kateter' => 'Suprapubik Kateter',
                ];
                $urineRows = 2;
            @endphp
            <tr>
                <td rowspan="{{ $urineRows }}" class="text-center align-middle font-weight-bold">2</td>
                <td class="font-weight-bold" colspan="7">Urine Kateter</td>
            </tr>
            @foreach ($urineCatheterItems as $key => $label)
                <tr>
                    <td>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="fr_{{ $key }}"
                                name="faktor_resiko[urine_catheter][{{ $key }}][terpasang]" value="1"
                                @checked(isset($data['urine_catheter'][$key]['terpasang']))>
                            <label class="custom-control-label"
                                for="fr_{{ $key }}">{{ $label }}</label>
                        </div>
                    </td>
                    <td><input class="form-control" type="text"
                            name="faktor_resiko[urine_catheter][{{ $key }}][lokasi]"
                            value="{{ $data['urine_catheter'][$key]['lokasi'] ?? '' }}"></td>
                    <td><input class="form-control" type="date"
                            name="faktor_resiko[urine_catheter][{{ $key }}][tgl_mulai]"
                            value="{{ $data['urine_catheter'][$key]['tgl_mulai'] ?? '' }}"></td>
                    <td><input class="form-control" type="date"
                            name="faktor_resiko[urine_catheter][{{ $key }}][tgl_selesai]"
                            value="{{ $data['urine_catheter'][$key]['tgl_selesai'] ?? '' }}"></td>
                    <td><input class="form-control" type="number"
                            name="faktor_resiko[urine_catheter][{{ $key }}][total_hari]"
                            value="{{ $data['urine_catheter'][$key]['total_hari'] ?? '' }}"></td>
                    <td><input class="form-control" type="date"
                            name="faktor_resiko[urine_catheter][{{ $key }}][tgl_infeksi]"
                            value="{{ $data['urine_catheter'][$key]['tgl_infeksi'] ?? '' }}"></td>
                    <td><input class="form-control" type="text"
                            name="faktor_resiko[urine_catheter][{{ $key }}][catatan]"
                            value="{{ $data['urine_catheter'][$key]['catatan'] ?? '' }}"></td>
                </tr>
            @endforeach

            {{-- 3. VENTILASI MEKANIK --}}
            @php
                $ventilasiItems = [
                    'endotrakeal' => 'Tuba Endotrakeal',
                    'tracheostomi' => 'Tracheostomi',
                ];
                $ventilasiRows = 2;
            @endphp
            <tr>
                <td rowspan="{{ $ventilasiRows }}" class="text-center align-middle font-weight-bold">3</td>
                <td class="font-weight-bold" colspan="7">Ventilasi Mekanik</td>
            </tr>
            @foreach ($ventilasiItems as $key => $label)
                <tr>
                    <td>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="fr_{{ $key }}"
                                name="faktor_resiko[ventilasi_mekanik][{{ $key }}][terpasang]" value="1"
                                @checked(isset($data['ventilasi_mekanik'][$key]['terpasang']))>
                            <label class="custom-control-label"
                                for="fr_{{ $key }}">{{ $label }}</label>
                        </div>
                    </td>
                    <td><input class="form-control" type="text"
                            name="faktor_resiko[ventilasi_mekanik][{{ $key }}][lokasi]"
                            value="{{ $data['ventilasi_mekanik'][$key]['lokasi'] ?? '' }}"></td>
                    <td><input class="form-control" type="date"
                            name="faktor_resiko[ventilasi_mekanik][{{ $key }}][tgl_mulai]"
                            value="{{ $data['ventilasi_mekanik'][$key]['tgl_mulai'] ?? '' }}"></td>
                    <td><input class="form-control" type="date"
                            name="faktor_resiko[ventilasi_mekanik][{{ $key }}][tgl_selesai]"
                            value="{{ $data['ventilasi_mekanik'][$key]['tgl_selesai'] ?? '' }}"></td>
                    <td><input class="form-control" type="number"
                            name="faktor_resiko[ventilasi_mekanik][{{ $key }}][total_hari]"
                            value="{{ $data['ventilasi_mekanik'][$key]['total_hari'] ?? '' }}"></td>
                    <td><input class="form-control" type="date"
                            name="faktor_resiko[ventilasi_mekanik][{{ $key }}][tgl_infeksi]"
                            value="{{ $data['ventilasi_mekanik'][$key]['tgl_infeksi'] ?? '' }}"></td>
                    <td><input class="form-control" type="text"
                            name="faktor_resiko[ventilasi_mekanik][{{ $key }}][catatan]"
                            value="{{ $data['ventilasi_mekanik'][$key]['catatan'] ?? '' }}"></td>
                </tr>
            @endforeach

            {{-- 4. LAIN-LAIN --}}
            <tr>
                <td class="text-center align-middle font-weight-bold">4</td>
                <td>Lain-lain (Drain, dll)</td>
                <td><input class="form-control" type="text" name="faktor_resiko[lain_lain][lokasi]"
                        value="{{ $data['lain_lain']['lokasi'] ?? '' }}"></td>
                <td><input class="form-control" type="date" name="faktor_resiko[lain_lain][tgl_mulai]"
                        value="{{ $data['lain_lain']['tgl_mulai'] ?? '' }}"></td>
                <td><input class="form-control" type="date" name="faktor_resiko[lain_lain][tgl_selesai]"
                        value="{{ $data['lain_lain']['tgl_selesai'] ?? '' }}"></td>
                <td><input class="form-control" type="number" name="faktor_resiko[lain_lain][total_hari]"
                        value="{{ $data['lain_lain']['total_hari'] ?? '' }}"></td>
                <td><input class="form-control" type="date" name="faktor_resiko[lain_lain][tgl_infeksi]"
                        value="{{ $data['lain_lain']['tgl_infeksi'] ?? '' }}"></td>
                <td><input class="form-control" type="text" name="faktor_resiko[lain_lain][catatan]"
                        value="{{ $data['lain_lain']['catatan'] ?? '' }}"></td>
            </tr>
        </tbody>
    </table>
</div>
