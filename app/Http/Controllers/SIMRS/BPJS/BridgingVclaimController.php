<?php

namespace App\Http\Controllers\SIMRS\BPJS;

use App\Http\Controllers\Controller;
use App\Models\BPJS\BpjsDataSepInternal;
use App\Models\BPJS\BpjsDataSuratKontrol;
use App\Models\BPJS\BpjsRujukanKhusus;
use App\Models\BPJS\SepApproval;
use App\Models\SIMRS\Departement;
use App\Models\SIMRS\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BridgingVclaimController extends Controller
{
    public function listRegistrasiSEP()
    {
        $departements = Departement::orderBy('name')->get();
        return view('app-type.simrs.bpjs.bridging-vclaim.list-registrasi-sep', [
            'departements' => $departements
        ]);
    }

    public function listDataSEP(Request $request)
    {
        // Query dasar dengan LEFT JOIN ke tabel SEP
        $query = Registration::query()
            ->join('patients', 'registrations.patient_id', '=', 'patients.id')
            ->join('departements', 'registrations.departement_id', '=', 'departements.id')
            ->leftJoin('bpjs_seps', 'registrations.id', '=', 'bpjs_seps.registration_id');

        $recordsTotal = $query->count();

        // --- Terapkan Filter Pencarian ---
        $query->when($request->tgl1 && $request->tgl2, function ($q) use ($request) {
            // Gunakan kolom 'date' dari skema registrations baru Anda
            $startDate = Carbon::createFromFormat('d-m-Y', $request->tgl1)->toDateString();
            $endDate = Carbon::createFromFormat('d-m-Y', $request->tgl2)->toDateString();
            return $q->whereBetween('registrations.date', [$startDate, $endDate]);
        });

        $query->when($request->layanan, function ($q, $layanan) {
            // Gunakan kolom 'registration_type'
            $regType = ($layanan === 'f') ? 'RAWAT JALAN' : 'RAWAT INAP'; // Sesuaikan valuenya
            return $q->where('registrations.registration_type', $regType);
        });

        $query->when($request->did, function ($q, $did) {
            return $q->where('registrations.departement_id', $did);
        });

        $query->when($request->nosep, function ($q, $nosep) {
            // Filter berdasarkan kolom di tabel bpjs_seps
            return $q->where('bpjs_seps.sep_number', 'like', "%{$nosep}%");
        });

        $query->when($request->norujuk, function ($q, $norujuk) {
            // Gunakan kolom 'rujukan' dari tabel registrations
            return $q->where('registrations.rujukan', 'like', "%{$norujuk}%");
        });

        $recordsFiltered = $query->count();

        // --- Pagination dan Ordering ---
        $query->skip($request->start)->take($request->length);

        if ($request->has('order')) {
            $orderColumnIndex = $request->order[0]['column'];
            $orderColumnName = $request->columns[$orderColumnIndex]['data'];
            $orderDir = $request->order[0]['dir'];

            $columnMapping = [
                'tglreg' => 'registrations.date',
                'tglsep' => 'bpjs_seps.sep_date', // Sort by sep_date
                'nama_pasien' => 'patients.name',
                'no_reg' => 'registrations.registration_number',
                'departement' => 'departements.name'
            ];

            if (isset($columnMapping[$orderColumnName])) {
                $query->orderBy($columnMapping[$orderColumnName], $orderDir);
            }
        }

        $registrations = $query->get([
            'registrations.id',
            'registrations.date as tglreg',
            'bpjs_seps.sep_date as tglsep', // Ambil dari tabel join
            'patients.medical_record_number',
            'patients.name as patient_name',
            'registrations.registration_number as no_reg',
            'departements.name as departement_name',
            'patients.nomor_penjamin as nokartu',
            'bpjs_seps.sep_number as sep', // Ambil dari tabel join
            'registrations.rujukan as norujukan',
        ]);

        $data = [];
        foreach ($registrations as $reg) {
            $data[] = [
                'id' => $reg->id,
                'tglreg' => Carbon::parse($reg->tglreg)->format('d M Y'),
                'tglsep' => $reg->tglsep ? Carbon::parse($reg->tglsep)->format('d M Y') : '-',
                'nama_pasien' => "[ {$reg->medical_record_number} ] {$reg->patient_name}",
                'no_reg' => $reg->no_reg,
                'departement' => $reg->departement_name,
                'nokartu' => $reg->nokartu,
                'sep' => $reg->sep ?? '-',
                'norujukan' => $reg->norujukan,
            ];
        }

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    public function detailRegistrasi($id)
    {
        $registration = Registration::with('patient', 'departement')->findOrFail($id);

        // Gunakan kolom dari skema baru
        $detailData = [
            'nama' => $registration->patient->name,
            'jnspeserta' => $registration->patient->penjamin->name ?? 'N/A', // Asumsi ada relasi ke penjamin
            'diagnosa' => $registration->diagnosa_awal ?? 'N/A',
            'kelasrawat' => $registration->kelas_rawat_id ?? 'N/A', // Sesuaikan
            'hakkelas' => $registration->patient->hak_kelas ?? 'N/A',
            'jnspelayanan' => $registration->registration_type,
            'poli' => $registration->departement->name,
        ];

        return response()->json($detailData);
    }

    // Approval SEP
    /**
     * Menampilkan halaman Persetujuan SEP.
     */
    public function persetujuanSEP()
    {
        return view('app-type.simrs.bpjs.bridging-vclaim.persetujuan-sep');
    }

    /**
     * Menyediakan data untuk Server-Side DataTables Persetujuan SEP.
     */
    public function listPersetujuanSEP(Request $request)
    {
        $query = SepApproval::query();

        // Filter
        $query->when($request->tgl1 && $request->tgl2, function ($q) use ($request) {
            $startDate = Carbon::createFromFormat('d-m-Y', $request->tgl1)->startOfDay();
            $endDate = Carbon::createFromFormat('d-m-Y', $request->tgl2)->endOfDay();
            return $q->whereBetween('tglsep', [$startDate, $endDate]);
        });

        $query->when($request->layanan, function ($q, $layanan) {
            $serviceType = ($layanan === 'f') ? 'Rawat Jalan' : 'Rawat Inap';
            return $q->where('jns_pelayanan', $serviceType);
        });

        // Kloning query untuk menghitung total
        $totalQuery = clone $query;
        $recordsTotal = $totalQuery->count();
        $recordsFiltered = $recordsTotal; // Akan sama karena filter diterapkan di query utama

        // Pagination dan Ordering
        $query->skip($request->start)->take($request->length);
        if ($request->has('order')) {
            $orderColumnName = $request->columns[$request->order[0]['column']]['data'];
            $orderDir = $request->order[0]['dir'];
            $query->orderBy($orderColumnName, $orderDir);
        }

        $approvals = $query->get();

        $data = [];
        foreach ($approvals as $approval) {
            $data[] = [
                'id' => $approval->id,
                'nokartu' => $approval->nokartu,
                'jns_pelayanan' => $approval->jns_pelayanan,
                'jnspengajuan' => $approval->jnspengajuan,
                'tglsep' => Carbon::parse($approval->tglsep)->format('d M Y'),
                'keterangan' => $approval->keterangan,
                'status' => $approval->status,
            ];
        }

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    /**
     * Menghapus data persetujuan SEP.
     */
    public function destroyPersetujuan(SepApproval $approval)
    {
        try {
            $approval->delete();
            return response()->json(['success' => true, 'message' => 'Data persetujuan berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus data.'], 500);
        }
    }

    // Rujukan
    /**
     * Menampilkan halaman Rujukan.
     */
    public function rujukan()
    {
        return view('app-type.simrs.bpjs.bridging-vclaim.rujukan');
    }

    /**
     * Menyediakan data untuk Server-Side DataTables Rujukan.
     */
    public function listRujukanData(Request $request)
    {
        // Query dasar dengan LEFT JOIN ke SEP dan RUJUKAN
        $query = Registration::query()
            ->join('patients', 'registrations.patient_id', '=', 'patients.id')
            ->join('bpjs_seps', 'registrations.id', '=', 'bpjs_seps.registration_id') // INNER JOIN karena rujukan butuh SEP
            ->leftJoin('bpjs_rujukans', 'registrations.id', '=', 'bpjs_rujukans.registration_id');

        $recordsTotal = $query->count();

        // --- Terapkan Filter Pencarian ---
        $query->when($request->tgl1 && $request->tgl2, function ($q) use ($request) {
            $startDate = Carbon::createFromFormat('d-m-Y', $request->tgl1)->startOfDay();
            $endDate = Carbon::createFromFormat('d-m-Y', $request->tgl2)->endOfDay();
            // Filter berdasarkan tanggal SEP, karena rujukan dibuat dari SEP
            return $q->whereBetween('bpjs_seps.sep_date', [$startDate, $endDate]);
        });

        $query->when($request->rujuk, function ($q, $statusRujukan) {
            if ($statusRujukan === 'sudah') {
                return $q->whereNotNull('bpjs_rujukans.id');
            }
            if ($statusRujukan === 'belum') {
                return $q->whereNull('bpjs_rujukans.id');
            }
        });

        $query->when($request->nosep, fn($q, $nosep) => $q->where('bpjs_seps.sep_number', 'like', "%{$nosep}%"));
        $query->when($request->norujuk, fn($q, $norujuk) => $q->where('bpjs_rujukans.no_rujukan', 'like', "%{$norujuk}%"));

        $recordsFiltered = $query->count();

        // Pagination dan Ordering
        $query->skip($request->start)->take($request->length);
        if ($request->has('order')) {
            $orderColumnName = $request->columns[$request->order[0]['column']]['data'];
            $orderDir = $request->order[0]['dir'];
            $query->orderBy($orderColumnName, $orderDir);
        }

        $rujukans = $query->get([
            'registrations.id',
            'bpjs_rujukans.tgl_rujukan',
            'bpjs_rujukans.no_rujukan',
            'bpjs_seps.sep_number',
            'patients.nomor_penjamin as nokartu',
            'patients.medical_record_number',
            'patients.name as patient_name',
            'registrations.registration_type',
            'bpjs_rujukans.ppk_dirujuk_nama'
        ]);

        $data = [];
        foreach ($rujukans as $rujukan) {
            $data[] = [
                'id' => $rujukan->id,
                'tglrujukan' => $rujukan->tgl_rujukan ? Carbon::parse($rujukan->tgl_rujukan)->format('d M Y') : '-',
                'norujukan' => $rujukan->no_rujukan ?? '-',
                'nosep' => $rujukan->sep_number,
                'nokartu' => $rujukan->nokartu,
                'nama' => "[ {$rujukan->medical_record_number} ] {$rujukan->patient_name}",
                'rirj' => $rujukan->registration_type,
                'ppk' => $rujukan->ppk_dirujuk_nama ?? '-',
            ];
        }

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    /**
     * Menampilkan halaman Rujukan Khusus.
     */
    public function rujukanKhusus()
    {
        return view('app-type.simrs.bpjs.bridging-vclaim.rujukan-khusus');
    }

    /**
     * Menyediakan data untuk Server-Side DataTables Rujukan Khusus.
     */
    public function listRujukanKhususData(Request $request)
    {
        // Ambil filter dari request
        $filters = [
            'tgl_awal'  => $request->input('tgl1') ? Carbon::createFromFormat('d-m-Y', $request->input('tgl1'))->format('Y-m-d') : now()->format('Y-m-d'),
            'tgl_akhir' => $request->input('tgl2') ? Carbon::createFromFormat('d-m-Y', $request->input('tgl2'))->format('Y-m-d') : now()->format('Y-m-d'),
            'no_rujukan' => $request->input('norujukan')
        ];

        // Panggil metode dari model/service untuk fetch data dari API
        $apiData = BpjsRujukanKhusus::fetchData($filters);

        $recordsTotal = count($apiData);
        $recordsFiltered = $recordsTotal;

        // Terapkan pagination manual pada array hasil API
        $paginatedData = array_slice($apiData, $request->start, $request->length);

        $data = [];
        foreach ($paginatedData as $item) {
            $data[] = [
                'idrujukan'    => $item['idRujukan'],
                'nokartu'      => $item['peserta']['noKartu'],
                'nama_peserta' => $item['peserta']['nama'],
                'norujukan'    => $item['noRujukan'],
                'diagppk'      => $item['ppkRujukan']['diagnosa']['nama'],
                'tglrujukan_awal'    => Carbon::parse($item['tglMulaiRujukan'])->format('d M Y'),
                'tglrujukan_berakhir' => Carbon::parse($item['tglAkhirRujukan'])->format('d M Y'),
            ];
        }

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }


    /**
     * Menampilkan halaman Lembar Pengajuan Klaim.
     */
    public function lembarPengajuanKlaim()
    {
        return view('app-type.simrs.bpjs.bridging-vclaim.lembar-pengajuan-klaim');
    }

    /**
     * Menyediakan data untuk Server-Side DataTables LPK.
     */
    public function listLpkData(Request $request)
    {
        // Query dasar dengan LEFT JOIN ke SEP dan LPK
        $query = Registration::query()
            ->join('patients', 'registrations.patient_id', '=', 'patients.id')
            ->join('bpjs_seps', 'registrations.id', '=', 'bpjs_seps.registration_id') // INNER JOIN karena LPK butuh SEP
            ->leftJoin('bpjs_lpks', 'registrations.id', '=', 'bpjs_lpks.registration_id');

        $recordsTotal = $query->count();

        // --- Terapkan Filter Pencarian ---
        $query->when($request->tgl1 && $request->tgl2, function ($q) use ($request) {
            $startDate = Carbon::createFromFormat('d-m-Y', $request->tgl1)->startOfDay();
            $endDate = Carbon::createFromFormat('d-m-Y', $request->tgl2)->endOfDay();
            // Filter berdasarkan tanggal registrasi
            return $q->whereBetween('registrations.date', [$startDate, $endDate]);
        });

        $query->when($request->lpk, function ($q, $statusLpk) {
            if ($statusLpk === 'sudah') {
                return $q->whereNotNull('bpjs_lpks.id');
            }
            if ($statusLpk === 'belum') {
                return $q->whereNull('bpjs_lpks.id');
            }
        });

        $query->when($request->nosep, fn($q, $nosep) => $q->where('bpjs_seps.sep_number', 'like', "%{$nosep}%"));

        $recordsFiltered = $query->count();

        // Pagination dan Ordering
        $query->skip($request->start)->take($request->length);
        if ($request->has('order')) {
            $orderColumnName = $request->columns[$request->order[0]['column']]['data'];
            $orderDir = $request->order[0]['dir'];
            $query->orderBy($orderColumnName, $orderDir);
        }

        $lpks = $query->get([
            'registrations.id',
            'bpjs_seps.sep_number as nosep',
            'patients.nomor_penjamin as nokartu',
            'patients.name as patient_name',
            'registrations.date as tglmasuk',
            'bpjs_lpks.id as lpk_id' // Untuk mengecek apakah LPK sudah ada
        ]);

        $data = [];
        foreach ($lpks as $lpk) {
            $data[] = [
                'id' => $lpk->id,
                'nosep' => $lpk->nosep,
                'nokartu' => $lpk->nokartu,
                'nama' => $lpk->patient_name,
                'tglmasuk' => Carbon::parse($lpk->tglmasuk)->format('d M Y'),
                'lpk_id' => $lpk->lpk_id,
            ];
        }

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    /**
     * Menampilkan halaman Rencana Kontrol Pasien.
     */
    public function rencanaKontrol()
    {
        // Ganti nama rute view jika Anda menyimpannya di lokasi lain
        return view('app-type.simrs.bpjs.bridging-vclaim.rencana-kontrol');
    }

    /**
     * Menampilkan halaman SPRI (Surat Pengantar Rawat Inap).
     *
     * Halaman ini menggunakan logika dan data yang sama dengan Rencana Kontrol,
     * hanya dibedakan berdasarkan jenis_kontrol = 1 (SPRI).
     */
    public function spri()
    {
        // Ganti nama rute view jika Anda menyimpannya di lokasi lain
        return view('app-type.simrs.bpjs.bridging-vclaim.spri');
    }

    /**
     * Menyediakan data untuk Server-Side DataTables Rencana Kontrol dan SPRI.
     * Sudah diubah untuk menerima parameter 'jenis_kontrol'
     */
    public function listRencanaKontrolData(Request $request)
    {
        $query = Registration::query()
            ->join('patients', 'registrations.patient_id', '=', 'patients.id')
            ->join('departements', 'registrations.departement_id', '=', 'departements.id')
            ->join('bpjs_seps', 'registrations.id', '=', 'bpjs_seps.registration_id')
            ->leftJoin('bpjs_rencana_kontrols', 'registrations.id', '=', 'bpjs_rencana_kontrols.registration_id');

        $recordsTotal = $query->count();

        // --- Terapkan Filter Pencarian ---
        $query->when($request->tgl1 && $request->tgl2, function ($q) use ($request) {
            $startDate = Carbon::createFromFormat('d-m-Y', $request->tgl1)->startOfDay();
            $endDate = Carbon::createFromFormat('d-m-Y', $request->tgl2)->endOfDay();
            return $q->whereBetween('registrations.date', [$startDate, $endDate]);
        });

        $query->when($request->show_rencana_kontrol, function ($q, $status) {
            if ($status === 'sudah') {
                return $q->whereNotNull('bpjs_rencana_kontrols.id');
            }
            if ($status === 'belum') {
                return $q->whereNull('bpjs_rencana_kontrols.id');
            }
        });

        $query->when($request->nosep, fn($q, $nosep) => $q->where('bpjs_seps.sep_number', 'like', "%{$nosep}%"));
        $query->when($request->no_surat_kontrol, fn($q, $no) => $q->where('bpjs_rencana_kontrols.no_surat_kontrol', 'like', "%{$no}%"));

        // !! PENYESUAIAN PENTING UNTUK SPRI !!
        // Filter berdasarkan jenis kontrol (1 untuk SPRI, 2 untuk Surat Kontrol)
        $query->when($request->jenis_kontrol, fn($q, $jenis) => $q->where('bpjs_rencana_kontrols.jenis_kontrol', $jenis));

        $recordsFiltered = $query->count();

        // ... (sisa logika sama persis dengan sebelumnya)
        $query->skip($request->start)->take($request->length);
        if ($request->has('order')) {
            $orderColumnName = $request->columns[$request->order[0]['column']]['data'];
            $orderDir = $request->order[0]['dir'];
            $query->orderBy($orderColumnName, $orderDir);
        }

        $list = $query->get([
            'registrations.id',
            'patients.nomor_penjamin as nokartu',
            'patients.name as patient_name',
            'patients.medical_record_number',
            'registrations.registration_number as noreg',
            'departements.name as department_name',
            'bpjs_seps.sep_number as nosep',
            'bpjs_rencana_kontrols.tgl_rencana_kontrol',
            'bpjs_rencana_kontrols.no_surat_kontrol',
            'bpjs_rencana_kontrols.poli_kontrol_nama',
        ]);

        $data = [];
        foreach ($list as $item) {
            $data[] = [
                'id' => $item->id,
                'nokartu' => $item->nokartu,
                'nama' => "[ {$item->medical_record_number} ] {$item->patient_name}",
                'noreg' => $item->noreg,
                'name_formal' => $item->department_name,
                'nosep' => $item->nosep,
                'tgl_rencana_kontrol' => $item->tgl_rencana_kontrol ? Carbon::parse($item->tgl_rencana_kontrol)->format('d M Y') : '-',
                'no_surat_kontrol' => $item->no_surat_kontrol ?? '-',
                'poli_kontrol' => $item->poli_kontrol_nama ?? '-',
            ];
        }

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    public function dataSuratKontrol()
    {
        return view('app-type.simrs.bpjs.bridging-vclaim.data-surat-kontrol');
    }

    public function listDataSuratKontrol(Request $request)
    {
        $filters = [
            'format_filter' => $request->input('filtnoka') ? '2' : '1', // 1=Tgl Entri/Kontrol, 2=No.Kartu
            'tgl_awal'  => $request->input('tgl_awal') ? Carbon::createFromFormat('d-m-Y', $request->input('tgl_awal'))->format('Y-m-d') : null,
            'tgl_akhir' => $request->input('tgl_akhir') ? Carbon::createFromFormat('d-m-Y', $request->input('tgl_akhir'))->format('Y-m-d') : null,
            'bulan'     => $request->input('bulan'),
            'tahun'     => $request->input('tahun'),
            'noka'      => $request->input('noka'),
        ];

        $apiData = BpjsDataSuratKontrol::fetchData($filters);

        $recordsTotal = count($apiData);
        $recordsFiltered = $recordsTotal;

        $paginatedData = array_slice($apiData, $request->start, $request->length);

        $data = [];
        foreach ($paginatedData as $item) {
            $data[] = [
                'noSuratKontrol' => $item['noSuratKontrol'],
                'jnsPelayanan' => $item['jnsPelayanan'],
                'namaJnsKontrol' => $item['namaJnsKontrol'],
                'tglRencanaKontrol' => Carbon::parse($item['tglRencanaKontrol'])->format('d M Y'),
                'tglTerbitKontrol' => Carbon::parse($item['tglTerbitKontrol'])->format('d M Y'),
                'namaDokter' => $item['namaDokter'],
                'noKartu' => $item['noKartu'],
                'nama' => $item['nama'],
                'noSepAsalKontrol' => $item['noSepAsalKontrol'],
                'namaPoliAsal' => $item['namaPoliAsal'],
                'namaPoliTujuan' => $item['namaPoliTujuan'],
                'tglSEP' => $item['tglSEP'] ? Carbon::parse($item['tglSEP'])->format('d M Y') : '-',
            ];
        }

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    /**
     * Menampilkan halaman Detail SEP.
     */
    public function detailSEP()
    {
        return view('app-type.simrs.bpjs.bridging-vclaim.detail-sep');
    }

    /**
     * Mengambil data detail SEP dari API VClaim.
     */
    public function getDetailSepData(Request $request)
    {
        $request->validate(['sep' => 'required']);
        $nomorSep = $request->sep;

        // =================================================================================
        // !! PENTING !!
        // Di sini Anda akan menempatkan logika panggilan API sesungguhnya ke BPJS.
        // Endpoint: SEP/{noSEP}
        // $apiResponse = ... (hasil panggilan API)
        // =================================================================================

        // Simulasi respons API SUKSES
        $apiResponse = [
            "response" => [
                "catatan" => "Eks KLL",
                "diagnosa" => "C50.9 - Malignant neoplasm of breast, unspecified",
                "jnsPelayanan" => "Rawat Inap",
                "kelasRawat" => "Kelas 1",
                "noSep" => $nomorSep,
                "penjamin" => "Jasa raharja PT",
                "peserta" => [
                    "asuransi" => null,
                    "hakKelas" => "Kelas 1",
                    "jnsPeserta" => "PNS Pusat",
                    "kelamin" => "P",
                    "nama" => "SITI ROHMAH",
                    "noKartu" => "0001111222233",
                    "noMr" => "123456",
                    "tglLahir" => "1977-10-10"
                ],
                "poli" => "",
                "poliEksekutif" => "0",
                "tglSep" => "2025-09-15"
            ],
            "metaData" => ["code" => "200", "message" => "Sukses"]
        ];

        // Setelah mendapatkan data dari VClaim, cari data registrasi lokal berdasarkan nomor rekam medis
        $dataLokal = Registration::whereHas('patient', function ($query) use ($apiResponse) {
            $query->where('medical_record_number', $apiResponse['response']['peserta']['noMr']);
        })
            ->where('status', 'aktif') // Asumsi ada status 'aktif' untuk registrasi yang berjalan
            ->latest('date')
            ->first();

        // Gabungkan data dari API dan data lokal
        $responseData = array_merge($apiResponse['response'], [
            'nama_pasien_simrs' => $dataLokal->patient->name ?? '-',
            'noreg_simrs'       => $dataLokal->registration_number ?? '-',
            'tgl_reg_simrs'     => $dataLokal ? Carbon::parse($dataLokal->date)->format('d M Y') : '-',
            'pregid_simrs'      => $dataLokal->id ?? null,
        ]);

        return response()->json([
            'metaData' => $apiResponse['metaData'],
            'response' => $responseData
        ]);
    }

    /**
     * Menghapus SEP dari API VClaim.
     */
    public function deleteSepData(Request $request)
    {
        $request->validate(['sep' => 'required']);

        // =================================================================================
        // !! PENTING !!
        // Di sini Anda akan menempatkan logika panggilan API DELETE ke BPJS.
        // Endpoint: SEP/Delete
        // Body: { "request": { "t_sep": { "noSep": "...", "user": "..." } } }
        // $apiResponse = ... (hasil panggilan API)
        // =================================================================================

        // Simulasi respons sukses dari API
        $apiResponse = ["metaData" => ["code" => "200", "message" => "Sukses"]];

        if ($apiResponse['metaData']['code'] == '200') {
            return response()->json(['success' => true, 'message' => 'SEP berhasil dihapus dari server BPJS.']);
        } else {
            return response()->json(['success' => false, 'message' => $apiResponse['metaData']['message']], 400);
        }
    }

    public function dataSepInternal()
    {
        return view('app-type.simrs.bpjs.bridging-vclaim.data-sep-internal');
    }

    public function listDataSepInternal(Request $request)
    {
        $nomorSep = $request->input('nosep');

        if (!$nomorSep) {
            return response()->json(['data' => []]); // Kirim data kosong jika tidak ada No SEP
        }

        $apiResponse = BpjsDataSepInternal::fetchData($nomorSep);

        $data = [];
        if ($apiResponse['metaData']['code'] == '200' && isset($apiResponse['response']['list'])) {
            $data = $apiResponse['response']['list'];
        }

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => count($data),
            'recordsFiltered' => count($data),
            'data' => $data,
        ]);
    }

    public function deleteSepInternal(Request $request)
    {
        $request->validate([
            'nosep' => 'required',
            'idrujuk_internal' => 'required',
            'tglrujukinternal' => 'required',
        ]);

        // =================================================================================
        // !! PENTING !!
        // Di sini Anda akan menempatkan logika panggilan API DELETE ke BPJS.
        // Endpoint: SEP/Internal/Delete
        // Body: { "request": { "t_sep": { "noSep": "...", "noSurat": "...", "tglRujukanInternal": "...", "user": "..." } } }
        // 'noSurat' adalah 'idrujuk_internal'
        // =================================================================================

        // Simulasi respons sukses
        return response()->json(['success' => true, 'message' => 'Rujukan Internal berhasil dihapus.']);
    }
}
