<?php

namespace App\Http\Controllers;

use App\Exports\BarangNonFarmasiExport;
use App\Imports\BarangNonFarmasiImport;
use App\Models\WarehouseBarangNonFarmasi;
use App\Models\WarehouseGolonganBarang;
use App\Models\WarehouseKategoriBarang;
use App\Models\WarehouseKelompokBarang;
use App\Models\WarehouseMasterBarangEditLog;
use App\Models\WarehouseSatuanBarang;
use App\Models\WarehouseSatuanTambahanBarangNonFarmasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\Facades\DataTables;

class WarehouseBarangNonFarmasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("pages.simrs.warehouse.master-data.barang-non-farmasi");
    }
    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function data(Request $request)
    {
        $query = WarehouseBarangNonFarmasi::with(['satuan', 'kategori', 'golongan', 'kelompok'])
            ->select('warehouse_barang_non_farmasi.*');

        // Filtering
        if ($request->filled('kode')) {
            $query->where('kode', 'like', '%' . $request->kode . '%');
        }
        if ($request->filled('nama')) {
            $query->where('nama', 'like', '%' . $request->nama . '%');
        }
        if ($request->filled('kategori')) {
            $query->whereHas('kategori', function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->kategori . '%');
            });
        }
        if ($request->filled('kelompok')) {
            $query->whereHas('kelompok', function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->kelompok . '%');
            });
        }
        if ($request->filled('golongan')) {
            $query->whereHas('golongan', function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->golongan . '%');
            });
        }
        if ($request->filled('aktif')) {
            $query->where('aktif', $request->aktif);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('kategori_name', fn($row) => $row->kategori->nama ?? '-')
            ->addColumn('kelompok_name', fn($row) => $row->kelompok->nama ?? '-')
            ->addColumn('golongan_name', fn($row) => $row->golongan->nama ?? '-')
            ->addColumn('satuan_name', fn($row) => $row->satuan->nama ?? '-')
            ->addColumn('status', fn($row) => $row->aktif ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-danger">Non Aktif</span>')
            ->addColumn('action', function ($row) {
                $editUrl = route('warehouse.master-data.barang-non-farmasi.edit', $row->id);
                $deleteUrl = route('warehouse.master-data.barang-non-farmasi.destroy', $row->id);
                $actionBtn = '<div class="d-flex justify-content-center">';
                $actionBtn .= '<a href="javascript:void(0);" onclick="openPopup(\'' . $editUrl . '\')" class="btn btn-warning btn-sm mr-1"><i class="fal fa-pencil"></i> Edit</a>';
                $actionBtn .= '<a href="javascript:void(0)" class="btn btn-danger btn-sm delete-btn" data-url="' . $deleteUrl . '"><i class="fal fa-trash"></i> Hapus</a>';
                $actionBtn .= '</div>';
                return $actionBtn;
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function export()
    {
        return Excel::download(new BarangNonFarmasiExport, 'barang_non_farmasi.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file_import' => 'required|mimes:xls,xlsx'
        ]);

        try {
            Excel::import(new BarangNonFarmasiImport, $request->file('file_import'));
        } catch (ValidationException $e) {
            // Maatwebsite Excel ValidationException does not always have failures() method
            $failures = method_exists($e, 'failures') ? $e->failures() : [];
            return back()->with('import_errors', $failures);
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage());
        }

        return back()->with('success', 'Data Barang Non Farmasi berhasil diimpor!');
    }

    private function validateRequest(Request $request, $id = null): array
    {
        $kodeRule = 'required|string|max:255|unique:warehouse_barang_non_farmasi,kode';
        if ($id) {
            $kodeRule .= ',' . $id;
        }

        return $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => $kodeRule,
            'keterangan' => 'nullable|string',
            'hna' => 'required|numeric',
            'ppn' => 'required|numeric',
            'aktif' => 'required|boolean',
            'jual_pasien' => 'required|boolean',
            'kategori_id' => 'required|exists:warehouse_kategori_barang,id',
            'golongan_id' => 'nullable|exists:warehouse_golongan_barang,id',
            'kelompok_id' => 'nullable|exists:warehouse_kelompok_barang,id',
            'satuan_id' => 'required|exists:warehouse_satuan_barang,id',
            "satuans_id" => "nullable|array",
            "satuans_id.*" => "exists:warehouse_satuan_barang,id",
            "satuans_jumlah" => "nullable|array",
            "satuans_jumlah.*" => "integer",
            "satuans_status" => "nullable|array",
            "satuans_status.*" => "boolean"
        ]);
    }

    public function create()
    {
        return view("pages.simrs.warehouse.master-data.partials.popup-add-barang-non-farmasi", [
            "kategoris" => WarehouseKategoriBarang::all(),
            "kelompoks" => WarehouseKelompokBarang::all(),
            "golongans" => WarehouseGolonganBarang::all(),
            "satuans" => WarehouseSatuanBarang::all()
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $this->validateRequest($request);

        DB::beginTransaction();
        try {
            $barang = WarehouseBarangNonFarmasi::create($validatedData);

            if (isset($validatedData["satuans_id"])) {
                foreach ($validatedData['satuans_id'] as $index => $satuanId) {
                    WarehouseSatuanTambahanBarangNonFarmasi::create([
                        "barang_id" => $barang->id,
                        "satuan_id" => $satuanId,
                        "isi" => $validatedData['satuans_jumlah'][$index] ?? 0,
                        "aktif" => isset($validatedData['satuans_status'][$index]) ? $validatedData['satuans_status'][$index] : false,
                    ]);
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Barang Non Farmasi berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menambahkan Barang Non Farmasi: ' . $e->getMessage());
        }
    }

    public function show(WarehouseBarangNonFarmasi $warehouseBarangNonFarmasi)
    {
        //
    }

    public function edit($id)
    {
        $barang = WarehouseBarangNonFarmasi::findOrFail($id);

        return view("pages.simrs.warehouse.master-data.partials.popup-edit-barang-non-farmasi", [
            "barang" => $barang,
            "kategoris" => WarehouseKategoriBarang::all(),
            "kelompoks" => WarehouseKelompokBarang::all(),
            "golongans" => WarehouseGolonganBarang::all(),
            "satuans" => WarehouseSatuanBarang::all()
        ]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $this->validateRequest($request, $id);

        $validatedData3 = $request->validate([
            "alasan_edit" => 'required|string',
            "user_id" => 'required|exists:users,id'
        ]);

        DB::beginTransaction();
        try {
            $barang = WarehouseBarangNonFarmasi::findOrFail($id);
            $barang->update($validatedData);

            WarehouseSatuanTambahanBarangNonFarmasi::where('barang_id', $barang->id)->forceDelete();

            if (isset($validatedData['satuans_id'])) {
                foreach ($validatedData['satuans_id'] as $index => $satuanId) {
                    WarehouseSatuanTambahanBarangNonFarmasi::create([
                        "barang_id" => $barang->id,
                        "satuan_id" => $satuanId,
                        "isi" => $validatedData['satuans_jumlah'][$index] ?? 0,
                        "aktif" => isset($validatedData['satuans_status'][$index]) ? $validatedData['satuans_status'][$index] : false,
                    ]);
                }
            }

            WarehouseMasterBarangEditLog::create([
                "goods_id" => $barang->id,
                "goods_type" => WarehouseBarangNonFarmasi::class,
                "nama_barang" => $validatedData["nama"],
                "kode_barang" => $validatedData["kode"],
                "keterangan" => $validatedData3["alasan_edit"],
                "hna" => $validatedData["hna"],
                "status_aktif" => $validatedData["aktif"],
                "golongan_id" => $validatedData["golongan_id"] ?? null,
                "kelompok_id" => $validatedData["kelompok_id"] ?? null,
                "satuan_id" => $validatedData["satuan_id"],
                "performed_by" => $validatedData3["user_id"]
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Barang Non Farmasi berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal mengupdate Barang Non Farmasi: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            WarehouseBarangNonFarmasi::destroy($id);
            return response()->json([
                'success' => true,
                'message' => 'Barang Non Farmasi berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
