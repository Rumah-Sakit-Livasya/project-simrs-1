<?php

namespace App\Listeners;

use App\Events\BillingFinalized;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Keuangan\KonfirmasiAsuransi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class CreateKonfirmasiAsuransi
{
    public function handle(BillingFinalized $event)
    {
        $billing = $event->billing;
        $reg = $billing->registration;

        \Log::info('Creating insurance confirmation for billing', [
            'billing_id' => $billing->id,
            'penjamin_id' => $reg->penjamin_id ?? null,
            'amount' => $billing->wajib_bayar
        ]);

        // Validasi tambahan
        if (!$reg || !$reg->penjamin_id || $billing->wajib_bayar <= 0) {
            \Log::warning('Skipping insurance confirmation creation', [
                'reason' => !$reg ? 'No registration' : (!$reg->penjamin_id ? 'No penjamin_id' : 'Invalid amount'),
                'billing_id' => $billing->id
            ]);
            return;
        }

        try {
            DB::transaction(function () use ($billing, $reg) {
                KonfirmasiAsuransi::create([
                    'penjamin_id'        => $reg->penjamin_id,
                    'registration_id'    => $billing->registration_id,
                    'jumlah'             => (float) $billing->wajib_bayar,
                    'diskon'             => 0,
                    'tanggal'            => now()->toDateString(),
                    'status_pembayaran'  => 'Belum Ada Pembayaran',
                    'created_by'         => auth()->id() ?? 1,
                    'updated_by'         => auth()->id() ?? 1,
                    'tagihan_ke'        => $reg->penjamin_id,
                    'status'            => 'Belum Di Buat Tagihan',
                    // Tambahkan field lain jika diperlukan
                ]);

                \Log::info('Insurance confirmation created successfully', [
                    'billing_id' => $billing->id,
                    'penjamin_id' => $reg->penjamin_id
                ]);
            });
        } catch (\Exception $e) {
            \Log::error('Failed to create insurance confirmation: ' . $e->getMessage(), [
                'billing_id' => $billing->id,
                'error' => $e->getTraceAsString()
            ]);
        }
    }
}
