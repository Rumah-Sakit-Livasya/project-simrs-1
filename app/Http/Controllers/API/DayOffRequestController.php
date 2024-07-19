<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceCode;
use App\Models\DayOffRequest;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class DayOffRequestController extends Controller
{
    public function getDayOffRequest($id)
    {
        try {
            $day_off_request = DayOffRequest::find($id);
            return response()->json($day_off_request, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function update($id)
    {
        try {
            // Find day off requests by ID
            $day_off_request = DayOffRequest::findOrFail($id);
            // Ambil rentang tanggal dari permintaan
            $dateRange = explode(' - ', request()->date);
            // Ubah tanggal ke format Y-m-d
            $startDate = Carbon::createFromFormat('m/d/Y', $dateRange[0])->format('Y-m-d');
            $endDate = Carbon::createFromFormat('m/d/Y', $dateRange[1])->format('Y-m-d');

            // Check if image is not empty
            if (request()->hasFile('photo')) {
                // Upload photo
                $image = request()->file('photo');
                $imageName = request()->attendance_code_id . '_cuti_' . time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/img/pengajuan/cuti/', $imageName);

                // Hapusrequest()->clockin foto yang ada jika ada
                if ($day_off_request->logo) {
                    // Hapus foto yang ada dari penyimpanan
                    Storage::delete('public/img/pengajuan/cuti/' . $day_off_request->logo);
                }

                // Update company with new image
                $day_off_request->update([
                    'attendance_code_id' => request()->attendance_code_id,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'description' => request()->description,
                    'is_approved' => request()->is_approved,
                    'approved_line_child' => request()->employee_id,
                    'approved_line_parent' => null,
                    'photo' => $imageName,
                ]);
            } else {

                // Update company with new image
                $day_off_request->update([
                    'attendance_code_id' => request()->attendance_code_id,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'approved_line_child' => request()->employee_id,
                    'approved_line_parent' => null,
                    'description' => request()->description,
                    'is_approved' => request()->is_approved,
                ]);
            }

            if (request()->is_approved == 'Disetujui') {
                for ($date = Carbon::parse($startDate); $date->lte(Carbon::parse($endDate)); $date->addDay()) {
                    // Lakukan sesuatu dengan $date di sini
                    // echo $date->toDateString() . "\n";
                    $attendance = Attendance::where('date', $date->toDateString())->where('employee_id', $day_off_request->employee_id)->first();
                    $attendance->update(['is_day_off' => '1', 'day_off_request_id' => $day_off_request->id, 'clock_in' => null, 'clock_out' => null, 'late_clock_in' => null, 'early_clock_out' => null, 'attendance_code_id' => request()->attendance_code_id]);
                    // DB::table('attendances')
                    //     ->where('date', $date->toDateString())
                    //     ->where('employee_id', $employee_id)
                    //     ->update(['is_day_off' => '1', 'day_off_request_id' => $day_off_request->id, 'clock_in' => null, 'clock_out' => null, 'late_clock_in' => null, 'early_clock_out' => null, 'attendance_code_id' => request()->attendance_code_id]);
                }
            }

            // Return response
            return response()->json(['message' => 'Pengajuan Day Off di Update!']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function store()
    {

        try {
            $validator = \Validator::make(request()->all(), [
                'date' => 'required',
                'photo' => 'nullable|file|mimes:jpg,png,jpeg|max:1024', // Maksimum 5MB (5120 KB)
                'description' => 'required|string',
                'employee_id' => 'required',
            ], [
                'date.required' => 'Kolom tanggal wajib diisi.',
                'photo.file' => 'Kolom foto harus berupa file.',
                'photo.mimes' => 'Format foto harus berupa JPG, PNG, atau JPEG.',
                'photo.max' => 'Ukuran file gambar tidak boleh lebih dari 1MB.', // Pesan khusus untuk ukuran file gambar terlalu besar
                'photo.uploaded' => 'Ukuran file gambar terlalu besar.', // Pesan khusus untuk ukuran file gambar terlalu besar
                'description.required' => 'Kolom deskripsi wajib diisi.',
                'description.string' => 'Kolom deskripsi harus berupa teks.',
                'employee_id.required' => 'Kolom ID karyawan wajib diisi.',
            ]);


            if ($validator->fails()) {
                $errors = $validator->errors();
                if ($errors->has('photo')) {
                    throw new \Exception($errors->first('photo'));
                }
            }

            // Header untuk cURL
            $headers = [
                'Key:KeyAbcKey',
                'Nama:arul',
                'Sandi:123###!!',
            ];

            // Ambil rentang tanggal dari permintaan
            $dateRange = explode(' - ', request()->date);
            // Ubah tanggal ke format Y-m-d
            $startDate = Carbon::createFromFormat('m/d/Y', $dateRange[0])->format('Y-m-d');
            $endDate = Carbon::createFromFormat('m/d/Y', $dateRange[1])->format('Y-m-d');
            $employee_id = request()->employee_id;
            $employee = Employee::find($employee_id);
            $is_approved = 'Pending';

            $attendance_code = AttendanceCode::find(request()->attendance_code_id);
            $approval_line = Employee::find($employee->approval_line);
            $approval_line_parent = Employee::find($employee->approval_line_parent);

            $messages = "*Pengajuan " . $attendance_code->description . " :* \n";
            $messages .= "*" . $employee->fullname . "*\n\n";
            $messages .= "Start Date   : " . ($startDate ?? "-") . "\n";
            $messages .= "End Date  : " . ($endDate ?? "-") . "\n";
            $messages .= "Keterangan : " . (request()->description ?? "-") . "\n";
            $messages .= "\nTolong acc/tolak melalui website Smart HR atau melalui link berikut: \n\n";

            if (!isset($employee->approval_line) && !isset($employee->approval_line_parent)) {
                $is_approved = "Disetujui";
            }
            if (isset($employee->approval_line) && !isset($employee->approval_line_parent)) {
                $is_approved = "Verifikasi";
            }
            if (isset(request()->is_approved)) {
                $is_approved = request()->is_approved;
            }
            if (request()->hasFile('photo')) {
                $image = request()->file('photo');
                $imageName = request()->attendance_code_id . '_cuti_' . time() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('img/pengajuan/cuti', $imageName, 'public');
                // Create company dengan file logo

                $day_off_request = DayOffRequest::create([
                    'attendance_code_id' => request()->attendance_code_id,
                    'employee_id' => $employee_id,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'approved_line_child' => $employee->approval_line,
                    'approved_line_parent' => $employee->approval_line_parent,
                    'photo' => $imageName,
                    'description' => request()->description,
                    'is_approved' => $is_approved,
                ]);

                $messages .= 'https://internal.livasya.com/attendances/day-off-requests/' . $day_off_request->id;
            } else {
                $day_off_request = DayOffRequest::create([
                    'attendance_code_id' => request()->attendance_code_id,
                    'employee_id' => $employee_id,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'approved_line_child' => $employee->approval_line,
                    'approved_line_parent' => $employee->approval_line_parent,
                    'description' => request()->description,
                    'is_approved' => $is_approved,
                ]);

                $messages .= 'https://internal.livasya.com/attendances/day-off-requests/' . $day_off_request->id;
            }

            if ($is_approved == "Disetujui") {
                for ($date = Carbon::parse($startDate); $date->lte(Carbon::parse($endDate)); $date->addDay()) {
                    $attendance = Attendance::where('date', $date->toDateString())->where('employee_id', $day_off_request->employee_id)->first();
                    $attendance->update(['is_day_off' => '1', 'day_off_request_id' => $day_off_request->id, 'clock_in' => null, 'clock_out' => null, 'late_clock_in' => null, 'early_clock_out' => null, 'attendance_code_id' => request()->attendance_code_id]);
                }
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

                    if (request()->hasFile('photo')) {
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

            //return response
            return response()->json(['message' => 'Request Absensi Berhasil di Tambahkan!']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function approve($id)
    {
        $day_off_request = DayOffRequest::find($id);
        $approved_line_parent = Employee::find($day_off_request->approved_line_parent);

        // Header untuk cURL
        $headers = [
            'Key:KeyAbcKey',
            'Nama:arul',
            'Sandi:123###!!',
        ];

        $messages = "*Pengajuan " . $day_off_request->attendance_code->description . " :* \n";
        $messages .= "*" . $day_off_request->employee->fullname . "*\n\n";
        $messages .= "Start Date : " . ($day_off_request->start_date ?? "-") . "\n";
        $messages .= "Clock Out  : " . ($day_off_request->end_date ?? "-") . "\n";
        $messages .= "Keterangan : " . ($day_off_request->description ?? "-") . "\n";
        $messages .= "\nTolong acc melalui website Smart HR atau melalui link berikut: \n\n";
        $messages .= 'https://internal.livasya.com/attendances/day-off-requests/' . $day_off_request->id;



        // dd($day_off_request);
        if (auth()->user()->hasRole('super admin')) {
            $is_approved = "Disetujui";
        } else {
            if ($day_off_request->approved_line_child !== null && $day_off_request->approved_line_parent == null) {
                $is_approved = "Disetujui";
            } else if (($day_off_request->approved_line_child !== null && $day_off_request->approved_line_parent !== null) && ($day_off_request->approved_line_child == request()->employee_id)) {
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

                if (isset($day_off_request->photo)) {
                    $filePath = 'img/pengajuan/cuti/' . $day_off_request->photo;
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
                if (isset($day_off_request->photo)) {
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
            } else if (($day_off_request->approved_line_child !== null && $day_off_request->approved_line_parent !== null) && ($day_off_request->approved_line_parent == request()->employee_id)) {
                $is_approved = "Disetujui";
            }
        }

        if ($is_approved == "Disetujui") {
            $startDate = Carbon::parse($day_off_request->start_date);
            $endDate = Carbon::parse($day_off_request->end_date);

            for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
                // Lakukan sesuatu dengan $date di sini
                // echo $date->toDateString() . "\n";
                $attendance = Attendance::where('date', $date->toDateString())->where('employee_id', $day_off_request->employee_id)->first();
                if ($attendance) {
                    $attendance->update(['is_day_off' => '1', 'day_off_request_id' => $day_off_request->id, 'clock_in' => null, 'clock_out' => null, 'late_clock_in' => null, 'early_clock_out' => null, 'attendance_code_id' => request()->attendance_code_id]);
                } else {
                    Attendance::create([
                        'employee_id' => $day_off_request->employee_id,
                        'date' => $date->toDateString(),
                        'is_day_off' => '1',
                        'day_off_request_id' => $day_off_request->id,
                        'clock_in' => null,
                        'clock_out' => null,
                        'late_clock_in' => null,
                        'early_clock_out' => null,
                        'attendance_code_id' => request()->attendance_code_id
                    ]);
                }
                // DB::table('attendances')
                //     ->where('date', $date->toDateString())
                //     ->where('employee_id', $day_off_request->employee_id)
                //     ->update(['is_day_off' => '1', 'day_off_request_id' => $id, 'clock_in' => null, 'clock_out' => null, 'late_clock_in' => null, 'early_clock_out' => null, 'attendance_code_id' => $day_off_request->attendance_code_id]);
            }
        }
        $day_off_request->update([
            'is_approved' => $is_approved
        ]);

        return response()->json(['message' => 'Status Pengajuan: ' . $is_approved]);
    }

    public function reject($id)
    {

        $day_off_request = DayOffRequest::find($id);

        // Hapus foto yang ada jika ada
        if ($day_off_request->photo) {
            // Hapus foto yang ada dari penyimpanan
            Storage::delete('public/img/pengajuan/cuti/' . $day_off_request->photo);
        }
        $day_off_request->update([
            'is_approved' => 'Ditolak'
        ]);

        return response()->json(['message' => 'Pengajuan Berhasil di Tolak!']);
    }

    public function destroy($id)
    {
        $day_off_request = DayOffRequest::where('id', $id)->first();
        if ($day_off_request->attendance) {
            foreach ($day_off_request->attendance as $attendance) {
                $absensi = Attendance::find($attendance->id);
                $absensi->update([
                    'day_off_request_id' => null,
                    'attendance_code_id' => null,
                    'is_day_off' => null
                ]);
            }
        }
        // Hapus foto yang ada jika ada
        if ($day_off_request->photo) {
            // Hapus foto yang ada dari penyimpanan
            Storage::delete('public/img/pengajuan/cuti/' . $day_off_request->photo);
        }
        $day_off_request->delete();

        return response()->json(['message' => 'Pengajuan Berhasil di Hapus!']);
    }
}
