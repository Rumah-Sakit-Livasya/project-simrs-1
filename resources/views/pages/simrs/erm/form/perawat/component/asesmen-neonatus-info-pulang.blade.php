@php
    $pendidikan = $data['pendidikan_kesehatan_pulang'] ?? [];
    $infoPulang = $data['info_bayi_pulang'] ?? [];
    $waktuPeriksa = $data['waktu_pemeriksaan_akhir'] ?? null;
@endphp

<hr>
<h4 class="text-primary mt-4 font-weight-bold">V. PENDIDIKAN DAN INFORMASI KESEHATAN SEBELUM BAYI DIBAWA PULANG</h4>

<div class="table-responsive">
    <table class="table table-borderless table-sm">
        <tbody>
            @php
                $pendidikanItems = [
                    'bahu' => ['label' => 'Bahu', 'sub_label' => 'Fraktur'],
                    'tali_pusat' => ['label' => 'Tali pusat', 'sub_label' => 'Puput'],
                    'tali_pusat_perdarahan' => ['label' => '', 'sub_label' => 'Perdarahan'],
                    'tali_pusat_infeksi' => ['label' => '', 'sub_label' => 'Infeksi'],
                    'ekstremitas' => ['label' => 'Ekstremitas', 'sub_label' => 'Jumlah jari'],
                    'ekstremitas_fraktur' => ['label' => '', 'sub_label' => 'Fraktur'],
                    'ekstremitas_lecet' => ['label' => '', 'sub_label' => 'Lecet / haematoma'],
                    'alat_kelamin' => ['label' => 'Alat Kelamin', 'sub_label' => 'Usam pada labia'],
                    'alat_kelamin_testis' => ['label' => '', 'sub_label' => 'Testis sudah turun'],
                    'bokong' => ['label' => 'Bokong', 'sub_label' => 'Atresia ani'],
                    'bokong_lecet' => ['label' => '', 'sub_label' => 'Lecet'],
                ];
            @endphp

            @foreach ($pendidikanItems as $key => $item)
                <tr>
                    <td style="width: 15%;"><b>{{ $item['label'] }}</b></td>
                    <td style="width: 20%;">{{ $item['sub_label'] }}</td>
                    <td style="width: 10%;">
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="{{ $key }}_tidak"
                                name="pendidikan_kesehatan_pulang[{{ $key }}][status]" value="tidak"
                                class="custom-control-input" @checked(isset($pendidikan[$key]['status']) && $pendidikan[$key]['status'] == 'tidak')>
                            <label class="custom-control-label" for="{{ $key }}_tidak">Tidak</label>
                        </div>
                    </td>
                    <td>
                        <div class="custom-control custom-radio custom-control-inline d-flex align-items-center">
                            <input type="radio" id="{{ $key }}_ya"
                                name="pendidikan_kesehatan_pulang[{{ $key }}][status]" value="ya"
                                class="custom-control-input" @checked(isset($pendidikan[$key]['status']) && $pendidikan[$key]['status'] == 'ya')>
                            <label class="custom-control-label" for="{{ $key }}_ya">Ya:</label>
                            <input type="text" name="pendidikan_kesehatan_pulang[{{ $key }}][keterangan]"
                                class="form-control form-control-sm ml-2 flex-grow-1"
                                value="{{ $pendidikan[$key]['keterangan'] ?? '' }}">
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<h5 class="font-weight-bold mt-4">Keadaan Umum Bayi Pada Waktu Pulang</h5>
<div class="form-group">
    <label>Keadaan Umum:</label>
    <input type="text" class="form-control" name="info_bayi_pulang[keadaan_umum]"
        value="{{ $infoPulang['keadaan_umum'] ?? '' }}">
</div>
<div class="row">
    <div class="col-md-3 form-group"><label>Berat Badan</label>
        <div class="input-group"><input type="number" step="0.1" name="info_bayi_pulang[bb]" class="form-control"
                value="{{ $infoPulang['bb'] ?? '' }}">
            <div class="input-group-append"><span class="input-group-text">gr</span></div>
        </div>
    </div>
    <div class="col-md-3 form-group"><label>Panjang Badan</label>
        <div class="input-group"><input type="number" step="0.1" name="info_bayi_pulang[pb]" class="form-control"
                value="{{ $infoPulang['pb'] ?? '' }}">
            <div class="input-group-append"><span class="input-group-text">cm</span></div>
        </div>
    </div>
    <div class="col-md-3 form-group"><label>Lingkar Kepala</label>
        <div class="input-group"><input type="number" step="0.1" name="info_bayi_pulang[lk]" class="form-control"
                value="{{ $infoPulang['lk'] ?? '' }}">
            <div class="input-group-append"><span class="input-group-text">cm</span></div>
        </div>
    </div>
    <div class="col-md-3 form-group"><label>Lingkar Dada</label>
        <div class="input-group"><input type="number" step="0.1" name="info_bayi_pulang[ld]" class="form-control"
                value="{{ $infoPulang['ld'] ?? '' }}">
            <div class="input-group-append"><span class="input-group-text">cm</span></div>
        </div>
    </div>
</div>

<h5 class="font-weight-bold mt-4">Obat Yang Dibawa Pulang</h5>
@include('pages.simrs.erm.form.perawat.component.tabel-obat', [
    'prefix' => 'info_bayi_pulang[obat_pulang]',
    'data' => $infoPulang['obat_pulang'] ?? [],
])

<div class="row mt-3">
    <div class="col-md-6 form-group">
        <label>Pemeriksaan Akhir Dilayani Tanggal & Pukul</label>
        <div class="input-group">
            <input type="date" name="tgl_dilayani" class="form-control"
                value="{{ optional($waktuPeriksa)->format('Y-m-d') }}">
            <input type="time" name="jam_dilayani" class="form-control"
                value="{{ optional($waktuPeriksa)->format('H:i') }}">
        </div>
    </div>
</div>
