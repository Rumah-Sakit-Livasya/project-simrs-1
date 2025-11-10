<?php

namespace App\Http\Controllers\SIMRS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

// Impor Model yang dibutuhkan (sesuaikan dengan nama model di aplikasi Anda)
use App\Models\SIMRS\kelasRawat;
use App\Models\SIMRS\Penjamin;
use App\Models\SIMRS\Doctor;
use App\Models\SIMRS\Pengkajian\TransferPasienAntarRuangan;
use App\Models\SIMRS\Registration; // Asumsi data laporan dari model ini
use App\Models\SIMRS\Room;

class ReportRanapController extends Controller
{
    /**
     * FUNGSI 1: Menampilkan halaman panel pencarian Laporan Pasien Rawat Inap.
     */
    public function laporanPasienRanapIndex()
    {
        // Ambil data untuk mengisi dropdown di form pencarian
        $data['kelas_rawat'] = kelas_rawat::orderBy('kelas')->get();
        $data['penjamin'] = Penjamin::orderBy('nama_perusahaan')->get();
        $data['dokter'] = Doctor::with('employee')->get()->sortBy('employee.fullname');

        // Alasan keluar bisa berupa array statis atau dari tabel database
        $data['alasan_keluar'] = [
            'PULANG',
            'RUJUK',
            'MENINGGAL',
            'LARI'
        ];

        return view('pages.simrs.IGD.laporan.pasien-ranap.index', $data);
    }

    /**
     * FUNGSI 2: Memproses data dan menampilkan halaman laporan (pop-up).
     */
    public function laporanPasienRanapReport(Request $request)
    {
        // 1. UBAH ATURAN VALIDASI
        $validator = Validator::make($request->all(), [
            'periode_awal' => 'required|date', // 'date' lebih fleksibel, menerima Y-m-d
            'periode_akhir' => 'required|date|after_or_equal:periode_awal',
        ]);

        if ($validator->fails()) {
            // Untuk debugging, Anda bisa tambahkan ini sementara
            // dd($validator->errors()); 
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // 2. UBAH FORMAT CARBON
        $periode_awal = \Carbon\Carbon::createFromFormat('Y-m-d', $request->periode_awal)->startOfDay();
        $periode_akhir = \Carbon\Carbon::createFromFormat('Y-m-d', $request->periode_akhir)->endOfDay();

        // ... sisa kode Anda sama ...

        $query = Registration::query()
            ->with(['patient', 'doctor.employee', 'penjamin', 'room', 'kelas_rawat'])
            ->where('registration_type', 'rawat-inap')
            ->whereBetween('registration_date', [$periode_awal, $periode_akhir]);

        // ... filter lainnya ...

        $results = $query->orderBy('registration_date', 'asc')->get();

        // Data untuk header
        $report_params = [
            'periode_awal'  => $request->periode_awal, // kirim format asli
            'periode_akhir' => $request->periode_akhir,
            'kelas'         => $request->filled('kelas_id') ? kelasRawat::find($request->kelas_id)->kelas : 'Semua Kelas',
            'penjamin'      => $request->filled('penjamin_id') ? Penjamin::find($request->penjamin_id)->nama_perusahaan : 'Semua Penjamin',
            'dokter'        => $request->filled('dokter_id') && Doctor::find($request->dokter_id) ? Doctor::find($request->dokter_id)->employee->fullname : 'Semua Dokter',
        ];

        return view('pages.simrs.IGD.laporan.pasien-ranap.report', [
            'records' => $results,
            'params'  => $report_params
        ]);
    }

    public function laporanPerTanggalIndex()
    {
        // Data untuk dropdown
        $data['kelas_rawat'] = kelasRawat::orderBy('kelas')->get();
        $data['penjamin'] = Penjamin::orderBy('nama_perusahaan')->get();
        $data['dokter'] = Doctor::with('employee')->get()->sortBy('employee.fullname');

        // Data untuk dropdown Bulan dan Tahun
        $data['months'] = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];
        // Ambil 5 tahun ke belakang dan 1 tahun ke depan
        $currentYear = date('Y');
        for ($i = $currentYear - 5; $i <= $currentYear + 1; $i++) {
            $data['years'][$i] = $i;
        }

        // Nama file view disesuaikan
        return view('pages.simrs.IGD.laporan.per-tanggal.index', $data);
    }

    /**
     * FUNGSI: Memproses data dan menampilkan halaman laporan rekap per tanggal (pop-up).
     */
    public function laporanPerTanggalReport(Request $request)
    {
        // Validasi
        $request->validate([
            'month' => 'required|numeric|between:1,12',
            'year' => 'required|numeric|digits:4',
        ]);



        $month = $request->month;
        $year = $request->year;

        // Tentukan tanggal awal dan akhir dari bulan yang dipilih
        $startDate = \Carbon\Carbon::create($year, $month, 1)->startOfDay();
        $endDate = $startDate->copy()->endOfMonth();
        $daysInMonth = $startDate->daysInMonth;

        // Query dasar
        $query = Registration::query()
            ->where('registration_type', 'rawat-inap')
            ->whereBetween('registration_date', [$startDate, $endDate]);

        // Terapkan filter opsional
        if ($request->filled('kelas_id')) {
            $query->where('kelas_rawat_id', $request->kelas_id);
        }
        if ($request->filled('penjamin_id')) {
            $query->where('penjamin_id', $request->penjamin_id);
        }
        if ($request->filled('dokter_id')) {
            $query->where('doctor_id', $request->dokter_id);
        }

        $registrations = $query->with('kelas_rawat')->get();

        // --- Proses data mentah menjadi format rekapitulasi (pivot) ---
        $reportData = [];
        foreach ($registrations as $reg) {
            $kelasId = $reg->kelas_rawat_id;
            $kelasNama = $reg->kelas_rawat->kelas ?? 'Tanpa Kelas';
            $day = \Carbon\Carbon::parse($reg->registration_date)->day;

            // Inisialisasi jika kelas ini baru pertama kali muncul
            if (!isset($reportData[$kelasId])) {
                $reportData[$kelasId] = [
                    'nama_kelas' => $kelasNama,
                    'counts' => array_fill(1, $daysInMonth, 0), // Buat array [1=>0, 2=>0, ...]
                ];
            }
            // Tambahkan hitungan untuk tanggal yang sesuai
            $reportData[$kelasId]['counts'][$day]++;
        }

        // Data untuk ditampilkan di header laporan
        $params = [
            'month_name' => $startDate->isoFormat('MMMM'),
            'year'       => $year,
            'kelas'      => $request->filled('kelas_id') ? kelas_rawat::find($request->kelas_id)->kelas : 'Semua Kelas',
            'penjamin'   => $request->filled('penjamin_id') ? Penjamin::find($request->penjamin_id)->nama_perusahaan : 'Semua Penjamin',
            'dokter'     => $request->filled('dokter_id') && ($d = Doctor::find($request->dokter_id)) ? $d->employee->fullname : 'Semua Dokter',
        ];

        return view('pages.simrs.IGD.laporan.per-tanggal.report', [
            'records' => $reportData,
            'params' => $params,
            'daysInMonth' => $daysInMonth,
        ]);
    }

    public function laporanTransferIndex()
    {
        // Data untuk dropdown Kelas
        $data['kelas_rawat'] = KelasRawat::orderBy('kelas')->get();

        // Nama file view disesuaikan
        return view('pages.simrs.IGD.laporan.transfer.index', $data);
    }

    /**
     * FUNGSI: Memproses data dan menampilkan halaman laporan transfer (pop-up).
     */
    public function laporanTransferReport(Request $request)
    {
        // Validasi input
        $request->validate([
            'periode_awal' => 'required|date',
            'periode_akhir' => 'required|date|after_or_equal:periode_awal',
        ]);

        // Konversi format tanggal
        $periode_awal = \Carbon\Carbon::parse($request->periode_awal)->startOfDay();
        $periode_akhir = \Carbon\Carbon::parse($request->periode_akhir)->endOfDay();

        // Query dasar ke tabel transfer
        $query = TransferPasienAntarRuangan::query()
            ->with([ // Eager loading relasi yang dibutuhkan
                'registration.patient',
                'registration.penjamin',
                'ruangan_asal',
                'ruangan_tujuan',
                'user'
            ])
            ->whereBetween('created_at', [$periode_awal, $periode_akhir]);

        // Terapkan filter opsional
        if ($request->filled('kelas_id')) {
            // Filter berdasarkan kelas asal ATAU kelas tujuan
            $query->where(function ($q) use ($request) {
                $q->whereHas('ruangan_asal', function ($subq) use ($request) {
                    $subq->where('kelas_rawat_id', $request->kelas_id);
                })->orWhereHas('ruangan_tujuan', function ($subq) use ($request) {
                    $subq->where('kelas_rawat_id', $request->kelas_id);
                });
            });
        }

        if ($request->filled('no_rm_nama')) {
            $searchTerm = $request->no_rm_nama;
            $query->whereHas('registration.patient', function ($q) use ($searchTerm) {
                $q->where('medical_record_number', 'like', "%$searchTerm%")
                    ->orWhere('name', 'like', "%$searchTerm%");
            });
        }

        $results = $query->orderBy('created_at', 'asc')->get();

        // Data untuk ditampilkan di header laporan
        $params = [
            'periode_awal'  => $request->periode_awal,
            'periode_akhir' => $request->periode_akhir,
            'kelas'         => $request->filled('kelas_id') ? KelasRawat::find($request->kelas_id)->kelas : 'Semua Kelas',
            'no_rm_nama'    => $request->no_rm_nama ?? 'Semua Pasien',
        ];

        return view('pages.simrs.IGD.laporan.transfer.report', [
            'records' => $results,
            'params'  => $params,
        ]);
    }

    public function laporanPasienAktifIndex()
    {
        // Data untuk dropdown filter
        $data['kelas_rawat'] = KelasRawat::orderBy('kelas')->get();
        $data['dokter'] = Doctor::with('employee')->get()->sortBy('employee.fullname');

        // Error 'jenis_ruangan' sebelumnya terjadi di sini. Query ini sudah dihapus
        // karena panel pencarian baru tidak memerlukan filter ruangan.

        return view('pages.simrs.IGD.laporan.pasien-aktif.index', $data);
    }

    /**
     * FUNGSI: Memproses data dan menampilkan halaman laporan pasien aktif (pop-up).
     */
    public function laporanPasienAktifReport(Request $request)
    {
        // Validasi, sekarang menyertakan tanggal
        $request->validate([
            'periode_awal' => 'required|date',
            'periode_akhir' => 'required|date|after_or_equal:periode_awal',
        ]);

        $periode_awal = \Carbon\Carbon::parse($request->periode_awal)->startOfDay();
        $periode_akhir = \Carbon\Carbon::parse($request->periode_akhir)->endOfDay();

        // Query dasar untuk pasien yang aktif dan merupakan rawat inap
        $query = Registration::query()
            ->with([
                'patient',
                'penjamin',
                'doctor.employee',
                'kelas_rawat',
                'room',
                'patient.bed' // Eager load relasi bed dari patient
            ])
            ->where('registration_type', 'rawat-inap')
            ->where('status', 'aktif');

        // Terapkan filter tanggal pada tanggal registrasi
        $query->whereBetween('registration_date', [$periode_awal, $periode_akhir]);

        // Terapkan filter opsional lainnya
        if ($request->filled('kelas_id')) {
            $query->where('kelas_rawat_id', $request->kelas_id);
        }
        if ($request->filled('dokter_id')) {
            $query->where('doctor_id', $request->dokter_id);
        }
        if ($request->filled('no_rm_nama')) {
            $searchTerm = $request->no_rm_nama;
            $query->whereHas('patient', function ($q) use ($searchTerm) {
                $q->where('medical_record_number', 'like', "%$searchTerm%")
                    ->orWhere('name', 'like', "%$searchTerm%");
            });
        }

        $results = $query->orderBy('registration_date', 'asc')->get();

        // Data untuk ditampilkan di header laporan
        $params = [
            'periode_awal'  => $request->periode_awal,
            'periode_akhir' => $request->periode_akhir,
            'kelas'         => $request->filled('kelas_id') ? KelasRawat::find($request->kelas_id)->kelas : 'Semua Kelas',
            'dokter'        => $request->filled('dokter_id') && ($d = Doctor::find($request->dokter_id)) ? $d->employee->fullname : 'Semua Dokter',
        ];

        return view('pages.simrs.IGD.laporan.pasien-aktif.report', [
            'records' => $results,
            'params'  => $params,
        ]);
    }
}
