<?php

namespace App\Http\Controllers\SIMRS\Radiologi;

use App\Exports\RadiologiTarifExport;
use App\Http\Controllers\Controller;
use App\Imports\RadiologiTarifImport;
use App\Models\Employee;
use App\Models\OrderParameterRadiologi;
use App\Models\OrderRadiologi;
use App\Models\RegistrationOTC;
use App\Models\SIMRS\Doctor;
use App\Models\SIMRS\GroupPenjamin;
use App\Models\SIMRS\GrupParameterRadiologi;
use App\Models\SIMRS\KategoriRadiologi;
use App\Models\SIMRS\KelasRawat;
use App\Models\SIMRS\Penjamin;
use App\Models\SIMRS\Radiologi\TarifParameterRadiologi;
use App\Models\SIMRS\Registration;
use App\Models\TemplateHasilRadiologi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class RadiologiController extends Controller
{
    public function index(Request $request)
    {
        $query = OrderRadiologi::query()
            ->with(['registration', 'registration_otc', 'patient', 'doctor.employee']); // Pastikan relasi yang dibutuhkan ada di with()

        $filters = ['registration_number', 'no_order'];
        $filterApplied = false;

        // Cek apakah ada filter yang dikirim dari form
        foreach ($filters as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, 'like', '%' . $request->$filter . '%');
                $filterApplied = true;
            }
        }

        if ($request->filled('medical_record_number')) {
            $query->whereHas('registration.patient', function ($q) use ($request) {
                $q->where('medical_record_number', 'like', '%' . $request->medical_record_number . '%');
            });
            $filterApplied = true;
        }

        if ($request->filled('name')) {
            $query->whereHas('registration.patient', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->name . '%');
            });
            $filterApplied = true;
        }

        // Filter berdasarkan rentang tanggal HANYA jika dikirim dari form
        if ($request->filled('registration_date')) {
            // Nama parameter di form adalah 'registration_date', bukan 'order_date'
            $dateRange = explode(' - ', $request->registration_date);
            if (count($dateRange) === 2) {
                $startDate = Carbon::createFromFormat('Y-m-d', $dateRange[0])->startOfDay();
                $endDate = Carbon::createFromFormat('Y-m-d', $dateRange[1])->endOfDay();
                $query->whereBetween('order_date', [$startDate, $endDate]);
            }
            $filterApplied = true;
        }

        // --- PERUBAHAN UTAMA DI SINI ---
        // Jika ada filter yang diterapkan, ambil data sesuai filter.
        // Jika tidak, ambil data untuk hari ini sebagai default.
        if ($filterApplied) {
            $orders = $query->orderBy('order_date', 'desc')->get();
        } else {
            // Default: Ambil data untuk hari ini
            $query->whereDate('order_date', Carbon::today());
            $orders = $query->orderBy('order_date', 'desc')->get();
        }

        return view('pages.simrs.radiologi.list-order', [
            'orders' => $orders
        ]);
    }


    public function order()
    {
        $radiologyDoctors = Doctor::whereHas('department_from_doctors', function ($query) {
            $query->where('name', 'like', '%radiologi%');
        })->get();
        return view('pages.simrs.radiologi.order', [
            'radiologyDoctors' => $radiologyDoctors,
            'penjamins' => Penjamin::all(),
            'kelas_rawats' => KelasRawat::all(),
            'radiology_categories' => KategoriRadiologi::all(),
            'tarifs' => TarifParameterRadiologi::all(),
        ]);
    }

    public function notaOrder($id)
    {
        $order = OrderRadiologi::findOrFail($id);
        return view('pages.simrs.radiologi.partials.nota-order', [
            'order' => $order
        ]);
    }

    public function hasilOrder($id)
    {
        $order = OrderRadiologi::findOrFail($id);
        $order->load(['registration', 'registration_otc', 'registration_otc.doctor']);
        return view('pages.simrs.radiologi.partials.hasil-order', [
            'order' => $order
        ]);
    }

    public function labelOrder($id)
    {
        $order = OrderRadiologi::findOrFail($id);
        $order->load(['registration', 'registration_otc', 'registration_otc.doctor']);
        return view('pages.simrs.radiologi.partials.label-order', [
            'order' => $order
        ]);
    }

    public function editHasilParameter($id)
    {
        $parameter = OrderParameterRadiologi::findOrFail($id);
        return view('pages.simrs.radiologi.partials.edit-hasil-parameter', [
            'parameter' => $parameter
        ]);
    }

    public function editOrder($id)
    {
        $order = OrderRadiologi::findOrFail($id);
        // organizations table, id "Radiologi" = 24
        $radiografers = Employee::where(['organization_id' => 24])->get();
        $parameters =  $order->order_parameter_radiologi;
        $parameterCategories = [];

        foreach ($parameters as $parameter) {
            $category = $parameter->parameter_radiologi->kategori_radiologi->nama_kategori;
            $category = $parameter['parameter_radiologi']['kategori_radiologi']['nama_kategori'];
            if (!isset($parameterCategories[$category])) {
                $parameterCategories[$category] = [];
            }
            $parameterCategories[$category][] = $parameter;
        }

        $template = TemplateHasilRadiologi::all();


        return view('pages.simrs.radiologi.partials.edit-order', [
            'order' => $order,
            'parametersInCategory' => $parameterCategories,
            'radiografers' => $radiografers,
            'templates' => $template
        ]);
    }

    public function templateHasil(Request $request)
    {
        $query = TemplateHasilRadiologi::query();
        $filters = ['cari-judul', 'cari-template'];
        $filterApplied = false;

        foreach ($filters as $filter) {
            if ($request->filled($filter)) {
                $query->where(str_replace("cari-", "", $filter), 'like', '%' . $request->$filter . '%');
                $filterApplied = true;
            }
        }

        // Get the filtered results if any filter is applied
        if ($filterApplied) {
            $template = $query->get();
        } else {
            $template = TemplateHasilRadiologi::all();
        }

        return view('pages.simrs.radiologi.template-hasil', [
            'templates' => $template
        ]);
    }

    public function simpanTemplateHasil(Request $request, $id)
    {
        try {
            if (!isset($id) || $id == 0) {
                // insert
                $validatedData = $request->validate([
                    'judul' => 'required',
                    'template' => 'required',
                ]);
                TemplateHasilRadiologi::create([
                    'judul' => $validatedData['judul'],
                    'template' => $validatedData['template']
                ]);
            } else {
                // update
                $validatedData = $request->validate([
                    'judul' . $id => 'required',
                    'template' . $id => 'required'
                ]);
                $template = TemplateHasilRadiologi::findOrFail($id);
                $template->update([
                    'judul' => $validatedData['judul' . $id],
                    'template' => $validatedData['template' . $id]
                ]);
            }
        } catch (\Exception $e) {
            return response("<script> alert('Error: " . $e->getMessage() . "'); </script>");
        }

        // success
        return back();
    }

    public function deleteTemplate($id)
    {
        try {
            $template = TemplateHasilRadiologi::findOrFail($id);
            $template->delete();
        } catch (\Exception $e) {
            return response("<script> alert('Error: " . $e->getMessage() . "'); </script>");
        }

        return back();
    }

    public function simulasiHarga()
    {
        return view('pages.simrs.radiologi.simulasi-harga', [
            'radiology_categories' => KategoriRadiologi::all(),
            'tarifs' => TarifParameterRadiologi::all(),
            'group_penjamins' => GroupPenjamin::all(),
            'kelas_rawats' => KelasRawat::all()
        ]);
    }

    public function report(Request $request)
    {
        $groupParameter = GrupParameterRadiologi::all();
        $radiografer = Employee::where('organization_id', 24)->get();
        $penjamin = Penjamin::all();

        return view('pages.simrs.radiologi.laporan', [
            'groupParameters' => $groupParameter,
            'radiografers' => $radiografer,
            'penjamins' => $penjamin
        ]);
    }

    public function reportView($fromDate, $endDate, $tipe_rawat, $group_parameter, $penjamin, $radiografer)
    {

        $query = OrderParameterRadiologi::query()->with(['order_radiologi', 'registration', 'registration_otc', 'registration_otc.doctor']);
        $query->whereHas('order_radiologi', function ($q) use ($fromDate, $endDate) {
            $q->whereBetween('order_radiologi.order_date', [$fromDate, $endDate]);
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
            $query->whereHas('order_radiologi', function ($q) {
                $q->where("otc_id", "!=", null);
            });
        }

        if ($group_parameter && $group_parameter != '-') {
            $query->whereHas('grup_parameter_radiologi', function ($q) use ($group_parameter) {
                $q->where('id', 'like', '%' . $group_parameter . '%');
            });
        }

        if ($penjamin && $penjamin != '-') {
            $query->whereHas('penjamins', function ($q) use ($penjamin) {
                $q->where('id', 'like', '%' . $penjamin . '%');
            });
        }

        if ($radiografer && $radiografer != '-') {
            $query->whereHas('employees', function ($q) use ($radiografer) {
                $q->where('id', 'like', '%' . $radiografer . '%');
            });
        }

        $orders = $query->get();

        return view('pages.simrs.radiologi.partials.laporan-view', [
            'orders' => $orders,
            'startDate' => $fromDate,
            'endDate' => $endDate
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

        return view('pages.simrs.radiologi.partials.popup-pilih-pasien', compact("registrations", "poli"));
    }

    public function export(Request $request)
    {
        $request->validate(['grup_penjamin_id' => 'required|integer']);
        $grupPenjaminId = $request->grup_penjamin_id;

        return Excel::download(new RadiologiTarifExport($grupPenjaminId), 'Template-Tarif-Radiologi.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xls,xlsx,csv']);

        try {
            Excel::import(new RadiologiTarifImport, $request->file('file'));
            return back()->with('success', 'Tarif radiologi berhasil diimpor!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            // Cari order berdasarkan ID, jika tidak ketemu akan otomatis error 404
            $order = OrderRadiologi::findOrFail($id);

            // Lakukan validasi tambahan jika perlu, misalnya:
            if ($order->status_billed == 1) {
                return response()->json(['success' => false, 'message' => 'Order yang sudah ditagih tidak bisa dihapus.'], 400);
            }

            // Hapus tagihan pasien dan bilingan tagihan pasien yang terkait
            // Ambil semua parameter order radiologi
            $parameterIds = $order->order_parameter_radiologi()->pluck('id')->toArray();

            // Ambil semua tagihan pasien yang terkait dengan bilingan dan order ini
            if ($order->bilingan_id) {
                // Hapus bilingan_tagihan_pasien yang terkait dengan bilingan_id dan tagihan pasien dari order ini
                $tagihanIds = \App\Models\SIMRS\TagihanPasien::where('bilingan_id', $order->bilingan_id)
                    ->where('registration_id', $order->registration_id)
                    ->whereIn('tagihan', function ($query) use ($parameterIds) {
                        $query->selectRaw("CONCAT('[Biaya Radiologi] ', parameter)")
                            ->from('parameter_radiologi')
                            ->whereIn('id', function ($sub) use ($parameterIds) {
                                $sub->select('parameter_radiologi_id')
                                    ->from('order_parameter_radiologi')
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
            return response()->json(['success' => true, 'message' => 'Order Radiologi dan tagihan terkait berhasil dihapus.']);
        } catch (\Exception $e) {
            // Catat error untuk debugging
            \Log::error('Gagal menghapus order radiologi: ' . $e->getMessage());

            // Kirim respons error ke client
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan pada server saat menghapus data.'], 500);
        }
    }
}
