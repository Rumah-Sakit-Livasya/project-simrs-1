<?php

namespace App\Events;

use App\Models\WhatsappMessage; // Pastikan namespace model Anda benar
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Data pesan yang akan di-broadcast.
     * Kita buat array agar lebih fleksibel dan hanya mengirim data yang perlu.
     *
     * @var array
     */
    public $message;

    /**
     * Create a new event instance.
     *
     * @param \App\Models\WhatsappMessage $messageModel
     */
    public function __construct(WhatsappMessage $messageModel)
    {
        $this->message = [
            'id' => $messageModel->id,
            'status' => $messageModel->status,
            'phone_number' => $messageModel->phone_number, // Sertakan ini untuk logika lain jika perlu
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // Broadcast ke channel privat yang sama dengan pesan baru
        return new PrivateChannel('whatsapp-chat');
    }

    /**
     * Nama alias untuk event ini di sisi client (JavaScript).
     * Ini penting agar kita bisa membedakannya dari event '.message.new'.
     */
    public function broadcastAs()
    {
        return 'message.status.updated';
    }
}
