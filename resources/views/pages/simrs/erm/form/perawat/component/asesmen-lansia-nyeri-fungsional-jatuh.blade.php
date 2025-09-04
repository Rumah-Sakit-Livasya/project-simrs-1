@php
    $nyeri = $data['asesmen_nyeri'] ?? [];
    $fungsional = $data['status_fungsional'] ?? [];
    $jatuh = $data['resiko_jatuh_lansia'] ?? [];
@endphp

<hr>
<h4 class="text-primary mt-4 font-weight-bold">ASESMEN NYERI, STATUS FUNGSIONAL & RESIKO JATUH LANSIA</h4>

{{-- ================= ASESMEN NYERI (Sama seperti Dewasa) ================= --}}
<div class="card">
    <div class="card-header bg-light">
        <h5 class="mb-0"><b>Asesmen Nyeri</b></h5>
    </div>
    <div class="card-body">
        <p class="text-center"><b>Intensitas nyeri "Wong Baker Facer pain rating scale" dan "Numeric rating scale"
                (NRS)</b></p>
        <div class="row align-items-center">
            <div class="col-md-11">
                <div class="d-flex justify-content-around text-center flex-wrap wong-baker-scale-lansia">
                    @for ($i = 0; $i < 6; $i++)
                        <div class="p-2">
                            <img src="/img/nyeri/{{ $i + 1 }}.jpg" class="img-fluid"
                                style="max-width: 100px; border: 1px solid #ddd; border-radius: 5px; cursor: pointer;"
                                alt="Skala Nyeri Wong Baker">
                            <div class="mt-2">
                                @if ($i == 0)
                                    <span class="badge badge-pill badge-success pointer" data-skor="0">0</span>
                                @elseif($i == 1)
                                    <span class="badge badge-pill badge-success pointer" data-skor="1">1</span><span
                                        class="badge badge-pill badge-success pointer" data-skor="2">2</span>
                                @elseif($i == 2)
                                    <span class="badge badge-pill badge-info pointer" data-skor="3">3</span><span
                                        class="badge badge-pill badge-info pointer" data-skor="4">4</span>
                                @elseif($i == 3)
                                    <span class="badge badge-pill badge-primary pointer" data-skor="5">5</span><span
                                        class="badge badge-pill badge-primary pointer" data-skor="6">6</span>
                                @elseif($i == 4)
                                    <span class="badge badge-pill badge-warning pointer" data-skor="7">7</span><span
                                        class="badge badge-pill badge-warning pointer" data-skor="8">8</span>
                                @else<span class="badge badge-pill badge-danger pointer"
                                        data-skor="9">9</span><span class="badge badge-pill badge-danger pointer"
                                        data-skor="10">10</span>
                                @endif
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
            <div class="col-md-1 form-group"><label><b>Skor</b></label><input type="text"
                    name="asesmen_nyeri[skor_nyeri]" id="skor_nyeri_lansia"
                    class="form-control text-center font-weight-bold" style="font-size: 2rem; height: 60px;"
                    value="{{ $nyeri['skor_nyeri'] ?? '' }}" readonly></div>
        </div>
        @include('pages.simrs.erm.form.perawat.component.asesmen-nyeri-pqrst', [
            'data' => $nyeri['pqrst'] ?? [],
            'prefix' => 'asesmen_nyeri[pqrst]',
        ])
    </div>
</div>

{{-- ================= STATUS FUNGSIONAL (BARTHEL INDEX) ================= --}}
<div class="card mt-4">
    <div class="card-header bg-light">
        <h5 class="mb-0"><b>Status Fungsional (Barthel Index)</b></h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="text-center bg-light">
                    <tr>
                        <th>No</th>
                        <th>Keterangan</th>
                        <th>Skor</th>
                        <th>Pilihan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ([
        'defekasi' => ['label' => 'Mengendalikan rangsang defekasi', 'options' => ['Tak terkendali' => 0, 'Kadang tak terkendali' => 5, 'Terkendali teratur' => 10]],
        'berkemih' => ['label' => 'Mengendalikan rangsang berkemih', 'options' => ['Tak terkendali/pakai kateter' => 0, 'Kadang tak terkendali' => 5, 'Mandiri' => 10]],
        'membersihkan_diri' => ['label' => 'Membersihkan diri (cuci muka, sisir, sikat gigi)', 'options' => ['Butuh pertolongan' => 0, 'Mandiri' => 5]],
        'penggunaan_jamban' => ['label' => 'Penggunaan jamban', 'options' => ['Tergantung pertolongan' => 0, 'Perlu bantuan sebagian' => 5, 'Mandiri' => 10]],
        'makan' => ['label' => 'Makan', 'options' => ['Tidak mampu' => 0, 'Perlu bantuan memotong' => 5, 'Mandiri' => 10]],
        'berbaring_ke_duduk' => ['label' => 'Berubah sikap dari berbaring ke duduk', 'options' => ['Tidak mampu' => 0, 'Perlu banyak bantuan (2 org)' => 5, 'Mandiri' => 10]],
        'berpindah_berjalan' => ['label' => 'Berpindah / berjalan', 'options' => ['Tidak mampu' => 0, 'Bisa dengan kursi roda' => 5, 'Bantuan 1 orang' => 10, 'Mandiri' => 15]],
        'memakai_baju' => ['label' => 'Memakai baju', 'options' => ['Tergantung orang lain' => 0, 'Sebagian dibantu' => 5, 'Mandiri' => 10]],
        'naik_turun_tangga' => ['label' => 'Naik turun tangga', 'options' => ['Tidak mampu' => 0, 'Butuh pertolongan' => 5, 'Mandiri' => 10]],
        'mandi' => ['label' => 'Mandi', 'options' => ['Tergantung orang lain' => 0, 'Mandiri' => 5]],
    ] as $key => $item)
                        <tr>
                            <td class="text-center align-middle"><b>{{ $loop->iteration }}</b></td>
                            <td class="align-middle"><b>{{ $item['label'] }}</b></td>
                            <td colspan="2">
                                @foreach ($item['options'] as $pilihan => $skor)
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="fungsional_{{ $key }}_{{ $skor }}"
                                            name="status_fungsional[barthel_index][{{ $key }}]"
                                            value="{{ $skor }}" class="custom-control-input skor_fungsional"
                                            data-skor="{{ $skor }}" @checked(isset($fungsional['barthel_index'][$key]) && $fungsional['barthel_index'][$key] == $skor)>
                                        <label class="custom-control-label"
                                            for="fungsional_{{ $key }}_{{ $skor }}">{{ $pilihan }}
                                            <span class="badge badge-secondary">{{ $skor }}</span></label>
                                    </div>
                                @endforeach
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-light">
                    <tr>
                        <th class="text-right" colspan="3">Total Skor Barthel Index</th>
                        <td><input type="text" name="status_fungsional[barthel_index][total_skor]"
                                id="hasil_skor_fungsional" class="form-control font-weight-bold" readonly
                                value="{{ $fungsional['barthel_index']['total_skor'] ?? '' }}"></td>
                    </tr>
                    <tr>
                        <th class="text-right" colspan="3">Analisis</th>
                        <td><input type="text" name="status_fungsional[barthel_index][analisis]"
                                id="analisis_skor_fungsional" class="form-control font-weight-bold" readonly
                                value="{{ $fungsional['barthel_index']['analisis'] ?? '' }}"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="row">
            <div class="col-md-6 form-group">
                <label>Perlu bantuan, sebutkan:</label>
                <input type="text" name="status_fungsional[perlu_bantuan]" class="form-control"
                    value="{{ $fungsional['perlu_bantuan'] ?? '' }}">
            </div>
            <div class="col-md-6 form-group">
                <label>Alat bantu jalan, sebutkan:</label>
                <input type="text" name="status_fungsional[alat_bantu_jalan]" class="form-control"
                    value="{{ $fungsional['alat_bantu_jalan'] ?? '' }}">
            </div>
        </div>
    </div>
</div>

{{-- ================= PENGKAJIAN RESIKO JATUH LANSIA ================= --}}
<div class="card mt-4">
    <div class="card-header bg-light">
        <h5 class="mb-0"><b>Pengkajian Resiko Jatuh Lansia (> 60 tahun)</b></h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="text-center bg-light">
                    <tr>
                        <th>No</th>
                        <th>Skrining</th>
                        <th>Jawaban</th>
                        <th>Keterangan</th>
                        <th>Skor</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $skriningJatuh = [
                            'riwayat_jatuh' => [
                                'label' => 'Apakah pasien datang ke RS karena jatuh?',
                                'options' => ['ya' => 6, 'tidak' => 0],
                                'keterangan' => 'Salah satu Ya = 6',
                            ],
                            'status_mental' => [
                                'label' => 'Status Mental',
                                'options' => ['delirium' => 14, 'disorientasi' => 14, 'agitasi' => 14],
                                'keterangan' => 'Salah satu Ya = 14',
                            ],
                            'penglihatan' => [
                                'label' => 'Penglihatan',
                                'options' => ['kacamata' => 1, 'buram' => 1, 'glaukoma_katarak' => 1],
                                'keterangan' => 'Salah satu Ya = 1',
                            ],
                            'kebiasaan_berkemih' => [
                                'label' => 'Kebiasaan Berkemih',
                                'options' => ['perubahan_perilaku' => 2],
                                'keterangan' => 'Ya = 2',
                            ],
                        ];
                    @endphp
                    {{-- Loop untuk skrining sederhana --}}
                    @foreach ($skriningJatuh as $key => $item)
                        <tr>
                            <td class="text-center align-middle"><b>{{ $loop->iteration }}</b></td>
                            <td class="align-middle"><b>{{ $item['label'] }}</b></td>
                            <td>
                                @foreach ($item['options'] as $optionKey => $skor)
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox"
                                            id="jatuh_lansia_{{ $key }}_{{ $optionKey }}"
                                            name="resiko_jatuh_lansia[skrining][{{ $key }}][{{ $optionKey }}]"
                                            value="{{ $skor }}"
                                            class="custom-control-input checkbox-skor-lansia"
                                            data-group="{{ $key }}" data-skor="{{ $skor }}"
                                            @checked(isset($jatuh['skrining'][$key][$optionKey]))>
                                        <label class="custom-control-label"
                                            for="jatuh_lansia_{{ $key }}_{{ $optionKey }}">
                                            @if ($item['label'] == 'Status Mental')
                                                @if ($optionKey == 'delirium')
                                                    Apakah pasien delirium?
                                                @elseif($optionKey == 'disorientasi')
                                                    Apakah pasien disorientasi?
                                                @else
                                                    Apakah pasien agitasi?
                                                @endif
                                            @else
                                                {{ ucwords(str_replace('_', ' ', $optionKey)) }}
                                            @endif
                                        </label>
                                    </div>
                                @endforeach
                            </td>
                            <td class="text-center align-middle">{{ $item['keterangan'] }}</td>
                            <td><input type="text" id="skor_jatuh_{{ $key }}"
                                    name="resiko_jatuh_lansia[skrining][{{ $key }}][skor]"
                                    class="form-control skor-jatuh-group" readonly
                                    value="{{ $jatuh['skrining'][$key]['skor'] ?? '' }}"></td>
                        </tr>
                    @endforeach

                    {{-- Untuk Transfer & Mobilitas --}}
                    <tr>
                        <td class="text-center align-middle" rowspan="2"><b>5 & 6</b></td>
                        <td class="align-middle"><b>Transfer & Mobilitas</b></td>
                        <td colspan="2">
                            @foreach ([
        'transfer' => ['label' => 'Transfer', 'options' => ['Mandiri' => 0, 'Bantuan 1 orang' => 1, 'Bantuan 2 orang' => 2, 'Tidak seimbang/Bantuan total' => 3]],
        'mobilitas' => ['label' => 'Mobilitas', 'options' => ['Mandiri' => 0, 'Bantuan 1 orang' => 1, 'Bantuan 2 orang' => 2, 'Tidak bisa bergerak' => 3]],
    ] as $key => $item)
                                <div class="form-group">
                                    <label><b>{{ $item['label'] }}</b></label>
                                    <select class="form-control transfer-mobilitas"
                                        name="resiko_jatuh_lansia[transfer_mobilitas][{{ $key }}]"
                                        data-group="transfer_mobilitas">
                                        @foreach ($item['options'] as $pilihan => $skor)
                                            <option value="{{ $skor }}" @selected(isset($jatuh['transfer_mobilitas'][$key]) && $jatuh['transfer_mobilitas'][$key] == $skor)>
                                                {{ $pilihan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endforeach
                        </td>
                        <td><input type="text" id="skor_jatuh_transfer_mobilitas"
                                name="resiko_jatuh_lansia[transfer_mobilitas][skor]"
                                class="form-control skor-jatuh-group" readonly
                                value="{{ $jatuh['transfer_mobilitas']['skor'] ?? '' }}"></td>
                    </tr>
                </tbody>
                <tfoot class="bg-light">
                    <tr>
                        <th colspan="4" class="text-right">Total Skor Resiko Jatuh</th>
                        <th><input type="text" name="resiko_jatuh_lansia[total_skor]" id="total_skor_jatuh_lansia"
                                class="form-control font-weight-bold" readonly
                                value="{{ $jatuh['total_skor'] ?? '' }}"></th>
                    </tr>
                    <tr>
                        <th colspan="4" class="text-right">Analisis</th>
                        <th><input type="text" name="resiko_jatuh_lansia[analisis]" id="analisis_jatuh_lansia"
                                class="form-control font-weight-bold" readonly
                                value="{{ $jatuh['analisis'] ?? '' }}"></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
