<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class WhatsappController extends Controller
{
    public function sendMessage(Request $request)
    {

        // Validasi input
        $validated = $request->validate([
            'nama' => 'required|string',
            'nomor' => 'required|string|min:10|max:15',
            'message' => 'required|string|max:255',
            'file' => 'nullable|file',
        ]);

        // Header untuk cURL
        $headers = [
            'Key:KeyAbcKey',
            'Nama:arul',
            'Sandi:123###!!',
        ];

        // Data untuk database
        $dbData = [
            'nama' => $request->nama,
            'nomor' => $request->nomor,
            'message' => $request->message,
        ];

        // Data untuk request HTTP
        $httpData = [
            'number' => $request->nomor,
            'message' => $request->message,
        ];

        // Jika ada file yang diunggah
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $imageName = $request->nama . '_message_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('lampiran/whatsapp', $imageName, 'public');

            // Tambahkan nama file ke data database
            $dbData['file'] = $imageName;

            // Tambahkan file ke data HTTP
            $httpData['file_dikirim'] = new \CURLFile(storage_path('app/public/' . $path));
        }

        // Simpan data ke database
        DB::table('send_message_whatsapps')->insert($dbData);

        // Mengirim request HTTP menggunakan cURL
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'http://192.168.0.100:3001/send-message');
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_POST, 1);
        if (isset($dbData['file'])) {
            $filePath = storage_path('app/public/lampiran/whatsapp/' . $dbData['file']);
            $httpData['file_dikirim'] = new \CURLFile($filePath);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $httpData);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $curlError = curl_error($curl);
        curl_close($curl);

        // Penanganan respons dan kesalahan
        if ($response === false) {
            // cURL error
            $errorMessage = 'cURL error: ' . $curlError;
            return Redirect::route('whatsapp')->with('error', $errorMessage);
        } elseif ($httpCode == 200) {
            // Memeriksa konten respons
            $responseJson = json_decode($response, true);
            if ($responseJson === null) {
                // JSON parsing error
                $errorMessage = 'JSON parsing error: ' . json_last_error_msg();
                return Redirect::route('whatsapp')->with('error', $errorMessage);
            }
            return Redirect::route('whatsapp')->with('success', 'Pesan berhasil dikirim!');
        } else {
            // HTTP error
            $errorMessage = 'HTTP error: ' . $httpCode;
            return Redirect::route('whatsapp')->with('error', $errorMessage);
        }
    }
}
