<div class="modal fade  p-0" id="tambah-data" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-xl modal-dialog" role="document">
        <div class="modal-content">
            <form autocomplete="off" novalidate method="post" id="store-form">
                @method('post')
                @csrf
                <div class="modal-header">
                    <h5 class="font-weight-bold">Tambah Perusahaan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body py-0">

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="File">Logo <span class="text-danger">*</span></label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('logo') is-invalid @enderror"
                                        name="logo" id="customFile">
                                    <label class="custom-file-label" for="customFile">Pilih Logo</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email">Email <span class="text-danger">*</span></label>
                                <input type="email" value="{{ old('email') }}"
                                    class="form-control @error('email') is-invalid @enderror" id="email"
                                    name="email" placeholder="Masukan Email">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Nama Perusahaan <span class="text-danger">*</span></label>
                                <input type="text" value="{{ old('name') }}"
                                    class="form-control @error('name') is-invalid @enderror" id="name"
                                    name="name" placeholder="Masukan Nama Perusahaan">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="phone_number">No Tel <span class="text-danger">*</span></label>
                                <input type="text" value="{{ old('phone_number') }}"
                                    class="form-control @error('phone_number') is-invalid @enderror" id="phone_number"
                                    name="phone_number" placeholder="Masukan No Tel">
                                @error('phone_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <label class="form-label font-weight-normal" for="address">Alamat <span
                                class="text-danger">*</span></label>
                        <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3"></textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="province">Provinsi <span class="text-danger">*</span></label>
                                <input type="text" value="{{ old('province') }}"
                                    class="form-control @error('province') is-invalid @enderror" id="province"
                                    name="province" placeholder="Masukan Provinsi">
                                @error('province')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="category">Kategori</label>
                                <input type="text" value="{{ old('category') }}"
                                    class="form-control @error('category') is-invalid @enderror" id="category"
                                    name="category" placeholder="Masukan Kategori">
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="city">Kota <span class="text-danger">*</span></label>
                                <input type="text" value="{{ old('city') }}"
                                    class="form-control @error('city') is-invalid @enderror" id="city"
                                    name="city" placeholder="Masukan Kota">
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="class">Kelas</label>
                                <input type="text" value="{{ old('class') }}"
                                    class="form-control @error('class') is-invalid @enderror" id="class"
                                    name="class" placeholder="Masukan Kelas">
                                @error('class')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group mt-3">
                                <label for="operating_permit_number">izin Operasional</label>
                                <input type="text" value="{{ old('operating_permit_number') }}"
                                    class="form-control @error('operating_permit_number') is-invalid @enderror"
                                    id="operating_permit_number" name="operating_permit_number"
                                    placeholder="Masukan izin Operasional">
                                @error('operating_permit_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mt-3">
                                <label for="code">Kode Perusahaan</label>
                                <input type="text" value="{{ old('code') }}"
                                    class="form-control @error('code') is-invalid @enderror" id="code"
                                    name="code" placeholder="Masukan Kode Perusahaan">
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">
                        <div class="ikon-tambah">
                            <span class="fal fa-plus-circle mr-1"></span>
                            Tambah
                        </div>
                        <div class="span spinner-text d-none">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Loading...
                        </div>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
