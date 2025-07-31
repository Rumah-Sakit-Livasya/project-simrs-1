<div class="modal fade" id="editModal{{ $zat->id }}" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('warehouse.master-data.zat-aktif.update', ['id' => $zat->id]) }}" method="post">
                @csrf
                @method('put')
                <input type="hidden" name="id" value="{{ $zat->id }}">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editModalLabel">Edit Zat [{{ $zat->nama }}]</h1>
                </div>
                <div class="modal-body">

                    <table style="width: 100%">
                        <tr>
                            <td>Kode Zat</td>
                            <td>:</td>
                            <td>
                                <input type="text" value="{{ $zat->kode }}"
                                    style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                    class="form-control" id="kode" name="kode">
                                @error('kode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </td>
                        </tr>

                        <tr>
                            <td>Nama Zat</td>
                            <td>:</td>
                            <td>
                                <input type="text" value="{{ $zat->nama }}"
                                    style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                    class="form-control" id="nama" name="nama">
                                @error('nama')
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
                                        value="1" {{ $zat->aktif ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status_aktif_true">
                                        Aktif
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="aktif"
                                        id="status_aktif_false" value="0" {{ !$zat->aktif ? 'checked' : '' }}>
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
                        <span class="fal fa-plus mr-1"></span>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
