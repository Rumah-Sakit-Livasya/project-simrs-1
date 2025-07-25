<header class="text-warning mt-4">
    <h4 class="font-weight-bold">SKALA FLACC</h4>
</header>
<div class="row mt-3">
    <div class="col-md-4 mb-3">
        <div class="form-group">
            <label class="control-label text-primary">Wajah</label>
            <select name="flacc_wajah" class="select2 form-select flacc-select">
                <option></option>
                <option value="Tersenyum / Tidak ada Ekspresi Khusus"
                    {{ ($pengkajian?->flacc_wajah ?? '') == 'Tersenyum / Tidak ada Ekspresi Khusus' ? 'selected' : '' }}>
                    Tersenyum / Tidak ada Ekspresi Khusus (Skor 0)
                </option>
                <option value="Sesekali Meringis atau mengerutkan kening, ditarik, tertarik"
                    {{ ($pengkajian?->flacc_wajah ?? '') == 'Sesekali Meringis atau mengerutkan kening, ditarik, tertarik' ? 'selected' : '' }}>
                    Sesekali Meringis atau mengerutkan kening, ditarik, tertarik (Skor 1)
                </option>
                <option value="Sering ke dagu bergetar konstan, rahang terkatup"
                    {{ ($pengkajian?->flacc_wajah ?? '') == 'Sering ke dagu bergetar konstan, rahang terkatup' ? 'selected' : '' }}>
                    Sering ke dagu bergetar konstan, rahang terkatup (Skor 2)
                </option>
            </select>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="form-group">
            <label class="control-label text-primary">Kaki</label>
            <select name="flacc_kaki" class="select2 form-select flacc-select">
                <option></option>
                <option value="Yang normal posisi atau santai"
                    {{ ($pengkajian?->flacc_kaki ?? '') == 'Yang normal posisi atau santai' ? 'selected' : '' }}>
                    Yang normal posisi atau santai (Skor 0)"
                </option>
                <option value="Gelisah, Tegang"
                    {{ ($pengkajian?->flacc_kaki ?? '') == 'Gelisah, Tegang' ? 'selected' : '' }}>
                    Gelisah, Tegang (Skor 1)
                </option>
                <option value="Menendang atau kaki dibuat"
                    {{ ($pengkajian?->flacc_kaki ?? '') == 'Menendang atau kaki dibuat' ? 'selected' : '' }}>
                    Menendang atau kaki dibuat (Skor 2)"
                </option>
            </select>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="form-group">
            <label class="control-label text-primary">Aktivitas</label>
            <select name="flacc_aktivitas" class="select2 form-select flacc-select">
                <option></option>
                <option value="Berbaring tenang, posisi normal, bergerak dengan mudah"
                    {{ ($pengkajian?->flacc_aktivitas ?? '') == 'Berbaring tenang, posisi normal, bergerak dengan mudah' ? 'selected' : '' }}>
                    Berbaring tenang, posisi normal, bergerak dengan mudah (Skor 0)
                </option>
                <option value="Menggeliat, pergeseran bolak-balik, tegang"
                    {{ ($pengkajian?->flacc_aktivitas ?? '') == 'Menggeliat, pergeseran bolak-balik, tegang' ? 'selected' : '' }}>
                    Menggeliat, pergeseran bolak-balik, tegang (Skor 1)
                </option>
                <option value="Melengkung, kaku atau menyentak"
                    {{ ($pengkajian?->flacc_aktivitas ?? '') == 'Melengkung, kaku atau menyentak' ? 'selected' : '' }}>
                    Melengkung, kaku atau menyentak (Skor 2)
                </option>
            </select>
        </div>
    </div>
</div>
<div class="row mt-3">
    <div class="col-md-4 mb-3">
        <div class="form-group">
            <label class="control-label text-primary">Menangis</label>
            <select name="flacc_menangis" class="select2 form-select flacc-select">
                <option></option>
                <option value="Tidak ada teriakan (terjaga atau tertidur)"
                    {{ ($pengkajian?->flacc_menangis ?? '') == 'Tidak ada teriakan (terjaga atau tertidur)")' ? 'selected' : '' }}>
                    Tidak ada teriakan (terjaga atau tertidur) (Skor 0)"
                </option>
                <option value="Eregan atau merintih, sesekali keluhan"
                    {{ ($pengkajian?->flacc_menangis ?? '') == 'Eregan atau merintih, sesekali keluhan' ? 'selected' : '' }}>
                    Eregan atau merintih, sesekali keluhan (Skor 1)
                </option>
                <option value="Mengangis terus, jeritan atau isak tangis, keluhan sering"
                    {{ ($pengkajian?->flacc_menangis ?? '') == 'Mengangis terus, jeritan atau isak tangis, keluhan sering' ? 'selected' : '' }}>
                    Mengangis terus, jeritan atau isak tangis, keluhan sering (Skor 2)
                </option>
            </select>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="form-group">
            <label class="control-label text-primary">Bersuara</label>
            <select name="flacc_bersuara" class="select2 form-select flacc-select">
                <option></option>
                <option value="Bersuara normal, tenang"
                    {{ ($pengkajian?->flacc_bersuara ?? '') == 'Bersuara normal, tenang' ? 'selected' : '' }}>
                    Bersuara normal, tenang (Skor 0)
                </option>
                <option value="Tenang bila dipeluk"
                    {{ ($pengkajian?->flacc_bersuara ?? '') == 'Tenang bila dipeluk' ? 'selected' : '' }}>
                    Tenang bila dipeluk (Skor 1)
                </option>
                <option value="Sulit untuk ditenangkan"
                    {{ ($pengkajian?->flacc_bersuara ?? '') == 'Sulit untuk ditenangkan' ? 'selected' : '' }}>
                    Sulit untuk ditenangkan (Skor 2)
                </option>
            </select>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="form-group">
            <label class="control-label text-primary">Skor</label>
            <input type="text" name="flacc_skor" id="flacc-skor" class="form-control"
                value="{{ $pengkajian?->flacc_skor }}">
        </div>
    </div>
</div>
