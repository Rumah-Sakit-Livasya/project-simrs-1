<?php

namespace App\Http\Controllers\SIMRS\Obat;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Registration;
use App\Models\SIMRS\Obat\OrderObat;
use App\Models\SIMRS\Obat\OrderObatDetail;
use App\Models\SIMRS\Doctor;
use App\Models\Warehouse;
use App\Models\Obat;
use App\Models\WarehouseBarangFarmasi;
use App\Models\WarehouseMasterGudang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class OrderObatController extends Controller
{
    public function data(int $registration_id)
    {
        $orders = OrderObat::with(['doctor', 'warehouse', 'user'])
            ->where('registration_id', $registration_id)
            ->select('order_obats.*');

        return DataTables::of($orders)
            ->addIndexColumn()
            ->editColumn('order_date', fn($row) => \Carbon\Carbon::parse($row->order_date)->format('d-m-Y H:i'))
            ->addColumn('action', function ($row) {
                $btn = '<button type="button" class="btn btn-warning btn-xs btn-icon waves-effect waves-themed btn-edit-order" data-id="' . $row->id . '" title="Edit"><i class="fal fa-pencil"></i></button> ';
                $btn .= '<button type="button" class="btn btn-danger btn-xs btn-icon waves-effect waves-themed btn-delete-order" data-id="' . $row->id . '" title="Hapus"><i class="fal fa-trash"></i></button>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function fetchCreateForm(int $registration_id)
    {
        $registration = Registration::findOrFail($registration_id);
        $warehouses = WarehouseMasterGudang::get();
        $doctors = Doctor::get();
        return view('pages.simrs.order_obat.form_partial', compact('registration', 'warehouses', 'doctors'));
    }

    public function fetchEditForm(int $registration_id, OrderObat $order_obat)
    {
        $registration = Registration::findOrFail($registration_id);
        $order_obat->load('details.obat');
        $warehouses = WarehouseMasterGudang::get();
        $doctors = Doctor::get();
        return view('pages.simrs.order_obat.form_partial', compact('order_obat', 'registration', 'warehouses', 'doctors'));
    }

    public function store(Request $request, int $registration_id)
    {
        $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'items' => 'required|json'
        ]);

        DB::beginTransaction();
        try {
            $registration = Registration::findOrFail($registration_id);
            $order = OrderObat::create([
                'registration_id' => $registration_id,
                'patient_id' => $registration->patient_id,
                'doctor_id' => $request->doctor_id,
                'warehouse_id' => $request->warehouse_id,
                'user_id' => Auth::id(),
                'no_order' => $this->generateOrderNumber(),
                'order_date' => now(),
            ]);
            $items = json_decode($request->items, true);
            foreach ($items as $item) {
                OrderObatDetail::create([
                    'order_obat_id' => $order->id,
                    'obat_id' => $item['obat_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['quantity'] * $item['price'],
                ]);
            }
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Order berhasil disimpan.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, int $registration_id, OrderObat $order_obat)
    {
        $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'items' => 'required|json'
        ]);

        DB::beginTransaction();
        try {
            $order_obat->update([
                'doctor_id' => $request->doctor_id,
                'warehouse_id' => $request->warehouse_id,
            ]);
            $order_obat->details()->delete();
            $items = json_decode($request->items, true);
            foreach ($items as $item) {
                OrderObatDetail::create([
                    'order_obat_id' => $order_obat->id,
                    'obat_id' => $item['obat_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['quantity'] * $item['price'],
                ]);
            }
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Order berhasil diperbarui.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(int $registration_id, OrderObat $order_obat)
    {
        try {
            $order_obat->delete();
            return response()->json(['success' => true, 'message' => 'Order berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus order.'], 500);
        }
    }

    public function searchObat(Request $request)
    {
        $term = $request->input('term');
        $obats = WarehouseBarangFarmasi::where('name', 'LIKE', "%{$term}%")
            ->limit(10)
            ->get(['id', 'name as text', 'price']);
        return response()->json($obats);
    }

    private function generateOrderNumber()
    {
        $date = now()->format('ymd');
        $lastOrder = OrderObat::where('no_order', 'like', "OBAT{$date}%")->latest()->first();
        $newNumber = '0001';
        if ($lastOrder) {
            $lastNumber = (int) substr($lastOrder->no_order, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        }
        return "OBAT{$date}{$newNumber}";
    }
}
