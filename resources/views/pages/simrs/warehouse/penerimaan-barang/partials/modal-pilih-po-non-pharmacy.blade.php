<div class="modal fade" id="pilihPOModal" tabindex="-1" aria-labelledby="pilihPOModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="addModalLabel">Pilih Purchase Order</h1>
            </div>
            <div class="modal-body">
                <input type="text" id="searchPOInput" placeholder="Cari Purchase Order..." class="form-control">
                <br>
                <input type="text" id="searchPOSupplierInput" placeholder="Cari Supplier..." class="form-control">
                <br>
                <table class="table table-bordered table-hover table-striped w-100">
                    <thead class="bg-primary-600">
                        <tr>
                            <th>Tanggal PO</th>
                            <th>Kode PO</th>
                            <th>Supplier</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pos as $po)
                            <tr class="pointer po-row" onclick="PopupPBNPharmacyClass.SelectPO({{ json_encode($po) }})"
                                data-bs-dismiss="modal" title="Pilih {{ $po->kode_po }}">
                                <td>{{ tgl($po->tanggal_po) }}</td>
                                <td>{{ $po->kode_po }}</td>
                                <td>{{ $po->supplier->nama }}</td>
                                <td>{{ $po->keterangan }}</td>
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
