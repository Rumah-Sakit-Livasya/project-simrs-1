<div class="modal fade" id="authModal" tabindex="-1" aria-labelledby="authModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="authModalLabel">Otorisasi Pengguna</h1>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="authForm">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <select name="email" class="form-control" id="email" required>
                                    <option value="" selected disabled hidden>Pilih User</option>
                                    @foreach ($auth_users as $auth_user)
                                        <option value="{{ $auth_user->user->email }}">
                                            {{ $auth_user->user->employee->fullname }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" id="authBtn" class="btn btn-primary">Otorisasi</button>
            </div>
        </div>
    </div>
</div>
