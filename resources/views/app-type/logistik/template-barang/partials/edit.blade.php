<div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form autocomplete="off" novalidate action="javascript:void(0)" method="post" enctype="multipart/form-data"
                id="update-form">
                @method('patch')
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Template</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Gambar</label>
                        <input type="hidden" name="oldImage">
                        <img class="image-preview img-fluid mb-3 col-sm-5 d-block">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="foto" name="foto"
                                onchange="previewImage()">
                            <label class="custom-file-label" for="foto">Pilih
                                Gambar Galeri</label>
                        </div>
                        @error('foto')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="category_id">
                            Kategori Barang
                        </label>
                        <select class="form-control w-100 @error('category_id') is-invalid @enderror" id="category_id"
                            name="category_id">
                            <optgroup label="Kategori Barang">
                                @foreach ($categoryBarang as $row)
                                    <option value="{{ $row->id }}"
                                        {{ old('category_id') === $row->id ? 'selected' : '' }}>
                                        {{ strtoupper($row->name) }}
                                    </option>
                                @endforeach
                            </optgroup>
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="name">Nama Barang</label>
                        <input type="text" value="{{ old('name') }}"
                            class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                            placeholder="Nama Barang">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="barang_code">Kode Barang</label>
                        <input type="text" value="{{ old('barang_code') }}"
                            class="form-control @error('barang_code') is-invalid @enderror" id="barang_code"
                            name="barang_code" placeholder="Kode Barang"
                            onkeyup="this.value = this.value.toUpperCase()">
                        @error('barang_code')
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
