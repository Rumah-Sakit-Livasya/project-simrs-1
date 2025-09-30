{{-- File ini di-include di dalam loop foreach pada datatable --}}
<div class="modal fade" id="editModal{{ $gudang->id }}" tabindex="-1" role="dialog"
    aria-labelledby="editModalLabel{{ $gudang->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('warehouse.master-data.master-gudang.update', $gudang->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel{{ $gudang->id }}">Edit Master Gudang:
                        {{ $gudang->nama }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- Nama Gudang --}}
                    <div class="form-group mb-3">
                        <label class="form-label" for="nama">Nama Gudang <span class="text-danger">*</span></label>
                        <input type="text" id="nama" name="nama"
                            class="form-control @error('nama') is-invalid @enderror"
                            value="{{ old('nama', isset($gudang) ? $gudang->nama : '') }}" required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Cost Center (dengan Select2) --}}
                    <div class="form-group mb-3">
                        <label class="form-label" for="cost_center">Cost Center</label>
                        <select id="cost_center" name="cost_center"
                            class="form-control select2 @error('cost_center') is-invalid @enderror">
                            <option value="" disabled selected>Pilih Cost Center</option>
                            @foreach ($costCenters as $center)
                                <option value="{{ $center->nama_rnc }}"
                                    {{ old('cost_center', isset($gudang) ? $gudang->cost_center : '') == $center->nama_rnc ? 'selected' : '' }}>
                                    {{ $center->nama_rnc }}
                                </option>
                            @endforeach
                        </select>
                        @error('cost_center')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            {{-- Checkbox Apotek & Warehouse --}}
                            <div class="custom-control custom-checkbox mb-2">
                                <input type="checkbox" class="custom-control-input apotek-checkbox"
                                    id="apotek{{ isset($gudang) ? $gudang->id : '' }}" name="apotek" value="1"
                                    {{ old('apotek', isset($gudang) && $gudang->apotek) ? 'checked' : '' }}>
                                <label class="custom-control-label"
                                    for="apotek{{ isset($gudang) ? $gudang->id : '' }}">Gudang adalah
                                    Apotek</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input"
                                    id="warehouse{{ isset($gudang) ? $gudang->id : '' }}" name="warehouse"
                                    value="1"
                                    {{ old('warehouse', isset($gudang) && $gudang->warehouse) ? 'checked' : '' }}>
                                <label class="custom-control-label"
                                    for="warehouse{{ isset($gudang) ? $gudang->id : '' }}">Gudang adalah
                                    Penyimpanan</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            {{-- Checkbox Default Apotek --}}
                            <div class="custom-control custom-checkbox mb-2">
                                <input type="checkbox" class="custom-control-input default-apotek-checkbox"
                                    id="rajal_default{{ isset($gudang) ? $gudang->id : '' }}" name="rajal_default"
                                    value="1"
                                    {{ old('rajal_default', isset($gudang) && $gudang->rajal_default) ? 'checked' : '' }}>
                                <label class="custom-control-label"
                                    for="rajal_default{{ isset($gudang) ? $gudang->id : '' }}">Default
                                    Apotek Rajal</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input default-apotek-checkbox"
                                    id="ranap_default{{ isset($gudang) ? $gudang->id : '' }}" name="ranap_default"
                                    value="1"
                                    {{ old('ranap_default', isset($gudang) && $gudang->ranap_default) ? 'checked' : '' }}>
                                <label class="custom-control-label"
                                    for="ranap_default{{ isset($gudang) ? $gudang->id : '' }}">Default
                                    Apotek Ranap</label>
                            </div>
                        </div>
                    </div>

                    {{-- Status Aktif --}}
                    <div class="form-group">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <div class="frame-wrap">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input"
                                    id="status_aktif{{ isset($gudang) ? $gudang->id : '' }}" name="aktif"
                                    value="1"
                                    {{ old('aktif', isset($gudang) ? $gudang->aktif : '1') == '1' ? 'checked' : '' }}
                                    required>
                                <label class="custom-control-label"
                                    for="status_aktif{{ isset($gudang) ? $gudang->id : '' }}">Aktif</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input"
                                    id="status_nonaktif{{ isset($gudang) ? $gudang->id : '' }}" name="aktif"
                                    value="0"
                                    {{ old('aktif', isset($gudang) ? $gudang->aktif : '') == '0' ? 'checked' : '' }}
                                    required>
                                <label class="custom-control-label"
                                    for="status_nonaktif{{ isset($gudang) ? $gudang->id : '' }}">Non
                                    Aktif</label>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fal fa-save"></i>
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
