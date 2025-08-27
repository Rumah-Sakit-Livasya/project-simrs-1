<style>
    .datepicker {
        width: 100%
    }
</style>

<div class="row justify-content-center">
    <div class="col-xl-8">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    Form <span class="fw-300"><i>Pencarian</i></span>
                </h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">

                    <form>
                        @csrf

                        <div class="row justify-content-center">
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-3" style="text-align: right">
                                            <label class="form-label text-end" for="tanggal_db">
                                                Tanggal Distribusi
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <input type="text" class="form-control datepicker" id="datepicker-1"
                                                placeholder="mm/dd/yyyy - mm/dd/yyyy" name="tanggal_db">

                                            @error('tanggal_db')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-3" style="text-align: right">
                                            <label class="form-label text-end" for="jenis">
                                                Jenis Barang
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <select name="jenis" id="jenis" class="form-control">
                                                <option value="">Semua</option>
                                                <option value="f">Farmasi</option>
                                                <option value="nf">Non Farmasi</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center">
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-3" style="text-align: right">
                                            <label class="form-label text-end" for="asal_gudang_id">
                                                Gudang Asal
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <select name="asal_gudang_id" id="asal_gudang_id"
                                                class="form-control select2">
                                                <option value="" selected>Semua</option>
                                                @foreach ($gudang_asals as $gudang)
                                                    <option value="{{ $gudang->id }}">{{ $gudang->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-3" style="text-align: right">
                                            <label class="form-label text-end" for="tujuan_gudang_id">
                                                Gudang Tujuan
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <select name="tujuan_gudang_id" id="tujuan_gudang_id"
                                                class="form-control select2">
                                                <option value="" selected>Semua</option>
                                                @foreach ($gudangs as $gudang)
                                                    <option value="{{ $gudang->id }}">{{ $gudang->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center">
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-3" style="text-align: right">
                                            <label class="form-label text-end" for="nama_barang">
                                                Nama Barang
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <input type="text" class="form-control" id="nama-barang"
                                                name="nama_barang">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-3" style="text-align: right">
                                            <label class="form-label text-end" for="kode_db">
                                                Kode DB
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <input type="text" class="form-control" id="kode-db" name="kode_db">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row justify-content-end mt-3">
                            <div class="col-xl-3">
                                <button id="reportBtn" class="btn btn-outline-primary waves-effect waves-themed">
                                    <span class="fal fa-search mr-1"></span>
                                    Laporan
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
