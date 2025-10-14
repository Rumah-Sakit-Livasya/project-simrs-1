<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class ProcurementPurchaseOrderPharmacy extends Model implements AuditableContract
{
    use Auditable, SoftDeletes;

    protected $table = 'procurement_purchase_order_pharmacy';
    protected $guarded = ['id'];

    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProcurementPurchaseOrderPharmacyItems::class, 'po_id');
    }

    public function supplier(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(WarehouseSupplier::class, 'supplier_id');
    }

    /**
     * User yang membuat PO.
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke User yang melakukan approval PO.
     * Foreign key 'app_user_id' adalah kolom di tabel 'procurement_purchase_order_pharmacy'.
     * Owner key 'id' adalah kolom primary key di tabel 'users'.
     */
    public function app_user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'app_user_id', 'id');
    }

    public function pb(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(WarehousePenerimaanBarangFarmasi::class, 'po_id');
    }
}
