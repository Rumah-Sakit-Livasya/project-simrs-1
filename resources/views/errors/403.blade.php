<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - This action is unauthorized</title>
    <!-- Link font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- CDN GSAP untuk animasi -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.1/gsap.min.js"></script>
    <!-- Vanilla Tilt.js untuk efek 3D -->
    <script src="https://cdn.jsdelivr.net/npm/vanilla-tilt@1.7.0/dist/vanilla-tilt.min.js"></script>
    <style>
        :root {
            --glass-bg: rgba(255, 255, 255, 0.1);
            --glass-border: rgba(255, 255, 255, 0.2);
            --gradient-1: #FFB5E8;
            --gradient-2: #B8E1FF;
            --gradient-3: #AFF8DB;
            --text-primary: rgba(255, 255, 255, 0.95);
            --text-secondary: rgba(255, 255, 255, 0.7);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

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

        .maintenance-image {
            width: 100px;
            margin-bottom: 1.5rem;
            filter: drop-shadow(0px 8px 16px rgba(0, 0, 0, 0.3));
        }

        .title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 1rem;
            text-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }

        .description {
            font-size: 1.1rem;
            line-height: 1.6;
            color: var(--text-secondary);
            margin-bottom: 2rem;
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

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
        }

        .interactive-element:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.05);
        }

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
    <div class="glass-container tilt" data-tilt data-tilt-max="25" data-tilt-speed="400"
        style="z-index: 99999; position: relative;">
        <img src="https://cdn0.iconfinder.com/data/icons/internet-security-134/66/78_unauthorized_access_unauthorized_attack_hacking_pirating_illegal_access-512.png"
            class="maintenance-image" alt="Error Illustration">

        <h1 class="title">403 - This action is unauthorized</h1>
        <p class="description">
            Anda tidak memiliki izin untuk mengakses halaman ini. Silakan kembali ke halaman sebelumnya.
        </p>
        <p>
            <button onclick="history.back()"
                style="z-index: 999999; position: relative; background-color: rgba(0, 59, 236, 0.2); color: var(--text-primary); border: none; padding: 1rem 2rem; font-size: 1rem; cursor: pointer; border-radius: 12px; box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);">
                Kembali
            </button>
        </p>
    </div>

    <script>
        VanillaTilt.init(document.querySelector(".tilt"), {
            max: 25,
            speed: 400,
            glare: true,
            "max-glare": 0.5
        });

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
