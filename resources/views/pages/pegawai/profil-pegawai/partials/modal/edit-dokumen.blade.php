<!-- Modal Edit Dokumen -->
<div class="modal fade" id="edit-dokumen-modal" tabindex="-1" role="dialog" aria-labelledby="modalEditDokumenLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form id="edit-dokumen-form" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="edit-dokumen-id" name="id">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="modalEditDokumenLabel">Ubah Dokumen Kepegawaian</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit-nama">Nama Dokumen</label>
                        <input type="text" name="nama" id="edit-nama" class="form-control"
                            placeholder="Masukkan nama dokumen" required>
                    </div>

                    <div class="form-group">
                        <label for="edit-expire">Tanggal Expire <small class="text-muted">(opsional)</small></label>
                        <input type="date" name="expire" id="edit-expire" class="form-control">
                        <small class="form-text text-muted">Isi jika dokumen memiliki masa berlaku, biarkan kosong jika
                            tidak.</small>
                    </div>

                    <div class="form-group">
                        <label for="edit-file">File Dokumen</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="file" id="edit-file"
                                accept=".pdf,.jpg,.jpeg,.png">
                            <label class="custom-file-label" for="edit-file">Pilih file baru...</label>
                        </div>
                        <small class="form-text text-muted">Format: PDF, JPG, PNG. Kosongkan jika tidak ingin mengubah
                            file.</small>
                        <div id="file-sekarang" class="mt-2"></div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
