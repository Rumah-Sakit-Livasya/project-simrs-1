<?php

namespace App\Http\Controllers\Inventaris;

use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Models\Inventaris\Barang;
use App\Http\Controllers\Controller;
use App\Models\Inventaris\ReportBarang;
use App\Models\Inventaris\CategoryBarang;
use App\Models\Inventaris\TemplateBarang;
use App\Models\Inventaris\RoomMaintenance;
use Illuminate\Support\Facades\Auth;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $customName = $request->input('custom_name');
        $templateBarang = $request->input('template_barang_id');
        $kategoriBarang = $request->input('category_barang_id');
        $identitasBarang = $request->input('identitas_barang');
        $ruanganId = $request->input('ruangan_id');
        $companyId = $request->input('company_id');
        $allRoom = RoomMaintenance::orderBy('name', 'asc')->get();
        $barang = [];

        // Get the organization of the authenticated user
        $rooms = Auth::user()->employee->organization->room_maintenance;
        $organizationId = Auth::user()->employee->organization_id;
        $companies = Auth::user()->employee->company;

        // Query for searching
        $query = Barang::query();

        // Apply filters based on input
        if ($customName) {
            $query->where('custom_name', 'like', '%' . $customName . '%');
        }
        if ($identitasBarang) {
            $query->where('item_code', 'like', '%' . $identitasBarang . '%');
        }
        if ($templateBarang) {
            $query->where('template_barang_id', $templateBarang);
        }
        if ($kategoriBarang) {
            $query->where('category_barang_id', $kategoriBarang);
        }
        if ($ruanganId) {
            $query->where('room_id', $ruanganId);
        }
        if ($companyId) {
            $query->where('company_id', $companyId);
        }

        // Check if the user has admin permissions
        if (Auth::user()->can('admin inventaris barang')) {
            // If the user is an admin, retrieve all items with filters applied
            $barang = $query->orderBy('id', 'desc')->get();
            $rooms = RoomMaintenance::orderBy('name', 'asc')->get();
            $companies = Company::all();
        } else {
            // Filter by organization through the room maintenance relationship
            $query->whereHas('room.organizations', function ($q) use ($organizationId) {
                $q->where('organization_id', $organizationId);
            });
            // If no admin access, get items related to the user's organization
            $barang = $query->orderBy('id', 'desc')->limit(100)->get();
        }

        // Prepare alert message only if there are filters applied
        $jumlahBarang = $barang->count();
        $filterMessage = "Jumlah barang yang terget: $jumlahBarang. Filter yang digunakan: ";
        $filterParts = [];

        if ($customName) {
            $filterParts[] = "Nama Barang: $customName";
        }
        if ($templateBarang) {
            $filterParts[] = "Template: " . TemplateBarang::find($templateBarang)->name;
        }
        if ($kategoriBarang) {
            $filterParts[] = "Kategori: " . CategoryBarang::find($kategoriBarang)->name;
        }
        if ($identitasBarang) {
            $filterParts[] = "Identitas Barang: $identitasBarang";
        }
        if ($ruanganId) {
            $filterParts[] = "Ruangan: " . RoomMaintenance::find($ruanganId)->name;
        }
        if ($companyId) {
            $filterParts[] = "Company: " . Company::find($companyId)->name;
        }

        if (!empty($filterParts)) {
            $filterMessage .= implode(", ", $filterParts) . ".";
        } else {
            $filterMessage = null; // Do not display the message if no filters are applied
        }

        return view('pages.inventaris.barang.index', [
            'barang' => $barang,
            'companies' => $companies,
            'templates' => TemplateBarang::orderBy('name', 'asc')->get(),
            'categories' => CategoryBarang::orderBy('name', 'asc')->get(),
            'rooms' => $rooms,
            'allRoom' => $allRoom,
            'jumlah' => $jumlahBarang,
            'ruangan' => $rooms,
            'filterMessage' => $filterMessage // Send the alert message to the view
        ]);
    }

    public function store(Request $request)
    {
        try {
            $user = User::findOrFail($request->user_id);
            $validatedData = $request->validate([
                'custom_name' => "max:255",
                'template_barang_id' => "required|max:255",
                'condition' => "required|max:5120",
                'bidding_year' => "required|max:255",
                'merk' => "max:5120",
                'harga_barang' => "max:5120",
                'room_id' => "max:255",
            ]);

            $template = $validatedData['template_barang_id'];
            $category = TemplateBarang::where('id', $template)->get('category_id')->first()->category_id;
            $validatedData['category_barang_id'] = $category;

            $barang_code = RoomMaintenance::where('id', $request['room_id'])->first()->room_code;

            $barang_id = $request->template_barang_id;
            $template = TemplateBarang::where('id', $barang_id)->first();

            // Report Hasil
            $templateBarang = TemplateBarang::where('id', $request->template_barang_id)->first();

            if ($request->custom_name === null) {
                $nameBarang = $templateBarang->name;
            } else {
                $nameBarang = $request->custom_name;
            }

            if ($request->room_id === null) {
                $room = RoomMaintenance::findOrFail($request->room_id);
                $namaRuang = $room->name;
            } else {
                $namaRuang = "Sistem";
            }

            $reportData = [
                'barang_id' => $request->barang_id,
                'room_id' => $request->room_id
            ];
            $reportData['user_id'] = $user->id;
            $reportData['keterangan'] = strtoupper("$nameBarang telah ditambahkan ke $namaRuang oleh $user->name");

            $lastUrutanBarang = Barang::where('template_barang_id', $template->id)
                ->where('urutan_barang', '<>', '0')
                ->orderByDesc('urutan_barang')
                ->first();
            // Start from 0 if $lastUrutanBarang is null, otherwise from the last number
            $startUrutan = $lastUrutanBarang ? intval($lastUrutanBarang->urutan_barang) : 0;

            $validatedData['company_id'] = $request->company_id;
            $companyCode = Company::findOrFail($request->company_id)->code;
            if ($request->jumlah) {
                for ($i = 1; $i <= $request->jumlah; $i++) {
                    $urutanBarang = $startUrutan + $i;

                    // Format the urutan_barang to have leading zeros
                    $validatedData['urutan_barang'] = str_pad($urutanBarang, 3, '0', STR_PAD_LEFT);

                    // Generate the item code
                    $item_code = $companyCode . "/" . $template->category->category_code . "/" . $barang_code . "/" . $template->barang_code . "." . $template->name . " " . $validatedData['urutan_barang'] . "/" . $request->bidding_year;
                    $validatedData['item_code'] = $item_code;

                    // Create the Barang and ReportBarang records
                    Barang::create($validatedData);
                    ReportBarang::create($reportData);
                }
            } else {
                $urutanBarang = $startUrutan + 1;

                // Format the urutan_barang to have leading zeros
                $validatedData['urutan_barang'] = str_pad($urutanBarang, 3, '0', STR_PAD_LEFT);

                // Generate the item code
                $item_code = $companyCode . "/" . $template->category->category_code . "/" . $barang_code . "/" . $template->barang_code . "." . $template->name . " " . $validatedData['urutan_barang'] . "/" . $request->bidding_year;
                $validatedData['item_code'] = $item_code;
                // return dd($validatedData);
                Barang::create($validatedData);
                ReportBarang::create($reportData);
            }

            return response()->json(['message' => "Berhasil ditambahkan!"], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function move(Request $request)
    {
        $barang = Barang::where('id', $request->barang_id)->first();
        $company = Company::where('id', $barang->company->id)->first();
        if ($barang->item_code == 0) {
            $room_code = RoomMaintenance::where('id', $request->room_id)->first()->room_code;

            $template_barang_id = $barang->template_barang_id;
            $template = TemplateBarang::where('id', $template_barang_id)->first();

            $lastUrutanBarang = Barang::where('template_barang_id', $template->id)
                ->where('urutan_barang', '<>', '0')
                ->orderByDesc('urutan_barang')
                ->first();

            if ($lastUrutanBarang) {
                $urutanBarang = intval($lastUrutanBarang->urutan_barang) + 1;
            } else {
                $urutanBarang = 1;
            }

            $barang->urutan_barang = str_pad($urutanBarang, 3, '0', STR_PAD_LEFT);
            $user = User::findOrFail($request->user_id);

            $item_code = $company->code . "/" . $template->category->category_code . "/" . $room_code . "/" . $template->barang_code . "." . $template->name . " " . $barang->urutan_barang . "/" . $barang->bidding_year;
            $barang->item_code = $item_code;
        } else {
            $itemCode = $request->item_code;
            $newRoomCode = RoomMaintenance::where('id', $request->room_id)->first()->room_code;

            $itemCodeParts = explode('/', $itemCode);
            if (isset($itemCodeParts[2])) {
                $itemCodeParts[2] = strtoupper($newRoomCode);
            }

            // Reassemble the item code
            $updatedItemCode = implode('/', $itemCodeParts);

            // Update the Barang record
            $user = User::findOrFail($request->user_id);
            $barang->item_code = $updatedItemCode;
        }

        // Assign the Room_id
        $barang->room_id = $request->room_id;

        // Create a new ReportBarang record
        $reportBarang = new ReportBarang();
        $reportBarang->user_id = $user->id;
        $reportBarang->barang_id = $request->barang_id;
        $reportBarang->room_id = $request->room_id;
        $reportBarang->keterangan = strtoupper("Barang telah dipindahkan oleh " . $user->employee->fullname . " ke " . RoomMaintenance::where('id', $request->room_id)->first()->name);

        try {
            $barang->save();
            $reportBarang->save();

            return response()->json(['message' => 'Berhasil dilakukan pindah rumah barang!'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan pada server',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function pinjam(Request $request)
    {
        // Update the Barang record
        $barang = Barang::where('id', $request->barang_id)->first();
        $user = User::findOrFail($request->user_id);
        $ruangPinjam = RoomMaintenance::findOrFail($request->ruang_pinjam)->first();
        $room = RoomMaintenance::findOrFail($barang->room_id)->first();

        $barang->ruang_pinjam = $request->ruang_pinjam;
        $barang->pinjam = true;

        if ($barang->custom_name === null) {
            $nameBarang = $barang->template_barang->name;
        } else {
            $nameBarang = $barang->custom_name;
        }

        // Create a new ReportBarang record
        $reportBarang = new ReportBarang();
        $reportBarang->user_id = $user->id;
        $reportBarang->barang_id = $request->barang_id;
        $reportBarang->room_id = $request->room_id;
        $reportBarang['keterangan'] = strtoupper("$nameBarang telah dipinjamkan dari ruang $room->name ke ruang $ruangPinjam->name oleh $user->name");

        try {
            $barang->save();
            $reportBarang->save();

            return response()->json(['message' => 'Berhasil dilakukan pinjam barang!'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan pada server',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function back(Request $request)
    {
        // Update the Barang record
        $barang = Barang::where('id', $request->barang_id)->first();
        $user = User::findOrFail($request->user_id);
        $ruangPinjam = RoomMaintenance::findOrFail($request->ruang_pinjam)->first();
        $room = RoomMaintenance::findOrFail($barang->room_id)->first();

        $barang->ruang_pinjam = false;
        $barang->pinjam = false;

        if ($barang->custom_name === null) {
            $nameBarang = $barang->template_barang->name;
        } else {
            $nameBarang = $barang->custom_name;
        }

        // Create a new ReportBarang record
        $reportBarang = new ReportBarang();
        $reportBarang->user_id = $user->id;
        $reportBarang->barang_id = $request->barang_id;
        $reportBarang->room_id = $request->room_id;
        $reportBarang['keterangan'] = strtoupper("$nameBarang telah dikembalikan dari ruang $ruangPinjam->name ke ruang $room->name oleh $user->name");

        try {
            $barang->save();
            $reportBarang->save();

            return response()->json(['message' => 'Berhasil dilakukan pengembalian barang!'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan pada server',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getBarang($id)
    {
        try {
            $barang = Barang::findOrFail($id);

            return response()->json([
                'id' => $barang->id,
                'room_id' => $barang->room_id,
                'company_id' => $barang->company_id,
                'custom_name' => $barang->custom_name,
                'item_code' => $barang->item_code,
                'condition' => $barang->condition,
                'template_barang_id' => $barang->template_barang_id,
                'merk' => $barang->merk,
                'harga_barang' => $barang->harga_barang,
                'bidding_year' => $barang->bidding_year,
                'urutan_barang' => $barang->urutan_barang,
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Data tidak ditemukan',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan pada server',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'custom_name' => "max:255",
            'item_code' => "required|max:255",
            'condition' => "required|max:255",
            // 'template_barang_id' => "required|max:255",
            'merk' => "max:255",
            'harga_barang' => "max:255",
            'bidding_year' => "required|max:255",
            'urutan_barang' => "required|max:255",
        ]);

        try {
            $barang = Barang::findOrFail($request->barang_id);
            $barang->update($validatedData);
            return response()->json(['message' => "$barang->name Berhasil diupdate!"], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $barang = Barang::find($id);
            $urutanDihapus = $barang->urutan_barang;
            $barang->delete();

            // Cari item dengan urutan_barang tertinggi
            $barangTerbaru = Barang::where('template_barang_id', $barang->template_barang_id)->orderBy('urutan_barang', 'desc')->first();

            if ($barangTerbaru && intval($barangTerbaru->urutan_barang) > intval($urutanDihapus)) {
                // Perbarui item terbaru untuk mengisi celah
                $barangTerbaru->urutan_barang = $urutanDihapus;

                // Perbarui kode item untuk mencerminkan urutan_barang yang baru
                $itemCodeParts = explode('/', $barangTerbaru->item_code);
                if (isset($itemCodeParts[3])) { // Asumsikan urutan_barang adalah bagian ke-5 (index 3)
                    $itemCodeParts[3] = str_pad($urutanDihapus, 3, '0', STR_PAD_LEFT);
                }

                // Gabungkan kembali kode item
                $kodeItemTerbaru = implode('/', $itemCodeParts);
                $barangTerbaru->item_code = $kodeItemTerbaru;

                // Simpan perubahan pada barangTerbaru
                $barangTerbaru->save();
            }

            return response()->json(['message' => "$barang->name berhasil dihapus"], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
}
