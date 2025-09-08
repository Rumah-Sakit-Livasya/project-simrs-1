<?php

namespace App\Models\Keuangan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PettyCashDetail extends Model
{
    use HasFactory;

    protected $table = 'petty_cash_detail';

    protected $fillable = [
        'petty_cash_id',
        'coa_id',
        'keterangan',
        'nominal',
        'cost_center_id',
    ];

    /**
     * Relasi ke header petty cash (N : 1)
     */
    public function pettyCash()
    {
        return $this->belongsTo(PettyCash::class, 'petty_cash_id');
    }

    /**
     * Relasi ke Chart of Account (coa_id)
     */
    public function coa()
    {
        return $this->belongsTo(ChartOfAccount::class, 'coa_id');
    }

    /**
     * Relasi ke Cost Center
     */
    public function costCenter()
    {
        return $this->belongsTo(RncCenter::class, 'cost_center_id');
    }
}
