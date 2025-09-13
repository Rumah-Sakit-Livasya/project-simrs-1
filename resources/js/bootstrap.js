// resources/js/bootstrap.js

import axios from "axios";
window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;

// ==========================================================
// KITA AKAN GUNAKAN BLOK INI UNTUK TES
// HAPUS TANDA // DAN GANTI DENGAN KUNCI ASLI ANDA
// ==========================================================
window.Echo = new Echo({
    broadcaster: "pusher",
    key: "a84c07f76285956db9ca", // Contoh: 'ab123cdef456ghi789'
    cluster: "mt1", // Contoh: 'ap1'
    forceTLS: true,
});

// Baris ini tetap ada untuk mengirim sinyal
document.dispatchEvent(new Event("laravel-echo-ready"));
