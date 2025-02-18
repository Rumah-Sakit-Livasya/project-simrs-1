<?php

namespace App\Http\Controllers\SIMRS\TextToSpeech;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TextToSpeechController extends Controller
{
    public function tts(Request $request)
    {
        $text = $request->query('text', 'Antrian 13 Poliklinik Obgyn');
        $encodedText = urlencode($text);

        // URL Google Translate TTS
        $ttsUrl = "https://translate.google.com/translate_tts?ie=UTF-8&tl=id&client=tw-ob&q={$encodedText}";

        return redirect($ttsUrl);
    }
}
