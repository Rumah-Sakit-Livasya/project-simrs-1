<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TtsController extends Controller
{
    /**
     * Menghasilkan audio dari teks menggunakan layanan Google Translate.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function generateSpeech(Request $request)
    {
        // Validasi input 'text'
        $validated = $request->validate([
            'text' => 'required|string|max:255',
        ]);

        $text = $validated['text'];
        $lang = 'id'; // Bahasa Indonesia

        // URL endpoint tidak resmi Google TTS
        $url = "https://translate.google.com/translate_tts?ie=UTF-8&q=" . urlencode($text) . "&tl=" . $lang . "&client=tw-ob";

        try {
            // Gunakan Laravel HTTP Client untuk mengambil data audio
            $response = Http::withHeaders([
                'Referer' => 'http://translate.google.com/',
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.104 Safari/537.36',
            ])->get($url);

            if ($response->successful()) {
                // Kirimkan data audio kembali ke browser sebagai response
                return response($response->body())
                    ->header('Content-Type', 'audio/mpeg')
                    ->header('Content-Disposition', 'inline; filename="speech.mp3"');
            }

            // Jika gagal, kembalikan error
            return response()->json(['error' => 'Gagal mengambil data audio dari server TTS.'], 502);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
}
