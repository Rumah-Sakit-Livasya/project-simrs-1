<?php

namespace App\Http\Controllers;

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

    /**
     * Menampilkan daftar percakapan.
     */
    public function index()
    {
        // Ambil pesan terakhir dari setiap percakapan
        $conversations = WhatsappMessage::select('phone_number', 'contact_name', DB::raw('MAX(created_at) as last_message_at'))
            ->groupBy('phone_number', 'contact_name')
            ->orderBy('last_message_at', 'desc')
            ->get();

        return view('pages.whatsapp.index', compact('conversations'));
    }

    /**
     * Menampilkan riwayat chat dengan nomor tertentu.
     */
    public function show($phoneNumber)
    {
        // Ambil semua pesan untuk nomor ini, urutkan dari yang terlama
        $messages = WhatsappMessage::where('phone_number', $phoneNumber)
            ->orderBy('created_at', 'asc')
            ->get();

        // Ambil daftar percakapan untuk sidebar
        $conversations = WhatsappMessage::select('phone_number', 'contact_name', DB::raw('MAX(created_at) as last_message_at'))
            ->groupBy('phone_number', 'contact_name')
            ->orderBy('last_message_at', 'desc')
            ->get();

        $contactName = $messages->first()->contact_name ?? $phoneNumber;

        return view('whatsapp.chat', compact('messages', 'conversations', 'phoneNumber', 'contactName'));
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

    public function processMessage(Request $request)
    {
        if ($request->getMethod() !== 'POST') {
            return response()->json(['error' => 1, 'data' => 'Method Not Allowed'], 405);
        }

        $headers = $request->headers->all();
        $content = $request->json()->all();
        Log::info('WhatsApp Webhook Received: ' . json_encode($content, JSON_PRETTY_PRINT));

        $key = $headers['key'][0] ?? '';
        $user = $headers['nama'][0] ?? '';
        $sandi = $headers['sandi'][0] ?? '';
        if (!($key == 'KeyAbcKey' && $user == 'arul' && $sandi == '123###!!')) {
            return response()->json(['error' => 1, 'data' => 'gagal proses'], 403);
        }

        // --- Simpan Pesan Masuk ke Database ---
        $messageId = $content['data'][1]['key']['id'] ?? null;
        $msg = $content['data'][1]['message']['extendedTextMessage']['text'] ??
            ($content['data'][1]['message']['conversation'] ??
                ($content['message'] ?? ''));
        $nama = $content['data'][1]['pushName'] ?? 'Unknown';
        $nomor = Str::before($content['data'][1]['key']['remoteJid'] ?? '', '@');

        // Simpan hanya jika ada pesan dan nomor yang valid
        if (!empty($msg) && !empty($nomor)) {
            WhatsappMessage::updateOrCreate(
                ['message_id' => $messageId], // Cari berdasarkan message_id untuk menghindari duplikat
                [
                    'phone_number' => $nomor,
                    'contact_name' => $nama,
                    'message' => $msg,
                    'direction' => 'in',
                    'status' => 'read' // Anggap langsung terbaca karena masuk sistem
                ]
            );
        }

        // --- Logika Respon Otomatis (jika ada) ---
        $response = '';
        if ($msg == '/rekapabsen') {
            // ... (Kode rekap absen Anda diletakkan di sini) ...
            $response = 'Ini adalah rekap absen...'; // Ganti dengan hasil rekap Anda
            $this->_sendToNodeServer($nomor, $response); // Kirim balasan
        }

        return response()->json(['status' => 'success', 'message' => 'Webhook processed']);
    }

    // --- PRIVATE HELPER: Fungsi untuk mengirim pesan via cURL ---

    private function _sendToNodeServer($number, $message, $filePath = null)
    {
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
            Log::error('cURL Error to Node Server: ' . $error);
            return false;
        }

        Log::info('Response from Node Server: ' . $response);
        return $response;
    }
}
