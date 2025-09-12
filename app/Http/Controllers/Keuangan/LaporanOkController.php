<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\SIMRS\KelasRawat;
use App\Models\SIMRS\Operasi\ProsedurOperasi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SimpleCollectionExport; // Pastikan Anda memiliki export class ini

class LaporanOkController extends Controller
{
    /**
     * Menampilkan halaman filter Laporan OK.
     */
    public function index(Request $request)
    {
        $kelas_rawat_list = KelasRawat::orderBy('kelas')->get();
        return view('app-type.keuangan.laporanpendukung.ok.index', [
            'kelas_rawat_list' => $kelas_rawat_list,
        ]);
    }

    /**
     * Handle the Excel export request.
     * Disesuaikan dengan relasi model yang baru.
     */
    public function export(Request $request)
    {
        $request->validate([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
        ]);

        $fileName = 'Laporan_Operasi_' . $request->tanggal_awal . '_sd_' . $request->tanggal_akhir . '.xlsx';

        // PERUBAHAN: Query dimulai dari ProsedurOperasi dan menggunakan relasi 'orderOperasi'
        $query = ProsedurOperasi::query()->with([
            'orderOperasi.registration.patient',
            'orderOperasi.registration.penjamin',
            'orderOperasi.kelasRawat',
            'tindakanOperasi', // Asumsi relasi ini ada dan benar
            'dokterOperator.employee',
            'dokterAnestesi.employee',
            'assDokterOperator.employee'
        ])
            ->whereHas('orderOperasi', function ($q) use ($request) {
                $q->whereBetween('tgl_operasi', [
                    Carbon::parse($request->tanggal_awal)->startOfDay(),
                    Carbon::parse($request->tanggal_akhir)->endOfDay()
                ]);
                if ($request->filled('kelas_rawat_id')) {
                    $q->where('kelas_rawat_id', $request->kelas_rawat_id);
                }
            })->get();

        $exportData = $query->map(function ($prosedur) {
            // PERUBAHAN: Mengakses data melalui relasi 'orderOperasi'
            return [
                'Tgl Operasi' => Carbon::parse($prosedur->orderOperasi->tgl_operasi)->format('d-m-Y H:i'),
                'No Registrasi' => $prosedur->orderOperasi->registration->registration_number ?? 'N/A',
                'No RM' => $prosedur->orderOperasi->registration->patient->medical_record_number ?? 'N/A',
                'Nama Pasien' => $prosedur->orderOperasi->registration->patient->name ?? 'N/A',
                'Penjamin' => $prosedur->orderOperasi->registration->penjamin->name ?? 'N/A',
                'Kelas Rawat' => $prosedur->orderOperasi->kelasRawat->kelas ?? 'N/A',
                'Nama Tindakan' => $prosedur->tindakanOperasi->nama_operasi ?? 'N/A', // Asumsi nama_operasi ada
                'Dokter Operator' => $prosedur->dokterOperator->employee->fullname ?? 'N/A',
                'Dokter Anestesi' => $prosedur->dokterAnestesi->employee->fullname ?? 'N/A',
                'Asisten Operator' => $prosedur->assDokterOperator->employee->fullname ?? 'N/A',
            ];
        });

        return Excel::download(new SimpleCollectionExport($exportData), $fileName);
    }

    /**
     * Generate the print preview page.
     * Disesuaikan dengan relasi model yang baru.
     */
    public function print(Request $request)
    {
        $request->validate([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
        ]);

        // PERUBAHAN: Tambahkan 'tindakanOperasi.tarif' ke dalam eager loading
        $query = ProsedurOperasi::with([
            'orderOperasi.registration.patient',
            'orderOperasi.kelasRawat',
            'tindakanOperasi.tarif', // <-- MEMUAT SEMUA TARIF UNTUK TINDAKAN INI
            'dokterOperator.employee',
            'assDokterOperator.employee',
            'dokterAnestesi.employee',
            'assDokterAnestesi.employee',
            'dokterResusitator.employee',
            'dokterTambahan.employee',
        ])
            ->whereHas('orderOperasi', function ($q) use ($request) {
                $q->whereBetween('tgl_operasi', [
                    Carbon::parse($request->tanggal_awal)->startOfDay(),
                    Carbon::parse($request->tanggal_akhir)->endOfDay()
                ]);
                if ($request->filled('kelas_rawat_id')) {
                    $q->where('kelas_rawat_id', $request->kelas_rawat_id);
                }
            })
            ->latest('created_at');

        $procedures = $query->get();

        $printData = $procedures->map(function ($proc) {
            $crew = [];
            // Ambil ID kelas rawat dari order
            $kelasRawatId = $proc->orderOperasi->kelas_rawat_id;

            // Cari tarif yang sesuai dengan kelas rawat pasien dari data yang sudah di-load
            $tarifForKelas = null;
            if ($proc->tindakanOperasi && $proc->tindakanOperasi->tarif) {
                $tarifForKelas = $proc->tindakanOperasi->tarif->firstWhere('kelas_rawat_id', $kelasRawatId);
            }

            // Helper function untuk menambahkan kru DENGAN HARGA
            $addCrew = function ($doctor, $role, $harga) use (&$crew) {
                if ($doctor && $doctor->employee) {
                    $crew[] = [
                        'name' => $doctor->employee->fullname,
                        'role' => $role,
                        'price' => $harga ?? 0 // Gunakan harga yang ditemukan, atau 0 jika tidak ada
                    ];
                }
            };

            // PERUBAHAN: Ambil harga dari $tarifForKelas sesuai peran dokter
            $addCrew($proc->dokterOperator, 'Operator', $tarifForKelas->operator_dokter ?? null);
            $addCrew($proc->assDokterOperator, 'Asisten Operator', $tarifForKelas->asisten_operator_1_dokter ?? null);
            $addCrew($proc->dokterAnestesi, 'Anestesi', $tarifForKelas->operator_anastesi_dokter ?? null);
            $addCrew($proc->assDokterAnestesi, 'Asisten Anestesi', $tarifForKelas->asisten_anastesi_1_dokter ?? null);
            $addCrew($proc->dokterResusitator, 'Resusitator', $tarifForKelas->operator_resusitator_dokter ?? null);
            $addCrew($proc->dokterTambahan, 'Dokter Tambahan', $tarifForKelas->dokter_tambahan_1_dokter ?? null);

            if (empty($crew)) {
                $crew[] = ['name' => '-', 'role' => 'N/A', 'price' => 0];
            }

            return (object)[
                'tindakan' => $proc->tindakanOperasi->nama_operasi ?? 'N/A', // Pastikan relasi ini sudah benar
                'tgl_operasi' => Carbon::parse($proc->orderOperasi->tgl_operasi)->format('d M Y H:i:s'),
                'no_rm' => $proc->orderOperasi->registration->patient->medical_record_number ?? 'N/A',
                'nama_pasien' => $proc->orderOperasi->registration->patient->name ?? 'N/A',
                'kelas' => $proc->orderOperasi->kelasRawat->kelas ?? 'N/A',
                'crew' => $crew
            ];
        });

        $kelasRawatName = 'Semua Kelas Rawat';
        if ($request->filled('kelas_rawat_id')) {
            $kelas = KelasRawat::find($request->kelas_rawat_id);
            $kelasRawatName = $kelas ? $kelas->kelas : 'N/A';
        }

        return view('app-type.keuangan.laporanpendukung.ok.print', [
            'printData' => $printData,
            'tanggal_awal' => Carbon::parse($request->tanggal_awal)->format('d-m-Y'),
            'tanggal_akhir' => Carbon::parse($request->tanggal_akhir)->format('d-m-Y'),
            'kelas_rawat_name' => $kelasRawatName,
        ]);
    }
}
