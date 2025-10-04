@extends('inc.layout')
@section('title', 'Migrasi Data Pabrik')

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <!-- Page Title -->
        <div class="subheader">
            <h1 class="subheader-title">
                <i class='subheader-icon fal fa-database'></i> Migrasi Data Pabrik
                <small>
                    Import dan Export data pabrik dari dan ke file Excel.
                </small>
            </h1>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Opsi Migrasi
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <div class="row">
                                <!-- Export Section -->
                                <div class="col-md-6 border-right">
                                    <h5 class="frame-heading">Export Data</h5>
                                    <div class="frame-wrap">
                                        <p>Klik tombol di bawah untuk mengunduh semua data pabrik dalam format Excel. File
                                            ini bisa dijadikan template untuk impor.</p>
                                        <form action="{{ route('warehouse-pabrik.export') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-success">
                                                <i class="fal fa-file-excel mr-1"></i> Export ke Excel
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Import Section -->
                                <div class="col-md-6">
                                    <h5 class="frame-heading">Import Data</h5>
                                    <div class="frame-wrap">
                                        <p>Pilih file Excel (.xlsx atau .xls) untuk diimpor. Pastikan kolom sesuai
                                            dengan template hasil export.</p>
                                        <form action="{{ route('warehouse-pabrik.import') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group">
                                                <label class="form-label">Pilih File (Excel)</label>
                                                <div class="custom-file">
                                                    <input type="file"
                                                        class="custom-file-input @error('file') is-invalid @enderror"
                                                        id="customFile" name="file" required>
                                                    <label class="custom-file-label" for="customFile">Choose
                                                        file</label>
                                                </div>
                                                @error('file')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <button type="submit" class="btn btn-primary mt-3">
                                                <i class="fal fa-upload mr-1"></i> Import dari Excel
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('plugin')
    <script>
        // Script to show filename in custom file input
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });
    </script>
@endsection
