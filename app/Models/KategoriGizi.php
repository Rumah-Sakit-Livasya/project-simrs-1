<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriGizi extends Model
{
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
