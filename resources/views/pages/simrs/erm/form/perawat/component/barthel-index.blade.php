<header class="text-warning mt-4">
    <h4 class="font-weight-bold">BARTHEL INDEX (STATUS FUNGSIONAL)</h4>
</header>
<div class="row mt-3">
    <div class="col-md-3 mb-3">
        <div class="form-group">
            <label class="control-label text-primary">Makan</label>
            <select name="barthel_makan" class="select2 form-select barthel-select">
                <option></option>
                <option value="Tidak Mampu" {{ ($pengkajian?->barthel_makan ?? '') == 'Tidak Mampu' ? 'selected' : '' }}>
                    Tidak Mampu (Skor 0)
                </option>
                <option value="Dibantu" {{ ($pengkajian?->barthel_makan ?? '') == 'Dibantu' ? 'selected' : '' }}>
                    Dibantu (Skor 1)
                </option>
                <option value="Makan Mandiri"
                    {{ ($pengkajian?->barthel_makan ?? '') == 'Makan Mandiri' ? 'selected' : '' }}>
                    Mandiri (Skor 2)
                </option>
            </select>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="form-group">
            <label class="control-label text-primary">Mandi</label>
            <select name="barthel_mandi" class="select2 form-select barthel-select">
                <option></option>
                <option value="Dibantu Mandi"
                    {{ ($pengkajian?->barthel_mandi ?? '') == 'Dibantu Mandi' ? 'selected' : '' }}>
                    Dibantu (Skor 0)
                </option>
                <option value="Mandi Mandiri"
                    {{ ($pengkajian?->barthel_mandi ?? '') == 'Mandi Mandiri' ? 'selected' : '' }}>
                    Mandiri (Skor 1)
                </option>
            </select>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="form-group">
            <label class="control-label text-primary">Berhias</label>
            <select name="barthel_berhias" class="select2 form-select barthel-select">
                <option></option>
                <option value="Dibantu Berhias"
                    {{ ($pengkajian?->barthel_berhias ?? '') == 'Dibantu Berhias' ? 'selected' : '' }}>
                    Dibantu Berhias (Skor 0)
                </option>
                <option value="Berhias Mandiri"
                    {{ ($pengkajian?->barthel_berhias ?? '') == 'Berhias Mandiri' ? 'selected' : '' }}>
                    Mandiri (Skor 1)
                </option>
            </select>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="form-group">
            <label class="control-label text-primary">Berpakaian</label>
            <select name="barthel_berpakaian" class="select2 form-select barthel-select">
                <option></option>
                <option value="Dibantu total"
                    {{ ($pengkajian?->barthel_berpakaian ?? '') == 'Dibantu total' ? 'selected' : '' }}>
                    Dibantu total (Skor 0)
                </option>
                <option value="Dibantu Sebagian"
                    {{ ($pengkajian?->barthel_berpakaian ?? '') == 'Dibantu Sebagian' ? 'selected' : '' }}>
                    Dibantu Sebagian (Skor 1)
                </option>
                <option value="Berpakaian Mandiri"
                    {{ ($pengkajian?->barthel_berpakaian ?? '') == 'Berpakaian Mandiri' ? 'selected' : '' }}>
                    Mandiri (Skor 2)
                </option>
            </select>
        </div>
    </div>
</div>
<div class="row mt-3">
    <div class="col-md-3 mb-3">
        <div class="form-group">
            <label class="control-label text-primary">BAB</label>
            <select name="barthel_bab" class="select2 form-select barthel-select">
                <option></option>
                <option value="Inkontinen" {{ ($pengkajian?->barthel_bab ?? '') == 'Inkontinen' ? 'selected' : '' }}>
                    Inkontinen (Skor 0)
                </option>
                <option value="Kadang Inkontinen/Konstipasi"
                    {{ ($pengkajian?->barthel_bab ?? '') == 'Kadang Inkontinen/Konstipasi' ? 'selected' : '' }}>
                    Kadang Inkontinen/Konstipasi (Skor 1)
                </option>
                <option value="Tidak ada masalah"
                    {{ ($pengkajian?->barthel_bab ?? '') == 'Tidak ada masalah' ? 'selected' : '' }}>
                    Tidak ada masalah (Skor 2)
                </option>
            </select>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="form-group">
            <label class="control-label text-primary">BAK</label>
            <select name="barthel_bak" class="select2 form-select barthel-select">
                <option></option>
                <option value="Inkontinen/pakai kateter"
                    {{ ($pengkajian?->barthel_bak ?? '') == 'Inkontinen/pakai kateter' ? 'selected' : '' }}>
                    Inkontinen/pakai kateter (Skor 0)
                </option>
                <option value="kadang inkontinen"
                    {{ ($pengkajian?->barthel_bak ?? '') == 'kadang inkontinen' ? 'selected' : '' }}>
                    kadang inkontinen (1x24 jam) (Skor 1)
                </option>
                <option value="Mandiri" {{ ($pengkajian?->barthel_bak ?? '') == 'Mandiri' ? 'selected' : '' }}>
                    Mandiri (Skor 2)
                </option>
            </select>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="form-group">
            <label class="control-label text-primary">Toileting</label>
            <select name="barthel_toileting" class="select2 form-select barthel-select">
                <option></option>
                <option value="Tergantung total"
                    {{ ($pengkajian?->barthel_toileting ?? '') == 'Tergantung total' ? 'selected' : '' }}>
                    Tergantung total (Skor 0)
                </option>
                <option value="Dibantu Sebagian"
                    {{ ($pengkajian?->barthel_toileting ?? '') == 'Dibantu Sebagian' ? 'selected' : '' }}>
                    Dibantu Sebagian (Skor 1)
                </option>
                <option value="Mandiri" {{ ($pengkajian?->barthel_toileting ?? '') == 'Mandiri' ? 'selected' : '' }}>
                    Mandiri (Skor 2)
                </option>
            </select>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="form-group">
            <label class="control-label text-primary">Transfer</label>
            <select name="barthel_transfer" class="select2 form-select barthel-select">
                <option></option>
                <option value="Tidak Mampu"
                    {{ ($pengkajian?->barthel_transfer ?? '') == 'Tidak Mampu' ? 'selected' : '' }}>
                    Tidak Mampu (Skor 0)
                </option>
                <option value="Dibantu lebih dari 1 orang untuk duduk"
                    {{ ($pengkajian?->barthel_transfer ?? '') == 'Dibantu lebih dari 1 orang untuk duduk' ? 'selected' : '' }}>
                    Dibantu lebih dari 1 orang untuk duduk (Skor 1)
                </option>
                <option value="Dibantu 1 Orang"
                    {{ ($pengkajian?->barthel_transfer ?? '') == 'Dibantu 1 Orang' ? 'selected' : '' }}>
                    Dibantu 1 Orang (Skor 2)
                </option>
                <option value="Transfer Mandiri"
                    {{ ($pengkajian?->barthel_transfer ?? '') == 'Transfer Mandiri' ? 'selected' : '' }}>
                    Mandiri (Skor 3)
                </option>
            </select>
        </div>
    </div>
</div>
<div class="row mt-3">
    <div class="col-md-3 mb-3">
        <div class="form-group">
            <label class="control-label text-primary">Mobilisasi</label>
            <select name="barthel_mobilitas" class="select2 form-select barthel-select">
                <option></option>
                <option value="Tidak Mampu"
                    {{ ($pengkajian?->barthel_mobilitas ?? '') == 'Tidak Mampu' ? 'selected' : '' }}>
                    Tidak Mampu (Skor 0)
                </option>
                <option value="Kursi Roda"
                    {{ ($pengkajian?->barthel_mobilitas ?? '') == 'Kursi Roda' ? 'selected' : '' }}>
                    Kursi Roda (Skor 1)
                </option>
                <option value="Dibantu 1 Orang"
                    {{ ($pengkajian?->barthel_mobilitas ?? '') == 'Dibantu 1 Orang' ? 'selected' : '' }}>
                    Dibantu 1 Orang (Skor 2)
                </option>
                <option value="Mandiri walau pakai alat bantu"
                    {{ ($pengkajian?->barthel_mobilitas ?? '') == 'Mandiri walau pakai alat bantu' ? 'selected' : '' }}>
                    Mandiri walau pakai alat bantu (Skor 3)
                </option>
            </select>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="form-group">
            <label class="control-label text-primary">Naik Tangga</label>
            <select name="barthel_naik_tangga" class="select2 form-select barthel-select">
                <option></option>
                <option value="Tidak Mampu"
                    {{ ($pengkajian?->barthel_naik_tangga ?? '') == 'Tidak Mampu' ? 'selected' : '' }}>
                    Tidak Mampu (Skor 0)
                </option>
                <option value="Dibantu dan Alat bantu"
                    {{ ($pengkajian?->barthel_naik_tangga ?? '') == 'Dibantu dan Alat bantu' ? 'selected' : '' }}>
                    Dibantu dan Alat bantu (Skor 1)
                </option>
                <option value="Mandiri" {{ ($pengkajian?->barthel_naik_tangga ?? '') == 'Mandiri' ? 'selected' : '' }}>
                    Mandiri (Skor 2)
                </option>
            </select>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="form-group">
            <label class="control-label text-primary">Skor</label>
            <input type="text" name="barthel_skor" id="barthel-skor" class="form-control" {{-- value="{{ $pengkajian?->barthel_skor }}" --}}>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="form-group">
            <label class="control-label text-primary">Analisa</label>
            <input type="text" name="barthel_analisa" id="barthel-analisa" class="form-control"
                value="{{ $pengkajian?->barthel_analisa }}">
        </div>
    </div>
</div>
