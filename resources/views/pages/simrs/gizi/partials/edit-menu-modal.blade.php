<div class="modal fade edit-modal" id="editModal{{ $menu->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('gizi.menu.update', $menu->id) }}" method="POST" class="form-menu">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Menu: {{ $menu->nama }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- Form fields are identical to add modal, but with values --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Nama Menu</label>
                                <input type="text" name="nama" class="form-control" value="{{ $menu->nama }}"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Kategori</label>
                                <select name="kategori_id" class="form-control select2" required>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ $menu->kategori_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input"
                                    id="edit-aktif-true-{{ $menu->id }}" name="aktif" value="1"
                                    {{ $menu->aktif ? 'checked' : '' }}>
                                <label class="custom-control-label"
                                    for="edit-aktif-true-{{ $menu->id }}">Aktif</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input"
                                    id="edit-aktif-false-{{ $menu->id }}" name="aktif" value="0"
                                    {{ !$menu->aktif ? 'checked' : '' }}>
                                <label class="custom-control-label" for="edit-aktif-false-{{ $menu->id }}">Non
                                    Aktif</label>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label class="form-label">Cari & Tambah Makanan</label>
                        <select class="form-control select2-food-search"
                            data-target-table="#editModal{{ $menu->id }} .food-table-body">
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
                            {{-- Populate with existing foods --}}
                            @foreach ($menu->makanan_menu as $makananMenu)
                                <tr data-id="{{ $makananMenu->makanan_id }}">
                                    <td>
                                        {{ $makananMenu->makanan->nama }}
                                        <input type="hidden" name="foods[{{ $makananMenu->makanan_id }}][id]"
                                            value="{{ $makananMenu->makanan_id }}">
                                    </td>
                                    <td class="text-right harga">{{ number_format($makananMenu->makanan->harga, 0) }}
                                    </td>
                                    <td class="text-center">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input"
                                                id="food-status-{{ $menu->id }}-{{ $makananMenu->makanan_id }}"
                                                name="foods[{{ $makananMenu->makanan_id }}][status]" value="1"
                                                {{ $makananMenu->aktif ? 'checked' : '' }}>
                                            <label class="custom-control-label"
                                                for="food-status-{{ $menu->id }}-{{ $makananMenu->makanan_id }}"></label>
                                            <input type="hidden" name="foods[{{ $makananMenu->makanan_id }}][status]"
                                                value="0">
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-xs btn-danger remove-food-btn"><i
                                                class="fal fa-times"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td class="text-right font-weight-bold">Total Harga</td>
                                <td class="text-right font-weight-bold total-harga-display">Rp 0</td>
                                {{-- Will be calculated by JS --}}
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    </table>
                    <input type="hidden" name="harga" class="total-harga-input" value="{{ $menu->harga }}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
