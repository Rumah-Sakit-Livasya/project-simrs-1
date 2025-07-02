<?php

namespace App\Models\Keuangan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranApSupplierDetail extends Model
{
    use HasFactory;

    /**
     * Nama tabel database.
     */
    protected $table = 'pembayaran_ap_supplier_details';

    /**
     * Atribut yang dikecualikan dari mass assignment.
     */
    protected $guarded = ['id'];

    /**
     * Menonaktifkan timestamps (created_at, updated_at).
     */
    public $timestamps = false;

    /**
     * Relasi ke model PembayaranApSupplierHeader (Many to One).
     * Satu detail pembayaran dimiliki oleh satu header.
     */
    public function header()
    {
        return $this->belongsTo(PembayaranApSupplierHeader::class, 'pembayaran_ap_header_id');
    }

    public function apSupplier()
    {
        // Relasi ini mengatakan "satu detail    MILIK SATU header AP Supplier".
        // Parameter kedua ('ap_supplier_header_id') adalah nama foreign key
        // di tabel 'pembayaran_ap_supplier_detail' yang menunjuk ke tabel 'ap_supplier_header'.
        return $this->belongsTo(ApSupplierHeader::class, 'ap_supplier_header_id');
    }


    /**
     * Relasi ke model ApSupplierHeader (Many to One).
     * Satu detail pembayaran merujuk ke satu invoice.
     */
    public function invoice()
    {
        return $this->belongsTo(ApSupplierHeader::class, 'ap_supplier_header_id');
    }
}
