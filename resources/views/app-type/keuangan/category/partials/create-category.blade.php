<div class="modal fade" id="tambah-kategori" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form autocomplete="off" novalidate action="{{ route('category.store') }}" method="post">
                @method('post')
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kategori</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label" for="typeKategori">
                            Tipe
                        </label>
                        <select class="form-control w-100 @error('type_id') is-invalid @enderror" id="typeKategori"
                            name="type_id">
                            <optgroup label="Type">
                                @foreach ($types as $type)
                                    <option value="{{ $type->id }}"
                                        {{ $type->id === old('type_id') ?? 'selected' }}>{{ $type->nama }}
                                    </option>
                                @endforeach
                            </optgroup>
                        </select>
                        @error('jenis')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="nama">Nama Kategori</label>
                        <input type="text" value="{{ old('nama') }}"
                            class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama"
                            placeholder="Nama Kategori">
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">
                        <span class="fal fa-plus-circle mr-1"></span>
                        Tambah
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
