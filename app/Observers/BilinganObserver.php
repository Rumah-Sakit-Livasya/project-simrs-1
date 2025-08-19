<?php

namespace App\Observers\SIMRS; // Make sure this namespace is correct for your project structure

use App\Models\SIMRS\Bilingan;
use App\Models\SIMRS\TagihanPasien; // Import TagihanPasien model
use App\Models\keuangan\JasaDokter; // Import JasaDokter model
use Illuminate\Support\Facades\DB; // Import DB facade
use Illuminate\Support\Facades\Log; // Import Log facade
use Carbon\Carbon; // Import Carbon for date handling

class BilinganObserver
{
    public function updated(Bilingan $bilingan)
    {
        if ($bilingan->wasChanged('status') && $bilingan->status === 'final') {
            Log::info("Bilingan ID {$bilingan->id} status changed to FINAL. Attempting to auto-create Jasa Dokter AP.");

            DB::beginTransaction();

            try {
                $tagihanPasienItems = $bilingan->tagihanPasien()
                    ->where('tagihan', 'LIKE', '[Tindakan Medis]%')
                    ->whereDoesntHave('jasaDokter')
                    ->with([
                        'registration.doctor.employee',
                        'registration.penjamin',
                        'registration.kelas_rawat',
                        'tindakan_medis.tarifTindakanMedis',
                    ])
                    ->get();

                if ($tagihanPasienItems->isEmpty()) {
                    Log::info("Bilingan ID {$bilingan->id}: No eligible '[Tindakan Medis]' TagihanPasien items found for auto-creation or they already have AP records.");
                    DB::commit();
                    return;
                }

                Log::info("Bilingan ID {$bilingan->id}: Found {$tagihanPasienItems->count()} eligible TagihanPasien items for auto-creation.");

                $apDate = Carbon::parse($bilingan->updated_at);

                $lastApToday = JasaDokter::withTrashed()
                    ->whereDate('ap_date', $apDate->toDateString())
                    ->orderBy('ap_number', 'desc')
                    ->first();

                $currentSequence = 1;
                if ($lastApToday && preg_match('/^JD-\d{8}-(\d+)$/', $lastApToday->ap_number, $matches)) {
                    $currentSequence = (int)$matches[1] + 1;
                }

                $createdCount = 0;
                $skippedItems = [];

                foreach ($tagihanPasienItems as $item) {
                    // Re-check inside the loop to prevent race conditions if another process creates AP just before this loop item
                    if ($item->jasaDokter()->withTrashed()->exists()) {
                        $skippedItems[] = $item->id . " (AP already exists or cancelled)";
                        continue;
                    }

                    $registration = $item->registration;
                    $doctor = $registration->doctor ?? null;
                    $tindakanMedis = $item->tindakan_medis;
                    $tarif = null;
                    $shareDr = 0;
                    $nominalTotal = 0;
                    $jkp = 0;
                    $diskon = 0;
                    $ppn_persen = 11.00;

                    if ($tindakanMedis) {
                        $tarif = $tindakanMedis->getTarif(
                            $registration->penjamin_id ?? null,
                            $registration->kelas_rawat_id ?? null
                        );

                        if ($tarif) {
                            $shareDr = $tarif->share_dr ?? 0;
                            $nominalTotal = $tarif->total ?? 0;
                            $jkp = $tarif->jkp ?? 0;
                            // If diskon or ppn come from tarif, get them here
                            // $diskon = $tarif->diskon ?? 0;
                            // $ppn_persen = $tarif->ppn_persen ?? 11.00;
                        } else {
                            Log::warning("Bilingan ID {$bilingan->id}, TagihanPasien ID {$item->id}: Tarif not found for Tindakan Medis ID {$tindakanMedis->id}. Using default 0 values for share_dr and nominal.");
                        }
                    } else {
                        Log::warning("Bilingan ID {$bilingan->id}, TagihanPasien ID {$item->id}: TindakanMedis relation is missing. Cannot determine tariff details.");
                        $skippedItems[] = $item->id . " (TindakanMedis missing)";
                        continue;
                    }

                    $apNumber = 'JD-' . $apDate->format('Ymd') . '-' . str_pad($currentSequence, 4, '0', STR_PAD_LEFT);
                    $currentSequence++;

                    JasaDokter::create([
                        'registration_id' => $registration->id ?? null,
                        'tagihan_pasien_id' => $item->id,
                        'bilingan_id' => $bilingan->id,
                        'dokter_id' => $doctor->id ?? null,
                        // 'order_tindakan_medis_id' => null, // Keep as null or populate if you have this link
                        'nama_tindakan' => $tindakanMedis->nama_tindakan ?? ($item->tagihan ?? 'N/A'),
                        'nominal' => $nominalTotal,
                        'diskon' => $diskon,
                        'ppn_persen' => $ppn_persen,
                        'jkp' => $jkp,
                        'jasa_dokter' => null, // Keep null initially as per your schema description
                        'share_dokter' => $shareDr,
                        'status' => 'final', // Auto-create as final
                        'ap_number' => $apNumber,
                        'ap_date' => $apDate->toDateString(),
                        'bill_date' => $bilingan->created_at?->toDateString() ?? null,
                    ]);

                    $createdCount++;
                }

                DB::commit();
                Log::info("Bilingan ID {$bilingan->id}: Successfully auto-created {$createdCount} Jasa Dokter AP records. Skipped items: " . implode(', ', $skippedItems));
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Error auto-creating Jasa Dokter AP for Bilingan ID {$bilingan->id}: " . $e->getMessage(), ['exception' => $e, 'bilingan_id' => $bilingan->id]);
            }
        }
    }

    public function deleted(Bilingan $bilingan)
    {
        // If Bilingan is soft-deleted, soft-delete associated JasaDokter records
        if ($bilingan->isSoftDeleting()) {
            $bilingan->tagihanPasien()->each(function ($item) {
                // Check if there's an active JasaDokter to soft-delete
                if ($item->jasaDokter) {
                    $item->jasaDokter->delete();
                }
            });
            Log::info("Soft-deleted associated JasaDokter records for Bilingan ID {$bilingan->id}.");
        }
    }

    public function restored(Bilingan $bilingan)
    {
        // If Bilingan is restored, restore associated JasaDokter records that were soft-deleted *with* this billing
        $bilingan->tagihanPasien()->each(function ($item) {
            // Check if the associated JasaDokter was soft-deleted
            if ($item->jasaDokter()->onlyTrashed()->exists()) {
                $item->jasaDokter()->onlyTrashed()->restore();
            }
        });
        Log::info("Restored associated JasaDokter records for Bilingan ID {$bilingan->id}.");
    }
}
