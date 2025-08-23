<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plasma Antrian - {{ $plasmaRawatJalan->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-primary: #F4F7FC;
            --panel-bg: #FFFFFF;
            --header-bg: #2C3E50;
            --text-dark: #34495E;
            --text-light: #7F8C8D;
            --accent-primary: #3498DB;
            --accent-secondary: #E74C3C;
            --border-color: #EAEDF1;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            height: 100vh;
            font-family: 'Roboto', sans-serif;
            background-color: var(--bg-primary);
            overflow: hidden;
        }

        .header {
            background: var(--header-bg);
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            height: 12vh;
        }

        .header h1 {
            font-size: 2.5rem;
            font-weight: 700;
        }

        .header h4 {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .main-content {
            display: flex;
            padding: 2rem;
            gap: 2rem;
            height: calc(100vh - 12vh - 8vh);
        }

        .now-serving-panel {
            flex: 2;
            background: var(--panel-bg);
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.07);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease-in-out;
        }

        .now-serving-panel.highlight-update {
            animation: highlight 1.5s ease;
        }

        @keyframes highlight {
            0% {
                transform: scale(1);
                background-color: #E9F5FF;
                border: 2px solid var(--accent-primary);
            }

            50% {
                transform: scale(1.02);
                background-color: #D4E9F9;
                border: 2px solid var(--accent-primary);
            }

            100% {
                transform: scale(1);
                background-color: var(--panel-bg);
                border: 2px solid transparent;
            }
        }

        #view_current_antrian {
            font-size: 16vh;
            font-weight: 900;
            color: var(--accent-secondary);
            line-height: 1;
            margin: 1rem 0;
        }

        #view_current_poli {
            font-size: 5vh;
            font-weight: 700;
            color: var(--text-dark);
            margin-top: 1rem;
        }

        #view_current_dokter {
            font-size: 3vh;
            color: var(--text-light);
            margin-top: 0.5rem;
        }

        .panel-label {
            font-size: 1.5rem;
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .queue-list-panel {
            flex: 1;
            background: var(--panel-bg);
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.07);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .queue-list-header {
            padding: 1.2rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-dark);
            border-bottom: 2px solid var(--border-color);
        }

        .queue-list {
            list-style: none;
            overflow-y: auto;
            flex-grow: 1;
        }

        .queue-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.2rem;
            border-bottom: 1px solid var(--border-color);
            transition: background-color 0.2s ease;
        }

        .queue-item:last-child {
            border-bottom: none;
        }

        .queue-item:nth-child(even) {
            background-color: #FBFCFD;
        }

        .queue-item-info {
            text-align: left;
        }

        .department-name {
            font-weight: 600;
            font-size: 1.1rem;
            color: var(--text-dark);
        }

        .doctor-name {
            font-size: 0.9rem;
            color: var(--text-light);
        }

        .queue-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--accent-primary);
        }

        .footer {
            background: var(--header-bg);
            color: white;
            position: fixed;
            bottom: 0;
            width: 100%;
            height: 8vh;
            display: flex;
            align-items: center;
            overflow: hidden;
        }

        .marquee {
            font-size: 1.2rem;
            white-space: nowrap;
        }

        .marquee span {
            display: inline-block;
            padding-left: 100%;
            animation: marquee 30s linear infinite;
        }

        @keyframes marquee {
            0% {
                transform: translate(0, 0);
            }

            100% {
                transform: translate(-100%, 0);
            }
        }

        #start-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(44, 62, 80, 0.95);
            /* Latar belakang gelap semi-transparan */
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            text-align: center;
        }

        .start-box {
            padding: 3rem;
        }

        .start-box h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .start-box p {
            font-size: 1.2rem;
            opacity: 0.8;
            margin-bottom: 2rem;
        }

        .btn-start {
            background-color: var(--accent-primary);
            color: white;
            border: none;
            padding: 1rem 2.5rem;
            font-size: 1.5rem;
            font-weight: 600;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-start:hover {
            background-color: #2980B9;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>

<body>
    <button id="test-suara-manual"
        style="position: fixed; top: 15vh; left: 2rem; z-index: 10000; padding: 1rem; font-size: 1rem;"
        class="btn btn-success">
        Test Suara Manual
    </button>

    <div id="start-overlay">
        <div class="start-box">
            <h1>Selamat Datang di Antrian Poliklinik</h1>
            <p>Klik tombol di bawah ini untuk memulai tampilan.</p>
            <button id="start-button" class="btn-start">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="5 3 19 12 5 21 5 3"></polygon>
                </svg>
                Mulai Tampilan
            </button>
        </div>
    </div>
    <div class="header">
        <div>
            <h1>RUMAH SAKIT LIVASYA</h1>
            <h4>Jl. Raya Timur III Dawuan No. 875 Kab. Majalengka Telp 081211151300</h4>
        </div>
    </div>

    <div class="main-content">
        <div class="now-serving-panel" id="now-serving-panel">
            <div class="panel-label">NOMOR ANTRIAN</div>
            <div id="view_current_antrian">-</div>
            <div class="panel-label">MENUJU POLI</div>
            <div id="view_current_poli">-</div>
            <div id="view_current_dokter">-</div>
        </div>

        <div class="queue-list-panel">
            <div class="queue-list-header">Antrian Poliklinik</div>
            <ul class="queue-list">
                @forelse ($plasmaRawatJalan->departements as $departement)
                    <li class="queue-item">
                        <div class="queue-item-info">
                            <div class="department-name">{{ $departement->name }}</div>
                            <div class="doctor-name">{{ $departement->default_dokter ?? 'Belum ada dokter' }}</div>
                        </div>
                        <div class="queue-number" id="curr_ant_perpoli_{{ $departement->id }}">00</div>
                    </li>
                @empty
                    <li class="queue-item">
                        <div class="queue-item-info">
                            <div class="department-name text-muted">Belum ada departemen yang ditambahkan</div>
                        </div>
                    </li>
                @endforelse
            </ul>
        </div>
    </div>

    <div class="footer">
        <div class="marquee">
            <span>RUMAH SAKIT LIVASYA - MELAYANI SEPENUH HATI. KEPUASAAN ANDA ADALAH PRIORITAS KAMI &nbsp;&nbsp;&nbsp; |
                &nbsp;&nbsp;&nbsp;</span>
        </div>
    </div>

    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.14.2/dist/echo.iife.js"></script>
    <script src="{{ asset('js/terbilang.js') }}"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Referensi ke elemen-elemen penting
            const startButton = document.getElementById('start-button');
            const overlay = document.getElementById('start-overlay');
            const antrianDisplay = document.getElementById('view_current_antrian');
            const poliDisplay = document.getElementById('view_current_poli');
            const dokterDisplay = document.getElementById('view_current_dokter');
            const servingPanel = document.getElementById('now-serving-panel');

            let isPlaying = false;
            let hasStarted = false;

            // Fungsi pemutar suara menggunakan HTML5 Audio
            function playCallSound(nomorAntrian, namaPoli) {
                if (isPlaying) {
                    console.warn("Audio sedang diputar, panggilan baru diabaikan.");
                    return;
                }
                isPlaying = true;

                const nomorTerbilang = terbilang(parseInt(nomorAntrian, 10));
                const textToSpeak = `Nomor antrian, ${nomorTerbilang}, silahkan menuju ke, ${namaPoli}`;
                const ttsUrl = `/api/tts?text=${encodeURIComponent(textToSpeak)}`;

                console.log("Mempersiapkan audio dari:", ttsUrl);

                const audio = new Audio(ttsUrl);
                audio.playbackRate = 0.9;

                // ==========================================================
                // PERBAIKAN UTAMA: Tangani Promise dari .play()
                // ==========================================================
                const playPromise = audio.play();

                if (playPromise !== undefined) {
                    playPromise.then(_ => {
                            // Pemutaran berhasil dimulai
                            console.log("Pemutaran audio berhasil dimulai.");
                        })
                        .catch(error => {
                            // Ini akan dieksekusi jika browser memblokir autoplay!
                            console.error("GAGAL MEMUTAR AUDIO SECARA OTOMATIS:", error);
                            alert(
                                "Browser memblokir pemutaran suara. Pastikan situs ini diizinkan untuk memutar audio."
                                );
                            isPlaying = false; // Reset flag karena gagal
                        });
                }
                // ==========================================================

                audio.onended = () => {
                    isPlaying = false;
                    console.log("Pemutaran selesai.");
                };
                audio.onerror = () => {
                    isPlaying = false;
                    console.error("Error saat memuat file audio.");
                };
            }

            // Fungsi untuk memulai listener Echo
            function initializeEchoListener() {
                // Inisialisasi Echo secara manual dengan kunci yang sudah di-hardcode
                try {
                    window.Echo = new Echo({
                        broadcaster: 'pusher',
                        // ==========================================================
                        // PASTIKAN ANDA MENGGANTI INI DENGAN KUNCI ASLI ANDA
                        // ==========================================================
                        key: 'a84c07f76285956db9ca',
                        cluster: 'mt1',
                        forceTLS: true
                    });

                    console.log("Laravel Echo diinisialisasi.");

                    const plasmaId = {{ $plasmaRawatJalan->id }};
                    const channelName = `plasma-channel.${plasmaId}`;

                    // Pantau status koneksi untuk debugging
                    window.Echo.connector.pusher.connection.bind('state_change', function(states) {
                        console.log("Status koneksi Pusher:", states.current);
                    });

                    console.log(`Mendengarkan di channel: ${channelName}`);

                    window.Echo.channel(channelName)
                        .listen('.pasien.dipanggil', (e) => {
                            console.log('%c EVENT DITERIMA! ',
                                'background: #28a745; color: #fff; font-weight: bold; padding: 5px;', e);

                            const reg = e.registration;
                            if (!reg || !reg.departement) {
                                console.error("Data event tidak lengkap:", e);
                                return;
                            }

                            const nomorAntrian = reg.no_urut;
                            const namaPoli = reg.departement.name;
                            const namaDokter = reg.nama_dokter;
                            const nomorFormatted = 'A-' + String(nomorAntrian).padStart(2, '0');

                            poliDisplay.textContent = namaPoli;
                            antrianDisplay.textContent = nomorFormatted;
                            dokterDisplay.textContent = namaDokter;

                            playCallSound(nomorAntrian, namaPoli);

                            servingPanel.classList.remove('highlight-update');
                            void servingPanel.offsetWidth;
                            servingPanel.classList.add('highlight-update');

                            const poliQueueElement = document.getElementById(
                                `curr_ant_perpoli_${reg.departement_id}`);
                            if (poliQueueElement) {
                                poliQueueElement.textContent = String(nomorAntrian).padStart(2, '0');
                            }
                        });
                } catch (e) {
                    console.error("GAGAL menginisialisasi Echo:", e);
                }
            }

            // Event listener untuk tombol start
            startButton.addEventListener('click', function() {
                if (hasStarted) return;
                hasStarted = true;
                overlay.style.display = 'none';

                try {
                    const audioContext = new(window.AudioContext || window.webkitAudioContext)();
                    if (audioContext.state === 'suspended') audioContext.resume();
                } catch (e) {}

                initializeEchoListener();
            });
        });
    </script>
</body>

</html>
