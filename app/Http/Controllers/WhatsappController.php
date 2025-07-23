<?php

namespace App\Http\Controllers;

use App\Events\NewWhatsappMessage;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\WhatsappMessage; // BARU: Menggunakan model kita
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

class WhatsappController extends Controller
{
    // --- BARU: Metode untuk menampilkan halaman chat ---

    public function chatPage($phoneNumber = null)
    {
        // Selalu ambil daftar percakapan untuk sidebar
        $conversations = WhatsappMessage::select(/*...query Anda...*/)->get();

        $messages = null;
        $contactName = null;

        if ($phoneNumber) {
            // Jika ada phoneNumber, ambil pesan dan nama kontak
            $messages = WhatsappMessage::where('phone_number', $phoneNumber)->orderBy('created_at', 'asc')->get();
            $contactName = $messages->first()->contact_name ?? $phoneNumber;
        }

        return view('pages.whatsapp.index', compact('conversations', 'messages', 'phoneNumber', 'contactName'));
    }

    /**
     * Menampilkan halaman utama dengan daftar percakapan.
     * (Dipanggil oleh route 'whatsapp.index')
     */
    public function index()
    {
        $conversations = WhatsappMessage::select('phone_number', 'contact_name', DB::raw('MAX(created_at) as last_message_at'))
            ->groupBy('phone_number', 'contact_name')
            ->orderBy('last_message_at', 'desc')
            ->get();

        // Penting: Render view yang sama dengan method show, tapi tanpa $messages
        // Ganti 'pages.whatsapp.index' dengan path view Anda yang benar.
        return view('pages.whatsapp.index', compact('conversations'));
    }

    /**
     * Menampilkan riwayat chat dengan nomor tertentu.
     * (Dipanggil oleh route 'whatsapp.chat')
     */
    public function show($phoneNumber)
    {
        // Ambil semua pesan untuk nomor ini
        $messages = WhatsappMessage::where('phone_number', $phoneNumber)
            ->orderBy('created_at', 'asc')
            ->get();

        // Ambil daftar percakapan untuk sidebar (kode ini perlu di method show juga)
        $conversations = WhatsappMessage::select('phone_number', 'contact_name', DB::raw('MAX(created_at) as last_message_at'))
            ->groupBy('phone_number', 'contact_name')
            ->orderBy('last_message_at', 'desc')
            ->get();

        $contactName = $messages->first()->contact_name ?? $phoneNumber;

        // Penting: Render view yang sama, sekarang dengan SEMUA variabel
        // Ganti 'pages.whatsapp.index' dengan path view Anda yang benar.
        return view('pages.whatsapp.index', compact('messages', 'conversations', 'phoneNumber', 'contactName'));
    }

    // --- BARU: Metode untuk mengirim balasan dari halaman chat ---

    /**
     * Mengirim balasan dari form di halaman chat.
     */
    public function reply(Request $request)
    {
        $validated = $request->validate([
            'phone_number' => 'required|string',
            'message' => 'required|string',
        ]);

        // 1. Simpan pesan keluar ke database
        $message = WhatsappMessage::create([
            'phone_number' => $validated['phone_number'],
            'message' => $validated['message'],
            'direction' => 'out',
            'status' => 'sending',
        ]);

        // 2. Kirim ke server Node.js
        $this->_sendToNodeServer($validated['phone_number'], $validated['message']);

        return back()->with('success', 'Balasan berhasil dikirim!');
    }

    // --- MODIFIKASI: Menerima webhook dan menyimpan pesan masuk ---

    // di WhatsappController.php
    public function processMessage(Request $request)
    {
        // ... (kode validasi header Anda) ...
        $content = $request->json()->all();
        Log::info('WhatsApp Webhook Received: ' . json_encode($content, JSON_PRETTY_PRINT));

        // Ambil data dari event pertama di array 'data'
        $eventData = $content['data'][0] ?? ($content['data'][1] ?? null);
        if (!$eventData) {
            return response()->json(['error' => 1, 'data' => 'No event data found'], 400);
        }

        $key = $eventData['key'] ?? [];
        $messageId = $key['id'] ?? null;
        $isFromMe = $key['fromMe'] ?? false; // <-- PENTING
        $remoteJid = $key['remoteJid'] ?? '';
        $nomor = Str::before($remoteJid, '@');

        $msg = $eventData['message']['extendedTextMessage']['text'] ??
            ($eventData['message']['conversation'] ?? '');

        // Abaikan jika tidak ada pesan atau nomor
        if (empty($msg) || empty($nomor)) {
            return response()->json(['status' => 'ignored', 'message' => 'No message or number']);
        }

        // Tentukan arah pesan berdasarkan `isFromMe`
        $direction = $isFromMe ? 'out' : 'in';

        $savedMessage = WhatsappMessage::updateOrCreate(
            ['message_id' => $messageId],
            [
                'phone_number' => $nomor,
                // Jika dari kita, mungkin tidak ada pushName
                'contact_name' => $eventData['pushName'] ?? ($isFromMe ? 'Admin' : 'Unknown'),
                'message' => $msg,
                'direction' => $direction, // <-- Menggunakan arah yang dinamis
                'status' => 'read'
            ]
        );

        // Siarkan event ke browser
        broadcast(new NewWhatsappMessage($savedMessage))->toOthers();

        return response()->json(['status' => 'success', 'message' => 'Webhook processed']);
    }

    // --- PRIVATE HELPER: Fungsi untuk mengirim pesan via cURL ---

    /**
     * Mengirim pesan ke server Node.js dan mencatatnya di database.
     *
     * @param string $number Nomor tujuan
     * @param string $message Isi pesan
     * @param string|null $filePath Path ke file (jika ada)
     * @return bool|string Respon dari server Node.js atau false jika gagal.
     */
    private function _sendToNodeServer($number, $message, $filePath = null)
    {
        // =====================================================================
        // LANGKAH 1: SIMPAN PESAN KELUAR KE DATABASE KITA TERLEBIH DAHULU
        // =====================================================================
        // Ini sangat penting agar pesan yang kita kirim langsung muncul di UI.
        $outgoingMessage = WhatsappMessage::create([
            'phone_number' => $number,
            'message'      => $message,
            'direction'    => 'out', // Arahnya 'out' (keluar)
            'status'       => 'sending', // Status awal 'sending'
            'contact_name' => 'Admin', // Atau nama user yang sedang login
            // message_id akan null untuk pesan keluar, ini tidak masalah
        ]);


        // =====================================================================
        // LANGKAH 2: SIARKAN PESAN KELUAR INI KE BROWSER
        // =====================================================================
        // Ini akan membuat pesan langsung muncul di layar pengirim DAN di layar
        // admin lain yang mungkin sedang membuka chat yang sama.
        broadcast(new NewWhatsappMessage($outgoingMessage))->toOthers();


        // =====================================================================
        // LANGKAH 3: KIRIM PESAN KE SERVER NODE.JS (Kode Asli Anda)
        // =====================================================================
        $headers = ['Key:KeyAbcKey', 'Nama:arul', 'Sandi:123###!!'];
        $httpData = ['number' => $number, 'message' => $message];

        if ($filePath) {
            $httpData['file_dikirim'] = new \CURLFile($filePath);
        }

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

        if ($error) {
            // Jika cURL gagal, update status pesan di DB menjadi 'failed'
            $outgoingMessage->update(['status' => 'failed']);
            Log::error('cURL Error to Node Server: ' . $error);
            return false;
        }


        // =====================================================================
        // LANGKAH 4: UPDATE STATUS PESAN BERDASARKAN RESPON DARI NODE.JS
        // =====================================================================
        $responseDecoded = json_decode($response, true);
        if (isset($responseDecoded['status']) && $responseDecoded['status'] === 'success') {
            // Jika Node.js bilang sukses, update status pesan menjadi 'sent'
            // dan simpan message_id dari WhatsApp jika ada.
            $outgoingMessage->update([
                'status'     => 'sent',
                'message_id' => $responseDecoded['id'] ?? null,
            ]);
        } else {
            // Jika Node.js bilang gagal, update status menjadi 'failed'
            $outgoingMessage->update(['status' => 'failed']);
        }

        Log::info('Response from Node Server: ' . $response);
        return $response;
    }
}
