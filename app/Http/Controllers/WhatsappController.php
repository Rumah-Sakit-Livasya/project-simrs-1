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

    // =====================================================================
    // === PERBAIKAN UTAMA ADA DI processMessage DAN _sendToNodeServer ===
    // =====================================================================


    /**
     * Menerima webhook dari Node.js dan menyimpan pesan masuk/keluar.
     * SUDAH DIPERBAIKI untuk menangani 'gema' dan mencegah duplikasi.
     */
    public function processMessage(Request $request)
    {
        $content = $request->json()->all();
        Log::info('Webhook Diterima: ' . json_encode($content, JSON_PRETTY_PRINT));

        $eventData = $content['data'][0] ?? null;
        if (!$eventData) {
            return response()->json(['status' => 'ignored', 'message' => 'No event data']);
        }

        $key = $eventData['key'] ?? [];
        $messageId = $key['id'] ?? null;
        if (!$messageId) {
            return response()->json(['status' => 'ignored', 'message' => 'No message ID']);
        }

        $isFromMe = $key['fromMe'] ?? false;
        $nomor = Str::before($key['remoteJid'] ?? '', '@');
        $msg = $eventData['message']['conversation'] ?? ($eventData['message']['extendedTextMessage']['text'] ?? '');
        if (empty($msg) || empty($nomor)) {
            return response()->json(['status' => 'ignored', 'message' => 'Empty message or number']);
        }

        $savedMessage = null;

        if ($isFromMe) {
            // Ini adalah pesan KELUAR (baik dari HP atau 'gema' dari web)
            // Cari pesan yang cocok (isi sama, arah keluar, tujuan sama) yang baru saja dikirim dari web
            // dan belum memiliki message_id.
            $pendingMessage = WhatsappMessage::where('phone_number', $nomor)
                ->where('direction', 'out')
                ->where('message', $msg) // Cocokkan juga isi pesannya
                ->whereNull('message_id')
                ->where('created_at', '>=', now()->subMinutes(1)) // Hanya cari dalam 1 menit terakhir
                ->latest()
                ->first();

            if ($pendingMessage) {
                // KETEMU! Ini adalah 'gema'. Jangan buat baris baru, cukup UPDATE.
                $pendingMessage->update([
                    'message_id' => $messageId,
                    'status' => 'sent', // Update statusnya menjadi sent
                ]);
                $savedMessage = $pendingMessage;
                Log::info("Pesan keluar 'gema' berhasil dicocokkan dan diupdate. ID: " . $savedMessage->id);
                // Kita TIDAK melakukan broadcast di sini, karena statusnya akan diupdate secara visual.
                // Jika ingin broadcast status, event-nya harus berbeda.
            } else {
                // TIDAK KETEMU. Ini adalah pesan keluar yang benar-benar baru (dari HP).
                // Buat record baru seperti biasa.
                $savedMessage = WhatsappMessage::create([
                    'message_id'   => $messageId,
                    'phone_number' => $nomor,
                    'contact_name' => 'Admin',
                    'message'      => $msg,
                    'direction'    => 'out',
                    'status'       => $eventData['status'] ?? 'read',
                ]);
                // Siarkan pesan baru dari HP ini
                broadcast(new NewWhatsappMessage($savedMessage))->toOthers();
                Log::info("Pesan keluar baru dari HP dibuat. ID: " . $savedMessage->id);
            }
        } else {
            // Ini adalah pesan MASUK dari orang lain. Gunakan updateOrCreate.
            $savedMessage = WhatsappMessage::updateOrCreate(
                ['message_id' => $messageId],
                ['phone_number' => $nomor, 'contact_name' => $eventData['pushName'] ?? 'Unknown', 'message' => $msg, 'direction' => 'in', 'status' => 'read']
            );
            // Siarkan pesan masuk ini
            broadcast(new NewWhatsappMessage($savedMessage))->toOthers();
            Log::info("Pesan masuk baru dibuat/diupdate. ID: " . $savedMessage->id);
        }

        return response()->json(['status' => 'success', 'message' => 'Webhook processed']);
    }


    /**
     * Helper untuk mengirim pesan keluar.
     * SUDAH DIPERBAIKI: Tidak lagi mengupdate message_id.
     */
    private function _sendToNodeServer($number, $message, $filePath = null)
    {
        // 1. Simpan pesan keluar ke DB dengan status 'sending'
        $outgoingMessage = WhatsappMessage::create([
            'phone_number' => $number,
            'message'      => $message,
            'direction'    => 'out',
            'status'       => 'sending',
            'contact_name' => 'Admin',
        ]);

        // 2. Siarkan ke browser agar UI update instan (memunculkan pesan dengan ikon jam)
        broadcast(new NewWhatsappMessage($outgoingMessage))->toOthers();

        // 3. Kirim ke Node.js via cURL
        $headers = ['Key:KeyAbcKey', 'Nama:arul', 'Sandi:123###!!'];
        $httpData = ['number' => $number, 'message' => $message];
        // ... (kode cURL Anda) ...
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'http://192.168.0.100:3001/send-message',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $httpData,
            CURLOPT_HTTPHEADER => $headers,
        ]);
        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);
        // ... (akhir kode cURL) ...

        if ($error) {
            $outgoingMessage->update(['status' => 'failed']);
            Log::error('cURL Error to Node Server: ' . $error);
            return false;
        }

        // 4. TIDAK ADA LAGI UPDATE message_id DI SINI
        // Kita hanya mencatat log, dan membiarkan webhook `processMessage` menangani semuanya.
        Log::info('Response from Node Server: ' . $response);
        return $response;
    }
}
