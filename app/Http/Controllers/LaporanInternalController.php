<?php

namespace App\Http\Controllers;

use App\Exports\LaporanHarianExport;
use App\Imports\LaporanInternalImport;
use App\Models\Employee;
use App\Models\LaporanInternal;
use App\Models\Organization;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

class LaporanInternalController extends Controller
{
    public function index(Request $request)
    {
        if ($request) {
            $query = LaporanInternal::query();

            // Filter berdasarkan jenis
            if ($request->filled('jenis')) {
                $query->where('jenis', $request->jenis);
            }

            // Filter berdasarkan status
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Filter berdasarkan tanggal
            if ($request->filled('tanggal')) {
                $query->whereDate('tanggal', $request->tanggal);
            }
        }

        // Ambil parent "Sub Bagian Umum"
        $parentOrganization = Organization::where('name', 'like', '%Sub Bagian Umum%')->first();

        $umum = [];

        if ($parentOrganization) {
            // Ambil ID parent dan semua child-nya dalam bentuk asosiatif
            $units = array_merge(
                [['id' => $parentOrganization->id, 'name' => $parentOrganization->name]],
                $parentOrganization->getAllChildAssociative()
            );

            // Ambil hanya ID saja dari array tersebut
            $organizationIds = collect($units)->pluck('id')->toArray();

            // Query ke Employee berdasarkan organization_id
            $umum = Employee::whereIn('organization_id', $organizationIds)
                ->where('is_active', 1)
                ->get();
        }

        $employeeUnit = Employee::where('organization_id', auth()->user()->employee->organization_id)
            ->where('is_active', 1)
            ->get();

        return view('pages.laporan-internal.index', compact('umum', 'employeeUnit'));
    }

    public function getLaporan($id)
    {
        try {
            // Logika untuk mengambil data laporan dari database atau sumber lain
            // Misalkan menggunakan model Laporan untuk mengambil data
            $laporan = LaporanInternal::find($id);

            // Jika data ditemukan, kembalikan dalam bentuk response JSON
            if ($laporan) {
                return response()->json([
                    'status' => 'success',
                    'data' => $laporan
                ], 200); // Status 200 menandakan OK
            } else {
                // Jika data tidak ditemukan
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data laporan tidak ditemukan'
                ], 404); // Status 404 untuk not found
            }
        } catch (\Exception $e) {
            // Jika terjadi kesalahan pada proses
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500); // Status 500 untuk internal server error
        }
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'organization_id' => 'required|exists:organizations,id',
            'unit_terkait' => 'required|exists:organizations,id',
            'tanggal' => 'required|date',
            'jenis' => 'required|in:kendala,kegiatan',
            'kegiatan' => 'required|string',
            'status' => 'required|in:diproses,selesai,ditunda,ditolak',
            'keterangan' => 'required_if:status,ditunda,ditolak|nullable|string',
            'jam_masuk' => 'nullable',
            'jam_diterima' => 'nullable',
            'jam_diproses' => 'nullable',
            'jam_selesai' => 'nullable',
            'dokumentasi' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048', // 2MB max
        ]);

        // Handle file upload if exists
        if ($request->hasFile('dokumentasi')) {
            $file = $request->file('dokumentasi');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('dokumentasi', $fileName);
            $validated['dokumentasi'] = Storage::url($path);
        }
        // return dd($validated['dokumentasi'] = Storage::url($path));

        $laporan = LaporanInternal::create($validated);

        return response()->json([
            'message' => 'Laporan berhasil ditambahkan.',
            'data' => $laporan
        ]);
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'organization_id' => 'required|exists:organizations,id',
            'unit_terkait' => 'required|exists:organizations,id',
            'tanggal' => 'required|date',
            'jenis' => 'required|in:kendala,kegiatan',
            'kegiatan' => 'required|string',
            'status' => 'required|in:diproses,selesai,ditunda,ditolak',
            'keterangan' => 'required_if:status,ditunda,ditolak|nullable|string',
            'jam_masuk' => 'nullable',
            'jam_diterima' => 'nullable',
            'jam_diproses' => 'nullable',
            'jam_selesai' => 'nullable',
            'dokumentasi' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048', // 2MB max
        ]);

        // Temukan laporan yang akan diupdate
        $laporan = LaporanInternal::findOrFail($id);

        // Jika ada file dokumentasi baru
        if ($request->hasFile('dokumentasi')) {
            // Hapus file lama jika ada
            if ($laporan->dokumentasi) {
                // Ambil path file lama
                $oldFilePath = public_path('storage/' . $laporan->dokumentasi);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath); // Hapus file lama
                }
            }

            // Simpan file dokumentasi baru
            $file = $request->file('dokumentasi');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('dokumentasi', $fileName);
            $validated['dokumentasi'] = Storage::url($path);
        }

        // Update laporan dengan data yang valid
        $laporan->update($validated);

        return response()->json([
            'message' => 'Laporan berhasil diperbarui.',
            'data' => $laporan
        ]);
    }


    public function list(Request $request)
    {
        // Build base query with eager loading
        $query = LaporanInternal::with('user.employee')
            ->when(!Auth::user()->hasRole('super admin'), function ($query) {
                $query->where('user_id', Auth::user()->id);
            })
            ->where('organization_id', Auth::user()->employee->organization_id)
            ->latest(); // Add latest() to order by created_at desc
        // ->where('organization_id', $request->organization);

        // Apply filters if provided
        $filters = ['jenis', 'status', 'tanggal'];
        foreach ($filters as $filter) {
            if ($request->filled($filter)) {
                if ($filter === 'tanggal') {
                    $query->whereDate($filter, $request->$filter);
                } else {
                    $query->where($filter, $request->$filter);
                }
            }
        }

        // Filter by user if provided
        if ($request->filled('user_id')) {
            $query->whereIn('user_id', $request->user_id);
        }

        // Return DataTable with custom columns
        return DataTables::of($query)
            ->addColumn('fullname', function ($item) {
                return optional($item->user->employee)->fullname ?? '-';
            })
            ->addColumn('action', function ($item) {
                return '
                <div class="btn-group">
                    <button class="btn btn-sm btn-icon btn-primary" onclick="editLaporan(' . $item->id . ')">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-icon btn-danger" onclick="deleteLaporan(' . $item->id . ')">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function complete($id)
    {
        try {
            $laporan = LaporanInternal::findOrFail($id);

            // Update status dan waktu selesai
            $laporan->update([
                'status' => 'Selesai',
                'jam_selesai' => now()->format('H:i:s'), // Format waktu sekarang
                'updated_at' => now() // Update timestamp
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Laporan berhasil ditandai sebagai selesai'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status laporan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function exportHarian(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jenis' => 'nullable|in:kegiatan,kendala'
        ]);

        $tanggal = Carbon::parse($request->tanggal)->format('Y-m-d');
        $jenis = $request->jenis;

        $filename = 'Laporan_' . ($jenis ? ucfirst($jenis) : 'All') . '_' . $tanggal . '.xlsx';

        return Excel::download(new LaporanHarianExport($tanggal, $jenis), $filename);
    }

    public function exportWordHarian(Request $request)
    {
        $tanggal = $request->input('tanggal');

        $organizationId = $request->organization_id;
        $organizationName = Organization::find($organizationId)->name;


        if ($request->has('pic') && is_array($request->input('pic'))) {
            // Jika input 'pic' ada dan berupa array, ambil datanya berdasarkan array ID
            $employees = Employee::whereIn('id', $request->input('pic'))
                ->where('is_active', 1)
                ->get();
        } else {
            // Jika tidak ada input 'pic', ambil semua employee berdasarkan organization_id
            $employees = Employee::where('organization_id', $organizationId)
                ->where('is_active', 1)
                ->get();
        }

        // Mengubah menjadi array nama lengkap
        $member = $employees->pluck('fullname')->toArray();

        // Menggabungkan array menjadi string, dipisahkan dengan koma
        $result = implode(', ', $member);

        $query = LaporanInternal::with('organization', 'user')
            ->where('organization_id', $organizationId)
            ->whereDate('tanggal', $tanggal)
            ->orderBy('jenis');

        $laporans = $query->get();

        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        // Mengatur margin halaman
        $section->getStyle()->setMarginRight(600);  // Mengurangi margin kanan
        $section->getStyle()->setMarginLeft(600);   // Mengurangi margin kiri

        // Judul
        $section->addText('LAPORAN HARIAN ' . strtoupper($organizationName), ['bold' => true, 'size' => 16], ['alignment' => 'center']);
        $section->addText('Hari/Tanggal: ' . Carbon::parse($tanggal)->translatedFormat('l, d F Y'), ['size' => 12], ['alignment' => 'center']);
        $section->addText('Anggota:', ['size' => 8], ['alignment' => 'center']);
        $section->addText($result, ['size' => 8], ['alignment' => 'center']);
        $section->addTextBreak(1);

        // Header Tabel
        $table = $section->addTable([
            'borderSize' => 6,
            'borderColor' => '999999',
            'cellMargin' => 100, // Memperbesar jarak antara sel
            'alignment' => 'center'
        ]);

        $headerStyle = ['bold' => true, 'size' => 8];
        $rowStyle = ['bold' => false, 'size' => 8];

        // Header Tabel
        $table->addRow();
        $table->addCell(1000)->addText('No', $headerStyle, ['alignment' => 'center']);
        $table->addCell(3500)->addText('Jenis', $headerStyle, ['alignment' => 'center']);
        $table->addCell(3500)->addText('Unit', $headerStyle, ['alignment' => 'center']);
        $table->addCell(6000)->addText('Kegiatan', $headerStyle, ['alignment' => 'center']);
        $table->addCell(3500)->addText('PIC', $headerStyle, ['alignment' => 'center']);
        $table->addCell(3500)->addText('Status', $headerStyle, ['alignment' => 'center']);
        $table->addCell(3500)->addText('Keterangan', $headerStyle, ['alignment' => 'center']);
        if ($organizationName == 'Informasi Teknologi (IT)') {
            $table->addCell(4500)->addText('Masuk/Mulai', $headerStyle, ['alignment' => 'center']);
            $table->addCell(3500)->addText('Selesai', $headerStyle, ['alignment' => 'center']);
        }
        $table->addCell(6500)->addText('Dokumentasi', $headerStyle, ['alignment' => 'center']);

        // Data Baris
        $no = 1;
        foreach ($laporans as $laporan) {
            $table->addRow();
            $table->addCell(1000)->addText($no++, $rowStyle, ['alignment' => 'center']);
            $table->addCell(2500)->addText(ucfirst($laporan->jenis), $rowStyle, ['alignment' => 'center']);
            if ($laporan->jenis === "kegiatan") {
                $table->addCell(3000)->addText('Internal', $rowStyle, ['alignment' => 'center']);
            } else {
                $table->addCell(3000)->addText($this->convertSymbolsToWords($laporan->getUnitTerkaitName()), $rowStyle, ['alignment' => 'center']);
            }
            $table->addCell(5000)->addText($this->convertSymbolsToWords($laporan->kegiatan), $rowStyle);
            $table->addCell(3000)->addText(
                // Cek apakah organisasi user adalah "Informasi Teknologi (IT)"
                $organizationName == 'Informasi Teknologi (IT)'
                    ? (
                        $laporan->user->id == 231 ? 'IT Support SIMRS' : ($laporan->user->id == 14 ? 'IT Hardware Networking' : 'IT Programmer Developer')
                    )
                    : optional($laporan->user->employee)->fullname, // Jika bukan, tampilkan nama lengkap
                $rowStyle,
                ['alignment' => 'center']
            );
            $table->addCell(2500)->addText(ucfirst($laporan->status), $rowStyle, ['alignment' => 'center']);
            $table->addCell(2500)->addText(ucfirst($this->convertSymbolsToWords($laporan->keterangan)), $rowStyle, ['alignment' => 'center']);
            if ($organizationName == 'Informasi Teknologi (IT)') {
                $table->addCell(3000)->addText($laporan->jam_masuk ?? '-', $rowStyle, ['alignment' => 'center']);
                $table->addCell(2000)->addText($laporan->jam_selesai ?? '-', $rowStyle, ['alignment' => 'center']);
            }
            // **Menampilkan Gambar di Kolom Dokumentasi**
            $imageCell = $table->addCell(2500, ['alignment' => 'center']);

            if (!empty($laporan->dokumentasi) && file_exists(public_path($laporan->dokumentasi))) {
                $imageCell->addImage(public_path($laporan->dokumentasi), [
                    'width' => 100,    // lebar gambar dalam Word
                    'height' => 100,   // tinggi gambar dalam Word
                    'alignment' => 'center',
                ]);
            } else {
                $imageCell->addText('Tidak ada gambar', $rowStyle, ['alignment' => 'center']);
            }
        }
        // Save as Word
        $fileName = 'Daily_Report_' . $organizationName . '_' . $tanggal . '.docx';
        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');

        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        ob_clean();
        flush();
        $objWriter->save("php://output");
        exit;
    }

    public function exportPPTXHarian(Request $request)
    {
        $laporan = LaporanInternal::with(['organization', 'user'])
            ->whereMonth('created_at', $request->bulan) // 5 = Mei
            ->whereYear('created_at', 2025) // ganti tahun sesuai kebutuhan
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(fn($item) => $item->organization->name)
            ->reject(fn($group, $orgName) => in_array($orgName, ['Sanitasi', 'PSRS']))
            ->map(function ($orgGroup) {
                return $orgGroup->groupBy(fn($item) => $item->user->name)
                    ->map(function ($userGroup) {
                        return $userGroup->groupBy(fn($item) => $item->jenis);
                    });
            });

        return view('pages.laporan-internal.pptx', compact('laporan'));
    }

    public function import(Request $request)
    {
        // Validasi file
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            Excel::import(new LaporanInternalImport, $request->file('file'));

            return response()->json([
                'message' => 'Import data laporan internal berhasil.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat import data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $laporan = LaporanInternal::findOrFail($id);

        // Delete documentation file if exists
        if ($laporan->dokumentasi) {
            $filePath = str_replace('/storage', 'public', $laporan->dokumentasi);
            Storage::delete($filePath);
        }

        $laporan->delete();

        return response()->json(['message' => 'Laporan berhasil dihapus']);
    }

    /**
     * Method untuk mengganti simbol dengan kata
     */
    private function convertSymbolsToWords($text)
    {
        // Daftar simbol yang akan diganti dengan kata
        $symbolReplacements = [
            '&' => 'dan',
            '>' => 'lebih besar dari',
            '<' => 'lebih kecil dari',
            '=' => 'sama dengan',
            '-' => 'minus',
            '+' => 'plus',
            '*' => 'kali',
            '/' => 'dibagi',
            '%' => 'persen',
            '^' => 'pangkat',
            '√' => 'akar kuadrat',
            '∞' => 'tak terhingga',
            '≈' => 'perkiraan',
            '≠' => 'tidak sama dengan',
            '≤' => 'lebih kecil atau sama dengan',
            '≥' => 'lebih besar atau sama dengan',
            '±' => 'plus-minus',
            '≡' => 'identik',
            '∑' => 'jumlah',
            'π' => 'pi',
            '€' => 'Euro',
            '$' => 'Dollar',
            '£' => 'Pound',
            '¥' => 'Yen',
            '₹' => 'Rupee',
            '°' => 'derajat',
            '✔' => 'centang',
            '✘' => 'silang',
            '♠' => 'Pik',
            '♥' => 'Hati',
            '♦' => 'Wajik',
            '♣' => 'Kelopak',
            '✈' => 'Pesawat',
            '★' => 'Bintang',
            '☀' => 'Matahari',
            '☁' => 'Awan',
            '☂' => 'Payung',
            '⚡' => 'Petir',
            '✿' => 'Bunga',
            '☃' => 'Salju',
            '♪' => 'Musik',
            '♫' => 'Notasi Musik',
            '☠' => 'Tengkorak',
            '♻' => 'Daur Ulang',
            '⚙' => 'Gear',
            '⚖' => 'Keadilan',
            '⚔' => 'Pedang',
            '⚰' => 'Peti Mati',
            '✉' => 'Surat',
            '✂' => 'Gunting',
            '⚒' => 'Palang',
            '⚓' => 'Jangkar',
            '⚔' => 'Pertempuran',
            '✏' => 'Pensil',
            '✍' => 'Menulis',
            '☮' => 'Damai',
            '☯' => 'Yin Yang',
            '✡' => 'Bintang David',
        ];

        // Ganti simbol-simbol di dalam teks dengan kata yang sesuai
        return strtr($text, $symbolReplacements);
    }
}
