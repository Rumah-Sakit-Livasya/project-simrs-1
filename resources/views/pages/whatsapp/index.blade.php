@extends('inc.layout')
@section('title', 'WhatsApp Chat')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        {{-- Custom CSS untuk halaman chat --}}
        <style>
            .chat-container {
                display: flex;
                height: 80vh;
                /* Sesuaikan tinggi sesuai kebutuhan */
                border: 1px solid #ddd;
                background-color: #fff;
            }

            .sidebar {
                width: 35%;
                max-width: 350px;
                border-right: 1px solid #ddd;
                display: flex;
                flex-direction: column;
            }

            .sidebar-header {
                padding: 1rem;
                border-bottom: 1px solid #ddd;
                background-color: #f8f9fa;
            }

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
                gap: 1rem;
            }

            .convo-list a:hover {
                background-color: #f8f9fa;
            }

            .convo-list a.active {
                background-color: #e9ecef;
            }

            .convo-avatar {
                width: 40px;
                height: 40px;
                background-color: #007bff;
                color: white;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: bold;
            }

            .convo-details {
                overflow: hidden;
            }

            .convo-name {
                font-weight: 600;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .main-chat {
                width: 100%;
                display: flex;
                flex-direction: column;
                background-color: #e5ddd5;
                /* Latar belakang seperti WA */
            }

            .chat-header {
                padding: 1rem;
                border-bottom: 1px solid #ddd;
                font-weight: bold;
                background-color: #f8f9fa;
            }

            .chat-history {
                flex-grow: 1;
                padding: 1rem;
                overflow-y: auto;
            }

            .message {
                max-width: 70%;
                padding: 0.5rem 1rem;
                border-radius: 8px;
                margin-bottom: 10px;
                line-height: 1.4;
                box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);
            }

            .message-in {
                background-color: #fff;
                align-self: flex-start;
            }

            .message-out {
                background-color: #dcf8c6;
                align-self: flex-end;
            }

            .message-time {
                font-size: 0.75em;
                color: #999;
                text-align: right;
                margin-top: 5px;
            }

            .reply-form-wrapper {
                padding: 1rem;
                border-top: 1px solid #ddd;
                background-color: #f0f0f0;
            }

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
                <h2>
                    WhatsApp Messenger
                </h2>
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
                                    <a href="{{ route('whatsapp.chat', ['phoneNumber' => $convo->phone_number]) }}"
                                        class="{{ isset($phoneNumber) && $convo->phone_number == $phoneNumber ? 'active' : '' }}">
                                        <div class="convo-avatar">
                                            {{ strtoupper(substr($convo->contact_name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div class="convo-details">
                                            <div class="convo-name">{{ $convo->contact_name ?? $convo->phone_number }}</div>
                                            <small>Terakhir:
                                                {{ \Carbon\Carbon::parse($convo->last_message_at)->diffForHumans() }}</small>
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
                                        <div
                                            style="display: flex; justify-content: {{ $message->direction == 'out' ? 'flex-end' : 'flex-start' }};">
                                            <div
                                                class="message {{ $message->direction == 'out' ? 'message-out' : 'message-in' }}">
                                                <div>{!! nl2br(e($message->message)) !!}</div>
                                                <div class="message-time">{{ $message->created_at->format('H:i') }}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="reply-form-wrapper">
                                    <form action="{{ route('whatsapp.reply') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="phone_number" value="{{ $phoneNumber }}">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="message"
                                                placeholder="Ketik balasan..." autocomplete="off" required>
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="submit">Kirim</button>
                                            </div>
                                        </div>
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
    <script>
        // Script auto-scroll yang sudah ada
        document.addEventListener("DOMContentLoaded", function() {
            var chatHistory = document.getElementById("chat-history");
            if (chatHistory) {
                chatHistory.scrollTop = chatHistory.scrollHeight;
            }
        });
    </script>

    {{-- HANYA JALANKAN JIKA USER LOGIN --}}
    @auth
        <script>
            // Fungsi untuk membuat dan menambahkan gelembung chat ke layar
            function appendMessageToUI(message) {
                const chatHistory = document.getElementById("chat-history");
                if (!chatHistory) return; // Keluar jika pengguna tidak sedang di halaman chat

                const messageClass = message.direction === 'out' ? 'message-out' : 'message-in';
                const justifyContent = message.direction === 'out' ? 'flex-end' : 'flex-start';

                const messageWrapper = document.createElement('div');
                messageWrapper.style.display = 'flex';
                messageWrapper.style.justifyContent = justifyContent;

                // Menggunakan backtick (`) untuk template literal yang lebih mudah dibaca
        messageWrapper.innerHTML = `
                                <div class="message ${messageClass}">
                                    <div>${message.message.replace(/\n/g, '<br>')}</div>
                                    <div class="message-time">${new Date(message.created_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}</div>
                                </div>
                            `;

                chatHistory.appendChild(messageWrapper);
                chatHistory.scrollTop = chatHistory.scrollHeight; // Auto-scroll
            }

            // Ambil nomor telepon yang sedang aktif dari variabel PHP
            const activeChatPhoneNumber = "{{ $phoneNumber ?? null }}";

            // Mulai mendengarkan Private Channel 'whatsapp-chat'
            window.Echo.private('whatsapp-chat')
                .listen('.message.new', (event) => {
                    console.log('Pesan baru diterima dari Echo:', event.message);

                    // LOGIKA UTAMA:
                    // Hanya tambahkan pesan ke UI jika pesan tersebut milik percakapan yang sedang dibuka.
                    if (activeChatPhoneNumber && event.message.phone_number === activeChatPhoneNumber) {
                        appendMessageToUI(event.message);
                    }

                    // TODO (Opsional):
                    // 1. Tampilkan notifikasi "toast" untuk pesan yang masuk tapi tidak sedang dibuka.
                    // 2. Perbarui daftar percakapan di sidebar (update "Terakhir: ...").
                    // 3. Mainkan suara notifikasi.
                    // const notifSound = new Audio('/sounds/notification.mp3');
                    // notifSound.play();
                });
        </script>
    @endauth
@endsection
