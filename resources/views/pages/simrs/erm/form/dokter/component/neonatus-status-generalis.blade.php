{{-- =================================================================================================== --}}
{{-- FILE: resources/views/pages/simrs/erm/form/dokter/component/neonatus-status-generalis.blade.php   --}}
{{-- =================================================================================================== --}}

{{-- STATUS GENERALIS --}}
<h4 class="text-primary mt-4 font-weight-bold">III. Status Generalis</h4>
<div class="row">
    {{-- ====================================================== --}}
    {{--                       KOLOM KIRI                       --}}
    {{-- ====================================================== --}}
    <div class="col-md-6">
        {{-- Grup Kepala --}}
        <div class="form-group">
            <label class="form-label font-weight-bold text-info">Kepala</label>
            <div class="row align-items-center">
                <div class="col-md-4"><label class="form-label" for="kepala_caput">Caput Kepala</label></div>
                <div class="col-md-8">
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="kepala_caput_ada" name="data[status_generalis][kepala][caput]"
                            class="custom-control-input" value="Ada"
                            {{ ($data['status_generalis']['kepala']['caput'] ?? null) == 'Ada' ? 'checked' : '' }}>
                        <label class="custom-control-label" for="kepala_caput_ada">Ada</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="kepala_caput_tidak" name="data[status_generalis][kepala][caput]"
                            class="custom-control-input" value="Tidak Ada"
                            {{ ($data['status_generalis']['kepala']['caput'] ?? null) == 'Tidak Ada' ? 'checked' : '' }}>
                        <label class="custom-control-label" for="kepala_caput_tidak">Tidak Ada</label>
                    </div>
                </div>
            </div>
        </div>
        <hr>

        {{-- Grup Hidung --}}
        <div class="form-group">
            <label class="form-label font-weight-bold text-info">Hidung</label>
            <div class="row align-items-center">
                <div class="col-md-4"><label class="form-label" for="hidung_nch">NCH</label></div>
                <div class="col-md-8">
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="hidung_nch_ada" name="data[status_generalis][hidung][nch]"
                            class="custom-control-input" value="Ada"
                            {{ ($data['status_generalis']['hidung']['nch'] ?? null) == 'Ada' ? 'checked' : '' }}>
                        <label class="custom-control-label" for="hidung_nch_ada">Ada</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="hidung_nch_tidak" name="data[status_generalis][hidung][nch]"
                            class="custom-control-input" value="Tidak Ada"
                            {{ ($data['status_generalis']['hidung']['nch'] ?? null) == 'Tidak Ada' ? 'checked' : '' }}>
                        <label class="custom-control-label" for="hidung_nch_tidak">Tidak Ada</label>
                    </div>
                </div>
            </div>
        </div>
        <hr>

        {{-- Grup Mata --}}
        <div class="form-group">
            <label class="form-label font-weight-bold text-info">Mata</label>
            <div class="d-flex align-items-center">
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="mata_normal" name="data[status_generalis][mata][kondisi]"
                        class="custom-control-input" value="Normal"
                        {{ ($data['status_generalis']['mata']['kondisi'] ?? null) == 'Normal' ? 'checked' : '' }}>
                    <label class="custom-control-label" for="mata_normal">Normal</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="mata_abnormal" name="data[status_generalis][mata][kondisi]"
                        class="custom-control-input" value="Abnormal"
                        {{ ($data['status_generalis']['mata']['kondisi'] ?? null) == 'Abnormal' ? 'checked' : '' }}>
                    <label class="custom-control-label" for="mata_abnormal">Abnormal</label>
                </div>
                <input name="data[status_generalis][mata][keterangan]" placeholder="Keterangan..." type="text"
                    class="form-control form-control-sm flex-1 ml-2"
                    value="{{ $data['status_generalis']['mata']['keterangan'] ?? '' }}">
            </div>
        </div>
        <hr>

        {{-- Grup Mulut --}}
        <div class="form-group">
            <label class="form-label font-weight-bold text-info">Mulut</label>
            <div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="mulut_bersih" name="data[status_generalis][mulut][kondisi]"
                        class="custom-control-input" value="Bersih"
                        {{ ($data['status_generalis']['mulut']['kondisi'] ?? null) == 'Bersih' ? 'checked' : '' }}>
                    <label class="custom-control-label" for="mulut_bersih">Bersih</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="mulut_tidak_bersih" name="data[status_generalis][mulut][kondisi]"
                        class="custom-control-input" value="Tidak Bersih"
                        {{ ($data['status_generalis']['mulut']['kondisi'] ?? null) == 'Tidak Bersih' ? 'checked' : '' }}>
                    <label class="custom-control-label" for="mulut_tidak_bersih">Tidak Bersih</label>
                </div>
            </div>
        </div>
        <hr>

        {{-- Grup Leher --}}
        <div class="form-group">
            <label class="form-label font-weight-bold text-info">Leher</label>
            <div class="d-flex align-items-center">
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="leher_normal" name="data[status_generalis][leher][kondisi]"
                        class="custom-control-input" value="Normal"
                        {{ ($data['status_generalis']['leher']['kondisi'] ?? null) == 'Normal' ? 'checked' : '' }}>
                    <label class="custom-control-label" for="leher_normal">Normal</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="leher_abnormal" name="data[status_generalis][leher][kondisi]"
                        class="custom-control-input" value="Abnormal"
                        {{ ($data['status_generalis']['leher']['kondisi'] ?? null) == 'Abnormal' ? 'checked' : '' }}>
                    <label class="custom-control-label" for="leher_abnormal">Abnormal</label>
                </div>
                <input name="data[status_generalis][leher][keterangan]" placeholder="Keterangan..." type="text"
                    class="form-control form-control-sm flex-1 ml-2"
                    value="{{ $data['status_generalis']['leher']['keterangan'] ?? '' }}">
            </div>
        </div>
        <hr>

        {{-- Grup Dada --}}
        <div class="form-group">
            <label class="form-label font-weight-bold text-info">Dada</label>
            <div class="row align-items-center mb-2">
                <div class="col-md-4"><label class="form-label" for="dada_retraksi">Retraksi</label></div>
                <div class="col-md-8">
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="dada_retraksi_ada" name="data[status_generalis][dada][retraksi]"
                            class="custom-control-input" value="Ada"
                            {{ ($data['status_generalis']['dada']['retraksi'] ?? null) == 'Ada' ? 'checked' : '' }}>
                        <label class="custom-control-label" for="dada_retraksi_ada">Ada</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="dada_retraksi_tidak" name="data[status_generalis][dada][retraksi]"
                            class="custom-control-input" value="Tidak Ada"
                            {{ ($data['status_generalis']['dada']['retraksi'] ?? null) == 'Tidak Ada' ? 'checked' : '' }}>
                        <label class="custom-control-label" for="dada_retraksi_tidak">Tidak Ada</label>
                    </div>
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-md-4"><label class="form-label" for="dada_simetris">Simetris</label></div>
                <div class="col-md-8">
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="dada_simetris_ya" name="data[status_generalis][dada][simetris]"
                            class="custom-control-input" value="Ya"
                            {{ ($data['status_generalis']['dada']['simetris'] ?? null) == 'Ya' ? 'checked' : '' }}>
                        <label class="custom-control-label" for="dada_simetris_ya">Ya</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="dada_simetris_tidak" name="data[status_generalis][dada][simetris]"
                            class="custom-control-input" value="Tidak"
                            {{ ($data['status_generalis']['dada']['simetris'] ?? null) == 'Tidak' ? 'checked' : '' }}>
                        <label class="custom-control-label" for="dada_simetris_tidak">Tidak</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ====================================================== --}}
    {{--                       KOLOM KANAN                      --}}
    {{-- ====================================================== --}}
    <div class="col-md-6">
        {{-- Grup Perut --}}
        <div class="form-group">
            <label class="form-label font-weight-bold text-info">Perut</label>
            <div class="row align-items-center">
                <div class="col-md-4"><label class="form-label" for="perut_supel">Supel</label></div>
                <div class="col-md-8">
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="perut_supel_ya" name="data[status_generalis][perut][supel]"
                            class="custom-control-input" value="Ya"
                            {{ ($data['status_generalis']['perut']['supel'] ?? null) == 'Ya' ? 'checked' : '' }}>
                        <label class="custom-control-label" for="perut_supel_ya">Ya</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="perut_supel_tidak" name="data[status_generalis][perut][supel]"
                            class="custom-control-input" value="Tidak"
                            {{ ($data['status_generalis']['perut']['supel'] ?? null) == 'Tidak' ? 'checked' : '' }}>
                        <label class="custom-control-label" for="perut_supel_tidak">Tidak</label>
                    </div>
                </div>
            </div>
        </div>
        <hr>

        {{-- Grup Ekstremitas --}}
        <div class="form-group">
            <label class="form-label font-weight-bold text-info">Ekstremitas</label>
            {{-- Atas --}}
            <div class="row align-items-center mb-2">
                <div class="col-md-4"><label class="form-label mb-0">Atas: Sianosis</label></div>
                <div class="col-md-8">
                    <div class="d-flex align-items-center">
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="ekstremitas_atas_ya"
                                name="data[status_generalis][ekstremitas][atas][sianosis]"
                                class="custom-control-input" value="Ya"
                                {{ ($data['status_generalis']['ekstremitas']['atas']['sianosis'] ?? null) == 'Ya' ? 'checked' : '' }}>
                            <label class="custom-control-label" for="ekstremitas_atas_ya">Ya</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="ekstremitas_atas_tidak"
                                name="data[status_generalis][ekstremitas][atas][sianosis]"
                                class="custom-control-input" value="Tidak"
                                {{ ($data['status_generalis']['ekstremitas']['atas']['sianosis'] ?? null) == 'Tidak' ? 'checked' : '' }}>
                            <label class="custom-control-label" for="ekstremitas_atas_tidak">Tidak</label>
                        </div>
                        <input name="data[status_generalis][ekstremitas][atas][keterangan]" placeholder="Lokasi..."
                            type="text" class="form-control form-control-sm flex-1 ml-2"
                            value="{{ $data['status_generalis']['ekstremitas']['atas']['keterangan'] ?? '' }}">
                    </div>
                </div>
            </div>
            {{-- Bawah --}}
            <div class="row align-items-center mb-2">
                <div class="col-md-4"><label class="form-label mb-0">Bawah: Sianosis</label></div>
                <div class="col-md-8">
                    <div class="d-flex align-items-center">
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="ekstremitas_bawah_ya"
                                name="data[status_generalis][ekstremitas][bawah][sianosis]"
                                class="custom-control-input" value="Ya"
                                {{ ($data['status_generalis']['ekstremitas']['bawah']['sianosis'] ?? null) == 'Ya' ? 'checked' : '' }}>
                            <label class="custom-control-label" for="ekstremitas_bawah_ya">Ya</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="ekstremitas_bawah_tidak"
                                name="data[status_generalis][ekstremitas][bawah][sianosis]"
                                class="custom-control-input" value="Tidak"
                                {{ ($data['status_generalis']['ekstremitas']['bawah']['sianosis'] ?? null) == 'Tidak' ? 'checked' : '' }}>
                            <label class="custom-control-label" for="ekstremitas_bawah_tidak">Tidak</label>
                        </div>
                        <input name="data[status_generalis][ekstremitas][bawah][keterangan]" placeholder="Lokasi..."
                            type="text" class="form-control form-control-sm flex-1 ml-2"
                            value="{{ $data['status_generalis']['ekstremitas']['bawah']['keterangan'] ?? '' }}">
                    </div>
                </div>
            </div>
            {{-- Lainnya --}}
            <div class="row align-items-center">
                <div class="col-md-4"><label class="form-label" for="ekstremitas_gerak">Lainnya: Gerak</label></div>
                <div class="col-md-8">
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="ekstremitas_gerak_aktif"
                            name="data[status_generalis][ekstremitas][gerak]" class="custom-control-input"
                            value="Aktif"
                            {{ ($data['status_generalis']['ekstremitas']['gerak'] ?? null) == 'Aktif' ? 'checked' : '' }}>
                        <label class="custom-control-label" for="ekstremitas_gerak_aktif">Aktif</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="ekstremitas_gerak_tidak"
                            name="data[status_generalis][ekstremitas][gerak]" class="custom-control-input"
                            value="Tidak Aktif"
                            {{ ($data['status_generalis']['ekstremitas']['gerak'] ?? null) == 'Tidak Aktif' ? 'checked' : '' }}>
                        <label class="custom-control-label" for="ekstremitas_gerak_tidak">Tidak Aktif</label>
                    </div>
                </div>
            </div>
        </div>
        <hr>

        {{-- Grup Kelainan Kongenital --}}
        <div class="form-group">
            <label class="form-label font-weight-bold text-info">Kelainan Kongenital</label>
            <div class="d-flex align-items-center">
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="kelainan_ada"
                        name="data[status_generalis][kelainan_kongenital][status]" class="custom-control-input"
                        value="Ada"
                        {{ ($data['status_generalis']['kelainan_kongenital']['status'] ?? null) == 'Ada' ? 'checked' : '' }}>
                    <label class="custom-control-label" for="kelainan_ada">Ada</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="kelainan_tidak"
                        name="data[status_generalis][kelainan_kongenital][status]" class="custom-control-input"
                        value="Tidak"
                        {{ ($data['status_generalis']['kelainan_kongenital']['status'] ?? null) == 'Tidak' ? 'checked' : '' }}>
                    <label class="custom-control-label" for="kelainan_tidak">Tidak</label>
                </div>
                <input name="data[status_generalis][kelainan_kongenital][keterangan]" placeholder="Sebutkan..."
                    type="text" class="form-control form-control-sm flex-1 ml-2"
                    value="{{ $data['status_generalis']['kelainan_kongenital']['keterangan'] ?? '' }}">
            </div>
        </div>
        <hr>

        {{-- Grup Anus --}}
        <div class="form-group">
            <label class="form-label font-weight-bold text-info">Anus</label>
            <div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="anus_ada" name="data[status_generalis][anus][status]"
                        class="custom-control-input" value="Ada"
                        {{ ($data['status_generalis']['anus']['status'] ?? null) == 'Ada' ? 'checked' : '' }}>
                    <label class="custom-control-label" for="anus_ada">Ada</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="anus_tidak" name="data[status_generalis][anus][status]"
                        class="custom-control-input" value="Tidak Ada"
                        {{ ($data['status_generalis']['anus']['status'] ?? null) == 'Tidak Ada' ? 'checked' : '' }}>
                    <label class="custom-control-label" for="anus_tidak">Tidak Ada</label>
                </div>
            </div>
        </div>
    </div>
</div>
