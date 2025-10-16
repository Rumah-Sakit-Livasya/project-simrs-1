<?php

namespace App\Console\Commands;

use App\Http\Controllers\BotMessageController;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
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
        $this->info('Starting contract expiry notification via direct call...');

        try {
            // 1. Buat instance Controller
            $controller = new BotMessageController();

            // 2. Buat instance Request kosong (jika Controller membutuhkan objek Request)
            $request = new Request();

            // 3. Panggil metode controller secara langsung
            // Metode ini akan menjalankan seluruh logika Anda untuk mencari kontrak kadaluarsa dan mengirim CURL
            $response = $controller->notifyExpiryContract($request);

            // Periksa hasil respons dari controller
            if ($response->getStatusCode() === 200) {
                $this->info('Contract expiry notification process finished successfully.');
                // Opsional: Log hasil
                \Log::info('Contract Expiry Notification Success: ' . $response->getContent());
                return Command::SUCCESS;
            } else {
                $this->error("Contract expiry notification failed within controller logic. Status: {$response->getStatusCode()}");
                return Command::FAILURE;
            }
        } catch (\Exception $e) {
            $this->error('An error occurred: ' . $e->getMessage());
            \Log::error('Contract Expiry Notification Exception: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
