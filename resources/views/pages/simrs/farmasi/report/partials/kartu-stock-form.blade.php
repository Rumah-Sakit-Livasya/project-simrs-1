<style>
    #loading-page {
        position: absolute;
        min-height: 100%;
        min-width: 100%;
        background: rgba(0, 0, 0, 0.75);
        border-radius: 0 0 4px 4px;
        z-index: 1000;
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

                    <form action="{{ route('farmasi.report.kartu-stock') }}" method="post">
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
                                            <input type="string" class="form-control"
                                                value="{{ request('tanggal') ?: '' }}" id="datepicker-1"
                                                autocomplete="off" name="tanggal">

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
                                            <label class="form-label text-end" for="satuan_barang_type">
                                                Barang*
                                            </label>
                                        </div>
                                        <div class="col-xl">
                                            <select name="satuan_barang_type" class="form-control select2" required>
                                                <option value="" selected hidden disabled>Pilih Barang</option>
                                                @foreach ($barangs as $barang)
                                                    @php
                                                        $id =
                                                            $barang->satuan->id .
                                                            '_' .
                                                            $barang->id .
                                                            '_' .
                                                            $barang->type;
                                                    @endphp
                                                    <option value="{{ $id }}"
                                                        {{ request('satuan_barang_type') == $id ? 'selected' : '' }}>
                                                        [{{ $barang->satuan->kode }}] {{ $barang->nama }}</option>
                                                    @foreach ($barang->satuan_tambahan as $st)
                                                        @php
                                                            $id =
                                                                $st->satuan->id .
                                                                '_' .
                                                                $barang->id .
                                                                '_' .
                                                                $barang->type;
                                                        @endphp

                                                        <option value="{{ $id }}"
                                                            {{ request('satuan_barang_type') == $id ? 'selected' : '' }}>
                                                            [{{ $st->satuan->kode }}] {{ $barang->nama }}</option>
                                                    @endforeach
                                                @endforeach
                                            </select>
                                            @error('satuan_barang_type')
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
                            <div class="col-xl-2">
                                <button type="submit" class="btn btn-outline-primary waves-effect waves-themed">
                                    <span class="fal fa-search mr-1"></span>
                                    Cari
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
