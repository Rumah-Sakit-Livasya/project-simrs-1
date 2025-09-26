<div class="modal fade" id="editModal{{ $kategori->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('gizi.kategori.update', $kategori->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Kategori: {{ $kategori->nama }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Nama Kategori</label>
                        <input type="text" name="nama" class="form-control" value="{{ $kategori->nama }}"
                            required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">COA Pendapatan</label>
                        <input type="text" name="coa_pendapatan" class="form-control"
                            value="{{ $kategori->coa_pendapatan }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">COA Biaya</label>
                        <input type="text" name="coa_biaya" class="form-control" value="{{ $kategori->coa_biaya }}"
                            required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input"
                                    id="edit-aktif-true-{{ $kategori->id }}" name="aktif" value="1"
                                    {{ $kategori->aktif ? 'checked' : '' }}>
                                <label class="custom-control-label"
                                    for="edit-aktif-true-{{ $kategori->id }}">Aktif</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input"
                                    id="edit-aktif-false-{{ $kategori->id }}" name="aktif" value="0"
                                    {{ !$kategori->aktif ? 'checked' : '' }}>
                                <label class="custom-control-label" for="edit-aktif-false-{{ $kategori->id }}">Non
                                    Aktif</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
