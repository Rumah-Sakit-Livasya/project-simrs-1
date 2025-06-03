<?php

namespace App\Http\Controllers;

use App\Exports\LaporanHarianExport;
use App\Models\LaporanInternal;
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

        return view('pages.laporan-internal.index');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'organization_id' => 'required|exists:organizations,id',
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
        $query = LaporanInternal::with('user.employee');

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

        $query = LaporanInternal::with('organization', 'user')
            ->whereDate('tanggal', $tanggal)
            ->orderBy('jenis');

        $laporans = $query->get();

        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        // Mengatur margin halaman
        $section->getStyle()->setMarginRight(600);  // Mengurangi margin kanan
        $section->getStyle()->setMarginLeft(600);   // Mengurangi margin kiri

        // Judul
        $section->addText('LAPORAN HARIAN IT', ['bold' => true, 'size' => 16], ['alignment' => 'center']);
        $section->addText('Hari/Tanggal: ' . Carbon::parse($tanggal)->translatedFormat('l, d F Y'), ['size' => 12], ['alignment' => 'center']);
        $section->addText('Anggota:', ['size' => 10], ['alignment' => 'center']);
        $section->addText('Dimas, Tiyas, Elsa, Adib, Ricky', ['size' => 10], ['alignment' => 'center']);
        $section->addTextBreak(1);

        // Header Tabel
        $table = $section->addTable([
            'borderSize' => 6,
            'borderColor' => '999999',
            'cellMargin' => 100, // Memperbesar jarak antara sel
            'alignment' => 'center'
        ]);

        $headerStyle = ['bold' => true, 'size' => 10];
        $rowStyle = ['bold' => false, 'size' => 10];

        // Header Tabel
        $table->addRow();
        $table->addCell(1500)->addText('No', $headerStyle, ['alignment' => 'center']);
        $table->addCell(3500)->addText('Jenis', $headerStyle, ['alignment' => 'center']);
        $table->addCell(4500)->addText('Unit', $headerStyle, ['alignment' => 'center']);
        $table->addCell(7000)->addText('Kegiatan', $headerStyle, ['alignment' => 'center']);
        $table->addCell(4500)->addText('PIC', $headerStyle, ['alignment' => 'center']);
        $table->addCell(3500)->addText('Status', $headerStyle, ['alignment' => 'center']);
        $table->addCell(4500)->addText('Masuk/Mulai', $headerStyle, ['alignment' => 'center']);
        $table->addCell(3500)->addText('Selesai', $headerStyle, ['alignment' => 'center']);

        // Data Baris
        $no = 1;
        foreach ($laporans as $laporan) {
            $table->addRow();
            $table->addCell(1000)->addText($no++, $rowStyle, ['alignment' => 'center']);
            $table->addCell(2500)->addText(ucfirst($laporan->jenis), $rowStyle, ['alignment' => 'center']);
            if ($laporan->jenis === "kegiatan") {
                $table->addCell(3000)->addText('Internal', $rowStyle, ['alignment' => 'center']);
            } else {
                $table->addCell(3000)->addText(optional($laporan->organization)->name ?? '-', $rowStyle, ['alignment' => 'center']);
            }
            $table->addCell(5000)->addText($laporan->kegiatan);
            $table->addCell(3000)->addText(
                optional($laporan->user)->id == 231 ? 'IT Support SIMRS' : (optional($laporan->user)->id == 14 ? 'IT Hardware Networking' : 'IT Programmer Developer'),
                $rowStyle,
                ['alignment' => 'center']
            );
            $table->addCell(2500)->addText(ucfirst($laporan->status), $rowStyle, ['alignment' => 'center']);
            $table->addCell(3000)->addText($laporan->jam_masuk ?? '-', $rowStyle, ['alignment' => 'center']);
            $table->addCell(2000)->addText($laporan->jam_selesai ?? '-', $rowStyle, ['alignment' => 'center']);
        }

        // Save as Word
        $fileName = 'Daily_Report_IT_' . $tanggal . '.docx';
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
