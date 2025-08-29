{{--
    Partial ini merender tabel PQRST (Provocati, Qualitas, Region, Skala, Timing) untuk asesmen nyeri.

    Props yang diterima:
    - $data: Array yang berisi data PQRST yang sudah ada (misal: dari $nyeri['pqrst']).
    - $prefix: String prefix untuk atribut 'name' pada input, agar bisa digunakan di form yang berbeda
              (misal: 'asesmen_nyeri[pqrst]' atau 'asesmen_nyeri_anak[pqrst]').
--}}

<div class="table-responsive mt-3">
    <p class="font-weight-bold">Deskripsi Nyeri (PQRST):</p>
    <table class="table table-bordered">
        <thead class="text-center bg-light">
            <tr>
                <th style="width: 25%">Tanggal & Pukul</th>
                <th>Kategori</th>
                <th>Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ([
        'provocati' => 'P (Provocati): Penyebab/Yang memperberat',
        'qualitas' => 'Q (Qualitas): Kualitas/Seperti apa rasanya',
        'region' => 'R (Region): Area/Lokasi dan penyebaran',
        'skala' => 'S (Skala): Tingkat keparahan/skor',
        'timing' => 'T (Timing): Waktu/Kapan & durasi',
    ] as $key => $label)
                <tr>
                    <td>
                        <div class="d-flex">
                            <input type="date" name="{{ $prefix }}[{{ $key }}][tanggal]"
                                class="form-control mr-2" value="{{ $data[$key]['tanggal'] ?? '' }}">
                            <input type="time" name="{{ $prefix }}[{{ $key }}][jam]"
                                class="form-control" value="{{ $data[$key]['jam'] ?? '' }}">
                        </div>
                    </td>
                    <td class="align-middle"><b>{{ $label }}</b></td>
                    <td>
                        <textarea class="form-control" name="{{ $prefix }}[{{ $key }}][deskripsi]" rows="2">{{ $data[$key]['deskripsi'] ?? '' }}</textarea>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
