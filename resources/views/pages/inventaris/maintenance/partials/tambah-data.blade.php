<div class="modal fade" id="tambah-data" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form autocomplete="off" novalidate action="javascript:void(0)" method="post" enctype="multipart/form-data"
                id="store-form">
                @method('post')
                @csrf
                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                <input type="hidden" name="barang_id" value="{{ $barang->id }}">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label" for="datepicker-modal-2">Tanggal</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text fs-xl"><i class="fal fa-calendar"></i></span>
                            </div>
                            <input type="text" id="datepicker-modal-2"
                                class="form-control @error('tanggal') is-invalid @enderror" placeholder="Select a date"
                                name="tanggal">
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @include('components.notification.error')
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="kondisi">Kondisi</label>
                        <input type="text" value="{{ old('kondisi') }}"
                            class="form-control @error('kondisi') is-invalid @enderror" id="kondisi" name="kondisi"
                            placeholder="Kondisi Awal" onkeyup="this.value = this.value.toUpperCase()">
                        @error('kondisi')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @include('components.notification.error')
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="hasil">Hasil</label>
                        <textarea class="form-control @error('kondisi') is-invalid @enderror" name="hasil" id="hasil" rows="5"></textarea>
                        @error('hasil')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @include('components.notification.error')
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="rtl">Rencana Tindak Lanjut</label>
                        <textarea class="form-control @error('kondisi') is-invalid @enderror" name="rtl" id="rtl" rows="5"></textarea>
                        @error('rtl')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @include('components.notification.error')
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="foto2">Gambar</label>
                        <img class="image-preview img-fluid mb-3 col-sm-5 d-block">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="foto" name="foto"
                                onchange="previewImage()">
                            <label class="custom-file-label" for="foto">Pilih Gambar Galeri</label>
                        </div>
                        @error('foto')
                            <p class="text-danger">{{ $message }}</p>
                            <div class="invalid-feedback">{{ $message }}</div>
                            @include('components.notification.error')
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
