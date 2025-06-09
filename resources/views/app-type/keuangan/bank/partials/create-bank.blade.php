<div class="modal fade" id="tambah-bank" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form autocomplete="off" novalidate action="{{ route('bank.store') }}" method="post">
                @method('post')
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Bank</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="nama">Nama Bank</label>
                                <input type="text" value="{{ old('nama') }}"
                                    class="form-control @error('nama') is-invalid @enderror" id="nama"
                                    name="nama" placeholder="Nama Bank">
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="pemilik">Pemilik Rekening</label>
                                <input type="text" value="{{ old('pemilik') }}"
                                    class="form-control @error('pemilik') is-invalid @enderror" id="pemilik"
                                    name="pemilik" placeholder="Pemilik Rekening">
                                @error('pemilik')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="nomor">Nomor Rekening</label>
                                <input type="text" value="{{ old('nomor') }}"
                                    class="form-control @error('nomor') is-invalid @enderror" id="nomor"
                                    name="nomor" placeholder="Nomor Rekening">
                                @error('nomor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="is_aktivasi">Aktivasi?</label>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="is_aktivasi"
                                        name="is_aktivasi" value="1" {{ old('is_aktivasi') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_aktivasi">Ya</label>
                                </div>
                                @error('is_aktivasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="saldo">Saldo Awal</label>
                                <input type="text" value="{{ old('saldo') }}"
                                    class="form-control @error('saldo') is-invalid @enderror" id="saldo"
                                    name="saldo" placeholder="Saldo Awal">
                                @error('saldo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="akun_kas_bank">Akun Kas/Bank</label>
                                <select class="form-control select2" id="akun_kas_bank" name="akun_kas_bank">
                                    <option value="" disabled selected>Pilih Akun Kas/Bank</option>
                                    @foreach ($chartOfAccounts as $coa)
                                        <option value="{{ $coa->id }}">{{ $coa->code }} - {{ $coa->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('akun_kas_bank')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="akun_kliring">Akun Kliring</label>
                                <select class="form-control select2" id="akun_kliring" name="akun_kliring">
                                    <option value="" disabled selected>Pilih Akun Kliring</option>
                                    @foreach ($chartOfAccounts as $coa)
                                        <option value="{{ $coa->id }}">{{ $coa->code }} - {{ $coa->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('akun_kliring')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="is_bank">Bank?</label>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="is_bank" name="is_bank"
                                        value="1" {{ old('is_bank') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_bank">Ya</label>
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
                        <span class="fal fa-plus-circle mr-1"></span>
                        Tambah
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
