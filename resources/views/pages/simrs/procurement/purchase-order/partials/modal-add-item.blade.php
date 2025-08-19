<div class="modal fade" id="pilihItemModal" tabindex="-1" aria-labelledby="pilihItemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="addModalLabel">Pilih barang</h1>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6">
                        <div class="row">
                            <div class="col-2">
                                <label for="sumber_item">Sumber Item</label>
                            </div>
                            <div class="col">
                                <select name="sumber_item" id="sumber-item-select" class="form-control">
                                    <option value="pr">PR</option>
                                    <option value="npr">No PR</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="row">
                            <div class="col-2">
                                <label for="tipe_pr">Tipe PR</label>
                            </div>
                            <div class="col">
                                <select name="tipe_pr" id="tipe-pr-select" class="form-control">
                                    <option value="">All</option>
                                    <option value="normal">Normal</option>
                                    <option value="urgent">Urgent</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <input type="text" id="searchItemInput" placeholder="Cari barang..." class="form-control">
                <br>
                <table class="table table-bordered table-hover table-striped w-100">
                    <thead class="bg-primary-600">
                        <tr>
                            <th>Kode PR</th>
                            <th>Nama Barang</th>
                            <th>Satuan</th>
                            <th>Stok All</th>
                            <th>Qty APP</th>
                            <th>Telah di Order</th>
                            <th>Belum di Order</th>
                            <th>Qty Order</th>
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
