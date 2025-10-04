<?php

namespace App\Http\Controllers;

use App\Exports\BarangFarmasiExport;
use App\Imports\BarangFarmasiImport;
use App\Models\WarehouseBarangFarmasi;
use App\Models\WarehouseGolonganBarang;
use App\Models\WarehouseKategoriBarang;
use App\Models\WarehouseKelompokBarang;
use App\Models\WarehouseMasterBarangEditLog;
use App\Models\WarehousePabrik;
use App\Models\WarehouseSatuanBarang;
use App\Models\WarehouseSatuanTambahanBarangFarmasi;
use App\Models\WarehouseZatAktif;
use App\Models\WarehouseZatAktifBarangFarmasi;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class WarehouseBarangFarmasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = WarehouseBarangFarmasi::query();
        $filters = ['nama', 'kode', 'keterangan', 'hna', 'ppn', 'aktif', 'jual_pasien', 'kategori_id', 'kelompok_id', 'satuan_id'];
        $filterApplied = false;

        foreach ($filters as $filter) {
            if ($request->filled($filter)) {
                if (in_array($filter, ['hna', 'ppn', 'kategori_id', 'kelompok_id', 'satuan_id'])) {
                    $query->where($filter, $request->$filter);
                } else {
                    $query->where($filter, 'like', '%' . $request->$filter . '%');
                }
                $filterApplied = true;
            }
        }

        // Get the filtered results if any filter is applied
        if ($filterApplied) {
            $barangs = $query->orderBy('created_at', 'desc')->get();
        } else {
            // Return all data if no filter is applied
            $barangs = WarehouseBarangFarmasi::all();
        }

        return view("pages.simrs.warehouse.master-data.barang-farmasi", [
            "barangs" => $barangs,
            "kategoris" => WarehouseKategoriBarang::all(),
            "kelompoks" => WarehouseKelompokBarang::all(),
            "golongans" => WarehouseGolonganBarang::all(),
            "satuans" => WarehouseSatuanBarang::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("pages.simrs.warehouse.master-data.partials.popup-add-barang-farmasi", [
            "kategoris" => WarehouseKategoriBarang::all(),
            "kelompoks" => WarehouseKelompokBarang::all(),
            "golongans" => WarehouseGolonganBarang::all(),
            "satuans" => WarehouseSatuanBarang::all(),
            "zats" => WarehouseZatAktif::all(),
            "pabriks" => WarehousePabrik::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'restriksi' => 'nullable|string',
            'hna' => 'required|integer',
            'ppn' => 'required|integer',
            'ppn_rajal' => 'required|integer',
            'ppn_ranap' => 'required|integer',
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
            'harga_principal' => 'nullable|integer',
            'diskon_principal' => 'nullable|integer',
            "satuans_id" => "nullable|array",
            "satuans_id.*" => "exists:warehouse_satuan_barang,id",
            "satuans_jumlah" => "nullable|array",
            "satuans_jumlah.*" => "integer",
            "satuans_status" => "nullable|array",
            "satuans_status.*" => "boolean"
        ]);

        $barang = WarehouseBarangFarmasi::create($validatedData);

        if (isset($validatedData["satuans_id"])) {
            foreach ($validatedData['satuans_id'] as $index => $satuanId) {
                WarehouseSatuanTambahanBarangFarmasi::create([
                    "barang_id" => $barang->id,
                    "satuan_id" => $satuanId,
                    "isi" => $validatedData['satuans_jumlah'][$index],
                    "aktif" => isset($validatedData['satuans_status'][$index]) ? $validatedData['satuans_status'][$index] : false,
                ]);
            }
        }

        // handle zat_aktif here
        if ($request->has('zat_aktif')) {
            $zatAktifIds = $request->input('zat_aktif');
            foreach ($zatAktifIds as $zatAktifId) {
                WarehouseZatAktifBarangFarmasi::create([
                    "barang_id" => $barang->id,
                    "zat_id" => $zatAktifId
                ]);
            }
        }

        return redirect()->back()->with('success', 'Barang Farmasi berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(WarehouseBarangFarmasi $warehouseBarangFarmasi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WarehouseBarangFarmasi $warehouseBarangFarmasi, $id)
    {
        return view("pages.simrs.warehouse.master-data.partials.popup-edit-barang-farmasi", [
            "barang" => $warehouseBarangFarmasi->where("id", $id)->first(),
            "kategoris" => WarehouseKategoriBarang::all(),
            "kelompoks" => WarehouseKelompokBarang::all(),
            "golongans" => WarehouseGolonganBarang::all(),
            "satuans" => WarehouseSatuanBarang::all(),
            "zats" => WarehouseZatAktif::all(),
            "pabriks" => WarehousePabrik::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WarehouseBarangFarmasi $warehouseBarangFarmasi)
    {
        $validatedData = $request->validate([
            'id' => 'required|integer',
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'restriksi' => 'nullable|string',
            'hna' => 'required|integer',
            'ppn' => 'required|integer',
            'ppn_rajal' => 'required|integer',
            'ppn_ranap' => 'required|integer',
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
            'harga_principal' => 'nullable|integer',
            'diskon_principal' => 'nullable|integer'
        ]);

        $validatedData2 = $request->validate([
            'id' => 'required|integer',
            "satuans_id" => "nullable|array",
            "satuans_id.*" => "exists:warehouse_satuan_barang,id",
            "satuans_jumlah" => "nullable|array",
            "satuans_jumlah.*" => "integer",
            "satuans_status" => "nullable|array",
            "satuans_status.*" => "boolean"
        ]);

        $validatedData3 = $request->validate([
            "alasan_edit" => 'required|string',
            "user_id" => 'required|exists:users,id'
        ]);

        $warehouseBarangFarmasi
            ->where("id", $validatedData['id'])
            ->update($validatedData);

        // delete all data from WarehouseSatuanTambahanBarangFarmasi
        // where "barang_id" == $validatedData['id']
        WarehouseSatuanTambahanBarangFarmasi::where('barang_id', $validatedData2['id'])->forceDelete();

        // delete all data from WarehouseZatAktifBarangFarmasi
        // where "barang_id" == $validatedData['id']
        WarehouseZatAktifBarangFarmasi::where('barang_id', $validatedData2['id'])->forceDelete();

        if ($request->has('satuans_id')) {
            foreach ($validatedData2['satuans_id'] as $index => $satuanId) {
                WarehouseSatuanTambahanBarangFarmasi::create([
                    "barang_id" => $validatedData2['id'],
                    "satuan_id" => $satuanId,
                    "isi" => $validatedData2['satuans_jumlah'][$index],
                    "aktif" => isset($validatedData2['satuans_status'][$index]) ? $validatedData2['satuans_status'][$index] : false,
                ]);
            }
        }

        if ($request->has('zat_aktif')) {
            $zatAktifIds = $request->input('zat_aktif');
            foreach ($zatAktifIds as $zatAktifId) {
                WarehouseZatAktifBarangFarmasi::create([
                    "barang_id" => $validatedData2['id'],
                    "zat_id" => $zatAktifId
                ]);
            }
        }

        // add log
        WarehouseMasterBarangEditLog::create([
            "goods_id" => $validatedData['id'],
            "goods_type" => WarehouseBarangFarmasi::class,
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

        return redirect()->back()->with('success', 'Barang Farmasi berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WarehouseBarangFarmasi $warehouseBarangFarmasi, $id)
    {
        try {
            $warehouseBarangFarmasi::destroy($id);
            return response()->json([
                'success' => true,
                'message' => 'Barang Farmasi berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
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
}
