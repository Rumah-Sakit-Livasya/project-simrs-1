@extends('inc.blank') {{-- <-- INI PERUBAHAN PALING PENTING --}}
@section('title', 'WhatsApp Messenger')

@section('extended-css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        /* CSS ini sudah benar dan tidak perlu diubah lagi */
        .chat-container {
            display: flex;
            width: 100%;
            height: 100%;
        }

        .sidebar {
            width: 30%;
            max-width: 420px;
            min-width: 300px;
            border-right: 1px solid #ddd;
            display: flex;
            flex-direction: column;
            background-color: #fff;
        }

        .main-chat {
            width: 70%;
            display: flex;
            flex-direction: column;
            background-color: #e5ddd5;
            background-image: url('https://user-images.githubusercontent.com/15075759/28719144-86dc0f70-73b1-11e7-911d-60d70fcded21.png');
        }

        .sidebar-header,
        .chat-header {
            padding: 10px 16px;
            border-bottom: 1px solid #ddd;
            background-color: #f0f2f5;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .chat-header {
            font-weight: 500;
        }

        .sidebar-header h4 {
            margin: 0;
            font-size: 1.1rem;
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

        .chat-history {
            flex-grow: 1;
            padding: 1rem 5%;
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

        .message-content {
            word-wrap: break-word;
        }

        .message-meta {
            display: flex;
            align-items: center;
            float: right;
            margin-top: 5px;
            margin-left: 15px;
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
            color: #ccc;
        }
    </style>
@endsection

@section('content')
    {{-- Kode HTML ini sudah benar --}}
    <div class="chat-container">
        {{-- Kolom Kiri: Daftar Percakapan --}}
        <div class="sidebar">
            <div class="sidebar-header">
                <h4>Percakapan</h4>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-xs" title="Kembali ke Dashboard">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </a>
            </div>
            <div class="convo-list">
                @forelse ($conversations as $convo)
                    <a href="{{ route('whatsapp.chat', ['phoneNumber' => $convo->phone_number]) }}"
                        class="d-flex {{ isset($phoneNumber) && $convo->phone_number == $phoneNumber ? 'active' : '' }}"
                        data-phone="{{ $convo->phone_number }}">
                        <div class="convo-avatar">{{ strtoupper(substr($convo->contact_name ?? 'U', 0, 1)) }}</div>
                        <div class="convo-details">
                            <div class="convo-name">{{ $convo->contact_name ?? $convo->phone_number }}</div>
                            <small class="last-message">
                                @if ($convo->direction == 'out')
                                    <i
                                        class="fa-solid {{ $convo->status == 'read' ? 'fa-check-double text-primary' : 'fa-check-double' }}"></i>
                                @endif
                                {{ Str::limit($convo->message, 25) }}
                            </small>
                        </div>
                        <div class="convo-meta">
                            <small
                                class="last-message-time">{{ $convo->created_at->isToday() ? $convo->created_at->format('H:i') : $convo->created_at->format('d/m/y') }}</small>
                            <span class="badge badge-success badge-pill unread-badge mt-1" style="display: none;"></span>
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
                <div class="chat-header">{{ $contactName }}</div>
                <div class="chat-history" id="chat-history">
                    @foreach ($messages as $message)
                        <div class="message-wrapper {{ $message->direction == 'out' ? 'out' : 'in' }}"
                            data-db-id="{{ $message->id }}">
                            <div class="message {{ $message->direction == 'out' ? 'message-out' : 'message-in' }}">
                                <div class="message-content">{!! nl2br(e($message->message)) !!}</div>
                                <div class="message-meta">
                                    <span class="message-time">{{ $message->created_at->format('H:i') }}</span>
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
                <div class="chat-placeholder">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 3.34a10 10 0 1 1-14.995 8.984L2 22l8.66-8.66a10 10 0 0 1 6.34-1.996z"></path>
                    </svg>
                    <h4>WhatsApp Web</h4>
                    <p>Kirim dan terima pesan tanpa harus menghubungkan telepon Anda.</p>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('plugin')
    {{-- SCRIPT UNTUK FUNGSI FORM INPUT DAN REAL-TIME --}}
    @if (isset($messages))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // =============================================================
                // BAGIAN 1: FUNGSI DASAR & INTERAKSI FORM
                // =============================================================
                const messageInput = document.getElementById('message-input');
                const form = document.getElementById('reply-form');
                const chatHistory = document.getElementById('chat-history');

                // Auto-scroll ke pesan terakhir saat halaman dimuat
                if (chatHistory) {
                    chatHistory.scrollTop = chatHistory.scrollHeight;
                }

                // Auto-resize textarea
                if (messageInput) {
                    messageInput.addEventListener('input', function() {
                        this.style.height = 'auto';
                        this.style.height = (this.scrollHeight) + 'px';
                    });

                    // Kirim form dengan Enter, buat baris baru dengan Shift+Enter
                    messageInput.addEventListener('keydown', function(e) {
                        if (e.key === 'Enter' && !e.shiftKey) {
                            e.preventDefault();
                            if (this.value.trim() !== '') {
                                form.submit();
                                this.disabled = true; // Nonaktifkan input setelah dikirim
                            }
                        }
                    });
                }

                // Cegah pengiriman form kosong
                if (form) {
                    form.addEventListener('submit', function(e) {
                        if (messageInput.value.trim() === '') {
                            e.preventDefault();
                        } else {
                            document.getElementById('send-btn').disabled = true; // Nonaktifkan tombol kirim
                        }
                    });
                }

                // =============================================================
                // BAGIAN 2: LOGIKA REAL-TIME DENGAN LARAVEL ECHO
                // =============================================================
                const activeChatPhoneNumber = "{{ $phoneNumber ?? null }}";

                // ---------------------------------
                // Fungsi Helper untuk memanipulasi UI
                // ---------------------------------

                function getStatusIconHtml(status) {
                    if (status === 'sending') return '<i class="fa-regular fa-clock"></i>';
                    if (status === 'sent') return '<i class="fa-solid fa-check"></i>';
                    if (status === 'delivered') return '<i class="fa-solid fa-check-double"></i>';
                    if (status === 'read') return '<i class="fa-solid fa-check-double text-primary"></i>';
                    if (status === 'failed') return '<i class="fa-solid fa-circle-exclamation text-danger"></i>';
                    return ''; // Default
                }

                function appendMessageToUI(message) {
                    // Hanya tambahkan pesan ke UI jika nomor teleponnya cocok dengan chat yang sedang dibuka
                    if (message.phone_number !== activeChatPhoneNumber) {
                        console.log(
                            `Pesan untuk ${message.phone_number} diterima, tapi tidak ditampilkan di chat aktif.`);
                        return; // Abaikan jika bukan untuk chat ini
                    }

                    const messageDirection = message.direction === 'out' ? 'out' : 'in';
                    const messageClass = message.direction === 'out' ? 'message-out' : 'message-in';

                    // Buat elemen status hanya jika pesan keluar
                    const statusHtml = message.direction === 'out' ?
                        `<span class="message-status" data-message-id="${message.id}">${getStatusIconHtml(message.status)}</span>` :
                        '';

                    const messageHtml = `
                        <div class="message-wrapper ${messageDirection}" data-db-id="${message.id}">
                            <div class="message ${messageClass}">
                                <div class="message-content">${message.message.replace(/\n/g, '<br>')}</div>
                                <div class="message-meta">
                                    <span class="message-time">${new Date(message.created_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}</span>
                                    ${statusHtml}
                                </div>
                            </div>
                        </div>
                    `;

                    chatHistory.insertAdjacentHTML('beforeend', messageHtml);
                    chatHistory.scrollTop = chatHistory.scrollHeight; // Auto-scroll
                }

                function updateMessageStatusUI(messageId, newStatus) {
                    const statusElement = document.querySelector(`.message-status[data-message-id="${messageId}"]`);
                    if (statusElement) {
                        statusElement.innerHTML = getStatusIconHtml(newStatus);
                        console.log(`[UI-UPDATE] Status untuk pesan ID ${messageId} diubah menjadi ${newStatus}`);
                    }
                }

                function updateSidebar(message) {
                    const convoLink = document.querySelector(`.convo-list a[data-phone="${message.phone_number}"]`);
                    if (convoLink) {
                        // Update last message
                        const lastMessageEl = convoLink.querySelector('.last-message');
                        if (lastMessageEl) {
                            lastMessageEl.innerHTML = (message.direction === 'out' ?
                                    '<i class="fa-solid fa-check-double text-primary"></i>' : '') + message.message
                                .substring(0, 25) + '...';
                        }
                        // Update time
                        const timeEl = convoLink.querySelector('.last-message-time');
                        if (timeEl) {
                            timeEl.textContent = new Date(message.created_at).toLocaleTimeString('id-ID', {
                                hour: '2-digit',
                                minute: '2-digit'
                            });
                        }
                        // Pindahkan percakapan ke paling atas
                        convoLink.parentElement.prepend(convoLink);
                    } else {
                        // Jika percakapan belum ada, muat ulang halaman untuk menampilkannya
                        // Ini adalah cara sederhana, bisa dioptimalkan dengan membuat elemen baru via JS
                        window.location.reload();
                    }
                }

                // ---------------------------------
                // Koneksi dan Listener Laravel Echo
                // ---------------------------------
                if (typeof window.Echo === 'undefined') {
                    console.log('Laravel Echo tidak ditemukan! Pastikan bootstrap.js sudah dimuat.');
                    return;
                }

                console.log('Mencoba subscribe ke channel: private-whatsapp-chat');
                const channel = window.Echo.private('whatsapp-chat');

                channel.error((error) => {
                    console.log("Gagal subscribe ke channel:", error);
                });

                channel.subscribed(() => {
                    console.log("Berhasil subscribe ke channel private-whatsapp-chat!");
                });

                // Listener untuk PESAN BARU (baik masuk maupun keluar)
                channel.listen('.NewWhatsappMessage', (event) => {
                    console.log('[EVENT: PESAN BARU]', event.message);
                    appendMessageToUI(event.message);
                    updateSidebar(event.message);
                });

                // Listener untuk UPDATE STATUS PESAN KELUAR
                channel.listen('.MessageStatusUpdated', (event) => {
                    console.log('[EVENT: STATUS UPDATE]', event.message);
                    updateMessageStatusUI(event.message.id, event.message.status);
                    // Anda juga bisa update status di sidebar jika diperlukan
                });
            });
        </script>
    @endif

    @auth
        {{-- DOMPurify tidak lagi diperlukan karena kita tidak menggunakan innerHTML dari sumber eksternal --}}
        {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/dompurify/3.0.8/purify.min.js"></script> --}}
    @endauth
@endsection
