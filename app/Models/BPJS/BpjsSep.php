<?php

namespace App\Models;

use App\Models\SIMRS\Registration;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BpjsSep extends Model
{
    use HasFactory;

    // Sesuaikan nama tabel jika berbeda
    protected $table = 'bpjs_seps';

    protected $fillable = [
        'registration_id',
        'sep_number',
        'sep_date',
        // tambahkan kolom lain yang relevan...
    ];

    /**
     * Data SEP ini dimiliki oleh satu registrasi.
     */
    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }
}
