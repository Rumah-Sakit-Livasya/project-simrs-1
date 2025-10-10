<div class="modal fade" id="pilihItemModal" tabindex="-1" aria-labelledby="pilihItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Pilih Barang untuk Diretur</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <input type="text" id="searchItemInput" placeholder="Cari Nama Barang..."
                            class="form-control">
                    </div>
                    <div class="col-md-4">
                        <input type="text" id="searchPBInput" placeholder="Cari Kode Penerimaan..."
                            class="form-control">
                    </div>
                    <div class="col-md-4">
                        <input type="text" id="searchNoFakturInput" placeholder="Cari No Faktur..."
                            class="form-control">
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped w-100">
                        <thead class="bg-primary-600">
                            <tr>
                                <th>#</th>
                                <th>Tgl Terima</th>
                                <th>Tgl Exp</th>
                                <th>Kode Terima</th>
                                <th>No Faktur</th>
                                <th>No Batch</th>
                                <th>Nama Barang</th>
                                <th>Satuan</th>
                                <th>Gudang</th>
                                <th class="text-center">Stok</th>
                                <th>Telah Diretur</th>
                                <th class="text-right">Harga</th>
                                {{-- TAMBAHKAN HEADER UNTUK KOLOM AKSI --}}
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="itemTable">
                            {{-- Konten akan dimuat oleh AJAX --}}
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
