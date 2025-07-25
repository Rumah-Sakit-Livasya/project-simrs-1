<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\BankPerusahaan;
use App\Models\Keuangan\Bank;
use App\Models\Keuangan\ChartOfAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BankController extends Controller
{
    public function index()
    {
        // Ambil data dari BankPerusahaan sebagai sumber utama
        $banks = BankPerusahaan::all();

        // Ambil data akun untuk dropdown di modal
        $chartOfAccounts = ChartOfAccount::orderBy('id', 'asc')->get();

        return view('app-type.keuangan.setup.bank.index', [
            'banks' => $banks,
            'chartOfAccounts' => $chartOfAccounts
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name'          => 'required|string|max:70',
            'pemilik'       => 'required|string|max:255',
            'nomor'         => 'required|string|max:255',
            'saldo'         => 'required|numeric|min:0',
            'akun_kas_bank' => 'required|exists:chart_of_account,id',
            'akun_kliring'  => 'required|exists:chart_of_account,id',
            'is_aktivasi'   => 'nullable',
            'is_bank'       => 'nullable',
        ]);

        DB::beginTransaction();

        try {
            // Simpan ke tabel `banks`
            $bank = new Bank();
            $bank->name = $validatedData['name'];
            $bank->saldo_awal = $validatedData['saldo'];
            $bank->total_masuk = $validatedData['saldo'];
            $bank->total_keluar = 0;
            $bank->save();

            // Simpan ke tabel `bank_perusahaan`
            $bankPerusahaan = new BankPerusahaan();
            $bankPerusahaan->nama = $validatedData['name']; // Sesuaikan dengan field 'nama'
            $bankPerusahaan->pemilik = $validatedData['pemilik'];
            $bankPerusahaan->nomor = $validatedData['nomor'];
            $bankPerusahaan->saldo = $validatedData['saldo'];
            $bankPerusahaan->akun_kas_bank = $validatedData['akun_kas_bank'];
            $bankPerusahaan->akun_kliring = $validatedData['akun_kliring'];
            $bankPerusahaan->is_aktivasi = $request->has('is_aktivasi');
            $bankPerusahaan->is_bank = $request->has('is_bank');
            $bankPerusahaan->save();

            DB::commit();

            return redirect()->route('bank.index')->with('success', 'Bank baru berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan bank baru: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Gagal menyimpan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $bank = BankPerusahaan::findOrFail($id);
            $chartOfAccounts = ChartOfAccount::orderBy('id', 'asc')->get();

            return view('app-type.keuangan.setup.bank.partials.edit', [
                'bank' => $bank,
                'chartOfAccounts' => $chartOfAccounts
            ]);
        } catch (\Exception $e) {
            Log::error('Bank tidak ditemukan: ' . $e->getMessage());
            return redirect()->route('bank.index')->with('error', 'Bank tidak ditemukan.');
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name'          => 'required|string|max:70',
            'pemilik'       => 'required|string|max:255',
            'nomor'         => 'required|string|max:255',
            'saldo'         => 'required|numeric|min:0',
            'akun_kas_bank' => 'required|exists:chart_of_account,id',
            'akun_kliring'  => 'required|exists:chart_of_account,id',
            'is_aktivasi'   => 'nullable',
            'is_bank'       => 'nullable',
        ]);

        DB::beginTransaction();

        try {
            // Update bank_perusahaan table
            $bankPerusahaan = BankPerusahaan::findOrFail($id);
            $oldName = $bankPerusahaan->nama; // Simpan nama lama untuk update tabel banks

            $bankPerusahaan->nama = $validatedData['name'];
            $bankPerusahaan->pemilik = $validatedData['pemilik'];
            $bankPerusahaan->nomor = $validatedData['nomor'];
            $bankPerusahaan->saldo = $validatedData['saldo'];
            $bankPerusahaan->akun_kas_bank = $validatedData['akun_kas_bank'];
            $bankPerusahaan->akun_kliring = $validatedData['akun_kliring'];
            $bankPerusahaan->is_aktivasi = $request->has('is_aktivasi');
            $bankPerusahaan->is_bank = $request->has('is_bank');
            $bankPerusahaan->save();

            // Update banks table jika ada
            $bank = Bank::where('name', $oldName)->first();
            if ($bank) {
                $bank->name = $validatedData['name'];
                $bank->saldo_awal = $validatedData['saldo'];
                $bank->save();
            }

            DB::commit();

            return redirect()->route('bank.index')->with('success', 'Bank berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal memperbarui bank: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Gagal memperbarui: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $bankPerusahaan = BankPerusahaan::findOrFail($id);
            $bankName = $bankPerusahaan->nama;

            // Hapus dari tabel bank_perusahaan
            $bankPerusahaan->delete();

            // Hapus dari tabel banks jika ada
            $bank = Bank::where('name', $bankName)->first();
            if ($bank) {
                $bank->delete();
            }

            DB::commit();

            return redirect()->route('bank.index')->with('success', 'Bank berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menghapus bank: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Gagal menghapus bank: ' . $e->getMessage());
        }
    }
}
