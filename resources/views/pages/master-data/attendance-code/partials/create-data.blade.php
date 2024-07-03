<div class="modal fade font-weight-bold p-0" id="tambah-data" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form autocomplete="off" novalidate method="post" id="store-form">
                @method('post')
                @csrf
                <div class="modal-header">
                    <h5 class="font-weight-bold">Tambah Kode Absensi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body py-0">
                    <div class="form-group">
                        <label for="name">Kode</label>
                        <input type="text" value="{{ old('code') }}"
                            class="form-control @error('code') is-invalid @enderror" id="code" name="code"
                            placeholder="Masukan Kode">
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-body py-0 mt-2">
                    <div class="form-group mb-2">
                        <label for="name">Keterangan</label>
                        <input type="text" value="{{ old('description') }}"
                            class="form-control @error('description') is-invalid @enderror" id="description"
                            name="description" placeholder="Masukan Deksripsi">
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="name">Total Hari</label>
                        <input type="text" value="{{ old('total_hari') }}"
                            class="form-control @error('total_hari') is-invalid @enderror" id="total_hari"
                            name="total_hari" placeholder="Masukan Deksripsi">
                        @error('total_hari')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
