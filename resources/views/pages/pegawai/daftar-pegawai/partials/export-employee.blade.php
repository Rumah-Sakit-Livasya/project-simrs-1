<form id="export-pegawai" method="GET" action="{{ route('employees.export') }}">
    @csrf
    <div class="export-container">
        <div class="export-wrapper">
            <div class="export-icon mb-3">
                <i class="fas fa-file-excel fa-3x"></i>
            </div>
            <div class="export-text mb-3">
                <p>Klik tombol dibawah ini untuk mengekspor data pegawai ke Excel</p>
            </div>
        </div>
        <button type="submit" class="btn btn-success btn-block">
            <div class="ikon-export">
                <span class="fas fa-download mr-1"></span>
                Export
            </div>
            <div class="span spinner-text d-none">
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Loading...
            </div>
        </button>
    </div>
</form>
