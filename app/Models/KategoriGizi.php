<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KategoriGizi extends Model
{
    use SoftDeletes;
    protected $table = "kategori_gizi";
    protected $guarded = ["id"];

    public function orders()
    {
        return $this->hasMany(OrderGizi::class, "kategori_id", "id");
    }

    public function diet_gizi()
    {
        return $this->hasMany(DietGizi::class, "kategori_id");
    }

    public function menus()
    {
        return $this->hasMany(MenuGizi::class, "kategori_id");
    }
}
