<?php

namespace App\Models\keuangan;

use App\Models\Keuangan\PenerimaanBarangHeader;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ApSupplierDetail extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     *
     * @var string
     */
    protected $table = 'ap_supplier_detail';

    /**
     * Atribut yang bisa diisi secara massal.
     *
     * @var array
     */
    protected $guarded = [
        'id',
    ];

    // --- RELASI ELOQUENT ---

    /**
     * Relasi ke model AP Header.
     * Sebuah Detail AP dimiliki oleh satu Header.
     */
    public function header(): BelongsTo
    {
        return $this->belongsTo(ApSupplierHeader::class, 'ap_supplier_header_id');
    }

    public function penerimaanBarang()
    {
        return $this->morphTo();
    }
}
