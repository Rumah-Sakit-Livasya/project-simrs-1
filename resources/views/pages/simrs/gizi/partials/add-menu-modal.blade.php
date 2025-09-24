<div class="modal fade" id="addMenuModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('gizi.menu.store') }}" method="POST" class="form-menu">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Menu Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Nama Menu</label>
                                <input type="text" name="nama" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Kategori</label>
                                <select name="kategori_id" class="form-control select2" required>
                                    <option value="" disabled selected>Pilih Kategori</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
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
                    <hr>
                    <div class="form-group">
                        <label class="form-label">Cari & Tambah Makanan</label>
                        <select class="form-control select2-food-search"
                            data-target-table="#addMenuModal .food-table-body">
                            <option></option>
                            @foreach ($foods as $food)
                                <option value="{{ $food->id }}">{{ $food->nama }} - (Rp
                                    {{ number_format($food->harga) }})</option>
                            @endforeach
                        </select>
                    </div>

                    <table class="table table-bordered table-sm mt-3">
                        <thead class="bg-primary-200">
                            <tr>
                                <th>Nama Makanan</th>
                                <th class="text-right">Harga</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="food-table-body">
                            {{-- Baris makanan akan ditambahkan di sini oleh JS --}}
                        </tbody>
                        <tfoot>
                            <tr>
                                <td class="text-right font-weight-bold">Total Harga</td>
                                <td class="text-right font-weight-bold total-harga-display">Rp 0</td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    </table>
                    <input type="hidden" name="harga" class="total-harga-input" value="0">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan Menu</button>
                </div>
            </form>
        </div>
    </div>
</div>
