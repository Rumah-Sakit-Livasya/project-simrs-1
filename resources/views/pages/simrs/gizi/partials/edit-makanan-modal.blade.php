<div class="modal fade" id="editModal{{ $food->id }}" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('makanan.gizi.update', ['id' => $food->id]) }}" name="form-edit" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addModalLabel">Edit Makanan</h1>
                </div>
                <div class="modal-body">

                    <table style="width: 100%">
                        <tr>
                            <td>Nama Makanan</td>
                            <td>:</td>
                            <td>
                                <input type="text" value="{{ $food->nama }}"
                                    style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                    class="form-control" id="nama" name="nama">
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </td>
                        </tr>

                        <tr>
                            <td>Harga</td>
                            <td>:</td>
                            <td>
                                <input type="text" value="{{ $food->harga }}"
                                    style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                    class="form-control" id="harga" name="harga" onkeyup="formatAngka(this)">
                                @error('harga')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </td>
                        </tr>

                        <tr>
                            <td>Status Aktif</td>
                            <td>:</td>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="aktif" id="status_aktif_true"
                                        value="1" {{ $food->aktif ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status_aktif_true">
                                        Aktif
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="aktif"
                                        id="status_aktif_false" value="0" {{ !$food->aktif ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status_aktif_false">
                                        Non Aktif
                                    </label>
                                </div>
                            </td>
                        </tr>
                    </table>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <span class="fal fa-pencil mr-1"></span>
                        Edit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
