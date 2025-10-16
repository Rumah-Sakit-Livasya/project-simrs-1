<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NotifyDocumentExpiry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:document-expiry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notification for expiring documents to HRD.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting document expiry notification...');

        try {
            // Lakukan POST request ke URL internal Anda
            $response = Http::withHeaders([
                'Key' => 'KeyAbcKey', // Header validasi
                'Nama' => 'arul',     // Header validasi
                'Sandi' => '123###!!', // Header validasi
            ])->post(route('notify-expiry-document-route')); // Ganti dengan nama route yang benar

            // Cek status respons
            if ($response->successful()) {
                $this->info('Document expiry notification sent successfully.');
                Log::info('Document Expiry Notification Success: ' . $response->body());
                return Command::SUCCESS;
            } else {
                $this->error("Failed to send document expiry notification. HTTP Status: {$response->status()}");
                Log::error('Document Expiry Notification Failed: ' . $response->body());
                return Command::FAILURE;
            }
        } catch (\Exception $e) {
            $this->error('An error occurred during document expiry notification: ' . $e->getMessage());
            Log::error('Document Expiry Notification Exception: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
