<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class WhatsappController extends Controller
{
    /**
     * Mengirim pesan WhatsApp melalui API eksternal.
     */
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
            'created_at' => now(),
            'updated_at' => now(),
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
        }

        // Simpan data ke database
        DB::table('send_message_whatsapps')->insert($dbData);

        // Mengirim request HTTP menggunakan cURL
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'http://192.168.0.100:3001/send-message');
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_POST, 1);

        if (isset($dbData['file'])) {
            $filePath = storage_path('app/public/' . $path);
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
            $errorMessage = 'HTTP error: ' . $httpCode . ' - ' . $response;
            return Redirect::route('whatsapp')->with('error', $errorMessage);
        }
    }

    public function processMessage(Request $request)
    {
        // Cek apakah metode POST
        if ($request->getMethod() !== 'POST') {
            return response()->json(['error' => 1, 'data' => 'Method Not Allowed'], 405);
        }

        // Ambil data dari header dan JSON
        $headers = $request->headers->all();
        $content = $request->json()->all();

        Log::info('WhatsApp Webhook Received: ' . json_encode($content, JSON_PRETTY_PRINT));

        // Validasi header custom Anda
        $key = $headers['key'][0] ?? '';
        $user = $headers['nama'][0] ?? '';
        $sandi = $headers['sandi'][0] ?? '';

        $error = true;
        if ($key == 'KeyAbcKey' && $user == 'arul' && $sandi == '123###!!') {
            $error = false;
        }

        if ($error) {
            return response()->json(['error' => 1, 'data' => 'gagal proses'], 403);
        }

        // Mengambil data dari struktur JSON webhook yang benar
        $msg = $content['entry'][0]['changes'][0]['value']['messages'][0]['text']['body'] ?? '';
        $nama = $content['entry'][0]['changes'][0]['value']['contacts'][0]['profile']['name'] ?? 'Sahabat Livasya';
        $data = $content['data'] ?? ($content['entry'][0]['changes'][0]['value']['messages'][0] ?? []);

        // Abaikan webhook yang bukan pesan teks dari pengguna
        if (empty($msg)) {
            return response()->json(['status' => 'success', 'message' => 'Not a user message, skipped.']);
        }

        $response = '';

        if ($msg == '/test-kirim') {
            // ... (Logika /test-kirim Anda)
            $response .= 'Halo ' . $nama;
        } else if ($msg == '/rekapabsen') {
            // ... (Semua logika /rekapabsen Anda ada di sini)
            $total_pegawai_rs = Employee::where('is_active', 1)->where('company_id', 1)->count();
            $total_pegawai_pt = Employee::where('is_active', 1)->where('company_id', 2)->count();
            $total_clockin = Attendance::whereNotNull('clock_in')
                ->whereDate('date', Carbon::now()->format('Y-m-d'))
                ->whereHas('employees', function ($query) {
                    $query->where('is_active', 1);
                })->count();
            $total_no_clockin = Attendance::whereNull('clock_in')->whereNull('is_day_off')
                ->whereDate('date', Carbon::now()->format('Y-m-d'))
                ->whereHas('employees', function ($query) {
                    $query->where('organization_id', '!=', 3);
                    $query->where('is_active', 1);
                })->count();
            $total_libur = Attendance::where('is_day_off', 1)
                ->whereNull('attendance_code_id')
                ->whereNull('day_off_request_id')
                ->whereDate('date', Carbon::now()->format('Y-m-d'))
                ->whereHas('employees', function ($query) {
                    $query->where('is_active', 1);
                })->count();

            $attendancesWithLeave = Attendance::with(['day_off.attendance_code', 'attendance_code'])
                ->whereNotNull('is_day_off')
                ->where('date', Carbon::now()->format('Y-m-d'))
                ->where(function ($query) {
                    $query->whereNotNull('attendance_code_id')
                        ->orWhereNotNull('day_off_request_id');
                })
                ->get();

            $total_izin = $attendancesWithLeave->filter(function ($item) {
                return ($item->attendance_code_id == 1) || ($item->day_off->attendance_code_id ?? null) == 1;
            })->count();
            $total_sakit = $attendancesWithLeave->filter(function ($item) {
                return ($item->attendance_code_id == 2) || ($item->day_off->attendance_code_id ?? null) == 2;
            })->count();
            $total_cuti = $attendancesWithLeave->filter(function ($item) {
                $code = $item->attendance_code_id ?? ($item->day_off->attendance_code_id ?? null);
                return !in_array($code, [1, 2, null]);
            })->count();


            $response = "\n\n⬛️ <b>REKAP ABSEN HARI INI:</b>\n\n";
            $response .= "🔹 <code>Total Pegawai RS: $total_pegawai_rs </code>\n";
            $response .= "🔹 <code>Total Pegawai PT: $total_pegawai_pt </code>\n";
            $response .= "🔹 <code>Sudah clockin: $total_clockin </code>\n";
            $response .= "🔹 <code>Belum clockin: $total_no_clockin </code>\n";
            $response .= "🔹 <code>Pegawai libur: $total_libur </code>\n";
            $response .= "🔹 <code>Pegawai Cuti: $total_cuti </code>\n";
            $response .= "🔹 <code>Pegawai Izin: $total_izin </code>\n";
            $response .= "🔹 <code>Pegawai Sakit: $total_sakit </code>\n\n";

            $response .= "\n🟥 <b>DAFTAR PEGAWAI YANG TELAT:</b> \n\n";
            $pegawai_telat = Attendance::with('employees')->whereNotNull('clock_in')->whereNotNull('late_clock_in')->whereHas('employees', function ($query) {
                $query->where('is_active', 1);
                $query->whereNotIn('id', [1, 2, 14, 222]);
            })->where('date', Carbon::now()->format('Y-m-d'))->orderBy('late_clock_in')->get();
            foreach ($pegawai_telat as $key => $row) {
                if ($row->late_clock_in > 5 && $row->late_clock_in < 70) {
                    $response .= "🔸" . Str::limit($row->employees->fullname, $limit = 16) . " ( " . $row->late_clock_in . " menit )\n";
                }
            }

            $response .= "\n";
            $response .= "<b>Rekap tersebut diambil berdasarkan tanggal " . Carbon::now()->translatedFormat('d F Y H:i') . "</b>";
        } else if ($msg == '/tidakabsen') {
            // ... Logika untuk /tidakabsen ...

        } else if ($msg == '/isiabsenpeg') {
            // ... Logika untuk /isiabsenpeg ...

        } else {
            // --- PERUBAHAN 1: BLOK ELSE DIKOSONGKAN ---
            // Jika tidak ada perintah yang cocok, jangan lakukan apa-apa.
            // Biarkan variabel $response tetap kosong.
        }

        // --- PERUBAHAN 2: HANYA KIRIM JIKA ADA RESPONS ---
        // Cek apakah ada respons yang perlu dikirim.
        if (!empty($response)) {
            // Jika $response tidak kosong, kirimkan sebagai balasan.
            return response()->json(['error' => ($error ? "1" : "0"), 'data' => $response]);
        }

        // Jika $response kosong (karena perintah tidak dikenali),
        // kirim response sukses tanpa data untuk memberitahu server WhatsApp
        // bahwa pesan telah diterima, tanpa mengirim balasan ke pengguna.
        return response()->json(['status' => 'success', 'message' => 'Command not recognized, no reply sent.']);
    }
}
