<?php

namespace App\Http\Controllers;

use App\Events\NewWhatsappMessage;
use App\Models\WhatsappMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class WhatsappController extends Controller
{
    // --- SATU METODE UNTUK MENAMPILKAN SEMUA HALAMAN CHAT ---

    /**
     * Menampilkan halaman chat.
     * Menggabungkan fungsi index() dan show() menjadi satu agar lebih efisien.
     *
     * @param string|null $phoneNumber
     */
    public function showChatPage($phoneNumber = null)
    {
        // 1. Ambil pesan terakhir dari setiap percakapan untuk sidebar (Query yang lebih baik)
        $subQuery = WhatsappMessage::select('phone_number', DB::raw('MAX(id) as last_message_id'))
            ->groupBy('phone_number');

        $conversations = WhatsappMessage::joinSub($subQuery, 'last_messages', function ($join) {
            $join->on('whatsapp_messages.id', '=', 'last_messages.last_message_id');
        })
            ->select('whatsapp_messages.*') // Ambil semua data dari pesan terakhir
            ->orderBy('whatsapp_messages.created_at', 'desc')
            ->get();

        $messages = null;
        $contactName = null;

        // 2. Jika ada nomor telepon yang dipilih dari URL, ambil riwayat pesannya
        if ($phoneNumber) {
            $messages = WhatsappMessage::where('phone_number', $phoneNumber)
                ->orderBy('created_at', 'asc')
                ->get();

            // Ambil nama kontak dari pesan pertama atau gunakan nomor telepon
            $contactName = $messages->first()->contact_name ?? $phoneNumber;
        }

        // 3. Kirim semua data yang relevan ke satu view yang sama
        return view('pages.whatsapp.index', compact('conversations', 'messages', 'phoneNumber', 'contactName'));
    }


    // --- METODE UNTUK MENGIRIM BALASAN ---

    /**
     * Mengirim balasan dari form di halaman chat.
     * SUDAH DIPERBAIKI: Menghilangkan duplikasi pembuatan pesan.
     */
    public function reply(Request $request)
    {
        $validated = $request->validate([
            'phone_number' => 'required|string',
            'message' => 'required|string',
        ]);

        // LANGSUNG PANGGIL HELPER. Helper _sendToNodeServer akan menangani
        // penyimpanan ke DB, broadcasting, dan pengiriman ke Node.js.
        $this->_sendToNodeServer($validated['phone_number'], $validated['message']);

        return back()->with('success', 'Balasan berhasil dikirim!');
    }

    /**
     * Mengirim pesan dari form lain (misalnya, broadcast).
     * Metode ini dipanggil oleh rute 'whatsapp.send'.
     */
    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            // 'number' bisa berisi satu nomor atau banyak nomor dipisah koma
            'number'  => 'required|string',
            'message' => 'required|string',
        ]);

        // Pecah string nomor menjadi array jika ada koma
        $numbers = explode(',', $validated['number']);

        foreach ($numbers as $number) {
            // Hilangkan spasi yang mungkin ada
            $cleaned_number = trim($number);

            if (!empty($cleaned_number)) {
                // Gunakan helper yang sudah ada untuk mengirim pesan
                $this->_sendToNodeServer($cleaned_number, $validated['message']);
            }
        }

        return back()->with('success', 'Pesan broadcast berhasil dimasukkan ke antrian pengiriman!');
    }


    // --- METODE UNTUK MENERIMA WEBHOOK (Sudah Benar) ---

    /**
     * Menerima webhook dari Node.js dan menyimpan pesan masuk/keluar.
     */
    public function processMessage(Request $request)
    {
        // ... (kode validasi header Anda bisa ditambahkan di sini) ...

        $content = $request->json()->all();
        Log::info('WhatsApp Webhook Received: ' . json_encode($content, JSON_PRETTY_PRINT));

        $eventData = $content['data'][0] ?? null;
        if (!$eventData) {
            return response()->json(['error' => 1, 'data' => 'No event data found'], 400);
        }

        $key = $eventData['key'] ?? [];
        $messageId = $key['id'] ?? null;
        if (!$messageId) {
            // Abaikan event tanpa message ID (seperti notifikasi 'typing')
            return response()->json(['status' => 'ignored', 'message' => 'No message ID']);
        }

        $isFromMe = $key['fromMe'] ?? false;
        $remoteJid = $key['remoteJid'] ?? '';
        $nomor = Str::before($remoteJid, '@');
        $msg = $eventData['message']['conversation'] ?? ($eventData['message']['extendedTextMessage']['text'] ?? '');

        if (empty($msg) || empty($nomor)) {
            return response()->json(['status' => 'ignored', 'message' => 'Empty message or number']);
        }

        $direction = $isFromMe ? 'out' : 'in';

        // Gunakan updateOrCreate untuk menangani pesan masuk DAN update status pesan keluar
        $savedMessage = WhatsappMessage::updateOrCreate(
            ['message_id' => $messageId],
            [
                'phone_number' => $nomor,
                'contact_name' => $eventData['pushName'] ?? ($isFromMe ? 'Admin' : 'Unknown'),
                'message' => $msg,
                'direction' => $direction,
                'status' => $eventData['status'] ?? 'read' // Ambil status dari event jika ada
            ]
        );

        broadcast(new NewWhatsappMessage($savedMessage))->toOthers();

        return response()->json(['status' => 'success', 'message' => 'Webhook processed']);
    }


    // --- PRIVATE HELPER (Sudah Benar) ---

    /**
     * Helper untuk mengirim pesan keluar.
     * Ini adalah satu-satunya tempat di mana pesan keluar dibuat di database.
     */
    private function _sendToNodeServer($number, $message, $filePath = null)
    {
        // 1. Simpan pesan keluar ke DB
        $outgoingMessage = WhatsappMessage::create([
            'phone_number' => $number,
            'message'      => $message,
            'direction'    => 'out',
            'status'       => 'sending', // Status awal
            'contact_name' => 'Admin',
        ]);

        // 2. Siarkan ke browser agar UI update instan
        broadcast(new NewWhatsappMessage($outgoingMessage))->toOthers();

        // 3. Kirim ke Node.js via cURL
        $headers = ['Key:KeyAbcKey', 'Nama:arul', 'Sandi:123###!!'];
        $httpData = ['number' => $number, 'message' => $message];

        if ($filePath) {
            $httpData['file_dikirim'] = new \CURLFile($filePath);
        }

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'http://192.168.0.100:3001/send-message', // Ganti dengan URL dari .env
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $httpData,
            CURLOPT_HTTPHEADER => $headers,
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);

        if ($error) {
            $outgoingMessage->update(['status' => 'failed']);
            Log::error('cURL Error to Node Server: ' . $error);
            return false;
        }

        // 4. Update status pesan berdasarkan respon dari Node.js
        $responseDecoded = json_decode($response, true);
        if (isset($responseDecoded['status']) && $responseDecoded['status'] === 'success') {
            $outgoingMessage->update([
                'status'     => 'sent',
                'message_id' => $responseDecoded['id'] ?? null,
            ]);
        } else {
            $outgoingMessage->update(['status' => 'failed']);
        }

        Log::info('Response from Node Server: ' . $response);
        return $response;
    }
}
