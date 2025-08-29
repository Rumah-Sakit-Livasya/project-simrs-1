<table class="table table-bordered">
    <thead class="text-center bg-light">
        <tr>
            <th>Faktor Resiko Pasien Pulang</th>
            <th style="width: 8%;">Ya</th>
            <th style="width: 8%;">Tidak</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @php
            $questions = [
                'tinggal_sendiri' => 'Apakah pasien tinggal sendiri',
                'khawatir_kembali' => 'Apakah mereka khawatir ketika kembali ke rumah',
                'perawat_rumah' => 'Apakah di rumah ada yang merawat',
                'ada_tangga' => 'Apakah di rumah tempat tinggal ada tangga',
                'merawat_lainnya' => 'Apakah pasien memiliki tanggung jawab mengurus anak/keluarga atau peliharaan',
                'perawatan_lanjutan' => 'Apakah ketika pulang perlu perawatan lanjutan yang harus dilakukan dirumah',
                'jum_obat' => 'Apakah pasien pulang dengan jumlah obat lebih dari 6 macam',
                'mengajukan_pendampingan' => 'Apakah pasien mengajukan permohonan pendampingan dari RS',
                'transportasi_pulang' => 'Bagaimana transportasi pasien untuk pulang',
            ];
        @endphp
        @foreach ($questions as $key => $question)
            <tr>
                <td>{{ $question }}</td>
                <td class="text-center"><input type="radio" name="skrining_faktor_resiko[{{ $key }}][jawaban]"
                        value="ya" @checked(isset($data[$key]['jawaban']) && $data[$key]['jawaban'] == 'ya')></td>
                <td class="text-center"><input type="radio" name="skrining_faktor_resiko[{{ $key }}][jawaban]"
                        value="tidak" @checked(isset($data[$key]['jawaban']) && $data[$key]['jawaban'] == 'tidak')></td>
                <td><input type="text" class="form-control"
                        name="skrining_faktor_resiko[{{ $key }}][keterangan]"
                        value="{{ $data[$key]['keterangan'] ?? '' }}"></td>
            </tr>
        @endforeach
        <tr>
            <td>Bagaimana jenis rumah pasien</td>
            <td colspan="2"></td>
            <td>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="rumah_permanen" name="skrining_faktor_resiko[jenis_rumah]"
                        value="Permanen" class="custom-control-input" @checked(isset($data['jenis_rumah']) && $data['jenis_rumah'] == 'Permanen')>
                    <label class="custom-control-label" for="rumah_permanen">Permanen</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="rumah_semi" name="skrining_faktor_resiko[jenis_rumah]"
                        value="Semi Permanen" class="custom-control-input" @checked(isset($data['jenis_rumah']) && $data['jenis_rumah'] == 'Semi Permanen')>
                    <label class="custom-control-label" for="rumah_semi">Semi Permanen</label>
                </div>
            </td>
        </tr>
    </tbody>
</table>
