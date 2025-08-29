<div class="form-group">
    <label>Rencana lama rawat inap:</label>
    <div class="custom-control custom-radio">
        <input type="radio" id="ditetapkan_ya" name="rencana_perawatan_rumah[status_penetapan]"
            value="Sudah bisa ditetapkan" class="custom-control-input" @checked(isset($data['status_penetapan']) && $data['status_penetapan'] == 'Sudah bisa ditetapkan')>
        <label class="custom-control-label" for="ditetapkan_ya">Sudah bisa ditetapkan</label>
        <div class="d-inline-flex align-items-center ml-3">
            <input type="number" name="rencana_perawatan_rumah[lama_hari]" class="form-control form-control-sm"
                style="width: 80px;" value="{{ $data['lama_hari'] ?? '' }}">
            <span class="mx-2">hari, rencana pulang tanggal</span>
            <input type="date" name="rencana_perawatan_rumah[tanggal_pulang]" class="form-control form-control-sm"
                value="{{ $data['tanggal_pulang'] ?? '' }}">
        </div>
    </div>
    <div class="custom-control custom-radio mt-2">
        <input type="radio" id="ditetapkan_tidak" name="rencana_perawatan_rumah[status_penetapan]"
            value="Belum bisa ditetapkan" class="custom-control-input" @checked(isset($data['status_penetapan']) && $data['status_penetapan'] == 'Belum bisa ditetapkan')>
        <label class="custom-control-label" for="ditetapkan_tidak">Belum bisa ditetapkan, karena:</label>
        <div class="d-inline-block ml-2 w-50">
            <input type="text" name="rencana_perawatan_rumah[alasan_belum_ditetapkan]"
                class="form-control form-control-sm" value="{{ $data['alasan_belum_ditetapkan'] ?? '' }}">
        </div>
    </div>
</div>
