import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    // >>> PENAMBAHAN DIMULAI DI SINI <<<
    server: {
        // Konfigurasi ini memaksa Vite untuk menggunakan 'localhost'
        // dan menghindari masalah dengan alamat IPV6 '[::1]' yang
        // terkadang diblokir oleh ekstensi browser atau firewall.
        host: "localhost",
        hmr: {
            host: "localhost",
        },
    },
    // >>> PENAMBAHAN SELESAI DI SINI <<<
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/js/recognition.js",
            ],
            refresh: true,
        }),
    ],
});
