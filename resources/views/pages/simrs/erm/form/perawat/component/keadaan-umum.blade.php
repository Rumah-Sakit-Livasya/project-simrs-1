<header class="text-warning mt-4">
    <h4 class="font-weight-bold">KEADAAN UMUM</h4>
</header>
<div class="row mt-3">
    <div class="col-md-4 mb-3">
        <div class="form-group">
            <label class="control-label text-primary">Keaadaan Umum</label>
            <select name="keadaan_umum" class="select2 form-select">
                <option></option>
                <option value="Baik" {{ ($pengkajian?->keadaan_umum ?? '') == 'Baik' ? 'selected' : '' }}>Baik</option>
                <option value="Sedang" {{ ($pengkajian?->keadaan_umum ?? '') == 'Sedang' ? 'selected' : '' }}>Sedang
                </option>
                <option value="Buruk" {{ ($pengkajian?->keadaan_umum ?? '') == 'Buruk' ? 'selected' : '' }}>Buruk
                </option>
            </select>
        </div>
    </div>
</div>
