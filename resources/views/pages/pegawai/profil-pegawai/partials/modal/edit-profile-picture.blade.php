<div class="modal fade" id="changeProfileModal" tabindex="-1" aria-labelledby="changeProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changeProfileModalLabel">Ubah Foto Profil</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form autocomplete="off" action="#" novalidate method="post" id="update-profile-picture"
                enctype="multipart/form-data">
                <div class="modal-body">
                    <!-- Isi modal disini, misalnya formulir untuk mengunggah gambar baru -->
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="employee_id" id="employee-id" value="">
                    <div class="form-group mb-0">
                        <label class="form-label d-block">Foto</label>
                        <div class="img-preview-container">
                            <img class="img-preview img-fluid mb-3 col-sm-5 p-0" style="border-radius: 11px">
                        </div>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="foto" name="foto"
                                onchange="previewImage()">
                            <label class="custom-file-label" for="foto">Unggah Foto</label>
                        </div>
                    </div>
                    <!-- Anda juga bisa menambahkan pratinjau gambar di sini -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>

        </div>
    </div>
</div>
