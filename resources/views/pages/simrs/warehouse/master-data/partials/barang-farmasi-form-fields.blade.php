<div class="row">
    <div class="col-md-4 mb-3">
        <label for="kode" class="form-label">Kode Barang <span class="text-danger">*</span></label>
        <input type="text" name="kode" id="kode" class="form-control @error('kode') is-invalid @enderror"
            value="{{ old('kode', $barang->kode ?? '') }}" required autocomplete="off">
        @error('kode')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-8 mb-3">
        <label for="nama" class="form-label">Nama Barang <span class="text-danger">*</span></label>
        <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror"
            value="{{ old('nama', $barang->nama ?? '') }}" required autocomplete="off">
        @error('nama')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4 mb-3">
        <label for="kategori_id" class="form-label">Kategori <span class="text-danger">*</span></label>
        <select name="kategori_id" id="kategori_id"
            class="form-select select2 @error('kategori_id') is-invalid @enderror" required>
            <option value="">-- Pilih Kategori --</option>
            @foreach ($kategoris as $kategori)
                <option value="{{ $kategori->id }}"
                    {{ old('kategori_id', $barang->kategori_id ?? '') == $kategori->id ? 'selected' : '' }}>
                    {{ $kategori->nama }}
                </option>
            @endforeach
        </select>
        @error('kategori_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label for="kelompok_id" class="form-label">Kelompok</label>
        <select name="kelompok_id" id="kelompok_id"
            class="form-select select2 @error('kelompok_id') is-invalid @enderror">
            <option value="">-- Pilih Kelompok --</option>
            @foreach ($kelompoks as $kelompok)
                <option value="{{ $kelompok->id }}"
                    {{ old('kelompok_id', $barang->kelompok_id ?? '') == $kelompok->id ? 'selected' : '' }}>
                    {{ $kelompok->nama }}
                </option>
            @endforeach
        </select>
        @error('kelompok_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label for="golongan_id" class="form-label">Golongan</label>
        <select name="golongan_id" id="golongan_id"
            class="form-select select2 @error('golongan_id') is-invalid @enderror">
            <option value="">-- Pilih Golongan --</option>
            @foreach ($golongans as $golongan)
                <option value="{{ $golongan->id }}"
                    {{ old('golongan_id', $barang->golongan_id ?? '') == $golongan->id ? 'selected' : '' }}>
                    {{ $golongan->nama }}
                </option>
            @endforeach
        </select>
        @error('golongan_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4 mb-3">
        <label for="satuan_id" class="form-label">Satuan <span class="text-danger">*</span></label>
        <select name="satuan_id" id="satuan_id" class="form-select select2 @error('satuan_id') is-invalid @enderror"
            required>
            <option value="">-- Pilih Satuan --</option>
            @foreach ($satuans as $satuan)
                <option value="{{ $satuan->id }}"
                    {{ old('satuan_id', $barang->satuan_id ?? '') == $satuan->id ? 'selected' : '' }}>
                    {{ $satuan->nama }}
                </option>
            @endforeach
        </select>
        @error('satuan_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label for="principal" class="form-label">Principal</label>
        <select name="principal" id="principal" class="form-select select2 @error('principal') is-invalid @enderror">
            <option value="">-- Pilih Principal --</option>
            @foreach ($pabriks as $pabrik)
                <option value="{{ $pabrik->id }}"
                    {{ old('principal', $barang->principal ?? '') == $pabrik->id ? 'selected' : '' }}>
                    {{ $pabrik->nama }}
                </option>
            @endforeach
        </select>
        @error('principal')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label for="hna" class="form-label">HNA <span class="text-danger">*</span></label>
        <input type="number" name="hna" id="hna" class="form-control @error('hna') is-invalid @enderror"
            value="{{ old('hna', $barang->hna ?? '') }}" required>
        @error('hna')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4 mb-3">
        <label for="harga_principal" class="form-label">Harga Principal</label>
        <input type="number" name="harga_principal" id="harga_principal"
            class="form-control @error('harga_principal') is-invalid @enderror"
            value="{{ old('harga_principal', $barang->harga_principal ?? '') }}">
        @error('harga_principal')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label for="diskon_principal" class="form-label">Diskon Principal (%)</label>
        <input type="number" name="diskon_principal" id="diskon_principal"
            class="form-control @error('diskon_principal') is-invalid @enderror"
            value="{{ old('diskon_principal', $barang->diskon_principal ?? '') }}">
        @error('diskon_principal')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4 mb-3">
        <label for="ppn" class="form-label">PPN (%) <span class="text-danger">*</span></label>
        <input type="number" name="ppn" id="ppn" class="form-control @error('ppn') is-invalid @enderror"
            value="{{ old('ppn', $barang->ppn ?? '') }}" required>
        @error('ppn')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label for="ppn_rajal" class="form-label">PPN Rajal (%) <span class="text-danger">*</span></label>
        <input type="number" name="ppn_rajal" id="ppn_rajal"
            class="form-control @error('ppn_rajal') is-invalid @enderror"
            value="{{ old('ppn_rajal', $barang->ppn_rajal ?? '') }}" required>
        @error('ppn_rajal')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label for="ppn_ranap" class="form-label">PPN Ranap (%) <span class="text-danger">*</span></label>
        <input type="number" name="ppn_ranap" id="ppn_ranap"
            class="form-control @error('ppn_ranap') is-invalid @enderror"
            value="{{ old('ppn_ranap', $barang->ppn_ranap ?? '') }}" required>
        @error('ppn_ranap')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4 mb-3">
        <label for="tipe" class="form-label">Tipe <span class="text-danger">*</span></label>
        <select name="tipe" id="tipe" class="form-select @error('tipe') is-invalid @enderror" required>
            <option value="">-- Pilih Tipe --</option>
            <option value="FN" {{ old('tipe', $barang->tipe ?? '') == 'FN' ? 'selected' : '' }}>FN</option>
            <option value="NFN" {{ old('tipe', $barang->tipe ?? '') == 'NFN' ? 'selected' : '' }}>NFN</option>
        </select>
        @error('tipe')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label for="formularium" class="form-label">Formularium <span class="text-danger">*</span></label>
        <select name="formularium" id="formularium" class="form-select @error('formularium') is-invalid @enderror"
            required>
            <option value="">-- Pilih Formularium --</option>
            <option value="RS" {{ old('formularium', $barang->formularium ?? '') == 'RS' ? 'selected' : '' }}>RS
            </option>
            <option value="NRS" {{ old('formularium', $barang->formularium ?? '') == 'NRS' ? 'selected' : '' }}>NRS
            </option>
        </select>
        @error('formularium')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label for="jenis_obat" class="form-label">Jenis Obat</label>
        <select name="jenis_obat" id="jenis_obat" class="form-select @error('jenis_obat') is-invalid @enderror">
            <option value="">-- Pilih Jenis --</option>
            <option value="paten" {{ old('jenis_obat', $barang->jenis_obat ?? '') == 'paten' ? 'selected' : '' }}>
                Paten</option>
            <option value="generik" {{ old('jenis_obat', $barang->jenis_obat ?? '') == 'generik' ? 'selected' : '' }}>
                Generik</option>
        </select>
        @error('jenis_obat')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label for="exp" class="form-label">Kadaluarsa (EXP Reminder)</label>
        <select name="exp" id="exp" class="form-select @error('exp') is-invalid @enderror">
            <option value="">-- Pilih --</option>
            <option value="1w" {{ old('exp', $barang->exp ?? '') == '1w' ? 'selected' : '' }}>1 Minggu</option>
            <option value="2w" {{ old('exp', $barang->exp ?? '') == '2w' ? 'selected' : '' }}>2 Minggu</option>
            <option value="3w" {{ old('exp', $barang->exp ?? '') == '3w' ? 'selected' : '' }}>3 Minggu</option>
            <option value="1mo" {{ old('exp', $barang->exp ?? '') == '1mo' ? 'selected' : '' }}>1 Bulan</option>
            <option value="2mo" {{ old('exp', $barang->exp ?? '') == '2mo' ? 'selected' : '' }}>2 Bulan</option>
            <option value="3mo" {{ old('exp', $barang->exp ?? '') == '3mo' ? 'selected' : '' }}>3 Bulan</option>
            <option value="6mo" {{ old('exp', $barang->exp ?? '') == '6mo' ? 'selected' : '' }}>6 Bulan</option>
        </select>
        @error('exp')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-8 mb-3">
        <label for="zat_aktif" class="form-label">Zat Aktif</label>
        <select name="zat_aktif[]" id="zat_aktif"
            class="form-select select2 @error('zat_aktif') is-invalid @enderror" multiple>
            @foreach ($zats as $zat)
                <option value="{{ $zat->id }}"
                    {{ in_array($zat->id, old('zat_aktif', isset($barang) && $barang->zat_aktif ? $barang->zat_aktif->pluck('id')->toArray() : [])) ? 'selected' : '' }}>
                    {{ $zat->nama }}
                </option>
            @endforeach
        </select>
        @error('zat_aktif')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-12 mb-3">
        <label for="keterangan" class="form-label">Keterangan</label>
        <textarea name="keterangan" id="keterangan" class="form-control @error('keterangan') is-invalid @enderror"
            rows="2">{{ old('keterangan', $barang->keterangan ?? '') }}</textarea>
        @error('keterangan')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-12 mb-3">
        <label for="restriksi" class="form-label">Restriksi</label>
        <textarea name="restriksi" id="restriksi" class="form-control @error('restriksi') is-invalid @enderror"
            rows="2">{{ old('restriksi', $barang->restriksi ?? '') }}</textarea>
        @error('restriksi')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4 mb-3">
        <label for="aktif" class="form-label">Status <span class="text-danger">*</span></label>
        <select name="aktif" id="aktif" class="form-select @error('aktif') is-invalid @enderror" required>
            <option value="1" {{ old('aktif', $barang->aktif ?? 1) == 1 ? 'selected' : '' }}>Aktif</option>
            <option value="0" {{ old('aktif', $barang->aktif ?? 1) == 0 ? 'selected' : '' }}>Non Aktif</option>
        </select>
        @error('aktif')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<hr>
<h5 class="mb-2">Satuan Tambahan</h5>
<div id="satuan-tambahan-container">
    @php
        $oldSatuansId = old(
            'satuans_id',
            isset($barang) && $barang->satuan_tambahan ? $barang->satuan_tambahan->pluck('satuan_id')->toArray() : [],
        );
        $oldSatuansJumlah = old(
            'satuans_jumlah',
            isset($barang) && $barang->satuan_tambahan ? $barang->satuan_tambahan->pluck('isi')->toArray() : [],
        );
        $oldSatuansStatus = old(
            'satuans_status',
            isset($barang) && $barang->satuan_tambahan ? $barang->satuan_tambahan->pluck('aktif')->toArray() : [],
        );
    @endphp
    @if (count($oldSatuansId))
        @foreach ($oldSatuansId as $index => $satuanId)
            <div class="row satuan-tambahan-row mb-2 align-items-end">
                <div class="col-md-5">
                    <label class="form-label">Satuan</label>
                    <select name="satuans_id[]" class="form-select select2">
                        <option value="">-- Pilih Satuan --</option>
                        @foreach ($satuans as $satuan)
                            <option value="{{ $satuan->id }}" {{ $satuanId == $satuan->id ? 'selected' : '' }}>
                                {{ $satuan->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Isi</label>
                    <input type="number" name="satuans_jumlah[]" class="form-control"
                        value="{{ $oldSatuansJumlah[$index] ?? '' }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Aktif</label>
                    <div class="form-check mt-2">
                        <input type="checkbox" name="satuans_status[{{ $index }}]" value="1"
                            class="form-check-input"
                            {{ isset($oldSatuansStatus[$index]) && $oldSatuansStatus[$index] ? 'checked' : '' }}>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-danger btn-sm remove-satuan-tambahan"><i
                            class="fal fa-trash"></i></button>
                </div>
            </div>
        @endforeach
    @endif
</div>
<button type="button" class="btn btn-outline-success btn-sm" id="add-satuan-tambahan"><i class="fal fa-plus"></i>
    Tambah Satuan</button>
