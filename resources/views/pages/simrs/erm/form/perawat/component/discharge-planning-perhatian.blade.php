<table class="table table-bordered">
    <tbody>
        @php
            $perhatianItems = [
                'pola_hidup' => 'Membiasakan pola hidup sehat',
                'bantuan_askep' => 'Perlu bantuan asuhan keperawatan (Home Care)',
                'perlu_pendamping' => 'Perlu pendamping orang lain (Care Giver)',
                'alat_bantu' => 'Perlu alat bantu',
                'mobilisasi' => 'Mobilisasi/latihan fisik',
                'pantau_obat' => 'Pemantauan cara minum obat',
                'pantau_diet' => 'Pemantauan diet/konsumsi makanan',
                'perawatan_luka' => 'Perawatan luka',
                'lain_lain' => 'Lain-lain',
            ];
        @endphp
        @foreach ($perhatianItems as $key => $label)
            <tr>
                <td class="d-flex align-items-center">
                    <div class="custom-control custom-checkbox" style="min-width: 350px;">
                        <input type="hidden" name="hal_diperhatikan[{{ $key }}][checked]" value="0">
                        <input type="checkbox" id="perhatian_{{ $key }}"
                            name="hal_diperhatikan[{{ $key }}][checked]" value="1"
                            class="custom-control-input" @checked(isset($data[$key]['checked']) && $data[$key]['checked'] == 1)>
                        <label class="custom-control-label"
                            for="perhatian_{{ $key }}">{{ $label }}</label>
                    </div>
                    <span class="mx-2">:</span>
                    <input type="text" class="form-control" name="hal_diperhatikan[{{ $key }}][keterangan]"
                        value="{{ $data[$key]['keterangan'] ?? '' }}">
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
