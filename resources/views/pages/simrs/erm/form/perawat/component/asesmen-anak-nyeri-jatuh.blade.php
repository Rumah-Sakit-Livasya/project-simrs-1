@php
    $nyeri = $data['asesmen_nyeri_anak'] ?? [];
    $jatuh = $data['resiko_jatuh_anak'] ?? [];
@endphp

<hr>
<h4 class="text-primary mt-4 font-weight-bold">ASESMEN NYERI & RESIKO JATUH ANAK</h4>

{{-- ================= ASESMEN NYERI ANAK ================= --}}
<div class="card">
    <div class="card-header bg-light">
        <h5 class="mb-0"><b>Asesmen Nyeri Anak</b></h5>
    </div>
    <div class="card-body">
        {{-- Wong Baker Pain Scale --}}
        <p class="text-center"><b>Intensitas Nyeri “Wong Baker Facer pain rating scale” dan Numeric rating scale” (NRS)
                (Untuk usia > 6 tahun)</b></p>
        <div class="row align-items-center">
            <div class="col-md-11">
                <div class="d-flex justify-content-around text-center flex-wrap wong-baker-scale-anak">
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
                    name="asesmen_nyeri_anak[wong_baker_skor]" id="skor_nyeri_anak"
                    class="form-control text-center font-weight-bold" style="font-size: 2rem; height: 60px;"
                    value="{{ $nyeri['wong_baker_skor'] ?? '' }}" readonly></div>
        </div>

        {{-- FLACC Scale --}}
        <p class="text-center mt-4"><b>Untuk anak kurang dari 6 tahun skala yang digunakan FLACC</b></p>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="text-center bg-light">
                    <tr>
                        <th>Pengkajian</th>
                        <th>Skor 0</th>
                        <th>Skor 1</th>
                        <th>Skor 2</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (['wajah' => ['label' => 'Wajah', 'options' => ['Senyum' => 0, 'Meringis/Menarik diri' => 1, 'Sering mengetarkan dagu, mengatupkan rahang' => 2]], 'kaki' => ['label' => 'Kaki', 'options' => ['Gerak normal/Relaksasi' => 0, 'Tegang/Tidak tenang' => 1, 'Kaki menendang/menarik diri' => 2]], 'aktivitas' => ['label' => 'Aktivitas', 'options' => ['Tidur, Posisi normal' => 0, 'Gerak menggeliat, kaku, berguling' => 1, 'Melengkungkan punggung' => 2]], 'menangis' => ['label' => 'Menangis', 'options' => ['Tidak menangis (bangun/tidur)' => 0, 'Mengerang/Merengek' => 1, 'Menangis terus menerus, menjerit' => 2]], 'bersuara' => ['label' => 'Bersuara', 'options' => ['Bersuara normal' => 0, 'Tenang bila dipeluk' => 1, 'Sulit untuk menenangkan' => 2]]] as $key => $item)
                        <tr>
                            <td class="align-middle"><b>{{ $item['label'] }}</b></td>
                            @foreach ($item['options'] as $pilihan => $skor)
                                <td>
                                    <div class="custom-control custom-radio"><input type="radio"
                                            id="flacc_{{ $key }}_{{ $skor }}"
                                            name="asesmen_nyeri_anak[flacc][{{ $key }}]"
                                            value="{{ $skor }}" class="custom-control-input skor_flacc"
                                            data-skor="{{ $skor }}" @checked(isset($nyeri['flacc'][$key]) && $nyeri['flacc'][$key] == $skor)><label
                                            class="custom-control-label"
                                            for="flacc_{{ $key }}_{{ $skor }}">{{ $pilihan }}</label>
                                    </div>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-light">
                    <tr>
                        <th class="text-right" colspan="3">Total Skor FLACC</th>
                        <td><input type="text" name="asesmen_nyeri_anak[flacc][total_skor]" id="jumlah_skor_flacc"
                                class="form-control font-weight-bold" readonly
                                value="{{ $nyeri['flacc']['total_skor'] ?? '' }}"></td>
                    </tr>
                    <tr>
                        <th class="text-right" colspan="3">Analisis FLACC</th>
                        <td><input type="text" name="asesmen_nyeri_anak[flacc][analisis]" id="analisis_flacc"
                                class="form-control font-weight-bold" readonly
                                value="{{ $nyeri['flacc']['analisis'] ?? '' }}"></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        @include('pages.simrs.erm.form.perawat.component.asesmen-nyeri-pqrst', [
            'data' => $nyeri['pqrst'] ?? [],
            'prefix' => 'asesmen_nyeri_anak[pqrst]',
        ])
    </div>
</div>

{{-- ================= RESIKO JATUH ANAK (HUMPTY DUMPTY) ================= --}}
<div class="card mt-4">
    <div class="card-header bg-light">
        <h5 class="mb-0"><b>Pengkajian Resiko Jatuh Anak (Skala Humpty Dumpty)</b></h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            @php
                $humptyDumpty = [
                    'usia' => [
                        'label' => 'Usia',
                        'options' => [
                            '< 3 tahun' => 4,
                            '3 - 7 tahun' => 3,
                            '7 - 13 tahun' => 2,
                            '>= 13 tahun' => 1,
                        ],
                    ],
                    'jenis_kelamin' => [
                        'label' => 'Jenis Kelamin',
                        'options' => [
                            'Laki-laki' => 2,
                            'Perempuan' => 1,
                        ],
                    ],
                    'diagnosis' => [
                        'label' => 'Diagnosis',
                        'options' => [
                            'Diagnosis neurologi' => 4,
                            'Perubahan oksigenasi (respiratorik, dehidrasi, anemia, anoreksia, sinkop, pusing, dsb.)' => 3,
                            'Gangguan perilaku/psikiatri' => 2,
                            'Diagnosis lainnya' => 1,
                        ],
                    ],
                    'gangguan_kognitif' => [
                        'label' => 'Gangguan Kognitif',
                        'options' => [
                            'Tidak menyadari keterbatasan dirinya' => 3,
                            'Lupa akan adanya keterbatasan' => 2,
                            'Orientasi baik terhadap diri sendiri' => 1,
                        ],
                    ],
                    'faktor_lingkungan' => [
                        'label' => 'Faktor Lingkungan',
                        'options' => [
                            'Riwayat jatuh / Bayi diletakkan di tempat tidur dewasa' => 4,
                            'Pasien menggunakan alat bantu / Bayi diletakkan dalam tempat tidur bayi/perabot rumah' => 3,
                            'Pasien diletakkan di tempat tidur' => 2,
                            'Area di luar rumah sakit' => 1,
                        ],
                    ],
                    'respon_terhadap_operasi' => [
                        'label' => 'Respons terhadap Pembedahan/Sedasi/Anastesi',
                        'options' => [
                            'Dalam 24 jam' => 3,
                            'Dalam 48 jam' => 2,
                            '> 48 jam / Tidak menjalani pembedahan' => 1,
                        ],
                    ],
                    'penggunaan_medikamentosa' => [
                        'label' => 'Penggunaan Medikamentosa',
                        'options' => [
                            'Penggunaan multipel: sedatif, hipnosis, barbiturat, fenotiazin, antidepresan, pencahar, diuretik, narkose' => 3,
                            'Penggunaan salah satu obat di atas' => 2,
                            'Penggunaan medikasi lain / Tidak ada medikasi' => 1,
                        ],
                    ],
                ];
            @endphp

            <table class="table table-bordered table-hover">
                <thead class="bg-light">
                    <tr>
                        <th>Parameter</th>
                        @foreach ($humptyDumpty as $key => $item)
                            @foreach ($item['options'] as $pilihan => $skor)
                                <th class="text-center">{{ $pilihan }}<br><span
                                        class="badge badge-secondary">{{ $skor }}</span></th>
                            @endforeach
                        @break
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($humptyDumpty as $key => $item)
                    <tr>
                        <td class="align-middle"><b>{{ $item['label'] }}</b></td>
                        @foreach ($item['options'] as $pilihan => $skor)
                            <td class="text-center align-middle">
                                <div class="custom-control custom-radio d-inline-block">
                                    <input type="radio" id="jatuh_anak_{{ $key }}_{{ $skor }}"
                                        name="resiko_jatuh_anak[humpty_dumpty][{{ $key }}]"
                                        value="{{ $skor }}" class="custom-control-input humpty"
                                        data-skor="{{ $skor }}" @checked(isset($jatuh['humpty_dumpty'][$key]) && $jatuh['humpty_dumpty'][$key] == $skor)>
                                    <label class="custom-control-label"
                                        for="jatuh_anak_{{ $key }}_{{ $skor }}"></label>
                                </div>
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-light">
                <tr>
                    <th class="text-right"
                        colspan="{{ count($humptyDumpty[array_key_first($humptyDumpty)]['options']) + 1 }}">Total
                        Skor Humpty Dumpty</th>
                    <td>
                        <input type="text" name="resiko_jatuh_anak[humpty_dumpty][total_skor]" id="skor_humpty"
                            class="form-control font-weight-bold bg-white" readonly
                            value="{{ $jatuh['humpty_dumpty']['total_skor'] ?? '' }}">
                    </td>
                </tr>
                <tr>
                    <th class="text-right"
                        colspan="{{ count($humptyDumpty[array_key_first($humptyDumpty)]['options']) + 1 }}">
                        Analisis Resiko</th>
                    <td>
                        <input type="text" name="resiko_jatuh_anak[humpty_dumpty][analisis]"
                            id="analisis_humpty" class="form-control font-weight-bold bg-white" readonly
                            value="{{ $jatuh['humpty_dumpty']['analisis'] ?? '' }}">
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
</div>
