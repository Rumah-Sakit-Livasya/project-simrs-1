<div class="modal fade edit-modal" id="editModal{{ $menu->id }}" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('menu.gizi.update', ['id' => $menu->id]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addModalLabel">Edit Menu</h1>
                </div>
                <div class="modal-body">

                    <table style="width: 100%">
                        <tr>
                            <td>Nama Menu</td>
                            <td>:</td>
                            <td>
                                <input type="text" value="{{ $menu->nama }}"
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
                                        value="1" {{ $menu->aktif == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status_aktif_true">
                                        Aktif
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="aktif"
                                        id="status_aktif_false" value="0"
                                        {{ $menu->aktif == '0' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status_aktif_false">
                                        Non Aktif
                                    </label>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td>Cari Makanan</td>
                            <td>:</td>
                            <td>
                                <select class="select2 form-control w-100" id="search-food">
                                    <option value=""></option>
                                    @foreach ($foods as $food)
                                        <option value="{{ $food->id }}">
                                            [{{ rp($food->harga) }}] {{ $food->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="3">
                                <table class="table table-bordered table-hover table-striped w-100">
                                    <thead class="bg-primary-600">
                                        <tr>
                                            <th>Nama Makanan</th>
                                            <th>Harga</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table-food">
                                        @foreach ($menu->makanan_menu as $makanan)
                                            <tr id="food{{ $loop->index }}">
                                                <td>{{ $makanan->makanan->nama }}</td>
                                                <td>{{ rp($makanan->makanan->harga) }}</td>
                                                <td>
                                                    <input type="hidden" name="foods_id[{{ $loop->index }}]"
                                                        value="{{ $makanan->makanan->id }}">
                                                    <input type="checkbox" name="foods_status[{{ $loop->index }}]"
                                                        value="1" {{ $makanan->aktif ? 'checked' : '' }}
                                                        onchange="window['handler_#editModal{{ $menu->id }}'].updateFoodStatus(this.checked, {{ $makanan->makanan->harga }})">
                                                </td>
                                                <td>
                                                    <a class="mdi mdi-close pointer mdi-24px text-danger delete-btn"
                                                        title="Hapus"
                                                        onclick="window['handler_#editModal{{ $menu->id }}'].deleteFood({{ $loop->index }}, {{ $makanan->makanan->harga }})"></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-right">Total
                                                <input type="hidden" value="{{ $menu->harga }}" name="harga">
                                            </td>
                                            <td id="harga-display">{{ rp($menu->harga) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
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
<script>
    // on document ready
    // use vanilla js
    document.addEventListener('DOMContentLoaded', function() {
        window["handler_#editModal" + {{ $menu->id }}] = new ModalMenuGiziHandler("#editModal" + {{ $menu->id }});
    });
</script>
