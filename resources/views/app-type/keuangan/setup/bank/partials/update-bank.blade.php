<div class="modal fade" id="ubah-bank{{ $bank->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form autocomplete="off" novalidate action="{{ route('bank.update', $bank->id) }}" method="post">
                @method('put')
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Bank</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama">Nama Bank</label>
                        <input type="text" value="{{ old('nama', $bank->nama) }}"
                            class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama"
                            placeholder="Nama Bank">
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="pemilik">Pemilik Rekening</label>
                        <input type="text" value="{{ old('pemilik', $bank->pemilik) }}"
                            class="form-control @error('pemilik') is-invalid @enderror" id="pemilik" name="pemilik"
                            placeholder="Pemilik Rekening">
                        @error('pemilik')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nomor">Nomor Rekening</label>
                        <input type="text" value="{{ old('nomor', $bank->nomor) }}"
                            class="form-control @error('nomor') is-invalid @enderror" id="nomor" name="nomor"
                            placeholder="Nomor Rekening">
                        @error('nomor')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="saldo">Saldo Awal</label>
                        <input type="text" value="{{ old('saldo', $bank->saldo) }}"
                            class="form-control @error('saldo') is-invalid @enderror" id="saldo" name="saldo"
                            placeholder="Saldo Awal">
                        @error('saldo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">
                        <span class="fal fa-pencil mr-1"></span>
                        Tambah
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
