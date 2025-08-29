<h5 class="mt-4 mb-3 font-weight-bold">Skrining Gizi (Malnutrition Screening Tool - MST)</h5>
<div class="table-responsive">
    <table class="table table-bordered">
        <thead class="bg-light">
            <tr>
                <th>Parameter</th>
                <th>Pilihan</th>
                <th class="text-center" style="width: 10%;">Skor</th>
            </tr>
        </thead>
        <tbody>
            {{-- Pertanyaan A --}}
            <tr>
                <td class="align-middle" rowspan="5">
                    <b>A. Apakah pasien mengalami penurunan berat badan yang tidak direncanakan/tidak diinginkan dalam 6
                        bulan terakhir?</b>
                </td>
                <td>
                    <div class="custom-control custom-radio">
                        <input type="radio" id="mst_a_tidak" name="nutrisi[skrining_mst][penurunan_bb]" value="tidak"
                            class="custom-control-input skor_mst" data-skor="0" @checked(isset($data['penurunan_bb']) && $data['penurunan_bb'] == 'tidak')>
                        <label class="custom-control-label" for="mst_a_tidak">Tidak</label>
                    </div>
                </td>
                <td class="text-center align-middle">0</td>
            </tr>
            <tr>
                <td>
                    <div class="custom-control custom-radio">
                        <input type="radio" id="mst_a_ragu" name="nutrisi[skrining_mst][penurunan_bb]" value="ragu"
                            class="custom-control-input skor_mst" data-skor="2" @checked(isset($data['penurunan_bb']) && $data['penurunan_bb'] == 'ragu')>
                        <label class="custom-control-label" for="mst_a_ragu">Tidak yakin / Ragu-ragu (ada tanda: baju
                            menjadi lebih longgar)</label>
                    </div>
                </td>
                <td class="text-center align-middle">2</td>
            </tr>
            <tr>
                <td class="pl-5">
                    <b>Ya, ada penurunan BB sebanyak:</b>
                    @foreach ([
        '1-5 kg' => 1,
        '6-10 kg' => 2,
        '11-15 kg' => 3,
        '>15 kg' => 4,
    ] as $pilihan => $skor)
                        <div class="custom-control custom-radio ml-3">
                            <input type="radio" id="mst_a_ya_{{ Str::slug($pilihan) }}"
                                name="nutrisi[skrining_mst][penurunan_bb_detail]" value="{{ $pilihan }}"
                                class="custom-control-input skor_mst" data-skor="{{ $skor }}"
                                @checked(isset($data['penurunan_bb_detail']) && $data['penurunan_bb_detail'] == $pilihan)>
                            <label class="custom-control-label"
                                for="mst_a_ya_{{ Str::slug($pilihan) }}">{{ $pilihan }}</label>
                        </div>
                    @endforeach
                </td>
                <td class="text-center align-middle">1-4</td>
            </tr>
            <tr>
                <td>
                    <div class="custom-control custom-radio">
                        <input type="radio" id="mst_a_tidak_tahu" name="nutrisi[skrining_mst][penurunan_bb]"
                            value="tidak_tahu" class="custom-control-input skor_mst" data-skor="2"
                            @checked(isset($data['penurunan_bb']) && $data['penurunan_bb'] == 'tidak_tahu')>
                        <label class="custom-control-label" for="mst_a_tidak_tahu">Tidak tahu berapa Kg
                            penurunannya</label>
                    </div>
                </td>
                <td class="text-center align-middle">2</td>
            </tr>

            {{-- Pertanyaan B --}}
            <tr>
                <td class="align-middle" rowspan="2">
                    <b>B. Apakah asupan makan pasien berkurang karena penurunan nafsu makan atau kesulitan menerima
                        makanan?</b>
                </td>
                <td>
                    <div class="custom-control custom-radio">
                        <input type="radio" id="mst_b_tidak" name="nutrisi[skrining_mst][asupan_berkurang]"
                            value="tidak" class="custom-control-input skor_mst" data-skor="0"
                            @checked(isset($data['asupan_berkurang']) && $data['asupan_berkurang'] == 'tidak')>
                        <label class="custom-control-label" for="mst_b_tidak">Tidak</label>
                    </div>
                </td>
                <td class="text-center align-middle">0</td>
            </tr>
            <tr>
                <td>
                    <div class="custom-control custom-radio">
                        <input type="radio" id="mst_b_ya" name="nutrisi[skrining_mst][asupan_berkurang]"
                            value="ya" class="custom-control-input skor_mst" data-skor="1"
                            @checked(isset($data['asupan_berkurang']) && $data['asupan_berkurang'] == 'ya')>
                        <label class="custom-control-label" for="mst_b_ya">Ya</label>
                    </div>
                </td>
                <td class="text-center align-middle">1</td>
            </tr>
        </tbody>
        <tfoot class="bg-light">
            <tr>
                <th class="text-right" colspan="2">Total Skor MST</th>
                <td><input type="text" name="nutrisi[skrining_mst][total_skor]" id="hasil_skor_mst"
                        class="form-control font-weight-bold bg-white" readonly value="{{ $data['total_skor'] ?? '' }}">
                </td>
            </tr>
            <tr>
                <th class="text-right" colspan="2">Analisis</th>
                <td><input type="text" name="nutrisi[skrining_mst][analisis]" id="analisis_skor_mst"
                        class="form-control font-weight-bold bg-white" readonly value="{{ $data['analisis'] ?? '' }}">
                </td>
            </tr>
        </tfoot>
    </table>
</div>
