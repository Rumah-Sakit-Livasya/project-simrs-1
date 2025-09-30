<?php

namespace App\Models\SIMRS\Obat;

use App\Models\Obat; // Asumsi
use App\Models\WarehouseBarangFarmasi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderObatDetail extends Model
{
    use HasFactory;

    protected $table = 'order_obat_details';
    protected $guarded = ['id'];

    public function orderObat()
    {
        return $this->belongsTo(OrderObat::class, 'order_obat_id');
    }

    public function obat()
    {
        return $this->belongsTo(WarehouseBarangFarmasi::class, 'warehouse_barang_farmasi_id');
    }
}
