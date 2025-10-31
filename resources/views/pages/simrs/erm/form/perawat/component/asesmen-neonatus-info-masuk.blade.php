@php
    $infoMasuk = $data['info_masuk_ruangan'] ?? [];
@endphp
<h4 class="text-primary mt-4 font-weight-bold">INFORMASI MASUK RUANGAN</h4>
<div class="row">
    <div class="col-md-6 form-group">
        <label>Tanggal & Jam Masuk Ruangan</label>
        <div class="input-group">
            <input type="date" name="tgl_masuk" class="form-control"
                value="{{ optional($pengkajian->waktu_masuk_ruangan)->format('Y-m-d') }}">
            <input type="time" name="jam_masuk_pasien" class="form-control"
                value="{{ optional($pengkajian->waktu_masuk_ruangan)->format('H:i') }}">
        </div>
    </div>
</div>
<div class="form-group mt-3">
    <label class="font-weight-bold">Cara Masuk Ruangan</label>
    <div class="d-flex flex-wrap">
        @foreach (['Poliklinik', 'UGD', 'Dokter Pribadi'] as $cara)
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
                value="{{ $infoMasuk['cara_masuk_ket'] ?? '' }}">
        </div>
    </div>
</div>
<div class="form-group">
    <label class="font-weight-bold">Tiba di Ruangan Dengan Cara</label>
    <div class="d-flex flex-wrap">
        @foreach (['Jalan Kaki', 'Kursi Roda', 'Brankar', 'Inkubator'] as $cara)
            <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" id="cara_tiba_{{ Str::slug($cara) }}" name="info_masuk_ruangan[cara_tiba]"
                    value="{{ $cara }}" class="custom-control-input" @checked(isset($infoMasuk['cara_tiba']) && $infoMasuk['cara_tiba'] == $cara)>
                <label class="custom-control-label" for="cara_tiba_{{ Str::slug($cara) }}">{{ $cara }}</label>
            </div>
        @endforeach
    </div>
</div>
<div class="form-group">
    <label class="font-weight-bold">Macam Kasus Trauma</label>
    <div class="d-flex flex-wrap">
        @foreach (['KLL', 'Child Abuse'] as $trauma)
            <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" id="trauma_{{ Str::slug($trauma) }}" name="info_masuk_ruangan[macam_trauma]"
                    value="{{ $trauma }}" class="custom-control-input" @checked(isset($infoMasuk['macam_trauma']) && $infoMasuk['macam_trauma'] == $trauma)>
                <label class="custom-control-label" for="trauma_{{ Str::slug($trauma) }}">{{ $trauma }}</label>
            </div>
        @endforeach
    </div>
</div>
