<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
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

    /**
     * Menerima dan memproses webhook dari layanan WhatsApp.
     */
    public function processMessage(Request $request)
    {
        // Cek apakah metode POST
        if ($request->getMethod() !== 'POST') {
            return response()->json(['error' => 1, 'data' => 'Method Not Allowed'], 405);
        }

        // Ambil data dari header dan JSON
        $headers = $request->headers->all();
        $content = $request->json()->all();

        // Opsional tapi sangat disarankan: Log request untuk debugging
        Log::info('WhatsApp Webhook Received: ' . json_encode($content, JSON_PRETTY_PRINT));

        // Validasi header
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

        // --- PERBAIKAN: Ambil data dari struktur JSON webhook WhatsApp yang benar ---
        $msg = $content['entry'][0]['changes'][0]['value']['messages'][0]['text']['body'] ?? '';
        $nama = $content['entry'][0]['changes'][0]['value']['contacts'][0]['profile']['name'] ?? 'Sahabat Livasya';
        $data = $content['data'] ?? []; // Untuk kompatibilitas dengan blok /isiabsenpeg
        // --- AKHIR PERBAIKAN ---

        $response = '';

        // Abaikan webhook yang bukan pesan dari pengguna (misal: status 'delivered', 'read')
        if (empty($msg)) {
            return response()->json(['status' => 'success', 'message' => 'Not a user message, skipped.']);
        }

        if ($msg == '/test-kirim') {
            $response .= 'Halo ' . $nama;
        } else if ($msg == '/rekapabsen') {
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
            $total_izin = 0;
            $total_sakit = 0;
            $total_cuti = 0;
            $absensi_pegawai = Attendance::with(['day_off'])->where('is_day_off', '!=', null)->where('date', Carbon::now()->format('Y-m-d'))->get();
            foreach ($absensi_pegawai as $absensi) {
                if ($absensi->attendance_code_id != null || $absensi->day_off_request_id != null) {
                    if ($absensi->attendance_code_id == 1) {
                        $total_izin += 1;
                    } elseif ($absensi->attendance_code_id == 2) {
                        $total_sakit += 1;
                    } elseif ($absensi->attendance_code_id != 1 && $absensi->attendance_code_id != 2) {
                        $total_cuti += 1;
                    } elseif (empty($absensi->attendance_code_id)) {
                        if ($absensi->day_off) {
                            if ($absensi->day_off->attendance_code_id == 1) {
                                $total_izin += 1;
                            } elseif ($absensi->day_off->attendance_code_id == 2) {
                                $total_sakit += 1;
                            } else {
                                $total_cuti += 1;
                            }
                        }
                    }
                }
            }

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
            $pegawai_telat = Attendance::whereNotNull('clock_in')->whereNotNull('late_clock_in')->whereHas('employees', function ($query) {
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
            $response = "";
            // Note: $data['shift'] mungkin perlu penyesuaian tergantung bagaimana data ini dikirim.
            if (isset($data["shift"])) {
                if ($data["shift"] == "pagi") {
                    $absent_pagi_pt = Attendance::where('clock_in', null)->where('is_day_off', null)
                        ->whereHas('shift', function ($query) {
                            $query->where('time_in', '>', '04:00:00')
                                ->where('time_in', '<', '09:00:00');
                        })->whereHas('employees', function ($query) {
                            $query->where('is_active', 1)->where('company_id', 2);
                        })->where('date', Carbon::now()->format('Y-m-d'))->get();

                    $absent_pagi_rs = Attendance::where('clock_in', null)->where('is_day_off', null)
                        ->whereHas('shift', function ($query) {
                            $query->where('time_in', '>', '04:00:00')
                                ->where('time_in', '<', '09:00:00');
                        })->whereHas('employees', function ($query) {
                            $query->where('is_active', 1)->where('company_id', 1)->where('organization_id', '!=', 3)->whereNotIn('id', [1, 2, 14, 222]);
                        })->where('date', Carbon::now()->format('Y-m-d'))->get();

                    $response .= "\n🔴 <b>DAFTAR KARYAWAN YANG TIDAK ABSEN PAGI ‼️ </b>\n\n";
                    if ($absent_pagi_pt->isNotEmpty()) {
                        $response .= "🔻 <b>Karyawan PT: </b>\n";
                        foreach ($absent_pagi_pt as $key => $row) {
                            $response .= "⛔️ " . $row->employees->fullname . "\n";
                        }
                        $response .= "\n";
                    }
                    if ($absent_pagi_rs->isNotEmpty()) {
                        $response .= "🔻 <b>Karyawan RS Livasya: </b>\n";
                        foreach ($absent_pagi_rs as $key => $row) {
                            $response .= "⛔️ " . $row->employees->fullname . "\n";
                        }
                        $response .= "\n";
                    }
                } else if ($data["shift"] == "siang") {
                    $absent_siang_pt = Attendance::where('clock_in', null)->where('is_day_off', null)
                        ->whereHas('shift', function ($query) {
                            $query->where('time_in', '>', '12:00:00')
                                ->where('time_in', '<', '15:00:00');
                        })->whereHas('employees', function ($query) {
                            $query->where('is_active', 1);
                            $query->where('company_id', 2);
                        })
                        ->where('date', Carbon::now()->format('Y-m-d'))
                        ->get();

                    $absent_siang_rs = Attendance::where('clock_in', null)->where('is_day_off', null)
                        ->whereHas('shift', function ($query) {
                            $query->where('time_in', '>', '12:00:00')
                                ->where('time_in', '<', '15:00:00');
                        })->whereHas('employees', function ($query) {
                            $query->where('is_active', 1);
                            $query->where('company_id', 1);
                        })
                        ->where('date', Carbon::now()->format('Y-m-d'))
                        ->get();

                    $response .= "\n🔴 <b>DAFTAR KARYAWAN YANG TIDAK ABSEN SIANG ‼️ </b>\n\n";
                    if ($absent_siang_pt->isNotEmpty()) {
                        $response .= "🔻 <b>Karyawan PT: </b>\n";
                        foreach ($absent_siang_pt as $key => $row) {
                            $response .= "⛔️ " . $row->employees->fullname . "\n";
                        }
                        $response .= "\n";
                    }

                    if ($absent_siang_rs->isNotEmpty()) {
                        $response .= "🔻 <b>Karyawan RS Livasya: </b>\n";
                        foreach ($absent_siang_rs as $key => $row) {
                            $response .= "⛔️ " . $row->employees->fullname . "\n";
                        }
                        $response .= "\n";
                    }
                } else if ($data["shift"] == "malam") {
                    $absent_malam_pt = Attendance::where('clock_in', null)->where('is_day_off', null)
                        ->whereHas('shift')->whereHas('employees', function ($query) {
                            $query->where('is_active', 1);
                            $query->where('company_id', 2);
                        })
                        ->where('date', Carbon::now()->format('Y-m-d'))
                        ->get();

                    $absent_malam_rs = Attendance::where('clock_in', null)->where('is_day_off', null)
                        ->whereHas('shift')->whereHas('employees', function ($query) {
                            $query->where('is_active', 1);
                            $query->where('company_id', 1);
                            $query->whereNotIn('id', [1, 2, 14, 222]);
                        })
                        ->where('date', Carbon::now()->format('Y-m-d'))
                        ->get();

                    $response .= "\n🔴 <b>DAFTAR KARYAWAN YANG TIDAK ABSEN HARI INI ‼️ </b>\n\n";
                    if ($absent_malam_pt->isNotEmpty()) {
                        $response .= "🔻 <b>Karyawan PT: </b>\n";
                        foreach ($absent_malam_pt as $key => $row) {
                            $response .= "⛔️ " . $row->employees->fullname . "\n";
                        }
                        $response .= "\n";
                    }

                    if ($absent_malam_rs->isNotEmpty()) {
                        $response .= "🔻 <b>Karyawan RS Livasya: </b>\n";
                        foreach ($absent_malam_rs as $key => $row) {
                            $response .= "⛔️ " . $row->employees->fullname . "\n";
                        }
                        $response .= "\n";
                    }
                }
            }

            $response .= "\n\n🔴 <b>DAFTAR KARYAWAN YANG LIBUR/IZIN/SAKIT/CUTI: </b>\n\n";
            $response .= "🔻 <b>Karyawan PT: </b>\n";
            $attendancesPT = Attendance::whereNotNull('is_day_off')->whereHas('employees', function ($query) {
                $query->where('is_active', 1)->where('company_id', 2);
            })->where('date', Carbon::now()->format('Y-m-d'))->get();

            foreach ($attendancesPT as $key => $row) {
                if ($row->attendance_code_id != null || $row->day_off_request_id != null) {
                    $status = isset($row->attendance_code_id) ? ($row->attendance_code->name ?? 'N/A') : ($row->day_off->attendance_code->name ?? 'N/A');
                    $response .= "▪️ " . $row->employees->fullname . " ( " . $status . " )\n";
                } else {
                    $response .= "▪️ " . $row->employees->fullname . " ( Libur )\n";
                }
            }
            $response .= "\n";

            $response .= "\n🔻 <b>Karyawan RS Livasya: </b>\n";
            $attendancesLivasya = Attendance::whereNotNull('is_day_off')->whereHas('employees', function ($query) {
                $query->where('is_active', 1)->where('company_id', 1);
            })->where('date', Carbon::now()->format('Y-m-d'))->get();

            foreach ($attendancesLivasya as $row) {
                if ($row->attendance_code_id != null || $row->day_off_request_id != null) {
                    $status = $row->attendance_code->description ?? ($row->day_off->attendance_code->description ?? 'N/A');
                    $response .= "<b>▪️ " . $row->employees->fullname . " ( " . $status . " )</b> \n";
                } else {
                    $response .= "▪️ " . $row->employees->fullname . " ( Libur )\n";
                }
            }

            $response .= "\n";
            $response .= "<b>Rekap tersebut diambil berdasarkan tanggal " . Carbon::now()->translatedFormat('d F Y H:i') . "</b>";
        } else if ($msg == '/isiabsenpeg') {
            $idTelegram = $data['id'] ?? null;
            $usernameTelegram = $data['uname'] ?? null;
            $namaPegawai = $data['name'] ?? null;
            $tanggal = date("d M Y H:i:s", $data['date']);
            $latitude = $data['latitude'] ?? null;
            $longitude = $data['longitude'] ?? null;

            $response = 'Terima kasih ' . $namaPegawai . ' sudah mengisi Absensi 😍 ';
            $response .= "\n" . 'Pada tanggal ' . date("d M Y", $data['date']) . ' jam ' . date("H:i:s", $data['date']);
        } else {
            $response  = "Halo *$nama* , \r\n";
            $response .= "Salam sehat sahabat Livasya, terimakasih sudah menghubungi kontak Customer Service *Rumah Sakit Livasya Majalengka.* \r\n\r\n";
            $response .= "*Jam Operasional IGD 24 Jam.* \r\n";
            $response .= "Untuk Layanan dan informasi lainnya bisa kunjungi website official kami di www.livasya.com atau silahkan klik menu layanan dibawah ini: \r\n";
        }

        return response()->json(['error' => ($error ? "1" : "0"), 'data' => $response]);
    }
}
