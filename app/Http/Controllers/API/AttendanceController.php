<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Imports\AttendanceImport;
use App\Models\Attendance;
use App\Models\AttendanceOutsource;
use App\Models\DayOffRequest;
use App\Models\Employee;
use App\Models\Payroll;
use App\Models\Shift;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class AttendanceController extends Controller
{
    public function clock_in(Request $request)
    {
        //tes
        try {
            $validator = Validator::make($request->all(), [
                'employee_id' => 'required|exists:employees,id',
                'latitude' => 'required',
                'longitude' => 'required',
                'photo' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $employee = Employee::findOrFail($request->employee_id);
            $company = $employee->company;
            $employee_id = $request->employee_id;
            $penggunaLatitude = $request->latitude;
            $penggunaLongitude = $request->longitude;
            $radiusPerusahaan = $company->radius;
            $is_clock_in = false;

            //Jika memiliki lokasi absen ditabel lokasi
            if ($employee->locations()->exists()) {

                //jika memiliki lebih dari 1 lokasi
                foreach ($employee->locations as $i => $location) {
                    $perusahaanLatitude = $location->latitude;
                    $perusahaanLongitude = $location->longitude;
                    $jarak = haversine($perusahaanLatitude, $perusahaanLongitude, $penggunaLatitude, $penggunaLongitude);

                    // jika jarak sudah masuk radius, maka izinkan absen
                    if ($jarak <= $radiusPerusahaan) {
                        $is_clock_in = true;
                    }
                }
            } else {
                // koordinat perusahaan
                $perusahaanLatitude = $company->latitude;
                $perusahaanLongitude = $company->longitude;

                $jarak = haversine($perusahaanLatitude, $perusahaanLongitude, $penggunaLatitude, $penggunaLongitude);

                // jika jarak sudah masuk radius, maka izinkan absen
                if ($jarak <= $radiusPerusahaan) {
                    $is_clock_in = true;
                }
            }

            if ($is_clock_in) {
                $file = $request->file('photo');
                $fileName = 'clock-in-' . uniqid() . '.' . $file->getClientOriginalExtension();
                $directory = 'absensi/clock-in/' . now()->format('m-Y') . '/' . now()->format('d-m-Y');

                // Simpan file secara manual ke storage
                $storagePath = storage_path('app/public/' . $directory);
                if (!file_exists($storagePath)) {
                    mkdir($storagePath, 0755, true); // Buat folder jika belum ada
                }

                // Pindahkan file ke folder tujuan
                $file->move($storagePath, $fileName);

                // Path relatif untuk database
                $photoPath = $directory . '/' . $fileName;

                $tanggal_sekarang = now()->format('Y-m-d');
                $request['location'] = "{$request->latitude},{$request->longitude}";
                $request['foto_clock_in'] = $photoPath;  // Ensure this is added to the request data

                // Set clock_in to current time
                $request['clock_in'] = now();

                // Kurangi satu hari dari waktu sekarang untuk check apakah kemarin shift malam atau tidak
                $tanggal_kemarin = Carbon::now()->subDay();

                // Cek absen kemarin apakah shift malam
                $attendance_kemarin = Attendance::where('employee_id', $employee_id)
                    ->where('date', $tanggal_kemarin->format('Y-m-d'))
                    ->first();

                $attendance = Attendance::where('employee_id', $employee_id)
                    ->where('date', $tanggal_sekarang)
                    ->first();

                if (!isset($attendance)) {
                    throw new \Exception("Pegawai Belum Memiliki Shift!");
                }

                if ($attendance_kemarin) {
                    if ($attendance_kemarin->is_day_off != 1 && $attendance_kemarin?->shift?->time_out <= '07:00' && $attendance_kemarin?->shift?->time_in >= '20:00' && $attendance_kemarin->clock_in == null) {
                        $waktu_absen = $attendance->shift->time_in;
                        $perbedaanMenit = $request['clock_in']->greaterThan($waktu_absen)
                            ? Carbon::parse($waktu_absen)->seconds(0)->diffInMinutes(Carbon::parse($request['clock_in'])->seconds(0))
                            : null;

                        $request['late_clock_in'] = ($perbedaanMenit == '0') ? null : $perbedaanMenit;
                        $attendance->update($request->only(['clock_in', 'foto_clock_in', 'location', 'late_clock_in']));
                    } else {
                        $waktu_absen = $attendance->shift->time_in;
                        $perbedaanMenit = $request['clock_in']->greaterThan($waktu_absen)
                            ? Carbon::parse($waktu_absen)->seconds(0)->diffInMinutes(Carbon::parse($request['clock_in'])->seconds(0))
                            : null;

                        $request['late_clock_in'] = ($perbedaanMenit == '0') ? null : $perbedaanMenit;
                        $attendance->update($request->only(['clock_in', 'foto_clock_in', 'location', 'late_clock_in']));
                    }
                } else {
                    $waktu_absen = $attendance->shift->time_in;
                    $perbedaanMenit = $request['clock_in']->greaterThan($waktu_absen)
                        ? Carbon::parse($waktu_absen)->seconds(0)->diffInMinutes(Carbon::parse($request['clock_in'])->seconds(0))
                        : null;

                    $request['late_clock_in'] = ($perbedaanMenit == '0') ? null : $perbedaanMenit;
                    $attendance->update($request->only(['clock_in', 'foto_clock_in', 'location', 'late_clock_in']));
                }

                return response()->json(['message' => 'Berhasil Clock In!']);
            } else {
                return response()->json(['error' => 'Lokasi tidak terdeteksi atau berada di luar jangkauan!'], 422);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function clock_out(Request $request)
    {
        try {
            // Find the employee and company
            $employee = Employee::where('id', $request->employee_id)->first();
            $company = $employee->company;

            $perusahaanLatitude = null;
            $perusahaanLongitude = null;
            $radiusPerusahaan = $company->radius; // Radius dalam kilometer
            $is_clock_in = false; // Check if within radius
            $penggunaLatitude = $request->latitude; // Employee's location
            $penggunaLongitude = $request->longitude; // Employee's location

            // Check if the employee has locations
            if ($employee->locations()->exists()) {
                // If multiple locations exist
                foreach ($employee->locations as $location) {
                    $perusahaanLatitude = $location->latitude;
                    $perusahaanLongitude = $location->longitude;
                    $jarak = haversine($perusahaanLatitude, $perusahaanLongitude, $penggunaLatitude, $penggunaLongitude);

                    // If within radius, allow clock out
                    if ($jarak <= $radiusPerusahaan) {
                        $is_clock_in = true;
                        break; // Stop checking other locations if already within radius
                    }
                }
            } else {
                // Use company coordinates
                $perusahaanLatitude = $company->latitude;
                $perusahaanLongitude = $company->longitude;
                $jarak = haversine($perusahaanLatitude, $perusahaanLongitude, $penggunaLatitude, $penggunaLongitude);

                // If within radius, allow clock out
                if ($jarak <= $radiusPerusahaan) {
                    $is_clock_in = true;
                }
            }

            // Validate location
            if ($is_clock_in) {
                // Validate input
                $validator = Validator::make($request->all(), [
                    'latitude' => 'required',
                    'longitude' => 'required',
                    'photo' => 'required|image' // Ensure it's an image, nullable because it is optional
                ]);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }

                // Store the photo if it exists
                $photoPath = null;
                if ($request->hasFile('photo')) {
                    $file = $request->file('photo');
                    $fileName = 'clock-out-' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $directory = 'absensi/clock-out/' . now()->format('m-Y') . '/' . now()->format('d-m-Y');

                    // Simpan file secara manual ke storage
                    $storagePath = storage_path('app/public/' . $directory);
                    if (!file_exists($storagePath)) {
                        mkdir($storagePath, 0755, true); // Buat folder jika belum ada
                    }

                    // Pindahkan file ke folder tujuan
                    $file->move($storagePath, $fileName);

                    // Path relatif untuk database
                    $photoPath = $directory . '/' . $fileName;
                }

                // Check attendance data for yesterday
                $tanggal_kemarin = Carbon::now()->subDay();
                $attendance_kemarin = Attendance::where('employee_id', $request->employee_id)
                    ->where('date', $tanggal_kemarin->format('Y-m-d'))
                    ->first();

                // Get today's attendance
                $tanggal_sekarang = Carbon::now();
                $attendance = Attendance::where('employee_id', $request->employee_id)
                    ->where('date', $tanggal_sekarang->format('Y-m-d'))
                    ->first();

                if ($attendance_kemarin) {
                    if ($request->check_date == 'yesterday') {
                        // Process for yesterday's clock-out
                        if (!isset($attendance_kemarin)) {
                            throw new \Exception("Pegawai Belum Memiliki Shift!");
                        }

                        $waktu_absen_pulang = $attendance_kemarin->shift->time_out;
                        if ($tanggal_sekarang->format('Y-m-d') == $attendance_kemarin->date) {
                            $waktu_dikonversi = Carbon::createFromFormat('H:i', $attendance_kemarin->shift->time_out);
                            $tanggal_besok = Carbon::parse($attendance_kemarin->date)->addDay();
                            $tanggal_besok->setTime($waktu_dikonversi->hour, $waktu_dikonversi->minute);
                            $perbedaanMenit = abs($tanggal_sekarang->diffInMinutes($tanggal_besok));
                        } else {
                            $perbedaanMenit = $tanggal_sekarang->lessThan($waktu_absen_pulang) ? abs($tanggal_sekarang->diffInMinutes($waktu_absen_pulang)) : null;
                        }
                        $perbedaanMenit = ($perbedaanMenit == 0) ? null : $perbedaanMenit;

                        if ($attendance_kemarin->clock_in != null) {
                            // Update attendance with clock-out time and photo path
                            $attendance_kemarin->update([
                                'clock_out' => $tanggal_sekarang,
                                'early_clock_out' => $perbedaanMenit,
                                'foto_clock_out' => $photoPath // Store the photo path
                            ]);

                            return response()->json(['message' => 'Berhasil Clock Out!']);
                        } else {
                            $perbedaanMenit = $tanggal_sekarang->lessThan($waktu_absen_pulang) ? abs($tanggal_sekarang->diffInMinutes($waktu_absen_pulang)) : null;
                            $attendance->update([
                                'clock_out' => $tanggal_sekarang,
                                'early_clock_out' => $perbedaanMenit,
                                'foto_clock_out' => $photoPath // Store the photo path
                            ]);
                        }
                    }
                }

                if ($attendance) {
                    if ($attendance->clock_in !== null) {
                        // Calculate clock-out time difference
                        $waktu_absen_pulang = $attendance->shift->time_out;

                        if ($attendance->shift->time_out > '06:00' && $attendance->shift->time_out < '07:10') {
                            $waktu_dikonversi = Carbon::createFromFormat('H:i', $attendance->shift->time_out);
                            $tanggal_besok = Carbon::parse($attendance->date)->addDay();
                            $tanggal_besok->setTime($waktu_dikonversi->hour, $waktu_dikonversi->minute);
                            $perbedaanMenit = abs($tanggal_sekarang->diffInMinutes($tanggal_besok));
                        } else {
                            $perbedaanMenit = $tanggal_sekarang->lessThan($waktu_absen_pulang) ? abs($tanggal_sekarang->diffInMinutes($waktu_absen_pulang)) : null;
                        }

                        // Update today's clock-out details with photo path
                        $attendance->update([
                            'clock_out' => $tanggal_sekarang,
                            'early_clock_out' => $perbedaanMenit,
                            'foto_clock_out' => $photoPath, // Store the photo path
                        ]);

                        return response()->json(['message' => 'Berhasil Clock Out!']);
                    } else {
                        return response()->json(['error' => 'Anda belum clock in!'], 422);
                    }
                } else {
                    return response()->json(['error' => 'Data absensi tidak ditemukan!'], 422);
                }
            } else {
                throw new \Exception("Lokasi diluar jangkauan!");
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 404);
        }
    }


    // public function clock_in(Request $request)
    // {
    //     $employee = Employee::where('id', request()->employee_id)->first();
    //     $company = $employee->company;

    //     try {
    //         // dd($request);
    //         // Kolonial
    //         // latitude = -6.763461746615957,
    //         // longitude = 108.16947348181606

    //         // Rs Livaysa
    //         // latitude = -6.764976435287691
    //         // longitude = 108.17786913965288

    //         // Ruko Hana Sakura
    //         // latitude = -6.830374017910872
    //         // longitude = 108.2496121212737

    //         $perusahaanLatitude = null;
    //         $perusahaanLongitude = null;
    //         $radiusPerusahaan = $company->radius; // Radius dalam kilometer
    //         $is_clock_in = false; //cek apakah sesuai radius lokasi
    //         $penggunaLatitude = $request['latitude']; //lokasi absen pegawai
    //         $penggunaLongitude = $request['longitude']; //lokasi absen pegawai


    //         //Jika memiliki lokasi absen ditabel lokasi
    //         if ($employee->locations()->exists()) {

    //             //jika memiliki lebih dari 1 lokasi
    //             foreach ($employee->locations as $i => $location) {
    //                 $perusahaanLatitude = $location->latitude;
    //                 $perusahaanLongitude = $location->longitude;
    //                 $jarak = haversine($perusahaanLatitude, $perusahaanLongitude, $penggunaLatitude, $penggunaLongitude);

    //                 // jika jarak sudah masuk radius, maka izinkan absen
    //                 if ($jarak <= $radiusPerusahaan) {
    //                     $is_clock_in = true;
    //                 }
    //             }
    //         } else {
    //             // koordinat perusahaan
    //             $perusahaanLatitude = $company->latitude;
    //             $perusahaanLongitude = $company->longitude;

    //             $jarak = haversine($perusahaanLatitude, $perusahaanLongitude, $penggunaLatitude, $penggunaLongitude);

    //             // jika jarak sudah masuk radius, maka izinkan absen
    //             if ($jarak <= $radiusPerusahaan) {
    //                 $is_clock_in = true;
    //             }
    //         }

    //         // Validasi apakah pengguna berada dalam radius perusahaan
    //         if ($is_clock_in) {
    //             $employee_id = $request['employee_id'];
    //             $tanggal_sekarang = now()->format('Y-m-d');
    //             $request['location'] = $request['latitude'] . "," . $request['longitude'];
    //             // $request['early_clock_out'] = null;
    //             $validator = Validator::make($request->all(), [
    //                 'latitude' => 'required',
    //                 'longitude' => 'required',
    //                 'clock_in' => 'nullable',
    //                 'clock_out' => 'nullable',
    //             ]);

    //             if ($validator->fails()) {
    //                 return response()->json($validator->errors(), 422);
    //             }
    //             // Kurangi satu hari dari waktu sekarang untuk check apakah kemarin shift malam atau tidak
    //             $tanggal_kemarin = Carbon::now()->subDay();
    //             //cek absen kemarin apakah shift malam
    //             $attendance_kemarin = Attendance::where('employee_id', $employee_id)->where('date', $tanggal_kemarin->format('Y-m-d'))->first();

    //             $request['clock_in'] = Carbon::now();
    //             // $waktu_absen = Carbon::createFromFormat('H:i', '08:00');
    //             $attendance = Attendance::where('employee_id', $employee_id)->where('date', $tanggal_sekarang)->first();
    //             if (!isset($attendance)) {
    //                 throw new \Exception("Pegawai Belum Memiliki Shift!");
    //             }
    //             if ($attendance_kemarin->shift->time_in >= '19.00' && $attendance_kemarin->shift->time_in <= '21.00' && $attendance_kemarin->is_day_off != 1 && $attendance_kemarin->clock_in == null && $attendance->shift->time_in >= '05.00' && $attendance->shift->time_out <= '21.00') {
    //                 $request['late_clock_in'] = 60;
    //                 $attendance_kemarin->update($request->all());
    //             } else {
    //                 $waktu_absen = $attendance->shift->time_in;
    //                 $perbedaanMenit = $request->clock_in->greaterThan($waktu_absen) ? abs($request->clock_in->diffInMinutes($waktu_absen)) : null;
    //                 $perbedaanMenit = ($perbedaanMenit == 0) ? null : $perbedaanMenit;
    //                 $request['late_clock_in'] = $perbedaanMenit;
    //                 $attendance->update($request->all());
    //             }

    //             return response()->json(['message' => 'Berhasil Clock In!']);
    //         } else {
    //             return response()->json(['error' => 'Lokasi tidak terdeteksi atau berada di luar jangkauan!'], 422);
    //         }
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'error' => $e->getMessage()
    //         ], 404);
    //     }
    // }

    // public function clock_out(Request $request)
    // {

    //     $employee = Employee::where('id', request()->employee_id)->first();
    //     $company = $employee->company;
    //     try {
    //         $perusahaanLatitude = null;
    //         $perusahaanLongitude = null;
    //         $radiusPerusahaan = $company->radius; // Radius dalam kilometer
    //         $is_clock_in = false; //cek apakah sesuai radius lokasi
    //         $penggunaLatitude = $request['latitude']; //lokasi absen pegawai
    //         $penggunaLongitude = $request['longitude']; //lokasi absen pegawai


    //         //Jika memiliki lokasi absen ditabel lokasi
    //         if ($employee->locations()->exists()) {

    //             //jika memiliki lebih dari 1 lokasi
    //             foreach ($employee->locations as $i => $location) {
    //                 $perusahaanLatitude = $location->latitude;
    //                 $perusahaanLongitude = $location->longitude;
    //                 $jarak = haversine($perusahaanLatitude, $perusahaanLongitude, $penggunaLatitude, $penggunaLongitude);

    //                 // jika jarak sudah masuk radius, maka izinkan absen
    //                 if ($jarak <= $radiusPerusahaan) {
    //                     $is_clock_in = true;
    //                 }
    //             }
    //         } else {
    //             // koordinat perusahaan
    //             $perusahaanLatitude = $company->latitude;
    //             $perusahaanLongitude = $company->longitude;

    //             $jarak = haversine($perusahaanLatitude, $perusahaanLongitude, $penggunaLatitude, $penggunaLongitude);

    //             // jika jarak sudah masuk radius, maka izinkan absen
    //             if ($jarak <= $radiusPerusahaan) {
    //                 $is_clock_in = true;
    //             }
    //         }

    //         // Validasi apakah pengguna berada dalam radius perusahaan
    //         if ($is_clock_in) {

    //             $validator = Validator::make($request->all(), [
    //                 'latitude' => 'required',
    //                 'longitude' => 'required',
    //             ]);

    //             if ($validator->fails()) {
    //                 return response()->json($validator->errors(), 422);
    //             }

    //             // Kurangi satu hari dari waktu sekarang untuk check apakah kemarin shift malam atau tidak
    //             $tanggal_kemarin = Carbon::now()->subDay();
    //             //cek absen kemarin apakah shift malam
    //             $attendance_kemarin = Attendance::where('employee_id', $request->employee_id)->where('date', $tanggal_kemarin->format('Y-m-d'))->first();

    //             //get data untuk absensi hari ini jika tidak shift malam
    //             $tanggal_sekarang = Carbon::now();
    //             $attendance = Attendance::where('employee_id', $request->employee_id)->where('date', $tanggal_sekarang->format('Y-m-d'))->first();
    //             if ($attendance_kemarin->clock_out == null && $attendance_kemarin->shift->time_out > '06:00' && $attendance_kemarin->shift->time_out < '07:10') {
    //                 if (!isset($attendance_kemarin)) {
    //                     throw new \Exception("Pegawai Belum Memiliki Shift!");
    //                 }
    //                 $waktu_absen_pulang = $attendance_kemarin->shift->time_out;
    //                 if ($tanggal_sekarang->format('Y-m-d') == $attendance_kemarin->date) {
    //                     $waktu_dikonversi = Carbon::createFromFormat('H:i', $attendance_kemarin->shift->time_out);
    //                     $tanggal_besok = Carbon::parse($attendance_kemarin->date)->addDay();
    //                     $tanggal_besok->setTime($waktu_dikonversi->hour, $waktu_dikonversi->minute);
    //                     $perbedaanMenit = abs($tanggal_sekarang->diffInMinutes($tanggal_besok)) + 1;
    //                 } else {
    //                     $perbedaanMenit = $tanggal_sekarang->lessThan($waktu_absen_pulang) ? abs($tanggal_sekarang->diffInMinutes($waktu_absen_pulang)) : null;
    //                 }
    //                 $perbedaanMenit = ($perbedaanMenit == 0) ? null : $perbedaanMenit;

    //                 if ($attendance_kemarin->clock_in != null) {
    //                     $attendance_kemarin->update([
    //                         'clock_out' => $tanggal_sekarang,
    //                         'early_clock_out' => $perbedaanMenit,
    //                     ]);

    //                     return response()->json(['message' => 'Berhasil Clock Out!']);
    //                 } else {
    //                     return response()->json(['error' => 'Anda belum clock in!'], 422);
    //                 }
    //             } else {
    //                 $waktu_absen_pulang = $attendance->shift->time_out;

    //                 if ($attendance->clock_in !== null) {
    //                     $waktu_absen_pulang = $attendance->shift->time_out;

    //                     //jika shift nya shift malam update ke tanggal sekarang dan tetapkan perbedaan menitnya dari jam hari ini sampai jam 07:00 besok
    //                     if ($attendance->shift->time_out > '06:00' && $attendance->shift->time_out < '07:10') {
    //                         $waktu_dikonversi = Carbon::createFromFormat('H:i', $attendance->shift->time_out);
    //                         $tanggal_besok = Carbon::parse($attendance->date)->addDay();
    //                         $tanggal_besok->setTime($waktu_dikonversi->hour, $waktu_dikonversi->minute);
    //                         $perbedaanMenit = abs($tanggal_sekarang->diffInMinutes($tanggal_besok));
    //                     } else {
    //                         $perbedaanMenit = $tanggal_sekarang->lessThan($waktu_absen_pulang) ? abs($tanggal_sekarang->diffInMinutes($waktu_absen_pulang)) + 1 : null;
    //                     }

    //                     $attendance->update([
    //                         'clock_out' => $tanggal_sekarang,
    //                         'early_clock_out' => $perbedaanMenit,
    //                     ]);

    //                     return response()->json(['message' => 'Berhasil Clock Out!']);
    //                 } else {
    //                     return response()->json(['error' => 'Anda belum clock in!'], 422);
    //                 }
    //             }
    //         } else {
    //             throw new \Exception("Lokasi diluar jangkauan!");
    //         }

    //         //return response
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'error' => $e->getMessage()
    //         ], 404);
    //     }
    // }

    public function clock_in_outsource(Request $request)
    {

        try {
            $perusahaanLatitude = -6.764068373562436;
            $perusahaanLongitude = 108.17782772851972;
            $radiusPerusahaan = 1; // Radius dalam kilometer
            $is_clock_in = false; //cek apakah sesuai radius lokasi
            $penggunaLatitude = $request->latitude; //lokasi absen pegawai
            $penggunaLongitude = $request->longitude; //lokasi absen pegawai

            // Panggil fungsi haversine untuk menghitung jarak
            $jarak = haversine($perusahaanLatitude, $perusahaanLongitude, $penggunaLatitude, $penggunaLongitude);

            // jika jarak sudah masuk radius, maka izinkan absen
            if ($jarak <= $radiusPerusahaan) {
                $is_clock_in = true;
            }

            if ($is_clock_in) {
                $imageData = $request->input('image');
                if (!$imageData) {
                    return response()->json(['error' => 'Image data is required'], 400);
                }
                $imageData = str_replace('data:image/png;base64,', '', $imageData);
                $imageData = str_replace(' ', '+', $imageData);

                // Buat nama file unik
                $fileName = time() . '.png';

                // Simpan nama file ke database
                $attendance_outsource = AttendanceOutsource::create([
                    'employee_id' => auth()->user()->employee_id,
                    'date' => Carbon::now()->format('Y-m-d'),
                    'attendance_code' => 1, //code clockin
                    'time' => Carbon::now(),
                    'location' => $request->location,
                    'image' => $fileName
                ]);

                if ($attendance_outsource) {
                    // Simpan gambar ke storage lokal
                    Storage::put('public/img/absen/outsource/' . $fileName, base64_decode($imageData));
                }

                return response()->json(['success' => 'Image uploaded successfully', 'image' => $fileName]);
            } else {
                return response()->json(['error' => 'Lokasi tidak terdeteksi atau berada di luar jangkauan!'], 422);
            }
        } catch (Exception $e) {
            // Log the error for debugging purposes
            Log::error('Error uploading image: ' . $e->getMessage());

            // Return a JSON response with the error message
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function storeAttendanceOutsourcing(Request $request)
    {
        try {
            $lokasi = '-6.764976435287691, 108.17786913965288';

            // Buat nama file unik
            $fileName = time() . '.png';

            if (!$request->has('image')) {
                $fileName = null;
            }

            // Simpan nama file ke database
            $attendance_outsource = AttendanceOutsource::create([
                'employee_id' => $request->employee_id,
                'date' => $request->date,
                'attendance_code' => $request->attendance_code, //code clockin
                'time' => $request->time,
                'location' => $lokasi,
                'image' => $fileName
            ]);

            return response()->json(['success' => 'Berhasil disimpan!']);
        } catch (Exception $e) {
            // Log the error for debugging purposes
            Log::error('Error: ' . $e->getMessage());

            // Return a JSON response with the error message
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function clock_out_outsource(Request $request)
    {
        try {
            $perusahaanLatitude = -6.764068373562436;
            $perusahaanLongitude = 108.17782772851972;
            $radiusPerusahaan = 0.09; // Radius dalam kilometer
            $is_clock_out = false; //cek apakah sesuai radius lokasi
            $penggunaLatitude = $request->latitude; //lokasi absen pegawai
            $penggunaLongitude = $request->longitude; //lokasi absen pegawai

            // Panggil fungsi haversine untuk menghitung jarak
            $jarak = haversine($perusahaanLatitude, $perusahaanLongitude, $penggunaLatitude, $penggunaLongitude);

            // jika jarak sudah masuk radius, maka izinkan absen
            if ($jarak <= $radiusPerusahaan) {
                $is_clock_out = true;
            }

            if ($is_clock_out) {
                // Simpan nama file ke database
                AttendanceOutsource::create([
                    'employee_id' => auth()->user()->employee_id,
                    'date' => Carbon::now()->format('Y-m-d'),
                    'attendance_code' => 2, //code clockout
                    'time' => Carbon::now(),
                    'location' => $request->location,
                    'image' => null
                ]);

                return response()->json(['message' => 'Berhasil clockout']);
            } else {
                return response()->json(['error' => 'Lokasi tidak terdeteksi atau berada di luar jangkauan!'], 422);
            }
        } catch (Exception $e) {

            // Return a JSON response with the error message
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function import(Request $request)
    {
        try {
            Excel::import(new AttendanceImport, $request->file('attendance_shift'));
            //return response
            return response()->json(['message' => 'Jadwal Shift Berhasil di Tambahkan!']);
        } catch (\Exception $e) {
            // dd($e->getMessage());
            return response()->json([
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    public function getAttendance($id)
    {
        try {
            $attendance = Attendance::findOrFail($id);
            $fullname = $attendance->employees->fullname;
            if ($attendance->employees->gender == "Laki-laki") {
                $foto = $attendance->employees->foto ? '/' . $attendance->employees->foto : '/img/demo/avatars/avatar-c.png';
            } else {
                $foto = $attendance->employees->foto ? '/' . $attendance->employees->foto : '/img/demo/avatars/avatar-p.png';
            }
            $jabatan = $attendance->employees->job_position->name ?? 'Staff';
            $organisasi = $attendance->employees->organization->name ?? '-';
            $status = $attendance->day_off->attendance_code->description ?? ($attendance->attendance_code->description ?? 'Libur');
            $start_date = $attendance->day_off->start_date ?? $attendance->date;
            $end_date = $attendance->day_off->end_date ?? $attendance->date;
            $end_date = $attendance->day_off->end_date ?? $attendance->date;
            $email = $attendance->employees->email ?? '-';
            $phone = $attendance->employees->mobile_phone ?? '-';
            return response()->json([
                'attendance' => $attendance,
                'fullname' => $fullname,
                'foto' => $foto,
                'status' => $status,
                'start_date' => tgl($start_date),
                'end_date' => tgl($end_date),
                'email' => $email,
                'phone' => $phone,
                'jabatan' => $jabatan,
                'organisasi' => $organisasi,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function updateAttendance($id)
    {
        try {
            $absensi = Attendance::where('id', $id)->first();
            $shift = Shift::where('id', request()->shift_id)->first();
            $perbedaanMenit_timein = null;
            $perbedaanMenit_timeout = null;
            $clock_in = isset(request()->clock_in) ? request()->clock_in : $absensi->clock_in;
            $clock_out = isset(request()->clock_out) ? request()->clock_out : $absensi->clock_out;
            $is_day_off = null;
            $attendance_code_id = $absensi->attendance_code_id;
            $day_off_request_id = $absensi->day_off_request_id;

            if ($absensi->day_off_request_id != null) {
                $is_day_off = null;
                $day_off_request_id = null;
                $attendance_code_id = null;

                $absensi->day_off->update([
                    'is_approved' => 'Ditolak'
                ]);
            } else {
                if (isset($shift->time_in)) {
                    if ($shift->name == 'dayoff' || $shift->name == 'National Holiday') {
                        $clock_in = null;
                        $clock_out = null;
                        $perbedaanMenit_timein = null;
                        $perbedaanMenit_timeout = null;
                        $is_day_off = 1;
                    } else {
                        $waktu_timein = $shift->time_in;
                        $waktu_timeout = $shift->time_out;

                        if (request()->clock_in) {
                            $perbedaanMenit_timein = Carbon::parse($clock_in)->greaterThan($waktu_timein) ? Carbon::parse($waktu_timein)->seconds(0)->diffInMinutes(Carbon::parse($clock_in)->seconds(0)) : null;
                        }
                        if (request()->clock_out) {
                            $perbedaanMenit_timeout = Carbon::parse($clock_out)->format('H:i') < $waktu_timeout ? abs(Carbon::parse($clock_out)->diffInMinutes(Carbon::parse($waktu_timeout))) : null;
                        }
                    }
                }
            }

            $absensi->update([
                'shift_id' => request()->shift_id,
                'clock_in' => $clock_in,
                'clock_out' => $clock_out,
                'late_clock_in' => $perbedaanMenit_timein,
                'early_clock_out' => $perbedaanMenit_timeout,
                'is_day_off' => $is_day_off,
                'attendance_code_id' => $attendance_code_id,
                'day_off_request_id' => $day_off_request_id,
            ]);

            return response()->json(['message' => 'Jadwal Shift Berhasil diubah!'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function updateManagementShift()
    {
        try {
            $day_off_request_id = [];
            // Loop melalui data yang dikirimkan melalui Ajax
            foreach (request()->attendances as $attendance) {
                // Cari data berdasarkan tanggal dan lakukan pembaruan shift_id
                $shift = Shift::where('id', $attendance['shift_id'])->first();
                $absensi = Attendance::where('date', $attendance['date'])->where('employee_id', $attendance['employee_id'])->first();
                if (isset($shift->time_in)) {
                    $waktu_timein = $shift->time_in;
                    $waktu_timeout = $shift->time_out;
                    $perbedaanMenit_timein = null;
                    $perbedaanMenit_timeout = null;


                    //Jika libur
                    if ($shift->name == 'dayoff' || $shift->name == 'National Holiday') {
                        $clock_in = null;
                        $clock_out = null;
                        $perbedaanMenit_timein = null;
                        $perbedaanMenit_timeout = null;
                        $is_day_off = 1;
                        $absensi->update([
                            'shift_id' => $attendance['shift_id'],
                            'clock_in' => $clock_in,
                            'clock_out' => $clock_out,
                            'late_clock_in' => null,
                            'early_clock_out' => null,
                            'is_day_off' => $is_day_off
                        ]);

                        // Jika masuk
                    } else {
                        if ($absensi->clock_in) {
                            $perbedaanMenit_timein = Carbon::parse($absensi->clock_in)->greaterThan($waktu_timein) ? Carbon::parse($waktu_timein)->diffInMinutes(Carbon::parse($absensi->clock_in)->seconds(0)) : null;
                        }
                        if ($absensi->clock_out) {
                            $perbedaanMenit_timeout = Carbon::parse($absensi->clock_out)->format('H:i') < $waktu_timeout ? Carbon::parse($absensi->clock_out)->diffInMinutes(Carbon::parse($waktu_timeout)) : null;
                        }

                        $absensi->update([
                            'shift_id' => $attendance['shift_id'],
                            'late_clock_in' => $perbedaanMenit_timein == 0 ? null : $perbedaanMenit_timein,
                            'early_clock_out' => $perbedaanMenit_timeout,
                            'is_day_off' => null,
                        ]);

                        if ($absensi->day_off) {
                            $day_off_request_id[] = $absensi->day_off->id;
                            $absensi->update([
                                'is_day_off' => null,
                                'day_off_request_id' => null,
                                'attendance_code_id' => null,
                            ]);
                        }
                    }
                }
            }

            $unique_day_off_request_id = array_unique($day_off_request_id);
            if (count($unique_day_off_request_id) > 0) {
                foreach ($unique_day_off_request_id as $key => $value) {
                    $day_off = DayOffRequest::where('id', $value)->first();
                    $day_off->delete();
                }
            }
            // Response jika pembaruan berhasil
            return response()->json(['message' => 'Jadwal Shift Berhasil diubah!']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function search(Request $request)
    {
        try {
            if (!empty($request['employee_id'])) {
                $employees = Employee::whereIn('id', $request['employee_id'])->get();
            } else {
                $employees = Employee::all();
            }

            foreach ($employees as $employee) {
                // Mendapatkan tanggal awal dan akhir dari periode yang diminta
                $periode = convertPeriodePayroll($request->periode);
                list($startMonth, $endMonth) = explode(' - ', $periode);
                $startPeriod = Carbon::createFromFormat('F Y', $startMonth)->startOfMonth()->addDays(25);
                $endPeriod = Carbon::createFromFormat('F Y', $endMonth)->endOfMonth()->subMonth()->addDays(25);

                $startFormatted = $startPeriod->translatedFormat('F Y');
                $endFormatted = $endPeriod->translatedFormat('F Y');

                $periode = $startFormatted . ' - ' . $endFormatted;

                // Mengkonversi detik ke menit dan membulatkannya ke bawah
                $totalLateInMinutes = floor(Attendance::where('employee_id', $employee->id)
                    ->whereBetween('date', [$startPeriod, $endPeriod])
                    ->sum('late_clock_in'));
                $userId = $request->user_id;

                // return $totalLateInMinutes;
                // Allowance
                $basicSalary = $employee->salary->basic_salary ?? 0;
                $tunjanganJabatan = $employee->salary->tunjangan_jabatan ?? 0;
                $tunjanganProfesi = $employee->salary->tunjangan_profesi ?? 0;
                $tunjanganMakanDanTransport = $employee->salary->tunjangan_makan_dan_transport ?? 0;
                $tunjanganMasaKerja = $employee->salary->tunjangan_masa_kerja ?? 0;
                $guaranteeFee = $employee->salary->guarantee_fee ?? 0;
                $uangDuduk = $employee->salary->uang_duduk ?? 0;
                $taxAllowance = $employee->salary->tax_allowance ?? 0;

                // Deduction
                $potonganKeterlambatanValue = 0;
                $simpananPokok = $employee->deduction->simpanan_pokok ?? 0;
                $potonganIzinValue = 0;
                $potonganSakitValue = 0;
                $potonganAbsensiValue = 0;
                $potonganKoperasiValue = $employee->deduction->potongan_koperasi ?? 0;
                $potonganBPJSKesehatanValue = $employee->deduction->potongan_bpjs_kesehatan ?? 0;
                $potonganBPJSKetenagakerjaanValue = $employee->deduction->potongan_bpjs_ketenagakerjaan ?? 0;
                $potonganPajakValue = $employee->deduction->potongan_pajak ?? 0;

                // Logika potongan gaji berdasarkan total keterlambatan masuk
                if ($totalLateInMinutes > 480) {
                    $potonganKeterlambatanValue = $basicSalary / 173 * 9;
                } elseif ($totalLateInMinutes > 420) {
                    $potonganKeterlambatanValue = $basicSalary / 173 * 8;
                } elseif ($totalLateInMinutes > 360) {
                    $potonganKeterlambatanValue = $basicSalary / 173 * 7;
                } elseif ($totalLateInMinutes > 300) {
                    $potonganKeterlambatanValue = $basicSalary / 173 * 6;
                } elseif ($totalLateInMinutes > 240) {
                    $potonganKeterlambatanValue = $basicSalary / 173 * 5;
                } elseif ($totalLateInMinutes > 180) {
                    $potonganKeterlambatanValue = $basicSalary / 173 * 4;
                } elseif ($totalLateInMinutes > 120) {
                    $potonganKeterlambatanValue = $basicSalary / 173 * 3;
                } elseif ($totalLateInMinutes > 60) {
                    $potonganKeterlambatanValue = $basicSalary / 173 * 2;
                } elseif ($totalLateInMinutes > 30) {
                    $potonganKeterlambatanValue = $basicSalary / 173 * 1;
                }
                $potonganKeterlambatanValue = intval($potonganKeterlambatanValue);

                // Query untuk mencari data absensi sesuai dengan periode yang diminta
                $endPeriodHariKerja = Carbon::createFromFormat('F Y', $endMonth)->endOfMonth()->subMonth()->addDays(24);
                $absensi = Attendance::where('employee_id', $employee->id)
                    ->whereBetween('date', [$startPeriod, $endPeriodHariKerja])
                    ->whereNull('attendance_code_id')
                    ->whereNull('day_off_request_id')
                    ->whereNull('clock_out')
                    ->whereNull('clock_in')
                    ->whereNull('is_day_off')
                    ->count();

                // Jika ada catatan kehadiran yang memenuhi syarat, tambahkan potongan absensi sebesar 99000
                if ($absensi) {
                    $potonganAbsensiValue = $employee->deduction->potongan_absensi ?? 0;
                    $potonganAbsensiValue = $potonganAbsensiValue * $absensi;
                }

                // Query untuk mencari data absensi sesuai dengan periode yang diminta
                $hariKerja = Attendance::where('employee_id', $employee->id)
                    ->whereBetween('date', [$startPeriod, $endPeriod])
                    ->whereNull('attendance_code_id')
                    ->whereNull('day_off_request_id')
                    ->whereNotNull('clock_in')
                    ->whereNull('is_day_off')
                    ->count();


                // Cek apakah terdapat catatan kehadiran yang memenuhi syarat potongan izin
                $izin = Attendance::where('employee_id', $employee->id)
                    ->whereBetween('date', [$startPeriod, $endPeriod])
                    ->where('attendance_code_id', 1)
                    ->where('is_day_off', 1)
                    ->count();

                // Jika ada catatan kehadiran yang memenuhi syarat, tambahkan potongan izin sebesar 99000
                if ($izin) {
                    $potonganIzinValue = ($employee->deduction->potongan_izin ?? 0) * $izin;
                }

                // Cek apakah terdapat catatan kehadiran yang memenuhi syarat potongan izin
                $sakit = Attendance::where('employee_id', $employee->id)
                    ->whereBetween('date', [$startPeriod, $endPeriod])
                    ->where('attendance_code_id', 2)
                    ->where('is_day_off', 1)
                    ->count();

                // Jika ada catatan kehadiran yang memenuhi syarat, tambahkan potongan sakit sebesar 99000
                if ($sakit) {
                    $potonganSakitValue = ($employee->deduction->potongan_sakit ?? 0) * $sakit;
                }

                $totalAllowance = $tunjanganJabatan + $tunjanganProfesi + $tunjanganMakanDanTransport + $tunjanganMasaKerja + $guaranteeFee + $uangDuduk + $taxAllowance;
                $totalDeduction = $potonganKeterlambatanValue + $potonganIzinValue + $potonganSakitValue + $simpananPokok + $potonganKoperasiValue + $potonganAbsensiValue + $potonganBPJSKesehatanValue + $potonganBPJSKetenagakerjaanValue + $potonganPajakValue;
                $takeHomePay = $basicSalary + $totalAllowance - $totalDeduction;


                // Menyimpan potongan gaji ke dalam array
                $payroll[] = [
                    'user_id' => $userId,
                    'employee_id' => $employee->id,
                    'employee_name' => $employee->fullname,
                    'basic_salary' => $basicSalary,
                    'tunjangan_jabatan' => $tunjanganJabatan,
                    'tunjangan_profesi' => $tunjanganProfesi,
                    'tunjangan_makan_dan_transport' => $tunjanganMakanDanTransport,
                    'tunjangan_masa_kerja' => $tunjanganMasaKerja,
                    'guarantee_fee' => $guaranteeFee,
                    'uang_duduk' => $uangDuduk,
                    'tax_allowance' => $taxAllowance,
                    'total_allowance' => $totalAllowance,
                    'potongan_keterlambatan' => $potonganKeterlambatanValue,
                    'potongan_izin' => $potonganIzinValue,
                    'potongan_sakit' => $potonganSakitValue,
                    'simpanan_pokok' => $simpananPokok,
                    'potongan_koperasi' => $potonganKoperasiValue,
                    'potongan_absensi' => $potonganAbsensiValue,
                    'potongan_bpjs_kesehatan' => $potonganBPJSKesehatanValue,
                    'potongan_bpjs_ketenagakerjaan' => $potonganBPJSKetenagakerjaanValue,
                    'potongan_pajak' => $potonganPajakValue,
                    'total_deduction' => $totalDeduction,
                    'take_home_pay' => $takeHomePay,
                    'periode' => $periode,
                    'hari_kerja' => $hariKerja,
                    'is_review' => 0,
                ];
            }

            foreach ($payroll as $data) {
                // Cek apakah payroll untuk karyawan tersebut sudah ada dalam database
                $existingPayroll = Payroll::where('employee_id', $data['employee_id'])
                    ->where('periode', $periode)
                    ->first();

                if ($existingPayroll) {
                    // Jika sudah ada, update data payroll yang ada dengan data baru
                    $existingPayroll->update($data);
                } else {
                    // Jika belum ada, buat data payroll baru
                    Payroll::create($data);
                }
            }
            return response()->json(['message' => 'Payroll berhasil di tambah!']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'No result'
            ], 404);
        }
    }

    public function getDetailAttendance(Request $request)
    {
        $attendance = Attendance::where('date', $request->tanggal)
            ->where('employee_id', $request->employee_id)
            ->first();

        $employeeName = $attendance->employees->fullname;

        if (!$attendance) {
            return response()->json(['success' => false, 'message' => 'Data absensi tidak ditemukan'], 404);
        }

        return response()->json(['success' => true, 'data' => $attendance, 'nama' => $employeeName], 200);
    }
}
