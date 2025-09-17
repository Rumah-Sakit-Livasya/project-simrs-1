<?php

namespace App\Http\Controllers\SIMRS\CPPT;

use App\Http\Controllers\Controller;
use App\Models\FarmasiResepElektronik;
use App\Models\FarmasiResepElektronikItems;
use App\Models\FarmasiResepResponse;
use App\Models\Sbar;
use App\Models\SIMRS\CPPT\CPPT;
use App\Models\SIMRS\Doctor;
use App\Models\SIMRS\JadwalDokter;
use App\Models\SIMRS\Registration;
use App\Models\WarehouseBarangFarmasi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class CPPTController extends Controller
{
    public function getJadwalDokter($departement_id)
    {
        $hariIni = ucfirst(Carbon::now()->locale('id')->isoFormat('dddd')); // e.g. 'Selasa'
        $jamSekarang = Carbon::now()->format('H:i:s'); // e.g. '14:33:06'

        $jadwal_dokter = JadwalDokter::with('doctor.employee')
            ->whereHas('doctor', function ($query) use ($departement_id) {
                $query->where('departement_id', $departement_id);
            })
            ->where('hari', $hariIni)
            ->where(function ($query) use ($jamSekarang) {
                $query->where(function ($q) use ($jamSekarang) {
                    // Jadwal normal (misal 08:00 - 17:00)
                    $q->whereRaw('jam_mulai <= jam_selesai')
                        ->where('jam_mulai', '<=', $jamSekarang)
                        ->where('jam_selesai', '>=', $jamSekarang);
                })->orWhere(function ($q) use ($jamSekarang) {
                    // Jadwal malam (misal 22:00 - 06:00)
                    $q->whereRaw('jam_mulai > jam_selesai')
                        ->where(function ($sub) use ($jamSekarang) {
                            $sub->where('jam_mulai', '<=', $jamSekarang)
                                ->orWhere('jam_selesai', '>=', $jamSekarang);
                        });
                });
            })
            ->get();

        $data = $jadwal_dokter->map(function ($item) {
            return [
                'doctor_id' => $item->doctor_id,
                'doctor_name' => $item->doctor->employee->fullname,
            ];
        });

        return response()->json($data);
    }

    public function getCPPT(Request $request)
    {
        try {
            // 1. Validasi input dari filter untuk keamanan
            $request->validate([
                'registration_id' => 'required|exists:registrations,id',
                'start_date' => 'nullable|date_format:Y-m-d',
                'end_date' => 'nullable|date_format:Y-m-d|after_or_equal:start_date',
                'care_status' => 'nullable|string|in:ri,rj,igd',
                'cppt_type' => 'nullable|string|in:dokter,perawat',
            ]);

            // 2. Mulai membangun query, jangan panggil ->get() dulu
            $query = CPPT::with(['user.employee', 'signature'])
                ->where('registration_id', $request->registration_id);

            // ===================================================================
            // APLIKASIKAN FILTER SECARA KONDISIONAL
            // ===================================================================

            // Filter berdasarkan Tipe CPPT (role)
            if ($request->filled('cppt_type')) {
                // Jika filter diisi (misal: 'perawat'), gunakan nilai dari filter
                $query->where('tipe_cppt', $request->cppt_type);
            } else {
                // Jika filter kosong, terapkan logika default method ini (tampilkan semua selain dokter)
                $query->where('tipe_cppt', '!=', 'dokter');
            }

            // Filter berdasarkan Status Rawat (dept)
            if ($request->filled('care_status')) {
                // Frontend mengirim 'ri', 'rj', 'igd'. Database menyimpan 'rawat-inap', 'rawat-jalan', 'igd'
                // Kita perlu menyesuaikannya jika format di database berbeda.
                // Asumsi: format di DB sama dengan yang dikirim frontend (ri, rj, igd)
                // Jika tidak, Anda perlu mapping. Contoh:
                // $careStatusMap = ['ri' => 'rawat-inap', 'rj' => 'rawat-jalan', 'igd' => 'igd'];
                // $dbCareStatus = $careStatusMap[$request->care_status] ?? null;
                // if($dbCareStatus) $query->where('tipe_rawat', $dbCareStatus);

                $query->where('tipe_rawat', $request->care_status);
            }

            // Filter berdasarkan Rentang Tanggal (sdate & edate)
            if ($request->filled('start_date') && $request->filled('end_date')) {
                // Gunakan whereBetween untuk query yang efisien
                // Tambahkan waktu untuk memastikan seluruh hari terakhir ter-cover
                $query->whereBetween('created_at', [
                    $request->start_date . ' 00:00:00',
                    $request->end_date . ' 23:59:59',
                ]);
            }

            // 3. Eksekusi query setelah semua filter diterapkan
            $cppt = $query->orderBy('created_at', 'desc')->get();

            // 4. Lakukan formatting data seperti sebelumnya
            // Tidak perlu lagi mengecek isNotEmpty(), karena map tidak akan error pada collection kosong
            $formattedCppt = $cppt->map(function ($item) {
                $item->nama = optional($item->user->employee)->fullname;

                if (! empty($item->tipe_rawat)) {
                    $item->tipe_rawat = $item->tipe_rawat === 'igd'
                        ? 'UGD'
                        : ucwords(str_replace('-', ' ', $item->tipe_rawat));
                }
                // INI BAGIAN PALING PENTING
                // Pastikan 'signature' di-load, lalu buat 'signature_url' dan 'signature_pic'
                $item->signature_url = $item->signature ? Storage::url($item->signature->signature) : null;
                $item->signature_pic = $item->signature ? $item->signature->pic : null;

                return $item;
            });

            // Langsung kembalikan hasil yang sudah diformat
            return response()->json($formattedCppt, 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Tangani error validasi secara spesifik
            return response()->json(['error' => 'Input tidak valid.', 'details' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan pada server: ' . $e->getMessage()], 500);
        }
    }

    public function getCPPTDokter(Request $request)
    {
        try {
            // Jika filter Tipe CPPT diisi dan BUKAN 'dokter', langsung kembalikan array kosong.
            if ($request->filled('cppt_type') && $request->cppt_type !== 'dokter') {
                return response()->json([], 200);
            }

            // Query dengan eager load user.employee dan signature
            $query = CPPT::where('registration_id', $request->registration_id)
                ->where('tipe_cppt', '=', 'dokter')
                ->with('user.employee', 'signature');

            // Filter berdasarkan Status Rawat (misal: 'ri', 'rj', 'igd')
            if ($request->filled('care_status')) {
                $query->where('tipe_rawat', $request->care_status);
            }

            // Filter berdasarkan Rentang Tanggal
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('created_at', [
                    $request->start_date . ' 00:00:00',
                    $request->end_date . ' 23:59:59',
                ]);
            }

            $cppt = $query->orderBy('created_at', 'desc')->get();

            // Kirim data employee juga
            $formattedCppt = $cppt->map(function ($item) {
                // Nama dokter (jika ada relasi doctor di employee)
                $item->nama = optional($item->user->employee->doctor)->name;

                if (! empty($item->tipe_rawat)) {
                    $item->tipe_rawat = $item->tipe_rawat === 'igd'
                        ? 'UGD'
                        : ucwords(str_replace('-', ' ', $item->tipe_rawat));
                }

                // Signature
                $item->signature_url = $item->signature ? Storage::url($item->signature->signature) : null;
                $item->signature_pic = $item->signature ? $item->signature->pic : null;

                // Kirim data employee (bisa null jika tidak ada)
                $item->employee = $item->user && $item->user->employee ? $item->user->employee : null;

                return $item;
            });

            return response()->json($formattedCppt, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan pada server: ' . $e->getMessage()], 500);
        }
    }

    private function generate_pharmacy_re_code()
    {
        $date = Carbon::now();
        $year = $date->format('y');
        $month = $date->format('m');

        $count = FarmasiResepElektronik::withTrashed()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count() + 1;
        $count = str_pad($count, 6, '0', STR_PAD_LEFT);

        return 'REJ' . $year . $month . $count;
    }

    public function store(Request $request, $type, $registration_number)
    {
        // dd($request->all());
        $validatedData = $request->validate([
            'registration_id' => 'required',
            'doctor_id' => 'nullable',
            'konsulkan_ke' => 'nullable',
            'subjective' => 'required',
            'objective' => 'required',
            'assesment' => 'required',
            'planning' => 'required',
            'instruksi' => 'nullable',
            'evaluasi' => 'nullable',
            'implementasi' => 'nullable',
            'medical_record_number' => 'required',
            // ttd
            'signature_data' => 'nullable|array',
            'signature_data.pic' => 'nullable|string',
            'signature_data.signature_image' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $registration_type = Registration::find($request->registration_id)->registration_type;
            $validatedData['user_id'] = auth()->user()->id;
            $validatedData['tipe_rawat'] = $registration_type;

            if ($request->has('tipe_cppt') && $request->tipe_cppt === 'gizi') {
                $validatedData['tipe_cppt'] = 'gizi';
                $validatedData['diagnosa_gizi'] = $request->input('diagnosa_gizi');
                $validatedData['intervensi_gizi'] = $request->input('intervensi_gizi');
                $validatedData['monitoring'] = $request->input('monitoring');
            } else if ($request->has('perawat_id')) {
                $validatedData['tipe_cppt'] = 'perawat';
                $validatedData['user_id'] = Doctor::find($request->doctor_id)->employee->user->id;
            } elseif ($request->has('doctor_id')) {
                $validatedData['tipe_cppt'] = 'dokter';
                $validatedData['user_id'] = Doctor::find($request->doctor_id)->employee->user->id;
            }

            $cppt = CPPT::create($validatedData);

            // Logika penyimpanan tanda tangan (signature) - samakan dengan PengkajianController
            $signatureData = $validatedData['signature_data'] ?? null;
            if (! empty($signatureData['signature_image']) && str_starts_with($signatureData['signature_image'], 'data:image')) {
                $oldPath = optional($cppt->signature)->signature;
                $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', str_replace(' ', '+', $signatureData['signature_image'])));
                $imageName = 'ttd_cppt_' . $cppt->id . '_' . time() . '.png';
                $newPath = 'signatures/' . $imageName;
                \Storage::disk('public')->put($newPath, $image);

                $cppt->signature()->updateOrCreate(
                    [
                        'signable_id' => $cppt->id,
                        'signable_type' => get_class($cppt),
                    ],
                    [
                        'signature' => $newPath,
                        'pic' => $signatureData['pic'] ?? null,
                        'role' => $signatureData['role'] ?? 'perawat',
                    ]
                );

                if ($oldPath && \Storage::disk('public')->exists($oldPath)) {
                    \Storage::disk('public')->delete($oldPath);
                }
            }

            // farmasi rajal
            if ($request->has('resep_manual') || $request->has('gudang_id')) {
                if (! $request->has('gudang_id')) { // manual recipe only
                    $re = FarmasiResepElektronik::create([
                        'cppt_id' => $cppt->id,
                        'user_id' => auth()->user()->id,
                        'kode_re' => $this->generate_pharmacy_re_code(),
                        'total' => 0,
                        'resep_manual' => $request->get('resep_manual'),
                        'registration_id' => $validatedData['registration_id'],
                    ]);
                } else {
                    $total = $request->get('total_harga_obat');
                    if ($total == null) {
                        throw new \Exception('Total harga obat tidak boleh kosong');
                    }

                    $re = FarmasiResepElektronik::create([
                        'cppt_id' => $cppt->id,
                        'user_id' => auth()->user()->id,
                        'kode_re' => $this->generate_pharmacy_re_code(),
                        'gudang_id' => $request->get('gudang_id'),
                        'total' => $total,
                        'resep_manual' => $request->has('resep_manual') ? $request->get('resep_manual') : null,
                        'registration_id' => $validatedData['registration_id'],
                    ]);

                    $barang_ids = $request->get('barang_id');
                    $qtys = $request->get('qty');
                    $hargas = $request->get('hna');
                    $subtotals = $request->get('subtotal');
                    $instruksi_obats = $request->get('instruksi_obat');
                    $signas = $request->get('signa');
                    if (! $barang_ids || ! $qtys || ! $hargas || ! $subtotals || ! $instruksi_obats || ! $signas) {
                        throw new \Exception('Field tidak lengkap');
                    }

                    foreach ($barang_ids as $key => $barang_id) {
                        FarmasiResepElektronikItems::create([
                            're_id' => $re->id,
                            'barang_id' => $barang_id,
                            'satuan_id' => WarehouseBarangFarmasi::findOrFail($barang_id)->satuan_id,
                            'qty' => $qtys[$key],
                            'harga' => $hargas[$key],
                            'subtotal' => $subtotals[$key],
                            // signa, instruksi
                            'instruksi' => $instruksi_obats[$key],
                            'signa' => $signas[$key],
                        ]);
                    }
                }

                // create response
                FarmasiResepResponse::create([
                    're_id' => $re->id,
                ]);
            }

            DB::commit();

            return response()->json(['message' => ' berhasil ditambahkan!'], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Mengambil data satu CPPT untuk form edit.
     */
    public function edit(CPPT $cppt)
    {
        // Gunakan Gate untuk memeriksa otorisasi
        // Pastikan Anda sudah mendaftarkan 'modify-cppt' di AuthServiceProvider atau bootstrap/app.php
        if (! Gate::allows('modify-cppt', $cppt)) {
            return response()->json(['error' => 'Anda tidak memiliki izin untuk mengedit data ini.'], 403);
        }

        // Eager load relasi yang mungkin dibutuhkan di form edit
        $cppt->load('signature');

        return response()->json($cppt);
    }

    /**
     * Memperbarui data CPPT yang sudah ada.
     */
    public function update(Request $request, CPPT $cppt)
    {
        // 1. Otorisasi
        if (! Gate::allows('modify-cppt', $cppt)) {
            return response()->json(['error' => 'Anda tidak memiliki izin untuk memperbarui data ini.'], 403);
        }

        // 2. Validasi (mirip dengan method store, tapi sesuaikan jika perlu)
        $validatedData = $request->validate([
            'subjective' => 'required|string',
            'objective' => 'required|string',
            'assesment' => 'required|string',
            'planning' => 'required|string',
            'instruksi' => 'nullable|string',
            'evaluasi' => 'nullable|string',
            // Tambahkan validasi lain jika ada input baru saat edit
        ]);

        DB::beginTransaction();
        try {
            // 3. Update data utama
            $cppt->update($validatedData);

            // 4. Handle update tanda tangan jika ada gambar baru yang dikirim
            if ($request->filled('signature_image')) {
                $imageData = $request->input('signature_image');
                $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageData));
                $imageName = 'ttd_cppt_' . $cppt->id . '_' . time() . '.png';
                $path = 'signatures/' . $imageName;

                // Hapus tanda tangan lama jika ada
                if ($cppt->signature && Storage::disk('public')->exists($cppt->signature->signature)) {
                    Storage::disk('public')->delete($cppt->signature->signature);
                }

                // Simpan file baru
                Storage::disk('public')->put($path, $image);

                // Perbarui relasi signature
                $cppt->signature()->update([
                    'signature' => $path,
                    'pic' => $request->input('pic') ?? Auth::user()->name,
                    'role' => $request->input('role'),
                ]);
            }

            DB::commit();

            return response()->json(['success' => 'CPPT berhasil diperbarui!']);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['error' => 'Gagal memperbarui CPPT: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Menghapus data CPPT.
     */
    public function destroy(CPPT $cppt)
    {
        // Otorisasi
        if (! Gate::allows('modify-cppt', $cppt)) {
            return response()->json(['error' => 'Anda tidak memiliki izin untuk menghapus data ini.'], 403);
        }

        DB::beginTransaction();
        try {
            // Hapus tanda tangan terkait jika ada
            if ($cppt->signature && Storage::disk('public')->exists($cppt->signature->signature)) {
                Storage::disk('public')->delete($cppt->signature->signature);
                $cppt->signature()->delete();
            }

            // Hapus resep elektronik terkait jika ada (opsional, tergantung kebutuhan bisnis)
            // FarmasiResepElektronik::where('cppt_id', $cppt->id)->delete();

            $cppt->delete();

            DB::commit();

            return response()->json(['success' => 'CPPT berhasil dihapus.']);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['error' => 'Gagal menghapus CPPT: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Memverifikasi sebuah entri CPPT.
     */
    public function verify(CPPT $cppt)
    {
        // Otorisasi
        if (! Gate::allows('verify-cppt', $cppt)) {
            return response()->json(['error' => 'Anda tidak memiliki izin untuk melakukan verifikasi.'], 403);
        }

        // Pastikan ada kolom untuk menampung status verifikasi di tabel 'cppts'
        // Contoh: $table->boolean('is_verified')->default(false);
        // Contoh: $table->foreignId('verified_by')->nullable()->constrained('users');
        // Contoh: $table->timestamp('verified_at')->nullable();

        $cppt->update([
            'is_verified' => true,
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        return response()->json(['success' => 'CPPT berhasil diverifikasi.']);
    }

    /**
     * Mengambil data SBAR berdasarkan cppt_id.
     *
     * @param  int  $cpptId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSbar($cpptId)
    {
        // Cari SBAR berdasarkan cppt_id
        $sbar = \App\Models\Sbar::where('cppt_id', $cpptId)->latest()->first();

        if (! $sbar) {
            return response()->json(['error' => 'Data SBAR tidak ditemukan untuk CPPT ini.'], 404);
        }

        return response()->json([
            'id' => $sbar->id,
            'cppt_id' => $sbar->cppt_id,
            'user_id' => $sbar->user_id,
            'situation' => $sbar->situation,
            'background' => $sbar->background,
            'assessment' => $sbar->assessment,
            'recommendation' => $sbar->recommendation,
            'created_at' => $sbar->created_at,
            'updated_at' => $sbar->updated_at,

            // TAMBAHKAN DUA BARIS INI SECARA EKSPLISIT
            'signature_penerima' => $sbar->signature_penerima,
            'signature_pemberi' => $sbar->signature_pemberi,
        ]);
    }
}
