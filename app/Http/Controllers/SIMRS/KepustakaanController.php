<?php

namespace App\Http\Controllers\SIMRS;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\SIMRS\Kepustakaan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class KepustakaanController extends Controller
{
    public function index()
    {
        $kepustakaan = Kepustakaan::whereNull('parent_id')
            ->orderByRaw("CASE WHEN type = 'folder' THEN 1 ELSE 2 END")
            ->orderBy('name', 'asc')
            ->get();

        $childrenFolder = [];

        $organizations = Organization::all();
        $breadcrumbs = collect();
        $view = 'parent';

        return view('pages.simrs.kepustakaan.index', compact('kepustakaan', 'breadcrumbs', 'organizations', 'childrenFolder', 'view'));
    }

    public function showFolder($encryptedId)
    {
        $id = Crypt::decrypt($encryptedId);
        // Cari folder berdasarkan nama
        $folder = Kepustakaan::where('id', $id)
            ->where('type', 'folder') // Memastikan hanya folder
            ->firstOrFail();

        $childrenFolder = $folder->allChildren;

        if ($folder->kategori == 'Perizinan' && !auth()->user()->can('master kepustakaan')) {
            return redirect()->back()->with('error', 'Anda tidak punya akses ini!');
        }

        $breadcrumbs = getBreadcrumbs($folder);
        if (auth()->user()->can('master kepustakaan')) {
            $kepustakaan = Kepustakaan::where('parent_id', $folder->id)
                ->orderByRaw("CASE WHEN type = 'folder' THEN 1 ELSE 2 END")
                ->orderBy('name', 'asc')
                ->get();

            $organizationFolder = Organization::pluck('id')
                ->toArray();
            $organizations = Organization::all();
        } else {
            $organizations = Organization::where('id', auth()->user()->employee->organization_id)->get();
            // Ambil semua organisasi anak secara rekursif
            function getChildOrganizationIds($organization)
            {
                $ids = [];

                foreach ($organization->child_structures as $childStructure) {
                    $childOrg = $childStructure->organization;
                    if ($childOrg) {
                        $ids[] = $childOrg->id;
                        $ids = array_merge($ids, getChildOrganizationIds($childOrg)); // Rekursif
                    }
                }

                return $ids;
            }

            // Inisialisasi array organisasi
            $organizationFolder = [];

            // Organisasi utama dari user
            $currentOrganization = auth()->user()->employee->organization;
            $organizationFolder[] = $currentOrganization->id;

            // Tambahkan semua anak organisasi (rekursif)
            $organizationFolder = array_merge($organizationFolder, getChildOrganizationIds($currentOrganization));
            // Query kepustakaan
            $kepustakaan = Kepustakaan::where('parent_id', $folder->id)
                ->where(function ($query) use ($organizationFolder) {
                    $query->whereNull('organization_id')
                        ->orWhereIn('organization_id', $organizationFolder)
                        ->orWhereIn('organization_id', [25, 26, 27]);
                })
                ->orderByRaw("CASE WHEN type = 'folder' THEN 1 ELSE 2 END")
                ->orderBy('name', 'asc')
                ->get();
        }


        $view = 'child';
        return view('pages.simrs.kepustakaan.index', compact('kepustakaan', 'breadcrumbs', 'organizations', 'organizationFolder', 'folder', 'childrenFolder', 'view'));
    }

    public function getKepustakaan($encryptedId)
    {
        $id = Crypt::decrypt($encryptedId);
        $folder = Kepustakaan::where('id', $id)
            ->firstOrFail();
        return response()->json($folder, 200);
    }

    public function downloadFile($encryptedId)
    {
        // Pastikan user sudah login
        if (!auth()->check()) {
            abort(403, 'Unauthorized');
        }

        $id = Crypt::decrypt($encryptedId);
        $file = Kepustakaan::where('id', $id)->firstOrFail();

        $path = $file->file;

        // dd($path);
        if (!Storage::disk('private')->exists($path)) {
            abort(404, 'File not found');
        }

        return Storage::disk('private')->download($path);
    }

    // public function store(Request $request)
    // {
    //     // Atur aturan validasi dasar
    //     $rules = [
    //         'type' => 'required',
    //         'kategori' => 'nullable',
    //         'parent_id' => 'nullable',
    //         'organization_id' => 'nullable',
    //         'name' => 'required',
    //         'size' => 'nullable',
    //         'file' => 'required', // default: file wajib
    //     ];

    //     // Jika tipenya folder, maka file boleh kosong
    //     if ($request->input('type') === 'folder') {
    //         $rules['file'] = 'nullable';
    //     }

    //     // Pesan kustom untuk masing-masing input
    //     $messages = [
    //         'type.required' => 'Tipe harus dipilih.',
    //         'name.required' => 'Nama folder atau file harus diisi.',
    //         'file.required' => 'File harus diunggah jika bukan folder.',
    //     ];

    //     $validatedData = $request->validate($rules, $messages);

    //     // Ambil data organisasi jika ada
    //     $organization = Organization::find($request->organization_id);

    //     if ($request->hasFile('file')) {
    //         $file = $request->file('file');
    //         $fileName = Carbon::now()->timestamp . '_' . \Str::slug($request->name) . '.' . $file->getClientOriginalExtension();
    //         $kategori = \Str::slug($request->kategori ?? 'lainnya');
    //         $orgName = $organization ? \Str::slug($organization->name) : 'umum';
    //         $path = 'kepustakaan/' . $kategori . '/' . $orgName;

    //         // Simpan file secara manual ke storage private
    //         $storagePath = storage_path('app/private/' . $path);
    //         if (!file_exists($storagePath)) {
    //             mkdir($storagePath, 0755, true);
    //         }

    //         $file->move($storagePath, $fileName);

    //         // Simpan path relatif ke database
    //         $validatedData['file'] = $path . '/' . $fileName;
    //     }

    //     try {
    //         Kepustakaan::create($validatedData);
    //         return response()->json(['message' => 'Folder/File berhasil ditambahkan!'], 200);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }

    public function store(Request $request)
    {
        $organizationId = $request->organization_id ?? (auth()->user()?->employee?->organization_id ?? null);
        // Atur aturan validasi dasar
        $rules = [
            'type' => 'required',
            'kategori' => 'nullable',
            'parent_id' => 'nullable',
            'organization_id' => 'nullable',
            'name' => 'required',
            'size' => 'nullable',
            'file' => 'required', // default: file wajib
            'month' => 'nullable|integer', // <-- Tambahkan ini
            'year' => 'nullable|integer',  // <-- Tambahkan ini
        ];

        // Jika tipenya folder, maka file boleh kosong
        if ($request->input('type') === 'folder') {
            $rules['file'] = 'nullable';
        }

        // UPDATE: Jika kategori adalah 'Laporan', bulan dan tahun wajib diisi
        if ($request->input('kategori') === 'Laporan') {
            $rules['month'] = 'required|integer';
            $rules['year'] = 'required|integer';
        }

        // Pesan kustom untuk masing-masing input
        $messages = [
            'type.required' => 'Tipe harus dipilih.',
            'name.required' => 'Nama folder atau file harus diisi.',
            'file.required' => 'File harus diunggah jika bukan folder.',
            'month.required' => 'Bulan laporan harus dipilih.', // <-- Tambahkan ini
            'year.required' => 'Tahun laporan harus dipilih.',  // <-- Tambahkan ini
        ];

        $validatedData = $request->validate($rules, $messages);

        $organization = Organization::find($organizationId);
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = Carbon::now()->timestamp . '_' . \Str::slug($request->name) . '.' . $file->getClientOriginalExtension();
            $kategori = \Str::slug($request->kategori ?? 'lainnya');
            $orgName = $organization ? \Str::slug($organization->name) : 'umum';
            $path = 'kepustakaan/' . $kategori . '/' . $orgName;

            // Simpan file secara manual ke storage private
            $storagePath = storage_path('app/private/' . $path);
            if (!file_exists($storagePath)) {
                mkdir($storagePath, 0755, true);
            }

            $file->move($storagePath, $fileName);

            // Simpan path relatif ke database
            $validatedData['file'] = $path . '/' . $fileName;
        }

        try {
            // Data yang divalidasi sekarang sudah termasuk 'month' dan 'year'
            Kepustakaan::create($validatedData);
            return response()->json(['message' => 'Folder/File berhasil ditambahkan!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $encryptedId)
    {
        $validatedData = $request->validate([
            'name' => 'nullable',
        ]);

        try {
            $id = Crypt::decrypt($encryptedId);
            $file = Kepustakaan::where('id', $id)->firstOrFail();

            if ($request->has('parent_id')) {
                $file->parent_id = $request->parent_id;
            } else {
                $file->name = $request->name;
            }

            $file->save();
            // $organization = Organization::where('id', $request->organization_id)->first();

            // $oldPath = "/kepustakaan/" . \Str::slug($file->kategori) . "/" . $file->file;

            // // Pastikan file lama ada
            // if (!Storage::disk('private')->exists($oldPath)) {
            //     abort(404, 'File not found');
            // }

            // // Nama file baru dan path baru
            // $fileName = Carbon::now()->timestamp . '_' . $request->name . '.' . pathinfo($file->file, PATHINFO_EXTENSION);
            // $kategori = \Str::slug($request->kategori) ?? 'lainnya';
            // $newPath = 'kepustakaan/' . $kategori . '/' . \Str::slug($organization->name) . '/' . $fileName;

            // // Pindahkan file ke lokasi baru
            // if (Storage::disk('private')->move($oldPath, $newPath)) {
            //     // Update path di database
            //     $file->file = $newPath;
            //     $file->kategori = $request->kategori;
            //     $file->save();
            //     return response()->json(['message' => ' Folder berhasil diupdate!'], 200);
            // } else {
            //     abort(500, 'Failed to move file');
            // }

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete(Request $request, $encryptedId)
    {

        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        try {
            // Dekripsi ID
            $id = Crypt::decrypt($encryptedId);

            // Cari record di database
            $kepustakaan = Kepustakaan::where('id', $id)->firstOrFail();


            $credentials = $request->only('email', 'password');

            if ($this->zimbraLogin($credentials['email'], $credentials['password'])) {
                $user = User::where('email', $credentials['email'])->where('is_active', 1)->first();

                if ($user == null) {
                    return response()->json(['message' => 'Email atau Password Salah!'], 403);
                } else {
                    // Rekursif hapus folder dan semua child-nya
                    $this->deleteFolderAndChildren($kepustakaan);

                    return response()->json(['message' => 'Folder dan semua child-nya berhasil dihapus.'], 200);
                }
            } else {
                return response()->json(['message' => 'Email atau Password Salah!'], 403);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete file or record: ' . $e->getMessage()], 500);
        }
    }

    private function zimbraLogin($email, $password)
    {
        $data = [
            "Header" => [
                "context" => [
                    "_jsns" => "urn:zimbra",
                    "userAgent" => ["name" => "curl", "version" => "8.8.15"],
                ],
            ],
            "Body" => [
                "AuthRequest" => [
                    "_jsns" => "urn:zimbraAccount",
                    "account" => ["_content" => $email, "by" => "name"],
                    "password" => $password,
                ],
            ],
        ];

        try {
            $encodedData = json_encode($data);

            $url = 'https://webmail.livasya.com/service/soap';
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Content-Type:application/json'
            ));
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $encodedData);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            $result = curl_exec($curl);
            curl_close($curl);

            $mark = 'AUTH_FAILED';

            if (strpos($result, $mark) !== false) {
                return false; // Autentikasi gagal
            } else {
                // Autentikasi berhasil
                // Anda mungkin ingin melakukan sesuatu di sini, seperti memproses respons
                // atau mengembalikan informasi tambahan
                return true;
            }
        } catch (\Exception $e) {
            // Tangani kesalahan saat menjalankan permintaan cURL
            return false;
        }
    }

    private function deleteFolderAndChildren($folder)
    {
        // Ambil semua child berdasarkan parent_id
        $children = Kepustakaan::where('parent_id', $folder->id)->get();

        foreach ($children as $child) {
            if ($child->type == 'file') {
                // Hapus file dari storage
                $filePath = $child->file;
                if (Storage::disk('private')->exists($filePath)) {
                    Storage::disk('private')->delete($filePath);
                }
            } else {
                // Jika child adalah folder, lakukan rekursi untuk menghapus child folder-nya
                $this->deleteFolderAndChildren($child);
            }
            // Hapus child dari database
            $child->delete();
        }

        // Hapus folder dari database
        $folder->delete();
    }

    /**
     * Menampilkan dasbor checklist laporan bulanan per unit.
     */
    // public function laporanDashboard(Request $request)
    // {
    //     // 1. Tentukan periode (bulan dan tahun) yang akan dicek.
    //     // Ambil dari request, atau gunakan bulan dan tahun saat ini sebagai default.
    //     // FIX: Lakukan type casting ke integer untuk menghindari error di Carbon.
    //     $selectedYear = (int) $request->input('year', Carbon::now()->year);
    //     $selectedMonth = (int) $request->input('month', Carbon::now()->month);

    //     // 2. Ambil semua organisasi/unit yang relevan.
    //     // $organizations = Organization::where('is_active', 1)->orderBy('name', 'asc')->get();
    //     $organizations = Organization::orderBy('name', 'asc')->get();

    //     // 3. Ambil ID semua organisasi yang SUDAH mengunggah laporan pada periode yang dipilih.
    //     $submittedOrganizationIds = Kepustakaan::where('kategori', 'Laporan')
    //         ->where('type', 'file')
    //         ->whereYear('created_at', $selectedYear)
    //         ->whereMonth('created_at', $selectedMonth)
    //         ->whereNotNull('organization_id')
    //         ->pluck('organization_id')
    //         ->unique()
    //         ->toArray();

    //     // 4. Siapkan data untuk view.
    //     $reportStatus = [];
    //     foreach ($organizations as $org) {
    //         $status = in_array($org->id, $submittedOrganizationIds);
    //         $submissionData = null;

    //         if ($status) {
    //             // Jika sudah mengirim, ambil detail file terakhir yang diunggah
    //             $submissionData = Kepustakaan::where('organization_id', $org->id)
    //                 ->where('kategori', 'Laporan')
    //                 ->where('type', 'file')
    //                 ->whereYear('created_at', $selectedYear)
    //                 ->whereMonth('created_at', $selectedMonth)
    //                 ->latest('created_at') // Ambil yang paling baru
    //                 ->first();
    //         }

    //         $reportStatus[] = [
    //             'organization_name' => $org->name,
    //             'status' => $status,
    //             'submission' => $submissionData,
    //         ];
    //     }

    //     // 5. Kirim data ke view.
    //     return view('pages.simrs.kepustakaan.laporan_dashboard', compact(
    //         'reportStatus',
    //         'selectedYear',
    //         'selectedMonth'
    //     ));
    // }

    // app/Http-v2025/Controllers/SIMRS/KepustakaanController.php
    // app/Http/Controllers/SIMRS/KepustakaanController.php

    public function laporanDashboard(Request $request)
    {
        $selectedYear = (int) $request->input('year', Carbon::now()->year);
        $selectedMonth = (int) $request->input('month', Carbon::now()->month);

        // ===================================================================
        // LANGKAH 1: Ambil dan Siapkan Data Unit
        // ===================================================================

        // 1a. Ambil hierarki organisasi utama
        $orderedOrganizations = Organization::getAllOrderedByHierarchy();

        // 1b. Dapatkan ID organisasi 'Sub Bagian Umum' untuk referensi
        $subBagianUmumOrg = Organization::where('name', 'Sub Bagian Umum')->first();

        // 1c. Ambil unit virtual dari Kepustakaan
        $virtualUnits = collect();
        if ($subBagianUmumOrg) {
            // Cari folder "Unit Laundry" dan "Unit Kebersihan" yang dimiliki oleh ANAK dari "Sub Bagian Umum"
            $childOrgIds = $subBagianUmumOrg->child_structures()->pluck('child_organization')->toArray();
            $virtualUnits = Kepustakaan::whereIn('organization_id', $childOrgIds)
                ->whereIn('name', ['Unit Laundry', 'Unit Kebersihan'])
                ->where('kategori', 'Laporan')
                ->where('type', 'folder')
                ->get();
        }


        // ===================================================================
        // LANGKAH 2: Bangun Daftar Unit Gabungan (Master List)
        // ===================================================================

        $masterUnitList = collect();
        foreach ($orderedOrganizations as $org) {
            $masterUnitList->push([
                'id' => $org['id'],
                'is_virtual' => false,
                'organization_name' => $org['prefixed_name'],
                'original_name' => $org['name'],
            ]);

            // Sisipkan unit virtual di bawah 'Sub Bagian Umum'
            if ($subBagianUmumOrg && $org['id'] === $subBagianUmumOrg->id) {
                foreach ($virtualUnits as $virtualUnit) {
                    $masterUnitList->push([
                        'id' => 'virtual_' . $virtualUnit->id, // ID unik, misal: 'virtual_53'
                        'is_virtual' => true,
                        'organization_name' => str_repeat('— ', $org['depth'] + 1) . $virtualUnit->name,
                        'original_name' => $virtualUnit->name,
                    ]);
                }
            }
        }

        // ===================================================================
        // LANGKAH 3: Cek Status Laporan dengan Efisien
        // ===================================================================

        // 3a. Ambil ID dari semua unit nyata dan ID folder dari semua unit virtual
        $realOrgIds = $masterUnitList->where('is_virtual', false)->pluck('id')->toArray();
        $virtualFolderIds = $virtualUnits->pluck('id')->toArray();

        // 3b. Ambil SEMUA file laporan yang relevan dalam SATU QUERY
        $allSubmissions = Kepustakaan::where('kategori', 'Laporan')
            ->where('type', 'file')
            ->where('year', $selectedYear)
            ->where('month', $selectedMonth)
            ->get();

        // 3c. Buat Peta Status Pengiriman yang Cerdas
        $submissionMap = [];
        foreach ($allSubmissions as $submission) {
            // Handle real organization submissions
            if ($submission->organization_id) {
                // Check if this is not a virtual unit's submission
                $isVirtualUnitSubmission = false;
                if ($submission->parent_id) {
                    $currentFolder = Kepustakaan::find($submission->parent_id);
                    while ($currentFolder) {
                        if (in_array($currentFolder->id, $virtualFolderIds)) {
                            $isVirtualUnitSubmission = true;
                            break;
                        }
                        $currentFolder = $currentFolder->parent;
                    }
                }

                // Only map if not a virtual unit's submission
                if (!$isVirtualUnitSubmission) {
                    $submissionMap[$submission->organization_id] = $submission;
                }
            }

            // Handle virtual unit submissions
            if ($submission->parent_id) {
                $currentFolder = Kepustakaan::find($submission->parent_id);
                while ($currentFolder) {
                    if (in_array($currentFolder->id, $virtualFolderIds)) {
                        $virtualId = 'virtual_' . $currentFolder->id;
                        $submissionMap[$virtualId] = $submission;
                        break;
                    }
                    $currentFolder = $currentFolder->parent;
                }
            }
        }

        // ===================================================================
        // LANGKAH 4: Bangun dan Filter Hasil Akhir
        // ===================================================================

        $reportStatus = [];
        foreach ($masterUnitList as $unit) {
            $submissionData = $submissionMap[$unit['id']] ?? null;
            $status = !is_null($submissionData);

            $reportStatus[] = [
                'organization_name' => $unit['organization_name'],
                'original_name' => $unit['original_name'],
                'status' => $status,
                'submission' => $submissionData,
            ];
        }

        $excludedNames = [
            'fitboss',
            'pt',
            'driver',
            'dokter',
            'gudang farmasi',
            'farmasi ranap',
            'farmasi rajal',
            'rawat inap 2',
            'rawat inap',
            'umum rumah tangga',
            'gardener',
            'sod',
        ];

        $finalReportStatus = array_filter($reportStatus, function ($report) use ($excludedNames) {
            return !in_array(strtolower($report['original_name']), $excludedNames);
        });

        return view('pages.simrs.kepustakaan.laporan_dashboard', [
            'reportStatus' => $finalReportStatus,
            'selectedYear' => $selectedYear,
            'selectedMonth' => $selectedMonth,
        ]);
    }
}
