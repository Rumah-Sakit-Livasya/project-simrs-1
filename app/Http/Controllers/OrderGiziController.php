<?php

namespace App\Http\Controllers;

use App\Models\JamMakanGizi;
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
use Yajra\DataTables\Facades\DataTables;

class OrderGiziController extends Controller
{
    public function index()
    {
        $kelasRawats = KelasRawat::all();
        return view('pages.simrs.gizi.list-order', compact('kelasRawats'));
    }

    public function datatable(Request $request)
    {
        $query = OrderGizi::with([
            'registration.patient',
            'registration.kelas_rawat',
            'category'
        ])->select('order_gizi.*');

        // Filter
        if ($request->filled('medical_record_number')) {
            $query->whereHas('registration.patient', fn($q) => $q->where('medical_record_number', 'like', '%' . $request->medical_record_number . '%'));
        }
        if ($request->filled('registration_number')) {
            $query->whereHas('registration', fn($q) => $q->where('registration_number', 'like', '%' . $request->registration_number . '%'));
        }
        if ($request->filled('patient_name')) {
            $query->whereHas('registration.patient', fn($q) => $q->where('name', 'like', '%' . $request->patient_name . '%'));
        }
        if ($request->filled('nama_pemesan')) {
            $query->where('nama_pemesan', 'like', '%' . $request->nama_pemesan . '%');
        }
        if ($request->filled('tanggal_order')) {
            $query->whereDate('tanggal_order', $request->tanggal_order);
        }
        if ($request->filled('waktu_makan')) {
            $query->where('waktu_makan', $request->waktu_makan);
        }
        if ($request->filled('kelas_id')) {
            $query->whereHas('registration', fn($q) => $q->where('kelas_id', $request->kelas_id));
        }
        if ($request->filled('status_order') && in_array($request->status_order, ['0', '1'])) {
            $query->where('status_order', $request->status_order);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('detail', '') // Placeholder for expander
            ->addColumn('pasien_info', function ($row) {
                $kelas = $row->registration->kelas_rawat->kelas ?? 'N/A';
                $nama = $row->registration->patient->name ?? 'N/A';
                return "[$kelas] $nama";
            })
            ->addColumn('no_reg_rm', function ($row) {
                $rm = $row->registration->patient->medical_record_number ?? 'N/A';
                $reg = $row->registration->registration_number ?? 'N/A';
                return "$rm / $reg";
            })
            ->addColumn('harga_formatted', function ($row) {
                return 'Rp ' . number_format($row->total_harga);
            })
            ->addColumn('ditagihkan_formatted', function ($row) {
                return $row->ditagihkan ? 'Ya' : 'Tidak';
            })
            ->addColumn('status_payment_formatted', function ($row) {
                if ($row->status_order) {
                    return $row->status_payment ?
                        '<span class="badge badge-success">Payment (Closed)</span>' :
                        '<span class="badge badge-warning">Not Billed</span>';
                }
                return '<span class="badge badge-info">Deliver First</span>';
            })
            ->addColumn('status_order_formatted', function ($row) {
                return $row->status_order ?
                    '<span class="badge badge-success">Delivered</span>' :
                    '<span class="badge badge-info">Process</span>';
            })
            ->addColumn('action', function ($row) {
                return view('pages.simrs.gizi.partials.list-order-actions', ['order' => $row])->render();
            })
            ->addColumn('detail_makanan', function ($row) {
                $row->load('foods');
                return view('pages.simrs.gizi.partials.detail-order-gizi', ['order' => $row])->render();
            })
            ->rawColumns(['status_payment_formatted', 'status_order_formatted', 'action', 'detail_makanan'])
            ->make(true);
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $order = OrderGizi::findOrFail($id);
            $order->status_order = true;
            $order->save();
            return response()->json(['success' => true, 'message' => 'Status order berhasil diubah menjadi Terkirim.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function bulkUpdateStatus(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer'
        ]);

        try {
            OrderGizi::whereIn('id', $validated['ids'])->where('status_order', false)->update(['status_order' => true]);
            return response()->json(['success' => true, 'message' => 'Status order terpilih berhasil diubah.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function bulkLabel(Request $request)
    {
        $order_ids = $request->query('ids');
        if (is_string($order_ids)) {
            $order_ids = json_decode($order_ids, true);
        }

        if (empty($order_ids) || !is_array($order_ids)) {
            return response('Tidak ada ID order yang valid.', 400);
        }

        $orders = OrderGizi::whereIn('id', $order_ids)->get();
        if ($orders->isEmpty()) {
            return response('Data order tidak ditemukan.', 404);
        }

        $pdf = Pdf::loadView('pages.simrs.gizi.partials.bulk-pdf-label-order', compact('orders'));
        return $pdf->stream('bulk-label-order-' . now()->format('Y-m-d_Hi') . '.pdf');
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
            'jam_makans' => JamMakanGizi::all(),
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
            "waktu_makan" => "nullable|string",
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
