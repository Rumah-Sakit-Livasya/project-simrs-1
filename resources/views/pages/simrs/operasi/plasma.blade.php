<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Operasi - RS Livasya</title>
    {{-- Auto-refresh halaman setiap 60 detik --}}
    <meta http-equiv="refresh" content="60">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            overflow-x: hidden;
            min-height: 100vh;
        }

        .container {
            max-width: 100%;
            margin: 0;
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            min-height: calc(100vh - 80px);
        }

        header {
            background-color: #fff;
            color: black;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 25px 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.4);
            position: relative;
            z-index: 10;
        }

        .hospital-info {
            display: flex;
            text-transform: uppercase;
            align-items: center;
        }


        .hospital-info img {
            height: 90px;
            width: 90px;
            margin-right: 20px;
            border-radius: 8px;
        }

        .hospital-details h1 {
            margin: 0;
            font-size: 32px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .hospital-details p {
            margin: 5px 0 0 0;
            font-size: 16px;
            opacity: 0.9;
            font-weight: 300;
        }

        .schedule-title {
            text-align: right;
        }

        .schedule-title h2 {
            margin: 0;
            font-size: 36px;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .schedule-title p {
            margin: 5px 0 0 0;
            font-size: 18px;
            opacity: 0.9;
            font-weight: 300;
        }

        .table-container {
            padding: 10px;
            background: white;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 18px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            background: white;
        }

        thead {
            background-color: #366bc3;
            color: white;
        }

        thead tr:first-child th {
            font-size: 16px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 20px 15px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        thead tr:last-child th {
            font-size: 15px;
            font-weight: 500;
            padding: 15px 12px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        tbody td {
            padding: 20px 15px;
            border: 1px solid #e5e7eb;
            text-align: left;
            vertical-align: top;
            font-size: 17px;
            line-height: 1.5;
            background: white;
        }

        tbody tr:nth-child(even) td {
            background-color: #f8f9fa;
        }

        tbody tr:nth-child(odd) td {
            background: white;
        }

        tbody tr:hover td {
            background-color: #e3f2fd;
            transition: background-color 0.2s ease;
        }

        .patient-id {
            font-weight: 600;
        }

        .patient-id .norm {
            font-weight: 700;
            font-size: 20px;
            color: #1f2937;
            display: block;
            margin-bottom: 6px;
        }

        .patient-id .name {
            color: #366bc3;
            font-weight: 600;
            font-size: 18px;
            display: block;
            margin-bottom: 6px;
            text-transform: uppercase;
        }

        .patient-id .birth {
            color: #6b7280;
            font-size: 15px;
            font-weight: 500;
            display: block;
        }

        .tindakan-cell {
            font-weight: 600;
            color: #1f2937;
        }

        .dokter-cell {
            font-weight: 600;
            color: #374151;
        }

        .jam-cell {
            font-size: 24px;
            font-weight: 700;
            color: #366bc3;
            text-align: center;
        }

        .status-selesai {
            font-weight: 700;
            color: #047857;
            text-align: center;
            text-transform: uppercase;
            font-size: 16px;
            letter-spacing: 0.5px;
        }

        .status-belum {
            font-weight: 700;
            color: #dc2626;
            text-align: center;
            text-transform: uppercase;
            font-size: 16px;
            letter-spacing: 0.5px;
        }

        .status-menunggu {
            font-weight: 700;
            color: #f59e0b;
            text-align: center;
            text-transform: uppercase;
            font-size: 16px;
            letter-spacing: 0.5px;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            font-size: 22px;
            color: #6b7280;
            background-color: #f9fafb;
        }

        .empty-state strong {
            display: block;
            margin-bottom: 10px;
        }

        footer {
            background-color: #366bc3;
            color: white;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            padding: 20px 30px;
            font-size: 20px;
            font-weight: 600;
            position: fixed;
            bottom: 0;
            width: 100%;
            left: 0;
            z-index: 1000;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
        }

        #clock {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 8px;
            padding: 12px 20px;
            margin-right: 30px;
            font-family: 'Courier New', monospace;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 1px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .marquee {
            flex: 1;
            overflow: hidden;
            white-space: nowrap;
            position: relative;
        }

        .marquee-content {
            display: inline-block;
            animation: scroll-left 25s linear infinite;
            font-size: 20px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        @keyframes scroll-left {
            0% {
                transform: translateX(100%);
            }

            100% {
                transform: translateX(-100%);
            }
        }

        /* Responsiveness */
        @media (max-width: 1400px) {
            table {
                font-size: 16px;
            }

            tbody td {
                padding: 18px 12px;
            }

            .patient-id .norm {
                font-size: 18px;
            }

            .patient-id .name {
                font-size: 16px;
            }

            .jam-cell {
                font-size: 22px;
            }
        }

        @media (max-width: 1200px) {
            .hospital-details h1 {
                font-size: 28px;
            }

            .schedule-title h2 {
                font-size: 32px;
            }

            table {
                font-size: 15px;
            }

            tbody td {
                padding: 15px 10px;
            }

            .patient-id .norm {
                font-size: 17px;
            }

            .patient-id .name {
                font-size: 15px;
            }

            .jam-cell {
                font-size: 20px;
            }
        }

        @media (max-width: 992px) {
            .hospital-details h1 {
                font-size: 24px;
            }

            .schedule-title h2 {
                font-size: 28px;
            }

            table {
                font-size: 14px;
            }

            tbody td {
                padding: 12px 8px;
            }

            .patient-id .norm {
                font-size: 16px;
            }

            .patient-id .name {
                font-size: 14px;
            }

            .jam-cell {
                font-size: 18px;
            }

            #clock {
                font-size: 18px;
                margin-right: 20px;
            }

            .marquee-content {
                font-size: 18px;
            }
        }

        /* Loading Animation */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, .3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Pulse effect for important elements */
        .pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }

            100% {
                opacity: 1;
            }
        }

        /* CSS class dari template dashboard */
        .bg-primary-600 {
            background-color: #366bc3 !important;
        }

        /* Text alignment untuk kolom tertentu */
        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <header>
            <div class="hospital-info">
                <img src="/img/logo.png" class="logo" alt="Logo RS Livasya">
                <div class="hospital-details">
                    <h1>Rumah Sakit Livasya</h1>
                    <p>Jl. Raya Timur III Dawuan No. 875 Kab. Majalengka 081211151300
                        Kab. Majalengka Jawa Barat</p>
                </div>
            </div>
            <div class="schedule-title">
                <h2>Jadwal Operasi</h2>
                <p>{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY') }}</p>
            </div>
        </header>

        <div class="table-container">
            <table>
                <thead class="bg-primary-600">
                    <tr>
                        <th rowspan="2">Tanggal Operasi</th>
                        <th rowspan="2">Identifikasi Pasien</th>
                        <th rowspan="2">Diagnosa</th>
                        <th rowspan="2">Rencana Tindakan</th>
                        <th rowspan="2">Penjamin</th>
                        <th colspan="2">Tim Bedah</th>
                        <th colspan="2">Tim Anestesi</th>
                        <th rowspan="2">Asal Ruang</th>
                        <th rowspan="2">Ruang OP</th>
                        <th rowspan="2">Jam Operasi</th>
                        <th rowspan="2">Status</th>
                    </tr>
                    <tr>
                        <th>dr. Operator</th>
                        <th>Asisten Bedah</th>
                        <th>dr. Anestesi</th>
                        <th>Asisten Anestesi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($prosedurs as $prosedur)
                        <tr>
                            <td class="text-center">
                                {{ \Carbon\Carbon::parse($prosedur->orderOperasi->tgl_operasi)->format('d M Y') }}
                            </td>
                            <td class="patient-id">
                                <span
                                    class="norm">{{ $prosedur->orderOperasi->registration->patient->medical_record_number ?? '-' }}</span>
                                <span
                                    class="name">{{ $prosedur->orderOperasi->registration->patient->name ?? '-' }}</span>
                                <span
                                    class="birth">{{ $prosedur->orderOperasi->registration->patient->date_of_birth ? \Carbon\Carbon::parse($prosedur->orderOperasi->registration->patient->date_of_birth)->format('d-m-Y') : '-' }}</span>
                            </td>
                            <td class="text-center">
                                {{ $prosedur->orderOperasi->registration->diagnosa_awal ?? 'Belum Terisi' }}
                            </td>
                            <td class="tindakan-cell">
                                {{ $prosedur->tindakanOperasi->nama_operasi ?? '-' }}
                            </td>
                            <td class="text-center">
                                {{ $prosedur->orderOperasi->registration->penjamin->nama_perusahaan ?? '-' }}
                            </td>
                            <td class="dokter-cell">
                                {{ $prosedur->dokterOperator?->employee?->fullname ?? '-' }}
                            </td>
                            <td class="dokter-cell">
                                {{ $prosedur->assDokterOperator1?->employee?->fullname ?? '-' }}
                            </td>
                            <td class="dokter-cell">
                                {{ $prosedur->dokterAnastesi?->employee?->fullname ?? '-' }}
                            </td>
                            <td class="dokter-cell">
                                {{ $prosedur->assDokterAnastesi?->employee?->fullname ?? '-' }}
                            </td>
                            <td class="text-center">
                                {{ $prosedur->orderOperasi->registration->ruang_asal ?? 'SUPERIOR' }}
                            </td>
                            <td class="text-center">
                                {{ $prosedur->ruang_operasi ?? 'OK-1' }}
                            </td>
                            <td class="jam-cell">
                                {{ $prosedur->waktu_mulai ? \Carbon\Carbon::parse($prosedur->waktu_mulai)->format('H:i') : '-' }}
                            </td>
                            <td
                                class="@if ($prosedur->status == 'selesai') status-selesai @elseif($prosedur->status == 'berlangsung') status-belum @else status-menunggu @endif">
                                {{ ucfirst($prosedur->status ?? 'Menunggu') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="13" class="empty-state">
                                <strong>Tidak ada jadwal operasi untuk hari ini</strong>
                                <small>Sistem akan memperbarui otomatis setiap 60 detik</small>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <footer class="bg-primary-600">
        <span id="clock"></span>
        <div class="marquee">
            <div class="marquee-content">
                Selamat Datang di Rumah Sakit Livasya - Melayani dengan Sepenuh Hati untuk Kesehatan Anda - Jadwal
                Operasi Terupdate Real Time - Informasi Akurat dan Terpercaya
            </div>
        </div>
    </footer>

    <script>
        function updateClock() {
            const now = new Date();
            const time = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            });
            const date = now.toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
            document.getElementById('clock').innerHTML = `
                <div style="font-size: 22px;">${time}</div>
                <div style="font-size: 12px; opacity: 0.8;">${date}</div>
            `;
        }

        // Update clock immediately and then every second
        updateClock();
        setInterval(updateClock, 1000);

        // Add loading indicator before refresh
        let refreshTimer = 60;

        function updateRefreshCounter() {
            refreshTimer--;
            if (refreshTimer <= 5) {
                document.title = `Jadwal Operasi - Refresh dalam ${refreshTimer}s`;
            }
            if (refreshTimer <= 0) {
                document.body.style.opacity = '0.7';
                document.body.innerHTML +=
                    '<div style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999;"><div class="loading"></div></div>';
            }
        }
        setInterval(updateRefreshCounter, 1000);

        // Add smooth animations
        window.addEventListener('load', function() {
            document.querySelectorAll('tbody tr').forEach((row, index) => {
                row.style.opacity = '0';
                row.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    row.style.transition = 'all 0.5s ease';
                    row.style.opacity = '1';
                    row.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });

        // Add pulse effect to urgent items
        setInterval(() => {
            document.querySelectorAll('.status-belum, .status-menunggu').forEach(el => {
                el.classList.add('pulse');
                setTimeout(() => el.classList.remove('pulse'), 2000);
            });
        }, 5000);
    </script>
</body>

</html>
