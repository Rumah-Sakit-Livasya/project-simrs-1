<div class="modal fade edit-modal" id="editModal{{ $jam_makan->id }}" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('jam-makan.gizi.update', ["id" => $jam_makan->id]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addModalLabel">Edit Waktu Jam Makan</h1>
                </div>
                <div class="modal-body">

                    <table style="width: 100%">
                        <tr>
                            <td>Nama Waktu Makan</td>
                            <td>:</td>
                            <td>
                                <input type="text" value="{{ $jam_makan->waktu_makan }}"
                                    style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                    class="form-control" id="waktu_makan" name="waktu_makan">
                                @error('waktu_makan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </td>
                        </tr>

                        <tr>
                            <td>Jam</td>
                            <td>:</td>
                            <td>
                                <input type="time" value="{{ \Carbon\Carbon::parse($jam_makan->jam)->format('H:i') }}"
                                    style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                    class="form-control" id="jam" name="jam">
                                @error('jam')
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
                                        value="1" {{ $jam_makan->aktif ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status_aktif_true">
                                        Aktif
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="aktif"
                                        id="status_aktif_false" value="0" {{ !$jam_makan->aktif ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status_aktif_false">
                                        Non Aktif
                                    </label>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td>Auto Order?</td>
                            <td>:</td>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="auto_order" id="auto_order_true"
                                        value="1" checked  {{ $jam_makan->auto_order ? 'checked' : '' }}>
                                    <label class="form-check-label" for="auto_order_true">
                                        Aktif
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="auto_order"
                                        id="auto_order_false" value="0"  {{ !$jam_makan->auto_order ? 'checked' : '' }}>
                                    <label class="form-check-label" for="auto_order_false">
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
