<header class="text-warning mt-4">
    <h4 class="font-weight-bold">RIWAYAT PSIKOSOSIAL, SPIRITUAL & KEPERCAYAAN</h4>
</header>
<div class="row mt-3">
    <div class="col-md-3 mb-3">
        <div class="form-group">
            <label for="status_psikologis" class="control-label text-primary">Status
                psikologis</label>
            <select name="status_psikologis" id="status_psikologis" class="select2 form-select">
                <option></option>
                <option value="Tenang" {{ ($pengkajian?->status_psikologis ?? '') == 'Tenang' ? 'selected' : '' }}>
                    Tenang
                </option>
                <option value="Cemas" {{ ($pengkajian?->status_psikologis ?? '') == 'Cemas' ? 'selected' : '' }}>Cemas
                </option>
                <option value="Takut" {{ ($pengkajian?->status_psikologis ?? '') == 'Takut' ? 'selected' : '' }}>Takut
                </option>
                <option value="Marah" {{ ($pengkajian?->status_psikologis ?? '') == 'Marah' ? 'selected' : '' }}>Marah
                </option>
                <option value="Sedih" {{ ($pengkajian?->status_psikologis ?? '') == 'Sedih' ? 'selected' : '' }}>Sedih
                </option>
                <option value="Kecenderuangan bunuh diri"
                    {{ ($pengkajian?->status_psikologis ?? '') == 'Kecenderuangan bunuh diri' ? 'selected' : '' }}>
                    Kecenderuangan bunuh diri
                </option>
            </select>
        </div>
        <div class="form-group mt-2">
            <label for="hubungan_keluarga" class="control-label text-primary">Hubungan dengan
                anggota
                keluarga</label>
            <input type="text" name="hubungan_keluarga" id="hubungan_keluarga" class="form-control"
                value="{{ $pengkajian?->hubungan_keluarga }}">
        </div>
        <div class="form-group mt-2">
            <label for="status_perkawinan" class="control-label text-primary">Status
                perkawinan</label>
            <input type="text" name="status_perkawinan" id="status_perkawinan" class="form-control"
                value="{{ $pengkajian?->status_perkawinan }}">
        </div>
        <div class="form-group mt-2">
            <label for="pendidikan" class="control-label text-primary">Pendidikan</label>
            <input type="text" name="pendidikan" id="pendidikan" class="form-control"
                value="{{ $pengkajian?->pendidikan }}">
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="form-group">
            <label for="status_mental" class="control-label text-primary">Status Mental</label>
            <input type="text" name="status_mental" id="status_mental" class="form-control"
                value="{{ $pengkajian?->status_mental }}">
        </div>
        <div class="form-group mt-2">
            <label for="tempat_tinggal" class="control-label text-primary">Tempat tinggal
                (rumah/panti/kos/dll)</label>
            <input type="text" name="tempat_tinggal" id="tempat_tinggal" class="form-control"
                value="{{ $pengkajian?->tempat_tinggal }}">
        </div>
        <div class="form-group mt-2">
            <label for="pekerjaan" class="control-label text-primary">Pekerjaan</label>
            <input type="text" name="pekerjaan" id="pekerjaan" class="form-control"
                value="{{ $pengkajian?->pekerjaan }}">
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="form-group">
            <label for="masalah_perilaku" class="control-label text-primary">Masalah perilaku (bila
                ada)</label>
            <input type="text" name="masalah_perilaku" id="masalah_perilaku" class="form-control"
                value="{{ $pengkajian?->masalah_perilaku }}">
        </div>
        <div class="form-group mt-2">
            <label for="kerabat_dihubungi" class="control-label text-primary">Kerabat yang dapat
                dihubungi</label>
            <input type="text" name="kerabat_dihubungi" id="kerabat_dihubungi" class="form-control"
                value="{{ $pengkajian?->kerabat_dihubungi }}">
        </div>
        <div class="form-group mt-2">
            <label for="agama" class="control-label text-primary">Agama</label>
            <input type="text" name="agama" id="agama" class="form-control" value="{{ $pengkajian?->agama }}">
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="form-group">
            <label for="kekerasan" class="control-label text-primary">Kekerasan yg pernah
                dialami</label>
            <input type="text" name="kekerasan" id="kekerasan" class="form-control"
                value="{{ $pengkajian?->kekerasan }}">
        </div>
        <div class="form-group mt-2">
            <label for="kontak_kerabat" class="control-label text-primary">Kontak kerabat yang
                dapat
                dihubungi</label>
            <input type="text" name="kontak_kerabat" id="kontak_kerabat" class="form-control"
                value="{{ $pengkajian?->kontak_kerabat }}">
        </div>
        <div class="form-group mt-2">
            <label for="penghasilan" class="control-label text-primary">Penghasilan</label>
            <select name="penghasilan" id="penghasilan" class="select2 form-select">
                <option></option>
                <option value="< 1 Juta" {{ ($pengkajian?->penghasilan ?? '') == '< 1 Juta' ? 'selected' : '' }}>&lt;
                    1
                    Juta
                </option>
                <option value="1 - 2,9 Juta"
                    {{ ($pengkajian?->penghasilan ?? '') == '1 - 2,9 Juta' ? 'selected' : '' }}>1 -
                    2,9
                    Juta</option>
                <option value="3 - 4,9 Juta"
                    {{ ($pengkajian?->penghasilan ?? '') == '3 - 4,9 Juta' ? 'selected' : '' }}>3 -
                    4,9
                    Juta</option>
                <option value="5 - 9,9 Juta"
                    {{ ($pengkajian?->penghasilan ?? '') == '5 - 9,9 Juta' ? 'selected' : '' }}>5 -
                    9,9
                    Juta</option>
                <option value="10 - 14,9 Juta"
                    {{ ($pengkajian?->penghasilan ?? '') == '10 - 14,9 Juta' ? 'selected' : '' }}>10 -
                    14,9 Juta</option>
                <option value="15 - 19.5 Juta"
                    {{ ($pengkajian?->penghasilan ?? '') == '15 - 19.5 Juta' ? 'selected' : '' }}>15 -
                    19.5 Juta</option>
                <option value="> 20 Juta" {{ ($pengkajian?->penghasilan ?? '') == '> 20 Juta' ? 'selected' : '' }}>
                    &gt; 20
                    Juta</option>
            </select>
        </div>
    </div>
</div>
