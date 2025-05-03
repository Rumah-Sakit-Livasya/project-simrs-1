<div class="modal fade" id="pilihMenuModal" tabindex="-1" aria-labelledby="pilihMenuModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="addModalLabel">Pilih menu</h1>
            </div>
            <div class="modal-body">
                <input type="text" id="searchMenuInput" placeholder="Cari menu..." class="form-control">
                <br>
                <table class="table table-bordered table-hover table-striped w-100">
                    <thead class="bg-primary-600">
                        <tr>
                            <th>Nama Menu</th>
                            <th>Harga</th>
                            <th>Isi Menu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($menus as $menu)
                            @php
                                $foods = [];
                                foreach ($menu->makanan_menu as $makanan) {
                                    if ($makanan->aktif) $foods[] = $makanan->makanan;
                                }
                                $foods_json = json_encode($foods);
                            @endphp
                            <tr class="pointer" onclick="PopupOrderGiziClass.menuSelect({{ $foods_json }})" data-bs-dismiss="modal">
                                <td class="align-middle menu-name">{{ $menu->nama }}</td>
                                <td class="align-middle">{{ rp($menu->harga) }}</td>
                                <td class="align-middle">
                                    <table>
                                        <thead class="bg-info-600">
                                            <tr>
                                                <th>Nama Makanan</th>
                                                <th>Harga</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($foods as $makanan)
                                                    <tr>
                                                        <td class="align-middle">{{ $makanan->nama }}</td>
                                                        <td class="align-middle">{{ rp($makanan->harga) }}</td>
                                                    </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>
