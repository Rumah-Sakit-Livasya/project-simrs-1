<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('warehouse.master-data.pabrik.store') }}" method="post">
                @csrf
                @method('post')
                <div class="modal-header">

                    <h1 class="modal-title fs-5" id="addModalLabel">Tambah Pabrik</h1>
                </div>
                <div class="modal-body">

                    <table style="width: 100%">
                        <tr>
                            <td>Nama Pabrik</td>
                            <td>:</td>
                            <td>
                                <input type="text" value="{{ request('nama') }}"
                                    style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                    class="form-control" id="nama" name="nama" required>
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </td>
                        </tr>

                        <tr>
                            <td>Alamat</td>
                            <td>:</td>
                            <td>
                                <input type="text" value="{{ request('alamat') }}"
                                    style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                    class="form-control" id="alamat" name="alamat">
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </td>
                        </tr>

                        <tr>
                            <td>Telp</td>
                            <td>:</td>
                            <td>
                                <input type="text" value="{{ request('telp') }}"
                                    style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                    class="form-control" id="telp" name="telp">
                                @error('telp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </td>
                        </tr>

                        <tr>
                            <td>Contact Person</td>
                            <td>:</td>
                            <td>
                                <input type="text" value="{{ request('contact_person') }}"
                                    style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                    class="form-control" id="contact_person" name="contact_person">
                                @error('contact_person')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </td>
                        </tr>

                        <tr>
                            <td>Contact Person Phone</td>
                            <td>:</td>
                            <td>
                                <input type="text" value="{{ request('contact_person_phone') }}"
                                    style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                    class="form-control" id="contact_person_phone" name="contact_person_phone">
                                @error('contact_person_phone')
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
