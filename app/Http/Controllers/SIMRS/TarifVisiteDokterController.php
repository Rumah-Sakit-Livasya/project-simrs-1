<?php

namespace App\Http\Controllers\SIMRS;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Doctor;
use App\Models\SIMRS\KelasRawat;
use App\Models\SIMRS\TarifVisiteDokter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TarifVisiteDokterController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = TarifVisiteDokter::with(['doctor.employee', 'kelas_rawat'])->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('doctor_name', function ($row) {
                    return $row->doctor->employee->fullname ?? 'N/A';
                })
                ->addColumn('kelas_rawat_name', function ($row) {
                    return $row->kelas_rawat->name ?? 'N/A';
                })
                ->editColumn('share_rs', function ($row) {
                    return 'Rp ' . number_format($row->share_rs, 0, ',', '.');
                })
                ->editColumn('share_dr', function ($row) {
                    return 'Rp ' . number_format($row->share_dr, 0, ',', '.');
                })
                ->editColumn('prasarana', function ($row) {
                    return 'Rp ' . number_format($row->prasarana, 0, ',', '.');
                })
                ->editColumn('total', function ($row) {
                    return 'Rp ' . number_format($row->total, 0, ',', '.');
                })
                ->addColumn('action', function ($row) {
                    $btn = '<button type="button" class="btn btn-warning btn-sm btn-icon waves-effect waves-themed" data-id="' . $row->id . '" id="editBtn"><i class="fal fa-edit"></i></button>';
                    $btn .= ' <button type="button" class="btn btn-danger btn-sm btn-icon waves-effect waves-themed" data-id="' . $row->id . '" id="deleteBtn"><i class="fal fa-trash-alt"></i></button>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $doctors = Doctor::with('employee')->whereHas('employee')->get();
        $kelasRawat = KelasRawat::all();

        return view('pages.simrs.tarif-visite-dokter.index', compact('doctors', 'kelasRawat'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'kelas_rawat_id' => 'required|exists:kelas_rawat,id',
            'share_rs' => 'required|numeric',
            'share_dr' => 'required|numeric',
            'prasarana' => 'required|numeric',
        ]);

        try {
            DB::beginTransaction();
            TarifVisiteDokter::updateOrCreate(
                ['id' => $request->id],
                [
                    'doctor_id' => $request->doctor_id,
                    'kelas_rawat_id' => $request->kelas_rawat_id,
                    'share_rs' => $request->share_rs,
                    'share_dr' => $request->share_dr,
                    'prasarana' => $request->prasarana,
                ]
            );
            DB::commit();
            return response()->json(['message' => 'Data berhasil disimpan'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal menyimpan data: ' . $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        $data = TarifVisiteDokter::find($id);
        if (!$data) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }
        return response()->json($data);
    }

    public function destroy($id)
    {
        try {
            $data = TarifVisiteDokter::findOrFail($id);
            $data->delete();
            return response()->json(['message' => 'Data berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menghapus data: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Menampilkan halaman popup untuk setting tarif dokter spesifik.
     */
    public function setTariffForDoctor(Doctor $doctor)
    {
        $kelasRawat = KelasRawat::all();
        $doctor->load('employee'); // Eager load relasi employee untuk mendapatkan nama
        return view('pages.simrs.tarif-visite-dokter.set-tarif-popup', compact('doctor', 'kelasRawat'));
    }

    /**
     * Menyediakan data tarif untuk DataTables di halaman popup.
     */
    public function getTariffByDoctor(Request $request, Doctor $doctor)
    {
        if ($request->ajax()) {
            $data = TarifVisiteDokter::where('doctor_id', $doctor->id)->with('kelas_rawat')->latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('kelas_rawat_name', function ($row) {
                    return $row->kelas_rawat->kelas ?? 'N/A';
                })
                ->editColumn('share_rs', fn($row) => 'Rp ' . number_format($row->share_rs, 0, ',', '.'))
                ->editColumn('share_dr', fn($row) => 'Rp ' . number_format($row->share_dr, 0, ',', '.'))
                ->editColumn('prasarana', fn($row) => 'Rp ' . number_format($row->prasarana, 0, ',', '.'))
                ->editColumn('total', fn($row) => 'Rp ' . number_format($row->total, 0, ',', '.'))
                ->addColumn('action', function ($row) {
                    $btn = '<button type="button" class="btn btn-warning btn-sm btn-icon waves-effect waves-themed" data-id="' . $row->id . '" id="editBtn"><i class="fal fa-edit"></i></button>';
                    $btn .= ' <button type="button" class="btn btn-danger btn-sm btn-icon waves-effect waves-themed" data-id="' . $row->id . '" id="deleteBtn"><i class="fal fa-trash-alt"></i></button>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        abort(404);
    }
}
