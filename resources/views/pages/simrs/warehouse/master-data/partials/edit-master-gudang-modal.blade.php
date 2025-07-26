<div class="modal fade" id="editModal{{ $master_gudang->id }}" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('warehouse.master-data.master-gudang.update', ['id' => $master_gudang->id ]) }}" method="post">
                @csrf
                @method('put')
                <input type="hidden" name="id" value="{{ $master_gudang->id }}">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editModalLabel">Edit Master Gudang</h1>
                </div>
                <div class="modal-body">

                    <table style="width: 100%">
                        <tr>
                            <td>Nama Gudang</td>
                            <td>:</td>
                            <td>
                                <input type="text" value="{{ $master_gudang->nama }}"
                                    style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                    class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama">
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </td>
                        </tr>
                        <tr>
                            <td>Cost Center</td>
                            <td>:</td>
                            <td>
                                <input type="text" value="{{ $master_gudang->cost_center }}"
                                    style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                    class="form-control @error('cost_center') is-invalid @enderror" id="cost_center" name="cost_center">
                                @error('cost_center')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </td>
                        </tr>
                        <tr>
                            <td>Apotek</td>
                            <td>:</td>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="apotek"
                                        id="apotek" value="1" {{ $master_gudang->apotek ? 'checked' : '' }}>
                                    <label class="form-check-label" for="apotek">
                                        Apotek
                                    </label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Warehouse</td>
                            <td>:</td>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="warehouse"
                                        id="warehouse" value="1" {{ $master_gudang->warehouse ? 'checked' : '' }}>
                                    <label class="form-check-label" for="warehouse">
                                        Warehouse
                                    </label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Default Apotek</td>
                            <td>:</td>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="apotek_default"
                                        id="apotek_default" value="1" {{ $master_gudang->apotek_default ? 'checked' : '' }}>
                                    <label class="form-check-label" for="apotek_default">
                                        Dilihat dokter + apoteker (hanya bisa satu)
                                    </label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Status Aktif</td>
                            <td>:</td>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="aktif"
                                        id="status_aktif_true" value="1" {{ $master_gudang->aktif == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status_aktif_true">
                                        Aktif
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="aktif"
                                        id="status_aktif_false" value="0" {{ $master_gudang->aktif == 0 ? 'checked' : '' }}>
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
