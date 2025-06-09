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

        Log::info('--- CreateKonfirmasiAsuransi LISTENER WAS CALLED --- Event for Billing ID: ' . $event->billing->id);
        // $billing = $event->billing;
        // $reg = $billing->registration;

        // if (!$reg || !$reg->penjamin_id) return;

        // $data = [
        //     'penjamin_id'        => $reg->penjamin_id,
        //     'registration_id'    => $billing->registration_id,
        //     'jumlah'             => is_numeric($billing->wajib_bayar) ? (float) $billing->wajib_bayar : 0,
        //     'diskon'             => 0,
        //     'tanggal'            => Carbon::now()->toDateString(),
        //     'status_pembayaran'  => 'Belum Ada Pembayaran',
        //     'created_by'         => auth()->id() ?? 1,
        //     'updated_by'         => auth()->id() ?? 1,
        //     'tagihan_ke'         => $reg->penjamin_id,
        //     'status'             => 'Belum Di Buat Tagihan',
        // ];

        // DB::transaction(function () use ($data) {
        //     KonfirmasiAsuransi::create($data);
        // });
    }
}
