<?php

namespace App\Events;

use App\Models\WhatsappMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel; // Gunakan PrivateChannel
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewWhatsappMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    // Buat properti ini public agar bisa diakses oleh JavaScript
    public WhatsappMessage $message;

    public function __construct(WhatsappMessage $message)
    {
        $this->message = $message;
    }

    /**
     * Tentukan channel broadcast. Kita gunakan PrivateChannel agar aman.
     */
    public function broadcastOn(): array
    {
        // Channel ini bersifat umum untuk semua admin yang login.
        // Nama 'whatsapp-chat' harus sama dengan yang didengarkan di JavaScript.
        return [
            new PrivateChannel('whatsapp-chat'),
        ];
    }

    /**
     * Tentukan nama event yang akan didengar oleh Echo.
     */
    public function broadcastAs(): string
    {
        return 'message.new';
    }
}
