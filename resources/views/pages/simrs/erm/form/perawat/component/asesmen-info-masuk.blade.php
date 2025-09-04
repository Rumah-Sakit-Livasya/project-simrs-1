@php
    $infoMasuk = $data['info_masuk_ruangan'] ?? [];
    $pemeriksaan = $data['pemeriksaan_dibawa'] ?? [];
    $obat = $data['obat_dibawa'] ?? [];
@endphp

<h4 class="text-primary mt-4 font-weight-bold">INFORMASI MASUK RUANGAN</h4>

{{-- Tanggal & Jam Masuk --}}
<div class="row">
    <div class="col-md-4 form-group">
        <label>Tanggal Masuk Ruangan</label>
        <input type="date" name="tgl_masuk" class="form-control"
            value="{{ optional($pengkajian->waktu_masuk_ruangan)->format('Y-m-d') }}">
    </div>
    <div class="col-md-2 form-group">
        <label>Jam</label>
        <input type="time" name="jam_masuk" class="form-control"
            value="{{ optional($pengkajian->waktu_masuk_ruangan)->format('H:i') }}">
    </div>
</div>

{{-- Cara Masuk Ruangan --}}
<div class="form-group">
    <label class="font-weight-bold">Cara Masuk Ruangan</label>
    <div class="d-flex flex-wrap">
        @foreach (['Poliklinik', 'UGD', 'Dokter pribadi'] as $cara)
            <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" id="cara_masuk_{{ Str::slug($cara) }}" name="info_masuk_ruangan[cara_masuk]"
                    value="{{ $cara }}" class="custom-control-input" @checked(isset($infoMasuk['cara_masuk']) && $infoMasuk['cara_masuk'] == $cara)>
                <label class="custom-control-label" for="cara_masuk_{{ Str::slug($cara) }}">{{ $cara }}</label>
            </div>
        @endforeach
        <div class="custom-control custom-radio custom-control-inline d-flex align-items-center">
            <input type="radio" id="cara_masuk_lainnya" name="info_masuk_ruangan[cara_masuk]" value="Lainnya"
                class="custom-control-input" @checked(isset($infoMasuk['cara_masuk']) && $infoMasuk['cara_masuk'] == 'Lainnya')>
            <label class="custom-control-label" for="cara_masuk_lainnya">Lainnya:</label>
            <input type="text" name="info_masuk_ruangan[cara_masuk_ket]" class="form-control form-control-sm ml-2"
                style="width: 200px;" value="{{ $infoMasuk['cara_masuk_ket'] ?? '' }}">
        </div>
    </div>
</div>

{{-- Tiba Di Ruangan --}}
<div class="form-group">
    <label class="font-weight-bold">Tiba Di Ruangan Dengan Cara</label>
    <div class="d-flex flex-wrap">
        @foreach (['Jalan kaki', 'Kursi roda', 'Brankar'] as $cara)
            <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" id="cara_tiba_{{ Str::slug($cara) }}" name="info_masuk_ruangan[cara_tiba]"
                    value="{{ $cara }}" class="custom-control-input" @checked(isset($infoMasuk['cara_tiba']) && $infoMasuk['cara_tiba'] == $cara)>
                <label class="custom-control-label" for="cara_tiba_{{ Str::slug($cara) }}">{{ $cara }}</label>
            </div>
        @endforeach
        <div class="custom-control custom-radio custom-control-inline d-flex align-items-center">
            <input type="radio" id="cara_tiba_lainnya" name="info_masuk_ruangan[cara_tiba]" value="Lainnya"
                class="custom-control-input" @checked(isset($infoMasuk['cara_tiba']) && $infoMasuk['cara_tiba'] == 'Lainnya')>
            <label class="custom-control-label" for="cara_tiba_lainnya">Lainnya:</label>
            <input type="text" name="info_masuk_ruangan[cara_tiba_ket]" class="form-control form-control-sm ml-2"
                style="width: 200px;" value="{{ $infoMasuk['cara_tiba_ket'] ?? '' }}">
        </div>
    </div>
</div>

{{-- Hasil Pemeriksaan Dibawa --}}
<div class="form-group">
    <label class="font-weight-bold">Hasil Pemeriksaan Yang Dibawa Keluarga</label>
    <div class="d-flex flex-wrap">
        @foreach (['Laboratorium', 'Radiologi', 'USG'] as $item)
            <div class="custom-control custom-checkbox custom-control-inline">
                <input type="checkbox" id="pemeriksaan_{{ Str::slug($item) }}"
                    name="pemeriksaan_dibawa[{{ Str::slug($item) }}]" value="1" class="custom-control-input"
                    @checked(isset($pemeriksaan[Str::slug($item)]))>
                <label class="custom-control-label"
                    for="pemeriksaan_{{ Str::slug($item) }}">{{ $item }}</label>
            </div>
        @endforeach
        <div class="custom-control custom-checkbox custom-control-inline d-flex align-items-center">
            <input type="checkbox" id="pemeriksaan_lainnya" name="pemeriksaan_dibawa[lainnya]" value="1"
                class="custom-control-input" @checked(isset($pemeriksaan['lainnya']))>
            <label class="custom-control-label" for="pemeriksaan_lainnya">Lainnya:</label>
            <input type="text" name="pemeriksaan_dibawa[lainnya_ket]" class="form-control form-control-sm ml-2"
                style="width: 200px;" value="{{ $pemeriksaan['lainnya_ket'] ?? '' }}">
        </div>
    </div>
</div>

{{-- Obat-Obatan Dibawa --}}
<h4 class="text-primary mt-4 font-weight-bold">OBAT-OBATAN YANG DIBAWA DARI RUMAH</h4>
<div class="table-responsive">
    <table class="table table-bordered">
        <thead class="bg-light text-center">
            <tr>
                <th style="width: 5%;">No</th>
                <th>Nama Obat</th>
                <th>Dosis & Frekuensi</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @for ($i = 0; $i < 8; $i++)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td><input type="text" name="obat_dibawa[{{ $i }}][nama]" class="form-control"
                            value="{{ $obat[$i]['nama'] ?? '' }}"></td>
                    <td><input type="text" name="obat_dibawa[{{ $i }}][dosis]" class="form-control"
                            value="{{ $obat[$i]['dosis'] ?? '' }}"></td>
                    <td><input type="text" name="obat_dibawa[{{ $i }}][jumlah]" class="form-control"
                            value="{{ $obat[$i]['jumlah'] ?? '' }}"></td>
                </tr>
            @endfor
        </tbody>
    </table>
</div>
