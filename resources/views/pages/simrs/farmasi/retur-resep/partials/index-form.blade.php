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

                    <form action="{{ route('farmasi.retur-resep') }}" method="get">
                        @csrf

                        <div class="row justify-content-center">
                            <div class="col-xl-4">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-3" style="text-align: right">
                                            <label class="form-label text-end" for="tanggal">
                                                Tanggal
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <input type="text" value="{{ request('tanggal') }}"
                                                style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                class="form-control" id="datepicker-1" name="tanggal">
                                            @error('tanggal')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-4">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-3" style="text-align: right">
                                            <label class="form-label text-end" for="nama_pasien">
                                                Nama Pasien
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <input type="text" value="{{ request('nama_pasien') }}"
                                                style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                                class="form-control" id="nama_pasien" name="nama_pasien">
                                            @error('nama_pasien')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-4">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-4" style="text-align: right">
                                            <label class="form-label text-end" for="gudang_id">
                                                Gudang
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <select name="gudang_id" id="gudang" class="form-control select2">
                                                <option value=""
                                                    {{ request('gudang_id') == '' ? 'selected' : '' }}>Semua
                                                </option>
                                                @foreach ($gudangs as $gudang)
                                                    <option value="{{ $gudang->id }}"
                                                        {{ request('gudang_id') == $gudang->id ? 'selected' : '' }}>
                                                        {{ $gudang->nama }}</option>
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
                                    Tambah Retur
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
