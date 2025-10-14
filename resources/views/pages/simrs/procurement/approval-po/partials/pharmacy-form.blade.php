<form id="filter-form">
    <div class="form-row">
        <div class="col-md-4 mb-3">
            <label class="form-label" for="tanggal_po_filter">Tanggal PO</label>
            <input type="text" class="form-control" id="tanggal_po_filter" placeholder="Pilih rentang tanggal">
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label" for="kode_po_filter">Kode PO</label>
            <input type="text" class="form-control" id="kode_po_filter" placeholder="Masukkan Kode PO">
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label" for="nama_barang_filter">Nama Barang</label>
            <input type="text" class="form-control" id="nama_barang_filter" placeholder="Masukkan Nama Barang">
        </div>
    </div>
    <div class="form-row">
        <div class="col-md-4 mb-3">
            <label class="form-label" for="approval_filter">Status Approval</label>
            <select id="approval_filter" class="form-control select2">
                <option value="unreviewed" selected>Unreviewed</option>
                <option value="approve">Approved</option>
                <option value="reject">Rejected</option>
                <option value="revision">Revision</option>
                <option value="all">Semua</option>
            </select>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label" for="tipe_po_filter">Tipe PO</label>
            <select id="tipe_po_filter" class="form-control select2">
                <option value="">Semua</option>
                <option value="normal">Normal</option>
                <option value="urgent">Urgent</option>
            </select>
        </div>
    </div>
    <div
        class="panel-content border-faded border-left-0 border-right-0 border-bottom-0 d-flex flex-row align-items-center">
        <button class="btn btn-primary ml-auto" id="filter-btn" type="button">Cari</button>
    </div>
</form>
