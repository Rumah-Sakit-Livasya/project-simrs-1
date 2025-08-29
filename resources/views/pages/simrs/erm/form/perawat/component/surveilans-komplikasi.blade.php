<h5 class="font-weight-bold mt-4 mb-3">Komplikasi / Infeksi Rumah Sakit</h5>
@php
    $komplikasiItems = [
        'ido' => '1. IDO',
        'isk' => '2. ISK',
        'pneumonia' => '3. Pneumonia',
        'iad' => '4. IAD',
        'lain_lain' => '5. Lain-lain (Plebitis/Dekubitus)',
    ];
@endphp

@foreach ($komplikasiItems as $key => $label)
    <div class="card mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 form-group">
                    <label>{{ $label }}</label>
                    <select class="form-control" name="komplikasi_infeksi[{{ $key }}][status]">
                        <option value=""></option>
                        <option value="Ada" @selected(isset($data[$key]['status']) && $data[$key]['status'] == 'Ada')>Ada</option>
                        <option value="Tidak ada" @selected(isset($data[$key]['status']) && $data[$key]['status'] == 'Tidak ada')>Tidak ada</option>
                    </select>
                </div>
                <div class="col-md-2 form-group">
                    <label>Hari Ke-</label>
                    <input class="form-control" type="number" name="komplikasi_infeksi[{{ $key }}][hari_ke]"
                        value="{{ $data[$key]['hari_ke'] ?? '' }}">
                </div>
                <div class="col-md-6 form-group">
                    <label>Hasil Kultur</label>
                    <input class="form-control" type="text"
                        name="komplikasi_infeksi[{{ $key }}][hasil_kultur]"
                        value="{{ $data[$key]['hasil_kultur'] ?? '' }}">
                </div>
            </div>
        </div>
    </div>
@endforeach
