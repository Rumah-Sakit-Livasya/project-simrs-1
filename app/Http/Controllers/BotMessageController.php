<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\UploadFile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BotMessageController extends Controller
{
    public function processMessage(Request $request)
    {
        // Cek apakah metode POST
        if ($request->getMethod() !== 'POST') {
            return response()->json(['error' => 1, 'data' => 'Method Not Allowed'], 405);
        }

        // Ambil data dari header dan JSON
        $headers = $request->headers->all();
        $content = $request->json()->all();

        // Log request untuk debugging. Ini akan sangat membantu!
        Log::info('Webhook Received: ' . json_encode($content, JSON_PRETTY_PRINT));

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

        $response = '';

        // --- ROUTER UTAMA: Membedakan jenis webhook berdasarkan $content['message'] ---
        $webhookType = $content['message'] ?? '';
        $webhookData = $content['data'] ?? [];

        $eventLoggerTypes = ['contact', 'message', 'kirim_pesan', 'update_status_pesan', 'interactive'];

        if (in_array($webhookType, $eventLoggerTypes)) {
            // --- LOGIKA BARU: Untuk menyimpan event ke database ---

            switch ($webhookType) {
                case 'contact':
                    $nama = $webhookData['nama'] ?? '';
                    $nomor = $webhookData['nomor'] ?? '';
                    // TODO: Simpan kontak baru ke database Anda
                    //     /* Contoh:
                    // WhatsappLog::create([
                    //     'event_type' => 'contact',
                    //     'sender_name' => $nama,
                    //     'sender_number' => $nomor,
                    // ]);
                    // */
                    Log::info("Menerima kontak baru: $nama ($nomor)");
                    break;

                case 'message': // Event saat pesan MASUK
                    $nama = $webhookData['nama'] ?? '';
                    $nomor = $webhookData['nomor'] ?? '';
                    $id_pesan = $webhookData['id_pesan'] ?? '';
                    $pesan = $webhookData['pesan'] ?? '';
                    // TODO: Simpan pesan masuk ke database Anda
                    /* Contoh:
                WhatsappLog::create([
                    'event_type' => 'message_received',
                    'message_id' => $id_pesan,
                    'sender_name' => $nama,
                    'sender_number' => $nomor,
                    'message_content' => $pesan,
                ]);
                */
                    Log::info("Menerima pesan masuk dari $nama: '$pesan'");
                    break;

                case 'kirim_pesan': // Event saat pesan DIKIRIM dari sistem Anda
                    $nomor = $webhookData['nomor'] ?? '';
                    $nama = $webhookData['nama'] ?? '';
                    $id_pesan = $webhookData['id_pesan'] ?? '';
                    $pesan = $webhookData['pesan'] ?? '';
                    // TODO: Simpan log pesan keluar ke database Anda
                    /* Contoh:
                WhatsappLog::create([
                    'event_type' => 'message_sent',
                    'message_id' => $id_pesan,
                    'recipient_name' => $nama,
                    'recipient_number' => $nomor,
                    'message_content' => $pesan,
                    'status' => 'sending' // Status awal
                ]);
                */
                    Log::info("Mengirim pesan ke $nama: '$pesan'");
                    break;

                case 'update_status_pesan':
                    $id_pesan = $webhookData['id_pesan'] ?? '';
                    $status_pesan = strtolower($webhookData['status_pesan'] ?? '');
                    // TODO: Update status pesan di database Anda
                    /* Contoh:
                $logPesan = WhatsappLog::where('message_id', $id_pesan)->first();
                if ($logPesan) {
                    $logPesan->status = $status_pesan; // 'sent', 'delivered', 'read', 'failed'
                    $logPesan->save();
                }
                */
                    Log::info("Update status untuk pesan $id_pesan: $status_pesan");
                    break;

                case 'interactive':
                    $pesan = $webhookData['pesan'] ?? '';
                    $title = $webhookData['title'] ?? '';
                    $response = "Mohon Maaf, Fitur *$title* masih dalam pengembangan.";
                    break;
            }
        } else {
            // --- LOGIKA LAMA: Untuk merespons perintah pengguna seperti /rekapabsen ---

            // Ambil data dari struktur pesan masuk yang lebih kompleks
            $msg = $content['data'][1]['message']['extendedTextMessage']['text'] ??
                ($content['data'][1]['message']['conversation'] ?? $webhookType);

            $nama = $content['data'][1]['pushName'] ?? 'Sahabat Livasya';

            if ($msg == '/test-kirim') {
                $response .= 'Halo ' . $nama;
            } else if ($msg == '/rekapabsen') {
                $total_pegawai_rs = Employee::where('is_active', 1)->where('company_id', 1)->count();
                $total_pegawai_pt = Employee::where('is_active', 1)->where('company_id', 2)->count();
                $total_clockin = Attendance::whereNotNull('clock_in')
                    ->whereDate('date', Carbon::now()->format('Y-m-d'))
                    ->whereHas('employees', fn($q) => $q->where('is_active', 1))->count();
                $total_no_clockin = Attendance::whereNull('clock_in')->whereNull('is_day_off')
                    ->whereDate('date', Carbon::now()->format('Y-m-d'))
                    ->whereHas('employees', fn($q) => $q->where('organization_id', '!=', 3)->where('is_active', 1))->count();
                $total_libur = Attendance::where('is_day_off', 1)
                    ->whereNull('attendance_code_id')->whereNull('day_off_request_id')
                    ->whereDate('date', Carbon::now()->format('Y-m-d'))
                    ->whereHas('employees', fn($q) => $q->where('is_active', 1))->count();

                $attendancesWithLeave = Attendance::with(['day_off.attendance_code', 'attendance_code'])
                    ->whereNotNull('is_day_off')->where('date', Carbon::now()->format('Y-m-d'))
                    ->where(fn($q) => $q->whereNotNull('attendance_code_id')->orWhereNotNull('day_off_request_id'))
                    ->get();

                $total_izin = $attendancesWithLeave->filter(fn($item) => ($item->attendance_code_id == 1) || optional($item->day_off)->attendance_code_id == 1)->count();
                $total_sakit = $attendancesWithLeave->filter(fn($item) => ($item->attendance_code_id == 2) || optional($item->day_off)->attendance_code_id == 2)->count();
                $total_cuti = $attendancesWithLeave->filter(fn($item) => !in_array($item->attendance_code_id ?? optional($item->day_off)->attendance_code_id, [1, 2, null]))->count();

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
                $pegawai_telat = Attendance::with('employees')->whereNotNull('clock_in')->whereNotNull('late_clock_in')
                    ->whereHas('employees', fn($q) => $q->where('is_active', 1)->whereNotIn('id', [1, 2, 14, 222]))
                    ->where('date', Carbon::now()->format('Y-m-d'))->orderBy('late_clock_in')->get();

                foreach ($pegawai_telat as $row) {
                    if ($row->late_clock_in > 5 && $row->late_clock_in < 70) {
                        $response .= "🔸" . Str::limit($row->employees->fullname, 16) . " ( " . $row->late_clock_in . " menit )\n";
                    }
                }
                $response .= "\n";
                $response .= "<b>Rekap tersebut diambil berdasarkan tanggal " . Carbon::now()->translatedFormat('d F Y h:i A') . "</b>";
            } else if ($msg == '/tidakabsen') {
                $response = "";
                if (isset($data["shift"])) {
                    if ($data["shift"] == "pagi") {
                        $absent_pagi_pt = Attendance::where('clock_in', null)->where('is_day_off', null)
                            ->whereHas('shift', function ($query) {
                                $query->where('time_in', '>', '04:00:00') // Menambahkan kondisi time_in > 04:00:00
                                    ->where('time_in', '<', '09:00:00'); // Menambahkan kondisi time_in < 09:00:00
                            })->whereHas('employees', function ($query) {
                                $query->where('is_active', 1); //Hanya untuk karyawan yng aktif
                                $query->where('company_id', 2); // Hanya untuk karyawan PT
                            })
                            ->where('date', Carbon::now()->format('Y-m-d'))
                            ->get();

                        $absent_pagi_rs = Attendance::where('clock_in', null)->where('is_day_off', null)
                            ->whereHas('shift', function ($query) {
                                $query->where('time_in', '>', '04:00:00') // Menambahkan kondisi time_in > 04:00:00
                                    ->where('time_in', '<', '09:00:00'); // Menambahkan kondisi time_in < 09:00:00
                            })->whereHas('employees', function ($query) {
                                $query->where('is_active', 1); //Hanya untuk karyawan yng aktif
                                $query->where('company_id', 1); // Hanya untuk karyawan RS
                                $query->where('organization_id', '!=', 3);
                                $query->whereNotIn('id', [1, 2, 14, 222]); // Mengecualikan employee dengan ID;
                            })
                            ->where('date', Carbon::now()->format('Y-m-d'))
                            ->get();

                        $response .= "\n🔴 <b>DAFTAR KARYAWAN YANG TIDAK ABSEN PAGI ‼️ </b>\n\n";
                        if (isset($absent_pagi_pt)) {
                            $response .= "🔻 <b>Karyawan PT: </b>\n";

                            foreach ($absent_pagi_pt as $key => $row) {
                                $response .= "⛔️ " . $row->employees->fullname . "\n";
                            }
                            $response .= "\n";
                        }

                        if (isset($absent_pagi_rs)) {
                            $response .= "🔻 <b>Karyawan RS Livasya: </b>\n";

                            foreach ($absent_pagi_rs as $key => $row) {
                                $response .= "⛔️ " . $row->employees->fullname . "\n";
                            }
                            $response .= "\n";
                        }
                    } else if ($data["shift"] == "siang") {
                        $absent_siang_pt = Attendance::where('clock_in', null)->where('is_day_off', null)
                            ->whereHas('shift', function ($query) {
                                $query->where('time_in', '>', '12:00:00') // Menambahkan kondisi time_in > 04:00:00
                                    ->where('time_in', '<', '15:00:00'); // Menambahkan kondisi time_in < 09:00:00
                            })->whereHas('employees', function ($query) {
                                $query->where('is_active', 1); //Hanya untuk karyawan yng aktif
                                $query->where('company_id', 2); // Hanya untuk karyawan PT
                            })
                            ->where('date', Carbon::now()->format('Y-m-d'))
                            ->get();

                        $absent_siang_rs = Attendance::where('clock_in', null)->where('is_day_off', null)
                            ->whereHas('shift', function ($query) {
                                $query->where('time_in', '>', '12:00:00') // Menambahkan kondisi time_in > 04:00:00
                                    ->where('time_in', '<', '15:00:00'); // Menambahkan kondisi time_in < 09:00:00
                            })->whereHas('employees', function ($query) {
                                $query->where('is_active', 1); //Hanya untuk karyawan yng aktif
                                $query->where('company_id', 1); // Hanya untuk karyawan RS
                            })
                            ->where('date', Carbon::now()->format('Y-m-d'))
                            ->get();

                        $response .= "\n🔴 <b>DAFTAR KARYAWAN YANG TIDAK ABSEN SIANG ‼️ </b>\n\n";
                        if (isset($absent_siang_pt)) {
                            $response .= "🔻 <b>Karyawan PT: </b>\n";

                            foreach ($absent_siang_pt as $key => $row) {
                                $response .= "⛔️ " . $row->employees->fullname . "\n";
                            }
                            $response .= "\n";
                        }

                        if (isset($absent_siang_rs)) {
                            $response .= "🔻 <b>Karyawan RS Livasya: </b>\n";

                            foreach ($absent_siang_rs as $key => $row) {
                                $response .= "⛔️ " . $row->employees->fullname . "\n";
                            }
                            $response .= "\n";
                        }
                    } else if ($data["shift"] == "malam") {
                        $absent_malam_pt = Attendance::where('clock_in', null)->where('is_day_off', null)
                            ->whereHas('shift')->whereHas('employees', function ($query) {
                                $query->where('is_active', 1); //Hanya untuk karyawan yng aktif
                                $query->where('company_id', 2); // Hanya untuk karyawan PT
                            })
                            ->where('date', Carbon::now()->format('Y-m-d'))
                            ->get();

                        $absent_malam_rs = Attendance::where('clock_in', null)->where('is_day_off', null)
                            ->whereHas('shift')->whereHas('employees', function ($query) {
                                $query->where('is_active', 1); //Hanya untuk karyawan yng aktif
                                $query->where('company_id', 1); // Hanya untuk karyawan RS
                                $query->whereNotIn('id', [1, 2, 14, 222]);
                            })
                            ->where('date', Carbon::now()->format('Y-m-d'))
                            ->get();

                        $response .= "\n🔴 <b>DAFTAR KARYAWAN YANG TIDAK ABSEN HARI INI ‼️ </b>\n\n";
                        if (isset($absent_malam_pt)) {
                            $response .= "🔻 <b>Karyawan PT: </b>\n";

                            foreach ($absent_malam_pt as $key => $row) {
                                $response .= "⛔️ " . $row->employees->fullname . "\n";
                            }
                            $response .= "\n";
                        }

                        if (isset($absent_malam_rs)) {
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
                $attendancesPT = Attendance::where('is_day_off', '!=', null)->whereHas('employees', function ($query) {
                    $query->where('is_active', 1); //Hanya untuk karyawan yng aktif
                    $query->where('company_id', 2); // Hanya untuk karyawan PT
                })->where('date', Carbon::now()->format('Y-m-d'))->get();

                foreach ($attendancesPT as $key => $row) {
                    if ($row->attendance_code_id != null || $row->day_off_request_id != null) {
                        $response .= "▪️ " . $row->employees->fullname . " ( " . isset($row->attendance_code_id) ? $row->attendance_code->name : $row->day_off->attendance_code->name . " )\n";
                    } else {
                        $response .= "▪️ " . $row->employees->fullname . " ( Libur )\n";
                    }
                }
                $response .= "\n";
                $attendancesLivasya = Attendance::where('is_day_off', '!=', null)->whereHas('employees', function ($query) {
                    $query->where('is_active', 1); //Hanya untuk karyawan yng aktif
                    $query->where('company_id', 1); // Hanya untuk karyawan PT
                })->where('date', Carbon::now()->format('Y-m-d'))->get();

                // dd($attendancesLivasya);
                $response .= "\n🔻 <b>Karyawan RS Livasya: </b>\n";

                // Array untuk pegawai dengan attendance_code_id atau day_off_request_id
                $employeesWithAttendance = [];
                // Array untuk pegawai dengan Libur
                $employeesOnLeave = [];

                // Memisahkan pegawai berdasarkan kondisi
                foreach ($attendancesLivasya as $key => $row) {
                    if ($row->attendance_code_id != null || $row->day_off_request_id != null) {
                        $employeesWithAttendance[] = $row;
                    } else {
                        $employeesOnLeave[] = $row;
                    }
                }

                // Menambahkan pegawai dengan attendance_code_id atau day_off_request_id ke dalam respons
                foreach ($employeesWithAttendance as $row) {
                    $response .= "<b>▪️ " . $row->employees->fullname . " ( ";
                    if ($row->attendance_code_id != null) {
                        $response .= $row->attendance_code->description;
                    } else {
                        $response .= $row->day_off->attendance_code->description;
                    }
                    $response .= " )</b> \n";
                }
                $response .= "\n";
                // Menambahkan pegawai dengan Libur ke dalam respons
                foreach ($employeesOnLeave as $row) {
                    $response .= "▪️ " . $row->employees->fullname . " ( Libur )\n";
                }

                $response .= "\n";
                $response .= "<b>Rekap tersebut diambil berdasarkan tanggal " . Carbon::now()->translatedFormat('d F Y h:i A') . "</b>";
            } else if ($msg == '/isiabsenpeg') {
                $idTelegram = isset($data['id']) ? $data['id'] : null;
                $usernameTelegram = $data['uname'] ?? null;
                $nama = $data['name'] ?? null;
                $tanggal = date("d M Y H:i:s", $data['date']);
                $latitude = $data['latitude'] ?? null;
                $longitude = $data['longitude'] ?? null;

                $response = 'terima kasih ' . $nama . ' sudah mengisi Absensi 😍 ';
                $response .= chr(10) . 'pada tanggal ' . date("d M Y", $data['date']) . ' jam ' . date("H:i:s", $data['date']);;
            }
            // else {
            //     // $idTelegram = isset($data['id']) ? $data['id'] : null;
            //     // $usernameTelegram = $data['username'] ?? null;
            //     // $nama = $data['first_name'] ?? null;

            //     // $error = true;
            //     // $response = 'else';
            //     $response  = "Halo *$nama* , \r\n";
            //     $response .= "Salam sehat sahabat Livasya, terimakasih sudah menghubungi kontak Customer Service *Rumah Sakit Livasya Majalengka.* \r\n\r\n";
            //     $response .= "*Jam Operasional IGD 24 Jam.* \r\n";
            //     $response .= "Untuk Layanan dan informasi lainnya bisa kunjungi website official kami di www.livasya.com atau silahkan klik menu layanan dibawah ini: \r\n";
            // }
        }

        // --- PENGIRIMAN RESPONS AKHIR ---

        // Hanya kirim balasan jika variabel $response sudah diisi (misal: dari /rekapabsen atau 'interactive')
        if (!empty($response)) {
            return response()->json(['error' => "0", 'data' => $response]);
        }

        // Jika tidak ada balasan (misal: untuk logging), kirim status sukses agar tidak ada retry
        return response()->json(['status' => 'success', 'message' => 'Webhook processed successfully, no reply needed.']);
    }

    public function livasyaMessage(Request $request)
    {
        // Cek apakah metode POST
        if ($request->getMethod() !== 'POST') {
            return response()->json(['error' => 1, 'data' => 'ok cuy'], 405);
        }

        // Ambil data dari header dan JSON
        $headers = $request->headers->all();
        $content = $request->json()->all();
        $msg = $content['message'] ?? '';

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

        if ($msg == 'test_string') {
            $response = [
                'message_type' => 'string',
                'data' => 'Halo String'
            ];
        } else if ($msg == 'test_array') {
            $response =  [
                'message_type' => 'array',
                'title' => 'judul tombol nya',
                'body' => 'isi text nya',
                'data' => [
                    [
                        'id' => '/jadwal_praktek',
                        'title' => 'Jadwal Poli'
                    ],
                    [
                        'id' => '/info_fasilitas',
                        'title' => 'Fasilitas Unggulan'
                    ],
                    [
                        'id' => '/info_rajal',
                        'title' => 'Info Rawat Jalan'
                    ],
                    [
                        'id' => '/daftar_poli_rajal',
                        'title' => 'Pendaftaran Poliklinik'
                    ],
                    [
                        'id' => '/info_medical',
                        'title' => 'Medical Check Up'
                    ],
                    [
                        'id' => '/info_persalinan',
                        'title' => 'Biaya Persalinan'
                    ]
                ]
            ];

            // Bagian pilihan jadwal poliklinik

        } else if ($msg == '/jadwal_praktek') {

            $response =  [
                'message_type' => 'array',
                'title' => 'Pilih Layanan',
                'body' => 'Berikut adalah jadwal Poliklinik di Rumah Sakit Livasya. Silahkan klik tombol dibawah untuk info selengkapnya.',
                'data' => [
                    [
                        'id' => '/klinik_obgyn',
                        'title' => 'Poli Obgyn'
                    ],
                    [
                        'id' => '/klinik_anak',
                        'title' => 'Poli Anak'
                    ],
                    [
                        'id' => '/klinik_tht',
                        'title' => 'Poli THT'
                    ],
                    [
                        'id' => '/klinik_dalam',
                        'title' => 'Poli Penyakit Dalam'
                    ],
                    [
                        'id' => '/klinik_bedah',
                        'title' => 'Poli Bedah'
                    ],
                    [
                        'id' => '/klinik_paru',
                        'title' => 'Poli Paru'
                    ],
                    [
                        'id' => '/klinik_jiwa',
                        'title' => 'Poli Jiwa'
                    ],
                    [
                        'id' => '/klinik_gigi',
                        'title' => 'Poli Gigi'
                    ],
                    [
                        'id' => '/klinik_jantung',
                        'title' => 'Poli Jantung'
                    ]
                ]
            ];

            // Bagian pilihan fasilitas unggulan

        } else if ($msg == '/info_fasilitas') {

            $response =  [
                'message_type' => 'array',
                'title' => 'Pilih Layanan',
                'body' => 'Berikut adalah Fasilitas Unggulan di Rumah Sakit Livasya. Silahkan klik tombol dibawah untuk info selengkapnya.',
                'data' => [
                    [
                        'id' => '/foto_bayi',
                        'title' => 'Baby Newborn Photo'
                    ],
                    [
                        'id' => '/baby_spa',
                        'title' => 'Baby Spa Swimming'
                    ],
                    [
                        'id' => '/maternity',
                        'title' => 'Maternity'
                    ],
                    [
                        'id' => '/partus_moment',
                        'title' => 'Partus Moment'
                    ],
                    [
                        'id' => '/senam_hamil',
                        'title' => 'Senam Hamil'
                    ]
                ]
            ];

            // Bagian informasi pendaftaran

        } else if ($msg == '/info_pendaftaran') {

            $response =  [
                'message_type' => 'array',
                'title' => 'Pilih Layanan',
                'body' => 'Silahkan klik tombol dibawah untuk info selengkapnya.',
                'data' => [
                    [
                        'id' => '/info_bpjs_asuransi',
                        'title' => 'Info BPJS'
                    ],
                    [
                        'id' => '/syarat',
                        'title' => 'Syarat Pendaftaran'
                    ],
                    [
                        'id' => '/prosedur',
                        'title' => 'Prosedur Pendaftaran'
                    ],
                    [
                        'id' => '/info_dafol',
                        'title' => 'Pendaftaran Online'
                    ]
                ]
            ];

            // Isi Respon Menu Pendaftaran

        } else if ($msg == '/usg_persalinan') {

            $response =  [
                'message_type' => 'array',
                'title' => 'Pilih Layanan',
                'body' => 'Silahkan klik tombol dibawah untuk info selengkapnya.',
                'data' => [
                    [
                        'id' => '/info_usg',
                        'title' => 'Biaya USG'
                    ],
                    [
                        'id' => '/info_persalinan',
                        'title' => 'Biaya Persalinan'
                    ]
                ]
            ];

            // Isi Respon Menu Pendaftaran

        } else if ($msg == '/info_bpjs_asuransi') {
            $response = [
                'message_type' => 'string',
                'data' => "*Informasi BPJS* \n\nSaat ini rumah sakit livasya menyediakan layanan rawat inap dan rawat jalan khusus peserta BPJS/JKN/KIS mulai dari :\n\n▪️ Spesialis Anak\n▪️ Spesialis kandungan\n▪️ Spesialis bedah\n▪️ Spesialis penyakit dalam\n▪️ Spesialis THT"
            ];
        } else if ($msg == '/syarat') {
            $response = [
                'message_type' => 'string',
                'data' => "*Syarat pendaftaran*\n\n*Syarat pendaftaran rawat jalan*\n▪️ khusus peserta penjamin umum cukup membawa data diri/kartu berobat\n▪️ khusus peserta BPJS/JKN/KIS cukup membawa rujukan faskes 1 dan data diri\n▪️ khusus peserta asuransi swasta cukup membawa karru asuransi dan data diri\n\n*Syarat pendaftaran rawat inap*\n▪️ khusus peserta penjamin umum cukup membawa identitas diri\n▪️ khusus peserta penjamin BPJS/JKN/KIS Cukup membawa kartu identitas ,KK,KTP dan kartu BPJS (Bila ada)\n▪️ khusus peserta asuransi swasta cukup membawa kartu asuransi dan data diri"
            ];
        } else if ($msg == '/info_dafol') {
            $response = [
                'message_type' => 'string',
                'data' => "*Pendafataran online khusus penjamin JKN/BPJS silahkan bapak/ibu akses melalui Aplikasi MOBILE JKN* \n\nPendafataran online khusus penjamin Umum/asuransi silahkan bapak/ibu akses melalui link website berikut :  https://dafol.livasya.com/ \n\n*Note* : \n▪️ Pendaftaran online By link website dapat diakses H-3 atau paling lambat H-1 sebelum tanggal kunjungan\n▪️ Pendafataran online By Mobile JKN(BPJS) dapat diakses H-30 dan paling lambat di hari H sebelum jam praktek poliklinik"
            ];
        } else if ($msg == '/info_usg') {
            $response = [
                'message_type' => 'string',
                'data' => "*Biaya USG*\n\n- *USG 2D* 195k\n- *USG 3D* 265k\n- *USG 4D* 325k\n- *TRANSVAGINAL* 350k+-\n\n*Note* : berikut kisaran estimasi include Konsul dokter + usg +print usg dan admin"
            ];

            // Bagian Prosedur

        } else if ($msg == '/prosedur') {
            $response = [
                'message_type' => 'string',
                'data' => "*Prosedur pendaftaran*\n\nLayanan pendaftaran poliklinik  bisa diakses melalui online maupun onsite sesuai dengan jam praktek poliklinik"
            ];

            // Bagian Medical Checkup

        } else if ($msg == '/info_persalinan') {
            $response = [
                'message_type' => 'string',
                'data' => "*Biaya Persalinan*\n\n*Biaya Persalinan Normal*\n- *Superior* : Mulai dari 6,5t\n- *Deluxe 2* : Mulai dari 7jt\n- *Deluxe 1* : Mulai dari 8,5jt\n- *VIP* : Mulai dari 9 jt\n\n*Biaya Persalinan Caesar*\n- *Superior* : Mulai dari 12jt\n- *Deluxe 2* : Mulai dari 14jt\n- *Deluxe 1* : Mulai dari 16jt\n- *VIP* : Mulai dari 18jt\n\n*Fasilitas yang diberikan* : Vaksin Hb + Polio, Foto Baby setelah melahirkan, dan bingkisan.\n*Fasilitas tambahan* : Foto Maternity dan Video Partus Moment (video persalinan khusus VIP), namun harus booking terlebih dahulu min. 1bulan sebelum HPL\n\n*NOTE* : Biaya tsb hanya sbg perkiraan total. Biaya total bisa kurang/lebih dari daftar diatas."
            ];

            // Bagian Medical Checkup

        } else if ($msg == '/info_cs') {
            $response = [
                'message_type' => 'string',
                'data' => "*Costumer Service*\n\nSalam sehat, terima kasih sudah menghubungi kontak Customer Care Rumah Sakit Livasya Majalengka.\n\nJam Operasional\nIGD 24 Jam\nPoliklinik Senin - Sabtu 08:00 - 21:00\n\nUntuk layanan telepon anda dapat menghubungi nomor kami di:\nhotline +622338668019\nwhatsapp: +6281211151300\n\nKunjungi website kami di www.livasya.co.id untuk mendapatkan informasi lengkap mengenai rumah sakit dan jadwal dokter"
            ];

            // Bagian Rawat Inap

        } else if ($msg == '/info_medical') {
            $response = [
                'message_type' => 'string',
                'data' => "*Medical Check - Up*\n\n*Paket Silver*\n\n- Pemeriksaan fisik dan buta warna oleh dokter umum\n- Pemeriksaan gula darah\n- Pemeriksaan kolesterol\n- Pemeriksaan asam urat\n\n*Biaya : Rp. 150.000*\n\n*Paket Gold*\n\n- Pemeriksaan fisik dan buta warna oleh dokter umum\n- Hematologi rutin\n- Gula darah puasa\n- Kolesterol\n- Trigliserida\n- Asam urat\n- Ureum\n- Kreatinin\n- SGOT\n- Urine lengkap\n\n*Biaya : Rp. 700.000*\n\n*Paket Diamond*\n\n- Pemeriksaan fisik dan buta warna oleh dokter umum\n- Hematologi rutin\n- Gula darah puasa\n- Kolesterol\n- Trigliserida\n- Asam urat\n- Ureum\n- Kreatinin\n- SGOT\n- SGPT\n- Urine lengkap\n- EKG\n- Rontgen thorax\n\n*Biaya : Rp. 920.000*"
            ];

            // Bagian Rawat Inap

        } else if ($msg == '/igd') {
            $response = [
                'message_type' => 'string',
                'data' => "*Instalasi Gawat Darurat - IGD*\n\nPelayanan IGD (Instalasi Gawat Darurat) 24 jam di Rumah Sakit Livasya adalah salah satu fasilitas krusial yang menyediakan layanan medis mendesak sepanjang hari, tujuh hari seminggu. Berikut adalah penjelasan singkat tentang IGD 24 jam Rumah Sakit Livasya:\n\n- *Aksesibilitas* : IGD Rumah Sakit Livasya buka 24 jam setiap hari, sehingga siap menerima pasien yang membutuhkan perawatan medis mendesak kapan pun dibutuhkan. Ini memastikan bahwa pasien dapat mengakses perawatan medis dengan cepat, tanpa harus menunggu jam kerja normal.\n- *Tim Medis Siap Sedia* : IGD Rumah Sakit Livasya dilengkapi dengan tim medis yang terlatih dan siap sedia untuk menangani berbagai jenis keadaan darurat. Tim ini terdiri dari dokter, perawat, dan tenaga medis lainnya yang memiliki pengalaman dan keterampilan dalam menangani situasi medis yang mendesak.\n- *Peralatan dan Fasilitas Medis* : Instalasi Gawat Darurat Rumah Sakit Livasya dilengkapi dengan peralatan medis canggih dan fasilitas pendukung yang diperlukan untuk menangani berbagai kondisi medis darurat. Ini termasuk peralatan resusitasi, ruang operasi darurat, ruang observasi, dan fasilitas pencitraan medis seperti X-ray\n- *Penanganan Kasus Mendesak* : IGD Rumah Sakit Livasya menerima berbagai kasus medis mendesak, termasuk kecelakaan, cedera, dan keadaan darurat lainnya. Tim medis di sini terlatih untuk menangani kasus-kasus ini dengan cepat dan efektif, memberikan perawatan yang tepat sesuai dengan kondisi pasien.\n- *Prioritas pada Kepedulian dan Kehati-hatian* : Meskipun memberikan perawatan medis yang cepat, tim medis IGD Rumah Sakit Livasya tetap menjaga kehati-hatian dan memastikan bahwa setiap langkah yang diambil sesuai dengan standar keamanan dan kualitas yang tinggi. Kepedulian terhadap kebutuhan dan kondisi pasien juga menjadi prioritas utama dalam setiap tindakan yang dilakukan.\n- *Koordinasi dengan Unit Perawatan Lanjutan* : Setelah mendapatkan perawatan di IGD, pasien yang membutuhkan perawatan lebih lanjut akan dirujuk ke unit perawatan lanjutan di Rumah Sakit Livasya atau di fasilitas kesehatan lainnya, jika diperlukan. Koordinasi yang baik antara tim medis di IGD dan unit perawatan lanjutan penting untuk memastikan kelancaran proses perawatan pasien.\n\nDengan pelayanan IGD 24 jam yang komprehensif, tim medis yang terlatih, dan fasilitas medis yang lengkap, Rumah Sakit Livasya berkomitmen untuk memberikan perawatan medis berkualitas tinggi kepada setiap pasien yang membutuhkan bantuan medis mendesak, kapan pun diperlukan.*"
            ];

            // Bagian Rawat Inap

        } else if ($msg == '/rawat_inap') {
            $response = [
                'message_type' => 'string',
                'data' => "*Fasilitas Rawat Inap*\n\n*VIP Room*\nNikmati kenyamanan maksimal dengan fasilitas eksklusif dalam ruangan pribadi yang dirancang untuk memberikan privasi dan ketenangan:\n- Bed pasien (1 pasien per ruangan)\n- Box bayi\n- Sofa bed untuk pendamping\n- Kamar mandi dalam\n- Nakas\n- Meja Mayo & Meja Crandenza\n- TV LED\n- AC untuk kesejukan optimal\n- Dispenser untuk kemudahan akses air minum\n- Lemari es untuk menyimpan makanan & minuman\n\n*Deluxe 1*\nKombinasi kenyamanan dan efisiensi dalam ruangan yang tetap memberikan privasi:\n- Bed pasien (1 ruangan berkapasitas 2 pasien)\n- Kursi tunggu untuk keluarga/pengunjung\n- Box bayi\n- Kamar mandi dalam\n- Nakas\n- Meja Mayo & Meja Crandenza\n- TV LED\n- AC\n- Lemari es\n\n*Deluxe 2*\nPilihan ideal dengan fasilitas lengkap untuk kenyamanan pasien dan pendamping:\n- Bed pasien (1 ruangan berkapasitas 2 pasien)\n- Kursi tunggu untuk pendamping\n- Box bayi\n- Kamar mandi dalam\n- Nakas\n- Meja Mayo & Meja Crandenza\n- TV LED\n- AC\n\n*Superior Room*\nKamar dengan fasilitas standar terbaik untuk mendukung pemulihan pasien:\n- Bed pasien (1 ruangan berkapasitas 2 pasien)\n- Kursi tunggu untuk pendamping\n- Box bayi\n- Kamar mandi dalam\n- Nakas\n- Meja Mayo & Meja Crandenza\n- TV LED\n- AC\n\nSetiap kamar di RS Livasya dirancang untuk memberikan kenyamanan, keamanan, dan kemudahan bagi pasien serta keluarga."
            ];
        } else if ($msg == '/layanan_fasilitas') {

            $response =  [
                'message_type' => 'array',
                'title' => 'Pilih Layanan',
                'body' => 'Silahkan klik tombol dibawah untuk info selengkapnya.',
                'data' => [
                    [
                        'id' => '/igd',
                        'title' => 'IGD (24 Jam)'
                    ],
                    [
                        'id' => '/rawat_inap',
                        'title' => 'Rawat Inap'
                    ],
                    [
                        'id' => '/jadwal_praktek',
                        'title' => 'Rawat Jalan'
                    ],

                    [
                        'id' => '/layanan_vaksin',
                        'title' => 'Layanan Vaksin'
                    ],
                    [
                        'id' => '/info_fasilitas',
                        'title' => 'Fasilitas'
                    ],
                ]
            ];
        } else if ($msg == '/layanan_vaksin') {
            $response = [
                'message_type' => 'string',
                'data' => "*Imunisasi dasar dan tambahan*\n- vaxigrip/influenza: 570.265\n- Influvac/influenza :  549.497\n- Rotarix / Rotavirus: 829.864\n- Synflorix / pcv: 1.710.510\n- prevenar Injc 13 /PCV : 1.349.224\n- PCV dinkes : 28.585\n- Varivax / varicella: 943.764\n- BCG  dinkes : 28.585\n- DPT Pentabio Dinkes (Demam) : 28.585\n- DPT Hexaxim (Tanpa demam) : 2.018.424\n- DPT Infanrix (Tanpa Demam) :  2.098.524\n- MR/Campak dinkes : 28.585\n- MMR 2 : 677.481\n- TYPHIM /thypoid: 571.337\n- Rotavac Dinkes /rotavirus : 28.585\n- Havrix 720 Junior : 834.388\n- Polio tetes (Dinkes) : 28.585\n- Polio Injek ( Dinkes) : 28.585\n- HB 0 : 28.585\n- Cervarix : 1.105.443\n- Gardasil 4' INJ : 1.584.959\n- Gardasil 9' INJ : 3.177.340\n- Imojev : 977.919\n- TD : 28.585\n\n*Note:*\n1. Harga belum termasuk Konsul dokter +Admin+embalase\n2. Beberapa vaksin mengikuti Sistem PO ,konfirmasi ketersediaan terlebih dahulu\n3. Khusus vaksin yang mengikuti sistem PO akan dikenakan biaya Deposit sebelum pemesanan\n4. Deposit pemesanan tidak dapat dikembalikan bila sewaktu cancel / tidak jadi vaksinasi\n5. Khusus vaksin dalam PO, kedatangan vaksin tidak bisa diestimasikan"
            ];
        } else if ($msg == '/klinik_obgyn') {
            $response = [
                'message_type' => 'string',
                'data' => "*Jadwal praktek poliklinik Obgyn*\n\n*dr Dindaadi kusuma Sp.OG*\nSenin-kamis : 08.30-13.00\nJumat-sabtu : 13.00-15.00\n\n*dr H Mohammad Taufiq Sp.OG*\nJumat dan sabtu : 8.30-10.30\nSenin- sabtu : 16.00-18.00\nNote : hari libur/tanggal merah tidak ada praktek"
            ];
        } else if ($msg == '/klinik_anak') {
            $response = [
                'message_type' => 'string',
                'data' => "*JADWAL POLI ANAK*\n\n- Pasien umum\n\n*Dr. Tina Restu Sp.A*\nSenin-sabtu Pukul : 07.00-09.00\n\n*Dr. Ratih Sp.A*\nSenin, Rabu Pukul : 14.30 - Selesai\nSelasa,kamis Jumat Pukul :15.30 - Selesai\n- Pasien BPJS dengan Rujukan faskes 1\n\n*Dr. Tina Restu Sp.A*\nSenin - Jumat  Pukul : 13.00 - Selesai\n\n*Dr. Ratih Sp.A*\nSenin,Rabu  Pukul : 14.30 - Selesai\nSelasa , kamis dan jumat  Pukul : 15.30-Selesai\n\nHari libur/tanggal merah tidak praktek"
            ];
        } else if ($msg == '/klinik_tht') {
            $response = [
                'message_type' => 'string',
                'data' => "*JADWAL POLIKLINIK THT*\n\nPasien umum,Asuransi,BPJS\n\n*dr. H.M.Nuruddin Zainudin, Sp.THT-KL*\n\nSelasa & Kamis\nPukul : 12.00-Selesai"
            ];
        } else if ($msg == '/klinik_dalam') {
            $response = [
                'message_type' => 'string',
                'data' => "*JADWAL POLIKLINIK PENYAKIT DALAM*\n\n Pasien umum,asuransi,BPJS\n\n*dr. Zikry Aulia Hidayat, Sp.PD*\n\nSenin,rabu,Jumat\nPukul 16.00-Selesai\n\n*dr. Jansen budiono Sp.PD*\n\nSelasa dan Kamis\nPukul 15.00-Selesai"
            ];
        } else if ($msg == '/klinik_bedah') {
            $response = [
                'message_type' => 'string',
                'data' => "*Jadwal Poliklinik Bedah Umum*\n\n*dr. Rizky Baihaqi Sp.B*\n\nSenin-Sabtu\nPukul 08.00-12.00\n\nJumat\nPukul 08.00-11.00\n\nMelayani peserta JKN/BPJS,Umum dan Asuransi Swasta"
            ];
        } else if ($msg == '/klinik_paru') {
            $response = [
                'message_type' => 'string',
                'data' => "*Jadwal Poliklinik spesialis Paru*\n\n*dr Tania libristina ambun Sp.P*\n\nSelasa, Rabu, Kamis\nPkl 15.00-17.00\n\nBerlaku dengan reservasi H-1"
            ];
        } else if ($msg == '/klinik_jiwa') {
            $response = [
                'message_type' => 'string',
                'data' => "*Jadwal Poliklinik Spesialis Kedokteran Jiwa*\n\n*dr Agri Mohammad iqbal Sp.KJ*\n\nSenin,Rabu,Jumat\nPkl. 08.00-10.00\n\nBerlaku dengan reservasi H-1"
            ];
        } else if ($msg == '/klinik_gigi') {
            $response = [
                'message_type' => 'string',
                'data' => "*Jadwal Poliklinik Gigi*\n\n*drg. Viki Dwi prananda*\n\nSenin-Jumat\nPkl . 08.00-15.00\n\nSabtu\nPkl 08.00-12.00"
            ];
        } else if ($msg == '/klinik_jantung') {
            $response = [
                'message_type' => 'string',
                'data' => "Jadwal belum ada"
            ];

            // Bagian Fasilitas unggulan

        } else if ($msg == '/foto_bayi') {
            $response = [
                'message_type' => 'string',
                'data' => "*Baby Newborn Photoshoot*\n\nRS Livasya di Majalengka menawarkan layanan fotografi bayi baru lahir (newborn baby photography) yang bertujuan mengabadikan momen berharga si kecil. Layanan ini mencakup berbagai genre fotografi, seperti:\n\nFocusing: Memotret bayi tanpa tambahan aksesoris, menonjolkan keaslian dan kemurnian.\nLifestyle: Mengabadikan interaksi keluarga dengan bayi di rumah sakit atau saat membawa pulang bayi.\nKomersial: Foto yang digunakan untuk promosi produk bayi atau keperluan komeRSl lainnya.\n\n*Biaya untuk Baby Newborn Photoshoot*\nRp 200.000\n(dapat 1 file dan 1 cetak foto + figura)\nRp 50.000\n(untuk menambah file)\nRp 200.000\n(untuk penambahan foto cetak)\n\nUntuk informasi lebih lanjut mengenai layanan ini, Anda dapat mengunjungi situs resmi RS Livasya di https://livasya.com/fasilitas/newborn-baby-photography.\n\nSelain itu, RS Livasya juga menyediakan layanan fotografi maternity shoot untuk mengabadikan momen kehamilan.\n\nInformasi lebih lanjut dapat ditemukan di\nhttps://www.livasya.co.id/fasilitas/photography-maternity-shoot.\nUntuk melihat contoh hasil fotografi bayi baru lahir di RS Livasya, Anda dapat menonton video berikut:"
            ];
        } else if ($msg == '/baby_spa') {
            $response = [
                'message_type' => 'string',
                'data' => "*Baby Spa and Swim*\n\nRS Livasya di Majalengka menyediakan layanan Baby Spa yang terdiri dari hidroterapi dan pijat bayi. Layanan ini tersedia setiap hari mulai pukul 09.00 hingga 15.00. Sebagai promo, setiap kunjungan akan mendapatkan voucher gratis; kumpulkan 10 voucher untuk mendapatkan 1 kali pijat bayi gratis.\n\nAlamat RS Livasya: Jl. Raya Timur III No.875, Dawuan, Kec. Dawuan, Kabupaten Majalengka, Jawa Barat 45453. Untuk informasi lebih lanjut, Anda dapat menghubungi nomor telepon (0233) 8668019 atau WhatsApp di 0812-1115-1300.\n\nSebelum membawa si kecil untuk sesi Baby Spa, pastikan untuk memeriksa kebersihan fasilitas dan bahan yang digunakan guna menghindari risiko alergi atau iritasi pada kulit bayi.\n\n*Biaya untuk Baby Newborn Photoshoot*\n\n*1 Paket Baby Spa*\nRp 150.000\n*Baby Message*\nRp 60.000\n*Baby Swim*\nRp 60.000\n*Foto Bayi (Baby Spa)*\nRp 60.000\n*Tindik Bayi*\nRp 65.000"
            ];
        } else if ($msg == '/maternity') {
            $response = [
                'message_type' => 'string',
                'data' => "*Foto Maternity*\n\nRS Livasya di Majalengka menyediakan layanan Photography Maternity Shoot untuk mengabadikan momen kehamilan Anda. Layanan ini dirancang khusus bagi ibu hamil yang ingin mendokumentasikan masa kehamilan mereka melalui sesi pemotretan profesional.\n\n*Biaya untuk Partus Moment*\nRp 300.000."
            ];
        } else if ($msg == '/partus_moment') {
            $response = [
                'message_type' => 'string',
                'data' => "*Partus Moment*\n\RS Livasya di Majalengka menyediakan layanan Partus Moment, yaitu dokumentasi profesional selama proses persalinan untuk mengabadikan momen berharga kelahiran buah hati Anda. Layanan ini dirancang untuk menangkap setiap detik penting dan emosi yang terjadi selama proses persalinan, sehingga menjadi kenangan yang tak terlupakan bagi keluarga.\n\n*Biaya Partus Moment*\nRp 2.000.000\n\nUntuk melihat contoh dari layanan Partus Moment, Anda dapat mengunjungi kanal YouTube resmi RS Livasya, di mana terdapat berbagai video dokumentasi persalinan yang telah diabadikan sebelumnya. Salah satunya adalah video persalinan normal Ny. Devi & Tn. Dani yang dapat Anda saksikan melalui tautan berikut:\n\nJika Anda tertarik untuk memanfaatkan layanan ini atau memerlukan informasi lebih lanjut, silakan menghubungi RS Livasya melalui nomor telepon (0233) 8668019 atau WhatsApp di 0812-1115-1300. Alamat RS Livasya: Jl. Raya Timur III No.875, Dawuan, Kec. Dawuan, Kabupaten Majalengka, Jawa Barat 45453.\n\nRS Livasya berkomitmen untuk memberikan pelayanan terbaik dalam setiap momen berharga Anda dan keluarga."
            ];
        } else if ($msg == '/senam_hamil') {
            $response = [
                'message_type' => 'string',
                'data' => "*Senam Hamil*\n\nRS Livasya di Majalengka menyediakan layanan senam hamil yang dirancang khusus untuk membantu ibu hamil mempersiapkan diri secara fisik dan mental menjelang persalinan. Program ini bertujuan untuk meningkatkan kebugaran, mengurangi ketidaknyamanan selama kehamilan, serta mempersiapkan tubuh untuk proses persalinan yang lancar.\n\n*Biaya untuk Senam Hamil\nRp 50.000\n\n*Manfaat Senam Hamil di RS Livasya:*\n\nMeningkatkan Kebugaran Fisik: Latihan terstruktur membantu menjaga stamina dan kekuatan otot selama kehamilan.\nMengurangi Ketidaknyamanan: Gerakan senam dapat membantu mengurangi nyeri punggung, kram kaki, dan pembengkakan.\nPersiapan Persalinan: Melatih teknik pernapasan dan relaksasi yang berguna saat proses persalinan.\nDukungan Emosional: Bertemu dengan sesama ibu hamil dapat memberikan dukungan dan berbagi pengalaman.\nInformasi Tambahan:\n\nBiaya: Biaya senam hamil di rumah sakit swasta di Indonesia umumnya berkisar antara Rp 25.000 hingga lebih dari Rp 200.000 per sesi. Untuk informasi tarif spesifik di RS Livasya, disarankan menghubungi langsung pihak rumah sakit.\nALODOKTER\nJadwal: Jadwal senam hamil dapat berbeda-beda. Sebaiknya Anda menghubungi RS Livasya untuk mendapatkan informasi terkini mengenai jadwal kelas.\nKontak RS Livasya:\n\nAlamat: Jl. Raya Timur III No.875, Dawuan, Kec. Dawuan, Kabupaten Majalengka, Jawa Barat 45453\nTelepon: (0233) 8668019\nWhatsApp: 0812-1115-1300\nEmail: contact@livasya.com\nUntuk informasi lebih lanjut mengenai layanan senam hamil dan fasilitas lainnya, Anda dapat mengunjungi situs resmi RS Livasya di https://www.livasya.com/.\n\nRS Livasya berkomitmen untuk mendukung kesehatan ibu dan anak melalui berbagai layanan yang komprehensif dan profesional.."
            ];
        } else {
            // Else untuk menampilkan menu jika input tidak dikenali
            $response = [
                'message_type' => 'array',
                'title' => 'Pilih Layanan',
                'body' => 'Mohon maaf, Sahabat Livasya. Silakan ketik ulang kebutuhan Anda dengan benar, pilih salah satu dari menu berikut, atau ketik "halo" untuk menampilkan menu.',
                'data' => [
                    [
                        'id' => '/info_pendaftaran',
                        'title' => 'Info Pendaftaran'
                    ],
                    [
                        'id' => '/jadwal_praktek',
                        'title' => 'Jadwal Dokter'
                    ],
                    [
                        'id' => '/layanan_fasilitas',
                        'title' => 'Layanan & Fasilitas'
                    ],
                    [
                        'id' => '/info_fasilitas',
                        'title' => 'Fasillitas Unggulan'
                    ],
                    [
                        'id' => '/usg_persalinan',
                        'title' => 'USG & Persalinan'
                    ],
                    [
                        'id' => '/info_dafol',
                        'title' => 'Daftar Online'
                    ],
                    [
                        'id' => '/info_medical',
                        'title' => 'Medical Check-Up'
                    ],
                    [
                        'id' => '/info_cs',
                        'title' => 'Customer Service'
                    ]
                ]
            ];
        }

        return response()->json($response);


        // return response()->json(['error' => ($error ? "1" : "0"), $response]);
    }

    public function notifyExpiryContract(Request $request)
    {
        // return 'Berhasil';

        // Cek apakah metode POST
        if ($request->getMethod() !== 'POST') {
            return response()->json(['error' => 1, 'data' => 'ok cuy'], 405);
        }

        // Ambil data dari header dan JSON
        $headers = $request->headers->all();

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

        $responsePegawai = '';
        $responseHRD = '';
        $employees = Employee::where('is_active', 1)->whereMonth('end_status_date', Carbon::now()->month)->whereYear('end_status_date', Carbon::now()->year)->orderBy('end_status_date', 'asc')->get();
        $headers = [
            'Key:KeyAbcKey',
            'Nama:arul',
            'Sandi:123###!!',
        ];

        // Data untuk request HTTP
        $responseHRD .= "*DAFTAR PEGAWAI YANG AKAN HABIS KONTRAK* \n\n";
        if ($employees->count() > 0) {
            foreach ($employees as $employee) {
                $responsePegawai .= "*INFO KONTRAK AKAN BERAKHIR* \n\n";
                $responsePegawai .= "Halo kak, *" . $employee->fullname . "*, kontrakmu akan berakhir pada tanggal " . tgl(Carbon::parse($employee->end_status_date)->format('Y-m-d')) . ". Harap konfirmasi kebagian HRD untuk kontrak selanjutnya ya! 😇.\n\n";
                $responsePegawai .= "_Reported automatic by: Smart HR_";

                if ($employee->mobile_phone) {
                    $httpData = [
                        'number' => formatNomorIndo($employee->mobile_phone),
                        'message' => $responsePegawai,
                    ];

                    // Mengirim request HTTP menggunakan cURL
                    $curl = curl_init();
                    curl_setopt($curl, CURLOPT_URL, 'http://192.168.0.100:3001/send-message');
                    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
                    curl_setopt($curl, CURLOPT_POST, 1);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $httpData);
                    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

                    $response = curl_exec($curl);
                    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                    $curlError = curl_error($curl);
                    curl_close($curl);
                }
                $responsePegawai = '';
                $responseHRD .= "🔸 " . $employee->fullname . " (" . tgl(Carbon::parse($employee->end_status_date)->format('Y-m-d')) . ") \n";
            }

            $responseHRD .= "\n _Reported automatic by: Smart HR_";

            $hrdList = Employee::where('organization_id', 31)->latest()->get();
            $responses = [];

            foreach ($hrdList as $hrd) {
                $httpDataHRD = [
                    'number' => formatNomorIndo($hrd->mobile_phone),
                    'message' => $responseHRD,
                ];

                // Mengirim request HTTP menggunakan cURL
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, 'http://192.168.0.100:3001/send-message');
                curl_setopt($curl, CURLOPT_TIMEOUT, 30);
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $httpDataHRD);
                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

                $response = curl_exec($curl);
                $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                $curlError = curl_error($curl);
                curl_close($curl);

                // Menyimpan hasil respons untuk setiap HRD
                $responses[] = [
                    'employee_id' => $hrd->id,
                    'number' => $httpDataHRD['number'],
                    'http_code' => $httpCode,
                    'response' => $response,
                    'error' => $curlError,
                ];
            }

            // Mengembalikan hasil dalam bentuk JSON
            return response()->json(['results' => $responses]);
        }
    }

    public function notifyExpiryDocumentToHRD(Request $request)
    {
        // Pastikan method POST
        if ($request->getMethod() !== 'POST') {
            return response()->json(['error' => 1, 'data' => 'Method tidak diizinkan'], 405);
        }

        // Validasi header
        $headers = $request->headers->all();
        $key = $headers['key'][0] ?? '';
        $user = $headers['nama'][0] ?? '';
        $sandi = $headers['sandi'][0] ?? '';

        if (!($key == 'KeyAbcKey' && $user == 'arul' && $sandi == '123###!!')) {
            return response()->json(['error' => 1, 'data' => 'Gagal proses'], 403);
        }

        $today = \Carbon\Carbon::now();
        $expiryThreshold = $today->copy()->addDays(30); // H-30

        // Ambil dokumen yang hampir habis masa berlakunya
        $documents = UploadFile::whereNotNull('expire')
            ->whereDate('expire', '<=', $expiryThreshold)
            ->whereDate('expire', '>=', $today)
            ->orderBy('expire', 'asc')
            ->get();

        if ($documents->isEmpty()) {
            return response()->json(['message' => 'Tidak ada dokumen yang hampir kadaluarsa']);
        }

        // Ambil list HRD
        $hrdList = Employee::where('organization_id', 31)->whereNotNull('mobile_phone')->get();

        $headersCurl = [
            'Key:KeyAbcKey',
            'Nama:arul',
            'Sandi:123###!!',
        ];

        $responses = [];

        // Buat message untuk HRD
        $messageHRD = "*DAFTAR DOKUMEN KEPEGAWAIAN HAMPIR KADALUARSA*\n\n";
        foreach ($documents as $doc) {
            $employeeName = $doc->employee->fullname ?? '-';
            $expireDate = \Carbon\Carbon::parse($doc->expire)->locale('id')->isoFormat('DD MMMM YYYY');
            $messageHRD .= "🔸 {$doc->nama} ({$employeeName}) akan berakhir pada {$expireDate}\n";
        }
        $messageHRD .= "\n_Reported automatic by: Smart HR_";

        // Kirim notifikasi ke semua HRD
        foreach ($hrdList as $hrd) {
            $httpDataHRD = [
                'number' => formatNomorIndo($hrd->mobile_phone),
                'message' => $messageHRD,
            ];

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, 'http://192.168.0.100:3001/send-message');
            curl_setopt($curl, CURLOPT_TIMEOUT, 30);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $httpDataHRD);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headersCurl);

            $response = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $curlError = curl_error($curl);
            curl_close($curl);

            $responses[] = [
                'employee_id' => $hrd->id,
                'number' => $httpDataHRD['number'],
                'http_code' => $httpCode,
                'response' => $response,
                'error' => $curlError,
            ];
        }

        return response()->json(['results' => $responses]);
    }
}
