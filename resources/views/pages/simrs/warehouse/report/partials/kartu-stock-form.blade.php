<div class="panel">
    <div class="panel-hdr">
        <h2>Filter Laporan Kartu Stok</h2>
    </div>
    <div class="panel-container show">
        <div class="panel-content">
            <form action="{{ route('warehouse.report.kartu-stock') }}" method="post" id="filter-form">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label class="form-label" for="tanggal">Tanggal</label>
                        <input type="text" class="form-control" value="{{ request('tanggal') }}" id="datepicker-1"
                            name="tanggal" required>
                    </div>
                    <div class="form-group col-md-5">
                        <label class="form-label" for="satuan_barang_type">Barang*</label>
                        <select name="satuan_barang_type" class="form-control select2" required>
                            <option value="" disabled selected>Pilih Barang...</option>
                            @foreach ($barangs as $barang)
                                @php $id_main = ($barang->satuan->id ?? '') . '_' . $barang->id . '_' . $barang->type; @endphp
                                @if ($barang->satuan)
                                    <option value="{{ $id_main }}"
                                        {{ request('satuan_barang_type') == $id_main ? 'selected' : '' }}>
                                        [{{ $barang->satuan->kode ?? 'N/A' }}] {{ $barang->nama }}
                                    </option>
                                @endif
                                @foreach ($barang->satuan_tambahan as $st)
                                    @php $id_sub = ($st->satuan->id ?? '') . '_' . $barang->id . '_' . $barang->type; @endphp
                                    @if ($st->satuan)
                                        <option value="{{ $id_sub }}"
                                            {{ request('satuan_barang_type') == $id_sub ? 'selected' : '' }}>
                                            [{{ $st->satuan->kode ?? 'N/A' }}] {{ $barang->nama }}
                                        </option>
                                    @endif
                                @endforeach
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label class="form-label" for="gudang_id">Gudang</label>
                        <select name="gudang_id" class="form-control select2">
                            <option value="">Semua Gudang</option>
                            @foreach ($gudangs as $gudang)
                                <option value="{{ $gudang->id }}"
                                    {{ request('gudang_id') == $gudang->id ? 'selected' : '' }}>
                                    {{ $gudang->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div
                    class="panel-content border-faded border-left-0 border-right-0 border-bottom-0 d-flex flex-row align-items-center">
                    <button type="submit" class="btn btn-primary ml-auto" id="btn-cari">
                        <i class="fal fa-search"></i> Cari
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
