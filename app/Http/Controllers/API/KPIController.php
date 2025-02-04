<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AspekPenilaian;
use App\Models\Employee;
use App\Models\GroupPenilaian;
use App\Models\IndikatorPenilaian;
use App\Models\PenilaianPegawai;
use App\Models\RekapPenilaianBulanan;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class KPIController extends Controller
{
    public function storeGroupForm()
    {
        try {
            $validator = Validator::make(request()->all(), [
                'nama_group' => 'required',
                'status_penilaian' => 'required',
                'rumus_penilaian' => 'required',
            ]);

            if ($validator->fails()) {
                throw new \Exception("Ada inputan yng belum terisi");
            }
            $bobot = 0;
            foreach (request()->bobot as $row) {
                $bobot += $row;
            }

            if ($bobot < 100 || $bobot > 100) {
                throw new \Exception("Total bobot tidak boleh kurang/lebih dari 100");
            }

            $group_penilaian = GroupPenilaian::create([
                'nama_group' => request()->nama_group,
                'status_penilaian' => request()->penilai,
                'rumus_penilaian' => request()->pejabat_penilai,
                'is_active' => 1,
            ]);
            foreach (request()->aspek_penilaian as $index => $row) {

                $aspek_penilaian = AspekPenilaian::create([
                    'group_penilaian_id' => $group_penilaian->id,
                    'nama' => $row,
                    'bobot' => request()->bobot[$index],
                ]);

                $paramName = "indikator_" . $index + 1;

                $items = request()->input($paramName); // default ke array kosong jika tidak ditemukan
                foreach ($items as $inde_ind => $col) {
                    $indikator_penilaian = IndikatorPenilaian::create([
                        'aspek_penilaian_id' => $aspek_penilaian->id,
                        'nama' => $col,
                        'max_nilai' => 5
                    ]);
                }
            }

            //return response
            return response()->json(['message' => 'Penilaian Berhasil di Tambahkan!']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function saveSignature(Request $request, $id)
    {
        $data = $request->input('signature_image');

        // Base64-encoded image string
        $image = str_replace('data:image/png;base64,', '', $data);
        $image = str_replace(' ', '+', $image);
        $imageName = 'signature_' . $id . '.png';

        // Save the image to the storage
        $path = 'employee/ttd/' . $imageName;
        Storage::disk('private')->put($path, base64_decode($image));

        $employee = Employee::find($id);

        $pegawai = Employee::findOrFail($request->idPegawai);
        $pejabat_penilai = Employee::findOrFail($request->pejabat_penilai);
        $direktur = Employee::findOrFail($request->direktur);

        $encryptTahunDanEmployeeId = rtrim(strtr(base64_encode("$request->idPegawai-$request->tahun"), '+/', '-_'), '=');
        $message = "Penilaian atas nama {$pegawai->fullname} telah selesai dibuat. Silakan periksa dan tandatangani dokumen penilaian tersebut melalui link berikut: \n";
        $message .= route('kpi.show.form-penilaian.done', [$request->idForm, $request->periode, $encryptTahunDanEmployeeId]);

        $headers = [
            'Key:KeyAbcKey',
            'Nama:arul',
            'Sandi:123###!!',
        ];


        if ($request->idRekap != null || $request->idRekap != "") {
            $rekap = RekapPenilaianBulanan::findOrFail($request->idRekap);
            if ($request->tipe == 'ttd_pegawai') {
                $rekap->update(['is_verified_pegawai' => 1]);
            } elseif ($request->tipe == 'ttd_penilai') {
                $rekap->update(['is_verified_penilai' => 1]);

                $httpData = [
                    'number'  => $pejabat_penilai->mobile_phone,
                    'message' => $message,
                ];

                // Kirim request HTTP menggunakan cURL
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
            } elseif ($request->tipe == 'ttd_pejabat_penilai') {
                $rekap->update(['is_verified_pejabat_penilai' => 1]);

                $httpData = [
                    'number'  => $direktur->mobile_phone,
                    'message' => $message,
                ];

                // Kirim request HTTP menggunakan cURL
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
            } elseif ($request->tipe == 'ttd_hrd') {
                $rekap->update(['is_verified_hrd' => 1]);
            } elseif ($request->tipe == 'ttd_direktur') {
                $rekap->update(['is_verified_direktur' => 1]);
            }
        }

        if ($employee) {
            $employee->ttd = $imageName;
            $employee->save();
            return response()->json([
                'path' => url('/api/dashboard/kpi/private-signature/' . $imageName), // URL ke file private
            ]);
        }
    }

    public function updateGroupForm($id)
    {
        try {
            $group_penilaian = GroupPenilaian::find($id);
            $group_penilaian->update(
                [
                    'nama_group' => request()->nama_group,
                    'penilai' => request()->penilai,
                    'pejabat_penilai' => request()->pejabat_penilai,
                    'rumus_penilaian' => request()->rumus_penilaian
                ]
            );
            foreach ($group_penilaian->aspek_penilaians as $index => $aspek) {
                $aspek->update([
                    'nama' => request()->aspek_penilaian[$index],
                    'bobot' => request()->bobot[$index]
                ]);
                $index2 = 0;
                foreach ($aspek->indikator_penilaians as $indikator) {
                    $indikator->update([
                        'nama' => request()->input("indikator_$index")[$index2]
                    ]);
                    $index2++;
                }
            }
            return response()->json(['message' => 'Form Berhasil di Ubah!']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function storePenilaianPegawai(Request $request, $id_form, $id_pegawai)
    {

        $message = '';
        // dd(request());
        DB::beginTransaction();

        try {
            $validator = Validator::make(request()->all(), [
                'employee_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $check_rekap_penilaian = RekapPenilaianBulanan::where('employee_id', $id_pegawai)
                ->where('group_penilaian_id', $id_form)
                ->where('tahun', $request->tahun)
                ->where('periode', $request->periode)
                ->first();

            $check_penilaian_pegawai = PenilaianPegawai::where('employee_id', $id_pegawai)
                ->where('group_penilaian_id', $id_form)
                ->where('tahun', $request->tahun)
                ->where('periode', $request->periode)
                ->first();

            if (isset($check_penilaian_pegawai) || isset($check_rekap_penilaian)) {
                return response()->json([
                    'error' => 'Pegawai sudah diberikan penilaian!'
                ], 500);
            }

            $total_nilai_fix = 0;
            $group_penilaian = GroupPenilaian::find($id_form);

            foreach ($group_penilaian->aspek_penilaians as $row) {
                $formatted_string = Str::slug($row->nama, '_');
                $result = 'nilai_' . $formatted_string;
                $total_nilai_fix += $request->input('total_akhir_' . $formatted_string);

                foreach ($row->indikator_penilaians as $key => $col) {
                    PenilaianPegawai::create([
                        'employee_id' => $id_pegawai,
                        'group_penilaian_id' => $id_form,
                        'periode' => $request->periode,
                        'tahun' => $request->tahun,
                        'pejabat_penilai' => $request->pejabat_penilai,
                        'penilai' => $request->penilai,
                        'indikator_penilaian_id' => $col->id,
                        'nilai' => $request->input($result)[$key],
                    ]);
                }
            }

            $keterangan = match (true) {
                $total_nilai_fix > 95 => "Sangat Baik",
                $total_nilai_fix > 85 => "Baik",
                $total_nilai_fix > 65 => "Cukup",
                $total_nilai_fix > 50 => "Kurang",
                default => "Sangat Kurang",
            };

            $request['is_ya'] = $request->keterangan_ya ? 1 : 0;
            $request['is_tidak'] = $request->keterangan_ya ? 0 : 1;

            $rekap = RekapPenilaianBulanan::create([
                'group_penilaian_id' => $id_form,
                'employee_id' => $id_pegawai,
                'tahun' => $request->tahun,
                'periode' => $request->periode,
                'total_nilai' => $total_nilai_fix,
                'keterangan' => $keterangan,
                'keterangan_ya' => $request->keterangan_ya,
                'keterangan_tidak' => $request->keterangan_tidak,
                'is_ya' => $request->is_ya,
                'is_tidak' => $request->is_tidak
            ]);

            // Commit jika semua berhasil
            DB::commit();
            // dd($rekap->employee->mobile_phone);

            $penilai = Employee::findOrFail($request->penilai);
            $encryptTahunDanEmployeeId = rtrim(strtr(base64_encode("$id_pegawai-$request->tahun"), '+/', '-_'), '=');
            $message = "Penilaian atas nama {$rekap->employee->fullname} telah selesai dibuat. Silakan periksa dan tandatangani dokumen penilaian tersebut melalui link berikut: \n";
            $message .= route('kpi.show.form-penilaian.done', [$id_form, $request->periode, $encryptTahunDanEmployeeId]);

            $headers = [
                'Key:KeyAbcKey',
                'Nama:arul',
                'Sandi:123###!!',
            ];

            // Daftar nomor tujuan
            $numbers = [
                $rekap->employee->mobile_phone,
                $penilai->mobile_phone
            ];

            foreach ($numbers as $number) {
                $httpData = [
                    'number'  => $number,
                    'message' => $message,
                ];

                // Kirim request HTTP menggunakan cURL
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

                // Cek jika ada error
                if ($httpCode != 200) {
                    error_log("Gagal mengirim pesan ke {$number}: " . $curlError);
                }
            }


            return response()->json(['success' => 'Penilaian berhasil disimpan']);
        } catch (\Exception $e) {
            // Rollback semua perubahan jika terjadi error
            DB::rollBack();

            // Hapus data PenilaianPegawai dengan employee_id terkait
            PenilaianPegawai::where('employee_id', $id_pegawai)
                ->where('group_penilaian_id', $id_form)
                ->where('tahun', $request->tahun)
                ->delete();

            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function getPegawai($id_pegawai)
    {
        try {
            $employee = Employee::where('id', $id_pegawai)->first(['fullname', 'job_position_id', 'organization_id', 'employee_code']);
            $unit = $employee->organization->name;
            $jabatan = $employee->jobPosition->name;
            $pegawai = [
                'nama' => $employee->fullname,
                'jabatan' => $jabatan,
                'unit' => $unit,
                'nip' => $employee->employee_code
            ];
            return response()->json($pegawai, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function destroyForm($id)
    {
        try {
            $form = GroupPenilaian::find($id);
            $form->delete();
            //return response
            return response()->json(['message' => 'Group Form Berhasil di Hapus!']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function destroyIndikator($id)
    {
        try {
            $indikator = IndikatorPenilaian::find($id);
            $indikator->delete();
            //return response
            return response()->json(['message' => 'Indikator Berhasil di Hapus!']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function destroyAspek($id)
    {
        try {
            $aspek = AspekPenilaian::find($id);
            $aspek->delete();
            //return response
            return response()->json(['message' => 'Aspek Berhasil di Hapus!']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 404);
        }
    }
}
