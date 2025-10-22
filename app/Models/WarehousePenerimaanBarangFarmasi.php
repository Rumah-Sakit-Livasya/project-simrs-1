<?php

namespace App\Models;

use App\Models\Keuangan\ApSupplierDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class WarehousePenerimaanBarangFarmasi extends Model implements AuditableContract
{
    use Auditable, SoftDeletes;

    protected $table = "warehouse_penerimaan_barang_farmasi";
    protected $guarded = ["id"];

    public function items()
    {
        return $this->hasMany(WarehousePenerimaanBarangFarmasiItems::class, 'pb_id', 'id');
    }

    public function gudang()
    {
        return $this->belongsTo(WarehouseMasterGudang::class, 'gudang_id', 'id');
    }

    public function supplier()
    {
        return $this->belongsTo(WarehouseSupplier::class, 'supplier_id', 'id');
    }

    public function po()
    {
        return $this->belongsTo(ProcurementPurchaseOrderPharmacy::class, 'po_id', 'id');
    }

    public function apSupplierDetails()
    {
        return $this->morphMany(ApSupplierDetail::class, 'penerimaanBarang');
    }

    /**
     * Check if penerimaan can be edited
     * Allows editing of tanggal, no_faktur, penerima, ED, batch even if there's movement
     * Only blocks editing if there are other restrictions
     */
    public function canBeEdited()
    {
        // Always allow editing for these fields: tanggal, no_faktur, penerima, ED, batch
        // even if there's movement
        return true;
    }

    /**
     * Check if penerimaan can be deleted
     * Only block deletion if there's actual movement/distribution
     */
    public function canBeDeleted()
    {
        // Check if items have been distributed/moved
        foreach ($this->items as $item) {
            // Check if item has been used in distributions
            $hasDistribution = \DB::table('warehouse_distribusi_barang_farmasi_items')
                ->where('pb_item_id', $item->id)
                ->exists();

            if ($hasDistribution) {
                return false;
            }
        }

        return true;
    }
}
