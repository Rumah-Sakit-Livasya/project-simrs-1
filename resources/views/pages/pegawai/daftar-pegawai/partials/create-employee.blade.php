<form id="import-pegawai" enctype="multipart/form-data">
    @method('POST')
    @csrf

    <div class="upload-container">
        <div class="upload-wrapper" id="drop-area">
            <div class="upload-icon">
                <i class="fas fa-file-excel"></i>
            </div>
            <div class="upload-text">
                <p>Klik tombol dibawah ini untuk upload file</p>
                <label class="button" for="fileElem">Browse Files</label>
                <input type="file" id="fileElem" multiple accept=".xls, .xlsx" style="display: none;"
                    name="employee_import">
            </div>
        </div>
        <div id="fileList"></div>

        <button type="submit" class="btn btn-primary btn-block">
            <div class="ikon-tambah">
                <span class="fas fa-upload mr-1"></span>
                Tambah
            </div>
            <div class="span spinner-text d-none">
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Loading...
            </div>
        </button>
    </div>
</form>
