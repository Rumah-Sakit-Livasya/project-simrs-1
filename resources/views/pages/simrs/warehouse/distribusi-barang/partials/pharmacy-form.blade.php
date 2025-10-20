<form id="filter-form">
    <div class="form-row">
        <div class="form-group col-md-6">
            <label class="form-label" for="tanggal_db">Tanggal DB</label>
            <input type="text" class="form-control datepicker" id="tanggal_db" name="tanggal_db"
                placeholder="Pilih rentang tanggal">
        </div>
        <div class="form-group col-md-6">
            <label class="form-label" for="kode_db">Kode DB</label>
            <input type="text" class="form-control" id="kode_db" name="kode_db" placeholder="Masukkan kode DB">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
            <label class="form-label" for="nama_barang">Nama Barang</label>
            <input type="text" class="form-control" id="nama_barang" name="nama_barang"
                placeholder="Masukkan nama barang">
        </div>
        <div class="form-group col-md-6">
            <label class="form-label" for="status">Status</label>
            <select class="form-control select2" name="status" id="status">
                <option value="">Semua Status</option>
                <option value="draft">Draft</option>
                <option value="final">Final</option>
            </select>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
            <label class="form-label" for="asal_gudang_id">Gudang Asal</label>
            <select name="asal_gudang_id" class="form-control select2" id="asal-gudang">
                <option value="">Semua Gudang</option>
                @foreach ($gudang_asals as $gudang)
                    <option value="{{ $gudang->id }}">{{ $gudang->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-6">
            <label class="form-label" for="tujuan_gudang_id">Gudang Tujuan</label>
            <select name="tujuan_gudang_id" id="tujuan-gudang" class="form-control select2">
                <option value="">Semua Gudang</option>
                @foreach ($gudangs as $gudang)
                    <option value="{{ $gudang->id }}">{{ $gudang->nama }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div
        class="panel-content border-faded border-left-0 border-right-0 border-bottom-0 d-flex flex-row align-items-center">
        <button class="btn btn-primary ml-auto" type="submit">Cari</button>
    </div>
</form>
