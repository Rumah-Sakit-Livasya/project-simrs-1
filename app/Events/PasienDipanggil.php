<?php

namespace App\Events;

// Nama file Anda PasienDipanggil.php, maka nama class harus PasienDipanggil
// Bukan PanggilanAntrianDibuat
use App\Models\SIMRS\Registration;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PasienDipanggil implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    // Kita buat properti ini menjadi public agar bisa diakses
    public $registration;
    public $plasmaId;

    /**
     * Create a new event instance.
     */
    public function __construct(Registration $registration, $plasmaId)
    {
        $this->registration = $registration;
        $this->plasmaId = $plasmaId;
    }

    // ====================================================================
    // TAMBAHKAN METHOD INI - Ini adalah bagian yang paling penting
    // ====================================================================
    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        // Pastikan relasi 'departement' sudah dimuat sebelumnya di controller.
        // Jika belum, muat di sini untuk keamanan.
        $this->registration->loadMissing('departement');

        return [
            // Kita akan mengirim seluruh objek registration yang sudah berisi departement
            'registration' => $this->registration,
        ];
    }
    // ====================================================================


    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('plasma-channel.' . $this->plasmaId),
        ];
    }

    /**
     * The name of the event to broadcast.
     */
    public function broadcastAs()
    {
        return 'pasien.dipanggil';
    }
}
