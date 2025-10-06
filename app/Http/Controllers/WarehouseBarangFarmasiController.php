<?php

namespace App\Http\Controllers;

use App\Exports\BarangFarmasiExport;
use App\Imports\BarangFarmasiImport;
use App\Models\WarehouseBarangFarmasi;
use App\Models\WarehouseGolonganBarang;
use App\Models\WarehouseKategoriBarang;
use App\Models\WarehouseKelompokBarang;
use App\Models\WarehousePabrik;
use App\Models\WarehouseSatuanBarang;
use App\Models\WarehouseZatAktif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\Facades\DataTables;

class WarehouseBarangFarmasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("pages.simrs.warehouse.master-data.barang-farmasi");
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function data(Request $request)
    {
        $query = WarehouseBarangFarmasi::with(['satuan', 'kategori', 'golongan', 'kelompok'])
            ->select('warehouse_barang_farmasi.*');

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
        if ($request->filled('satuan')) {
            $query->whereHas('satuan', function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->satuan . '%');
            });
        }
        if ($request->filled('aktif')) {
            if ($request->aktif === 'Aktif') {
                $query->where('aktif', 1);
            } elseif ($request->aktif === 'Tidak Aktif') {
                $query->where('aktif', 0);
            }
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('kategori_name', fn($row) => $row->kategori->nama ?? '-')
            ->addColumn('kelompok_name', fn($row) => $row->kelompok->nama ?? '-')
            ->addColumn('golongan_name', fn($row) => $row->golongan->nama ?? '-')
            ->addColumn('satuan_name', fn($row) => $row->satuan->nama ?? '-')
            ->addColumn('status', fn($row) => $row->aktif ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-danger">Non Aktif</span>')
            ->addColumn('action', function ($row) {
                $editUrl = route('warehouse.master-data.barang-farmasi.edit', $row->id);
                $deleteUrl = route('warehouse.master-data.barang-farmasi.destroy', $row->id);
                $actionBtn = '<div class="d-flex justify-content-center">';
                $actionBtn .= '<a href="javascript:void(0);" onclick="openPopup(\'' . $editUrl . '\')" class="btn btn-warning btn-sm mr-1"><i class="fal fa-pencil"></i> Edit</a>';
                $actionBtn .= '<a href="javascript:void(0)" class="btn btn-danger btn-sm delete-btn" data-url="' . $deleteUrl . '"><i class="fal fa-trash"></i> Hapus</a>';
                $actionBtn .= '</div>';
                return $actionBtn;
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategoris = WarehouseKategoriBarang::where('aktif', 1)->get();
        $kelompoks = WarehouseKelompokBarang::where('aktif', 1)->get();
        $golongans = WarehouseGolonganBarang::where('aktif', 1)->get();
        $satuans = WarehouseSatuanBarang::where('aktif', 1)->get();
        $zats = WarehouseZatAktif::where('aktif', 1)->get();
        $pabriks = WarehousePabrik::where('aktif', 1)->get();
        return view("pages.simrs.warehouse.master-data.partials.popup-add-barang-non-farmasi", compact('kategoris', 'kelompoks', 'golongans', 'satuans', 'zats', 'pabriks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $this->validateRequest($request);

        DB::beginTransaction();
        try {
            $barang = WarehouseBarangFarmasi::create($validatedData);

            // Attach Zat Aktif
            if ($request->has('zat_aktif')) {
                $barang->zat_aktif()->attach($request->zat_aktif);
            }

            // Create Satuan Tambahan
            if ($request->has('satuans_id')) {
                foreach ($request->satuans_id as $index => $satuanId) {
                    if ($satuanId && isset($request->satuans_jumlah[$index])) {
                        $barang->satuan_tambahan()->create([
                            "satuan_id" => $satuanId,
                            "isi" => $request->satuans_jumlah[$index],
                            "aktif" => isset($request->satuans_status[$index]),
                        ]);
                    }
                }
            }
            DB::commit();
            return redirect()->route('warehouse.master-data.barang-farmasi.index')->with('success', 'Barang Farmasi berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan data: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $barang = WarehouseBarangFarmasi::with(['satuan_tambahan', 'zat_aktif'])->findOrFail($id);
        $kategoris = WarehouseKategoriBarang::where('aktif', 1)->get();
        $kelompoks = WarehouseKelompokBarang::where('aktif', 1)->get();
        $golongans = WarehouseGolonganBarang::where('aktif', 1)->get();
        $satuans = WarehouseSatuanBarang::where('aktif', 1)->get();
        $zats = WarehouseZatAktif::where('aktif', 1)->get();
        $pabriks = WarehousePabrik::where('aktif', 1)->get();
        return view("pages.simrs.warehouse.master-data.partials.popup-edit-barang-farmasi", compact('barang', 'kategoris', 'kelompoks', 'golongans', 'satuans', 'zats', 'pabriks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $this->validateRequest($request, $id);

        DB::beginTransaction();
        try {
            $barang = WarehouseBarangFarmasi::findOrFail($id);
            $barang->update($validatedData);

            // Sync Zat Aktif
            $barang->zat_aktif()->sync($request->zat_aktif ?? []);

            // Sync Satuan Tambahan
            $barang->satuan_tambahan()->delete();
            if ($request->has('satuans_id')) {
                foreach ($request->satuans_id as $index => $satuanId) {
                    if ($satuanId && isset($request->satuans_jumlah[$index])) {
                        $barang->satuan_tambahan()->create([
                            "satuan_id" => $satuanId,
                            "isi" => $request->satuans_jumlah[$index],
                            "aktif" => isset($request->satuans_status[$index]),
                        ]);
                    }
                }
            }

            // Optional: Add log if needed
            // ...

            DB::commit();
            return redirect()->route('warehouse.master-data.barang-farmasi.index')->with('success', 'Barang Farmasi berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            WarehouseBarangFarmasi::destroy($id);
            return response()->json(['success' => true, 'message' => 'Barang Farmasi berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function export()
    {
        return Excel::download(new BarangFarmasiExport, 'barang_farmasi.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file_import' => 'required|mimes:xls,xlsx'
        ]);

        try {
            Excel::import(new BarangFarmasiImport, $request->file('file_import'));
        } catch (ValidationException $e) {
            $failures = $e->failures();
            // Handle validasi error, misalnya kirim kembali ke view dengan pesan error
            return back()->with('import_errors', $failures);
        } catch (\Exception $e) {
            // Handle error umum lainnya
            return back()->with('error', 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage());
        }

        return back()->with('success', 'Data Barang Farmasi berhasil diimpor!');
    }

    private function validateRequest(Request $request, $id = null)
    {
        $kodeRule = 'required|string|max:255|unique:warehouse_barang_farmasi,kode';
        if ($id) {
            $kodeRule .= ',' . $id;
        }

        return $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => $kodeRule,
            'keterangan' => 'nullable|string',
            'restriksi' => 'nullable|string',
            'hna' => 'required|numeric',
            'ppn' => 'required|numeric',
            'ppn_rajal' => 'required|numeric',
            'ppn_ranap' => 'required|numeric',
            'tipe' => 'required|in:FN,NFN',
            'formularium' => 'required|in:RS,NRS',
            'jenis_obat' => 'nullable|in:paten,generik',
            'exp' => 'nullable|in:1w,2w,3w,1mo,2mo,3mo,6mo',
            'aktif' => 'required|boolean',
            'kategori_id' => 'required|exists:warehouse_kategori_barang,id',
            'golongan_id' => 'nullable|exists:warehouse_golongan_barang,id',
            'kelompok_id' => 'nullable|exists:warehouse_kelompok_barang,id',
            'satuan_id' => 'required|exists:warehouse_satuan_barang,id',
            'principal' => 'nullable|exists:warehouse_pabrik,id',
            'harga_principal' => 'nullable|numeric',
            'diskon_principal' => 'nullable|numeric',
            "satuans_id" => "nullable|array",
            "satuans_id.*" => "nullable|exists:warehouse_satuan_barang,id",
            "satuans_jumlah" => "nullable|array",
            "satuans_jumlah.*" => "nullable|numeric",
            'zat_aktif' => 'nullable|array',
            'zat_aktif.*' => 'exists:warehouse_zat_aktif,id',
        ]);
    }
}
