<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Keuangan\ChartOfAccount;
use App\Models\Keuangan\TransaksiRutin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class TransaksiRutinController extends Controller
{
    public function index(Request $request)
    {
        $query = TransaksiRutin::with('chartOfAccount');

        // Apply filters if any
        if ($request->filled('nama_transaksi')) {
            $query->where('nama_transaksi', 'like', '%' . $request->nama_transaksi . '%');
        }
        if ($request->filled('status') && $request->status !== '') {
            $query->where('is_active', $request->status);
        }

        $transaksiRutin = $query->get();
        $chartOfAccounts = ChartOfAccount::where('header', 0)->orderBy('code')->get();

        return view('app-type.keuangan.setup.transaksi-rutin.index', [
            'transaksiRutin' => $transaksiRutin,
            'chartOfAccounts' => $chartOfAccounts,
            'filter_nama' => $request->nama_transaksi,
            'filter_status' => $request->status
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_transaksi' => 'required|string|max:255',
            'chart_of_account_id' => 'required|exists:chart_of_account,id',
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('toast_error', $validator->errors()->first())
                ->withInput();
        }

        try {
            TransaksiRutin::create($request->all());
            return redirect()->route('transaksi-rutin.index')
                ->with('toast_success', 'Transaksi Rutin berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Error creating TransaksiRutin: ' . $e->getMessage());
            return redirect()->back()
                ->with('toast_error', 'Terjadi kesalahan saat menambahkan data.')
                ->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $transaksiRutin = TransaksiRutin::find($id);
        if (!$transaksiRutin) {
            return redirect()->route('transaksi-rutin.index')
                ->with('toast_error', 'Data tidak ditemukan.');
        }

        $validator = Validator::make($request->all(), [
            'nama_transaksi' => 'required|string|max:255',
            'chart_of_account_id' => 'required|exists:chart_of_account,id',
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('toast_error', $validator->errors()->first())
                ->withInput();
        }

        try {
            $transaksiRutin->update($request->all());
            return redirect()->route('transaksi-rutin.index')
                ->with('toast_success', 'Transaksi Rutin berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error updating TransaksiRutin: ' . $e->getMessage());
            return redirect()->back()
                ->with('toast_error', 'Terjadi kesalahan saat memperbarui data.')
                ->withInput();
        }
    }

    public function destroy(Request $request)
    {
        // Log untuk debugging
        Log::info('Delete request received', [
            'request_method' => $request->method(),
            'all_data' => $request->all(),
            'ids' => $request->input('ids')
        ]);

        $ids = $request->input('ids');

        // Validasi input
        if (empty($ids) || !is_array($ids)) {
            Log::warning('No IDs provided for deletion', ['ids' => $ids]);
            return redirect()->route('transaksi-rutin.index')
                ->with('toast_error', 'Tidak ada data yang dipilih untuk dihapus.');
        }

        try {
            // Cek apakah data yang akan dihapus ada
            $existingData = TransaksiRutin::whereIn('id', $ids)->get();

            if ($existingData->isEmpty()) {
                Log::warning('No data found for deletion', ['ids' => $ids]);
                return redirect()->route('transaksi-rutin.index')
                    ->with('toast_error', 'Data yang dipilih tidak ditemukan.');
            }

            // Hapus data
            $deletedCount = TransaksiRutin::whereIn('id', $ids)->delete();

            Log::info('Data deleted successfully', [
                'deleted_count' => $deletedCount,
                'ids' => $ids
            ]);

            return redirect()->route('transaksi-rutin.index')
                ->with('toast_success', "Berhasil menghapus {$deletedCount} data.");
        } catch (\Exception $e) {
            Log::error('Error deleting TransaksiRutin: ' . $e->getMessage(), [
                'ids' => $ids,
                'exception' => $e
            ]);

            return redirect()->route('transaksi-rutin.index')
                ->with('toast_error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }
}
