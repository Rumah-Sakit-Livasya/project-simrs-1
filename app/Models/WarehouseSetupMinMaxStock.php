<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class WarehouseSetupMinMaxStock extends Model implements AuditableContract
{
    use Auditable, SoftDeletes;
    protected $table = "warehouse_setup_min_max_stock";
    protected $guarded = ["id"];

    public function barang_farmasi()
    {
        return $this->belongsTo(WarehouseBarangFarmasi::class, "barang_f_id", "id");
    }

    public function barang_non_farmasi()
    {
        return $this->belongsTo(WarehouseBarangNonFarmasi::class, "barang_nf_id", "id");
    }

    public function gudang(){
        return $this->belongsTo(WarehouseMasterGudang::class,"gudang_id", "id");
    }
}
