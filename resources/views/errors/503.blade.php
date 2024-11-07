<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>✨ Website Maintenance</title>
    <!-- Link font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- CDN GSAP untuk animasi -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.1/gsap.min.js"></script>
    <!-- Vanilla Tilt.js untuk efek 3D -->
    <script src="https://cdn.jsdelivr.net/npm/vanilla-tilt@1.7.0/dist/vanilla-tilt.min.js"></script>
    <style>
        /* Variabel warna pastel */
        :root {
            --glass-bg: rgba(255, 255, 255, 0.1);
            --glass-border: rgba(255, 255, 255, 0.2);
            --gradient-1: #FFB5E8;
            --gradient-2: #B8E1FF;
            --gradient-3: #AFF8DB;
            --text-primary: rgba(255, 255, 255, 0.95);
            --text-secondary: rgba(255, 255, 255, 0.7);
        }

        /* Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Gaya dasar halaman */
        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            background: linear-gradient(-45deg, var(--gradient-1), var(--gradient-2), var(--gradient-3));
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        /* Animasikan latar belakang gradien */
        @keyframes gradient {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        /* Glass Container */
        .glass-container {
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 3rem;
            text-align: center;
            max-width: 700px;
            width: 90%;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.2), inset 0 0 32px 0 rgba(255, 255, 255, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        /* Gambar dan judul */
        .maintenance-image {
            width: 100px;
            margin-bottom: 1.5rem;
            filter: drop-shadow(0px 8px 16px rgba(0, 0, 0, 0.3));
            transform: translateZ(20px);
            /* Membuat gambar lebih menonjol */
        }

        .title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 1rem;
            text-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            /* Bayangan untuk menonjolkan teks */
            transform: translateZ(30px);
            /* Efek agar teks terlihat lebih menonjol */
        }

        .description {
            font-size: 1.1rem;
            line-height: 1.6;
            color: var(--text-secondary);
            margin-bottom: 2rem;
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transform: translateZ(10px);
        }

        /* Tombol interaktif */
        .interactive-element {
            background: rgba(255, 255, 255, 0.1);
            padding: 1rem 2rem;
            border-radius: 12px;
            border: 1px solid var(--glass-border);
            color: var(--text-primary);
            cursor: pointer;
            transition: all 0.3s ease;
            margin: 1rem 0;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            /* Bayangan untuk menonjolkan tombol */
            transform: translateZ(20px);
        }

        .interactive-element:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.05) translateZ(20px);
        }

        /* Media query untuk layar kecil */
        @media (max-width: 768px) {
            .glass-container {
                padding: 2rem;
            }

            .maintenance-image {
                width: 150px;
            }

            .title {
                font-size: 2rem;
            }

            .description {
                font-size: 1rem;
            }
        }
    </style>
</head>

<body>
    <div class="glass-container tilt" data-tilt data-tilt-max="25" data-tilt-speed="400">
        <img src="https://cdn.iconfinder.com/icons/10258520/11399960/512/raster.png?token=1730710880-InU5AlUcxy8%2FUdHao8%2Fv9O3McmS%2F0Z0uxal1jNg6a8E%3D"
            class="maintenance-image" alt="Maintenance Illustration">

        <h1 class="title">Website Dalam Perbaikan</h1>
        <p class="description">
            Kami sedang melakukan peningkatan sistem untuk memberikan pengalaman yang lebih baik untuk Anda.
            Mohon tunggu sebentar, kami akan segera kembali dengan fitur-fitur baru yang menakjubkan! ✨
        </p>

        <div class="description">
            Tim IT
        </div>
    </div>

    <!-- Inisialisasi efek 3D dan animasi -->
    <script>
        // Inisialisasi Vanilla Tilt.js untuk efek 3D
        VanillaTilt.init(document.querySelector(".tilt"), {
            max: 25,
            speed: 400,
            glare: true,
            "max-glare": 0.5
        });

        // Animasi menggunakan GSAP
        gsap.from(".glass-container", {
            y: -50,
            opacity: 0,
            duration: 1,
            ease: "power3.out"
        });

        gsap.from(".title", {
            y: 20,
            opacity: 0,
            delay: 0.5,
            duration: 1,
            ease: "power3.out"
        });

        gsap.from(".description", {
            y: 20,
            opacity: 0,
            delay: 0.7,
            duration: 1,
            ease: "power3.out"
        });

        gsap.from(".interactive-element", {
            scale: 0.9,
            opacity: 0,
            delay: 1,
            duration: 1,
            ease: "elastic.out(1, 0.3)"
        });
    </script>
</body>

</html>
