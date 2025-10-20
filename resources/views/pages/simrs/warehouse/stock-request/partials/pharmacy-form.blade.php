<form id="filter-form" action="{{ route('warehouse.stock-request.pharmacy.index') }}" method="get" autocomplete="off">
    <div class="row justify-content-center mb-3">
        <div class="col-xl-6">
            <div class="form-group">
                <div class="row">
                    <div class="col-xl-2 text-end">
                        <label for="tanggal_sr" class="form-label">
                            Tanggal SR
                        </label>
                    </div>
                    <div class="col-xl">
                        <input type="text" class="form-control datepicker" id="datepicker-1" name="tanggal_sr"
                            placeholder="mm/dd/yyyy - mm/dd/yyyy" value="{{ request('tanggal_sr') }}"
                            autocomplete="off">
                        @error('tanggal_sr')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="form-group">
                <div class="row">
                    <div class="col-xl-2 text-end">
                        <label for="kode_sr" class="form-label">
                            Kode SR
                        </label>
                    </div>
                    <div class="col-xl">
                        <input type="text" class="form-control" id="kode_sr" name="kode_sr"
                            value="{{ request('kode_sr') }}"
                            style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0">
                        @error('kode_sr')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center mb-3">
        <div class="col-xl-6">
            <div class="form-group">
                <div class="row">
                    <div class="col-xl-2 text-end">
                        <label for="nama_barang" class="form-label">
                            Nama Barang
                        </label>
                    </div>
                    <div class="col-xl">
                        <input type="text" class="form-control" id="nama_barang" name="nama_barang"
                            value="{{ request('nama_barang') }}"
                            style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0">
                        @error('nama_barang')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="form-group">
                <div class="row">
                    <div class="col-xl-2 text-end">
                        <label for="status" class="form-label">
                            Status
                        </label>
                    </div>
                    <div class="col-xl">
                        <select class="form-control" name="status" id="status">
                            <option value="">Semua</option>
                            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="final" {{ request('status') === 'final' ? 'selected' : '' }}>Final</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-end mt-3">
        <div class="col-xl-2">
            <button type="submit" class="btn btn-outline-primary waves-effect waves-themed">
                <span class="fal fa-search mr-1"></span>
                Cari
            </button>
        </div>
        <div class="col-xl-3">
            <button type="button" class="btn btn-primary waves-effect waves-themed" id="tambah-btn">
                <span class="fal fa-plus mr-1"></span>
                Stock Request
            </button>
        </div>
    </div>
</form>
