{{-- resources/views/pages/simrs/erm/partials/modal-diagnosa-icd10.blade.php --}}

<div class="modal fade" id="modal-diagnosa-icd10" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Pilih Diagnosa (ICD-10)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form Pencarian -->
                <form id="diagnosa-icd10-search-form" class="mb-3" onsubmit="return false;">
                    <div class="input-group">
                        <input type="text" id="diagnosa_icd10_search_input" class="form-control"
                            placeholder="Ketik kode atau nama diagnosa...">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">Cari</button>
                        </div>
                    </div>
                </form>

                <!-- Tabel untuk menampilkan data -->
                <div class="table-responsive">
                    <table id="diagnosa-icd10-table" class="table table-bordered table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama Diagnosa (ID)</th>
                                <th>Nama Diagnosa (EN)</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Data akan diisi oleh DataTables melalui AJAX --}}
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
