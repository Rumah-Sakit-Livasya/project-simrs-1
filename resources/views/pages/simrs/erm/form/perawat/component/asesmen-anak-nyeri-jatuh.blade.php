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
<div class="col-md-12">
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0 text-center font-weight-bold">PENGKAJIAN RISIKO JATUH ANAK</h5>
        </div>
        <div class="card-body">
            <div class="mb-2">
                <h6 class="mb-3 font-weight-bold text-success text-center">ANAK (1 BLN - 17 TH)</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <tbody>
                        <tr class="bg-light">
                            <th class="col-xs-2 text-center">Parameter</th>
                            <th class="col-xs-2 text-center">Skor : 4</th>
                            <th class="col-xs-3 text-center">Skor : 3</th>
                            <th class="col-xs-3 text-center">Skor : 2</th>
                            <th class="col-xs-2 text-center">Skor : 1</th>
                        </tr>
                        {{-- Usia --}}
                        <tr>
                            <td>Usia</td>
                            <td>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="humpty_usia_4"
                                        name="resiko_jatuh_anak[humpty_dumpty][usia]" value="< 3 tahun" data-skor="4"
                                        class="custom-control-input humpty" @checked(isset($jatuh['humpty_dumpty']['usia']) && $jatuh['humpty_dumpty']['usia'] == 4)>
                                    <label class="custom-control-label" for="humpty_usia_4">&lt; 3 tahun</label>
                                </div>
                            </td>
                            <td>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="humpty_usia_3"
                                        name="resiko_jatuh_anak[humpty_dumpty][usia]" value="3 - 7 tahun"
                                        data-skor="3" class="custom-control-input humpty"
                                        @checked(isset($jatuh['humpty_dumpty']['usia']) && $jatuh['humpty_dumpty']['usia'] == 3)>
                                    <label class="custom-control-label" for="humpty_usia_3">3 - 7 tahun</label>
                                </div>
                            </td>
                            <td>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="humpty_usia_2"
                                        name="resiko_jatuh_anak[humpty_dumpty][usia]" value="7 - 13 tahun"
                                        data-skor="2" class="custom-control-input humpty"
                                        @checked(isset($jatuh['humpty_dumpty']['usia']) && $jatuh['humpty_dumpty']['usia'] == 2)>
                                    <label class="custom-control-label" for="humpty_usia_2">7 - 13 tahun</label>
                                </div>
                            </td>
                            <td>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="humpty_usia_1"
                                        name="resiko_jatuh_anak[humpty_dumpty][usia]" value=">= 13 tahun"
                                        data-skor="1" class="custom-control-input humpty"
                                        @checked(isset($jatuh['humpty_dumpty']['usia']) && $jatuh['humpty_dumpty']['usia'] == 1)>
                                    <label class="custom-control-label" for="humpty_usia_1">&ge; 13 tahun</label>
                                </div>
                            </td>
                        </tr>
                        {{-- Jenis Kelamin --}}
                        <tr>
                            <td>Jenis kelamin</td>
                            <td>
                                <div class="custom-control custom-radio">
                                    <label class="custom-control-label">&nbsp;</label>
                                </div>
                            </td>
                            <td>
                                <div class="custom-control custom-radio">
                                    <label class="custom-control-label">&nbsp;</label>
                                </div>
                            </td>
                            <td>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="humpty_jk_2"
                                        name="resiko_jatuh_anak[humpty_dumpty][jenis_kelamin]" value="Laki-laki"
                                        data-skor="2" class="custom-control-input humpty"
                                        @checked(isset($jatuh['humpty_dumpty']['jenis_kelamin']) && $jatuh['humpty_dumpty']['jenis_kelamin'] == 2)>
                                    <label class="custom-control-label" for="humpty_jk_2">Laki-laki</label>
                                </div>
                            </td>
                            <td>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="humpty_jk_1"
                                        name="resiko_jatuh_anak[humpty_dumpty][jenis_kelamin]" value="Perempuan"
                                        data-skor="1" class="custom-control-input humpty"
                                        @checked(isset($jatuh['humpty_dumpty']['jenis_kelamin']) && $jatuh['humpty_dumpty']['jenis_kelamin'] == 1)>
                                    <label class="custom-control-label" for="humpty_jk_1">Perempuan</label>
                                </div>
                            </td>
                        </tr>
                        {{-- Diagnosis --}}
                        <tr>
                            <td>Diagnosis</td>
                            <td>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="humpty_diag_4"
                                        name="resiko_jatuh_anak[humpty_dumpty][diagnosis]" value="Diagnosis neurologi"
                                        data-skor="4" class="custom-control-input humpty"
                                        @checked(isset($jatuh['humpty_dumpty']['diagnosis']) && $jatuh['humpty_dumpty']['diagnosis'] == 4)>
                                    <label class="custom-control-label" for="humpty_diag_4">Diagnosis
                                        neurologi</label>
                                </div>
                            </td>
                            <td>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="humpty_diag_3"
                                        name="resiko_jatuh_anak[humpty_dumpty][diagnosis]"
                                        value="Perubahan oksigenasi (diagnosis respiratorik, dehidrasi, anemia, anoreksia, sinkop, pusing, dsb.)"
                                        data-skor="3" class="custom-control-input humpty"
                                        @checked(isset($jatuh['humpty_dumpty']['diagnosis']) && $jatuh['humpty_dumpty']['diagnosis'] == 3)>
                                    <label class="custom-control-label" for="humpty_diag_3">Perubahan oksigenasi
                                        (diagnosis respiratorik, dehidrasi, anemia, anoreksia, sinkop, pusing,
                                        dsb.)</label>
                                </div>
                            </td>
                            <td>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="humpty_diag_2"
                                        name="resiko_jatuh_anak[humpty_dumpty][diagnosis]"
                                        value="Gangguan perilaku/psikiatri" data-skor="2"
                                        class="custom-control-input humpty" @checked(isset($jatuh['humpty_dumpty']['diagnosis']) && $jatuh['humpty_dumpty']['diagnosis'] == 2)>
                                    <label class="custom-control-label" for="humpty_diag_2">Gangguan
                                        perilaku/psikiatri</label>
                                </div>
                            </td>
                            <td>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="humpty_diag_1"
                                        name="resiko_jatuh_anak[humpty_dumpty][diagnosis]" value="Diagnosis lainnya"
                                        data-skor="1" class="custom-control-input humpty"
                                        @checked(isset($jatuh['humpty_dumpty']['diagnosis']) && $jatuh['humpty_dumpty']['diagnosis'] == 1)>
                                    <label class="custom-control-label" for="humpty_diag_1">Diagnosis lainnya</label>
                                </div>
                            </td>
                        </tr>
                        {{-- Gangguan Kognitif --}}
                        <tr>
                            <td>Gangguan kognitif</td>
                            <td>
                                <div class="custom-control custom-radio">
                                    <label class="custom-control-label">&nbsp;</label>
                                </div>
                            </td>
                            <td>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="humpty_kog_3"
                                        name="resiko_jatuh_anak[humpty_dumpty][gangguan_kognitif]"
                                        value="Tidak menyadari keterbatasan dirinya" data-skor="3"
                                        class="custom-control-input humpty" @checked(isset($jatuh['humpty_dumpty']['gangguan_kognitif']) && $jatuh['humpty_dumpty']['gangguan_kognitif'] == 3)>
                                    <label class="custom-control-label" for="humpty_kog_3">Tidak menyadari
                                        keterbatasan dirinya</label>
                                </div>
                            </td>
                            <td>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="humpty_kog_2"
                                        name="resiko_jatuh_anak[humpty_dumpty][gangguan_kognitif]"
                                        value="Lupa akan adanya keterbatasan" data-skor="2"
                                        class="custom-control-input humpty" @checked(isset($jatuh['humpty_dumpty']['gangguan_kognitif']) && $jatuh['humpty_dumpty']['gangguan_kognitif'] == 2)>
                                    <label class="custom-control-label" for="humpty_kog_2">Lupa akan adanya
                                        keterbatasan</label>
                                </div>
                            </td>
                            <td>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="humpty_kog_1"
                                        name="resiko_jatuh_anak[humpty_dumpty][gangguan_kognitif]"
                                        value="Orientasi baik terhadap diri sendiri" data-skor="1"
                                        class="custom-control-input humpty" @checked(isset($jatuh['humpty_dumpty']['gangguan_kognitif']) && $jatuh['humpty_dumpty']['gangguan_kognitif'] == 1)>
                                    <label class="custom-control-label" for="humpty_kog_1">Orientasi baik terhadap
                                        diri sendiri</label>
                                </div>
                            </td>
                        </tr>
                        {{-- Faktor Lingkungan --}}
                        <tr>
                            <td>Faktor lingkungan</td>
                            <td>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="humpty_ling_4"
                                        name="resiko_jatuh_anak[humpty_dumpty][faktor_lingkungan]"
                                        value="Riwayat jatuh / Bayi diletakkan di tempat tidur dewasa" data-skor="4"
                                        class="custom-control-input humpty" @checked(isset($jatuh['humpty_dumpty']['faktor_lingkungan']) && $jatuh['humpty_dumpty']['faktor_lingkungan'] == 4)>
                                    <label class="custom-control-label" for="humpty_ling_4">Riwayat jatuh / Bayi
                                        diletakkan di tempat tidur dewasa</label>
                                </div>
                            </td>
                            <td>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="humpty_ling_3"
                                        name="resiko_jatuh_anak[humpty_dumpty][faktor_lingkungan]"
                                        value="Pasien menggunakan alat bantu / Bayi diletakkan dalam tempat tidur bayi/perabot rumah"
                                        data-skor="3" class="custom-control-input humpty"
                                        @checked(isset($jatuh['humpty_dumpty']['faktor_lingkungan']) && $jatuh['humpty_dumpty']['faktor_lingkungan'] == 3)>
                                    <label class="custom-control-label" for="humpty_ling_3">Pasien menggunakan alat
                                        bantu / Bayi diletakkan dalam tempat tidur bayi/perabot rumah</label>
                                </div>
                            </td>
                            <td>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="humpty_ling_2"
                                        name="resiko_jatuh_anak[humpty_dumpty][faktor_lingkungan]"
                                        value="Pasien diletakkan di tempat tidur" data-skor="2"
                                        class="custom-control-input humpty" @checked(isset($jatuh['humpty_dumpty']['faktor_lingkungan']) && $jatuh['humpty_dumpty']['faktor_lingkungan'] == 2)>
                                    <label class="custom-control-label" for="humpty_ling_2">Pasien diletakkan di
                                        tempat tidur</label>
                                </div>
                            </td>
                            <td>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="humpty_ling_1"
                                        name="resiko_jatuh_anak[humpty_dumpty][faktor_lingkungan]"
                                        value="Area di luar rumah sakit" data-skor="1"
                                        class="custom-control-input humpty" @checked(isset($jatuh['humpty_dumpty']['faktor_lingkungan']) && $jatuh['humpty_dumpty']['faktor_lingkungan'] == 1)>
                                    <label class="custom-control-label" for="humpty_ling_1">Area di luar rumah
                                        sakit</label>
                                </div>
                            </td>
                        </tr>
                        {{-- Respon terhadap operasi --}}
                        <tr>
                            <td>Respons terhadap pembedahan/sedasi/anastesi</td>
                            <td>
                                <div class="custom-control custom-radio">
                                    <label class="custom-control-label">&nbsp;</label>
                                </div>
                            </td>
                            <td>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="humpty_respon_3"
                                        name="resiko_jatuh_anak[humpty_dumpty][respon_terhadap_operasi]"
                                        value="Dalam 24 jam" data-skor="3" class="custom-control-input humpty"
                                        @checked(isset($jatuh['humpty_dumpty']['respon_terhadap_operasi']) &&
                                                $jatuh['humpty_dumpty']['respon_terhadap_operasi'] == 3)>
                                    <label class="custom-control-label" for="humpty_respon_3">Dalam 24 jam</label>
                                </div>
                            </td>
                            <td>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="humpty_respon_2"
                                        name="resiko_jatuh_anak[humpty_dumpty][respon_terhadap_operasi]"
                                        value="Dalam 48 jam" data-skor="2" class="custom-control-input humpty"
                                        @checked(isset($jatuh['humpty_dumpty']['respon_terhadap_operasi']) &&
                                                $jatuh['humpty_dumpty']['respon_terhadap_operasi'] == 2)>
                                    <label class="custom-control-label" for="humpty_respon_2">Dalam 48 jam</label>
                                </div>
                            </td>
                            <td>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="humpty_respon_1"
                                        name="resiko_jatuh_anak[humpty_dumpty][respon_terhadap_operasi]"
                                        value="> 48 jam / Tidak menjalani pembedahan/sedasi/anestesi" data-skor="1"
                                        class="custom-control-input humpty" @checked(isset($jatuh['humpty_dumpty']['respon_terhadap_operasi']) &&
                                                $jatuh['humpty_dumpty']['respon_terhadap_operasi'] == 1)>
                                    <label class="custom-control-label" for="humpty_respon_1">&gt; 48 jam / Tidak
                                        menjalani pembedahan/sedasi/anestesi</label>
                                </div>
                            </td>
                        </tr>
                        {{-- Penggunaan medikamentosa --}}
                        <tr>
                            <td>Penggunaan medikamentosa</td>
                            <td>
                                <div class="custom-control custom-radio">
                                    <label class="custom-control-label">&nbsp;</label>
                                </div>
                            </td>
                            <td>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="humpty_med_3"
                                        name="resiko_jatuh_anak[humpty_dumpty][penggunaan_medikamentosa]"
                                        value="Penggunaan multipel: sedatif, obat hipnosis, barbiturat, fenotiazin, antidepresan, pencahar, diuretik, narkose"
                                        data-skor="3" class="custom-control-input humpty"
                                        @checked(isset($jatuh['humpty_dumpty']['penggunaan_medikamentosa']) &&
                                                $jatuh['humpty_dumpty']['penggunaan_medikamentosa'] == 3)>
                                    <label class="custom-control-label" for="humpty_med_3">Penggunaan multipel:
                                        sedatif, obat hipnosis, barbiturat, fenotiazin, antidepresan, pencahar,
                                        diuretik, narkose</label>
                                </div>
                            </td>
                            <td>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="humpty_med_2"
                                        name="resiko_jatuh_anak[humpty_dumpty][penggunaan_medikamentosa]"
                                        value="Penggunaan salah satu: sedatif, obat hipnosis, barbiturat, fenotiazin, antidepresan, pencahar, diuretik, narkose"
                                        data-skor="2" class="custom-control-input humpty"
                                        @checked(isset($jatuh['humpty_dumpty']['penggunaan_medikamentosa']) &&
                                                $jatuh['humpty_dumpty']['penggunaan_medikamentosa'] == 2)>
                                    <label class="custom-control-label" for="humpty_med_2">Penggunaan salah satu:
                                        sedatif, obat hipnosis, barbiturat, fenotiazin, antidepresan, pencahar,
                                        diuretik, narkose</label>
                                </div>
                            </td>
                            <td>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="humpty_med_1"
                                        name="resiko_jatuh_anak[humpty_dumpty][penggunaan_medikamentosa]"
                                        value="Penggunaan medikasi lain / Tidak ada medikasi" data-skor="1"
                                        class="custom-control-input humpty" @checked(isset($jatuh['humpty_dumpty']['penggunaan_medikamentosa']) &&
                                                $jatuh['humpty_dumpty']['penggunaan_medikamentosa'] == 1)>
                                    <label class="custom-control-label" for="humpty_med_1">Penggunaan medikasi lain /
                                        Tidak ada medikasi</label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <div class="form-group">
                                    <input type="text" name="resiko_jatuh_anak[humpty_dumpty][total_skor]"
                                        id="skor_humpty"
                                        class="form-control font-weight-bold text-success text-center"
                                        style="font-size: 20px; height: 60px;" readonly
                                        value="{{ $jatuh['humpty_dumpty']['total_skor'] ?? '' }}">
                                    <label for="skor_humpty" class="control-label">Skor Total</label>
                                </div>
                            </th>
                            <th>
                                <div class="form-group">
                                    <input type="text" name="resiko_jatuh_anak[humpty_dumpty][analisis]"
                                        id="analisis_humpty"
                                        class="form-control font-weight-bold text-success text-center"
                                        style="font-size: 20px; height: 60px;" readonly
                                        value="{{ $jatuh['humpty_dumpty']['analisis'] ?? '' }}">
                                    <label for="analisis_humpty" class="control-label">Analisis</label>
                                </div>
                            </th>
                            <th colspan="3"></th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
