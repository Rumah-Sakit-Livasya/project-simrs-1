<?php

namespace App\Http\Controllers\API;

use App\Events\PasienDipanggil;
use App\Http\Controllers\Controller;
use App\Models\SIMRS\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Pusher\Pusher;

class AntrianController extends Controller
{
    public function panggilPasien(Request $request)
    {
        $validated = $request->validate([
            'registration_id' => 'required|exists:registrations,id',
            'plasma_id' => 'required|exists:plasma_display_rawat_jalans,id',
        ]);

        try {
            $registration = Registration::with('departement')->findOrFail($validated['registration_id']);
            $plasmaId = $validated['plasma_id'];

            // (Opsional) Update status di database untuk pencatatan
            $registration->update(['status_panggilan' => 'calling', 'waktu_panggil' => now()]);

            // =================================================================
            // KIRIM EVENT SECARA MANUAL (METODE YANG SUDAH TERBUKTI)
            // =================================================================
            $options = [
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'useTLS' => true,
                'curl_options' => [ // Tetap gunakan ini untuk keamanan
                    CURLOPT_SSL_VERIFYHOST => 0,
                    CURLOPT_SSL_VERIFYPEER => 0,
                ],
            ];

            // Buat instance Pusher
            $pusher = new Pusher(
                env('PUSHER_APP_KEY'),
                env('PUSHER_APP_SECRET'),
                env('PUSHER_APP_ID'),
                $options
            );

            // Siapkan data payload. Strukturnya harus cocok dengan yang diharapkan JavaScript.
            $data = [
                'registration' => $registration, // Kirim seluruh objek registration
            ];

            // Tentukan channel dan nama event
            $channelName = 'plasma-channel.' . $plasmaId;
            $eventName = 'pasien.dipanggil';

            // Kirim event!
            $pusher->trigger($channelName, $eventName, $data);

            Log::info("Event manual berhasil dikirim ke Pusher di channel: " . $channelName);

            // =================================================================

            return response()->json([
                'success' => true,
                'message' => 'Sinyal panggilan terkirim via metode manual.',
            ]);
        } catch (\Exception $e) {
            Log::error("GAGAL MENGIRIM EVENT PUSHER (MANUAL): " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim sinyal ke plasma. Periksa log server.'
            ], 500);
        }
    }
}
