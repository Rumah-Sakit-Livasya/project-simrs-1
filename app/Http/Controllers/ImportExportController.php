<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KelasRawatExport;
use App\Exports\RoomsExport;
use App\Exports\BedsExport;
use App\Imports\KelasRawatImport;
use App\Imports\RoomsImport;
use App\Imports\BedsImport;

class ImportExportController extends Controller
{
    /**
     * Menampilkan halaman untuk import & export.
     */
    public function index()
    {
        return view('pages.simrs.control-panel.manajemen-data.import-export');
    }

    // --- EXPORT METHODS ---

    public function exportKelasRawat()
    {
        return Excel::download(new KelasRawatExport, 'kelas_rawat.xlsx');
    }

    public function exportRooms()
    {
        return Excel::download(new RoomsExport, 'ruangan.xlsx');
    }

    public function exportBeds()
    {
        return Excel::download(new BedsExport, 'beds.xlsx');
    }

    // --- IMPORT METHODS ---

    public function importKelasRawat(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            Excel::import(new KelasRawatImport, $request->file('file'));
            return redirect()->back()->with('success', 'Data Kelas Rawat berhasil diimpor!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage());
        }
    }

    public function importRooms(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            Excel::import(new RoomsImport, $request->file('file'));
            return redirect()->back()->with('success', 'Data Ruangan berhasil diimpor!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage());
        }
    }

    public function importBeds(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            Excel::import(new BedsImport, $request->file('file'));
            return redirect()->back()->with('success', 'Data Bed berhasil diimpor!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage());
        }
    }
}
