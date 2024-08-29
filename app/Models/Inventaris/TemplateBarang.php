<?php

namespace App\Models\Inventaris;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TemplateBarang extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
    protected $table = 'template_barang';

    public function barang()
    {
        return $this->hasMany(Barang::class);
    }

    public function category()
    {
        return $this->belongsTo(CategoryBarang::class);
    }
}
