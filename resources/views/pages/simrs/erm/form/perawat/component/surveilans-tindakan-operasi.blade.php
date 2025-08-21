<h5 class="font-weight-bold mt-4 mb-3">Tindakan / Operasi</h5>
<div class="form-group">
    <label>Tindakan/Operasi</label>
    <input class="form-control" name="tindakan_operasi[nama_tindakan]" type="text"
        value="{{ $data['nama_tindakan'] ?? '' }}">
</div>
<div class="form-group">
    <label>1. Diagnosa</label>
    <textarea name="tindakan_operasi[diagnosa]" class="form-control" rows="2">{{ $data['diagnosa'] ?? '' }}</textarea>
</div>
<div class="form-group">
    <label>2. Tanggal & Lama Operasi</label>
    <div class="input-group mb-2">
        <div class="input-group-prepend"><span class="input-group-text">1.</span></div>
        <input class="form-control" name="tindakan_operasi[tgl_op_1]" type="date"
            value="{{ $data['tgl_op_1'] ?? '' }}">
        <div class="input-group-prepend"><span class="input-group-text">Lama</span></div>
        <input class="form-control" name="tindakan_operasi[lama_op_1]" type="time"
            value="{{ $data['lama_op_1'] ?? '' }}">
    </div>
    <div class="input-group">
        <div class="input-group-prepend"><span class="input-group-text">2.</span></div>
        <input class="form-control" name="tindakan_operasi[tgl_op_2]" type="date"
            value="{{ $data['tgl_op_2'] ?? '' }}">
        <div class="input-group-prepend"><span class="input-group-text">Lama</span></div>
        <input class="form-control" name="tindakan_operasi[lama_op_2]" type="time"
            value="{{ $data['lama_op_2'] ?? '' }}">
    </div>
</div>
<div class="form-group">
    <label>3. Jenis Operasi</label>
    <div>
        @foreach (['Bersih', 'Bersih tercemar', 'Tercemar', 'Kotor'] as $jenis)
            <div class="custom-control custom-checkbox custom-control-inline">
                <input type="checkbox" class="custom-control-input" id="op_jenis_{{ Str::slug($jenis) }}"
                    name="tindakan_operasi[jenis_op][]" value="{{ $jenis }}" @checked(in_array($jenis, $data['jenis_op'] ?? []))>
                <label class="custom-control-label" for="op_jenis_{{ Str::slug($jenis) }}">{{ $jenis }}</label>
            </div>
        @endforeach
    </div>
</div>
{{-- Lanjutkan pola yang sama untuk 'Tindakan Operasi' dan 'ASA Score' --}}
