<?php

namespace App\Models\SIMRS\Persalinan;

use Illuminate\Database\Eloquent\Model;

class OrderPersalinanDetail extends Model
{
    protected $table = 'order_persalinan_detail';
    protected $guarded = [];

    /** Relasi ke Order Persalinan */
    public function order()
    {
        return $this->belongsTo(OrderPersalinan::class, 'order_persalinan_id');
    }

    /** Relasi ke Master Persalinan */
    public function persalinan()
    {
        return $this->belongsTo(Persalinan::class, 'persalinan_id');
    }
}
