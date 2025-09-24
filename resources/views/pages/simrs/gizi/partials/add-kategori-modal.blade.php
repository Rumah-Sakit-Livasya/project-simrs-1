<div class="modal fade" id="addKategoriModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('gizi.kategori.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kategori Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label" for="add-nama">Nama Kategori</label>
                        <input type="text" id="add-nama" name="nama" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="add-coa-pendapatan">COA Pendapatan</label>
                        <input type="text" id="add-coa-pendapatan" name="coa_pendapatan" class="form-control"
                            required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="add-coa-biaya">COA Biaya</label>
                        <input type="text" id="add-coa-biaya" name="coa_biaya" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="add-aktif-true" name="aktif"
                                    value="1" checked>
                                <label class="custom-control-label" for="add-aktif-true">Aktif</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="add-aktif-false" name="aktif"
                                    value="0">
                                <label class="custom-control-label" for="add-aktif-false">Non Aktif</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
