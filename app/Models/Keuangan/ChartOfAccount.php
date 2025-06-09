<?php

namespace App\Models\Keuangan;

use App\Models\WarehouseKategoriBarang;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChartOfAccount extends Model
{
    use SoftDeletes;

    protected $table = 'chart_of_account';
    protected $guarded = ['id'];

    public function group()
    {
        return $this->belongsTo(GroupChartOfAccount::class, 'group_id');
    }

    public function children()
    {
        return $this->hasMany(ChartOfAccount::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(ChartOfAccount::class, 'parent_id');
    }

    public function coa_inventory_kategori_barang()
    {
        return $this->hasMany(WarehouseKategoriBarang::class, 'coa_inventory', 'id');
    }

    public function coa_sales_outpatient_kategori_barang()
    {
        return $this->hasMany(WarehouseKategoriBarang::class, 'coa_sales_outpatient', 'id');
    }

    public function coa_cogs_outpatient_kategori_barang()
    {
        return $this->hasMany(WarehouseKategoriBarang::class, 'coa_cogs_outpatient', 'id');
    }

    public function coa_cogs_inpatient_kategori_barang()
    {
        return $this->hasMany(WarehouseKategoriBarang::class, 'coa_cogs_inpatient', 'id');
    }

    public function coa_sales_inpatient_kategori_barang()
    {
        return $this->hasMany(WarehouseKategoriBarang::class, 'coa_sales_inpatient', 'id');
    }

    public function coa_adjustment_daily_kategori_barang()
    {
        return $this->hasMany(WarehouseKategoriBarang::class, 'coa_adjustment_daily', 'id');
    }

    public function coa_adjustment_so_kategori_barang()
    {
        return $this->hasMany(WarehouseKategoriBarang::class, 'coa_adjustment_so', 'id');
    }

}
