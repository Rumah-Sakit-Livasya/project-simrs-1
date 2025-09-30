<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <i class="fal fa-filter mr-2"></i>
                    Filter <span class="fw-300"><i>Pencarian</i></span>
                </h2>
                <div class="panel-toolbar">
                    <button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10"
                        data-original-title="Collapse"></button>
                    <button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip"
                        data-offset="0,10" data-original-title="Fullscreen"></button>
                </div>
            </div>
            <form action="{{ route('warehouse.master-data.master-gudang.index') }}" method="get">
                <div class="panel-container show">
                    <div class="panel-content">
                        <div class="row">
                            {{-- Nama Gudang --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label" for="filter_nama">Nama Gudang</label>
                                <input type="text" value="{{ request('nama') }}" class="form-control"
                                    id="filter_nama" name="nama" placeholder="Cari nama gudang...">
                            </div>
                            {{-- Filter Apotek --}}
                            <div class="col-md-2 mb-3">
                                <label for="filter_apotek" class="form-label">Apotek?</label>
                                <select class="form-control select2" id="filter_apotek" name="apotek">
                                    <option value="">Semua</option>
                                    <option value="1" @if (request('apotek') == '1') selected @endif>Ya</option>
                                    <option value="0" @if (request('apotek') == '0') selected @endif>Tidak
                                    </option>
                                </select>
                            </div>
                            {{-- Filter Warehouse --}}
                            <div class="col-md-2 mb-3">
                                <label for="filter_warehouse" class="form-label">Warehouse?</label>
                                <select class="form-control select2" id="filter_warehouse" name="warehouse">
                                    <option value="">Semua</option>
                                    <option value="1" @if (request('warehouse') == '1') selected @endif>Ya</option>
                                    <option value="0" @if (request('warehouse') == '0') selected @endif>Tidak
                                    </option>
                                </select>
                            </div>
                            {{-- Filter Status --}}
                            <div class="col-md-4 mb-3">
                                <label for="filter_aktif" class="form-label">Status</label>
                                <select class="form-control select2" id="filter_aktif" name="aktif">
                                    <option value="">Semua</option>
                                    <option value="1" @if (request('aktif') == '1') selected @endif>Aktif
                                    </option>
                                    <option value="0" @if (request('aktif') == '0') selected @endif>Non Aktif
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div
                        class="panel-content border-faded border-left-0 border-right-0 border-bottom-0 d-flex flex-row justify-content-end">
                        <a href="{{ route('warehouse.master-data.master-gudang.index') }}" class="btn btn-secondary">
                            <i class="fal fa-sync mr-1"></i>
                            Reset
                        </a>
                        <button type="submit" class="btn btn-primary ml-2">
                            <i class="fal fa-search mr-1"></i>
                            Cari
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
