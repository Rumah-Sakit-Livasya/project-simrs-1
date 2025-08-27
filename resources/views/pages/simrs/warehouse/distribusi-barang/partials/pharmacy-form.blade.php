<style>
    .datepicker {
        width: 100%;
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

                    <form action="{{ route('warehouse.distribusi-barang.pharmacy') }}" method="get">
                        @csrf

                        <div class="row justify-content-center">
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-3" style="text-align: right">
                                            <label class="form-label text-end" for="tanggal_db">
                                                Tanggal DB
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
                                            <label class="form-label text-end" for="kode_db">
                                                Kode DB
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <input type="text" value="{{ request('kode_db') }}"
                                                style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                class="form-control" id="kode_db" name="kode_db">
                                            @error('kode_db')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
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
                                            <input type="text" value="{{ request('nama_barang') }}"
                                                style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                class="form-control" id="nama_barang" name="nama_barang">
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
                                        <div class="col-xl-3" style="text-align: right">
                                            <label class="form-label text-end" for="status">
                                                Status
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            {{-- select, draft / final --}}
                                            <select class="form-control" name="status" id="status">
                                                <option value="">Semua</option>
                                                <option value="draft">Draft</option>
                                                <option value="final">Final</option>
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
                                            <select name="asal_gudang_id" class="form-control select2" id="asal-gudang">
                                                <option value="" disabled selected hidden>Pilih Gudang
                                                </option>
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
                                            <select name="tujuan_gudang_id" id="tujuan-gudang"
                                                class="form-control select2">
                                                <option value="" disabled selected hidden>Pilih Gudang
                                                </option>
                                                @foreach ($gudangs as $gudang)
                                                    <option value="{{ $gudang->id }}">{{ $gudang->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row justify-content-end mt-3">
                            <div class="col-xl-3">
                                <button type="submit" class="btn btn-outline-primary waves-effect waves-themed">
                                    <span class="fal fa-search mr-1"></span>
                                    Cari
                                </button>
                            </div>
                            <div class="col-xl-3">
                                <button type="button" class="btn btn-primary waves-effect waves-themed"
                                    id="tambah-btn">
                                    <span class="fal fa-plus mr-1"></span>
                                    Distribusi Barang
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
