<?php

namespace App\Http\Controllers\SIMRS;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\SIMRS\Kepustakaan;
use App\Models\User;
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

        $organizations = Organization::all();
        $breadcrumbs = collect();
        return view('pages.simrs.kepustakaan.index', compact('kepustakaan', 'breadcrumbs', 'organizations'));
    }

    public function showFolder($encryptedId)
    {
        $id = Crypt::decrypt($encryptedId);
        // Cari folder berdasarkan nama
        $folder = Kepustakaan::where('id', $id)
            ->where('type', 'folder') // Memastikan hanya folder
            ->firstOrFail();

        if ($folder->kategori == 'Perizinan' && !auth()->user()->can('master kepustakaan')) {
            return redirect()->back()->with('error', 'Anda tidak punya akses ini!');
        }

        $breadcrumbs = getBreadcrumbs($folder);
        if (auth()->user()->can('master kepustakaan')) {
            $kepustakaan = Kepustakaan::where('parent_id', $folder->id)
                ->orderByRaw("CASE WHEN type = 'folder' THEN 1 ELSE 2 END")
                ->orderBy('name', 'asc')
                ->get();
        } else {
            $kepustakaan = Kepustakaan::where('parent_id', $folder->id)
                ->where('organization_id', auth()->user()->employee->organization_id)
                ->orderByRaw("CASE WHEN type = 'folder' THEN 1 ELSE 2 END")
                ->orderBy('name', 'asc')
                ->get();
        }

        if (auth()->user()->hasRole('super admin') || auth()->user()->can('master kepustakaan')) {
            $organizations = Organization::all();
        } else {
            $organizations = Organization::where('id', auth()->user()->employee->organization_id)->first();
        }

        return view('pages.simrs.kepustakaan.index', compact('kepustakaan', 'breadcrumbs', 'folder', 'organizations'));
    }

    public function getKepustakaan($encryptedId)
    {
        $id = Crypt::decrypt($encryptedId);
        $folder = Kepustakaan::where('id', $id)
            ->firstOrFail();

        return response()->json($folder->name, 200);
    }

    public function downloadFile($encryptedId)
    {
        // Pastikan user sudah login
        if (!auth()->check()) {
            abort(403, 'Unauthorized');
        }

        $id = Crypt::decrypt($encryptedId);
        $file = Kepustakaan::where('id', $id)->firstOrFail();

        $path = "/kepustakaan" . "/" . \Str::slug($file->kategori) . "/" . $file->file;

        if (!Storage::disk('private')->exists($path)) {
            abort(404, 'File not found');
        }

        return Storage::disk('private')->download($path);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'type' => 'required',
            'kategori' => 'nullable',
            'parent_id' => 'nullable',
            'organization_id' => 'nullable',
            'name' => 'required',
            'size' => 'nullable',
            'file' => 'nullable',
        ]);

        if (request()->hasFile('file')) {
            $file = request()->file('file');
            $fileName = $request->name . '.' . $file->getClientOriginalExtension();
            $path = 'kepustakaan/' . \Str::slug($request->kategori);
            $pathFix = $file->storeAs($path, $fileName, 'private');
            $validatedData['file'] = $fileName;
        }

        try {
            $store = Kepustakaan::create($validatedData);
            return response()->json(['message' => ' Folder/File ditambahkan!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $encryptedId)
    {
        $validatedData = $request->validate([
            'name' => 'required',
        ]);

        try {

            $id = Crypt::decrypt($encryptedId);
            $file = Kepustakaan::where('id', $id)->firstOrFail();

            $file->update($validatedData);
            return response()->json(['message' => ' Folder berhasil diupdate!'], 200);
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
                $filePath = 'kepustakaan/' . \Str::slug($child->kategori) . '/' . $child->file;
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
}
