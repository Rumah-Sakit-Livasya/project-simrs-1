<?php

namespace App\Http\Controllers\SIMRS\Laboratorium;

use App\Exports\LaboratoriumTarifExport;
use App\Http\Controllers\Controller;
use App\Imports\LaboratoriumTarifImport;
use App\Models\Employee;
use App\Models\OrderParameterLaboratorium;
use App\Models\SIMRS\Doctor;
use App\Models\SIMRS\GroupPenjamin;
use App\Models\SIMRS\KelasRawat;
use App\Models\SIMRS\Laboratorium\KategoriLaboratorium;
use App\Models\SIMRS\Laboratorium\NilaiNormalLaboratorium;
use App\Models\SIMRS\Laboratorium\OrderLaboratorium;
use App\Models\SIMRS\Laboratorium\ParameterLaboratorium;
use App\Models\SIMRS\Laboratorium\TarifParameterLaboratorium;
use App\Models\SIMRS\Penjamin;
use App\Models\SIMRS\Registration;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LaboratoriumController extends Controller
{
    public function order()
    {
        $laboratoriumDoctors = Doctor::whereHas('department_from_doctors', function ($query) {
            $query->where('name', 'like', '%lab%');
        })->get();
        return view('pages.simrs.laboratorium.order', [
            'laboratoriumDoctors' => $laboratoriumDoctors,
            'penjamins' => Penjamin::all(),
            'kelas_rawats' => KelasRawat::all(),
            'laboratorium_categories' => KategoriLaboratorium::all(),
            'tarifs' => TarifParameterLaboratorium::all(),
        ]);
    }

    public function notaOrder($id)
    {
        $order = OrderLaboratorium::findOrFail($id);
        return view('pages.simrs.laboratorium.partials.nota-order', [
            'order' => $order
        ]);
    }

    public function labelOrder($id)
    {
        $order = OrderLaboratorium::findOrFail($id);
        $order->load(['registration', 'registration_otc', 'registration_otc.doctor']);
        return view('pages.simrs.laboratorium.partials.label-order', [
            'order' => $order
        ]);
    }

    public function hasilOrder($id)
    {
        $order = OrderLaboratorium::findOrFail($id);
        $order->load(['registration', 'registration_otc', 'registration_otc.doctor']);
        // dd($order->doctor->employee->fullname);
        return view('pages.simrs.laboratorium.partials.hasil-order', [
            'order' => $order,
            'nilai_normals' => NilaiNormalLaboratorium::all()
        ]);
    }

    public function index(Request $request)
    {
        // 1. Eager Loading Komprehensif
        $query = OrderLaboratorium::query()->with([
            'registration.patient.penjamin',
            'registration_otc.penjamin',
            'doctor.employee',
            'order_parameter_laboratorium.parameter_laboratorium' // Untuk child row
        ]);

        // 2. Terapkan Filter Secara Kondisional

        // Filter berdasarkan rentang tanggal dari input
        if ($request->filled('registration_date')) {
            $dateRange = explode(' - ', $request->registration_date);
            if (count($dateRange) === 2) {
                $startDate = Carbon::parse($dateRange[0])->startOfDay();
                $endDate = Carbon::parse($dateRange[1])->endOfDay();
                $query->whereBetween('order_date', [$startDate, $endDate]);
            }
        } else {
            // *** INI BAGIAN PENTINGNYA ***
            // Jika tidak ada input tanggal, default ke hari ini
            $query->whereDate('order_date', today());
        }

        // Filter berdasarkan No. Order
        $query->when($request->filled('no_order'), function ($q) use ($request) {
            $q->where('no_order', 'like', '%' . $request->no_order . '%');
        });

        // Filter berdasarkan Nama Pasien (universal)
        $query->when($request->filled('name'), function ($q) use ($request) {
            $q->where(function ($subQuery) use ($request) {
                $subQuery->whereHas('registration.patient', function ($patientQuery) use ($request) {
                    $patientQuery->where('name', 'like', '%' . $request->name . '%');
                })->orWhereHas('registration_otc', function ($otcQuery) use ($request) {
                    $otcQuery->where('nama_pasien', 'like', '%' . $request->name . '%');
                });
            });
        });

        // Filter berdasarkan No. Registrasi (universal)
        $query->when($request->filled('registration_number'), function ($q) use ($request) {
            $q->where(function ($subQuery) use ($request) {
                $subQuery->whereHas('registration', function ($regQuery) use ($request) {
                    $regQuery->where('registration_number', 'like', '%' . $request->registration_number . '%');
                })->orWhereHas('registration_otc', function ($otcQuery) use ($request) {
                    $otcQuery->where('registration_number', 'like', '%' . $request->registration_number . '%');
                });
            });
        });

        // Filter berdasarkan No. RM (hanya pasien biasa)
        $query->when($request->filled('medical_record_number'), function ($q) use ($request) {
            $cleanedRM = str_replace('-', '', $request->medical_record_number);
            $q->whereHas('registration.patient', function ($patientQuery) use ($cleanedRM) {
                $patientQuery->where('medical_record_number', 'like', '%' . $cleanedRM . '%');
            });
        });

        // 3. Eksekusi Query dan Kirim Data ke View
        $orders = $query->orderByDesc('id')->get(); // Diurutkan berdasarkan ID terbaru

        // Menambahkan link detail pasien pada setiap order untuk kemudahan di view
        $orders->each(function ($order) {
            if ($order->registration && $order->registration->patient) {
                $order->patient_detail_link = route('detail.pendaftaran.pasien', $order->registration->patient->id);
            } else {
                $order->patient_detail_link = '#'; // Link default jika data tidak lengkap
            }
        });

        return view('pages.simrs.laboratorium.list-order', [
            'orders' => $orders
        ]);
    }

    public function editOrder($id)
    {
        $order = OrderLaboratorium::findOrFail($id);
        $parameters = $order->order_parameter_laboratorium;
        $parameterCategories = [];

        foreach ($parameters as $parameter) {
            $category = $parameter->parameter_laboratorium->kategori_laboratorium->nama_kategori;
            if (!isset($parameterCategories[$category])) {
                $parameterCategories[$category] = [];
            }
            $parameterCategories[$category][] = $parameter;
        }

        // --- TAMBAHAN BARU: Muat semua kategori dan tarif untuk modal ---
        $all_laboratorium_categories = KategoriLaboratorium::with('parameter_laboratorium')->get();
        $all_tarifs = TarifParameterLaboratorium::all();
        // --- AKHIR TAMBAHAN ---

        return view('pages.simrs.laboratorium.partials.edit-order', [
            'order' => $order,
            'parametersInCategory' => $parameterCategories,
            'nilai_normals' => NilaiNormalLaboratorium::all(),
            'all_laboratorium_categories' => $all_laboratorium_categories, // Kirim ke view
            'all_tarifs' => $all_tarifs, // Kirim ke view
        ]);
    }

    public function addTindakan(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:order_laboratorium,id',
            'parameter_data' => 'required|array',
            'parameter_data.*.id' => 'required|integer|exists:parameter_laboratorium,id',
            'parameter_data.*.jumlah' => 'required|integer|min:1',
        ]);

        // Eager load relasi yang dibutuhkan untuk efisiensi
        $order = OrderLaboratorium::with('registration.penjamin', 'registration.kelas_rawat')->findOrFail($request->order_id);

        // --- INI BAGIAN YANG DIPERBAIKI (1) ---
        // Mengambil ID GRUP Penjamin, bukan ID Penjamin-nya.
        // Asumsi: Relasi `penjamin` pada model `Registration` ada, dan model `Penjamin` punya kolom `group_penjamin_id`
        // Jika order OTC atau data tidak lengkap, default ke grup 1 (UMUM)
        $groupPenjaminId = $order->registration->penjamin->group_penjamin_id ?? 1;
        $kelasRawatId = $order->registration->kelas_rawat_id ?? 1; // Default ke kelas 1 (atau sesuaikan)
        // --- AKHIR BAGIAN PERBAIKAN (1) ---

        $addedCount = 0;
        foreach ($request->parameter_data as $data) {
            $parameterId = $data['id'];
            $jumlah = $data['jumlah'];

            $exists = OrderParameterLaboratorium::where('order_laboratorium_id', $order->id)
                ->where('parameter_laboratorium_id', $parameterId)
                ->exists();

            if ($exists) {
                continue;
            }

            // --- INI BAGIAN YANG DIPERBAIKI (2) ---
            // Menggunakan kolom 'group_penjamin_id' sesuai dengan skema database
            $tarif = TarifParameterLaboratorium::where('parameter_laboratorium_id', $parameterId)
                ->where('group_penjamin_id', $groupPenjaminId) // Menggunakan kolom dan variabel yang benar
                ->where('kelas_rawat_id', $kelasRawatId)
                ->first();
            // --- AKHIR BAGIAN PERBAIKAN (2) ---

            // Loop untuk menambahkan item sebanyak 'jumlah'
            for ($i = 0; $i < $jumlah; $i++) {
                OrderParameterLaboratorium::create([
                    'order_laboratorium_id' => $order->id,
                    'parameter_laboratorium_id' => $parameterId,
                    'nominal_rupiah' => $tarif->total ?? 0,
                    'user_id' => auth()->id(),
                    'employee_id' => auth()->user()->employee->id,
                ]);
            }
            $addedCount++;
        }

        if ($addedCount > 0) {
            return response()->json(['success' => true, 'message' => "$addedCount jenis tindakan berhasil ditambahkan."]);
        }

        return response()->json(['success' => false, 'message' => "Tidak ada tindakan baru yang ditambahkan (mungkin sudah ada)."]);
    }


    public function addTindakanPopup($order_id)
    {
        $order = OrderLaboratorium::findOrFail($order_id);

        // Ambil ID parameter yang sudah ada di order ini untuk dinonaktifkan di popup
        $existingParameterIds = $order->order_parameter_laboratorium()->pluck('parameter_laboratorium_id')->toArray();

        // Ambil semua kategori untuk ditampilkan
        $all_laboratorium_categories = KategoriLaboratorium::with('parameter_laboratorium')->get();

        return view('pages.simrs.laboratorium.partials.add-tindakan-popup', [
            'order' => $order,
            'all_laboratorium_categories' => $all_laboratorium_categories,
            'existingParameterIds' => $existingParameterIds
        ]);
    }

    public function simulasiHarga()
    {
        return view('pages.simrs.laboratorium.simulasi-harga', [
            'laboratorium_categories' => KategoriLaboratorium::all(),
            'tarifs' => TarifParameterLaboratorium::all(),
            'group_penjamins' => GroupPenjamin::all(),
            'kelas_rawats' => KelasRawat::all()
        ]);
    }

    public function popupPilihPasien(Request $request, $poli)
    {
        $query = Registration::query()->with(['patient', 'departement']);
        $filters = ['registration_number'];
        $filterApplied = false;

        // active only
        $query->where('status', 'aktif');

        if ($poli == 'rajal') {
            $query->where('registration_type', 'rawat-jalan');
        } elseif ($poli == 'ranap') {
            $query->where('registration_type', 'rawat-inap');
        }
        foreach ($filters as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, 'like', '%' . $request->$filter . '%');
                $filterApplied = true;
            }
        }

        // Filter by date range
        if ($request->filled('registration_date')) {
            $dateRange = explode(' - ', $request->registration_date);
            if (count($dateRange) === 2) {
                $startDate = date('Y-m-d 00:00:00', strtotime($dateRange[0]));
                $endDate = date('Y-m-d 23:59:59', strtotime($dateRange[1]));
                $query->whereBetween('registration_date', [$startDate, $endDate]);
            }
            $filterApplied = true;
        }

        if ($request->filled('name')) {
            $query->whereHas('patient', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->name . '%');
            });
            $filterApplied = true;
        }

        if ($request->filled('medical_record_number')) {
            $query->whereHas('patient', function ($q) use ($request) {
                $q->where('medical_record_number', 'like', '%' . $request->medical_record_number . '%');
            });
            $filterApplied = true;
        }

        // Get the filtered results if any filter is applied
        if ($filterApplied) {
            $registrations = $query->orderBy('registration_date', 'asc')->get();
        } else {
            // Return empty collection if no filters applied
            $registrations = collect();
        }

        return view('pages.simrs.laboratorium.partials.popup-pilih-pasien', compact("registrations", "poli"));
    }

    public function reportParameterView($fromDate, $endDate, $tipe_rawat, $penjamin)
    {
        $query = OrderParameterLaboratorium::query()->with(['order_laboratorium', 'registration', 'registration_otc', 'registration_otc.doctor']);
        $query->whereHas('order_laboratorium', function ($q) use ($fromDate, $endDate) {
            $q->whereBetween('order_laboratorium.order_date', [$fromDate, $endDate]);
        });

        if ($tipe_rawat != "otc" && $tipe_rawat != "-") {
            $query->whereHas('registration', function ($q) use ($tipe_rawat) {
                switch ($tipe_rawat) {
                    case 'rajal':
                        $q->where('registration_type', 'rawat-jalan');
                        break;
                    case 'ranap':
                        $q->where('registration_type', 'rawat-inap');
                        break;
                }
            });
        }

        if ($tipe_rawat == "otc") {
            $query->whereHas('order_laboratorium', function ($q) {
                $q->where("otc_id", "!=", null);
            });
        }

        if ($penjamin && $penjamin != '-') {
            $query->whereHas('penjamins', function ($q) use ($penjamin) {
                $q->where('id', 'like', '%' . $penjamin . '%');
            });
        }

        $orders = $query->get();

        // [group => [parameter => count]]
        $reports = [];

        foreach ($orders as $order) {
            $grup = $order->parameter_laboratorium->grup_parameter_laboratorium->nama_grup;

            // check if $reports has $grup
            if (!isset($reports[$grup])) {
                $reports[$grup] = [];
            }

            // check if $reports[$grup] has $parameter
            $nama_parameter = $order->parameter_laboratorium->parameter;
            if (!isset($reports[$grup][$nama_parameter])) {
                $reports[$grup][$nama_parameter] = 0;
            }

            // up the count
            $reports[$grup][$nama_parameter] += 1;
        }

        // dd($reports);

        return view('pages.simrs.laboratorium.partials.laporan-parameter-view', [
            'reports' => $reports,
            'startDate' => $fromDate,
            'endDate' => $endDate
        ]);
    }

    public function reportPatientView($fromDate, $endDate, $tipe_rawat, $penjamin, $parameter)
    {
        $query = OrderParameterLaboratorium::query()->with(['order_laboratorium', 'registration', 'registration_otc', 'registration_otc.doctor']);
        $query->whereHas('order_laboratorium', function ($q) use ($fromDate, $endDate) {
            $q->whereBetween('order_laboratorium.order_date', [$fromDate, $endDate]);
        });

        if ($tipe_rawat != "otc" && $tipe_rawat != "-") {
            $query->whereHas('registration', function ($q) use ($tipe_rawat) {
                switch ($tipe_rawat) {
                    case 'rajal':
                        $q->where('registration_type', 'rawat-jalan');
                        break;
                    case 'ranap':
                        $q->where('registration_type', 'rawat-inap');
                        break;
                }
            });
        }

        if ($tipe_rawat == "otc") {
            $query->whereHas('order_laboratorium', function ($q) {
                $q->where("otc_id", "!=", null);
            });
        }

        if ($penjamin && $penjamin != '-') {
            $query->whereHas('penjamins', function ($q) use ($penjamin) {
                $q->where('id', 'like', '%' . $penjamin . '%');
            });
        }

        if ($parameter && $parameter != '-') {
            $query->where('id', $parameter);
        }

        $orders = $query->get();

        return view('pages.simrs.laboratorium.partials.laporan-patient-view', [
            'orders' => $orders,
            'startDate' => $fromDate,
            'endDate' => $endDate
        ]);
    }

    public function reportParameter(Request $request)
    {
        $penjamin = Penjamin::all();
        return view('pages.simrs.laboratorium.laporan-parameter', [
            'penjamins' => $penjamin
        ]);
    }

    public function reportPatient(Request $request)
    {
        $parameters = ParameterLaboratorium::all();
        $penjamin = Penjamin::all();

        return view('pages.simrs.laboratorium.laporan-pasien', [
            'parameters' => $parameters,
            'penjamins' => $penjamin
        ]);
    }

    public function export(Request $request)
    {
        $request->validate([
            'grup_penjamin_id' => 'required|integer',
            'departement_id' => 'required|integer',
        ]);
        $grupPenjaminId = $request->grup_penjamin_id;
        $departementId = $request->departement_id;

        return Excel::download(new LaboratoriumTarifExport($grupPenjaminId, $departementId), 'Template-Tarif-Laboratorium.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xls,xlsx,csv']);

        try {
            Excel::import(new LaboratoriumTarifImport, $request->file('file'));
            return back()->with('success', 'Tarif laboratorium berhasil diimpor!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            // Cari order berdasarkan ID, jika tidak ketemu akan otomatis error 404
            $order = OrderLaboratorium::findOrFail($id);

            // Lakukan validasi tambahan jika perlu, misalnya:
            if ($order->status_billed == 1) {
                return response()->json(['success' => false, 'message' => 'Order yang sudah ditagih tidak bisa dihapus.'], 400);
            }

            // Hapus tagihan pasien dan bilingan tagihan pasien yang terkait
            // Ambil semua parameter order laboratorium
            $parameterIds = $order->order_parameter_laboratorium()->pluck('id')->toArray();

            // Ambil semua tagihan pasien yang terkait dengan bilingan dan order ini
            if ($order->bilingan_id) {
                // Hapus bilingan_tagihan_pasien yang terkait dengan bilingan_id dan tagihan pasien dari order ini
                $tagihanIds = \App\Models\SIMRS\TagihanPasien::where('bilingan_id', $order->bilingan_id)
                    ->where('registration_id', $order->registration_id)
                    ->whereIn('tagihan', function ($query) use ($parameterIds) {
                        $query->selectRaw("CONCAT('[Biaya Laboratorium] ', parameter)")
                            ->from('parameter_laboratorium')
                            ->whereIn('id', function ($sub) use ($parameterIds) {
                                $sub->select('parameter_laboratorium_id')
                                    ->from('order_parameter_laboratorium')
                                    ->whereIn('id', $parameterIds);
                            });
                    })
                    ->pluck('id')
                    ->toArray();

                if (!empty($tagihanIds)) {
                    // Hapus bilingan_tagihan_pasien
                    \App\Models\SIMRS\BilinganTagihanPasien::whereIn('tagihan_pasien_id', $tagihanIds)->delete();
                    // Hapus tagihan_pasien
                    \App\Models\SIMRS\TagihanPasien::whereIn('id', $tagihanIds)->delete();
                }
            }

            // Hapus order
            $order->delete();

            // Kirim respons sukses
            return response()->json(['success' => true, 'message' => 'Order Laboratorium dan tagihan terkait berhasil dihapus.']);
        } catch (\Exception $e) {
            // Catat error untuk debugging
            \Log::error('Gagal menghapus order laboratorium: ' . $e->getMessage());

            // Kirim respons error ke client
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan pada server saat menghapus data.'], 500);
        }
    }
}
