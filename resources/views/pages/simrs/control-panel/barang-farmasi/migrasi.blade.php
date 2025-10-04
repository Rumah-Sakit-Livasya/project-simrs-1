@extends('inc.layout')
@section('title', 'Migrasi Nilai Normal Laboratorium')

@section('content')
    <main id="js-page-content" role="main" class="page-content" @style('margin-top: 5rem;')>
        <div class="container" style="padding-top: 20px;">
            {{-- Header Halaman --}}
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Migrasi Data Barang Farmasi</h1>
            </div>

            {{-- Card Utama --}}
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Export & Import Data Master Barang Farmasi</h6>
                </div>
                <div class="card-body">

                    {{-- Notifikasi / Alert --}}
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- Menampilkan error validasi dari Laravel Excel --}}
                    @if (session()->has('import_errors'))
                        <div class="alert alert-danger" role="alert">
                            <strong>Gagal mengimpor data. Terdapat beberapa kesalahan:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach (session()->get('import_errors') as $failure)
                                    <li>
                                        <strong>Baris {{ $failure->row() }}:</strong>
                                        {{ implode(', ', $failure->errors()) }}
                                        (Nilai: '{{ $failure->values()[$failure->attribute()] ?? '' }}')
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- 1. Bagian Export --}}
                    <div class="mb-4">
                        <h5 class="font-weight-bold">Export Data</h5>
                        <p>Unduh semua data master barang farmasi yang ada saat ini ke dalam format Excel. File ini juga
                            dapat
                            digunakan sebagai template untuk impor data.</p>
                        <form action="{{ route('barang-farmasi.export') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-file-excel mr-2"></i>Export ke Excel
                            </button>
                        </form>
                    </div>

                    <hr>

                    {{-- 2. Bagian Import --}}
                    <div>
                        <h5 class="font-weight-bold">Import Data</h5>
                        <p>Unggah file Excel untuk menambah atau memperbarui data master barang farmasi. Pastikan struktur
                            kolom
                            sesuai dengan template yang diunduh dari fitur export.</p>
                        <form action="{{ route('barang-farmasi.import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="file_import">Pilih File Excel (.xlsx, .xls)</label>
                                {{-- Menggunakan custom file input dari Bootstrap untuk tampilan yang lebih baik --}}
                                <div class="custom-file">
                                    <input type="file"
                                        class="custom-file-input @error('file_import') is-invalid @enderror"
                                        id="file_import" name="file_import" required>
                                    <label class="custom-file-label" for="file_import">Pilih file...</label>
                                    @error('file_import')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-2">
                                <i class="fas fa-upload mr-2"></i>Import dari Excel
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </main>
@endsection

@section('plugin')
    {{-- Script ini diperlukan agar nama file muncul di input file Bootstrap --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.querySelector('.custom-file-input');
            if (fileInput) {
                fileInput.addEventListener('change', function(e) {
                    let fileName = e.target.files[0] ? e.target.files[0].name : 'Pilih file...';
                    let nextSibling = e.target.nextElementSibling;
                    if (nextSibling) {
                        nextSibling.innerText = fileName;
                    }
                });
            }
        });
    </script>
@endsection
