<div class="modal fade" id="ubah-transaksi{{ $t->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form autocomplete="off" novalidate action="{{ route('transaksi.update', $t->id) }}" method="post"
                enctype="multipart/form-data">
                @method('put')
                @csrf
                <input type="hidden" name="oldImage" value="{{ $t->foto }}">
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Transaksi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="datepicker-modal-2">Tanggal Transaksi</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text fs-xl"><i class="fal fa-calendar"></i></span>
                                    </div>
                                    <input type="text" id="datepicker-modal-2" class="form-control"
                                        placeholder="Tanggal Transaksi" aria-label="date"
                                        aria-describedby="datepicker-modal-2" name="tanggal"
                                        value="{{ old('tanggal', $t->tanggal) }}">
                                </div>
                                @error('tanggal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="kategoriTransaksi">
                                    Kategori
                                </label>
                                <select class="form-control w-100 @error('category') is-invalid @enderror"
                                    id="kategoriTransaksiUpdate{{ $t->id }}" name="category_id">
                                    <optgroup label="Pilih Kategori Transaksi">
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ $category->id === old('category_id', $t->category_id) ?? 'selected' }}>
                                                {{ $category->nama }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="nominal">Nominal</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp.</span>
                                    </div>
                                    <input type="number" id="nominal" class="form-control" placeholder="Nominal"
                                        name="nominal" aria-label="Nominal" onkeyup="formatRupiah(this)"
                                        aria-describedby="nominal" value="{{ old('nominal', $t->nominal) }}">
                                </div>
                                @error('nominal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label" for="rekeningBank">
                                    Rekening Bank
                                </label>
                                <select class="form-control w-100 @error('bank_id') is-invalid @enderror"
                                    id="rekeningBankUpdate{{ $t->id }}" name="bank_id">
                                    <optgroup label="Pilih Rekening">
                                        @foreach ($banks as $bank)
                                            <option value="{{ $bank->id }}"
                                                {{ $bank->id === old('bank_id', $t->bank_id) ?? 'selected' }}>
                                                {{ $bank->nama }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                                @error('bank_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="jenisTransaksi">
                                    Jenis
                                </label>
                                <select class="form-control w-100 @error('jenis') is-invalid @enderror"
                                    id="jenisTransaksiUpdate{{ $t->id }}" name="jenis">
                                    <optgroup label="Jenis">
                                        <option value="pemasukan"
                                            {{ old('jenis', $t->jenis) === 'pemasukan' ?? 'selected' }}>
                                            Pemasukan</option>
                                        <option value="pengeluaran"
                                            {{ old('jenis', $t->jenis) === 'pengeluaran' ?? 'selected' }}>
                                            Pengeluaran</option>
                                    </optgroup>
                                </select>
                                @error('jenis')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Upload File</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('foto') is-invalid @enderror"
                                        id="customFile" name="foto">
                                    <label class="custom-file-label" for="customFile">Upload bukti transaksi</label>
                                </div>
                                <span class="help-block">File yang di perbolehkan *PDF | *JPG | *jpeg | *PNG</span>
                                @error('foto')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="form-label" for="keterangan">Keterangan</label>
                                <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan"
                                    rows="5">{{ old('keterangan', $t->keterangan) }}</textarea>
                                @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">
                        <span class="fal fa-pencil mr-1"></span>
                        Ubah
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
