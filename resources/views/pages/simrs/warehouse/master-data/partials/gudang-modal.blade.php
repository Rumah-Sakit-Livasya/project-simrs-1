<div class="modal fade" id="gudangModal" tabindex="-1" role="dialog" aria-labelledby="gudangModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="gudangForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="gudangModalLabel">Modal Title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- Hidden input untuk ID dan Method spoofing (PUT) --}}
                    <input type="hidden" name="id" id="gudangId">
                    <input type="hidden" name="_method" id="formMethod">

                    {{-- === MULAI FORM FIELDS === --}}

                    {{-- Nama Gudang --}}
                    <div class="form-group mb-3">
                        <label class="form-label" for="nama">Nama Gudang <span class="text-danger">*</span></label>
                        <input type="text" id="nama" name="nama" class="form-control" required>
                        <div class="invalid-feedback d-block" id="nama_error"></div>
                    </div>

                    {{-- Cost Center (dengan Select2) --}}
                    <div class="form-group mb-3">
                        <label class="form-label" for="cost_center">Cost Center</label>
                        <select id="cost_center" name="cost_center" class="form-control select2-modal">
                            <option value="" disabled selected>Pilih Cost Center</option>
                            @foreach ($costCenters as $center)
                                <option value="{{ $center->nama_rnc }}">{{ $center->nama_rnc }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback d-block" id="cost_center_error"></div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            {{-- Checkbox Apotek & Warehouse --}}
                            <div class="custom-control custom-checkbox mb-2">
                                <input type="checkbox" class="custom-control-input apotek-checkbox" id="apotek"
                                    name="apotek" value="1">
                                <label class="custom-control-label" for="apotek">Gudang adalah Apotek</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="warehouse" name="warehouse"
                                    value="1">
                                <label class="custom-control-label" for="warehouse">Gudang adalah Penyimpanan</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            {{-- Checkbox Default Apotek --}}
                            <div class="custom-control custom-checkbox mb-2">
                                <input type="checkbox" class="custom-control-input default-apotek-checkbox"
                                    id="rajal_default" name="rajal_default" value="1">
                                <label class="custom-control-label" for="rajal_default">Default Apotek Rajal</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input default-apotek-checkbox"
                                    id="ranap_default" name="ranap_default" value="1">
                                <label class="custom-control-label" for="ranap_default">Default Apotek Ranap</label>
                            </div>
                        </div>
                    </div>

                    {{-- Status Aktif --}}
                    <div class="form-group">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <div class="frame-wrap">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="status_aktif" name="aktif"
                                    value="1" required>
                                <label class="custom-control-label" for="status_aktif">Aktif</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="status_nonaktif" name="aktif"
                                    value="0" required>
                                <label class="custom-control-label" for="status_nonaktif">Non Aktif</label>
                            </div>
                        </div>
                    </div>

                    {{-- === AKHIR FORM FIELDS === --}}

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" id="saveBtn" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
