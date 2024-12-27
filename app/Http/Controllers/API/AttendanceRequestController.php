<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceRequest;
use App\Models\AttendanceRequestLamp;
use App\Models\AttendanceRequestLampDetail;
use App\Models\Employee;
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon as SupportCarbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AttendanceRequestController extends Controller
{

    public function getAttendanceRequest($id)
    {
        try {
            $attendance_request = attendanceRequest::find($id);
            return response()->json($attendance_request, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function ontime($id)
    {
        try {
            // Temukan pegawai berdasarkan id
            $attendance = Attendance::find($id);

            if ($attendance) {
                // Logika untuk memperbarui clockin dan clockout
                $attendance->clock_in = $attendance->shift->time_in;
                $attendance->clock_out = $attendance->shift->time_out;
                $attendance->save();

                return response()->json([
                    'message' => 'Clockin and Clockout times updated successfully.'
                ]);
            } else {
                return response()->json([
                    'error' => 'Attendances not found.'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function alfa($id)
    {
        try {
            // Temukan pegawai berdasarkan id
            $attendance = Attendance::find($id);

            if ($attendance) {
                // Logika untuk memperbarui clockin dan clockout
                $attendance->shift_id = $attendance->shift_id == 1 ? 2 : $attendance->shift_id;
                $attendance->clock_in = null;
                $attendance->clock_out = null;
                $attendance->is_day_off = null;
                $attendance->attendance_code_id = null;
                $attendance->day_off_request_id = null;
                $attendance->save();

                return response()->json([
                    'message' => 'Clockin and Clockout times updated successfully.'
                ]);
            } else {
                return response()->json([
                    'error' => 'Attendances not found.'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function ontimeAll(Request $request)
    {
        // try {
        // Periksa apakah permintaan telah menyertakan parameter periode
        if ($request->has('periode')) {
            $periode = $request->periode;
            $months = explode(' - ', $periode);

            // Pastikan bahwa periode yang diterima valid
            if (count($months) !== 2) {
                return response()->json(['success' => false, 'error' => 'Periode tidak valid.'], 400);
            }

            $startMonth = $months[0];
            $endMonth = $months[1];

            $startPeriod = Carbon::createFromFormat('F Y', $startMonth)->startOfMonth();
            $endPeriod = Carbon::createFromFormat('F Y', $endMonth)->endOfMonth();
        } else {
            // Jika tidak, tentukan periode bulan sekarang
            $today = Carbon::now();
            if ($today->day >= 26) {
                $startPeriod = $today->copy()->startOfMonth()->addDays(1); // Tanggal 1 bulan depan
            } else {
                $startPeriod = $today->copy()->subMonth()->startOfMonth()->addDays(25); // Tanggal 26 bulan sebelumnya
            }
            $endPeriod = $today->copy()->subMonth()->endOfMonth()->addDays(25); // Tanggal 25 bulan sekarang
        }

        // Query untuk mencari data absensi sesuai dengan periode yang diminta
        $attendances = Attendance::whereBetween('date', [$startPeriod, $endPeriod])
            ->get();

        foreach ($attendances as $attendance) {
            $attendance->clock_in = $attendance->shift->time_in;
            $attendance->clock_out = $attendance->shift->time_out;
            $attendance->save();
        }

        return response()->json(['success' => true, 'message' => 'Clock In dan Clock Out berhasil diupdate untuk semua entri.']);
        // } catch (\Exception $e) {
        //     return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        // }
    }

    public function update($id)
    {
        try {
            // dd(request()->name);
            // Find day off requests by ID
            $attendance_request = AttendanceRequest::findOrFail($id);

            // Check if image is not empty
            if (request()->hasFile('file')) {
                // Upload file
                $image = request()->file('file');
                $imageName = request()->attendance_code_id . '_cuti_' . time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/img/pengajuan/cuti/', $imageName);

                // Hapus foto yang ada jika ada
                if ($attendance_request->logo) {
                    // Hapus foto yang ada dari penyimpanan
                    Storage::delete('public/img/pengajuan/cuti/' . $attendance_request->logo);
                }



                // Update company with new image
                $attendance_request->update([
                    'date' => request()->date,
                    'clockin' => request()->clockin,
                    'clockout' => request()->clockout,
                    'description' => request()->description,
                    'is_approved' => request()->is_approved,
                    'approved_line_child' => request()->employee_id,
                    'approved_line_parent' => null,
                    'file' => $imageName,
                ]);
            } else {

                // Update company with new image
                $attendance_request->update([
                    'date' => request()->date,
                    'clockin' => request()->clockin,
                    'clockout' => request()->clockout,
                    'description' => request()->description,
                    'approved_line_child' => request()->employee_id,
                    'approved_line_parent' => null,
                    'is_approved' => request()->is_approved,
                ]);
            }

            if (isset(request()->clockin) || isset(request()->clockout)) {
                if (request()->is_approved == 'Disetujui') {
                    $attendance = Attendance::find($attendance_request->attendance_id);
                    $attendance->update([
                        'clock_in' => request()->clockin ?? $attendance->clock_in,
                        'clock_out' => request()->clockout ?? $attendance->clock_out,
                    ]);
                }
            }

            // Return response
            return response()->json(['message' => 'Pengajuan Absensi di Update!']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 404);
        }
    }


    public function store()
    {
        try {
            if (request()->clockin == null && request()->clockout == null) {
                throw new \Exception('Clockin atau Clockout harap diisi!');
            }

            $validator = Validator::make(request()->all(), [
                'employee_id' => 'required',
                'date' => 'required|date',
                'clockin' => 'required_if:check-clockin,on|date_format:H:i',
                'clockout' => 'required_if:check-clockout,on|date_format:H:i',
                'file' => 'nullable|file|mimes:jpg,png,jpeg',
                'description' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                if ($errors->has('file')) {
                    throw new \Exception("File harus berupa gambar!");
                }
                if ($errors->has('date')) {
                    throw new \Exception("Tanggal Harap diisi!");
                }
            }

            $employee = Employee::findOrFail(request()->employee_id, ['approval_line', 'approval_line_parent', 'fullname']);
            $is_approved = 'Disetujui';

            // if (!isset($employee->approval_line) && !isset($employee->approval_line_parent)) {
            //     $is_approved = "Disetujui";
            // } elseif (isset($employee->approval_line) && !isset($employee->approval_line_parent)) {
            //     $is_approved = "Verifikasi";
            // }

            $attendance = Attendance::where('date', request()->date)
                ->where('employee_id', request()->employee_id)
                ->firstOrFail();

            // Setup pesan untuk WhatsApp
            // $messages = "*Pengajuan Absensi: " . Carbon::parse(request()->date)->translatedFormat('j F Y') . "* \n";
            // $messages .= "*" . $employee->fullname . "*\n\n";
            // $messages .= "Clock In   : " . (request()->clockin ?? "-") . "\n";
            // $messages .= "Clock Out  : " . (request()->clockout ?? "-") . "\n";
            // $messages .= "Keterangan : " . (request()->description ?? "-") . "\n";
            // $messages .= "\nTolong acc melalui website Smart HR atau melalui link berikut: \n\n";

            // Handle file upload
            $filePath = null;
            if (request()->hasFile('file')) {
                // $image = request()->file('file');
                // $imageName = request()->date . '_absensi_' . time() . '.' . $image->getClientOriginalExtension();
                // $filePath = $image->storeAs('img/pengajuan/absensi', $imageName, 'public');

                $file = request()->file('file');
                $imageName = request()->date . '_absensi_' . time() . '.'  . $file->getClientOriginalExtension();
                $directory = 'img/pengajuan/absensi';

                // Simpan file secara manual ke storage
                $storagePath = storage_path('app/public/' . $directory);
                if (!file_exists($storagePath)) {
                    mkdir($storagePath, 0755, true); // Buat folder jika belum ada
                }

                // Pindahkan file ke folder tujuan
                $file->move($storagePath, $imageName);

                // Path relatif untuk database
                $filePath = $directory . '/' . $imageName;
            }

            // Create attendance request
            $attendanceRequestData = [
                'employee_id' => request()->employee_id,
                'attendance_id' => $attendance->id,
                'date' => request()->date,
                'approved_line_child' => $employee->approval_line,
                'approved_line_parent' => $employee->approval_line_parent,
                'clockin' => request()->clockin,
                'clockout' => request()->clockout,
                'description' => request()->description,
                'is_approved' => $is_approved,
            ];
            if ($filePath) {
                $attendanceRequestData['file'] = $imageName;
            }
            $attendanceRequest = AttendanceRequest::create($attendanceRequestData);

            // $messages .= 'https://internal.livasya.com/attendances/attendance-requests/' . $attendanceRequest->id;

            // Handle approval logic
            if ($is_approved === "Disetujui") {
                $updateData = [];
                if (request()->clockin && request()->clockout) {
                    $updateData = [
                        'clock_in' => request()->clockin,
                        'clock_out' => request()->clockout,
                        'late_clock_in' => null,
                        'early_clock_out' => null,
                    ];
                } elseif (request()->clockin) {
                    $updateData = ['clock_in' => request()->clockin, 'late_clock_in' => null];
                } elseif (request()->clockout) {
                    $updateData = ['clock_out' => request()->clockout, 'early_clock_out' => null];
                }
                $attendance->update($updateData);
            }
            // } else {
            //     $this->sendWhatsAppNotification($employee, $messages, $filePath);
            // }
            return response()->json(['message' => 'Absensi Berhasil Diubah!']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 404);
        }
    }

    // private function sendWhatsAppNotification($employee, $messages, $filePath)
    // {
    //     $approvalLine = Employee::find($employee->approval_line);
    //     if (!isset($approvalLine) || !isset($approvalLine->mobile_phone)) {
    //         return;
    //     }

    //     $number = $approvalLine->mobile_phone;
    //     $formattedNumber = substr($number, 0, 1) === '0' ? '62' . substr($number, 1) : $number;

    //     $httpData = [
    //         'number' => $formattedNumber,
    //         'message' => $messages,
    //     ];

    //     if ($filePath) {
    //         $httpData['file_dikirim'] = new \CURLFile(storage_path('app/public/' . $filePath));
    //     }

    //     $headers = [
    //         'Key:KeyAbcKey',
    //         'Nama:arul',
    //         'Sandi:123###!!',
    //     ];

    //     $curl = curl_init();
    //     curl_setopt($curl, CURLOPT_URL, 'http://192.168.3.111:3001/send-message');
    //     curl_setopt($curl, CURLOPT_TIMEOUT, 30);
    //     curl_setopt($curl, CURLOPT_POST, 1);
    //     curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    //     curl_setopt($curl, CURLOPT_POSTFIELDS, $httpData);
    //     curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    //     $response = curl_exec($curl);
    //     if (curl_errno($curl)) {
    //         throw new \Exception('cURL Error: ' . curl_error($curl));
    //     }
    //     curl_close($curl);
    // }

    // public function approve($id)
    // {
    //     $attendance_request = AttendanceRequest::find($id);
    //     $approved_line_parent = Employee::find($attendance_request->approved_line_parent);

    //     // Header untuk cURL
    //     $headers = [
    //         'Key:KeyAbcKey',
    //         'Nama:arul',
    //         'Sandi:123###!!',
    //     ];

    //     //isi pesan
    //     $messages = "*Pengajuan Absensi: " . Carbon::parse($attendance_request->date)->translatedFormat('j F Y') . "* \n";
    //     $messages .= "*" . $attendance_request->employee->fullname . "*\n\n";
    //     $messages .= "Clock In   : " . ($attendance_request->clockin ?? "-") . "\n";
    //     $messages .= "Clock Out  : " . ($attendance_request->clockout ?? "-") . "\n";
    //     $messages .= "Keterangan : " . ($attendance_request->description ?? "-") . "\n";
    //     $messages .= "\nTolong acc melalui website Smart HR atau melalui link berikut: \n\n";
    //     $messages .= 'https://internal.livasya.com/attendances/attendance-requests/' . $attendance_request->id;

    //     // dd($attendance_request);
    //     if (auth()->user()->hasRole('super admin')) {
    //         $is_approved = "Disetujui";
    //     } else {
    //         if ($attendance_request->approved_line_child !== null && $attendance_request->approved_line_parent == null) {
    //             $is_approved = "Disetujui";
    //         } else if (($attendance_request->approved_line_child !== null && $attendance_request->approved_line_parent !== null) && ($attendance_request->approved_line_child == request()->employee_id)) {
    //             $is_approved = "Verifikasi";

    //             $number = $approved_line_parent->mobile_phone;

    //             if (substr($number, 0, 1) === '0') {
    //                 // Hapus karakter pertama ('0') dan tambahkan awalan '62'
    //                 $formattedNumber = '62' . substr($number, 1);
    //             } else {
    //                 // Jika nomor telepon tidak dimulai dengan '0', gunakan nilai asli
    //                 $formattedNumber = $number;
    //             }

    //             // Data untuk request HTTP
    //             $httpData = [
    //                 'number' => $formattedNumber,
    //                 'message' => $messages,
    //             ];
    //             $filePath = 'img/pengajuan/absensi/' . $attendance_request->file;
    //             if (isset($attendance_request->file)) {
    //                 $httpData['file_dikirim'] = new \CURLFile(storage_path('app/public/' . $filePath));
    //             }

    //             // Mengirim request HTTP menggunakan cURL
    //             $curl = curl_init();
    //             curl_setopt($curl, CURLOPT_URL, 'http://192.168.3.111:3001/send-message');
    //             curl_setopt($curl, CURLOPT_TIMEOUT, 30);
    //             curl_setopt($curl, CURLOPT_POST, 1);
    //             curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    //             curl_setopt($curl, CURLOPT_POSTFIELDS, $httpData);
    //             curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    //             $response = curl_exec($curl);
    //             $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    //             $curlError = curl_error($curl);
    //             curl_close($curl);

    //             $httpData = [
    //                 'number' => '6281564705558',
    //                 'message' => $messages,
    //             ];
    //             if (isset($attendance_request->file)) {
    //                 $httpData['file_dikirim'] = new \CURLFile(storage_path('app/public/' . $filePath));
    //             }


    //             // Mengirim request HTTP menggunakan cURL
    //             $curl = curl_init();
    //             curl_setopt($curl, CURLOPT_URL, 'http://192.168.3.111:3001/send-message');
    //             curl_setopt($curl, CURLOPT_TIMEOUT, 30);
    //             curl_setopt($curl, CURLOPT_POST, 1);
    //             curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    //             curl_setopt($curl, CURLOPT_POSTFIELDS, $httpData);
    //             curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    //             $response = curl_exec($curl);
    //             $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    //             $curlError = curl_error($curl);
    //             curl_close($curl);
    //         } else if (($attendance_request->approved_line_child !== null && $attendance_request->approved_line_parent !== null) && ($attendance_request->approved_line_parent == request()->employee_id)) {
    //             $is_approved = "Disetujui";


    //             // DB::table('attendances')
    //             //     ->where('id', $attendance_request->attendance_id)
    //             //     ->where('employee_id', $attendance_request->employee_id)
    //             //     ->update($updateData);
    //         }
    //     }

    //     if ($is_approved == 'Disetujui') {
    //         // Periksa apakah ada data clock_in dan clock_out yang dikirim
    //         if ($attendance_request->clockin == null && $attendance_request->clockout != null) {
    //             $updateData = ['clock_out' => $attendance_request->clockout];
    //             $updateData['early_clock_out'] = null;
    //         } else if ($attendance_request->clockout == null && $attendance_request->clockin != null) {
    //             $updateData['clock_in'] = $attendance_request->clockin;
    //             $updateData['late_clock_in'] = null;
    //         } else if ($attendance_request->clockin != null && $attendance_request->clockout != null) {
    //             $updateData['clock_in'] = $attendance_request->clockin;
    //             $updateData['clock_out'] = $attendance_request->clockout;
    //             $updateData['late_clock_in'] = null;
    //             $updateData['early_clock_out'] = null;
    //         }
    //         $attendance = Attendance::where('id', $attendance_request->attendance_id)->first();
    //         $attendance->update($updateData);
    //     }

    //     $attendance_request->update([
    //         'is_approved' => $is_approved
    //     ]);

    //     return response()->json(['message' => 'Status Pengajuan: ' . $is_approved]);
    // }

    // public function reject($id)
    // {

    //     $attendance_request = AttendanceRequest::find($id);
    //     // dd($attendance_request);
    //     // Hapus foto yang ada jika ada
    //     if ($attendance_request->file) {
    //         // Hapus foto yang ada dari penyimpanan
    //         Storage::delete('public/img/pengajuan/absensi' . $attendance_request->file);
    //     }
    //     $attendance_request->update([
    //         'is_approved' => 'Ditolak'
    //     ]);

    //     return response()->json(['message' => 'Pengajuan Berhasil di Tolak!']);
    // }

    public function destroy($id)
    {
        $attendance_request = AttendanceRequest::find($id);
        // Hapus foto yang ada jika ada
        if ($attendance_request->file) {
            // Hapus foto yang ada dari penyimpanan
            Storage::delete('public/img/pengajuan/absensi' . $attendance_request->file);
        }
        $attendance_request->delete();

        return response()->json(['message' => 'Pengajuan Berhasil di Hapus!']);
    }

    public function submitFormReqAttendance(Request $request)
    {
        $validated = $request->validate([
            'employee_id_fix' => 'required|array',
            'employee_id_fix.*' => 'exists:employees,id', // Pastikan setiap employee_id valid
            'tanggal' => 'required|array',
            'tanggal.*' => 'date', // Validasi tanggal
            'clockin' => 'nullable|array',
            'clockin.*' => 'date_format:H:i', // Validasi format waktu
            'clockout' => 'nullable|array',
            'clockout.*' => 'date_format:H:i', // Validasi format waktu
            'lampiran' => 'required|file|mimes:pdf|max:2048',
        ]);

        try {
            // Mulai transaksi untuk menyimpan data
            \DB::beginTransaction();

            $lampiranPath = null;
            if ($request->hasFile('lampiran')) {
                // Menyimpan file PDF ke storage
                $lampiran = $request->file('lampiran');

                $fileName = 'lampiran-' . uniqid() . '.' . $lampiran->getClientOriginalExtension();
                $directory = 'lampiran/' . now()->format('Y-m-d') . '/';

                // Simpan file secara manual ke storage
                $storagePath = storage_path('app/public/' . $directory);
                if (!file_exists($storagePath)) {
                    mkdir($storagePath, 0755, true); // Buat folder jika belum ada
                }

                // Pindahkan file ke folder tujuan
                $lampiran->move($storagePath, $fileName);

                $lampiranPath = $directory . '/' . $fileName;
            }

            $lamp = AttendanceRequestLamp::create([
                'tanggal' => SupportCarbon::parse($request->tanggal[0]),
                'organization_id' => auth()->user()->employee->organization_id,
                'lampiran' => $lampiranPath,
            ]);

            // Proses data form dan simpan ke database
            foreach ($request->employee_id_fix as $index => $employee_id) {
                $user = User::where('id', $employee_id)->first();
                $user->update(['is_request_attendance' => 0]);

                // Simpan data ke model AttendanceRequestLamp
                AttendanceRequestLampDetail::create([
                    'attendance_request_lamp_id' => $lamp->id,
                    'employee_id' => $employee_id,
                    'tanggal' => $request->tanggal[$index],
                    'clock_in' => $request->clockin[$index],
                    'clock_out' => $request->clockout[$index],
                    'lampiran' => $lampiranPath,
                ]);
            }

            // Commit transaksi jika tidak ada error
            \DB::commit();

            // Mengembalikan response sukses
            return response()->json(['message' => 'Form berhasil disubmit!'], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Ambil error pertama dari semua pesan error
            $firstError = collect($e->errors())->flatten()->first();

            return response()->json([
                'message' => $firstError ?? 'Validasi gagal.', // Ambil error pertama atau gunakan default
                'errors' => $e->errors(), // Tetap kirimkan semua error untuk debugging jika diperlukan
            ], 422);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi error
            \DB::rollBack();

            // Tangani error dan kembalikan pesan kesalahan
            return response()->json(['message' => 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.', 'error' => $e->getMessage()], 500);
        }
    }
}
