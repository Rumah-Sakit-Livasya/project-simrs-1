<?php

namespace App\Http\Controllers\SIMRS\Gizi;

use App\Http\Controllers\Controller;
use App\Models\KategoriGizi;
use App\Models\MakananGizi;
use App\Models\MakananMenuGizi;
use App\Models\MenuGizi;
use App\Models\OrderGizi;
use App\Models\OrderMakananGizi;
use App\Models\SIMRS\KelasRawat;
use App\Models\SIMRS\Penjamin;
use App\Models\SIMRS\Registration;
use App\Models\SIMRS\Room;
use Illuminate\Http\Request;

class GiziController extends Controller
{
    public function index(Request $request)
    {
        $query = Registration::query()->with(["patient", "penjamin", "patient.bed", "patient.bed.room", "patient.family", "kelas_rawat"]);
        $filters = ['medical_record_number', 'registration_number', 'no_order'];

        foreach ($filters as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, 'like', '%' . $request->$filter . '%');
            }
        }

        $query->where("status", "aktif");
        $query->where("registration_type", "rawat-inap");

        if ($request->filled('patient_name')) {
            $query->whereHas('patient', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->patient_name . '%');
            });
        }

        if ($request->filled('penjamin_id')) {
            $query->whereHas('penjamin', function ($q) use ($request) {
                $q->where('id', '%' . $request->penjamin_id . '%');
            });
        }

        if ($request->filled('kelas_id')) {
            $query->whereHas('kelas_rawat', function ($q) use ($request) {
                $q->where('id', '%' . $request->kelas_id . '%');
            });
        }

        if ($request->filled('room_id')) {
            $query->whereHas('patient.bed.room', function ($q) use ($request) {
                $q->where('id', '%' . $request->room_id . '%');
            });
        }

        if ($request->filled('keluarga_pj')) {
            $query->whereHas('patient.family', function ($q) use ($request) {
                $q->where('family_name', 'like', '%' . $request->keluarga_pj . '%');
            });
        }

        if ($request->filled('address')) {
            $query->whereHas('patient', function ($q) use ($request) {
                $q->where('address', 'like', '%' . $request->address . '%');
            });
        }

        // Get the filtered results if any filter is applied
        $registrations = $query->orderBy('date', 'asc')->get();


        return view('pages.simrs.gizi.list-pasien', [
            'registrations' => $registrations,
            'penjamins' => Penjamin::all(),
            'kelasRawats' => KelasRawat::all(),
            'rooms' => Room::all()
        ]);
    }

    public function reports()
    {
        return view("pages.simrs.gizi.laporan", [
            "categories" => KategoriGizi::all(),
            "menus" => MenuGizi::all(),
            "foods" => MakananGizi::all()
        ]);
    }

    public function reports_view($fromDate, $endDate, $kategori_id, $food_id, $status_payment, $waktu_makan, $untuk)
    {

        $query = OrderGizi::query()->with(["foods", "category"]);
        $query->whereBetween('tanggal_order', [$fromDate, $endDate]);

        if ($kategori_id && $kategori_id != '-') {
            $query->whereHas('category', function ($q) use ($kategori_id) {
                $q->where('id', 'like', '%' . $kategori_id . '%');
            });
        }

        if ($food_id && $food_id != '-') {
            $query->whereHas('foods', function ($q) use ($food_id) {
                $q->where('makanan_id', 'like', '%' . $food_id . '%');
            });
        }

        if ($status_payment && $status_payment != '-') {
            $query->where('status_payment', 'like', '%' . $status_payment . '%');
        }

        if ($waktu_makan && $waktu_makan != '-') {
            $query->where('waktu_makan', 'like', '%' . $waktu_makan . '%');
        }

        if ($untuk && $untuk != '-') {
            $query->where('untuk', 'like', '%' . $untuk . '%');
        }

        $orders = $query->get();

        return view('pages.simrs.gizi.partials.laporan-view', [
            'orders' => $orders,
            'startDate' => $fromDate,
            'endDate' => $endDate
        ]);
    }

}
