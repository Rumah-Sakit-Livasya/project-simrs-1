<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NotifyContractExpiry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:contract-expiry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notification for expiring contracts to HRD and employees.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting contract expiry notification...');

        try {
            // Lakukan POST request ke URL internal Anda
            // Pastikan URL base sudah benar (misal: menggunakan env('APP_URL') atau tentukan secara langsung jika berjalan di server lokal)
            $response = Http::withHeaders([
                'Key' => 'KeyAbcKey', // Header validasi
                'Nama' => 'arul',     // Header validasi
                'Sandi' => '123###!!', // Header validasi
            ])->post(route('notify-contract-route')); // Ganti dengan nama route yang benar

            // Cek status respons
            if ($response->successful()) {
                $this->info('Contract expiry notification sent successfully.');
                Log::info('Contract Expiry Notification Success: ' . $response->body());
                return Command::SUCCESS;
            } else {
                $this->error("Failed to send contract expiry notification. HTTP Status: {$response->status()}");
                Log::error('Contract Expiry Notification Failed: ' . $response->body());
                return Command::FAILURE;
            }
        } catch (\Exception $e) {
            $this->error('An error occurred during contract expiry notification: ' . $e->getMessage());
            Log::error('Contract Expiry Notification Exception: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
