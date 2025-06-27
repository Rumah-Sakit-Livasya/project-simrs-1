<?php

namespace App\Http\Controllers;

use App\Models\KategoriGizi;
use App\Models\MakananGizi;
use App\Models\MakananMenuGizi;
use App\Models\MenuGizi;
use App\Models\OrderGizi;
use App\Models\OrderMakananGizi;
use App\Models\SIMRS\KelasRawat;
use App\Models\SIMRS\Registration;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;

class OrderGiziController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = OrderGizi::query()->with(["registration", "registration.patient", "registration.kelas_rawat"]);
        $filters = ['nama_pemesan', 'waktu_makan', 'tanggal_order', "status_order"];

        foreach ($filters as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, 'like', '%' . $request->$filter . '%');
            }
        }

        if ($request->filled('medical_record_number')) {
            $query->whereHas('registration.patient', function ($q) use ($request) {
                $q->where('medical_record_number', 'like', '%' . $request->medical_record_number . '%');
            });
        }

        if ($request->filled('registration_number')) {
            $query->whereHas('registration', function ($q) use ($request) {
                $q->where('registration_number', 'like', '%' . $request->registration_number . '%');
            });
        }

        if ($request->filled('patient_name')) {
            $query->whereHas('registration.patient', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->patient_name . '%');
            });
        }

        if ($request->filled('kelas_id')) {
            $query->whereHas('registration.kelas_rawat', function ($q) use ($request) {
                $q->where('id', $request->kelas_id);
            });
        }

        // Get the filtered results if any filter is applied
        $orders = $query->orderBy('tanggal_order', 'asc')->get();


        return view('pages.simrs.gizi.list-order', [
            'orders' => $orders,
            'kelasRawats' => KelasRawat::all()
        ]);
    }

    public function edit($id)
    {
        $order = OrderGizi::findOrFail($id);

        return view('pages.simrs.gizi.partials.edit-order', compact('order'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($untuk, $registration_id)
    {
        $registration = Registration::findOrFail($registration_id);

        $view = "pages.simrs.gizi.partials.popup-order-pasien";
        if ($untuk == "keluarga") {
            $view = "pages.simrs.gizi.partials.popup-order-keluarga";
        }

        return view($view, [
            'registration' => $registration,
            'categories' => KategoriGizi::all(),
            'menus' => MenuGizi::all(),
            'foods' => MakananGizi::all(),
            'menu_foods' => MakananMenuGizi::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            "user_id" => "required|integer",
            "employee_id" => "required|integer",
            "untuk" => "required|string|in:pasien,keluarga",
            "registration_id" => "required|integer",
            "nama_pemesan" => "required|string|max:255",
            "tanggal_order" => "required|date",
            "kategori_id" => "required|integer",
            "waktu_makan" => "nullable|string|in:pagi,siang,sore",
            "ditagihkan" => "required|boolean",
            "digabung" => "required|boolean",
            "foods_id" => "required|array",
            "foods_id.*" => "required|integer",
            "qty" => "required|array",
            "qty.*" => "required|integer|min:1",
            "total_harga" => "required|numeric|min:0",
        ]);

        try {
            $order = OrderGizi::create($validatedData);
            $order_id = $order->id;

            foreach ($validatedData['qty'] as $key => $qty) {
                $food_id = $validatedData["foods_id"][$key];
                $food = MakananGizi::findOrFail($food_id);

                for ($i = 0; $i < $qty; $i++) {
                    OrderMakananGizi::create([
                        "order_id" => $order_id,
                        "makanan_id" => $food_id,
                        "harga" => $food->harga
                    ]);
                }
            }

            return redirect()->back()->with('success', 'Order berhasil dibuat!');

        } catch (Exception $e) {
            return back()->with("error", $e->getMessage());
        }
    }

    public function update_status(Request $request)
    {
        $validatedData = $request->validate([
            "id" => "integer|required"
        ]);

        $order = OrderGizi::findOrFail($validatedData["id"]);
        $order->status_order = true;
        $order->save();
        return response()->json([
            "success" => true
        ]);
    }

    public function label($order_id)
    {
        $order = OrderGizi::findOrFail($order_id);
        $pdf = Pdf::loadView('pages.simrs.gizi.partials.pdf-label-order', compact('order'));

        return $pdf->stream('label-order-' . $order_id . '.pdf');
    }

    public function print_nota($order_id)
    {
        $order = OrderGizi::findOrFail($order_id);

        return view('pages.simrs.gizi.partials.nota-order', compact('order'));
    }

    public function bulk_label($order_ids)
    {
        $order_ids = json_decode($order_ids, true);

        // get orders with ids inside the order_ids array
        $orders = OrderGizi::whereIn('id', $order_ids)->get();

        // foreach ($orders as $order) {
        //     dd($order);
        // }

        $pdf = Pdf::loadView('pages.simrs.gizi.partials.bulk-pdf-label-order', compact('orders'));

        return $pdf->stream('bulk-label-order-' . now()->format('Y-m-d_Hi') . '.pdf');
    }

    public function update(Request $request)
    {
        $order = OrderGizi::findOrFail($request->id);
        $food_ids = $order->foods->pluck('id')->toArray();

        for ($i = 0; $i < count($food_ids); $i++) {
            OrderMakananGizi::findOrFail($food_ids[$i])
                ->update([
                    'persentase_habis' => $request["habis_" . $food_ids[$i]]
                ]);
        }

        return "<script>window.close()</script>";
    }
}
