<div class="modal fade" id="editModal{{ $food->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('gizi.makanan.update', $food->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Makanan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label" for="edit-nama-{{ $food->id }}">Nama Makanan</label>
                        <input type="text" id="edit-nama-{{ $food->id }}" name="nama" class="form-control"
                            value="{{ $food->nama }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="edit-harga-{{ $food->id }}">Harga</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="number" id="edit-harga-{{ $food->id }}" name="harga"
                                class="form-control" value="{{ $food->harga }}" required min="0">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <div class="frame-wrap">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input"
                                    id="edit-aktif-true-{{ $food->id }}" name="aktif" value="1"
                                    {{ $food->aktif ? 'checked' : '' }}>
                                <label class="custom-control-label"
                                    for="edit-aktif-true-{{ $food->id }}">Aktif</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input"
                                    id="edit-aktif-false-{{ $food->id }}" name="aktif" value="0"
                                    {{ !$food->aktif ? 'checked' : '' }}>
                                <label class="custom-control-label" for="edit-aktif-false-{{ $food->id }}">Non
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
