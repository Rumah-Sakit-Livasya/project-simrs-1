<?php

namespace App\Http\Controllers\SIMRS\Gizi;

use App\Http\Controllers\Controller;
use App\Models\KategoriGizi;
use App\Models\MakananGizi;
use App\Models\MenuGizi;
use App\Models\OrderGizi;
use App\Models\SIMRS\KelasRawat;
use App\Models\SIMRS\Penjamin;
use App\Models\SIMRS\Registration;
use App\Models\SIMRS\Room;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class GiziController extends Controller
{
    /**
     * Menampilkan halaman Daftar Pasien Gizi.
     * Method ini hanya menyiapkan view dan data untuk filter.
     */
    public function index()
    {
        $penjamins = Penjamin::all();
        $kelasRawats = KelasRawat::all();
        $rooms = Room::all(); // Menggunakan nama model Ruangan

        return view('pages.simrs.gizi.list-pasien', compact('penjamins', 'kelasRawats', 'rooms'));
    }

    /**
     * Menyediakan data pasien untuk DataTables.
     * Method ini menangani request AJAX dari DataTables.
     */
    public function datatable(Request $request)
    {
        // Query dasar: Hanya pasien Rawat Inap yang masih aktif
        $query = Registration::with([
            'patient.bed.room',
            'kelas_rawat',
            'doctor.employee',
            'diet_gizi.category',
            'penjamin'
        ])
            ->where('status', 'aktif')
            ->where('registration_type', 'rawat-inap') // Sesuaikan dengan nilai di database Anda
            ->select('registrations.*');

        // --- Menerapkan Filter dari request AJAX ---
        if ($request->filled('medical_record_number')) {
            $query->whereHas('patient', fn($q) => $q->where('medical_record_number', 'like', '%' . $request->medical_record_number . '%'));
        }
        if ($request->filled('registration_number')) {
            $query->where('registration_number', 'like', '%' . $request->registration_number . '%');
        }
        if ($request->filled('patient_name')) {
            $query->whereHas('patient', fn($q) => $q->where('name', 'like', '%' . $request->patient_name . '%'));
        }
        if ($request->filled('penjamin_id')) {
            $query->where('penjamin_id', $request->penjamin_id);
        }
        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }
        if ($request->filled('room_id')) {
            // Pastikan relasi ini benar: Registration -> Patient -> Bed -> Room
            $query->whereHas('patient.bed', fn($q) => $q->where('ruangan_id', $request->room_id));
        }
        // Filter-filter dari kode asli Anda yang mungkin tidak terpakai di form
        if ($request->filled('keluarga_pj')) {
            $query->whereHas('patient.family', fn($q) => $q->where('family_name', 'like', '%' . $request->keluarga_pj . '%'));
        }
        if ($request->filled('address')) {
            $query->whereHas('patient', fn($q) => $q->where('address', 'like', '%' . $request->address . '%'));
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('kelas', fn($row) => $row->kelas_rawat->kelas ?? 'N/A')
            ->addColumn('ruang', fn($row) => $row->patient->bed->room->ruangan ?? 'N/A')
            ->addColumn('tempat_tidur', fn($row) => $row->patient->bed->nama_tt ?? 'N/A')
            ->addColumn('pasien_info', function ($row) {
                $mrn = $row->patient->medical_record_number ?? 'N/A';
                $nama = $row->patient->name ?? 'N/A';
                return "[$mrn] $nama";
            })
            ->addColumn('dokter', fn($row) => $row->doctor->employee->fullname ?? 'N/A')
            ->addColumn('kategori_diet', fn($row) => $row->diet_gizi->category->nama ?? '<span class="badge badge-warning">Belum Dipilih</span>')
            ->addColumn('asuransi', fn($row) => $row->penjamin->penjamin ?? '-')
            ->addColumn('action', function ($row) {
                return view('pages.simrs.gizi.partials.list-pasien-actions', ['registration' => $row])->render();
            })
            ->rawColumns(['kategori_diet', 'action'])
            ->make(true);
    }


    /**
     * Menampilkan halaman Laporan.
     */
    public function reports()
    {
        return view("pages.simrs.gizi.laporan", [
            "categories" => KategoriGizi::all(),
            "menus" => MenuGizi::all(),
            "foods" => MakananGizi::all()
        ]);
    }

    /**
     * Menampilkan hasil Laporan dalam view terpisah.
     */
    public function reports_view($fromDate, $endDate, $kategori_id, $food_id, $status_payment, $waktu_makan, $untuk)
    {
        // Query Anda sudah cukup baik, hanya perlu penyederhanaan sedikit
        $query = OrderGizi::query()->with(["foods", "category"])
            ->whereBetween('tanggal_order', [$fromDate, $endDate]);

        if ($kategori_id != '-') {
            $query->where('kategori_id', $kategori_id);
        }
        if ($food_id != '-') {
            $query->whereHas('foods', fn($q) => $q->where('makanan_id', $food_id));
        }
        if ($status_payment != '-') {
            $query->where('status_payment', $status_payment);
        }
        if ($waktu_makan != '-') {
            $query->where('waktu_makan', $waktu_makan);
        }
        if ($untuk != '-') {
            $query->where('untuk', $untuk);
        }

        $orders = $query->get();

        return view('pages.simrs.gizi.partials.laporan-view', [
            'orders' => $orders,
            'startDate' => $fromDate,
            'endDate' => $endDate
        ]);
    }
}
