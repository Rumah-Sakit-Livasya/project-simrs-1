<?php

namespace App\Http\Controllers;

use App\Exports\LaporanHarianExport;
use App\Models\Employee;
use App\Models\LaporanInternal;
use App\Models\Organization;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
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

    public function list(Request $request)
    {
        $query = LaporanInternal::with('user.employee')->where('organization_id', $request->organization);

        // Apply filters based on request
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        // Filter by user if provided
        if ($request->filled('user')) {
            $query->whereIn('user_id', $request->user);
        }

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
                $table->addCell(3000)->addText(Organization::find($laporan->unit_terkait)->name ?? '-', $rowStyle, ['alignment' => 'center']);
            }
            $table->addCell(5000)->addText($laporan->kegiatan, $rowStyle);
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
            $table->addCell(2500)->addText(ucfirst($laporan->keterangan), $rowStyle, ['alignment' => 'center']);
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
}
