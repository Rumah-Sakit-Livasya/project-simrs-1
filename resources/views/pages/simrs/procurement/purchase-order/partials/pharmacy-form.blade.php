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
            <label class="form-label" for="status_filter">Status Approval</label>
            <select id="status_filter" class="form-control select2">
                <option value="">Semua</option>
                <option value="unreviewed">Unreviewed</option>
                <option value="approve">Approve</option>
                <option value="reject">Reject</option>
                <option value="revision">Revision</option>
            </select>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label" for="tipe_input_filter">Tipe Input</label>
            <select id="tipe_input_filter" class="form-control select2">
                <option value="">Semua</option>
                <option value="0">Normal</option>
                <option value="1">Auto</option>
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
        <button class="btn btn-success ml-2" id="tambah-btn" type="button">Tambah PO</button>
    </div>
</form>
