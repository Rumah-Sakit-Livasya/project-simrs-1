<!-- Modal Upload Dokumen -->
<div class="modal fade" id="tambah-dokumen-modal" tabindex="-1" role="dialog" aria-labelledby="modalUploadDokumenLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form id="tambah-dokumen-form" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalUploadDokumenLabel">Upload Dokumen Kepegawaian</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama">Nama Dokumen</label>
                        <input type="text" name="nama" id="nama" class="form-control"
                            placeholder="Masukkan nama dokumen" required>
                    </div>

                    <div class="form-group">
                        <label for="expire">Tanggal Expire <small class="text-muted">(opsional)</small></label>
                        <input type="date" name="expire" id="expire" class="form-control">
                        <small class="form-text text-muted">Isi jika dokumen memiliki masa berlaku, biarkan kosong jika
                            tidak.</small>
                    </div>

                    <div class="form-group">
                        <label for="file">File Dokumen</label>
                        <input type="file" name="file" id="file" class="form-control-file"
                            accept=".pdf,.jpg,.jpeg,.png" required>
                        <small class="form-text text-muted">Format yang diizinkan: PDF, JPG, PNG</small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- <!-- Script untuk memunculkan modal -->
<script>
    $(document).on('click', '#tambah-dokumen', function() {
        $('#form-upload-dokumen')[0].reset();
        $('#modal-upload-dokumen').modal('show');
    });
</script> --}}
