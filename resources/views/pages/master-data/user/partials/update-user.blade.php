{{-- resources/views/pages/master-data/user/partials/update-user.blade.php --}}
<div class="modal fade" id="ubah-user" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form autocomplete="off" novalidate id="update-form">
                <div class="modal-header">
                    <h5 class="modal-title">Ubah User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- Hidden input untuk menyimpan ID user --}}
                    <input type="hidden" id="update-user-id" name="id">
                    <div class="form-group">
                        <label for="update-name">Nama</label>
                        <input type="text" class="form-control" id="update-name" name="name"
                            placeholder="Nama User" required>
                    </div>
                    <div class="form-group">
                        <label for="update-email">Email</label>
                        <input type="email" class="form-control" id="update-email" name="email" placeholder="Email"
                            required>
                    </div>
                    {{-- PENTING: Tambahkan opsi ganti password --}}
                    <hr>
                    <div class="form-group">
                        <label for="update-password">Ganti Password (Opsional)</label>
                        <input type="password" class="form-control" id="update-password" name="password"
                            placeholder="Isi jika ingin mengubah password">
                        <small class="form-text text-muted">Kosongkan kolom ini jika tidak ingin mengubah
                            password.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <span class="fal fa-pencil mr-1"></span>
                        Ubah
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
