<div class="modal fade" id="edit-bank" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form autocomplete="off" novalidate id="edit-bank-form" method="post">
                @method('put')
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Edit Bank</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="edit-name">Nama Bank</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="edit-name" name="name" placeholder="Nama Bank">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="edit-pemilik">Pemilik Rekening</label>
                                <input type="text" class="form-control @error('pemilik') is-invalid @enderror"
                                    id="edit-pemilik" name="pemilik" placeholder="Pemilik Rekening">
                                @error('pemilik')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="edit-nomor">Nomor Rekening</label>
                                <input type="text" class="form-control @error('nomor') is-invalid @enderror"
                                    id="edit-nomor" name="nomor" placeholder="Nomor Rekening">
                                @error('nomor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="edit-is_aktivasi">Aktivasi?</label>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="edit-is_aktivasi"
                                        name="is_aktivasi" value="1">
                                    <label class="custom-control-label" for="edit-is_aktivasi">Ya</label>
                                </div>
                                @error('is_aktivasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="edit-saldo">Saldo Awal</label>
                                <input type="text" class="form-control @error('saldo') is-invalid @enderror"
                                    id="edit-saldo" name="saldo" placeholder="Saldo Awal">
                                @error('saldo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="edit-akun_kas_bank">Akun Kas/Bank</label>
                                <select class="form-control select2" id="edit-akun_kas_bank" name="akun_kas_bank">
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
                                <label for="edit-akun_kliring">Akun Kliring</label>
                                <select class="form-control select2" id="edit-akun_kliring" name="akun_kliring">
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
                                <label for="edit-is_bank">Bank?</label>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="edit-is_bank" name="is_bank"
                                        value="1">
                                    <label class="custom-control-label" for="edit-is_bank">Ya</label>
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
