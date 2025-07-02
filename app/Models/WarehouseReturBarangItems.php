<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class WarehouseReturBarangItems extends Model implements AuditableContract
{
    use Auditable, SoftDeletes;

    protected $table = "warehouse_retur_barang_item";
    protected $guarded = ["id"];

    public function rb()
    {
        return $this->belongsTo(WarehouseReturBarang::class, 'rb_id');
    }

    public function stored()
    {
        if ($this->si_f_id) {
            return $this->belongsTo(StoredBarangFarmasi::class, 'si_f_id');
        } else { // si_nf_id
            return $this->belongsTo(StoredBarangNonFarmasi::class, 'si_nf_id');
        }
    }

    public function storedFarmasi()
    {
        return $this->belongsTo(StoredBarangFarmasi::class, 'si_f_id');
    }


    public function storedNonFarmasi()
    {
        return $this->belongsTo(StoredBarangNonFarmasi::class, 'si_nf_id');
    }



    public function getStoredAttribute()
    {
        // Accessor ini akan memeriksa relasi mana yang sudah dimuat (loaded)
        // dan mengembalikannya. Ini bekerja DENGAN BAIK setelah eager loading.
        if ($this->relationLoaded('storedFarmasi') && $this->storedFarmasi) {
            return $this->storedFarmasi;
        }

        if ($this->relationLoaded('storedNonFarmasi') && $this->storedNonFarmasi) {
            return $this->storedNonFarmasi;
        }

        // Fallback jika tidak di-eager load (meskipun sebaiknya selalu di-eager load)
        return $this->storedFarmasi()->first() ?? $this->storedNonFarmasi()->first();
    }

    public function getBarangInfoAttribute()
    {
        $storedItem = $this->stored; // Ini sekarang akan memanggil getStoredAttribute()
        if ($storedItem) {
            return $storedItem->barang;
        }
        return null;
    }
}
