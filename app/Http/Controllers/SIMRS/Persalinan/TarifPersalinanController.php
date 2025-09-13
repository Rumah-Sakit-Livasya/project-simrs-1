<?php

namespace App\Http\Controllers\SIMRS\Persalinan;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Persalinan\TarifPersalinan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log; // Opsional: untuk debugging jika masih ada masalah

class TarifPersalinanController extends Controller
{
    /**
     * Menyimpan atau memperbarui tarif persalinan berdasarkan persalinan, grup penjamin, dan kelas rawat.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $persalinanId ID dari jenis persalinan (dari URL)
     * @param  int  $grupPenjaminId ID dari grup penjamin (dari URL)
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, $persalinanId, $grupPenjaminId)
    {
        // Langkah 1: Validasi input yang masuk dari form.
        // Ini memastikan 'kelas_rawat_ids' dikirim dan berupa string.
        $validator = Validator::make($request->all(), [
            'kelas_rawat_ids' => 'required|string',
            // Anda bisa menambahkan validasi lebih detail untuk setiap tarif jika diperlukan, contoh:
            // 'operator_dokter.*' => 'nullable|numeric|min:0',
            // 'operator_rs.*' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            // Jika validasi gagal, kembalikan response error 422 dengan detail kesalahan.
            return response()->json(['message' => 'Data tidak valid.', 'errors' => $validator->errors()], 422);
        }

        // Langkah 2: Proses input 'kelas_rawat_ids' dengan aman.
        // Input ini diharapkan berupa string yang dipisahkan koma, contoh: "1,2,3".
        $kelasRawatIdsString = $request->input('kelas_rawat_ids');
        $kelasRawatIds = explode(',', $kelasRawatIdsString);

        // Cek jika setelah di-explode hasilnya kosong (misal string inputnya hanya ",")
        if (empty(array_filter($kelasRawatIds))) {
            return response()->json(['message' => 'Tidak ada kelas rawat yang dipilih.'], 400); // 400 Bad Request
        }

        // Langkah 3: Lakukan perulangan untuk setiap kelas rawat dan simpan datanya.
        // Variabel $persalinanId dan $grupPenjaminId diambil dari parameter route,
        // bukan dari body request, untuk memastikan konsistensi.
        foreach ($kelasRawatIds as $kelasRawatId) {
            // Bersihkan spasi dari ID (untuk menghindari error dari input seperti "1, 2, 3")
            $kelasRawatId = trim($kelasRawatId);

            // Lewati iterasi jika ID kosong setelah dibersihkan (misal dari input "1,,2")
            if (empty($kelasRawatId)) {
                continue;
            }

            try {
                TarifPersalinan::updateOrCreate(
                    // Kunci unik untuk mencari record yang ada
                    [
                        'persalinan_id'     => $persalinanId,
                        'group_penjamin_id' => $grupPenjaminId,
                        'kelas_rawat_id'    => $kelasRawatId,
                    ],
                    // Data yang akan diisi atau diperbarui
                    [
                        'operator_dokter'       => $request->input("operator_dokter.{$kelasRawatId}", 0),
                        'operator_rs'           => $request->input("operator_rs.{$kelasRawatId}", 0),
                        'operator_prasarana'    => $request->input("operator_prasarana.{$kelasRawatId}", 0),
                        'ass_operator_dokter'   => $request->input("ass_operator_dokter.{$kelasRawatId}", 0),
                        'ass_operator_rs'       => $request->input("ass_operator_rs.{$kelasRawatId}", 0),
                        'anastesi_dokter'       => $request->input("anastesi_dokter.{$kelasRawatId}", 0),
                        'anastesi_rs'           => $request->input("anastesi_rs.{$kelasRawatId}", 0),
                        'ass_anastesi_dokter'   => $request->input("ass_anastesi_dokter.{$kelasRawatId}", 0),
                        'ass_anastesi_rs'       => $request->input("ass_anastesi_rs.{$kelasRawatId}", 0),
                        'resusitator_dokter'    => $request->input("resusitator_dokter.{$kelasRawatId}", 0),
                        'resusitator_rs'        => $request->input("resusitator_rs.{$kelasRawatId}", 0),
                        'umum_dokter'           => $request->input("umum_dokter.{$kelasRawatId}", 0),
                        'umum_rs'               => $request->input("umum_rs.{$kelasRawatId}", 0),
                        'ruang'                 => $request->input("ruang.{$kelasRawatId}", 0),
                    ]
                );
            } catch (\Exception $e) {
                // Jika terjadi error saat menyimpan (misal: MassAssignmentException), log error tersebut.
                Log::error('Gagal menyimpan tarif persalinan: ' . $e->getMessage());

                // Kembalikan response error 500
                return response()->json(['message' => 'Terjadi kesalahan pada server saat menyimpan data.'], 500);
            }
        }

        // Langkah 4: Kembalikan response sukses.
        return response()->json(['message' => 'Tarif persalinan berhasil disimpan.']);
    }

    /**
     * Mengambil data tarif persalinan yang sudah ada untuk ditampilkan di form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $persalinanId
     * @param  int  $grupPenjaminId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTarifPersalinan(Request $request, $persalinanId, $grupPenjaminId)
    {
        // Mengambil data dari database berdasarkan parameter URL
        $tarif_persalinan = TarifPersalinan::where('persalinan_id', $persalinanId)
            ->where('group_penjamin_id', $grupPenjaminId)
            ->get(); // Ambil semua tarif yang cocok

        // Memformat data agar sesuai dengan yang dibutuhkan oleh JavaScript di frontend.
        // Menggunakan ->map() sudah efisien untuk kasus ini.
        $data = $tarif_persalinan->map(function ($item) {
            return [
                'kelas_rawat_id'        => $item->kelas_rawat_id,
                'operator_dokter'       => $item->operator_dokter,
                'operator_rs'           => $item->operator_rs,
                'operator_prasarana'    => $item->operator_prasarana,
                'ass_operator_dokter'   => $item->ass_operator_dokter,
                'ass_operator_rs'       => $item->ass_operator_rs,
                'anastesi_dokter'       => $item->anastesi_dokter,
                'anastesi_rs'           => $item->anastesi_rs,
                'ass_anastesi_dokter'   => $item->ass_anastesi_dokter,
                'ass_anastesi_rs'       => $item->ass_anastesi_rs,
                'resusitator_dokter'    => $item->resusitator_dokter,
                'resusitator_rs'        => $item->resusitator_rs,
                'umum_dokter'           => $item->umum_dokter,
                'umum_rs'               => $item->umum_rs,
                'ruang'                 => $item->ruang,
            ];
        });

        return response()->json(['data' => $data]);
    }
}
