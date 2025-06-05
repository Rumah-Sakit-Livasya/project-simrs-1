@extends('inc.layout-no-side')

@section('content')
    <style>
        .ppt-slide {
            position: relative;
            width: 1580px;
            padding: 40px 40px 40px 80px;
            /* beri ruang kiri */
            margin: 0 auto 40px auto;
            background: #ffffff;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
            page-break-after: always;
            overflow: hidden;
        }

        /* Side bar design */
        .side-bar {
            position: absolute;
            top: 0;
            left: 0;
            width: 20px;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .side-blue {
            flex: 8;
            background-color: #0094db;
            /* biru */
        }

        .side-orange {
            flex: 2;
            background-color: #f47a20;
            /* oranye */
        }

        .ppt-title {
            font-size: 20px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
        }

        .ppt-subtitle {
            font-size: 16px;
            font-weight: 600;
            color: #6c757d;
            margin-bottom: 10px;
        }

        .jenis-title {
            font-size: 14px;
            font-weight: 600;
            color: #17a2b8;
            margin-top: 10px;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .kegiatan-item {
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #ddd;
        }

        .kegiatan-time {
            font-size: 12px;
            color: #999;
        }
    </style>


    @php
        $maxItemsPerSlide = 8;
        $currentItem = 0;
        $openSlide = false;
    @endphp

    <div class="container-fluid">
        @foreach ($laporan as $orgName => $userGroups)
            @foreach ($userGroups as $userName => $jenisGroups)
                @foreach ($jenisGroups as $jenis => $items)
                    @foreach ($items as $l)
                        @if ($currentItem % $maxItemsPerSlide == 0)
                            @if ($openSlide)
    </div> <!-- Close previous .ppt-slide -->
    @endif
    <div class="ppt-slide">
        <div class="side-bar">
            <div class="side-blue"></div>
            <div class="side-orange"></div>
        </div>

        <div class="ppt-title">
            <img src="/img/logo_name2.png" width="200" class="img-fluid" alt="">
            <span class="ppt-title-text text-center" style="margin-left: 20rem">
                {{ $orgName }}
            </span>
        </div>
        <div class="ppt-subtitle">User: {{ $userName }}</div>
        <div class="jenis-title">{{ $jenis }}</div>
        @php $openSlide = true; @endphp
    @elseif ($loop->first)
        <div class="jenis-title">{{ $jenis }}</div>
        @endif

        <div class="kegiatan-item">
            <p>{{ $l->kegiatan }}</p>
            <div class="kegiatan-time">{{ $l->created_at->format('d-m-Y H:i') }}</div>
        </div>

        @php $currentItem++; @endphp
        @endforeach
        @endforeach
        @endforeach
        @endforeach

        @if ($openSlide)
    </div> <!-- Close last .ppt-slide -->
    @endif
    </div>

    <!-- Tombol Export -->
    <div class="text-center my-4">
        <button id="exportPDF" class="btn btn-danger">Export ke PDF</button>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <script>
        document.getElementById('exportPDF').addEventListener('click', async function() {
            const slides = document.querySelectorAll('.ppt-slide');
            const {
                jsPDF
            } = window.jspdf;

            // Ambil ukuran slide pertama untuk menentukan ukuran halaman
            const firstSlide = slides[0];
            const canvas = await html2canvas(firstSlide, {
                scale: 2
            });
            const imgData = canvas.toDataURL('image/jpeg', 1.0);

            const pageWidth = canvas.width;
            const pageHeight = canvas.height;

            // Buat PDF dengan ukuran slide dan orientasi landscape
            const pdf = new jsPDF({
                orientation: 'landscape',
                unit: 'px',
                format: [pageWidth, pageHeight]
            });

            // Tambahkan halaman pertama
            pdf.addImage(imgData, 'JPEG', 0, 0, pageWidth, pageHeight);

            // Tambahkan sisanya
            for (let i = 1; i < slides.length; i++) {
                const slideCanvas = await html2canvas(slides[i], {
                    scale: 2
                });
                const slideImgData = slideCanvas.toDataURL('image/jpeg', 1.0);
                const w = slideCanvas.width;
                const h = slideCanvas.height;

                pdf.addPage([w, h], 'landscape');
                pdf.addImage(slideImgData, 'JPEG', 0, 0, w, h);
            }

            pdf.save("laporan-kegiatan.pdf");
        });
    </script>
@endsection
