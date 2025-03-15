<div class="modal fade" id="ubah-kategori{{ $category->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form autocomplete="off" novalidate action="{{ route('category.update', $category->id) }}" method="post">
                @method('post')
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Kategori</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label" for="typeKategoriUpdate{{ $category->id }}">
                            Tipe
                        </label>
                        <select class="form-control w-100 @error('type_id') is-invalid @enderror"
                            id="typeKategoriUpdate{{ $category->id }}" name="type_id">
                            <optgroup label="Type">
                                @foreach ($types as $type)
                                    <option value="{{ $type->id }}"
                                        {{ $type->id === old('type_id', $category->type_id) ?? 'selected' }}>
                                        {{ $type->nama }}
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
                        <input type="text" value="{{ old('nama', $category->nama) }}"
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
                        <span class="fal fa-pencil mr-1"></span>
                        Ubah
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
