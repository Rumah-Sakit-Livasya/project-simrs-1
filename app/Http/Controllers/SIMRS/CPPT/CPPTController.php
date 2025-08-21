<?php

namespace App\Http\Controllers\SIMRS\CPPT;

use App\Http\Controllers\Controller;
use App\Models\FarmasiResepElektronik;
use App\Models\FarmasiResepElektronikItems;
use App\Models\FarmasiResepResponse;
use App\Models\SIMRS\CPPT\CPPT;
use App\Models\SIMRS\JadwalDokter;
use App\Models\SIMRS\Registration;
use App\Models\WarehouseBarangFarmasi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            $id = $request->registration_id;

            $cppt = CPPT::where('registration_id', $id)
                ->where('tipe_cppt', '!=', 'dokter')
                ->with(['user.employee', 'signature']) // tambahkan relasi signature
                ->orderBy('created_at', 'desc')
                ->get();

            if ($cppt->isNotEmpty()) {
                $cppt = $cppt->map(function ($item) {
                    $item->nama = optional($item->user->employee)->fullname;

                    // Format tipe_rawat
                    if (!empty($item->tipe_rawat)) {
                        $item->tipe_rawat = $item->tipe_rawat === 'igd'
                            ? 'UGD'
                            : ucwords(str_replace('-', ' ', $item->tipe_rawat));
                    }

                    // Tambahkan full path ke signature jika ada
                    $item->signature_url = $item->signature
                        ? asset('storage/' . $item->signature->signature)
                        : null;

                    return $item;
                });

                return response()->json($cppt, 200);
            } else {
                return response()->json(['error' => 'Data tidak ditemukan!'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function getCPPTDokter(Request $request)
    {
        try {
            $id = $request->registration_id;

            $cppt = CPPT::where('registration_id', $id)
                ->where('tipe_cppt', '=', 'dokter')
                ->with('user.employee')->orderBy('created_at', 'desc')
                ->get();

            if ($cppt->isNotEmpty()) {
                $cppt = $cppt->map(function ($item) {
                    $item->nama = optional($item->user->employee)->fullname;

                    // Modifikasi tipe_rawat menjadi format huruf kapital pada setiap kata
                    if (!empty($item->tipe_rawat)) {
                        $item->tipe_rawat = $item->tipe_rawat === 'igd'
                            ? 'UGD'
                            : ucwords(str_replace('-', ' ', $item->tipe_rawat));
                    }

                    // Tambahkan full path ke signature jika ada
                    $item->signature_url = $item->signature
                        ? asset('storage/' . $item->signature->signature)
                        : null;

                    return $item;
                });

                return response()->json($cppt, 200);
            } else {
                return response()->json(['error' => 'Data tidak ditemukan!'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
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

        return "REJ" . $year . $month . $count;
    }

    public function store(Request $request, $type, $registration_number)
    {
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
            'medical_record_number' => 'required'
        ]);


        DB::beginTransaction();
        try {
            $registration_type = Registration::find($request->registration_id)->registration_type;
            $validatedData['user_id'] = auth()->user()->id;
            $validatedData['tipe_rawat'] = $registration_type;

            if (auth()->user()->employee->doctor) {
                $validatedData['tipe_cppt'] = 'dokter';
            } else if (str_contains(auth()->user()->name, "A.Md.Kep")) {
                $validatedData['tipe_cppt'] = 'perawat';
            } else if (str_contains(auth()->user()->name, "A.Md.Keb")) {
                $validatedData['tipe_cppt'] = 'bidan';
            } else {
                $validatedData['tipe_cppt'] = auth()->user()->employee->organization->name;
            }


            $cppt = CPPT::create($validatedData);

            // Handle signature jika ada
            if ($request->filled('signature_image')) {
                $imageData = $request->input('signature_image');
                $image = base64_decode(str_replace('data:image/png;base64,', '', str_replace(' ', '+', $imageData)));
                $imageName = 'ttd_' . time() . '.png';
                $path = 'signatures/' . $imageName;

                // Hapus tanda tangan lama jika ada
                if ($cppt->signature && \Storage::disk('public')->exists($cppt->signature->signature)) {
                    \Storage::disk('public')->delete($cppt->signature->signature);
                }

                // Simpan file baru
                \Storage::disk('public')->put($path, $image);

                // Simpan/Update relasi signature
                $cppt->signature()->updateOrCreate(
                    [
                        'signable_id' => $cppt->id,
                        'signable_type' => get_class($cppt),
                    ],
                    [
                        'signature' => $path,
                        'pic' => $request->input('pic'),
                        'role' => $request->input('role') ?? 'perawat',
                    ]
                );
            }

            // farmasi rajal
            if ($request->has('resep_manual') || $request->has('gudang_id')) {
                if (!$request->has("gudang_id")) { // manual recipe only
                    $re = FarmasiResepElektronik::create([
                        'cppt_id' => $cppt->id,
                        'user_id' => auth()->user()->id,
                        'kode_re' => $this->generate_pharmacy_re_code(),
                        'total' => 0,
                        'resep_manual' => $request->get('resep_manual'),
                        'registration_id' => $validatedData['registration_id']
                    ]);
                } else {
                    $total = $request->get('total_harga_obat');
                    if ($total == null) {
                        throw new \Exception("Total harga obat tidak boleh kosong");
                    }

                    $re = FarmasiResepElektronik::create([
                        'cppt_id' => $cppt->id,
                        'user_id' => auth()->user()->id,
                        'kode_re' => $this->generate_pharmacy_re_code(),
                        'gudang_id' => $request->get("gudang_id"),
                        'total' => $total,
                        'resep_manual' => $request->has('resep_manual') ? $request->get('resep_manual') : null,
                        'registration_id' => $validatedData['registration_id']
                    ]);

                    $barang_ids = $request->get('barang_id');
                    $qtys = $request->get('qty');
                    $hargas = $request->get('hna');
                    $subtotals = $request->get('subtotal');
                    $instruksi_obats = $request->get('instruksi_obat');
                    $signas = $request->get('signa');
                    if (!$barang_ids || !$qtys || !$hargas || !$subtotals || !$instruksi_obats || !$signas) {
                        throw new \Exception("Field tidak lengkap");
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
                    "re_id" => $re->id
                ]);
            }

            DB::commit();
            return response()->json(['message' => ' berhasil ditambahkan!'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
