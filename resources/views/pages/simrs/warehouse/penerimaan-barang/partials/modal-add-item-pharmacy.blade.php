<div class="modal fade" id="pilihItemModal" tabindex="-1" aria-labelledby="pilihItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="addModalLabel">Pilih barang</h1>
            </div>
            <div class="modal-body">
                <input type="text" id="searchItemInput" placeholder="Cari barang..." class="form-control">
                <br>
                <table class="table table-bordered table-hover table-striped w-100">
                    <thead class="bg-primary-600">
                        <tr>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Satuan</th>
                            <th>Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="itemTable">
                        @foreach ($barangs as $barang)
                            <tr class="item">
                                <td>{{ $barang->kode }}</td>
                                <td>{{ $barang->nama }}</td>
                                <td>{{ $barang->satuan->nama }}</td>
                                <td>{{ rp($barang->hna) }}</td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-sm"
                                        onclick="PopupPBPharmacyClass.addItem({{ json_encode($barang) }})">
                                        Pilih
                                    </button>
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
