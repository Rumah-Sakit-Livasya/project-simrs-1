<button class="btn btn-success mb-4" data-backdrop="static" data-keyboard="false" id="downloadTemplateDeductionBtn">
    <i class="fas fa-download mr-2"></i> Unduh Template Potongan
</button>

<form id="import-deduction" action="{{ route('deduction.import') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="upload-container">
        <div class="upload-wrapper" id="drop-area">
            <div class="upload-icon">
                <i class="fas fa-file-excel"></i>
            </div>
            <div class="upload-text">
                <p>Klik tombol di bawah ini untuk mengunggah file</p>
                <label class="button" for="fileElemDeduction">Browse Files</label>
                <input type="file" style="display: none;" id="fileElemDeduction" name="deduction_import">
            </div>
        </div>
        <div id="fileListDeduction"></div>

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
