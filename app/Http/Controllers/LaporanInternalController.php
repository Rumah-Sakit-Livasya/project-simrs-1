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
    public function index()
    {
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
            ->addColumn('dokumentasi', function ($item) {
                if (!$item->dokumentasi || is_numeric($item->dokumentasi)) {
                    return '<span class="text-muted">Tidak ada</span>';
                }

                // Check if it's already a full URL
                if (filter_var($item->dokumentasi, FILTER_VALIDATE_URL)) {
                    $fileUrl = $item->dokumentasi;
                } else {
                    // Handle relative paths
                    $fileUrl = $item->dokumentasi;
                }

                return e($fileUrl);
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
        $jenis = $request->input('jenis');

        $query = LaporanInternal::with('organization', 'user')
            ->whereDate('tanggal', $tanggal);

        if ($jenis) {
            $query->where('jenis', $jenis);
        }

        $laporans = $query->get();

        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        // Judul
        $section->addText('Laporan Harian IT', ['bold' => true, 'size' => 16]);
        $section->addText("Tanggal: " . date('d-m-Y', strtotime($tanggal)));
        $section->addText("Anggota: Dimas Candra Pebriyanto, Tiyas Frahesta, Elsa Ramadini, Muhammad Adib, Ricky Ahmad");
        $section->addTextBreak(1);

        // Tabel Header
        $table = $section->addTable([
            'borderSize' => 6,
            'borderColor' => '999999',
            'cellMargin' => 10,
        ]);

        $table->addRow();
        $table->addCell(500)->addText('No', ['bold' => true, 'size' => 9],  ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $table->addCell(1500)->addText('Unut', ['bold' => true, 'size' => 9],  ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $table->addCell(1500)->addText('User', ['bold' => true, 'size' => 9],  ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $table->addCell(1000)->addText('Jenis', ['bold' => true, 'size' => 9],  ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $table->addCell(3000)->addText('Kegiatan', ['bold' => true, 'size' => 9],  ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $table->addCell(1000)->addText('Status', ['bold' => true, 'size' => 9],  ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $table->addCell(1500)->addText('Masuk/Mulai', ['bold' => true, 'size' => 9],  ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $table->addCell(1500)->addText('Selesai', ['bold' => true, 'size' => 9],  ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

        $no = 1;
        foreach ($laporans as $laporan) {
            $table->addRow();
            $table->addCell(500)->addText($no++);
            $table->addCell(1500)->addText(optional($laporan->organization)->name ?? '-');
            $table->addCell(1500)->addText(optional($laporan->user)->name ?? '-');
            $table->addCell(1000)->addText($laporan->jenis);
            $table->addCell(3000)->addText($laporan->kegiatan);
            $table->addCell(1000)->addText($laporan->status);
            $table->addCell(1500)->addText($laporan->jam_masuk ?? '-');
            $table->addCell(1500)->addText($laporan->jam_selesai ?? '-');
        }

        $fileName = 'laporan_internal_' . $tanggal . '.docx';
        $tempFile = tempnam(sys_get_temp_dir(), 'word');
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($tempFile);

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
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
