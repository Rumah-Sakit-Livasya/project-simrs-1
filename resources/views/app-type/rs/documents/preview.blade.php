<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- Ambil judul dari dokumen --}}
    <title>Preview: {{ $document->title }}</title>

    {{-- CSS minimalis untuk styling --}}
    <link rel="stylesheet" media="screen, print" href="/css/vendors.bundle.css">
    <link rel="stylesheet" media="screen, print" href="/css/app.bundle.css">

    <style>
        /* Pastikan konten mengisi seluruh body */
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
            /* Mencegah scrollbar ganda */
            background-color: #f0f2f5;
            /* Warna latar belakang netral */
        }

        .preview-container {
            height: 100%;
            width: 100%;
            display: flex;
            flex-direction: column;
        }

        .preview-header {
            padding: 0.75rem 1.25rem;
            background-color: #fff;
            border-bottom: 1px solid #e9e9e9;
            flex-shrink: 0;
            /* Header tidak menyusut */
        }

        .preview-content {
            flex-grow: 1;
            /* Konten mengisi sisa ruang */
            overflow: auto;
            /* Scroll jika perlu */
        }

        .fallback-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            flex-direction: column;
        }
    </style>
</head>

<body>

    <div class="preview-container">
        {{-- Header di dalam popup untuk menampilkan judul dan tombol download --}}
        <div class="preview-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-truncate" title="{{ $document->title }}">
                <i class="fal fa-file-alt mr-2"></i>
                {{ $document->title }}
            </h5>
            <a href="{{ $fileUrl }}" class="btn btn-primary btn-sm" download="{{ $document->file_name }}">
                <i class="fal fa-download mr-1"></i>
                Download
            </a>
        </div>

        {{-- Konten utama (viewer atau fallback) --}}
        <div class="preview-content">
            @if ($extension === 'pdf')
                {{-- Embed PDF menggunakan iframe --}}
                <iframe src="{{ $fileUrl }}" width="100%" height="100%" frameborder="0"></iframe>
            @elseif (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg']))
                {{-- Tampilkan gambar, pastikan terpusat --}}
                <div style="display: flex; justify-content: center; align-items: center; height: 100%; padding: 1rem;">
                    <img src="{{ $fileUrl }}" class="img-fluid" style="max-height: 100%;" alt="Image Preview">
                </div>
            @else
                {{-- Fallback untuk tipe file lain --}}
                <div class="fallback-container">
                    <i class="fal fa-file-alt fa-4x mb-3 text-muted"></i>
                    <h4 class="text-muted">Preview tidak tersedia untuk tipe file (.{{ $extension }})</h4>
                    <p>Silakan download file untuk melihatnya.</p>
                </div>
            @endif
        </div>
    </div>

</body>

</html>
