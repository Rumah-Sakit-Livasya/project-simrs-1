<?php

namespace App\Models\Keuangan;

use App\Models\Keuangan\ChartOfAccount;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransaksiRutin extends Model
{
    use HasFactory;

    protected $table = 'transaksi_rutins'; // 👈 tambahkan ini

    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relasi ke Chart of Account.
     */
    public function chartOfAccount(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'chart_of_account_id');
    }
}
