<?php

namespace App\Http\Controllers\SIMRS\TextToSpeech;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;

class TextToSpeechController extends Controller
{
    public function tts(Request $request)
    {
        $text = $request->query('text', 'Antrian 13 Poliklinik Obgyn');
        $encodedText = urlencode($text);
        $ttsUrl = "https://translate.google.com/translate_tts?ie=UTF-8&tl=id&client=tw-ob&q={$encodedText}";

        // Ambil audio dari Google TTS
        $response = Http::withHeaders([
            'User-Agent' => 'Mozilla/5.0'
        ])->get($ttsUrl);

        if ($response->successful()) {
            // Kirim file audio sebagai response
            return Response::make($response->body(), 200, [
                'Content-Type' => 'audio/mpeg',
                'Content-Disposition' => 'inline; filename="tts.mp3"',
            ]);
        }

        return response()->json(['error' => 'Gagal memuat audio'], 500);
    }
}
