<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('warehouse.master-data.master-gudang.store') }}" method="post">
                @csrf
                @method('post')
                <div class="modal-header">

                    <h1 class="modal-title fs-5" id="addModalLabel">Tambah Master Gudang</h1>
                </div>
                <div class="modal-body">

                    <table style="width: 100%">
                        <tr>
                            <td>Nama Gudang</td>
                            <td>:</td>
                            <td>
                                <input type="text" value="{{ old('nama') }}"
                                    style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                    class="form-control @error('nama') is-invalid @enderror" id="nama"
                                    name="nama">
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </td>
                        </tr>
                        <tr>
                            <td>Cost Center</td>
                            <td>:</td>
                            <td>
                                <input type="text" value="{{ old('cost_center') }}"
                                    style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                    class="form-control @error('cost_center') is-invalid @enderror" id="cost_center"
                                    name="cost_center">
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
                                    <input class="form-check-input" type="checkbox" name="apotek" id="apotek"
                                        value="1" {{ old('apotek') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="apotek">
                                        Gudang adalah Apotek
                                    </label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Warehouse</td>
                            <td>:</td>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="warehouse" id="warehouse"
                                        value="1" {{ old('warehouse') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="warehouse">
                                        Gudang adalah penyimpanan
                                    </label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Default Apotek Rajal</td>
                            <td>:</td>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="rajal_default"
                                        id="rajal_default" value="1" {{ old('rajal_default') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="rajal_default">
                                        Dilihat dokter + apoteker (hanya bisa satu)
                                    </label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Default Apotek Ranap</td>
                            <td>:</td>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="ranap_default"
                                        id="ranap_default" value="1" {{ old('ranap_default') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="ranap_default">
                                        Dilihat ketika pasien ranap dipilih (hanya bisa satu)
                                    </label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Status Aktif</td>
                            <td>:</td>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="aktif" id="status_aktif_true"
                                        value="1" checked>
                                    <label class="form-check-label" for="status_aktif_true">
                                        Aktif
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="aktif"
                                        id="status_aktif_false" value="0">
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
