@php
    $keadaanUmum = $data['keadaan_umum'] ?? [];
@endphp

<hr>
<h4 class="text-primary mt-4 font-weight-bold">II. KEADAAN UMUM</h4>

<h5 class="font-weight-bold mt-3">KESADARAN (TABEL DOWN SCORE)</h5>
<div class="table-responsive">
    <table class="table table-bordered">
        <thead class="text-center bg-light">
            <tr>
                <th>Kriteria</th>
                <th>Skor 0</th>
                <th>Skor 1</th>
                <th>Skor 2</th>
            </tr>
        </thead>
        <tbody>
            @foreach ([
        'pernapasan' => ['label' => 'Pernapasan', 'options' => ['< 60x/menit' => 0, '60-80x/menit' => 1, '> 80x/menit atau Apnea' => 2]],
        'retraksi' => ['label' => 'Retraksi', 'options' => ['Tidak Ada' => 0, 'Retraksi Ringan' => 1, 'Retraksi Berat' => 2]],
        'sianosis' => ['label' => 'Sianosis', 'options' => ['Tidak Ada' => 0, 'Hilang Dengan Pemberian O2' => 1, 'Menetap Walaupun Diberi O2' => 2]],
        'air_entry' => ['label' => 'Air Entry (Udara Masuk)', 'options' => ['Udara Masuk Bilateral Baik' => 0, 'Penurunan Ringan Udara Masuk' => 1, 'Tidak Ada Udara Masuk' => 2]],
        'merintih' => ['label' => 'Merintih', 'options' => ['Tidak Merintih' => 0, 'Dapat Didengar Dengan Stetoskop' => 1, 'Dapat Didengar Tanpa Alat Bantu' => 2]],
    ] as $key => $item)
                <tr>
                    <td class="align-middle"><b>{{ $item['label'] }}</b></td>
                    @foreach ($item['options'] as $pilihan => $skor)
                        <td>
                            <div class="custom-control custom-radio">
                                <input type="radio" id="down_score_{{ $key }}_{{ $skor }}"
                                    name="keadaan_umum[down_score][{{ $key }}]" value="{{ $skor }}"
                                    class="custom-control-input kesadaran" data-skor="{{ $skor }}"
                                    @checked(isset($keadaanUmum['down_score'][$key]) && $keadaanUmum['down_score'][$key] == $skor)>
                                <label class="custom-control-label"
                                    for="down_score_{{ $key }}_{{ $skor }}">{{ $pilihan }}</label>
                            </div>
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
        <tfoot class="bg-light">
            <tr>
                <td colspan="2" class="text-left">
                    <small>
                        <b>Interpretasi:</b><br>
                        &lt; 4 : Tidak ada gawat nafas<br>
                        4-6 : Gawat nafas<br>
                        &gt; 6 : Ancaman gagal nafas
                    </small>
                </td>
                <th class="text-right align-middle">Skor Total & Analisis</th>
                <td>
                    <div class="input-group">
                        <input type="text" name="keadaan_umum[down_score][total_skor]" id="skor_kesadaran"
                            class="form-control font-weight-bold" readonly
                            value="{{ $keadaanUmum['down_score']['total_skor'] ?? '' }}">
                        <input type="text" name="keadaan_umum[down_score][analisis]" id="analisis_kesadaran"
                            class="form-control font-weight-bold" readonly
                            value="{{ $keadaanUmum['down_score']['analisis'] ?? '' }}">
                    </div>
                </td>
            </tr>
        </tfoot>
    </table>
</div>

<div class="row mt-3">
    <div class="col-md-3 form-group">
        <label>Respirasi</label>
        <div class="input-group">
            <input type="number" name="keadaan_umum[tanda_vital][rr]" class="form-control"
                value="{{ $keadaanUmum['tanda_vital']['rr'] ?? '' }}">
            <div class="input-group-append"><span class="input-group-text">x/menit</span></div>
        </div>
    </div>
    <div class="col-md-3 form-group">
        <label>Nadi</label>
        <div class="input-group">
            <input type="number" name="keadaan_umum[tanda_vital][pr]" class="form-control"
                value="{{ $keadaanUmum['tanda_vital']['pr'] ?? '' }}">
            <div class="input-group-append"><span class="input-group-text">x/menit</span></div>
        </div>
    </div>
    <div class="col-md-3 form-group">
        <label>Suhu</label>
        <div class="input-group">
            <input type="number" step="0.1" name="keadaan_umum[tanda_vital][suhu]" class="form-control"
                value="{{ $keadaanUmum['tanda_vital']['suhu'] ?? '' }}">
            <div class="input-group-append"><span class="input-group-text">°C</span></div>
        </div>
    </div>
    <div class="col-md-3 form-group">
        <label>SpO2</label>
        <div class="input-group">
            <input type="number" name="keadaan_umum[tanda_vital][spo2]" class="form-control"
                value="{{ $keadaanUmum['tanda_vital']['spo2'] ?? '' }}">
            <div class="input-group-append"><span class="input-group-text">%</span></div>
        </div>
    </div>
</div>
