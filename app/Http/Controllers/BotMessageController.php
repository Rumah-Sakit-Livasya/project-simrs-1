<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BotMessageController extends Controller
{
    public function processMessage(Request $request)
    {
        // Cek apakah metode POST
        if ($request->getMethod() !== 'POST') {
            return response()->json(['error' => 1, 'data' => 'ok cuy'], 405);
        }

        // Ambil data dari header dan JSON
        $headers = $request->headers->all();
        $content = $request->json()->all();

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

        // Logika bisnis
        $msg = $content['message'] ?? '';
        $data = $content['data'] ?? [];
        $response = '';

        if ($msg == '/rekapabsen') {
            $total_pegawai = Employee::where('is_active', 1)->count();
            $total_clockin = Attendance::whereNotNull('clock_in')
                ->whereDate('date', Carbon::now()->format('Y-m-d'))
                ->whereHas('employees', function ($query) {
                    $query->where('is_active', 1); // Hanya untuk karyawan yang aktif
                })->count();
            $total_no_clockin = Attendance::whereNull('clock_in')->whereNull('is_day_off')
                ->whereDate('date', Carbon::now()->format('Y-m-d'))
                ->whereHas('employees', function ($query) {
                    $query->where('organization_id', '!=', 3);
                    $query->where('is_active', 1); // Hanya untuk karyawan yang aktif
                })->count();
            $total_libur = Attendance::where('is_day_off', 1)
                ->whereNull('attendance_code_id')
                ->whereNull('day_off_request_id')
                ->whereDate('date', Carbon::now()->format('Y-m-d'))
                ->whereHas('employees', function ($query) {
                    $query->where('is_active', 1); // Hanya untuk karyawan yang aktif
                })->count();
            $total_izin = 0;
            $total_sakit = 0;
            $total_cuti = 0;
            $absensi_pegawai = Attendance::where('is_day_off', '!=', null)->where('date', Carbon::now()->format('Y-m-d'))->get();
            foreach ($absensi_pegawai as $absensi) {
                if ($absensi->attendance_code_id != null || $absensi->day_off_request_id != null) {
                    if ($absensi->attendance_code_id == 1) {
                        $total_izin += 1;
                    } elseif ($absensi->attendance_code_id == 2) {
                        $total_sakit += 1;
                    } elseif ($absensi->attendance_code_id != 1 && $absensi->attendance_code_id != 2) {
                        $total_cuti += 1;
                    } elseif ($absensi->attendance_code_id == null || $absensi->attendance_code_id == "") {
                        // Jika attendance_code_id di Attendance tidak ada, cek di DayOffRequest melalui relasi day_off
                        if ($absensi->day_off) {
                            // Cek apakah day_off_request memiliki attendance_code_id yang diinginkan
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
            $response .= "🔹 <code>Total Pegawai: $total_pegawai </code>\n";
            $response .= "🔹 <code>Sudah clockin: $total_clockin </code>\n";
            $response .= "🔹 <code>Belum clockin: $total_no_clockin </code>\n";
            $response .= "🔹 <code>Pegawai libur: $total_libur </code>\n";
            $response .= "🔹 <code>Pegawai Cuti: $total_cuti </code>\n";
            $response .= "🔹 <code>Pegawai Izin: $total_izin </code>\n";
            $response .= "🔹 <code>Pegawai Sakit: $total_sakit </code>\n\n";

            $response .= "\n🟥 <b>DAFTAR PEGAWAI YANG TELAT:</b> \n\n";
            $pegawai_telat = Attendance::whereNotNull('clock_in')->whereNotNull('late_clock_in')->where('date', Carbon::now()->format('Y-m-d'))->orderBy('late_clock_in')->get();
            foreach ($pegawai_telat as $key => $row) {
                if ($row->late_clock_in > 5 && $row->late_clock_in < 70) {
                    $response .= "🔸" . Str::limit($row->employees->fullname, $limit = 16) . " ( " . $row->late_clock_in . " menit )\n";
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
                        ->whereHas('shift', function ($query) {
                            $query->where('time_in', '>', '17:00:00') // Menambahkan kondisi time_in > 04:00:00
                                ->where('time_in', '<', '20:00:00'); // Menambahkan kondisi time_in < 09:00:00
                        })->whereHas('employees', function ($query) {
                            $query->where('is_active', 1); //Hanya untuk karyawan yng aktif
                            $query->where('company_id', 2); // Hanya untuk karyawan PT
                        })
                        ->where('date', Carbon::now()->format('Y-m-d'))
                        ->get();

                    $absent_malam_rs = Attendance::where('clock_in', null)->where('is_day_off', null)
                        ->whereHas('shift', function ($query) {
                            $query->where('time_in', '>', '17:00:00') // Menambahkan kondisi time_in > 04:00:00
                                ->where('time_in', '<', '20:00:00'); // Menambahkan kondisi time_in <b 09:00:00
                        })->whereHas('employees', function ($query) {
                            $query->where('is_active', 1); //Hanya untuk karyawan yng aktif
                            $query->where('company_id', 1); // Hanya untuk karyawan RS
                        })
                        ->where('date', Carbon::now()->format('Y-m-d'))
                        ->get();

                    $response .= "\n🔴 <b>DAFTAR KARYAWAN YANG TIDAK ABSEN MALAM ‼️ </b>\n\n";
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
        } else {
            $idTelegram = isset($data['id']) ? $data['id'] : null;
            $usernameTelegram = $data['username'] ?? null;
            $nama = $data['first_name'] ?? null;

            $error = true;
            $response = 'else';
        }

        return response()->json(['error' => ($error ? "1" : "0"), 'data' => $response]);
    }

    public function notifyExpiryContract(Request $request)
    {
        // Cek apakah metode POST
        if ($request->getMethod() !== 'POST') {
            return response()->json(['error' => 1, 'data' => 'ok cuy'], 405);
        }

        // Ambil data dari header dan JSON
        $headers = $request->headers->all();
        $content = $request->json()->all();

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
        $responseHRD = '';
        $employees = Employee::where('is_active', 1)->whereMonth('end_status_date', Carbon::now()->month)->whereYear('end_status_date', Carbon::now()->year)->orderBy('end_status_date', 'asc')->get();
        $headers = [
            'Key:KeyAbcKey',
            'Nama:arul',
            'Sandi:123###!!',
        ];

        // Data untuk request HTTP
        if ($employees->count() > 0) {
            foreach ($employees as $employee) {
                $response .= "*INFO KONTRAK AKAN BERAKHIR* \n\n";
                $response .= "Halo kak, *" . $employee->fullname . "*, kontrakmu akan berakhir pada tanggal " . tgl(Carbon::parse($employee->end_status_date)) . ". Harap konfirmasi kebagian HRD untuk kontrak selanjutnya ya! 😇.\n\n";
                $response .= "_Reported automatic by: Smart HR_";

                if ($employee->mobile_phone) {
                    $httpData = [
                        'number' => $employee->mobile_phone,
                        'message' => $response,
                    ];

                    // Mengirim request HTTP menggunakan cURL
                    $curl = curl_init();
                    curl_setopt($curl, CURLOPT_URL, 'http://192.168.3.111:3001/send-message');
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

                $responseHRD .= "*DAFTAR PEGAWAI YANG AKAN HABIS KONTRAK* \n\n";
                $responseHRD .= "🔸 " . $employee->fullname . " (" . tgl(Carbon::parse($employee->end_status_date)) . ") \n";
                $responseHRD .= "\n _Reported automatic by: Smart HR_";
            }

            $hrd = Employee::where('organization_id', 31)->get();
            foreach ($hrd as $h) {
                $httpDataHRD = [
                    'number' => $h->mobile_phone,
                    'message' => $responseHRD,
                ];

                // Mengirim request HTTP menggunakan cURL
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, 'http://192.168.3.111:3001/send-message');
                curl_setopt($curl, CURLOPT_TIMEOUT, 30);
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $httpDataHRD);
                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

                $response = curl_exec($curl);
                $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                $curlError = curl_error($curl);
                curl_close($curl);

                return response()->json(['error' => ($curlError ? "1" : "0"), 'data' => $response]);
            }
        }
    }
}
