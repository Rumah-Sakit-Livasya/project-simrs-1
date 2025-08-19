<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\WebhookLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class WebhookController extends Controller
{
    /**
     * Menerima dan memproses webhook dari WhatsApp.
     */
    public function handleWhatsapp(Request $request)
    {
        // Ambil semua data JSON sebagai array
        $payload = $request->all();

        // Gunakan Log untuk debugging, sangat berguna saat development
        Log::info('Webhook WhatsApp Diterima:', $payload);

        try {
            // Ekstrak data dari payload yang bersarang
            // Gunakan null coalescing operator (??) untuk menghindari error jika key tidak ada
            $messageId = $payload['key']['id'] ?? null;

            // Jika tidak ada ID pesan, hentikan proses
            if (!$messageId) {
                return response()->json(['status' => 'error', 'message' => 'Message ID tidak ditemukan'], 400);
            }

            // Cek apakah pesan sudah ada untuk menghindari duplikat
            if (WebhookLog::where('message_id', $messageId)->exists()) {
                return response()->json(['status' => 'ignored', 'message' => 'Pesan duplikat diabaikan'], 200);
            }

            $sessionId = $payload['sessionId'] ?? null;
            $senderJid = $payload['key']['participant'] ?? $payload['key']['remoteJid'] ?? null;
            $groupJid = (str_contains($payload['key']['remoteJid'], '@g.us')) ? $payload['key']['remoteJid'] : null;
            $senderName = $payload['pushName'] ?? null;
            $messageText = $payload['message']['extendedTextMessage']['text'] ?? null;

            // Konversi Unix timestamp ke format datetime
            $messageTimestamp = isset($payload['messageTimestamp'])
                ? Carbon::createFromTimestamp($payload['messageTimestamp'])
                : null;

            // Simpan ke database
            WebhookLog::create([
                'session_id'        => $sessionId,
                'message_id'        => $messageId,
                'sender_jid'        => $senderJid,
                'group_jid'         => $groupJid,
                'sender_name'       => $senderName,
                'message_text'      => $messageText,
                'message_timestamp' => $messageTimestamp,
                'full_payload'      => $payload, // Simpan payload asli (Laravel akan handle encoding ke JSON)
            ]);

            // Kirim respon sukses
            return response()->json(['status' => 'success', 'message' => 'Webhook berhasil diproses'], 200);
        } catch (\Exception $e) {
            // Jika terjadi error, catat di log dan kirim respon error
            Log::error('Gagal memproses webhook WhatsApp: ' . $e->getMessage(), ['payload' => $payload]);
            return response()->json(['status' => 'error', 'message' => 'Terjadi kesalahan internal'], 500);
        }
    }
}
