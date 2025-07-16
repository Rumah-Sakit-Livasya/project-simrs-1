<div class="modal fade edit-bank-modal" id="edit-bank-{{ $bank->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form autocomplete="off" novalidate class="edit-bank-form" action="{{ route('bank.update', $bank->id) }}"
                method="post">
                @method('put')
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Edit Bank - {{ $bank->nama }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name-{{ $bank->id }}">Nama Bank</label>
                                <input type="text" value="{{ old('name', $bank->nama) }}"
                                    class="form-control @error('name') is-invalid @enderror"
                                    id="name-{{ $bank->id }}" name="name" placeholder="Nama Bank">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="pemilik-{{ $bank->id }}">Pemilik Rekening</label>
                                <input type="text" value="{{ old('pemilik', $bank->pemilik) }}"
                                    class="form-control @error('pemilik') is-invalid @enderror"
                                    id="pemilik-{{ $bank->id }}" name="pemilik" placeholder="Pemilik Rekening">
                                @error('pemilik')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="nomor-{{ $bank->id }}">Nomor Rekening</label>
                                <input type="text" value="{{ old('nomor', $bank->nomor) }}"
                                    class="form-control @error('nomor') is-invalid @enderror"
                                    id="nomor-{{ $bank->id }}" name="nomor" placeholder="Nomor Rekening">
                                @error('nomor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="is_aktivasi-{{ $bank->id }}">Aktivasi?</label>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                        id="is_aktivasi-{{ $bank->id }}" name="is_aktivasi" value="1"
                                        {{ old('is_aktivasi', $bank->is_aktivasi) ? 'checked' : '' }}>
                                    <label class="custom-control-label"
                                        for="is_aktivasi-{{ $bank->id }}">Ya</label>
                                </div>
                                @error('is_aktivasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="saldo-{{ $bank->id }}">Saldo Awal</label>
                                <input type="text" value="{{ old('saldo', $bank->saldo) }}"
                                    class="form-control @error('saldo') is-invalid @enderror"
                                    id="saldo-{{ $bank->id }}" name="saldo" placeholder="Saldo Awal">
                                @error('saldo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="akun_kas_bank-{{ $bank->id }}">Akun Kas/Bank</label>
                                <select class="form-control select2" id="akun_kas_bank-{{ $bank->id }}"
                                    name="akun_kas_bank">
                                    <option value="" disabled>Pilih Akun Kas/Bank</option>
                                    @foreach ($chartOfAccounts as $coa)
                                        <option value="{{ $coa->id }}"
                                            {{ old('akun_kas_bank', $bank->akun_kas_bank) == $coa->id ? 'selected' : '' }}>
                                            {{ $coa->code }} - {{ $coa->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('akun_kas_bank')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="akun_kliring-{{ $bank->id }}">Akun Kliring</label>
                                <select class="form-control select2" id="akun_kliring-{{ $bank->id }}"
                                    name="akun_kliring">
                                    <option value="" disabled>Pilih Akun Kliring</option>
                                    @foreach ($chartOfAccounts as $coa)
                                        <option value="{{ $coa->id }}"
                                            {{ old('akun_kliring', $bank->akun_kliring) == $coa->id ? 'selected' : '' }}>
                                            {{ $coa->code }} - {{ $coa->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('akun_kliring')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="is_bank-{{ $bank->id }}">Bank?</label>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input"
                                        id="is_bank-{{ $bank->id }}" name="is_bank" value="1"
                                        {{ old('is_bank', $bank->is_bank) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_bank-{{ $bank->id }}">Ya</label>
                                </div>
                                @error('is_bank')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">
                        <span class="fal fa-save mr-1"></span>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
