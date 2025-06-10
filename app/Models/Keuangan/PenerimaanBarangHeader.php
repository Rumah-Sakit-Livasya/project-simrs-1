<?php

namespace App\Models\keuangan;

use App\Models\User;
use App\Models\WarehouseSupplier;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PenerimaanBarangHeader extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Nama tabel yang terhubung dengan model ini.
     *
     * @var string
     */
    protected $table = 'penerimaan_barang_header';

    /**
     * Atribut yang bisa diisi secara massal.
     *
     * @var array
     */
    protected $fillable = [
        'no_grn',
        'tanggal_penerimaan',
        'supplier_id',
        'purchase_id',
        'no_surat_jalan_supplier',
        'total_nilai_barang',
        'status_ap',
        'user_penerima_id',
        'catatan_penerimaan',
    ];

    /**
     * Tipe data native untuk atribut tertentu.
     *
     * @var array
     */
    protected $casts = [
        'tanggal_penerimaan' => 'date',
    ];

    // --- RELASI ELOQUENT ---

    /**
     * Relasi ke model Supplier.
     * Sebuah GRN dimiliki oleh satu Supplier.
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(WarehouseSupplier::class, 'supplier_id');
    }

    /**
     * Relasi ke model Purchase Order.
     * Sebuah GRN berasal dari satu PO.
     */
    // public function purchase(): BelongsTo
    // {
    //     return $this->belongsTo( ::class, 'purchase_id');
    // }

    /**
     * Relasi ke model User (yang menerima barang).
     * Sebuah GRN diterima oleh satu User.
     */
    public function userPenerima(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_penerima_id');
    }
}
