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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class KPIController extends Controller
{
    public function storeGroupForm()
    {
        try {
            $validator = Validator::make(request()->all(), [
                'nama_group' => 'required',
                'penilai' => 'required',
                'pejabat_penilai' => 'required',
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
                'penilai' => request()->penilai,
                'pejabat_penilai' => request()->pejabat_penilai,
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
        Storage::disk('public')->put($path, base64_decode($image));

        $employee = Employee::find($id);
        if ($employee) {
            $employee->ttd = $imageName;
            $employee->save();
            return response()->json(['path' => Storage::url($path)]);
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
        try {

            $validator = Validator::make(request()->all(), [
                'employee_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $total_nilai_fix = 0;

            $group_penilaian = GroupPenilaian::find($id_form);
            foreach ($group_penilaian->aspek_penilaians as $index => $row) {
                $aspek_penilaian_id = $row->id;
                $aspek_penilaian = $row->nama;

                // Ubah ke lowercase dan ganti spasi dengan underscore
                $formatted_string = Str::slug($aspek_penilaian, '_'); // Underscore sebagai separator

                // Tambahkan prefiks
                $result = 'nilai_' . $formatted_string;
                $total_nilai_fix += $request->input('total_akhir_' . $formatted_string);
                foreach ($row->indikator_penilaians as $key => $col) {
                    PenilaianPegawai::create([
                        'employee_id' => $id_pegawai,
                        'group_penilaian_id' => $id_form,
                        'periode' => $request->periode,
                        'tahun' => $request->tahun,
                        'indikator_penilaian_id' => $col->id,
                        'nilai' => $request->input($result)[$key],
                    ]);
                }
            }


            if ($total_nilai_fix > 95) {
                $keterangan = "Sangat Baik";
            } else if ($total_nilai_fix > 85 && $total_nilai_fix < 96) {
                $keterangan = "Baik";
            } else if ($total_nilai_fix > 65 && $total_nilai_fix < 86) {
                $keterangan = "Cukup";
            } else if ($total_nilai_fix > 50 && $total_nilai_fix < 66) {
                $keterangan = "Kurang";
            } else if ($total_nilai_fix <= 50) {
                $keterangan = "Sangat Kurang";
            }
            // dd($request->catatan);
            RekapPenilaianBulanan::create([
                'group_penilaian_id' => $id_form,
                'employee_id' => $id_pegawai,
                'periode' => $request->periode,
                'tahun' => $request->tahun,
                'total_nilai' => $total_nilai_fix,
                'keterangan' => $keterangan,
                'catatan' => $request->catatan,
                'komentar_pegawai' => $request->komentar_pegawai,
                'komentar_penilai' => $request->komentar_penilai,
                'komentar_pejabat_penilai' => $request->komentar_pejabat_penilai,
            ]);

            return response()->json(['message' => 'Penilaian Berhasil di Tambahkan!']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 404);
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
