{{-- =================================================================================================== --}}
{{-- FILE: resources/views/pages/simrs/erm/form/dokter/component/neonatus-status-generalis.blade.php   --}}
{{-- =================================================================================================== --}}

{{-- STATUS GENERALIS --}}
<h4 class="text-primary mt-4 font-weight-bold">III. Status Generalis</h4>
<div class="row">
    {{-- Kolom Kiri --}}
    <div class="col-md-6">
        <div class="mb-3">
            <h5 class="font-weight-bold text-info">Kepala</h5>
            <div class="form-group row align-items-center mb-0">
                <label class="col-md-4 col-form-label">Caput Kepala</label>
                <div class="col-md-8">
                    <div class="form-radio">
                        <label class="radio-styled radio-info"><input type="radio"
                                name="data[status_generalis][kepala][caput]" value="Ada"
                                {{ ($data['status_generalis']['kepala']['caput'] ?? null) == 'Ada' ? 'checked' : '' }}><span>Ada</span></label>
                        <label class="radio-styled radio-info ml-3"><input type="radio"
                                name="data[status_generalis][kepala][caput]" value="Tidak Ada"
                                {{ ($data['status_generalis']['kepala']['caput'] ?? null) == 'Tidak Ada' ? 'checked' : '' }}><span>Tidak
                                Ada</span></label>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="mb-3">
            <h5 class="font-weight-bold text-info">Hidung</h5>
            <div class="form-group row align-items-center mb-0">
                <label class="col-md-4 col-form-label">NCH</label>
                <div class="col-md-8">
                    <div class="form-radio">
                        <label class="radio-styled radio-info"><input type="radio"
                                name="data[status_generalis][hidung][nch]" value="Ada"
                                {{ ($data['status_generalis']['hidung']['nch'] ?? null) == 'Ada' ? 'checked' : '' }}><span>Ada</span></label>
                        <label class="radio-styled radio-info ml-3"><input type="radio"
                                name="data[status_generalis][hidung][nch]" value="Tidak Ada"
                                {{ ($data['status_generalis']['hidung']['nch'] ?? null) == 'Tidak Ada' ? 'checked' : '' }}><span>Tidak
                                Ada</span></label>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="mb-3">
            <h5 class="font-weight-bold text-info">Mata</h5>
            <div class="form-group row align-items-center mb-0">
                <div class="col-md-12">
                    <div class="form-radio d-flex align-items-center">
                        <label class="radio-styled radio-info mb-0"><input type="radio"
                                name="data[status_generalis][mata][kondisi]" value="Normal"
                                {{ ($data['status_generalis']['mata']['kondisi'] ?? null) == 'Normal' ? 'checked' : '' }}><span>Normal</span></label>
                        <label class="radio-styled radio-info ml-3 mb-0"><input type="radio"
                                name="data[status_generalis][mata][kondisi]" value="Abnormal"
                                {{ ($data['status_generalis']['mata']['kondisi'] ?? null) == 'Abnormal' ? 'checked' : '' }}><span>Abnormal</span></label>
                        <input name="data[status_generalis][mata][keterangan]" placeholder="Keterangan..."
                            type="text" class="form-control form-control-sm ml-2" style="width: 150px;"
                            value="{{ $data['status_generalis']['mata']['keterangan'] ?? '' }}">
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="mb-3">
            <h5 class="font-weight-bold text-info">Mulut</h5>
            <div class="form-group row align-items-center mb-0">
                <div class="col-md-12">
                    <div class="form-radio">
                        <label class="radio-styled radio-info"><input type="radio"
                                name="data[status_generalis][mulut][kondisi]" value="Bersih"
                                {{ ($data['status_generalis']['mulut']['kondisi'] ?? null) == 'Bersih' ? 'checked' : '' }}><span>Bersih</span></label>
                        <label class="radio-styled radio-info ml-3"><input type="radio"
                                name="data[status_generalis][mulut][kondisi]" value="Tidak Bersih"
                                {{ ($data['status_generalis']['mulut']['kondisi'] ?? null) == 'Tidak Bersih' ? 'checked' : '' }}><span>Tidak
                                Bersih</span></label>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="mb-3">
            <h5 class="font-weight-bold text-info">Leher</h5>
            <div class="form-group row align-items-center mb-0">
                <div class="col-md-12">
                    <div class="form-radio d-flex align-items-center">
                        <label class="radio-styled radio-info mb-0"><input type="radio"
                                name="data[status_generalis][leher][kondisi]" value="Normal"
                                {{ ($data['status_generalis']['leher']['kondisi'] ?? null) == 'Normal' ? 'checked' : '' }}><span>Normal</span></label>
                        <label class="radio-styled radio-info ml-3 mb-0"><input type="radio"
                                name="data[status_generalis][leher][kondisi]" value="Abnormal"
                                {{ ($data['status_generalis']['leher']['kondisi'] ?? null) == 'Abnormal' ? 'checked' : '' }}><span>Abnormal</span></label>
                        <input name="data[status_generalis][leher][keterangan]" placeholder="Keterangan..."
                            type="text" class="form-control form-control-sm ml-2" style="width: 150px;"
                            value="{{ $data['status_generalis']['leher']['keterangan'] ?? '' }}">
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="mb-3">
            <h5 class="font-weight-bold text-info">Dada</h5>
            <div class="form-group row align-items-center mb-2">
                <label class="col-md-4 col-form-label">Retraksi</label>
                <div class="col-md-8">
                    <div class="form-radio">
                        <label class="radio-styled radio-info"><input type="radio"
                                name="data[status_generalis][dada][retraksi]" value="Ada"
                                {{ ($data['status_generalis']['dada']['retraksi'] ?? null) == 'Ada' ? 'checked' : '' }}><span>Ada</span></label>
                        <label class="radio-styled radio-info ml-3"><input type="radio"
                                name="data[status_generalis][dada][retraksi]" value="Tidak Ada"
                                {{ ($data['status_generalis']['dada']['retraksi'] ?? null) == 'Tidak Ada' ? 'checked' : '' }}><span>Tidak
                                Ada</span></label>
                    </div>
                </div>
            </div>
            <div class="form-group row align-items-center mb-0">
                <label class="col-md-4 col-form-label">Simetris</label>
                <div class="col-md-8">
                    <div class="form-radio">
                        <label class="radio-styled radio-info"><input type="radio"
                                name="data[status_generalis][dada][simetris]" value="Ya"
                                {{ ($data['status_generalis']['dada']['simetris'] ?? null) == 'Ya' ? 'checked' : '' }}><span>Ya</span></label>
                        <label class="radio-styled radio-info ml-3"><input type="radio"
                                name="data[status_generalis][dada][simetris]" value="Tidak"
                                {{ ($data['status_generalis']['dada']['simetris'] ?? null) == 'Tidak' ? 'checked' : '' }}><span>Tidak</span></label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Kolom Kanan --}}
    <div class="col-md-6">
        <div class="mb-3">
            <h5 class="font-weight-bold text-info">Perut</h5>
            <div class="form-group row align-items-center mb-0">
                <label class="col-md-4 col-form-label">Supel</label>
                <div class="col-md-8">
                    <div class="form-radio">
                        <label class="radio-styled radio-info"><input type="radio"
                                name="data[status_generalis][perut][supel]" value="Ya"
                                {{ ($data['status_generalis']['perut']['supel'] ?? null) == 'Ya' ? 'checked' : '' }}><span>Ya</span></label>
                        <label class="radio-styled radio-info ml-3"><input type="radio"
                                name="data[status_generalis][perut][supel]" value="Tidak"
                                {{ ($data['status_generalis']['perut']['supel'] ?? null) == 'Tidak' ? 'checked' : '' }}><span>Tidak</span></label>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="mb-3">
            <h5 class="font-weight-bold text-info">Ekstremitas</h5>
            <p class="font-weight-bold mb-1">Atas</p>
            <div class="form-group row align-items-center mb-2">
                <label class="col-md-4 col-form-label">Sianosis</label>
                <div class="col-md-8">
                    <div class="form-radio d-flex align-items-center">
                        <label class="radio-styled radio-info mb-0"><input type="radio"
                                name="data[status_generalis][ekstremitas][atas][sianosis]" value="Ya"
                                {{ ($data['status_generalis']['ekstremitas']['atas']['sianosis'] ?? null) == 'Ya' ? 'checked' : '' }}><span>Ya</span></label>
                        <input name="data[status_generalis][ekstremitas][atas][keterangan]" placeholder="Lokasi..."
                            type="text" class="form-control form-control-sm ml-2" style="width: 150px;"
                            value="{{ $data['status_generalis']['ekstremitas']['atas']['keterangan'] ?? '' }}">
                        <label class="radio-styled radio-info ml-3 mb-0"><input type="radio"
                                name="data[status_generalis][ekstremitas][atas][sianosis]" value="Tidak"
                                {{ ($data['status_generalis']['ekstremitas']['atas']['sianosis'] ?? null) == 'Tidak' ? 'checked' : '' }}><span>Tidak</span></label>
                    </div>
                </div>
            </div>
            <p class="font-weight-bold mb-1 mt-3">Bawah</p>
            <div class="form-group row align-items-center mb-2">
                <label class="col-md-4 col-form-label">Sianosis</label>
                <div class="col-md-8">
                    <div class="form-radio d-flex align-items-center">
                        <label class="radio-styled radio-info mb-0"><input type="radio"
                                name="data[status_generalis][ekstremitas][bawah][sianosis]" value="Ya"
                                {{ ($data['status_generalis']['ekstremitas']['bawah']['sianosis'] ?? null) == 'Ya' ? 'checked' : '' }}><span>Ya</span></label>
                        <input name="data[status_generalis][ekstremitas][bawah][keterangan]" placeholder="Lokasi..."
                            type="text" class="form-control form-control-sm ml-2" style="width: 150px;"
                            value="{{ $data['status_generalis']['ekstremitas']['bawah']['keterangan'] ?? '' }}">
                        <label class="radio-styled radio-info ml-3 mb-0"><input type="radio"
                                name="data[status_generalis][ekstremitas][bawah][sianosis]" value="Tidak"
                                {{ ($data['status_generalis']['ekstremitas']['bawah']['sianosis'] ?? null) == 'Tidak' ? 'checked' : '' }}><span>Tidak</span></label>
                    </div>
                </div>
            </div>
            <p class="font-weight-bold mb-1 mt-3">Lainnya</p>
            <div class="form-group row align-items-center mb-0">
                <label class="col-md-4 col-form-label">Gerak</label>
                <div class="col-md-8">
                    <div class="form-radio">
                        <label class="radio-styled radio-info"><input type="radio"
                                name="data[status_generalis][ekstremitas][gerak]" value="Aktif"
                                {{ ($data['status_generalis']['ekstremitas']['gerak'] ?? null) == 'Aktif' ? 'checked' : '' }}><span>Aktif</span></label>
                        <label class="radio-styled radio-info ml-3"><input type="radio"
                                name="data[status_generalis][ekstremitas][gerak]" value="Tidak Aktif"
                                {{ ($data['status_generalis']['ekstremitas']['gerak'] ?? null) == 'Tidak Aktif' ? 'checked' : '' }}><span>Tidak
                                Aktif</span></label>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="mb-3">
            <h5 class="font-weight-bold text-info">Kelainan Kongenital</h5>
            <div class="form-group row align-items-center mb-0">
                <div class="col-md-12">
                    <div class="form-radio d-flex align-items-center">
                        <label class="radio-styled radio-info mb-0"><input type="radio"
                                name="data[status_generalis][kelainan_kongenital][status]" value="Ada"
                                {{ ($data['status_generalis']['kelainan_kongenital']['status'] ?? null) == 'Ada' ? 'checked' : '' }}><span>Ada</span></label>
                        <input name="data[status_generalis][kelainan_kongenital][keterangan]"
                            placeholder="Sebutkan..." type="text" class="form-control form-control-sm ml-2"
                            style="width: 150px;"
                            value="{{ $data['status_generalis']['kelainan_kongenital']['keterangan'] ?? '' }}">
                        <label class="radio-styled radio-info ml-3 mb-0"><input type="radio"
                                name="data[status_generalis][kelainan_kongenital][status]" value="Tidak"
                                {{ ($data['status_generalis']['kelainan_kongenital']['status'] ?? null) == 'Tidak' ? 'checked' : '' }}><span>Tidak</span></label>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="mb-3">
            <h5 class="font-weight-bold text-info">Anus</h5>
            <div class="form-group row align-items-center mb-0">
                <div class="col-md-12">
                    <div class="form-radio">
                        <label class="radio-styled radio-info"><input type="radio"
                                name="data[status_generalis][anus][status]" value="Ada"
                                {{ ($data['status_generalis']['anus']['status'] ?? null) == 'Ada' ? 'checked' : '' }}><span>Ada</span></label>
                        <label class="radio-styled radio-info ml-3"><input type="radio"
                                name="data[status_generalis][anus][status]" value="Tidak Ada"
                                {{ ($data['status_generalis']['anus']['status'] ?? null) == 'Tidak Ada' ? 'checked' : '' }}><span>Tidak
                                Ada</span></label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
