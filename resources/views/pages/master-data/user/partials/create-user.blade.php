{{-- resources/views/pages/master-data/user/partials/create-user.blade.php --}}
<div class="modal fade" id="tambah-user" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form autocomplete="off" novalidate id="store-form">
                {{-- Tidak perlu @method dan @csrf karena dikirim via JS --}}
                <div class="modal-header">
                    <h5 class="modal-title">Tambah User Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- Peningkatan UX: Pilih Pegawai dulu --}}
                    <div class="form-group">
                        <label for="employee_id">Pilih Pegawai</label>
                        <select class="select2-create form-control w-100" id="employee_id" name="employee_id" required>
                            <option value=""></option>
                            @foreach ($employees as $employee)
                                {{-- Menambahkan data-email dan data-name untuk diisi otomatis oleh JS --}}
                                <option value="{{ $employee->id }}" data-email="{{ $employee->email }}"
                                    data-name="{{ $employee->fullname }}">
                                    {{ $employee->fullname }}
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Silakan pilih pegawai.</div>
                    </div>
                    {{-- Peningkatan UX: Terisi otomatis, tapi bisa diubah --}}
                    <div class="form-group">
                        <label for="create-name">Nama Panggilan / Username</label>
                        <input type="text" class="form-control" id="create-name" name="name"
                            placeholder="Nama panggilan untuk login" required>
                        <div class="invalid-feedback">Nama panggilan tidak boleh kosong.</div>
                    </div>
                    <div class="form-group">
                        <label for="create-email">Email</label>
                        <input type="email" class="form-control" id="create-email" name="email"
                            placeholder="Email untuk login" required>
                        <div class="invalid-feedback">Format email tidak valid.</div>
                    </div>
                    {{-- PENTING: Tambahkan input password --}}
                    <div class="form-group">
                        <label for="create-password">Password</label>
                        <input type="password" class="form-control" id="create-password" name="password"
                            placeholder="Minimal 8 karakter" required minlength="8">
                        <div class="invalid-feedback">Password minimal 8 karakter.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <span class="fal fa-plus-circle mr-1"></span>
                        Tambah
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
