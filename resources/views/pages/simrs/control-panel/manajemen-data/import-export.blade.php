@extends('inc.layout')
@section('title', 'Import & Export Data')

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <ol class="breadcrumb page-breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
            <li class="breadcrumb-item active">Import & Export Data</li>
            <li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>
        </ol>
        <div class="subheader">
            <h1 class="subheader-title">
                <i class='subheader-icon fal fa-upload'></i> Manajemen Data <small>Import & Export</small>
            </h1>
        </div>

        <div class="row">
            <!-- Panel Kelas Rawat -->
            <div class="col-md-4">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Kelas Rawat
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <div class="panel-tag">
                                Gunakan fitur ini untuk mengelola data master Kelas Rawat. Pastikan format file Excel sesuai
                                dengan template yang di-export.
                            </div>
                            <form action="{{ route('import.kelas-rawat') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label class="form-label">File Excel</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="fileKelasRawat" name="file"
                                            required>
                                        <label class="custom-file-label" for="fileKelasRawat">Pilih file...</label>
                                    </div>
                                    <span class="help-block">File harus berformat .xlsx atau .xls</span>
                                </div>
                                <button type="submit" class="btn btn-primary"><i
                                        class="fal fa-upload mr-1"></i>Import</button>
                                <a href="{{ route('export.kelas-rawat') }}" class="btn btn-success"><i
                                        class="fal fa-download mr-1"></i>Export Template</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel Ruangan (Rooms) -->
            <div class="col-md-4">
                <div id="panel-2" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Ruangan (Rooms)
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <div class="panel-tag">
                                Pastikan data Kelas Rawat sudah ada sebelum mengimpor Ruangan. Kolom `nama_kelas_rawat` di
                                Excel harus cocok.
                            </div>
                            <form action="{{ route('import.rooms') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label class="form-label">File Excel</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="fileRooms" name="file"
                                            required>
                                        <label class="custom-file-label" for="fileRooms">Pilih file...</label>
                                    </div>
                                    <span class="help-block">File harus berformat .xlsx atau .xls</span>
                                </div>
                                <button type="submit" class="btn btn-primary"><i
                                        class="fal fa-upload mr-1"></i>Import</button>
                                <a href="{{ route('export.rooms') }}" class="btn btn-success"><i
                                        class="fal fa-download mr-1"></i>Export Template</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel Tempat Tidur (Beds) -->
            <div class="col-md-4">
                <div id="panel-3" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Tempat Tidur (Beds)
                        </h2>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <div class="panel-tag">
                                Pastikan data Ruangan sudah ada sebelum mengimpor Bed. Kolom `nama_ruangan` di Excel harus
                                cocok.
                            </div>
                            <form action="{{ route('import.beds') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label class="form-label">File Excel</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="fileBeds" name="file"
                                            required>
                                        <label class="custom-file-label" for="fileBeds">Pilih file...</label>
                                    </div>
                                    <span class="help-block">File harus berformat .xlsx atau .xls</span>
                                </div>
                                <button type="submit" class="btn btn-primary"><i
                                        class="fal fa-upload mr-1"></i>Import</button>
                                <a href="{{ route('export.beds') }}" class="btn btn-success"><i
                                        class="fal fa-download mr-1"></i>Export Template</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('plugin')
    <script>
        // Script untuk menampilkan nama file pada input custom file
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });
    </script>
@endsection
