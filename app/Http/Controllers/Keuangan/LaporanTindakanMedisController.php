<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Doctor; // Pastikan model Doctor ada
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Exports\SimpleCollectionExport;
use Maatwebsite\Excel\Facades\Excel;

class LaporanTindakanMedisController extends Controller
{
    /**
     * Menampilkan halaman filter Laporan Tindakan Medis.
     */
    public function index()
    {
        // Ambil daftar dokter aktif untuk dropdown filter
        $doctors = Doctor::with('employee:id,fullname')
            ->whereHas('employee', fn($q) => $q->where('is_active', true))
            ->get()
            ->filter(fn($d) => $d->employee !== null)
            ->sortBy('employee.fullname');

        // Tipe rawat adalah data statis
        $tipeRawatList = ['RAWAT JALAN', 'IGD', 'RAWAT INAP'];

        return view('app-type.keuangan.laporanpendukung.tindakan-medis.index', [
            'doctors' => $doctors,
            'tipeRawatList' => $tipeRawatList,
        ]);
    }

    /**
     * Membuat dan menampilkan halaman pop-up untuk dicetak.
     */
    public function print(Request $request)
    {
        $request->validate([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
        ]);

        $laporanData = $this->getLaporanData($request);

        // Menentukan nama filter untuk ditampilkan di header cetak
        $tipeRawatNama = $request->filled('tipe_rawat') ? $request->tipe_rawat : 'Semua Tipe Rawat';
        $dokterNama = 'Semua Dokter';
        if ($request->filled('dokter_id')) {
            $dokter = Doctor::with('employee:id,fullname')->find($request->dokter_id);
            $dokterNama = $dokter?->employee?->fullname ?? 'N/A';
        }

        return view('app-type.keuangan.laporanpendukung.tindakan-medis.print', [
            'laporanData' => $laporanData,
            'tanggalAwal' => Carbon::parse($request->tanggal_awal)->format('d-m-Y'),
            'tanggalAkhir' => Carbon::parse($request->tanggal_akhir)->format('d-m-Y'),
            'tipeRawatNama' => $tipeRawatNama,
            'dokterNama' => $dokterNama,
        ]);
    }

    /**
     * Menangani permintaan ekspor ke Excel.
     */
    public function export(Request $request)
    {
        $request->validate([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
        ]);

        $laporanData = $this->getLaporanData($request);

        // Mengubah format data agar sesuai untuk Excel
        $exportData = $laporanData->map(function ($item) {
            return [
                'TANGGAL PEMERIKSAAN' => $item->tanggal,
                'NAMA PASIEN' => $item->nama_pasien,
                'NO RM' => $item->no_rm,
                'NAMA TINDAKAN' => $item->nama_tindakan,
                'DOKTER (DPJP)' => $item->nama_dokter,
                'JML TINDAKAN' => $item->jumlah,
                'HARGA' => $item->harga,
            ];
        });

        $fileName = 'Laporan_Tindakan_Medis_' . $request->tanggal_awal . '_sd_' . $request->tanggal_akhir . '.xlsx';
        return Excel::download(new SimpleCollectionExport($exportData), $fileName);
    }

    /**
     * Helper function untuk mengambil data dari tabel tagihan_pasien.
     * Asumsi: tabel tagihan pasien memiliki kolom 'dokter_id' untuk DPJP.
     */
    private function getLaporanData(Request $request)
    {
        $startDate = Carbon::parse($request->tanggal_awal)->startOfDay();
        $endDate = Carbon::parse($request->tanggal_akhir)->endOfDay();

        $query = DB::table('tagihan_pasien as tp')
            ->join('registrations as reg', 'tp.registration_id', '=', 'reg.id')
            ->join('patients as p', 'reg.patient_id', '=', 'p.id')
            ->leftJoin('doctors as d', 'tp.dokter_id', '=', 'd.id') // Menggunakan LEFT JOIN jika dokter bisa kosong
            ->leftJoin('employees as e', 'd.employee_id', '=', 'e.id')
            ->select(
                'tp.created_at as tanggal', // Gunakan created_at atau date sesuai kebutuhan
                'p.name as nama_pasien',
                'p.medical_record_number as no_rm',
                'tp.tagihan as nama_tindakan',
                'e.fullname as nama_dokter',
                'tp.quantity as jumlah',
                'tp.wajib_bayar as harga' // atau 'nominal'
            )
            ->whereBetween('tp.created_at', [$startDate, $endDate])
            // Filter tambahan untuk hanya mengambil item yang merupakan tindakan medis
            // Sesuaikan 'kategori_tagihan' dengan nama kolom dan nilai di database Anda
            ->where('tp.kategori_tagihan', 'TINDAKAN')
            ->whereNull('tp.deleted_at')
            ->orderBy('tp.created_at', 'desc');

        // Terapkan filter dari form
        if ($request->filled('tipe_rawat')) {
            $query->where('reg.registration_type', $request->tipe_rawat);
        }

        if ($request->filled('dokter_id')) {
            $query->where('tp.dokter_id', $request->dokter_id);
        }

        $results = $query->get();

        // Format tanggal sebelum dikirim ke view
        $results->transform(function ($item) {
            $item->tanggal = Carbon::parse($item->tanggal)->format('d M Y H:i:s');
            return $item;
        });

        return $results;
    }
}
