@php
    $nyeri = $data['asesmen_nyeri'] ?? [];
    $jatuh = $data['resiko_jatuh_dewasa'] ?? [];
@endphp

<hr>
<h4 class="text-primary mt-4 font-weight-bold">ASESMEN NYERI & RESIKO JATUH</h4>

{{-- ================= ASESMEN NYERI ================= --}}
<div class="card">
    <div class="card-header bg-light">
        <h5 class="mb-0"><b>Asesmen Nyeri</b></h5>
    </div>
    <div class="card-body">
        <p class="text-center"><b>Intensitas nyeri "Wong Baker Facer pain rating scale" dan "Numeric rating scale"
                (NRS)</b><br><small>(Untuk usia lebih dari 6 tahun)</small></p>
        <div class="row align-items-center">
            <div class="col-md-11">
                <div class="d-flex justify-content-around text-center flex-wrap wong-baker-scale">
                    @for ($i = 0; $i < 6; $i++)
                        <div class="p-2">
                            <img src="/img/nyeri/{{ $i + 1 }}.jpg" class="img-fluid"
                                style="max-width: 100px; border-radius: 5px; cursor: pointer;"
                                alt="Skala Nyeri Wong Baker">
                            <div class="mt-2">
                                @if ($i == 0)
                                    <span class="badge badge-pill badge-success pointer" data-skor="0">0</span>
                                @elseif($i == 1)
                                    <span class="badge badge-pill badge-success pointer" data-skor="1">1</span>
                                    <span class="badge badge-pill badge-success pointer" data-skor="2">2</span>
                                @elseif($i == 2)
                                    <span class="badge badge-pill badge-info pointer" data-skor="3">3</span>
                                    <span class="badge badge-pill badge-info pointer" data-skor="4">4</span>
                                @elseif($i == 3)
                                    <span class="badge badge-pill badge-primary pointer" data-skor="5">5</span>
                                    <span class="badge badge-pill badge-primary pointer" data-skor="6">6</span>
                                @elseif($i == 4)
                                    <span class="badge badge-pill badge-warning pointer" data-skor="7">7</span>
                                    <span class="badge badge-pill badge-warning pointer" data-skor="8">8</span>
                                @else
                                    <span class="badge badge-pill badge-danger pointer" data-skor="9">9</span>
                                    <span class="badge badge-pill badge-danger pointer" data-skor="10">10</span>
                                @endif
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
            <div class="col-md-1 form-group">
                <label for="skor_nyeri"><b>Skor</b></label>
                <input type="text" name="asesmen_nyeri[skor_nyeri]" id="skor_nyeri"
                    class="form-control text-center font-weight-bold" style="font-size: 2rem; height: 60px;"
                    value="{{ $nyeri['skor_nyeri'] ?? '' }}" readonly>
            </div>
        </div>
        <div class="table-responsive mt-3">
            <table class="table table-bordered">
                <thead class="text-center bg-light">
                    <tr>
                        <th style="width: 25%">Tanggal & Pukul</th>
                        <th>Kategori</th>
                        <th>Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (['provocati' => 'Provocati/Penyebab', 'qualitas' => 'Qualitas/Kualitas', 'region' => 'Region/Area', 'skala' => 'Skala/Skor', 'timing' => 'Timing/Waktu'] as $key => $label)
                        <tr>
                            <td>
                                <div class="d-flex">
                                    <input type="date" name="asesmen_nyeri[{{ $key }}][tanggal]"
                                        class="form-control mr-2" value="{{ $nyeri[$key]['tanggal'] ?? '' }}">
                                    <input type="time" name="asesmen_nyeri[{{ $key }}][jam]"
                                        class="form-control" value="{{ $nyeri[$key]['jam'] ?? '' }}">
                                </div>
                            </td>
                            <td class="align-middle text-center"><b>{{ $label }}</b></td>
                            <td>
                                <textarea class="form-control" name="asesmen_nyeri[{{ $key }}][deskripsi]" rows="2">{{ $nyeri[$key]['deskripsi'] ?? '' }}</textarea>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ================= RESIKO JATUH DEWASA (SKALA MORSE) ================= --}}
<div class="card mt-4">
    <div class="card-header bg-light">
        <h5 class="mb-0"><b>Pengkajian Resiko Jatuh Dewasa (Skala Morse)</b></h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="bg-light">
                    <tr>
                        <th>Parameter</th>
                        <th>Pilihan</th>
                        <th class="text-center">Skor</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ([
        'history_of_falling' => ['label' => 'Riwayat Jatuh (< 3 bulan)', 'options' => ['Tidak' => 0, 'Ya' => 25]],
        'secondary_diagnosis' => ['label' => 'Diagnosa Sekunder (> 1 penyakit)', 'options' => ['Tidak' => 0, 'Ya' => 15]],
        'ambulatory_aid' => ['label' => 'Alat Bantu Jalan', 'options' => ['Bed rest/dibantu perawat' => 0, 'Kruk/tongkat/walker' => 15, 'Berpegangan pada perabot' => 30]],
        'iv_heparin_lock' => ['label' => 'Terapi Intravena / Menggunakan heparin lock', 'options' => ['Tidak' => 0, 'Ya' => 20]],
        'gait_transferring' => ['label' => 'Cara Berjalan/Berpindah', 'options' => ['Normal/bed rest/imobil' => 0, 'Lemah' => 10, 'Terganggu' => 20]],
        'mental_status' => ['label' => 'Status Mental', 'options' => ['Sadar akan kemampuan diri' => 0, 'Lupa keterbatasan diri' => 15]],
    ] as $key => $item)
                        <tr>
                            <td class="align-middle"><b>{{ $item['label'] }}</b></td>
                            <td colspan="2">
                                @foreach ($item['options'] as $pilihan => $skor)
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="jatuh_{{ $key }}_{{ $skor }}"
                                            name="resiko_jatuh_dewasa[morse_fall][{{ $key }}]"
                                            value="{{ $skor }}" class="custom-control-input morse_fall"
                                            data-skor="{{ $skor }}" @checked(isset($jatuh['morse_fall'][$key]) && $jatuh['morse_fall'][$key] == $skor)>
                                        <label class="custom-control-label"
                                            for="jatuh_{{ $key }}_{{ $skor }}">{{ $pilihan }}
                                            <span class="badge badge-secondary">{{ $skor }}</span></label>
                                    </div>
                                @endforeach
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-light">
                        <th class="text-right" colspan="2">Total Skor</th>
                        <td><input type="text" name="resiko_jatuh_dewasa[morse_fall][total_skor]"
                                id="skor_morse_fall" class="form-control font-weight-bold bg-white" readonly
                                value="{{ $jatuh['morse_fall']['total_skor'] ?? '' }}"></td>
                    </tr>
                    <tr class="bg-light">
                        <th class="text-right" colspan="2">Analisis Resiko</th>
                        <td><input type="text" name="resiko_jatuh_dewasa[morse_fall][analisis]"
                                id="analisis_morse_fall" class="form-control font-weight-bold bg-white" readonly
                                value="{{ $jatuh['morse_fall']['analisis'] ?? '' }}"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
