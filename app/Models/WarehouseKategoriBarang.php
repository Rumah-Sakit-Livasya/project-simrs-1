<?php

namespace App\Models;

use App\Models\Keuangan\ChartOfAccount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class WarehouseKategoriBarang extends Model implements AuditableContract
{
    use Auditable, SoftDeletes;

    protected $table = "warehouse_kategori_barang";
    protected $guarded = ["id"];

    public function barang_non_farmasi()
    {
        return $this->hasMany(WarehouseBarangNonFarmasi::class, "kategori_id", "id");
    }

    public function barang_farmasi()
    {
        return $this->hasMany(WarehouseBarangFarmasi::class, "kategori_id", "id");
    }
    
    // <td>{{ $kategori->coa_inventory->name }}</td>
    // <td>{{ $kategori->coa_sales_outpatient->name }}</td>
    // <td>{{ $kategori->coa_cogs_outpatient->name }}</td>
    // <td>{{ $kategori->coa_sales_inpatient->name }}</td>
    // <td>{{ $kategori->coa_cogs_inpatient->name }}</td>
    // <td>{{ $kategori->coa_adjustment_daily->name }}</td>
    // <td>{{ $kategori->coa_adjustment_so->name }}</td>

    public function _coa_inventory()
    {
        return $this->belongsTo(ChartOfAccount::class, 'coa_inventory', 'id');
    }

    public function _coa_sales_outpatient()
    {
        return $this->belongsTo(ChartOfAccount::class, 'coa_sales_outpatient', 'id');
    }

    public function _coa_cogs_outpatient()
    {
        return $this->belongsTo(ChartOfAccount::class, 'coa_cogs_outpatient', 'id');
    }

    public function _coa_cogs_inpatient()
    {
        return $this->belongsTo(ChartOfAccount::class, 'coa_cogs_inpatient', 'id');
    }

    public function _coa_sales_inpatient()
    {
        return $this->belongsTo(ChartOfAccount::class, 'coa_sales_inpatient', 'id');
    }

    public function _coa_adjustment_daily()
    {
        return $this->belongsTo(ChartOfAccount::class, 'coa_adjustment_daily', 'id');
    }

    public function _coa_adjustment_so()
    {
        return $this->belongsTo(ChartOfAccount::class, 'coa_adjustment_so', 'id');
    }
}
