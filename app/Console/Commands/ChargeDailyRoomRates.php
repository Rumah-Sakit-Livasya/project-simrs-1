<?php

namespace App\Console\Commands;

use App\Models\SIMRS\Bilingan;
use App\Models\SIMRS\BilinganTagihanPasien;
use App\Models\SIMRS\Registration;
use App\Models\SIMRS\TagihanPasien;
use App\Models\SIMRS\TarifKelasRawat;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ChargeDailyRoomRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:charge-daily-room-rates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menambahkan tagihan biaya kamar harian untuk semua pasien rawat inap yang masih aktif.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai proses penambahan tagihan kamar harian...');
        Log::info('Scheduler: Memulai proses penambahan tagihan kamar harian.');

        // 1. Ambil semua registrasi rawat inap yang statusnya 'aktif'
        // Eager load relasi penting untuk menghindari N+1 query problem
        $activeInpatients = Registration::where('registration_type', 'rawat-inap')
            ->where('status', 'aktif')
            ->with(['patient', 'penjamin.group_penjamin', 'kelas_rawat', 'bilingan'])
            ->get();


        if ($activeInpatients->isEmpty()) {
            $this->info('Tidak ada pasien rawat inap aktif yang ditemukan.');
            Log::info('Scheduler: Tidak ada pasien rawat inap aktif yang ditemukan.');
            return;
        }

        $this->info("Ditemukan {$activeInpatients->count()} pasien rawat inap aktif.");
        $today = Carbon::today();
        $processedCount = 0;

        // 2. Loop melalui setiap pasien
        foreach ($activeInpatients as $registration) {
            // Validasi awal
            if (!$registration->bilingan) {
                Log::warning("Scheduler: Pasien {$registration->patient->name} (Reg: {$registration->id}) tidak memiliki Bilingan. Dilewati.");
                continue;
            }

            // 3. Cek apakah tagihan kamar untuk hari ini sudah ada
            $alreadyChargedToday = TagihanPasien::where('registration_id', $registration->id)
                ->where('tagihan', 'like', '[Biaya Kamar]%')
                ->whereDate('created_at', $today)
                ->exists();

            if ($alreadyChargedToday) {
                $this->line("-> Pasien {$registration->patient->name} (Reg: {$registration->id}) sudah ditagihkan untuk hari ini. Dilewati.");
                continue;
            }

            // 4. Cari tarif kamar yang sesuai
            $tarifKelasRawat = TarifKelasRawat::where('kelas_rawat_id', $registration->kelas_rawat_id)
                ->where('group_penjamin_id', $registration->penjamin->group_penjamin_id)
                ->first();

            if (!$tarifKelasRawat || $tarifKelasRawat->tarif <= 0) {
                Log::error("Scheduler: TARIF TIDAK DITEMUKAN untuk pasien {$registration->patient->name} (Reg: {$registration->id}) dengan Kelas Rawat ID: {$registration->kelas_rawat_id} dan Group Penjamin ID: {$registration->penjamin->group_penjamin_id}.");
                continue;
            }

            $dailyRate = $tarifKelasRawat->tarif;

            // 5. Buat tagihan baru dan update bilingan dalam sebuah transaksi database
            try {
                \DB::transaction(function () use ($registration, $dailyRate) {
                    // Buat record TagihanPasien baru
                    $newTagihan = TagihanPasien::create([
                        'user_id' => 1, // ID user 'Sistem' atau user default
                        'bilingan_id' => $registration->bilingan->id,
                        'registration_id' => $registration->id,
                        'date' => Carbon::now(),
                        'tagihan' => '[Biaya Kamar] - ' . $registration->kelas_rawat->kelas,
                        'tipe_tagihan' => 'Biaya Kamar',
                        'quantity' => 1,
                        'nominal_awal' => $dailyRate,
                        'nominal' => $dailyRate,
                        'wajib_bayar' => $dailyRate,
                        'is_paid' => 0,
                    ]);

                    // Hubungkan tagihan baru ke bilingan melalui tabel pivot
                    BilinganTagihanPasien::create([
                        'tagihan_pasien_id' => $newTagihan->id,
                        'bilingan_id' => $registration->bilingan->id,
                    ]);

                    // Update total wajib bayar di bilingan
                    $registration->bilingan->increment('wajib_bayar', $dailyRate);
                });

                $this->info("-> Berhasil menambahkan tagihan Rp " . number_format($dailyRate) . " untuk pasien {$registration->patient->name} (Reg: {$registration->id}).");
                $processedCount++;
            } catch (\Exception $e) {
                Log::critical("Scheduler: GAGAL membuat tagihan untuk pasien {$registration->patient->name} (Reg: {$registration->id}). Error: " . $e->getMessage());
            }
        }

        $this->info("Proses selesai. {$processedCount} tagihan berhasil ditambahkan.");
        Log::info("Scheduler: Proses selesai. {$processedCount} tagihan berhasil ditambahkan.");
    }
}
