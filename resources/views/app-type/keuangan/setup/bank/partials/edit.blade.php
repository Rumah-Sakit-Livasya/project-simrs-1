<div class="modal fade" id="edit-bank-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Bank</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {{-- Form action akan diisi oleh JavaScript --}}
            <form id="edit-bank-form" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    {{-- Konten form sama dengan modal 'create', tapi dengan id unik --}}
                    <div class="form-group">
                        <label for="nama">Nama Bank</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    {{-- ... tambahkan semua field lain seperti di modal create ... --}}
                    {{-- Contoh: --}}
                    <div class="form-group">
                        <label for="pemilik">Pemilik</label>
                        <input type="text" class="form-control" id="pemilik" name="pemilik" required>
                    </div>
                    <div class="form-group">
                        <label for="nomor">Nomor Rekening</label>
                        <input type="text" class="form-control" id="nomor" name="nomor" required>
                    </div>
                    <div class="form-group">
                        <label for="saldo">Saldo</label>
                        <input type="number" step="0.01" class="form-control" id="saldo" name="saldo"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="akun_kas_bank">Akun Kas Bank</label>
                        <select class="form-control select2" id="akun_kas_bank" name="akun_kas_bank"
                            style="width: 100%;" required>
                            @foreach ($chartOfAccounts as $coa)
                                <option value="{{ $coa->id }}">{{ $coa->code }} - {{ $coa->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="akun_kliring">Akun Kliring</label>
                        <select class="form-control select2" id="akun_kliring" name="akun_kliring" style="width: 100%;"
                            required>
                            @foreach ($chartOfAccounts as $coa)
                                <option value="{{ $coa->id }}">{{ $coa->code }} - {{ $coa->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="is_aktivasi" name="is_aktivasi"
                            value="1">
                        <label class="form-check-label" for="is_aktivasi">Aktifkan</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="is_bank" name="is_bank" value="1">
                        <label class="form-check-label" for="is_bank">Apakah ini Bank?</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
