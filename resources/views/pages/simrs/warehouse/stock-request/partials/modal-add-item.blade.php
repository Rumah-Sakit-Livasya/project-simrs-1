<div class="modal fade" id="pilihItemModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pilih Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i
                            class="fal fa-times"></i></span></button>
            </div>
            <div class="modal-body">
                <div class="form-row">
                    <div class="col-md-8"><input type="text" id="searchItemInput" placeholder="Cari barang..."
                            class="form-control"></div>
                    <div class="col-md-4"><select class="form-control" id="itemSourceSelect">
                            <option value="stock" selected>Dari Stok Gudang</option>
                            <option value="barang">Dari Master Barang</option>
                        </select></div>
                </div>
                <div class="table-responsive mt-3" style="max-height: 400px;">
                    <table class="table table-bordered table-hover table-striped w-100">
                        <thead class="bg-primary-600">
                            <tr>
                                <th>Kode</th>
                                <th>Nama Barang</th>
                                <th>Satuan</th>
                                <th>Stok Asal</th>
                                <th>Stok Tujuan</th>
                                <th>Min</th>
                                <th>Max</th>
                                <th style="width:10%">Qty</th>
                                <th style="width:5%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="itemTable">
                            {{-- Konten dimuat oleh AJAX di sini --}}
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
