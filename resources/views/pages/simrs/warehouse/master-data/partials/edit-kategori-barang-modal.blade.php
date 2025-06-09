<div class="modal fade edit-modal" id="editModal{{ $kategori->id }}" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('warehouse.master-data.kategori-barang.update', ['id' => $kategori->id]) }}"
                method="post">
                @csrf
                @method('put')
                <input type="hidden" name="id" value="{{ $kategori->id }}">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editModalLabel">Edit Kategori Barang</h1>
                </div>
                <div class="modal-body">
                    <table style="width: 100%">
                        <tr>
                            <td>Nama Kategori</td>
                            <td>:</td>
                            <td>
                                <input type="text" value="{{ $kategori->nama }}"
                                    style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                    class="form-control" id="nama" name="nama">
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </td>
                        </tr>
                        <tr>
                            <td>COA Inventory</td>
                            <td>:</td>
                            <td>
                                <select class="edit-modal-select"name="coa_inventory">
                                    <option value="" hidden disabled selected>Pilih COA</option>
                                    @foreach ($coas as $item)
                                        <option {{ $item->id == $kategori->coa_inventory ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>COA Sales Outpatient</td>
                            <td>:</td>
                            <td>
                                <select class="edit-modal-select"name="coa_sales_outpatient">
                                    <option value="" hidden disabled selected>Pilih COA</option>
                                    @foreach ($coas as $item)
                                        <option {{ $item->id == $kategori->coa_sales_outpatient ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>COA COGS Outpatient</td>
                            <td>:</td>
                            <td>
                                <select class="edit-modal-select"name="coa_cogs_outpatient">
                                    <option value="" hidden disabled selected>Pilih COA</option>
                                    @foreach ($coas as $item)
                                        <option {{ $item->id == $kategori->coa_cogs_outpatient ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>COA Sales Inpatient</td>
                            <td>:</td>
                            <td>
                                <select class="edit-modal-select"name="coa_sales_inpatient">
                                    <option value="" hidden disabled selected>Pilih COA</option>
                                    @foreach ($coas as $item)
                                        <option {{ $item->id == $kategori->coa_sales_inpatient ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>COA COGS Inpatient</td>
                            <td>:</td>
                            <td>
                                <select class="edit-modal-select"name="coa_cogs_inpatient">
                                    <option value="" hidden disabled selected>Pilih COA</option>
                                    @foreach ($coas as $item)
                                        <option {{ $item->id == $kategori->coa_cogs_inpatient ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>COA Adjustment Daily</td>
                            <td>:</td>
                            <td>
                                <select class="edit-modal-select"name="coa_adjustment_daily">
                                    <option value="" hidden disabled selected>Pilih COA</option>
                                    @foreach ($coas as $item)
                                        <option {{ $item->id == $kategori->coa_adjustment_daily ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>COA Adjustment SO</td>
                            <td>:</td>
                            <td>
                                <select class="edit-modal-select"name="coa_adjustment_so">
                                    <option value="" hidden disabled selected>Pilih COA</option>
                                    @foreach ($coas as $item)
                                        <option {{ $item->id == $kategori->coa_adjustment_so ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Konsinyasi</td>
                            <td>:</td>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="konsinsyasi"
                                        id="konsinsyasi_true" value="1"
                                        {{ $kategori->konsinsyasi == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="konsinsyasi_true">
                                        Ya
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="konsinsyasi"
                                        id="konsinsyasi_false" value="0"
                                        {{ $kategori->konsinsyasi == 0 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="konsinsyasi_false">
                                        Tidak
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
                                        value="1" {{ $kategori->aktif == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status_aktif_true">
                                        Aktif
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="aktif"
                                        id="status_aktif_false" value="0"
                                        {{ $kategori->aktif == 0 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status_aktif_false">
                                        Non Aktif
                                    </label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Kode</td>
                            <td>:</td>
                            <td>
                                <input type="text" value="{{ $kategori->kode }}"
                                    style="border: 0; border-bottom: 1.9px solid #eaeaea; margin-top: -.5rem; border-radius: 0"
                                    class="form-control" id="kode" name="kode">
                                @error('kode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
