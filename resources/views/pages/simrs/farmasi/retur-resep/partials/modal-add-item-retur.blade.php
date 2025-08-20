<div class="modal fade" id="pilihItemModal" tabindex="-1" aria-labelledby="pilihItemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="addModalLabel">Pilih obat</h1>
            </div>
            <div class="modal-body">
                <input type="text" id="searchItemInput" placeholder="Cari kode obat / nama obat / no batch..." class="form-control">
                <br>
                <input type="text" id="searchNoResepInput" placeholder="Cari No Resep..." class="form-control">
                <br>
                <table class="table table-bordered table-hover table-striped w-100">
                    <thead class="bg-primary-600">
                        <tr>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Satuan</th>
                            <th>Tanggal Exp.</th>
                            <th>No Batch</th>
                            <th>No Resep</th>
                            <th>Gudang</th>
                            <th>Telah Diretur</th>
                            <th>Qty</th>
                            <th>Harga</th>
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
