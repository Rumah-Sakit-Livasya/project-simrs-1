@php
    $fisik = $data['penilaian_fisik'] ?? [];
@endphp

<hr>
<h4 class="text-primary mt-4 font-weight-bold">III. PENILAIAN FISIK</h4>

<div class="table-responsive">
    <table class="table table-bordered">
        <tbody>
            @php
                $penilaian = [
                    'Bentuk ubun-ubun' => ['Cekung', 'Rata', 'Menonjol'],
                    'Rambut' => ['Normal', 'Merah', 'Tipis'],
                    'Mata' => ['Normal', 'Ikteric', 'Anemis'],
                    'Penglihatan' => ['Baik', 'Rusak', 'Alat bantu'],
                    'Hidung' => ['Normal', 'Bengkok', 'Ingusan'],
                    'Mulut' => ['Bersih', 'Kotor', 'Lain-lain'],
                    'Gigi' => ['Normal', 'Caries'],
                    'Telinga' => ['Bersih', 'Kotor', 'Serumen'],
                    'Pendengaran' => ['Baik', 'Alat bantu'],
                    'Bentuk Dada' => ['Simetris', 'Asimetris'],
                    'Pernapasan' => ['Normal', 'Sesak', 'Batuk'],
                    'Abdomen' => ['Normal', 'Buncit', 'Nyeri Tekan'],
                    'Tali pusat' => ['Basah', 'Kering', 'Bau'],
                    'Gastrointestinal' => ['Normal', 'Refluks', 'Nausea'],
                    'Anus' => ['Normal', 'Kelainan'],
                    'Miksi' => ['Normal', 'Retensi', 'Inkontinens'],
                    'Defekasi' => ['Normal', 'Retensi', 'Diare'],
                    'Lanugo' => ['Ya', 'Tidak'],
                    'Turgor kuli' => ['Baik', 'Sedang', 'Kurang'],
                    'Kelembaban' => ['Baik', 'Buruk'],
                    'Warna kulit' => ['Merah muda', 'Pucat', 'Ikteric'],
                    'Sirkulasi' => ['Baik', 'Oedema', 'Sianosis'],
                    'Ekstremitas' => ['Normal', 'Kelainan'],
                    'Kuku' => ['Bersih', 'Kotor'],
                    'Reflek menangis' => ['Kuat', 'Lemah', 'Merintih'],
                    'Reflek hisap' => ['Kuat', 'Lemah', 'Lain-lain'],
                    'Reflek babinski' => ['Ada', 'Tidak'],
                    'Tonic neck' => ['Ada', 'Tidak'],
                    'Bicara' => ['Normal', 'Gangguan'],
                    'Reflek menelan' => ['Normal', 'Sulit', 'Ada kelainan'],
                    'Reflek menoleh' => ['Kuat', 'Lemah'],
                    'Reflek genggam' => ['Kuat', 'Lemah'],
                    'Reflek moro' => ['Ada', 'Tidak'],
                    'Pola tidur' => ['Normal', 'Kurang', 'Masalah'],
                ];
            @endphp

            @foreach (array_chunk($penilaian, 4, true) as $chunk)
                <tr>
                    @foreach ($chunk as $label => $options)
                        <td class="bg-light font-weight-bold" style="width: 15%;">{{ $label }}</td>
                        <td style="width: 35%;">
                            <div class="d-flex flex-wrap">
                                @foreach ($options as $option)
                                    <div class="custom-control custom-checkbox custom-control-inline">
                                        <input type="checkbox" id="fisik_{{ Str::slug($label) }}_{{ Str::slug($option) }}"
                                            name="penilaian_fisik[{{ Str::slug($label) }}][{{ Str::slug($option) }}]"
                                            value="1" class="custom-control-input" @checked(isset($fisik[Str::slug($label)][Str::slug($option)]))>
                                        <label class="custom-control-label"
                                            for="fisik_{{ Str::slug($label) }}_{{ Str::slug($option) }}">{{ $option }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </td>
                    @endforeach
                    {{-- Tambahkan <td> kosong jika baris tidak penuh --}}
                    @if (count($chunk) < 2)
                        <td colspan="2"></td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
