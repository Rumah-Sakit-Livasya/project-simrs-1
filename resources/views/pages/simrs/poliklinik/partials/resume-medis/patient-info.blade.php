<table class="table table-borderless">
    <tbody>
        <tr>
            <td style="width: 20%;">
                <label>Nama Pasien</label>
            </td>
            <td style="width: 3%;">
                <label>:</label>
            </td>
            <td style="width: 50%;">
                <input type="text" class="form-control" id="nama_pasien" name="nama_pasien"
                    value="{{ $registration->patient->name }}" readonly>
            </td>
            <td style="width: 20%;">
                <div class="form-check">
                    <input class="form-check-input custom-checkbox" type="radio" id="kunjungan_awal"
                        name="alasan_masuk_rs" value="kunjungan_awal">
                    <label class="form-check-label ml-2" for="kunjungan_awal">Kunjungan Awal</label>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <label>No. Rekam Medis</label>
            </td>
            <td>
                <label>:</label>
            </td>
            <td>
                <input type="text" class="form-control" id="medical_record_number" name="medical_record_number"
                    value="{{ $registration->patient->medical_record_number }}" readonly>
            </td>
            <td>
                <div class="form-check">
                    <input class="form-check-input custom-checkbox" type="radio" id="kontrol_lanjutan"
                        name="alasan_masuk_rs" value="kontrol_lanjutan">
                    <label class="form-check-label ml-2" for="kontrol_lanjutan">Kontrol Lanjutan</label>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <label>Tanggal Lahir</label>
            </td>
            <td>
                <label>:</label>
            </td>
            <td>
                <div class="input-group">
                    <input type="text" class="form-control" id="tgl_lahir" name="tgl_lahir" placeholder="dd/mm/yyyy"
                        value="{{ \Carbon\Carbon::parse($registration->patient->date_of_birth)->format('d/m/Y') }}"
                        readonly>
                </div>
            </td>
            <td>
                <div class="form-check">
                    <input class="form-check-input custom-checkbox" type="radio" id="observasi" name="alasan_masuk_rs"
                        value="observasi">
                    <label class="form-check-label ml-2" for="observasi">Observasi</label>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <label>Jenis Kelamin</label>
            </td>
            <td>
                <label>:</label>
            </td>
            <td>
                <input type="text" class="form-control" id="jenis_kelamin" name="jenis_kelamin"
                    value="{{ $registration->patient->gender }}" readonly>
            </td>
            <td>
                <div class="form-check">
                    <input class="form-check-input custom-checkbox" type="radio" id="post_operasi"
                        name="alasan_masuk_rs" value="post_operasi">
                    <label class="form-check-label ml-2" for="post_operasi">Post Operasi</label>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <label>Tanggal Masuk RS</label>
            </td>
            <td>
                <label>:</label>
            </td>
            <td>
                <input type="datetime-local" class="form-control" id="tgl_masuk" name="tgl_masuk"
                    placeholder="dd/mm/yyyy"
                    value="{{ \Carbon\Carbon::parse(now()->setTimeZone('Asia/Jakarta'))->format('d/m/Y H:i') }}">
            </td>
            <td>
                <div class="input-group">
                    <div class="form-group">
                        <label class="form-label">Berat Lahir</label>
                        <div class="input-group">
                            <input type="text"
                                class="form-control numeric text-left border-top-0 border-right-0 border-left-0 border-bottom"
                                id="berat_lahir" name="berat_lahir">
                            <span class="input-group-addon grey-text text-small">gram</span>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <label>Cara Keluar RS</label>
            </td>
            <td>
                <label>:</label>
            </td>
            <td colspan="2">
                <div class="d-flex flex-wrap gap-3">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input custom-checkbox" type="radio" id="sembuh" name="cara_keluar"
                            value="sembuh">
                        <label class="form-check-label" for="sembuh">Sembuh</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input custom-checkbox" type="radio" id="meninggal" name="cara_keluar"
                            value="meninggal">
                        <label class="form-check-label" for="meninggal">Meninggal</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input custom-checkbox" type="radio" id="rawat" name="cara_keluar"
                            value="rawat">
                        <label class="form-check-label" for="rawat">Rawat</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input custom-checkbox" type="radio" id="rujuk"
                            name="cara_keluar" value="rujuk">
                        <label class="form-check-label" for="rujuk">Rujuk</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input custom-checkbox" type="radio" id="aps"
                            name="cara_keluar" value="aps">
                        <label class="form-check-label" for="aps">APS</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input custom-checkbox" type="radio" id="kontrol"
                            name="cara_keluar" value="kontrol">
                        <label class="form-check-label" for="kontrol">Kontrol</label>
                    </div>
                </div>
            </td>
        </tr>
    </tbody>
</table>
