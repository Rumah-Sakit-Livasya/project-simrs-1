<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Exports\SimpleCollectionExport;
use Maatwebsite\Excel\Facades\Excel;

class LaporanTindakanPasienController extends Controller
{
    /**
     * HANYA menampilkan halaman filter Laporan Tindakan Pasien.
     */
    public function index()
    {
        // Ambil data untuk dropdown filter
        $doctors = Doctor::with('employee:id,fullname')->whereHas('employee', fn($q) => $q->where('is_active', true))->get()->filter(fn($d) => $d->employee !== null)->sortBy('employee.fullname');

        $tipeRawatList = ['RAWAT JALAN', 'IGD', 'RAWAT INAP'];

        return view('app-type.keuangan.laporanpendukung.tindakan-pasien.index', [
            'doctors' => $doctors,
            'tipeRawatList' => $tipeRawatList,
        ]);
    }

    /**
     * Membuat dan menampilkan halaman pop-up untuk dicetak.
     * (Metode ini tidak perlu diubah, sudah benar)
     */
    public function print(Request $request)
    {
        $request->validate(['tanggal_awal' => 'required|date', 'tanggal_akhir' => 'required|date']);
        $laporanData = $this->getLaporanData($request);
        $tipeRawatNama = $request->filled('tipe_rawat') ? $request->tipe_rawat : 'Semua Tipe Rawat';
        $dokterNama = 'Semua Dokter';
        if ($request->filled('dokter_id')) {
            $dokter = Doctor::with('employee:id,fullname')->find($request->dokter_id);
            $dokterNama = $dokter?->employee?->fullname ?? 'N/A';
        }
        return view('app-type.keuangan.laporanpendukung.tindakan-pasien.print', [
            'laporanData' => $laporanData,
            'tanggalAwal' => Carbon::parse($request->tanggal_awal)->format('d-m-Y'),
            'tanggalAkhir' => Carbon::parse($request->tanggal_akhir)->format('d-m-Y'),
            'tipeRawatNama' => $tipeRawatNama,
            'dokterNama' => $dokterNama,
        ]);
    }

    /**
     * Menangani permintaan ekspor ke Excel.
     * (Metode ini tidak perlu diubah, sudah benar)
     */
    public function export(Request $request)
    {
        // ... (kode export dari sebelumnya sudah benar)
        $request->validate(['tanggal_awal' => 'required|date', 'tanggal_akhir' => 'required|date']);
        $laporanData = $this->getLaporanData($request);
        $exportData = $laporanData->map(
            fn($item) => [
                'TANGGAL PEMERIKSAAN' => $item->tanggal,
                'NAMA PASIEN' => $item->nama_pasien,
                'NO RM' => $item->no_rm,
                'NAMA TINDAKAN' => $item->nama_tindakan,
                'DOKTER (DPJP)' => $item->nama_dokter,
                'JML TINDAKAN' => $item->jumlah,
                'HARGA' => $item->harga,
            ],
        );
        $fileName = 'Laporan_Tindakan_Pasien_' . $request->tanggal_awal . '_sd_' . $request->tanggal_akhir . '.xlsx';
        return Excel::download(new SimpleCollectionExport($exportData), $fileName);
    }

    /**
     * Helper function untuk mengambil data gabungan.
     * (Metode ini tidak perlu diubah, sudah benar)
     */
    private function getLaporanData(Request $request)
    {
        // ... (kode getLaporanData dari sebelumnya sudah benar)
        $startDate = Carbon::parse($request->tanggal_awal)->startOfDay();
        $endDate = Carbon::parse($request->tanggal_akhir)->endOfDay();

        $operasiQuery = DB::table('prosedur_operasi as po')
            ->join('order_operasi as oo', 'po.order_operasi_id', '=', 'oo.id')
            ->join('tindakan_operasi as to', 'po.tindakan_operasi_id', '=', 'to.id')
            ->join('registrations as reg', 'oo.registration_id', '=', 'reg.id')
            ->join('patients as p', 'reg.patient_id', '=', 'p.id')
            ->leftJoin('doctors as d', 'po.dokter_operator_id', '=', 'd.id')
            ->leftJoin('employees as e', 'd.employee_id', '=', 'e.id')
            ->select('oo.tgl_operasi as tanggal', 'p.name as nama_pasien', 'p.medical_record_number as no_rm', 'to.nama_operasi as nama_tindakan', 'e.fullname as nama_dokter', 'reg.registration_type as tipe_rawat', 'po.dokter_operator_id as dokter_id', 'reg.id as registration_id')
            ->whereBetween('oo.tgl_operasi', [$startDate, $endDate])
            ->whereNull('po.deleted_at');

        $persalinanQuery = DB::table('order_persalinan as op')
            ->join('persalinan as per', 'op.persalinan_id', '=', 'per.id')
            ->join('registrations as reg', 'op.registration_id', '=', 'reg.id')
            ->join('patients as p', 'reg.patient_id', '=', 'p.id')
            ->leftJoin('doctors as d', 'op.dokter_bidan_operator_id', '=', 'd.id')
            ->leftJoin('employees as e', 'd.employee_id', '=', 'e.id')
            ->select('op.tgl_persalinan as tanggal', 'p.name as nama_pasien', 'p.medical_record_number as no_rm', 'per.nama_persalinan as nama_tindakan', 'e.fullname as nama_dokter', 'reg.registration_type as tipe_rawat', 'op.dokter_bidan_operator_id as dokter_id', 'reg.id as registration_id')
            ->whereBetween('op.tgl_persalinan', [$startDate, $endDate])
            ->whereNull('op.deleted_at');

        $tindakanMedisQuery = DB::table('order_tindakan_medis as otm')
            ->join('tindakan_medis as tm', 'otm.tindakan_medis_id', '=', 'tm.id')
            ->join('registrations as reg', 'otm.registration_id', '=', 'reg.id')
            ->join('patients as p', 'reg.patient_id', '=', 'p.id')
            ->select('otm.created_at as tanggal', 'p.name as nama_pasien', 'p.medical_record_number as no_rm', 'tm.nama_tindakan as nama_tindakan', DB::raw('NULL as nama_dokter'), 'reg.registration_type as tipe_rawat', DB::raw('NULL as dokter_id'), 'reg.id as registration_id')
            ->whereBetween('otm.created_at', [$startDate, $endDate])
            ->whereNull('otm.deleted_at');

        $unionQuery = $operasiQuery->unionAll($persalinanQuery)->unionAll($tindakanMedisQuery);
        $finalQuery = DB::query()->fromSub($unionQuery, 'tindakan')->orderBy('tanggal', 'desc');

        if ($request->filled('tipe_rawat')) {
            $finalQuery->where('tipe_rawat', $request->tipe_rawat);
        }
        if ($request->filled('dokter_id')) {
            $finalQuery->where('dokter_id', $request->dokter_id);
        }

        $results = $finalQuery->get();
        $results->transform(function ($item) {
            $tagihan = DB::table('tagihan_pasien')
                ->where('registration_id', $item->registration_id)
                ->where('tagihan', 'like', '%' . $item->nama_tindakan . '%')
                ->first();
            $item->harga = $tagihan->wajib_bayar ?? 0;
            $item->jumlah = $tagihan->quantity ?? 1;
            $item->tanggal = Carbon::parse($item->tanggal)->format('d M Y H:i:s');
            return $item;
        });

        return $results;
    }
}
