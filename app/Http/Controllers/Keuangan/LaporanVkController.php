<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\KelasRawat;
use App\Models\SIMRS\Persalinan\OrderPersalinan;
use App\Models\SIMRS\Persalinan\TarifPersalinan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Exports\SimpleCollectionExport; // Pastikan Anda memiliki file ini
use Maatwebsite\Excel\Facades\Excel;

class LaporanVkController extends Controller
{
    /**
     * HANYA menampilkan halaman filter untuk Laporan VK.
     */
    public function index(Request $request)
    {
        $listKelasRawat = KelasRawat::orderBy('kelas')->get();
        return view('app-type.keuangan.laporanpendukung.vk.index', [
            'listKelasRawat' => $listKelasRawat,
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
            'kelas_rawat_id' => 'nullable|exists:kelas_rawat,id'
        ]);

        $laporanData = $this->getLaporanData($request);

        $kelasRawatNama = 'Semua Kelas Rawat';
        if ($request->filled('kelas_rawat_id')) {
            $kelas = KelasRawat::find($request->kelas_rawat_id);
            $kelasRawatNama = $kelas ? $kelas->kelas : 'N/A';
        }

        // =========================================================================
        // PERBAIKAN UTAMA ADA DI SINI: Arahkan ke view 'print', bukan 'index'
        // =========================================================================
        return view('app-type.keuangan.laporanpendukung.vk.print', [
            'laporanData' => $laporanData,
            'tanggalAwal' => Carbon::parse($request->tanggal_awal)->format('d-m-Y'),
            'tanggalAkhir' => Carbon::parse($request->tanggal_akhir)->format('d-m-Y'),
            'kelasRawatNama' => $kelasRawatNama
        ]);
    }

    /**
     * [BARU] Menangani permintaan ekspor Excel.
     */
    public function export(Request $request)
    {
        $request->validate([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
        ]);

        $laporanData = $this->getLaporanData($request);

        $exportData = [];
        // Ubah data menjadi format datar untuk Excel
        foreach ($laporanData as $transaksi) {
            if (empty($transaksi['kru'])) continue;
            foreach ($transaksi['kru'] as $kru) {
                $exportData[] = [
                    'TANGGAL' => $transaksi['tanggal'],
                    'NO RM' => $transaksi['no_rm'],
                    'NAMA PASIEN' => $transaksi['nama_pasien'],
                    'KELAS' => $transaksi['kelas'],
                    'TINDAKAN' => $transaksi['tindakan'],
                    'KRU VK' => $kru['nama'],
                    'HARGA' => $kru['harga'],
                ];
            }
        }

        $fileName = 'Laporan_VK_' . $request->tanggal_awal . '_sd_' . $request->tanggal_akhir . '.xlsx';
        return Excel::download(new SimpleCollectionExport(collect($exportData)), $fileName);
    }

    /**
     * Helper function untuk mengambil dan memproses data laporan.
     * (Nama function diubah dari getLaporanVkData menjadi getLaporanData)
     */
    private function getLaporanData(Request $request)
    {
        $start_date = Carbon::parse($request->tanggal_awal)->startOfDay();
        $end_date = Carbon::parse($request->tanggal_akhir)->endOfDay();

        $query = OrderPersalinan::with([
            'registration.patient',
            'registration.penjamin.group_penjamin',
            'kelasRawat',
            'persalinan',
            'dokterBidan.employee:id,fullname',
            'asistenOperator.employee:id,fullname',
            'dokterAnestesi.employee:id,fullname',
            'asistenAnestesi.employee:id,fullname',
            'dokterResusitator.employee:id,fullname',
            'dokterUmum.employee:id,fullname'
        ])
            ->whereBetween('tgl_persalinan', [$start_date, $end_date])
            ->orderBy('tgl_persalinan', 'desc');

        if ($request->filled('kelas_rawat_id')) {
            $query->where('kelas_rawat_id', $request->kelas_rawat_id);
        }

        $orders = $query->get();
        // Sisa logika di sini sudah benar...
        $groupedData = [];
        foreach ($orders as $order) {
            if (!$order->registration?->patient || !$order->registration?->penjamin?->group_penjamin_id) continue;
            $tarif = TarifPersalinan::where(['persalinan_id' => $order->persalinan_id, 'kelas_rawat_id' => $order->kelas_rawat_id, 'group_penjamin_id' => $order->registration->penjamin->group_penjamin_id])->first();
            if (!$tarif) continue;
            $kru = [];
            $roles = [['petugas' => $order->dokterBidan, 'nama' => '(DOKTER/BIDAN)', 'components' => ['operator_dokter', 'operator_rs', 'operator_prasarana']], ['petugas' => $order->dokterResusitator, 'nama' => '(DR RESUSITATOR)', 'components' => ['resusitator_dokter', 'resusitator_rs']], ['petugas' => $order->dokterAnestesi, 'nama' => '(DR ANESTESI)', 'components' => ['anastesi_dokter', 'anastesi_rs']], ['petugas' => $order->asistenOperator, 'nama' => '(ASISTEN OP)', 'components' => ['ass_operator_dokter', 'ass_operator_rs']], ['petugas' => $order->asistenAnestesi, 'nama' => '(ASISTEN ANESTESI)', 'components' => ['ass_anastesi_dokter', 'ass_anastesi_rs']], ['petugas' => $order->dokterUmum, 'nama' => '(DR UMUM)', 'components' => ['umum_dokter', 'umum_rs']]];
            foreach ($roles as $role) {
                if ($role['petugas']?->employee) {
                    $totalHarga = collect($role['components'])->sum(fn($component) => $tarif->{$component} ?? 0);
                    if ($totalHarga > 0) {
                        $kru[] = ['nama' => $role['petugas']->employee->fullname . ' ' . $role['nama'], 'harga' => $totalHarga];
                    }
                }
            }
            if (!empty($kru)) {
                $groupedData[$order->id] = ['tanggal' => Carbon::parse($order->tgl_persalinan)->format('d M Y H:i:s'), 'no_rm' => $order->registration->patient->medical_record_number, 'nama_pasien' => $order->registration->patient->name, 'kelas' => $order->kelasRawat?->kelas, 'tindakan' => $order->persalinan?->nama_persalinan, 'kru' => $kru];
            }
        }
        return $groupedData;
    }
}
