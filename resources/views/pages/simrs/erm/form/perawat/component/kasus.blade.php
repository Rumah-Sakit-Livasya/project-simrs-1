<header class="text-warning mt-4">
    <h4 class="font-weight-bold">KASUS</h4>
</header>
<div class="row mt-3">
    <div class="col-md-4 mb-3">
        <div class="form-group">
            <div class="custom-control custom-checkbox custom-control-inline">
                <input type="checkbox" class="custom-control-input" name="kasus_trauma" id="kasus_trauma" value="1"
                    {{ $pengkajian?->kasus_trauma == 1 ? 'checked' : '' }}>
                <label class="custom-control-label" for="kasus_trauma">Trauma</label>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="form-group">
            <div class="custom-control custom-checkbox custom-control-inline">
                <input type="checkbox" class="custom-control-input" name="kasus_non_trauma" id="kasus_non_trauma"
                    value="1" {{ $pengkajian?->kasus_non_trauma == 1 ? 'checked' : '' }}>
                <label class="custom-control-label" for="kasus_non_trauma">Non Trauma</label>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="form-group">
            <div class="custom-control custom-checkbox custom-control-inline">
                <input type="checkbox" class="custom-control-input" name="kasus_obstetri" id="kasus_obstetri"
                    value="1" {{ $pengkajian?->kasus_obstetri == 1 ? 'checked' : '' }}>
                <label class="custom-control-label" for="kasus_obstetri">Obstetri</label>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="form-group">
            <div class="custom-control custom-checkbox custom-control-inline">
                <input type="checkbox" class="custom-control-input" name="kasus_rujukan" id="kasus_rujukan"
                    value="1" {{ $pengkajian?->kasus_rujukan == 1 ? 'checked' : '' }}>
                <label class="custom-control-label" for="kasus_rujukan">Rujukan Dari</label>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="form-group">
            <div class="custom-control custom-checkbox custom-control-inline">
                <input type="checkbox" class="custom-control-input" name="kasus_tanda_kedukaan"
                    id="kasus_tanda_kedukaan" value="1"
                    {{ $pengkajian?->kasus_tanda_kedukaan == 1 ? 'checked' : '' }}>
                <label class="custom-control-label" for="kasus_tanda_kedukaan">Tanda Tanda
                    Kedukaan</label>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="form-group">
            <div class="custom-control custom-checkbox custom-control-inline">
                <input type="checkbox" class="custom-control-input" name="kasus_apneu" id="kasus_apneu" value="1"
                    {{ $pengkajian?->kasus_apneu == 1 ? 'checked' : '' }}>
                <label class="custom-control-label" for="kasus_apneu">Apneu</label>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-2 mb-3">
        <div class="form-group">
            <label class="control-label text-primary">Transportasi ke IGD</label>
            <select name="transportasi_igd" class="select2 form-select">
                <option value=""></option>
                <option value="Ambulance" {{ ($pengkajian?->transportasi_igd ?? '') == 'Ambulance' ? 'selected' : '' }}>
                    Ambulance</option>
                <option value="Datang Sendiri"
                    {{ ($pengkajian?->transportasi_igd ?? '') == 'Datang Sendiri' ? 'selected' : '' }}>
                    Datang Sendiri</option>
                <option value="Kendaraan Pribadi"
                    {{ ($pengkajian?->transportasi_igd ?? '') == 'Kendaraan Pribadi' ? 'selected' : '' }}>
                    Kendaraan Pribadi</option>
            </select>
        </div>
        <div class="form-group mt-2">
            <label class="control-label text-primary">Spesialistik</label>
            <select name="spesialistik" class="select2 form-select">
                <option value=""></option>
                <option value="BEDAH UMUM" {{ ($pengkajian?->spesialistik ?? '') == 'BEDAH UMUM' ? 'selected' : '' }}>
                    BEDAH
                    UMUM</option>
                <option value="INTERNIST" {{ ($pengkajian?->spesialistik ?? '') == 'INTERNIST' ? 'selected' : '' }}>
                    INTERNIST
                </option>
                <option value="JIWA" {{ ($pengkajian?->spesialistik ?? '') == 'JIWA' ? 'selected' : '' }}>JIWA
                </option>
                <option value="KULIT DAN KELAMIN"
                    {{ ($pengkajian?->spesialistik ?? '') == 'KULIT DAN KELAMIN' ? 'selected' : '' }}>
                    KULIT DAN KELAMIN</option>
                <option value="MATA" {{ ($pengkajian?->spesialistik ?? '') == 'MATA' ? 'selected' : '' }}>MATA
                </option>
                <option value="NEUROLOGI" {{ ($pengkajian?->spesialistik ?? '') == 'NEUROLOGI' ? 'selected' : '' }}>
                    NEUROLOGI
                </option>
                <option value="OBGYN" {{ ($pengkajian?->spesialistik ?? '') == 'OBGYN' ? 'selected' : '' }}>OBGYN
                </option>
                <option value="ORTHOPEDI" {{ ($pengkajian?->spesialistik ?? '') == 'ORTHOPEDI' ? 'selected' : '' }}>
                    ORTHOPEDI
                </option>
                <option value="PARU-PARU" {{ ($pengkajian?->spesialistik ?? '') == 'PARU-PARU' ? 'selected' : '' }}>
                    PARU-PARU
                </option>
                <option value="SPESIALIS ANAK"
                    {{ ($pengkajian?->spesialistik ?? '') == 'SPESIALIS ANAK' ? 'selected' : '' }}>
                    SPESIALIS ANAK</option>
                <option value="SPESIALIS BEDAH SYARAF"
                    {{ ($pengkajian?->spesialistik ?? '') == 'SPESIALIS BEDAH SYARAF' ? 'selected' : '' }}>
                    SPESIALIS BEDAH SYARAF</option>
                <option value="THT" {{ ($pengkajian?->spesialistik ?? '') == 'THT' ? 'selected' : '' }}>THT</option>
                <option value="UROLOGI" {{ ($pengkajian?->spesialistik ?? '') == 'UROLOGI' ? 'selected' : '' }}>UROLOGI
                </option>
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 mb-3">
        <label class="control-label text-primary">Hambatan Pasien</label>
    </div>
    <div class="col-md-2 mb-3">
        <div class="form-group">
            <div class="custom-control custom-checkbox custom-control-inline">
                <input type="checkbox" class="custom-control-input" name="hambatan_tidak_ada" id="hambatan_tidak_ada"
                    value="1" {{ $pengkajian?->hambatan_tidak_ada == 1 ? 'checked' : '' }}>
                <label class="custom-control-label" for="hambatan_tidak_ada">Tidak Ada</label>
            </div>
        </div>
    </div>
    <div class="col-md-2 mb-3">
        <div class="form-group">
            <div class="custom-control custom-checkbox custom-control-inline">
                <input type="checkbox" class="custom-control-input" name="hambatan_bahasa" id="hambatan_bahasa"
                    value="1" {{ $pengkajian?->hambatan_bahasa == 1 ? 'checked' : '' }}>
                <label class="custom-control-label" for="hambatan_bahasa">Bahasa</label>
            </div>
        </div>
    </div>
    <div class="col-md-2 mb-3">
        <div class="form-group">
            <div class="custom-control custom-checkbox custom-control-inline">
                <input type="checkbox" class="custom-control-input" name="hambatan_fisik" id="hambatan_fisik"
                    value="1" {{ $pengkajian?->hambatan_fisik == 1 ? 'checked' : '' }}>
                <label class="custom-control-label" for="hambatan_fisik">Fisik</label>
            </div>
        </div>
    </div>
    <div class="col-md-2 mb-3">
        <div class="form-group">
            <div class="custom-control custom-checkbox custom-control-inline">
                <input type="checkbox" class="custom-control-input" name="hambatan_tuli" id="hambatan_tuli"
                    value="1" {{ $pengkajian?->hambatan_tuli == 1 ? 'checked' : '' }}>
                <label class="custom-control-label" for="hambatan_tuli">Tuli</label>
            </div>
        </div>
    </div>
    <div class="col-md-2 mb-3">
        <div class="form-group">
            <div class="custom-control custom-checkbox custom-control-inline">
                <input type="checkbox" class="custom-control-input" name="hambatan_bisu" id="hambatan_bisu"
                    value="1" {{ $pengkajian?->hambatan_bisu == 1 ? 'checked' : '' }}>
                <label class="custom-control-label" for="hambatan_bisu">Bisu</label>
            </div>
        </div>
    </div>
    <div class="col-md-2 mb-3">
        <div class="form-group">
            <div class="custom-control custom-checkbox custom-control-inline">
                <input type="checkbox" class="custom-control-input" name="hambatan_buta" id="hambatan_buta"
                    value="1" {{ $pengkajian?->hambatan_buta == 1 ? 'checked' : '' }}>
                <label class="custom-control-label" for="hambatan_buta">Buta</label>
            </div>
        </div>
    </div>
</div>
