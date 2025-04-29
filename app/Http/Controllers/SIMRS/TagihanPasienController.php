<?php

namespace App\Http\Controllers\SIMRS;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\SIMRS\Bilingan;
use App\Models\SIMRS\Departement;
use App\Models\SIMRS\KelasRawat;
use App\Models\SIMRS\Registration;
use App\Models\SIMRS\TagihanPasien;
use App\Models\SIMRS\TindakanMedis;
use App\Models\TarifTindakanMedis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TagihanPasienController extends Controller
{
    public function index(Request $request)
    {
        $query = Bilingan::query();

        // return $request->registration_date;
        if ($request->filled('registration_date')) {
            $dates = explode(' - ', $request->registration_date);
            $start_date = date('Y-m-d 00:00:00', strtotime(trim($dates[0])));
            $end_date = date('Y-m-d 23:59:59', strtotime(trim($dates[1])));
            $query->whereBetween('created_at', [$start_date, $end_date]);
        } else {
            $query->whereDate('created_at', today());
        }

        if ($request->filled('medical_record_number')) {
            $query->whereHas('registration.patient', function ($q) use ($request) {
                $q->where('medical_record_number', $request->medical_record_number);
            });
        }

        if ($request->filled('name')) {
            $query->whereHas('registration.patient', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->name . '%');
            });
        }

        // Format Date
        $registrationDate = old('registration_date') ?? request('registration_date');
        $startDate = $endDate = now()->format('Y-m-d');

        if ($registrationDate && strpos($registrationDate, ' - ') !== false) {
            [$startDate, $endDate] = explode(' - ', $registrationDate);
        }

        $tagihan_pasien = $query->get();
        return view('pages.simrs.keuangan.kasir.index', compact('tagihan_pasien', 'startDate', 'endDate', 'registrationDate'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'tipe_tagihan' => 'required|string',
            'kelas_rawat_id' => 'required|integer',
            'dokter_id' => 'required|integer',
            'departement_id' => 'required|integer',
            'tindakan_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
            'nominal' => 'required|numeric',
            'bilingan_id' => 'required|integer',
            'registration_id' => 'required|integer',
            'user_id' => 'required|integer',
        ]);

        $tindakan = TindakanMedis::where('id', $request->tindakan_id)->first();
        if ($validatedData['tipe_tagihan'] == "Biaya Tindakan Medis") {
            $validatedData['tagihan'] =  "[Tindakan Medis] " . $tindakan->nama_tindakan;
            $validatedData['type'] = "Tindakan Medis";
            $validatedData['tindakan_medis_id'] = $tindakan->id;
        }

        try {
            // Simpan data ke database
            $tagihanPasien = TagihanPasien::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan.',
                'data' => $tagihanPasien,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function detailTagihan($id)
    {
        $bilingan = Bilingan::where('id', $id)->first();
        $kelasRawats = KelasRawat::all();
        $doctors = Employee::where('is_doctor', 1)->get();
        $departements = Departement::all();
        // return dd($kelasRawats);
        return view('pages.simrs.keuangan.kasir.detail', compact('bilingan', 'kelasRawats', 'doctors', 'departements'));
    }

    public function destroyTagihan($id)
    {
        try {
            $tagihan = TagihanPasien::findOrFail($id);
            $tagihan->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error deleting tagihan: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getData($id)
    {
        try {
            $data = TagihanPasien::select(['id', 'date as tanggal', 'tagihan as detail_tagihan', 'quantity', 'nominal', 'tipe_diskon', 'disc', 'diskon as diskon_rp', 'jamin', 'jaminan as jaminan_rp', 'wajib_bayar'])
                ->where('bilingan_id', $id)
                ->get(); // Ambil data yang diperlukan

            // Add a 'del' column for delete actions
            $data = $data->map(function ($item) {
                $item->del = '<button class="btn btn-danger delete" data-id="' . $item->id . '"><i class="fas fa-trash"></i></button>'; // Icon trash button

                // If value is 0, display it as is
                $item->nominal = $item->nominal == 0 ? '0' : $item->nominal;
                $item->diskon_rp = $item->diskon_rp == 0 ? '0' : $item->diskon_rp;
                $item->disc = $item->disc == 0 ? '0' : $item->disc;
                $item->jaminan_rp = $item->jaminan_rp == 0 ? '0' : $item->jaminan_rp;
                $item->jamin = $item->jamin == 0 ? '0' : $item->jamin;

                return $item;
            });

            // Log the data for debugging
            \Log::info('Data fetched for DataTables:', $data->toArray());

            return response()->json([
                'data' => $data,
                'recordsTotal' => $data->count(),
                'recordsFiltered' => $data->count(),
            ]); // Return data in the expected format for DataTables
        } catch (\Exception $e) {
            \Log::error('Error fetching data for DataTables: ' . $e->getMessage());
            return response()->json(['error' => 'Data could not be retrieved 10000.'], 500); // Return error response
        }
    }

    public function getNominalAwal($id)
    {
        try {
            $tagihan = TagihanPasien::findOrFail($id);
            return response()->json(['nominal_awal' => $tagihan->nominal_awal]);
        } catch (\Exception $e) {
            \Log::error('Error fetching nominal awal: ' . $e->getMessage());
            return response()->json(['error' => 'Data could not be retrieved.'], 500);
        }
    }

    // Method to update the TagihanPasien data
    public function updateTagihan(Request $request, $id)
    {
        $validatedData = $request->validate([
            'tanggal' => 'required',
            'detail_tagihan' => 'required',
            'quantity' => 'required',
            'nominal' => 'required',
            'tipe_diskon' => 'required',
            'disc' => 'required',
            'diskon_rp' => 'required',
            'jamin' => 'required',
            'jaminan_rp' => 'required',
            'wajib_bayar' => 'required',
        ]);

        try {
            $tagihan = TagihanPasien::findOrFail($id);
            if (is_null($tagihan->nominal_awal)) {
                $tagihan->nominal_awal = $tagihan->nominal;
            }
            $tagihan->update($validatedData);

            return response()->json(['success' => 'Data updated successfully.']);
        } catch (\Exception $e) {
            \Log::error('Error updating data: ' . $e->getMessage());
            return response()->json(['error' => 'Data could not be updated. Reason: ' . $e->getMessage()], 500);
        }
    }


    public function updateDisc($id)
    {
        try {
            // Ambil tipe diskon yang dikirimkan dari frontend
            $tipeDiskon = request()->input('tipe_diskon');

            // Ambil tagihan berdasarkan ID
            $tagihan = TagihanPasien::findOrFail($id);

            // Ambil informasi tindakan medis
            $tindakan = $tagihan->tindakan_medis;
            $group_penjamin_id = $tagihan->registration->penjamin->group_penjamin_id;
            $kelas_id = $tagihan->registration->kelas_rawat_id;

            // Dapatkan tarif tindakan medis
            $tarif = $tindakan->tarifTindakanMedis($group_penjamin_id, $kelas_id);

            // Inisialisasi diskon berdasarkan tarif yang didapat
            $disc = [
                'share_rs' => $tarif['share_rs'],
                'share_dr' => $tarif['share_dr'],
                'total' => $tarif['total'],
            ];

            // Tentukan diskon berdasarkan tipe diskon
            $quantity = $tagihan->quantity; // Pastikan ada quantity yang relevan
            $diskon = 0;
            if ($tipeDiskon === 'Dokter') {
                $diskon = $disc['share_dr'];
            } elseif ($tipeDiskon === 'Rumah Sakit') {
                $diskon = $disc['share_rs'];
            } elseif ($tipeDiskon === 'All') {
                $diskon = $disc['total'];
            } else {
                $diskon = 0; // Default jika tidak ada tipe yang cocok
            }

            // Ambil data registrasi dan bilingannya
            $registration = $tagihan->registration;
            $bilingan = $tagihan->bilingan;

            // Hitung total tagihan dan wajib bayar
            $nominal = $tagihan->nominal_awal; // Ganti dengan metode yang sesuai
            $totalTagihan = $tagihan->total_tagihan; // Ganti dengan metode yang sesuai
            $totalTagihanWithQuantity = $totalTagihan; // Total tagihan setelah dikalikan quantity
            $wajibBayar = $totalTagihanWithQuantity - $diskon;


            // Update data tagihan dengan diskon baru
            $tagihan->update([
                'tipe_diskon' => $tipeDiskon,
                'diskon' => $diskon,
                'wajib_bayar' => $wajibBayar,
            ]);

            // Kembalikan data ke frontend
            return response()->json([
                'bilingan' => $bilingan,
                'registration' => $registration,
                'disc' => $disc,
                'wajib_bayar' => $wajibBayar,
                'diskon' => $diskon
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching tagihan: ' . $e->getMessage());
            return response()->json(['error' => 'Data could not be retrieved.'], 500);
        }
    }
}
