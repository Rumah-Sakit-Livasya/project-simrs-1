<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceRequest;
use App\Models\Employee;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
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
        // return dd(request()->all());

        try {
            $validator = Validator::make(request()->all(), [
                'employee_id' => 'required',
                'date' => 'required|date',
                'clockin' => 'required_if:check-clockin,on|date_format:H:i',
                'clockout' => 'required_if:check-clockout,on|date_format:H:i',
                'file' => 'nullable|file|mimes:jpg,png,jpeg',
                'description' => 'nullable|string',
            ]);

            if (!request()->has('clockin') && !request()->has('clockout')) {
                return redirect()->back()->with('error', 'Clockin atau Clockout harap diisi!');
            }

            $employee = Employee::where('id', request()->employee_id)->first(['approval_line', 'approval_line_parent', 'fullname']);
            $is_approved = 'Pending';

            if (!isset($employee->approval_line) && !isset($employee->approval_line_parent)) {
                $is_approved = "Disetujui";
            }
            if (isset($employee->approval_line) && !isset($employee->approval_line_parent)) {
                $is_approved = "Verifikasi";
            }

            if ($validator->fails()) {
                $errors = $validator->errors();
                if ($errors->has('file')) {
                    throw new \Exception("File harus berupa gambar!");
                }
                if ($errors->has('date')) {
                    throw new \Exception("Tanggal Harap diisi!");
                }
            }

            // Header untuk cURL
            $headers = [
                'Key:KeyAbcKey',
                'Nama:arul',
                'Sandi:123###!!',
            ];

            $approval_line = Employee::find($employee->approval_line);
            // $approval_line_parent = Employee::find($employee->approval_line_parent);
            $attendance = Attendance::where('date', request()->date)->where('employee_id', request()->employee_id)->first();
            //messages wa
            $messages = "*Pengajuan Absensi: " . Carbon::parse(request()->date)->translatedFormat('j F Y') . "* \n";
            $messages .= "*" . $employee->fullname . "*\n\n";
            $messages .= "Clock In   : " . (request()->clockin ?? "-") . "\n";
            $messages .= "Clock Out  : " . (request()->clockout ?? "-") . "\n";
            $messages .= "Keterangan : " . (request()->description ?? "-") . "\n";
            $messages .= "\nTolong acc melalui website Smart HR atau melalui link berikut: \n\n";

            if (request()->hasFile('file')) {
                $image = request()->file('file');
                $imageName = request()->date . '_absensi_' . time() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('img/pengajuan/absensi', $imageName, 'public');
                $attendance_request = AttendanceRequest::create([
                    'employee_id' => request()->employee_id,
                    'attendance_id' => $attendance->id,
                    'date' => request()->date,
                    'approved_line_child' => $employee->approval_line,
                    'approved_line_parent' => $employee->approval_line_parent,
                    'clockin' => request()->clockin,
                    'clockout' => request()->clockout,
                    'is_approved' => $is_approved,
                    'file' => $imageName,
                    'description' => request()->description,
                ]);

                $messages .= 'https://internal.livasya.com/attendances/attendance-requests/' . $attendance_request->id;
            } else {
                $attendance_request = AttendanceRequest::create([
                    'employee_id' => request()->employee_id,
                    'attendance_id' => $attendance->id,
                    'date' => request()->date,
                    'approved_line_child' => $employee->approval_line,
                    'approved_line_parent' => $employee->approval_line_parent,
                    'clockin' => request()->clockin,
                    'clockout' => request()->clockout,
                    'description' => request()->description,
                    'is_approved' => $is_approved,
                ]);
                $messages .= 'https://internal.livasya.com/attendances/attendance-requests/' . $attendance_request->id;
            }

            if ($is_approved == "Disetujui") {
                // Periksa apakah ada data clock_in dan clock_out yang dikirim
                if (request()->clockin == null && request()->clockout != null) {
                    $updateData = ['clock_out' => request()->clockout];
                    $updateData['early_clock_out'] = null;
                } else if (request()->clockout == null && request()->clockin != null) {
                    $updateData['clock_in'] = request()->clockin;
                    $updateData['late_clock_in'] = null;
                } else if (request()->clockin != null && request()->clockout != null) {
                    $updateData['clock_in'] = request()->clockin;
                    $updateData['clock_out'] = request()->clockout;
                    $updateData['late_clock_in'] = null;
                    $updateData['early_clock_out'] = null;
                }
                $attendance->update($updateData);
            } else {

                if (isset($approval_line) && isset($approval_line->mobile_phone)) {
                    $number = $approval_line->mobile_phone;
                    if (substr($number, 0, 1) === '0') {
                        // Hapus karakter pertama ('0') dan tambahkan awalan '62'
                        $formattedNumber = '62' . substr($number, 1);
                    } else {
                        // Jika nomor telepon tidak dimulai dengan '0', gunakan nilai asli
                        $formattedNumber = $number;
                    }
                    // Data untuk request HTTP
                    $httpData = [
                        'number' => $formattedNumber,
                        'message' => $messages,
                    ];

                    if (request()->hasFile('file')) {
                        $httpData['file_dikirim'] = new \CURLFile(storage_path('app/public/' . $path));
                    }

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
            }

            return response()->json(['message' => 'Request Absensi Berhasil di Tambahkan!']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function approve($id)
    {
        $attendance_request = AttendanceRequest::find($id);
        $approved_line_parent = Employee::find($attendance_request->approved_line_parent);

        // Header untuk cURL
        $headers = [
            'Key:KeyAbcKey',
            'Nama:arul',
            'Sandi:123###!!',
        ];

        //isi pesan
        $messages = "*Pengajuan Absensi: " . Carbon::parse($attendance_request->date)->translatedFormat('j F Y') . "* \n";
        $messages .= "*" . $attendance_request->employee->fullname . "*\n\n";
        $messages .= "Clock In   : " . ($attendance_request->clockin ?? "-") . "\n";
        $messages .= "Clock Out  : " . ($attendance_request->clockout ?? "-") . "\n";
        $messages .= "Keterangan : " . ($attendance_request->description ?? "-") . "\n";
        $messages .= "\nTolong acc melalui website Smart HR atau melalui link berikut: \n\n";
        $messages .= 'https://internal.livasya.com/attendances/attendance-requests/' . $attendance_request->id;

        // dd($attendance_request);
        if (auth()->user()->hasRole('Admin')) {
            $is_approved = "Disetujui";
        } else {
            if ($attendance_request->approved_line_child !== null && $attendance_request->approved_line_parent == null) {
                $is_approved = "Disetujui";
            } else if (($attendance_request->approved_line_child !== null && $attendance_request->approved_line_parent !== null) && ($attendance_request->approved_line_child == request()->employee_id)) {
                $is_approved = "Verifikasi";

                $number = $approved_line_parent->mobile_phone;

                if (substr($number, 0, 1) === '0') {
                    // Hapus karakter pertama ('0') dan tambahkan awalan '62'
                    $formattedNumber = '62' . substr($number, 1);
                } else {
                    // Jika nomor telepon tidak dimulai dengan '0', gunakan nilai asli
                    $formattedNumber = $number;
                }

                // Data untuk request HTTP
                $httpData = [
                    'number' => $formattedNumber,
                    'message' => $messages,
                ];
                $filePath = 'img/pengajuan/absensi/' . $attendance_request->file;
                if (isset($attendance_request->file)) {
                    $httpData['file_dikirim'] = new \CURLFile(storage_path('app/public/' . $filePath));
                }

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

                $httpData = [
                    'number' => '6281564705558',
                    'message' => $messages,
                ];
                if (isset($attendance_request->file)) {
                    $httpData['file_dikirim'] = new \CURLFile(storage_path('app/public/' . $filePath));
                }


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
            } else if (($attendance_request->approved_line_child !== null && $attendance_request->approved_line_parent !== null) && ($attendance_request->approved_line_parent == request()->employee_id)) {
                $is_approved = "Disetujui";


                // DB::table('attendances')
                //     ->where('id', $attendance_request->attendance_id)
                //     ->where('employee_id', $attendance_request->employee_id)
                //     ->update($updateData);
            }
        }

        if ($is_approved == 'Disetujui') {
            // Periksa apakah ada data clock_in dan clock_out yang dikirim
            if ($attendance_request->clockin == null && $attendance_request->clockout != null) {
                $updateData = ['clock_out' => $attendance_request->clockout];
                $updateData['early_clock_out'] = null;
            } else if ($attendance_request->clockout == null && $attendance_request->clockin != null) {
                $updateData['clock_in'] = $attendance_request->clockin;
                $updateData['late_clock_in'] = null;
            } else if ($attendance_request->clockin != null && $attendance_request->clockout != null) {
                $updateData['clock_in'] = $attendance_request->clockin;
                $updateData['clock_out'] = $attendance_request->clockout;
                $updateData['late_clock_in'] = null;
                $updateData['early_clock_out'] = null;
            }
            $attendance = Attendance::where('id', $attendance_request->attendance_id)->first();
            $attendance->update($updateData);
        }

        $attendance_request->update([
            'is_approved' => $is_approved
        ]);

        return response()->json(['message' => 'Status Pengajuan: ' . $is_approved]);
    }

    public function reject($id)
    {

        $attendance_request = AttendanceRequest::find($id);
        // dd($attendance_request);
        // Hapus foto yang ada jika ada
        if ($attendance_request->file) {
            // Hapus foto yang ada dari penyimpanan
            Storage::delete('public/img/pengajuan/absensi' . $attendance_request->file);
        }
        $attendance_request->update([
            'is_approved' => 'Ditolak'
        ]);

        return response()->json(['message' => 'Pengajuan Berhasil di Tolak!']);
    }

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
}
