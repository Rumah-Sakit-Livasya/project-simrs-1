<?php

namespace App\Models;

use App\Models\Keuangan\ApSupplierDetail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApSupplierHeader extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Nama tabel yang terhubung dengan model ini.
     *
     * @var string
     */
    protected $table = 'ap_supplier_header';

    /**
     * Atribut yang bisa diisi secara massal (mass assignable).
     *
     * @var array
     */
    protected $fillable = [
        'kode_ap',
        'supplier_id',
        'no_invoice_supplier',
        'tanggal_ap',
        'tanggal_invoice_supplier',
        'due_date',
        'tanggal_faktur_pajak',
        'subtotal',
        'diskon_final',
        'ppn_persen',
        'ppn_nominal',
        'biaya_lainnya',
        'grand_total',
        'notes',
        'status_pembayaran',
        'user_entry_id',
        'ada_kwitansi',
        'ada_faktur_pajak',
        'ada_surat_jalan',
        'ada_salinan_po',
        'ada_tanda_terima_barang',
        'ada_berita_acara',
    ];

    /**
     * Tipe data native untuk atribut tertentu.
     *
     * @var array
     */
    protected $casts = [
        'tanggal_ap' => 'date',
        'tanggal_invoice_supplier' => 'date',
        'due_date' => 'date',
        'tanggal_faktur_pajak' => 'date',
        'ada_kwitansi' => 'boolean',
        'ada_faktur_pajak' => 'boolean',
        'ada_surat_jalan' => 'boolean',
        'ada_salinan_po' => 'boolean',
        'ada_tanda_terima_barang' => 'boolean',
        'ada_berita_acara' => 'boolean',
    ];

    // --- RELASI ELOQUENT ---

    /**
     * Relasi ke model Supplier.
     * Sebuah AP Header dimiliki oleh satu Supplier.
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(WarehouseSupplier::class, 'supplier_id');
    }

    /**
     * Relasi ke model User (yang membuat entri).
     * Sebuah AP Header dibuat oleh satu User.
     */
    public function userEntry(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_entry_id');
    }

    /**
     * Relasi ke model Detail AP.
     * Sebuah AP Header memiliki banyak Detail.
     */
    public function details(): HasMany
    {
        return $this->hasMany(ApSupplierDetail::class, 'ap_supplier_header_id');
    }
}
