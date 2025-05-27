<div class="modal fade" id="pilihItemModal" tabindex="-1" aria-labelledby="pilihItemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
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
                            <th>Stok Gudang</th>
                            <th>Stok All</th>
                            <th>Stok Request</th>
                            <th>Min Stok</th>
                            <th>Max Stok</th>
                            <th>Qty</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="itemTable">
                        
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>
