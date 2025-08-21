<h6 class="mt-3"><b>Nilai Apgar Score</b></h6>
<div class="table-responsive">
    <table class="table table-bordered text-center">
        <thead class="bg-light">
            <tr>
                <th rowspan="2" class="align-middle">Tanda</th>
                <th colspan="3">1 Menit</th>
                <th colspan="3">5 Menit</th>
                <th colspan="3">10 Menit</th>
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
            @foreach (['bunyi_jantung' => 'Bunyi Jantung', 'pernapasan' => 'Pernapasan', 'tonus_otot' => 'Tonus Otot', 'reflek' => 'Reflek', 'warna_kulit' => 'Warna Kulit'] as $key => $label)
                <tr>
                    <td><b>{{ $label }}</b></td>
                    @foreach (['1mnt', '5mnt', '10mnt'] as $waktu)
                        @for ($skor = 0; $skor <= 2; $skor++)
                            <td>
                                <div class="custom-control custom-radio">
                                    <input type="radio"
                                        id="apgar_{{ $key }}_{{ $waktu }}_{{ $skor }}"
                                        name="pengkajian_khusus_neonatus[intranatal][apgar][{{ $key }}][{{ $waktu }}]"
                                        value="{{ $skor }}"
                                        class="custom-control-input apgar_{{ $waktu }}"
                                        data-skor="{{ $skor }}" @checked(isset($data[$key][$waktu]) && $data[$key][$waktu] == $skor)>
                                    <label class="custom-control-label"
                                        for="apgar_{{ $key }}_{{ $waktu }}_{{ $skor }}"></label>
                                </div>
                            </td>
                        @endfor
                    @endforeach
                </tr>
            @endforeach
        </tbody>
        <tfoot class="bg-light">
            <tr>
                <td><b>Jumlah</b></td>
                <td colspan="3"><input type="text"
                        name="pengkajian_khusus_neonatus[intranatal][apgar][total_1mnt]" id="apgar_score_1mnt"
                        class="form-control font-weight-bold" readonly value="{{ $data['total_1mnt'] ?? '' }}"></td>
                <td colspan="3"><input type="text"
                        name="pengkajian_khusus_neonatus[intranatal][apgar][total_5mnt]" id="apgar_score_5mnt"
                        class="form-control font-weight-bold" readonly value="{{ $data['total_5mnt'] ?? '' }}"></td>
                <td colspan="3"><input type="text"
                        name="pengkajian_khusus_neonatus[intranatal][apgar][total_10mnt]" id="apgar_score_10mnt"
                        class="form-control font-weight-bold" readonly value="{{ $data['total_10mnt'] ?? '' }}"></td>
            </tr>
        </tfoot>
    </table>
</div>
