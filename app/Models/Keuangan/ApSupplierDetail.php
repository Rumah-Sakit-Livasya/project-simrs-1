<?php

namespace App\Models\keuangan;

use App\Models\Keuangan\PenerimaanBarangHeader;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
    protected $fillable = [
        'ap_supplier_header_id',
        'penerimaan_barang_header_id',
        'nominal_grn',
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

    /**
     * Relasi ke model Penerimaan Barang (GRN).
     * Sebuah Detail AP mengacu pada satu GRN.
     */
    public function penerimaanBarang(): BelongsTo
    {
        return $this->belongsTo(PenerimaanBarangHeader::class, 'penerimaan_barang_header_id');
    }
}
