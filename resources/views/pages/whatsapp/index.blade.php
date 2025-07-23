@extends('inc.layout')
@section('title', 'WhatsApp Messenger')

{{-- Pre-load Font Awesome di section terpisah agar lebih rapi --}}
@section('extended-css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection


@section('content')
    <main id="js-page-content" role="main" class="page-content">
        {{-- Custom CSS untuk halaman chat --}}
        <style>
            /* Layout Utama */
            .chat-container {
                display: flex;
                height: 85vh;
                /* Sedikit lebih tinggi */
                border: 1px solid #ddd;
                background-color: #fff;
            }

            .sidebar {
                width: 35%;
                max-width: 380px;
                border-right: 1px solid #ddd;
                display: flex;
                flex-direction: column;
                background-color: #fff;
            }

            .main-chat {
                width: 100%;
                display: flex;
                flex-direction: column;
                background-color: #e5ddd5;
                background-image: url('https://user-images.githubusercontent.com/15075759/28719144-86dc0f70-73b1-11e7-911d-60d70fcded21.png');
                /* Pola background WA */
            }

            /* Header */
            .sidebar-header,
            .chat-header {
                padding: 1rem;
                border-bottom: 1px solid #ddd;
                background-color: #f0f2f5;
                flex-shrink: 0;
            }

            .chat-header {
                font-weight: 600;
            }

            /* Daftar Percakapan (Sidebar) */
            .convo-list {
                flex-grow: 1;
                overflow-y: auto;
            }

            .convo-list a {
                display: flex;
                align-items: center;
                padding: 0.75rem 1rem;
                border-bottom: 1px solid #f1f1f1;
                text-decoration: none;
                color: #333;
                gap: 0.75rem;
            }

            .convo-list a:hover {
                background-color: #f5f5f5;
            }

            .convo-list a.active {
                background-color: #e9ecef;
            }

            .convo-avatar {
                width: 48px;
                height: 48px;
                background-color: #007bff;
                color: white;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: bold;
                font-size: 1.2rem;
                flex-shrink: 0;
            }

            .convo-details {
                flex-grow: 1;
                overflow: hidden;
            }

            .convo-name {
                font-weight: 600;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .last-message {
                color: #6c757d;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                display: flex;
                align-items: center;
                gap: 4px;
                font-size: 0.9em;
            }

            .convo-meta {
                display: flex;
                flex-direction: column;
                align-items: flex-end;
                font-size: 0.75rem;
                flex-shrink: 0;
            }

            .last-message-time {
                color: #667781;
                font-weight: 400;
            }

            /* Riwayat Chat */
            .chat-history {
                flex-grow: 1;
                padding: 1rem 3rem;
                /* Beri padding lebih */
                overflow-y: auto;
                display: flex;
                flex-direction: column;
            }

            .message-wrapper {
                display: flex;
                margin-bottom: 2px;
            }

            .message-wrapper.out {
                justify-content: flex-end;
            }

            .message-wrapper.in {
                justify-content: flex-start;
            }

            .message {
                max-width: 65%;
                padding: 6px 12px;
                border-radius: 8px;
                line-height: 1.4;
                box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);
                position: relative;
            }

            .message-in {
                background-color: #fff;
            }

            .message-out {
                background-color: #dcf8c6;
            }

            /* Ekor gelembung chat */
            .message-in::before {
                content: "";
                position: absolute;
                top: 0;
                left: -8px;
                width: 0;
                height: 0;
                border-style: solid;
                border-width: 0px 10px 10px 0;
                border-color: transparent #fff transparent transparent;
            }

            .message-out::before {
                content: "";
                position: absolute;
                top: 0;
                right: -8px;
                width: 0;
                height: 0;
                border-style: solid;
                border-width: 0px 0 10px 10px;
                border-color: transparent transparent transparent #dcf8c6;
            }

            .message-content {
                word-wrap: break-word;
                /* Pecah kata panjang */
            }



            .message-meta {
                display: flex;
                align-items: center;
                float: right;
                margin-top: 5px;
                margin-left: 15px;
                /* Beri jarak dari teks */
            }

            .message-time {
                font-size: 0.75em;
                color: #667781;
            }

            .message-status {
                font-size: 0.9em;
                margin-left: 5px;
                color: #8696a0;
            }

            .message-status .text-primary {
                color: #53bdeb !important;
            }

            /* Form Balasan */
            .reply-form-wrapper {
                padding: 0.5rem 1rem;
                border-top: 1px solid #ddd;
                background-color: #f0f2f5;
                flex-shrink: 0;
            }

            #message-input {
                border-radius: 20px;
                padding: 0.5rem 1rem;
                max-height: 120px;
                resize: none;
                overflow-y: auto !important;
                border: none;
            }

            #message-input:focus {
                box-shadow: none;
            }

            .btn-circle {
                width: 45px;
                height: 45px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 0;
                flex-shrink: 0;
            }

            /* Placeholder */
            .chat-placeholder {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                height: 100%;
                color: #777;
                text-align: center;
            }

            .chat-placeholder svg {
                width: 100px;
                height: 100px;
                margin-bottom: 1rem;
            }
        </style>

        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>WhatsApp Messenger</h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content p-0">
                    <div class="chat-container">
                        {{-- Kolom Kiri: Daftar Percakapan --}}
                        <div class="sidebar">
                            <div class="sidebar-header">
                                <h4>Percakapan</h4>
                            </div>
                            <div class="convo-list">
                                @forelse ($conversations as $convo)
                                    {{-- HTML DIPERBAIKI: Semua elemen dibungkus dengan benar di dalam <a> --}}
                                    <a href="{{ route('whatsapp.chat', ['phoneNumber' => $convo->phone_number]) }}"
                                        class="d-flex {{ isset($phoneNumber) && $convo->phone_number == $phoneNumber ? 'active' : '' }}"
                                        data-phone="{{ $convo->phone_number }}">
                                        <div class="convo-avatar">
                                            {{ strtoupper(substr($convo->contact_name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div class="convo-details">
                                            <div class="convo-name">{{ $convo->contact_name ?? $convo->phone_number }}</div>
                                            <small class="last-message">
                                                @if ($convo->direction == 'out')
                                                    <i class="fa-solid fa-check-double text-primary"></i>
                                                    <!-- Contoh Ikon Status -->
                                                @endif
                                                {{ Str::limit($convo->message, 25) }}
                                            </small>
                                        </div>
                                        <div class="convo-meta">
                                            <small
                                                class="last-message-time">{{ $convo->created_at->isToday() ? $convo->created_at->format('H:i') : $convo->created_at->format('d/m/y') }}</small>
                                            {{-- Placeholder untuk unread count, diisi oleh JS --}}
                                            <span class="badge badge-success badge-pill unread-badge mt-1"
                                                style="display: none;"></span>
                                        </div>
                                    </a>
                                @empty
                                    <div class="p-3 text-center text-muted">Belum ada percakapan.</div>
                                @endforelse
                            </div>
                        </div>

                        {{-- Kolom Kanan: Area Chat atau Placeholder --}}
                        <div class="main-chat">
                            @if (isset($messages))
                                {{-- Jika ada chat yang dipilih, tampilkan riwayat dan form balasan --}}
                                <div class="chat-header">
                                    {{ $contactName }}
                                </div>
                                <div class="chat-history" id="chat-history">
                                    @foreach ($messages as $message)
                                        <div class="message-wrapper {{ $message->direction == 'out' ? 'out' : 'in' }}">
                                            <div
                                                class="message {{ $message->direction == 'out' ? 'message-out' : 'message-in' }}">
                                                <div class="message-content">{!! nl2br(e($message->message)) !!}</div>
                                                <div class="message-meta">
                                                    <span
                                                        class="message-time">{{ $message->created_at->format('H:i') }}</span>
                                                    @if ($message->direction == 'out')
                                                        <span class="message-status" data-message-id="{{ $message->id }}">
                                                            @if ($message->status == 'sending')
                                                                <i class="fa-regular fa-clock"></i>
                                                            @endif
                                                            @if ($message->status == 'sent')
                                                                <i class="fa-solid fa-check"></i>
                                                            @endif
                                                            @if ($message->status == 'delivered')
                                                                <i class="fa-solid fa-check-double"></i>
                                                            @endif
                                                            @if ($message->status == 'read')
                                                                <i class="fa-solid fa-check-double text-primary"></i>
                                                            @endif
                                                            @if ($message->status == 'failed')
                                                                <i class="fa-solid fa-circle-exclamation text-danger"></i>
                                                            @endif
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="reply-form-wrapper">
                                    <form id="reply-form" action="{{ route('whatsapp.reply') }}" method="POST"
                                        class="d-flex align-items-center">
                                        @csrf
                                        <input type="hidden" name="phone_number" value="{{ $phoneNumber }}">
                                        <button type="button" class="btn btn-link btn-lg text-muted mr-2"><i
                                                class="fa-solid fa-paperclip"></i></button>
                                        <textarea id="message-input" name="message" class="form-control" placeholder="Ketik balasan..." rows="1" required></textarea>
                                        <button id="send-btn" class="btn btn-primary btn-circle ml-2" type="submit"><i
                                                class="fa-solid fa-paper-plane"></i></button>
                                    </form>
                                </div>
                            @else
                                {{-- Jika belum ada chat yang dipilih, tampilkan placeholder --}}
                                <div class="chat-placeholder">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="feather feather-message-square">
                                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                    </svg>
                                    <h4>Pilih percakapan untuk memulai</h4>
                                    <p>Riwayat chat Anda akan ditampilkan di sini.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('plugin')
    {{-- SCRIPT UNTUK FUNGSI FORM INPUT --}}
    @if (isset($messages))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const messageInput = document.getElementById('message-input');
                const form = document.getElementById('reply-form');
                const sendBtn = document.getElementById('send-btn');

                // Auto-scroll ke pesan terakhir saat halaman dimuat
                const chatHistory = document.getElementById("chat-history");
                if (chatHistory) {
                    chatHistory.scrollTop = chatHistory.scrollHeight;
                }

                // Auto-grow textarea
                messageInput.addEventListener('input', function() {
                    this.style.height = 'auto';
                    this.style.height = (this.scrollHeight) + 'px';
                });

                // Kirim form saat menekan Enter (bukan Shift+Enter)
                messageInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' && !e.shiftKey) {
                        e.preventDefault();
                        if (this.value.trim() !== '') {
                            // Memicu event 'submit' pada form
                            form.dispatchEvent(new Event('submit', {
                                cancelable: true
                            }));
                        }
                    }
                });

                // >>> PERUBAHAN DIMULAI DI SINI <<<
                // Tangani event submit form untuk mencegah pengiriman ganda
                form.addEventListener('submit', function(e) {
                    // Validasi sekali lagi untuk memastikan pesan tidak kosong
                    if (messageInput.value.trim() === '') {
                        e.preventDefault(); // Hentikan pengiriman jika kosong
                        return;
                    }

                    // Nonaktifkan tombol kirim dan textarea
                    sendBtn.disabled = true;
                    messageInput.disabled = true;

                    // Ganti ikon tombol kirim dengan spinner
                    sendBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';

                    // Lanjutkan dengan submit form.
                    // Setelah halaman dimuat ulang (post-redirect-get),
                    // form akan kembali dalam keadaan normal.
                    this.submit();
                });
                // >>> PERUBAHAN SELESAI DI SINI <<<
            });
        </script>
    @endif

    {{-- SCRIPT UNTUK REAL-TIME DENGAN LARAVEL ECHO --}}
    @auth
        <script>
            // Ambil nomor telepon yang sedang aktif dari variabel PHP
            const activeChatPhoneNumber = "{{ $phoneNumber ?? null }}";
            const chatHistoryEl = document.getElementById("chat-history");
            const convoListEl = document.querySelector('.convo-list');

            // Fungsi untuk membuat Ikon Status
            function getStatusIcon(status) {
                if (status === 'sending') return '<i class="fa-regular fa-clock"></i>';
                if (status === 'sent') return '<i class="fa-solid fa-check"></i>';
                if (status === 'delivered') return '<i class="fa-solid fa-check-double"></i>';
                if (status === 'read') return '<i class="fa-solid fa-check-double text-primary"></i>';
                if (status === 'failed') return '<i class="fa-solid fa-circle-exclamation text-danger"></i>';
                return '';
            }

            // Fungsi untuk menambahkan gelembung chat ke UI
            function appendMessageToUI(message) {
                if (!chatHistoryEl) return;

                const messageWrapper = document.createElement('div');
                messageWrapper.className = `message-wrapper ${message.direction === 'out' ? 'out' : 'in'}`;

                const statusHtml = message.direction === 'out' ?
                    `<span class="message-status" data-message-id="${message.id}">${getStatusIcon(message.status)}</span>` : '';

                messageWrapper.innerHTML = `
                <div class="message ${message.direction === 'out' ? 'message-out' : 'message-in'}">
                    <div class="message-content">${message.message.replace(/\n/g, '<br>')}</div>
                    <div class="message-meta">
                        <span class="message-time">${new Date(message.created_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}</span>
                        ${statusHtml}
                    </div>
                </div>`;

                chatHistoryEl.appendChild(messageWrapper);
                chatHistoryEl.scrollTop = chatHistoryEl.scrollHeight; // Auto-scroll
            }

            // Fungsi untuk mengupdate sidebar
            function updateSidebar(message) {
                if (!convoListEl) return;

                let convoItem = convoListEl.querySelector(`a[data-phone="${message.phone_number}"]`);

                if (convoItem) {
                    // Update elemen yang ada
                    const lastMessageEl = convoItem.querySelector('.last-message');
                    const lastTimeEl = convoItem.querySelector('.last-message-time');
                    let prefix = message.direction === 'out' ? `<i class="fa-solid fa-check mr-1"></i>` : '';

                    lastMessageEl.innerHTML = prefix + DOMPurify.sanitize(message.message.substring(0, 25) + (message.message
                        .length > 25 ? '...' : ''));
                    lastTimeEl.textContent = new Date(message.created_at).toLocaleTimeString('id-ID', {
                        hour: '2-digit',
                        minute: '2-digit'
                    });

                    // Pindahkan ke paling atas
                    convoListEl.prepend(convoItem);
                } else {
                    // TODO: Buat elemen percakapan baru jika belum ada di daftar
                    // Ini bisa dilakukan dengan me-render template Blade via AJAX atau membuat HTML string
                    window.location.reload(); // Solusi sementara: reload halaman jika ada percakapan baru
                }
            }

            // Mulai mendengarkan Private Channel
            if (window.Echo) {
                window.Echo.private('whatsapp-chat')
                    .listen('.message.new', (event) => {
                        console.log('Pesan baru diterima:', event.message);

                        // Update UI chat jika percakapan sedang aktif
                        if (activeChatPhoneNumber && event.message.phone_number === activeChatPhoneNumber) {
                            appendMessageToUI(event.message);
                        } else {
                            // Jika chat tidak aktif, mainkan suara notifikasi
                            // Anda perlu menyediakan file audio ini di folder public/sounds
                            // const notifSound = new Audio('/sounds/notification.mp3');
                            // notifSound.play().catch(e => console.error("Gagal memutar suara:", e));
                        }

                        // SELALU update sidebar untuk semua pesan baru
                        updateSidebar(event.message);
                    });
            }
        </script>
        {{-- Untuk keamanan, gunakan DOMPurify jika pesan bisa mengandung HTML --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/dompurify/3.0.8/purify.min.js"></script>
    @endauth
@endsection
