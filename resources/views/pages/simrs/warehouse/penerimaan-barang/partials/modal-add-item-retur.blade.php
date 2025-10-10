<div class="modal fade" id="pilihItemModal" tabindex="-1" aria-labelledby="pilihItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="addModalLabel">Pilih barang</h1>
            </div>
            <div class="modal-body">
                <input type="text" id="searchItemInput" placeholder="Cari barang..." class="form-control">
                <br>
                <input type="text" id="searchPBInput" placeholder="Cari Kode PB..." class="form-control">
                <br>
                <input type="text" id="searchNoFakturInput" placeholder="Cari Nomor Faktur..." class="form-control">
                <br>
                <table class="table table-bordered table-hover table-striped w-100">
                    <thead class="bg-primary-600">
                        <tr>
                            <th>#</th>
                            <th>Tanggal Terima</th>
                            <th>Tanggal Expired</th>
                            <th>Kode PB</th>
                            <th>No Faktur</th>
                            <th>No Batch</th>
                            <th>Nama Barang</th>
                            <th>Satuan</th>
                            <th>Gudang</th>
                            <th>Qty</th>
                            <th>Telah Diretur</th>
                            <th>Harga</th>
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
