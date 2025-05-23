<?php

namespace App\Models\Keuangan;

use Illuminate\Database\Eloquent\Model;

class InvoiceCounter extends Model
{
    protected $table = 'invoice_counters';

    protected $fillable = [
        'bulan_tahun',
        'counter'
    ];

    /**
     * Mendapatkan dan menaikkan counter untuk bulan dan tahun tertentu
     *
     * @param string $bulanTahun Format: YYYY-MM
     * @return integer Nomor counter yang sudah dinaikkan
     */
    public static function getNextCounter($bulanTahun)
    {
        $counter = self::firstOrCreate(
            ['bulan_tahun' => $bulanTahun],
            ['counter' => 0]
        );

        $counter->counter = $counter->counter + 1;
        $counter->save();

        return $counter->counter;
    }
}
