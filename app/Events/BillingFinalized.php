<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\SIMRS\Bilingan;

class BillingFinalized
{


    use Dispatchable, InteractsWithSockets, SerializesModels;
    use Dispatchable, SerializesModels;

    public $billing;

    public function __construct(Bilingan $billing)
    {
       $this->billing = $billing;
        Log::info('--- BillingFinalized Event CONSTRUCTOR CALLED --- Bilingan ID: ' . $billing->id . ' --- Timestamp: ' . now());
    }
    /**
     * Create a new event instance.
     */


    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
