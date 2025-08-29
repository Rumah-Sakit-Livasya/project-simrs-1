<?php

namespace App\Http\Controllers;

use App\Models\FarmasiResepResponse;
use App\Models\SIMRS\Departement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FarmasiResepResponseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $relations = [
            're',
            're.resep',
            're.resep.registration',
            're.resep.doctor',
            're.resep.doctor.employee',
            're.resep.doctor.department_from_doctors',
            're.resep.registration.patient',
            'inputer',
            'penyiap',
            'raciker',
            'verifikator',
            'penyerah',
        ];
        $query = FarmasiResepResponse::query()->with($relations);
        $filter = false;

        if ($request->filled('tanggal')) {
            $dateRange = explode(' - ', $request->tanggal);
            if (count($dateRange) === 2) {
                $startDate = date('Y-m-d 00:00:00', strtotime($dateRange[0]));
                $endDate = date('Y-m-d 23:59:59', strtotime($dateRange[1]));
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
            $filter = true;
        }

        if ($request->filled('nama_pasien')) {
            $query->whereHas('re.resep.registration.patient', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->nama_pasien}%");
            });
            $filter = true;
        }

        if ($request->filled('medical_record_number')) {
            $query->whereHas('re.resep.registration.patient', function ($q) use ($request) {
                $q->where('medical_record_number', 'like', "%{$request->medical_record_number}%");
            });
            $filter = true;
        }

        if ($request->filled('registration_number')) {
            $query->whereHas('re.resep.registration', function ($q) use ($request) {
                $q->where('registration_number', 'like', "%{$request->registration_number}%");
            });
            $filter = true;
        }

        if ($request->filled('departement_id')) {
            $query->whereHas('re.resep.registration', function ($q) use ($request) {
                $q->where('departement_id', 'like', "%{$request->departement_id}%");
            });
            $filter = true;
        }

        if ($filter) {
            $responses = $query->get();
        } else {
            $responses = FarmasiResepResponse::with($relations)->whereDate('created_at', today())->get();
        }

        $departements = Departement::all();

        return view('pages.simrs.farmasi.resep-response.index', [
            'responses' => $responses,
            'departements' => $departements,
        ]);
    }

    public function report(string $json)
    {
        $data = json_decode($json);
        // dd($data);

        $relations = [
            're',
            're.registration',
            're.registration.penjamin',
            're.resep',
            're.resep.registration',
            're.resep.registration.penjamin',
            're.resep.doctor',
            're.resep.doctor.employee',
            're.resep.doctor.department_from_doctors',
            're.resep.registration.patient',
            'inputer',
            'penyiap',
            'raciker',
            'verifikator',
            'penyerah',
        ];
        $query = FarmasiResepResponse::query()->with($relations);
        $filter = false;

        if (isset($data->tanggal) && $data->tanggal !== '') {
            $dateRange = explode(' - ', $data->tanggal);
            if (count($dateRange) === 2) {
                $startDate = date('Y-m-d 00:00:00', strtotime($dateRange[0]));
                $endDate = date('Y-m-d 23:59:59', strtotime($dateRange[1]));
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
            $filter = true;
        } else {
            return abort(411, 'Property "tanggal" cannot be empty.');
        }

        if (isset($data->nama_pasien) && $data->nama_pasien !== '') {
            $query->whereHas('re.resep.registration.patient', function ($q) use ($data) {
                $q->where('name', 'like', "%{$data->nama_pasien}%");
            });
            $filter = true;
        }

        if (isset($data->medical_record_number) && $data->medical_record_number !== '') {
            $query->whereHas('re.resep.registration.patient', function ($q) use ($data) {
                $q->where('medical_record_number', 'like', "%{$data->medical_record_number}%");
            });
            $filter = true;
        }

        if (isset($data->registration_number) && $data->registration_number !== '') {
            $query->whereHas('re.resep.registration', function ($q) use ($data) {
                $q->where('registration_number', 'like', "%{$data->registration_number}%");
            });
            $filter = true;
        }

        if (isset($data->departement_id) && $data->departement_id !== '') {
            $query->whereHas('re.resep.registration', function ($q) use ($data) {
                $q->where('departement_id', 'like', "%{$data->departement_id}%");
            });
            $filter = true;
        }

        if ($filter) {
            $responses = $query->get();
        } else {
            $responses = FarmasiResepResponse::with($relations)->whereDate('created_at', today())->get();
        }

        return view('pages.simrs.farmasi.resep-response.report', [
            'responses' => $responses,
            'startDate' => $dateRange[0],
            'endDate' => $dateRange[1],
        ]);
    }

    public function telaahResep(int $id)
    {
        $response = FarmasiResepResponse::findOrFail($id);

        if (! isset($response->resep_id) && ! isset($response->re->resep)) {
            return abort(404);
        }

        $resep_id = 0;
        if (isset($response->resep_id)) {
            $resep_id = $response->resep_id;
        } else {
            $resep_id = $response->re->resep->id;
        }

        return redirect()->route('farmasi.transaksi-resep.popup.telaah-resep', ['id' => $resep_id]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(FarmasiResepResponse $farmasiResepResponse)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FarmasiResepResponse $farmasiResepResponse)
    {
        //
    }

    public function updateKeterangan(FarmasiResepResponse $farmasiResepResponse, $id, string $btoa)
    {
        DB::beginTransaction();
        try {
            $response = $farmasiResepResponse->findOrFail($id);
            $data = json_decode(base64_decode($btoa));

            $response->update([
                'keterangan' => $data->keterangan,
            ]);

            $response->save();
            DB::commit();

            return response()->json(['success' => true], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FarmasiResepResponse $farmasiResepResponse, $id, string $btoa)
    {
        DB::beginTransaction();
        try {
            $response = $farmasiResepResponse->findOrFail($id);
            $data = json_decode(base64_decode($btoa));
            $datetime = date('Y-m-d H:i:s', $data->timestamp / 1000);

            switch ($data->type) {
                case 'input_resep':
                    $response->update([
                        'input_resep_user_id' => $data->user_id,
                        'input_resep_time' => $datetime,
                    ]);
                    break;

                case 'penyiapan':
                    $response->update([
                        'penyiapan_user_id' => $data->user_id,
                        'penyiapan_time' => $datetime,
                    ]);
                    break;

                case 'racik':
                    $response->update([
                        'racik_user_id' => $data->user_id,
                        'racik_time' => $datetime,
                    ]);
                    break;

                case 'verifikasi':
                    $response->update([
                        'verifikasi_user_id' => $data->user_id,
                        'verifikasi_time' => $datetime,
                    ]);
                    break;

                case 'penyerahan':
                    $response->update([
                        'penyerahan_user_id' => $data->user_id,
                        'penyerahan_time' => $datetime,
                    ]);
                    break;
            }

            $response->save();
            DB::commit();

            return response()->json(['success' => true], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FarmasiResepResponse $farmasiResepResponse)
    {
        //
    }
}
