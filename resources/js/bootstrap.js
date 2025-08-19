// resources/js/bootstrap.js

import axios from "axios";
window.axios = axios;

window.axios.defaults.headers.common["X-Requested-with"] = "XMLHttpRequest";

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// 1. IMPORT LIBRARY YANG DIBUTUHKAN
import Echo from "laravel-echo";
import Pusher from "pusher-js";

// 2. JADIKAN PUSHER TERSEDIA SECARA GLOBAL
// Laravel Echo akan mencari window.Pusher secara default.
window.Pusher = Pusher;

// 3. INISIALISASI DAN KONFIGURASI LARAVEL ECHO
window.Echo = new Echo({
    broadcaster: "pusher",
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true, // Gunakan 'true' jika website Anda menggunakan HTTPS
    // Jika Anda masih mengalami masalah SSL di lokal, Anda bisa mencoba opsi di bawah.
    // Namun, ini seharusnya tidak diperlukan jika php.ini sudah benar.
    // encrypted: true,
    // wsHost: window.location.hostname,
    // wsPort: 6001, // Port default untuk server websocket lokal seperti Soketi/laravel-websockets
    // wssPort: 6001,
    // disableStats: true,
    // enabledTransports: ['ws', 'wss'],
});
