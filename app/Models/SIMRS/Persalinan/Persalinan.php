<?php

namespace App\Models\SIMRS\Persalinan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Persalinan extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'persalinan';
    protected $fillable = ['tipe', 'kode', 'nama_persalinan', 'nama_billing'];

    public function orderPersalinan() {}
}
